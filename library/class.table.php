<?
/**
 * Schnittstelle zur generierung von Tabellen, Formularen und Listen
 *
 * @author Ueli Wyss
 * @package IPA-Vorbereitung
 */
  

include_once('class.form.php');
include_once('class.iList.php');

define('NO_UID','NO_UID');

class table {

	Const MODE_EDIT='edit';
	Const MODE_NEW='new';
	Const MODE_VIEW='view';
	Const MODE_SAVE='save';

	/**
	 * Liefert den Typ der Tabelle, der im 'ctrl'-Bereich gespeichert ist.
	 * Falls er nicht gesetzt ist, wird der Default-Typ aus dem $TBL_STANDARDS Array gewählt.
	 *
	 * @param string $tableName
	 * @return string
	 */
	static function getTableType($tableName) {
		global $TABLES,$TBL_STANDARDS;
		$tableType=$TABLES[$tableName]['ctrl']['type'];
		if(!$tableType) $tableType=$TBL_STANDARDS['tableTypes']['default'];
		return $tableType;
	}

	static function getFieldDefault($tableName,$fieldName) {
		global $TABLES;

		if($TABLES[$tableName]['fields'][$fieldName]['dbconfig']['default']!='') {
			return ' default '.$TABLES[$tableName]['fields'][$fieldName]['dbconfig']['default'];
		} else {
			return '';
		}
	}

	static function getFieldLength($tableName,$fieldName) {
		global $TABLES, $TBL_DEFAULTS;

		$type=table::getFieldType($tableName,$fieldName);

		if($TABLES[$tableName]['fields'][$fieldName]['dbconfig']['length']) {
			$len = $TABLES[$tableName]['fields'][$fieldName]['dbconfig']['length'];
		} else {
			switch($type) {
				case 'varchar':
				$len=$TBL_DEFAULTS['fieldLen_varchar'];
				break;
				case 'int':
				$len=$TBL_DEFAULTS['fieldLen_int'];
				break;
				case 'timestamp':
				$len=$TBL_DEFAULTS['fieldLen_timestamp'];
				break;
			}
		}
		return $len;
	}

	static function getDefaultRows($tableName) {
		global $TABLES;

		if(is_array($TABLES[$tableName]['defaultRows'])) {
			return $TABLES[$tableName]['defaultRows'];
		} else {
			return array();
		}
	}

	/**
	 * Fragt ab, ob die Tabelle in der MySQL-DB vorhanden sein soll oder nicht.
	 *
	 * @param string $tableName
	 * @return boolean
	 */
	static function isDbTable($tableName) {
		global $TABLES;
		return $TABLES[$tableName]['ctrl']['isDbTable'];
	}

	static function getField($tableName,$fieldName) {
		global $TABLES;

		$data = $TABLES[$tableName]['fields'][$fieldName];
		$data["name"]=$fieldName;

		return $data;
	}

	static function fieldExists($tableName,$fieldName) {
		global $TABLES;

		return is_array($TABLES[$tableName]['fields'][$fieldName]);
	}

	static function getFields($tableName) {
		global $TABLES;

		$data = $TABLES[$tableName]['fields'];
		foreach($data as $fieldName=>$field) {
			$data[$fieldName]["name"]=$fieldName;
		}
		return $data;
	}

	static function getFormConfig($tableName,$fieldName,$form) {
		global $TABLES;

		if($TABLES[$tableName]['fields'][$fieldName]['formconfig']['all']) {
			return $TABLES[$tableName]['fields'][$fieldName]['formconfig']['all'];
		} elseif($TABLES[$tableName]['fields'][$fieldName]['formconfig'][$form]) {
			return $TABLES[$tableName]['fields'][$fieldName]['formconfig'][$form];
		} else {
			return $TABLES[$tableName]['fields'][$fieldName]['formconfig']['default'];
		}
	}

	static function getFormButtons($tableName,$form) {
		global $TABLES;

		return $TABLES[$tableName]['forms'][$form]['buttons'];
	}

	static function getFormPalette($tableName,$form) {
		global $TABLES;

		if($TABLES[$tableName]['forms'][$form]) {
			$config = $TABLES[$tableName]['forms'][$form];
		} else {
			$config = $TABLES[$tableName]['forms']['default'];
		}
		//$config = explode(",",$config);
		return $config;
	}

	static function getFieldType($tableName,$fieldName) {
		global $TABLES, $TBL_DEFAULTS;

		if($TABLES[$tableName]['fields'][$fieldName]['dbconfig']['type']) {
			return $TABLES[$tableName]['fields'][$fieldName]['dbconfig']['type'];
		} else {
			return $TBL_DEFAULTS['fieldType'];
		}
	}

	/**
	 * Listet alle Feldnamen (mit ',' getrennt) in einem String auf, die zum PrimaryKey ($TABLES[$tableName]['fields'][$fieldName]['dbconfig']['primaryKey']==true) gehören.
	 *
	 * @param string $tableName
	 * @return string
	 * @todo Es muss berücksichtigt werden, dass mehrere Felder den PrimaryKey bilden können.
	 */
	static function getPrimaryKeys($tableName) {
		global $TABLES;

		foreach($TABLES[$tableName]['fields'] as $key => $field) {
			if($field['dbconfig']['primaryKey']) {
				$list.=$key.',';
			}
		}
		return substr($list,0,(strlen($list)-1));
	}

	static function formatValue($tableName,$fieldName,$value,$save=false) {
		global $TABLES;
		$fieldType=table::getFieldType($tableName,$fieldName);
		if($save) {
			switch($fieldType) {
				case 'date':
					$value=div::date_gerToSQL($value);
					break;
				case 'datetime':
					$value=div::date_gerToSQL($value);
					break;
			}
		} else {
			switch($fieldType) {
				case 'date':
					$value=div::date_sqlToGer($value);
					break;
				case 'datetime':
					$value=div::date_sqlToGer($value);
					break;
			}
		}
		return $value;
	}

	static function isAutoIncrement($tableName,$fieldName) {
		global $TABLES;
		//echo $tableName."[".$fieldName."]=".$TABLES[$tableName]['fields'][$fieldName]['dbconfig']['autoIncrement']."<br>";
		return $TABLES[$tableName]['fields'][$fieldName]['dbconfig']['autoIncrement'];
	}

	static function getFieldConfig($tableName,$fieldName) {
		global $TABLES;

		$data=array(
		'type'=>table::getFieldType($tableName,$fieldName),
		'length'=>table::getFieldLength($tableName,$fieldName),
		'autoIncrement'=>table::isAutoIncrement($tableName,$fieldName),
		'default'=>table::getFieldDefault($tableName,$fieldName),
		'primaryKey'=>preg_match("/".$fieldName."/",table::getPrimaryKeys($tableName)),
		'name'=>$fieldName,
		);
		return $data;
	}

