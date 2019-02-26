/*!
 * phpVibe v5
 *
 * Copyright Media Vibe Solutions
 * http://www.phpRevolution.com
 * phpVibe IS NOT FREE SOFTWARE
 * If you have downloaded this CMS from a website other
 * than www.phpvibe.com or www.phpRevolution.com or if you have received
 * this CMS from someone who is not a representative of phpVibe, you are involved in an illegal activity.
 * The phpVibe team takes actions against all unlincensed websites using Google, local authorities and 3rd party agencies.
 * Designed and built exclusively for sale @ phpVibe.com & phpRevolution.com.
 */
 
 //Initialize
jQuery(function($){
/*Detect touch device*/
	var tryTouch;
	try {
	document.createEvent("TouchEvent");
	tryTouch = 1;
	} catch (e) {
		tryTouch = 0;
	}
/*Browser detection*/
	var $is_mobile = false;
    var $is_tablet = false;
	var $is_pc = false;

if ($( window ).width() < 500) {
$is_mobile = true;
} else if ($( window ).width() < 900) {
$is_tablet = true;
} else {
$is_pc = true;
}
	
		
	$('.pv_tip, .tipN, .tipS, .tipW, .tipE').tooltip();
	$('#share-embed-code, #share-embed-code-small, #share-embed-code-large, #share-this-link').tooltip({'trigger':'focus'});
	$('.pv_pop').popover();
	$('.dropdown-toggle').dropdown();
	
	 var vh = $("#video-content").height() - 34;
	if($is_mobile) { 	
	  $('.scroll-items').slimScroll({height:180});
	  $('.items').slimScroll({height:180});
	   
	  } else {
	  $('.scroll-items').slimScroll({height:340});
	  $('.items').slimScroll({height:vh});
	  }
var sidebarsh = screen.height - 67;	
$('.sidescroll').slimScroll({height:sidebarsh, position: 'left', size: '1px', railOpacity: '0.01',color: '#fff',railColor: '#fff'});

	/* Ajax forms */
	 $('.ajax-form').ajaxForm({
            target: '.ajax-form-result',
			success: function(data) {
            $('.ajax-form').hide();
        }
        });
	$('.ajax-form-video').ajaxForm({
            target: '.ajax-form-result',
			success: function(data) {         
        }
        });
	/* Infinite scroll */	
	var $container = $('.loop-content:last');	
		if(jQuery('#page_nav').html()){
		
      $container.infinitescroll({
        navSelector  : '#page_nav',    // selector for the paged navigation 
        nextSelector : '#page_nav a',  // selector for the NEXT link (to page 2)
        itemSelector : '.video', 		// selector for all items you'll retrieve
		bufferPx: 60,
        loading: {
		    msgText: 'Loading next',
            finishedMsg: 'The End.',
            img: site_url + 'tpl/main/images/load.gif'
          }
        },
        // call Isotope as a callback
     function ( newElements ) {	
	 
  var $newElems = jQuery( newElements ).hide(); // hide to begin with
  // ensure that images load before adding to masonry layout
  $newElems.imagesLoaded(function(){
    $newElems.fadeIn(); // fade in when ready	
	 });
	 
	
	  });
	    };
	 $('.video-related').imagesLoaded(function(){
    $('.video-related').removeClass('hide'); 
	$('.relatedLoader').hide();	
	 });
	 $('.video-player-sidebar').imagesLoaded(function(){
    $('.video-player-sidebar').removeClass('hide'); 
	$('.vpLoader').hide();	
	 });
	 if(jQuery('#home-content').html()){
	 setTimeout(function(){
    $('.loop-content').removeClass('hide'); 
	$('.homeLoader').hide();	
	 }, 2100);
	 }
	
	
	/* END */
	
});
$( window ).resize(function() {

if ($( window ).width() < 1530) { 
$( "#sidebar" ).addClass( "hide" );
}	
});
$(document).ready(function(){
$('img').error(function(){
        $(this).attr('src', site_url + 'storage/uploads/noimage.png');
});	
$.getJSON( site_url + "api/noty/", function( data ) {
if ( data ) {	
//alert(data);
}
}).error(function(jqXHR, textStatus, errorThrown) {
        console.log("error " + textStatus);
        console.log("incoming Text " + jqXHR.responseText);
  });		
$(".owl-carousel").owlCarousel({items : 6,navigation : true, navigationText: [
      "<i class='icon-chevron-left icon-white'></i>",
      "<i class='icon-chevron-right icon-white'></i>"
      ],  itemsCustom : [
        [0, 1],
        [400, 2],
        [570, 3],
        [700, 4],
		[1000, 5],
        [1200, 6]
      ]});	

if ($( window ).width() < 1530) { 
$( "#sidebar" ).addClass( "hide" );
}
	
//Emoticons
$('.message .body').emotions();
//Fill the screen
  $(".fullit").click(function(){
 	$("#video-content").toggleClass('gofullscreen');
	$("#ListRelated").toggleClass('hide');
	$("#flT").toggleClass('icon-resize-full').toggleClass('icon-resize-small');
  });
  
//Kill ad
   $(".close-ad").click(function(){
 	$(this).closest(".adx").hide();
  });
 

  //Add to
   $("#addtolist").click(function(){
    $("#bookit").slideToggle();
  });
   $("#embedit").click(function(){
    $(".video-share").toggleClass('hide');
  });
  //Sidebar 
  $("#show-sidebar").click(function(){          
          $("#sidebar").toggleClass('hide');
		  $("#wrapper").toggleClass('haside');
		  if ( !$("#sidebar").hasClass("hide") ) {
		  $("a#show-sidebar > i").removeClass('icon-bars').addClass('icon-close');
		  }
		  if ($("#sidebar").hasClass("hide") ) {
		  $("a#show-sidebar > i").addClass('icon-bars').removeClass('icon-close');
		  }
		  if ( !$("#sidebar").hasClass("hide") ) {
var sideSpace =  parseInt($("#wrapper").offset().left);
if(sideSpace < 240) {
$("#wrapper").css({"margin-left": "240px", "margin-right": "auto"});
}
} else {
var sideSpace = $("#wrapper").offset().left;
if(sideSpace == 240) {
$("#wrapper").css({"margin-left": "auto", "margin-right": "auto"});
}	
}	
   	
  });
  
 if ( !$("#sidebar").hasClass("hide") ) {
var sideSpace = $("#wrapper").offset().left;
if(sideSpace < 240) {
$("#wrapper").css({"margin-left": "240px", "margin-right": "auto"});
}
}
 if ( !$("#sidebar").hasClass("hide") ) {
		  $("a#show-sidebar > i").removeClass('icon-bars').addClass('icon-close');
		  }
  //End sidebar
    //VideoPlayer Container
  var vpWidth = $('.video-player').width();
  var vpHeight = Math.round((vpWidth/16)*9);
  $(".video-player").css("min-height",vpHeight);
  //End sidebar
      $(".cgcover").click(function(){
    $(".upcover").toggleClass('hide')
  });
 
$('.table-checks .check-all').click(function(){
		var parentTable = $(this).parents('table');										   
		var ch = parentTable.find('tbody input[type=checkbox]');										 
		if($(this).is(':checked')) {
		
			//check all rows in table
			ch.each(function(){ 
				$(this).attr('checked',true);
				$(this).parent().addClass('checked');	//used for the custom checkbox style
				$(this).parents('tr').addClass('selected');
			});
						
			//check both table header and footer
			parentTable.find('.check-all').each(function(){ $(this).attr('checked',true); });
		
		} else {
			
			//uncheck all rows in table
			ch.each(function(){ 
				$(this).attr('checked',false); 
				$(this).parent().removeClass('checked');	//used for the custom checkbox style
				$(this).parents('tr').removeClass('selected');
			});	
			
			//uncheck both table header and footer
			parentTable.find('.check-all').each(function(){ $(this).attr('checked',false); });
		}
	});
	
	  jQuery(".backtotop").addClass("hidden");
    jQuery(window).scroll(function () {
        if (jQuery(this).scrollTop() === 0) {
            jQuery(".backtotop").addClass("hidden")
        } else {
            jQuery(".backtotop").removeClass("hidden")
        }
    });

    jQuery('.backtotop').click(function () {
        jQuery('body,html').animate({
            scrollTop:0
        }, 1200);
        return false;
    });
	  $('a.media-href').fluidbox();
if ($( window ).width() > 500) { 
	  var oh = $("#video-content").height() - 34;
	 $('.items').parent().replaceWith($('.items'));	 
	 $('.items').slimScroll({height:oh});
	  }	  
	  
	
});

