<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');

$view=div::http_getGP('view')==''?"list":div::http_getGP('view');
$action=table::decodeAction();
$tableName='usr_user';

$tab=new tab();

if(user::curusr_getPermission("usr_group")==user::PERMISSION_READ) {
	$mode=table::MODE_VIEW;
} elseif(user::curusr_getPermission("usr_group")==user::PERMISSION_FULL) {
	$mode=table::MODE_EDIT;
}

if($view=='list') {


	if(user::curusr_getPermission("usr_group")>=user::PERMISSION_READ) {
		$tab->addElement($tab1=new tabItem('Arbeitsgruppen'));
		$tab1->content=table::getList('usr_group','listAll',false,array(),$tab1);
	}

	if(user::curusr_getPermission("usr_group")>=user::PERMISSION_FULL) {
		$tab2=new tabItem('Neue Arbeitsgruppegruppe');
		$tab->addElement($tab2);
		$tab2->content=table::getForm('usr_group',table::MODE_NEW,false,table::MODE_NEW);
	}


} else {

	if(user::curusr_getPermission("usr_group")>=user::PERMISSION_READ) {
		$tab->addElement(new tabItem('Allgemein',table::getForm('usr_group',table::MODE_EDIT,false,$mode,$action['uid']),true));
	}


	if($action['mode']!=table::MODE_NEW) {
		if(user::curusr_getPermission("usr_group_perms")==user::PERMISSION_READ) {
			$tab->addElement($rightTab=new tabItem('Berechtigungen'));
			$rightTab->content=table::getList('usr_group','directRights_read',true,array("uid"=>$action['uid']),$rightTab);
		}

		if(user::curusr_getPermission("usr_group_perms")>=user::PERMISSION_FULL) {
			$tab->addElement($rightTab=new tabItem('Berechtigungen'));
			$rightTab->content=table::getList('usr_group','directRights_full',true,array("uid"=>$action['uid']),$rightTab);
			$regNewPerm=new formButton('Berechtigung hinzufügen',"top.editElement('".table::MODE_NEW."','usr_grouphasperm','".NO_UID."','".$action['uid']."',true)",ROOT_DIR.'images/ilist_actions_add.gif');
			div::htm_mergeSiteContent($rightTab->content,$regNewPerm->wrapContent());
		}
	}
}

div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>