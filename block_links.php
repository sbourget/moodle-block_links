<?php

/* Reference block by Tom Flannaghan and Andrew Walker - Alton College */
/* Modified by Sean Madden - RIT for Goffstown School District */

    class block_links extends block_list {
        function init() {
            $this->title = "Links";
            $this->version = 2006053011;
        }
        
        function specialization() {
            global $CFG;
            
            $this->title = !empty($CFG->block_links_title) ? $CFG->block_links_title : $this->title;
        }
        
        function get_content() {
			global $CFG,$USER;
            if ($this->content !== NULL) {
                return $this->content;
            }
            
            $this->content = new stdClass;
            $this->content->items = array();
            $this->content->icons = array();
            $this->content->footer = '';
            
            $rs = get_records('block_links ORDER BY linktext');
            if (!is_array($rs)) {
                $rs = array();
            }
            $rs = stripslashes_safe($rs);
            
            $course = get_record('course', 'id', $this->instance->pageid);
            
            foreach ($rs as $link) {
                $temp = 'allow_' . $link->id;
                
                if (isset($this->config->$temp)) {
                    if ($this->config->$temp == 1) {
                        $this->add_link($link);
                    }
                } else if (($link->defaultshow == 1) & (($link->department == 'All') | ($link->department == $_SESSION['USER']->department))) {
                    $this->add_link($link);
                }
            }
            
			if (isadmin()) {
				$link->url = $CFG->wwwroot."/blocks/links/config_global_action.php";
				$link->linktext = "<b>".get_string('manage_links', 'block_links')."</b>";
				$link->notes = "";
				$this->add_link($link);
			}
            
            return $this->content;
        }
        
        function add_link($link) {
            global $CFG;
            
            $target = !empty($CFG->block_links_window) ? ' target="_blank"' : '';
        
            $this->content->items[] = '<a href="' . $link->url.'"' . $target . '>'. $link->linktext . '</a> <em>' .$link->notes . '</em>';
            $this->content->icons[] = '<img src="' . $CFG->pixpath . '/f/web.gif" height="16" width="16" alt="" />';
        }
        
             
        function has_config() {
            return true;
        }

    }
?>
