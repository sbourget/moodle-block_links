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
global $USER, $DB;

$save = optional_param('save', null, PARAM_ALPHANUM);   // SAVE option???
$id = optional_param('id', null, PARAM_ALPHANUM);   // id option???
$modify = optional_param('modify', -1, PARAM_INT);
$add = optional_param('add', -1, PARAM_INT);
$delete = optional_param('delete', -1, PARAM_INT);

require_login();
$context = get_context_instance(CONTEXT_SYSTEM);
if((!has_capability('moodle/site:manageblocks', $context)) || (!has_capability('block/links:managelinks', $context))) {
    error('ACCESS DENIED.');
}


if ($delete != -1) {
    $DB->delete_records('block_links', array('id'=> $delete));
}

if (!is_null($save)) {
    
    $itemdata = new stdClass;
    $itemdata->linktext = required_param('linktext');
    $itemdata->url = required_param('url');
    $itemdata->notes = required_param('notes');
    $itemdata->defaultshow = required_param('defaultshow');
    $itemdata->department = required_param('department');
           
    if ($id == 'NEW') {
        $DB->insert_record('block_links', $itemdata);
    } else {
        $itemdata->id = required_param('id');
        $DB->update_record('block_links', $itemdata);
    }
    unset($itemdata);
}


$stradmin = get_string('administration');
$strmodules = get_string('managemodules');
$strblocks = get_string('blocks');
$strmanagelinks = get_string('managelinks', 'block_links');
$strlinks = get_string('links', 'block_links');
$stradd = get_string('addlink', 'block_links');
$navigation = "$stradmin -> $strmodules -> $strblocks -> $strlinks -> $strmanagelinks";

    
$rs = $DB->get_records('block_links');
if (!is_array($rs)) {
    $rs = array();
}

if (count($rs) == 0) {
    $add = 1;
}

$content  = '<div id="content">'."\n".'  <h2 class="main">'.$strmanagelinks.'</h2>'."\n";
$content .= link_table_headings();

$editform = false;
$row = 1;
foreach ($rs as $index => $link) {
    if ($link->id == $modify) {
        $editform = true;
        $content .= link_modify_form($link, $row);
    } else {
        $content .= link_table_row($link, $row);
    }
    $row = 1 - $row;
}
if ($add != -1) {
    $content .= link_modify_form('', $row);
} else {
    $content .= '  <tr>'."\n".'   <td colspan="6" align="center"><hr /><a href="?add=true">'.$stradd.'</a></td>'."\n".'  </tr>'."\n";
}
$content .= '</table>'."\n";

if ($editform || $add != -1) {
    $content = '<form method="post" action="?">' . $content . '</form>'."\n";
}
$content .= '</div>'."\n";

/// Print the header
$PAGE->set_url('/blocks/links/config_global_action.php');
$PAGE->navbar->add($strmanagelinks);
$PAGE->set_title($strmanagelinks);
$PAGE->set_heading(format_string($strmanagelinks));
$PAGE->set_pagelayout('incourse');
echo $OUTPUT->header();


echo $content;
echo $OUTPUT->footer();


/**
 * generates the table headers for the config page
 * @return string HTML
 */
function link_table_headings() {
    
    $strlinktext = get_string('linktext', 'block_links');
    $strurl = get_string('url', 'block_links');
    $strnotes = get_string('notes', 'block_links');
    $strdefaultshow = get_string('defaultshow', 'block_links');
    $strcatagory = get_string('catagory', 'block_links');
    $stractions = get_string('actions', 'block_links');
    
    $content  = '  <table class="generaltable generalbox" align="center" cellpadding="5">'."\n".'    <tr>'."\n";
    $content .= '      <th class="header c0" scope="col">'.$strlinktext.'</th>'."\n";
    $content .= '      <th class="header c1" scope="col">'.$strurl.'</th>'."\n";
    $content .= '      <th class="header c2" scope="col">'.$strnotes.'</th>'."\n";
    $content .= '      <th class="header c3" scope="col">'.$strdefaultshow.'</th>'."\n";
    $content .= '      <th class="header c4" scope="col">'.$strcatagory.'</th>'."\n";
    $content .= '      <th class="header c5" scope="col">'.$stractions.'</th>'."\n";
    $content .= '  </tr>'."\n";
    return $content;
}
/**
 * Generates a single row HTML row for the config page
 * @global object $CFG
 * @global object $OUTPUT
 * @param object $link - object with link db record info
 * @param int $row - row number used for CSS
 * @return string HTML
 */
