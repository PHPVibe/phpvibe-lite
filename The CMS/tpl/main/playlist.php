<div id="playlist-content" class="main-holder pad-holder col-md-12 top10 nomargin">
<?php 
$heading = '';
$heading_meta = '';
$heading_meta .= '
<div class="media row right20 left10 playlist-head mtop20">
<div class="media-left">
<div class="avatar">';
if(not_empty($playlist->picture)) {
$heading_meta .= '<img class="pic img-circle" src="'.thumb_fix($playlist->picture, true, 60, 60).'" />';
} else {
$heading_meta .= '<img class="pic NoAvatar img-circle" data-name="'.trim($playlist->title).'" src="" />';
	
}
$heading_meta .= '
</div>
</div>
<div class="media-body">';
if($playlist->ptype ==1) {
$heading_meta .= '
<a class="btn btn-primary tipN pull-right" title="'._lang("Play all").'" href="'.site_url().'forward/'.$playlist->id.'/"><i class="icon icon-play-circle"></i>  '._lang("Play all").'</a>
';
}
$heading_meta .= '
<h1 class="media-heading">
'._html($playlist->title).'
</h1>
<p>
<small> '._html($playlist->description).'</small>
</p>
</div></div>

';

if($playlist->ptype ==1) {
$options = DB_PREFIX."videos.id,".DB_PREFIX."videos.media,".DB_PREFIX."videos.title,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.liked,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw";
$vq = "SELECT ".DB_PREFIX."videos.id, ".DB_PREFIX."videos.title, ".DB_PREFIX."videos.user_id, ".DB_PREFIX."videos.thumb, ".DB_PREFIX."videos.views, ".DB_PREFIX."videos.liked, ".DB_PREFIX."videos.duration, ".DB_PREFIX."videos.nsfw, ".DB_PREFIX."users.name AS owner
FROM ".DB_PREFIX."playlist_data
LEFT JOIN ".DB_PREFIX."videos ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."videos.id
LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id
WHERE ".DB_PREFIX."playlist_data.playlist =  '".$playlist->id."'
ORDER BY ".DB_PREFIX."playlist_data.id DESC ".this_offset(bpp());
include_once(TPL.'/video-loop.php');
} else {
$options = DB_PREFIX."images.id,".DB_PREFIX."images.title,".DB_PREFIX."images.user_id,".DB_PREFIX."images.thumb";
$vq = "SELECT $options, ".DB_PREFIX."users.name AS owner, ".DB_PREFIX."users.avatar
FROM ".DB_PREFIX."playlist_data
LEFT JOIN ".DB_PREFIX."images ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."images.id
LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."images.user_id = ".DB_PREFIX."users.id
WHERE ".DB_PREFIX."playlist_data.playlist =  '".$playlist->id."'
ORDER BY ".DB_PREFIX."playlist_data.id DESC ".this_offset(bpp());
echo '<div id="imagelist-content mbot20">';	
include_once(TPL.'/images-loop.php');	
echo '</div>';
}
?>
</div>