	static function getAllFieldConfigs($tableName) {
		global $db;

		$fields=table::getFields($tableName);
       
		$data=array();
		foreach($fields as $fieldName=>$field) {
			$data[$fieldName]=table::getFieldConfig($tableName,$fieldName);
		}
        //echo $db->view_array($data);
		return $data;
	}

	static function getListPalette($tableName,$list) {
		global $TABLES;

		if($TABLES[$tableName]['lists'][$list]) {
			return $TABLES[$tableName]['lists'][$list];
		} elseif($TABLES[$tableName]['lists']['default']) {
			return $TABLES[$tableName]['lists']['default'];
		}
	}

	static function getFieldLabel($tableName,$fieldName) {
		global $TABLES;

		return ucfirst($TABLES[$tableName]['fields'][$fieldName]['label']);
	}

	static function getFieldDescription($tableName,$fieldName) {
		global $TABLES;

		return $TABLES[$tableName]['fields'][$fieldName]['description'];
	}

	static function getResources($tableName) {
		global $TABLES;

		return $TABLES[$tableName]['resources'];
	}

	static function getAllocation($tableName,$fieldName) {
		global $TABLES;

		return $TABLES[$tableName]['fields'][$fieldName]['allocation'];
	}

	

	/*static function getTreeviewConfig($tableName,$treeview) {
		global $TABLES;

		return $TABLES[$tableName]['treeviews'][$treeview];
	}*/

	static function getTreeviewConfig($tableName,$treeview, $parent_table=null) {
		global $TABLES;

		if(!$parent_table) {
			$treeviewConfig=$TABLES[$tableName]['treeviews'][$treeview];
			if(!$treeViewConfig['table']) {
				$treeviewConfig['table']=$tableName;
			}
			$treeViewConfig['querys']=array(table::getTreeViewSubQuery($treeViewConfig));
			$result=$treeViewConfig;
		} else  {
			for($i=0;$i<count($tableName);$i++) {
				if(!$tableName[$i]['parent_table']) {
					$tableName[$i]['parent_table']=$parent_table;
				}
			}
			$result=$tableName;
		}



		if(is_array($result['subitems'])) {
			foreach($result['subitems'] as $treeViewLevel) {
				if(!is_array($result['querys'])) {
					$result['querys']=array();
				}
				$result['querys'][]=table::getTreeViewSubQuery($treeViewLevel);
			}
			$result['subitems']=table::prepareTreeviewConfig($result['subitems'],$treeview,$result['table']);
		}

		return $result;
	}

	static function getTreeviewSubquery(&$config) {
		$query=array();


		if($config['parent_table']) {
			$query['subQuery']='SELECT '.$config['table'].'.'.$config['text_field'].' as text,'.$config['table'].'.uid as uid FROM ';
			//Untergeordnetes Objekt.
			if($config['mm_table']) {
				//Mit Zwischentabelle
				$query['subQuery'].=$config['parent_table'].', '.$config['mm_table'].', '.$config['table'];
				$sub_where_clause=' WHERE '.$config['parent_table'].'.uid=<parent_uid> AND '.$config['parent_table'].'.uid='.$config['mm_table'].'.'.$config['mm_foreign_key'].' AND '.$config['table'].'.uid='.$config['mm_table'].'.'.$config['mm_key'];
			} else {
				//Ohne Zwischentabelle
				$query['subQuery'].=$config['parent_table'].', '.$config['table'];

				$sub_where_clause=' WHERE '.$config['parent_table'].'.uid=<parent_uid> AND '.$config['parent_table'].'.uid='.$config['table'].'.'.$config['parent_key'];
			}

			if($config['parent_item_field']) {
				//Mit Feld, das das übergeordnete Objekt (mittels Uid) aus der selben Tabelle angibt.
				$query['selfReferencingQuery']='SELECT '.$config['table'].'.'.$config['text_field'].' as text,'.$config['table'].'.uid as uid FROM '.$config['table'];

				$self_where_clause=' WHERE '.$config['table'].'.'.$config['parent_item_field'].'=<parent_uid>';
			}

		} else {
			//Haupttabelle.
			$query['topLevelQuery']='SELECT '.$config['table'].'.'.$config['text_field'].' as text,'.$config['table'].'.uid as uid FROM '.$config['table'];
			if($config['parent_item_field']) {
				//Mit Feld, das das übergeordnete Objekt (mittels Uid) aus der selben Tabelle angibt.
				$query['selfReferencingQuery']=$query['topLevelQuery'];

				$self_where_clause=' WHERE '.$config['table'].'.'.$config['parent_item_field'].'=<parent_uid>';

				$top_where_clause=' WHERE '.$config['table'].'.'.$config['parent_item_field'].'=0 OR '.$config['table'].'.'.$config['parent_item_field'].'=null';
			}
		}

		if($config['where_clause']) {
			$top_where_clause=$top_where_clause?' AND '.$config['where_clause']:' WHERE '.$config['where_clause'];
			$self_where_clause=$self_where_clause?' AND '.$config['where_clause']:' WHERE '.$config['where_clause'];
		}

		$query['topLevelQuery'].=$top_where_clause;
		$query['selfReferencingQuery'].=$self_where_clause;
		$query['subQuery'].=$sub_where_clause;

		return $query;
	}

	static function getTreeView($tableName,$treeview='default',$inSection=false,&$tabItem=null) {
		global $db;

		echo $db->view_array(table::getTreeviewConfig($tableName,$treeview));
	}

	static function updateResources($tableName) {
		global $db, $TABLES;


		$resources=table::getResources($tableName);
		if(is_array($resources)) {
			foreach($resources as $name=>$resource) {
				$values=array(
				'r_name'=>$resource['name'],
				'r_shortName'=>$name,
				'r_description'=>$resource['description'],
				);
				if(!$db->rowExists('usr_resource',$values)) {
					$db->exec_INSERTquery('usr_resource',$values);
				}
			}
		}
	}

	static function updateAllResources() {
		global $TABLES;

		foreach($TABLES as $tableName=>$table) {
			table::updateResources($tableName);
		}
	}

	/**
	 * Überprüft ob die angegebene Tabelle in der Datenbank existiert.
	 * Wenn ja, werden die Feld-Attribute im $TABLES-Array mit denen in der Datenbank verglichen.
	 * Wenn alles übereinstimmt, wird true zurückgegeben.
	 * Falls der Parameter $justExistance gesetzt ist, wird nur die Existenz der Tabelle überprüft.
	 *
	 * @param string $tableName
	 * @param boolean $justExistance
	 * @return boolean
	 */
	/*	public static function checkTable($tableName,$justExistance=false) {
	global $TABLES,$db;

	if($db->tableExists($tableName)) {

	if($justExistance) {
	return true;
	} else {
	$result= $db->exec_SELECTquery("*",$tableName,'');

	$dbFields = $db->getFieldInfo($result);
	$arrayFields = table::getFields($tableName);

	$equal=true;

	foreach($arrayFields as $fieldName=>$field) {
	$fieldExists=is_array($dbFields[$fieldName])?true:false;

	if($fieldExists) {
	$fieldsEqual=$dbFields[$fieldName]["len"]==table::getFieldLength($tableName,$fieldName) && $dbFields[$fieldName]["type"]==table::getFieldType($tableName,$fieldName) && $dbFields[$fieldName]["autoIncrement"]==table::isAutoIncrement($tableName,$fieldName);
	if(!$fieldsEqual) {
	$equal=false;
	}
	} else {
	$equal=false;
	}
	}
	}

	foreach($dbFields as $field) {
	if(!is_array(table::getField($tableName,$field["name"]))) {
	$equal=false;
	}
	}

	return $equal;

	} else {
	return false;
	}
	}*/

