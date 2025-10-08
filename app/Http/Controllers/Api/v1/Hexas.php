<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Hexa;
use App\Models\Game;
use Illuminate\Http\Request;

class Hexas extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Hexa::whereHas('game', function ($q) use ($user) {
            $q->where('creator_id', $user->id);
        });

        if ($request->has('game_id')) {
            $query->where('game_id', $request->input('game_id'));
        }

        $hexas = $query->orderBy($request->input('order', 'name'))->get();

        return response()->json($hexas);
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

        $hexa = new Hexa();
        $hexa->game_id = $game->id;
        $hexa->name = $request->input('name');
        $hexa->description = $request->input('description');
        $hexa->image = $request->input('image');
        $hexa->save();

        return response()->json(['id' => $hexa->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $hexa = Hexa::whereHas('game', function ($q) use ($user) {
            $q->where('creator_id', $user->id);
        })->findOrFail($id);

        return response()->json($hexa);
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
        $hexa = Hexa::whereHas('game', function ($q) use ($user) {
            $q->where('creator_id', $user->id);
        })->findOrFail($id);

        if ($request->has('game_id')) {
            $game = Game::where('creator_id', $user->id)->findOrFail($request->input('game_id'));
            $hexa->game_id = $game->id;
        }

        $hexa->name = $request->input('name', $hexa->name);
        $hexa->description = $request->input('description', $hexa->description);
        $hexa->image = $request->input('image', $hexa->image);
        $hexa->save();

        return response()->json($hexa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        $hexa = Hexa::whereHas('game', function ($q) use ($user) {
            $q->where('creator_id', $user->id);
        })->findOrFail($id);
        $hexa->delete();

        return response()->json(['message' => 'Hexa deleted successfully']);
    }
}
