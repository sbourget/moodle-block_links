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
 * @copyright 2006 Sean Madden - RIT for Goffstown School District
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * 
 * Reference block by Tom Flannaghan and Andrew Walker - Alton College
 * Modified by Sean Madden - RIT for Goffstown School District (for Moodle 1.6)
 * Updated for Moodle 1.8+ by Stephen Bourget - Goffstown School District
 */

require_once($CFG->dirroot.'/blocks/links/lib.php');
    
    class block_links extends block_list {
        function init() {
            $this->title = get_string('links','block_links');
        }
        
        function specialization() {
            global $CFG;
            
            $this->title = !empty($CFG->block_links_title) ? $CFG->block_links_title : $this->title;
        }
        
        function get_content() {
            global $CFG, $DB;
            if ($this->content !== NULL) {
                return $this->content;
            }
            
            $this->content = new stdClass();
            $this->content->items = array();
            $this->content->icons = array();
            $this->content->footer = '';
            
            $rs = $DB->get_records('block_links', array('defaultshow'=>'1'), 'linktext');
            if (!is_array($rs)) {
                $rs = array();
            }
            
            $link = new stdClass();
            foreach ($rs as $link) {
//                $temp = 'allow_' . $link->id;
//                
//                if (isset($this->config->$temp)) {
//                    if ($this->config->$temp == 1) {
//                        $this->add_link($link);
//                    }
//                } else if (($link->department == 'All') || ($link->department == $_SESSION['USER']->department)) {
//                    $this->add_link($link);
//                }
                if ($link->department == 'All') {
                    $this->add_link($link);
                } else {
                    // Check to see if the user should be able to view it.
                    switch($CFG->block_links_profile_field) {
                        case BLOCK_LINKS_INSTITUTION:
                            if ($link->department == $_SESSION['USER']->institution) {
                                $this->add_link($link);
                            }
                            break;
                        
                        case BLOCK_LINKS_DEPARTMENT:
                            if ($link->department == $_SESSION['USER']->department) {
                                $this->add_link($link);
                            }
                            break;
                        
                        case BLOCK_LINKS_CITY:
                            if ($link->department == $_SESSION['USER']->city) {
                                $this->add_link($link);
                            }
                            break;
                        
                        case BLOCK_LINKS_COUNTRY:
                            if ($link->department == $_SESSION['USER']->country) {
                                $this->add_link($link);
                            }
                            break;
                        
                        default:
                    }
                }
            }

         if(empty($this->instance->pinned)) {
             $context = context_block::instance($this->instance->id);
         } 
         else {
             $context = context_system::instance(); //Pinned blocks do not have own context
         }
         if((has_capability('moodle/site:manageblocks', $context)) && (has_capability('block/links:managelinks', $context))) {
                $link->url = $CFG->wwwroot."/blocks/links/config_global_action.php";
                $link->linktext = "<b>".get_string('managelinks', 'block_links')."</b>";
                $link->notes = "";
                $this->add_link($link);
        }
            
            return $this->content;
        }
        
        function add_link($link) {
            global $CFG, $OUTPUT;
            
            $target = !empty($CFG->block_links_window) ? ' target="_blank"' : '';
        
            $this->content->items[] = '<a href="' . $link->url.'"' . $target . '>'. $link->linktext . '</a> <em>' .$link->notes . '</em>';
            $this->content->icons[] = '<img src="'.$OUTPUT->pix_url('web','block_links').'" height="16" width="16" alt="" />';
        }

        function has_config() {
            return true;
        }

    }
