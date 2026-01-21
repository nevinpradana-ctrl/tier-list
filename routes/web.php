<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\ElementController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TierListController;

// Frontend Routes
Route::get('/', function () {
    return view('frontend.index');
})->name('home');

Route::get('/games', [TierListController::class, 'viewTierList'])->name('games.index');
Route::get('/game/{gameSlug}/{categoryId?}', [TierListController::class, 'viewTierList'])->name('game.tier-list');

// Admin Auth Routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin Protected Routes
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');

    // Games Management
    Route::get('/admin/games', [GameController::class, 'index'])->name('admin.games.index')->middleware('role:admin,staff_viewer,staff_editor');
    Route::get('/admin/games/create', [GameController::class, 'create'])->name('admin.games.create')->middleware('role:admin,staff_editor');
    Route::post('/admin/games', [GameController::class, 'store'])->name('admin.games.store')->middleware('role:admin,staff_editor');
    Route::get('/admin/games/{game}/edit', [GameController::class, 'edit'])->name('admin.games.edit')->middleware('role:admin,staff_editor');
    Route::put('/admin/games/{game}', [GameController::class, 'update'])->name('admin.games.update')->middleware('role:admin,staff_editor');
    Route::delete('/admin/games/{game}', [GameController::class, 'destroy'])->name('admin.games.destroy')->middleware('role:admin');

    // Characters Management
    Route::get('/admin/characters', [CharacterController::class, 'index'])->name('admin.characters.index')->middleware('role:admin,staff_viewer,staff_editor');
    Route::get('/admin/characters/create', [CharacterController::class, 'create'])->name('admin.characters.create')->middleware('role:admin,staff_editor');
    Route::post('/admin/characters', [CharacterController::class, 'store'])->name('admin.characters.store')->middleware('role:admin,staff_editor');
    Route::get('/admin/characters/{character}/edit', [CharacterController::class, 'edit'])->name('admin.characters.edit')->middleware('role:admin,staff_editor');
    Route::put('/admin/characters/{character}', [CharacterController::class, 'update'])->name('admin.characters.update')->middleware('role:admin,staff_editor');
    Route::delete('/admin/characters/{character}', [CharacterController::class, 'destroy'])->name('admin.characters.destroy')->middleware('role:admin');

    // Elements Management
    Route::get('/admin/elements', [ElementController::class, 'index'])->name('admin.elements.index')->middleware('role:admin,staff_viewer,staff_editor');
    Route::get('/admin/elements/create', [ElementController::class, 'create'])->name('admin.elements.create')->middleware('role:admin,staff_editor');
    Route::post('/admin/elements', [ElementController::class, 'store'])->name('admin.elements.store')->middleware('role:admin,staff_editor');
    Route::get('/admin/elements/{element}/edit', [ElementController::class, 'edit'])->name('admin.elements.edit')->middleware('role:admin,staff_editor');
    Route::put('/admin/elements/{element}', [ElementController::class, 'update'])->name('admin.elements.update')->middleware('role:admin,staff_editor');
    Route::delete('/admin/elements/{element}', [ElementController::class, 'destroy'])->name('admin.elements.destroy')->middleware('role:admin');

    // Roles Management
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index')->middleware('role:admin,staff_viewer,staff_editor');
    Route::get('/admin/roles/create', [RoleController::class, 'create'])->name('admin.roles.create')->middleware('role:admin,staff_editor');
    Route::post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store')->middleware('role:admin,staff_editor');
    Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('role:admin,staff_editor');
    Route::put('/admin/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update')->middleware('role:admin,staff_editor');
    Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('role:admin');

    // AJAX Routes for Chain Select
    Route::get('/api/elements/{gameId}', [CharacterController::class, 'getElementsByGame']);
    Route::get('/api/roles/{gameId}', [CharacterController::class, 'getRolesByGame']);

    // Tier Lists Management
    Route::get('/admin/tier-lists', [TierListController::class, 'index'])->name('admin.tier-lists.index');
    Route::get('/admin/tier-lists/create', [TierListController::class, 'create'])->name('admin.tier-lists.create');
    Route::post('/admin/tier-lists', [TierListController::class, 'store'])->name('admin.tier-lists.store');
    Route::get('/admin/tier-lists/{tierCategory}/edit', [TierListController::class, 'edit'])->name('admin.tier-lists.edit');
    Route::put('/admin/tier-lists/{tierCategory}', [TierListController::class, 'update'])->name('admin.tier-lists.update');
    Route::delete('/admin/tier-lists/{tierCategory}', [TierListController::class, 'destroy'])->name('admin.tier-lists.destroy');
    Route::get('/admin/tier-lists/{tierCategory}/manage', [TierListController::class, 'manageTiers'])->name('admin.tier-lists.manage');
    Route::post('/admin/tier-lists/{tierCategory}/assign', [TierListController::class, 'assignTier'])->name('admin.tier-lists.assign');
    Route::delete('/admin/tier-lists/{tierCategory}/tier-data/{tierData}', [TierListController::class, 'removeTier'])->name('admin.tier-lists.remove');
});
