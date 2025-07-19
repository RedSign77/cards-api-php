<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardType;

class CardTypes extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cardType = CardType::where('game_id', request()->input('game_id'))
            ->orderBy(request()->input('order', 'name'))
            ->get();

        return response()->json($cardType);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'game_id' => 'required|integer|exists:games,id',
        ]);

        $cardType = new CardType();
        $cardType->game_id = $request->input('game_id');
        $cardType->name = $request->input('name');
        $cardType->typetext = $request->input('typetext');
        $cardType->description = $request->input('description');
        $cardType->save();

        return response()->json($cardType, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cardType = CardType::findOrFail($id);

        return response()->json($cardType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cardType = CardType::findOrFail($id);
        $cardType->game_id = $request->input('game_id', $cardType->game_id);
        $cardType->name = $request->input('name', $cardType->name);
        $cardType->typetext = $request->input('typetext', $cardType->typetext);
        $cardType->description = $request->input('description', $cardType->description);
        $cardType->save();

        return response()->json($cardType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cardType = CardType::findOrFail($id);
        $cardType->delete();

        return response()->json(['message' => 'Card type deleted successfully'], 204);
    }
}
