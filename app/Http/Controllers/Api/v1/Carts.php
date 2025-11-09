<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PhysicalCard;
use Illuminate\Http\Request;

class Carts extends Controller
{
    /**
     * Display the authenticated user's cart
     */
    public function index()
    {
        $user = auth()->user();
        $cart = $user->getOrCreateCart();

        $cart->load(['items.physicalCard.user']);

        return response()->json([
            'cart_id' => $cart->id,
            'total_items' => $cart->getTotalItems(),
            'total_price' => $cart->getTotalPrice(),
            'items' => $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'physical_card_id' => $item->physical_card_id,
                    'physical_card' => [
                        'id' => $item->physicalCard->id,
                        'title' => $item->physicalCard->title,
                        'set' => $item->physicalCard->set,
                        'language' => $item->physicalCard->language,
                        'edition' => $item->physicalCard->edition,
                        'condition' => $item->physicalCard->condition,
                        'price_per_unit' => $item->physicalCard->price_per_unit,
                        'currency' => $item->physicalCard->currency,
                        'image' => $item->physicalCard->image,
                        'available_quantity' => $item->physicalCard->quantity,
                        'seller' => [
                            'id' => $item->physicalCard->user->id,
                            'name' => $item->physicalCard->user->name,
                        ],
                    ],
                    'quantity' => $item->quantity,
                    'subtotal' => $item->getSubtotal(),
                ];
            }),
        ]);
    }

    /**
     * Add an item to the cart
     */
    public function addItem(Request $request)
    {
        $request->validate([
            'physical_card_id' => 'required|integer|exists:physical_cards,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $physicalCard = PhysicalCard::findOrFail($request->physical_card_id);

        // Prevent users from adding their own cards to cart
        if ($physicalCard->user_id === $user->id) {
            return response()->json([
                'message' => 'You cannot add your own cards to the cart',
            ], 422);
        }

        // Check if card is approved/published
        if (!$physicalCard->isApproved() && !$physicalCard->isPublished()) {
            return response()->json([
                'message' => 'This card is not available for purchase',
            ], 422);
        }

        // Validate quantity against available stock
        if ($request->quantity > $physicalCard->quantity) {
            return response()->json([
                'message' => 'Requested quantity exceeds available stock',
                'available_quantity' => $physicalCard->quantity,
            ], 422);
        }

        $cart = $user->getOrCreateCart();

        // Check if item already exists in cart
        $cartItem = $cart->items()->where('physical_card_id', $request->physical_card_id)->first();

        if ($cartItem) {
            // Update quantity, but validate total doesn't exceed stock
            $newQuantity = $cartItem->quantity + $request->quantity;

            if ($newQuantity > $physicalCard->quantity) {
                return response()->json([
                    'message' => 'Total quantity exceeds available stock',
                    'available_quantity' => $physicalCard->quantity,
                    'current_cart_quantity' => $cartItem->quantity,
                ], 422);
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            // Create new cart item
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'physical_card_id' => $request->physical_card_id,
                'quantity' => $request->quantity,
            ]);
        }

        $cartItem->load('physicalCard.user');

        return response()->json([
            'message' => 'Item added to cart successfully',
            'cart_item' => [
                'id' => $cartItem->id,
                'physical_card_id' => $cartItem->physical_card_id,
                'quantity' => $cartItem->quantity,
                'subtotal' => $cartItem->getSubtotal(),
            ],
        ], 201);
    }

    /**
     * Update cart item quantity
     */
    public function updateItem(Request $request, string $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $cart = $user->getOrCreateCart();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->findOrFail($cartItemId);

        $physicalCard = $cartItem->physicalCard;

        // Validate quantity against available stock
        if ($request->quantity > $physicalCard->quantity) {
            return response()->json([
                'message' => 'Requested quantity exceeds available stock',
                'available_quantity' => $physicalCard->quantity,
            ], 422);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'message' => 'Cart item updated successfully',
            'cart_item' => [
                'id' => $cartItem->id,
                'quantity' => $cartItem->quantity,
                'subtotal' => $cartItem->getSubtotal(),
            ],
        ]);
    }

    /**
     * Remove an item from the cart
     */
    public function removeItem(string $cartItemId)
    {
        $user = auth()->user();
        $cart = $user->getOrCreateCart();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->findOrFail($cartItemId);

        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart successfully',
        ]);
    }

    /**
     * Clear all items from the cart
     */
    public function clear()
    {
        $user = auth()->user();
        $cart = $user->getOrCreateCart();

        $cart->items()->delete();

        return response()->json([
            'message' => 'Cart cleared successfully',
        ]);
    }
}
