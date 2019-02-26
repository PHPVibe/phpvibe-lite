<?php $options = "id,title,media,user_id,thumb,views,liked,duration,nsfw, description"; 
$tq= "select ".$options.", '".toDb($profile->name)."' as owner FROM ".DB_PREFIX."videos WHERE views > 0 and pub > 0 and date < now() and user_id ='".$profile->id."' AND MONTH(date) = MONTH(CURDATE( )) ORDER BY views DESC ".this_offset(2);
$trending = $db->get_results($tq);
if($trending) {
echo '<h4 class="loop-heading"><span>'._lang('Trending').'</span></h4>
<div id="SearchResults" class="loop-content phpvibe-video-list vTrends">'; 
foreach ($trending as $video) {
			$title = _html(_cut($video->title, 70));
			$full_title = _html(str_replace("\"", "",$video->title));			
			$url = video_url($video->id , $video->title);
			$watched = (is_watched($video->id)) ? '<span class="vSeen">'._lang("Watched").'</span>' : '';
			$liked = (is_liked($video->id)) ? '' : '<a class="heartit  pv_tip" data-toggle="tooltip" data-placement="left" title="'._lang("Like this video").'" href="javascript:iLikeThis('.$video->id.')"><i class="icon-heart"></i></a>';
            $wlater = (is_user()) ? '<a class="laterit pv_tip" data-toggle="tooltip" data-placement="right" title="'._lang("Add to watch later").'" href="javascript:Padd('.$video->id.', '.later_playlist().')"><i class="icon-plus-square-o"></i></a>' : '';
			$description = str_replace(array("\"","<br>","<br/>","<br />")," ",_html($video->description));
            $description = _cut(trim($description),500);
			if(empty($description)) {$description = $full_title;} 
			echo '
<div id="video-'.$video->id.'" class="video">
<div class="video-inner">
<div class="video-thumb">
		<a class="clip-link" data-id="'.$video->id.'" title="'.$full_title.'" href="'.$url.'">
			<span class="clip">
				<img src="'.thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')).'" alt="'.$full_title.'" /><span class="vertical-align"></span>
			</span>
          	<span class="overlay"></span>
		</a>'.$liked.$watched.$wlater;
if($video->duration > 0) { echo '   <span class="timer">'.video_time($video->duration).'</span>'; }
echo '</div>	
<div class="video-data">
	<h4 class="video-title"><a href="'.$url.'" title="'.$full_title.'">'._html($title).'</a></h4>
	<p class="small">'.$description.'</p>
<ul class="stats">	
<li>		'._lang("by").' <a href="'.profile_url($video->user_id, $video->owner).'" title="'.$video->owner.'">'.$video->owner.'</a></li>
 <li>'.$video->views.' '._lang('views').'</li>';
if(isset($video->date)) { echo '<li>'.time_ago($video->date).'</li>';}
echo '</ul>
</div>	
	</div>
		</div>
';
}

echo '</div>';
}
?>
<div class="clearfix"></div>

<?php
$vq = "select ".$options.", '".$profile->name."' as owner FROM ".DB_PREFIX."videos WHERE pub > 0 and date < now() and media < 2 and user_id ='".$profile->id."' ORDER BY id DESC ".this_offset(6);
$uploads = $db->get_results($vq);
if($uploads) {
echo '<h4 class="loop-heading"><span>'._lang("Videos").' ('.number_format($vd->nr - $md->nr).')</span> <a href="'.site_url().'forward/uvs-'.$profile->id.'/" class="btn btn-default btn-xs pull-right"><i class="icon icon-play"></i> '._lang("Play all").'</a></h4>';	
echo '<div id="SearchResults" class="loop-content phpvibe-video-list vTrends bottom20">'; 
foreach ($uploads as $video) {
			$title = _html(_cut($video->title, 70));
			$full_title = _html(str_replace("\"", "",$video->title));			
			$url = video_url($video->id , $video->title);
			$watched = (is_watched($video->id)) ? '<span class="vSeen">'._lang("Watched").'</span>' : '';
			$liked = (is_liked($video->id)) ? '' : '<a class="heartit  pv_tip" data-toggle="tooltip" data-placement="left" title="'._lang("Like this video").'" href="javascript:iLikeThis('.$video->id.')"><i class="icon-heart"></i></a>';
            $wlater = (is_user()) ? '<a class="laterit pv_tip" data-toggle="tooltip" data-placement="right" title="'._lang("Add to watch later").'" href="javascript:Padd('.$video->id.', '.later_playlist().')"><i class="icon-plus-square-o"></i></a>' : '';
			$description = str_replace(array("\"","<br>","<br/>","<br />")," ",_html($video->description));
            $description = _cut(trim($description),85);
			if(empty($description)) {$description = $full_title;} 
			echo '
<div id="video-'.$video->id.'" class="video halfVideo">
<div class="video-inner">
<div class="video-thumb">
		<a class="clip-link" data-id="'.$video->id.'" title="'.$full_title.'" href="'.$url.'">
			<span class="clip">
				<img src="'.thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')).'" alt="'.$full_title.'" /><span class="vertical-align"></span>
			</span>
          	<span class="overlay"></span>
		</a>'.$liked.$watched.$wlater;
if($video->duration > 0) { echo '   <span class="timer">'.video_time($video->duration).'</span>'; }
echo '</div>	
<div class="video-data">
	<h4 class="video-title"><a href="'.$url.'" title="'.$full_title.'">'._html($title).'</a></h4>
	<p class="small">'.$description.'</p>
<ul class="stats">	
<li>		'._lang("by").' <a href="'.profile_url($video->user_id, $video->owner).'" title="'.$video->owner.'">'.$video->owner.'</a></li>
 <li>'.$video->views.' '._lang('views').'</li>';
if(isset($video->date)) { echo '<li>'.time_ago($video->date).'</li>';}
echo '</ul>
</div>	
	</div>
		</div>
';
}
echo '<br style="clear:both">';
echo '</div>';
}

?>
<div class="clearfix"></div>
</div>
