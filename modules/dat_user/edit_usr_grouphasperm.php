<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');


$action=table::decodeAction();

//$tab=new tab();

if(user::curusr_getPermission("usr_group")==user::PERMISSION_READ) {
	$mode=table::MODE_VIEW;
} elseif(user::curusr_getPermission("usr_group")==user::PERMISSION_FULL) {
	$mode=table::MODE_EDIT;
}


if($action['uid']==NO_UID) {
	if(user::curusr_getPermission("usr_group_perms")>=user::PERMISSION_FULL) {
		$content=table::getList('usr_resource','addToGroup');
	}

} else {
	if(user::curusr_getPermission("usr_group_perms")>=user::PERMISSION_FULL) {
		$content=table::getForm('usr_grouphasperm','default',false,$action['mode'],$action['uid'],new tabItem('Pseudo'),$action['foreign_uid']);
	}
}




//div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>