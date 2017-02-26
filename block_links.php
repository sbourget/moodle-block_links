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
 * @copyright 2006 Sean Madden - RIT for Goffstown School District
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Reference block by Tom Flannaghan and Andrew Walker - Alton College
 * Modified by Sean Madden - RIT for Goffstown School District (for Moodle 1.6)
 * Updated for Moodle 1.8+ by Stephen Bourget - Goffstown School District
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/blocks/links/lib.php');

/**
 * Block links class definition.
 *
 * This block can be added to a any page to display of list of
 * hyperlinks based on the users department, institution, or profile settings.
 *
 * @package   block_links
 * @copyright  2015 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_links extends block_list {

    /**
     * Core function used to initialize the block.
     */
    public function init() {
        $this->title = get_string('links', 'block_links');
    }

    /**
     * Allows for config setting to modify this block.
     */
    public function specialization() {

        $blockconfig = get_config('block_links');
        if (!empty($blockconfig->default_title)) {
            $this->title = $blockconfig->default_title;
        }
    }

    /**
     * Used to generate the content for the block.
     * @return string
     */
    public function get_content() {
        global $DB, $OUTPUT;
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $rs = $DB->get_records('block_links', array('defaultshow' => BLOCK_LINKS_SHOWLINK), 'linktext');
        if (!is_array($rs)) {
            $rs = array();
        }

        $link = new stdClass();
        foreach ($rs as $link) {
            if (block_links_check_permissions($link)) {
                // Does the user have permission, or is it viewable to all?
                $this->add_link($link);
            }
        }

        if (empty($this->instance->pinned)) {
            $context = context_block::instance($this->instance->id);
        } else {
            $context = context_system::instance(); // Pinned blocks do not have own context.
        }
        if ((has_capability('moodle/site:manageblocks', $context)) && (has_capability('block/links:managelinks', $context))) {
            $link->url = new moodle_url('/blocks/links/config_global_action.php');
            $link->linktext = html_writer::tag('span', get_string('managelinks', 'block_links'), array('class' => 'links-bold'));
            $this->content->items[] = html_writer::tag('a', $link->linktext, array('href' => $link->url));
            $this->content->icons[] = html_writer::empty_tag('img',
                    array('src' => $OUTPUT->pix_url('web', 'block_links'), 'class' => 'icon', 'alt' => ''));
        }

        return $this->content;
    }

    /**
     * Helper function to add links to a page
     * @param stdClass $link
     */
    private function add_link($link) {
        global $OUTPUT;
        $blockconfig = get_config('block_links');

        $url = new moodle_url('/blocks/links/follow.php', array('id' => $link->id));
        $linktext = html_writer::tag('a', $link->linktext, array('href' => $url, 'target' => $blockconfig->link_target));
        if (!empty($link->notes)) {
            $linktext .= html_writer::tag('span', $link->notes, array('class' => 'links-italic'));
        }
        $this->content->items[] = $linktext;
        $this->content->icons[] = html_writer::empty_tag('img',
                array('src' => $OUTPUT->pix_url('web', 'block_links'), 'class' => 'icon'));

    }

    /**
     * Core function used to identify if the block has a config page.
     */
    public function has_config() {
        return true;
    }

}
