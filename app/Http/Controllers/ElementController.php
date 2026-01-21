<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ElementController extends Controller
{
    public function index()
    {
        $elements = Element::with('game')->paginate(15);
        return view('admin.elements.index', compact('elements'));
    }

    public function create()
    {
        $games = Game::all();
        return view('admin.elements.create', compact('games'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'element_name' => 'required|string|max:50',
            'icon_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('icon_url')) {
            $file = $request->file('icon_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('elements', $file, $filename);
            $validated['icon_url'] = '/storage/' . $path;
        }

        Element::create($validated);
        return redirect('/admin/elements')->with('success', 'Element berhasil ditambahkan!');
    }

    public function edit(Element $element)
    {
        $games = Game::all();
        return view('admin.elements.edit', compact('element', 'games'));
    }

    public function update(Request $request, Element $element)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'element_name' => 'required|string|max:50',
            'icon_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('icon_url')) {
            // Delete old file if exists
            if ($element->icon_url && strpos($element->icon_url, '/storage/') === 0) {
                $oldPath = str_replace('/storage/', '', $element->icon_url);
                Storage::disk('public')->delete($oldPath);
            }
            $file = $request->file('icon_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('elements', $file, $filename);
            $validated['icon_url'] = '/storage/' . $path;
        }

        $element->update($validated);
        return redirect('/admin/elements')->with('success', 'Element berhasil diperbarui!');
    }

    public function destroy(Element $element)
    {
        // Delete file if exists
        if ($element->icon_url && strpos($element->icon_url, '/storage/') === 0) {
            $path = str_replace('/storage/', '', $element->icon_url);
            Storage::disk('public')->delete($path);
        }
        $element->delete();
        return redirect('/admin/elements')->with('success', 'Element berhasil dihapus!');
    }
}
