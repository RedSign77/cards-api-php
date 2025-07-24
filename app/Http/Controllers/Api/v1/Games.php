<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class Games extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'order' => 'sometimes|string|in:name,created_at,updated_at',
        ]);
        $user = auth()->user();
        $games = Game::where('creator_id', $user['id'])->get();

        return response()->json($games);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'creator_id' => 'required|integer|exists:users,id',
        ]);

        $game = new Game();
        $game->creator_id = $request->input('creator_id', 1);
        $game->name = $request->input('name');
        $game->save();

        return response()->json($game->id, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $game = Game::findOrFail($id);

        return response()->json($game);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'creator_id' => 'required|integer|exists:users,id',
        ]);

        $game = Game::findOrFail($id);
        $game->name = $request->input('name', $game->name);
        $game->creator_id = $request->input('creator_id', $game->creator_id);
        $game->save();

        return response()->json($game);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $game = Game::findOrFail($id);
        $game->delete();

        return response()->json(['message' => 'Game deleted successfully']);
    }
}
