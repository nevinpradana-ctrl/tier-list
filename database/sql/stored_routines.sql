DELIMITER $$

-- DROP existing functions/procedures if present
DROP FUNCTION IF EXISTS fn_count_characters_in_game$$
DROP FUNCTION IF EXISTS fn_get_game_slug_by_id$$
DROP FUNCTION IF EXISTS fn_avg_rank_sort_order$$
DROP FUNCTION IF EXISTS fn_character_full_desc$$
DROP FUNCTION IF EXISTS fn_elements_count_per_game$$

DROP PROCEDURE IF EXISTS sp_create_tier_category$$
DROP PROCEDURE IF EXISTS sp_move_character_to_rank$$
DROP PROCEDURE IF EXISTS sp_swap_tier_positions$$
DROP PROCEDURE IF EXISTS sp_cleanup_unused_elements$$
DROP PROCEDURE IF EXISTS sp_update_game_icon$$

-- 1) Function: count characters in a game
CREATE FUNCTION fn_count_characters_in_game(gid INT) RETURNS INT
DETERMINISTIC
BEGIN
  DECLARE v_count INT DEFAULT 0;
  SELECT COUNT(*) INTO v_count FROM characters WHERE game_id = gid;
  RETURN v_count;
END$$

-- 2) Function: get game slug by id
CREATE FUNCTION fn_get_game_slug_by_id(gid INT) RETURNS VARCHAR(100)
DETERMINISTIC
BEGIN
  DECLARE v_slug VARCHAR(100) DEFAULT NULL;
  SELECT slug INTO v_slug FROM games WHERE id = gid LIMIT 1;
  RETURN v_slug;
END$$

-- 3) Function: average sort_order for specified rank within a game
CREATE FUNCTION fn_avg_rank_sort_order(gid INT, rank_label VARCHAR(5)) RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
  DECLARE v_avg DECIMAL(10,2) DEFAULT NULL;
  SELECT AVG(td.sort_order) INTO v_avg
  FROM tier_data td
  INNER JOIN tier_categories tc ON td.tier_category_id = tc.id
  WHERE tc.game_id = gid AND td.rank = rank_label;
  RETURN IFNULL(v_avg, 0);
END$$

-- 4) Function: character descriptive string (name + rarity + game)
CREATE FUNCTION fn_character_full_desc(cid INT) RETURNS VARCHAR(255)
DETERMINISTIC
BEGIN
  DECLARE v_desc VARCHAR(255);
  SELECT CONCAT(c.name, ' (Rarity:', c.rarity, ') - ', COALESCE(g.game_name,'Unknown'))
    INTO v_desc
    FROM characters c
    LEFT JOIN games g ON c.game_id = g.id
    WHERE c.id = cid
    LIMIT 1;
  RETURN COALESCE(v_desc, 'Unknown Character');
END$$

-- 5) Function: count elements for a game
CREATE FUNCTION fn_elements_count_per_game(gid INT) RETURNS INT
DETERMINISTIC
BEGIN
  DECLARE v_cnt INT DEFAULT 0;
  SELECT COUNT(*) INTO v_cnt FROM elements WHERE game_id = gid;
  RETURN v_cnt;
END$$

-- Procedures

-- 1) Create a tier category and return its id
CREATE PROCEDURE sp_create_tier_category(IN p_game_id INT, IN p_name VARCHAR(100), OUT p_new_id INT)
BEGIN
  INSERT INTO tier_categories (game_id, category_name, created_at, updated_at)
  VALUES (p_game_id, p_name, NOW(), NOW());
  SET p_new_id = LAST_INSERT_ID();
END$$

-- 2) Move (insert or update) a character into a category with rank and sort order
CREATE PROCEDURE sp_move_character_to_rank(IN p_tier_category_id INT, IN p_character_id INT, IN p_rank VARCHAR(5), IN p_sort_order INT)
BEGIN
  DECLARE v_exists INT DEFAULT 0;
  SELECT COUNT(*) INTO v_exists FROM tier_data WHERE tier_category_id = p_tier_category_id AND character_id = p_character_id;
  IF v_exists > 0 THEN
    UPDATE tier_data SET rank = p_rank, sort_order = p_sort_order, updated_at = NOW()
    WHERE tier_category_id = p_tier_category_id AND character_id = p_character_id;
  ELSE
    INSERT INTO tier_data (tier_category_id, character_id, rank, sort_order, created_at, updated_at)
    VALUES (p_tier_category_id, p_character_id, p_rank, p_sort_order, NOW(), NOW());
  END IF;
END$$

-- 3) Swap rank and sort_order between two tier_data rows
CREATE PROCEDURE sp_swap_tier_positions(IN p_id1 INT, IN p_id2 INT)
BEGIN
  DECLARE r1 VARCHAR(5);
  DECLARE s1 INT;
  DECLARE r2 VARCHAR(5);
  DECLARE s2 INT;

  SELECT rank, sort_order INTO r1, s1 FROM tier_data WHERE id = p_id1 LIMIT 1;
  SELECT rank, sort_order INTO r2, s2 FROM tier_data WHERE id = p_id2 LIMIT 1;

  UPDATE tier_data SET rank = r2, sort_order = s2, updated_at = NOW() WHERE id = p_id1;
  UPDATE tier_data SET rank = r1, sort_order = s1, updated_at = NOW() WHERE id = p_id2;
END$$

-- 4) Cleanup elements not referenced by any character
CREATE PROCEDURE sp_cleanup_unused_elements(OUT p_deleted INT)
BEGIN
  DELETE FROM elements WHERE id NOT IN (SELECT DISTINCT element_id FROM characters WHERE element_id IS NOT NULL);
  SET p_deleted = ROW_COUNT();
END$$

-- 5) Update game icon URL
CREATE PROCEDURE sp_update_game_icon(IN p_game_id INT, IN p_new_path VARCHAR(255))
BEGIN
  UPDATE games SET icon_url = p_new_path, updated_at = NOW() WHERE id = p_game_id;
END$$

DELIMITER ;
