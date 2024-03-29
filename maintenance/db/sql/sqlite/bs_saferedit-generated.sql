-- This file is automatically generated using maintenance/generateSchemaSql.php.
-- Source: extensions/BlueSpiceSaferEdit/maintenance/db/sql/bs_saferedit.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TABLE /*_*/bs_saferedit (
  se_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  se_user_name VARCHAR(255) NOT NULL,
  se_page_title BLOB NOT NULL,
  se_page_namespace INTEGER DEFAULT 0 NOT NULL,
  se_edit_section INTEGER DEFAULT -1 NOT NULL,
  se_timestamp BLOB NOT NULL
);