	/**
	 * Überprüft und mutiert eine Datenbank-Tabelle, sodass sie dieselben Felder besitzt, wie sie im $TABLES-Array konfiguriert wurden.
	 * Falls die Tabelle noch nicht existiert, wird sie erstellt.
	 *
	 * @param unknown_type $tableName
	 * @return unknown
	 */
	static function updateTable($tableName) {
		global $TABLES,$db;

		table::dropUnusedTables();

		if($db->tableExists($tableName)) {
			$result= $db->exec_SELECTquery("*",$tableName,"");

			$dbFields = $db->getFieldInfo($result);
			$arrayFields = table::getFields($tableName);


			foreach($arrayFields as $fieldName=>$field) {
				$fieldExists=is_array($dbFields[$fieldName])?true:false;

				if($fieldExists) {
					$fieldsEqual=table::isEqualField($tableName,$fieldName,$dbFields[$fieldName]);

					if(!$fieldsEqual) {
						//echo "Feldkonfiguration von ".$fieldName." ist nicht gleich.<br>";
						$db->ddl_updateTableField($tableName,array(table::getFieldConfig($tableName,$fieldName)));

					}
				} else {

					$db->ddl_addTableFields($tableName,array(table::getFieldConfig($tableName,$fieldName)));
				}
			}

			foreach($dbFields as $field) {
				if(!table::fieldExists($tableName,$field["name"])) {
					$db->ddl_dropTableFields($tableName,$field["name"]);
				}
			}
			table::checkPrimaryKeys($tableName,$dbFields);
			//table::refreshPrimaryKeys($tableName);
		} else {
			$db->ddl_createTable($tableName,table::getAllFieldConfigs($tableName),table::getPrimaryKeys($tableName));
		}

		$defaultRows=table::getDefaultRows($tableName);
		foreach($defaultRows as $row) {
            //echo $db->view_array($row);
			foreach($row as $fieldName=>$value) {
				$row[$fieldName]=formElement::parseString($value);
			}
			if(!$db->rowExists($tableName,$row)) {
				$db->exec_INSERTquery($tableName,$row);
			}
		}
	}

	static function updateAllTables() {
		global $TABLES;

		foreach($TABLES as $tableName=>$table) {
            echo $tableName.'<br>';
			table::updateTable($tableName);
		}
		table::dropUnusedTables();
	}

	static function createTable($tableName) {
		global $db;

		$fields=table::getAllFieldConfigs($tableName);
		$db->ddl_createTable($tableName,$fields,table::getPrimaryKeys($tableName));
	}

	static function wrapPrimaryKey($tableName) {
		$keylist = table::getPrimaryKeys($tableName);
		if($keylist!='') {
			$query=' PRIMARY KEY ('.$keylist.')';
		}

		return $query;
	}



	/*static function refreshPrimaryKeys($tableName) {
	global $db;



	$dropQuery='ALTER TABLE '.$tableName.' DROP PRIMARY KEY';
	$db->admin_query($dropQuery);

	$primaryKeys=table::getPrimaryKeys($tableName);
	if($primaryKeys!='') {
	$addQuery='ALTER TABLE '.$tableName.' ADD PRIMARY KEY ('.$primaryKeys.')';
	$db->admin_query($addQuery);
	}

	}*/

	static function checkPrimaryKeys($tableName,$dbTable) {
		global $db;


		$keys=table::getPrimaryKeys($tableName);
		$drop=false;

		foreach($dbTable as $fieldName=>$field) {
			if($field['primaryKey']) {
				if(!div::var_isInList($keys,$fieldName)) {
					$drop=true;
				}
				$dbKeys.=$fieldName.",";
			}
		}
		$dbKeys=substr($dbKeys,0,(strlen($dbKeys)-1));


		//$equal=count(explode(",",$keys))==count(explode(",",$dbKeys))?true:false;

		if(!$drop) {
			$keyArray=explode(",",$keys);
			foreach($keyArray as $key) {
				if(!div::var_isInList($dbKeys,$key)) {
					$drop=true;
				}
			}


		}

		if($drop) {
			$db->ddl_dropPrimaryKey($tableName);
			if(!$keys=='') {
				$db->ddl_addPrimaryKey($tableName,$keys);
			}
		}
	}

	private static function isEqualField($tableName,$fieldName,$dbField) {
		$dbFieldType=$dbField["type"];
		$fieldType=table::getFieldType($tableName,$fieldName);

		$dbFieldLen=$dbField["len"];
		$fieldLen=table::getFieldLength($tableName,$fieldName);

		$dbAutoIncrement=$dbField["autoIncrement"];
		$autoIncrement=table::isAutoIncrement($tableName,$fieldName);

		if($dbFieldType==$fieldType && $dbFieldLen==$fieldLen && $dbAutoIncrement==$autoIncrement) {
			return true;
		} else {
			return false;
		}
	}

	static function getUniqueFields($tableName,$form) {
		global $db;

		$palette=table::getFormPalette($tableName,$form);
		$palette=explode(",",$palette['fields']);
		$unique=array();
		foreach($palette as $fieldName) {
			$formconfig=table::getFormConfig($tableName,$fieldName,form);
			if(div::var_isInList($formconfig['eval'],'unique')) {
				$unique[]=$fieldName;
			}
		}
		return $unique;
	}

	public static function dropUnusedTables() {
		global $db,$TABLES;

		static $execCount=0;


		if($execCount<1) {
			$dbTables=$db->admin_get_tables();

			foreach($dbTables as $table) {
				if(!is_array($TABLES[$table])) {
					//$db->ddl_dropTables($table);
				}
			}
			$execCount++;
		}
	}

