<?php /*
** Add players support (js & css) to the header **
*/
function players_js() {
return apply_filter( 'addplayers', false );
}
/* FlowPlayer assets */
function flowsup($ini = ''){
$fp = '<link rel="stylesheet" type="text/css"href="' . site_url() . 'lib/players/fplayer/skin/skin.css">'. PHP_EOL;
$fp .= '<script src="' . site_url() . 'lib/players/fplayer/flowplayer.min.js"></script>'. PHP_EOL;
$fp .= '<script src="' . site_url() . 'lib/players/fplayer/flowplayer.quality-selector.min.js"></script>
		<script src="' . site_url() . 'lib/players/fplayer/flowplayer.hlsjs.min.js"></script>
		<script src="' . site_url() . 'lib/players/fplayer/flowplayer.speed-menu.min.js"></script>'. PHP_EOL;
return $ini.$fp;
}
/* jPlayer assets */
function jpsup($ini = ''){
$jp = '<link rel="stylesheet"  href="' . site_url() . 'lib/players/customJP/css/ytube.jplayer.css" />'. PHP_EOL;
$jp .= ' <script src="' . site_url() . 'lib/players/customJP/js/jquery.jplayer.min.js"></script>'. PHP_EOL;
$jp .= ' <script src="' . site_url() . 'lib/players/customJP/js/jplayer.easydeploy.min.js"></script>'. PHP_EOL;
return $ini.$jp;
}
/* Videojs assets */
function wavesup($ini = ''){
$vjs = '';
return $ini.$vjs;	
}
function vjsup($ini = ''){
$vjs = jwplayersup(). PHP_EOL;
return $ini.$vjs;
}
/* JwPlayer assets */
function jwplayersup($ini = ''){
$jp = '<script type="text/javascript" src="' . site_url() . 'lib/players/jwplayer/jwplayer.js"></script>'. PHP_EOL;
if (get_option('jwkey')) { $jp .= '<script type="text/javascript">jwplayer.key="' . get_option('jwkey') . '";</script>'. PHP_EOL;}
return $ini.$jp;
}

