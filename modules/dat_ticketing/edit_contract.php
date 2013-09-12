<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');

$view=div::http_getGP('view')==''?"list":div::http_getGP('view');
$action=table::decodeAction();
$tableName='contract';

$tab=new tab();

if(user::curusr_getPermission($tableName)==user::PERMISSION_READ) {
	$mode=table::MODE_VIEW;
} elseif(user::curusr_getPermission($tableName)==user::PERMISSION_FULL) {
	$mode=table::MODE_EDIT;
}

if($view=='list') {
	if(user::curusr_getPermission($tableName)>=user::PERMISSION_READ) {
		$tab->addElement($tab1=new tabItem('Verträge'));
		$tab1->content=table::getList($tableName,'listAll',false,array(),$tab1);
	}

	if(user::curusr_getPermission($tableName)>=user::PERMISSION_FULL) {
		$tab->addElement($tab2=new tabItem('Neuer Vertrag'));
		$tab2->content=table::getForm($tableName,'new',false,table::MODE_NEW,NO_UID,$tab2);
	}

} else {
	if(user::curusr_getPermission($tableName)>=user::PERMISSION_READ) {
		$tab->addElement($tab2=new tabItem('Allgemein'));
		$tab2->content=table::getForm($tableName,'edit',false,$mode,$action['uid'],$tab2);
	}

}




div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>