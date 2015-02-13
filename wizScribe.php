<?php
/*
Plugin Name: wizScriber - Video Scribing Banner Ads
Plugin URI: http://www.wizMotions.com
Description: Attention-grabbing widget that knocks on your visitor's screen and shows them your most important message ...in 5 seconds!
Version: 1.25
Author: IMW Enterprises
Author URI: mailto:http://www.imwenterprises.com
License: GPLv2 or later
*/

/*
 * "WSVSBA" is the abbreviation of "WizScriber-Video Scribing Banner Ads".
 */

define('WSVSBA_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('WSVSBA_PLUGIN_NAME', trim(dirname(WSVSBA_PLUGIN_BASENAME), '/'));
define('WSVSBA_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));
define('WSVSBA_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));
define('WSVSBA_VERSION', '1.1');
define('WSVSBA_POSTTYPE', 'imw_wizscriber');

require_once WSVSBA_PLUGIN_DIR . '/admin/functions.php';
require_once WSVSBA_PLUGIN_DIR . '/classes/class-wizScriber.php';
if (is_admin())
    require_once WSVSBA_PLUGIN_DIR . '/admin/admin.php';

//----------------------------------------------------------------------
add_action('init', 'wsvsba_init');
function wsvsba_init() {
    WSVSBA_wizScriber::register_post_type();
}

add_action('activate_' . WSVSBA_PLUGIN_BASENAME, 'wsvsba_install');
function wsvsba_install() {
    WSVSBA_wizScriber::register_post_type();
}
?>