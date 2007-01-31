<?php //$Id: mysql.php,v 1.1 2006/06/06 21:53:42 wildgirl Exp $

function links_upgrade($oldversion) {
/// This function does anything necessary to upgrade 
/// older versions to match current functionality 

    global $CFG;
    if ($oldversion < 2006053011) {
        table_column('block_links','','course_categorie_id','int','10','','','','');
    }

    return true;
}

?>