	static function saveData() {
		global $db, $TABLES;



		$action=table::decodeAction();

		if($action) {
			if(is_array($_POST['data'])) {
				$data=$_POST;
			} else {
				$data=$_GET;
			}
            
			echo $db->view_array($data);
             
            
			$formConfig = table::getFormPalette($action['tableName'],$action['form']);
			$palette=explode(",",$formConfig['fields']);

			//echo $db->view_array($palette);
			$save=true;

			foreach($palette as $fieldName) {
				$fieldName=preg_match("/=/",$fieldName)?substr($fieldName,0,strpos($fieldName,"=")):$fieldName;
                  
				$field=table::getFormConfig($action['tableName'],$fieldName,$action['form']);
				//Text wird von unerwünschten Escapezeichen '\' gereinigt.
				$data['data'][$action['tableName']][$action['uid']][$fieldName]=stripslashes($data['data'][$action['tableName']][$action['uid']][$fieldName]);
                
				if($field['type']=='captcha') {
					if($data['data'][$action['tableName']][$action['uid']][$fieldName]==div::var_restoreObject('captcha')) {
						$save=true;
						echo "Captcha OK";
						unset($data['data'][$action['tableName']][$action['uid']][$fieldName]);
					} else {
						echo "CAPTCHA NICHT GUT";
						$save=false;
					}
				}

                 

               
				if(is_array($data['data'][$action['tableName']][$action['uid']][$fieldName])) {
					$data['data'][$action['tableName']][$action['uid']][$fieldName]=$data['data'][$action['tableName']][$action['uid']][$fieldName][2];
				}

				if(is_array($data['data'][$action['tableName']][$action['uid']][$fieldName])) {
					$data['data'][$action['tableName']][$action['uid']][$fieldName]=$data['data'][$action['tableName']][$action['uid']][$fieldName][2];
				}

				if($field['type']=='checkbox') {
					$data['data'][$action['tableName']][$action['uid']][$fieldName]=$data['data'][$action['tableName']][$action['uid']][$fieldName]=='on'?1:0;
				}

				/*if(div::var_isInList($field['eval'],'confirm')) {
				$data['data'][$action['tableName']][$action['uid']][$fieldName]=$data['data'][$action['tableName']][$action['uid']][$fieldName][2];
				}*/


				switch($field['specialVal']) {
					case 'now':
					$data['data'][$action['tableName']][$action['uid']][$fieldName]=div::date_gerToSQL(div::date_getDateTime());
					break;
				}

				if(div::var_isInList($field['eval'],'datetime')) {
					preg_match("^(([0-2]?[0-9]|3[0-1]).([0]?[1-9]|1[0-2]).([1-3][0-9][0-9][0-9])) ?((([0-1]?[0-9])|(2[0-3])):([0-5][0-9]))$/",$data['data'][$action['tableName']][$action['uid']][$fieldName],$datetime);
					$data['data'][$action['tableName']][$action['uid']][$fieldName]=mktime($datetime[6],$datetime[9],0,$datetime[3],$datetime[2],$datetime[4]);
					$data['data'][$action['tableName']][$action['uid']][$fieldName]=date("d.m.Y H:i:s",$data['data'][$action['tableName']][$action['uid']][$fieldName]);
				} elseif(div::var_isInList($field['eval'],'date')) {
					preg_match("/^(([0-2]?[0-9]|3[0-1]).([0]?[1-9]|1[0-2]).([1-3][0-9][0-9][0-9]))$/",$data['data'][$action['tableName']][$action['uid']][$fieldName],$date);
					$data['data'][$action['tableName']][$action['uid']][$fieldName]=mktime(0,0,0,$date[3],$date[2],$date[4]);
					$data['data'][$action['tableName']][$action['uid']][$fieldName]=date("d.m.Y",$data['data'][$action['tableName']][$action['uid']][$fieldName]);
				}

				if(div::var_isInList($field['eval'],'crypt')) {
					$data['data'][$action['tableName']][$action['uid']][$fieldName]=md5($data['data'][$action['tableName']][$action['uid']][$fieldName]);
				}

				if(div::var_isInList($field['eval'],'noHTML')) {
					$data['data'][$action['tableName']][$action['uid']][$fieldName]=htmlentities($data['data'][$action['tableName']][$action['uid']][$fieldName],ENT_QUOTES,'UTF-8');
				}

				if(div::var_isInList($field['eval'],'trim')) {
					$data['data'][$action['tableName']][$action['uid']][$fieldName]=trim($data['data'][$action['tableName']][$action['uid']][$fieldName]);
				}

				//Weitere Evals berücksichtigen (plz,telnr.,...)

				$data['data'][$action['tableName']][$action['uid']][$fieldName]=table::formatValue($action['tableName'],$fieldName,$data['data'][$action['tableName']][$action['uid']][$fieldName],true);
			}

			echo $db->view_array($data);

			$formConfig['where']=formElement::parseString($formConfig['where']);

			$addition=$formConfig['where']?explode(" AND ",$formConfig['where']):'';
			if(is_array($addition)) {
				foreach($addition as $add) {
					$add=explode("=",$add);
					$additionVal[$add[0]]=$add[1];
				}
			}


			if($save) {
				if($action['mode']==table::MODE_NEW) {
					$unique=table::getUniqueFields($action['tableName'],$action['form']);
					$where=array();
					foreach($unique as $uni) {
						$where[$uni]=$data['data'][$action['tableName']][$action['uid']][$uni];
					}

					if(count($where)==0 || !$db->rowExists($action['tableName'],$where)) {
						if(is_array($addtionVal)) {
							$data['data'][$action['tableName']][$action['uid']]=array_merge($data['data'][$action['tableName']][$action['uid']],$additionVal);
						}
						$db->exec_INSERTquery($action['tableName'],$data['data'][$action['tableName']][$action['uid']]);
						raiseMessage(MESSAGE_INFO,"Der neue Datensatz wurde erfolgreich gespeichert.");
						$saved=true;
						$action['uid']=$db->sql_insert_id();
					} else {
						raiseMessage(MESSAGE_ERROR,"Daten konnten nicht gespeichert werden. Datensatz existiert bereits.");
						$saved=false;
					}
				} else {
					if($formConfig['where']) {
						$db->exec_UPDATEquery($action['tableName'],$formConfig['where'],$data['data'][$action['tableName']][$action['uid']]);
						$numRows=$db->sql_affected_rows();
						if($numRows==0) {
							if(is_array($addtionVal)) {
								$data['data'][$action['tableName']][$action['uid']]=array_merge($data['data'][$action['tableName']][$action['uid']],$additionVal);
							}

							$db->exec_INSERTquery($action['tableName'],$data['data'][$action['tableName']][$action['uid']]);
							$action['uid']=$db->sql_insert_id();
						}
					} else {
						$db->exec_UPDATEquery($action['tableName'],'uid='.$action['uid'],$data['data'][$action['tableName']][$action['uid']]);
					}
					raiseMessage(MESSAGE_INFO,"Datensatz wurde erfolgreich aktualisiert.");
					$saved=true;
				}
			}

		}
    
		if($saved) {
			
			echo div::htm_includeJS('
			if(top.opener) {
                top.opener.content.location.reload();
               
            } else {
                
                top.editElement("'.table::MODE_EDIT.'","'.$action['tableName'].'","'.$action['uid'].'","'.$action['foreign_uid'].'",false,"","&'.$data['activeTab'].'&saved=true","","'.div::http_getGP('triggerFile').'");
            }
            
			'); 
            
            if($data['execOnSave']) {
                $execOnSave=preg_replace("/<insert_uid>/",$action['uid'],$data['execOnSave']);
                echo formElement::parseString(urldecode($execOnSave));
                //eval(formElement::parseString(urldecode($execOnSave)).";");
            }  
		}

		//return $action."[".$action['tableName']."(".$action['form'].")][".$action['uid']."]";

	}



	static function getForm($tableName,$form='default',$inSection=false,$mode=table::MODE_NEW,$uid=NO_UID,&$tabItem=null,$foreign_uid=NO_UID) {
		global $db, $TABLES;
		$formConfig = table::getFormPalette($tableName,$form);
		$palette=explode(",",$formConfig['fields']);

		$content=array();

		$formular = new form($tabItem);
		$formular->description=$formConfig['description'];
		$formular->caption=$formConfig['title'];
		if($inSection) {$formular->showCaption=false;}

		if(($mode==table::MODE_EDIT || $mode==table::MODE_VIEW)) {
			foreach($palette as $fieldName) {
				if(!preg_match("/=/",$fieldName)) {
					$select.=$fieldName.",";
				}
			}
			$select=preg_replace("/\[([^\]]*)\],/","",substr($select,0,(strlen($select)-1)));     
            
			if($formConfig['where']) {
				$formConfig['where']=formElement::parseString($formConfig['where']);
				$data=$db->exec_SELECTgetRows($select,$tableName,$formConfig['where']);
			} else {
				$data=$db->exec_SELECTgetRows($select,$tableName,'uid='.$uid);
			}
			$data=$data[0];
		}
        
		foreach($palette as $fieldName) {
			//echo $form;
			$fieldConfig=table::getformConfig($tableName,$fieldName,$form);
            //echo $db->view_array($fieldConfig);
            $elementId=$fieldName."_".$formular->getInstances(); 
            $args=div::var_unsetKeys($fieldConfig,'type,max,size,cols,rows,eval,items,onBlur,onChange,onFocus,readOnly,value,foreign_display,foreign_where,default');
            
			if($args['coordinate_addressFields']) {
                preg_match_all('/\[([^\]]*)\]([^\[]*)/',$args['coordinate_addressFields'],$results);
                for($i=0;$i<count($results[0]);$i++) {
                    $args['coordinate_addressFields']=str_replace($results[1][$i],$results[1][$i]."_".$formular->getInstances(),$args['coordinate_addressFields']);
                }
            }
            if($fieldConfig['specialVal']=='') {                           
				if(preg_match("/=/",$fieldName)) {
					$formElement=new formElement('hidden');


					$array=explode("=",$fieldName);
					$fieldName=$array[0];

					$value=formElement::parseString($array[1]);

					$formElement->name=table::getFormElemId($tableName,$fieldName,$uid);

					$formElement->value=$value;

				} else {
					$field = table::getField($tableName,$fieldName);
					//echo $db->view_array($fieldConfig);
                    if(preg_match('/^\[(.*)\]$/',$fieldName,$subheader)) {
                        $formElement=new formSubheader($subheader[1]);
                    } else {
                         switch ($mode) {
                            case table::MODE_EDIT:
                            $formElement=new formElement($fieldConfig['type'],$fieldConfig['readOnly']);
                            break;
                            case table::MODE_NEW:
                            $formElement=new formElement($fieldConfig['type']);
                            $formElement->value=$fieldConfig['value']!=''?$fieldConfig['value']:$formElement->value;

                            break;
                            case table::MODE_VIEW:
                            $formElement=new formElement($fieldConfig['type'],true);
                            break;
                        }


                        $formElement->label=$field['label'];
                        $formElement->description=$field['description'];
                        $formElement->name = table::getFormElemId($tableName,$fieldName,$uid);

                        $formElement->max=$fieldConfig['max']!=''?$fieldConfig['max']:$formElement->max;
                        $formElement->size=$fieldConfig['size']!=''?$fieldConfig['size']:$formElement->size;
                        $formElement->cols=$fieldConfig['cols']!=''?$fieldConfig['cols']:$formElement->cols;
                        $formElement->rows=$fieldConfig['rows']!=''?$fieldConfig['rows']:$formElement->rows;
                        $formElement->eval=$fieldConfig['eval']!=''?$fieldConfig['eval']:$formElement->eval;
                        $formElement->items=$fieldConfig['items']!=''?$fieldConfig['items']:$formElement->items;
                        $formElement->onChange=$fieldConfig['onChange']!=''?$fieldConfig['onChange']:$formElement->onChange;
                        $formElement->onBlur=$fieldConfig['onBlur']!=''?$fieldConfig['onBlur']:$formElement->onBlur;
                        $formElement->onFocus=$fieldConfig['onFocus']!=''?$fieldConfig['onFocus']:$formElement->onFocus;

                        //echo $db->view_array($field);
                        $formElement->table=$field['foreign_table']!=''?$field['foreign_table']:$formElement->table;
                        $formElement->key=$field['foreign_key']!=''?$field['foreign_key']:$formElement->key;
                        $formElement->display=$fieldConfig['foreign_display']!=''?table::getConcat($tableName,$fieldName,$form):$formElement->display;
                        $formElement->where=$fieldConfig['foreign_where']!=''?$fieldConfig['foreign_where']:$formElement->where;
                        $formElement->orderBy=$fieldConfig['foreign_orderBy']!=''?$fieldConfig['foreign_orderBy']:$formElement->orderBy;
                        $formElement->args=$args;
                        
                        if(is_array($data)) {
                            $formElement->value=table::formatValue($tableName,$fieldName,$data[$fieldName],false);
                        } else {
                            $formElement->value=$fieldConfig['default']!=''?$fieldConfig['default']:$formElement->value;
                        }
                    }
					
				}
				$formElement->id=$elementId;
				$formular->addElement($formElement,"end");
			}
		}
		


		$actionField = new formElement(formElement::TYPE_HIDDEN);
		$actionField->name="action";
		$actionField->value=table::encodeAction($mode,$tableName,$uid,$foreign_uid,$form);
		$formular->addElement($actionField,"end");

		if($formConfig['execOnSave']) {
			$execOnSave = new formElement(formElement::TYPE_HIDDEN);
			$execOnSave->name="execOnSave";
			$execOnSave->value=urlencode($formConfig['execOnSave']);
			$formular->addElement($execOnSave,"end");
		}
		/*$uid_field = new formElement(formElement::TYPE_HIDDEN);
		$uid_field->name="uid";
		$uid_field->value=$uid;
		$formular->addElement($uid_field,"end");

		$foreign_uid_field = new formElement(formElement::TYPE_HIDDEN);
		$foreign_uid_field->name="foreign_uid";
		$foreign_uid_field->value=$foreign_uid;
		$formular->addElement($foreign_uid_field,"end");*/

		$formular->action=ROOT_DIR."saveForm.php";
		//echo $db->view_array($formElement->wrapContent());

		$buttons=table::getFormButtons($tableName,$form);
		if(is_array($buttons)) {
			foreach($buttons as $name=>$config) {
				$button=new formButton($config['text'],$config['onClick'],$config['icon']);
				$button->fgColor=$config['fgColor'];
				$button->bgColor=$config['bgColor'];
				$button->border=$config['border'];
				if(!$button->onClick && $name=='submit') {
					$button->onClick=$formular->getFormName().'.onsubmit();';
				}
				$formular->addButton($button);
			}
		}

		if($formular->getInstances()==0 && div::http_getGP('popup')) {
			$content['JS']='
static function editElement(action,tableName,uid,foreign_uid,popup,urlPref,urlExt) {
	top.opener.top.content.location.reload();
	window.close();
}
	';
		}

		if($inSection) {
			$section=new section("",$formular->wrapContent());
			div::htm_mergeSiteContent($content,$section->wrapContent());
		} else {
			div::htm_mergeSiteContent($content,$formular->wrapContent());
		}
		return $content;
	}

	/**
	 * Liefert die ID für das Formularelement aus dem $TABLES-Array.
	 *
	 * @param string $tableName
	 * @param string $fieldName
	 * @return string
	 */
	static function getFormElemId($tableName,$fieldName,$uid) {
		global $CURRENT_TABLE;
		return 'data['.$tableName.']['.$uid.']['.$fieldName.']';
	}

	static function getList($tableName,$listName='default',$inSection=false,$args=array(),&$tabItem=null){
		global $db;
        
        $list=new listFromTable($tableName,$listName,$inSection,$args,$tabItem);
        return $list->getList();
	}

	static function encodeAction($action,$tableName,$uid=NO_UID,$foreign_uid=NO_UID,$form='') {
		return $action."[".$tableName."(".$form.")][".$foreign_uid."][".$uid."]";
	}

	static function decodeAction() {
		global $db;
		if($action=div::http_getGP('action')) {
			preg_match("/(.*)\[(.*)\((.*)\)]\[(.*)\]\[(.*)\]/U",$action,$actions);
			$data=array(
				'mode'=>$actions[1],
				'tableName'=>$actions[2],
				'form'=>$actions[3],
				'foreign_uid'=>$actions[4]?$actions[4]:NO_UID,
				'uid'=>$actions[5]?$actions[5]:NO_UID,
				'popup'=>div::http_getGP('popup'),
			);
		} else {
            $data=array(
                'mode'=>'EDIT',
                'uid'=>'NO_UID',
            );
        }
		return $data;
	}

	public static function getListTableNameOfField($listTableName,$fieldName,$listName) {
		global $TABLES;

		$query=table::getListQuery($listTableName,$listName);

		if($TABLES[$listTableName]['fields'][$fieldName]) {
			$tableName_ofField=$listTableName;
		} elseif($TABLES[$query['foreign_table']]['fields'][$fieldName]) {
			$tableName_ofField=$query['foreign_table'];
		} elseif($TABLES[$query['mm_table']]['fields'][$fieldName]) {
			$tableName_ofField=$query['mm_table'];
		}
		//div::debug($listName."::".$listTableName."::".$fieldName,$tableName_ofField);
		return $tableName_ofField;
	}
    
    public static function getForeignTables($tableName) {
        global $TABLES;
        
        $foreign_tables=array();
        foreach($TABLES[$tableName]['fields'] as $field) {
            if($field['foreign_table']) {
                $foreign_tables[$field['foreign_table']]=array('primaryKey');
            }
        }
        return $foreign_tables;
    }
    
    public static function getRelations($tableName) {
        global $RELATIONS,$db;
        $rel=array();
          
    
        for($i=0;$i<count($RELATIONS);$i++) {
             if($RELATIONS[$i]['table_m']==$tableName || $RELATIONS[$i]['table_1']==$tableName || $RELATIONS[$i]['table_2']==$tableName) {
                 $rel[]=$RELATIONS[$i];
             }
        }
        //echo $db->view_array($rel);
        return $rel;
    }
    
    function deleteRows($tableName,$uids) {
        global $db;
        $relations=table::getRelations($tableName);
        echo $db->view_array($relations);
        
        if(!is_array($uids)) $uids=array($uids);
                 
        echo $db->view_array($uids);
        
        foreach($relations as $rel) {
            if($rel['relation']=='m:m') {
               $mm_key=$rel['table_1']==$tableName?$rel['key_mm_1']:$rel['key_mm_2'];
               foreach($uids as $uid) {
                   
                  $res=$db->exec_DELETEquery($rel['table_mm'],$mm_key.'='.$uid);
                  $numRows=mysql_affected_rows();
                  if($numRows>0) raiseMessage(MESSAGE_WARNING,"Datensätze gelöscht: DELETE FROM ".$rel['table_mm']." WHERE ".$mm_key.'='.$uid." >> ".($numRows==1?"1 Datensatz":$numRows." Datensätze")." betroffen."); 
               }
                
            } else {
               if($rel['table_1']==$tableName) {
                   foreach($uids as $uid) {
                        $primaryKeys=explode(",",table::getPrimaryKeys($tableName));
                        $res=$db->exec_DELETEquery($rel['table_m'],$rel['key_1_foreign'].'=(SELECT '.$rel['key_1'].' FROM '.$tableName.' WHERE '.$primaryKeys[0].'='.$uid.')');
                        $numRows=mysql_affected_rows();
                        
                        if($numRows>0) raiseMessage(MESSAGE_WARNING,"Datensätze gelöscht: DELETE FROM ".$rel['table_m']." WHERE ".$rel['key_1_foreign'].'='.$uid." >> ".($numRows==1?"1 Datensatz":$numRows." Datensätze")." betroffen.");    
                   }
                   
                    
               }
            }
            
        }
        
        foreach($uids as $uid) {
            $primaryKeys=explode(',',table::getPrimaryKeys($tableName));
            
            $res=$db->exec_DELETEquery($tableName,$primaryKeys[0].'='.$uid);
            
            $numRows=mysql_affected_rows();
            if($numRows>0) raiseMessage(MESSAGE_WARNING,"Datensätze gelöscht: DELETE FROM ".$tableName." WHERE ".$primaryKeys[0].'='.$uid." >> ".($numRows==1?"1 Datensatz":$numRows." Datensätze")." betroffen.");         
        }
    }
    
    public static function getConcat($tableName,$fieldName,$form='',$relations='') {
        global $TABLES;
        
        if(!$form AND $TABLES[$tableName]['fields'][$fieldName]['foreign_display']) $foreign_display=$TABLES[$tableName]['fields'][$fieldName]['foreign_display'];
        elseif($TABLES[$tableName]['fields'][$fieldName]['formconfig'][$form]['foreign_display']) $foreign_display=$TABLES[$tableName]['fields'][$fieldName]['formconfig'][$form]['foreign_display']; 
        elseif($TABLES[$tableName]['fields'][$fieldName]['formconfig']['all']['foreign_display']) $foreign_display=$TABLES[$tableName]['fields'][$fieldName]['formconfig']['all']['foreign_display'];
        elseif($TABLES[$tableName]['fields'][$fieldName]['foreign_display']) $foreign_display=$TABLES[$tableName]['fields'][$fieldName]['foreign_display'];
        else return false;
        
        if(!$relations) $relations=table::getRelations($tableName);
        
        foreach($relations as $rel) {
           if($rel['key_1_foreign']==$fieldName) {
               preg_match_all('/\[([^\]]*)\]([^\[]*)/',$foreign_display,$foreign_display);
                $display_query='';
                
                
                
                for($i=0;$i<count($foreign_display[0]);$i++) {
                    $fullField=$rel['table_1'].".".$foreign_display[1][$i];
                    if($TABLES[$rel['table_1']]['fields'][$foreign_display[1][$i]]['dbconfig']['type']=='date') {
                        $display_query.='DATE_FORMAT('.$fullField.',\'%e.%c.%Y\')';
                    } elseif($TABLES[$rel['table_1']]['fields'][$foreign_display[1][$i]]['dbconfig']['type']=='datetime') {
                        $display_query.='DATE_FORMAT('.$fullField.',\'%e.%c.%Y %k:%i:%s\')'; 
                    } else {
                        $display_query.=$fullField; 
                    }
                    $display_query.=(($i!=count($foreign_display[0])-1)?",":"");
                    if($foreign_display[2][$i]!='')
                        $display_query.="'".$foreign_display[2][$i]."'".(($i!=count($foreign_display[0])-1)?",":"");
                    
                }
                return " CONCAT(".$display_query.") AS '".$tableName.".".$fieldName."'";
           }
            
        } 
        return $fieldName;
    }
}

class listFromTable {
    private $list;
    private $tabItem;
    private $section;    
    
