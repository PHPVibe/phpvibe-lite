<?php  error_reporting(0);
// Root 
if( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', str_replace( '\\', '/',  dirname( __FILE__ ) )  );
$cachef = explode('tpl', ABSPATH);
$txt = '';

/* Define scripts */
$scripts = array (
  0 => 'bootstrap',
  1 => 'jquery.form.min',
  2 => 'jquery.imagesloaded.min',
  3 => 'jquery.infinitescroll.min',
  4 => 'js-alert',
  5 => 'jquery.slimscroll.min',
  6 => 'jquery.emoticons',
  7 => 'owl.carousel.min',
  8 => 'jquery.minimalect.min',
  9 => 'jquery.validarium',
  10 => 'jquery.tagsinput',
  11 => 'jssocials.min',
  12 => 'jquery.grid-a-licious.min',
  13 => 'phpvibe_app',
  14 => 'extravibes'
);

/* Cache file */
$cachexists= true;
$cachedfile = $cachef[0].'storage/cache/js-combined.cache';
    if (file_exists($cachef[0].'storage/cache/js-combined-min.cache') && (filesize($cachef[0].'storage/cache/js-combined-min.cache') > 100000 )) {
	// Prefer minimized	file
	$txt = file_get_contents($cachef[0].'storage/cache/js-combined-min.cache');	
	$cachexists= true;
	} elseif (file_exists($cachef[0].'storage/cache/js-combined.cache')){ 
    // Use combined	file
	$txt = file_get_contents($cachef[0].'storage/cache/js-combined.cache');	
	$cachexists= true;	
	} else {
    // Get individual js & combine	them	
	if(!is_null($scripts) && !empty($scripts)) {
		foreach ($scripts as $js) {			
			$jsfile = ABSPATH.'/js/'.trim($js).'.js';
			//echo $jsfile;	
            if (file_exists($jsfile)) {			
            $txt .= file_get_contents($jsfile);	
			}			
			
		}
		$cachexists= false;
	}
	
	
}

// Enable GZip encoding.
ob_start("ob_gzhandler");
// Enable caching
header('Cache-Control: public');
// Expire in one day
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
// Set the correct MIME type, because Apache won't set it for us
header("Content-Type: application/javascript");
// Print the css
echo($txt);	
// Write cache file 
if(!$cachexists) {
    // Minify
	// Create cache file
	$f = fopen($cachedfile, 'w');
	fwrite ($f, $txt);
	fclose($f);	
}
?>