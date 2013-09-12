<?
$TABLES['appartment_reservation']=Array(
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
		'r_created'=>array(
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
		'r_name'=>array(
			'label'=>'Name',
			'description'=>'Ihr Name',
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
		'r_prename'=>array(
			'label'=>'Name',
			'description'=>'Ihr Vorname',
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
		'r_address'=>array(
			'label'=>'Adresse',
			'description'=>'Ihre Anschrift mit Hausnummer',
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
		'r_plz'=>array(
			'label'=>'Plz',
			'description'=>'Postleitzahl',
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
		'r_ort'=>array(
			'label'=>'Ortschaft',
			'description'=>'Ortschaft',
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
		'r_email'=>array(
			'label'=>'E-Mail',
			'description'=>'Ihre E-Mail Adresse',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required,email',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		'r_tel'=>array(
			'label'=>'Telefon Nr.',
			'description'=>'Ihre Handy-, Privat- oder Geschäftsnummer',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,num',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		'r_comment'=>array(
			'label'=>'Bemerkungen, Wünsche',
			'description'=>'Schreiben Sie hier Ihre Mitteilung an uns.',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim',
					'rows'=>4,
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>500,
			),
		),
		'r_arrival'=>array(
			'label'=>'Ankunft',
			'description'=>'Datum Ihrer Ankuft z.B. 12.03.2008',
			'formconfig'=>array(
				'all'=>array(
					'eval'=>'date',
				),
			),
			'dbconfig'=>array(
				'type'=>'datetime',

			),
		),
		'r_departure'=>array(
			'label'=>'Abreise',
			'description'=>'Datum Ihrer Abreise z.B. 12.03.2008',
			'formconfig'=>array(
				'all'=>array(
					'eval'=>'date',
				),
			),
			'dbconfig'=>array(
				'type'=>'datetime',
			),
		),
		'r_numpers'=>array(
			'label'=>'Anzahl Personen',
			'description'=>'Anzahl Personen',
			'formconfig'=>array(
				'all'=>array(
					'eval'=>'num',
				),
			),
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>11,
			),
		),
		'r_numchilds'=>array(
			'label'=>'davon Kinder',
			'description'=>'Anzahl Kinder',
			'formconfig'=>array(
				'all'=>array(
					'eval'=>'num',
				),
			),
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>11,
			),
		),

	),
	'forms'=>array(
		'new'=>array(
			'fields'=>'r_name,r_prename,r_address,r_plz,r_ort,r_arrival,r_departure,r_numpers,r_numchilds',
		),
		'edit'=>array(
			'fields'=>'r_name,r_prename,r_address,r_plz,r_ort,r_arrival,r_departure,r_numpers,r_numchilds',
		),
		'new_front'=>array(
			'fields'=>'r_name,r_prename,r_address,r_plz,r_ort,r_arrival,r_departure,r_numpers,r_numchilds',
			'execOnSave'=>'<func>'.urlencode("div::http_redirect('&saved=true')").'</func>',
			'buttons'=>array(
				'submit'=>array(
					'text'=>'Abschicken',
					'bgColor'=>'#FFFFFF',
					'fgColor'=>'#CE5706',
					'border'=>'2px solid #CE5706',
					'icon'=>'images/form_hacken2.png',
				),
			),
		),
	),
	'lists'=>array(
		'listAll'=>array(
			'fields'=>'r_name,r_prename,r_address,r_plz,r_ort,r_arrival,r_departure,r_numpers,r_numchilds,r_created',
			'commonActions'=>'drop',
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
	),
);


class appartment_reservation {
	public $CSS;

	function reservation() {
		$this->setDefaults();
	}

	function setDefaults() {
	}

	function delete_appartment_reservation($reservation_id) {
		global $db;

		$db->exec_DELETEquery("appartment_reservation","uid=".$guestbook_id);
	}
}
?>