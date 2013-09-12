<?
$TABLES['updates']=array(
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
		'u_name'=>array(
			'label'=>'name',
			'description'=>'Name des Updates',
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
		'u_description'=>array(
			'label'=>'beschreibung',
			'description'=>'Beschreibungstext des Updates.',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>1000,
			),
		),
		'u_token'=>array(
			'label'=>'token',
			'description'=>'Token, der das Update identifizieren lässt.',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>1000,
			),
		),
		'u_installed'=>array(
			'label'=>'Installiert',
			'description'=>'Datum der Installation',
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
	),
);

class update {
	Const DOWNLOAD_SERVER='http://www.nortody.ch/domino/updates/';
	Const CONFIG_FILE='updates.xml';

	Const UPDATETYPE_REQUIRED='required';
	Const UPDATETYPE_OPTIONAL='optional';



	public static function checkForUpdates($justRequired=true) {
		self::downloadUpdateConfig();

		$updateConfig=div::xml_xmlToArray(TEMP_DIR.self::CONFIG_FILE);
		$hasUpdates=false;

		foreach($updateConfig as $update) {
			if(!$justRequired OR $update['type']==self::UPDATETYPE_REQUIRED) {
				if(!self::isInstalled($update['token'])) {
					$hasUpdates=true;
				}
			}
		}
		return $hasUpdates;
	}

	private static function downloadUpdateConfig() {
		if(!file_exists(TEMP_DIR.self::CONFIG_FILE)) {
			copy(self::DOWNLOAD_SERVER.self::CONFIG_FILE,TEMP_DIR.self::CONFIG_FILE);
		}
	}

	public static function isInstalled($token) {
		global $db;

		$result=$db->exec_SELECTquery("u_token","update","u_token='".$token."'");
		if($db->sql_num_rows($result)==1) {
			return true;
		} else {
			return false;
		}
	}

	public static function createUpdatePackage($config,$files) {
		global $db;

		$token=self::getToken();
		echo "TOKEN:".$token."<br>";
		$fileName=$config['name'].'_'.$token.'.dom';


		//Updatefile aktualisieren.
		$commonUpdateFile=array(
			'type'=>$config['type'],
			'title'=>$config['title'],
			'file'=>$fileName,
			'token'=>$token,
		);
		$updateConfig=div::xml_xmlToArray(self::DOWNLOAD_SERVER.self::CONFIG_FILE);
		$updateConfig[]=$commonUpdateFile;
		$fp=fopen(self::DOWNLOAD_SERVER.self::CONFIG_FILE,"w");
		fwrite($fp,div::xml_arrayToXML($updateConfig));
		fclose($fp);

		//config.xml erstellen.
		$updateConfigFile=array(
			'files'=>array(),
		);
		foreach($files as $file) {
			$updateConfigFile['files'][]=$file;
		}
		$updateConfigFile=array_merge($updateConfigFile,$commonUpdateFile);
		$fp=fopen(TEMP_DIR.'update_config.xml',"w");
		//echo "ARAYTOXML".div::xml_arrayToXML($updateConfigFile);
		fwrite($fp,div::xml_arrayToXML($updateConfigFile));
		fclose($fp);
		$files[]=TEMP_DIR.'update_config.xml';
		//Module Berücksichtigen.
		foreach($config['modules'] as $module) {
			$moduleExcludes.=module::getModuleExcludes($module);
		}

		echo $db->view_array($files);

		//CompressedFile erstellen.
		//div::file_domGzFileCompress(UPDATE_DIR.$fileName,$files,$excludes);
		/*$rar=new rar($fileName);
		foreach($files as $file) {
			if(is_dir($file)) {
				$rar->addfolder($file);
			} else {
				$rar->addFile($file);
			}
		}
		echo "FILENAME:".UPDATE_DIR.$fileName;
		$rar->compression(12);
		//saveZip(UPDATE_DIR.$fileName);*/
		return $fileName;
	}

	private function getToken() {
      	$uppercase  = range('A', 'Z');
		global $db;
		echo $db->view_array($uppercase);
      	$charPool=$uppercase;

      	$numeric    = range(0, 9);
      	$charPool=array_merge($charPool,$numeric);

      	$lowercase = range('a', 'z');
      	$charPool=array_merge($charPool,$lowercase);

      $poolLength = count($charPool) - 1;

      for ($i = 0; $i < 20; $i++) {
        $string .= $charPool[mt_rand(0, $poolLength)];
      }
      return md5($string);

	}


}