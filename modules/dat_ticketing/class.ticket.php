<?
/**
 * Beinhaltet alle Tabellen-, Listen- und Formulardefinitionen und ergänzende Funktionen des eigentlichen Ticketing-Systems
 *
 * @author Ueli Wyss
 * @package IPA
 */

$TABLES['ticket']=Array(
	'ctrl'=>Array(
		'isDbTable'=>true,
	),
	'fields'=>Array(
		'uid'=>array(
			'label'=>'id',
			'description'=>'Primärschlüssel-Feld der Tabelle',
			'dbconfig'=>Array(
				'type'=>'int',
				'autoIncrement'=>1,
				'primaryKey'=>1
			),
		),
		't_number'=>array(
			'label'=>'Ticketnummer',
			'description'=>'Nummer, die das Ticket identifiziert.',
			'formconfig'=>array(
				'all'=>array(
					'specialVal'=>'',
					'readOnly'=>true,
				),
			),
			'dbconfig'=>Array(
				'type'=>'varchar',
				'length'=>20,
			),
		),
		't_trigger_id'=>array(
			'label'=>'Auslöser',
			'description'=>'Benutzer, der das Ticket ausgelöst hat.',
			'foreign_table'=>'usr_user',
			'foreign_key'=>'uid',
			'foreign_display'=>'u_loginName',
			'formconfig'=>array(
				'new_agent'=>array(
					'type'=>'select',
					'foreign_display'=>'u_nachname, u_vorname, u_loginName',
					'eval'=>'required',
				),
				'default'=>array(
					'type'=>'select',
					'foreign_display'=>'u_nachname, u_vorname, u_loginName',
					'readOnly'=>true,
				),
			),
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>11,
			),
		),
		't_created'=>array(
			'label'=>'erstellt',
			'description'=>'Erstelldatum',
			'formconfig'=>array(
				'default'=>array(
					'specialVal'=>'now',
				),
				'edit'=>array(
					'type'=>'text',
					'readOnly'=>true,
				),
			),
			'dbconfig'=>array(
				'type'=>'datetime',
			),
		),
		't_closed'=>array(
			'label'=>'Geschlossen am',
			'description'=>'Schliessdatum des Tickets',
			'formconfig'=>array(
				'edit'=>array(
					'type'=>'text',
					'readOnly'=>true,
				),
			),
			'dbconfig'=>array(
				'type'=>'datetime',
			),
		),
		't_contract_id'=>array(
			'label'=>'Vertrag',
			'description'=>'Vertrag, der für das Ticket gewählt wurde.',
			'foreign_table'=>'contract',
			'foreign_key'=>'uid',
			'foreign_display'=>'c_shortName',
			'formconfig'=>array(
				'default'=>array(
					'type'=>'select',
					'foreign_display'=>'c_shortName',
					'eval'=>'required',
				),
				'edit_agent'=>array(
					'type'=>'select',
					'foreign_display'=>'c_shortName',
					'eval'=>'required',
				),
				'edit_customer'=>array(
					'type'=>'select',
					'foreign_display'=>'c_shortName',
					'readOnly'=>true,
				),
			),
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>11,
			),
		),
		't_lastEditor_id'=>array(
			'label'=>'letzter Bearbeiter',
			'description'=>'Benutzer, der das Ticket als letztes Bearbeitet hat.',
			'foreign_table'=>'usr_user',
			'foreign_key'=>'uid',
			'foreign_display'=>'u_loginName',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
					'foreign_display'=>'u_loginName,u_nachname,u_vorname',
					'readOnly'=>true,
				),
			),
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>11,
			),
		),
		't_owner_id'=>array(
			'label'=>'Besitzer',
			'description'=>'Benutzer, der als Besitzer des Tickets eingetragen ist.',
			'foreign_table'=>'usr_user',
			'foreign_key'=>'uid',
			'foreign_display'=>'u_loginName',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
					'foreign_display'=>'u_loginName,u_nachname,u_vorname',
					'readOnly'=>true,
				),
			),
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>11,
			),
		),
		't_team_id'=>array(
			'label'=>'Team',
			'description'=>'Teamangehörigkeit des Tickets',
			'foreign_table'=>'usr_team',
			'foreign_key'=>'uid',
			'foreign_display'=>'t_name',
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>11,
			),
		),
		't_priority'=>array(
			'label'=>'priorität',
			'description'=>'Wichtigkeit des Tickets.',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
					'eval'=>'required',
					'items'=>array(
						ticket::PRIORITY_LOW=>'Niedrig',
						ticket::PRIORITY_MID=>'Mittel',
						ticket::PRIORITY_HIGH=>'Hoch',
					),
				),
			),
			'allocation'=>array(
				ticket::PRIORITY_LOW=>'Niedrig',
				ticket::PRIORITY_MID=>'Mittel',
				ticket::PRIORITY_HIGH=>'Hoch',
			),
		),
		't_description'=>array(
			'label'=>'Problembeschreibung',
			'description'=>'Problembeschreibung',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim,required',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>1000,
			),
		),
		't_status'=>array(
			'label'=>'Status',
			'description'=>'Status des Tickets.',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
					'readOnly'=>true,
					'items'=>array(
						ticket::STATUS_OPEN=>"Offen",
						ticket::STATUS_EDIT=>"In Bearbeitung",
						ticket::STATUS_INCOMPLETE=>"Unvollständig",
						ticket::STATUS_CLOSED=>"Geschlossen",
						ticket::STATUS_ASSIGNED=>"Zugewiesen",
					),
				),
			),
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>11,
			),
			'allocation'=>array(
				ticket::STATUS_OPEN=>"Offen",
				ticket::STATUS_EDIT=>"In Bearbeitung",
				ticket::STATUS_INCOMPLETE=>"Unvollständig",
				ticket::STATUS_CLOSED=>"Geschlossen",
				ticket::STATUS_ASSIGNED=>"Zugewiesen",
			),
		),
	),
	'forms'=>Array(
		'default'=>array(
			'fields'=>'',
		),
		'new_agent'=>array(
			'fields'=>'t_number=<func>ticket::getNewTicketNumber()</func>,t_trigger_id,t_priority,t_created,t_contract_id,t_lastEditor_id=<func>user::curusr_getId()</func>,t_description,t_team_id=<func>user::curusr_getTeamId()</func>,t_status='.ticket::STATUS_OPEN,
			'title'=>'Neues Ticket',
			'description'=>'Hier können Sie ein neues Ticket erfassen.',
		),
		'new_customer'=>array(
			'fields'=>'t_number=<func>ticket::getNewTicketNumber()</func>,t_trigger_id=<func>user::curusr_getId()</func>,t_priority,t_created,t_lastEditor_id=<func>user::curusr_getId()</func>,t_description,t_status='.ticket::STATUS_INCOMPLETE,
			'title'=>'Neues Ticket',
			'description'=>'Hier können Sie ein neues Ticket erfassen.<br><br><li>Beschreiben Sie Ihr Problem bitte möglichst detailiert (muss kein Roman sein).<br><li>Wählen sie die Priorität des Tickets nach der Auswirkung des Problems auf Ihre Arbeit.',
		),
		'edit_agent'=>array(
			'fields'=>'t_number,t_trigger_id,t_priority,t_lastEditor_id,t_description,t_status,t_owner_id,t_created,t_contract_id',
			'title'=>'Ticketeigenschaften',
			'description'=>'Die Eigenschaften dieses Tickets können nicht mehr verändert werden. Sie können jedoch das Ticket bearbeiten, indem Sie einen Kommentar erfassen (falls Sie die nötigen Rechte besitzen).',
		),
		'edit_customer'=>array(
			'fields'=>'t_number,t_trigger_id,t_priority,t_lastEditor_id,t_description,t_status,t_owner_id,t_created,t_contract_id',
			'title'=>'Ticketeigenschaften',
			'description'=>'Falls das Ticket noch nicht vom Informatik-Support bearbeitet wird, können Sie noch Änderungen vornehmen.',
		),
		'complete'=>array(
			'fields'=>'t_priority,t_contract_id,t_lastEditor_id=<func>user::curusr_getId()</func>,t_description,t_team_id=<func>user::curusr_getTeamId()</func>,t_status='.ticket::STATUS_OPEN,
			'execOnSave'=>'<func>ticket::assume(<action>uid</action>)</func>',
			'title'=>'Ticket vervollständigen',
			'description'=>'Dieses Ticket wurde von einem Kunden erfasst und ist noch nicht vollständig. Vervollständigen Sie daher bitte die fehlenden Angaben.',
		),
	),
	'lists'=>Array(
		'owner_agent_open'=>Array(
			'title'=>'Offene Tickets',
			'description'=>'Sie sind der Besitzer der Tickets in dieser Auflistung. Diese Tickets sind noch nicht agbeschlossen. Das heisst, sie sollten in der nächsten Zeit von Ihnen gelöst oder weitergeleitet werden.',
			'fields'=>'t_number,t_trigger_id,t_priority,t_description,t_status',
			'whereClause'=>'t_owner_id=<func>user::curusr_getId()</func> AND t_status!='.ticket::STATUS_CLOSED,
			'additionalFields'=>array(
				'Laufzeit'=>'<func>ticket::wrapTicketRunTime(<uid>)</func>',
			),
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				/*'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',*/
				'assignToUser'=>array(
					'action'=>"javascript:top.editElement('new','usr_user','".NO_UID."','".$ticket_id."',true,'','','ofTeam');",
					'icon'=>'images/ilist_actions_assignUser.gif',
					'description'=>'Zuweisung innerhalb Team',
				),
				'assignToTeam'=>array(
					'action'=>"javascript:top.editElement('edit','ticket','".NO_UID."',<uid>,true);",
					'icon'=>'images/ilist_actions_assignTeam.gif',
					'description'=>'Weiterleitung ans nächste Support-Level',
				),
			),
		),
		'owner_agent_closed'=>Array(
			'title'=>'Gelöste Tickets',
			'description'=>'In dieser Ansicht sehen Sie alle Tickets, die von Ihnen gelöst wurden.',
			'fields'=>'t_number,t_trigger_id,t_priority,t_description,t_closed',
			'whereClause'=>'t_owner_id=<func>user::curusr_getId()</func> AND t_status='.ticket::STATUS_CLOSED,
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
			),
		),
		'owner_customer_open'=>Array(
			'title'=>'Tickets in Bearbeitung',
			'description'=>'Diese von Ihnen erfassten Probleme, befinden sich in Bearbeitung. Um weitere Informationen zu den Tickets zu erhalten, klicken Sie auf den Bearbeiten-Button (<img src="'.ICON_DIR.'ilist_actions_edit.gif">) neben dem Ticket.',
			'fields'=>'t_number,t_trigger_id,t_priority,t_description,t_status',
			'whereClause'=>'t_trigger_id=<func>user::curusr_getId()</func> AND (t_status='.ticket::STATUS_OPEN.' OR t_status='.ticket::STATUS_EDIT.' OR t_status='.ticket::STATUS_EDIT.')',
			'additionalFields'=>array(
				'Laufzeit'=>'<func>ticket::wrapTicketRunTime(<uid>)</func>',
			),
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'addRequest'=>array(
					'action'=>"javascript:top.editElement('".table::MODE_NEW."','addition','".NO_UID."','<uid>',true,'','','new_request')",
					'icon'=>ICON_DIR.'ilist_actions_request.gif',
					'description'=>'Rückfragen zum Ticket',
				),
				'setBack'=>array(
					'action'=>"javascript:top.editElement('".table::MODE_NEW."','addition','".NO_UID."','<uid>',true,'','','new_setbackreason')",
					'icon'=>ICON_DIR.'ilist_actions_setback.gif',
					'description'=>'Ticket zurückziehen',
				),
			),
		),
		'owner_customer_closed'=>Array(
			'title'=>'Abgeschlossene Tickets',
			'description'=>'Die folgenden Problemstellungen wurden von Ihnen erfasst, sind jedoch bereits durch den Informatik-Support gelöst worden.',
			'fields'=>'t_number,t_trigger_id,t_priority,t_description,t_status',
			'whereClause'=>'t_trigger_id=<func>user::curusr_getId()</func> AND t_status='.ticket::STATUS_CLOSED,
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
			),
		),
		'owner_customer_incomplete'=>Array(
			'title'=>'In der Warteschlange',
			'description'=>'Diese Tickets befinden sich in der Warteschlange. Sobald sie vom Support-Team angenommen werden, verschwinden sie aus dieser Ansicht.<br><br>Falls Sie Fragen haben können sie eine Rückfrage tätigen, indem Sie beim entsprechenden Ticket auf den Rückgfrage-Button (<img src="'.ICON_DIR.'ilist_actions_request.gif">) oder sich telefonisch beim Informatik-Support melden (031 634 90 90).<br>Wenn Sie das Ticket zurückziehen wollen, weil sich das Problem erledigt hat, klicken Sie auf den Rückzug-Button (<img src="'.ICON_DIR.'ilist_actions_setback.gif">). Beim Zurückziehen eines Tickets müssen Sie den Grund für den Rückzug angeben.',
			'fields'=>'t_number,t_trigger_id,t_priority,t_description,t_status',
			'whereClause'=>'t_trigger_id=<func>user::curusr_getId()</func> AND t_status='.ticket::STATUS_INCOMPLETE,
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'addRequest'=>array(
					'action'=>"javascript:top.editElement('".table::MODE_NEW."','addition','".NO_UID."','<uid>',true,'','','new_request')",
					'icon'=>ICON_DIR.'ilist_actions_request.gif',
					'description'=>'Rückfragen zum Ticket',
				),
				'setBack'=>array(
					'action'=>"javascript:top.editElement('".table::MODE_NEW."','addition','".NO_UID."','<uid>',true,'','','new_setbackreason')",
					'icon'=>ICON_DIR.'ilist_actions_setback.gif',
					'description'=>'Ticket zurückziehen',
				),
			),
		),
		'pool_1-Level'=>Array(
			'title'=>'Ticket-Pool',
			'description'=>'Diese Auflistung zeigt diejenigen Tickets, welche noch keinen Besitzer haben (noch keinem Agent zugewiesen wurden), aber ihrem Support-Team zugewiesen wurden. Durchs klicken auf den Annehmen-Button (<img src="images/ilist_actions_assume.gif">) wird das Ticket Ihnen zugewiesen.',
			'fields'=>'t_number,t_trigger_id,t_priority,t_description,t_status',
			'whereClause'=>'(t_owner_id IS NULL OR t_owner_id=0) AND (t_status='.ticket::STATUS_INCOMPLETE.' OR t_team_id=<func>user::curusr_getTeamId()</func>)',
			'additionalFields'=>array(
				'Laufzeit'=>'<func>ticket::wrapTicketRunTime(<uid>)</func>',
			),
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'assume'=>array(
					'action'=>"javascript:top.editElement('".table::MODE_EDIT."','<local_table>','<uid>','".NO_UID."',true,'','','complete')",
					'icon'=>'images/ilist_actions_assume.gif',
					'description'=>'Ticket annehmen',
				),
			),
		),
		'pool_2-3-Level'=>Array(
			'title'=>'Ticket-Pool',
			'description'=>'Diese Auflistung zeigt diejenigen Tickets, welche noch keinen Besitzer haben (noch keinem Agent zugewiesen wurden), aber ihrem Support-Team zugewiesen wurden. Durchs klicken auf den Annehmen-Button (<img src="images/ilist_actions_assume.gif">) wird das Ticket Ihnen zugewiesen.',
			'fields'=>'t_number,t_trigger_id,t_priority,t_description,t_status',
			'whereClause'=>'(t_owner_id IS NULL OR t_owner_id=0) AND t_team_id=<func>user::curusr_getTeamId()</func>',
			'additionalFields'=>array(
				'Laufzeit'=>'<func>ticket::wrapTicketRunTime(<uid>)</func>',
			),
			'actions'=>array(
				'edit'=>"javascript:top.editElement('".table::MODE_EDIT."','<local_table>','<uid>')",
				'assume'=>array(
					'action'=>"javascript:top.action('exec_func','&func=ticket::assume(<uid>)')",
					'icon'=>'images/ilist_actions_assume.gif',
					'description'=>'Ticket annehmen',
				),
			),
		),
		'team'=>Array(
			'title'=>'Team-Tickets',
			'description'=>'Zeigt alle Tickets, die einem Benutzer Ihres Teams zugewiesen sind.',
			'fields'=>'t_number,t_trigger_id,t_priority,t_description,t_status',
			'additionalFields'=>array(
				'Laufzeit'=>'<func>ticket::wrapTicketRunTime(<uid>)</func>',
			),
			'whereClause'=>'t_team_id=<func>user::curusr_getTeamId()</func> AND t_status!='.ticket::STATUS_CLOSED.' AND t_status!='.ticket::STATUS_OPEN,
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
			),
		),
	),
	'resources'=>array(
		'ticket'=>array(
			'name'=>'Tickets',
			'description'=>'Beinhaltet alle Tickets.',
		),
		'ticket_ofTeam'=>array(
			'name'=>'Team-Tickets',
			'description'=>'Beinhaltet alle Tickets des Teams, dem der Benutzer angehört.',
		),
		'ticket_own'=>array(
			'name'=>'Eigene Tickets',
			'description'=>'Beinhaltet die eigenen Tickets eines Benutzers.',
		),
		'ticket_new'=>array(
			'name'=>'Neues Ticket',
			'description'=>'Aktion, neue Tickets erfassen.',
		),
	),
);

