<?
/**
 * Eine TabControl-Klasse (tab), die ihre items (tabItem) verwaltet.
 * Die tab-Klasse stellt eine Kartei dar, als Behälter für die Karteireiter und deren Inhalt.
 *
 * @author Ueli Wyss
 * @package IPA-Vorbereitung
 */

/**
 * Frei mit CSS formatierbare TabControl-Klasse. Sie lässt sich über Java-Script funktionen steuern.
 * Beim anzeigen eines Tabs wird der Inhalt aller Tab-Items gleichzeitig geladen. So können sie per
 * show/hide-Funktionen geswitcht werden.
 */
class tab extends container{

	public $CSS; //StyleSheets, die das TabControl darstellen.
	public $JS; //Enthält Funktionen zum

	public static $instances=0;

	public $fullSpaceContent;

	/**
	 * Konstruktor
	 * Arbeitet folgende Befehlskette ab.
	 * 1. Standardwerte werden gesetzt (setDefaults())
	 * 2. Übergebene Werte werden gespeichert.
	 * 3. Über Formulare übergebene Werte ($_GET,$_POST) werden eingebunden.
	 *
	 * @return tab
	 */
	public function tab() {
		$this->setDefaults();

	}

	/************************************
	 *
	 * Standardfunktionen
	 *
	 * Diese Funktionen werden je nach bedarf in der selben Form auch in anderen Klassen definiert.
	 *
	 **************************************/

	/**
	 * Standardfunktion: Standardwerte werden gesetzt.
	 *
	 */
	private function setDefaults() {
		$this->CSS='
body {
	font-family:tahoma;
	font-size:11px;
}

.tab {
	width:100%;
}

.tab_header {
	height:25px !important;
	width:100%;
}

.tab_header_leftActive {
	/*
	background-image:url('.ROOT_DIR.'images/tab_header_leftActive.gif);
	height:32px !important;
	*/
	font-size:1px;
	width:15px !important;
	border-left:2px solid #46b0ee;
	border-top:2px solid #46b0ee;

}

.tab_header_leftInactive {
	/*
	background-image:url('.ROOT_DIR.'images/tab_header_leftInactive.gif);
	background-repeat:no-repeat;
	height:32px !important;
	*/
	font-size:1px;
	width:15px !important;
	cursor:pointer;
	border-left:1px solid #46b0ee;
	border-top:1px solid #46b0ee;
	border-bottom:2px solid #46b0ee;
}

.tab_header_midActive {
	/*
	background-image:url('.ROOT_DIR.'images/tab_header_midActive.gif);
	background-repeat:repeat-x;
	height:32px !important;
	*/
	width:17px !important;
	color:#ff9000;
	font-size:11px;
	font-weight:bold;
	vertical-align:middle;
	padding-left:0px;
	padding-right:0px;
	border-top:2px solid #46b0ee;
}

.tab_header_midInactive {
	/*
	background-image:url('.ROOT_DIR.'images/tab_header_midInactive.gif);
	background-repeat:repeat-x;
	height:32px !important;
	*/
	width:10px;
	color:#46b0ee;
	font-size:10px;
	font-weight:bold;
	padding-left:5px;
	padding-right:5px;
	vertical-align:middle;
	cursor:pointer;
	border-bottom:2px solid #46b0ee;
	border-top:1px solid #46b0ee;
}

.tab_header_rightActive {
	border-right:2px solid #46b0ee;
	border-top:2px solid #46b0ee;
	/*
	background-image:url('.ROOT_DIR.'images/tab_header_rightActive.gif);
	background-repeat:no-repeat;
	height:32px !important;
	*/
	font-size:1px;
	width:15px !important;
	cursor:pointer;

}

.tab_header_rightInactive {
	/*
	background-image:url('.ROOT_DIR.'images/tab_header_rightInactive.gif);
	background-repeat:no-repeat;
	height:32px !important;
	*/
	font-size:1px;
	width:15px !important;
	cursor:pointer;
	border-right:1px solid #46b0ee;
	border-top:1px solid #46b0ee;
	border-bottom:2px solid #46b0ee;

}

.tab_header_space {
	border-bottom:2px solid #46b0ee;
	font-size:1px;
	width:5px;
}

.tab_header_firstSpace {
	border-bottom:2px solid #46b0ee;
	font-size:1px;
	width:5px;
}

.tab_header_fullSpace {
	border-bottom:2px solid #46b0ee;
	font-size:1px;
}

.tab_content {
	padding:20px;
	border-right:1px solid black;
	border-bottom:2px solid #46b0ee;
	border-left:2px solid #46b0ee;
	border-right:2px solid #46b0ee;
	width:100%;
}';
		$this->JS="
function tab_switch(numTab,tab) {
	var old_id = eval('tab_active_'+numTab);
    
	if(tab!=old_id) {
		var header_left_old = document.getElementById('tab_header_left_'+numTab+'_'+old_id);
		var header_mid_old = document.getElementById('tab_header_mid_'+numTab+'_'+old_id);
		var header_right_old = document.getElementById('tab_header_right_'+numTab+'_'+old_id);
		var content_old = document.getElementById('tab_content_'+numTab+'_'+old_id);

		var header_left_new = document.getElementById('tab_header_left_'+numTab+'_'+tab);
		var header_mid_new = document.getElementById('tab_header_mid_'+numTab+'_'+tab);
		var header_right_new = document.getElementById('tab_header_right_'+numTab+'_'+tab);
		var content_new = document.getElementById('tab_content_'+numTab+'_'+tab);

		header_left_old.className='tab_header_leftInactive';
		header_left_new.className='tab_header_leftActive';

		header_mid_old.className='tab_header_midInactive';
		header_mid_new.className='tab_header_midActive';

		header_right_old.className='tab_header_rightInactive';
		header_right_new.className='tab_header_rightActive';

		content_old.style.display='none';
		content_new.style.display='block';
        
        top.activeTab[".$this->getInstances()."]=tab;   
		eval('tab_active_'+numTab+' = '+tab+';');
        
	} 
}";
	}

