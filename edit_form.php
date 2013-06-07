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
require_once('../../config.php');
require_once($CFG->libdir . '/formslib.php');

class link_edit_form extends moodleform {
    protected $isadding;
    protected $id;
    protected $linktext = '';
    protected $url = '';
    protected $notes = '';
    protected $defaultshow = true;
    protected $newwindow = 1;
    protected $department = 'All';
    
    
    function __construct($actionurl, $isadding, $id) {
        $this->isadding = $isadding;
        $this->id = $id;
        parent::moodleform($actionurl);
    }

    function definition() {
        global $DB;
        $mform =& $this->_form;

        // Then show the fields about where this block appears.
        $mform->addElement('header', 'editlinkheader', get_string('managelinks', 'block_links'));

        $mform->addElement('text', 'linktext', get_string('linktext', 'block_links'), array('size' => 60));
        $mform->setType('linktext', PARAM_TEXT);
        $mform->addRule('linktext', null, 'required');
        
        $mform->addElement('text', 'url', get_string('url', 'block_links'), array('size' => 60));
        $mform->setType('url', PARAM_URL);
        $mform->addRule('url', null, 'required');        

        $mform->addElement('header', 'additionalsettings', get_string('additionalsettings', 'block_links'));
        
        $mform->addElement('text', 'notes', get_string('notes', 'block_links'), array('size' => 60));
        $mform->setType('notes', PARAM_TEXT);
        
        $mform->addElement('select', 'defaultshow', get_string('defaultshow', 'block_links'), array(1=>get_string('yes'),0=>get_string('no')));
        $mform->setType('defaultshow', PARAM_INT);
        $mform->setDefault('defaultshow',$this->defaultshow);

        $options = array();
        $options['All'] = get_string('all', 'block_links');
    
        $sql = "SELECT DISTINCT department FROM {user} ORDER BY department";
        $categories = $DB->get_records_sql($sql);
        foreach ($categories as $category) {
            if (!empty($category->department)) {
                $options[$category->department] = $category->department;
            }
        }
        $mform->addElement('select', 'department', get_string('category', 'block_links'), $options);
        $mform->setType('department', PARAM_ALPHANUMEXT);
        $mform->setDefault('department','All');
        
        // Hidden.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true);
    }
    
}