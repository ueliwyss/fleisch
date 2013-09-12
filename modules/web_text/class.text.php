
<?php

$TABLES['text']=Array(
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
		't_created'=>array(
			'label'=>'erstelldatum',
			'description'=>'Erstelldatum',
			'formconfig'=>array(
				'all'=>array(
					'specialVal'=>'now'
				),
			),
			'dbconfig'=>array(
				'type'=>'datetime',
			),
		),
		't_description'=>array(
			'label'=>'Kurzbeschreibung',
			'description'=>'Kurze Beschreibung oder Einleitung',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim,required',
					'rows'=>3,
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>1000,
			),
		),
		't_keyname'=>array(
			'label'=>'Schlüssel-Name',
			'description'=>'Der Text wird später über diesen Namen angesprochen.',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required,unique',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>1000,
			),
		),
		't_content_de'=>array(
			'label'=>'Deutsch',
			'description'=>'Text in Deutsch',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>10000,
			),
		),
		't_content_en'=>array(
			'label'=>'Englisch',
			'description'=>'Text in Englisch',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>10000,
			),
		),
		't_content_fr'=>array(
			'label'=>'Französisch',
			'description'=>'Text in Französisch',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>10000,
			),
		),
		't_content_it'=>array(
			'label'=>'Italienisch',
			'description'=>'Text in Italienisch',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>10000,
			),
		),
	),
	'forms'=>array(
		'new'=>array(
			'fields'=>'t_keyname,t_description,t_content_de,t_content_en,t_content_fr,t_content_it,t_created',
		),
		'edit'=>array(
			'fields'=>'t_keyname,t_description,t_content_de,t_content_en,t_content_fr,t_content_it',
		),
	),
	'lists'=>array(
		'listAll'=>array(
			'fields'=>'t_keyname,t_description,t_content_de,t_content_en,t_content_fr,t_content_it,t_created',
			'commonActions'=>'drop',
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
	),
);

class text {
	Const LANG_DEUTSCH=1;
	Const LANG_ENGLISCH=2;
	Const LANG_ITALIENISCH=3;
	Const LANG_FRANZOESISCH=4;


	public $CSS;


	function news() {
		$this->setDefaults();
	}

	function setDefaults() {
		$this->CSS='';
	}

	public static function getText($keyname) {
		global $db,$lang;
        
        switch($lang) {
            case 'de':$lang_col='t_content_de'; break;
            case 'en':$lang_col='t_content_en'; break;
            case 'fr':$lang_col='t_content_fr'; break;
            case 'it':$lang_col='t_content_it'; break;
        }
            
        if(isset($_GET['edit'])) {
            
            $row = $db->exec_SELECTgetRows("t_content_de,t_content_en,t_content_fr,t_content_it,t_description","text","t_keyname = '".$keyname."'");
            //echo $db->view_array($row);
            if(strlen($row[0]['t_content_de'])>50) {
                $text.='<textarea name="'.$keyname.'" value"">'.$row[0][$lang_col].'</textarea><br>';
            } else {
                $text.='<input type="text" name="'.$keyname.'" value="'.$row[0][$lang_col].'"><br>';
            }
            
            $lang_col='t_content_de';
        } 

		    

		if(isset($lang_col) && $keyname!='') {
			$row = $db->exec_SELECTgetRows($lang_col.",t_description","text","t_keyname = '".$keyname."'");
            if(isset($_GET['edit'])) {
                $text.=htmlentities($row[0][$lang_col]);
            } else {
                $text.=$row[0][$lang_col];
            }
			

			if($text=='') {
				$text.=$keyname.'::>>'.$row[0]['t_description'];
			}
		}
        

		return $text;
	}

    public static function updateValues() {
        global $db,$lang;
        
        switch($lang) {
            case 'de':$lang_col='t_content_de'; break;
            case 'en':$lang_col='t_content_en'; break;
            case 'fr':$lang_col='t_content_fr'; break;
            case 'it':$lang_col='t_content_it'; break;
        }
        
        foreach ($_POST as $key => $val) {
            if($key!='submit') {
                $db->exec_UPDATEquery('text',"t_keyname='".$key."'",array($lang_col=>$val));
            
            }
        }
        return $succ;    
    }

	/**
	 * @return void
	 * @desc Gibt den Inhalt dieser Seite aus.
	*/
	function echoContent() {
		echo $this->content;
	}
}
?>