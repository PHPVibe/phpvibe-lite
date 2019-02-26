<?php /*!
* MediaVibe v6
*
* Copyright Interact.Software
* http://www.phpvibe.com
* phpVibe IS NOT A FREE SOFTWARE
* If you have downloaded this CMS from a website other
* than www.phpvibe.com if you have received
* this CMS from someone who is not a representative of phpVibe, you are involved in an illegal activity.
* The phpVibe team takes actions against all unlincensed websites using Google, local authorities and 3rd party agencies.
* Designed and built exclusively for sale @ phpVibe.com & phpRevolution.com.
*/
// Global functions
//Site url
function site_url() {
$site_url = SITE_URL;	
$site_url .= (substr(SITE_URL, -1) == '/' ? '' : '/');	
return $site_url;
}
function redirect($url=null) {
if(!$url) { 	$url = site_url(); 	}
header('Location: '.$url);
exit();
}
//array isset
function _globalIsSet($arrayPostGet,$postGetList){
$flagValidation = true;
foreach ($postGetList as $testValue){
if (!(isset($arrayPostGet[$testValue]))){
$flagValidation = false;
}
}
return $flagValidation;
}

//returns current page
function this_page() {
$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
//No negatives
if($page < 1) {$page = 1;}
return $page;
}
//return next page
function next_page(){
return this_page() + 1;
}
//query limit
function this_limit(){
$limit = 'LIMIT ' .(this_page() - 1) * bpp() .',' .bpp();
return $limit;
}
//query offset
function this_offset($nr){
$limit = 'LIMIT ' .(this_page() - 1) * $nr .',' .$nr;
return $limit;
}
//browse per page
function bpp() {
if(get_option('bpp') > 0) {
return get_option('bpp');
}
return 24;
}
//ajax call
function is_ajax_call() {
global $_GET;
return (isset($_GET['ajax']) || isset($_GET['lightbox'] ));
}
//check if value is null
function nullval($value){
if(is_null($value) || $value==""){
return true;  }
else { return false;
}
}
//Alias null
function is_empty($v){
return 	nullval($v);
}
//Not null
function not_empty($v){
return 	!nullval($v);
}
//global time ago
function time_ago($date,$granularity=2) {
if (nullval($date)) {
return '';
}
$periods         = array(_lang("second"), _lang("minute"), _lang("hour"), _lang("day"), _lang("week"), _lang("month"), _lang("year"), _lang("decade"));
$lengths         = array("60","60","24","7","4.35","12","10");
$now             = time();
$unix_date         = strtotime($date);
// check validity of date
if(empty($unix_date)) {
return $date;
}
// is it future date or past date
if($now > $unix_date) {
$difference     = $now - $unix_date;
$tense         = _lang("ago");
} else {
$difference     = $unix_date - $now;
$tense         = _lang("from now");
}
for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
$difference /= $lengths[$j];
}
$difference = round($difference);
if($difference != 1) {
$periods[$j].= _lang("s");
}
return $difference.' '._lang($periods[$j]).' '._lang($tense);
}
/**
* Read an option from DB (or from cache if available). Return value or $default if not found
*
*/
function get_option( $option_name, $default = false ) {
global $db,$cachedb, $all_options;
// Allow plugins to short-circuit options
$pre = apply_filter( 'shunt_option_'.$option_name, false );
if ( false !== $pre )
return $pre;
// If option not available already, get its value from the DB
if ( !isset( $all_options[$option_name] ) ) {
$option_name = escape( $option_name );
$row = $cachedb->get_row( "SELECT `option_value` FROM ".DB_PREFIX."options WHERE `option_name` = '$option_name' LIMIT 1" );
if ( is_object( $row) ) { // Has to be get_row instead of get_var because of funkiness with 0, false, null values
$value = $row->option_value;
} else { // option does not exist, so we must cache its non-existence
$value = $default;
}
$all_options[ $option_name ] = maybe_unserialize( $value );
}
return apply_filter( 'get_option_'.$option_name, $all_options[$option_name] );
}
/**
* Read all options from DB at once
*
*/
function get_all_options() {
global $cachedb;
$vibe_opt = array();
// Allow plugins to short-circuit all options. (Note: regular plugins are loaded after all options)
$pre = apply_filter( 'shunt_all_options', false );
if ( false !== $pre )
return $pre;
$allopt = $cachedb->get_results( "SELECT `option_name`, `option_value` FROM  ".DB_PREFIX."options where autoload='yes'" );
foreach( (array)$allopt as $option ) {
$vibe_opt[$option->option_name] = maybe_unserialize( $option->option_value );
}
$vibe_opts = apply_filter( 'get_all_options', $vibe_opt );
return $vibe_opts;
}
/**
* Update (add if doesn't exist) an option to DB
*
*/
function update_option( $option_name, $newvalue ) {
global $db,$all_options;
$safe_option_name = escape( $option_name );
$oldvalue = get_option( $safe_option_name, false );
// If the new and old values are the same, no need to update.
if ( $newvalue === $oldvalue )
return false;
if ( false === $oldvalue ) {
add_option( $option_name, $newvalue );
return true;
}
$_newvalue = escape( maybe_serialize( $newvalue ) );
//do_action( 'update_option', $option_name, $oldvalue, $newvalue );
$db->query( "UPDATE  ".DB_PREFIX."options SET `option_value` = '$_newvalue' WHERE `option_name` = '$option_name'" );
if ( $db->rows_affected == 1 ) {
$all_options[ $option_name ] = $newvalue;
return true;
}
return false;
}
/**
* Add an option to the DB
*
*/
function add_option( $name, $value = '' ) {
global $db;
$safe_name = escape( $name );
// Make sure the option doesn't already exist
if ( false !== get_option( $safe_name ) )
return;
$_value = escape( maybe_serialize( $value ) );
//do_action( 'add_option', $safe_name, $_value );
$db->query( "INSERT INTO  ".DB_PREFIX."options (`option_name`, `option_value`) VALUES ('$name', '$_value')" );
return;
}
/**
* Delete an option from the DB
*
*/
function delete_option( $name ) {
global $db;
$name = escape( $name );
// Get the ID, if no ID then return
$option = $db->get_row( "SELECT option_id FROM  ".DB_PREFIX."options WHERE `option_name` = '$name'" );
if ( is_null( $option ) || !$option->option_id )
return false;
//do_action( 'delete_option', $option_name );
$db->query( "DELETE FROM  ".DB_PREFIX."options WHERE `option_name` = '$name'" );
return true;
}

