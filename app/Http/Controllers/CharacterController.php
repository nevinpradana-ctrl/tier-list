<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Element;
use App\Models\Game;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CharacterController extends Controller
{
    public function index(Request $request)
    {
        $query = Character::with(['game', 'element', 'role']);

        // Search filter by name
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'like', "%{$keyword}%");
        }

        // Filter by game
        if ($request->filled('game_id')) {
            $query->where('game_id', $request->game_id);
        }

        // Filter by rarity
        if ($request->filled('rarity')) {
            $query->where('rarity', $request->rarity);
        }

        // Filter by element
        if ($request->filled('element_id')) {
            $query->where('element_id', $request->element_id);
        }

        // Filter by role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        $characters = $query->paginate(15)->appends($request->all());
        
        // Get filter options
        $games = Game::all();
        $elements = Element::all();
        $roles = Role::all();

        return view('admin.characters.index', compact('characters', 'games', 'elements', 'roles'));
    }

    public function create()
    {
        $games = Game::all();
        return view('admin.characters.create', compact('games'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:100',
            'rarity' => 'required|integer|min:1|max:5',
            'element_id' => 'nullable|exists:elements,id',
            'role_id' => 'nullable|exists:roles,id',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('characters', $file, $filename);
            $validated['image_url'] = '/storage/' . $path;
        }

        Character::create($validated);
        return redirect('/admin/characters')->with('success', 'Karakter berhasil ditambahkan!');
    }

    public function edit(Character $character)
    {
        $games = Game::all();
        $elements = Element::where('game_id', $character->game_id)->get();
        $roles = Role::where('game_id', $character->game_id)->get();
        return view('admin.characters.edit', compact('character', 'games', 'elements', 'roles'));
    }

    public function update(Request $request, Character $character)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:100',
            'rarity' => 'required|integer|min:1|max:5',
            'element_id' => 'nullable|exists:elements,id',
            'role_id' => 'nullable|exists:roles,id',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('image_url')) {
            // Delete old file if exists
            if ($character->image_url && strpos($character->image_url, '/storage/') === 0) {
                $oldPath = str_replace('/storage/', '', $character->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $file = $request->file('image_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('characters', $file, $filename);
            $validated['image_url'] = '/storage/' . $path;
        }

        $character->update($validated);
        return redirect('/admin/characters')->with('success', 'Karakter berhasil diperbarui!');
    }

    public function destroy(Character $character)
    {
        $character->delete();
        return redirect('/admin/characters')->with('success', 'Karakter berhasil dihapus!');
    }

    public function getElementsByGame($gameId)
    {
        $elements = Element::where('game_id', $gameId)->get();
        return response()->json($elements);
    }

    public function getRolesByGame($gameId)
    {
        $roles = Role::where('game_id', $gameId)->get();
        return response()->json($roles);
    }
}

