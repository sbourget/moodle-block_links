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
require_once($CFG->dirroot . '/blocks/links/backup/moodle2/backup_links_stepslib.php'); // We have structure steps.

/**
 * links backup task that provides all the settings and steps to perform one complete backup of the block.
 *
 * @package   block_links
 * @copyright 2013 Stephen Bourget
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_links_block_task extends backup_block_task {

    /**
     * Define (add) particular settings this block can have
     */
    protected function define_my_settings() {
    }

    /**
     * Define (add) particular steps this block can have
     */
    protected function define_my_steps() {
        // Links has one structure step.
        $this->add_step(new backup_links_block_structure_step('links_structure', 'links.xml'));
    }

    /**
     * Define the associated file areas
     */
    public function get_fileareas() {
        return array(); // No associated fileareas.
    }

    /**
     * Define special handling of configdata.
     */
    public function get_configdata_encoded_attributes() {
        return array(); // No special handling of configdata.
    }

    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     */
    static public function encode_content_links($content) {
        return $content; // No special encoding of links.
    }
}
