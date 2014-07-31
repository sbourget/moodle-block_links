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
 * @copyright 2010 Stephen Bourget
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    // Load Constants
    require_once($CFG->dirroot.'/blocks/links/lib.php');
    
    $settings->add(new admin_setting_configtext('block_links_title', get_string('blocktitle', 'block_links'),
                       get_string('blocktitle_desc', 'block_links'), get_string('blockname', 'block_links'), PARAM_TEXT));

    $link ='<a href="'.$CFG->wwwroot.'/blocks/links/config_global_action.php">'.get_string('managelinks', 'block_links').'</a>';
    $settings->add(new admin_setting_heading('block_links_addheading', '', $link));
    
    $options = array();
    $options[BLOCK_LINKS_INSTITUTION] = get_string('institution', 'moodle');
    $options[BLOCK_LINKS_DEPARTMENT] = get_string('department', 'moodle');
    $options[BLOCK_LINKS_CITY] = get_string('city', 'moodle');
    $options[BLOCK_LINKS_COUNTRY] = get_string('country', 'moodle');

    $settings->add(new admin_setting_configselect('block_links_profile_field', get_string('profilefield', 'block_links'),
                   get_string('profilefield_desc', 'block_links'), BLOCK_LINKS_INSTITUTION, $options));
}