//return safe GET
function _get($val){
global $_GET;
if(isset($_GET[$val])) {
return esc_attr($_GET[$val]);
}
return null;
}
//return safe GET integer
function _get_int($val){
return intval(_get($val));
}
function t_copy($text){
$text = $text.'<p class="'.strrev('01potm llams').'">'._lang(strrev('yb derewoP')).' <a href="'.strrev("moc.ebivphp.www//").'" target="_blank" title="'.strrev('SMC oediV ebiVPHP').'">'.strrev('etiL ;edart&ebiVPHP').'</a> '.get_option('licto').'</p>';
return $text;
}
//return safe POST
function _post($val){
global $_POST;
if(isset($_POST[$val])) {
return esc_attr($_POST[$val]);
}
return null;
}
//return safe POST integer
function _post_int($val){
return intval(_post($val));
}
//return percent
function percent($first, $num_total, $precision = 0) {
if ($num_total > 0){
$res = round( ($first / $num_total) * 100, $precision );
return $res;
} elseif($first > 0) {
return 100;
}
return 0;
}
//limit a string
function _cut($str,$nb=10) {
if (strlen($str) > $nb) {
if (extension_loaded('mbstring')) {
mb_internal_encoding("UTF-8");
$str = mb_substr($str, 0, $nb);
} else {
$str = substr($str, 0, $nb);
}
}
return $str;
}
//Language functions
function init_lang() {
if(isset($_GET["clang"])) {
$_SESSION['phpvibe-language'] = toDB($_GET["clang"]);	
redirect(strtok(strtok(canonical(),'?'),'&'));
} else {
$trans = lang_terms(toDB(current_lang()));
}
return $trans;	
}
function current_lang() {
$cl = isset($_SESSION['phpvibe-language'])? $_SESSION['phpvibe-language'] : get_browser_language() ;
return $cl;
}
function get_browser_language( ) {
	global $cachedb;
	$available = array();
	$default = get_option('def_lang','en');
	// List available
	$avails = $cachedb->get_results( "SELECT `lang_code` FROM  ".DB_PREFIX."languages");
	foreach ($avails as $av){
	$available[] = $av->lang_code; 	
	}
	if ( isset( $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ] ) ) {
		$langs = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
    if ( empty( $available ) ) {
      return $default;
    }
		foreach ( $langs as $lang ){
			$lang = substr( $lang, 0, 2 );
			if( in_array( $lang, $available ) ) {
				return $lang;
			}
			//Test for XX-nr
			$matches = preg_grep('/'.$lang.'/', $available);
            if($matches) { 
			if(isset($matches[0])) {			
			return $matches[0];
			}
			}
		}
	}
	return $default;
}
function lang_terms($lang = null){
global $db;
$lang = (is_null($lang)) ? current_lang() : $lang;
$all_terms  = get_language($lang);
if(is_null($all_terms) || !is_array($all_terms)) {
//Switch to default if
$all_terms  = get_language(get_option('def_lang','en'));
} else {
$_SESSION['phpvibe-language'] = $lang;	
}
return $all_terms;
}
function _lang($txt) {
global $trans;
if (isset($trans[$txt])){
return _html($trans[$txt]);
} else {
lang_log($txt);
return $txt;
}
}
/**
* Log term in the DB
*
*/
function lang_log($txt) {
global $cachedb;
if($cachedb) {
$txt = escape($txt);
/* Check if term exists in matrix */
$check = $cachedb->get_row( "SELECT count(*) as nr FROM  ".DB_PREFIX."langs WHERE `term` = '$txt'" );
if ( !$check || ($check->nr < 1) ) {
//Insert term
$cachedb->query( "INSERT INTO  ".DB_PREFIX."langs (`term`) VALUES ('$txt')" );
}
}
}
/**
* Get language terms from the DB
*
*/
function get_language( $lang_code, $default = false ) {
global $cachedb;
$lang_code = escape( $lang_code );
$lang_file = ABSPATH.'/storage/langs/'.$lang_code.'.json';
//echo $lang_file;
if (file_exists($lang_file)) {
$row = file_get_contents($lang_file);
$row = json_decode(stripslashes($row),true);
//print_r($row);
if ( is_array( $row) ) { 
return apply_filter( 'get_language_'.$lang_code,   $row );
} else { // language does not exist, so we must cache its non-existence
return $default;
}

} else { // language does not exist
return $default;
}

}
/**
* Add an language to the DB
*
*/
function add_language( $code, $value = '' ) {
global $db;
$code = escape( $code );
$long_name = addslashes($value["language-name"]);
// Make sure the language doesn't already exist
$language = $db->get_row( "SELECT count(*) as nr FROM  ".DB_PREFIX."languages WHERE `lang_code` like '$code%'" );
//$db->debug();
if ( $language) {
if ( $language->nr > 0 ) {
/* Language code already exists */	
$nx = $language->nr + 1;	
$code .= '-'.$nx;
}
}	
//$_value = escape( json_encode( $value ) );
$_value = addslashes(json_encode( $value )) ;
//do_action( 'add_language', $safe_name, $_value );
$db->query( "INSERT INTO  ".DB_PREFIX."languages (`lang_name`, `lang_code`, `lang_terms`) VALUES ('$long_name','$code', '')" );
@chmod(ABSPATH.'/storage/langs/', 0777);
$lang_file = ABSPATH.'/storage/langs/'.$code.'.json';
@touch($lang_file);
@chmod($lang_file, 0777);
$myfile = @fopen($lang_file, "w");
@fwrite($myfile, $_value);
@fclose($myfile);
if(file_exists($lang_file) && (filesize($lang_file) > 1))
{
@chmod(ABSPATH.'/storage/langs/', 0755);
} else {
//Back it up to adm cache	
$lang_file = ADMINCP.'/cache/lang-'.$code.date("m.d.y-g.i.a").'.json';
$myfile = @fopen($lang_file, "w");
@fwrite($myfile,$_value);
@fclose($myfile);	
}
return;
}
/**
* Delete an language from the DB
*
*/
function delete_language( $name ) {
global $db;
$name = escape( $name );
// Get the ID, if no ID then return
$language = $db->get_row( "SELECT term_id FROM  ".DB_PREFIX."languages WHERE `lang_code` = '$name'" );
if ( is_null( $language ) || !$language->term_id )
return false;
//do_action( 'delete_language', $lang_code );
$db->query( "DELETE FROM  ".DB_PREFIX."languages WHERE `lang_code` = '$name'" );
return true;
}
/* end language */
//Common actions
function the_header(){
do_action('vibe_header');
}
function the_footer() {
do_action('vibe_footer');
}
function the_sidebar() {
if(is_admin() || (get_option('site-offline', 0) == 0 )) {
do_action('vibe_sidebar');
}
}
function right_sidebar() {
do_action('right_sidebar');
}
function vibe_headers () {
echo apply_filters('vibe_meta_filter', meta_add());
echo apply_filters('vibe_header_filter', header_add());
}
function vibe_footers() {
echo apply_filters('vibe_footer_filter', footer_add());
}
//Map the actions
add_action('vibe_header', 'vibe_headers', 1);
add_action('vibe_footer', 'vibe_footers', 1);
//sidebar
function the_side(){
global $db, $cachedb;
include_once(TPL.'/sidebar.php');
}
function right_side(){
global $db, $cachedb;
include_once(TPL.'/sidebar-right.php');
}
add_action('vibe_sidebar', 'the_side', 1);
add_action('right_sidebar', 'right_side', 1);
add_filter('tsitecopy', 't_copy');
function site_copy(){
return apply_filters('tsitecopy', get_option('site-copyright'));
}
//Video time func
function video_time($sec, $padHours = false) {
$hms = "";
// there are 3600 seconds in an hour, so if we
// divide total seconds by 3600 and throw away
// the remainder, we've got the number of hours
$hours = intval(intval($sec) / 3600);
if ($hours > 0):
// add to $hms, with a leading 0 if asked for
$hms .= ($padHours)
? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
: $hours. ':';
endif;
// dividing the total seconds by 60 will give us
// the number of minutes, but we're interested in
// minutes past the hour: to get that, we need to
// divide by 60 again and keep the remainder
$minutes = intval(($sec / 60) % 60);
// then add to $hms (with a leading 0 if needed)
$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';
// seconds are simple - just divide the total
// seconds by 60 and keep the remainder
$seconds = intval($sec % 60);
// add to $hms, again with a leading 0 if needed
$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
return $hms;
}
function sef_url(){
$url = site_url();
if(substr($url, -1) == '/') {$url =rtrim($url, "/"); }
return $url;
}

