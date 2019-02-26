<?php  // Root 
if( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', str_replace( '\\', '/',  dirname( __FILE__ ) )  );
$cachef = explode('tpl', ABSPATH);
$txt = '';
if(isset($_GET['f'])){
    $styles = preg_replace('/(\.+\/)/','',$_GET['f']);	
	$sf = preg_replace('/\W+/', '-', $styles);
    $cachedfile = $cachef[0].'storage/cache/css-'.$sf.'-'.date('w-m-y').'.cache';
    if (file_exists($cachedfile)) {
	$txt = file_get_contents($cachedfile);	
	} else {	
	if(!is_null($styles) && !empty($styles)) {
		$styles = explode(',',$styles);		
		foreach ($styles as $css) {			
			$cssfile = ABSPATH.'/'.$css.'.css';
			//echo $cssfile;	
            if (file_exists($cssfile)) {			
            $txt .= file_get_contents($cssfile);	
			}			
			
		}
	}
	
	// Remove space after colons
	$txt = str_replace(': ', ':', $txt);
	// Remove whitespace
	$txt = preg_replace("/\s{2,}/", " ", $txt);
    $txt = str_replace("\n", "", $txt);
    $txt = str_replace(', ', ",", $txt);
	$txt = preg_replace( '/(\/\*[\w\'\s\r\n\*\+\,\"\-\.]*\*\/)/', '$2', $txt );
	//A fix 
	$txt = str_replace('and(', 'and (', $txt);

	// Create cache file
	$f = fopen($cachedfile, 'w');
	fwrite ($f, $txt);
	fclose($f);
}
}
// Enable GZip encoding.
ob_start("ob_gzhandler");
// Enable caching
header('Cache-Control: public');
// Expire in one day
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
// Set the correct MIME type, because Apache won't set it for us
header("Content-type: text/css");
// Write everything out
echo($txt);	
?>