<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Figure;
use App\Models\Game;
use Illuminate\Http\Request;

class Figures extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Figure::whereHas('game', function ($q) use ($user) {
            $q->where('creator_id', $user->id);
        });

        if ($request->has('game_id')) {
            $query->where('game_id', $request->input('game_id'));
        }

        $figures = $query->orderBy($request->input('order', 'name'))->get();

        return response()->json($figures);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|integer|exists:games,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $game = Game::where('creator_id', $user->id)->findOrFail($request->input('game_id'));

        $figure = new Figure();
        $figure->game_id = $game->id;
        $figure->name = $request->input('name');
        $figure->description = $request->input('description');
        $figure->image = $request->input('image');
        $figure->save();

        return response()->json(['id' => $figure->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $figure = Figure::whereHas('game', function ($q) use ($user) {
            $q->where('creator_id', $user->id);
        })->findOrFail($id);

        return response()->json($figure);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'game_id' => 'sometimes|integer|exists:games,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $figure = Figure::whereHas('game', function ($q) use ($user) {
            $q->where('creator_id', $user->id);
        })->findOrFail($id);

        if ($request->has('game_id')) {
            $game = Game::where('creator_id', $user->id)->findOrFail($request->input('game_id'));
            $figure->game_id = $game->id;
        }

        $figure->name = $request->input('name', $figure->name);
        $figure->description = $request->input('description', $figure->description);
        $figure->image = $request->input('image', $figure->image);
        $figure->save();

        return response()->json($figure);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        $figure = Figure::whereHas('game', function ($q) use ($user) {
            $q->where('creator_id', $user->id);
        })->findOrFail($id);
        $figure->delete();

        return response()->json(['message' => 'Figure deleted successfully']);
    }
}