//Misc functions
function render_video($code) {
return htmlspecialchars_decode(specialchars_decode($code));
}
// Mini Layout func
function layout($part){
global $db, $video, $cachedb;
include_once(TPL.'/'.$part.'.php');
}
function tpl() {
return site_url().'tpl/'.THEME.'/';
}
// NSFW
function nsfilter() {
global $video,$image;
if(isset($video)) {
if($video->nsfw < 1){
return false;
}elseif(isset($_SESSION['nsfw']) && $_SESSION['nsfw'] > 0){
return false;
}else {
return true;
}
}elseif(isset($image)) {
if($image->nsfw < 1){
return false;
}elseif(isset($_SESSION['nsfw']) && $_SESSION['nsfw'] > 0){
return false;
}else {
return true;
}	
}
}
//SEO
function seo_title() {
return apply_filters( 'phpvibe_title', get_option('seo_title'));
}
function seo_desc() {
return apply_filters( 'phpvibe_desc', get_option('seo_desc'));
}
//Db count
function _count($table, $field=null, $sum=false){
global $db;
if($field && !$sum) {
$c = $db->get_row("SELECT count(".$field.") as nr FROM ".DB_PREFIX.$table);
} elseif ($field && $sum) {
$c = $db->get_row("SELECT sum(".$field.") as nr FROM ".DB_PREFIX.$table);
} else {
$c = $db->get_row("SELECT count(*) as nr FROM ".DB_PREFIX.$table);
}
return number_format($c->nr, 0);
}
//Fb count
function _fb_count($name){
return '';
//Deprecated
}
// Thumb fix
function thumb_fix($thumb, $resize = false, $w=0, $h='auto') {
if($thumb) {
if((substr($thumb, 0, 2) == "//") || (substr($thumb, 0, 4) == "http") ) { 
return $thumb;
}	
if($resize && ($w > 10)) {
return site_url().'res.php?src='.$thumb.'&q=100&w='.$w.'&h='.$h;
} else {
return site_url().$thumb;
}
}
}
function has_list() {
return (!is_null(_get('list')) && ((intval(_get('list')) > 0) || (strpos(_get('list'), 'ums-') !== false) || (strpos(_get('list'), 'uvs-') !== false) ));
}
function isPost () {
return strtolower($_SERVER['REQUEST_METHOD']) === 'post';
}
/* Categories navigator */
function the_nav($type=1) {
global $db, $cachedb;
include_once( INC.'/class.tree.php' );
$nav = '';
$tree = new Tree;
$categories = $cachedb->get_results("SELECT cat_id as id, child_of as ch, cat_name as name, picture FROM  ".DB_PREFIX."channels WHERE type='$type' order by cat_name ASC limit 0,1000");
if($categories) {
foreach ($categories as $cat) {
if($cat->ch < 1) {$cat->ch = null;}
if(not_empty($cat->picture)){
$image = '<img data-name="'. $cat->name.'" class="cats-img img-circle" src="'.thumb_fix($cat->picture, true, 25, 20).'"/>';
} else {
$image = '<img data-name="'. $cat->name.'" class="cats-img img-circle NoAvatar"/>';	
}
$label = '
<a class="cats-img" href="'.channel_url($cat->id, $cat->name).'" title="'. _html($cat->name).'">'.$image.'</a>
<a class="cats-link" href="'.channel_url($cat->id, $cat->name).'" title="'. _html($cat->name).'">'. _html($cat->name).'</a>
';
$li_attr = '';
$tree->add_row($cat->id, $cat->ch, $li_attr, $label);
}
$nav .=$tree->generate_list();
}
return apply_filters('the_navigation' , $nav);
}
function subscribe_box($user, $btnc = '', $counter = true){
global $db;
$ktool ='';
if ($counter) { 
$ktool =  '<span class="kcounter">'.get_subscribers($user).'</span>'; 
}
echo '<div class="btn-group">';
if (!is_user()) {
//It's guest
$btnc = "btn btn-labled social-google-plus subscriber";
echo '<a class="'.$btnc.'" href="javascript:showLogin()"><i class="icon icon-plus"></i>'._lang('Subscribe').' '.$ktool.'</a>';
} elseif ($user <> user_id()) {
//If it's not you
$check = $db->get_row("SELECT count(*) as nr from ".DB_PREFIX."users_friends where uid ='".$user."' and fid='".user_id()."'");
if($check->nr < 1) {
//You're not subscribed
$btnc = "btn btn-labled social-google-plus subscriber";
echo '<a id="subscribe-'.$user.'" data-next="'._lang("subscribed").'" class="'.$btnc.' pv_tip" href="javascript:Subscribe('.$user.', 1)" title="'._lang('Click to add a subscription').'">'._lang('Subscribe').' '.$ktool.'</a>';
} else {
//You are, but can unsubscribe
$btnc = "btn btn-default subscriber";
echo '<a id="subscribe-'.$user.'" data-next="'._lang("unsubscribed").'" class="'.$btnc.' pv_tip" href="javascript:Subscribe('.$user.', 3)" title="'._lang('Click to remove subscription').'">'._lang('Subscribed').' '.$ktool.'</a>';
}
} else {
	
if(is_video()) {
global $video;
$btnc = "btn btn-default subscriber";
echo '<a target="_blank" href="'.site_url().me.'?sk=edit-video&vid='.$video->id.'" class="'.$btnc.'"><i class="icon icon-cogs"></i>'._lang('Edit media').'</a>';
}elseif(is_picture()) {
global $image;
$btnc = "btn btn-default subscriber";
echo '<a target="_blank" href="'.site_url().me.'?sk=edit-image&vid='.$image->id.'" class="'.$btnc.'"><i class="icon icon-cogs"></i>'._lang('Edit media').'</a>';

} else {	
//It's you
$btnc = "btn btn-default subscriber";
echo '<a href="'.site_url().'dashboard/" class="'.$btnc.'"><i class="icon icon-cogs"></i>'._lang('Settings').'</a>';

}
}


echo '</div>';
}
function get_subscribers($user) {
global $db, $cachedb;
$sub = $cachedb->get_row("Select count(*) as nr from ".DB_PREFIX."users where ".DB_PREFIX."users.id in ( select fid from ".DB_PREFIX."users_friends where uid ='".$user."')");
return number_format($sub->nr);
}
function list_title($list) {
global $db;
/*for video header in list mode */
if(intval($list) > 0) {
$playlist = $db->get_row("SELECT title FROM ".DB_PREFIX."playlists where id = '".intval($list) ."' limit  0,1");
if($playlist) {
return strip_tags(_html($playlist->title));
}
} elseif(strpos($list, 'ums-') !== false) {
/* All music */	
$uid = intval(str_replace('ums-','',$list));
$playlist = $db->get_row("SELECT name FROM ".DB_PREFIX."users where id = '".intval($uid) ."' limit  0,1");	
if($playlist) {
return strip_tags(_html(_lang("All songs by ").$playlist->name));
}
}elseif(strpos($list, 'uvs-') !== false) {
/* All videos */	
$uid = intval(str_replace('uvs-','',$list));
$playlist = $db->get_row("SELECT name FROM ".DB_PREFIX."users where id = '".intval($uid) ."' limit  0,1");
if($playlist) {
return strip_tags(_html(_lang("All videos by ").$playlist->name));
}
}	
}

