<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class Cards extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cards = Card::all()
            ->map(function ($card) {
                $card->card_data = json_decode($card->card_data, true);
                return $card;
            });

        return response()->json($cards);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|integer',
            'type_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
            'card_text' => 'nullable|string',
            'card_data' => 'nullable|array',
        ]);

        $card = new Card();
        $card->game_id = $request->input('game_id');
        $card->type_id = $request->input('type_id');
        $card->name = $request->input('name');
        $card->image = $request->input('image');
        $card->card_text = $request->input('card_text');
        $card->card_data = json_encode($request->input('card_data'));
        $card->save();

        return response()->json($card->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $card = Card::findOrFail($id);
        $card->card_data = json_decode($card->card_data, true);

        return response()->json($card);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $card = Card::findOrFail($id);
        $card->game_id = $request->input('game_id', $card->game_id);
        $card->type_id = $request->input('type_id', $card->type_id);
        $card->name = $request->input('name', $card->name);
        $card->image = $request->input('image', $card->image);
        $card->card_text = $request->input('card_text', $card->card_text);
        $card->card_data = json_encode($request->input('card_data', json_decode($card->card_data, true)));
        $card->save();

        return response()->json($card);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $card = Card::findOrFail($id);
        $card->delete();

        return response()->json(['message' => 'Card deleted successfully']);
    }
}
