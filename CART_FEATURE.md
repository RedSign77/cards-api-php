# Cart Feature Documentation

## Overview

The cart feature allows logged-in users to add marketplace items (PhysicalCards) to their shopping cart. Users can select quantities based on the available stock for each marketplace listing.

## Database Schema

### Tables Created

#### `carts`
- `id` - Primary key
- `user_id` - Foreign key to users table (CASCADE on delete)
- `created_at` - Timestamp
- `updated_at` - Timestamp

#### `cart_items`
- `id` - Primary key
- `cart_id` - Foreign key to carts table (CASCADE on delete)
- `physical_card_id` - Foreign key to physical_cards table (CASCADE on delete)
- `quantity` - Integer (default: 1)
- `created_at` - Timestamp
- `updated_at` - Timestamp
- UNIQUE constraint on (`cart_id`, `physical_card_id`) - Prevents duplicate items

## Models

### Cart Model
**Location:** `app/Models/Cart.php`

**Relationships:**
- `user()` - BelongsTo User
- `items()` - HasMany CartItem

**Methods:**
- `getTotalPrice()` - Returns the total price of all items in cart
- `getTotalItems()` - Returns the total count of all items in cart

### CartItem Model
**Location:** `app/Models/CartItem.php`

**Relationships:**
- `cart()` - BelongsTo Cart
- `physicalCard()` - BelongsTo PhysicalCard

**Methods:**
- `getSubtotal()` - Returns quantity × price_per_unit

### User Model Updates
**Location:** `app/Models/User.php`

**New Relationships:**
- `cart()` - HasOne Cart

**New Methods:**
- `getOrCreateCart()` - Gets or creates user's cart

### PhysicalCard Model Updates
**Location:** `app/Models/PhysicalCard.php`

**New Relationships:**
- `cartItems()` - HasMany CartItem

## API Endpoints

All endpoints require authentication via `auth:sanctum` middleware.

**Base Path:** `/api/v1/`

### Get Cart
```
GET /api/v1/cart
```

**Response:**
```json
{
  "cart_id": 1,
  "total_items": 5,
  "total_price": 125.50,
  "items": [
    {
      "id": 1,
      "physical_card_id": 10,
      "physical_card": {
        "id": 10,
        "title": "Black Lotus",
        "set": "Alpha",
        "language": "English",
        "edition": "1st Edition",
        "condition": "Near Mint",
        "price_per_unit": 25000.00,
        "currency": "USD",
        "image": "images/black-lotus.jpg",
        "available_quantity": 1,
        "seller": {
          "id": 5,
          "name": "John Doe"
        }
      },
      "quantity": 1,
      "subtotal": 25000.00
    }
  ]
}
```

### Add Item to Cart
```
POST /api/v1/cart/items
```

**Request Body:**
```json
{
  "physical_card_id": 10,
  "quantity": 2
}
```

**Validation:**
- `physical_card_id` - Required, integer, must exist in physical_cards table
- `quantity` - Required, integer, minimum 1

**Business Rules:**
- Users cannot add their own cards to cart
- Card must be in `approved` or `published` status
- Requested quantity cannot exceed available stock
- If item already exists in cart, quantities are added together

**Success Response (201):**
```json
{
  "message": "Item added to cart successfully",
  "cart_item": {
    "id": 1,
    "physical_card_id": 10,
    "quantity": 2,
    "subtotal": 50.00
  }
}
```

**Error Responses (422):**
```json
{
  "message": "You cannot add your own cards to the cart"
}
```

```json
{
  "message": "This card is not available for purchase"
}
```

```json
{
  "message": "Requested quantity exceeds available stock",
  "available_quantity": 5
}
```

```json
{
  "message": "Total quantity exceeds available stock",
  "available_quantity": 10,
  "current_cart_quantity": 8
}
```

### Update Cart Item Quantity
```
PUT /api/v1/cart/items/{cartItemId}
```

**Request Body:**
```json
{
  "quantity": 3
}
```

**Validation:**
- `quantity` - Required, integer, minimum 1
- Quantity cannot exceed available stock

