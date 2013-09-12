<?
if($_POST['sort_order']) {
    
    include('../init.php');      
    $sort_order=explode('|',$_POST['sort_order']);
    for($i=0;$i<count($sort_order);$i++) {
        $db->exec_UPDATEquery($_POST['table'],'uid='.$sort_order[$i],array($_POST['field']=>$i+1));
    }
}

/**
 * Diese Datei enthält Klassen zur generierung von Dynamischen Listen, die irgendwelche Objekte darstellen können.
 *
 * @author Ueli Wyss
 * @package IPA-Vorbereitung
 */

abstract class iList extends container {
	Const LAYOUT_LIST=0;
	Const LAYOUT_GRID=1;

	Const DATAMODE_MANUAL=0;
	Const DATAMODE_FROMDB=1;

	public $items;
	public $columns;
	public $additionalFields;
    public $foreignTables;

	public $caption;
	public $description;

	public $showSelector=false;
	public $showDescription;
	public $showCaption;

	public $sortable;

	public $highlightOnMark;
	public $highlightColor;
	public $bgColor;

	public $numPages;
	public $itemsPerPage; //Maximale Anzahl der Elemente auf einer Seite.
	public $maxFieldLen;
    public $isSortable=false;
    public static $mootoolsLoaded=false;

	public $actions; //String der eine ','-getrennte Liste enthält, welche die ausführbaren Aktionen bestimmt. (Mögliche Keywords: delete, edit)
	public $commonActions;

	public $ownerScript; //Variable, die den Namen des Scripts enthält, das diese Klasse instanziiert (wird für Formulare verwendet);

	public $tabItem;

	public $icons;

	public static $instances=0;

	public $page;

	public $CSS; //Provisorische Speicherung der CSS-Stylesheets.
	public $JS; //Provisorische Speicherung der JavaScripts.

	public $dataMode;

	public $queryGenerated;

	public $numDBElements;
	public $numFetchDB;

	private $hasSelfJoinCol;



	/**
	 * Array das dieser Klasse übergeben wird und Informationen enthält aus der eine SQL-Abfrage genereiert werden kann.
	 * Die Informationen sind bewusst getrennt in diesem Array, damit man Werte wie z.B. Feldnamen einfach auslesen kann.
	 * Die Abfrage um eine Liste zu generieren kann auch über Zwischen- und Fremdtabellen laufen (siehe unten).
	 * Das Array hat folgenden Aufbau:
	 * 		'select' //Datenbankfelder, die angezeigt werden sollen. Default: * (Alle Felder)
	 * 		'local_table'* //Name der Haupttabelle aus der Datenbank.
	 * 		'mm_table' //Name der Zwischentabelle
	 * 		'foreign_table' //Name der Fremdtabelle
	 * 		'whereClause' //WHERE-Teil der SQL-Abfrage (ohne den Ausdruck "WHERE")
	 * 		'groupBy' //GROUP BY-Teil der SQL-Abfrage (ohne den Ausdruck "GROUP BY")
	 * 		'orderBy' //ORDER BY-Teil der SQL-Abfrage (ohne den Ausdruck "ORDER BY")
	 * 		'limit' //LIMIT-Teil der SQL-Abfrage (ohne den Ausdruck "LIMIT")
	 *
	 * Die mit * markierten Array-Felder sind zwingend.
	 *
	 * @var array
	 */
	public $query;

	function iList($query,&$tabItem=null) {
		$this->super_setDefaults();
		$this->tabItem=$tabItem;
		if(!$this->tabItem) { $this->tabItem=new tabItem('Pseudo'); }

		$this->query=$query;
	}

	private function super_setDefaults() {
		$this->icons=array(
			'ACTIONS_DROP'=>ICON_DIR.'ilist_actions_drop.gif',
			'ACTIONS_EDIT'=>ICON_DIR.'ilist_actions_edit.gif',
			'ACTIONS_ADD'=>ICON_DIR.'ilist_actions_add.gif',
			'SORT_ASC'=>ICON_DIR.'ilist_sort_ASC.gif',
			'SORT_DESC'=>ICON_DIR.'ilist_sort_DESC.gif',
			'MISC_CONNECTOR'=>ICON_DIR.'ilist_connector_line.gif',
			'ACTIONS_ARROW'=>ICON_DIR.'ilist_arrow_check.gif',
			'ACTIONS_ARROW_EXT'=>ICON_DIR.'ilist_arrow_check_ext.gif',
			'NAV_FIRST'=>ICON_DIR.'ilist_nav_first.gif',
			'NAV_PREV'=>ICON_DIR.'ilist_nav_prev.gif',
			'NAV_NEXT'=>ICON_DIR.'ilist_nav_next.gif',
			'NAV_LAST'=>ICON_DIR.'ilist_nav_last.gif',
		);
		$this->highlightOnMark=true;
		$this->highlightColor='#c7dff4';
		$this->maxFieldLen=150;
		$this->itemsPerPage=15;
		$this->page=0;
		$this->bgColor=array(
			'#f2f2f1',
			'#e4e4e3',
		);
		$this->dataMode=self::DATAMODE_FROMDB;
		$this->CSS.='
.ilist {
	font-family:tahoma,verdana;
	empty-cells:show;
}

.ilist_link {
	font-weight:bold;
	text-decoration:none;
	color:#0083ca;
	border:none;
}
.ilist_link:hover {
	text-decoration:underline;
}

.ilist_checkbox {
	text-align:center;
}

.ilist_field {
	/*
	border-left:1px solid #565656;
	border-right:1px solid white;
	*/
	cursor:pointer;
	font-size:11px;

	padding-left:5px;
	padding-right:5px;
	vertical-align:top;

}

.ilist_topLeft {
	border-bottom:1px solid #565656;
	width:30px;
}

.ilist_actions {
	/*
	border-left:1px solid #565656;
	border-right:1px solid #565656;
	*/
	padding-left:3px;
	padding-right:3px;
}

.ilist_actionBar {
	border-bottom:1px solid #565656;
	vertical-align:middle;
	font-size:11px;
	padding-bottom:4px;
}

.ilist_nav_bar{
	font-size:12px;
	text-align:center;
	empty-cells:show;
}

.ilist_nav_button {
	width:72px !important;
	font-size:1px;
}

.ilist_linkImg {
	border:none;
}

.ilist_selector_field {
	font-family:tahoma,verdana;
	font-size:12px;
	border:1px solid #3a5f7b;
}

.ilist_selector_field_marked {
	font-family:tahoma,verdana;
	font-size:12px;
	border:2px solid #980000;
}

.ilist_selection_header{
	font-family:tahoma,verdana;
	font-size:12px;
	font-weight:bold;
	color:#417fc6;
}

.ilist_caption {
	font-weight:bold;
	color:#417fc6;
	font-size:20px;
	padding-bottom:5px;
}

.ilist_description {
	font-weight:bold;
	font-size:11px;
	padding-bottom:20px;
	color:#737373;
}';
	}

