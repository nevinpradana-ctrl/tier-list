<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddColumnAndTableComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Games table comments
        DB::statement("ALTER TABLE `games` COMMENT = 'Table: games - stores game entries for tier lists';");
        DB::statement("ALTER TABLE `games` MODIFY `game_name` varchar(100) NOT NULL COMMENT 'Nama game (unik secara visual)';");
        DB::statement("ALTER TABLE `games` MODIFY `slug` varchar(100) NOT NULL COMMENT 'URL-friendly slug untuk routing';");
        DB::statement("ALTER TABLE `games` MODIFY `icon_url` varchar(255) NULL COMMENT 'Path public storage untuk icon game (nullable)';");
        DB::statement("ALTER TABLE `games` MODIFY `description` text NULL COMMENT 'Deskripsi singkat game (nullable)';");

        // Elements table comments
        DB::statement("ALTER TABLE `elements` COMMENT = 'Table: elements - elemental or attribute types per game';");
        DB::statement("ALTER TABLE `elements` MODIFY `game_id` bigint unsigned NOT NULL COMMENT 'Foreign key ke table games';");
        DB::statement("ALTER TABLE `elements` MODIFY `element_name` varchar(50) NOT NULL COMMENT 'Nama element (mis. Fire, Water)';");
        DB::statement("ALTER TABLE `elements` MODIFY `icon_url` varchar(255) NULL COMMENT 'Path public storage untuk icon element (nullable)';");

        // Roles table comments
        DB::statement("ALTER TABLE `roles` COMMENT = 'Table: roles - role/position types per game';");
        DB::statement("ALTER TABLE `roles` MODIFY `game_id` bigint unsigned NOT NULL COMMENT 'Foreign key ke table games';");
        DB::statement("ALTER TABLE `roles` MODIFY `role_name` varchar(50) NOT NULL COMMENT 'Nama role (mis. DPS, Healer)';");
        DB::statement("ALTER TABLE `roles` MODIFY `icon_url` varchar(255) NULL COMMENT 'Path public storage untuk icon role (nullable)';");

        // Characters table comments
        DB::statement("ALTER TABLE `characters` COMMENT = 'Table: characters - playable characters associated with games';");
        DB::statement("ALTER TABLE `characters` MODIFY `game_id` bigint unsigned NOT NULL COMMENT 'Foreign key ke table games';");
        DB::statement("ALTER TABLE `characters` MODIFY `name` varchar(100) NOT NULL COMMENT 'Nama karakter';");
        DB::statement("ALTER TABLE `characters` MODIFY `rarity` int NOT NULL COMMENT 'Rarity / tier indicator (integer)';");
        DB::statement("ALTER TABLE `characters` MODIFY `element_id` bigint unsigned NULL COMMENT 'Foreign key ke elements (nullable)';");
        DB::statement("ALTER TABLE `characters` MODIFY `role_id` bigint unsigned NULL COMMENT 'Foreign key ke roles (nullable)';");
        DB::statement("ALTER TABLE `characters` MODIFY `image_url` varchar(255) NULL COMMENT 'Path public storage untuk gambar karakter (nullable)';");

        // Tier categories table comments
        DB::statement("ALTER TABLE `tier_categories` COMMENT = 'Table: tier_categories - per-game tier list categories (e.g., PvE, PvP)';");
        DB::statement("ALTER TABLE `tier_categories` MODIFY `game_id` bigint unsigned NOT NULL COMMENT 'Foreign key ke games';");
        DB::statement("ALTER TABLE `tier_categories` MODIFY `category_name` varchar(100) NOT NULL COMMENT 'Nama kategori tier list';");

        // Tier data table comments
        DB::statement("ALTER TABLE `tier_data` COMMENT = 'Table: tier_data - assignments of characters to ranks within a category';");
        DB::statement("ALTER TABLE `tier_data` MODIFY `tier_category_id` bigint unsigned NOT NULL COMMENT 'Foreign key ke tier_categories';");
        DB::statement("ALTER TABLE `tier_data` MODIFY `character_id` bigint unsigned NOT NULL COMMENT 'Foreign key ke characters';");
        DB::statement("ALTER TABLE `tier_data` MODIFY `rank` varchar(5) NOT NULL COMMENT 'Rank label (e.g., SS, S, A)';");
        DB::statement("ALTER TABLE `tier_data` MODIFY `sort_order` int NOT NULL DEFAULT 0 COMMENT 'Order within the same rank (integer)';");
        DB::statement("ALTER TABLE `tier_data` MODIFY `note` text NULL COMMENT 'Catatan tambahan untuk assignment (nullable)';");

        // Admins table comments
        DB::statement("ALTER TABLE `admins` COMMENT = 'Table: admins - admin users for the application';");
        DB::statement("ALTER TABLE `admins` MODIFY `username` varchar(50) NOT NULL COMMENT 'Admin username (unique)';");
        DB::statement("ALTER TABLE `admins` MODIFY `password` varchar(255) NOT NULL COMMENT 'Hashed password (bcrypt)';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove column comments by re-modifying columns without COMMENT (MySQL will remove comment if not specified)
        DB::statement("ALTER TABLE `games` MODIFY `game_name` varchar(100) NOT NULL;");
        DB::statement("ALTER TABLE `games` MODIFY `slug` varchar(100) NOT NULL;");
        DB::statement("ALTER TABLE `games` MODIFY `icon_url` varchar(255) NULL;");
        DB::statement("ALTER TABLE `games` MODIFY `description` text NULL;");

        DB::statement("ALTER TABLE `elements` MODIFY `game_id` bigint unsigned NOT NULL;");
        DB::statement("ALTER TABLE `elements` MODIFY `element_name` varchar(50) NOT NULL;");
        DB::statement("ALTER TABLE `elements` MODIFY `icon_url` varchar(255) NULL;");

        DB::statement("ALTER TABLE `roles` MODIFY `game_id` bigint unsigned NOT NULL;");
        DB::statement("ALTER TABLE `roles` MODIFY `role_name` varchar(50) NOT NULL;");
        DB::statement("ALTER TABLE `roles` MODIFY `icon_url` varchar(255) NULL;");

        DB::statement("ALTER TABLE `characters` MODIFY `game_id` bigint unsigned NOT NULL;");
        DB::statement("ALTER TABLE `characters` MODIFY `name` varchar(100) NOT NULL;");
        DB::statement("ALTER TABLE `characters` MODIFY `rarity` int NOT NULL;");
        DB::statement("ALTER TABLE `characters` MODIFY `element_id` bigint unsigned NULL;");
        DB::statement("ALTER TABLE `characters` MODIFY `role_id` bigint unsigned NULL;");
        DB::statement("ALTER TABLE `characters` MODIFY `image_url` varchar(255) NULL;");

        DB::statement("ALTER TABLE `tier_categories` MODIFY `game_id` bigint unsigned NOT NULL;");
        DB::statement("ALTER TABLE `tier_categories` MODIFY `category_name` varchar(100) NOT NULL;");

        DB::statement("ALTER TABLE `tier_data` MODIFY `tier_category_id` bigint unsigned NOT NULL;");
        DB::statement("ALTER TABLE `tier_data` MODIFY `character_id` bigint unsigned NOT NULL;");
        DB::statement("ALTER TABLE `tier_data` MODIFY `rank` varchar(5) NOT NULL;");
        DB::statement("ALTER TABLE `tier_data` MODIFY `sort_order` int NOT NULL DEFAULT 0;");
        DB::statement("ALTER TABLE `tier_data` MODIFY `note` text NULL;");

        DB::statement("ALTER TABLE `admins` MODIFY `username` varchar(50) NOT NULL;");
        DB::statement("ALTER TABLE `admins` MODIFY `password` varchar(255) NOT NULL;");
    }
}
