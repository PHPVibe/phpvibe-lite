<div class="row">
<?php
if(isset($_GET['delete-com'])) {
$id = intval($_GET['delete-com']);
$db->query("DELETE FROM ".DB_PREFIX."em_comments where id='".$id."'");
$db->query("DELETE FROM ".DB_PREFIX."activity where type = '7' and object='".$id."'");
echo '<div class="msg-info">Comment #'.$id.' deleted.</div>';
}  
if(isset($_POST['checkRow'])) {
foreach ($_POST['checkRow'] as $del) {
$db->query("DELETE FROM ".DB_PREFIX."em_comments where id in (".implode(',', $_POST['checkRow']).")");
}
echo '<div class="msg-info">Comments #'.implode(',', $_POST['checkRow']).' deleted.</div>';
}
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."em_comments");
$comments   = $db->get_results("SELECT ".DB_PREFIX."em_comments . * , ".DB_PREFIX."em_likes.vote , ".DB_PREFIX."users.name, ".DB_PREFIX."users.avatar
FROM ".DB_PREFIX."em_comments
LEFT JOIN ".DB_PREFIX."em_likes ON ".DB_PREFIX."em_comments.id = ".DB_PREFIX."em_likes.comment_id
LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."em_comments.sender_id = ".DB_PREFIX."users.id
ORDER BY  ".DB_PREFIX."em_comments.id desc ".this_limit()."");
if($comments) {
$ps = admin_url('comments').'&p=';
$here = $ps.this_page();
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
    
    $html = '<div class="comment-list block full">';
	 foreach( $comments as $comment) {
    $html .= ' <article id="comment-id-'.$comment->id.'" style="    border-bottom: 1px solid #e8ecec; margin:10px 0">
<section class="media-body pbody">
<header class="pbody-heading clearfix">
<a href="'.profile_url($comment->sender_id,$comment->name).'">'.print_data(stripslashes($comment->name)).'</a> - '.time_ago($comment->created).' <span class="text-muted m-l-small pull-right" id="iLikeThis_'.$comment->id.'"></span>

<div class="pull-right">
<input type="checkbox" name="checkRow[]" value="'.$comment->id.'" class="styled" />
</div>
<div class="pull-right" style="margin:0 10px">
<a class="confirm pull-right btn btn-danger btn-sm " href="'.$here.'&delete-com='.$comment->id.'"><i class="icon-trash"></i> Delete</a>
</div>

</header>
<div><p>'.print_data(stripslashes($comment->comment_text)).'</p>
<p>';
if((intval($comment->object_id)> 0) || (strpos($comment->object_id,'video')!==false)) {
$html .='<a style="" href="'.video_url(str_replace('video_','',$comment->object_id),'coms').'#comment-id-'.$comment->id.'" target="_blank" class="btn  btn-outline btn-sm btn-primary"><i class="icon icon-link"></i></a></p>';
} elseif(strpos($comment->object_id,'img')!==false) {
$html .='<a style="" href="'.image_url(str_replace('img-','',$comment->object_id),'coms').'#comment-id-'.$comment->id.'" target="_blank" class="btn  btn-outline btn-sm btn-primary"><i class="icon icon-link"></i></a></p>';

} elseif(strpos($comment->object_id,'u-')!==false) {
$html .='<a style="" href="'.profile_url(str_replace('u-','',$comment->object_id),'coms').'&sk=comments#comment-id-'.$comment->id.'" target="_blank" class="btn  btn-outline btn-sm btn-primary"><i class="icon icon-link"></i></a></p>';
}
elseif(strpos($comment->object_id,'art')!==false) {
$html .='<a style="" href="'.article_url(str_replace('art','',$comment->object_id),'coms').'#comment-id-'.$comment->id.'" target="_blank" class="btn  btn-outline btn-sm btn-primary"><i class="icon icon-link"></i></a></p>';
}
$html .='</div>
                
              </section>
</article>
';
}
 $html .= '</div>';
$a->show_pages($ps); 
echo '
<form class="form-horizontal styled" action="'.$here.'" enctype="multipart/form-data" method="post">
<div class="block" style="z-index:999;margin: 20px 0; padding:10px; border: 1px solid #eee; position:fixed; right:14%; bottom:1px;     background-color: #f9f9f9;">
<div class="checkbox-custom checkbox-danger inline"> <input type="checkbox" name="checkRows" class="check-all-notb" /> <label for="checkRows">Select all</label> </div>  
<button class="btn btn-sm btn-danger" type="submit"><i class="icon icon-trash"></i> Selected</button>
</div>
<div class="clearfix">'.$html.'</div>
</form>
';
$a->show_pages($ps); 
} else {
echo '<div class="msg-note">Nothing here yet.</div>';
}
					
?>

				
</div>
<?php //End ?>
