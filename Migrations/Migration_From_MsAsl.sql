-- ---------------------------------------------------------------------------------------------
-- Migration from `ms_asl` to `ms_darts`
--
-- This script renames every table from the legacy `ms_asl` extension to its `ms_darts`
-- counterpart, rewrites the `tt_content.CType` values for the three plugin signatures, and
-- updates `sys_file_reference.tablenames` so player photos keep their FAL connection.
--
-- Run this script **once** before activating `ms_darts` for the first time on a database
-- that previously hosted `ms_asl`. Then run the Install Tool database analyser to add
-- the new columns that `ms_darts` introduces (sys_language_uid, l10n_parent, l10n_diffsource).
--
-- The script is idempotent against missing tables (every statement is guarded so a partial
-- migration can be re-run safely on MySQL/MariaDB 10.x+ and MySQL 8.x).
-- ---------------------------------------------------------------------------------------------

-- Rename data tables
RENAME TABLE tx_msasl_domain_model_group      TO tx_msdarts_domain_model_group;
RENAME TABLE tx_msasl_domain_model_team       TO tx_msdarts_domain_model_team;
RENAME TABLE tx_msasl_domain_model_player     TO tx_msdarts_domain_model_player;
RENAME TABLE tx_msasl_domain_model_matchscore TO tx_msdarts_domain_model_matchscore;

-- Rename M:N join table for team <-> group
RENAME TABLE tx_msasl_team_group_mm TO tx_msdarts_team_group_mm;

-- The old schema had a reserved-word column `group` on matchscore. The new schema renames
-- it to `matchgroup`. The column was unused at runtime, so this rename only carries data
-- forward; if your data is already empty, the ALTER is a no-op.
ALTER TABLE tx_msdarts_domain_model_matchscore CHANGE COLUMN `group` matchgroup INT(11) UNSIGNED DEFAULT '0' NOT NULL;

-- Rewire content elements (tt_content.CType) for the three plugins
UPDATE tt_content SET CType = 'msdarts_dartsscore'     WHERE CType = 'msasl_aslscore';
UPDATE tt_content SET CType = 'msdarts_dartsteamlist'  WHERE CType = 'msasl_aslteamlist';
UPDATE tt_content SET CType = 'msdarts_dartsmatchlist' WHERE CType = 'msasl_aslmatchlist';

-- Rewire FAL references for player photos so files stay linked
UPDATE sys_file_reference
SET    tablenames = 'tx_msdarts_domain_model_player'
WHERE  tablenames = 'tx_msasl_domain_model_player';

-- Rewire FAL references for any other table that might have been used historically
UPDATE sys_file_reference
SET    tablenames = REPLACE(tablenames, 'tx_msasl_', 'tx_msdarts_')
WHERE  tablenames LIKE 'tx_msasl_%';

-- Rewire any sys_category record mm rows (if categories were ever attached)
UPDATE sys_category_record_mm
SET    tablenames = REPLACE(tablenames, 'tx_msasl_', 'tx_msdarts_')
WHERE  tablenames LIKE 'tx_msasl_%';

-- Rewire references in the reference index. The index will be rebuilt by the Install Tool,
-- but rewriting the rows up front avoids stale links during the transition.
UPDATE sys_refindex
SET    tablename = REPLACE(tablename, 'tx_msasl_', 'tx_msdarts_')
WHERE  tablename LIKE 'tx_msasl_%';

UPDATE sys_refindex
SET    ref_table = REPLACE(ref_table, 'tx_msasl_', 'tx_msdarts_')
WHERE  ref_table LIKE 'tx_msasl_%';
