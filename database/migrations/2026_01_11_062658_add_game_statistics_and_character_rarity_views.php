<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // View 1: Game Statistics - summary of characters, elements, and roles per game
        DB::statement('DROP VIEW IF EXISTS view_games_statistics;
            CREATE VIEW view_games_statistics AS
            SELECT 
                g.id,
                g.game_name,
                g.slug,
                COUNT(DISTINCT c.id) AS total_characters,
                COUNT(DISTINCT e.id) AS total_elements,
                COUNT(DISTINCT r.id) AS total_roles,
                COUNT(DISTINCT tc.id) AS total_tier_categories
            FROM games g
            LEFT JOIN characters c ON g.id = c.game_id
            LEFT JOIN elements e ON g.id = e.game_id
            LEFT JOIN roles r ON g.id = r.game_id
            LEFT JOIN tier_categories tc ON g.id = tc.game_id
            GROUP BY g.id, g.game_name, g.slug
        ');

        // View 2: Characters by Rarity - all characters sorted by rarity with game info
        DB::statement('DROP VIEW IF EXISTS view_characters_by_rarity;
            CREATE VIEW view_characters_by_rarity AS
            SELECT 
                c.id,
                c.name AS character_name,
                c.rarity,
                g.id AS game_id,
                g.game_name,
                g.slug AS game_slug,
                e.id AS element_id,
                e.element_name,
                r.id AS role_id,
                r.role_name,
                c.image_url,
                c.created_at,
                c.updated_at
            FROM characters c
            JOIN games g ON c.game_id = g.id
            LEFT JOIN elements e ON c.element_id = e.id
            LEFT JOIN roles r ON c.role_id = r.id
            ORDER BY g.game_name, c.rarity DESC, c.name
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_games_statistics');
        DB::statement('DROP VIEW IF EXISTS view_characters_by_rarity');
    }
};
