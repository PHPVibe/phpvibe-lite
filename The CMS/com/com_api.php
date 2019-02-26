<?php  //Notifications
if(is_user() && (token() == "noty")) {
if(isset($_SESSION['lastNoty'])) {
if(($_SESSION['lastNoty'] - time()) < 1  ) { $continue = true;  } else { $continue = false;}
} else {
$continue = true;
}
$count = array("msg" => 0, "buzz" =>0);
if($continue){
$notif = $db->get_row("Select count(*) as nr from ".DB_PREFIX."activity where ((type not in (8,9) and ".DB_PREFIX."activity.object in (select id from ".DB_PREFIX."videos where user_id ='".user_id()."' ) ) or (type in (8,9) and ".DB_PREFIX."activity.object in (select id from ".DB_PREFIX."images where user_id ='".user_id()."' ) ) and user <> '".user_id()."') and `date` > '".user_noty()."'");
if($notif) {
$count["buzz"] = $notif->nr;	
}
$lists = $db->get_row("select count(case when read_at = 0 and (by_user <> '".user_id()."') then 1 else null end) as unread  from ".DB_PREFIX."conversation p1 INNER JOIN ( SELECT * FROM ".DB_PREFIX."con_msgs order by at_time desc ) p2 on p2.conv = p1.c_id  where ((p1.user_one='".user_id()."') OR (p1.user_two='".user_id()."'))");

if($lists) {
$count["msg"] = $lists->unread;	
}

$_SESSION['lastNoty'] = time();	
}
echo json_encode($count);
//End notifications
LastOnline();
exit();	
}
if(token() == "categories") {
$list = _get('list');
echo '
<div class="cats cats-fixed-right">
<div class="cats-inner">
'.the_nav($list).'
</div>
</div>
';	
exit();	
}
if(token() == "autoplay") {
if(isset($_SESSION['autoplayoff'])) {
unset($_SESSION['autoplayoff']);
} else {
$_SESSION['autoplayoff'] = 1;
}
exit();	
}

/* Playlist data */
if(token() == "playlist") {
$list = _get('list');
    if(is_empty($list)) {
	exit("Bad list id");
    }
$video = new stdClass();
$video->id = _get('videoid');
$video->user_id = _get('idowner');
$video->owner =  getUserName(_get('idowner'));
//print_r($video);
echo '<ul>';

layout('layouts/list');
echo '</ul>';
}
/* End list items */
/* Related videos */
if(token() == "relatedvids") {	
$video_id = _get('videoid');
if(is_empty($video_id)) {
	exit("Bad video id");
}
$titles = $cachedb->get_row("SELECT tags,title FROM ".DB_PREFIX."videos where id= '".$video_id."'");
$video = new stdClass();
$video->category = _get('videocategory');
$video->media = _get('videomedia');
$video->id = $video_id;
$video->title = $titles->title;
$video->tags= $titles->tags;
echo '<ul>';
layout('layouts/related');
echo '</ul>';
}
/* End related videos */

//Hooks
do_action('phpvibe-api');

?>