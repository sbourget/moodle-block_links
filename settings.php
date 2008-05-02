<?php  //$Id: settings.php,v 1.0 2008-05-02 17:38:47 sbourget Exp $



$settings->add(new admin_setting_configtext('block_links_title', get_string('blocktitle', 'block_links'),
                   get_string('blocktitle2', 'block_links'), get_string('blockname', 'block_links'), PARAM_TEXT));

$link ='<a href="'.$CFG->wwwroot.'/blocks/links/config_global_action.php">'.get_string('managelinks', 'block_links').'</a>';
$settings->add(new admin_setting_heading('block_links_addheading', '', $link));
?>
