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

if($action['form']=='ofTeam') {
	if(user::curusr_getPermission("usr_user")>=user::PERMISSION_READ) {
		$content=table::getList('usr_user','ofTeam',false,array());
		div::htm_echoContent($content);
	}
} else {
	if($view=='list') {

		if(user::curusr_getPermission("usr_user")>=user::PERMISSION_READ) {
			$tab->addElement($tab1=new tabItem('Benutzer'));
			$tab1->content=table::getList('usr_user','listAll',false,array(),$tab1);
		}

		if(user::curusr_getPermission("usr_user")>=user::PERMISSION_FULL) {
			$tab->addElement($tab2=new tabItem('Neuer Benutzer'));
			$tab2->content=table::getForm('usr_user',table::MODE_NEW,false,table::MODE_NEW,NO_UID,$tab2);
		}
	} else {

		if(user::curusr_getPermission("usr_user")>=user::PERMISSION_READ) {
			$tab->addElement($tab3=new tabItem('Allgemein'));
			$tab3->content=table::getForm('usr_user',table::MODE_EDIT,false,$mode,$action['uid'],$tab3);
		}

		if(user::curusr_getPermission("usr_user")>=user::PERMISSION_READ) {
			$tab->addElement($tab4=new tabItem('Details'));
			$tab4->content=table::getForm('usr_user','edit_detail',false,$mode,$action['uid'],$tab4);
		}


		if($action['mode']!=table::MODE_NEW) {
			if(user::curusr_getPermission("usr_user_perms")==user::PERMISSION_READ) {
				$tab->addElement($rightTab=new tabItem('Berechtigungen'));
				$rightTab->content=table::getList('usr_user','directRights_read',true,array("uid"=>$action['uid']),$rightTab);
			}
			if(user::curusr_getPermission("usr_user_perms")>=user::PERMISSION_FULL) {
				$tab->addElement($rightTab=new tabItem('Berechtigungen'));
				$rightTab->content=table::getList('usr_user','directRights_full',true,array("uid"=>$action['uid']),$rightTab);
				$regNewPerm=new formButton('Berechtigung hinzufügen',"top.editElement('".table::MODE_NEW."','usr_userhasperm','".NO_UID."','".$action['uid']."',true)",ROOT_DIR.'images/ilist_actions_add.gif');
				div::htm_mergeSiteContent($rightTab->content,$regNewPerm->wrapContent());
			}


			if(user::curusr_getPermission("usr_user_groups")==user::PERMISSION_READ) {
				$tab->addElement($groupTab=new tabItem('Arbeitsgruppen'));
				$groupTab->content=table::getList('usr_user','groups_read',true,array("uid"=>$action['uid']),$groupTab);
			}
			if(user::curusr_getPermission("usr_user_groups")>=user::PERMISSION_FULL) {
				$tab->addElement($groupTab=new tabItem('Arbeitsgruppen'));
				$groupTab->content=table::getList('usr_user','groups_full',true,array("uid"=>$action['uid']),$groupTab);
				$regNewGroup=new formButton('Gruppe hinzufügen',"top.editElement('".table::MODE_NEW."','usr_useringroup','".NO_UID."','".$action['uid']."',true)",ROOT_DIR.'images/ilist_actions_add.gif');
				div::htm_mergeSiteContent($groupTab->content,$regNewGroup->wrapContent());
			}

			if(user::curusr_getPermission("usr_user_groups")>=user::PERMISSION_READ) {
				$tab->addElement($sessionTab=new tabItem('Sessions'));
				$sessionTab->content=table::getList('usr_session','userSessions',true,array("uid"=>$action['uid']),$sessionTab);
			}
		}

		if(user::curusr_getPermission("usr_user")>=user::PERMISSION_FULL) {
			$tab->addElement($tab5=new tabItem('Passwort zurücksetzen'));
			$tab5->content=table::getForm('usr_user','resetPassword',false,table::MODE_EDIT,$action['uid'],$tab5);
		}

	}
	div::htm_mergeSiteContent($content,$tab->wrapContent());
	div::htm_echoContent($content);
}




?>