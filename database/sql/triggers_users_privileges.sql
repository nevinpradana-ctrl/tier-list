-- ===============================================================
-- GACHA TIER LIST DATABASE - TRIGGERS, USERS, AND PRIVILEGES
-- ===============================================================
-- Purpose: Educational demonstration of SQL triggers and user management
-- Date: 2025-12-29
-- ===============================================================

DELIMITER $$

-- Drop existing triggers if present
DROP TRIGGER IF EXISTS trg_auto_update_character_timestamp$$
DROP TRIGGER IF EXISTS trg_validate_character_rarity$$
DROP TRIGGER IF EXISTS trg_validate_character_rarity_update$$
DROP TRIGGER IF EXISTS trg_log_tier_data_changes$$
DROP TRIGGER IF EXISTS trg_log_tier_data_update$$
DROP TRIGGER IF EXISTS trg_log_tier_data_delete$$
DROP TRIGGER IF EXISTS trg_prevent_duplicate_tier_assignment$$
DROP TRIGGER IF EXISTS trg_enforce_rank_values$$
DROP TRIGGER IF EXISTS trg_enforce_rank_values_update$$
DROP TRIGGER IF EXISTS trg_auto_increment_sort_order$$
DROP TRIGGER IF EXISTS trg_audit_element_deletion$$
DROP TRIGGER IF EXISTS trg_cascade_update_game_on_character_change$$

-- ===============================================================
-- TRIGGER 1: Auto-update character updated_at timestamp on any change
-- ===============================================================
-- Purpose: Automatically update the 'updated_at' column whenever a character record is modified
-- Benefit: Ensures data integrity and tracks when records are last modified without manual intervention
-- Use case: Auditing, finding recently changed characters, debugging
--
-- Syntax explanation:
-- CREATE TRIGGER trg_auto_update_character_timestamp
--   - Defines a trigger named with 'trg_' prefix for clarity
-- BEFORE UPDATE ON characters
--   - Fires before any UPDATE statement executes on 'characters' table
-- FOR EACH ROW
--   - Applies to each row affected by the UPDATE (not once per statement)
-- BEGIN ... END
--   - Trigger body containing SQL statements
-- SET NEW.updated_at = NOW();
--   - NEW refers to the new row values; updates timestamp to current time
--
CREATE TRIGGER trg_auto_update_character_timestamp
BEFORE UPDATE ON characters
FOR EACH ROW
BEGIN
  SET NEW.updated_at = NOW();
END$$

-- ===============================================================
-- TRIGGER 2: Validate character rarity is between 1 and 5 on INSERT
-- ===============================================================
-- Purpose: Enforce business rule that rarity must be 1-5 (star rating system)
-- Benefit: Prevents invalid data entry at database level; no bad data can be inserted
-- Use case: Data validation, business rule enforcement, quality control
--
-- Syntax explanation:
-- BEFORE INSERT ON characters ... FOR EACH ROW
--   - Fires before INSERT, checks NEW values
-- IF NEW.rarity < 1 OR NEW.rarity > 5 THEN
--   - Conditional check; if rarity is outside valid range
-- CALL invalid_proc_rarity_must_be_1_to_5();
--   - Calls a non-existent procedure, which causes an error and prevents insertion
--   - This is a workaround since MariaDB doesn't support SIGNAL in all versions
--
CREATE TRIGGER trg_validate_character_rarity
BEFORE INSERT ON characters
FOR EACH ROW
BEGIN
  IF NEW.rarity < 1 OR NEW.rarity > 5 THEN
    CALL invalid_proc_rarity_must_be_1_to_5();
  END IF;
END$$

-- Create same trigger for UPDATE
CREATE TRIGGER trg_validate_character_rarity_update
BEFORE UPDATE ON characters
FOR EACH ROW
BEGIN
  IF NEW.rarity < 1 OR NEW.rarity > 5 THEN
    CALL invalid_proc_rarity_must_be_1_to_5();
  END IF;
END$$

-- ===============================================================
-- TRIGGER 3: Log all changes to tier_data in audit table
-- ===============================================================
-- Purpose: Create an audit trail of who/what/when tier_data changes occur
-- Benefit: Full traceability for debugging, compliance, and detecting unauthorized changes
-- Use case: Audit logging, compliance reporting, debugging tier list changes
--
-- First, create the audit table if it doesn't exist:
--
CREATE TABLE IF NOT EXISTS audit_tier_data (
  id INT AUTO_INCREMENT PRIMARY KEY,
  action VARCHAR(10),
  tier_data_id INT,
  tier_category_id INT,
  character_id INT,
  old_rank VARCHAR(5),
  new_rank VARCHAR(5),
  old_sort_order INT,
  new_sort_order INT,
  changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  comment VARCHAR(255)
)$$