	function init() {
		if(div::http_getGP('activeTab_'.$this->getName())) {
			if(count($this->items)>div::http_getGP('activeTab_'.$this->getName())) {
				$this->setActive(div::http_getGP('activeTab_'.$this->getName()));
			}
		}
	}

	/**
	 * Standardfunktion: Fügt ein Element zum Array $items hinzu.
	 * Dabei können entweder die beiden Keywords "begin"(Positionierung am Anfang) und "end"(Positionierung am Ende)
	 * zur Positionierung der Elemente innerhalb des Arrays verwendet werden. Andererseits kann der index durch eine Zahl angegeben werden.
	 * Falls eine Position vor dem Ende des Arrays gewählt wird, werden alle nachfolgenden Elemente um eine Stelle nach hinten verschoben.
	 *
	 * @param tabItem $item
	 * @param int $index
	 */
	/*public function addItem(&$item,$index="end") {
		if($item->active && is_array($this->items)) {
			$this->items[$this->getActive()]->active=false;
		}
		if(!is_string($this->getActive())) {
			$item->active=true;
		}
		if($index == "begin") {
			$index=0;
		} elseif($index == "end" || $index > count($this->items)) {
			if(is_array($this->items)) {
				$index=count($this->items);
			} else {
				$index=0;
			}
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
*/
	/**
	 * Standardfunktion: Entfernt ein Element vom $items-Array.
	 * Welches Element entfernt wird, bestimmt der index. Dieser kann durch die Keywords "first"(Erstes Element) oder "last"(Letztes Element)
	 * bestimmt werden oder durch eine Integer Zahl.
	 * Beim Entfernen wird die länge des Arrays um eins gekürzt. Falls sich das zu enfernende Element nicht am Ende des Arrays befindet
	 *
	 * @param int $index
	 * @return boolean
	 */
	/*public function removeItem($index) {
		if($this->items[$index]->active) {
			$this->items[0]->active=true;
		}
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
*/
	/**
	 * Standardfunktion: Bereitet die Daten auf, damit sie als HTML ausgegeben werden können.
	 * Diese Funktion liefert ein Array, das strukturierten HTML-Code enthält.
	 * Das Array hat folgenden Aufbau:
	 * 	'main' >> Der Hauptteil des HTML-Codes, der sich innerhalb des Body-Tags befindet.
	 * 	'header' >> Header-Informationen wie Meta-Tags, Java-Script oder CSS-Datei einbindungen.
	 * 	'CSS' >> Purer (ohne <style>-Tag) CSS-Text, der im Header angebracht wird.
	 * 	'JS' >> Purer (ohne <script>-Tag) Java-Script-Text, der im Header angebracht wird.
	 *
	 * Mit dieser Technik können durch die Trennung der einzelnen Programmiersprachen die W3C konventionen eingehalten werden.
	 * Und zusätzlich können unerklärliche Fehler-Meldungen vermieden werden.
	 *
	 * Mit der Funktion div::htm_mergeSiteContent() können zwei solche Arrays miteinander verbunden werden.
	 * Mit Hilfe der div::htm_echoContent() wird der Code dann schlussendlich sortiert, formatiert und ausgegeben.
	 * Alle Ausgaben von HTML (echos) sollen über diese Funktion laufen, welche erst am Ende aufgerufen werden soll.
	 *
	 * Generiert das Gerüst des TabControls und aktiviert den aktiven TabItem. Der Inhalt der TabItems wird über die Funktion
	 * tabItem::wrapContent() geholt.
	 *
	 * @return array
	 */
	public function wrapContent() {
		global $db;
		$content=array();
		$this->init();

		$content['JS'].='var tab_active_'.$this->getInstances().' = \''.($this->getActive()==0?'0':$this->getActive()).'\';';
		if($this->getInstances()==0) {
			$content['JS'].=$this->JS;
			$content['CSS'].=$this->CSS;
		}

		$content['main'].='

	<div class="tab">
		<div class="tab_header">
				<table class="tab_header" border="0" cellpaddding="0" cellspacing="0">
					<tr>
						<td class="tab_header_firstSpace">&nbsp;</td>';

		$i=0;
		foreach($this->items as $item) {
			if($i==$this->getActive()) {
				$classLeft="tab_header_leftActive";
				$classMid="tab_header_midActive";
				$classRight="tab_header_rightActive";
			} else {
				$classLeft="tab_header_leftInactive";
				$classMid="tab_header_midInactive";
				$classRight="tab_header_rightInactive";
			}
			$content['main'].='
						<td class="'.$classLeft.'" id="tab_header_left_'.$this->getInstances().'_'.$i.'" onClick="javascript:tab_switch('.$this->getInstances().','.$i.');">&nbsp;</td>
						<td class="'.$classMid.'" id="tab_header_mid_'.$this->getInstances().'_'.$i.'" onClick="javascript:tab_switch('.$this->getInstances().','.$i.');"><nobr>'.$item->title.'</nobr></td>
						<td class="'.$classRight.'" id="tab_header_right_'.$this->getInstances().'_'.$i.'" onClick="javascript:tab_switch('.$this->getInstances().','.$i.');">&nbsp;</td>';

			if($i!=(count($this->items)-1)) {
				$content['main'].='
						<td class="tab_header_space">&nbsp;</td>';
			}
			$i++;
		}

		$content['main'].='
						<td class="tab_header_fullSpace">&nbsp;'.$this->fullSpaceContent.'</td>
					</tr>
			</table>
		</div>';



		$i=0;
		foreach($this->items as $item) {
			$itemContent=$item->wrapContent();
			if($i!=$this->getActive()) {
				$display='display:none';
			} else {
				$display='';
			}
			$content['main'].='
		<div class="tab_content" id="tab_content_'.$this->getInstances().'_'.$i.'" style="'.$display.'">
			'.$itemContent['main'].'
		</div>';

			$itemContent['main']='';
			div::htm_mergeSiteContent($content,$itemContent);
			$i++;
		}
        $content['main'].='                  
<script language="JavaScript" type="text/JavaScript"> 
      //alert(top.siteCheckHash+\'::\'+\''.md5(div::http_getGP('action').basename($_SERVER['PHP_SELF'])).'\'+\''.div::http_getGP('action').basename($_SERVER['PHP_SELF']).'\');
      if(top.siteCheckHash['.$this->getInstances().'] != \''.md5(div::http_getGP('action').basename($_SERVER['PHP_SELF'])).'\') {
          top.activeTab['.$this->getInstances().']=0;  
      }
      
      tab_switch('.$this->getInstances().',top.activeTab['.$this->getInstances().']);
      top.siteCheckHash['.$this->getInstances().']=\''.md5(div::http_getGP('action').basename($_SERVER['PHP_SELF'])).'\'; 
</script>     
        ';
		return $content;
	}