**Success Response (200):**
```json
{
  "message": "Cart item updated successfully",
  "cart_item": {
    "id": 1,
    "quantity": 3,
    "subtotal": 75.00
  }
}
```

### Remove Item from Cart
```
DELETE /api/v1/cart/items/{cartItemId}
```

**Success Response (200):**
```json
{
  "message": "Item removed from cart successfully"
}
```

### Clear Cart
```
DELETE /api/v1/cart
```

**Success Response (200):**
```json
{
  "message": "Cart cleared successfully"
}
```

## Filament Admin Panel

### Cart Resource
**Location:** `app/Filament/Resources/CartResource.php`

**Navigation:**
- Icon: Shopping cart
- Label: "My Cart"
- Group: "Shopping"
- Sort Order: 1

**Features:**
- View-only resource (cannot create, edit, or delete via admin panel)
- Users can only see their own cart
- Displays total items count and total price
- Shows detailed view of all cart items with images, quantities, and prices

### Marketplace Integration
**Location:** `app/Filament/Pages/Marketplace.php`

**New Method:**
- `addToCart(int $cardId, int $quantity = 1)` - Adds item to cart with validation

**Features:**
- Add to cart button available on marketplace cards
- Quantity selector (limited by available stock)
- Real-time validation against stock levels
- Success/error notifications via Filament notifications
- Prevents adding own cards to cart
- Dispatches `cart-updated` event for frontend refresh

## Usage Examples

### API Usage

#### Adding item to cart
```bash
curl -X POST https://your-domain.com/api/v1/cart/items \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "physical_card_id": 10,
    "quantity": 2
  }'
```

#### Viewing cart
```bash
curl -X GET https://your-domain.com/api/v1/cart \
  -H "Authorization: Bearer YOUR_TOKEN"
```

#### Updating quantity
```bash
curl -X PUT https://your-domain.com/api/v1/cart/items/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "quantity": 5
  }'
```

#### Removing item
```bash
curl -X DELETE https://your-domain.com/api/v1/cart/items/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Filament/Livewire Usage

```php
// In a Livewire component or Filament page
public function addItemToCart($cardId, $quantity)
{
    $this->addToCart($cardId, $quantity);
}
```

## Migrations

To apply the cart feature to your database:

```bash
php artisan migrate
```

This will create the `carts` and `cart_items` tables.

## Security Features

1. **User Scoping:** Users can only access their own cart
2. **Stock Validation:** Prevents adding more items than available
3. **Ownership Check:** Users cannot add their own listings to cart
4. **Status Validation:** Only approved/published cards can be added
5. **Database Constraints:** UNIQUE constraint prevents duplicate entries
6. **Cascade Deletion:** Cart items are deleted when cart or physical card is deleted

## Future Enhancements

Potential features for future development:

1. **Checkout Process:** Payment integration
2. **Cart Expiry:** Auto-clear old cart items
3. **Save for Later:** Move items to wishlist
4. **Cart Sharing:** Share cart with friends
5. **Price Tracking:** Notify when prices change
6. **Stock Alerts:** Notify when out-of-stock items become available
7. **Multi-Currency Support:** Handle different currencies in cart
8. **Bulk Operations:** Add/remove multiple items at once
9. **Cart Analytics:** Track cart abandonment, conversion rates
10. **Saved Carts:** Multiple carts per user (e.g., "Birthday List", "Collection Completion")

## Files Created/Modified

### New Files
- `database/migrations/2025_11_09_000000_create_carts_table.php`
- `database/migrations/2025_11_09_000001_create_cart_items_table.php`
- `app/Models/Cart.php`
- `app/Models/CartItem.php`
- `app/Http/Controllers/Api/v1/Carts.php`
- `app/Filament/Resources/CartResource.php`
- `app/Filament/Resources/CartResource/Pages/ListCarts.php`
- `app/Filament/Resources/CartResource/Pages/ViewCart.php`

### Modified Files
- `app/Models/User.php` - Added cart relationship and getOrCreateCart method
- `app/Models/PhysicalCard.php` - Added cartItems relationship
- `app/Filament/Pages/Marketplace.php` - Added addToCart method
- `routes/api.php` - Added cart API routes
