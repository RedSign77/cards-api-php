# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Cards Forge API - A Laravel 12 application providing a REST API for managing trading cards, decks, games, and creators. Includes a Filament admin panel for content management.

**Key Technologies:**
- Laravel 12 (PHP 8.2+)
- Filament 3.0 (admin panel)
- Laravel Sanctum (API authentication)
- Vite + Tailwind CSS 4.0
- SQLite (default) / MySQL support

## Development Commands
This runs a concurrent process that starts:
- Laravel development server (`php artisan serve`)
- Queue worker (`php artisan queue:listen --tries=1`)
- Log viewer (`php artisan pail --timeout=0`)
- Vite dev server (`npm run dev`)

### Database
```bash
php artisan migrate        # Run migrations
php artisan migrate:fresh --seed  # Fresh migration with seeders
```

### Asset Building
```bash
npm run build             # Production build
npm run dev               # Development with hot reload
```

## Architecture Overview

### Core Domain Models

The application follows a hierarchical structure:

**Game** (top level)
- Created by a User (creator)
- Has many CardTypes
- Has many Decks
- Sends email notification on creation to admin

**CardType**
- Belongs to a Game and User
- Has many Cards
- Defines card categories within a game

**Deck**
- Belongs to a Game and User (creator)
- Contains `deck_data` JSON field for flexible data storage

**Card**
- Belongs to a Game, CardType, and User
- Contains `card_data` JSON field storing dynamic fields as array of objects with `fieldname` and `fieldvalue`
- Has optional image upload stored in `storage/app/public/images`

**User**
- Authenticated via Sanctum for API
- Has `supervisor` boolean flag for elevated privileges
- Supports avatar uploads stored in `storage/app/public/avatars`
- Sends email notification on registration to admin
- Activity logging for login/logout events
- Has shipping address fields for marketplace orders
- Has preferred currency setting with conversion support

**Order** (Marketplace)
- Belongs to a User (buyer) and User (seller)
- Has many OrderItems
- Contains complete shipping address information
- Tracks payment method, payment status, and order status
- Supports buyer/seller confirmation workflow for delivery
- Generates unique order numbers via `generateOrderNumber()`
- Order statuses: pending, packing, paid, shipped, delivered, completed, cancelled
- Payment statuses: pending, paid, failed, refunded

**OrderItem**
- Belongs to an Order and PhysicalCard
- Stores quantity, price per unit, and subtotal at time of purchase
- Price stored in buyer's currency (converted during checkout)

### Data Isolation

**Important:** Resources are user-scoped. Filament resources use `getEloquentQuery()` to filter by `auth()->id()` or `creator_id`, ensuring users only see their own content.

### API Structure

**Authentication Routes** (`/api`):
- `POST /user/register` - User registration
- `POST /user/login` - User login (returns Sanctum token)
- `POST /supervisor/login` - Supervisor login
- `GET /user/profile` - Get authenticated user profile (requires auth)
- `GET /user/logout` - Logout user

**Resource Routes** (`/api/v1`, requires `auth:sanctum` middleware):
- `cards` - Full CRUD for cards
- `cardtypes` - Full CRUD for card types
- `decks` - Full CRUD for decks
- `games` - Full CRUD for games

All v1 API controllers are in `app/Http/Controllers/Api/v1/`.

### Filament Admin Panel

Located at `/admin` route. Key features:

**Resources:** Card, CardType, Deck, Game, User, UserActivityLog, CompletedJob, FailedJob

**Custom Pages:**
- `Dashboard.php` - Main dashboard with CustomAccountWidget, QuickLinksWidget, and SystemStatsWidget
- `MyCart.php` - Shopping cart with currency conversion and checkout
- `MyOrders.php` - Buyer's order history with delivery confirmation
- `MySales.php` - Seller's order management with status updates
- Cart items have 30-minute reservation system to prevent overselling

**Profile Management:** Uses `joaopaulolndev/filament-edit-profile` plugin with avatar upload support.

### JSON Field Patterns

When working with `card_data` or `deck_data`:
- Stored as JSON in database
- Cast to `array` in models via `$casts`
- API controllers manually encode/decode when needed
- Filament uses Repeater component for editing

Example `card_data` structure:
```json
[
  {"fieldname": "Strength", "fieldvalue": "5"},
  {"fieldname": "Health", "fieldvalue": "10"}
]
```