	/**
	 * Standardfunktion: Liefert die Anzahl der Instanzen, die bisher von dieser Klasse abgeleitet wurden.
	 * Bei der Umwandlung der Variable (self::$instances) zum Typ String, wird beim Wert 0 ein leerer String
	 * zurückgegeben. Diese Funktion verhindert diesen Effekt, das ist auch ihr einziger Zweck.
	 *
	 * @return string
	 */
	function getInstances() {
		if(self::$instances==0) {
			return '0';
		} else {
			return self::$instances;
		}
	}


	/**
	 * Liefert den Index des Aktiven TabItem.
	 *
	 * @return int
	 */
	public function getActive() {
		global $db;

		$val=false;
		if(is_array($this->items)) {
			$db->view_array($this->items);
			foreach($this->items as $index=>$item) {
				if($item->active) {
					$val=$index;
				}
			}
			return div::var_intToString($val);
		} else {
			return false;
		}

	}


	/**
	 * Setzt den aktiven TabItem. Deaktiviert vorher den davor Aktiven.
	 *
	 * @param int $index
	 */
	function setActive($index) {
		$this->items[$this->getActive()]->active=false;
		$this->items[$index]->active=true;
	}

	public static function getName() {
		return 'tab_'.tab::getInstances();
	}
}

/**
 * Diese Klasse ist lediglich für die Speicherung des Inhalts eines tabs zuständig.
 */
class tabItem {
	public $title;
	public $content;
	public $index;

	public $active;


	/**
	 * Alle Attribute werden direkt über den Konstruktor gesetzt.
	 *
	 * @return tabItem
	 */
	public function tabItem($title,$content='',$active=false) {
		$this->title=$title;
		$this->content=$content;
		$this->active=$active;
	}

	/**
	 * Der Inahlt des Tabs wird zurückggegeben.
	 *
	 * @return unknown
	 */
	function wrapContent() {
		return $this->content;
	}

	public function getName() {
		return tab::getName();
	}

	function getIndex() {
		return $this->index;
	}
}

?>