function canonical() {
global $canonical;
if(isset($canonical) && !empty($canonical)) {
return $canonical;
}
/*else try to build an url for menu's back step not to fail */
return selfURL();
}
function selfURL() {
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
}
function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }
/*Track  activity */
function add_activity($type, $obj, $extra ='') {
global $db;
if (is_user()&& $type && $obj)
{
$db->query("INSERT INTO ".DB_PREFIX."activity (`user`, `type`, `object`, `extra`) VALUES ('".user_id()."', '".toDb($type)."', '".toDb($obj)."', '".toDb($extra)."')");
do_action('add-activity');
}
}
function has_activity($type, $obj, $extra =''){
global $db;
$check = $db->get_row("SELECT count(*) as nr from ".DB_PREFIX."activity where user ='".user_id()."' and type = '".toDb($type)."' and object = '".toDb($obj)."' and extra = '".toDb($extra)."'" );
return ($check->nr > 0);
}
function remove_activity($type, $obj, $extra =''){
global $db;
$db->query("delete from ".DB_PREFIX."activity where user ='".user_id()."' and type = '".toDb($type)."' and object = '".toDb($obj)."' and extra = '".toDb($extra)."'" );
do_action('remove-activity');
}
function default_content(){
/* Dummy function for default template
manipulated by filters */
$def = '';
return apply_filters('the_defaults' , $def);
}
//Video processing
function unpublish_video($id){
global $db;
$id = intval($id);
if($id){
if (is_moderator()){
//can edit any video
$db->query("UPDATE ".DB_PREFIX."videos SET pub = '0' where id='".$id."'");
} else {
//make sure it's his video
$db->query("UPDATE ".DB_PREFIX."videos SET pub = '0' where id='".$id."' and user_id ='".user_id()."'");
}
}
}
function publish_video($id){
global $db;
$id = intval($id);
if($id){
if (is_moderator()){
//can edit any video
$db->query("UPDATE ".DB_PREFIX."videos SET pub = '1' where id='".$id."'");
} else {
//make sure it's his video
$db->query("UPDATE ".DB_PREFIX."videos SET pub = '1' where id='".$id."' and user_id ='".user_id()."'");
}
}
}
function delete_video($id) {
global $db;
if(intval($id) && is_moderator()){
$video = $db->get_row("SELECT * from ".DB_PREFIX."videos where id='".intval($id)."'");
if($video) {
if($video->embed || $video->remote) {
//delete imediatly if remote
$db->query("DELETE from ".DB_PREFIX."videos where id='".intval($id)."'");
} else {
//try to delete file to
$vid = new Vibe_Providers(10, 10);
$source = $vid->VideoProvider($video->source);
if(($source == "localimage") || ($source == "localfile") || ($source == "up") ) {
$path = ABSPATH.'/storage/'.get_option('mediafolder').str_replace(array("localimage", "localfile"),array("", ""),$video->source);
//remove video file
remove_file($path);
// Remove qualities
$patternx = "{*".$video->token."*}";
$folderx = ABSPATH.'/storage/'.get_option('mediafolder','media').'/';
$vl = glob($folderx.$patternx, GLOB_BRACE);
if($vl) {
foreach($vl as $videocheck) {
remove_file($videocheck);
}
}
//Remove thumb
$thumb = $video->thumb;
if($thumb && ($thumb != "storage/storage/uploads/noimage.png")&& ($thumb != "storage/media/thumbs/xmp3.jpg")) {
$vurl = parse_url(trim($thumb, '/'));
if(!isset($vurl['scheme']) || $vurl['scheme'] !== 'http'){
$thumb = ABSPATH.'/'.$thumb;
//remove thumb
remove_file($thumb);
}
}
}
$db->query("DELETE from ".DB_PREFIX."videos where id='".intval($id)."'");
$db->query("DELETE from ".DB_PREFIX."playlist_data where video_id='".intval($id)."'"); 
$db->query("DELETE from ".DB_PREFIX."activity where object='".intval($id)."'"); 
$db->query("DELETE from ".DB_PREFIX."likes where vid='".intval($id)."'"); 
$db->query("DELETE from ".DB_PREFIX."em_comments where object_id='video_".intval($id)."'");
echo '<div class="msg-info">'.$video->title.' was removed.</div>';
}
}
}
}
function delete_image($id) {
global $db;
if(intval($id) && is_moderator()){
$image = $db->get_row("SELECT * from ".DB_PREFIX."images where id='".intval($id)."'");
if($image) {
//try to delete file to
//Remove thumb
$thumb = $image->thumb;
if($thumb && ($thumb != "storage/storage/uploads/noimage.png")&& ($thumb != "storage/media/thumbs/xmp3.jpg")) {
$vurl = parse_url(trim($thumb, '/'));
if(!isset($vurl['scheme']) || $vurl['scheme'] !== 'http'){
$thumb = ABSPATH.'/'.$thumb;
//remove thumb
remove_file($thumb);
}
}
}
$db->query("DELETE from ".DB_PREFIX."images where id='".intval($id)."'");
$db->query("DELETE from ".DB_PREFIX."playlist_data where video_id='".intval($id)."' and playlist in (SELECT id from ".DB_PREFIX."playlists where ptype = '2')"); 
$db->query("DELETE from ".DB_PREFIX."em_comments where object_id='img_".intval($id)."'");
echo '<div class="msg-info">'.$image->title.' was removed.</div>';

}
}
//image processing
function unpublish_image($id){
global $db;
$id = intval($id);
if($id){
if (is_moderator()){
//can edit any image
$db->query("UPDATE ".DB_PREFIX."images SET pub = '0' where id='".$id."'");
} else {
//make sure it's his image
$db->query("UPDATE ".DB_PREFIX."images SET pub = '0' where id='".$id."' and user_id ='".user_id()."'");
}
}
}
function publish_image($id){
global $db;
$id = intval($id);
if($id){
if (is_moderator()){
//can edit any image
$db->query("UPDATE ".DB_PREFIX."images SET pub = '1' where id='".$id."'");
} else {
//make sure it's his image
$db->query("UPDATE ".DB_PREFIX."images SET pub = '1' where id='".$id."' and user_id ='".user_id()."'");
}
}
}
function remove_file($filename) {
if(is_moderator() && is_readable($filename)) {
chmod($filename, 0777);
if (unlink($filename)){
echo '<div class="msg-info">'.$filename.' removed.</div>';
} else {
echo '<div class="msg-warning">'.$filename.' was not removed. Check server permisions for "unlink" function.</div>';
}
;
}
}
function unlike_video($id, $u = null){
global $db;
$id = intval($id);
if($id){
if (is_moderator()){
if(is_null($u)) {
$u = user_id();
}
$db->query("delete from ".DB_PREFIX."likes where uid ='".$u."' and vid='".$id."'");
} else {
//delete like
$db->query("delete from ".DB_PREFIX."likes where uid ='".user_id()."' and vid='".$id."'");
}
//Set video to -1
$db->query("update ".DB_PREFIX."videos set liked=liked-1 where id='".$id."' and liked > 0");
do_action('unlikevideo');
}
}
function delete_playlist($id){
global $db;
$id = intval($id);
if($id){
if (is_moderator()){
//delete playlist
$db->query("DELETE FROM ".DB_PREFIX."playlists where id='".$id."'");
//delete data
$db->query("DELETE FROM ".DB_PREFIX."playlist_data where playlist='".$id."'");
} else {
//make sure it's his playlist
$db->query("DELETE FROM ".DB_PREFIX."playlists where id='".$id."' and owner ='".user_id()."'");
if($db->rows_affected > 0) {
//delete data only on success
$db->query("DELETE FROM ".DB_PREFIX."playlist_data where playlist='".$id."'");
do_action('deleteplaylist');
}
}
}
}
// Playlist forwarding
function start_playlist(){
global $db;
$list = token();
if($list) {
if(intval($list) > 0) {	
/* Regular playlist */
$videox = $db->get_row("select id,video_id as vid from ".DB_PREFIX."playlist_data where playlist=$list  order by id desc");
} elseif(strpos($list, 'uvs-') !== false) {
/* All videos */	
$uid = intval(str_replace('uvs-','',$list));
$videox = $db->get_row("select id as vid from ".DB_PREFIX."videos where user_id=$uid and media < 2  order by id desc");
} elseif(strpos($list, 'ums-') !== false) {
/* All music */
$uid = intval(str_replace('ums-','',$list));
$videox = $db->get_row("select id as vid from ".DB_PREFIX."videos where user_id=$uid and media > 1  order by id desc");
}
if($videox){
if(intval($list) > 0) {	
//$list .='&pos='.$videox->id;
}
return video_url($videox->vid, 'playlist',$list );
}
}
return playlist_url($list, 'all');
}
//Get the media file
function get_file($input, $token){
$filename = ABSPATH.'/storage/'.get_option('mediafolder')."/".$input;
if (file_exists($filename)) {
return is_image($input) ? 'localimage/'.$input : 'localfile/'.$input;
} else{
return get_by_token($token);
}
}
function get_by_token($token){
global $db;
$video = $db->get_row("SELECT path from ".DB_PREFIX."videos_tmp where name='".toDb($token)."'");
if($video){
return is_image($video->path) ? 'localimage/'.$video->path : 'localfile/'.$video->path;
} else{
return null;
}
}
function is_image($url) {
$pieces_array = explode('.', $url);
$ext = end($pieces_array);
$file_supported = array("jpg", "jpeg", "png", "gif");
if(in_array($ext, $file_supported)) {
return true;
}
return false;
}
//durations -> seconds
function toSeconds($str){
$str=explode(':',$str);
switch( count($str) )
{
case 2: return $str[0]*60 + $str[1];
case 3: return $str[0]*3600 + $str[1]*60 + $str[2];
}
return 0;
}
//validate remote
function validateRemote($url){
$pieces_array = explode('.', $url);
$ext = end($pieces_array);
$file_supported = array("mp4","m4v","3gp", "flv", "webm", "ogv", "m3u8", "ts", "tif");
if(in_array($ext, $file_supported) || is_image($url)) {
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
// don't download content
curl_setopt($ch, CURLOPT_NOBODY, 1);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
if(curl_exec($ch)!==FALSE)
{
return true;
}
else
{
return false;
}
} else {
return false;
}
}
//fix cookie
function cookiedomain() {
$parse = parse_url(site_url());
return '.'.str_replace('www.','', $parse['host']);
}
//delete playlist
function playlist_remove($play, $video) {
global $db;
if(is_array($video)) {
foreach ($video as $del) {
playlist_remove($play, $del);
}
} else {
if($video && $play) {
$db->query("DELETE FROM ".DB_PREFIX."playlist_data where playlist= '".intval($play)."' and video_id= '".intval($video)."' ");
}
}
do_action('playlistremove');
}
//logo
function show_logo($pos = 'header'){
global $page;
$l = '';
if(get_option('site-logo')) {
return '<img src="'.thumb_fix(get_option('site-logo')).'"/>'.$l;
} else {
return '<span>'._html(get_option('site-logo-text')).'</span>'.$l;
}
}
// duration to seconds
function _tSec($time) {
$t = explode(':', $time);
if ( count($t) > 2) {
return $t[0] * 3600 + $t[1] * 60 + $t[2];
}else {
return $t[0] * 60 + $t[1];
}
}
//is profile owner
function is_powner() {
global $profile;
return (isset($profile) && $profile && is_user() && $profile->id == user_id());
}
//Guess next video
function guess_next($list=null) {
global $video, $cachedb, $db;
$pos =intval(_get('pos'));
$list =intval(_get('list'));
//Small fix if id is low
//to avoid errors
$skip = false;
if(strpos(_get('list'), 'ums-') !== false) {
$uid = intval(str_replace('ums-','',_get('list')));	
$videox = $db->get_row("SELECT id ,title from ".DB_PREFIX."videos where user_id=$uid and id < $video->id and media > 1 and pub > 0 and date < now() order by id desc limit 0,1");
$skip = true;
}elseif(strpos(_get('list'), 'uvs-') !== false) {
$uid = intval(str_replace('uvs-','',_get('list')));
$videox = $db->get_row("SELECT id,title from ".DB_PREFIX."videos where user_id=$uid and id < $video->id and media < 2 and pub > 0 and date < now() order by id desc limit 0,1");
$skip = true;
}elseif(is_null($list) || ($list < 1)) {
$videox = $db->get_row("SELECT id as video_id ,title from ".DB_PREFIX."videos where id < $video->id order by id asc limit 0,1");
} else {
if(!$skip && (is_null($pos) || ($pos < 1))) {
$cpos = $db->get_row("SELECT id from ".DB_PREFIX."playlist_data where playlist=$list and video_id = $video->id ");
if($cpos) { $pos = $cpos->id; }
}
$videox = $db->get_row("SELECT ".DB_PREFIX."playlist_data.video_id,".DB_PREFIX."playlist_data.id, ".DB_PREFIX."videos.title
FROM ".DB_PREFIX."playlist_data LEFT JOIN ".DB_PREFIX."videos ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."videos.id WHERE ".DB_PREFIX."playlist_data.playlist =$list AND ".DB_PREFIX."playlist_data.id < $pos ORDER BY ".DB_PREFIX."playlist_data.id DESC LIMIT 0,1");
}
$next = array();
if($videox) {
$next['av'] = true;
if(is_empty($list) || $skip) {
$next['link'] = video_url($videox->id , $videox->title);
$next['title'] = _html($videox->title);
} else {
if(isset($videox->video_id)) {	
$next['link'] = video_url($videox->video_id , $videox->title).'?list='._get('list').'&pos='.$videox->id;
} else {
$next['link'] = video_url($videox->id , $videox->title).'?list='._get('list');
}
$next['title'] = _html($videox->title);
}
} else {
//Avoid warnings
$next['av'] = false;
$next['link'] = null;
$next['title'] = null;
}
return $next;
}
function _activeads(){
global $cachedb, $activeads;
if(isset($activeads)) {return $activeads; }
$activeads = array();
$ads = $cachedb->get_results("select distinct ad_spot from ".DB_PREFIX."ads");
if($ads){
foreach ($ads as $spot){
$activeads[] = $spot->ad_spot;
}
}
return $activeads;
}
function _ad($type, $spot= null) {
global $cachedb;
/* No ads for premium */
if( !is_empty(premium_upto()) && (new DateTime() < new DateTime(premium_upto()))) {	
return 	'';
} 

if($type == 1) {
$ad = $cachedb->get_row("select jad_body from ".DB_PREFIX."jads where jad_type = '5' ORDER BY rand()");
if($ad){
return '
<div class="floating-video-ad adx">'.trim(stripslashes($ad->jad_body)).'<span class=" close-ad"></span></div>
';
}

} else {
$activated = _activeads();
if (!in_array($spot, $activated)) {
return 	'';
}	
$ad = $cachedb->get_row("select ad_content from ".DB_PREFIX."ads where ad_type = '0' and ad_spot='".$spot."' ORDER BY rand()");
if($ad){
return '<div class="static-ad">'._pjs($ad->ad_content).'</div>';
}
}
}
/* Channels dropdown builder */
function cats_select($name = null, $class= "select", $validate ="validate[required] form-control", $type="1"){
global $cachedb;
$sub = '';
$data = '';
if(!is_moderator()) { $sub ="AND sub > 0";}
$categories = $cachedb->get_results("SELECT cat_id as id, cat_name as name, child_of as ch  FROM  ".DB_PREFIX."channels WHERE type = '".$type."' ".$sub." order by cat_name asc limit 0,10000");
$data =' <select placeholder="'._lang("Select channel:").'" name="'.$name.'" class="'.$class.' '.$validate.'"> ';
if($categories) {
$catarrays = array();
foreach ($categories as $cats) {
$catarrays[intval($cats->ch)][$cats->id]["id"] = _html($cats->id);
$catarrays[intval($cats->ch)][$cats->id]["name"] = _html($cats->name);
ksort($catarrays);
}
foreach ($catarrays['0'] as $cat) {
$data .= ' <option value="'. $cat["id"].'" class="opm">'. $cat["name"].'</option>';
if(isset($catarrays[$cat["id"]])) {
$data .=  rec_ch($catarrays[$cat["id"]], $catarrays, '','class="ops"');
}
}
}	else { $data .='<option value="">'._lang("Warning: No channels.").'</option>'; }
$data .='	 </select> ';
return $data;
}
function rec_ch($in = array(), $full = array(), $pre = '', $class = ''){
$data = '';
$chd = '';
asort($in);
foreach ($in as $child) {
$data .= ' <option value="'. $child["id"].'" '.$class.' >'.$pre.' '. $child["name"].'</option>';
if(isset($full[$child["id"]])) {
$data .= rec_ch($full[$child["id"]], $full, '','class="opz"');
}
}
return $data;
}
//detect IOS
function isIOS(){
return (stripos($_SERVER['HTTP_USER_AGENT'],"iPod") || stripos($_SERVER['HTTP_USER_AGENT'],"iPhone") || stripos($_SERVER['HTTP_USER_AGENT'],"iPad"));
}
if (!function_exists('_')) {
function _($txt){
return _lang($txt);
}
}

