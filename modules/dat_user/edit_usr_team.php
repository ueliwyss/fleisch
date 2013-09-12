<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');

$view=div::http_getGP('view')==''?"list":div::http_getGP('view');
$action=table::decodeAction();
$tableName='usr_team';

$tab=new tab();

if(user::curusr_getPermission($tableName)==user::PERMISSION_READ) {
	$mode=table::MODE_VIEW;
} elseif(user::curusr_getPermission($tableName)==user::PERMISSION_FULL) {
	$mode=table::MODE_EDIT;
}

if($view=='list') {
	if(user::curusr_getPermission($tableName)>=user::PERMISSION_READ) {
		$tab->addElement($tab1=new tabItem('Support-Teams'));
		$tab1->content=table::getList($tableName,'listAll',false,array(),$tab1);
	}

	if(user::curusr_getPermission($tableName)>=user::PERMISSION_FULL) {
		$tab->addElement($tab2=new tabItem('Neues Support-Team'));
		$tab2->content=table::getForm($tableName,table::MODE_NEW,false,table::MODE_NEW);
	}


} else {
	if(user::curusr_getPermission($tableName)>=user::PERMISSION_READ) {
		$tab->addElement(new tabItem('Allgemein',table::getForm($tableName,table::MODE_EDIT,false,$mode,$action['uid']),true));
	}


	if($action['mode']!=table::MODE_NEW) {
		if(user::curusr_getPermission("usr_team_perms")==user::PERMISSION_READ) {
			$tab->addElement($rightTab=new tabItem('Berechtigungen'));
			$rightTab->content=table::getList($tableName,'directRights_read',true,array("uid"=>$action['uid']),$rightTab);
		}

		if(user::curusr_getPermission("usr_team_perms")>=user::PERMISSION_FULL) {
			$tab->addElement($rightTab=new tabItem('Berechtigungen'));
			$rightTab->content=table::getList($tableName,'directRights_full',true,array("uid"=>$action['uid']),$rightTab);
			$regNewPerm=new formButton('Berechtigung hinzufügen',"top.editElement('".table::MODE_NEW."','usr_teamhasperm','".NO_UID."','".$action['uid']."',true)",ICON_DIR.'ilist_actions_add.gif');
			div::htm_mergeSiteContent($rightTab->content,$regNewPerm->wrapContent());
		}
	}
}

div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>