<?php the_header(); 
the_sidebar(); ?>
 <ul class="nav nav-tabs nav-tabs-line hidden-md hidden-lg visible-xs visible-sm" id="myTabs" role="tablist">
 <li class="active"><a data-toggle="tab" href="#DashContent" role="tab"> <?php echo _lang('Dashboard'); ?></a></li>
 <li class=""><a data-toggle="tab" href="#DashSidebar" role="tab"><?php echo _lang('Menu'); ?></a></li>
 </ul>
  <script>
 $(document).ready(function() {
	 if ($(window).width() < 972) {
		 $('#DashContent').addClass('tab-pane active');
		 $('#DashSidebar').addClass('tab-pane');
		 $('#myTab a,#myTabs a').click(function (e) {
           e.preventDefault();
           $(this).tab('show');
         });
	 }
	 });
 </script>
  <div id="theHolder" class="row tab-content"> 

 <div id="DashContent" class="col-md-10 col-xs-12 isBoxed"> 
 <div class="row odet">
<?php 
    if(_get('msg')) {
	echo '<div class="msg-info">'.toDb(_get('msg')).'</div>';
	}
    if(isset($msg)) {echo $msg;}
    do_action('dash-top'); 
 if((_get('sk') == "edit") || isset($_POST['changeavatar']) || isset($_POST['changecover']) || isset($_POST['changeuser'])  ) {
  include_once(TPL.'/profile/edit.php');		
 } elseif(_get('sk') == "activity") { ?>	
	<div class="row odet">
	<div class="panel panel-transparent">
	<div class="panel-heading">
	<h4 class="panel-title"><?php echo _lang("Activity on your media");?></h4>
	</div>
	<div class="panel-body">
	<?php
	//Latest notifications
	$count= $db->get_row("Select count(*) as nr from vibe_activity where (type not in (8,9) and vibe_activity.object in (select id from vibe_videos where user_id ='".user_id()."' ) ) or (type in (8,9) and vibe_activity.object in (select id from vibe_images where user_id ='".user_id()."' ) ) and user <> '".user_id()."'");

		if($count){
		if($count->nr > 0) {
			echo '<p style="line-height:15px;"><span class="badge" style="font-size:16px">'.$count->nr.' <i class="icon material-icons" style="font-size:16px">&#xE7F7;</i></span></p>';
			$a = new pagination; 
			$ps = site_url().'dashboard/?sk=activity&p=';
			$a->set_current(this_page()); 
			$a->set_first_page(true); 
			$a->set_pages_items(7); 
			$a->set_per_page(bpp()); 
			$a->set_values($count->nr);
		$vq = "Select ".DB_PREFIX."activity.*, ".DB_PREFIX."users.avatar,".DB_PREFIX."users.id as pid, ".DB_PREFIX."users.name from ".DB_PREFIX."activity left join ".DB_PREFIX."users on ".DB_PREFIX."activity.user=".DB_PREFIX."users.id where
		((".DB_PREFIX."activity.type not in (8,9) and ".DB_PREFIX."activity.object in (select id from ".DB_PREFIX."videos where user_id ='".user_id()."' ))  or
		(".DB_PREFIX."activity.type in (8,9) and ".DB_PREFIX."activity.object in (select id from ".DB_PREFIX."images where user_id ='".user_id()."' ))) and ".DB_PREFIX."activity.user <> '".user_id()."'
		ORDER BY ".DB_PREFIX."activity.id DESC ".this_limit();
		$activity = $db->get_results($vq);
		if ($activity) {
		$did =  array();
		echo '<div class="row">
		<ul id="user-timeline" class="timelist user-timeline">
		'; 
		$licon = array();
		$licon["1"] = "icon-heart";
		$licon["2"] = "icon-share";
		$licon["3"] = "icon-youtube-play";
		$licon["4"] = "icon-upload";
		$licon["5"] = "icon-rss";
		$licon["6"] = "icon-comments";
		$licon["7"] = "icon-thumbs-up";
		$licon["8"] = "icon-camera";
		$licon["9"] = "icon-star";
		$lback = array();
		$lback["1"] = $lback["9"] = "bg-smooth";
		$lback["2"] = "bg-success";
		$lback["3"] = "bg-flat";
		$lback["4"] = $lback["8"] = "bg-default";
		$lback["5"] = "bg-default";
		$lback["6"] = "bg-info";
		$lback["7"] = "bg-smooth";
		foreach ($activity as $buzz) {
		$did = get_activity($buzz);	
		if(isset($did["what"]) && !nullval($did["what"])) {
		echo '
		<li class="cul-'.$buzz->type.' t-item">
		 <div class="user-timeline-time">'.time_ago($buzz->date).'</div>
		<i class="icon '.$licon[$buzz->type].' user-timeline-icon '.$lback[$buzz->type].'"></i>
		<div class="user-timeline-content">
		<p><a href="'.profile_url($buzz->pid,$buzz->name).'">'._html($buzz->name).'</a>  '.$did["what"].'</p>
		';
		if(isset($did["content"]) && !nullval($did["content"])) {
		echo '<div class="timeline-media">'.$did["content"].'</div>';
		}
		echo '</div>

		</li>';
		unset($did);
		}
		}
		echo '</ul><br style="clear:both;"/></div>';
		}
        $a->show_pages($ps);		
		} else {
		echo '<p>'._lang("No activity on your media yet").'</p>';	
		}
		} else {
		echo '<p>'._lang("No activity yet").'</p>';	
		}
?>	
</div>
</div>
</div>
<?php
} else {
	//Frontpage
		
	$playlists = array(history_playlist(),likes_playlist(),later_playlist());
	foreach ($playlists as $playlist)
	
	{
		if($playlist == history_playlist()) {
			echo '<h2>'._lang("Watch it again").'</h2>';
		} elseif ($playlist == likes_playlist()) {
			echo '<h2>'._lang("You've enjoyed this").'</h2>';			
		} else {
		echo '<h2>'._lang("You wanted to check this").'</h2>';	
		}
		$options = DB_PREFIX."videos.id,".DB_PREFIX."videos.media,".DB_PREFIX."videos.title,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.liked,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw";
		$vq = "SELECT ".DB_PREFIX."videos.id, ".DB_PREFIX."videos.title, ".DB_PREFIX."videos.user_id, ".DB_PREFIX."videos.thumb, ".DB_PREFIX."videos.views, ".DB_PREFIX."videos.liked, ".DB_PREFIX."videos.duration, ".DB_PREFIX."videos.nsfw, ".DB_PREFIX."users.group_id, ".DB_PREFIX."users.name AS owner
		FROM ".DB_PREFIX."playlist_data
		LEFT JOIN ".DB_PREFIX."videos ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."videos.id
		LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id
		WHERE ".DB_PREFIX."playlist_data.playlist =  '".$playlist."'
		ORDER BY ".DB_PREFIX."playlist_data.id DESC ".this_offset(bpp());
		include(TPL.'/video-carousel.php');
	}
	
	echo '<div class="block full text-center mtop20 mbot10">
	<a class="btn btn-default" href="'.profile_url(user_id(), user_name()).'"> '._lang("Go to profile").' </a>
	</div>
	';
}
 do_action('dash-bottom'); ?>
</div>
<?php do_action('dashboard-bottom'); ?>
</div>
<div id="DashSidebar" class="col-md-2 col-xs-12">
<?php   do_action('dashSide-top'); ?>
<div class="nav-tabs-vertical">
	<ul class="nav nav-tabs nav-tabs-line">
		<li class=""><a href="<?php echo site_url(); ?>dashboard/"><i class="icon icon-hashtag"></i><?php echo _lang("Overview");?></a></li>
		<li class=""><a href="<?php echo site_url(); ?>dashboard/?sk=activity"><i class="material-icons">&#xE7F7;</i><?php echo _lang("Activities");?></a></li>
		<li class=""><a href="<?php echo site_url(); ?>dashboard/?sk=edit"><i class="icon icon-cogs"></i><?php echo _lang("Channel Settings");?></a></li>
		<li class=""><a href="<?php echo site_url().me; ?>"><i class="icon icon-film"></i><?php echo _lang("Videos");?></a></li>
		<li class=""><a href="<?php echo site_url().me; ?>?sk=playlists"><i class="icon icon-bars"></i><?php echo _lang("Playlists");?></a></li>
	</ul>
</div>
<?php
do_action('dashSide-bottom'); ?>
</span>
</div>
</div>