<?
include('../../init.php');

include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');

$tableName='usr_user';

$tab=new tab();
if(user::curusr_getPermission("usr_session")>=user::PERMISSION_READ) {
	$tab->addElement($tab1=new tabItem('Sessions'));
	$tab1->content=table::getList('usr_session','default',false,array(),$tab1);
}

div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>