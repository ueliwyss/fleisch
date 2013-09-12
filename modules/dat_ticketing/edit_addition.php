<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');


$action=table::decodeAction();

//$tab=new tab();

if(user::curusr_getPermission("ticket_edit")==user::PERMISSION_READ) {
	$mode=table::MODE_VIEW;
} elseif(user::curusr_getPermission("usr_group")==user::PERMISSION_FULL) {
	$mode=table::MODE_EDIT;
}

if($action['uid']==NO_UID) {
		$content=table::getForm('addition',$action['form'],false,table::MODE_NEW,NO_UID,new tabItem('Pseudo'),$action['foreign_uid']);
}




//div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>