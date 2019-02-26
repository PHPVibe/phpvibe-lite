<div id="sidebar" class="<?php if(is_video() || is_picture() || is_com('conversation')) {echo 'hide';} ?> animated zoomInLeft"> 
<div class="sidescroll">
<?php do_action('sidebar-start');
echo _ad('0','sidebar-start');

//The menu	
echo '<div class="sidebar-nav blc"><ul>';
echo '<li class="lihead"><a href="'.site_url().'"><span class="iconed"><i class="material-icons">&#xE88A;</i></span> '._lang('Home').'</a></li>';
echo '<li class="lihead"><a href="'.list_url(browse).'"><span class="iconed"><i class="material-icons">&#xE038;</i></span> '._lang('Videos').'</a></li>';
if(get_option('imagesmenu') == 1 ) {
echo '<li class="lihead"><a href="'.site_url().buzz.'"><span class="iconed"><i class="material-icons">&#xE80E;</i></span> '._lang('What\'s up').'</a></li>';
}
if(get_option('showplaylists',1) == 1 ) {
echo '<li class="lihead"><a href="'.site_url().playlists.'/"><span class="iconed"><i class="material-icons">&#xE05F;</i></span>'._lang('Playlists').'</a></li>';
}

if(get_option('showusers',1) == 1 ) {
echo '<li class="lihead"><a href="'.site_url().members.'/"><span class="iconed"><i class="material-icons">&#xE8EF;</i></span>'._lang('Channels').'</a></li>';
}
if(get_option('showblog',1) == 1 ) {
echo '<li class="lihead"><a href="'.site_url().blog.'/"><span class="iconed"><i class="material-icons">&#xE8CD;</i></span>'._lang('Blog').'</a></li>';
}
echo '</ul></div>';
/* End of menu */
?>
<?php	
if (is_user()) {
/* start my playlists */	
?>
<h4 class="li-heading"><?php echo _lang('My collections'); ?></h4>
<div class="sidebar-nav blc"><ul>
<?php 
echo '<li><a href="'.site_url().me.'/?sk=likes"><span class="iconed"><i class="material-icons">&#xE8DC;</i></span> '. _lang('Likes').'</a> </li>
<li><a href="'.site_url().me.'/?sk=history"><span class="iconed"><i class="material-icons">&#xE889;</i></span> '. _lang('History').'</a> </li>
<li><a href="'.site_url().me.'/?sk=later"><span class="iconed"><i class="material-icons">&#xE924;</i></span> '. _lang('Watch Later').'</a> </li>
';
$plays = $cachedb->get_results("SELECT id, title FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and picture not in ('[likes]','[history]','[later]') and ptype < 2 order by title asc limit 0,100");
if($plays) { 
foreach ($plays as $play) {
echo '<li>
<a href="'.playlist_url($play->id, $play->title).'" original-title="'.$play->title.'" title="'.$play->title.'"><i class="material-icons">&#xE05F;</i>
'._html(_cut($play->title, 24)).'
</a>
</li>';
}
}

/* end my playlists */
/* start my albums */
$albums = $cachedb->get_results("SELECT id, title FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and picture not in ('[likes]','[history]','[later]') and ptype > 1 order by title asc limit 0,100");
if($albums) { 
foreach ($albums as $play) {
echo '<li>
<a href="'.playlist_url($play->id, $play->title).'" original-title="'.$play->title.'" title="'.$play->title.'"><i class="material-icons">&#xE438;</i>
'._html(_cut($play->title, 24)).'
</a>
</li>';
}
}
echo '</ul>
</div>';

/* end my albums */	
/* start my  subscriptions */ 
$followings = $cachedb->get_results("SELECT id,avatar,name from ".DB_PREFIX."users where id in (select uid from ".DB_PREFIX."users_friends where fid ='".user_id()."') order by lastNoty desc limit 0,15");
if($followings) {
$snr = $cachedb->num_rows;
?>
<h4 class="li-heading">
<a style="color: #797E89;" href="<?php echo profile_url(user_id(), user_name()); ?>?sk=subscribed" title="<?php echo _("View all"); ?>"><?php echo _lang('My subscriptions'); ?> </a>
</h4>
<div class="sidebar-nav blc"><ul>
<?php
foreach ($followings as $following) {
echo '
<li>
<a title="'.$following->name.'" href="'.profile_url($following->id , $following->name).'">
<img src="'.thumb_fix($following->avatar, true, 27, 27).'" alt="'.$following->name.'" />'._html(_cut($following->name, 25)).'';
echo '
</a></li>';
}
echo '</ul>
</div>
';
}
/* end subscriptions */
do_action('user-sidebar-end');	
} else {
echo '<div class="blc mtop20 odet fs300">';	
echo _lang('Share videos, music and pictures, follow friends and keep track of what you enjoy!');
echo '<p class="small mright20 mleft10"><a href="javascript:showLogin()" class="btn btn-primary btn-small btn-block mtop20">		
'._lang("Join us").'</a> </p>';	
echo '</div>';
do_action('guest-sidebar');					
}
do_action('sidebar-end');
echo lang_menu();
echo _ad('0','sidebar-end')
?>	
<div class="blc" style="height:400px">&nbsp;</div>
</div>
</div>