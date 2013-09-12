<?
$TABLES['module']=Array(
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
		'm_created'=>array(
			'label'=>'Installation',
			'description'=>'Installation des Moduls',
			'formconfig'=>array(
				'all'=>array(
					'specialVal'=>'now',
				),
			),
			'dbconfig'=>array(
				'type'=>'date',

			),
		),
		'm_type'=>array(
			'label'=>'Typ',
			'description'=>'Typ des Moduls',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		'm_name'=>array(
			'label'=>'Name',
			'description'=>'Name des Moduls, der Extension',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required,unique',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>400,
			),
		),
		'm_classpath'=>array(
			'label'=>'Klassenpfad',
			'description'=>'Pfad zur Klassen-Datei des Moduls vom module-Verzeichnis an.',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>1000,
			),
		),
		'm_version'=>array(
			'label'=>'Version',
			'description'=>'Versionsnummer des Moduls.',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,numeric',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>300,
			),
		),
		'm_active'=>array(
			'label'=>'Aktiv',
			'description'=>'Ist das Modul aktiviert?',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'checkbox',
				)
			),
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>1,
			),
		),
	),
	'forms'=>array(
		/*'new'=>array(
			'fields'=>'c_title,c_date,c_time,c_description,c_location,c_link,c_canceled,c_album_id',
		),*/
		'edit'=>array(
			'fields'=>'m_name,m_type,m_version,m_created,m_active',
		),
	),
	'lists'=>array(
		'listAll'=>array(
			'fields'=>'m_name,m_type,m_version,m_created,m_active',
			'commonActions'=>'drop',
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
	),
);

class module {




	function registerModule($moduleName) {
		global $db;

		$unique=table::getUniqueFields('module','edit');
		$config = module::getConfig($moduleName);
		//echo $db->view_array($config);
		$where=array();
		$values=array(
			'm_created'=>div::date_gerToSQL(div::date_getDateTime()),
			'm_type'=>$config['TYPE'],
			'm_name'=>$moduleName,
			'm_classpath'=>$moduleName."/".$config['COMPONENTS']['MAINCLASS'],
			'm_version'=>$config['VERSION'],
			'm_active'=>$config['ACTIVE']?true:false,
		);
		//echo $db->view_array($unique);
		foreach($unique as $uni) {
			$where[$uni]=$values[$uni];
		}
		//echo $db->view_array($where);
		if(count($where)==0 || !$db->rowExists('module',$where)) {
			$db->exec_INSERTquery("module",$values);
			return true;
		} else {
			foreach($where as $field=>$value) {
				$whereClause.=' '.$field.'=\''.$value.'\'';
			}
			$whereClause=trim($whereClause);
			$db->exec_UPDATEquery('module',$whereClause,$values);
			return false;
		}
	}

	function registerAllModules() {
		$dir = scandir(MOD_DIR);
		foreach($dir as $dir) {
			if(is_dir(MOD_DIR.$dir) AND $dir!='.' AND $dir!='..') {
				//echo "ORdner:".$dir."<br>";
				module::registerModule($dir);
			}

		}
	}

	function getConfig($moduleName) {
		if(file_exists(MOD_DIR.$moduleName."/config.xml")) {
			$config=div::xml_xmlToArray(MOD_DIR.$moduleName."/config.xml");
		} else {
			//DAtei nicht gefunden
			echo "Datei (".MOD_DIR.$moduleName."/config.xml".") nicht gefunden.<br>";
		}
		return $config['MODULE'];
	}

	function getVersion($moduleName) {
		global $db;

		if(module::isRegistered($moduleName)) {
			$rows = $db->exec_SELECTgetRows("m_version","module","m_name='".$moduleName."'");
			$version = $rows[0]['m_version'];
		}
		return $version;
	}

	function getClassPath($moduleName) {
		global $db;

		if(module::isRegistered($moduleName)) {
			$rows = $db->exec_SELECTgetRows("m_classpath","module","m_name='".$moduleName."'");
			$classPath = $rows[0]['m_classpath'];
		}
		return MOD_DIR.$classPath;
	}

	function isRegistered($moduleName) {
		global $db;

		$result = $db->exec_SELECTquery("*","module","m_name='".$moduleName."'");
		return $db->sql_num_rows($result)>=1?true:false;
	}

	function loadModules() {
		global $db,$TABLES;

		$modules=$db->exec_SELECTgetRows('*','module','m_active=1');
		//echo $db->view_array($modules);

		foreach($modules as $module) {
			//echo MOD_DIR."/".$module['m_classpath'];
			//echo MOD_DIR.$module['m_classpath'];
            if($module['m_type']=='Basic')
			include_once(MOD_DIR.$module['m_classpath']);
		}
        foreach($modules as $module) {
            //echo MOD_DIR."/".$module['m_classpath'];
            //echo MOD_DIR.$module['m_classpath'];
            if($module['m_type']!='Basic')
            include_once(MOD_DIR.$module['m_classpath']);
        }
	}

	function getMenuConfig() {
		global $db;

		$modules=$db->exec_SELECTgetRows('*','module','m_active=1');
		//echo $db->view_array($modules);
		$menu=array();

		foreach($modules as $module) {
			//echo MOD_DIR."/".$module['m_classpath'];
			//echo MOD_DIR.$module['m_classpath'];
			$config=module::getConfig($module['m_name']);
			
            //echo $db->view_array($config);
			if(is_array($config['MENU']['SUBMENUITEMS'])) {
				foreach($config['MENU']['SUBMENUITEMS'] as $caption=>$value) {
                    if(is_array($value)) {
                         
                         $permission=user::curusr_getPermission($value['[ATTRIBUTES]']['RESOURCE']);
                         $value=$value["[CONTENT]"];  
                    } else {
                        $permission=user::PERMISSION_FULL;
                    }
					//echo $config['MENU']['TOPLEVEL'];
					$wrapedValue=str_replace("::ROOT_LEVEL::","",$value);
					if($wrapedValue==$value) {
						$wrapedValue=MOD_DIR.$module['m_name'].'/'.$wrapedValue;
					}
                    if($permission>=user::PERMISSION_READ) {
                       $menu[$config['MENU']['TOPLEVEL']][strtoupper($caption)]=$wrapedValue;
                    }
					
					//echo $menu[$config['MENU']['TOPLEVEL']][strtoupper($caption)];
				}
			}
		}
		return $menu;
	}

	public static function getModuleExcludes($moduleName) {
		$config=module::getConfig($moduleName);
		$excludes=getModulePath($moduleName).'config.xml,';
		foreach($config['USERSPECIFICOBJECTS'] as $userObject) {
			$excludes=getModulePath($moduleName).$userObject.',';
		}
		$excludes=substr($excludes,0,strlen($excludes)-1);

		//echo "EXCLUDES::".$moduleName."::".$excludes;
		return $excludes;
	}

	public static function getModulePath($moduleName) {
		return MOD_DIR.$moduleName.'/';
	}
}