function iLikeThis(vid){
    $.post(
            site_url + 'lib/ajax/like.php', { 
                video_id:   vid,
				type : 1
            },
            
            function(data){
                $('#i-like-it').addClass('done-like');
						var a = JSON.parse(data);	
  $.gritter.add({            
            title:  a.title,          
            text: '<p>' + a.text + '</p>',           
            sticky: false,
			 class_name: 'gritter-light',          
            time: 15000
        });
            }); 
}
function DOtrackview(vid) {
$.post(site_url + 'lib/ajax/track.php', { 
                video_id:   vid
            },            
            function(data){
			//console.log(data);	
			}
); 			
}
function Padd(vid,pid){

    $.post(
            site_url + 'lib/ajax/addto.php', { 
                video_id:   vid,
				playlist : pid
            },
            
            function(data){
            var a = JSON.parse(data);	
  $.gritter.add({            
            title:  a.title,          
            text: '<p>' + a.text + '</p>',           
            sticky: false,
			 class_name: 'gritter-light',          
            time: 15000
        });
		 if(jQuery('li#PAdd-'+pid).html()){
			 $('li#PAdd-' + pid).remove();
		 }
		 if(jQuery('#video-' + vid).html()){
			$('#video-' + vid +' a.laterit').remove();
		 }
            }); 
}
function ReplyCom(cid){
$('li#' + cid).toggleClass('hide');	
}
function RemoveLike(vid){
    $.post(
            site_url + 'lib/ajax/dislike.php', { 
                video_id:   vid,
				type : 1
            },
            
            function(data){
                $('#i-like-it').removeClass('isLiked');
						var a = JSON.parse(data);	
  $.gritter.add({            
            title:  a.title,          
            text: '<p>' + a.text + '</p>',           
            sticky: false,
			 class_name: 'gritter-light',          
            time: 15000
        });
            }); 
}
function iHateThis(vid){
    $.post(
            site_url + 'lib/ajax/like.php', { 
                video_id:   vid,
				type : 2
            },
            
            function(data){
                $('#i-dislike-it').addClass('done-dislike');
						var a = JSON.parse(data);	
  $.gritter.add({            
            title:  a.title,          
            text: '<p>' + a.text + '</p>',           
            sticky: false,			        
            time: 15000
        });
            }); 
}
function Subscribe(user,type){
    $.post(
            site_url + 'lib/ajax/subscribe.php', { 
                the_user:   user,
				the_type : type
            },
            
            function(data){
			var a = JSON.parse(data);	
  $.gritter.add({            
            title:  a.title,          
            text: '<p>' + a.text + '</p>',           
            sticky: false,
			 class_name: 'gritter-light',          
            time: 15000
        });				
            }); 
}

