-- SQL export generated for phpMyAdmin import
-- Project: Tier List Game Gacha
-- Date: 2025-12-11

SET FOREIGN_KEY_CHECKS = 0;

-- Drop tables if exist (child tables first)
DROP TABLE IF EXISTS `tier_data`;
DROP TABLE IF EXISTS `tier_categories`;
DROP TABLE IF EXISTS `characters`;
DROP TABLE IF EXISTS `elements`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `games`;
DROP TABLE IF EXISTS `admins`;

/*
 Table: admins
 Purpose: Menyimpan kredensial administrator backend.
 Rules:
 - `username` harus unik dan tidak kosong.
 - `password` menyimpan hash bcrypt (panjang hingga 255).
 - created_at/updated_at otomatis diisi.
*/
CREATE TABLE `admins` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key: unique admin id',
  `username` VARCHAR(50) NOT NULL COMMENT 'Unique login username untuk admin',
  `password` VARCHAR(255) NOT NULL COMMENT 'Hashed password (bcrypt recommended)',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan record',
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu update terakhir',
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Admin credentials table';

-- Table: games
/*
 Table: games
 Purpose: Master data daftar game.
 Rules:
 - `slug` unik, digunakan di URL (contoh: genshin-impact).
 - `game_name` wajib diisi.
*/
CREATE TABLE `games` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key: game id',
  `game_name` VARCHAR(100) NOT NULL COMMENT 'Nama game (contoh: Genshin Impact)',
  `slug` VARCHAR(100) NOT NULL COMMENT 'URL-friendly identifier (unik)',
  `icon_url` VARCHAR(255) DEFAULT NULL COMMENT 'URL icon game (opsional)',
  `description` TEXT DEFAULT NULL COMMENT 'Deskripsi singkat game (opsional)',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan record',
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu update terakhir',
  PRIMARY KEY (`id`),
  UNIQUE KEY `games_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master table for games';

-- Table: elements
/*
 Table: elements
 Purpose: Menyimpan elemen/atribut unik per game (contoh: Pyro, Hydro).
 Rules:
 - `game_id` FK ke `games(id)` dengan ON DELETE CASCADE.
 - `element_name` wajib.
*/
CREATE TABLE `elements` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key: element id',
  `game_id` INT(11) NOT NULL COMMENT 'Foreign key ke games.id (owner game)',
  `element_name` VARCHAR(50) NOT NULL COMMENT 'Nama elemen/atribut (contoh: Pyro)',
  `icon_url` VARCHAR(255) DEFAULT NULL COMMENT 'URL icon elemen (opsional)',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan record',
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu update terakhir',
  PRIMARY KEY (`id`),
  KEY `elements_game_id_index` (`game_id`),
  CONSTRAINT `elements_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Game-specific elements';

-- Table: roles
/*
 Table: roles
 Purpose: Menyimpan role/class karakter per game (contoh: DPS, Healer).
 Rules:
 - `game_id` FK ke `games(id)` dengan ON DELETE CASCADE.
 - `role_name` wajib.
*/
CREATE TABLE `roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key: role id',
  `game_id` INT(11) NOT NULL COMMENT 'Foreign key ke games.id (owner game)',
  `role_name` VARCHAR(50) NOT NULL COMMENT 'Nama role/class (contoh: Main DPS)',
  `icon_url` VARCHAR(255) DEFAULT NULL COMMENT 'URL icon role (opsional)',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan record',
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu update terakhir',
  PRIMARY KEY (`id`),
  KEY `roles_game_id_index` (`game_id`),
  CONSTRAINT `roles_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Game-specific character roles';

-- Table: characters
/*
 Table: characters
 Purpose: Master character data.
 Rules:
 - `game_id` FK ke `games(id)`, ON DELETE CASCADE (hapus karakter bila game dihapus).
 - `element_id` dan `role_id` bisa NULL; jika elemen/role dihapus, set NULL.
 - `rarity` gunakan integer (3-5).
*/
CREATE TABLE `characters` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key: character id',
  `game_id` INT(11) NOT NULL COMMENT 'Foreign key ke games.id (owner game)',
  `name` VARCHAR(100) NOT NULL COMMENT 'Nama karakter',
  `rarity` TINYINT(1) NOT NULL COMMENT 'Rarity karakter (mis. 3-5)',
  `element_id` INT(11) DEFAULT NULL COMMENT 'Optional FK ke elements.id (nullable)',
  `role_id` INT(11) DEFAULT NULL COMMENT 'Optional FK ke roles.id (nullable)',
  `image_url` VARCHAR(255) DEFAULT NULL COMMENT 'URL gambar karakter (opsional)',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan record',
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu update terakhir',
  PRIMARY KEY (`id`),
  KEY `characters_game_id_index` (`game_id`),
  KEY `characters_element_id_index` (`element_id`),
  KEY `characters_role_id_index` (`role_id`),
  CONSTRAINT `characters_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  CONSTRAINT `characters_element_id_foreign` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE SET NULL,
  CONSTRAINT `characters_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master table for characters';

