<?php the_sidebar(); ?>
<div class="row">
<div class="col-md-9 blog-holder col-xs-12">
<?php do_action('blogpost-start');

$txt = '<h1 class="blogH"><span>'._html($_post->title).'</span></h2>';
$txt .= '<p>'.time_ago($_post->date).'</p>';
if($_post->pic) {
$txt .= '<div class="blog-image">							
<div class="text-center ">
<img src="'.thumb_fix($_post->pic).'" />
</div>
</div>';
	            }
$txt .= '<div class="blog-text mtop20">';
$txt .= _html($_post->content);
$txt .= '</div>';
echo  $txt;
?>
<div id ="jsshare" data-url="<?php echo canonical(); ?>" data-title="<?php echo _cut($_post->title, 40); ?>"></div>                   
    
<?php do_action('blogpost-end');
echo comments('art'.token_id());
?>
</div>
<?php include_once('blog-sidebar.php'); ?>
</div>