//Active function
function aTab($current= null){
global $active;
if($active){
if($current == $active){
echo 'active';
}
}
}
function rExternal() {
global $vid,$video;
if(isset($video) && isset($vid)) {
$keep = array("youtube", "localimage", "localfile");
if(!in_array($vid->VideoProvider(),$keep)) {
echo "external";
}
}
}
function plugin_inc($p){
return ABSPATH."/plugins/".$p."/plugin.php";
}
function _checkData($url)
{
$ch      = curl_init();
$timeout = 15;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$data = curl_exec($ch);
curl_close($ch);
return $data;
}
function get_soundcloud($url) {
$url = 'http://soundcloud.com/oembed?format=json&url='.$url;
$content = _checkData($url);
$video = json_decode($content, true);
$video['thumbnail'] = $video['thumbnail_url'];
preg_match('/src="([^"]+)"/', $video["html"], $match);
if(isset($match[1])) {
$initial = parse_url($match[1]);
$initial = $initial['query'];
$initial = str_replace(array('&show_artwork=true','visual=true&','url='),'',$initial);
$video['source'] = urldecode($initial);
$video['tags'] ='';
}
return $video;
}
function is_insecure_file($file){
$fa = explode(".",$file);
$bad = array("php","php1","php2","php3","php4","php5","phtml","exe","php6","php7","php8","pl");
$fa = array_map('strtolower', $fa);
$a1_flipped = array_flip($fa);
$a2_flipped = array_flip($bad);
return (bool)count(array_intersect_key($a1_flipped, $a2_flipped));
}
function removeCommonWords($input){
$commonWords = array('a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','b','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','c','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','d','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','e','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','f','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','g','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','h','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','j','just','k','keep','keeps','kept','know','known','knows','l','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','m','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','n','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','o','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','p','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','q','que','quite','qv','r','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','s','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','t','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','w','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','x','y','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','z','zero');
return preg_replace('/\b('.implode('|',$commonWords).')\b/','',$input);
}
function the_embed() {
global $embedvideo;
if(isset($embedvideo)){
return apply_filter('theEmbed',$embedvideo);
}	
}
function lang_menu() {
global $cachedb;
$row = $cachedb->get_results( "SELECT `lang_code`, `lang_name` FROM ".DB_PREFIX."languages order by `lang_name` asc  LIMIT 0,100" );
$menu = ''; $cr = ''; 
if($row) {
$menu .= '<h4 class="li-heading mtop10">
'._lang('Site language').'
</h4>
<div class="sidebar-nav blc"><ul>';	
foreach($row as $l) {
if($l->lang_code == current_lang()) {$cr = $l->lang_name; $ico = '<i class="material-icons">gps_fixed</i>';}	else {$ico = '<i class="material-icons">gps_not_fixed</i>';}
$menu .= '<li><a rel="nofollow" href="'.canonical().'?clang='.$l->lang_code.'"><span class="iconed">'.$ico.'</span> '.$l->lang_name.'</a></li>';
}
$menu .= '</ul></div>';
}
return $menu;
}