    private $listConfig;
    private $query;
    private $relations=array();
    private $usedRelations=array();
    
    public $tableName;
    public $listName;
    public $inSection;
    public $args;
    
    
    
    function listFromTable($tableName,$listName='default',$inSection=false,$args=array(),&$tabItem=null) {
             $this->tableName=$tableName;
             $this->listName=$listName;
             $this->inSection=$inSection;
             $this->args=$args;
             $this->tabItem=$tabItem;  
    }
    
    public function getList() {
        global $db;
        
        $this->loadRelations();
        $this->listConfig=$this->getListPalette();
        $this->query = $this->getListQuery();  
        
        if($listConfig['type']=='grid') {
            $this->list=new gridList($this->query,$this->tabItem);
        } else {  
            $this->list=new tabList($this->query,$this->tabItem);
        }
         
        $this->list->columns=$this->getListColumns();
        

        if($this->inSection) {$this->list->showCaption=false;}

        $this->list->description=$this->listConfig['description'];
        $this->list->caption=$this->listConfig['title'];
        $this->list->itemsPerPage=$this->listConfig['itemsPerPage']?$this->listConfig['itemsPerPage']:$this->list->itemsPerPage;
        $this->list->commonActions=$this->listConfig['commonActions'];
        $this->list->actions=$this->listConfig['actions'];
        $this->list->additionalFields=is_array($this->listConfig['additionalFields'])?$this->listConfig['additionalFields']:array();
        $this->list->isSortable=$this->listConfig['sortable']?true:false;

        if($this->inSection) { 
            $this->section=new section($this->listConfig['title'],$this->list->wrapContent());
                                                                           
            if($this->list->getNumDBItems_all()<1) $this->section->status=section::STATUS_CLOSED;
            $content=$this->section->wrapContent();
        } else {
            $content=$this->list->wrapContent();
        }


        return $content;
    }
    
