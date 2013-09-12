<?
/**
 * Messageverwaltung
 *
 * @author Ueli Wyss
 * @package IPA-Vorbereitung
 */

define('MESSAGE_INFO',1);
define('MESSAGE_WARNING',2);
define('MESSAGE_ERROR',3);


/**
 * Löst ein Meldung aus. Sie fügt die übergebene Meldung zum MessageViewer-Objekt hinzu, speichert es und aktualisiert das Message-Frame.
 *
 * @param string $type Typ der Meldung
 * @param string $text Meldungstext
 * @param string $number Meldungsnummer (Fehler-Code)
 */
function raiseMessage($type,$text=null,$number=null) {

	$path=TEMP_DIR;
	$target=ROOT_DIR."message.php";

	if(div::var_isSavedObject("messageViewer",$path)) {
		$msgViewer=div::var_restoreObject("messageViewer",$path);
	} else  {
		$msgViewer=new messageViewer();
	}

	if(!is_object($type)) {
		$msg=new message($type,$text,$number);
	} else {
		$msg=$type;
	}
	$msgViewer->addElement($msg);
	div::var_saveObject($msgViewer,"messageViewer",$path);
	echo div::htm_includeJS('
	if(top.message) {
		top.message.location.href="'.$target.'";
	} else {
		top.opener.top.message.location.href="'.$target.'";
	}
');
}



/**
 * Ein MessageViewer kann mehrere Message-Objekte verwalten und sie in HTML darstellen.
 *
 */
class messageViewer {

	public $JS;
	public $CSS;

	public $items=array();

	public $icons;

	public function messageViewer() {
		$this->setDefaults();
	}

	public function setDefaults() {

/*		$this->JS='
var MESSAGE_INFO=1;
var MESSAGE_WARNING=2;
var MESSAGE_ERROR=3;

addMessage(type,text,number,file,line) {
	var url=
}
';*/
		$this->CSS='
.message_text{
	font-family:tahoma;
	font-size:10px;
	font-weight:bold;
	color:#980000;
}

body{
	background-color:d1d1d1;
}';
		$this->icons=array(
			MESSAGE_INFO=>ICON_DIR.'message_info.gif',
			MESSAGE_WARNING=>ICON_DIR.'message_warning.gif',
			MESSAGE_ERROR=>ICON_DIR.'message_error.gif',
		);
	}

	private function init() {
		$action=div::http_getGP('action');
		if($action=='reset') {
			$this->reset();
		} elseif($action=='add') {
			$this->addElement(new message(div::http_getGP('type'),div::http_getGP('text'),div::http_getGP('number')),"begin");
		}
	}


	/**
	 * Formatiert alle Meldungen als HTML-Code und gibt diesen zurück.
	 *
	 * @return string HTML-Code
	 */
	public function wrapContent() {
		$this->setDefaults();
		$this->init();

		$content=array();
		/*$content['JS']=$this->JS;*/
		$content['CSS']=$this->CSS;
		$content['main']='<table>
';
		foreach($this->items as $item) {
			$content['main'].=
'<tr><td class="message_text">'.div::htm_wrapIcon($this->icons[$item->type]).'</td>
<td class="message_text">'.$item->datetime.": ".$item->text;
		}

		//$this->save();
		return $content;
	}

	/**
	 * Standardfunktion: Fügt ein Element zum Array $items hinzu.
	 * Dabei können entweder die beiden Keywords "begin"(Positionierung am Anfang) und "end"(Positionierung am Ende)
	 * zur Positionierung der Elemente innerhalb des Arrays verwendet werden. Andererseits kann der index durch eine Zahl angegeben werden.
	 * Falls eine Position vor dem Ende des Arrays gewählt wird, werden alle nachfolgenden Elemente um eine Stelle nach hinten verschoben.
	 *
	 * @param message $item
	 * @param int $index
	 */
	function addElement($item, $index="begin") {
		$item->requiredBold=$this->requiredBold;

		if($index == "begin") {
			$index=0;
		} elseif($index == "end" || $index > count($this->items)) {
			$index=count($this->items);
		}
		if(count($this->items)!=0) {
			$i=count($this->items)-1;
			while($i>=$index) {
				$this->items[$i+1]=$this->items[$i];
				$i--;
			}
		}
		$this->items[$index]=$item;
	}

	/**
	 * Standardfunktion: Entfernt ein Element vom $items-Array.
	 * Welches Element entfernt wird, bestimmt der index. Dieser kann durch die Keywords "first"(Erstes Element) oder "last"(Letztes Element)
	 * bestimmt werden oder durch eine Integer Zahl.
	 * Beim Entfernen wird die länge des Arrays um eins gekürzt. Falls sich das zu enfernende Element nicht am Ende des Arrays befindet
	 *
	 * @param int $index
	 * @return boolean
	 */
	function removeElement($index) {
		if(!$index>(count($this->items)-1)) {
			if($index == "last") { $index = $this->items[count($this->items)-1]; }
			elseif($index == "first") { $index = 0; }

			$i=$index;
			while($i>$index) {
				$this->items[$i]=$this->items[$i+1];
				$i++;
			}
			unset($this->items[count($this->items)-1]);
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Speichert verschiedene Eigenschaften einer Meldung (Message)
 *
 */
class message {

	public $type;
	public $text;
	public $number;

	public $file;
	public $class;
	public $line;

	public $datetime;

	public function message($type,$text,$number) {
		$backtrace=debug_backtrace();

		$this->type=$type;
		$this->text=$text;
		$this->number=$number;

		$this->file=$backtrace['file'];
		$this->class=$backtrace['class'];
		$this->line=$backtrace['line'];

		$this->datetime=div::date_getDatetime();
	}
}
?>