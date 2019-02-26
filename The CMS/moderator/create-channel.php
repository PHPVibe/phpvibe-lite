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
} 
$ch = 0;
if($_POST['subz'] > 1) {
$ch = $_POST['categ'.$_POST['type']];
}

$db->query("INSERT INTO ".DB_PREFIX."channels (`sub`,`type`,`child_of`, `cat_name`, `picture`, `cat_desc`) VALUES ('".$_POST['sub']."','".$_POST['type']."','".$ch."','".toDb($_POST['play-name'])."', '".toDb($picture)."' , '".toDb($_POST['play-desc'])."')");
echo '<div class="msg-info">Channel '.$_POST['play-name'].' created</div>';
}

?>
<div class="row">
<script>
   
 $(document).ready(function(){
  $('#chz,#a2,#a3').hide();
      $('.trigger').on('ifChecked', function(event){
        $('#a1,#a2,#a3').hide();
        $('#a' + $(this).data('rel')).show();
    });
	 $('.shs').on('ifChecked', function(event){
        if ($(this).data('rel') === 1) {
		$('#chz').hide();
		} else {
		$('#chz').show();
		}
    });

});
</script>
<form id="validate" class="form-horizontal styled" action="<?php echo admin_url('create-channel');?>" enctype="multipart/form-data" method="post">
<fieldset>
<div class="form-group form-material">
<label class="control-label"><i class="icon-bookmark"></i><?php echo _lang("Title"); ?></label>
<div class="controls">
<input type="text" name="play-name" class="validate[required] col-md-12" placeholder="<?php echo _lang("Your channel's title"); ?>" /> 						
</div>	
</div>
<div class="form-group form-material">
	<label class="control-label"><i class="icon-user"></i>Is this a sub-channel?</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="subz" data-rel="1" class="styled shs" value="1" checked>No</label>
	<label class="radio inline"><input type="radio" name="subz" data-rel="2" class="styled shs" value="2">Yes</label>
	</div>
	</div>		
<div class="form-group form-material">
	<label class="control-label"><i class="icon-user"></i>Accepted media</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="type" data-rel="1" class="styled trigger" value="1" checked>Video</label>
	</div>
	</div>	
	
<div id="chz" class="control-group row">
	<label class="control-label">Parent channel:</label>
	<div class="controls">
	<div id="a1" class="sel">
	<?php echo cats_select("categ1","select","");?>
	<span class="help-block" id="limit-text">FOR VIDEOS</span>
	  </div> 
<div id="a2" class="sel">
	<?php echo cats_select('categ2',"select","","2");?>
	<span class="help-block" id="limit-text">FOR MUSIC</span>
	  </div>  
<div id="a3" class="sel">
	<?php echo cats_select('categ3',"select","","3");?>
	<span class="help-block" id="limit-text">FOR IMAGES</span>
	  </div>  	
  
	  </div>
	  </div>
	

<div class="form-group form-material">
	<label class="control-label"><i class="icon-cloud-upload"></i>Sharing to this channel:</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="sub" class="styled" value="1" checked>Public (Every registred user)</label>
	<label class="radio inline"><input type="radio" name="sub" class="styled" value="0">Private (Mods & Admins)</label>

	</div>
	</div>	
<div class="form-group form-material">
<label class="control-label"><?php echo _lang("Description"); ?></label>
<div class="controls">
<textarea rows="5" cols="5" name="play-desc" class="auto col-md-12" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 88px;"></textarea>					
</div>	
</div>
<label class="control-label"><?php echo _lang("Channel image"); ?></label>
<div class="form-group form-material form-material-file">

<div class="controls">
<input type="text" class="form-control empty" readonly=""/>
<input type="file" id="play-img" name="play-img" class="styled" />
<label class="floating-label">Browse...</label>

</div>	
</div>
<div class="form-group form-material">
<button class="btn btn-large btn-primary pull-right" type="submit"><?php echo _lang("Create channel"); ?></button>	
</div>	
</fieldset>						
</form>
</div>