$TABLES['contract']=Array(
	'ctrl'=>Array(
		'isDbTable'=>true,
	),
	'fields'=>Array(
		'uid'=>array(
			'label'=>'id',
			'description'=>'Primärschlüssel-Feld der Tabelle',
			'dbconfig'=>Array(
				'type'=>'int',
				'autoIncrement'=>1,
				'primaryKey'=>1
			),
		),
		'c_shortName'=>array(
			'label'=>'kurzname',
			'description'=>'Kurzname des Vertrags.',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>100,
			),
		),
		'c_description'=>array(
			'label'=>'Beschreibung',
			'description'=>'Beschreibung des Status.',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		'c_created'=>array(
			'label'=>'erstellt',
			'description'=>'Erstelldatum',
			'formconfig'=>array(
				'all'=>array(
					'specialVal'=>'now',
				),
			),
			'dbconfig'=>array(
				'type'=>'datetime',
				/*'default'=>'CURRENT_TIMESTAMP',*/
			),
		),
		'c_creator_id'=>array(
			'label'=>'Ersteller',
			'description'=>'Benutzer, der den Status erfasst hat.',
			'foreign_table'=>'usr_user',
			'foreign_key'=>'uid',
			'foreign_display'=>'u_loginName',
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>11,
			),
		),
		'c_slaMoFrom'=>array(
			'label'=>'Montag von',
			'description'=>'Verfügbarkeitszeit: Montag von (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
					'default'=>'08:00',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_slaMoTo'=>array(
			'label'=>'Montag bis',
			'description'=>'Verfügbarkeitszeit: Montag bis (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
					'default'=>'16:30'
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_slaDiFrom'=>array(
			'label'=>'Dienstag von',
			'description'=>'Verfügbarkeitszeit: Dienstag von (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
					'default'=>'08:00',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_slaDiTo'=>array(
			'label'=>'Dienstag bis',
			'description'=>'Verfügbarkeitszeit: Dienstag bis (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
					'default'=>'16:30'
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_slaMiFrom'=>array(
			'label'=>'Mittwoch von',
			'description'=>'Verfügbarkeitszeit: Mittwoch von (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
					'default'=>'08:00',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_slaMiTo'=>array(
			'label'=>'Mittwoch bis',
			'description'=>'Verfügbarkeitszeit: Mittwoch bis (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
					'default'=>'16:30'
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_slaDoFrom'=>array(
			'label'=>'Donnerstag von',
			'description'=>'Verfügbarkeitszeit: Donnerstag von (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
					'default'=>'08:00',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_slaDoTo'=>array(
			'label'=>'Donnerstag bis',
			'description'=>'Verfügbarkeitszeit: Donnerstag bis (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
					'default'=>'16:30'
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_slaFrFrom'=>array(
			'label'=>'Freitag von',
			'description'=>'Verfügbarkeitszeit: Freitag von (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
					'default'=>'08:00',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_slaFrTo'=>array(
			'label'=>'Freitag bis',
			'description'=>'Verfügbarkeitszeit: Freitag bis (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
					'default'=>'16:30'
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_slaSaFrom'=>array(
			'label'=>'Samstag von',
			'description'=>'Verfügbarkeitszeit: Samstag von (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'time',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_slaSaTo'=>array(
			'label'=>'Samstag bis',
			'description'=>'Verfügbarkeitszeit: Samstag bis (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'time',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_slaSoFrom'=>array(
			'label'=>'Sonntag von',
			'description'=>'Verfügbarkeitszeit: Sonntag von (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'time',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_slaSoTo'=>array(
			'label'=>'Sonntag bis',
			'description'=>'Verfügbarkeitszeit: Sonntag bis (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'time',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_recHigh'=>array(
			'label'=>'Wiederherstellungszeit (Hoch)',
			'description'=>'Wiederherstellungszeit bei hoher Priorität (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_recMid'=>array(
			'label'=>'Wiederherstellungszeit (Mittel)',
			'description'=>'Wiederherstellungszeit bei mittlerer Priorität (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),
		'c_recLow'=>array(
			'label'=>'Wiederherstellungszeit (Niedrig)',
			'description'=>'Wiederherstellungszeit bei niedriger Priorität (Format: <b>hh:mm:ss</b> oder <b>hh:mm</b>)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
			),
		),

	),
	'forms'=>Array(
		'default'=>array(
			'fields'=>'',
		),
		'new'=>array(
			'fields'=>'c_creator_id=<func>user::curusr_getId()</func>,c_shortName,c_description,c_slaMoFrom,c_slaMoTo,c_slaDiFrom,c_slaDiTo,c_slaMiFrom,c_slaMiTo,c_slaDoFrom,c_slaDoTo,c_slaFrFrom,c_slaFrTo,c_slaSaFrom,c_slaSaTo,c_slaSoFrom,c_slaSoTo,c_created,c_recHigh,c_recMid,c_recLow',
			'title'=>'Neuen Vertrag erfassen',
			'description'=>'Hier können Sie einen neuen Vertrag erfassen.',
		),
		'edit'=>array(
			'fields'=>'c_shortName,c_description,c_slaMoFrom,c_slaMoTo,c_slaDiFrom,c_slaDiTo,c_slaMiFrom,c_slaMiTo,c_slaDoFrom,c_slaDoTo,c_slaFrFrom,c_slaFrTo,c_slaSaFrom,c_slaSaTo,c_slaSoFrom,c_slaSoTo,c_recHigh,c_recMid,c_recLow',
			'title'=>'Vertrag bearbeiten',
		),
	),
	'lists'=>array(
		'default'=>array(
		),
		'listAll'=>Array(
			'title'=>'Verträge',
			'description'=>'In dieser Ansicht werden alle Verträge aufgelistet.',
			'fields'=>'c_shortName,c_description',
			'commonActions'=>'drop',
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
	),
	'resources'=>array(
		'contract'=>array(
			'name'=>'Verträge',
			'description'=>'Beinhaltet alle Verträge (SLAs).',
		),
	),
);

$TABLES['addition']=Array(
	'ctrl'=>Array(
		'isDbTable'=>true,
	),
	'fields'=>Array(
		'uid'=>array(
			'label'=>'id',
			'description'=>'Primärschlüssel-Feld der Tabelle',
			'dbconfig'=>Array(
				'type'=>'int',
				'autoIncrement'=>1,
				'primaryKey'=>1
			),
		),
		'a_created'=>array(
			'label'=>'erstellt',
			'description'=>'Erstelldatum',
			'formconfig'=>array(
				'all'=>array(
					'specialVal'=>'now',
				),
			),
			'dbconfig'=>array(
				'type'=>'datetime',
				/*'default'=>'CURRENT_TIMESTAMP',*/
			),
		),
		'a_creator_id'=>array(
			'label'=>'Ersteller',
			'description'=>'Benutzer, der den Status erfasst hat.',
			'foreign_table'=>'usr_user',
			'foreign_key'=>'uid',
			'foreign_display'=>'u_loginName',
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>11,
			),
		),
		'a_type'=>array(
			'label'=>'Typ',
			'description'=>'Typ des Ticketzusatzes.',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
					'eval'=>'required',
					'items'=>array(
						ticket::ADDITIONTYPE_ADD=>'Ergänzung',
						ticket::ADDITIONTYPE_SOLUTION=>'Lösung',
						ticket::ADDITIONTYPE_INFO=>'Info',
					),
				),
			),
			'allocation'=>array(
				ticket::ADDITIONTYPE_ADD=>'Ergänzung',
				ticket::ADDITIONTYPE_SOLUTION=>'Lösung',
				ticket::ADDITIONTYPE_INFO=>'Info',
				ticket::ADDITIONTYPE_REQUEST=>'Nachfrage',
				ticket::ADDITIONTYPE_ANSWER=>'Anwort',
				ticket::ADDITIONTYPE_SETBACKREASON=>'Grund für Ticketrückzug',
				ticket::ADDITIONTYPE_SYSTEM=>'System',
			),
		),
		'a_text'=>array(
			'label'=>'Text',
			'description'=>'Text des Ticketzusatzes',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim,required',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>1000,
			),
		),
	),
	'forms'=>Array(
		'new'=>array(
			'title'=>'Neue Ergänzung zum Ticket',
			'description'=>'Hier können Sie eine Ergänzung zum Ticket erfassen. Wählen Sie zuerst den passenden Typ.',
			'fields'=>'a_type,a_text,a_created,a_creator_id=<func>user::curusr_getId()</func>',
			'execOnSave'=>'<func>ticket::assignAddition(<action>foreign_uid</action>,<insert_uid>)</func>',
		),
		'new_solution'=>array(
			'title'=>'Ticket lösen...',
			'description'=>'Geben Sie die Lösung des Problems möglichst detailiert an, damit beim erneuten Auftreten des Problems auf diesen Text zurückgegriffen werden kann.',
			'fields'=>'a_type='.ticket::ADDITIONTYPE_SOLUTION.',a_text,a_created,a_creator_id=<func>user::curusr_getId()</func>',
			'execOnSave'=>'<func>ticket::assignAddition(<action>foreign_uid</action>,<insert_uid>,true)</func>',
		),
		'new_request'=>array(
			'title'=>'Fragen stellen...',
			'description'=>'Füllen Sie dieses Formular aus, falls sie eine Frage zum betreffenden Problem haben.',
			'fields'=>'a_type='.ticket::ADDITIONTYPE_REQUEST.',a_text,a_created,a_creator_id=<func>user::curusr_getId()</func>',
			'execOnSave'=>'<func>ticket::assignAddition(<action>foreign_uid</action>,<insert_uid>)</func>',
		),
		'new_answer'=>array(
			'title'=>'antworten',
			'description'=>'Geben Sie hier die Antwort auf die vom Benutzer gestellte Frage.',
			'fields'=>'a_type='.ticket::ADDITIONTYPE_ANSWER.',a_text,a_created,a_creator_id=<func>user::curusr_getId()</func>',
			'execOnSave'=>'<func>ticket::assignAddition(<action>foreign_uid</action>,<insert_uid>)</func>',
		),
		'new_setbackreason'=>array(
			'title'=>'Begründung des Rückzugs',
			'description'=>'Geben Sie bitte an, warum sie das Ticket zurückziehen möchten. Beschreiben sie bitte auch, wie sich das Problem gelöst hat (falls es sich gelöst hat).',
			'fields'=>'a_type='.ticket::ADDITIONTYPE_SETBACKREASON.',a_text,a_created,a_creator_id=<func>user::curusr_getId()</func>',
			'execOnSave'=>'<func>ticket::assignAddition(<action>foreign_uid</action>,<insert_uid>,true)</func>',
		),
	),
);

$TABLES['tickethasaddition']=Array(
	'ctrl'=>Array(
		'isDbTable'=>true,
		'type'=>'intermediate',
	),
	'fields'=>Array(
		'ta_ticket_id'=>array(
			'label'=>'Ticket',
			'description'=>'Ticket',
			'foreign_table'=>'ticket',
			'foreign_key'=>'uid',
			'foreign_display'=>'t_number',
			'dbconfig'=>Array(
				'type'=>'int',
				'length'=>11,
				'primaryKey'=>1
			),
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
				),
			),
		),
		'ta_addition_id'=>array(
			'label'=>'Ticket-Addition',
			'description'=>'Ticket-Addition',
			'foreign_table'=>'addition',
			'foreign_key'=>'uid',
			'dbconfig'=>Array(
				'type'=>'int',
				'length'=>11,
				'primaryKey'=>1
			),
			'formconfig'=>array(
				'all'=>array(
					/*'type'=>'select',
					Wie soll das dargestellt werden?*/
				),
			),
		),
	),
);

