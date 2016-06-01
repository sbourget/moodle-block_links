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
$id = required_param('id', PARAM_INT);
$record = $DB->get_record('block_links', array('id' => $id), '*', MUST_EXIST);
$context = context_system::instance();
$params = array('context' => $context, 'objectid' => $id);
$event = \block_links\event\link_followed::create($params);
$event->trigger();
redirect($record->url);



