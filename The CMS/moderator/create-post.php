<?php
if(isset($_POST['play-name'])) {
$picture ='';
$formInputName   = 'play-img';							# This is the name given to the form's file input
	$savePath	     = ABSPATH.'/storage/uploads';								# The folder to save the image
	$saveName        = md5(time()).'-'.user_id();									# Without ext
	$allowedExtArray = array('.jpg', '.png', '.gif');	# Set allowed file types
	$imageQuality    = 100;
$uploader = new FileUploader($formInputName, $savePath, $saveName , $allowedExtArray);
if ($uploader->getIsSuccessful()) {
//$uploader -> resizeImage(200, 200, 'crop');
$uploader -> saveImage($uploader->getTargetPath(), $imageQuality);
$thumb  = $uploader->getTargetPath();
$picture  = str_replace(ABSPATH.'/' ,'',$thumb);
} else { $picture = '';}
$db->query("INSERT INTO ".DB_PREFIX."posts (`date`, `ch`, `pic`, `title`, `content`, `tags`)
 VALUES (now(),'".intval($_POST['ch'])."', '".$picture."', '".$db->escape($_POST['play-name'])."', '".$db->escape(htmlentities($_POST['content']))."', '".$db->escape($_POST['tags'])."')");
echo '<div class="msg-info">Post '.$_POST['play-name'].' created</div>';
}

?>
<div class="row">
<form id="validate" class="form-horizontal styled" action="<?php echo admin_url('create-post');?>" enctype="multipart/form-data" method="post">
<fieldset>
<div class="form-group form-material">
<label class="control-label"><i class="icon-text-height"></i>Title</label>
<div class="controls">
<input type="text" name="play-name" class="validate[required] col-md-12"/> 						
</div>	
</div>	

<div class="form-group form-material">
<label class="control-label">Article content</label>
<div class="controls">
<textarea rows="5" cols="5" name="content" class="ckeditor col-md-12" style="word-wrap: break-word; resize: horizontal; height: 88px;"></textarea>					
</div>	
</div>
<label class="control-label">Image?</label>
<div class="form-group form-material form-material-file">

<div class="controls">
<input type="text" class="form-control empty" readonly="" />
<input type="file" id="play-img" name="play-img" class="styled" />
<label class="floating-label">Browse...</label>


</div>	
</div>
<?php
echo '<div class="form-group form-material">
	<label class="control-label">'._lang("Category:").'</label>
	<div class="controls">
	<select data-placeholder="'._lang("Choose a category:").'" name="ch" id="clear-results" class="select" tabindex="2">
	';
$categories = $db->get_results("SELECT cat_id as id, cat_name as name FROM  ".DB_PREFIX."postcats order by cat_name asc limit 0,10000");
if($categories) {
foreach ($categories as $cat) {	
echo'<option value="'.intval($cat->id).'">'.stripslashes($cat->name).'</option>';
	}
}	else {
echo'<option value="">'._lang("No categories").'</option>';
}
echo '</select>
	  </div>             
	  </div>';
?>
<div class="form-group form-material">
	<label class="control-label">Tags</label>
	<div class="controls">
	<input type="text" id="tags" name="tags" class="tags col-md-12" value="">
	<span class="help-block" id="limit-text">Press enter after each tag</span>
	</div>
	</div>
<div class="form-group form-material">
<button class="btn btn-large btn-primary pull-right" type="submit">Create</button>	
</div>	
</fieldset>						
</form>
</div>
