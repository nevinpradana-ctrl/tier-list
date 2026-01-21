<?php

namespace App\Http\Controllers;

use App\Models\TierCategory;
use App\Models\TierData;
use App\Models\Game;
use App\Models\Character;
use Illuminate\Http\Request;

class TierListController extends Controller
{
    public function index(Request $request)
    {
        $query = TierCategory::with(['game']);

        // Search filter by category name or game name
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('category_name', 'like', "%{$keyword}%")
                  ->orWhereHas('game', function($subq) use ($keyword) {
                      $subq->where('game_name', 'like', "%{$keyword}%");
                  });
            });
        }

        // Filter by game
        if ($request->filled('game_id')) {
            $query->where('game_id', $request->game_id);
        }

        $tierCategories = $query->paginate(10)->appends($request->all());
        
        // Get all games for filter dropdown
        $games = Game::all();

        return view('admin.tier-lists.index', compact('tierCategories', 'games'));
    }

    public function create()
    {
        $games = Game::all();
        return view('admin.tier-lists.create', compact('games'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'category_name' => 'required|string|max:100',
        ]);

        TierCategory::create($validated);
        return redirect('/admin/tier-lists')->with('success', 'Kategori tier list berhasil ditambahkan!');
    }

    public function edit(TierCategory $tierCategory)
    {
        $games = Game::all();
        return view('admin.tier-lists.edit', compact('tierCategory', 'games'));
    }

    public function update(Request $request, TierCategory $tierCategory)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'category_name' => 'required|string|max:100',
        ]);

        $tierCategory->update($validated);
        return redirect('/admin/tier-lists')->with('success', 'Kategori tier list berhasil diperbarui!');
    }

    public function destroy(TierCategory $tierCategory)
    {
        $tierCategory->delete();
        return redirect('/admin/tier-lists')->with('success', 'Kategori tier list berhasil dihapus!');
    }

    public function manageTiers(TierCategory $tierCategory)
    {
        $characters = Character::where('game_id', $tierCategory->game_id)->get();
        $tierData = TierData::where('tier_category_id', $tierCategory->id)
            ->with('character')
            ->get()
            ->groupBy('rank');

        return view('admin.tier-lists.manage', compact('tierCategory', 'characters', 'tierData'));
    }

    public function assignTier(Request $request, TierCategory $tierCategory)
    {
        $validated = $request->validate([
            'character_id' => 'required|exists:characters,id',
            'rank' => 'required|in:SS,S,A,B,C,D',
            'note' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['tier_category_id'] = $tierCategory->id;

        // Check if character already exists in this tier list
        TierData::updateOrCreate(
            [
                'tier_category_id' => $tierCategory->id,
                'character_id' => $validated['character_id'],
            ],
            $validated
        );

        return redirect()->back()->with('success', 'Tier karakter berhasil diperbarui!');
    }

    public function removeTier(TierCategory $tierCategory, TierData $tierData)
    {
        $tierData->delete();
        return redirect()->back()->with('success', 'Karakter berhasil dihapus dari tier list!');
    }

    // Frontend view
    public function viewTierList($gameSlug, $categoryId = null)
    {
        $game = Game::where('slug', $gameSlug)->firstOrFail();
        $categories = TierCategory::where('game_id', $game->id)->get();

        if ($categoryId) {
            $category = TierCategory::findOrFail($categoryId);
        } else {
            $category = $categories->first();
        }

        $tierData = TierData::where('tier_category_id', $category->id)
            ->with('character')
            ->orderByRaw("CASE rank WHEN 'SS' THEN 1 WHEN 'S' THEN 2 WHEN 'A' THEN 3 WHEN 'B' THEN 4 WHEN 'C' THEN 5 WHEN 'D' THEN 6 ELSE 7 END")
            ->orderBy('sort_order')
            ->get()
            ->groupBy('rank');

        return view('frontend.tier-list', compact('game', 'category', 'categories', 'tierData'));
    }
}