    private function getListPalette() {
        global $TABLES;

        if($TABLES[$this->tableName]['lists'][$this->listName]) {
            return $TABLES[$this->tableName]['lists'][$this->listName];
        } elseif($TABLES[$this->tableName]['lists']['default']) {
            return $TABLES[$this->tableName]['lists']['default'];
        }
    }
    
    private function getListQuery() {
        global $db;

        $foreignKey=$this->getForeignKey();
        $query = array(
            'select'=>$this->getSelectForList(),
            'local_table'=>$this->tableName.($this->listConfig['local_table']?",".$this->listConfig['local_table']:''),
            'mm_table'=>$this->usedRelations[0]['relation']=='m:m'?$this->usedRelations[0]['table_mm']:false,
            'tables'=>implode(',',$this->getTablesForList()),
            'whereClause'=>$this->getWhereForList(),
            'groupBy'=>$this->listConfig['groupBy'],
            'orderBy'=>$this->listConfig['orderBy'],
            'limit'=>'',
            'sortable_field'=>$this->listConfig['sortable'],
            'join'=>$this->getJoinsForList(),
        );
    
        //echo $db->view_array($query);
        return $query;
    }
    
    private function getSelectForList($alias=true,$additional=true) {
        global $TABLES,$db;
        
        $querytables=$this->getRelatedTableNames();
        array_unshift($querytables,$this->tableName);
          
        
        $fields=$TABLES[$this->tableName]['lists'][$this->listName]['fields'];
        $fields=explode(",",$fields); 
        $select='';
        foreach($fields as $field) {
            $pos=stripos($field,".");
            if(!$pos===false) {     
                $tmp=$field;
                 $field=substr($tmp,$pos+1,strlen($tmp)-$pos-1);
                
                 $table=substr($tmp,0,$pos);
                  
            } else {
                $table=""; 
            }                    
            foreach($querytables as $relTab) {
                
                if($table) $relTab=$table; 
                if($TABLES[$relTab]['fields'][$field]) {
                    if($TABLES[$relTab]['fields'][$field]['foreign_display'] AND $alias) {
                        $select.=$this->getConcatForForeignKeys($field);
                    } else {
                        $select.=$relTab.'.'.$field;
                        if($alias) $select.=' AS \''.$relTab.'.'.$field.'\'';
                        $select.=','; 
                    }
                    
                    break;
                }
                
                if($table) break;             
            }  
        }
        if($additional) {
              $primaryKeys=$db->getPrimaryKey($this->tableName);
              $select.=$this->tableName.'.'.$primaryKeys[0].($this->usedRelations[0]['relation']=='m:m'?' AS localKey,':' AS primaryKey,');
              if($foreignKey=$this->getForeignKey()) {
                    $select.=$foreignKey[0].'.'.$foreignKey[1].' AS foreignKey,';
              }                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
        }     
        return substr($select,0,strlen($select)-1);
    }
    
