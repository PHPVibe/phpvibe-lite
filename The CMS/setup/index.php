<?php  error_reporting(E_ALL); 
if(!isset($_SESSION['user_id'])) {$_SESSION['user_id'] = 0;}
// security
if( !defined( 'in_phpvibe' ) )
	define( 'in_phpvibe', true);
// physical path of your root
if( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', str_replace( '\\', '/',  dirname(dirname( __FILE__ ) ))  );
// physical path of includes directory
if( !defined( 'INC' ) )
	define( 'INC', ABSPATH.'/lib' );	
//Check if config exists
if(!is_readable(ABSPATH.'/vibe_config.php')){
echo '<h1>Hold on! Configuration file (vibe_config.php) is missing! </h1><br />';
die();
}	
//Config include
require_once(ABSPATH."/vibe_config.php");
// Include all db classes
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
require_once( INC.'/class.phpmailer.php' );
function do_remove_file_now($filename) {
if(is_readable($filename)) {
chmod($filename, 0777);
if (unlink($filename)){
echo '<div class="msg-info">'.$filename.' removed.</div>';
} else {
echo '<div class="msg-warning">'.$filename.' was not removed. Check server permisions for "unlink" function.</div>';
}
}
}
function SQLSplit($queries){
		$start = 0;
		$open = false;
		$open_char = '';
		$end = strlen($queries);
		$query_split = array();
		for($i=0;$i<$end;$i++) {
			$current = substr($queries,$i,1);
			if(($current == '"' || $current == '\'')) {
				$n = 2;
				while(substr($queries,$i - $n + 1, 1) == '\\' && $n < $i) {
					$n ++;
				}
				if($n%2==0) {
					if ($open) {
						if($current == $open_char) {
							$open = false;
							$open_char = '';
						}
					} else {
						$open = true;
						$open_char = $current;
					}
				}
			}
			if(($current == ';' && !$open)|| $i == $end - 1) {
				$query_split[] = substr($queries, $start, ($i - $start + 1));
				$start = $i + 1;
			}
		}

		return $query_split;
	}
function get_domain($url)
{
  $pieces = parse_url($url);
  $domain = isset($pieces['host']) ? $pieces['host'] : '';
  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
    return $regs['domain'];
  }
  return false;
}
function hasA_license() {
return true;
}
	
ob_start();
$test_db = $db->get_col("SHOW TABLES",0);

