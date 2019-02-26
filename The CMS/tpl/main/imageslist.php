<?php the_sidebar();
//Add sorter 
if(isset($sortop) && $sortop) {
/* Most liked , Most viewed time sorting */
$st = '
<div class="btn-group pull-right">
       <a data-toggle="dropdown" class="btn btn-default btn-outline dropdown-toogle"> <i class="icon icon-calendar"></i> <i class="icon icon-angle-down"></i> </a>
			<ul class="dropdown-menu dropdown-menu-right bullet">
			<li title="'._lang("This Week").'"><a href="'.images_url(token()).(_get('tag')? '?tag='._get('tag') : '').'&sort=w"><i class="icon icon-circle-thin"></i>'._lang("This Week").'</a></li>
			<li title="'._lang("This Month").'"><a href="'.images_url(token()).(_get('tag')? '?tag='._get('tag') : '').'&sort=m"><i class="icon icon-circle-thin"></i>'._lang("This Month").'</a></li>
			<li title="'._lang("This Year").'"><a href="'.images_url(token()).(_get('tag')? '?tag='._get('tag') : '').'&sort=y"><i class="icon icon-circle-thin"></i>'._lang("This Year").'</a></li>
			<li class="divider" role="presentation"></li>
			<li title="'._lang("This Week").'"><a href="'.images_url(token()).(_get('tag')? '?tag='._get('tag') : '').'"><i class="icon icon-circle-thin"></i>'._lang("All").'</a></li>
		</ul>
		</div>
';
}
if(!isset($noNavs)) {  ?>
<ul class="nav nav-tabs nav-tabs-line mtop20">
    <li class="<?php aTab(browse);?>" role="presentation"><a href="<?php echo images_url('browse'); ?>"> <i class="icon icon-eye"></i> <?php echo _lang('Recent'); ?> </li></a>
    <li class="<?php aTab(mostviewed);?>" role="presentation"><a href="<?php echo images_url(mostviewed); ?>"> <i class="icon icon-line-chart"></i> <?php echo _lang('Most Viewed'); ?></a></li>
    <li class="<?php aTab(mostliked);?>" role="presentation"><a href="<?php echo images_url(mostliked); ?>"> <i class="icon icon-heart"></i> <?php echo _lang('Most Liked'); ?></a></li>
    <li class="<?php aTab(mostcom);?>" role="presentation"><a href="<?php echo images_url(mostcom); ?>"> <i class="icon icon-comments"></i> <?php echo _lang('Discussed'); ?></a></li>
    <li class="<?php aTab(promoted);?>" role="presentation"><a href="<?php echo images_url(promoted); ?>"> <i class="icon icon-bullhorn"></i> <?php echo _lang('Featured'); ?></a></li>
	<?php if(_UpImage()) { ?>
	<li class="pull-right" role="presentation"><a href="<?php echo site_url().upimage; ?>"> <i class="icon icon-plus-square"></i> <?php echo _lang('Add image'); ?></a></li>
    <?php } ?>
</ul>
<?php } ?>
<div class="text-center removeonload">
<div class="cp-spinner cp-flip"></div> 
</div>
<div id="imagelist-content" class="hides">
<?php echo _ad('0','images-list-top');
include_once(TPL.'/images-loop.php');
 echo _ad('0','images-list-bottom');
?>

</div>
<div class="load-cats" data-type="3">
&nbsp;
</div>
