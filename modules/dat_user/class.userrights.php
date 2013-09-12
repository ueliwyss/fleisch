<?
/**
 * Benutzerverwaltung
 *
 * @author Ueli Wyss
 * @package IPA-Vorbereitung
 */
global $RELATIONS;

$RELATIONS[]=array(
      'relation'=>'m:m',
      'table_1'=>'usr_user',
      'table_mm'=>'usr_useringroup',
      'table_2'=>'usr_group',
      'key_1'=>'uid',
      'key_2'=>'uid',
      'key_mm_1'=>'ug_user_id',
      'key_mm_2'=>'ug_group_id',
);

$RELATIONS[]=array(
      'relation'=>'m:m',
      'table_1'=>'usr_user',
      'table_mm'=>'usr_userhasperm',
      'table_2'=>'usr_resource',
      'key_1'=>'uid',
      'key_2'=>'uid',
      'key_mm_1'=>'up_user_id',
      'key_mm_2'=>'up_resource_id',
);

$RELATIONS[]=array(
      'relation'=>'m:m',
      'table_1'=>'usr_group',
      'table_mm'=>'usr_grouphasperm',
      'table_2'=>'usr_resource',
      'key_1'=>'uid',
      'key_2'=>'uid',
      'key_mm_1'=>'gp_group_id',
      'key_mm_2'=>'gp_resource_id',
);

$RELATIONS[]=array(
      'relation'=>'m:m',
      'table_1'=>'usr_roll',
      'table_mm'=>'usr_rollhasperm',
      'table_2'=>'usr_resource',
      'key_1'=>'uid',
      'key_2'=>'uid',
      'key_mm_1'=>'rp_roll_id',
      'key_mm_2'=>'rp_resource_id',
);

$RELATIONS[]=array(
      'relation'=>'m:1',
      'table_m'=>'usr_user',
      'table_1'=>'usr_location',
      'key_m'=>'uid',
      'key_1'=>'uid',
      'key_1_foreign'=>'u_location_id',
);

$RELATIONS[]=array(
      'relation'=>'m:1',
      'table_m'=>'usr_user',
      'table_1'=>'usr_sync',
      'key_m'=>'uid',
      'key_1'=>'uid',
      'key_1_foreign'=>'u_sync_id',
);

$RELATIONS[]=array(
      'relation'=>'m:1',
      'table_m'=>'usr_user',
      'table_1'=>'usr_department',
      'key_m'=>'uid',
      'key_1'=>'uid',
      'key_1_foreign'=>'u_department_id',
);

$RELATIONS[]=array(
      'relation'=>'m:1',
      'table_m'=>'usr_user',
      'table_1'=>'usr_roll',
      'key_m'=>'uid',
      'key_1'=>'uid',
      'key_1_foreign'=>'u_roll_id',
);

$RELATIONS[]=array(
      'relation'=>'m:1',
      'table_m'=>'usr_session',
      'table_1'=>'usr_user',
      'key_m'=>'uid',
      'key_1'=>'u_loginName',
      'key_1_foreign'=>'s_user',
);

$RELATIONS[]=array(
      'relation'=>'m:1',
      'table_m'=>'usr_user',
      'table_1'=>'usr_team',
      'key_m'=>'uid',
      'key_1'=>'uid',
      'key_1_foreign'=>'u_team_id',
);

$RELATIONS[]=array(
      'relation'=>'m:m',
      'table_1'=>'usr_team',
      'table_mm'=>'usr_teamhasperm',
      'table_2'=>'usr_resource',
      'key_1'=>'uid',
      'key_2'=>'uid',
      'key_mm_1'=>'tp_team_id',
      'key_mm_2'=>'tp_resource_id',
);