class ticket {
	Const STATUS_OPEN=1;
	Const STATUS_EDIT=2;
	Const STATUS_INCOMPLETE=4;
	Const STATUS_CLOSED=5;
	Const STATUS_ASSIGNED=6;

	Const SUPPORTLEVEL_1=1;
	Const SUPPORTLEVEL_2=2;
	Const SUPPORTLEVEL_3=3;

	Const PRIORITY_HIGH=3;
	Const PRIORITY_MID=2;
	Const PRIORITY_LOW=1;

	Const ADDITIONTYPE_ADD=1;
	Const ADDITIONTYPE_SOLUTION=2;
	Const ADDITIONTYPE_INFO=3;
	Const ADDITIONTYPE_REQUEST=4;
	Const ADDITIONTYPE_ANSWER=5;
	Const ADDITIONTYPE_SETBACKREASON=6;
	Const ADDITIONTYPE_SYSTEM=7;

	/**
	 * Generiert einen Button vom Typ formButton, welcher beim klicken ein Popup öffnet, welches die Teams des nächst höheren Support-Levels auflistet, um das Ticket zuzuweisen. Diese Funktion wird beim Bearbeiten eines Tickets aufgerufen.
	 *
	 * @param int $ticket_id Uid eines Tickets
	 * @return formButton
	 */
	function getNextSupportLevelButton($ticket_id) {
		global $db;

		$nextSupportLevel=ticket::getNextSupportLevel($ticket_id);
		if($nextSupportLevel) {
			$button=new formButton("An Support-Level ".$nextSupportLevel." weiterleiten","javascript:top.editElement('edit','ticket','".NO_UID."','".$ticket_id."',true);","images/ilist_actions_assignTeam.gif");
		}
		return $button;
	}

