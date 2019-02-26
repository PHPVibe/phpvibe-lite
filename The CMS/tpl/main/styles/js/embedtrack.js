function DOtrackview(vid) {
$.post(site_url + 'lib/ajax/track.php', { 
                video_id:   vid
            },            
            function(data){
			//console.log(data);	
			}
); 			
}