// SQL-Querys für die nötigen Tabellen, die erstellt werden müssen, falls sie nicht exisiteren.
$TABLES['usr_sync']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
					'type'=>'normal',
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
					's_name'=>array(
						'label'=>'name',
						'description'=>'Name des Synchronisations-Tasks.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim,required',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>100
						),
					),
					's_description'=>array(
						'label'=>'beschreibung',
						'description'=>'Beschreibung des Synchronisations-Tasks',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim',
							)
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>200,
						),
					),
					's_host'=>Array(
						'label'=>'Host',
						'description'=>'Host (Domain-Name oder IP-Adresse) des Servers auf dem der Verzeichnis-Dienst läuft.',
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
					's_domain'=>Array(
						'label'=>'Domäne',
						'description'=>'Name der Domäne (FQDN)',
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
					's_ou'=>Array(
						'label'=>'Organisationseinheit',
						'description'=>'Name der Organisationseinheit (OU) innerhalb der angegebenen Domäne.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>100,
						),
					),
					's_username'=>array(
						'label'=>'benutzername',
						'description'=>'Benutzername, mit dem auf den Verzeichnis-Dienst zugegriffen werden kann.',
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
					's_password'=>array(
						'label'=>'passwort',
						'description'=>'Passwort, um auf den Verzeichnis-Dienst zuzugreifen.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'password',
								'eval'=>'confirm,required'
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>200,
						),
					),
					/*'s_type'=>array(
						'label'=>'typ',
						'description'=>'Art der Synchronisation (Verzeichnis-Dienst).',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
								'items'=>array(
									'0'=>'Verzeichnis-Dienst',
									'1'=>'RENO-Datenbank',
								),
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>11,
						),
					),*/
					's_active'=>array(
						'label'=>'aktiv?',
						'description'=>'Soll der Synchronisations-Task ausgeführt werden?',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'checkbox',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>1,
						),
						'allocation'=>array(
							0=>'Nein',
							1=>'Ja',
						),
					),
					/*'s_company_id'=>array(
						'label'=>'unternehmen',
						'description'=>'Unternehmen, für das die Synchronisation durchgeführt wird.',
						'foreign_table'=>'usr_company',
						'foreign_key'=>'uid',
						'foreign_display'=>'c_name',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>11,
						),
					),*/
				),
				'forms'=>array(
					'default'=>array(
					),
					'new'=>array(
						'title'=>'Neues Synchonisations-Task',
						'description'=>'Hier können Sie ein neues Synchornisations-Task mit einem LDAP-Server erfassen.',
						'fields'=>'s_name,s_description,s_domain,s_ou,s_host,s_username,s_password,s_active',
					),
					'edit'=>array(
						'title'=>'Snychonisations-Task bearbeiten',
						'description'=>'',
						'fields'=>'s_name,s_description,s_domain,s_ou,s_host,s_username,s_active',
					),
					'resetPassword'=>array(
						'title'=>'Passwort zurücksetzen',
						'description'=>'Sie können hier das Passwort, mit dem auf den LDAP-Server zugegriffen wird, ändern.',
						'fields'=>'s_password',
					),
				),
				'lists'=>Array(
					'default'=>Array(
						'title'=>'Synchronisations-Tasks',
						'description'=>'Liste aller Synchronisations-Tasks. Beim klicken auf den Synchronisations-Button (<img src="'.ICON_DIR.'ilist_actions_sync.gif">) wird das Task ausgeführt.',
						'fields'=>'s_name,s_description,s_domain,s_ou,s_host,s_username,s_active',
						'commonActions'=>'drop',
						'actions'=>array(
							'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
							'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
							'sync'=>array(
								'action'=>'javascript:top.sync(\'<uid>\')',
								'icon'=>ICON_DIR.'ilist_actions_sync.gif',
								'description'=>'Synchornisieren',
							),
						),
					),

				),
				'resources'=>array(
					'usr_sync'=>array(
						'name'=>'Synchronisations-Tasks',
						'description'=>'Beinhaltet alle Synchronisations-Tasks.',
					),
					'usr_sync_exec'=>array(
						'name'=>'Synchronisations-Tasks ausführen',
						'description'=>'Aktion, die das eine Synchronisation auslöst (Achtung: Dies ist eine Aktion und kein Objekt)',
					),
				),
			);

			$TABLES['usr_user']=array(
				'ctrl'=>array(
					'isDbTable'=>true,
					'type'=>'normal',
				),
				'fields'=>array(
					'uid'=>array(
						'label'=>'id',
						'description'=>'Primärschlüssel-Feld der Tabelle',
						'dbconfig'=>Array(
							'type'=>'int',
							'length'=>11,
							'autoIncrement'=>1,
							'primaryKey'=>1
						),
					),
					'u_vorname'=>array(
						'label'=>'vorname',
						'description'=>'Vorname des Benutzers.',
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
					'u_nachname'=>array(
						'label'=>'nachname',
						'description'=>'Nachname des Benutzers.',
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
					'u_zeichen'=>array(
						'label'=>'zeichen',
						'description'=>'Kurzzeichen des Benutzers.',
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
					//Das Folgende Feld wird ev. ausgelagert.
					/*'u_raum'=>array(
						'label'=>'raum',
						'description'=>'Büro-Nummer des Benutzers.',
						'formconfig'=>array(
							'type'=>'text',
							'eval'=>'trim',
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>100,
						),
					),*/
					'u_strasse'=>array(
						'label'=>'strasse',
						'description'=>'Strasse (privat) des Benutzers.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>100,
						),
					),
					'u_plz'=>array(
						'label'=>'PLZ',
						'description'=>'Postleitzahl (privat) des Benutzers.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim,num',
							)
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>11,
						),
					),
					'u_ort'=>array(
						'label'=>'ort',
						'description'=>'Wohnort (privat) des Benutzers.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>100,
						),
					),
					'u_loginName'=>array(
						'label'=>'Login-Name',
						'description'=>'Login-Name, der bei der Anmeldung verwendet wird.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim,required,unique',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>100,
						),
					),
					'u_password'=>array(
						'label'=>'passwort',
						'description'=>'Passwort des Benutzers. Muss zur Validierung 2x eingegeben werden.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'password',
								'eval'=>'confirm,required,crypt',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>200,
						),
					),
					'u_lastLogin'=>array(
						'label'=>'letztes Login',
						'description'=>'Zeit und Datum des letzten Logins.',
						'dbconfig'=>array(
							'type'=>'timestamp',
						),
					),
					'u_synchronize'=>array(
						'label'=>'synchronisieren',
						'description'=>'Flag, das bestimmt, ob der Benutzer synchronisiert werden soll.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'checkbox',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>1,
						),
					),
					'u_lastSync'=>array(
						'label'=>'letzte Synchronisierung',
						'description'=>'Zeit und Datum der letzten Synchronisation.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'readOnly'=>true,
							),
						),
						'dbconfig'=>array(
							'type'=>'timestamp',
						),
					),
					'u_locked'=>array(
						'label'=>'gesperrt',
						'description'=>'Sperrung des Benutzers.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'checkbox',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>1,
						),
					),
					'u_created'=>array(
						'label'=>'erstellt',
						'description'=>'Erstelldatum',
						'formconfig'=>array(
							'all'=>array(
								'specialVal'=>'now',
							),
						),
						'dbconfig'=>array(
							'type'=>'timestamp',
							/*'default'=>'CURRENT_TIMESTAMP',*/
						),
					),
					'u_department_id'=>array(
						'label'=>'abteilung',
						'description'=>'Firmen-Abteilung des Benutzers.',
						'foreign_table'=>'usr_department',
						'foreign_key'=>'uid',
						'foreign_display'=>'[d_name]',
						/*'foreign_where'=>'d_company_id=<func>$user->curusr_getCompany()</func>',*/
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
								'foreign_display'=>'[d_name]',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>11,
						),
					),
					'u_roll_id'=>array(
						'label'=>'rolle',
						'description'=>'Rolle, die der Benutzer im System übernimmt.',
						'foreign_table'=>'usr_roll',
						'foreign_key'=>'uid',
						'foreign_display'=>'[r_name]',
						/*'foreign_where'=>'r_company_id=<func>$user->curusr_getCompany()</func>',*/
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
								'foreign_display'=>'[r_name]',
								'eval'=>'required',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>11,
						),
					),
					'u_location_id'=>array(
						'label'=>'standort',
						'description'=>'Standort, an dem der Benutzer Arbeitet.',
						'foreign_table'=>'usr_location',
						'foreign_key'=>'uid',
						'foreign_display'=>'[l_name]',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
								'foreign_display'=>'[l_name]',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>11,
						),
					),
					'u_lang'=>array(
						'label'=>'sprache',
						'description'=>'Sprache des Benutzers.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
								'items'=>array(
									'1'=>'Deutsch',
									'2'=>'Französisch',
									'3'=>'Englisch',
								),
								'eval'=>'required',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>11,
						),
						'allocation'=>array(
							'1'=>'Deutsch',
							'2'=>'Französisch',
							'3'=>'Englisch',
						),
					),
					'u_lastPwChange'=>array(
						'label'=>'letzter Passwortwechsel',
						'description'=>'Zeit und Datum, wann das letzte Mal Passwort gewechselt wurde.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'datetime',
								'readOnly'=>true,
							),
						),
						'dbconfig'=>array(
							'type'=>'timestamp',
						),
					),
					'u_tel'=>array(
						'label'=>'telefonnummer',
						'description'=>'Telefonnummer (geschäftlich) des Benutzers. Beispiel: <b>031 999 9999</b>.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>100,
						),
					),
					'u_fax'=>array(
						'label'=>'faxnummer',
						'description'=>'Faxnummer (geschäftlich) des Benutzers. Beispiel: <b>031 999 9999</b>.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>100,
						),
					),
					'u_mobile'=>array(
						'label'=>'mobile',
						'description'=>'Handy-Nummer geschàftlich oder privat.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>200,
						),
					),
					'u_active'=>array(
						'label'=>'aktiv',
						'description'=>'Flag: Ist der Benutzer aktiviert?',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'checkbox',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>1,
						),
					),
					'u_loginMode'=>array(
						'label'=>'autorisierungsmodus',
						'description'=>'Bestimmt auf welche Art sich der Benutzer am System anmeldet.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
								'eval'=>'required',
								'items'=>array(
									'1'=>'MySQL-Datenbank',
									'2'=>'LDAP',
								),
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>1,
						),
					),
					'u_email'=>array(
						'label'=>'email',
						'description'=>'Email Adresse des Benutzers.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim,email,required',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>200,
						),
					),
					'u_sync_id'=>array(
						'label'=>'synchronisiert von',
						'description'=>'Der Synchronisations-Task der den Benutzer erstellt hat. Wird verwendet um sich eventuell ueber LDAP am System anzumelden.',
						'foreign_table'=>'usr_sync',
						'foreign_key'=>'uid',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
								'readOnly'=>true,
								'foreign_display'=>'[s_name], [s_domain]',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>200,
						),
					),
					'u_team_id'=>array(
						'label'=>'Support-Team',
						'description'=>'Support-Team dem der Benutzer angehört.',
						'foreign_table'=>'usr_team',
						'foreign_key'=>'uid',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
								'foreign_display'=>'[t_name]',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>10,
						),
					),
				),
				'forms'=>array(
					'default'=>array(
					),
					'new'=>array(
						'title'=>'Neuen Benutzer erfassen',
						'description'=>'',
						'fields'=>'u_vorname,u_nachname,u_strasse,u_plz,u_ort,u_lang,u_zeichen,u_tel,u_fax,u_email,u_loginName,u_password,u_locked,u_roll_id,u_created,u_location_id,u_department_id,u_team_id,u_loginMode',
					),
					'edit'=>array(
						'title'=>'Benutzer bearbeiten',
						'description'=>'',
						'fields'=>'u_vorname,u_nachname,u_strasse,u_plz,u_ort,u_lang,u_zeichen,u_tel,u_fax,u_email',
					),
					'edit_detail'=>array(
						'title'=>'Benutzer Details',
						'description'=>'',
						'fields'=>'u_vorname,u_nachname,u_strasse,u_plz,u_ort,u_lang,u_zeichen,u_tel,u_fax,u_email,u_loginName,u_sync_id,u_lastSync,u_loginMode,u_locked,u_roll_id,u_location_id,u_department_id,u_team_id',
					),
					'resetPassword'=>array(
						'title'=>'Passwort zurücksetzen.',
						'description'=>'Hier können Sie das Passwort des Benutzers neu definieren.',
						'fields'=>'u_password',
					),
				),
				'lists'=>array(
					'default'=>array(
						'title'=>'Benutzer',
						'fields'=>'u_vorname,u_nachname,u_strasse,u_plz,u_ort,u_zeichen,u_tel,u_email,u_loginName',
						'commonActions'=>'drop',
						'actions'=>array(
							'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
							'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
						),
					),
					'listAll'=>array(
						'title'=>'Benutzer',
						'description'=>'Hier werden alle erfassten Benutzer aufgelistet.',
						'fields'=>'u_vorname,u_nachname,u_zeichen,u_tel,u_email,u_loginName,u_location_id',
						'commonActions'=>'drop',
						'actions'=>array(
							'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
							'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
						),
					),
					'directRights_full'=>array(
						'title'=>'Direkt zugewiesene Rechte',
						'description'=>'Hier werden alle Berechtigungen auf Objekte, die dem Benutzer direkt zugewiesen wurden, angezeigt.',
						'fields'=>'r_shortName,r_name,up_permission',
						'mm_table'=>'usr_userhasperm',
						'mm_key'=>'up_user_id',
						'mm_foreign_key'=>'up_resource_id',
						'foreign_table'=>'usr_resource',
						'whereClause'=>'usr_user.uid=<action>uid</action>',
						'commonActions'=>'drop',
						'actions'=>array(
							'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'usr_userhasperm\',\'<foreign_uid>\',\'<action>uid</action>\',1)',
							'drop'=>'javascript:top.dropElement(\'usr_userhasperm\',\'<uid>\',\'<foreign_uid>\')',
						),
					),
					'directRights_read'=>array(
						'title'=>'Direkt zugewiesene Rechte',
						'description'=>'Hier werden alle Berechtigungen auf Objekte, die dem Benutzer direkt zugewiesen wurden, angezeigt.',
						'fields'=>'r_shortName,r_name,up_permission',
						'mm_table'=>'usr_userhasperm',
						'mm_key'=>'up_user_id',
						'mm_foreign_key'=>'up_resource_id',
						'foreign_table'=>'usr_resource',
						'whereClause'=>'usr_user.uid=<action>uid</action>',
					),
					'groups_full'=>array(
						'title'=>'Arbeitsgruppen',
						'description'=>'Zeigt alle Arbeitsgruppen, denen der Benutzer angehört.',
						'fields'=>'g_name',
						'mm_table'=>'usr_useringroup',
						'mm_key'=>'ug_user_id',
						'mm_foreign_key'=>'ug_group_id',
						'foreign_table'=>'usr_group',
						'whereClause'=>'usr_user.uid=<action>uid</action>',
						'commonActions'=>'drop',
						'actions'=>array(
							'drop'=>'javascript:top.dropElement(\'usr_useringroup\',\'<uid>\',\'<foreign_uid>\')',
						),
					),
					'groups_read'=>array(
						'title'=>'Arbeitsgruppen',
						'description'=>'Zeigt alle Arbeitsgruppen, denen der Benutzer angehört.',
						'fields'=>'g_name',
						'mm_table'=>'usr_useringroup',
						'mm_key'=>'ug_user_id',
						'mm_foreign_key'=>'ug_group_id',
						'foreign_table'=>'usr_group',
						'whereClause'=>'usr_user.uid=<action>uid</action>',
					),
					'ofTeam'=>array(
						'title'=>'Team-Mitglieder',
						'description'=>'Hier sind alle Mitlieder Ihres Support-Teams aufgelistet.',
						'fields'=>'u_vorname,u_nachname,u_tel,u_email,u_loginName',
						'whereClause'=>'u_team_id=<func>user::curusr_getTeamId()</func>',
						'actions'=>array(
							'assign'=>array(
								'action'=>"javascript:opener.top.action('exec_func','&func=ticket::assignToUser(<action>foreign_uid</action>,<uid>)'); window.close();",
								'icon'=>ICON_DIR.'ilist_actions_assignUser.gif',
								'description'=>'Ticket dem Benutzer zuweisen'
							),
						),
					),
				),
				'resources'=>array(
					'usr_user'=>array(
						'name'=>'Benutzer',
						'description'=>'Schliesst alle Benutzer ein, gilt jedoch nicht für die Elemente, welche dem Benutzer zugewiesen sind (Berechtigungen, Gruppen, Sessions)',
					),
					'usr_user_perms'=>array(
						'name'=>'Benutzer>>Berechtigungen',
						'description'=>'Beinhaltet die Aktivität Benutzern Berechtigungen zuzuweisen, diese Verbindung zu ändern und zu löschen.',
					),
					'usr_user_groups'=>array(
						'name'=>'Benutzer>>Gruppen',
						'description'=>'Beinhaltet die Aktivität Benutzern zu Gruppen zuzuordnen, diese Verbindung zu ändern und zu löschen.',
					),
					'usr_user_sessions'=>array(
						'name'=>'Benutzer>>Sessions',
						'description'=>'Sessions des Benutzers durchschauen.'
					),
				),
				'defaultRows'=>array(
					array(
						'u_vorname'=>'Superadministrator',
						'u_nachname'=>'Nicht löschen!!!',
						'u_loginName'=>'sa',
						'u_password'=>'827ccb0eea8a706c4c34a16891f84e7b',//Passwort: 12345
						'u_loginMode'=>user::LOGINMODE_DB,
						'u_active'=>1,
						'u_locked'=>0,
						'u_roll_id'=>'<func>user::getRollId("Superuser")</func>',
					),
				),
			);

			$TABLES['usr_session']=array(
				'ctrl'=>array(
					'isDbTable'=>true,
					'type'=>'normal',
				),
				'fields'=>array(
					'uid'=>array(
						'label'=>'id',
						'description'=>'Primärschlüssel-Feld der Tabelle',
						'dbconfig'=>Array(
							'type'=>'int',
							'length'=>11,
							'autoIncrement'=>1,
							'primaryKey'=>1
						),
					),
					's_ip'=>array(
						'label'=>'ip',
						'description'=>'IP des PC von dem aus die Session gestartet wurde.',
						'dbconfig'=>Array(
							'type'=>'varchar',
							'length'=>30,
						),
					),
					's_openTime'=>array(
						'label'=>'eröffnet',
						'description'=>'Eröffnungszeitpunkt der Session.',
						'dbconfig'=>array(
							'type'=>'timestamp',
						),
					),
					's_closeTime'=>array(
						'label'=>'geschlossen',
						'description'=>'Schliessungszeitpunkt der Session.',
						'dbconfig'=>array(
							'type'=>'timestamp',
						),
					),
					's_sid'=>array(
						'label'=>'Session-ID',
						'description'=>'Session-ID',
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>200,
						),
					),
					's_user'=>array(
						'label'=>'Benutzer',
						'description'=>'Benutzer, der die Session ausgelöst hat.',
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>100,
						),
					),
				),
				'lists'=>array(
					'default'=>array(
						'title'=>'Sessions',
						'description'=>'Alle Sessions, die bisher geloggt wurden.',
						'fields'=>'s_openTime,s_closeTime,s_ip,s_user',
					),
					'userSessions'=>array(
						'title'=>'Sessions',
						'description'=>'Alle Sessions, die von diesem Benutzer eröffnet wurden.',
						'fields'=>'s_openTime,s_closeTime,s_ip',
						'whereClause'=>'usr_session.s_user="<func>user::getLoginName(<action>uid</action>)</func>"',
					),
				),
				'resources'=>array(
					'usr_session'=>array(
						'name'=>'Sessions',
						'description'=>'Beinhaltet alle Session-Objekte (Diese Objekte können nicht bearbeitet werden. Also im höchsten Fall Lese-Berechtigung)',
					),
				),
			);

			/*$TABLES['usr_company']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
				),
				'fields'=>Array(
					'uid'=>array(
						'description'=>'Primärschlüssel-Feld der Tabelle',
						'dbconfig'=>Array(
							'type'=>'int',
							'length'=>11,
							'autoIncrement'=>1,
							'primaryKey'=>1
						),
					),
					'c_name'=>array(
						'label'=>'firmenname',
						'description'=>'Name der Firma.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>100,
						),
					),
				),
				'forms'=>Array(
					'default'=>array(
						'fields'=>'c_name',
					),
					'new'=>array(
						'fields'=>'c_name',
					),
					'show'=>array(
						'fields'=>'c_name',
					),
				),
			);*/

			$TABLES['usr_department']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
					'type'=>'normal',
				),
				'fields'=>Array(
					'uid'=>array(
						'label'=>'id',
						'description'=>'Primärschlüssel-Feld der Tabelle',
						'dbconfig'=>Array(
							'type'=>'int',
							'length'=>11,
							'autoIncrement'=>1,
							'primaryKey'=>1
						),
					),
					'd_name'=>array(
						'label'=>'name',
						'description'=>'Name der Abteilung.',
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
					/*'d_organisation_id'=>array(
						'label'=>'organisation',
						'description'=>'Organisation, zudem die Abteilung gehört.',
						'foreign_table'=>'usr_organisation',
						'foreign_key'=>'uid',
						'foreign_display'=>'o_name',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>11,
						),
					),*/
				),
				'forms'=>Array(
					'default'=>array(
					),
					'new'=>array(
						'title'=>'Neue Abteilung erfassen',
						'description'=>'',
						'fields'=>'d_name',
					),
					'edit'=>array(
						'title'=>'Abteilung bearbeiten',
						'description'=>'',
						'fields'=>'d_name',
					),
				),
				'lists'=>array(
					'listAll'=>array(
						'title'=>'Abteilungen',
						'description'=>'Listet alle erfassten Abteilungen der Firma auf.',
						'fields'=>'d_name',
						'commonActions'=>'drop',
						'actions'=>array(
							'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
							'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
						),
					),
				),
				'resources'=>array(
					'usr_department'=>array(
						'name'=>'Abteilungen',
						'description'=>'Schliesst alle Abteilungen ein.',
					),
				),
			);

			/*$TABLES['usr_organisation']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
				),
				'fields'=>Array(
					'uid'=>array(
						'label'=>'id',
						'description'=>'Primärschlüssel-Feld der Tabelle',
						'dbconfig'=>Array(
							'type'=>'int',
							'length'=>11,
							'autoIncrement'=>1,
							'primaryKey'=>1
						),
					),
					'o_name'=>array(
						'label'=>'name',
						'description'=>'Name der Organisation.',
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
				),
				'forms'=>Array(
					'default'=>array(
						'fields'=>'o_name',
					),
					'new'=>array(
						'fields'=>'o_name',
					),
					'edit'=>array(
						'fields'=>'o_name',
					),
					'show'=>array(
						'fields'=>'o_name',
					),
				),
			);*/

			/*$TABLES['usr_lang']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
					'type'=>'normal',
				),
				'fields'=>Array(
					'uid'=>array(
						'label'=>'id',
						'description'=>'Primärschlüssel-Feld der Tabelle',
						'dbconfig'=>Array(
							'type'=>'int',
							'length'=>11,
							'autoIncrement'=>1,
							'primaryKey'=>1
						),
					),
					'o_name'=>array(
						'label'=>'name',
						'description'=>'Name der Sprache.',
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
				),
				'forms'=>Array(
					'default'=>array(
						'fields'=>'l_name',
					),
					'new'=>array(
						'fields'=>'l_name',
					),
					'edit'=>array(
						'fields'=>'l_name',
					),
					'show'=>array(
						'fields'=>'l_name',
					),
				),
			);*/

			$TABLES['usr_location']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
					'type'=>'normal',
				),
				'fields'=>Array(
					'uid'=>array(
						'label'=>'id',
						'description'=>'Primärschlüssel-Feld der Tabelle',
						'dbconfig'=>Array(
							'type'=>'int',
							'length'=>11,
							'autoIncrement'=>1,
							'primaryKey'=>1
						),
					),
					'l_name'=>array(
						'label'=>'name',
						'description'=>'Name des Standorts.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim,required,unique',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>100,
						),
					),
					'l_strasse'=>array(
						'label'=>'strasse',
						'description'=>'Adresse des Standorts.',
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
					'l_plz'=>array(
						'label'=>'PLZ',
						'description'=>'Postleitzahl des Standorts.',
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
					'l_ort'=>array(
						'label'=>'ort',
						'description'=>'Ortschaft des Standorts.',
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
				),
				'forms'=>Array(
					'default'=>array(
					),
					'new'=>array(
						'title'=>'Neuen Standort erfassen',
						'description'=>'Hier können Sie einen Firmenstandort erfassen.',
						'fields'=>'l_name,l_strasse,l_plz,l_ort',
					),
					'edit'=>array(
						'title'=>'Standort bearbeiten',
						'description'=>'',
						'fields'=>'l_name,l_strasse,l_plz,l_ort',
					),
				),
				'lists'=>array(
					'listAll'=>array(
						'title'=>'Standorte',
						'description'=>'Listet alle erfassten Firmenstandorte auf.',
						'fields'=>'l_name,l_strasse,l_plz,l_ort',
						'commonActions'=>'drop',
						'actions'=>array(
							'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
							'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
						),
					),

				),
				'resources'=>array(
					'usr_location'=>array(
						'name'=>'Standorte',
						'description'=>'Schliesst alle Firmen-Standorte ein.',
					),
				),
			);

			$TABLES['usr_group']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
					'type'=>'normal',
				),
				'fields'=>Array(
					'uid'=>array(
						'description'=>'Primärschlüssel-Feld der Tabelle',
						'dbconfig'=>Array(
							'type'=>'int',
							'length'=>11,
							'autoIncrement'=>1,
							'primaryKey'=>1
						),
					),
					'g_name'=>array(
						'label'=>'name',
						'description'=>'Name des Gruppe.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'text',
								'eval'=>'trim,required,unique',
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>100,
						),
					),
					/*'g_company_id'=>array(
						'label'=>'unternehmen',
						'description'=>'Unternehmen, in dem die Gruppe besteht. (Wird nicht beachtet, wenn t_organisation_id nicht null ist)',
						'foreign_table'=>'usr_company',
						'foreign_key'=>'uid',
						'foreign_display'=>'c_name',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>11,
						),
					),*/
					/*'g_organisation_id'=>array(
						'label'=>'organisation',
						'description'=>'Organisation, in der die Gruppe besteht.',
						'foreign_table'=>'usr_organisation',
						'foreign_key'=>'uid',
						'foreign_display'=>'o_name',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>11,
						),
					),*/
					/*'g_isSupTeam'=>array(
						'label'=>'Ist Support-Team',
						'description'=>'Flag: Funktioniert die Gruppe als Support-Team?',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'checkbox',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>1,
						),
						'allocation'=>array(
							0=>'Nein',
							1=>'Ja',
						),
					),*/
					'g_created'=>array(
						'label'=>'erstellt',
						'description'=>'Erstelldatum',
						'formconfig'=>array(
							'new'=>array(
								'specialVal'=>'now',
							),
						),
						'dbconfig'=>array(
							'type'=>'timestamp',
							/*'default'=>'CURRENT_TIMESTAMP',*/
						),
					),
				),
				'forms'=>Array(
					'default'=>array(
					),
					'new'=>array(
						'title'=>'Neue Arbeitgruppe',
						'description'=>'',
						'fields'=>'g_name,g_created',
					),
					'edit'=>array(
						'title'=>'Arbeitsgruppe bearbeiten',
						'description'=>'',
						'fields'=>'g_name',
					),
				),
				'lists'=>array(
					'listAll'=>array(
						'title'=>'Arbeitsgruppen',
						'description'=>'Die Liste zeigt alle Arbeitsgruppen, die erfasst wurden.',						'fields'=>'g_name,g_created',
						'commonActions'=>'drop',
						'actions'=>array(
							'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
							'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
						),
					),
					'addToUser'=>array(
						'title'=>'Arbeitsgruppen',
						'description'=>'Wählen sie die Arbeitsgruppe aus, die Sie dem Benutzer zuweisen wollen.',
						'fields'=>'g_name',
						'actions'=>array(
							'add'=>"javascript:opener.top.action('exec_func','&func=user::userToGroup(<action>foreign_uid</action>,<uid>)'); opener.top.editElement('".table::MODE_EDIT."','usr_user','<action>foreign_uid</action>',''); window.close();",
						),
					),
					'directRights_full'=>array(
						'title'=>'Direkt zugewiesene Rechte',
						'description'=>'Berechtigungen, die der Gruppe zugwiesen wurden.',
						'fields'=>'r_shortName,r_name,gp_permission',
						'mm_table'=>'usr_grouphasperm',
						'mm_key'=>'gp_group_id',
						'mm_foreign_key'=>'gp_resource_id',
						'foreign_table'=>'usr_resource',
						'whereClause'=>'usr_group.uid=<action>uid</action>',
						'commonActions'=>'drop',
						'actions'=>array(
							'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'usr_grouphasperm\',\'<foreign_uid>\',\'<action>uid</action>\',1)',
							'drop'=>'javascript:top.dropElement(\'usr_grouphasperm\',\'<uid>\',\'<foreign_uid>\')',
						),
					),
					'directRights_read'=>array(
						'title'=>'Direkt zugewiesene Rechte',
						'description'=>'Berechtigungen, die der Gruppe zugwiesen wurden.',
						'fields'=>'r_shortName,r_name,gp_permission',
						'mm_table'=>'usr_grouphasperm',
						'mm_key'=>'gp_group_id',
						'mm_foreign_key'=>'gp_resource_id',
						'foreign_table'=>'usr_resource',
						'whereClause'=>'usr_group.uid=<action>uid</action>',
					),
				),
				'resources'=>array(
					'usr_group'=>array(
						'name'=>'Gruppen',
						'description'=>'Schliesst alle Gruppen ein.',
					),
					'usr_group_perms'=>array(
						'name'=>'Gruppen>>Berechtigungen',
						'description'=>'Beinhaltet die Aktivität Gruppen Berechtigungen zuzuweisen, diese Verbindung zu ändern und zu löschen.',
					),
				),
			);

			$TABLES['usr_resource']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
					'type'=>'normal',
				),
				'fields'=>Array(
					'uid'=>array(
						'label'=>'id',
						'description'=>'Primärschlüssel-Feld der Tabelle',
						'dbconfig'=>Array(
							'type'=>'int',
							'length'=>11,
							'autoIncrement'=>1,
							'primaryKey'=>1
						),
					),
					'r_name'=>array(
						'label'=>'Objekt',
						'description'=>'Name des Objekts.',
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
					'r_shortName'=>array(
						'label'=>'kurzname',
						'description'=>'Kurzname des Rechts.',
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
					'r_created'=>array(
						'label'=>'erstellt',
						'description'=>'Erstelldatum',
						'formconfig'=>array(
							'new'=>array(
								'specialVal'=>'now',
							),
						),
						'dbconfig'=>array(
							'type'=>'timestamp',
							/*'default'=>'CURRENT_TIMESTAMP',*/
						),
					),
					'r_description'=>array(
						'label'=>'beschreibung',
						'description'=>'Beschreibung der Ressource.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'textarea',
								'eval'=>'trim'
							),
						),
						'dbconfig'=>array(
							'type'=>'varchar',
							'length'=>400,
						),
					),
				),
				'lists'=>array(
					'addToUser'=>array(
						'title'=>'Ressourcen',
						'description'=>'Wählen Sie das Objekt aus, auf das Sie dem Benutzer eine Berechtigung geben wollen.',
						'fields'=>'r_shortName,r_name,r_description',
						'actions'=>array(
							'add'=>'javascript:opener.top.editElement(\''.table::MODE_NEW.'\',\'usr_userhasperm\',\'<uid>\',\'<action>foreign_uid</action>\',true);',
						),
					),
					'addToGroup'=>array(
						'title'=>'Ressourcen',
						'description'=>'Wählen Sie das Objekt aus, auf das Sie der Arbeitsgruppe eine Berechtigung geben wollen.',
						'fields'=>'r_shortName,r_name,r_description',
						'actions'=>array(
							'add'=>'javascript:opener.top.editElement(\''.table::MODE_NEW.'\',\'usr_grouphasperm\',\'<uid>\',\'<action>foreign_uid</action>\',true);',
						),
					),
					'addToRoll'=>array(
						'title'=>'Ressourcen',
						'description'=>'Wählen Sie das Objekt aus, auf das Sie der Rolle eine Berechtigung geben wollen.',
						'fields'=>'r_shortName,r_name,r_description',
						'actions'=>array(
							'add'=>'javascript:opener.top.editElement(\''.table::MODE_NEW.'\',\'usr_rollhasperm\',\'<uid>\',\'<action>foreign_uid</action>\',true);',
						),
					),
					'addToTeam'=>array(
						'title'=>'Ressourcen',
						'description'=>'Wählen Sie das Objekt aus, auf das Sie dem Support-Team eine Berechtigung geben wollen.',
						'fields'=>'r_shortName,r_name,r_description',
						'actions'=>array(
							'add'=>'javascript:opener.top.editElement(\''.table::MODE_NEW.'\',\'usr_teamhasperm\',\'<uid>\',\'<action>foreign_uid</action>\',true);',
						),
					),
					'listAll'=>array(
						'title'=>'Ressourcen',
						'description'=>'Alle erfassten Ressourcen.',
						'fields'=>'r_shortName,r_name,r_description',
					)
				),
			);

			$TABLES['usr_roll']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
					'type'=>'normal',
				),
				'fields'=>Array(
					'uid'=>array(
						'label'=>'id',
						'description'=>'Primärschlüssel-Feld der Tabelle',
						'dbconfig'=>Array(
							'type'=>'int',
							'length'=>11,
							'autoIncrement'=>1,
							'primaryKey'=>1
						),
					),
					'r_name'=>array(
						'label'=>'name',
						'description'=>'Name der Rolle.',
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
					/*'r_company_id'=>array(
						'label'=>'unternehmen',
						'description'=>'Unternehmen, zu dem die Rolle gehört.',
						'foreign_table'=>'usr_company',
						'foreign_key'=>'uid',
						'foreign_display'=>'c_name',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>11,
						),
					),*/
					/*'r_created'=>array(
						'label'=>'erstellt',
						'description'=>'Erstelldatum',
						'formconfig'=>array(
							'new'=>array(
								'specialVal'=>'now',
							),
						),
						'dbconfig'=>array(
							'type'=>'timestamp',
							'default'=>'CURRENT_TIMESTAMP',
						),
					),*/
					/*'r_isReadOnly'=>array(
						'label'=>'Readonly',
						'description'=>'Flag: Kann die Rolle nicht bearbeitet werden?',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'checkbox',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>1,
						),
					),*/
				),
				'forms'=>array(
					'default'=>array(
						'title'=>'Rolle bearbeiten',
						'description'=>'Sie können den Namen der Rolle nicht ändern. Jedoch können Sie die Berechtigungen der Rolle verwalten (wenn Sie über die nötigen Rechte verfügen).',
						'fields'=>'r_name',
					),
				),
				'lists'=>array(
					'directRights_full'=>array(
						'title'=>'Direkt zugewiesene Berechtigungen',
						'description'=>'Berechtigungen, die der Rolle direkt zugewiesen wurden.',
						'fields'=>'usr_resource.r_shortName,usr_resource.r_name,usr_rollhasperm.rp_permission',
						'mm_table'=>'usr_rollhasperm',
						'mm_key'=>'rp_roll_id',
						'mm_foreign_key'=>'rp_resource_id',
						'foreign_table'=>'usr_resource',
						'whereClause'=>'usr_roll.uid=<action>uid</action>',
						'commonActions'=>'drop',
						'actions'=>array(
							'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'usr_rollhasperm\',\'<foreign_uid>\',\'<action>uid</action>\',1)',
							'drop'=>'javascript:top.dropElement(\'usr_rollhasperm\',\'<uid>\',\'<foreign_uid>\')',
						),
					),
					'directRights_read'=>array(
						'title'=>'Direkt zugewiesene Berechtigungen',
						'description'=>'Berechtigungen, die der Rolle direkt zugewiesen wurden.',
						'fields'=>'r_shortName,usr_resource.r_name,rp_permission',
						'mm_table'=>'usr_rollhasperm',
						'mm_key'=>'rp_roll_id',
						'mm_foreign_key'=>'rp_resource_id',
						'foreign_table'=>'usr_resource',
						'whereClause'=>'usr_roll.uid=<action>uid</action>',
					),
					'listAll'=>array(
						'title'=>'Rollen',
						'fields'=>'r_name',
						'actions'=>array(
							'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
						),
					),
				),
				'defaultRows'=>array(
					array(
						'r_name'=>'Superuser',
					),
					array(
						'r_name'=>'Administrator',
					),
					array(
						'r_name'=>'Agent',
					),
					array(
						'r_name'=>'Kunde',
					),
				),
				'resources'=>array(
					'usr_roll'=>array(
						'name'=>'Rollen',
						'description'=>'Schliesst alle Rollen ein. (Auf dieses Objekt kann höchsten "Lesen" vergeben werden',
					),
					'usr_roll_perms'=>array(
						'name'=>'Rollen>>Berechtigungen',
						'description'=>'Beinhaltet die Aktivität Rollen Berechtigungen zuzuweisen, diese Verbindung zu ändern und zu löschen.',
					),
				),
			);

			$TABLES['usr_team']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
					'type'=>'normal',
				),
				'fields'=>Array(
					'uid'=>array(
						'description'=>'Primärschlüssel-Feld der Tabelle',
						'dbconfig'=>Array(
							'type'=>'int',
							'length'=>11,
							'autoIncrement'=>1,
							'primaryKey'=>1
						),
					),
					't_name'=>array(
						'label'=>'name',
						'description'=>'Name des Gruppe.',
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
					't_created'=>array(
						'label'=>'erstellt',
						'description'=>'Erstelldatum',
						'formconfig'=>array(
							'new'=>array(
								'specialVal'=>'now',
							),
						),
						'dbconfig'=>array(
							'type'=>'timestamp',
							/*'default'=>'CURRENT_TIMESTAMP',*/
						),
					),
					't_supportLevel'=>array(
						'label'=>'Support-Level',
						'description'=>'Flag: Support-Level.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
								'items'=>array(
									ticket::SUPPORTLEVEL_1=>'1-Level',
									ticket::SUPPORTLEVEL_2=>'2-Level',
									ticket::SUPPORTLEVEL_3=>'3-Level',
								),
								'eval'=>'required',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>2,
						),
						'allocation'=>array(
							ticket::SUPPORTLEVEL_1=>'1-Level',
							ticket::SUPPORTLEVEL_2=>'2-Level',
							ticket::SUPPORTLEVEL_3=>'3-Level',
						),
					),
				),
				'forms'=>Array(
					'default'=>array(
					),
					'new'=>array(
						'title'=>'Neues Support-Team erfassen',
						'fields'=>'t_name,t_created,t_supportLevel',
					),
					'edit'=>array(
					'title'=>'Support-Team bearbeten',
						'fields'=>'t_name,t_supportLevel',
					),
				),
				'lists'=>array(
					'listAll'=>array(
						'title'=>'Support-Teams',
						'description'=>'Listet alle erfassten Support-Teams auf.',
						'fields'=>'t_name,t_created,t_supportLevel',
						'commonActions'=>'drop',
						'actions'=>array(
							'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
							'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
						),
					),
					'directRights_full'=>array(
						'title'=>'Direkt zugewiesene Berechtigungen',
						'description'=>'Berechtigungen, die dem Support-Team direkt zugewiesen wurden.',
						'fields'=>'r_shortName,r_name,tp_permission',
						'mm_table'=>'usr_teamhasperm',
						'mm_key'=>'tp_team_id',
						'mm_foreign_key'=>'tp_resource_id',
						'foreign_table'=>'usr_resource',
						'whereClause'=>'usr_team.uid=<action>uid</action>',
						'commonActions'=>'drop',
						'actions'=>array(
							'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'usr_teamhasperm\',\'<foreign_uid>\',\'<action>uid</action>\',1)',
							'drop'=>'javascript:top.dropElement(\'usr_teamhasperm\',\'<uid>\',\'<foreign_uid>\')',
						),
					),
					'directRights_read'=>array(
						'title'=>'Direkt zugewiesene Berechtigungen',
						'description'=>'Berechtigungen, die dem Support-Team direkt zugewiesen wurden.',
						'fields'=>'r_shortName,r_name,tp_permission',
						'mm_table'=>'usr_teamhasperm',
						'mm_key'=>'tp_team_id',
						'mm_foreign_key'=>'tp_resource_id',
						'foreign_table'=>'usr_resource',
						'whereClause'=>'usr_team.uid=<action>uid</action>',
					),
					'1-Level-Teams'=>array(
						'title'=>'1-Level Support-Teams',
						'description'=>'Wählen Sie das Team aus, an welches Sie das Ticket weiterleiten möchten.',
						'fields'=>'t_name,t_created',
						'whereClause'=>'usr_team.t_supportLevel='.ticket::SUPPORTLEVEL_3,
						'actions'=>array(
							'assign'=>array(
								'icon'=>ICON_DIR.'ilist_actions_assignTeam.gif',
								'action'=>"javascript:opener.top.action('exec_func','&func=ticket::assignToTeam(<action>foreign_uid</action>,<uid>)'); opener.top.content.location.href='edit_ticket.php'; window.close();",
								'description'=>'Ticket zuweisen',
							),
						),
					),
					'2-Level-Teams'=>array(
						'title'=>'2-Level Support-Teams',
						'description'=>'Wählen Sie das Team aus, an welches Sie das Ticket weiterleiten möchten.',
						'fields'=>'t_name,t_created',
						'whereClause'=>'usr_team.t_supportLevel='.ticket::SUPPORTLEVEL_2,
						'actions'=>array(
							'assign'=>array(
								'icon'=>ICON_DIR.'ilist_actions_assignTeam.gif',
								'action'=>"opener.top.action('exec_func','&func=ticket::assignToTeam(<action>foreign_uid</action>,<uid>)'); opener.top.content.location.href='edit_ticket.php'; window.close();",
								'description'=>'Ticket zuweisen',
							),
						),
					),
					'3-Level-Teams'=>array(
						'title'=>'3-Level Support-Teams',
						'description'=>'Wählen Sie das Team aus, an welches Sie das Ticket weiterleiten möchten.',
						'fields'=>'t_name,t_created',
						'whereClause'=>'usr_team.t_supportLevel='.ticket::SUPPORTLEVEL_3,
						'actions'=>array(
							'assign'=>array(
								'icon'=>ICON_DIR.'ilist_actions_assignTeam.gif',
								'action'=>"javascript:opener.top.action('exec_func','&func=ticket::assignToTeam(<action>foreign_uid</action>,<uid>)'); opener.top.content.location.href='edit_ticket.php'; window.close();",
								'description'=>'Ticket zuweisen',
							),
						),
					),
				),
				'resources'=>array(
					'usr_team'=>array(
						'name'=>'Support-Teams',
						'description'=>'Schliesst alle Support-Teams ein.',
					),
					'usr_team_perms'=>array(
						'name'=>'Gruppen>>Berechtigungen',
						'description'=>'Beinhaltet die Aktivität Support-Teams Berechtigungen zuzuweisen, diese Verbindung zu ändern und zu löschen.',
					),
				),
			);

			$TABLES['usr_rollhasperm']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
					'type'=>'intermediate',
				),
				'fields'=>Array(
					'rp_roll_id'=>array(
						'label'=>'rolle',
						'description'=>'Benutzerrolle',
						'foreign_table'=>'usr_roll',
						'foreign_key'=>'uid',
						'foreign_display'=>'[r_name]',
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
					'rp_resource_id'=>array(
						'label'=>'ressource',
						'description'=>'Ressource',
						'foreign_table'=>'usr_resource',
						'foreign_key'=>'uid',
						'foreign_display'=>'[r_name]',
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
					'rp_permission'=>array(
						'label'=>'berechtigung',
						'description'=>'Flag: Berechtigung.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
								'items'=>array(
									'1'=>'Kein Zugriff',
									'2'=>'Lesen',
									'3'=>'Vollzugriff',
								),
								'eval'=>'required',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>2,
						),
						'allocation'=>array(
							'1'=>'Kein Zugriff',
							'2'=>'Lesen',
							'3'=>'Vollzugriff',
						),
					),
				),
				'defaultRows'=>array(

					//Rechte für den Superuser (alle Berechtigungen)
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user_perms")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user_groups")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user_sessions")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_group")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_group_perms")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_team")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_team_perms")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_session")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_location")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_sync")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_sync_exec")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_roll")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_roll_perms")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_department")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket_ofTeam")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket_own")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("contract")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket_new")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),

					//Rechte für den Administrator

					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user_perms")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user_groups")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user_sessions")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_group")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_group_perms")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_team")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_team_perms")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_session")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_location")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_sync")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_sync_exec")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_roll")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_roll_perms")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_department")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket_ofTeam")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket_own")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("contract")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket_new")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),

					//Rechte für den Agenten

					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user_perms")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user_groups")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user_sessions")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_group")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_group_perms")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_team")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_team_perms")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_session")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_location")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_sync")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_sync_exec")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_roll")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_roll_perms")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_department")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket_ofTeam")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket_own")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("contract")</func>',
						'rp_permission'=>user::PERMISSION_READ,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket_new")</func>',
						'rp_permission'=>user::PERMISSION_FULL,
					),

					//Rechte für den Kunden.


					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user_perms")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user_groups")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_user_sessions")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_group")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_group_perms")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_team")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_team_perms")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_session")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_location")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_sync")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_sync_exec")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_roll")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_roll_perms")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("usr_department")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket_ofTeam")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket_own")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("contract")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
					array(
						'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
						'rp_resource_id'=>'<func>user::getResourceId("ticket_new")</func>',
						'rp_permission'=>user::PERMISSION_NO,
					),
				),
				'forms'=>array(
					'default'=>array(
						'title'=>'Berechtigungsstufe',
						'description'=>'Vergeben Sie eine Berechtigungsstufe auf das gewählte Objekt.',
						'fields'=>'rp_permission,rp_resource_id=<action>uid</action>,rp_roll_id=<action>foreign_uid</action>',
						'where'=>'rp_resource_id=<action>uid</action> AND rp_roll_id=<action>foreign_uid</action>',
					),
				),
			);

			$TABLES['usr_grouphasperm']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
					'type'=>'intermediate',
				),
				'fields'=>Array(
					'gp_group_id'=>array(
						'label'=>'gruppe',
						'description'=>'Gruppe',
						'foreign_table'=>'usr_group',
						'foreign_key'=>'uid',
						'foreign_display'=>'[g_name]',
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
					'gp_resource_id'=>array(
						'label'=>'recht',
						'description'=>'recht',
						'foreign_table'=>'usr_resource',
						'foreign_key'=>'uid',
						'foreign_display'=>'[r_name]',
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
					'gp_permission'=>array(
						'label'=>'berechtigung',
						'description'=>'Flag: Berechtigung.',
						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
								'items'=>array(
									'1'=>'Kein Zugriff',
									'2'=>'Lesen',
									'3'=>'Vollzugriff',
								),
								'eval'=>'required',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>2,
						),
						'allocation'=>array(
							'1'=>'Kein Zugriff',
							'2'=>'Lesen',
							'3'=>'Vollzugriff',
						),
					),
				),
				'forms'=>array(
					'default'=>array(
						'title'=>'Berechtigungsstufe',
						'description'=>'Vergeben Sie eine Berechtigungsstufe auf das gewählte Objekt.',
						'fields'=>'gp_permission,gp_resource_id=<action>uid</action>,gp_group_id=<action>foreign_uid</action>',
						'where'=>'gp_resource_id=<action>uid</action> AND gp_group_id=<action>foreign_uid</action>',
					),
				),
			);

			$TABLES['usr_userhasperm']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
					'type'=>'intermediate',
				),
				'fields'=>Array(
					'up_user_id'=>array(
						'label'=>'benutzer',
						'description'=>'Benutzer',
						'foreign_table'=>'usr_user',
						'foreign_key'=>'uid',
						'foreign_display'=>'[u_vorname] [u_name]',
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
					'up_resource_id'=>array(
						'label'=>'recht',
						'description'=>'recht',
						'foreign_table'=>'usr_resource',
						'foreign_key'=>'uid',
						'foreign_display'=>'[r_name]',
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
					'up_permission'=>array(
						'label'=>'berechtigung',
						'description'=>'Flag: Berechtigung.',

						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
								'items'=>array(
									'1'=>'Kein Zugriff',
									'2'=>'Lesen',
									'3'=>'Vollzugriff',
								),
								'eval'=>'required',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>2,
						),
						'allocation'=>array(
							'1'=>'Kein Zugriff',
							'2'=>'Lesen',
							'3'=>'Vollzugriff',
						),
					),
				),
				'forms'=>array(
					'default'=>array(
						'title'=>'Berechtigungsstufe',
						'description'=>'Vergeben Sie eine Berechtigungsstufe auf das gewählte Objekt.',
						'fields'=>'up_permission,up_resource_id=<action>uid</action>,up_user_id=<action>foreign_uid</action>',
						'where'=>'up_resource_id=<action>uid</action> AND up_user_id=<action>foreign_uid</action>',
                        'execOnSave'=>'<script language="JavaScript" type="text/JavaScript">top.window.close();</script>',
					),
				),
			);

			$TABLES['usr_teamhasperm']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
					'type'=>'intermediate',
				),
				'fields'=>Array(
					'tp_team_id'=>array(
						'label'=>'Team',
						'description'=>'Team',
						'foreign_table'=>'usr_team',
						'foreign_key'=>'uid',
						'foreign_display'=>'[t_name], [t_supportLevel]',
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
					'tp_resource_id'=>array(
						'label'=>'recht',
						'description'=>'recht',
						'foreign_table'=>'usr_resource',
						'foreign_key'=>'uid',
						'foreign_display'=>'[r_name]',
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
					'tp_permission'=>array(
						'label'=>'berechtigung',
						'description'=>'Flag: Berechtigung.',

						'formconfig'=>array(
							'all'=>array(
								'type'=>'select',
								'items'=>array(
									'1'=>'Kein Zugriff',
									'2'=>'Lesen',
									'3'=>'Vollzugriff',
								),
								'eval'=>'required',
							),
						),
						'dbconfig'=>array(
							'type'=>'int',
							'length'=>2,
						),
						'allocation'=>array(
							'1'=>'Kein Zugriff',
							'2'=>'Lesen',
							'3'=>'Vollzugriff',
						),
					),
				),
				'forms'=>array(
					'default'=>array(
						'title'=>'Berechtigungsstufe',
						'description'=>'Vergeben Sie eine Berechtigungsstufe auf das gewählte Objekt.',
						'fields'=>'tp_permission,tp_resource_id=<action>uid</action>,tp_team_id=<action>foreign_uid</action>',
						'where'=>'tp_resource_id=<action>uid</action> AND tp_team_id=<action>foreign_uid</action>',
					),
				),
			);

			$TABLES['usr_useringroup']=Array(
				'ctrl'=>Array(
					'isDbTable'=>true,
					'type'=>'intermediate',
				),
				'fields'=>Array(
					'ug_user_id'=>array(
						'label'=>'benutzer',
						'description'=>'Benutzer',
						'foreign_table'=>'usr_user',
						'foreign_key'=>'uid',
						'foreign_display'=>'[u_vorname] [u_name]',
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
					'ug_group_id'=>array(
						'label'=>'gruppe',
						'description'=>'gruppe',
						'foreign_table'=>'usr_group',
						'foreign_key'=>'uid',
						'foreign_display'=>'[g_name]',
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
            


class user {
	Const LOGINMODE_DB=1;
	Const LOGINMODE_LDAP=2;

	Const PERMISSION_NO=1;
	Const PERMISSION_READ=2;
	Const PERMISSION_FULL=3;
    
    Const ROLL_SUPERUSER=1;
    Const ROLL_ADMINISTRATOR=2;
    Const ROLL_AGENT=3;
    Const ROLL_KUNDE=4;

 	public $RIGHTS = Array("Rechte"=>"rights","Benutzer"=>"user");

 	/**
 	 * Verändert das Flag s_active eines Synchronistions-Tasks.
 	 *
 	 * @param int $sync_id ID des Synchornisations-Tasks
 	 * @param boolean $active
 	 * @return resource pointer
 	 */
 	function sync_setActive($sync_id,$active=true){
		Global $db;
		$active=$active?'1':'0';
		$values=array(
			's_active'=>$active,
		);

		return $db->exec_UPDATEquery("usr_sync","uid=".$sync_id,$values);
	}

	/**
	 * Führt die angegebene Synchronisation mit einem LDAP-Server durch.
	 *
	 * @param int $uid ID des Synchronisations-Tasks
	 */
	function sync_synchronize($uid){
		global $db;

		$sync=user::getSync($uid);
		$sync['s_filter']="(&(!(objectClass=computer))(objectClass=Person))";

		$data=user::getLDAPDAta($sync);
		if(is_array($data)) {
			foreach($data as $object) {

				$values=array(
					'u_vorname'=>utf8_decode($object['givenname'][0]),
					'u_nachname'=>utf8_decode($object['sn'][0]),
					'u_loginName'=>$object['cn'][0],
					'u_email'=>$object['mail'][0],
					'u_tel'=>$object['telephonenumber'][0],
					'u_zeichen'=>strtoupper(div::var_replaceUml(substr(utf8_decode($object['sn'][0]),0,2).substr(utf8_decode($object['givenname'][0]),0,1))),
					'u_lastSync'=>div::date_gerToSQL(div::date_getDateTime()),
					'u_sync_id'=>$uid,
				);

				$result=$db->exec_SELECTquery("*","usr_user","u_loginname='".$values['u_loginName']."'");

				if($db->sql_num_rows($result)) {
					$user=$db->sql_fetch_assoc($result);
					if($user['u_synchronize']) {
						$db->exec_UPDATEquery("usr_user","u_loginname='".$values['u_loginName']."'",$values);
					}
				} else {
					$values['u_loginMode']=user::LOGINMODE_LDAP;
					$values['u_synchronize']=1;
					$values['u_active']=1;
					$values['u_created']=div::date_gerToSQL(div::date_getDateTime());
					$values['u_roll_id']=user::getRollId("Kunde");
					$db->exec_INSERTquery("usr_user",$values);
				}

			}
			raiseMessage(MESSAGE_INFO,"Synchronisierung mit ".$sync['s_domain']." erfolgreich abgeschlossen.");
		} else {
			raiseMessage(MESSAGE_ERROR,"Synchronisierung mit ".$sync['s_domain']." fehlgeschlagen.");
		}
	}


	/**
	 * Baut eine Verbindung zu einem LDAP-Server auf und liest anhand der übergebenen Konfiguration die Daten aus und gibt diese zurück.
	 *
	 * @param array $config Konfigurationsarray
	 * @return array Empfangene Daten
	 */
	function getLDAPData($config) {
		global $db;

		//echo $db->view_array($config);
		if(function_exists("ldap_connect")) {
			if($conn=@ldap_connect($config['s_host'])) {

				ldap_set_option($conn,LDAP_OPT_PROTOCOL_VERSION,3);
				ldap_set_option($conn,LDAP_OPT_REFERRALS,0);

				$domain=explode(".",$config['s_domain']);

				if(@ldap_bind($conn,$domain[0]."\\".$config['s_username'],$config['s_password'])) {
					raiseMessage(MESSAGE_INFO,"Erfolgreiche Autentifizierung am LDAP Server: ".$config['s_host']);
					$config['s_filter']=$config['s_filter']?$config['s_filter']:"(cn=*)";

					if($config['s_ou']) {
						$baseDN.="OU=".$config['s_ou'].",";
					}
					foreach($domain as $domainPart) {
						$baseDN.="DC=".$domainPart.",";
					}
					$baseDN=substr($baseDN,0,(strlen($baseDN)-1));

					$result=ldap_search($conn,$baseDN,$config['s_filter']);
					$data=ldap_get_entries($conn,$result);
					unset($data['count']);
				} else {
					raiseMessage(MESSAGE_ERROR,"Autentifizierung am LDAP-Server ".$config['s_host']." fehlgeschlagen.");
				}
			} else {
				raiseMessage(MESSAGE_ERROR,"Es konnte keine Verbindung zum LDAP-Server ".$config['s_host']." hergestellt werden.");
			}
		} else {
			raiseMessage(MESSAGE_ERROR,"Auf dem Webserver sind die LDAP-Funktionen deaktiviert oder nicht installiert");
		}

		return $data;
	}


	/**
	 * Liest den angegebenen Datensatz aus der Datenbanktabelle usr_sync aus.
	 *
	 * @param int $uid ID des Synchronisations-Tasks
	 * @return array Empfangene Daten
	 */
	function getSync($uid) {
		global $db;
		$data=$db->exec_SELECTgetRows("*","usr_sync","uid=".$uid);
		return $data[0];
	}

	/**
	 * Liest die ID des angemeldeten Users aus dem $_SESSION-Array und gibt sie zurück.
	 *
	 * @return int ID des angemeldeten Benutzers
	 */
	function curusr_getId() {
		return $_SESSION['user']['uid'];
	}

	/**
	 * Liest die ID des Support-Teams, dem der angemeldete Benutzer angehört aus dem $_SESSION-Array und gibt Sie zurück,
	 *
	 * @return int ID des Support-Teams, dem der angemeldete Benutzer angehört
	 */
	function curusr_getTeamId() {
		if($_SESSION['user']['u_team_id']) {
			return $_SESSION['user']['u_team_id'];
		} else {
			return 0;
		}
	}

	/**
	 * Öffnet eine Session für den angegebenen Benutzer.
	 *
	 * @param int $uid ID eines Benutzers
	 */
	function session_open($uid=0){
		Global $db;

		$user=$db->exec_SELECTgetRows("u_loginName","usr_user","uid=".$uid);

		$date=div::date_gerToSQL(div::date_getDateTime());
		$values=array(
			's_ip'=>$_SERVER['REMOTE_ADDR']." (".$_ENV['COMPUTERNAME'].")",
			's_openTime'=>$date,
			's_user'=>$user[0]["u_loginName"],
			's_sid'=>session_id(),
		);
		$db->exec_INSERTquery("usr_session",$values);
		$_SESSION['session_id']=$db->sql_insert_id();

		$values=array(
				'u_lastLogin'=>$date,
		);
		$db->UPDATEquery("usr_user","uid=".$_SESSION['user']['uid'],$values);
	}


	/**
	 * Schliesst die geöffnete Session.
	 *
	 */
	function session_close(){
		Global $db;
		$values=array(
			's_closeTime'=>div::date_gerToSQL(div::date_getDateTime()),
		);
		$db->exec_UPDATEquery("usr_session","uid=".$_SESSION['session_id'],$values);
	}


	/**
	 * Authentifiziert einen Benutzer, entweder über die MySQL-Datenbank oder über einen LDAP-Server.
	 *
	 * @param string $username Benutzername
	 * @param string $password Passwort (unverschlüsselt)
	 * @return boolean Authentifizierung erfolgreich?
	 */
	function authUser($username,$password){
		global $db;

		$user=$db->exec_SELECTgetRows("*","usr_user","u_loginName='".$username."' AND (u_locked=0 OR u_locked=NULL)");
		$_SESSION['user']=$user[0];

		if($_SESSION['user']["u_loginMode"]==user::LOGINMODE_DB) {
            
			$result=$db->exec_SELECTquery("*","usr_user","u_loginName='".$username."' AND u_password='".md5($password)."'");
			
            if($db->sql_num_rows($result)!=0) {
                
				$authorized=true;
			}
		} elseif($_SESSION['user']['u_loginMode']==user::LOGINMODE_LDAP) {
			if(user::authLDAP($_SESSION['user'],$password)) {
				$authorized=true;
			} else {
				$authorized=false;
			}
		}

		if($authorized) {
			$_SESSION['user']['loggedIn']=true;
			$_SESSION['permissions']=user::curusr_getPermissions();
			user::session_open($_SESSION['user']['uid']);
		} else {
			//Fehler: Falsches Passwort.
		}
		return $authorized;
	}


	/**
	 * Authentifizierung eines Benutzers über einen LDAP-Server.
	 *
	 * @param string $user Benutzername
	 * @param string $password Passwort (unverschlüsselt)
	 * @return boolean Authentifizierung erfolgreich?
	 */
	function authLDAP($user,$password) {
		global $db;

		$sync=user::getSync($user['u_sync_id']);
		if($conn=ldap_connect($sync['s_host'])) {

			ldap_set_option($conn,LDAP_OPT_PROTOCOL_VERSION,3);
			ldap_set_option($conn,LDAP_OPT_REFERRALS,0);

			$domain=explode(".",$sync['s_domain']);

			if(@ldap_bind($conn,$domain[0]."\\".$user['u_loginName'],$password)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	/**
	 * Loggt den angemeldeten Benutzer aus. Dabei wird die Session geschlossen und entsprechende Flags gesetzt.
	 *
	 */
	function logoutUser() {
		global $db;
		if(user::isLoggedIn()) {
			$_SESSION['user']['loggedIn']=false;
			user::session_close($_SESSION['user']['uid']);
			/*div::http_redirect("login.php");*/
		}
	}

	/**
	 * Ermittelt ob ein Benutzer authentifiziert ist.
	 *
	 * @return boolean Ist ein Benutzer authentifiziert?
	 */
	function isLoggedIn() {
		if(is_array($_SESSION['user'])) {
			return $_SESSION['user']['loggedIn'];
		} else {
			return false;
		}
	}

	/**
	 * Liefert alle Berechtigungen des angemeldeten Benutzers als Array.
	 *
	 * Der Aufbau des Arrays:
	 * [Ressourcenname]=>[Berechtigungsstufe]
	 *
	 * @return array Berechtigungen
	 */
	function curusr_getPermissions() {
		global $db;

		$permissions=user::curusr_getDirectPerms();
		user::comparePermissions($permissions,user::curusr_getTeamPerms());
        user::comparePermissions($permissions,user::curusr_getGroupPerms());
		user::comparePermissions($permissions,user::curusr_getRollPerms());
        
		return $permissions;
	}

	/**
	 * Führt zwei Arrays, die Berechtigungen zusammen und speichert das Ergebnis im ersten Array.
	 * Dabei wird beachtet, dass die Berechtigungen im 1. Array kräftiger sind als die des zweiten.
	 * Das heisst, wenn 2 verschiedene Berechtigungen auf dieselbe Ressource vergeben werden, wird nur die im 1. Array beachtet.
	 *
	 * @param array $upperPerms starke Berechtigungen
	 * @param array $lowerPerms schwache Berechtigungen
	 */
	function comparePermissions(&$upperPerms,$lowerPerms) {
		global $db;
		foreach($lowerPerms as $lowerPerm) {
			$exists=false;
			foreach($upperPerms as $upperPerm) {
				if($upperPerm[0]==$lowerPerm[0]) {
					$exists=true;
				}
			}
			if(!$exists) {
				$upperPerms[]=$lowerPerm;
			}
		}
	}

	/**
	 * Liefert die Berechtigungen aller Gruppen, denen der angemeldete Benutzer angehört.
	 *
	 * @return array Berechtigungen der Gruppen
	 */
	function curusr_getGroupPerms() {
		global $db;

		$query="SELECT usr_resource.r_shortName, usr_grouphasperm.gp_permission FROM usr_useringroup LEFT JOIN (usr_grouphasperm,usr_resource) ON (usr_useringroup.ug_group_id=usr_grouphasperm.gp_group_id AND usr_grouphasperm.gp_resource_id=usr_resource.uid) WHERE usr_useringroup.ug_user_id=".user::curusr_getId();
		$result=$db->sql_query($query);
		$data=array();
		while($row=$db->sql_fetch_row($result)) {
				$data[]=$row;
		}

		return $data;
	}

	/**
	 * Liefert die Berechtigungen des Support-Teams, dem der angemeldete Benutzer angehört.
	 *
	 * @return array Berechtigungen des Teams
	 */
	function curusr_getTeamPerms() {
		global $db;

		if($_SESSION['user']['u_team_id']) {
			$query="SELECT usr_resource.r_shortName, usr_teamhasperm.tp_permission FROM usr_resource, usr_teamhasperm WHERE usr_resource.uid=usr_teamhasperm.tp_resource_id AND usr_teamhasperm.tp_team_id=".$_SESSION['user']['u_team_id'];
			$result=$db->sql_query($query);
			$data=array();
			while($row=$db->sql_fetch_row($result)) {
				$data[]=$row;
			}

			return $data;
		} else {
			return array();
		}
	}

	/**
	 * Liefert die Berechtigungen, die dem angemeldeten Benutzer direkt zugewiesen sind.
	 *
	 * @return array Berechtigungen des Benutzers (direkt)
	 */
	function curusr_getDirectPerms() {
		global $db;

		if($_SESSION['user']['uid']) {
			$query="SELECT usr_resource.r_shortName, usr_userhasperm.up_permission FROM usr_resource, usr_userhasperm WHERE usr_resource.uid=usr_userhasperm.up_resource_id AND usr_userhasperm.up_user_id=".$_SESSION['user']['uid'];
			$result=$db->sql_query($query);
			$data=array();
			while($row=$db->sql_fetch_row($result)) {
				$data[]=$row;
			}

			return $data;
		} else {
			return array();
		}
	}

	/**
	 * Liefert die Berechtigungen der Rolle, dem der angemeldete Benutzer angehört.
	 *
	 * @return array Berechtigungen der Rolle
	 */
	function curusr_getRollPerms() {
		global $db;

		if($_SESSION['user']['u_roll_id']) {
			$query="SELECT usr_resource.r_shortName, usr_rollhasperm.rp_permission FROM usr_resource, usr_rollhasperm WHERE usr_resource.uid=usr_rollhasperm.rp_resource_id AND usr_rollhasperm.rp_roll_id=".$_SESSION['user']['u_roll_id'];
			$result=$db->sql_query($query);
			$data=array();
			while($row=$db->sql_fetch_row($result)) {
				$data[]=$row;
			}
			//echo "getRollPerms:";
			//echo $db->view_array($data);

			return $data;
		} else {
			//echo "Nix-getRollPerms:";
			return array();
		}
	}

	/**
	 * Liest die ID einer Rolle aus der Datenbank und gibt diese zurück
	 *
	 * @param string $r_name Name der Rolle
	 * @return int ID der Rolle
	 */
	function getRollId($r_name) {
		global $db;

		table::updateTable("usr_roll");
		$result=$db->exec_SELECTquery("uid","usr_roll","r_name='".$r_name."'");
		if($db->sql_num_rows($result)) {
			$row=$db->sql_fetch_row($result);
			return $row[0];
		} else {
			return false;
		}
	}


	/**
	 * Liefert die ID einer Ressource bei der angabe des Kurznamens
	 *
	 * @param string $r_shortName Kurzname der Ressource
	 * @return int ID der Ressource
	 */
	function getResourceId($r_shortName) {
		global $db;

		//table::updateTable("usr_resource");
		$result=$db->exec_SELECTquery("uid","usr_resource","r_shortName='".$r_shortName."'");
        //echo "test:".$db->view_array($result);
		if($db->sql_num_rows($result)) {
			$row=$db->sql_fetch_row($result);
			return $row[0];
		} else {
            echo "Fehler:".$r_shortName.'<br>';
			return false;
		}
	}

	/**
	 * Liefert den Login-Namen (Benutzernamen) des angemeldeten Benutzers.
	 *
	 * @return string Login-Name
	 */
	function curusr_getLoginName() {
		if(user::isLoggedIn()) {
			return $_SESSION['user']['u_loginName'];
		}
	}

	/**
	 * Liefert die Berechtigungsstufe des angemeldeten Benutzers auf das angegebene Objekt (Ressource).
	 *
	 * @param  string $objectName Name des Objekts (Ressource)
	 * @return int Berechtigungsstufe (1-3)
	 */
	function curusr_getPermission($objectName) {
		global $db;
		if(user::isLoggedIn()) {
            if(user::curusr_getRollId()==user::ROLL_SUPERUSER) return user::PERMISSION_FULL;
			foreach($_SESSION['permissions'] as $perm) {
				if($objectName==$perm[0]) {
					$permission=$perm[1];
                    break;
				}
			}
			if(!$permission) {
				$permission=user::PERMISSION_NO;
			}
			return $permission;
		} else {
			return user::PERMISSION_NO;
		}
	}

	/**
	 * Liefert die ID der Rolle, der der angemeldete Benutzer angehört.
	 *
	 * @return int ID einer Rolle
	 */
	function curusr_getRollId() {
		if(user::isLoggedIn()) {
			return $_SESSION['user']['u_roll_id'];
		}
	}

	function getLoginName($uid) {
		global $db;

		$result=$db->exec_SELECTquery("u_loginName","usr_user","uid=".$uid);
		if($db->sql_num_rows($result)) {
			$row=$db->sql_fetch_row($result);
			return $row[0];
		} else {
			return false;
		}
	}

	/**
	 * Löscht einen Benutzer aus der Datenbank mit allen seinen Verbindungen zu anderen Tabellen.
	 *
	 * @param int $user_id ID des Benutzers
	 */
	function delete_user($user_id) {
		global $db;

		user::delete_userInGroup($user_id);
		user::delete_userHasPerm($user_id);

		$db->exec_DELETEquery("usr_user","uid=".$user_id);
	}

	/**
	 * Löscht die Verbindung zwischen einem Benutzer und einer Arbeitsgruppe aus der Datenbank.
	 *
	 * @param int $user_id ID des Benutzers
	 * @param int $group_id ID der Arbeitsgruppe
	 */
	function delete_userInGroup($user_id,$group_id) {
		global $db;

		$where="ug_user_id=".$user_id.($group_id?" AND ug_group_id=".$group_id:"");
		$db->exec_DELETEquery("usr_useringroup",$where);
	}

	/**
	 * Löscht eine Berechtigung eines Benutzers auf eine Ressource.
	 *
	 * @param int $user_id ID des Benutzers
	 * @param int $resource_id ID der Ressource (Objekt)
	 */
	function delete_userHasPerm($user_id,$resource_id=null) {
		global $db;

		$where="up_user_id=".$user_id.($resource_id?" AND up_resource_id=".$resource_id:"");
		$db->exec_DELETEquery("usr_userhasperm",$where);
	}

	/**
	 * Löscht eine Arbeitsgruppe aus der Datenbank mit allen ihrer Verbindungen zu anderen Tabellen.
	 *
	 * @param int $group_id ID der Arbeitsgruppe
	 */
	function delete_group($group_id) {
		global $db;

		$db->exec_DELETEquery("usr_useringroup","ug_group_id=".$group_id);
		$db->exec_DELETEquery("usr_grouphasperm","gp_group_id=".$group_id);

		$db->exec_DELETEquery("usr_group","uid=".$group_id);
	}

	/**
	 * Löscht eine Berechtigung einer Arbeitsgruppe auf eine Ressource.
	 *
	 * @param int $group_id ID der Arabeitsgruppe
	 * @param int $resource_id ID der Ressource (Objekt)
	 */
	function delete_groupHasPerm($group_id,$resource_id=null) {
		global $db;

		$where="gp_group_id=".$group_id.($resource_id?" AND gp_resource_id=".$resource_id:"");
		$db->exec_DELETEquery("usr_grouphasperm",$where);
	}

	/**
	 * Löscht ein Support-Team aus der Datenbank mit allen seinen Verbindungen zu anderen Tabellen.
	 *
	 * @param int $team_id ID des Support-Teams
	 */
	function delete_team($team_id) {
		global $db;

		$values=array(
			'u_team_id'=>'null',
		);
		$db->exec_UPDATEquery("usr_user","u_team_id=".$team_id,$values);

		$db->exec_DELETEquery("usr_team","uid=".$team_id);
	}

	/**
	 * öscht eine Berechtigung eines Support-Teams auf eine Ressource.
	 *
	 * @param int $team_id ID des Support-Teams
	 * @param int $resource_id ID der Ressource (Objekt)
	 */
	function delete_teamHasPerm($team_id,$resource_id=null) {
		global $db;

		$where="tp_team_id=".$team_id.($resource_id?" AND tp_resource_id=".$resource_id:"");
		$db->exec_DELETEquery("usr_teamhasperm",$where);
	}

	/**
	 * Löscht eine Abteilung aus der Datenbank mit allen ihrer Verbindungen zu anderen Tabellen.
	 *
	 * @param int $department_id ID der Abteilung
	 */
	function delete_department($department_id) {
		global $db;

		$values=array(
			'u_department_id'=>'null',
		);
		$db->exec_UPDATEquery("usr_user","u_department_id=".$department_id,$values);

		$db->exec_DELETEquery("usr_department","uid=".$department_id);
	}

	/**
	 * Löscht einen Firmenstandort aus der Datenbank mit allen seinen Verbindungen zu anderen Tabellen.
	 *
	 * @param int $location_id ID des Firmenstandorts
	 */
	function delete_location($location_id) {
		global $db;

		$values=array(
			'u_location_id'=>'null',
		);
		$db->exec_UPDATEquery("usr_user","u_location_id=".$location_id,$values);

		$db->exec_DELETEquery("usr_location","uid=".$location_id);
	}

	/**
	 * Löscht ein Synchronisatons-Task aus der Datenbank mit allen seinen Verbindungen zu anderen Tabellen.
	 *
	 * @param int $sync_id ID des Synchronisations-Tasks
	 */
	function delete_sync($sync_id) {
		global $db;

		$values=array(
			'u_sync_id'=>'null',
		);
		$db->exec_UPDATEquery("usr_user","u_sync_id=".$sync_id,$values);

		$db->exec_DELETEquery("usr_sync","uid=".$sync_id);
	}

	/**
	 * Löscht eine Berechtigung einer Rolle auf eine Ressource (Objekt)
	 *
	 * @param int $roll_id ID der Rolle
	 * @param int $resource_id ID der Ressource (Objekt)
	 */
	function delete_rollHasPerm($roll_id,$resource_id=null) {
		global $db;

		$where="rp_roll_id=".$roll_id.($resource_id?" AND rp_resource_id=".$resource_id:"");
		$db->exec_DELETEquery("usr_rollhasperm",$where);
	}

	/**
	 * Weist einen Benutzer einer Arbeitsgruppe zu.
	 *
	 * @param int $user_id ID des Benutzers
	 * @param int $group_id ID der Arbeitsgruppe
	 */
	function userToGroup($user_id,$group_id) {
		global $db;

		$values=array(
			'ug_user_id'=>$user_id,
			'ug_group_id'=>$group_id,
		);
		$db->exec_INSERTquery("usr_useringroup",$values);
	}

	/**
	 * Liefert das Supportlevel des Support-Teams, dem der angemeldete Benutzer angehört.
	 *
	 * @return int Support-Level (1-3)
	 */
	function curusr_getTeamSupportLevel() {
		global $db;

		if($_SESSION['user']['u_team_id']) {
			$result=$db->exec_SELECTquery("t_supportLevel","usr_team","uid=".$_SESSION['user']['u_team_id']);
			$row=$db->sql_fetch_assoc($result);
			return $row['t_supportLevel'];
		}
	}
}
?>