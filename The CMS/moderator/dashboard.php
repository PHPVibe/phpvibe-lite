<div class="row">
<div class="full">
<div class="panel panel-transparent">
<div class="panel-body card-holder">
<ul class="list-group card-icos">
 <li class="list-group-item">
<a href="<?php echo admin_url('videos'); ?>" title="">
 <i class="material-icons">
video_library
</i> 
</a>
   <strong> <?php echo _count('videos'); ?> </strong>   <a href="<?php echo admin_url('videos'); ?>" title=""><?php echo _lang('Videos');?></a>
  </li>
 <li class="list-group-item">
  <a href="<?php echo admin_url('users'); ?>" title="">
  <i class="icon-slideshare">
        </i>
		</a>
    <strong>
        <?php echo _count('users'); ?>
      </strong>
      <a href="<?php echo admin_url('users'); ?>" title=""><?php echo _lang('Members');?>
</a>
 </li>
 <li class="list-group-item">
  <a href="<?php echo admin_url('videos'); ?>" title="">
    <i class="material-icons">
remove_red_eye
</i>
</a>
		<strong>
        <?php echo _count('videos','views'); ?>
      </strong>
      <a href="<?php echo admin_url('videos'); ?>" title="">
         <?php echo _lang('Video views');?>
      </a>
  </li>

 <li class="list-group-item">
  <a href="<?php echo admin_url('videos'); ?>&sort=liked-desc" title="">
    <i class="material-icons">
thumb_up
</i>
</a>
	<strong><?php echo _count('likes' ); ?> </strong>
      <a href="<?php echo admin_url('videos'); ?>" title="">
        <?php echo _lang('Video likes');?>
      </a>
  </li>
 <li class="list-group-item">
 <a href="<?php echo admin_url('playlists'); ?>" title="">
      <i class="material-icons">
     playlist_add_check
     </i>
	 </a>
		<strong>
        <?php echo _count('playlists' ); ?>
      </strong>
      <a href="<?php echo admin_url('playlists'); ?>" title="">
     <?php echo _lang('Collections');?>
      </a>

  </li>
 <li class="list-group-item">
  <a href="<?php echo admin_url('comments'); ?>" title="">
    <i class="material-icons">
comment
</i>
 </a>
		<strong>
        <?php echo _count('em_comments' ); ?>
      </strong>
      <a href="<?php echo admin_url('comments'); ?>" title="">
       <?php echo _lang('Comments');?> 
      </a>
  </li>
 <li class="list-group-item">
  <a href="<?php echo admin_url('reports'); ?>" title="">
  <i class="material-icons">
flag
</i>
</a>  
		<strong>
        <?php echo _count('reports' ); ?>
      </strong>
      <a href="<?php echo admin_url('reports'); ?>" title="">
        <?php echo _lang('Reports');?>
      </a>  
  </li>
  
</ul>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-xlg-8 col-md-6  col-xs-12">
<div class="panel panel-bordered">
<?php $countu = $db->get_row("Select count(*) as nr from ".DB_PREFIX."users");
$users = $db->get_results("select * from ".DB_PREFIX."users order by id DESC limit 0,8");
?>
<div class="panel-heading">
<h3 class="panel-title">New users</h3> 
<ul class="panel-actions">
<li><a href="<?php echo admin_url("users");?>">View all (<?php echo $countu->nr; ?>)</a></li>
</div>

              </ul>
<div class="panel-body nopad scroll-items">
<ul class="list-group">
 <?php foreach ($users as $u) { ?>
<li class="list-group-item">
<div class="show no-margin pd-t-xs">
<span class="pull-left mg-t-xs mg-r-md">
<img data-name="<?php echo $u->name; ?>" class="NoAvatar avatar avatar-sm" alt="">
</span>
 <a href="<?php echo profile_url($u->id, $u->name); ?>" target="_blank"><?php echo _html($u->name); ?></a> <small class="pull-right"><?php echo count_uvid($u->id); ?> videos</small></div>
<small class="text-muted">Has <?php echo count_uact($u->id); ?> activities so far</small>
</li>
<?php } ?>
</ul>
</div>
</div>
</div>
<div class="col-xlg-4 col-md-6  col-xs-12 ">
<div class="panel panel-bordered">
<?php 
 function getpb($url)
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
$upd = json_decode(getpb("https://phpvibes.com/api"), true);	
if(!nullval($upd)) {
?>
<div class="panel-body nopad scroll-items">
<ul class="list-group">
<li class="list-group-item">
<?php 
$vFull = $upd["cms"]["version"].".".$upd["cms"]["suv"];
$yFull = 5;
 ?>
<div class="show no-margin pd-t-xs"> <h3 style="margin-top:2px;"><?php echo str_replace(array('(',')'),array('<small>','</small>'),_html($upd["cms"]["name"])); 
echo ' <a href="https://phpvibes.com" target="_blank" class="pull-right"><span class="badge badge-success pull-right">'.$vFull.'</span></a>'; ?></h3>
</div>
<?php if (file_exists(ABSPATH.'/'.ADMINCP.'/version.php')) {
	include_once(ABSPATH.'/'.ADMINCP.'/version.php');
	$yFull = $phpVersion.'.'.$phpSubversion;
?>
<p><?php echo '<span class="badge badge-primary">This is '.$yFull.'</span>'; ?>
<?php if($yFull < $vFull) { echo "<a href=\"https://www.phpvibe.com/recent-changes/\" target=\"_blank\"><strong class=\"redText\"> New update available! [Info]</strong></a>";} else {echo "<strong class=\"greenText\"> Up to date!</strong>";} ?></p>
<?php } else {
	echo "<strong class=\"redText\">Missing version.php file</strong>";
} ?>
</li>
 <?php  foreach ($upd["latest"] as $recent) {
$thumb = "https://phpvibes.com/".stripslashes($recent["thumb"]);
 ?>
<li class="list-group-item">
<span class="pull-left mg-t-xs mg-r-md">
<img src="<?php echo thumb_fix($thumb); ?>" class="avatar avatar-sm" alt="">
</span>
<div class="show no-margin pd-t-xs"> <a href="https://phpvibes.com/buy?id=<?php echo $recent["id"]; ?>" target="_blank"><?php echo _html($recent["name"]); ?></a> <small class="pull-right"><?php echo $recent["buys"]; ?> </small></div>
</li>
<?php } ?>
</ul>
<?php } else {
echo "Failed to retrieve the updates.";	
} ?>
</div>
</div>
</div>

</div>