-- Syntax explanation for trigger creation:
-- CREATE TRIGGER trg_log_tier_data_changes
--   - Trigger name
-- AFTER INSERT OR UPDATE OR DELETE ON tier_data
--   - Fires after DML (INSERT, UPDATE, DELETE); multiple events can be specified
-- FOR EACH ROW BEGIN
--   - Body for each affected row
-- INSERT INTO audit_tier_data (...) VALUES (...);
--   - Records the change in the audit table
-- OLD.column refers to the old row values (before change)
-- NEW.column refers to the new row values (after change)
--
CREATE TRIGGER trg_log_tier_data_changes
AFTER INSERT ON tier_data
FOR EACH ROW
BEGIN
  INSERT INTO audit_tier_data (action, tier_data_id, tier_category_id, character_id, new_rank, new_sort_order, comment)
  VALUES ('INSERT', NEW.id, NEW.tier_category_id, NEW.character_id, NEW.rank, NEW.sort_order, 'New tier assignment');
END$$

CREATE TRIGGER trg_log_tier_data_update
AFTER UPDATE ON tier_data
FOR EACH ROW
BEGIN
  INSERT INTO audit_tier_data (action, tier_data_id, tier_category_id, character_id, old_rank, new_rank, old_sort_order, new_sort_order, comment)
  VALUES ('UPDATE', NEW.id, NEW.tier_category_id, NEW.character_id, OLD.rank, NEW.rank, OLD.sort_order, NEW.sort_order, 'Tier rank/order changed');
END$$

CREATE TRIGGER trg_log_tier_data_delete
AFTER DELETE ON tier_data
FOR EACH ROW
BEGIN
  INSERT INTO audit_tier_data (action, tier_data_id, tier_category_id, character_id, old_rank, old_sort_order, comment)
  VALUES ('DELETE', OLD.id, OLD.tier_category_id, OLD.character_id, OLD.rank, OLD.sort_order, 'Tier assignment removed');
END$$

-- ===============================================================
-- TRIGGER 4: Prevent duplicate character assignment in same tier category
-- ===============================================================
-- Purpose: Ensure a character appears only once per tier category
-- Benefit: Prevents data inconsistency; a character cannot be in two ranks within one category
-- Use case: Data integrity, preventing duplicate entries
--
-- Syntax explanation:
-- DECLARE v_count INT;
--   - Local variable to hold count result
-- SELECT COUNT(*) INTO v_count FROM tier_data WHERE ...;
--   - Queries for existing assignments matching the same category and character
-- IF v_count > 0 THEN CALL invalid_proc(...);
--   - If duplicate exists, call non-existent procedure to trigger error
--
CREATE TRIGGER trg_prevent_duplicate_tier_assignment
BEFORE INSERT ON tier_data
FOR EACH ROW
BEGIN
  DECLARE v_count INT DEFAULT 0;
  SELECT COUNT(*) INTO v_count
  FROM tier_data
  WHERE tier_category_id = NEW.tier_category_id AND character_id = NEW.character_id;
  
  IF v_count > 0 THEN
    CALL invalid_proc_duplicate_character_in_category();
  END IF;
END$$

-- ===============================================================
-- TRIGGER 5: Enforce rank values must be SS, S, A, B, C, or D
-- ===============================================================
-- Purpose: Validate that rank column only accepts predefined tier values
-- Benefit: Prevents typos and invalid ranks; ensures consistency across all tier data
-- Use case: Data validation, business rule enforcement
--
-- Syntax explanation:
-- IF NEW.rank NOT IN ('SS', 'S', 'A', 'B', 'C', 'D') THEN
--   - Checks if rank is not in the set of valid values
-- CALL invalid_proc_invalid_rank();
--   - Triggers error if rank is invalid
--
CREATE TRIGGER trg_enforce_rank_values
BEFORE INSERT ON tier_data
FOR EACH ROW
BEGIN
  IF NEW.rank NOT IN ('SS', 'S', 'A', 'B', 'C', 'D') THEN
    CALL invalid_proc_invalid_rank();
  END IF;
END$$

CREATE TRIGGER trg_enforce_rank_values_update
BEFORE UPDATE ON tier_data
FOR EACH ROW
BEGIN
  IF NEW.rank NOT IN ('SS', 'S', 'A', 'B', 'C', 'D') THEN
    CALL invalid_proc_invalid_rank();
  END IF;
END$$

-- ===============================================================
-- TRIGGER 6: Auto-increment sort_order within a rank group
-- ===============================================================
-- Purpose: Automatically assign the next available sort_order when inserting into a rank
-- Benefit: Simplifies insertion logic; no need to calculate next order in application
-- Use case: Automatic ordering, streamlined insertions
--
-- Syntax explanation:
-- SELECT COALESCE(MAX(sort_order), 0) + 1 INTO v_next_order FROM tier_data WHERE ...;
--   - Finds the highest sort_order in the group and adds 1 (or starts at 1 if no rows)
-- COALESCE(x, 0) returns 0 if x is NULL (handles empty groups)
-- SET NEW.sort_order = v_next_order;
--   - Assigns the calculated value to the new row
--
CREATE TRIGGER trg_auto_increment_sort_order
BEFORE INSERT ON tier_data
FOR EACH ROW
BEGIN
  DECLARE v_next_order INT DEFAULT 1;
  
  IF NEW.sort_order IS NULL OR NEW.sort_order = 0 THEN
    SELECT COALESCE(MAX(sort_order), 0) + 1 INTO v_next_order
    FROM tier_data
    WHERE tier_category_id = NEW.tier_category_id AND rank = NEW.rank;
    
    SET NEW.sort_order = v_next_order;
  END IF;