	/**
	 * Fügt einen Eintrag (zu $items) vom Typ iListItem hinzu.
	 * Damit dies möglich ist, wird der Daten-Modus ($dataMode) auf Manuell (iList::DATAMODE_MANUAL) gestellt sein.
	 * Das heisst, die angegebene Datenbankabfrage ($query) wird nicht mehr berücksichtigt.
	 * Der Parameter $index bestimmt an welcher Stelle der Eintrag eingefügt werden soll.
	 * $index kann aber auch ein String mit dem Inhalt "end" (Eintrag ans Ende) oder "begin" (Eintrag am Anfang) sein.
	 * Alle nachfolgenden Einträge rutschen in der Reihenfolge nach.
	 *
	 * @param iListItem $item
	 * @param integer $index
	 * @return boolean
	 */
	public function addItem(object $item,$index) {
		if($index == "begin") {
			$index=0;
		} elseif($index == "end" || $index > count($this->items)) {
			$index=count($this->items);
		}
		if(count($this->items)!=0) {
			$i=count($this->items)-1;
			while($i>=$index) {
				$this->items[$i+1]=$this->items[$i];
				$this->items[$i+1]->index=($i+1);
				$i--;
			}
		}
		$item->index=$index;
		$this->items[$index]=$item;
		return true;
	}


	/**
	 * Löscht ein Eintrag aus dem $items-Array an der im $index-Parameter angegebenen Stelle.
	 * $index kann auch die Werte "last" (letzter Eintrag) oder "first" (erster Eintrag) annehmen.
	 * Alle nachfolgenden Einträge rutschen in der Reihenfolge nach.
	 *
	 * @param integer $index
	 * @return boolean
	 */
	public function removeItem($index) {
		if(!$index>(count($this->items)-1)) {
			if($index == "last") { $index = $this->items[count($this->items)-1]; }
			elseif($index == "first") { $index = 0; }

			$i=$index;
			while($i>$index) {
				$this->items[$i]=$this->items[$i+1];
				$this->items[$i]->index=$i;
				$i++;
			}
			unset($this->items[count($this->items)-1]);
			return true;
		} else {
			return false;
		}
	}

