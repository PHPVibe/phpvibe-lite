<div class="row usercoms">
 <p class="profile-location"> <i class="icon standardico icon-map-marker"></i>  <?php if($profile->local) { ?>  <?php echo _html($profile->local);?>, <?php } ?> <?php if($profile->country) { ?> <?php echo _html($profile->country);?> <?php } else { echo _lang("Unknown");} ?></p>
<blockquote><?php echo _html($profile->bio);?></blockquote>
<?php echo _ad('0','user-coms-list-top');
echo comments("u-".$profile->id);
 echo _ad('0','user-coms-list-bottom');
?>
<hr>
<h2 class="block text-center"><i class="icon-users"></i></h2>
<?php  $followers = $cachedb->get_results("SELECT id,avatar,name from ".DB_PREFIX."users where id in (select fid from ".DB_PREFIX."users_friends where uid ='".$profile->id."') order by lastlogin desc limit 0,5");
        if($followers) { ?>
		<h4 class="user-heads block mtop20"><?php echo _lang('Followed by'); ?></h4>
		<ul class="list-group">
		<?php foreach ($followers as $follower) { ?>
		  <li class="list-group-item user-small-list">
          <a class="" title="<?php echo $follower->name;?>" href="<?php echo profile_url($follower->id , $follower->name); ?>">
		  <img src="<?php echo thumb_fix($follower->avatar, true, 23, 23);?>" alt="<?php echo  $follower->name; ?>" />
		  <?php echo  $follower->name; ?>
		  </a>
          </li>
		<?php } ?>                  
        </ul>	
    <div class="row no-space msg-footer bottom20 text-center">
	<a href="<?php echo $canonical; ?>?sk=subscribers" class="btn btn-sm btn-default"><?php echo _lang("All followers"); ?></a>
	</div>		
<?php } ?>
<?php  $fans = $cachedb->get_results("SELECT id,avatar,name from ".DB_PREFIX."users where id in (select fid from ".DB_PREFIX."users_friends where uid ='".$profile->id."') order by lastlogin desc limit 0,5");
        if($fans) { ?>
		<h4 class="user-heads block mtop20"><?php echo _lang('Subscribed to'); ?></h4>
		<ul class="list-group">
		<?php foreach ($fans as $fan) { ?>
		  <li class="list-group-item user-small-list">
          <a class="" title="<?php echo $fan->name;?>" href="<?php echo profile_url($fan->id , $fan->name); ?>">
		  <img src="<?php echo thumb_fix($fan->avatar, true, 23, 23);?>" alt="<?php echo  $fan->name; ?>" />
		  <?php echo  $fan->name; ?>
		  </a>
          </li>
		<?php } ?>                  
        </ul>	
    <div class="row no-space bottom20 text-center">
	<a href="<?php echo $canonical; ?>?sk=subscribed" class="btn btn-sm btn-default"><?php echo _lang("All subscriptions"); ?></a>
	</div>		
<?php } ?>
</div>