	/**
	 * Liefert die Schaltfläche (Typ:formButton), die das Formular öffnet um eine Lösung für das Ticket zu erfassen und das Ticket zu schliessen.
	 *
	 * @param int $ticket_id Uid eines Tickets
	 * @return formButton
	 */
	function getSolveButton($ticket_id) {
		$button=new formButton("Lösen","javascript:top.editElement('new','addition','".NO_UID."','".$ticket_id."',true,'','','new_solution');",ICON_DIR."/ilist_actions_solve.gif");

		return $button;
	}

	/**
	 * Liefert die Schaltfläche (Typ:formButton), die das Formular öffnet um das Ticket einem Teammitglied zuzuweisen.
	 *
	 * @param int $ticket_id
	 * @return formButton
	 */
	function getAssignToTeamUserButton($ticket_id) {
		$button=new formButton("Zuweisen innerhalb Team","javascript:top.editElement('new','usr_user','".NO_UID."','".$ticket_id."',true,'','','ofTeam');","images/ilist_actions_assignUser.gif");

		return $button;
	}

	/**
	 * Liefert den Button vom Typ formButton, der verwendet wird um zu einem Ticket einen neuen Kommentar hinzuzufügen.
	 * Diese Funktion wird beim Bearbeiten eines Tickets aufgerufen.
	 *
	 * @param int $ticket_id Uid eines Tickets.
	 * @return formButton
	 */
	function getAdditionButton($ticket_id) {
		$button=new formButton("Bearbeiten","javascript:top.editElement('new','addition','".NO_UID."','".$ticket_id."',true,'','','new');",ICON_DIR."/ilist_actions_edit.gif");

		return $button;
	}