	public function wrapFooter() {
		$numPages=$this->getNumPages();

		$content='
<tr>
<td style="text-align:center;">
<table border="0" cellpadding="0" cellspacing="0" class="ilist_nav_bar">
	<tr>
		<td class="ilist_nav_button">';
		if($this->page!=0) {
			$content.='
			<a href="'.div::http_getURL(array('page_'.$this->getName()=>0,'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex())).'">
				<img class="ilist_linkImg" src="'.$this->icons['NAV_FIRST'].'">
			</a>';

		}
		$content.='
		</td>';
		$content.='
		<td class="ilist_nav_button">';
		if($this->page!=0) {
			$content.='
			<a href="'.div::http_getURL(array('page_'.$this->getName()=>($this->page-1),'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex())).'">
				<img class="ilist_linkImg" src="'.$this->icons['NAV_PREV'].'">
			</a>';

		}
		$content.='
		</td>';
		$content.='
<td class="ilist_nav_pages"><nobr>';

		for($i=0;$i<$numPages;$i++) {
			$content.='[';
			if($this->page==$i) {
				$content.='<b><font style="font-size:larger">'.($i+1).'</font></b>';
			} else {
				$content.='<a class="ilist_link" href="'.div::http_getURL(array('page_'.$this->getName()=>$i,'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex())).'">'.($i+1).'</a>';
			}
			$content.=']';
		}

		$content.='
</nobr></td>';
		$content.='
		<td class="ilist_nav_button">';
		if($this->page<($numPages-1)) {
			$content.='
			<a href="'.div::http_getURL(array('page_'.$this->getName()=>($this->page+1),'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex())).'">
				<img class="ilist_linkImg" src="'.$this->icons['NAV_NEXT'].'">
			</a>';

		}
		$content.='
		</td>';
		$content.='
		<td class="ilist_nav_button">';
		if($this->page<($numPages-1)) {
			$content.='
			<a href="'.div::http_getURL(array('page_'.$this->getName()=>($numPages-1),'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex())).'">
				<img class="ilist_linkImg" src="'.$this->icons['NAV_LAST'].'">
			</a>';

		}
		$content.='
		</td>
	</tr>
</table>
</td>
</tr>';
		return $content;
	}

	/**
	 * Schickt aus den Angaben im $query-Array eine Abfrage an die Datenbank und füllt das Resultat ins $items-Array ab.
	 * Dazu wird der Daten-Modus auf DATA_MODE_FROMDB gestellt.
	 *
	 * @return boolean
	 */
	public function fetchItems($force=false) {
		global $db;

		if($this->numFetchDB==0 || $force) {
			$this->prepQuery();

			$this->dataMode=ilist::DATAMODE_FROMDB;

			if($this->query['local_table']) {

				/*if($this->query['mm_table']!='' && $this->query['foreign_table']!='') {
					if($this->numFetchDB==0) { $this->query['whereClause']="AND ".$this->query['whereClause']; }
					$result = $db->exec_SELECT_mm_query($this->query['select'],$this->query['local_table'],$this->query['mm_table'],$this->query['mm_key'],$this->query['mm_foreign_key'],$this->query['foreign_table'],$this->query['whereClause'],$this->query['groupBy'],$this->query['orderBy'],$this->query['limit']);
				} else {
					$result = $db->exec_SELECTquery($this->query['select'],$this->query['local_table'],$this->query['whereClause'],$this->query['groupBy'],$this->query['orderBy'],$this->query['limit']);
				}*/
				$result=$db->sql_query($this->query['query']);

				if($this->labelsFromDB) {
					foreach($this->columns as $fieldName=>$col) {
						$this->columns[$fieldName]['label']=$fieldName;
					}
				}

				while($row = $db->sql_fetch_assoc($result)) {

                    //echo $db->view_array($row);
					if($this->query['mm_table']) {
						$uid=$row["localKey"];
						$foreign_uid=$row["foreignKey"];
						unset($row["foreignKey"]);
						unset($row["localKey"]);
					} else {
						$uid=$row["primaryKey"];
						$foreign_uid=div::http_getGP('foreign_uid')?div::http_getGP('foreign_uid'):div::http_getGP('uid');
						unset($row["primaryKey"]);
					}


					foreach($this->columns as $fieldName=>$col) {
						if(is_array($col['allocation'])) {
							$row[$fieldName]=$col['allocation'][$row[$fieldName]];
						}

					}
                    
					$item=new ilistItem($row);
					$item->uid=$uid;
					$item->foreign_uid=$foreign_uid;

					$this->addItem($item,"end");
				}
				$this->numFetchDB++;
				$this->filladditionalFields();
				return true;
			} else { return false; }
		} else { return false; }
	}

	public function hasSelfJoinCol() {
		if(!isset($this->hasSelfJoinCol)) {
			foreach($this->columns as $col) {
				if($this->query['local_table']==$col['foreign_table']) {
					//echo "HASSELFJOIN";
					$this->hasSelfJoinCol=true;
					return true;
				}
			}
			$this->hasSelfJoinCol=false;
			return false;
		} else {
			return $this->hasSelfJoinCol;
		}
	}

	/**
	 * @todo SELF-JOIN möglichkeit zuende führen.
	 *
	 */
	public function prepQuery($force=false) {
		global $db;

		//echo "QUERY:".$db->view_array($this->query);
		if(!$this->queryGenerated || $force) {
			$this->query['limit']=$this->getStart().','.$this->itemsPerPage;
			$this->query['select']=$this->query['select']==''?'*':$this->query['select'];

			//$this->query['local_table_alias']=$this->query['local_table']."_2";

			if(is_array($this->columns)) {
				//div::debug("List: ".$this->caption."-->COLUMNS",$db->view_array($this->columns));
				$joins=array();
				//$this->query['select']='';
				$this->query['selfjoin_tables']='';



				foreach($this->columns as $col) {


					/*if($col['foreign_table']) {
						/*if($this->query['local_table_alias']==$this->query['local_table']) {
							$col['foreign_table_alias']=$col['foreign_table'];
						} else {
							//echo "    --> IS SELFJOIN COL: ".$col['dbField']."<br>";
							$col['foreign_table_alias']=substr($col['foreign_table'],0,1).'2';
							//$this->query['selfjoin_tables'].=$this->query['selfjoin_tables']?", ":"";
							//$this->query['selfjoin_tables'].=$col['foreign_table']." ".$col['foreign_table_alias'];
						}*/
						//$col['foreign_table_alias']=$this->getTableAlias($col['foreign_table']);
                        /*$display_query='';    
						$foreign_display=explode(",",$col['foreign_display']);
						for($i=0;$i<count($foreign_display);$i++) {
							$display_query.=$col['foreign_table_alias'].".".$foreign_display[$i];
							if($i!=(count($foreign_display)-1)) {
								$display_query.=",', ',";
							}
						}
						$display_query=" CONCAT(".$display_query.") AS ".$col['dbField'];
						$this->query['select'].=$display_query.",";        */

						//if($this->query['local_table']!=$col['foreign_table']) {
						$whereAddition=$col['local_table'].".".$col['dbField']."=".$col['foreign_table_alias'].".".$col['foreign_key'];
						/*$joins[]=array(
							'table'=>($col['foreign_table_alias']?$col['foreign_table']." ".$col['foreign_table_alias']:$col['foreign_table']),
							'on'=>$whereAddition,
						);  
						} else {
							$whereAddition="(".$this->query['local_table_alias'].".".$col['dbField']."=".$col['foreign_table_alias'].".uid)";
							$this->query['whereClause'].=(preg_match("/".$whereAddition."/",$this->query['whereClause'])?"":($this->query['whereClause']?' AND '.$whereAddition:$whereAddition));
						}
                        


						//preg_match("/".$whereAddition."/",$this->query['join_on'])?"":($this->query['join_on']?" AND ".$whereAddition:$whereAddition);
						//$leftJoin.=div::var_isInList($leftJoin,$col['foreign_table'])?"":$col['foreign_table'].",";
					} else {
						//$this->query['select'].=(preg_match("/\./",$col['dbField'])?$col['dbField']:($this->query['foreign_table']?$col['dbField']:$this->query['local_table_alias'].".".$col['dbField'])).",";
					}*/

					$this->query['selector']=$this->query['selector'] AND $this->query['local_table']!=$this->query['local_table_alias'] AND ($this->query['selector']==$col['dbField'] OR $this->query['selector']==$this->query['local_table_alias'].".".$col['dbField'] OR $this->query['selector']==$col['foreign_table_alias'].".".$col['dbField'])?($col['foreign_table']?$col['foreign_table_alias'].".".$this->query['selector']:$this->query['local_table_alias'].".".$this->query['selector']):"";

				}


/*               foreach($this->foreignTables as $tableName=>$data) {
                   $joins[]=array(
                            'table'=>$tableName,
                            'on'=>$whereAddition,
                        );
               } */
				/*$leftJoins=$leftJoin?explode(",",substr($leftJoin,0,(strlen($leftJoin)-1))):null;
				if($leftJoins) {
					$this->query['join']=$this->query['local_table'];
					foreach($leftJoins as $join) {
						$this->query['join'].=" LEFT JOIN ".$join." ON ";
					}
				}*/

				//if(is_array($joins)) {
//					$this->query['join']='';
//					foreach($joins as $join) {
//                        if()
//						$this->query['join'].=" LEFT JOIN ".$join['table']." ON ".$join['on'];
//					}
//				}

				//$this->query['select']=substr($this->query['select'],0,strlen($this->query['select'])-1);
			}

/*			$primaryKeys=$db->getPrimaryKey($this->query['local_table']);
            
			if($this->query['mm_table']) {
				$this->query['select'].=", ".$this->query['mm_table'].".".$this->query['mm_key']." as localKey";
				$this->query['select'].=", ".$this->query['mm_table'].".".$this->query['mm_foreign_key']." as foreignKey";
				$this->query['whereClause'].=$this->query['whereClause']?" AND":"";
				$this->query['whereClause'].=" ".$this->query['local_table_alias'].".uid=".$this->query['mm_table'].".".$this->query['mm_key']." AND ".$this->query['foreign_table'].".uid=".$this->query['mm_table'].".".$this->query['mm_foreign_key'];
				$this->query['additional_tables']=$this->query['mm_table'].", ".$this->query['foreign_table'];
			} else {
				//echo "LOCAL_TABLE:".$this->query['local_table']."<br>";
				$local_table=explode(",",$this->query['local_table_alias']);
				//$this->query['select'].=", ".$local_table[0].".".$primaryKeys[0]." as primaryKey";
			} */

			//$this->query['tables']=($this->query['local_table_alias']!=$this->query['local_table']?$this->query['local_table']." ".$this->query['local_table_alias']:$this->query['local_table']).($this->query['additional_tables']?", ".$this->query['additional_tables']:"");
			//$this->query['tables'].=($this->query['selfjoin_tables']?", ".$this->query['selfjoin_tables']:"");

			$this->query['whereClause_normal']=($this->query['whereClause']?" WHERE ".$this->query['whereClause']:"");
			//Ist noch nicht fehlerfrei. funktioniert jedoch bei nicht allzukomplizierten Abfragen.

			$this->query['whereClause_selected']=$this->query['whereClause_normal'].($this->query['selector']?($this->query["whereClause_normal"]?" AND ".$this->query['selector']:" WHERE ".$this->query['selector']):"");

			$this->query['orderBy']=$this->query['orderBy']?" ORDER BY ".implode(" ",$this->getOrderBy()):'';
			$this->query['limit']=($this->query['limit']?" LIMIT ".$this->query['limit']:"");

			$this->query['query_noLimit']="SELECT ".$this->query['select']." FROM ".$this->query['tables'].$this->query['join'].$this->query['whereClause_normal'].$this->query['orderBy'];
			$this->query['query_selected']="SELECT ".$this->query['select']." FROM ".$this->query['tables'].$this->query['join'].$this->query['whereClause_selected'].$this->query['orderBy'];
			//$this->query['query_noLimit']=$this->parseString($this->query['query_noLimit']);
			$this->query['query']="SELECT ".$this->query['select']." FROM ".$this->query['tables'].$this->query['join'].$this->query['whereClause_selected'].$this->query['orderBy'].$this->query['limit'];


			//if(!$this->queryGenerated) echo $this->caption.'<br>'.$db->view_array($this->query);
			$this->queryGenerated=true;
		}

	}

	/**
	 * Filtert Informationen für die Konfiguration dieses Objekts aus den Arrays $_GET und $_POST und weist sie den entsprechenden Eigenschaften zu.
	 *
	 */
	public function init() {
		global $db;

		$this->query['local_table_alias']=$this->getTableAlias();

		reset($this->columns);
		while(list($key,$value) = each($this->columns)) {
			//echo "KEY: ".$key."<br>";
			$this->columns[$key]['foreign_table_alias']=($this->columns[$key]['foreign_table']?$this->getTableAlias($this->columns[$key]['foreign_table']):"");
		}


		if($this->getNumDBItems_all()) {
			if(div::http_getGP('orderBy_'.$this->getName())) {
				$this->query['orderBy']=div::http_getGP('orderBy_'.$this->getName()).' '.(div::http_getGP('dir_'.$this->getName())?div::http_getGP('dir_'.$this->getName()):'ASC');
			}
			if(div::http_getGP('page_'.$this->getName())) {
				$this->page=div::http_getGP('page_'.$this->getName());
			}
			if(div::http_getGP('selector_'.$this->getInstances())) {
				$tmp=explode("=",div::http_getGP('selector_'.$this->getInstances()));

				$config=table::getFieldConfig($this->query['local_table'],$tmp[0]);
				//echo $db->view_array($config);
				if($tmp[1]) {
					if ($config['type']=='datetime' OR $config['type']=='date' OR $config['type']=='time') {
						$where=$tmp[0]." Like '%".div::date_gerToSQL($tmp[1])."%'";
					} elseif($allocation=table::getAllocation($this->query['local_table'],$tmp[0])) {
						foreach($allocation as $val=>$display) {
							if(preg_match("/".$tmp[1]."/",$display)) {
								$where=$tmp[0]."='".$val."'";
							}
						}
						if(!$where) {
							$where=$tmp[0]." Like '%".$tmp[1]."%'";
						}
					} else {
						$where=$tmp[0]." Like '%".$tmp[1]."%'";
					}
				}

				$this->query['selector']=$this->query['selector']?$where.' AND '.$this->query['selector']:$where;
			}
		}

		$this->prepQuery(true);
	}

	public function getName() {
		return 'iList_'.self::getInstances();
	}

	public function parseString($str,$args=null) {
		if(is_string($str)) {
			$str=div::var_parseString($str,$args);
			$str=preg_replace("/<local_table>/",$this->query['local_table'],$str);
		}
		return $str;
	}



	public function getJSCode() {
		$code.="var highlightColor_".self::getInstances()."='".$this->highlightColor."';
var highlight_".self::getInstances()."=".div::var_boolToString($this->highlightOnMark).";
var checkable_".self::getInstances()."=".div::var_boolToString($this->hasCommonActions()).";
numLists++;";
		if(self::getInstances()==0) {
			$code.="

var checkboxprefix='_check_';
var rowPrefix='_item_';
var numLists=0;

function ilist_checkBoxes() {

			var i=0;
			var listNum=0;
			for(listNum=0;listNum<=numLists;listNum++) {
				if(eval('highlight_'+listNum) && eval('checkable_'+listNum)) {
					while(document.getElementsByName(listNum+checkboxprefix+i)[0]) {
						if(document.getElementsByName(listNum+checkboxprefix+i)[0].checked){
							document.getElementById(listNum+rowPrefix+i).style.backgroundColor=eval('highlightColor_'+listNum);
						} else {

							document.getElementById(listNum+rowPrefix+i).style.backgroundColor=document.getElementById(listNum+rowPrefix+i).oldColor;
						}
						i++;
					}
				}
				i=0;
			}
	}

function ilist_checkItem(name) {
	if(eval('checkable_'+name.substr(0,1))) {
		if(document.getElementsByName(name)[0].checked) {
			document.getElementsByName(name)[0].checked=false;
		} else {
			document.getElementsByName(name)[0].checked=true;
		}

		ilist_checkBoxes();
	}
}

function ilist_markAll(listNum) {
	var i=0;
	var check;
	if(document.getElementsByName(listNum+checkboxprefix+'0')[0]) {

		check=!document.getElementsByName(listNum+checkboxprefix+'0')[0].checked;
	}
	while(document.getElementsByName(listNum+checkboxprefix+i)[0]) {
		document.getElementsByName(listNum+checkboxprefix+i)[0].checked = check;
		i++;
	}
	ilist_checkBoxes();
}

function submitSelector(event,obj) {
	if (event && event.keyCode == 13) {
		if(obj.url.match(/=/)) {
			var prefix='&';
		} else {
			var prefix='?';
		}
		var addition='&'+obj.realName+'='+obj.name+'%3D'+obj.value;
   		location.href=obj.url+addition;
   	}
}

function empty(obj) {
	obj.value='';
}

function multiDelete(listNum,tableName) {

	var index=0;
	var checkbox;
	var uids='';
	var f_uids='';

	while(checkbox=document.getElementById(listNum+'_check_'+index)) {
		if(checkbox.checked) {
			uids+=checkbox.uid+',';
			f_uids+=checkbox.foreign_uid+',';
		}
		index++;
	}
	if(uids!='') {
		uids=uids.substr(0,(uids.length-1));
		f_uids=f_uids.substr(0,(f_uids.length-1));
		top.dropElement(tableName,uids,f_uids);
	}

}";

		}

		$this->JS=$code;
		return $code;
	}

	/**
	 * Wählt nach jedem Aufruf die nächste im Array $bgColor gepeicherte Farbe auf und gibt sie zurück.
	 * Das heisst, wenn STYLEMODE_AUTO ausgewählt ist und das $bgColor-Array mehrere Einträge enthält, wird die Hintergrundfarbe der Zeilen gewechselt.
	 *
	 * @return string
	 */
	public function getBgColor() {
		static $colorInd;                                
		if($colorInd==(count($this->bgColor)-1) OR $this->isSortable) {
			$colorInd=0;
		} else {
			$colorInd++;
		}
		return $this->bgColor[$colorInd];
	}

	public static function getInstances() {
		if(self::$instances==0) {
			return '0';
		} else {
			return self::$instances;
		}
	}

	public function filladditionalFields() {
		if(is_array($this->additionalFields AND is_array($this->items))) {
			foreach($this->items as $item) {
				foreach($this->additionalFields as $label=>$field) {
					$item->content[]=$this->parseString($field,array('uid'=>$item->uid,'foreign_uid'=>$item->foreign_uid));
				}
			}
		}

	}

	public function getStart() {
		return ($this->itemsPerPage*$this->page);
	}

	public function wrapSelector() {
		global $db;
		if($this->showSelector) {

			$content.='
<tr>';
			if($this->hasCommonActions()) {
				$content.='
<td></td>';
			}
			$content.='
<td colspan="'.count($this->columns).'" class="ilist_selection_header">Selektion</td>';
			$content.='
</tr>
<tr>
		';
			if($this->hasCommonActions()) {
				$content.='
<td></td>';
			}

			foreach($this->columns as $col) {
				$selector=explode("=",div::http_getGP('selector_'.self::getInstances()));
				$urlAdd=array(
						'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex(),
				);

				//$col['foreign_table_alias']=$this->getTableAlias($col['foreign_table']);
				$selector_name = ($this->query['local_table']!=$this->query['local_table_alias']?($col['foreign_table']?$col['foreign_table_alias'].".".$col['foreign_display']:$this->query['local_table_alias'].".".$col['dbField']):$col['dbField']);

				if($selector[0]==$selector_name AND $selector[1]) {
					$value=$selector[1];
					$class="ilist_selector_field_marked";
				} else {
					$value=$col['label'];
					$class="ilist_selector_field";
				}


				$content.='
					<td><input class="'.$class.'" url="'.div::http_getURL($urlAdd).'" onClick="empty(this)" realName="selector_'.self::getInstances().'" onkeypress="return submitSelector(event,this)" style="width:100%" type="text" name="'.$selector_name.'" value="'.$value.'"></td>';
			}
			if($this->hasActions()) {
				$content.='
<td></td>';
			}
			$content.='
</tr>';
			$content.='
<tr>
		';
			if($this->hasCommonActions()) {
				$content.='
<td></td>';
			}
			$content.='
<td colspan="'.count($this->columns).'" style="height:30px">&nbsp;</td>';
			$content.='
</tr>';


		}
		return $content;
	}

	public function getNumDBItems_selected() {
		global $db;

		$this->prepQuery();

		$result=$db->sql_query($this->query['query_selected']);
		$num=$db->sql_num_rows($result);
		$this->numDBElements=$num;
		$this->numFetchDB++;

		return $this->numDBElements;
	}

	public function getNumPages() {
		$numItems=$this->getNumDBItems_selected();

		$numPages=($numItems%$this->itemsPerPage)==0?($numItems/$this->itemsPerPage):(($numItems-($numItems%$this->itemsPerPage))/$this->itemsPerPage)+1;

		return $numPages;
	}

	public function getNumDBItems_all() {
		global $db;

		$this->prepQuery();

		$result=$db->sql_query($this->query['query_noLimit']);
		$num=$db->sql_num_rows($result);
		$this->numDBElements=$num;
		$this->numFetchDB++;

		return $this->numDBElements;
	}

	public function getNumDBItems_showed() {
		global $db;

		$this->prepQuery();

		$result=$db->sql_query($this->query['query']);
		$num=$db->sql_num_rows($result);
		$this->numDBElements=$num;
		$this->numFetchDB++;

		return $this->numDBElements;
	}

	public function hasCommonActions() {
		return $this->commonActions?true:false;
	}

	public function hasActions() {
		return is_array($this->actions);
	}

	public function wrapActionBar() {
		if($this->hasCommonActions()) {
			$content.='<tr>
			<td>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td class="ilist_actionBar">'.div::htm_wrapIcon($this->icons['ACTIONS_ARROW']).'
			<a onClick="javascript:ilist_markAll('.self::getInstances().');" style="cursor:pointer;">Alle/Keine markieren</a>'.div::htm_wrapIcon($this->icons['ACTIONS_ARROW_EXT']).'
			markierte: '.$this->wrapCommonActions().'</td>
			</tr>
			</table>
			</td>
			</tr>';
		}
		return $content;
	}

	/**
	 * Formt die orderBy-Clause aus dem $query-Array in ein folgendermassen struckturiertes Array um:
	 * $data[0]-->Array, das die Spaltennamen, nach denen sortiert wurde enthält.
	 * $data[1]-->'ASC' (vorwärts sortiert) oder 'DESC' (rückwärts sortiert).
	 *
	 * @return array
	 */
	public function getOrderBy() {
		$data=trim(str_replace('ORDER BY ','',$this->query['orderBy']));
		$data=explode(' ',$data);
		$data[1]=isset($data[1])?$data[1]:'ASC';

		return $data;
	}

	public function wrapCommonActions() {
        $commonActions=explode(",",$this->commonActions);
        
		foreach($commonActions as $name) {
			switch ($name) {
				case 'drop':
					$table=$this->query['mm_table']?$this->query['mm_table']:$this->query['local_table'];
					$content.='<img src="'.$this->icons["ACTIONS_DROP"].'" onClick="'.$this->parseString("multiDelete(".self::getInstances().",'".$table."')").'">';
					break;
				case 'add':
					$content.='<img src="'.$this->icons["ACTIONS_ADD"].'" style="cursor:pointer;" onClick="multiAction(\''.htmlspecialchars($action).'\')" alt="Zuordnen">';
				break;
			}
		}
		return $content;
	}

	public function wrapActions($index) {
		global $db;
			/*if($index==0) { $borderTop='border-top:1px solid #565656;'; }
			if($index==(count($this->items)-1)) { $borderBottom='border-bottom:1px solid #565656;'; }*/

			$content.='<td class="ilist_actions" style="'.$borderTop.$borderBottom.'">';
			//$actions=explode(",",$this->actions);
			if($this->hasActions()) {
				foreach($this->actions as $name=>$action) {
                    
                    
                    
					$action=$this->parseString($action,array('uid'=>$this->items[$index]->uid,'foreign_uid'=>$this->items[$index]->foreign_uid));
                    $action=div::var_parseString2($action,$this->items[$index]->content,'{','}');
					switch ($name) {   
						case 'drop':
							$content.='<img src="'.$this->icons["ACTIONS_DROP"].'" style="cursor:pointer;" onMouseDown="'.$action.'" alt="Löschen">';
							break;
						case 'edit':
							$content.='<img src="'.$this->icons["ACTIONS_EDIT"].'" style="cursor:pointer;" onMouseDown="'.$action.'" alt="Eigenschaften editieren">';
							break;
						case 'add':
							$content.='<img src="'.$this->icons["ACTIONS_ADD"].'" style="cursor:pointer;" onMouseDown="'.$action.'" alt="Zuordnen">';
							break;
						default:
							if(is_array($action)) {
                                
                                
								$onClick=$this->parseString($action['action'],array('uid'=>$this->items[$index]->uid,'foreign_uid'=>$this->items[$index]->foreign_uid));
								$content.='<img src="'.$action['icon'].'" style="cursor:pointer;" onClick="'.$onClick.'" alt="'.$action['description'].'">';
							}
					}
				}
			}

			$content.='</td>';
		return $content;
	}

	/**
	 * Liefert die Feldnamen bzw. Aliase einer Abfrage in einem Array.
	 *
	 * @param resource $result
	 * @return array
	 */
	public function getFieldNames($result) {
		global $db;

		$fieldInfo = $db->getFieldInfo($result);
		$data = array();

		foreach($fieldInfo as $info) {
			array_push($data,$info['name']);
		}
		return $data;
	}

	function wrapDescription() {
		if($this->showDescription) {
			$content='
<tr>
	<td class="ilist_description">
		'.$this->description.'
	</td>
</tr>';
		}
		return $content;
	}

	public function wrapCaption() {
		if($this->showCaption) {
			$content='
<tr>
	<td class="ilist_caption">
		'.$this->caption.'
	</td>
</tr>';
		}
		return $content;
	}

	function getTableAlias($tableName=null) {
		static $tables=1;

		if(!$tableName) {
			$tableName=$this->query['local_table'];
		}

		if($this->hasSelfJoinCol()) {
			$alias = substr($tableName,0,2).$tables;
			$tables++;

			return $alias;
		} else {
			return $tableName;
		}
	}
}

class gridList extends iList {
	public $itemsPerRow;

	public $showFieldNames;

	function gridList($query,&$tabItem=null) {
		$this->setDefaults();
		//echo $query['whereClause'];
		parent::iList($query,$tabItem);
	}

	private function setDefaults() {
		$this->showFieldNames=true;
		$this->itemsPerRow=4;
	}

	public function wrapContent() {
		global $db;
		$this->init();

		if($this->dataMode==iList::DATAMODE_FROMDB) {
			$this->fetchItems(true);
		}

		//echo $this->query['query'];

		$content=array();
		$content['JS']=$this->getJSCode();

		if(self::getInstances()==0) {
			$content['CSS']=$this->CSS;
		}
		$content['main'].='<table class="ilist" border="0" cellpadding="0" cellspacing="1">';

		$content['main'].=$this->wrapCaption();

		if((div::http_getGP('selector_'.self::getInstances()) || is_array($this->items)) && $this->getNumDBItems_all()) {
			if($this->showDescription) {
				$content['main'].=$this->wrapDescription();
			}
			$content['main'].=$this->wrapSelector();
		}

		if(is_array($this->items)) {
			//echo $db->view_array($this->items);
			//echo "Count:".count($this->items)."<br>";
			//echo "ITEMSPERPAGE:".$this->itemsPerPage."<br>";
			//$content['main'].=$this->wrapHeadLine();
			$numRows=($this->itemsPerPage-($this->itemsPerPage%$this->itemsPerRow))/$this->itemsPerRow+1;
			//echo "NUMROWS:".$numRows.'<br>';
			for($i=0;$i<$numRows;$i++) {
				$bgColor=$this->getBgColor();
				$content['main'].='<tr bgcolor="'.$bgColor.'" oldColor="'.$bgColor.'">
';
				for($g=0;$g<$this->itemsPerRow;$g++) {
					//echo ($i*$this->itemsPerRow)+$g."<br>";
					if($this->items[($i*$this->itemsPerRow)+$g]) {

						$content['main'].=$this->wrapItem(($i*$this->itemsPerRow)+$g);
					} else break;
				}
				$content['main'].='</tr>
';
			}

			$content['main'].=$this->wrapActionBar();
			$content['main'].=$this->wrapFooter();

			//div::var_saveObject($this,$this->getName());
			$this->instances++;
		} else {
			$content['main'].='<tr><td>Keine Einträge gefunden!</td></tr>';
		}
		$content['main'].='</table>';


		return $content;
	}

	private function wrapItem($index) {
		$content.='<td>
<table class="ilist" border="0" cellpadding="0" cellspacing="0">
';

		foreach($this->items[$index]->content as $key=>$item) {
			$content.='<tr>
<td class="ilist_field" width="30px" onMouseDown="javascript:ilist_checkItem(\''.self::getInstances().'_check_'.$index.'\');" style="'.$checkBorderTop.$borderBottom.'">
';
			if($this->columns[$key]['isImage']) {
				$thumb=new thumb($this->columns[$key]['imagePath'].$item,TEMP_DIR);
				$thumb->bgColor=new color($this->getBgColor());
				$content.=div::htm_wrapIcon($thumb->getThumb());
			} else {
				if($this->showFieldNames) {
					$content.='<b>'.$this->columns[$key]['label'].'</b>: ';
				}
				$content.=$item;
			}

			$content.='</td>
</tr>
';
		}

		$content.='<tr>
<td class="ilist_field" width="30px" onMouseDown="javascript:ilist_checkItem(\''.self::getInstances().'_check_'.$index.'\');" style="'.$checkBorderTop.$borderBottom.'">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
';
		if($this->hasCommonActions()){
			$content .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="ilist_connector"></td>
        </tr>
        <tr>
          <td class="ilist_checkbox">
              <input type="checkbox" foreign_uid="'.$this->items[$index]->foreign_uid.'" uid="'.$this->items[$index]->uid.'" id="'.self::getInstances().'_check_'.$index.'" name="'.self::getInstances().'_check_'.$index.'" onClick="return false;">
          </td>
        </tr>
        <tr>
          <td class="ilist_connector"></td>
        </tr>
      </table>';


		}
		$content.='</td>
';
		$content.=$this->wrapActions($index);
		$content.='</td>
</tr>';
		$content.='</table>
</td>';
		$content.='</table>
</td>
';
		return $content;
	}

	public function getNumCols() {
		return $this->itemsPerRow;
	}
}

class tabList extends iList {


	/*Const STYLINGMODE_AUTO=0;
	Const STYLINGMODE_MANUAL=1;

	*/



	//public $dataMode; //0=manual; 1=fromDB





	//public $query;




	//public $layout; //LAYOUT_LIST(0) || LAYOUT_GRID(1);
	//public $stylingMode; //STYLINGMODE_AUTO(0) || STYLINGMODE_MANUAL(1)
	 //Kann die Liste sortiert werden?
	 //Maximale Länge des Datenfeld-Inhalts.













	//public $icons; //Enthält die Pfade zu den Bildern und Icons, die in dieser Klasse verwendet werden.



/*	public $labelsFromDB;


*/

	public $showHeadLine;




	function tabList($query,&$tabItem=null) {
		$this->setDefaults();
		parent::iList($query,$tabItem);
	}



	private function setDefaults() {

		$this->sortable=true;
		$this->actions='edit';
		$this->commonActions='drop';
		$this->showHeadLine=true;

		$this->showDescription=true;
		$this->showCaption=true;
		$this->showSelector=false;


		$this->CSS.= '

.ilist_header_left {
	/*
	background-image:url('.ICON_DIR.'ilist_header_left.gif);
	background-repeat:no-repeat;
	background-position:left;
	*/
	border-left:1px solid #46b0ee;
	border-top:1px solid #46b0ee;
	border-bottom:1px solid #46b0ee;
	width:5px !important;
	height:15px;
}

.ilist_header_mid {
	/*
	background-image:url('.ICON_DIR.'ilist_header_mid.gif);
	background-repeat:repeat-x;
	*/
	color:#46b0ee;
	font-weight:bold;
	font-size:11px;
	vertical-align:middle;
	height:15px;
	padding-left:10px;
	padding-right:10px;
	border-top:1px solid #46b0ee;
	border-bottom:1px solid #46b0ee;

}

.ilist_header_right {
	/*
	background-image:url('.ICON_DIR.'ilist_header_right.gif);
	background-repeat:no-repeat;
	background-position:right;
	*/
	width:6px !important;
	height:15px;
	margin-right:2px;
	border-right:1px solid #46b0ee;
	border-top:1px solid #46b0ee;
	border-bottom:1px solid #46b0ee;


}

.ilist_connector {
	background-position:center;
	background-image:url('.ICON_DIR.'ilist_connector_line.gi);
	background-repeat:repeat-y;
	height:5px;
}';
	}

	private function wrapHeadLine() {
		if($this->showHeadLine) {
			$content.='<tr>
			';

			if($this->hasCommonActions()) { $content.='<td></td>'; }

			$orderBy=$this->getOrderBy();
			$i=0;

			foreach($this->columns as $col) {
				if(div::var_isInList($orderBy[0],$col['dbField'])) {
					if($orderBy[1]=='ASC') {
						$arrow=div::htm_wrapIcon($this->icons['SORT_ASC']);
						$urlAdd=array(
							'orderBy_'.$this->getName()=>$col['dbField'],
							'dir_'.$this->getName()=>'DESC'
						);

					} else {
						$arrow=div::htm_wrapIcon($this->icons['SORT_DESC']);
						$urlAdd=array(
							'orderBy_'.$this->getName()=>$col['dbField'],
							'dir_'.$this->getName()=>'ASC'
						);
					}
					$arrow='<td class="ilist_header_mid" style="text-align:right;">'.$arrow.'</td>';

				} else {
					$arrow='';
					$urlAdd=array(
							'orderBy_'.$this->getName()=>$col['dbField'],
							'dir_'.$this->getName()=>'ASC',
							'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex(),
					);
				}
				$href=' href="'.div::http_getURL($urlAdd).'"';
				$content.='<td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="ilist_header_left" style="">&nbsp;</td>
          <td class="ilist_header_mid"><a class="ilist_link"'.$href.'>'.$col['label'].'</a></td>
		  '.$arrow.'
          <td class="ilist_header_right">&nbsp;</td>
        </tr>
      </table></td>';
				$i++;
			}

			foreach($this->additionalFields as $label=>$inhalt) {
				$content.='<td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="ilist_header_left" style="">&nbsp;</td>
          <td class="ilist_header_mid">'.$label.'</td>
          <td class="ilist_header_right">&nbsp;</td>
        </tr>
      </table></td>';
			}
			$content.='</tr>
			';
		}
		return $content;
	}

	public function wrapContent() {
		global $db;
		$this->init();

		if($this->dataMode==iList::DATAMODE_FROMDB) {
			$this->fetchItems(true);
		}

		$content['JS']=$this->getJSCode();

		if(self::getInstances()==0) {
			$content['CSS']=$this->CSS;
		}
		$content['main'].='<table class="ilist" border="0" cellpadding="0" cellspacing="1">';

		$content['main'].=$this->wrapCaption();                                                              
        if($this->showDescription) {
             $content['main'].=$this->wrapDescription();
        }
        $content['main'].='<tr><td><table class="ilist" border="0" cellpadding="0" cellspacing="1" id="ilist_'.$this->getInstances().'"><thead>';
		if((div::http_getGP('selector_'.self::getInstances()) || is_array($this->items)) && $this->getNumDBItems_all()) {
			$content['main'].=$this->wrapSelector();
		}
        
		if(is_array($this->items)) {
            
			$content['main'].=$this->wrapHeadLine();
            $content['main'].='</thead><tbody id="ilist_body_'.$this->getInstances().'">';

            
            
          
			foreach($this->items as $key=>$item) {
				$content['main'].=$this->wrapItem($key);
			}                   
            $content['main'].='</tbody></table></td></tr><tr><td>';   
			$content['main'].=$this->wrapActionBar();
			$content['main'].=$this->wrapFooter();

			//div::var_saveObject($this,$this->getName());
			
		} else {
			$content['main'].='<tr><td>Keine Einträge gefunden!</td></tr></thead></table>';
		}
		$content['main'].='</td></tr></table>';
        if($this->isSortable) {
                           
            $sortable_field=explode(".",$this->query['sortable_field']);
            if(!self::$mootoolsLoaded) {  
                self::$mootoolsLoaded=true;
                 $content['header'].=div::htm_includeJSFile(LIB_PATH.'/mootools/mootools.js');   
                 $content['header'].=div::htm_includeJSFile(LIB_PATH.'/mootools/mootools_more_sortables.js');
            }
            
            $content['main'].="<script language='JavaScript'>var sortables_".$this->getInstances()." = new Sortables($('ilist_body_".$this->getInstances()."'), {
    clone: false,
    constrain:true,
    snap:15,
    opacity: 0.7,
    revert: true,
    onStart: function(el) {  
      el.setStyle('background','#add8e6'); 
    },
    onComplete: function(el) {      
      el.setStyle('background',el.getAttribute('oldColor')); 
      //build a string of the order 
      var sort_order = ''; 
      var rows = $('ilist_body_".$this->getInstances()."').childNodes;
    
      for (var i = 0; i < rows.length; i++) {
        sort_order = sort_order + rows[i].getAttribute('value') + '|';
      }  

      sort_order=sort_order.substring(0,sort_order.length-1);
       
       
        //do an ajax request 
        var req = new Request({ 
          url:'".LIB_PATH."class.iList.php', 
          method:'post', 
          autoCancel:true,                            
          data:'sort_order=' + sort_order + '&table=".$sortable_field[0]."&field=".$sortable_field[1]."', 
          onRequest: function() { 
             
          }, 
          onSuccess: function() { 
            
          } 
        }).send(); 
       
    } 
 

});
</script>";
           
        }

        self::$instances++; 
		return $content;
	}




