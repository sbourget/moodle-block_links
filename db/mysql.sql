# This file contains a complete database schema for all the 
# tables used by this module, written in SQL

# It may also contain INSERT statements for particular data 
# that may be used, especially new entries in the table log_display

# --------------------------------------------------------

#
# Table structure for table `prefix_block_learning_resources`
#

CREATE TABLE prefix_block_links (
 `id` int(11) NOT NULL auto_increment,
 `linktext` varchar(50) NOT NULL default '',
 `url` varchar(100) NOT NULL default '',
 `notes` varchar(100) NOT NULL default '',
 `defaultshow` int NOT NULL default 1,
 `department` varchar(100) NOT NULL ,
 PRIMARY KEY  (`id`)
);

