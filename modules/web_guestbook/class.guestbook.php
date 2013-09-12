<?
$TABLES['guestbook']=Array(
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
		'g_created'=>array(
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
		'g_name'=>array(
			'label'=>'Name',
			'description'=>'Dein Name und Vorname',
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
		'g_text'=>array(
			'label'=>'Text',
			'description'=>'Dein Kommentar',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim,required,noHTML',
					'rows'=>4,
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>500,
			),
		),
		'g_email'=>array(
			'label'=>'E-Mail',
			'description'=>'Deine E-Mail Adresse',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,email',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		'g_captcha'=>array(
			'label'=>'Validierung',
			'description'=>'Gib die Zeichenkette auf dem Bild ins Textfeld daneben ein. So können wir Missbrauch des Gästebuchs verhindern.Falls du die Zeichen nicht lesen kannst, lade das Gästebuch neu, indem du F5 drückst.',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'captcha',
					'length'=>5,
					'eval'=>'required',
				),
			),
		),
	),
	'forms'=>array(
		'new'=>array(
			'fields'=>'g_name,g_email,g_text,g_created,g_captcha',
		),
		'edit'=>array(
			'fields'=>'g_name,g_email,g_text',
		),
		'new_front'=>array(
			'fields'=>'g_name,g_email,g_text,g_created,g_captcha',
			'execOnSave'=>'<func>'.urlencode("div::http_redirect('../index.php?id=guestbook.php&saved=true')").'</func>',
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
			'fields'=>'g_name,g_email,g_text,g_created',
			'commonActions'=>'drop',
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
	),
);


class guestbook {
	public $CSS;

	function guestbook() {
		$this->setDefaults();
	}

	function setDefaults() {
		$this->CSS='
.guestbook_table {
	width:100%;
}

.guestbook_itemHeader {
	border-bottom:1px solid white;
}

.guestbook_itemContent {
	border-bottom:1px solid bottom;
	margin-bottom:4px;
	padding-bottom:12px;

}';
	}
	function listItems($limit) {
		global $db;

		if($limit==0) { $limit=''; } else { $limit='0,'.$limit; }
		$rows=$db->exec_SELECTgetRows("*","guestbook","","","g_created DESC",$limit);
		$content['CSS']=$this->CSS;
		$content['main'].='
<table class="guestbook_table" cellpadding="0" cellspacing="0">';
		foreach($rows as $item) {
			$content['main'].='
	<tr>
		<td class="guestbook_itemHeader"><font size="-1"><b>'.$item['g_name'].'</b></font> - <a href="mailto:'.$item['g_email'].'">'.$item['g_email'].'</a> ('.div::date_SQLToGer($item['g_created']).')</td>
	</tr>
	<tr>
		<td class="guestbook_itemContent">'.$item['g_text'].'</td>
	</tr>';
		}

		$content['main'].='
</table>';
		return $content;
	}

	function delete_guestbookItem($guestbook_id) {
		global $db;

		$db->exec_DELETEquery("guestbook","uid=".$guestbook_id);
	}
}