	private function wrapItem($index) {

		$bgColor=$this->getBgColor();
		$isLastItem = $index==(count($this->items)-1)?true:false;
		$isFirstItem = $index==0?true:false;

		if($isLastItem) { /*$borderBottom='border-bottom:1px solid #565656;';*/ }
		if($isFirstItem) {
			/*$checkBorderTop = 'border-top:1px solid #565656;';
			if(!$this->showHeadLine) { $borderTop='border-top:1px solid #565656;'; }*/
		}
		$content .= '<tr value="'.$this->items[$index]->uid.'" id="'.self::getInstances().'_item_'.$index.'" bgcolor="'.$bgColor.'" oldColor="'.$bgColor.'">
	';

		if($this->hasCommonActions()){
			$content .= '<td class="ilist_field" width="30px" onMouseDown="javascript:ilist_checkItem(\''.self::getInstances().'_check_'.$index.'\');" style="'.$checkBorderTop.$borderBottom.'"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="ilist_connector"></td>
        </tr>
        <tr>
          <td class="ilist_checkbox">
              <input type="checkbox" foreign_uid="'.$this->items[$index]->foreign_uid.'" uid="'.$this->items[$index]->uid.'" id="'.self::getInstances().'_check_'.$index.'" name="'.self::getInstances().'_check_'.$index.'" onClick="return false;">
          </td>
        </tr>
        <tr>
          <td class="ilist_connector"></td>
        </tr>
      </table></td>';
		}

		foreach($this->items[$index]->content as $key=>$field) {
			$isLastField=$key==(count($this->items[$index]->content)-1)?true:false;
			if(div::date_isSQLDate($field)) { $field=div::date_SQLToGer($field); }
			$field=$field==''?'':$field;
			if(!$this->hasCommonActions() && $isLastField) { $borderRight='border-right:1px solid #565656;'; }
             
			$content.= '<td class="ilist_field" onMouseDown="javascript:ilist_checkItem(\''.self::getInstances().'_check_'.$index.'\');" style="'.$borderTop.$borderBottom.$borderRight.'">'.div::str_cut(htmlspecialchars($field),$this->maxFieldLen).'</td>';
		}
		$content.= $this->wrapActions($index);
		$content .= '</tr>';

		return $content;
	}

	public function getNumCols($all=true) {
		$cols=count($this->columns);
		if($all) {
			if($this->hasCommonActions()) $cols++;
			if(is_array($this->actions)) $cols++;
		}
		return $cols;
	}
}

class iListItem extends object {
	public $content;
	public $minHeight;
	public $minWidth;
	public $uid;
	public $foreign_uid;
	public $isImage;
    public $data;

	function ilistItem($content,$minHeight=0,$minWidth=0) {
		$this->content = $content;
		$this->minHeight = $minHeight;
		$this->minWidth = $minWidth;
	}
}



?>