	/**
	 * Ordnet ein Ticket einem Benutzer zu und setzt den Status auf STATUS_EDIT.
	 * Diese Funktion wird beim klicken auf den Annehmen-Button in der Ticket-Auflistung gewählt.
	 *
	 * @param int $ticket_id Uid eines Tickets.
	 */
	function assume($ticket_id) {
		global $db;
		$values=array(
			't_owner_id'=>user::curusr_getId(),
			't_team_id'=>user::curusr_getTeamId(),
			't_status'=>ticket::STATUS_EDIT,
		);

		$db->exec_UPDATEquery("ticket","uid=".$ticket_id,$values);
	}

	/**
	 * Diese Funktion weist ein Ticket einem Team zu.
	 * Diese Funktion wird verwendet, wenn ein Ticket an ein Team des nächst höheren Support-Levels weitergeleitet wird.
	 *
	 * @param int $ticket_id Uid eines Tickets.
	 * @param int $team_id Uid eines Support-Teams.
	 */
	function assignToTeam($ticket_id,$team_id) {
		global $db;

		$values=array(
			't_team_id'=>$team_id,
			't_owner_id'=>'NULL',
			't_status'=>ticket::STATUS_OPEN,
		);
		$db->exec_UPDATEquery("ticket","uid=".$ticket_id,$values);

		$result=$db->exec_SELECTquery("*","ticket","uid=".$ticket_id);
		$ticket=$db->sql_fetch_assoc($result);

		$result=$db->exec_SELECTquery("*","usr_team","uid=".$team_id);
		$team=$db->sql_fetch_assoc($result);

		$supportLevels=table::getAllocation("usr_team","t_supportLevel");

		ticket::addSystemAddition($ticket_id,"Das Ticket ".$ticket['t_number']." wurde an das ".$supportLevels[$team['t_supportLevel']]."-Team ".$team['t_name']." weitergeleitet.");
	}

