<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDatabaseViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // View: characters with related names and URLs
        DB::statement(<<<'SQL'
CREATE OR REPLACE VIEW view_characters_full AS
SELECT 
    c.id AS character_id,
    c.name AS character_name,
    c.rarity,
    g.game_name,
    e.element_name,
    r.role_name,
    c.image_url,
    c.created_at,
    c.updated_at
FROM characters c
LEFT JOIN games g ON c.game_id = g.id
LEFT JOIN elements e ON c.element_id = e.id
LEFT JOIN roles r ON c.role_id = r.id;
SQL
        );

        // View: full tierlist entries with game and category
        DB::statement(<<<'SQL'
CREATE OR REPLACE VIEW view_tierlist_full AS
SELECT 
    td.id AS tier_data_id,
    g.game_name,
    tc.category_name,
    c.name AS character_name,
    c.rarity,
    td.rank,
    td.sort_order,
    td.note,
    td.created_at,
    td.updated_at
FROM tier_data td
INNER JOIN tier_categories tc ON td.tier_category_id = tc.id
INNER JOIN characters c ON td.character_id = c.id
INNER JOIN games g ON tc.game_id = g.id;
SQL
        );

        // View: elements joined with their game
        DB::statement(<<<'SQL'
CREATE OR REPLACE VIEW view_elements_per_game AS
SELECT 
    e.id AS element_id,
    e.element_name,
    g.game_name,
    g.slug,
    e.icon_url,
    e.created_at,
    e.updated_at
FROM elements e
INNER JOIN games g ON e.game_id = g.id;
SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS view_characters_full');
        DB::statement('DROP VIEW IF EXISTS view_tierlist_full');
        DB::statement('DROP VIEW IF EXISTS view_elements_per_game');
    }
}
