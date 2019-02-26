<?php require_once( INC.'/class.players.php' ); /* Players support */
/* Spicy */
function removeComTags($input){
  	// Tags to remove
	$commonWords = array('a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','b','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','c','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','d','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','e','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','f','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','g','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','h','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','j','just','k','keep','keeps','kept','know','known','knows','l','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','m','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','n','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','o','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','p','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','q','que','quite','qv','r','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','s','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','t','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','w','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','x','y','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','z','zero');
 	return preg_replace('/\b('.implode('|',$commonWords).')\b/','',$input);
}
/* End Spicy*/
 //constants
 define('UNKNOWN_PROVIDER', _lang('Unknown provider or incorrect URL. Please try again.'));
 define('INVALID_URL', _lang('This URL is invalid or the video is removed by the provider.'));
 $qualities = array();
 class Vibe_Providers
     {
     protected $height = 300;
     protected $width = 600;
     protected $link = "";
     function __construct($width = null, $height = null)
         {
         $this->setDimensions($width, $height);
         }
     public function theLink()
         {
         if (isset($this->link))
             {
             return $this->link;
             }
         }
     //check if video link is valid
     public function isValid($videoLink)
         {
         $this->link    = $videoLink;
         $videoProvider = $this->decideVideoProvider();
         if (!empty($videoProvider) && $videoProvider != "")
             {
             return true;
             }
         else
             {
             return false;
             }
         }
     // getEmbedCode
     public function getEmbedCode($videoLink, $width = null, $height = null)
         {
         $this->setDimensions($width, $height);
         if ($videoLink != "")
             {
             if (!is_numeric(strpos($videoLink, "http://")) && !is_numeric(strpos($videoLink, "https://")))
                 {
                 $videoLink = "https://" . $videoLink;
                 }
             $this->link    = $videoLink;
             $embedCode     = "";
             $videoProvider = $this->decideVideoProvider();
             if ($videoProvider == "")
                 {
                 $embedCode = UNKNOWN_PROVIDER;
                 }
             else
                 {
                 $embedCode = $this->generateEmbedCode($videoProvider);
                 }
             }
         else
             {
             $embedCode = INVALID_URL;
             }
         return $embedCode;
         }
     //Providers
     public function Hostings()
         {
         $hostings = array(		     
             'youtube',
             'vimeo',
			 'gametrailers',
             'metacafe',
             'dailymotion',
             'hell',
             'trilulilu',
             'viddler',
             'blip',
             'soundcloud',
             'myspace',
             'twitcam',
             'ustream',
             'liveleak',
             'livestream',
             'facebook',           
			 'putlocker',
             'vk',
             'vine',
             'telly',      
             'docs.google.com'
         );
         return apply_filter('vibe-video-sources', $hostings);
         }
     // decide video provider
     private function decideVideoProvider()
         {
         $videoProvider = "";	
         //providers list
         //hook for more sources
         $hostings      = $this->Hostings();
         //check	provider
		 $parse = parse_url($this->link);
         for ($i = 0; $i < count($hostings); $i++)
             {	
             if(isset($hostings[$i])) {		 
             if (is_numeric(strpos($parse['host'], $hostings[$i])))
                 {
                 $videoProvider = $hostings[$i];
                 }             
			 }
			 }
		 
         return $videoProvider;
         }
     // generate video ıd from link
     public function VideoProvider($link = null)
         {
				
			 
         if (is_null($link))
             {
             $thisProvider = $this->decideVideoProvider();
             }
         else
             {
             $this->link   = $link;
             $thisProvider = $this->decideVideoProvider();
             }
         return $thisProvider;
         }
     public function getVideoId($operand, $optionaOperand = null)
         {
         $videoId      = null;
         $startPosCode = strpos($this->link, $operand);
         if ($startPosCode != null)
             {
             $videoId = substr($this->link, $startPosCode + strlen($operand), strlen($this->link) - 1);
             if (!is_null($optionaOperand))
                 {
                 $startPosCode = strpos($videoId, $optionaOperand);
                 if ($startPosCode > 0)
                     {
                     $videoId = substr($videoId, 0, $startPosCode);
                     }
                 }
             }
         return $videoId;
         }
 public function remotevideo($url)
         {
         global $video;
         $embedCode = '';
         if ($url)
             {
             $pieces_array     = explode('.', $url);
             $ext              = end($pieces_array);
             $choice           = get_option('remote-player', 1);
             $mobile_supported = array(
                 "mp4",
                 "mp3",
                 "webm",
                 "ogv",
                 "m3u8",
                 "ts",
                 "tif"
             );
             if (!in_array($ext, $mobile_supported))
                 {
                 /*force jwplayer always on non-mobi formats, as others are just html5 */
                 $choice = 1;
                 }
             if ($choice == 1)
                 {
                 $embedCode = _jwplayer($url, thumb_fix($video->thumb), thumb_fix(get_option('player-logo')), $ext);
                 }
             elseif ($choice == 2)
                 {
                 $embedCode = flowplayer($url, thumb_fix($video->thumb), thumb_fix(get_option('player-logo')), $ext);
                 }
             elseif ($choice == 6)
                 {
                 $embedCode = vjsplayer($url, thumb_fix($video->thumb), thumb_fix(get_option('player-logo')), $ext);
                 }
             else
                 {
                 $embedCode = _jpcustom($url, thumb_fix($video->thumb));
                 }
             }
         return $embedCode;
         }
     // generate video embed code via using standart templates
     private function generateEmbedCode($videoProvider)
         {
         global $video,$qualities;
         $embedCode = "";
         switch ($videoProvider)
         {  
             case 'vine':
                 $videoId = $this->getVideoId("/v/");
                 if ($videoId != null)
                     {
                     $embedCode .= '<iframe class="vine-embed" src="https://vine.co/v/' . $videoId . '/embed/simple?audio=1" width="' . $this->width . '" height="' . $this->height . '" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>';
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
            case 'facebook':
					$videoId = $this->getVideoId("v=", "&");
					if (empty($videoId)) {
					if (strpos($this->link, '/?') !== false) {
					list($real,$junk) = @explode('/?', $this->link);
					} else {
					$real =    $this->link;
					}
					if(isset($real)) {
					$videoId = $this->getLastNr(rtrim($real, '/'));
					}
					}
					if ($videoId != null) {
					$embedCode .= '<div class="fb-video" data-href="https://www.facebook.com/video.php?v=' . $videoId . '" " data-width="1280" data-allowfullscreen="true"></div>';
					$embedCode .= _ad('1');
					} else {
					$embedCode = INVALID_URL;
					}
					break;
             case 'docs.google.com':
                 $videoId = str_replace('/edit', '/preview', $this->link);
                 if ($videoId != null)
                     {
                     $embedCode .= '<iframe src="' . $videoId . '" width="' . $this->width . '" height="' . $this->height . '"  frameborder="0"></iframe>';
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'youtube':
                 $videoId = $this->getVideoId("v=", "&");
                 if ($videoId != null)
                     {
                     $choice = get_option('youtube-player');
                     if ($choice < 1)
                         {
                         $embedCode .= "<iframe id=\"ytplayer\" width=\"" . $this->width . "\" height=\"" . $this->height . "\" src=\"https://www.youtube.com/embed/" . $videoId . "?enablejsapi=1&amp;version=3&amp;html5=1&amp;iv_load_policy=3&amp;modestbranding=1&amp;nologo=1&amp;vq=large&amp;autoplay=1&amp;ps=docs&amp;rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen=\"true\"></iframe>";
         $embedCode .= '<script>
							 var tag = document.createElement(\'script\');
							 tag.src = "https://www.youtube.com/iframe_api";
					         var firstScriptTag = document.getElementsByTagName(\'script\')[0];
                             firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
							 var player;
							 function onYTPlayerReady(event) {
							event.target.playVideo();
							}
							function onYTPlayerStateChange(event) {
							if(event.data === 0) {					
							startNextVideo();	
							}
							}
							 function onYouTubeIframeAPIReady() {
								player = new YT.Player(\'ytplayer\', {
								events: {
									\'onReady\': onYTPlayerReady,
									\'onStateChange\': onYTPlayerStateChange
										}
									});
							}
									
                  </script>';
                            
                         $embedCode .= _ad('1');
                         }
                     elseif($choice < 3)
                         {
                         $real_link = 'https://www.youtube.com/watch?v=' . $videoId;
                         $img       = 'https://img.youtube.com/vi/' . $videoId . '/mqdefault.jpg';
                         $embedCode = _jwplayer($real_link, $img, thumb_fix(get_option('player-logo')));
                         } else {
						 $real_link = 'https://www.youtube.com/watch?v=' . $videoId;
                         $img       = 'https://img.youtube.com/vi/' . $videoId . '/mqdefault.jpg';
                         $embedCode = vjsplayer($real_link, $img); 
						 }
						 
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'vimeo':
                 $videoIdForChannel = $this->getVideoId('#');
                 if (strlen($videoIdForChannel) > 0)
                     {
                     $videoId = $videoIdForChannel;
                     }
                 else
                     {
                     $videoId = $this->getVideoId(".com/");
                     }
                 //$videoId = $videoForChannel;
                 if ($videoId != null)
                     {
                     $embedCode .= '<iframe id="vimvideo" src="https://player.vimeo.com/video/' . $videoId . '?title=0&amp;player_id=vimvideo&amp;byline=0&amp;portrait=0&amp;color=cc181e&amp;autoplay=1" width="' . $this->width . '" height="' . $this->height . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
                     $embedCode .='<script src="https://f.vimeocdn.com/js/froogaloop2.min.js"></script>';
					 $embedCode .= '<script>
					 var nextPlay;
						$(document).ready(function() {
						if($("li#playingNow").html()) {	
						nextPlay = $("li#playingNow").next().find("a.clip-link").attr("href");
						}					
						});
					 var iframe = $("#vimvideo")[0],
                     player = $f(iframe);
				     player.addEvent(\'ready\', function() {		
		             player.addEvent(\'finish\', onFinish);
	                 });
                    function onFinish(id) {
					startNextVideo();
                    }
					 </script>';					 
					 $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'soundcloud':
                 if ($this->link)
                     {
                     $embedCode .= '<iframe width="100%" height="400" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?visual=true&url=' . $this->link . '&show_artwork=false&buying=false&sharing=false&show_comments=false"></iframe>';
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'putlocker':
                 $videoId = $this->getVideoId("file/");
                 if ($videoId != null)
                     {
                     $embedCode .= '<iframe width="' . $this->width . '" height="' . $this->height . '" src="https://www.putlocker.com/embed/' . $videoId . '" frameborder="0" scrolling="no" allowfullscreen></iframe>';
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'hell':
                 $videoId = $this->getVideoId("videos/");
                 //$videoId = $this->getLastNr($this->link);
                 if ($videoId != null)
                     {
                     $embedCode .= '<iframe width="' . $this->width . '" height="' . $this->height . '" src="https://www.hell.tv/embed/video/' . $videoId . '" frameborder="0" scrolling="no" allowfullscreen></iframe>';
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'dailymotion':
                 $videoId = $this->getVideoId("video/");
                 if ($videoId != null)
                     {
                     $embedCode .= '<iframe frameborder="0" width="' . $this->width . '" height="' . $this->height . '" src="https://www.dailymotion.com/embed/video/' . $videoId . '"></iframe>';
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'trilulilu':
                 $videoId = $this->getVideoId(".ro/");
                 if ($videoId != null)
                     {
                     $embedCode .= '<iframe width="' . $this->width . '" height="' . $this->height . '" src="https://embed.trilulilu.ro/' . $videoId . '" frameborder="0" allowfullscreen></iframe> ';
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'liveleak':
                 $videoId = $this->getVideoId("i=");
                 if ($videoId != null)
                     {
                     $embedCode .= '<iframe width="' . $this->width . '" height="' . $this->height . '" src="https://www.liveleak.com/e/' . $videoId . '" frameborder="0" allowfullscreen></iframe> ';
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'metacafe':
                 $videoId = $this->getVideoId("watch/", "/");
                 if ($videoId != null)
                     {
                     $embedCode .= '<iframe src="https://www.metacafe.com/embed/' . $videoId . '/" width="' . $this->width . '" height="' . $this->height . '" allowFullScreen frameborder=0></iframe>';
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'viddler':
                 $videoId = $this->getVideoId("v/");
                 if ($videoId != null)
                     {
                     $embedCode .= "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\"" . $this->width . "\" height=\"" . $this->height . "\" ";
                     $embedCode .= "id=\"viddler_1f72e4ee\">";
                     $embedCode .= "<param name=\"movie\" value=\"https://www.viddler.com/player/" . $videoId . "\" />";
                     $embedCode .= "<param name=\"allowScriptAccess\" value=\"always\" />";
                     $embedCode .= "<param name=\"allowFullScreen\" value=\"true\" />";
                     $embedCode .= "<embed src=\"https://www.viddler.com/player/" . $videoId . "\"";
                     $embedCode .= " width=\"" . $this->width . "\" height=\"" . $this->height . "\" type=\"application/x-shockwave-flash\" ";
                     $embedCode .= "allowScriptAccess=\"always\"";
                     $embedCode .= "allowFullScreen=\"true\" name=\"viddler_" . $videoId . "\"\"></embed></object>";
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'blip':
                 $videoId = $this->getLastNr($this->link);
                 if ($videoId != null)
                     {
                     $embedCode .= "<embed src=\"https://blip.tv/file/" . $videoId . "\" ";
                     $embedCode .= "type=\"application/x-shockwave-flash\" width=\"" . $this->width . "\" height=\"" . $this->height . "\"";
                     $embedCode .= " allowscriptaccess=\"always\" allowfullscreen=\"true\"></embed>";
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'myspace':
                 $this->link = strtolower($this->link);
                 $videoId    = $this->getVideoId("vid/", "&");
                 if ($videoId != null)
                     {
                     $embedCode .= "<object width=\"" . $this->width . "\" height=\"" . $this->height . "\" ><param name=\"allowFullScreen\" ";
                     $embedCode .= "value=\"true\"/><param name=\"wmode\" value=\"transparent\"/><param name=\"movie\" ";
                     $embedCode .= "value=\"https://mediaservices.myspace.com/services/media/embed.aspx/m=" . $videoId . ",t=1,mt=video\"/>";
                     $embedCode .= "<embed src=\"https://mediaservices.myspace.com/services/media/embed.aspx/m=" . $videoId . ",t=1,mt=video\" ";
                     $embedCode .= "width=\"" . $this->width . "\" height=\"" . $this->height . "\" allowFullScreen=\"true\" type=\"application/x-shockwave-flash\" ";
                     $embedCode .= "wmode=\"transparent\"></embed></object>";
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'ustream':
                 $videoId = $this->getVideoId("recorded/", '/');
                 if ($videoId != null)
                     {
                     $embedCode .= "<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" ";
                     $embedCode .= "width=\"" . $this->width . "\" height=\"" . $this->height . "\" ";
                     $embedCode .= "id=\"utv867721\" name=\"utv_n_859419\"><param name=\"flashvars\" ";
                     $embedCode .= "value\"beginPercent=0.0236&amp;endPercent=0.2333&amp;autoplay=false&locale=en_US\" />";
                     $embedCode .= "<param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" ";
                     $embedCode .= "value=\"always\" />";
                     $embedCode .= "<param name=\"src\" value=\"https://www.ustream.tv/flash/video/" . $videoId . "\" />";
                     $embedCode .= "<embed flashvars=\"beginPercent=0.0236&amp;endPercent=0.2333&amp;autoplay=false&locale=en_US\" ";
                     $embedCode .= "width=\"" . $this->width . "\" height=\"" . $this->height . "\" ";
                     $embedCode .= "allowfullscreen=\"true\" allowscriptaccess=\"always\" id=\"utv867721\" ";
                     $embedCode .= "name=\"utv_n_859419\" src=\"https://www.ustream.tv/flash/video/" . $videoId . "\" ";
                     $embedCode .= "type=\"application/x-shockwave-flash\" /></object>";
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'livestream':
                 $firstID  = $this->getVideoId("com/", '/');
                 $secondID = $this->getVideoId("?clipId=", '&');
                 if ($firstID != null && $secondID != null)
                     {
                     $embedCode .= "<object width=\"" . $this->width . "\" height=\"" . $this->height . "\" id=\"lsplayer\" ";
                     $embedCode .= "classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\">";
                     $embedCode .= "<param name=\"movie\" ";
                     $embedCode .= "value=\"https://cdn.livestream.com/grid/LSPlayer.swf?channel=" . $firstID . "&amp;";
                     $embedCode .= "clip=" . $secondID . "&amp;autoPlay=false\"></param>";
                     $embedCode .= "<param name=\"allowScriptAccess\" value=\"always\"></param><param name=\"allowFullScreen\" ";
                     $embedCode .= "value=\"true\"></param><embed name=\"lsplayer\" wmode=\"transparent\" ";
                     $embedCode .= "src=\"https://cdn.livestream.com/grid/LSPlayer.swf?channel=" . $firstID . "&amp;";
                     $embedCode .= "clip=" . $secondID . "&amp;autoPlay=false\" ";
                     $embedCode .= "width=\"" . $this->width . "\" height=\"" . $this->height . "\" allowScriptAccess=\"always\" allowFullScreen=\"true\" ";
                     $embedCode .= "type=\"application/x-shockwave-flash\"></embed></object>	";
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'gametrailers':
                 $videoFullID = $this->getVideoId("video/");
                 $videoId     = strpos($videoFullID, "/");
                 $videoId     = substr($videoFullID, $videoId + 1, strlen($videoFullID));
                 if ($videoId != null)
                     {
                     $embedCode .= '<embed src="https://media.mtvnservices.com/mgid:moses:video:gametrailers.com:' . $videoId . '" width="' . $this->width . '" height="' . $this->height . '" type="application/x-shockwave-flash" allowFullScreen="true" allowScriptAccess="always" base="." flashVars=""></embed>';
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'vk':
                 $firstIDs  = $this->getVideoId("video", '_');
                 $secondIDs = $this->getVideoId("_", '?');
                 $thirdIDs  = $this->getVideoId("hash=");
                 if ($firstIDs != null && $secondIDs != null && $thirdIDs != null)
                     {
                     $embedCode .= "<iframe src=\"https://vk.com/video_ext.php?oid=" . $firstIDs . "&id=" . $secondIDs . "&hash=" . $thirdIDs . "&sd\" width=\"" . $this->width . "\" height=\"" . $this->height . "\" frameborder=\"0\"></iframe>";
                     $embedCode .= _ad('1');
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             case 'telly':
                 $videoIdForChannel = $this->getVideoId('guid=');
                 if (strlen($videoIdForChannel) > 0)
                     {
                     $videoId = $videoIdForChannel;
                     }
                 else
                     {
                     $videoId = $this->getVideoId(".com/", '?');
                     }
                 if ($videoId != null)
                     {
                     $embedCode .= "<iframe src=\"https://telly.com/embed.php?guid=" . $videoId . "&#038;autoplay=0\" title=\"Telly video player \" class=\"twitvid-player\" type=\"text/html\" width=\"" . $this->width . "\" height=\"" . $this->height . "\" frameborder=\"0\"></iframe>";
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
             default:
                 if (has_filter('EmbedModify'))
                     {
                     $embedCode = apply_filters('EmbedModify', false);
                     }
                 else
                     {
                     $embedCode = INVALID_URL;
                     }
                 break;
         }
         return $embedCode;
         }
     // get id from weird rewrites
     public function getLastNr($url)
         {
         $pieces_array = explode('/', $url);
         $end_piece    = end($pieces_array);
         $id_pieces    = explode('-', $end_piece);
         $last_piece   = end($id_pieces);
         $videoId      = preg_replace("/[^0-9]/", "", $last_piece);
         return $videoId;
         }
     private function setDimensions($width = null, $height = null)
         {
         if ((!is_null($width)) && ($width != ""))
             {
             $this->width = $width;
             }
         if ((!is_null($height)) && ($height != ""))
             {
             $this->height = $height;
             }
         }
     private function match($regex, $str, $i = 0)
         {
         if (preg_match($regex, $str, $match) == 1)
             {
             return $match[$i];
             }
         else
             {
             return null;
             }
         }
     function get_data()
         {
         $default = array(
             'thumbnail' => '',
             'title' => '',
             'tags' => '',
             'description' => '',
             'duration' => ''
         );
         $details = $this->get_details();
         if (is_array($details))
             {
             return array_replace($default, $details);
             }
         else
             {
             return $default;
             }
         }
     function get_details()
         {
         $provider = $this->decideVideoProvider();
		 //var_dump($provider);
         switch ($provider)
         {
			/* Start Spicy */
case 'redtube':
$videoId            = $this->getVideoId(".com/");
$json_url           = 'https://api.redtube.com/?data=redtube.Videos.getVideoById&video_id=' . $videoId . '&output=json&thumbsize=big';
$content            = file_get_contents($json_url);
$data               = json_decode($content, true);
$video              = $data["video"];
$video['description'] = $video['title'];
$duration_arr       = explode(":", $video['duration']);
$video['duration']  = $duration_arr[0] * 60 + $duration_arr[1];
$video['thumbnail'] = str_replace("m.jpg", "b.jpg", $video['default_thumb']);
if (!empty($video['tags'])):
$video['tags'] = implode(', ', $video['tags']);
endif;
if (!empty($video['stars'])):
$video['stars'] = implode(', ', $video['stars']);
endif;
if (!empty($video['tags']) && !empty($video['stars'])):
$video['tags'] = $video['stars'] . "," . $video['tags'];
endif;
unset($data);
return $video;
break;
case 'pornhub':
$videoId = $this->getVideoId("viewkey=");
$link = 'https://www.pornhub.com/webmasters/video_by_id?thumbsize=large_hd&id='.$videoId;
$html = $this->getDataFromUrl($link);
$html              = json_decode($html, true);
$video              = $html["video"];
$video['description'] = $video['title'];
$duration_arr       = explode(":", $video['duration']);
$video['duration']  = $duration_arr[0] * 60 + $duration_arr[1];
$video['thumbnail'] = str_replace("m.jpg", "b.jpg", $video['default_thumb']);
$video['localtags'] = array();
if (!empty($video['tags'])):
foreach ($video['tags'] as $tag) 
{
$video['localtags'][] = $tag['tag_name'];	
}
$video['tags'] = implode(', ', $video['localtags']);
endif;
if (!empty($video['stars'])):
$video['stars'] = implode(', ', $video['stars']);
endif;
if (!empty($video['tags']) && !empty($video['stars'])):
$video['tags'] = $video['stars'] . "," . $video['tags'];
endif;
unset($html);
return $video;
break;
case 'xhamster':
$videoId            = $this->getVideoId(".com/movies/", "/");
$html = $this->getDataFromUrl($this->link);
preg_match('/background: url(.+?)no-repeat center/', $html, $tmatches);
$video['thumbnail'] = (isset($tmatches[1]))? str_replace(array("(",")"), "", $tmatches[1]) : '';
preg_match('~h1 itemprop="name">(.+?)</h1>~si', $html, $titlematches);
$video['title'] = $video['description'] = (isset($titlematches[1]))? strip_tags($titlematches[1]) : '';
$video['tags'] = implode(",", explode(" ",removeComTags($video['title'])));
preg_match('~Runtime:</span>(.+?)</div>~si', $html, $time);
if(isset($time[1])) {
$duration_arr       = explode(":", strip_tags($time[1]));
$video['duration']  = $duration_arr[0] * 60 + $duration_arr[1];	
}
unset($html);
return $video;
break;
case 'tnaflix':
$html = $this->getDataFromUrl($this->link);
$videoId            = $this->getVideoId("/video");
$video = array();
 $matches=null;
    preg_match_all('~<\s*meta\s+property="(og:[^"]+)"\s+content="([^"]*)~i',     $html,$matches);
    $ogtags=array();
    for($i=0;$i<count($matches[1]);$i++)
    {
        $ogtags[$matches[1][$i]]=$matches[2][$i];
    }
$video['title'] = (isset($ogtags["og:title"]))? $ogtags["og:title"] : ''; 
$video['thumbnail'] = (isset($ogtags["og:image"]))? $ogtags["og:image"] : '';
$video['description'] = (isset($ogtags["og:description"]))? $ogtags["og:description"] : '';
$video['tags'] = implode(",", explode(" ",removeComTags($video['title'])));
$pat = '~ data-vid=\''.$videoId.'\' (.*?) data-time=\'(.*?)\' ~si';
preg_match_all($pat, $html, $ds);
if(isset($ds[2][0])) {
$video['duration']	= intval($ds[2][0]);
}
unset($html);
return $video;
break;
case 'mofosex':
$html = $this->getDataFromUrl($this->link);
$html = $this->getDataFromUrl($this->link);
//$videoslug = $this->getVideoId(".com/video/");
    preg_match_all('~<\s*meta\s+property="(og:[^"]+)"\s+content="([^"]*)~i',     $html,$matches);
    $ogtags=array();
    for($i=0;$i<count($matches[1]);$i++)
    {
        $ogtags[$matches[1][$i]]=$matches[2][$i];
    }
$video['thumbnail'] = (isset($ogtags["og:image"]))? $ogtags["og:image"] : '';
preg_match('~h1>(.+?)</h1>~si', $html, $titlematches);
$video['title'] = $video['description'] = (isset($titlematches[1]))? strip_tags($titlematches[1]) : '';

unset($html);
return $video;
break;
case 'pornrabbit':
$html = $this->getDataFromUrl($this->link);
//$videoslug = $this->getVideoId(".com/video/");
    preg_match_all('~<\s*meta\s+property="(og:[^"]+)"\s+content="([^"]*)~i',     $html,$matches);
    $ogtags=array();
    for($i=0;$i<count($matches[1]);$i++)
    {
        $ogtags[$matches[1][$i]]=$matches[2][$i];
    }
$video['title'] = (isset($ogtags["og:title"]))? $ogtags["og:title"] : ''; 
$video['thumbnail'] = (isset($ogtags["og:image"]))? $ogtags["og:image"] : '';
$video['description'] = (isset($ogtags["og:description"]))? $ogtags["og:description"] : '';
$video['tags'] = implode(",", explode(" ",removeComTags($video['title'])));
$pat = '~ '.$video["title"].' is (.*?) minutes long ~si';
preg_match($pat, $html, $time);
if(isset($time[1])) {
$duration_arr       = explode(":", strip_tags($time[1]));
$video['duration']  = $duration_arr[0] * 60 + $duration_arr[1];	
}
unset($html);
return $video;
break;

case 'tube8':
$ids = explode("/",rtrim($this->link,'/'));
$id = end($ids);
$link = 'https://api.tube8.com/api.php?action=getvideobyid&output=json&thumbsize=big&video_id='.$id;
$html = $this->getDataFromUrl($link);
$video             = json_decode($html, true);
$video['description'] = $video['title'];
$video['duration']  = $video['video']['duration'];
$video['thumbnail'] = $video['thumbs']['big'][3];
$video['localtags'] = array();
if (!empty($video['tags'])):
foreach ($video['tags'] as $tag) 
{
$video['localtags'][] = $tag;	
}
$video['tags'] = implode(', ', $video['localtags']);
endif;
if (!empty($video['stars'])):
$video['stars'] = implode(', ', $video['stars']);
endif;
if (!empty($video['tags']) && !empty($video['stars'])):
$video['tags'] = $video['stars'] . "," . $video['tags'];
endif;
unset($html);
return $video;
break;
case 'spankwire':
$videoId = $this->getVideoId("/video", "/");
$link = 'https://www.spankwire.com/api/HubTrafficApiCall?data=getVideoById&thumbsize=large&output=json&video_id='.$videoId;
$html = $this->getDataFromUrl($link);
$video             = json_decode($html, true);
$video = $video['video'];
$video['description'] = $video['title'];
$duration_arr       = explode(":", strip_tags($video['duration']));
$video['duration']  = $duration_arr[0] * 60 * 60 + $duration_arr[1] * 60 + $duration_arr[2];
$video['thumbnail'] = $video['default_thumb'];
$video['localtags'] = array();
if (!empty($video['tags'])):
foreach ($video['tags'] as $tag) 
{
$video['localtags'][] = $tag;	
}
$video['tags'] = implode(', ', $video['localtags']);
endif;
if (!empty($video['stars'])):
$video['stars'] = implode(', ', $video['stars']);
endif;
if (!empty($video['tags']) && !empty($video['stars'])):
$video['tags'] = $video['stars'] . "," . $video['tags'];
endif;
unset($html);
return $video;
break;
case 'youporn':
$videoId = $this->getVideoId("watch/","/");
$link = 'https://www.youporn.com/api/webmasters/video_by_id/?output=json&thumbsize=big&video_id='.$videoId;
$html = $this->getDataFromUrl($link);
$video             = json_decode($html, true);
$video = $video['video'];
$video['description'] = $video['title'];
$duration_arr       = explode(":", strip_tags($video['duration']));
$video['duration']  = $duration_arr[0] * 60 + $duration_arr[1];
$video['thumbnail'] = $video['default_thumb'];
$video['localtags'] = array();
if (!empty($video['tags'])):
foreach ($video['tags'] as $tag) 
{
$video['localtags'][] = $tag['tag_name'];	
}
$video['tags'] = implode(', ', $video['localtags']);
endif;
if (!empty($video['stars'])):
$video['stars'] = implode(', ', $video['stars']);
endif;
if (!empty($video['tags']) && !empty($video['stars'])):
$video['tags'] = $video['stars'] . "," . $video['tags'];
endif;
unset($html);
return $video;
break;
case 'fantasti':
$ids = explode("/",rtrim($this->link,'/'));
$videoId = end($ids);
$link = 'https://fantasti.cc/wm/getVideoById.json?thumbsize=big&video_id='.$videoId;
$html = $this->getDataFromUrl($link);
$video             = json_decode($html, true);
$video = $video['video'];
$video['description'] = $video['title'];
$duration_arr       = explode(":", strip_tags($video['duration']));
$video['duration']  = $duration_arr[0] * 60 + $duration_arr[1];
$video['thumbnail'] = $video['default_thumb'];
$video['localtags'] = array();
if (!empty($video['tags'])):
foreach ($video['tags'] as $tag) 
{
$video['localtags'][] = $tag['tag'];	
}
$video['tags'] = implode(', ', $video['localtags']);
endif;
if (!empty($video['stars'])):
$video['stars'] = implode(', ', $video['stars']);
endif;
if (!empty($video['tags']) && !empty($video['stars'])):
$video['tags'] = $video['stars'] . "," . $video['tags'];
endif;
unset($html);
return $video;
break;
/* End Spicy */ 
             case 'vine':
                 $videoId              = $this->getVideoId("/v/");
                 $video                = array();
                 $video['description'] = '';
                 $video['title']       = '';
                 $video['thumbnail']   = '';
                 $url                  = "https://vine.co/v/" . $videoId;
                 $data                 = file_get_contents($url);
                 preg_match('~<\s*meta\s+property="(twitter:description)"\s+content="([^"]*)~i', $data, $matches);
                 if (isset($matches[2]))
                     {
                     $video['description'] = $matches[2];
                     }
                 unset($matches);
                 preg_match('/property="twitter:title" content="(.*?)"/', $data, $matches);
                 if (isset($matches[1]))
                     {
                     $video['title'] = $matches[1];
                     }
                 unset($matches);
                 preg_match('/property="twitter:image" content="(.*?)"/', $data, $matches);
                 if (isset($matches[1]))
                     {
                     $video['thumb']     = explode('?versionId', $matches[1]);
                     $video['thumbnail'] = $video['thumb']['0'];
                     }
                 $video['duration'] = 6;
                 unset($matches);
                 unset($data);
                 return $video;
                 break;
             case 'soundcloud':
                 $video = get_soundcloud($this->link);
                 return $video;
                 break;
             case 'vimeo':
                 $json_url              = "https://vimeo.com/api/v2/video/" . $this->getLastNr($this->link) . ".json";
                 $content               = $this->getDataFromUrl($json_url);
                 $video                 = json_decode($content, true);
                 $video[0]['thumbnail'] = $video[0]['thumbnail_medium'];
                 return $video[0];
                 break;
             case 'youtube':
                 if (!nullval(get_option('youtubekey', null)))
                     {
                     $yt            = new Youtube(array(
                         'key' => get_option('youtubekey')
                     ));
                     $id            = $yt->parseVIdFromURL($this->link);
                     $video         = $yt->Single($id);
                     $tags          = array_unique(explode('-', nice_tag(removeCommonWords($video["title"]))));
                     $video["tags"] = implode(',', $tags);
                     return $video;
                     }
                 break;
             case 'metacafe':
                 $idvid                = $this->getVideoId("watch/", "/");
                 $file_data            = "https://www.metacafe.com/api/item/" . $idvid;
                 $video                = array();
                 $xml                  = new SimpleXMLElement(file_get_contents($file_data));
                 $title_query          = $xml->xpath('/rss/channel/item/title');
                 $video['title']       = $title_query ? strval($title_query[0]) : '';
                 $description_query    = $xml->xpath('/rss/channel/item/media:description');
                 $video['description'] = $description_query ? strval($description_query[0]) : '';
                 $tags_query           = $xml->xpath('/rss/channel/item/media:keywords');
                 $video['tags']        = $tags_query ? explode(',', strval(trim($tags_query[0]))) : null;
                 if (isset($video['tags']) && !empty($video['tags']))
                     {
                     $video['tags'] = implode(', ', $video['tags']);
                     }
                 else
                     {
                     $video['tags'] = '';
                     }
                 $date_published_query = $xml->xpath('/rss/channel/item/pubDate');
                 $video['uploaded']    = $date_published_query ? ($date_published_query[0]) : null;
                 $thumbnails_query     = $xml->xpath('/rss/channel/item/media:thumbnail/@url');
                 if (isset($thumbnails_query[0]))
                     {
                     $video['thumbnail'] = strval($thumbnails_query[0]);
                     }
                 else
                     {
                     $video['thumbnail'] = '';
                     }
                 $video['duration'] = null;
                 return $video;
                 break;
             case 'dailymotion':
                 if (preg_match('#https://www.dailymotion.com/video/([A-Za-z0-9]+)#s', $this->link, $match))
                     {
                     $idvid = $match[1];
                     }
                 $file_data            = "https://www.dailymotion.com/rss/video/" . $idvid;
                 $video                = array();
                 $xml                  = new SimpleXMLElement(file_get_contents($file_data));
                 $title_query          = $xml->xpath('/rss/channel/item/title');
                 $video['title']       = $title_query ? strval($title_query[0]) : '';
                 $description_query    = $xml->xpath('/rss/channel/item/itunes:summary');
                 $video['description'] = $description_query ? strval($description_query[0]) : '';
                 $tags_query           = $xml->xpath('/rss/channel/item/itunes:keywords');
                 if (!empty($tags_query) && $tags_query)
                     {
                     $video['tags'] = $tags_query ? explode(',', strval(trim($tags_query[0]))) : null;
                     $video['tags'] = implode(', ', $video['tags']);
                     }
                 else
                     {
                     $video['tags'] = '';
                     }
                 $date_published_query = $xml->xpath('/rss/channel/item/pubDate');
                 $video['uploaded']    = $date_published_query ? ($date_published_query[0]) : null;
                 $thumbnails_query     = $xml->xpath('/rss/channel/item/media:thumbnail/@url');
                 $video['thumbnail']   = strval($thumbnails_query[0]);
                 $duration_query       = $xml->xpath('/rss/channel/item/media:group/media:content/@duration');
                 $video['duration']    = $duration_query ? intval($duration_query[0]) : null;
                 return $video;
             case 'myspace':
                 # Get XML data URL
                 $file_data            = "https://mediaservices.myspace.com/services/rss.ashx?type=video&videoID=" . $this->getLastNr($this->link);
                 # XML
                 $xml                  = new SimpleXMLElement(file_get_contents($file_data));
                 $video                = array();
                 # Get video title
                 $title_query          = $xml->xpath('/rss/channel/item/title');
                 $video['title']       = $title_query ? strval($title_query[0]) : '';
                 # Get video description
                 $description_query    = $xml->xpath('/rss/channel/item/media:content/media:description');
                 $video['description'] = $description_query ? strval($description_query[0]) : '';
                 # Get video tags
                 $tags_query           = $xml->xpath('/rss/channel/item/media:keywords');
                 $video['tags']        = $tags_query ? explode(',', strval(trim($tags_query[0]))) : null;
                 $video['tags']        = implode(', ', $video['tags']);
                 # Fet video duration
                 $duration_query       = $xml->xpath('/rss/channel/item/media:content/@duration');
                 $video['duration']    = $duration_query ? intval($duration_query[0]) : null;
                 # Get video publication date
                 $date_published_query = $xml->xpath('/rss/channel/item/pubDate');
                 $video['uploaded']    = $date_published_query ? ($date_published_query[0]) : null;
                 # Get video thumbnails
                 $thumbnails_query     = $xml->xpath('/rss/channel/item/media:thumbnail/@url');
                 $video['thumbnail']   = strval($thumbnails_query[0]);
                 return $video;
                 break;
             default:
                 $det                  = '';
                 $video                = array();
                 $video['description'] = '';
                 $video['title']       = '';
                 $video['thumbnail']   = '';
                 if (has_filter('EmbedDetails'))
                     {
                     $det = apply_filters('EmbedDetails', false);
                     }
                 if (nullval($det))
                     {
                     $site_html = @file_get_contents($this->link);
                     preg_match('/<meta property="og:image" content="(.*?)" \/>/', $site_html, $matches);
                     if (isset($matches[1]))
                         {
                         $video['thumbnail'] = $matches[1];
                         }
                     unset($matches);
                     preg_match('/<meta property="og:title" content="(.*?)" \/>/', $site_html, $matches);
                     if (isset($matches[1]))
                         {
                         $video['title'] = $matches[1];
                         }
                     unset($matches);
                     preg_match('/<meta property="og:description" content="(.*?)" \/>/', $site_html, $matches);
                     if (isset($matches[1]))
                         {
                         $video['description'] = $matches[1];
                         }
                     $det = $video;
                     /* End null check */
                     }
                 return $det;
                 break;
         }
         }
     function getDataFromUrl($url)
         {
         $ch      = curl_init();
         $timeout = 15;
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // add this one, it seems to spawn redirect 301 header
         curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13'); // spoof
         curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
         $data = curl_exec($ch);
         curl_close($ch);
         return $data;
         }
     }