<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');

$view=div::http_getGP('view')==''?"list":div::http_getGP('view');
$action=table::decodeAction();
$tableName='ticket';

//Ticketstatus beim der Ticketbearbeitung abrufen
$status=($action['uid'] && $action['uid']!=NO_UID)?ticket::getStatus($action['uid']):0;

if(user::curusr_getPermission("usr_user")==user::PERMISSION_READ) {
	$mode=table::MODE_VIEW;
} elseif(user::curusr_getPermission("usr_user")==user::PERMISSION_FULL) {
	$mode=table::MODE_EDIT;
}


if($action['form']=='complete') {
	//Ticket-Kompletierungsformular anzeigen.
	$content=table::getForm($tableName,$action['form'],false,table::MODE_EDIT,$action['uid']);

	if(ticket::getStatus($action['uid'])!=ticket::STATUS_INCOMPLETE) {
		$content['JS'].="
setTimeout('form_0.onsubmit();',100);
";
	}
	div::htm_echoContent($content);
} elseif($action['foreign_uid']!=NO_UID && $action['foreign_uid']) {
	//Teams des nächsten Support-Levels auflisten
	$supportLevel=(ticket::getSupportLevel($action['foreign_uid'])+1);
	$content=table::getList('usr_team',$supportLevel.'-Level-Teams',false,array());

	div::htm_echoContent($content);
} else {
	$tab=new tab();

	if($view=='list') {
		//Tickets auflisten
		if(user::curusr_getPermission("ticket")>=user::PERMISSION_FULL) {
			//Ticket-Pool anzeigen
			$tab->addElement($tab1=new tabItem('Ticket-Pool'));
			if(user::curusr_getTeamSupportLevel()==ticket::SUPPORTLEVEL_1) {
				//Ticket-Pool fürs 1. Support-Level anzeigen.
				$tab1->content=table::getList($tableName,'pool_1-Level',false,array(),$tab1);
			} else {
				//Ticket-Pool fürs 2. und 3. Support-Level anzeigen.
				$tab1->content=table::getList($tableName,'pool_2-3-Level',false,array(),$tab1);
			}

		}

		if(user::curusr_getPermission("ticket_own")>=user::PERMISSION_READ) {
			//eigene Tickets auflisten.
			$tab->addElement($tab1=new tabItem('Eigene Tickets'));
			if(user::curusr_getRollId()==user::getRollId('Kunde')) {
				//Listen der eigenen Tickets eines Kunden anzeigen (owner_customer_incomplete,ownew_customer_open,owner_customer_closed)
				$tab1->content=table::getList($tableName,'owner_customer_incomplete',true,array(),$tab1);
				div::htm_mergeSiteContent($tab1->content,table::getList($tableName,'owner_customer_open',true,array(),$tab1));
				div::htm_mergeSiteContent($tab1->content,table::getList($tableName,'owner_customer_closed',true,array(),$tab1));
			} else {
				//Listen der eingen Tickets eines Agenten anzeigen (owner_agent_open,owner_agent_closed)
				$tab1->content=table::getList($tableName,'owner_agent_open',true,array(),$tab1);
				div::htm_mergeSiteContent($tab1->content,table::getList($tableName,'owner_agent_closed',true,array(),$tab1));
			}
		}


		if(user::curusr_getPermission("ticket_ofTeam")>=user::PERMISSION_READ) {
			//Liste 'Team Tickets' anzeigen.
			$tab->addElement($tab7=new tabItem('Team Tickets'));
			$tab7->content=table::getList($tableName,'team',false,array(),$tab7);
		}

		if(user::curusr_getPermission("ticket_new")>=user::PERMISSION_READ) {
			//Ticketerfassungsformular anzeigen.
			if(user::curusr_getRollId()==user::getRollId('Kunde')) {
				//Ticketerfassungsformular für den Kunden anzeigen.
				$tab->addElement($tab2=new tabItem('Neues Ticket'));
				$tab2->content=table::getForm($tableName,'new_customer',false,table::MODE_NEW,NO_UID,$tab2);
			} else {
				//Ticketerfassungsformular für den Agenten anzeigen.
				$tab->addElement($tab2=new tabItem('Neues Ticket'));
				$tab2->content=table::getForm($tableName,'new_agent',false,table::MODE_NEW,NO_UID,$tab2);
			}
		}

	} else {
		//Ticket bearbeiten (Eigenschaften anzeigen)
		if(user::curusr_getPermission("ticket")>=user::PERMISSION_READ) {
			if(ticket::getStatus($action['uid'])==ticket::STATUS_INCOMPLETE && user::curusr_getRollId()==user::getRollId('Kunde')) {
				$ticketMode=table::MODE_EDIT;
			} else {
				$ticketMode=table::MODE_VIEW;
			}
			if(user::curusr_getRollId()==user::getRollId('Kunde')) {
				//Ticketbearbeitungsformular anzeigen
				$tab->addElement($tab3=new tabItem('Allgemein'));
				$tab3->content=table::getForm($tableName,table::MODE_EDIT."_customer",false,$ticketMode,$action['uid'],$tab3);
			} else {
				//Ticketbearbeitungsformular für den Agenten anzeigen.
				$tab->addElement($tab3=new tabItem('Allgemein'));
				$tab3->content=table::getForm($tableName,table::MODE_EDIT."_agent",false,$ticketMode,$action['uid'],$tab3);
			}

		}

		if(user::curusr_getPermission("ticket")>=user::PERMISSION_FULL) {
			$supportLevelButton=ticket::getNextSupportLevelButton($action['uid']);
			if($supportLevelButton && !ticket::isClosed($action['uid'])) {
				//'Nächstes Support-Level'-Button anzeigen
				div::htm_mergeSiteContent($tab3->content,$supportLevelButton->wrapContent());
			}
		}

		if(user::curusr_getPermission("ticket")>=user::PERMISSION_FULL) {
			if(!ticket::isClosed($action['uid'])) {
				//'Zuweisen innerhalb Team'-Button anzeigen
				$assignToUserButton=ticket::getAssignToTeamUserButton($action['uid']);
				div::htm_mergeSiteContent($tab3->content,$assignToUserButton->wrapContent());
			}
		}

		if(user::curusr_getPermission("ticket")>=user::PERMISSION_FULL) {
			if(!ticket::isClosed($action['uid']) && $status!=ticket::STATUS_INCOMPLETE) {
				//'Bearbeiten'-Button anzeigen
				$additionButton=ticket::getAdditionButton($action['uid']);
				div::htm_mergeSiteContent($tab3->content,$additionButton->wrapContent());
			}
		}



		if(user::curusr_getPermission("ticket")>=user::PERMISSION_FULL && user::curusr_getId()==ticket::getOwnerId($action['uid'])) {
			if(!ticket::isClosed($action['uid']) && user::curusr_getId()==ticket::getOwnerId($action['uid'])) {
				//'Lösen'-Button anzeigen
				$solveButton=ticket::getSolveButton($action['uid']);
				div::htm_mergeSiteContent($tab3->content,$solveButton->wrapContent());
			}
		}



		if($action['mode']!=table::MODE_NEW) {
			//Ticketbearbeitungsmodus
			if(user::curusr_getPermission("ticket")>=user::PERMISSION_READ) {
				//Tickethistory anzeigen.
				$tab->addElement($tab3=new tabItem('History'));
				div::htm_mergeSiteContent($tab3->content,ticket::getHistory($action['uid']));
			}

			if(user::curusr_getPermission("ticket")>=user::PERMISSION_FULL) {
				//Erfasser-Formular anzeigen
				$erfasser_id=$db->exec_SELECTgetRows("t_trigger_id","ticket","uid=".$action['uid']);
				$tab->addElement($tab3=new tabItem('Erfasser'));
				$tab3->content=table::getForm("usr_user",'edit_detail',false,table::MODE_VIEW,$erfasser_id[0]["t_trigger_id"],$tab3);
			}
		}


	}
	//HTML-Code ausgeben
	div::htm_mergeSiteContent($content,$tab->wrapContent());
	div::htm_echoContent($content);
}
?>