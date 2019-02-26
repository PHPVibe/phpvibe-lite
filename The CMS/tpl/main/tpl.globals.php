<?php /* PHPVibe v5 www.phpvibe.com */
function extra_js() {
return apply_filter( 'filter_extrajs', false );
}
function extra_css() {
return apply_filter( 'filter_extracss', false );
}
function wrapper_class(){
$cls = "container";	
if(is_com('conversation')) {
/* Fluid container for Messenger
   */	
$cls = "container-fluid";	
}	
return apply_filters("wrapper-class",$cls );	
}
/* Individual functions */
include_once(TPL.'/tpl.header.php');
include_once(TPL.'/tpl.footer.php');
?>