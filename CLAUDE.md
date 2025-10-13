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

### Start Development Server
```bash
composer dev
```
This runs a concurrent process that starts:
- Laravel development server (`php artisan serve`)
- Queue worker (`php artisan queue:listen --tries=1`)
- Log viewer (`php artisan pail --timeout=0`)
- Vite dev server (`npm run dev`)

### Alternative: Individual Services
```bash
php artisan serve          # Start server only
php artisan queue:listen   # Queue worker
php artisan pail           # Log viewer
npm run dev               # Frontend assets
```

### Testing
```bash
composer test              # Run all tests (clears config first)
php artisan test           # Run tests directly
php artisan test --filter=TestName  # Run specific test
```

### Database
```bash
php artisan migrate        # Run migrations
php artisan migrate:fresh --seed  # Fresh migration with seeders
```

### Code Quality
```bash
./vendor/bin/pint         # Format code (Laravel Pint)
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

**Custom Dashboard:** `app/Filament/Pages/Dashboard.php` with widgets:
- CustomAccountWidget (user profile)
- QuickLinksWidget (action shortcuts)
- SystemStatsWidget (database statistics)

**Import/Export:** Uses `pxlrbt/filament-excel` for Excel export on all resources. Importers in `app/Filament/Imports/`.

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

Two notification types:
- `NewUserRegistered` - Sent to `info@webtech-solutions.hu` on user creation
- `NewGameAdded` - Sent to `info@webtech-solutions.hu` on game creation

Both use model events in the `booted()` method.

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

### File Storage

Uses Laravel's public disk:
- Avatar images: `storage/app/public/avatars/`
- Card images: `storage/app/public/images/`
- Accessible via `/storage/` URL after running `php artisan storage:link`

### Development Environment

Uses Rewardenv Laravel project setup (see `.reward` directory). Can also use standard Laravel development with `php artisan serve`.

## Important Implementation Notes

- Always scope queries by user when creating new Filament resources
- Use `auth()->id()` for user filtering, `creator_id` for Game/Deck relationships
- JSON fields should use array casting in models
- When creating API endpoints, follow the existing v1 controller pattern
- All copyright headers use "Webtech-solutions 2025, All rights reserved."
- Card and Deck images should use FileUpload component with `imageEditor()` in Filament
- Notifications go to hardcoded admin email `signred@gmail.com`
