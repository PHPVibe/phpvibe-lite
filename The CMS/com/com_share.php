<?php if(!is_user()) { redirect(site_url().'login/'); }
$error='';
// SEO Filters
function modify_title( $text ) {
 return strip_tags(stripslashes($text.' '._lang('share')));
}
$token = md5(user_name().user_id().time());
function modify_content_embed( $text ) {
global $error, $token, $db;
if(!_post('vfile')) {
$data =  $error.'
<div class="panel isBoxed top20 right10">
     <div class="panel-body">
     <div id="formVid" class="block mtop20 UploadForm">
	<form id="validate" class="block mtop20" action="'.canonical().'" enctype="multipart/form-data" method="post">
	<input type="hidden" name="vembed" id="vembed" readonly/>
	<div class="form-group form-material floating">
                  <div class="input-group">
                    <span class="input-group-addon"><i class=" icon icon-link"></i></span>
                    <div class="form-control-wrap">
                      <input type="url" id="vfile" data-error="'._lang("Paste use a valid url like https://youtu.be/glri7uEYYTo or https://vimeo.com/98062343").'" name="vfile" class="form-control input-lg empty" required>
                      <label class="floating-label">'._lang("Paste a link to a web video").'</label>
						<div class="help-block with-errors"></div>
					</div>
                    <span class="input-group-btn">
                      <button id="Subtn" class="btn btn-outline btn-default" type="submit">'._lang("Embed").'</button>
                    </span>
			 </div>
			</div>
		</form>
		</div>              
			  </div>
    <div class="panel-footer">';
	$supported = @Vibe_Providers::Hostings();
	$local = array("localfile", "localimage","up","soundcloud");
	$data .= '<p class="text-center block mbot20 mtop20"><strong>'._lang("You can embed from:").'</strong></p>';
	$data .='<div class="row text-center mtop20">';
	foreach ($supported as $su) {
		if(!in_array($su, $local)) {
		$data .= "<div class='col-md-2 text-left'>".ucfirst($su).'</div>';
		}
	}
     $data .=  ' </div>
	         </div>  
	        </div>
        </div>';
		} elseif(_post('vfile')) {
		$vid = new Vibe_Providers();
		$file = _post('vfile');
		$file = str_replace('youtu.be/', 'youtube.com/watch?v=',$file );
		if(!$vid->isValid($file)){
		return '<div class="msg-warning mtop20">'._lang("Url is malformated or we don't support yet embeds from that website").'</div>';
		}
		$details = $vid->get_data();
//if(is_admin()) {
//var_dump($details);
//}	
/* Overwrite file, needed for some sources like Soundcloud */
if(isset($details['source']) && !nullval($details['source'])) {
	$file = $details['source'];
	}
	$type = 1;
	if(_post('media') && (intval(_post('media') > 0))) {$mt = intval(_post('media'));} else {$mt = 1;}	
	$span = 12;
	$data =  $error.'
	<div class="row odet isBoxed">
    <div id="formVid block">
	<div class="ajax-form-result clearfix "></div>
	<form id="validate" class="row ajax-form-video" action="'.site_url().'lib/ajax/addVideo.php" enctype="multipart/form-data" method="post">
	<div class="col-md-7">
	<input type="hidden" name="file" id="file" value="'.$file.'" readonly/>
	<input type="hidden" name="type" id="type" value="'.$type.'" readonly/>
	';
	$data .= '<input type="hidden" name="media" id="media" readonly value="'.$mt.'"/>';	
	$data .= '<div class="control-group">
	<div class="form-group form-material floating">
	<input type="text" id="title" name="title" class="form-control" required="" value="'.$details['title'].'">
	<label class="floating-label">'._lang("Title").'</label>
	</div>
	</div>
	<div class="control-group">
	<div class="form-group">
	<label class="control-label">'._lang("Description").'</label>
	<textarea id="description" name="description" class=" form-control auto" required="">'.$details['description'].'</textarea>
	<div class="help-block with-errors"></div>
	</div>
	</div>
	<div class="control-group">
	<div class="form-group">
	<div class="input-group">
    <span class="input-group-addon">'._lang("Tags:").'</span>
	<div class="form-control withtags">
	<input type="text" id="tags" name="tags" class="tags form-control" value="'.$details['tags'].'">
	</div>
	</div>
	</div>
	</div>';
		$init = (isset($details['duration']) && !nullval($details['duration']))? $details['duration'] : 0;
	    $hours = floor($init / 3600);
		$minutes = floor(($init / 60) % 60);
		$seconds = $init % 60;
		$dcls =(($hours > 0) || ($minutes > 0) || ($seconds > 0))? 'hide':'';
	$data .=' 	<div class="control-group '.$dcls.'">
	<label class="control-label">'._lang("Duration:").'</label>
	<div class="controls row">
	<div class="col-md-2 col-xs-3">
	   <div class="input-group">
			<span class="input-group-addon">'._lang("HH").'</span>
			<input type="number" class="form-control" min="00" max="59" name="hours" value="'.$hours.'">
		</div>
	</div>	
		<div class="col-md-2 col-xs-3 mleft10">
		 <div class="input-group">
				<span class="input-group-addon">'._lang("MM").'</span>
				<input type="number" min="00" max="59" class="form-control" name="minutes" value="'.$minutes.'">
			</div>
		</div>
		<div class="col-md-2 col-xs-3 mleft10">
		<div class="input-group">
        <span class="input-group-addon">'._lang("SS").'</span>
        <input type="number" name="seconds" min="00" max="59" class="form-control" value="'.$seconds.'">
		</div>
		</div>
		</div>
		</div>';
	$data .='<div class="col-md-12 top10 bottom20">
	  <p class="control-label">'._lang("Audience").'</p>
	  <div class="inline">	  
			<div class="btn-group" data-toggle="buttons" role="group">
			<label class="btn btn-outline btn-primary active">
			<input type="radio" name="nsfw" autocomplete="off" value="0" checked="checked" />
			<i class="icon icon-check text-active" aria-hidden="true"></i>
			'._lang("SAFE").'</label>
			<label class="btn btn-outline btn-primary ">
			<input type="radio" name="nsfw" autocomplete="off" value="1"  />
			<i class="icon icon-check text-active" aria-hidden="true"></i>
			'._lang("NSFW").'</label>
			</div>
	  </div>
	   <div class="inline mleft20">
			<div class="btn-group" data-toggle="buttons" role="group">
			<label class="btn btn-outline btn-primary active">
			<input type="radio" name="priv" autocomplete="off" value="0" checked="checked" />
			<i class="icon icon-check text-active" aria-hidden="true"></i>
			'._lang("Public").'</label>
			<label class="btn btn-outline btn-primary ">
			<input type="radio" name="priv" autocomplete="off" value="2"  />
			<i class="icon icon-check text-active" aria-hidden="true"></i>
			'._lang("Followers").'</label>
			</div>
	  </div>
	  
	  </div>
	 </div> 
	<div class="col-md-4 col-md-offset-1">
	<div class="row">
	<div class="control-group">
	<label class="control-label">'._lang("Channel:").'</label>
	<div class="form-group form-material floating">
	'.cats_select('categ','select',' form-control',$mt).'
	  </div>             
	  </div><div class="control-group mtop20">
	<div class="controls " style="padding-left:3px; "> ';
	if($details['thumbnail'] && !empty($details['thumbnail'])) {
	$data .=' 
	<img style="max-width:100%" src="'.$details['thumbnail'].'"/>
	<input type="hidden" id="remote-img" name="remote-img" class=" col-md-12" value="'.$details['thumbnail'].'">
	';
	} else {
	$data .='
	<div class="form-group form-material">
	<label class="control-label" for="inputFile">'._lang("Choose thumbnail:").'</label>
	<input type="text" class="form-control" placeholder="'._lang("Browse for image").'" readonly="" />
	<input type="file" name="play-img" id="play-img" />
		</div>
	 ';
	}	
	$data .=' 	
	</div>
	</div>
	</div>
	</div>
	<div class="control-group blc bottom20">
	<button id="Subtn" class="btn btn-primary pull-right" type="submit">';
	if(_contains($file, 'soundcloud')) {
	$data .=_lang("Save song"); 
	} else {
	$data .=_lang("Save video");
	}
	$data .=' </button>

	<div class="clearfix"></div>
	</div>
	</div>
	</form>
		<div class="clearfix"></div>
	</div>
';
} else {
$data ='<div class="msg-warning mtop20">'._lang("Something went wrong, please try again.").'</div>';
}
return $data;
}
add_filter( 'phpvibe_title', 'modify_title' );
if((get_option('sharingrule','0') == 1) ||  is_moderator()) {	
add_filter( 'the_defaults', 'modify_content_embed' );
} else {
function udisabled() {
return _lang("This uploading section is disabled");
}
add_filter( 'the_defaults', 'udisabled'  );
}
//Time for design
 the_header();
include_once(TPL.'/sharemedia.php');
the_footer();
?>