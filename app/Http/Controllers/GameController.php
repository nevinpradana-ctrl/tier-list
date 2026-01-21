<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::query();

        // Search filter
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('game_name', 'like', "%{$keyword}%")
                  ->orWhere('slug', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $games = $query->paginate(10)->appends($request->all());
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        return view('admin.games.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:games',
            'icon_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('icon_url')) {
            $file = $request->file('icon_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('games', $file, $filename);
            $validated['icon_url'] = '/storage/' . $path;
        }

        Game::create($validated);
        return redirect('/admin/games')->with('success', 'Game berhasil ditambahkan!');
    }

    public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'game_name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:games,slug,' . $game->id,
            'icon_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('icon_url')) {
            // Delete old file if exists
            if ($game->icon_url && strpos($game->icon_url, '/storage/') === 0) {
                $oldPath = str_replace('/storage/', '', $game->icon_url);
                Storage::disk('public')->delete($oldPath);
            }
            $file = $request->file('icon_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('games', $file, $filename);
            $validated['icon_url'] = '/storage/' . $path;
        }

        $game->update($validated);
        return redirect('/admin/games')->with('success', 'Game berhasil diperbarui!');
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return redirect('/admin/games')->with('success', 'Game berhasil dihapus!');
    }
}

