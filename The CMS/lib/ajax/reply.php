<?php  error_reporting(E_ALL);
require_once('../../load.php');
if (is_user( ) && isset($_REQUEST['message'])) {
   
    $info_user = user_id( );
	
    if($_REQUEST['conversation'] && (intval($_REQUEST['conversation']) > 0)) {
	$thecom =  html_entity_decode(urldecode($_REQUEST['message']));
	$com_body = toDb(MakeEmoji($thecom));
	$reply_id = intval($_REQUEST['conversation']);
	//No tricks
	$_conv = $db->get_row("select * from ".DB_PREFIX."conversation where c_id = '".$reply_id."' and ((user_one='".$info_user."') OR (user_two='".$info_user."'))");
    if($_conv) {
    $it = "INSERT INTO ".DB_PREFIX."con_msgs (`conv`, `at_time`, `by_user`, `reply`) VALUES ('".$reply_id."', now(), '".$info_user."', '".$com_body."')";
   	$addit = $db->query($it);
	// /finished insert
        
        
        
         //send reply to browser
        //header('Content-type: application/x-json');
		
        echo json_encode(array('ok'=> 1));
	} else {
 echo json_encode(array('ok'    => 0));
}
    
    }
} else {
 echo json_encode(array('ok'    => 0));
}
?>