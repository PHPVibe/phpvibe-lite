<?php  // Root 
if( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', str_replace( '\\', '/',  dirname( __FILE__ ) )  );
$cachef = explode('tpl', ABSPATH);
$txt = '';
function minifyJavascriptCode($text){
//$text = preg_replace('!^[ \t]*/\*.*?\*/[ \t]*[\r\n]!s', '', $text);
//$text = preg_replace('![ \t]*//.*[ \t]*[\r\n]!', '', $text);
//$text = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $text);
//$text = preg_replace('/\s+/', ' ', $text);
return $text;
}
/* Define scripts */
$scripts = 'bootstrap,jquery.form.min,jquery.imagesloaded.min,jquery.infinitescroll.min,js-alert,jquery.slimscroll.min,jquery.emoticons,owl.carousel.min,
jquery.minimalect.min,jquery.validarium,jquery.tagsinput, jssocials.min,jquery.grid-a-licious.min,phpvibe_app,extravibes';	
	$sf = preg_replace('/\W+/', '-', $scripts);
    $cachedfile = $cachef[0].'storage/cache/js-'.date('w-m-y').'.cache';
    if(isset($plm)){
	//if (file_exists($cachedfile)) {
	$txt = file_get_contents($cachedfile);	
	} else {	
	if(!is_null($scripts) && !empty($scripts)) {
		$scripts = explode(',',$scripts);		
		foreach ($scripts as $js) {			
			$jsfile = ABSPATH.'/js/'.trim($js).'.js';
			//echo $jsfile;	
            if (file_exists($jsfile)) {			
            $txt .= file_get_contents($jsfile);	
			}			
			
		}
	}
	
	// Minify
	$txt = minifyJavascriptCode($txt);

	// Create cache file
	$f = fopen($cachedfile, 'w');
	fwrite ($f, $txt);
	fclose($f);
}

// Enable GZip encoding.
ob_start("ob_gzhandler");
// Enable caching
header('Cache-Control: public');
// Expire in one day
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
// Set the correct MIME type, because Apache won't set it for us
header("Content-Type: application/javascript");
// Write everything out
echo($txt);	
?>