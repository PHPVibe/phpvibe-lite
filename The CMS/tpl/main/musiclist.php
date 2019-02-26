<?php the_sidebar(); 
//Add sorter 
if(isset($sortop) && $sortop) {
/* Time sorting */
$st = '<div class="btn-group pull-right">
       <a data-toggle="dropdown" class="btn btn-default btn-outline dropdown-toogle"> <i class="icon icon-calendar"></i> <i class="icon icon-angle-down"></i> </a>
			<ul class="dropdown-menu dropdown-menu-right bullet">
			<li title="'._lang("This Week").'"><a href="'.music_url(token()).'&sort=w"><i class="icon icon-circle-thin"></i>'._lang("This Week").'</a></li>
			<li title="'._lang("This Month").'"><a href="'.music_url(token()).'&sort=m"><i class="icon icon-circle-thin"></i>'._lang("This Month").'</a></li>
			<li title="'._lang("This Year").'"><a href="'.music_url(token()).'&sort=y"><i class="icon icon-circle-thin"></i>'._lang("This Year").'</a></li>
		    <li class="divider" role="presentation"></li>
			<li title="'._lang("No filter").'"><a href="'.music_url(token()).'"><i class="icon icon-circle-thin"></i>'._lang("All").'</a></li>
		</ul>
		</div>
      ';
}

?>
<div class="main-holder row">
<div id="songlist" class="col-md-12">
<ul class="nav nav-tabs nav-tabs-line mtop20">
                    <li class="<?php aTab(browse);?>" role="presentation"><a href="<?php echo music_url('browse'); ?>"> <i class="icon icon-volume-up"></i> <?php echo _lang('Recent'); ?> </li></a>
                    <li class="<?php aTab(mostviewed);?>" role="presentation"><a href="<?php echo music_url(mostviewed); ?>"> <i class="icon icon-line-chart"></i> <?php echo _lang('Most Listened'); ?></a></li>
                    <li class="<?php aTab(mostliked);?>" role="presentation"><a href="<?php echo music_url(mostliked); ?>"> <i class="icon icon-heart"></i> <?php echo _lang('Most Liked'); ?></a></li>
                    <li class="<?php aTab(mostcom);?>" role="presentation"><a href="<?php echo music_url(mostcom); ?>"> <i class="icon icon-comments"></i> <?php echo _lang('Discussed'); ?></a></li>
                    <li class="<?php aTab(promoted);?>" role="presentation"><a href="<?php echo music_url(promoted); ?>"> <i class="icon icon-bullhorn"></i> <?php echo _lang('Featured'); ?></a></li>
				    <?php if(_UpMusic() || _EmbedMusic()) { ?>
	                <li class="pull-right" role="presentation"><a href="<?php echo site_url().upmusic; ?>">  <i class="icon icon-soundcloud"></i> <?php echo _lang('Add song'); ?></a></li>
                   <?php } ?>
				 </ul>
<?php echo _ad('0','music-list-top');
include_once(TPL.'/music-loop.php');
echo _ad('0','music-list-bottom');
?>
</div>
<div class="load-cats" data-type="2">
&nbsp;
</div>