### Notifications

Email notifications can be disabled via `MAIL_ENABLED=false` in `.env`.

Notification types:
- `NewUserRegistered` - Sent to admin email on user creation (model event)
- `UserEmailConfirmed` - Sent to supervisors when user verifies email (model event)
- `EmailVerifiedSuccess` - Sent to user after successfully verifying email (verification route)
- `NewGameAdded` - Sent to admin email on game creation (model event)
- `UserApproved` - Sent to user when supervisor approves their account (Filament action)
- `CardApproved` - Sent to user when supervisor approves their card listing (Filament action)
- `CardRejected` - Sent to user when supervisor rejects their card listing (Filament action)
- `PendingReviewEscalation` - Sent to all supervisors when a card is pending review for 48+ hours (scheduled task)

All notifications check `config('mail.enabled')` before sending and implement `ShouldQueue` for background processing.

### Activity and Job Logging

**User Activity Logging:**
`UserActivityLog` model tracks user login/logout events via event listeners:
- `LogUserLogin` - Listens for `Illuminate\Auth\Events\Login`
- `LogUserLogout` - Listens for `Illuminate\Auth\Events\Logout`

**Completed Jobs Logging:**
`CompletedJob` model tracks successfully processed queue jobs:
- `LogCompletedJob` - Listens for `Illuminate\Queue\Events\JobProcessed`
- Stores job payload, execution details, and timing
- Viewable in Filament Admin Panel under Administration > Completed Jobs
- Supervisor-only access

All event listeners registered in `app/Providers/EventServiceProvider.php`.

### Scheduled Tasks

Scheduled tasks are defined in `routes/console.php`:
- `cards:check-pending-reviews` - Runs daily to check for cards in `under_review` status for 48+ hours and sends escalation notifications to supervisors
- `logs:cleanup` - Runs daily at 2 AM to clean up old logs (user activity, supervisor activity, completed jobs older than 20 days)
- `queue:work` - Runs every minute to process queued jobs

**Physical Card Review Escalation:**
Cards in `under_review` status waiting 48+ hours trigger automatic escalation notifications to all supervisors with:
- Card title and seller information
- Hours waiting
- Link to review dashboard
- Evaluation flags

### Marketplace & Currency System

**Currency Conversion:**
- Multi-currency support for physical card sales
- Users set preferred currency in profile
- Real-time price conversion during checkout
- Conversion rates stored in `currencies` table with `rate_to_usd` field
- Use `Currency::convertTo($amount, $targetCurrency)` for conversions

**Shopping Cart Workflow:**
1. Items added to cart with 30-minute reservation
2. Reservations automatically extended when viewing cart
3. Checkout groups items by seller (one order per seller)
4. Shipping costs per seller, converted to buyer's currency
5. Physical card inventory decremented on successful checkout

**Order Lifecycle:**
- Seller: pending → packing → paid → shipped → delivered → completed
- Buyer: confirms receipt when shipped/delivered
- Both confirmations required to mark as completed
- Payment coordinated outside platform (PayPal, check, bank transfer)

### File Storage

Uses Laravel's public disk:
- Avatar images: `storage/app/public/avatars/`
- Card images: `storage/app/public/images/`
- Accessible via `/storage/` URL after running `php artisan storage:link`

### Development Environment

Uses Rewardenv Laravel project setup (see `.reward` directory). Can also use standard Laravel development with `php artisan serve`.

## Important Implementation Notes

- Always make a feature documentation in dps/features directory for developers (maximum of 250 lines)
- Always scope queries by user when creating new Filament resources
- Use `auth()->id()` for user filtering, `creator_id` for Game/Deck relationships
- JSON fields should use array casting in models
- When creating API endpoints, follow the existing v1 controller pattern
- All copyright headers use "Webtech-solutions 2025, All rights reserved."
- Card and Deck images should use FileUpload component with `imageEditor()` in Filament
- Admin notification email configured via `MAIL_ADMIN_ADDRESS` environment variable
- Bash commands running inside a docker container with "docker exec cards-api-php-php-fpm-1 command"
- Marketplace transactions use DB transactions to ensure data integrity
- Orders are scoped: MyOrders filters by `buyer_id`, MySales filters by `seller_id`
- Currency conversions should always check if target currency differs from source before converting
- Cart reservation system prevents overselling - always validate available quantity