-- Table: tier_categories
/*
 Table: tier_categories
 Purpose: Definisikan berbagai jenis tier list per game (contoh: Overall, Spiral Abyss).
 Rules:
 - `game_id` FK ke `games(id)` ON DELETE CASCADE.
 - `category_name` wajib.
*/
CREATE TABLE `tier_categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key: category id',
  `game_id` INT(11) NOT NULL COMMENT 'Foreign key ke games.id (owner game)',
  `category_name` VARCHAR(100) NOT NULL COMMENT 'Nama kategori tier (contoh: Overall Tier List)',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan record',
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu update terakhir',
  PRIMARY KEY (`id`),
  KEY `tier_categories_game_id_index` (`game_id`),
  CONSTRAINT `tier_categories_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tier categories per game';

-- Table: tier_data
/*
 Table: tier_data
 Purpose: Menghubungkan karakter dengan ranking mereka di kategori tertentu.
 Rules:
 - Composite unique (tier_category_id, character_id) agar satu karakter hanya satu entry per kategori.
 - `rank` dibatasi ke nilai seperti: SS, S, A, B, C, D (validation aplikasi diharapkan).
 - FK dengan ON DELETE CASCADE untuk membersihkan data ketika kategori atau karakter dihapus.
*/
CREATE TABLE `tier_data` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key: tier_data id',
  `tier_category_id` INT(11) NOT NULL COMMENT 'Foreign key ke tier_categories.id',
  `character_id` INT(11) NOT NULL COMMENT 'Foreign key ke characters.id',
  `rank` VARCHAR(5) NOT NULL COMMENT "Ranking tier (SS, S, A, B, C, D)",
  `sort_order` INT(11) DEFAULT 0 COMMENT 'Manual ordering dalam tier yang sama',
  `note` TEXT DEFAULT NULL COMMENT 'Catatan tambahan untuk entry (opsional)',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan record',
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu update terakhir',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tier_data_unique` (`tier_category_id`,`character_id`),
  KEY `tier_data_tier_category_id_index` (`tier_category_id`),
  KEY `tier_data_character_id_index` (`character_id`),
  CONSTRAINT `tier_data_tier_category_id_foreign` FOREIGN KEY (`tier_category_id`) REFERENCES `tier_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tier_data_character_id_foreign` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Ranking assignments for characters per category';

-- Insert demo data (based on DatabaseSeeder.php)
-- NOTE: The application expects bcrypt-hashed passwords for admins.
-- Replace the placeholder password below with a bcrypt hash for 'admin123' if you want to login immediately.

