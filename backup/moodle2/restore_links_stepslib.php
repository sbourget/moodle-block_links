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
 * This block generates a simple list of links based on the users
 * department association
 *
 * @package   block_links
 * @copyright 2013 Stephen Bourget
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the restore steps that wll be used by the restore_links_block_task
 */

/**
 * Define the complete links structure for restore
 */
class restore_links_block_structure_step extends restore_structure_step {

    protected function define_structure() {

        $paths = array();

        $paths[] = new restore_path_element('block', '/block', true);
        $paths[] = new restore_path_element('link', '/block/link');

        return $paths;
    }

    public function process_block($data) {
        global $DB;

        $data = (object)$data;

        // For any reason (non multiple, dupe detected...) block not restored, return.
        if (!$this->task->get_blockid()) {
            return;
        }

        // Iterate over all the link elements, creating them if needed.
        if (isset($data->link)) {
            foreach ($data->link as $link) {
                $link = (object)$link;
                // Look if the same link is available by url.
                $select = 'url = :url AND linktext = :linktext';
                $params = array('url' => $link->url, 'linktext' => $link->linktext);

                // The link already exists, use it.
                if ($linkid = $DB->get_field_select('block_links', 'id', $select, $params, IGNORE_MULTIPLE)) {
                    // The record exists.
                    unset($linkid);

                } else {
                    // The link doesn't exist, create it.
                    $linkid = $DB->insert_record('block_links', $link);

                }
            }
        }
    }
}