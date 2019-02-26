<?php $playlists = $cachedb->get_results("select ".DB_PREFIX."playlists.*, '".$profile->name."' as user from ".DB_PREFIX."playlists WHERE ".DB_PREFIX."playlists.picture not in ('[likes]','[history]','[later]') and owner = '".$profile->id."' order by ptype ASC,views DESC limit 0,1000");
if($playlists) {
/*Count videos */	
$entries = array();	
$counter = $cachedb->get_results("SELECT COUNT(*) AS entries, playlist FROM  ".DB_PREFIX."playlist_data GROUP BY playlist LIMIT 0 , 30000");
if($counter){
foreach($counter as $c)	{
$entries[$c->playlist] = $c->entries;
}	
}
echo '<div id="SearchResults" class="loop-content phpvibe-video-list ">'; 
foreach ($playlists as $pl) {
			$title = _html(_cut($pl->title, 170));
			$full_title = _html(str_replace("\"", "",$pl->title));			
			$url = playlist_url($pl->id , $pl->title);
			$plays = 0;
            $ov = '';			
			if($pl->ptype == 1) { $ov = _lang("Play all");} else { $ov = _lang("View all");}
			if($pl->ptype == 1) { $ol = site_url().'forward/'.$pl->id;} else { $ol = $url;}
			$ove = '<div class="playlists-overlay">
	     	<a title="'._lang($ov).'" href="'.$ol.'">
			'.$ov.'
			</a>			
			</div>
			';
			if(isset($entries[$pl->id])) {$plays = intval($entries[$pl->id]); }
echo '
<div id="video-'.$pl->id.'" class="video">
<div class="video-inner">
<div class="video-thumb">
		<a class="clip-link" data-id="'.$pl->id.'" title="'.$full_title.'" href="'.$url.'">
			<span class="clip">
				<img src="'.thumb_fix($pl->picture, true, get_option('thumb-width'), get_option('thumb-height')).'" alt="'.$full_title.'" />
				'.$ove.'
				<div class="playlists-meta"><span>'.$plays.'</span><span><i class="icon icon-navicon"></i></span></div>
			</span>
          	
		</a>';
echo '</div>	
<div class="video-data">
	<h4 class="video-title"><a href="'.$url.'" title="'.$full_title.'">'._html($title).'</a></h4>
	<p style="font-size:11px">'._html(_cut($pl->description,270)).'</p>
<ul class="stats">';
echo '<li>'._lang("Watched").' '.intval($pl->views).' '._lang("times").'<li>
</ul>';
echo '</div>	
	</div>
		</div>
';
}
}