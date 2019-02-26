<?php $id = token_id();
if($id > 0) {
$playlist = $db->get_row("SELECT * FROM ".DB_PREFIX."playlists where id = '".$id ."' limit  0,1");
}
if ($playlist) {
// Canonical url
$canonical = playlist_url($playlist->id , $playlist->title);   
// SEO Filters
function modify_title( $text ) {
global $playlist;
    return strip_tags(stripslashes(get_option('seo-playlist-pre','').$playlist->title.get_option('seo-playlist-post','')));
}
function modify_desc( $text ) {
global $playlist;
    return _cut(strip_tags(stripslashes($playlist->description)), 160);
}
add_filter( 'phpvibe_title', 'modify_title' );
add_filter( 'phpvibe_desc', 'modify_desc' );
/*Now to the actual channel page */
if (!is_ajax_call()) { 
the_header();
the_sidebar();
}
include_once(TPL.'/playlist.php');
the_footer();
//Increase views
$db->query("UPDATE ".DB_PREFIX."playlists SET views = views+1 WHERE id = '".$playlist->id."'");
} else {
//Oups, not found
layout('404');
}
?>