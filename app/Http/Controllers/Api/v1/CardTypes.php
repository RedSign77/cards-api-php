<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CardType;
use Illuminate\Http\Request;

class CardTypes extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $query = CardType::where('user_id', $user->id);

        if (request()->has('game_id')) {
            $query->where('game_id', request()->input('game_id'));
        }

        $cardTypes = $query->orderBy(request()->input('order', 'name'))->get();

        return response()->json($cardTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'game_id' => 'required|integer|exists:games,id',
            'typetext' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $user = auth()->user();
        $cardType = new CardType();
        $cardType->user_id = $user->id;
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
        $user = auth()->user();
        $cardType = CardType::where('user_id', $user->id)->findOrFail($id);

        return response()->json($cardType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'game_id' => 'sometimes|integer|exists:games,id',
            'typetext' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $user = auth()->user();
        $cardType = CardType::where('user_id', $user->id)->findOrFail($id);
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
        $user = auth()->user();
        $cardType = CardType::where('user_id', $user->id)->findOrFail($id);
        $cardType->delete();

        return response()->json(['message' => 'Card type deleted successfully'], 200);
    }
}
