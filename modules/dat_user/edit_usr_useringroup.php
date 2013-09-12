<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');


$action=table::decodeAction();

//$tab=new tab();

if($action['uid']==NO_UID) {
	if(user::curusr_getPermission("usr_user_groups")>=user::PERMISSION_FULL) {
		$content=table::getList('usr_group','addToUser');
	}
} else {
	/*$content=table::getForm('usr_userhasperm','default',false,$action['mode'],$action['uid'],new tabItem('Pseudo'),$action['foreign_uid']);*/
	/*$tab->addElement(new tabItem('Allgemein',table::getForm('usr_user',$userForm,false,$mode,$uid),true));


	if($uid!="NEW") {
		$rightTab=new tabItem('Rechte',table::getList('usr_user','directRights',true,array("uid"=>$uid)));
		$regNew=new formButton('Berechtigung hinzufügen',"top.editElement('usr_userhasperm','NEW','',true)",'images/ilist_actions_add.gif');
		div::htm_mergeSiteContent($rightTab->content,$regNew->wrapContent());
		$tab->addElement($rightTab);
	}*/
}




//div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>