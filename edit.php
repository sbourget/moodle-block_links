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
require_once('../../config.php');
require_once('edit_form.php');

$id = optional_param('id', 0, PARAM_INT); // 0 means create new link.

require_login();
$context = context_system::instance();
if ((!has_capability('moodle/site:manageblocks', $context)) || (!has_capability('block/links:managelinks', $context))) {
    throw new moodle_exception('accessdenied', 'block_links');
}
$PAGE->set_context($context);
$returnurl = new moodle_url('/blocks/links/config_global_action.php', array());
$PAGE->set_url('/blocks/links/edit.php', array());
$PAGE->set_pagelayout('standard');
if ($id > 0) {
    // Updating an existing record.
    $strtitle = get_string('editlink', 'block_links');

    $mform = new link_edit_form($PAGE->url, false, $id);
    $record = $DB->get_record('block_links', array('id' => $id), '*', MUST_EXIST);
    $mform->set_data($record);
} else {
    // Adding a new record.
    $strtitle = get_string('addlink', 'block_links');

    $mform = new link_edit_form($PAGE->url, true, $id);
    $record = new stdClass;
    $record->id = 0;
    $mform->set_data($record);
}
if ($mform->is_cancelled()) {

    redirect($returnurl);

} else if ($data = $mform->get_data()) {

    if ($data->id == 0) {
        $id = $DB->insert_record('block_links', $data);
        // Trigger event about adding the link.
        $params = array('context' => $context, 'objectid' => $id);
        $event = \block_links\event\link_added::create($params);
        $event->add_record_snapshot('block_links', $data);
        $event->trigger();
    } else if (isset($data->id) && (int)$data->id > 0) {
        $id = $DB->update_record('block_links', $data);
        // Trigger event about updating the link.
        $params = array('context' => $context, 'objectid' => $data->id);
        $event = \block_links\event\link_updated::create($params);
        $event->add_record_snapshot('block_links', $data);
        $event->trigger();
    }

    redirect($returnurl);

} else {

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