	/**
	 * Diese Funktion weist das Ticket einem Benutzer zu.
	 * Diese Funktion wird beim Annehmen eines Tickets und beim Zuweisen innerhalb eines Support-Teams.
	 *
	 * @param unknown_type $ticket_id
	 * @param unknown_type $user_id
	 */
	function assignToUser($ticket_id,$user_id) {
		global $db;

		$values=array(
			't_owner_id'=>$user_id,
			't_status'=>ticket::STATUS_OPEN,
		);
		$db->exec_UPDATEquery("ticket","uid=".$ticket_id,$values);

		$result=$db->exec_SELECTquery("*","ticket","uid=".$ticket_id);
		$ticket=$db->sql_fetch_assoc($result);

		$result=$db->exec_SELECTquery("*","usr_user","uid=".$user_id);
		$user=$db->sql_fetch_assoc($result);

		ticket::addSystemAddition($ticket_id,"Das Ticket ".$ticket['t_number']." wurde dem Benutzer ".$user['u_loginName']."(".$user['u_vorname']." ".$user['u_nachname']." weitergeleitet.");
	}

	/**
	 * Weist einen Kommentar (addition) einem Ticket zu.
	 *
	 * @param int $ticket_id Uid eines Tickets.
	 * @param int $addition_id Uid eines Kommentars (addition).
	 */
	function assignAddition($ticket_id,$addition_id,$closeTicket=false) {
		global $db;

		$values=array(
			't_status'=>ticket::STATUS_EDIT,
		);
		$db->exec_UPDATEquery("ticket","uid=".$ticket_id,$values);

		$values=array(
			'ta_ticket_id'=>$ticket_id,
			'ta_addition_id'=>$addition_id,
		);
		$db->exec_INSERTquery("tickethasaddition",$values);

		if($closeTicket) {
			$values=array(
				't_status'=>ticket::STATUS_CLOSED,
				't_closed'=>div::date_gerToSQL(div::date_getDateTime()),
			);
			$db->exec_UPDATEquery("ticket","uid=".$ticket_id,$values);
		}
	}

	/**
	 * Liefert beim erstellen eines neuen Tickets die nächste noch freie Ticketnummer in 6-stelliger Form.
	 *
	 * @return string Ticketnummer
	 */
	function getNewTicketNumber() {
		global $db;

		$numlen=6;

		$result=$db->exec_SELECTquery("t_number","ticket","","","t_number DESC");
		$row=$db->sql_fetch_row($result);
		if($row[0]) {
			$num=$row[0]+1;
		} else {
			$num=1;
		}

		$num=str_repeat("0",($numlen-strlen($num))).$num;
		return $num;
	}

	/**
	 * Ermittelt das Support-Level indem sich ein Ticket momentan befindet.
	 * Die Angabe des Support-Levels wird aus dem Datensatz des Teams gelesen, welchem das Ticket zugeordnet ist.
	 *
	 * @param int $ticket_id Uid eines Tickets
	 * @return int Support-Level
	 */
	function getSupportLevel($ticket_id) {
		global $db;

		$query="SELECT t_supportLevel FROM ticket LEFT JOIN usr_team ON ticket.t_team_id=usr_team.uid WHERE ticket.uid=".$ticket_id;
		$result=$db->sql_query($query);
		$row=$db->sql_fetch_row($result);
		return $row[0][0];
	}

