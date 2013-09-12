<?
include('../../init.php');

include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');

$view=div::http_getGP('view')==''?"list":div::http_getGP('view');
$action=table::decodeAction();

$tab=new tab();

if(user::curusr_getPermission("usr_user")==user::PERMISSION_READ) {
	$mode=table::MODE_VIEW;
} elseif(user::curusr_getPermission("usr_user")==user::PERMISSION_FULL) {
	$mode=table::MODE_EDIT;
}

if($view=='list') {
	if(user::curusr_getPermission("usr_sync")>=user::PERMISSION_READ) {
		$tab->addElement($tab1=new tabItem('Synchroisations-Tasks'));
		$tab1->content=table::getList('usr_sync','default',false,array(),$tab1);
	}

	if(user::curusr_getPermission("usr_sync")>=user::PERMISSION_FULL) {
		$tab->addElement($tab2=new tabItem('Neues Synchronisations-Task'));
		$tab2->content=table::getForm('usr_sync',table::MODE_NEW,false,table::MODE_NEW);
	}
} else {
	if(user::curusr_getPermission("usr_sync")>=user::PERMISSION_READ) {
		$tab->addElement(new tabItem('Allgemein',table::getForm('usr_sync',table::MODE_EDIT,false,$mode,$action['uid']),true));
	}

	if(user::curusr_getPermission("usr_sync")>=user::PERMISSION_FULL) {
		$tab->addElement($tab5=new tabItem('Passwort zurücksetzen'));
		$tab5->content=table::getForm('usr_sync','resetPassword',false,table::MODE_EDIT,$action['uid'],$tab5);
	}
}




div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>