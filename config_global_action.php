<?php

require_once('../../config.php');

global $USER;

require_login();
if (!isadmin()) {
    error('ACCESS DENIED.');
}

$modify = optional_param('modify', -1);
$add = optional_param('add', -1);
$delete = optional_param('delete', -1);
if ($delete != -1) {
    delete_records('block_links', 'id', $delete);
}

if (isset($_POST['save'])) {
    
    $itemdata = new stdClass;
    $itemdata->linktext = required_param('linktext');
    $itemdata->url = required_param('url');
    $itemdata->notes = required_param('notes');
    $itemdata->defaultshow = required_param('defaultshow');
    $itemdata->department = required_param('department');
    
    $itemdata = addslashes_object($itemdata);
       
    if ($_POST['id'] == 'NEW') {
        insert_record('block_links', $itemdata);
    } else {
        $itemdata->id = required_param('id');
        update_record('block_links', $itemdata);
    }
    unset($itemdata);
}


$strconfiguration = get_string('configuration');
$stradmin = get_string('admin');
$strmanagelinks = get_string('manage_links', 'block_links');
$strlinks = get_string('links', 'block_links');
$stradd = get_string('addlink', 'block_links');


$navigation = "<a href=\"$CFG->wwwroot/$CFG->admin/index.php\">$stradmin</a> -> ".
    "<a href=\"$CFG->wwwroot/$CFG->admin/configure.php\">$strconfiguration</a> -> $strmanagelinks";
    
$rs = get_records('block_links');
if (!is_array($rs)) {
    $rs = array();
}
$rs = stripslashes_safe($rs);

if (count($rs) == 0) {
    $add = 1;
}

$content  = "<div id=\"content\">\n  <h2 class=\"main\">$strmanagelinks</h2>\n";
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
    $content .= "  <tr>\n   <td colspan=\"6\" align=\"center\"><hr /><a href=\"?add=true\">$stradd</a></td>\n  </tr>\n";
}
$content .= "</table>\n";

if ($editform || $add != -1) {
    $content = "<form method=\"post\" action=\"?\">" . $content . "</form>\n";
}
$content .= "</div>\n";


print_header($strmanagelinks, $strmanagelinks, $navigation);
echo $content;
print_footer();




function link_table_headings() {
    $strlinktext = get_string('linktext', 'block_links');
    $strurl = get_string('url', 'block_links');
    $strnotes = get_string('notes', 'block_links');
    $strdefaultshow = get_string('defaultshow', 'block_links');
    $strcatagory = get_string('catagory', 'block_links');
    $stractions = get_string('actions', 'block_links');
    
    $content  = "  <table class=\"generaltable generalbox\" align=\"center\" cellpadding=\"5\">\n    <tr>\n";
    $content .= "      <th class=\"header c0\" scope=\"col\">$strlinktext</th>\n";
    $content .= "      <th class=\"header c1\" scope=\"col\">$strurl</th>\n";
    $content .= "      <th class=\"header c2\" scope=\"col\">$strnotes</th>\n";
    $content .= "      <th class=\"header c3\" scope=\"col\">$strdefaultshow</th>\n";
    $content .= "      <th class=\"header c4\" scope=\"col\">$strcatagory</th>\n";
    $content .= "      <th class=\"header c5\" scope=\"col\">$stractions</th>\n";
    $content .= "  </tr>\n";
    return $content;
}

function link_table_row($link, $row = 0) {
    global $CFG;

    $strmodify = get_string('modify', 'block_links');
    $strdelete = get_string('delete', 'block_links');
    $stryes = get_string('yes', 'block_links');
    $strno = get_string('no', 'block_links');
    $strglobal = get_string('global', 'block_links');
    
    $content  = "  <tr class=\"r{$row}\">\n";
    $content .= "    <td>$link->linktext</td>\n";
    $content .= "    <td><a href=\"$link->url\">$link->url</a></td>\n";
    $content .= "    <td>$link->notes</td>\n";
            
    $content .= "    <td>";
    if ($link->defaultshow == '1') {
        $content .= "<img src=\"$CFG->pixpath/t/clear.gif\" height=\"10\" width=\"10\" alt=\"$stryes\" title=\"$stryes\" />";
    } else {
        $content .= "<img src=\"$CFG->pixpath/t/delete.gif\" height=\"11\" width=\"11\" alt=\"$strno\" title=\"$strno\" />";
    }
    $content .= "</td>\n";          
    $content .= "<td>";
	$content .= $link->department;
    $content .= "</td>";
    $content .= "    <td><a href=\"?modify=$link->id\" title=\"$strmodify\"><img src=\"$CFG->pixpath/t/edit.gif\" alt=\"$strmodify\" /></a> <a href=\"?delete=$link->id\" title=\"$strdelete\"><img src=\"$CFG->pixpath/t/delete.gif\" alt=\"$strdelete\" /></a></td>\n";           
    $content .= "  </tr>\n";
    return $content;
}

function link_modify_form($link = '', $row = 0) {
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
    
    
    $content  = "  <tr class=\"r{$row}\">\n    <td><input type=\"text\" name=\"linktext\" value=\"$link->linktext\" /></td>\n";
    $content .= "    <td><input type=\"text\" name=\"url\" value=\"$link->url\" /></td>\n";
    $content .= "    <td><input type=\"text\" name=\"notes\" value=\"$link->notes\" /></td>\n";
    $content .= "    <td><input type=\"hidden\" name=\"defaultshow\" value=\"0\" /><input type=\"checkbox\" name=\"defaultshow\" value=\"1\"";
    
    if ($link->defaultshow == '1') {
        $content .= ' checked="checked"';
    }
    $content .= " /></td>\n";
    $content .= "    <td><select name=\"department\">\n";
    $strglobal = get_string('global', 'block_links');
	$content .= "<option";
	if ('All' == $link->department){
		$content .= "SELECTED";
	}
	$content .= ">".$strglobal."</option>";
   	$catagories = get_records('user GROUP BY department','','','department');
    foreach ($catagories as $catagory) {
		$content .= "<option value=\"".$catagory->department."\" ";
		if ($catagory->department == $link->department){
			$content .= "SELECTED";
		}
		$content .= ">".$catagory->department."</option>";
		
    }
    $content .= "</select></td>\n";
    $content .= "    <td><input type=\"hidden\" name=\"id\" value=\"$link->id\"><input type=\"submit\" name=\"save\" value=\"$strok\" /><input type=\"submit\" value=\"$strcancel\" /></td>\n";
    return $content;
}

?>
