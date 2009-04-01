<?php
/*
Plugin Name: Magazine Columns
Plugin URI: http://bavotasan.com/downloads/magazine-columns-wordpress-plugin/
Description: Divides your post or page content into two or more columns, like a magazine article.
Author: c.bavota
Version: 1.0.2
Author URI: http://www.bavotasan.com/
*/

// Replace the_content function if a <!--column--> tag is detected
function add_columns($content) {
	if(stristr($content, '<!--column-->') && is_single()) {
		if(stristr($content, '<!--startcolumns-->')) {
			$topcontent = explode('<!--startcolumns-->', $content);
			if(stristr($content, '<!--stopcolumns-->')) {
				$bottomcontent = explode('<!--stopcolumns-->', $topcontent[1]);
				$content = preg_replace('/width="([0-9]*)" height="([0-9]*)"/', '',$bottomcontent[0]);
			} else {
				$content = preg_replace('/width="([0-9]*)" height="([0-9]*)"/', '',$topcontent[1]);
			}
		} else {
			$content = preg_replace('/width="([0-9]*)" height="([0-9]*)"/', '',$content);		
		}
		$content = explode('<!--column-->', $content);
		$count = count($content);
		if($count == 2) {
			$colname = "col_two";
		} elseif($count == 3) {
			$colname = "col_three";
		} elseif($count == 4) {
			$colname = "col_four";
		} else {
			$colname = "col_five";
		}		
		$x = 1;
		if($topcontent[0]) { echo $topcontent[0]; }
		foreach($content as $column) {
			add_columns_css($count);
			if($x ==5) {
			unset($content[0]);
			unset($content[1]);
			unset($content[2]);
			unset($content[3]);
			$column = implode("", $content);
			echo '<div class="columns" id="'.$colname.$x.'">'.$column.'</div>';
			break;
			} else {
			echo '<div class="columns" id="'.$colname.$x.'">'.$column.'</div>';
			}
			$x++;
		}
		if($bottomcontent[1]) { 
			$bottom = explode('<br />',$bottomcontent[1]);
			$bottom[0] = '<br class="clear" />';
			$bottom = implode("", $bottom);
			echo $bottom;
		}
	} else {
		return $content;
	}
}

// Adds the add_columns() function to the_content()
add_filter('the_content', 'add_columns');

// Creates the CSS for columns
function add_columns_css($count) {
	$two = 48; // width of two columns
	$three = 30; // width of three columns
	$four = 21; // width of four columns
	$five = 15.5; // width of five columns 
	echo "<style type='text/css'>";
	if($count == 2) {
	echo "	#col_two1 { float: left; width: ".$two."%; }";
	echo "	#col_two2 { float: right; width: ".$two."%; }";
	}
	if($count == 3) {	
	echo "	#col_three1, #col_three3 { float: left; width: ".$three."%; }";
	echo "	#col_three2 { float: left; width: ".$three."%; margin: 0 5%; }";
	}
	if($count == 4) {
	echo "	#col_four1, #col_four4 { float: left; width: ".$four."%; }";
	echo "	#col_four2 { float: left; width: ".$four."%; margin: 0 2.5% 0 5%; }";
	echo "	#col_four3 { float: left; width: ".$four."%; margin: 0 5% 0 2.5%; }";
	}
	if($count == 5) {
	echo "	#col_five1, #col_five5 { float: left; width: ".$five."%; }";
	echo "	#col_five2 { float: left; width: ".$five."%; margin: 0 2.5% 0 5%; }";
	echo "	#col_five3 { float: left; width: ".$five."%; margin: 0 2.5% 0 5%; }";
	echo "	#col_five4 { float: left; width: ".$five."%; margin: 0 5% 0 2.5%; }";
	}
	echo "	.columns img { width: 98% }";
	echo "	br.clear { clear: both; }";
	echo "</style>\n";
}

// Creates the columns quicktag button
function columns_quicktag_button() {
  if (strpos($_SERVER['REQUEST_URI'], 'post.php') ||
      strpos($_SERVER['REQUEST_URI'], 'post-new.php') ||
      strpos($_SERVER['REQUEST_URI'], 'page.php') ||
      strpos($_SERVER['REQUEST_URI'], 'page-new.php')) {
	echo '<script type="text/javascript" src="'  . get_option('siteurl') . '/wp-content/plugins/magazine-columns/js/mc.js"></script>';
    }
}

//Add the columns_quicktag_button() function to the proper admin pages
add_filter('admin_footer', 'columns_quicktag_button');
?>