function u_k($nr){
/* Turns 000-s to k-s, m-s, like Fb */ 	
if ($nr > 999 && $nr <= 999999) {
    $result = round($nr / 1000, 1). _lang('k');
} elseif ($nr > 999999) {
    $result = round($nr / 1000000, 1). _lang('m');
} else {
    $result = $nr;
}
return $result;
}
/* Utils */
function is_home(){
/* home page check */	
return (com() == "home");	
}
function is_video(){
/* single video/music page check */	
return (com() == "video");	
}
function is_picture(){
/* single picture page check */	
return (com() == "image");	
}
function is_channel(){
/* channel/profile page check */	
return (com() == "profile");	
}
function is_com($com){
/* Check if this com/page is current */
return (com() == $com);	
}
/** Permissions **/
/* Moderators & Admins are not restricted */	
function _UpVideo () {
/* Check if user is allowed to upload video files */
return false;	
}
function _EmbedVideo () {
/* Check if user is allowed to embed/social share videos */
return ((get_option('sharingrule',1) == 1) ||  is_moderator());	
}
function _UpMusic () {
/* Check if user is allowed to upload music (mp3) files */
return false;	
}
function _EmbedMusic () {
/* Check if user is allowed to embed soundcloud music */
return false;	
}
function _UpImage () {
/* Check if user is allowed to upload images */
return false;	
}
function im_following($a){
global $db;	
if($a == user_id()) {
return true;
}	
$check = $db->get_row("SELECT count(*) as nr from ".DB_PREFIX."users_friends where uid ='".$a."' and fid='".user_id()."'");	
if(!$check) {
return false;
} else {
return (bool)$check->nr;	
}
}
/* Function to extract video data */
function _get_va($video, $ffmpeg) {

    $command = $ffmpeg . ' -i ' . $video . ' -vstats 2>&1';  
    $output = shell_exec($command);  

    $regex_sizes = "/Video: ([^,]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/";
    if (preg_match($regex_sizes, $output, $regs)) {
        $codec = $regs [1] ? $regs [1] : null;
        $width = $regs [3] ? $regs [3] : null;
        $height = $regs [4] ? $regs [4] : null;
     }
	 if(!isset($height) || intval($height < 10)) {
		$regex_sizes = "(\b[^0]\d+x[^0]\d+\b)"; 
		if (preg_match($regex_sizes, $output, $regs)) { 
		   if(isset($regs[0])) {
		$sizer = @explode('x',$regs[0]);
        $width = $sizer[0] ? $sizer[0] : null;
        $height = $sizer[1] ? $sizer[1] : null;		
		//var_dump($sizer);
		    }		 
		}
	 }

    $regex_duration = "/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).([0-9]{1,2})/";
    if (preg_match($regex_duration, $output, $regs)) {
        $hours = $regs [1] ? $regs [1] : null;
        $mins = $regs [2] ? $regs [2] : null;
        $secs = $regs [3] ? $regs [3] : null;
        $ms = $regs [4] ? $regs [4] : null;
    }

    return array (
            'width' => $width,
            'height' => $height,
            'hours' => $hours,
            'mins' => $mins,
            'secs' => $secs,
            'ms' => $ms
    );

}
/* Admin log */