    private function getRelatedTableNames() {
        $tableNames=array();
        
        
        foreach($this->relations as $rel) {
            if($rel['relation']==('m:1')||$rel['relation']==('1:m')) { 
                $tableNames[]=($rel['table_m']==$this->tableName)?$rel['table_1']:$rel['table_m'];   
            } elseif($rel['relation']=='m:m') {
                $tableNames[]=($rel['table_1']==$this->tableName)?$rel['table_2']:$rel['table_1'];
                $tableNames[]=$rel['table_mm'];  
            }
        }
        return $tableNames;
    }
    
    private function loadRelations() {
        $this->relations=table::getRelations($this->tableName);
        
        foreach($this->relations as $rel) {
            if($this->fieldsOfTableInSelect($rel['relation']=='m:m'?($rel['table_1']==$this->tableName?$rel['table_2']:$rel['table_1']):($rel['table_1']==$this->tableName?$rel['table_m']:$rel['table_1']))) 
            $this->usedRelations[]=$rel;  
        }
    }
    
    private function getTablesForList() {
        
        $tableNames=array();
        
        foreach($this->relations as $rel) {
            if($rel['relation']=='m:m') {
                if($this->fieldsOfTableInSelect($rel['table_1']==$this->tableName?$rel['table_2']:$rel['table_1'])) {
                    
                    //tableNames[]=($rel['table_1']==$this->tableName)?$rel['table_2']:$rel['table_1'];
                    //$tableNames[]=$rel['table_mm'];
                }      
            }
        }
        
        $tableNames[]=$this->tableName;
        
        return $tableNames;
    }
    
