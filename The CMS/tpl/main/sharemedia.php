<?php the_sidebar(); 
$active = com();
?>
<div id="default-content" class="share-media">
<div class="row">
<div class="block mtop20">
                    <ul class="nav nav-tabs nav-tabs-line">
					<?php if(_EmbedVideo()) { ?>
                      <li class="<?php aTab('share');?>"><?php echo '<a href="'.site_url().share.'">';?><i class="icon icon-youtube"></i><?php echo _lang('Share video'); ?></a></li>
                      <?php } ?>					  
					</ul>
</div>
<div class="block">
<?php echo default_content(); ?>

</div>
</div>