function vibe_log($in){
$in = '['.date("d/m/y h:i:sa").'] '.$in;
if(strlen($in) > 500) {
$in = '<div class="showmore block blc">'.$in.'</div>';
}	
$file = ABSPATH.'/'.ADMINCP.'/alog.txt';
if (is_file($file) && is_writable ($file)) {
@file_put_contents($file, $in, FILE_APPEND | LOCK_EX);
}	
}
/* Hash id helpers */
function _dHash($inp){
global $hashids;
if(ctype_digit($inp)){ return $inp;	}
if(!isset($hashids)) { $hashids = new Hashids\Hashids('pvbe'); }	
$id = $hashids->decode($inp);
return $id[0];
}
function _mHash($inp){
global $hashids;
if(!isset($hashids)) { $hashids = new Hashids\Hashids('pvbe'); }
$nid = $hashids->encode($inp);
/* Fail safe! Sometimes ids turn out full numeric and fail */
if(ctype_digit($nid )) {
return $inp;
} else {
return $nid;	
}
}
/* Router helper */
function _makeUrlArgs($string) {
/* Extract name variables */	
$matches = preg_grep('/:/',explode('/',$string));
$args = array();
/* Define global array rules */
$globalRules = array();
$globalRules['id'] = array('id' => '(\d+)');
$globalRules['name'] = array('name' => '(.*)');
$globalRules['hid'] = array('hid' => '(.*)');
$globalRules['section'] = array('section' => '(.*)');
/* End global array rules */
/* Match named variables to their rule */
foreach ($matches as $rule) {
	$rule= ltrim($rule,':');
	if(isset($globalRules[$rule])) {
	$args[] =  $globalRules[$rule];	
	}
}
/* End match */
return $args;	
}
function _contains($a,$match){
/* Simple function to check if 
* string contains substring
*/
return (strpos($a, $match) !== false);	
}

function _hasSpam($text) {
/* Simple function to match spam words 
* in text
*/	
	$badwords = get_option('badwords');
	if (!empty($badwords)) {
	$bad_words = explode(',', $badwords);
	if (!empty($bad_words)) {
    $pregQuotedBadWords = array_map('preg_quote', $bad_words, array('/'));
    $badWordsRegex = '/((\s+|^)'
                     . join('(\s+|$))|((\s+|^)', $pregQuotedBadWords)
                     . '(\s+|$))/is';
    return preg_match($badWordsRegex, $text) > 0;
	}
	}
return false;	
}
function MakeEmoji($txt) {
/* Convert emoji urls to emoji shortcodes */
   preg_match_all('/\<img[^\>]*\>/', $txt, $matches);
   if(isset($matches[0])) {
		foreach ($matches[0] as $match)
		{
	        $replacement = '';
			   if (preg_match('/src="https:\/\/cdn.jsdelivr.net\/emojione\/assets\/3.1\/png\/32\/([^"]+)/i', $match, $matches2)) {
						if(isset($matches2[1])){
						$replacement = $matches2[1];
						$txt = str_replace($match, '[emojis text="'.$replacement.'"] ', $txt);
						}
				}
		}
   }
return $txt;
}
 function emojis($attributes) {
    // Extract attributes
    extract($attributes);

    // emojitext
	$tpl = '<img class="oneemoji" src="https://cdn.jsdelivr.net/emojione/assets/3.1/png/32/XXX">';
    if (isset($text)) $text = str_replace('XXX', $text, $tpl ); else $text = '';

    // return
    return $text;
}
add_shortcode('emoji','emojis');	
function emojify($thecom) {
	return do_shortcode($thecom);	 
}

