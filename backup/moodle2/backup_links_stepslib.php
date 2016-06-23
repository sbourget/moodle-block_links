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
 * This block generates a simple list of links based on the users profile.
 *
 * @package   block_links
 * @copyright 2013 Stephen Bourget
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Define the complete block structure for backup, with file and id annotations.
 *
 * @package   block_links
 * @copyright 2013 Stephen Bourget
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_links_block_structure_step extends backup_block_structure_step {

    /**
     * Define the structure for the links block.
     * @return void
     */
    protected function define_structure() {

        // Define each element separated.

        $link = new backup_nested_element('link', null, array(
            'id', 'linktext', 'url', 'notes', 'defaultshow', 'department'));

        // Define sources.

        $link->set_source_sql("SELECT * FROM {block_links}", array());

        // Annotations (none).

        // Return the root element (links), wrapped into standard block structure.
        return $this->prepare_block_structure($link);
    }
}
