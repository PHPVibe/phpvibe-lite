<?php the_sidebar(); ?>
<div class="row odet">
<?php do_action('page-start');
$txt = '<h1 class="pageH">'._html($_page->title).'</h1>';
if($_page->pic) {
$txt .= '<div class="row">
<img src="'.thumb_fix($_page->pic).'" />
</div>';
                }
$txt .= '<div class="page-content">';
$txt .= _html($_page->content);
$txt .= '</div>';
echo $txt; 
do_action('page-end');
?>
</div>