function addEMComment(oid,toid){
    if($('textarea#addEmComment_'+toid).val()){
        //mark comment box as inactive
          // $('#emAddButton_'+oid).attr('disabled','true');

      
        $.post(
            site_url + 'lib/ajax/addComment.php', { 
                comment:      encodeURIComponent($('textarea#addEmComment_'+toid).val()),
                object_id:    oid,
				reply : toid
               
            },
            
            function(data){
			   $('#emContent_'+oid+ '-'+toid+' li:first').after('<li id="comment-'+data.id+'" class="left"><img class="avatar" src="'+data.image+'" /><div class="message"><span class="arrow"> </span><a class="name" href="'+data.url+'">'+data.name+'</a> <span class="date-time"> '+data.date+' </span> <div class="body">'+data.text+'</div> </div></li>');
                //$('#comment_'+data.id).slideDown();
                
               $('textarea#addEmComment_'+toid).val('');
			   $('.body').emotions();
			    $('html, body').animate({scrollTop:$('#emContent_'+oid+ '-'+toid).offset().top - 1}, 'slow');
            }, "json");            
            
    }else{
        $('#addEmComment_'+toid).focus();
    }
	
 //$('#emAddButton_'+oid).attr('disabled','false');
 
    return false;
}

function iLikeThisComment(cid){
    $.post(
            site_url + 'lib/ajax/likeComment.php', { 
                comment_id:   cid
            },
            
            function(data){
			    
			    $('#iLikeThis_'+cid+'> a').remove();
                $('#iLikeThis_'+cid).prepend(data.text+'! &nbsp;');
            }, "json"); 
}
function processVid(file){
$('#vfile').val(file);
$('#Subtn').prop('disabled', false).html('Save').addClass("btn-success");
}
 /*! jQuery Cookie Plugin v1.3 | https://github.com/carhartl/jquery-cookie */
(function(f,b,g){var a=/\+/g;function e(h){return h}function c(h){return decodeURIComponent(h.replace(a," "))}var d=f.cookie=function(p,o,u){if(o!==g){u=f.extend({},d.defaults,u);if(o===null){u.expires=-1}if(typeof u.expires==="number"){var q=u.expires,s=u.expires=new Date();s.setDate(s.getDate()+q)}o=d.json?JSON.stringify(o):String(o);return(b.cookie=[encodeURIComponent(p),"=",d.raw?o:encodeURIComponent(o),u.expires?"; expires="+u.expires.toUTCString():"",u.path?"; path="+u.path:"",u.domain?"; domain="+u.domain:"",u.secure?"; secure":""].join(""))}var h=d.raw?e:c;var r=b.cookie.split("; ");for(var n=0,k=r.length;n<k;n++){var m=r[n].split("=");if(h(m.shift())===p){var j=h(m.join("="));return d.json?JSON.parse(j):j}}return null};d.defaults={};f.removeCookie=function(i,h){if(f.cookie(i)!==null){f.cookie(i,null,h);return true}return false}})(jQuery,document);

function DOtrackview(vid) {
$.post(site_url + 'lib/ajax/track.php', { 
                video_id:   vid
            },            
            function(data){
			//console.log(data);	
			}
); 			
}