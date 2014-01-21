<?php
/*
Plugin Name: wizScriber - Video Scribing Banner Ads
Plugin URI: http://www.imwenterprises.com/wizScribe
Description: Attention-grabbing widget that knocks on your visitor's screen and shows them your most important message ...in 5 seconds!
Version: 1.1
Author: IMW Enterprises
Author URI: mailto:igorkozar83@gmail.com
License: GPLv2 or later
*/
function wizScribe_admin() {  

    include('wizScribe_import_admin.php');  
	
} 
function wizScribe_admin_action() {  

	$page_title = "wizScriber";
	$menu_title = "wizScriber";
	$capability = 8;
	$menu_slug = "wizScriber";
	$function = "wizScribe_admin";
	$icon_url = plugins_url()."/wizscriber-video-scribing-banner-ads/img/wg-icon.png";
	add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url);
	
}  
//callback function

function wizScribe_callback_plugin(){

global $wpdb;

$table_name = $wpdb->prefix . "wizscribe";

	$charset_collate = '';
	
			if(method_exists($wpdb, "get_charset_collate"))
			
				$charset_collate = $wpdb->get_charset_collate();
				
			else{
			
				if ( ! empty($wpdb->charset) )
				
					$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
					
				if ( ! empty($wpdb->collate) )
				
					$charset_collate .= " COLLATE $wpdb->collate";
					
			}
    $sql = "CREATE TABLE " .$table_name." (
	
							  id int(9) NOT NULL AUTO_INCREMENT,
							  firsttext tinytext NOT NULL,
							  secondtext tinytext,
							  actiononclick tinyint,
							  finaltexttop tinytext,
							  finaltextbottom tinytext,
							  actiononclickurl tinytext,
							  whentoappear int,
							  position tinytext,
							  PRIMARY KEY (id)
							  
							)$charset_collate;";
	
    //reference to upgrade.php file
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	dbDelta($sql);
		
		$table_name = $wpdb->prefix . "wizscribe";
		
        $firsttext = "";    
          
        $secondtext = "";   
          
        $actiononclick = "";  
		
        $finaltexttop = "";   
  
        $finaltextbottom = "";  
  
        $actiononclickurl = "";  
		
		$whentoappear = "";
		
		$position = "";
		$wpdb->insert( $table_name, array( 'firsttext' => $firsttext, 'secondtext' =>  $secondtext, 'actiononclick' => $actiononclick, 'finaltexttop' => $finaltexttop, 'finaltextbottom' => $finaltextbottom, 'actiononclickurl' => $actiononclickurl, 'whentoappear' => $whentoappear, 'position' => $position ));
}
function wizScribe_uninstall_plugin(){
	global $wpdb;
	$table_name = $wpdb->prefix . "wizscribe";
	//build our query to delete our custom table
	$sql = "DROP TABLE " . $table_name . ";";
	//execute the query deleting the table
	$wpdb->query($sql);
	require_once(ABSPATH .'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}
function wizScribe_main(){

	require(ABSPATH .'wp-content/plugins/wizscriber-video-scribing-banner-ads/wizScribe_page_content.php');
	
}

add_shortcode('wizScribe', 'wizScribe_main');

function add_styles_scripts(){
	
	wp_register_script( 'player', plugins_url('/wizScribeSchell/player/video.js', __FILE__));

	wp_register_style( 'wizScribe-style-hand', plugins_url( '/wizScribeSchell/style-hand.css', __FILE__ ));  

	wp_register_style( 'wizScribe-style', plugins_url( '/css/wizScribe.css', __FILE__ )); 

//enqueue

	wp_enqueue_style( 'wizScribe-style-hand' );

	wp_enqueue_style( 'wizScribe-style' );
	
	wp_enqueue_script( 'player' );

} 
register_activation_hook(__FILE__,'wizScribe_callback_plugin');

register_deactivation_hook(__FILE__,'wizScribe_uninstall_plugin');

add_action('admin_menu', 'wizScribe_admin_action');  

add_action('init', 'add_styles_scripts');

?>