    private function getWhereForList() {
        global $TABLES;
         
        
        $where=array();
        foreach($this->relations as $rel) {
            if($rel['relation']==('m:1')||$rel['relation']==('1:m') AND ($rel['table_1']!=$this->tableName)) {
                
            } elseif($rel['relation']=='m:m') {
                if($this->fieldsOfTableInSelect($rel['table_2']==$this->tableName?$rel['table_1']:$rel['table_2'])) {
                    
                     //$where[]='('.$rel['table_1'].'.'.$rel['key_1'].'='.$rel['table_mm'].'.'.$rel['key_mm_1'].')';
                     //$where[]='('.$rel['table_2'].'.'.$rel['key_2'].'='.$rel['table_mm'].'.'.$rel['key_mm_2'].')';
                } 
            }
        }
        
        if($TABLES[$this->tableName]['lists'][$this->listName]['whereClause']) {          
            $where[]='('.formElement::parseString($TABLES[$this->tableName]['lists'][$this->listName]['whereClause']).')';
        }       
        return implode(' AND ',$where);
    }
    
    private function getJoinsForList() {
        global $db;
        //echo $db->view_array($relations);
        $join='';
        foreach($this->relations as $rel) {
            if($rel['relation']==('m:1')||$rel['relation']==('1:m') AND ($rel['table_1']!=$this->tableName)) {
                if($this->fieldsOfTableInSelect($rel['table_1']==$this->tableName?$rel['table_m']:$rel['table_1']))
                $join.=' LEFT JOIN '.$rel['table_1'].' ON '.$rel['table_1'].'.'.$rel['key_1'].'='.$rel['table_m'].'.'.$rel['key_1_foreign'];
            } elseif($rel['relation']=='m:m') {                                                             
                if($rel['table_2']==$this->tableName) {
                     $rel['table_2']=$rel['table_1'];
                     $rel['table_1']=$this->tableName;
                }
                if($this->fieldsOfTableInSelect($rel['table_1']==$this->tableName?$rel['table_2']:$rel['table_1'])) 
                $join.=' RIGHT JOIN ('.$rel['table_mm'].' LEFT JOIN '.$rel['table_2'].' ON '.$rel['table_mm'].'.'.$rel['key_mm_2'].' = '.$rel['table_2'].'.'.$rel['key_2'].') ON '.$rel['table_1'].'.'.$rel['key_1'].' = '.$rel['table_mm'].'.'.$rel['key_mm_1'];
                

            }
        } 
              
        return $join;
    }
    
    private function getConcatForForeignKeys($fieldName) {
        global $TABLES,$db;
                
        return table::getConcat($this->tableName,$fieldName).",";
    }
    
    private function fieldsOfTableInSelect($foreignTable) {
        global $db, $TABLES;
        
        $list=explode(',',$this->getSelectForList(false,false));
        $foreignFields=table::getAllFieldConfigs($foreignTable);
        
        //echo $db->view_array($foreignFields);
        foreach ($list as $listCol) {
            $listCol2=explode('.',$listCol); 
            if($TABLES[$listCol2[0]]['fields'][$listCol2[1]]['foreign_table']==$foreignTable)
            return true;
            
            foreach ($foreignFields as $foreignCol=>$att2) {   
                if($foreignCol==$listCol2[1] AND $foreignTable==$listCol2[0]) {
                    return true;
                }
            }
        }   
        return false;        
    }
    
    
    private function getListColumns() {
        global $db;

        $select=explode(',',$this->getSelectForList(false,false));
        
        
        $columns=array();

        
        foreach($select as $fieldName) {
            $tmp=null;

            
            $tmp=explode(".",$fieldName);
            $tmpFieldName=$tmp[1];
            $tmpTableName=$tmp[0];
        

            //$tableName_ofField = table::getListTableNameOfField($this->tableName,$tmpFieldName,$this->listName);
            
            //$this->listNameConfig=table::getListConfig($this->tableName,$tmpFieldName,$this->listName);
            //div::debug($tableName."::".$tmpFieldName."::".$this->listName."-->listConfig",$this->listNameConfig);
            $columns[$fieldName]=array(
                'isImage'=>$this->listNameConfig['isImage'],
                'imagePath'=>$this->listNameConfig['imagePath'],
                'dbField'=>$tmpFieldName,
                'label'=>table::getFieldLabel($tmpTableName,$tmpFieldName),
                'allocation'=>table::getAllocation($tmpTableName,$tmpFieldName),
            );
        }
        
        return $columns;
    }
                        
    private function getForeignKey() {
            if(count($this->usedRelations)>=1) {
                   $foreignKey=$this->usedRelations[0]['relation']=='m:m'?($this->usedRelations[0]['table_1']==$this->tableName?$this->usedRelations[0]['table_2'].'.'.$this->usedRelations[0]['key_2']:$this->usedRelations[0]['table_1'].'.'.$this->usedRelations[0]['key_1']):($this->usedRelations[0]['table_1']==$this->tableName?$this->usedRelations[0]['table_m'].'.'.$this->usedRelations[0]['key_m']:$this->usedRelations[0]['table_1'].'.'.$this->usedRelations[0]['key_1']);
                   return explode('.',$foreignKey);
            }
            return false;           
    }
}
 
$TBL_DEFAULTS=array(
	'tableType'=>'normal',
	'formElementType'=>'text',
	'fieldType'=>'int',
	'fieldLen_varchar'=>100,
	'fieldLen_int'=>11,
	'fieldLen_timestamp'=>19,
);


?>