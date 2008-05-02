<?php  //$Id: upgrade.php,v 1.0 2008-05-01 17:05:51 sbourget Exp $

// This file keeps track of upgrades to 
// the links block
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

function xmldb_block_links_upgrade($oldversion=0) {

    global $CFG, $THEME, $db;

    $result = true;

/// And upgrade begins here. For each one, you'll need one 
/// block of code similar to the next one. Please, delete 
/// this comment lines once this file start handling proper
/// upgrade code.

    if ($oldversion < 2006053011 and $result) {
        $result = true; //Nothing to do
    }

    //Finally, return result

    return $result;
}

?>