-- ADMIN
INSERT INTO `admins` (`id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', NOW(), NOW());

-- GAMES
INSERT INTO `games` (`id`, `game_name`, `slug`, `icon_url`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Genshin Impact', 'genshin-impact', 'https://via.placeholder.com/100?text=Genshin', 'Action RPG by miHoYo set in Teyvat', NOW(), NOW()),
(2, 'Honkai: Star Rail', 'honkai-star-rail', 'https://via.placeholder.com/100?text=HSR', 'Turn-based RPG by miHoYo', NOW(), NOW()),
(3, 'Zenless Zone Zero', 'zenless-zone-zero', 'https://via.placeholder.com/100?text=ZZZ', 'Action game by HoYoverse', NOW(), NOW());

-- ELEMENTS (Genshin)
INSERT INTO `elements` (`id`, `game_id`, `element_name`, `icon_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pyro', 'pyro.png', NOW(), NOW()),
(2, 1, 'Hydro', 'hydro.png', NOW(), NOW()),
(3, 1, 'Cryo', 'cryo.png', NOW(), NOW()),
(4, 1, 'Electro', 'electro.png', NOW(), NOW()),
(5, 1, 'Anemo', 'anemo.png', NOW(), NOW()),
(6, 1, 'Geo', 'geo.png', NOW(), NOW()),
-- ELEMENTS (HSR)
(7, 2, 'Fire', 'fire.png', NOW(), NOW()),
(8, 2, 'Ice', 'ice.png', NOW(), NOW()),
(9, 2, 'Quantum', 'quantum.png', NOW(), NOW()),
(10, 2, 'Wind', 'wind.png', NOW(), NOW()),
-- ELEMENTS (ZZZ)
(11, 3, 'Physical', 'physical.png', NOW(), NOW()),
(12, 3, 'Fire', 'fire.png', NOW(), NOW()),
(13, 3, 'Ice', 'ice.png', NOW(), NOW());

-- ROLES (Genshin)
INSERT INTO `roles` (`id`, `game_id`, `role_name`, `icon_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'Main DPS', 'dps.png', NOW(), NOW()),
(2, 1, 'Sub DPS', 'subdps.png', NOW(), NOW()),
(3, 1, 'Support', 'support.png', NOW(), NOW()),
(4, 1, 'Healer', 'healer.png', NOW(), NOW()),
-- ROLES (HSR)
(5, 2, 'Attacker', 'attacker.png', NOW(), NOW()),
(6, 2, 'Defender', 'defender.png', NOW(), NOW()),
(7, 2, 'Support', 'support.png', NOW(), NOW()),
-- ROLES (ZZZ)
(8, 3, 'Attack', 'attack.png', NOW(), NOW()),
(9, 3, 'Stun', 'stun.png', NOW(), NOW()),
(10, 3, 'Support', 'support.png', NOW(), NOW());

-- CHARACTERS (GENSHIN)
INSERT INTO `characters` (`id`, `game_id`, `name`, `rarity`, `element_id`, `role_id`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'Hu Tao', 5, 1, 1, 'https://via.placeholder.com/150?text=HuTao', NOW(), NOW()),
(2, 1, 'Yelan', 5, 2, 2, 'https://via.placeholder.com/150?text=Yelan', NOW(), NOW()),
(3, 1, 'Nahida', 5, 2, 3, 'https://via.placeholder.com/150?text=Nahida', NOW(), NOW()),
(4, 1, 'Zhongli', 5, 6, 3, 'https://via.placeholder.com/150?text=Zhongli', NOW(), NOW()),
(5, 1, 'Ganyu', 5, 3, 1, 'https://via.placeholder.com/150?text=Ganyu', NOW(), NOW()),
(6, 1, 'Fischl', 4, 4, 2, 'https://via.placeholder.com/150?text=Fischl', NOW(), NOW()),
-- CHARACTERS (HSR)
(7, 2, 'Seele', 5, 9, 5, 'https://via.placeholder.com/150?text=Seele', NOW(), NOW()),
(8, 2, 'Kafka', 5, 7, 5, 'https://via.placeholder.com/150?text=Kafka', NOW(), NOW()),
(9, 2, 'Gepard', 5, 8, 6, 'https://via.placeholder.com/150?text=Gepard', NOW(), NOW()),
(10, 2, 'Bailu', 5, 7, 7, 'https://via.placeholder.com/150?text=Bailu', NOW(), NOW()),
-- CHARACTERS (ZZZ)
(11, 3, 'Ellen Joe', 5, 11, 8, 'https://via.placeholder.com/150?text=EllenJoe', NOW(), NOW()),
(12, 3, 'Anton', 5, 13, 8, 'https://via.placeholder.com/150?text=Anton', NOW(), NOW());

-- TIER_CATEGORIES
INSERT INTO `tier_categories` (`id`, `game_id`, `category_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Overall Tier List (PvE)', NOW(), NOW()),
(2, 1, 'Spiral Abyss Meta', NOW(), NOW()),
(3, 2, 'Overall Tier List', NOW(), NOW()),
(4, 3, 'Beginner Friendly', NOW(), NOW());

-- TIER_DATA (Genshin - Overall)
INSERT INTO `tier_data` (`id`, `tier_category_id`, `character_id`, `rank`, `sort_order`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'SS', 1, 'Best single-target DPS', NOW(), NOW()),
(2, 1, 3, 'SS', 2, 'Best Dendro applicator', NOW(), NOW()),
(3, 1, 4, 'S', 1, 'Universal support, shields everything', NOW(), NOW()),
(4, 1, 5, 'S', 2, 'Excellent freeze or burst damage', NOW(), NOW()),
(5, 1, 2, 'S', 3, 'Strong off-field DPS', NOW(), NOW()),
(6, 1, 6, 'A', 1, 'Great budget Electro applicator', NOW(), NOW()),
-- TIER_DATA (Genshin - Abyss)
(7, 2, 1, 'S', 1, 'Viable but sometimes struggles with multiple enemies', NOW(), NOW()),
(8, 2, 3, 'SS', 1, 'Meta defining for Dendro teams', NOW(), NOW()),
(9, 2, 4, 'SS', 2, 'Enables many compositions', NOW(), NOW()),
-- TIER_DATA (HSR - Overall)
(10, 3, 7, 'SS', 1, 'Top-tier Quantum attacker', NOW(), NOW()),
(11, 3, 8, 'SS', 2, 'Best Nihility path damage dealer', NOW(), NOW()),
(12, 3, 9, 'S', 1, 'Reliable sustain and shield', NOW(), NOW()),
(13, 3, 10, 'A', 1, 'Good healer but action economy concerns', NOW(), NOW()),
-- TIER_DATA (ZZZ - Beginner)
(14, 4, 11, 'S', 1, 'Easy to use, high damage', NOW(), NOW()),
(15, 4, 12, 'S', 2, 'Good all-rounder', NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;

-- End of export.sql
-- IMPORTANT: The `admins.password` value above is a placeholder. To login with 'admin'/'admin123', replace the password value with a bcrypt hash for 'admin123'.
-- You can generate a bcrypt hash online or run in a PHP environment:
-- php -r "echo password_hash('admin123', PASSWORD_BCRYPT).PHP_EOL;"
-- Then run:
-- UPDATE `admins` SET `password` = '<the_hash>' WHERE `username` = 'admin';

-- Or after import, use Laravel tinker on the server to create/change admin:
-- php artisan tinker
-- >>> \App\Models\Admin::create(['username' => 'admin', 'password' => 'admin123']);