END$$

-- ===============================================================
-- TRIGGER 7: Audit element deletions with reference check
-- ===============================================================
-- Purpose: Log when an element is deleted and record how many characters referenced it
-- Benefit: Traceability of deletions; helps detect data cleanup issues
-- Use case: Audit trail, data quality monitoring
--
CREATE TABLE IF NOT EXISTS audit_element_deletions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  element_id INT,
  element_name VARCHAR(50),
  game_id INT,
  referenced_characters INT,
  deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)$$

-- Syntax explanation:
-- CREATE TRIGGER trg_audit_element_deletion
--   - Trigger for element deletion auditing
-- BEFORE DELETE ON elements
--   - Fires before DELETE is executed
-- SELECT COUNT(*) INTO v_ref_count FROM characters WHERE element_id = OLD.id;
--   - Checks how many characters reference the deleted element using OLD (pre-deletion) values
-- INSERT INTO audit_element_deletions (...) VALUES (OLD.id, OLD.element_name, ...);
--   - Logs the deletion for audit purposes
--
CREATE TRIGGER trg_audit_element_deletion
BEFORE DELETE ON elements
FOR EACH ROW
BEGIN
  DECLARE v_ref_count INT DEFAULT 0;
  
  SELECT COUNT(*) INTO v_ref_count
  FROM characters
  WHERE element_id = OLD.id;
  
  INSERT INTO audit_element_deletions (element_id, element_name, game_id, referenced_characters)
  VALUES (OLD.id, OLD.element_name, OLD.game_id, v_ref_count);
END$$

-- ===============================================================
-- TRIGGER 8: Auto-update game updated_at when any character changes
-- ===============================================================
-- Purpose: Update game's updated_at when a character within that game is modified
-- Benefit: Tracks which games have active changes; useful for "recently updated" lists
-- Use case: Dashboard sorting, activity tracking
--
-- Syntax explanation:
-- AFTER UPDATE ON characters
--   - Fires after a character UPDATE completes successfully
-- UPDATE games SET updated_at = NOW() WHERE id = NEW.game_id;
--   - Updates the parent game record using NEW.game_id (the character's game)
-- This creates a cascade of timestamps from child to parent
--
CREATE TRIGGER trg_cascade_update_game_on_character_change
AFTER UPDATE ON characters
FOR EACH ROW
BEGIN
  UPDATE games SET updated_at = NOW() WHERE id = NEW.game_id;
END$$

DELIMITER ;

-- ===============================================================
-- USER MANAGEMENT
-- ===============================================================

-- Drop existing users if present
DROP USER IF EXISTS 'nevin'@'localhost';
DROP USER IF EXISTS 'adit'@'localhost';
DROP USER IF EXISTS 'radith'@'localhost';
DROP USER IF EXISTS 'nayaka'@'localhost';
DROP USER IF EXISTS 'rizqi'@'localhost';

-- Create 5 users for team members
CREATE USER 'nevin'@'localhost' IDENTIFIED BY 'nevin_pass_123';
CREATE USER 'adit'@'localhost' IDENTIFIED BY 'adit_pass_123';
CREATE USER 'radith'@'localhost' IDENTIFIED BY 'radith_pass_123';
CREATE USER 'nayaka'@'localhost' IDENTIFIED BY 'nayaka_pass_123';
CREATE USER 'rizqi'@'localhost' IDENTIFIED BY 'rizqi_pass_123';

-- ===============================================================
-- PRIVILEGE ASSIGNMENT
-- ===============================================================

-- 1) Nevin: Full administrative access (SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, etc.)
GRANT ALL PRIVILEGES ON tier_list_gacha.* TO 'nevin'@'localhost';

-- 2) Radith: Full administrative access
GRANT ALL PRIVILEGES ON tier_list_gacha.* TO 'radith'@'localhost';

-- 3) Adit: View and insert data only (SELECT, INSERT)
GRANT SELECT, INSERT ON tier_list_gacha.* TO 'adit'@'localhost';

-- 4) Nayaka: View and insert data only (SELECT, INSERT)
GRANT SELECT, INSERT ON tier_list_gacha.* TO 'nayaka'@'localhost';

-- 5) Rizqi: Update and delete data (UPDATE, DELETE)
-- Note: Rizqi cannot INSERT or SELECT (read), only modify/delete existing records
GRANT UPDATE, DELETE ON tier_list_gacha.* TO 'rizqi'@'localhost';

-- Apply privilege changes
FLUSH PRIVILEGES;

-- ===============================================================
-- Summary of User Privileges
-- ===============================================================
-- User: nevin      | Privileges: ALL (SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, ALTER, etc.)
-- User: radith     | Privileges: ALL (SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, ALTER, etc.)
-- User: adit       | Privileges: SELECT, INSERT (View and add data)
-- User: nayaka     | Privileges: SELECT, INSERT (View and add data)
-- User: rizqi      | Privileges: UPDATE, DELETE (Modify and remove data only)
-- ===============================================================
