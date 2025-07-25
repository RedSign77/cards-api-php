<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use Illuminate\Http\Request;

class Decks extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $decks = Deck::where('creator_id', $user['id'])
            ->orderBy($request->input('order', 'deck_name'))
            ->get()
            ->each(function ($deck) {
                $deck->deck_data = json_decode($deck->card_data, true);
            });

        return response()->json($decks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|integer|exists:games,id',
            'deck_name' => 'required|string|max:255',
            'deck_description' => 'nullable|string|max:1000',
            'deck_data' => 'nullable|array',
        ]);
        $user = auth()->user();
        $deck = new Deck();
        $deck->creator_id = $user['id'];
        $deck->game_id = $request->input('game_id');
        $deck->deck_name = $request->input('deck_name');
        $deck->deck_description = $request->input('deck_description');
        $deck->deck_data = json_encode($request->input('deck_data'));
        $deck->save();

        return response()->json($deck->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $deck = Deck::findOrFail($id);
        $deck->deck_data = json_decode($deck->deck_data, true);

        return response()->json($deck);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'game_id' => 'required|integer|exists:games,id',
            'deck_name' => 'required|string|max:255',
            'deck_description' => 'nullable|string|max:1000',
            'deck_data' => 'nullable|array',
        ]);
        $user = auth()->user();
        $deck = Deck::findOrFail($id);
        $deck->creator_id = $user['id'];
        $deck->game_id = $request->input('game_id', $deck->game_id);
        $deck->deck_name = $request->input('deck_name', $deck->deck_name);
        $deck->deck_description = $request->input('deck_description', $deck->deck_description);
        $deck->deck_data = json_encode($request->input('deck_data', json_decode($deck->deck_data, true)));
        $deck->save();

        return response()->json($deck);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deck = Deck::findOrFail($id);
        $deck->delete();

        return response()->json(['message' => 'Deck deleted successfully']);
    }
}