function link_table_row($link, $row = 0) {
    global $CFG, $OUTPUT;

    $strmodify = get_string('modify', 'block_links');
    $strdelete = get_string('delete', 'block_links');
    $stryes = get_string('yes', 'block_links');
    $strno = get_string('no', 'block_links');

    $content  = '  <tr class="r'.$row.'">'."\n";
    $content .= '    <td>'.$link->linktext.'</td>'."\n";
    $content .= '    <td><a href="'.$link->url.'">'.$link->url.'</a></td>'."\n";
    $content .= '    <td>'.$link->notes.'</td>'."\n";
    $content .= '    <td>';
    if ($link->defaultshow == '1') {
        $content .= '<img src="'. $OUTPUT->pix_url('clear', 'block_links') .'" height="10" width="10" alt="'.$stryes.'" title="'.$stryes.'" />'."\n";
    } else {
        $content .= '<img src="'. $OUTPUT->pix_url('delete', 'block_links') .'" height="11" width="11" alt="'.$strno.'" title="'.$strno.'" />'."\n";
    }
    $content .= '</td>'."\n";          
    $content .= '<td>';
    $content .= $link->department;
    $content .= '</td>';
    $content .= '    <td><a href="?modify='.$link->id.'" title="'.$strmodify.'"><img src="'. $OUTPUT->pix_url('edit', 'block_links') .'" alt="'.$strmodify.'" /></a>';
    $content .= ' <a href="?delete='.$link->id.'" title="'.$strdelete.'"><img src="'. $OUTPUT->pix_url('delete', 'block_links') .'" alt="'.$strdelete.'" /></a></td>'."\n";
    $content .= '  </tr>'."\n";
    return $content;
}


/**
 * Generates the editing form for one of the links
 * @global object $DB
 * @param object $link - object with link db record info
 * @param int $row - row number used for CSS
 * @return string HTML
 * @todo This should be eventually be converted to mforms
 */
function link_modify_form($link = '', $row = 0) {
    global $DB;
    if ($link == '') {
        $link = new stdClass;
        $link->id = 'NEW';
        $link->linktext = '';
        $link->url = '';
        $link->notes = '';
        $link->defaultshow = '1';
        $link->newwindow = '1';
        $link->department = 'All';
        
        $strok = get_string('add');
    } else {
        $strok = get_string('edit');
    }
    
    $strcancel = get_string('cancel');
    
    
    $content  = '  <tr class="r'.$row.'">'."\n";
    $content .= '    <td><input type="text" name="linktext" value="'.$link->linktext.'" /></td>'."\n";
    $content .= '    <td><input type="text" name="url" value="'.$link->url.'" /></td>'."\n";
    $content .= '    <td><input type="text" name="notes" value="'.$link->notes.'" /></td>'."\n";
    $content .= '    <td><input type="hidden" name="defaultshow" value="0" />'."\n";
    $content .= '        <input type="checkbox" name="defaultshow" value="1"';
    
    if ($link->defaultshow == '1') {
        $content .= ' checked="checked"';
    }
    $content .= ' /></td>'."\n";
    $content .= '    <td><select name="department">'."\n";
    $strglobal = get_string('global', 'block_links');
    $content .= '        <option ';
    if ('All' == $link->department){
        $content .= 'selected = "selected"';
    }
    $content .= '>'.$strglobal.'</option>'."\n";

    $sql = "SELECT DISTINCT department FROM {user} ORDER BY department";
    $catagories = $DB->get_records_sql($sql);

    foreach ($catagories as $catagory) {
        if (!empty($catagory->department)) {
            $content .= '        <option value="'.$catagory->department.'" ';
            if ($catagory->department == $link->department){
                $content .= 'selected = "selected"';
            }
            $content .= '>'.$catagory->department.'</option>'."\n";
        }
    }
    
    $content .= '    </select></td>'."\n";
    $content .= '    <td><input type="hidden" name="id" value="'.$link->id.'" />'."\n";
    $content .= '        <input type="submit" name="save" value="'.$strok.'" />'."\n";
    $content .= '        <input type="submit" value="'.$strcancel.'" />'."\n";
    $content .= '    </td>'."\n";
    $content .= '  </tr>'."\n";
    return $content;
}

?>
