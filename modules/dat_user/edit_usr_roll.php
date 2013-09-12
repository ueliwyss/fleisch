<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');

$view=div::http_getGP('view')==''?"list":div::http_getGP('view');
$action=table::decodeAction();
$tableName='usr_user';

$tab=new tab();

if(user::curusr_getPermission("usr_roll")==user::PERMISSION_READ) {
	$mode=table::MODE_VIEW;
} elseif(user::curusr_getPermission("usr_roll")==user::PERMISSION_FULL) {
	$mode=table::MODE_EDIT;
}

if($view=='list') {
	if(user::curusr_getPermission("usr_roll")>=user::PERMISSION_READ) {
		$tab->addElement($tab1=new tabItem('Rollen'));
		$tab1->content=table::getList('usr_roll','listAll',false,array(),$tab1);
	}
} else {
	if(user::curusr_getPermission("usr_roll")>=user::PERMISSION_READ) {
		$tab->addElement(new tabItem('Allgemein',table::getForm('usr_roll',table::MODE_VIEW,false,table::MODE_VIEW,$action['uid']),true));
	}

	if($action['mode']!=table::MODE_NEW) {
		if(user::curusr_getPermission("usr_roll_perms")==user::PERMISSION_READ) {
			$tab->addElement($rightTab=new tabItem('Berechtigungen'));
			$rightTab->content=table::getList('usr_roll','directRights_read',true,array("uid"=>$action['uid']),$rightTab);
		}


		if(user::curusr_getPermission("usr_roll_perms")>=user::PERMISSION_FULL) {
			$tab->addElement($rightTab=new tabItem('Berechtigungen'));
			$rightTab->content=table::getList('usr_roll','directRights_full',true,array("uid"=>$action['uid']),$rightTab);
			$regNewPerm=new formButton('Berechtigung hinzufügen',"top.editElement('".table::MODE_NEW."','usr_rollhasperm','".NO_UID."','".$action['uid']."',true)",ROOT_DIR.'images/ilist_actions_add.gif');
			div::htm_mergeSiteContent($rightTab->content,$regNewPerm->wrapContent());
		}

	}
}




div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>