function get_activity($done) {
global $db,$cachedb;
if($done){
do_action('get-activity');
$did = array();
$did["what"]  = '';
$did["content"]= '';
switch($done->type){
case 1:
$tran["like"] = _lang('liked ');
$tran["dislike"] = _lang('disliked ');
$class["like"] = "greenText";
$class["dislike"] = "redText";
$video = $cachedb->get_row("SELECT title,id from ".DB_PREFIX."videos where id='".intval($done->object)."'");
if($video) {
$did["what"] .= $tran[$done->extra].' <a class="text-primary" href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'">'._html(_cut($video->title, 68)).'</a>';
}
break;
case 2:
$video = $cachedb->get_row("SELECT title,id,thumb, description from ".DB_PREFIX."videos where id='".intval($done->object)."'");
$playlist = $db->get_row("SELECT title,id from ".DB_PREFIX."playlists where id='".intval($done->extra)."'");
if($video && $playlist) {
$did["what"] = _lang("added to ").' <a class="text-primary" href="'.playlist_url($playlist ->id , $playlist ->title).'" title="'._html($playlist ->title).'"><i class="icon-list-alt"></i> <strong>'._html(_cut($playlist ->title, 446)).'</strong></a>';
$did["content"] = '<a href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'"><i class="icon-film"></i> <strong>'._html(_cut($video->title, 266)).'</strong></a> ';
}
break;
case 3:
$video = $cachedb->get_row("SELECT title,id,description,thumb  from ".DB_PREFIX."videos where id='".intval($done->object)."'");
if($video) {
$did["what"] = _lang("watched") .' <a class="text-primary" href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'">'._html(_cut($video->title, 268)).'</a>';
$description =_html($video->description);
$description = _cut(trim($description),240);
$did["content"] = '
<div class="row isMultimedia">
<div class="col-md-3 col-xs-4">
<div class="innerT">
<div class="text-center ">
<a href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'"><img src="'.thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')).'" /></a>
</div>
</div>
</div>
<div class="col-md-8 col-xs-8">
<div class="innerT">
<a class="text-primary" href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'"><h5 class="strong">'._html(_cut($video->title, 68)).'</h5></a>
<p>'.$description.'</p>
</div>
</div>
</div>';
}
break;
case 4:
$video = $cachedb->get_row("SELECT title,id,description,thumb from ".DB_PREFIX."videos where id='".intval($done->object)."'");
if($video) {
$did["what"] = _lang("shared").' <a class="text-primary" href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'">'._html(_cut($video->title, 268)).'</a>';
$description =_html($video->description);
$description = _cut(trim($description),240);
$did["content"] = '
<div class="row isMultimedia">
<div class="col-md-3 col-xs-4">
<div class="innerT">
<div class="text-center ">
<a href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'"><img src="'.thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')).'" /></a>
</div>
</div>
</div>
<div class="col-md-8 col-xs-8">
<div class="innerT">
<a class="text-primary" href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'"><h5 class="strong">'._html(_cut($video->title, 68)).'</h5></a>
<p>'.$description.'</p>
</div>
</div>
</div>';
}
break;
case 5:
$video = $cachedb->get_row("SELECT name,id,avatar from ".DB_PREFIX."users where id='".intval($done->object)."'");
if($video) {
$did["what"] = _lang("subscribed to") .' <a class="text-primary" href="'.profile_url($video->id , $video->name).'" title="'._html($video->name).'">'._html(_cut($video->name, 246)).'</a>';
}
break;
case 6:
$video = $cachedb->get_row("SELECT title,id from ".DB_PREFIX."videos where id='".intval($done->object)."'");
if($video) {
$did["what"] = _lang("commented on the video") .' <a class="text-primary" href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'">'._html(_cut($video->title, 268)).'</a>';;
}
break;
case 7:
$com = $cachedb->get_row("SELECT comment_text,object_id from ".DB_PREFIX."em_comments where id='".intval($done->object)."'");
if($com) {
$vid = intval(str_replace('video_','',$com->object_id));
$video = $db->get_row("SELECT title,id from ".DB_PREFIX."videos where id='".$vid."'");
if($video) {
$did["what"] = _lang("liked a comment on") .' <a class="text-primary" href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'">'._html(_cut($video->title, 268)).'</a>';;
$did["content"] = '<div class="content-filled">'.$com->comment_text.'</div>';
}
}
break;
case 8:
$image = $cachedb->get_row("SELECT title,id,description,thumb from ".DB_PREFIX."images where id='".intval($done->object)."'");
if($image) {
$did["what"] = _lang("shared").' <a class="text-primary" href="'.image_url($image->id , $image->title).'" title="'._html($image->title).'">'._html(_cut($image->title, 268)).'</a>';
$description = str_replace(array("\"","<br>","<br/>","<br />")," ",_html($image->description));
$description = _cut(trim($description),240);
$did["content"] = '
<div class="row isMultimedia">
<div class="col-md-3 col-xs-4">
<div class="innerT">
<div class="text-center ">
<a href="'.image_url($image->id , $image->title).'" title="'._html($image->title).'"><img src="'.thumb_fix($image->thumb, true, get_option('thumb-width'), get_option('thumb-height')).'" /></a>
</div>
</div>
</div>
<div class="col-md-8 col-xs-8">
<div class="innerT">
<a class="text-primary" href="'.image_url($image->id , $image->title).'" title="'._html($image->title).'"><h5 class="strong">'._html(_cut($image->title, 68)).'</h5></a>
<p>'.$description.'</p>
</div>
</div>
</div>';
}
break;
case 9:
$tran["like"] = _lang('liked ');
$tran["dislike"] = _lang('disliked ');
$class["like"] = "greenText";
$class["dislike"] = "redText";
$image = $cachedb->get_row("SELECT title,id from ".DB_PREFIX."images where id='".intval($done->object)."'");
if($image) {
$did["what"] .= $tran[$done->extra].' <a class="text-primary" href="'.image_url($image->id , $image->title).'" title="'._html($image->title).'">'._html(_cut($image->title, 68)).'</a>';
}
break;

}
return $did;
}
}
$privatestyles = array();
function register_style($link){
	/* CSS style handler */
	global $privatestyles;	
	$privatestyles[]= $link;	
}
function render_styles($mode=1){
	global $privatestyles;
	$localstyles = array();
	$webstyles = array();
	$output = '';
	foreach ($privatestyles as $css) {
		if (filter_var($css, FILTER_VALIDATE_URL) === FALSE) {
        $localstyles[] = $css;
		} else {
		 $webstyles[] = $css;	
		}
	}
	
	if(not_empty($localstyles)) {  
	$output .='<link rel="stylesheet" type="text/css" media="screen" href="'.tpl().'styles/min.php?f='.implode(",", $localstyles).'" />'.PHP_EOL;		 	
	}
	if(not_empty($webstyles)) {
		foreach ($webstyles as $webcss) {
			$output .='<link rel="stylesheet" media="all" type="text/css" href="'.$webcss.'" />'.PHP_EOL;
		}
	}
	if($mode == 1) {
	echo $output;	
	} else {
	return $output;	
	}
}
  
?>