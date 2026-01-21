<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Character;
use App\Models\Element;
use App\Models\Game;
use App\Models\Role;
use App\Models\TierCategory;
use App\Models\TierData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        Admin::create([
            'username' => 'admin',
            'password' => 'admin123', // Will be bcrypted by mutator
        ]);

        // Create Genshin Impact Game
        $genshin = Game::create([
            'game_name' => 'Genshin Impact',
            'slug' => 'genshin-impact',
            'icon_url' => null,
            'description' => 'Action RPG by miHoYo set in Teyvat',
        ]);

        // Create HSR Game
        $hsr = Game::create([
            'game_name' => 'Honkai: Star Rail',
            'slug' => 'honkai-star-rail',
            'icon_url' => null,
            'description' => 'Turn-based RPG by miHoYo',
        ]);

        // Create ZZZ Game
        $zzz = Game::create([
            'game_name' => 'Zenless Zone Zero',
            'slug' => 'zenless-zone-zero',
            'icon_url' => null,
            'description' => 'Action game by HoYoverse',
        ]);

        // ========== GENSHIN IMPACT ELEMENTS ==========
        $pyro = Element::create(['game_id' => $genshin->id, 'element_name' => 'Pyro', 'icon_url' => 'pyro.png']);
        $hydro = Element::create(['game_id' => $genshin->id, 'element_name' => 'Hydro', 'icon_url' => 'hydro.png']);
        $cryo = Element::create(['game_id' => $genshin->id, 'element_name' => 'Cryo', 'icon_url' => 'cryo.png']);
        $electro = Element::create(['game_id' => $genshin->id, 'element_name' => 'Electro', 'icon_url' => 'electro.png']);
        $anemo = Element::create(['game_id' => $genshin->id, 'element_name' => 'Anemo', 'icon_url' => 'anemo.png']);
        $geo = Element::create(['game_id' => $genshin->id, 'element_name' => 'Geo', 'icon_url' => 'geo.png']);

        // ========== GENSHIN IMPACT ROLES ==========
        $dps = Role::create(['game_id' => $genshin->id, 'role_name' => 'Main DPS', 'icon_url' => 'dps.png']);
        $subdps = Role::create(['game_id' => $genshin->id, 'role_name' => 'Sub DPS', 'icon_url' => 'subdps.png']);
        $support = Role::create(['game_id' => $genshin->id, 'role_name' => 'Support', 'icon_url' => 'support.png']);
        $healer = Role::create(['game_id' => $genshin->id, 'role_name' => 'Healer', 'icon_url' => 'healer.png']);

        // ========== GENSHIN CHARACTERS ==========
        $hu_tao = Character::create([
            'game_id' => $genshin->id,
            'name' => 'Hu Tao',
            'rarity' => 5,
            'element_id' => $pyro->id,
            'role_id' => $dps->id,
            'image_url' => 'https://via.placeholder.com/150?text=HuTao',
        ]);

        $yelan = Character::create([
            'game_id' => $genshin->id,
            'name' => 'Yelan',
            'rarity' => 5,
            'element_id' => $hydro->id,
            'role_id' => $subdps->id,
            'image_url' => 'https://via.placeholder.com/150?text=Yelan',
        ]);

        $nahida = Character::create([
            'game_id' => $genshin->id,
            'name' => 'Nahida',
            'rarity' => 5,
            'element_id' => $hydro->id,
            'role_id' => $support->id,
            'image_url' => 'https://via.placeholder.com/150?text=Nahida',
        ]);

        $zhongli = Character::create([
            'game_id' => $genshin->id,
            'name' => 'Zhongli',
            'rarity' => 5,
            'element_id' => $geo->id,
            'role_id' => $support->id,
            'image_url' => 'https://via.placeholder.com/150?text=Zhongli',
        ]);

        $ganyu = Character::create([
            'game_id' => $genshin->id,
            'name' => 'Ganyu',
            'rarity' => 5,
            'element_id' => $cryo->id,
            'role_id' => $dps->id,
            'image_url' => 'https://via.placeholder.com/150?text=Ganyu',
        ]);

        $fischl = Character::create([
            'game_id' => $genshin->id,
            'name' => 'Fischl',
            'rarity' => 4,
            'element_id' => $electro->id,
            'role_id' => $subdps->id,
            'image_url' => 'https://via.placeholder.com/150?text=Fischl',
        ]);

        // ========== HSR ELEMENTS ==========
        $fire_hsr = Element::create(['game_id' => $hsr->id, 'element_name' => 'Fire', 'icon_url' => 'fire.png']);
        $ice_hsr = Element::create(['game_id' => $hsr->id, 'element_name' => 'Ice', 'icon_url' => 'ice.png']);
        $quantum_hsr = Element::create(['game_id' => $hsr->id, 'element_name' => 'Quantum', 'icon_url' => 'quantum.png']);
        $wind_hsr = Element::create(['game_id' => $hsr->id, 'element_name' => 'Wind', 'icon_url' => 'wind.png']);

        // ========== HSR ROLES ==========
        $attacker = Role::create(['game_id' => $hsr->id, 'role_name' => 'Attacker', 'icon_url' => 'attacker.png']);
        $defender = Role::create(['game_id' => $hsr->id, 'role_name' => 'Defender', 'icon_url' => 'defender.png']);
        $hsr_support = Role::create(['game_id' => $hsr->id, 'role_name' => 'Support', 'icon_url' => 'support.png']);

        // ========== HSR CHARACTERS ==========
        $seele = Character::create([
            'game_id' => $hsr->id,
            'name' => 'Seele',
            'rarity' => 5,
            'element_id' => $quantum_hsr->id,
            'role_id' => $attacker->id,
            'image_url' => 'https://via.placeholder.com/150?text=Seele',
        ]);

        $kafka = Character::create([
            'game_id' => $hsr->id,
            'name' => 'Kafka',
            'rarity' => 5,
            'element_id' => $fire_hsr->id,
            'role_id' => $attacker->id,
            'image_url' => 'https://via.placeholder.com/150?text=Kafka',
        ]);

        $gepard = Character::create([
            'game_id' => $hsr->id,
            'name' => 'Gepard',
            'rarity' => 5,
            'element_id' => $ice_hsr->id,
            'role_id' => $defender->id,
            'image_url' => 'https://via.placeholder.com/150?text=Gepard',
        ]);

        $bailu = Character::create([
            'game_id' => $hsr->id,
            'name' => 'Bailu',
            'rarity' => 5,
            'element_id' => $fire_hsr->id,
            'role_id' => $hsr_support->id,
            'image_url' => 'https://via.placeholder.com/150?text=Bailu',
        ]);

        // ========== ZZZ ELEMENTS ==========
        $physical_zzz = Element::create(['game_id' => $zzz->id, 'element_name' => 'Physical', 'icon_url' => 'physical.png']);
        $fire_zzz = Element::create(['game_id' => $zzz->id, 'element_name' => 'Fire', 'icon_url' => 'fire.png']);
        $ice_zzz = Element::create(['game_id' => $zzz->id, 'element_name' => 'Ice', 'icon_url' => 'ice.png']);

        // ========== ZZZ ROLES ==========
        $attacker_zzz = Role::create(['game_id' => $zzz->id, 'role_name' => 'Attack', 'icon_url' => 'attack.png']);
        $stun_zzz = Role::create(['game_id' => $zzz->id, 'role_name' => 'Stun', 'icon_url' => 'stun.png']);
        $support_zzz = Role::create(['game_id' => $zzz->id, 'role_name' => 'Support', 'icon_url' => 'support.png']);

        // ========== ZZZ CHARACTERS ==========
        $ellen = Character::create([
            'game_id' => $zzz->id,
            'name' => 'Ellen Joe',
            'rarity' => 5,
            'element_id' => $physical_zzz->id,
            'role_id' => $attacker_zzz->id,
            'image_url' => 'https://via.placeholder.com/150?text=EllenJoe',
        ]);

        $anton = Character::create([
            'game_id' => $zzz->id,
            'name' => 'Anton',
            'rarity' => 5,
            'element_id' => $ice_zzz->id,
            'role_id' => $attacker_zzz->id,
            'image_url' => 'https://via.placeholder.com/150?text=Anton',
        ]);

        // ========== TIER CATEGORIES & DATA ==========

        // Genshin - Overall Tier List
        $genshin_overall = TierCategory::create([
            'game_id' => $genshin->id,
            'category_name' => 'Overall Tier List (PvE)',
        ]);

        TierData::create(['tier_category_id' => $genshin_overall->id, 'character_id' => $hu_tao->id, 'rank' => 'SS', 'sort_order' => 1, 'note' => 'Best single-target DPS']);
        TierData::create(['tier_category_id' => $genshin_overall->id, 'character_id' => $nahida->id, 'rank' => 'SS', 'sort_order' => 2, 'note' => 'Best Dendro applicator']);
        TierData::create(['tier_category_id' => $genshin_overall->id, 'character_id' => $zhongli->id, 'rank' => 'S', 'sort_order' => 1, 'note' => 'Universal support, shields everything']);
        TierData::create(['tier_category_id' => $genshin_overall->id, 'character_id' => $ganyu->id, 'rank' => 'S', 'sort_order' => 2, 'note' => 'Excellent freeze or burst damage']);
        TierData::create(['tier_category_id' => $genshin_overall->id, 'character_id' => $yelan->id, 'rank' => 'S', 'sort_order' => 3, 'note' => 'Strong off-field DPS']);
        TierData::create(['tier_category_id' => $genshin_overall->id, 'character_id' => $fischl->id, 'rank' => 'A', 'sort_order' => 1, 'note' => 'Great budget Electro applicator']);

        // Genshin - Abyss Tier List
        $genshin_abyss = TierCategory::create([
            'game_id' => $genshin->id,
            'category_name' => 'Spiral Abyss Meta',
        ]);

        TierData::create(['tier_category_id' => $genshin_abyss->id, 'character_id' => $hu_tao->id, 'rank' => 'S', 'sort_order' => 1, 'note' => 'Viable but sometimes struggles with multiple enemies']);
        TierData::create(['tier_category_id' => $genshin_abyss->id, 'character_id' => $nahida->id, 'rank' => 'SS', 'sort_order' => 1, 'note' => 'Meta defining for Dendro teams']);
        TierData::create(['tier_category_id' => $genshin_abyss->id, 'character_id' => $zhongli->id, 'rank' => 'SS', 'sort_order' => 2, 'note' => 'Enables many compositions']);

        // HSR - Overall Tier List
        $hsr_overall = TierCategory::create([
            'game_id' => $hsr->id,
            'category_name' => 'Overall Tier List',
        ]);

        TierData::create(['tier_category_id' => $hsr_overall->id, 'character_id' => $seele->id, 'rank' => 'SS', 'sort_order' => 1, 'note' => 'Top-tier Quantum attacker']);
        TierData::create(['tier_category_id' => $hsr_overall->id, 'character_id' => $kafka->id, 'rank' => 'SS', 'sort_order' => 2, 'note' => 'Best Nihility path damage dealer']);
        TierData::create(['tier_category_id' => $hsr_overall->id, 'character_id' => $gepard->id, 'rank' => 'S', 'sort_order' => 1, 'note' => 'Reliable sustain and shield']);
        TierData::create(['tier_category_id' => $hsr_overall->id, 'character_id' => $bailu->id, 'rank' => 'A', 'sort_order' => 1, 'note' => 'Good healer but action economy concerns']);

        // ZZZ - Beginner Tier List
        $zzz_beginner = TierCategory::create([
            'game_id' => $zzz->id,
            'category_name' => 'Beginner Friendly',
        ]);

        TierData::create(['tier_category_id' => $zzz_beginner->id, 'character_id' => $ellen->id, 'rank' => 'S', 'sort_order' => 1, 'note' => 'Easy to use, high damage']);
        TierData::create(['tier_category_id' => $zzz_beginner->id, 'character_id' => $anton->id, 'rank' => 'S', 'sort_order' => 2, 'note' => 'Good all-rounder']);
    }
}
