<?php //Check session start
if (!isset($_SESSION)) { @session_start(); }
// Root 
if( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', str_replace( '\\', '/',  dirname( __FILE__ ) )  );
// Includes
if( !defined( 'INC' ) )
	define( 'INC', ABSPATH.'/lib' );
// Security
if( !defined( 'in_phpvibe' ) )
	define( 'in_phpvibe', true);
// Configs 
require_once( ABSPATH.'/vibe_config.php' );
require_once( ABSPATH.'/vibe_setts.php' );
// Sql db classes
require_once( INC.'/ez_sql_core.php' );
if( !defined( 'cacheEngine' ) || (cacheEngine == "mysql") ) {
require_once( INC.'/ez_sql_mysql.php' );
  /* Define live db for MySql */
$db = new ezSQL_mysql(DB_USER,DB_PASS,DB_NAME,DB_HOST,'utf8');
 /* Define cached db for MySql */
$cachedb = new ezSQL_mysql(DB_USER,DB_PASS,DB_NAME,DB_HOST,'utf8');
} else {
require_once( INC.'/ez_sql_mysqli.php' );	
  /* Define live db for MySql Improved */
$db = new ezSQL_mysqli(DB_USER,DB_PASS,DB_NAME,DB_HOST,'utf8');
 /* Define cached db for MySql Improved */
$cachedb = new ezSQL_mysqli(DB_USER,DB_PASS,DB_NAME,DB_HOST,'utf8');	
}
if( !defined( 'DB_CACHE' ) ) {
$cachedb->cache_timeout = 6; /* Note: this is hours */
} else { $cachedb->cache_timeout = DB_CACHE; }
$cachedb->cache_dir = ABSPATH.'/storage/cache';
$cachedb->use_disk_cache = true;
$cachedb->cache_queries = true;
// Include functions
require_once( INC.'/Router.php' );
require_once( INC.'/Route.php' );
require_once( INC.'/HashGenerator.php');
require_once( INC.'/functions.permalinks.php');
require_once( INC.'/Hashids.php');
require_once( INC.'/functions.plugins.php' );
require_once( INC.'/functions.html.php' );
require_once( INC.'/functions.php' );
require_once( INC.'/functions.videoads.php' );
require_once( INC.'/functions.user.php' );
require_once( INC.'/functions.kses.php' );
require_once( INC.'/comments.php' );
// Theme
if( !defined( 'THEME' ) )
	define( 'THEME', get_option('theme','main') );	
// Themes directory
if( !defined( 'TPL' ) )
	define( 'TPL', ABSPATH.'/tpl/'.THEME);
// Site options
$all_options = get_all_options();
// Global classes
require_once( INC.'/class.upload.php' );
require_once( INC.'/class.providers.php' );
require_once( INC.'/class.pagination.php' );
require_once( INC.'/class.phpmailer.php' );
require_once( INC.'/class.images.php' );
require_once( INC.'/class.youtube.php' );
// Fix some slashes
if ( get_magic_quotes_gpc() ) {
    $_POST      = array_map( 'stripslashes_deep', $_POST );
    $_GET       = array_map( 'stripslashes_deep', $_GET );
    $_COOKIE    = array_map( 'stripslashes_deep', $_COOKIE );
    $_REQUEST   = array_map( 'stripslashes_deep', $_REQUEST );
}
// Current translation
$trans = init_lang(); 
// Plugins
if(!is_null(get_option('activePlugins',null))) {
//Plugins array	
$Plugins = explode(",",get_option('activePlugins',null));
if(!empty($Plugins) && is_array($Plugins)){
// Plugins loop
foreach ($Plugins as $plugin) {
if(file_exists(plugin_inc($plugin))) { include_once(plugin_inc($plugin)); }
}	
}	
}	
// Twitter Login
define( 'Tw_Key', get_option('Tw_Key') ); define( 'Tw_Secret', get_option('Tw_Secret') );
//Facebook API Login
define( 'Fb_Key', get_option('Fb_Key') ); define( 'Fb_Secret', get_option('Fb_Secret'));
// OnSite Login 
define('COOKIEKEY', get_option('COOKIEKEY') ); define('SECRETSALT', get_option('SECRETSALT')); define( 'COOKIESPLIT', get_option('COOKIESPLIT') );
// Cookie logins
authByCookie(); validate_session();
if(is_user()) {$killcache = true;}
?>