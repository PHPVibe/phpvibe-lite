<div class="col-md-3 col-xs-12 blog-sidebar left20 top10">
<?php echo _ad('0','blog-sidebar-top');
$blogcats = $db->get_results("select * from ".DB_PREFIX."postcats order by cat_id DESC limit 0,10000");;
if($blogcats) {
echo '<h4>'._lang("Blog categories").'</h4>
<div class="sidebar-nav row"><ul>';
foreach ($blogcats as $popular) {
echo '
<li class="row">
<a class="pull-left" title="'.$popular->cat_name.'" href="'.bc_url($popular->cat_id , $popular->cat_name).'"><img src="'.thumb_fix($popular->picture, true, 27, 27).'" alt="'.$popular->cat_name.'" />
'._cut(_html($popular->cat_name), 15).'</a>';
echo '</li>';
}
echo '</ul>
</div>';
}
$articles =  $db->get_results("select title,pid,pic from ".DB_PREFIX."posts ORDER BY pid DESC limit 0,10");
 /* The pages lists */
 if($articles) {
 echo '<h4>'._lang("Recent articles").'</h4>
 <div class="sidebar-nav row"><ul>';
	foreach ($articles as $art) {
echo '<li class="row"><a href="'.article_url($art->pid, $art->title).'" title="'._html($art->title).'">'._cut(_html($art->title),60).'</a></li>';
                             }
echo '</ul></div>';
            }
$pagesx = $db->get_results("select title,pid,pic from ".DB_PREFIX."pages WHERE menu = '1' ORDER BY title ASC limit 0,20");
 /* The pages lists */
 if($pagesx) {
 echo '<h4>'._lang("Pages").'</h4>
 <div class="sidebar-nav row"><ul>';
	foreach ($pagesx as $px) {
echo '<li class="row"><a href="'.page_url($px->pid, $px->title).'" title="'._html($px->title).'">'._cut(_html($px->title),60).'</a></li>';
                             }
echo '</ul></div>';
            }
echo _ad('0','blog-sidebar-bottom');
			?>

</div>
