<?
$TABLES['contact']=Array(
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
		'c_created'=>array(
			'label'=>'Datum',
			'description'=>'Datum des Konzerts',
			'formconfig'=>array(
				'all'=>array(
					'specialVal'=>'now',
				)
			),
			'dbconfig'=>array(
				'type'=>'datetime',
			),
		),
		'c_name'=>array(
			'label'=>'Name',
			'description'=>'Deinen Namen und Vornamen',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		'c_email'=>array(
			'label'=>'E-Mail',
			'description'=>'Deine E-Mail-Adresse',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,email,required',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		'c_description'=>array(
			'label'=>'Text',
			'description'=>'Deine Frage, Anfrage, Bemerkung',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim,required',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>1000,
			),
		),
	),
	'forms'=>array(
		'new'=>array(
			'fields'=>'c_name,c_email,c_description',
		),
		'edit'=>array(
			'fields'=>'c_name,c_email,c_description',
		),
		'new_front'=>array(
			'fields'=>'c_name,c_email,c_description',
			'execOnSave'=>'<func>'.urlencode("div::http_redirect('../index.php?id=kontakt.php&saved=true','content')").'</func>',
			'buttons'=>array(
				'submit'=>array(
					'text'=>'Abschicken',
					'bgColor'=>'#000000',
					'fgColor'=>'#FFFFFF',
					'border'=>'2px solid #7C0A0A',
					'icon'=>'',
				),
			),
		),
	),
	'lists'=>array(
		'listAll'=>array(
			'fields'=>'c_name,c_email,c_description',
			'commonActions'=>'drop',
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
	),

);

class contact{
	function delete_contact($contact_id) {
		global $db;

		$db->exec_DELETEquery("contact","uid=".$contact_id);
	}
}