	/**
	 * Liefert das nächst Höhere Supportlevel eines Ticekts als Zahl. Gibt 0 zurück, wenn sich das Ticket bereits im 3. Level befindet.
	 *
	 * @param int $ticket_id Uid eines Tickets
	 * @return int Support-Level
	 */
	function getNextSupportLevel($ticket_id) {
		global $db;

		$query="SELECT usr_team.t_supportLevel,ticket.t_status FROM ticket LEFT JOIN usr_team ON ticket.t_team_id=usr_team.uid WHERE ticket.uid=".$ticket_id;
		$result=$db->sql_query($query);
		$row=$db->sql_fetch_row($result);

		if($row[1]!=ticket::STATUS_CLOSED && $row[1]!=ticket::STATUS_INCOMPLETE) {
			$nextSupportLevel=($row[0]<ticket::SUPPORTLEVEL_3?($row[0]+1):0);
		} else {
			$nextSupportLevel=0;
		}
		return $nextSupportLevel;
	}

	/**
	 * Liefert den Status eines Tickets als Zahl.
	 *
	 * @param int $ticket_id Uid eines Tickets
	 * @return int Status
	 */
	function getStatus($ticket_id) {
		global $db;

		$result=$db->exec_SELECTquery("t_status","ticket","uid=".$ticket_id);
		$row=$db->sql_fetch_assoc($result);

		return $row['t_status'];
	}

	/**
	 * Gibt einen Wahrheitswert zurück, der bestimmt ob das Ticket bereits geschlossen wurde.
	 *
	 * @param int $ticket_id Uid eines Tickets
	 * @return boolean Ticket geschlossen?
	 */
	function isClosed($ticket_id) {
		global $db;

		$result=$db->exec_SELECTquery("t_status","ticket","uid=".$ticket_id);
		$row=$db->sql_fetch_assoc($result);

		return $row['t_status']==ticket::STATUS_CLOSED?true:false;
	}

	/**
	 * Liest die ID des Benutzers, der das angegebene Ticket besitzt aus und gibt sie zurück.
	 *
	 * @param int $ticket_id Uid eines Tickets
	 * @return int ID des Besitzers
	 */
	function getOwnerId($ticket_id) {
		global $db;

		$result=$db->exec_SELECTquery("t_owner_id","ticket","uid=".$ticket_id);
		$row=$db->sql_fetch_assoc($result);

		return $row['t_owner_id'];
	}

	/**
	 * Erstellt einen neuen Datensatz in der Tabelle addition, wobei der Typ(a_type) auf 'System' (ticket::ADDITIONTYPE_SYSTEM) festgelegt ist.
	 *
	 * @param int $ticket_id Uid eines Tickets
	 * @param string $text Ergänzungstext
	 */
	function addSystemAddition($ticket_id,$text) {
		global $db;

		$values=array(
			'a_type'=>ticket::ADDITIONTYPE_SYSTEM,
			'a_text'=>$text,
			'a_creator_id'=>'NULL',
			'a_created'=>div::date_gerToSQL(div::date_getDateTime()),
		);
		$db->exec_INSERTquery("addition",$values);
		$addition_id=$db->sql_insert_id();

		ticket::assignAddition($ticket_id,$addition_id);
	}

	/**
	 * Löscht einen Vertrag aus der Tabelle contract. Dabei wird auch bei allen Tickets, denen der Vertrag zugewiesen ist, das Feld 't_contract_id' auf NULL gesetzt.
	 *
	 * @param int $contract_id Uid eines Vertrags.
	 */
	function delete_contract($contract_id) {
		global $db;

		$values=array(
			't_contract_id'=>'NULL',
		);
		$db->exec_UPDATEquery("ticket","t_contract_id=".$contract_id,$values);

		$db->exec_DELETEquery("contract","uid=".$contract_id);
	}

	/**
	 * Diese Funktion berechnet die vergangene Zeit seit dem erfassen eines Tickets unter Berücksichtigung der Servicezeiten des zugewiesenen Vertrags. Das heisst, die Zeit wird nur während den Servicezeiten gezählt.
Diese Funktion bietet die Möglichkeit den Ergebniszeitwert als Timestamp (zur Weiterverarbeitung) oder als String (z.B. ‚13:14:00’) zurückzugeben. Diese Option wird über den Parameter $getTimestamp (Standard: false) angegeben.
	 *
	 * @param int $ticket_id Uid eines Tickets.
	 * @param boolean $getTimestamp Timestamp (oder String) zurückgeben?
	 * @return timestamp || string
	 */
	function getTicketRunTime($ticket_id,$getTimestamp=false) {
		global $db;

		$result=$db->exec_SELECTquery("*","ticket","uid=".$ticket_id);
		$ticket=$db->sql_fetch_assoc($result);

		if($ticket['t_status']!=ticket::STATUS_CLOSED && $ticket['t_status']!=ticket::STATUS_INCOMPLETE) {
			$result=$db->exec_SELECTquery("*","contract","uid=".$ticket['t_contract_id']);
			$contract=$db->sql_fetch_assoc($result);
			$createDate=strtotime($ticket["t_created"]);


			$now=time();
			$daytime=div::date_getTime();

			$datediff=(div::date_diff("d",div::date_gerToSQL(date("d.m.Y H:i:s",$createDate)),div::date_gerToSQL(date("d.m.Y H:i:s",$now)))+1);

			$timecount=0;

			for($i=0;$i<$datediff;$i++) {
				$weekday=date("l",$createDate);
				/*echo "WEEKDAY:".$weekday."<br>";*/
				switch($weekday) {
					case 'Monday': $slaFrom=$contract['c_slaMoFrom']; $slaTo=$contract['c_slaMoTo'];
						break;
					case 'Tuesday': $slaFrom=$contract['c_slaDiFrom']; $slaTo=$contract['c_slaDiTo'];
						break;
					case 'Wednesday': $slaFrom=$contract['c_slaMiFrom']; $slaTo=$contract['c_slaMiTo'];
						break;
					case 'Thursday': $slaFrom=$contract['c_slaDoFrom']; $slaTo=$contract['c_slaDoTo'];
						break;
					case 'Friday': $slaFrom=$contract['c_slaFrFrom']; $slaTo=$contract['c_slaFrTo'];
						break;
					case 'Saturday': $slaFrom=$contract['c_slaSaFrom']; $slaTo=$contract['c_slaSaTo'];
						break;
					case 'Sunday': $slaFrom=$contract['c_slaSoFrom']; $slaTo=$contract['c_slaSoTo'];
						break;
				}

				if($i<($datediff-1)) {
					if($i==0) {
						$timefrom=div::date_stringToTime(date("H:i:s",$createDate))<div::date_stringToTime($slaFrom)?$slaFrom:(div::date_stringToTime($slaTo)>div::date_stringToTime(date("H:i:s",$createDate))?date("H:i:s",$createDate):'00:00:00');
						$timefrom=div::date_gerToSQL(date("d.m.Y")." ".$timefrom);

						$timeto=div::date_stringToTime(date("H:i:s",$createDate))<div::date_stringToTime($slaTo)?$slaTo:'00:00:00';
						$timeto=div::date_gerToSQL(date("d.m.Y")." ".$timeto);
						$timecount=div::date_add($timecount,div::date_diff("s",$timefrom,$timeto));
					} else {
						$timecount=div::date_add($timecount,div::date_diff("s",$slaFrom,$slaTo));
					}

				} else {
					if(div::date_stringToTime($daytime)>div::date_stringToTime($slaFrom)) {
						if(div::date_stringToTime($daytime)>div::date_stringToTime($slaTo)) {

							$timeto=div::date_gerToSQL(date("d.m.Y")." ".$slaTo);
							//$timecount=div::date_add($timecount,div::date_diff("s",$slaFrom,$slaTo));
						} else {
							$timeto=div::date_gerToSQL(date("d.m.Y H:i:s",$now));
						}
						if($datediff==1){
							$timefrom=div::date_stringToTime(date("H:i:s",$createDate))>div::date_stringToTime($slaTo)?$slaTo:(div::date_stringToTime(date("H:i:s",$createDate))>div::date_stringToTime($slaFrom)?date("H:i:s",$createDate):$slaFrom);
							$timefrom=div::date_gerToSQL(date("d.m.Y")." ".$timefrom);
						} else {
							$timefrom=$slaFrom;
							$timefrom=div::date_gerToSQL(date("d.m.Y")." ".$timefrom);
						}

						$timecount=div::date_add($timecount,div::date_diff("s",$timefrom,$timeto));
					}
				}
				$createDate=div::date_addDays($createDate,1);
			}
		} else {
			$timecount=0;
		}

		if(!$getTimestamp) {
			$timecount=div::date_timeToString($timecount);
		}
		return $timecount;
	}

