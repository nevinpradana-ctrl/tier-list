<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('game')->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $games = Game::all();
        return view('admin.roles.create', compact('games'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'role_name' => 'required|string|max:50',
            'icon_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('icon_url')) {
            $file = $request->file('icon_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('roles', $file, $filename);
            $validated['icon_url'] = '/storage/' . $path;
        }

        Role::create($validated);
        return redirect('/admin/roles')->with('success', 'Role berhasil ditambahkan!');
    }

    public function edit(Role $role)
    {
        $games = Game::all();
        return view('admin.roles.edit', compact('role', 'games'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'role_name' => 'required|string|max:50',
            'icon_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('icon_url')) {
            // Delete old file if exists
            if ($role->icon_url && strpos($role->icon_url, '/storage/') === 0) {
                $oldPath = str_replace('/storage/', '', $role->icon_url);
                Storage::disk('public')->delete($oldPath);
            }
            $file = $request->file('icon_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('roles', $file, $filename);
            $validated['icon_url'] = '/storage/' . $path;
        }

        $role->update($validated);
        return redirect('/admin/roles')->with('success', 'Role berhasil diperbarui!');
    }

    public function destroy(Role $role)
    {
        // Delete file if exists
        if ($role->icon_url && strpos($role->icon_url, '/storage/') === 0) {
            $path = str_replace('/storage/', '', $role->icon_url);
            Storage::disk('public')->delete($path);
        }
        $role->delete();
        return redirect('/admin/roles')->with('success', 'Role berhasil dihapus!');
    }
}
