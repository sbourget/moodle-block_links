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
require_once('../../config.php');
require_once($CFG->libdir . '/tablelib.php');
global $DB;

$delete = optional_param('delete', 0, PARAM_INT);

require_login();
$context = context_system::instance();
if ((!has_capability('moodle/site:manageblocks', $context)) || (!has_capability('block/links:managelinks', $context))) {
    print_error('accessdenied', 'block_links');
}

$strmanagelinks = get_string('managelinks', 'block_links');

// Print the header.
$urlparams = array();
$baseurl = new moodle_url('/blocks/links/config_global_action.php', $urlparams);
$PAGE->set_url($baseurl);
$PAGE->set_context($context);
$PAGE->navbar->add(get_string('blocks'));
$PAGE->navbar->add(get_string('pluginname', 'block_links'), $baseurl);
$PAGE->navbar->add($strmanagelinks);
$PAGE->set_title($strmanagelinks);
$PAGE->set_heading(format_string($strmanagelinks));
$PAGE->set_pagelayout('standard');

if ($delete != -1) {
    $DB->delete_records('block_links', array('id' => $delete));
}

$rs = $DB->get_records('block_links', null, 'linktext');
if (!is_array($rs)) {
    $rs = array();
}

// Check to see if we have any records.
if (count($rs) == 0) {
    $add = 1;
}

echo $OUTPUT->header();

echo html_writer::start_tag('div', array('class' => 'content'));
echo html_writer::tag('h2', $strmanagelinks, array('class' => 'main'));

// Generate the table.
echo html_writer::start_tag('form', array('method' => 'post', 'action' => $baseurl));

$table = new flexible_table('links-administration');

$table->define_columns(array('linktext', 'url', 'notes', 'defaultshow', 'category', 'actions'));
$table->define_headers(array(get_string('linktext', 'block_links'),
                             get_string('url', 'block_links'),
                             get_string('notes', 'block_links'),
                             get_string('defaultshow', 'block_links'),
                             get_string('category', 'block_links'),
                             get_string('actions', 'moodle')));
$table->define_baseurl($baseurl);

$table->set_attribute('cellspacing', '0');
$table->set_attribute('id', 'links');
$table->set_attribute('class', 'generaltable generalbox');
$table->column_class('linktext', 'linktext');
$table->column_class('url', 'url');
$table->column_class('notes', 'notes');
$table->column_class('defaultshow', 'defaultshow');
$table->column_class('category', 'category');
$table->column_class('actions', 'actions');

$table->setup();

foreach ($rs as $index => $link) {

    if ($link->defaultshow == '1') {
        $show = '<img src="'. $OUTPUT->pix_url('clear', 'block_links') .'" height="10" width="10" alt="'.get_string('yes').'" title="'.get_string('yes').'" />'."\n";
    } else {
        $show = '<img src="'. $OUTPUT->pix_url('delete', 'block_links') .'" height="11" width="11" alt="'.get_string('no').'" title="'.get_string('no').'" />'."\n";
    }

    $editurl = new moodle_url('/blocks/links/edit.php', array('id' => $link->id));
    $editaction = $OUTPUT->action_icon($editurl, new pix_icon('t/edit', get_string('edit')));

    $deleteurl = new moodle_url('/blocks/links/config_global_action.php', array('delete' => $link->id, 'sesskey' => sesskey()));
    $deleteicon = new pix_icon('t/delete', get_string('delete'));
    $deleteaction = $OUTPUT->action_icon($deleteurl, $deleteicon, new confirm_action(get_string('deletelinkconfirm', 'block_links')));
    $icons = $editaction . ' ' . $deleteaction;

    $table->add_data(array($link->linktext,
                               html_writer::link($link->url, $link->url, array('target' => '_blank')),
                               $link->notes,
                               $show,
                               $link->department,
                               $icons));
}

$table->print_html();

echo html_writer::end_tag('form');
echo html_writer::start_tag('div', array('class' => 'actionbuttons'));

echo html_writer::empty_tag('hr', array());
$addurl = new moodle_url('/blocks/links/edit.php', array());
echo $OUTPUT->single_button($addurl, get_string('addlink', 'block_links'), 'get');
echo html_writer::end_tag('div');
echo html_writer::end_tag('div');

echo $OUTPUT->footer();