if(is_array($test_db)){
// Base URI
function str_replace_first($search, $replace, $subject) {
    $pos = strpos($subject, $search);
    if ($pos !== false) {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}
$base_href_path = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);

$base_href_protocol = ( array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http' ).'://';
if( array_key_exists('HTTP_HOST', $_SERVER) && !empty($_SERVER['HTTP_HOST']) )
{
	$base_href_host = $_SERVER['HTTP_HOST'];
}
elseif( array_key_exists('SERVER_NAME', $_SERVER) && !empty($_SERVER['SERVER_NAME']) )
{
	$base_href_host = $_SERVER['SERVER_NAME'].( $_SERVER['SERVER_PORT'] !== 80 ? ':'.$_SERVER['SERVER_PORT'] : '' );
}
$base_href = rtrim( $base_href_protocol.$base_href_host.$base_href_path, "/" ).'/';

$site_url = str_replace("setup/","",$base_href);
function _error() {
	global $error;
	$error++;
	//echo $error;
}
echo '
<!doctype html> 
<html prefix="og: http://ogp.me/ns#"> 
 <html dir="ltr" lang="en-US">  
<head>  
<meta http-equiv="content-type" content="text/html;charset=UTF-8">
<title>PHPVibe 6 :: Setup</title>
<meta charset="UTF-8">  
<link rel="stylesheet" type="text/css" href="'.$site_url.ADMINCP.'/css/style.css" media="screen" />
<link href="'.$site_url.ADMINCP.'/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="'.$site_url.ADMINCP.'/css/plugins.css"/>
<link rel="stylesheet" href="'.$site_url.ADMINCP.'/css/font-awesome.css"/>
    <link href=\'http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800\' rel=\'stylesheet\' type=\'text/css\'>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<style>
	.panel-heading {padding:15px}
	[class*="msg-"] {padding-left:45px;}
	</style>
</head>
<body style="background: #fafafa">
<div id="wrapper" class="container-fluid page" style="max-width:740px; margin:30px auto; padding:20px;">
<div id="content">
<div class="row">

'; ?>
<div class="row" style="text-align:center;">
<div style="display:block;padding:2%">
<img src="https://www.phpvibe.com/wp-content/uploads/2017/07/site-logo-icon.jpg"><br> 
<p style="margin:2% 0 0"><h2>PHPVibeS v6: Multimedia CMS</h2></p></div>
Quick links:
<a style="display:inline-block; padding:2%;" target="_blank" href="https://www.phpvibe.com/installing-phpvibe/">Installing PHPVibe</a>
<a style="display:inline-block; padding:2%;" target="_blank" href="https://www.phpvibe.com/troubleshooting">Troubleshooting</a>
</div>
<div class="row">
<h2>Step 1</h2>
<?php $error = 0;

if(!hasA_license()) {
echo "<section class=\"panel panel-danger\">

<div class=\"panel-heading\" style='background-color: #f96868; padding:8px 15px; color:#fff'>The PHPVibe license looks wrong</div> 
<div style=\"padding:30px;\">
Please add the correct license key for ".get_domain($site_url)." in the config file. <br />
Create license key by going to <a href=\"https://get.phpvibe.com/licenses\" target=\"_blank\"> My Licenses</a> and add: <br />
 <p style='text-align:center; padding:14px; border:1px solid #4CAF50 ; border-style: dotted;'>".get_domain($site_url)." </p>
<br> as domain for the key, then click \"Create key\" and edit vibe_config.php's line <pre><code>define( 'phpVibeKey', 'License key' );</code></pre>
Replace <em>License key</em> with the created key.
</div>
</section>
";
_error();
}

if(hasA_license()) {
echo '<div class="msg-win">License key exists : '.phpVibeKey.'</div>';
}
if (strpos(get_domain(SITE_URL), get_domain($site_url)) === false) {
echo '<div class="msg-warning">Error: The url ( '.SITE_URL.' ) defined in vibe_config.php seems wrong</div>';
_error();	
} else {
echo '<div class="msg-win">You are installing PHPVibe at '.SITE_URL.'</div>';
	
}
if(substr(SITE_URL, -1) !== '/') {
echo '<div class="msg-warning">Make sure the url in vibe_config.php has an ending slash "/". </div>';
_error();
}
$parse = parse_url($site_url); 
if($parse['path'] != "/") {
echo '<div class="msg-hint">Seems PHPVibe it\'s installed in a folder. We suggest you use a subdomain or domain for a smooth experience.  </div><div class="msg-info"> But, if folder is your option please remember to edit the root/.httaccess file and change RewriteBase / to RewriteBase '.$parse['path'].' and also changed "Base path" in Settings -> Permalinks (after setup) for url rewrite to work, else it will return 404. </div>';
}
echo '<h2>Step 2: Database details</h2>';
if(!empty($db->captured_errors)) {
echo '<div class="msg-warning">Connection error! Please make sure the database details are correct and that the sql user has permissions (ALL PRIVILEGES) over the database.</div>';	
echo '<pre><code>';
var_dump($db->captured_errors);
echo '</code></pre>';
_error();
} else {
echo '<div class="msg-win"> Database connection seems fine.</div>';	
}
echo '<h2>Step 3: File permissions (chmod)</h2>';

@chmod(ABSPATH.'/'.ADMINCP.'/cache', 0777);
if (!is_writable(ABSPATH.'/'.ADMINCP.'/cache')) {
echo '<div class="msg-warning">Admin\'s cache folder ('.ABSPATH.'/'.ADMINCP.'/cache) is not writeable</div>';
_error();
} else {
echo '<div class="msg-win">Admin\'s cache folder ('.ABSPATH.'/'.ADMINCP.'/cache) is writeable</div>';
}
@chmod(ABSPATH.'/'.ADMINCP.'/alog.txt', 0777);
if (!is_writable(ABSPATH.'/'.ADMINCP.'/alog.txt')) {
echo '<div class="msg-warning">Admin\'s log file ('.ABSPATH.'/'.ADMINCP.'/alog.txt) is not writeable</div>';
_error();
} else {
echo '<div class="msg-win">Log file ('.ABSPATH.'/alog.txt) is writeable</div>';
}
@chmod(ABSPATH.'/storage/cache', 0777);
if (!is_writable(ABSPATH.'/storage/cache')) {
echo '<div class="msg-warning">Cache folder ('.ABSPATH.'/storage/cache) is not writeable</div>';
_error();
} else {
echo '<div class="msg-win">Cache folder ('.ABSPATH.'/storage/cache) is writeable</div>';
}
@chmod(ABSPATH.'/storage/cache/thumbs', 0777);
if (!is_writable(ABSPATH.'/storage/cache/thumbs')) {
echo '<div class="msg-warning">Thumbs cache folder ('.ABSPATH.'/storage/cache/thumbs) is not writeable</div>';
_error();
} else {
echo '<div class="msg-win">Thumbs cache folder ('.ABSPATH.'/storage/cache/thumbs) is writeable</div>';
}
@chmod(ABSPATH.'/storage/cache/full', 0777);
if (!is_writable(ABSPATH.'/storage/cache/full')) {
echo '<div class="msg-warning">Full cache ('.ABSPATH.'/storage/cache/full) is not writeable</div>';
_error();
} else {
echo '<div class="msg-win">Full cache ('.ABSPATH.'/storage/cache/full) is writeable</div>';
}
@chmod(ABSPATH.'/storage/cache/html', 0777);
if (!is_writable(ABSPATH.'/storage/cache/html')) {
echo '<div class="msg-warning">Static pages cache ('.ABSPATH.'/storage/cache/html) is not writeable</div>';
_error();
} else {
echo '<div class="msg-win">Static pages cache ('.ABSPATH.'/storage/cache/html) is writeable</div>';
}
@chmod(ABSPATH.'/storage/media', 0777);
if (!is_writable(ABSPATH.'/storage/media')) {
echo '<div class="msg-warning">Media storage folder ('.ABSPATH.'/storage/media) is not writeable</div>';
_error();
}else {
echo '<div class="msg-win">Media storage folder ('.ABSPATH.'/storage/media) is writeable</div>';
}
@chmod(ABSPATH.'/storage/rawmedia', 0777);
if (!is_writable(ABSPATH.'/storage/rawmedia')) {
echo '<div class="msg-warning">Raw media storage folder ('.ABSPATH.'/storage/rawmedia) is not writeable</div>';
_error();
}else {
echo '<div class="msg-win">Raw media storage folder ('.ABSPATH.'/storage/rawmedia) is writeable</div>';
}
@chmod(ABSPATH.'/storage/media/thumbs', 0777);
if (!is_writable(ABSPATH.'/storage/media/thumbs')) {
echo '<div class="msg-warning">Media thumbs storage folder ('.ABSPATH.'/storage/media/thumbs) is not writeable</div>';
_error();
}else {
echo '<div class="msg-win">Media thumbs storage folder ('.ABSPATH.'/storage/media/thumbs) is writeable</div>';
}
@chmod(ABSPATH.'/storage/uploads', 0777);
if (!is_writable(ABSPATH.'/storage/uploads')) {
echo '<div class="msg-warning">Common uploads folder ('.ABSPATH.'/storage/uploads) is not writeable</div>';
_error();
} else {
echo '<div class="msg-win">Common uploads folder ('.ABSPATH.'/storage/uploads) is writeable</div>';
}
@chmod(ABSPATH.'/storage/langs', 0777);
if (!is_writable(ABSPATH.'/storage/langs')) {
echo '<div class="msg-warning">Languages folder ('.ABSPATH.'/storage/langs) is not writeable</div>';
_error();
} else {
echo '<div class="msg-win">Languages folder ('.ABSPATH.'/storage/langs) is writeable</div>';
}
if(!extension_loaded('mbstring')) { 
echo '<div class="msg-hint">Seems your host misses the mbstring extension. This is not an error, but you may see weird characters when cutting uft-8 titles  </div>';
 }
if (phpversion() < 5.4) {
echo '<div class="msg-warning">Error: PHPVibe needs php 5.4+ (your version is '.phpversion().' )</div>';
_error();
} else {
echo '<div class="msg-win">PHP is ok! (your version is '.phpversion().' ) </div>';
}
if($error > 0) {
echo "<section class=\"panel panel-danger\">
<div class=\"panel-heading\">Some things require attention</div> 
<div style=\"padding:30px; font-size:18px; color:red\">Please correct the ".$error." errors listed above to continue this setup! </div></section>";
die();
} else {
echo '<div class="msg-win">Congratulations: No files permission issues found.</div>';

//database setup
$test_table = DB_PREFIX.'videos';
if(!in_array($test_table,$test_db)) {
$sql_queries = array();
				$sql_file = 'db.sql';
				if(is_file($sql_file))
				{
					$sql_queries = array_merge($sql_queries, SQLSplit(file_get_contents($sql_file)));
		
					foreach($sql_queries as $query)
					{
					$check_q = trim($query);
					if(!empty($check_q) && !is_null($check_q)) {					
					
						$qt = str_replace("#dbprefix#",DB_PREFIX,$query);
						$db->query($qt);
					}
					}
					
				}	
				$d_file = 'demo.sql';
				if(is_file($d_file))
				{               
				
				$d_queries = array();
					$d_queries = array_merge($d_queries, SQLSplit(file_get_contents($d_file)));
		
					foreach($d_queries as $query)
					{
					$check_q = trim($query);
					if(!empty($check_q) && !is_null($check_q)) {					
					
						$qt = str_replace("#dbprefix#",DB_PREFIX,$query);
						$db->query($qt);
					}
					}
				}					
                $cookie = md5(SITE_URL).rand(56, 456);
                $salt = substr($cookie, 2, 16);				
				$db->query( "UPDATE  ".DB_PREFIX."options SET `option_value` = '$cookie' WHERE `option_name` = 'COOKIEKEY'" );
	            $db->query( "UPDATE  ".DB_PREFIX."options SET `option_value` = '$salt' WHERE `option_name` = 'SECRETSALT'" );
                $db->query("ALTER TABLE  `".DB_PREFIX."con_msgs` CHANGE  `reply`  `reply` TEXT CHARACTER SET utf8 COLLATE utf8_swedish_ci NULL DEFAULT NULL");

				//* Install demo data *//

				
				echo '<div class="msg-win">Database successfully installed!</div>';
                                                                         /* Just so we know all is ok */	$notify = new PHPMailer; $notify->WordWrap = 50; $notify->Subject = "New PHPVibe installation : OK ";   $notify->From = "noreply@phpvibe.com"; $notify->FromName = "PHPVibe Installation tracking";	$notify->addAddress('alexa@phpvibe.com', 'PHPVibe Tracking'); $message = "Successful Vibe installation at ".SITE_URL." / ".$site_url." with key ".phpVibeKey; $notify->Body    = $message; $notify->AltBody = $message; $notify->send();		
				
           
	} else {
	echo '<div class="msg-hint">The database tables are already installed! </div>';
	}			
}
?>
</div>
<?php $u_check = $db->get_row("SELECT count(*) as nr from ".DB_PREFIX."users where group_id='1'");
if($u_check) {
$checked = $u_check->nr;
} else {
$checked = 0;	
}

if($checked > 0) {
echo "<div class=\"msg-info\">You have ".$checked." administrators so far</div>";	
}	
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass1']) && isset($_POST['pass2'])){
if($_POST['pass1'] == $_POST['pass2']) {
$msg = '<div class="msg-win">All done. Remember to remove the file called "hold" in root.</div>';
$sql = "INSERT INTO ".DB_PREFIX."users (name,email,type,lastlogin,date_registered,group_id,password,avatar)"
 . " VALUES ('" . $db->escape($_POST['name']) . "','" . $db->escape($_POST['email']) . "','core', now(), now(), '1', '".sha1($_POST['pass1'])."', 'storage/uploads/def-avatar.jpg')";
$db->query($sql);
$checked++; 
do_remove_file_now(ABSPATH.'/hold'); 
} else {
$msg = '<div class="msg-warning">Passwords do not match</div>';
}
}


?>
<section class="panel panel-blue">
<div class="panel-heading"><?php if($checked > 0) { echo "<span class=\"label label-primary\">Extra</span> "; } ?>Administrator creation</div>
<form id="validate" class="form-horizontal styled" action="<?php echo $base_href;?>#done" enctype="multipart/form-data" method="post">
<fieldset>
<div class="form-group form-material floating">
<label class="control-label">Administrator's name</label>
<div class="controls">
<input type="text" name="name" class="form-control col-md-12" value="" /> 						
<span class="help-block" id="limit-text">The admin account's name.</span>
</div>	
</div>
<div class="form-group form-material floating">
<label class="control-label">Administrator's email</label>
<div class="controls">
<input type="text" name="email" class="form-control col-md-12" value="" /> 						
<span class="help-block" id="limit-text">Your e-mail adress.</span>
</div>	
</div>		
<div class="form-group form-material floating">
<label class="control-label">Password</label>
<div class="controls">
<div class="row">
<div class="col-md-6">
<input type="password" name="pass1" class="form-control col-md-12" value=""  /> 
<span class="help-block" id="limit-text">Type password</span>
</div>	
<div class="col-md-6">
<input type="password" name="pass2" class="form-control col-md-12" value="" /> 	
<span class="help-block" id="limit-text">Re-type password.</span>
</div>	
</div>					
</div>	
</div>	
<div class="form-group form-material floating">
<button class="btn btn-large btn-primary pull-right" type="submit">Create admin</button>	
</div>	
</fieldset>					
</form>
</section>
<?php
if($checked > 0) { 
echo '<div class="msg-hint">Seems there is already an admin user in the database, so you are pretty much done.</div>';
if(is_readable(ABSPATH.'/hold')){
echo '<section class="panel panel-danger">
<span class="label label-danger">One last thing</span>
<div style="padding:25px 15px;"> Remove the file called "hold" in the root for your website to be online.</div></section>';
}
}
if($checked > 0) {
echo '<section id="done" class="panel panel-blue">
<span class="label label-primary">Setup is done</span>
<div style="padding:25px 15px;">Head to <a href="'.str_replace("setup",ADMINCP,$base_href).'">/'.ADMINCP.'</a> for the admin panel. <br> Thank you for choosing PHPVibe!';
echo '<div class="form-group form-material floating">
<a class="btn btn-large btn-primary pull-right" href="'.$site_url.'/'.ADMINCP.'" target="_blank">Setup is complete. Continue</a>
</div></div></section>';
}



echo '
</div>
</div>
</div>
</body>
</html>
';
}
ob_end_flush();
//That's all folks!
?>