	/**
	 * Diese Funktion listet alle Kommentare, die zu einem Ticket erfasst wurden chronologisch auf. Dabei wird jeder Kommentar in eine Abteilung (Typ: section) verpackt und als HTML-Code zurückgegeben.
	 *
	 * @param  $ticket_id Uid eines Tickets
	 * @return string HTML-Code
	 */
	function getHistory($ticket_id) {
		global $db;

		$query="SELECT *, ticket.uid As ticket_id FROM tickethasaddition LEFT JOIN addition ON tickethasaddition.ta_addition_id=addition.uid LEFT JOIN usr_user ON addition.a_creator_id=usr_user.uid LEFT JOIN ticket ON tickethasaddition.ta_ticket_id=ticket.uid WHERE tickethasaddition.ta_ticket_id=".$ticket_id." ORDER BY addition.a_created ASC";
		$result=$db->sql_query($query);

		$content=array();
		$types=table::getAllocation("addition","a_type");


		while($row=$db->sql_fetch_assoc($result)) {
			$type=$types[$row['a_type']];
			$created=div::date_SQLToGer($row['a_created']);
			if($row['a_type']==ticket::ADDITIONTYPE_SYSTEM) {
				$creator='System';
			} else {
				$creator=$row['u_loginName']." - ".$row['u_vorname']." ".$row['u_nachname'];
			}

			if($row['a_type']==ticket::ADDITIONTYPE_REQUEST && $row['a_creator_id']!=user::curusr_getId()) {
				$answer='<a href="" onClick="return top.editElement(\'new\',\'addition\',\''.NO_UID.'\','.$row['ticket_id'].',true,\'\',\'\',\'new_answer\')">Antworten</a>';
			} else {
				$answer='';
			}

			$section=new section($created.": ".$type." (".$creator.") ".$answer,array('main'=>$row['a_text']));
			div::htm_mergeSiteContent($content,$section->wrapContent());
			//$content['main'].=$db->view_array($row);
		}
		return $content;
	}

	/**
	 * Formatiert die Zeit die seit dem Erfassen des Tickets vergangen ist:
	 * - Rot und Fett, wenn die Wiederherstellungszeit überschritten wurde.
	 * - Orange und Fett, wenn die Wiederherstellungszeit zu 80% aufgebraucht wurde.
	 *
	 * @see user::getTicketRuntime()
	 * @param int $ticket_id Uid eines Tickets.
	 * @return string HTML-Code
	 */
	function wrapTicketRunTime($ticket_id) {
		global $db;

		$result=$db->exec_SELECTquery("*","ticket","uid=".$ticket_id);
		$ticket=$db->sql_fetch_assoc($result);

		if($ticket['t_status']!=ticket::STATUS_CLOSED && $ticket['t_status']!=ticket::STATUS_INCOMPLETE) {
			$result=$db->exec_SELECTquery("*","contract","uid=".$ticket['t_contract_id']);
			$contract=$db->sql_fetch_assoc($result);

			$runtime=ticket::getTicketRunTime($ticket_id,true);
		} else {
			$runtime=0;
		}


		switch($ticket['t_priority']) {
			case ticket::PRIORITY_HIGH:$recTime=$contract['c_recHigh'];
				break;
			case ticket::PRIORITY_MID:$recTime=$contract['c_recMid'];
				break;
			case ticket::PRIORITY_LOW:$recTime=$contract['c_recLow'];
				break;
		}

		$recTime=div::date_stringToTime($recTime);

		if($recTime<$runtime) {
			$bold=true;
			$color='#FF0000';
			$text='Abgelaufen: ';
		} elseif(($recTime*0.8)<$runtime) {
			$bold=true;
			$color='#ff9000';
		} elseif(($recTime*0.6)<$runtime) {
			$bold=true;
		}

		$str=($bold?'<b>':'').'<font color="'.$color.'">'.$text.div::date_timeToString($runtime).'</font>'.($bold?'</b>':'');
		return $str;
	}
}