/* Player embeds
** Functions to create **
** the embed tags needed for each player **
*/
 function vjswaveplayer($file)
    {
	return ''; /* Disabled in LITE */
    }	
	
 function vjsplayer($file, $thumb, $logo = null, $type = '', $extra = '')
    {
    return _jwplayer($file, $thumb, $logo, $type, $extra);
    }
 function _jpcustom($file, $thumb, $extra = array())
    {
    /* Render jPlayer */
    global $video;
    $ads = _jads();
    $ext = substr($file, strrpos($file, '.') + 1);
    /* Overwrite default music cover if null/default */
    if ((($ext == "mp3") && nullval($thumb)) || (strpos($thumb, 'xmp3.jpg') !== false))
       {
       $thumb = get_option('musicthumb', 'http://37.media.tumblr.com/c87921eefd315482e66706d51a05054e/tumblr_n71ifjJAwU1tchrkco1_500.gif');
       }
    $embed = "<script type=\"text/javascript\">
			$(document).ready(function() {
			  if ($(window).width() < 600) {	
			  var containerWidth = $(window).width() - 6;
			  } else {
			var containerWidth = $('.video-player').width();
			  }
			var videoHeight = Math.round((containerWidth/16)*9);
			$('.mediaPlayer').mediaPlayer({
			media: {";
	if ($ext == "mp3")
       { /* Music */
       $embed .= "mp3: '" . $file . "',
	   poster: '" . $thumb."'";
       }
    else
       { /* Video */
	if(isset($extra['hd'])) {	   
	   $embed .= 'sd: {';	   
       $embed .= "m4v: '" . $file . "',
	   poster: '" . $thumb ."' }";	   
	   $embed .= ', hd: {';	   
       $embed .= "m4v: '" . $extra['hd'] . "',
	   poster: '" . $thumb ."' }";
	   } else {
	   $embed .= "m4v: '" . $file . "',
	   poster: '" . $thumb ."'";	   
	   }
       }
    $embed .= "
			},
			playerlogo : '" . thumb_fix(get_option('player-logo')) . "',
			playerlink : '" . canonical() . "',
			playerlogopos : '" . get_option('jp-logo', 'bright') . "',
			solution: 'html,flash',";
    if ($ext == "mp3")
       {
       $embed .= "supplied: 'mp3',";
       }
    $embed .= "swfPath: '" . site_url() . "lib/players/customJP/js/Jplayer.swf',
			size: {
			width: containerWidth,
			height: videoHeight
			},
			autoplay:true,
			loadstart: function() { },
			nativeVideoControls: {  ipad: /ipad/,   iphone: /iphone/,   android: /android/,   blackberry: /blackberry/,   iemobile: /iemobile/ },
			playing: function() { $('div.screenAd').addClass('hide');  }
			});
			var cpJP  = \"#\" + $(this).find('.Player').attr('id');
			$(\".mediaPlayer\").bind($.jPlayer.event.ended, function() {
            startNextVideo();	
            });
			" . $ads['js'] . "
			</script>
			<div id=\"uniquePlayer-1\" class=\"mediaPlayer darkskin \">
			<div id=\"uniqueContainer-1\" class=\"Player\">	</div>" . $ads['html'] . "
			</div>
			";
    return $embed;
    }
 function _jwplayer($file, $thumb, $logo = null, $type = null, $extra = array())
    {
    /* Render jwPlayer 7 */
    global $video;
    $ads   = _jwads();
	$media = 'file: "' . $file . '"';
	if(!empty($extra)) {
	if(isset($extra['sd'])) {unset($extra['sd']);}	
	if(isset($extra['hd'])) {unset($extra['hd']);}	
	krsort($extra);
	$media = 'sources: [';	
    foreach ($extra as $size=>$link) {		
    $media .= '{ file: "'.$link.'", type: "mp4",
        label: "'._lang(str_replace('hd','',strtolower($size)).'p').'"
      },';
	}
	$media = rtrim($media, ',');
	$media .= ']';
	}
    $embed = '<div id="video-setup" class="full">' . _lang("Loading the player...") . '</div>';
    $embed .= ' <script type="text/javascript">';
	/* Youtube doesn't work with autoplay on mobiles */
	if(!_contains($file,'youtube')) {		
	$embed .= 'var startIT = "true";';
	} else {
	$embed .= '
	var w = window,     d = document,     e = d.documentElement,
    g = d.getElementsByTagName(\'body\')[0],
    x = w.innerWidth || e.clientWidth || g.clientWidth;
	if(x < 900) {
	var startIT = "false";
	} else {
	var startIT = "true";	
	}';	
	}
	/* End Youtube mobile hack */
	
	/* Actual video caontainer */
	$embed .= 'jwplayer("video-setup").setup({ '.$media.',    image: "' . $thumb . '",primary : "html5", stretching: "fill", "controlbar": "bottom",  width: "100%",aspectratio:"16:9", autostart: startIT';
    if ($type)
       {
       $embed .= ', type: "' . strtolower($type) . '" ';
       }
    if ($video->srt)
       {
       $embed .= ', tracks: [ { file: "' . site_url() .'storage/'. get_option('mediafolder') . '/' . $video->srt . '" } ] ';
       }
    if ($logo && !nullval($logo))
       {
       $embed .= ',	logo: { file: "' . $logo . '",  position: "bottom-right",  link: "' . site_url() . '" , hide: true   }';
       }
       //Sharing plugin
       $embed .= ',skin: {   name: "glow"  },	sharing: { link: "' . canonical() . '",  "sites": ["facebook", "twitter","linkedin","pinterest","tumblr","googleplus","reddit"]},
	   abouttext: "'._lang('Video available at').' '.get_option('site-logo-text').'",
	aboutlink: "'.site_url().'"';
   
    $embed .= '  });';
     $embed .= '
	    $(document).ready(function() {
              jwplayer().onComplete( function(){
				 startNextVideo();				  
				  });
		});		  
        ';
    
    $embed .= $ads['js'] . ' </script>' . $ads['html'];
    return $embed;
    }
 function flowplayer($file, $thumb, $logo = null, $type = null, $extra=array())
    { global $video;
    $ads   = _flowads();
    $embed = '<script>	  
	var thelogo = "' . $logo . '"; 
	var thelink = "' . site_url() . '";
	</script>';
    $embed .= '	<style>
.flowplayer.is-poster {   background-image: url("'.$thumb.'"); }
.flowplayer.is-poster {    background-color: #888; } </style>		
<div id="vibeflow" class="fp-slim is-closeable"></div>	
<script>';
		if(!empty($extra)) {
	/* Unset sd/hd */
    if(isset($extra['sd'])) {unset($extra['sd']);}	
	if(isset($extra['hd'])) {unset($extra['hd']);}		
	$qu = array_keys($extra);	
	arsort($qu);
if(_contains($file, 'stream')) {
	$defaultqs = explode('&q=',$file);
	$defaultq = $defaultqs[1];
	$defaultt = $defaultqs[0];
	$tmplt = @$defaultqs[0].'&q={q}';
} else {
	$defaultqs = explode('-',$file);
	$defaultq = $defaultqs[1];
	$defaultq = str_replace('.mp4','',$defaultq);
	$tmplt = @$defaultqs[0].'-{q}.{ext}';
	}
	$sources = '';
	$qual = '[';
	foreach ($qu as $qua) {
	$qual .= '"'.$qua.'",';
     $sources .='{ label: "'.str_replace('hd','',strtolower($qua)).'p", src:  "'.str_replace($defaultq,$qua,$file).'" },';	 
	}
	$qual =rtrim($qual,',').']';
	  }
		$embed .= '
		flowplayer("#vibeflow", {
		autoplay: true,	
		fullscreen: true,
		native_fullscreen: true,
		share: false,
        clip: {
        title: "'._html($video->title).'",		 
		';
	if(!empty($extra)) {	
	$embed.= '	qualities: '.$qual.',
		defaultQuality: "'.$defaultq.'",
	vodQualities: {
        template: "'.$tmplt.'",
        qualities: ['.$sources.']
      }, 
	';	
	}
    $embed .= '  sources: [ 
	{ type: "video/mp4", src:  "'.$file.'" }
	    ]
    }
});
</script>
	
	<script>
  $(document).ready(function() {
    fapi = flowplayer();
    fapi.one("finish", function(){
    startNextVideo();
    });
  });
	' . $ads['js'] . '
	</script>
	' . $ads['html'];
    return $embed;
    }
?>