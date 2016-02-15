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
require_once('edit_form.php');

$id = optional_param('id', 0, PARAM_INT); // 0 means create new link.

require_login();
$context = context_system::instance();
if ((!has_capability('moodle/site:manageblocks', $context)) || (!has_capability('block/links:managelinks', $context))) {
    print_error('accessdenied', 'block_links');
}
$PAGE->set_context($context);
$returnurl = new moodle_url('/blocks/links/config_global_action.php', array());
$PAGE->set_url('/blocks/links/edit.php', array());
$PAGE->set_pagelayout('standard');
if ($id > 0) {
    $isadding = false;
    $record = $DB->get_record('block_links', array('id' => $id), '*', MUST_EXIST);
} else {
    $isadding = true;
    $record = new stdClass;
}
$mform = new link_edit_form($PAGE->url, $isadding, $id);
$mform->set_data($record);

if ($mform->is_cancelled()) {

    redirect($returnurl);

} else if ($data = $mform->get_data()) {

    if ($isadding) {
        $DB->insert_record('block_links', $data);
    } else {
        $data->id = $id;
        $DB->update_record('block_links', $data);
    }

    redirect($returnurl);

} else {
    if ($isadding) {
        $strtitle = get_string('addlink', 'block_links');
    } else {
        $strtitle = get_string('editlink', 'block_links');
    }

    $strmanagelinks = get_string('managelinks', 'block_links');
    $PAGE->navbar->add(get_string('blocks'));
    $PAGE->navbar->add(get_string('pluginname', 'block_links'), $returnurl);
    $PAGE->navbar->add($strmanagelinks);
    $PAGE->set_title($strmanagelinks);
    $PAGE->set_heading(format_string($strmanagelinks));

    $PAGE->set_title($strtitle);
    $PAGE->set_heading($strtitle);

    echo $OUTPUT->header();
    echo $OUTPUT->heading($strtitle, 2);

    $mform->display();

    echo $OUTPUT->footer();
}
