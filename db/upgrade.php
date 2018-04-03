<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Upgrade code for install
 *
 * @package    block_links
 * @copyright  2016 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * upgrade this block instance - this function could be skipped but it will be needed later
 * @param int $oldversion The old version of the links block
 * @return bool
 */
function xmldb_block_links_upgrade($oldversion=0) {

    global $CFG, $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2016060100) {
        // Upgrade $CFG properties to use config_plugins.
        if (isset($CFG->block_links_profile_field)) {
            set_config('profile_field', $CFG->block_links_profile_field, 'block_links');
            unset_config('block_links_profile_field');
        }
        if (isset($CFG->block_links_window)) {
            set_config('link_target', $CFG->block_links_window, 'block_links');
            unset_config('block_links_window');
        }
        if (isset($CFG->block_links_title)) {
            set_config('default_title', $CFG->block_links_title, 'block_links');
            unset_config('block_links_title');
        }

        upgrade_block_savepoint(true, 2016060100, 'links');
    }

    if ($oldversion < 2018040300) {

        // Changing precision of field url on table block_links to (255).
        $table = new xmldb_table('block_links');
        $field = new xmldb_field('url', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'linktext');

        // Launch change of precision for field url.
        $dbman->change_field_precision($table, $field);

        // Links savepoint reached.
        upgrade_block_savepoint(true, 2018040300, 'links');
    }

    if ($oldversion < 2018040301) {

        // Changing precision of field notes on table block_links to (255).
        $table = new xmldb_table('block_links');
        $field = new xmldb_field('notes', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'url');

        // Launch change of precision for field notes.
        $dbman->change_field_precision($table, $field);

        // Links savepoint reached.
        upgrade_block_savepoint(true, 2018040301, 'links');
    }

    if ($oldversion < 2018040302) {

        // Changing precision of field department on table block_links to (255).
        $table = new xmldb_table('block_links');
        $field = new xmldb_field('department', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'defaultshow');

        // Launch change of precision for field department.
        $dbman->change_field_precision($table, $field);

        // Links savepoint reached.
        upgrade_block_savepoint(true, 2018040302, 'links');
    }

    if ($oldversion < 2018040303) {

        // Changing precision of field linktext on table block_links to (255).
        $table = new xmldb_table('block_links');
        $field = new xmldb_field('linktext', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'id');

        // Launch change of precision for field linktext.
        $dbman->change_field_precision($table, $field);

        // Links savepoint reached.
        upgrade_block_savepoint(true, 2018040303, 'links');
    }

    // Finally, return result.

    return true;
}
