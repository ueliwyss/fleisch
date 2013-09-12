<?

$TABLES['page']=Array(
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
		'p_text'=>array(
			'label'=>'Titel',
			'description'=>'Titel oder Name des Konzerts (Chilbi)',
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
		'p_parent_id'=>array(
			'label'=>'ParentPage',
			'description'=>'Album, das Bilder zum Konzert enthält.',
			'foreign_table'=>'page',
			'foreign_key'=>'uid',
			'foreign_display'=>'p_text',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
					'foreign_display'=>'p_text',
				),
			),
			'dbconfig'=>array(
				'type'=>'int',
			),
		),
	),
	'forms'=>array(
		'new'=>array(
			'fields'=>'p_text,p_parent_id',
		),
		'edit'=>array(
			'fields'=>'p_parent_id,p_text',
		),
	),
	'lists'=>array(
		'listAll'=>array(
			'fields'=>'p_text,p_parent_id',
			'commonActions'=>'drop',
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
	),
	'treeviews'=>array(
		'peTree'=>array(
			'text_field'=>'p_text',
			'onClick'=>'',
			'parent_item_field'=>'p_parent_id',
			'where_clause'=>'',
			'icon'=>'',
			'subitems'=>array(
				'table'=>'element',
				'text_field'=>'e_text',
				'parent_item_field'=>'e_parent_id',
				'where_clause'=>'',
				'mm_foreign_key'=>'pe_page_id',
				'mm_key'=>'pe_element_id',
				'mm_table'=>'page_element',
			),
		),
	),
);

$TABLES['page_element']=array(
	'ctrl'=>Array(
		'isDbTable'=>true,
		'type'=>'intermediate',
	),
	'fields'=>array(
		'pe_page_id'=>array(
			'label'=>'benutzer',
			'description'=>'Benutzer',
			'foreign_table'=>'page',
			'foreign_key'=>'uid',
			'foreign_display'=>'p_text',
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
		'pe_element_id'=>array(
			'label'=>'recht',
			'description'=>'recht',
			'foreign_table'=>'element',
			'foreign_key'=>'uid',
			'foreign_display'=>'e_text',
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

$TABLES['element']=Array(
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
		'e_text'=>array(
			'label'=>'Titel',
			'description'=>'Titel oder Name des Konzerts (Chilbi)',
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
		'e_parent_id'=>array(
			'label'=>'ParentElement',
			'description'=>'Album, das Bilder zum Konzert enthält.',
			'foreign_table'=>'element',
			'foreign_key'=>'uid',
			'foreign_display'=>'e_text',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
					'foreign_display'=>'e_text',
				),
			),
			'dbconfig'=>array(
				'type'=>'int',
			),
		),
	),
	'forms'=>array(
		'new'=>array(
			'fields'=>'e_text,e_parent_id',
		),
		'edit'=>array(
			'fields'=>'e_parent_id,e_text',
		),
	),
	'lists'=>array(
		'listAll'=>array(
			'fields'=>'e_text,e_parent_id',
			'commonActions'=>'drop',
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
	),
);


?>