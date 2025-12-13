# Cards Forge API

![GitHub](https://img.shields.io/github/license/RedSign77/cards-api-php)
![GitHub issues](https://img.shields.io/github/issues/RedSign77/cards-api-php)
![GitHub stars](https://img.shields.io/github/stars/RedSign77/cards-api-php)
![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)
![Laravel](https://img.shields.io/badge/Laravel-12-red)

A comprehensive REST API for managing trading cards, decks, games, and creators. Built with Laravel 12 and Filament 3.0 admin panel, featuring a complete marketplace system with multi-currency support.

## Features

- **Game Management** - Create and manage trading card games
- **Card System** - Flexible card creation with dynamic JSON fields
- **Deck Building** - Build and organize decks within games
- **User Authentication** - Sanctum-based API authentication
- **Admin Panel** - Powerful Filament 3.0 admin interface
- **Marketplace** - Buy and sell physical cards with multi-currency support
- **Activity Logging** - Track user actions and system events
- **Email Notifications** - Automated notifications for key events
- **Excel Import/Export** - Bulk data operations
- **Image Management** - Avatar and card image uploads

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Admin Panel:** Filament 3.0
- **Authentication:** Laravel Sanctum
- **Frontend:** Vite + Tailwind CSS 4.0
- **Database:** SQLite (default) / MySQL
- **Queue:** Laravel Queue with database driver
- **File Storage:** Laravel public disk

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js & npm
- SQLite or MySQL

## API Documentation

### Authentication

**Register User**
```http
POST /api/user/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

**Login**
```http
POST /api/user/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password"
}
```

### API Endpoints (v1)

All v1 endpoints require authentication via Sanctum token:
```http
Authorization: Bearer {token}
```

- `GET|POST /api/v1/games` - Games management
- `GET|POST /api/v1/cardtypes` - Card types management
- `GET|POST /api/v1/cards` - Cards management
- `GET|POST /api/v1/decks` - Decks management

## Admin Panel

Access the admin panel at `/admin` after logging in with supervisor credentials.

**Features:**
- Resource management (Games, Cards, Decks, Users)
- Shopping cart and marketplace
- Order management (buyer/seller views)
- Activity logging
- Excel import/export
- System statistics dashboard

## Marketplace

The platform includes a complete marketplace system:
- Multi-currency support with real-time conversion
- Shopping cart with 30-minute reservation system
- Order lifecycle management
- Buyer/seller confirmation workflow
- Shipping address management

## Configuration

### Email Notifications
Configure email settings in `.env`:
```env
MAIL_ENABLED=true
MAIL_ADMIN_ADDRESS=admin@example.com
```

### Scheduled Tasks
Add to your crontab:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## License

All rights reserved. Copyright Webtech-solutions 2025.

## Support

For issues and feature requests, please use the [GitHub issue tracker](https://github.com/RedSign77/cards-api-php/issues).
