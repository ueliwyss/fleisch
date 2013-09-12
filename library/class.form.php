<?

if($_GET['action']=='checkCoordinates') {
    include('../init.php');
}    
    

/**
 * Mithilfe der in dieser Datei enthaltenen Klassen lassen sich Dynamische Eingabe- oder Editierformulare genierieren.
 *
 * @author Ueli Wyss
 * @package IPA
 * @subpackage Vorbereitung
 */


/**
 * - Einer Instanz der Hauptklasse(form) können beliebige Formular-Elemente  vom Typ formElement (Typen: text, password, checkbox, select, radio, hidden)
 * angefügt werden.
 * - Die Formular-Elemente lassen sich auf verschiedene Arten automatisiert Validieren/Überprüfen (Optionen: required, email, datetime, date ,time, numeric).
 * - Einem Formular können vordefinierte oder selbst erstellte Buttons angefügt werden.
 *
 */
class form extends container {

	/**
	 * Array, das alle formButton Elemente enthält.
	 * Normalerweise werden die Buttons über die Boolean-Variablen $saveButton und $backButton bestimmt.
	 * Die Funktion createButtons() generiert je nach Einstellung die Buttons und fügt sie dem Formular hinzu.
	 *
	 * @var array formButton
	 */
	public $buttons;


	/**
	 * Einstellung für das <form>-Tag, die bestimmt auf welche Art die Formular-Daten an das in der $action-Variable gespeicherte
	 * Script geschickt wird. Möglich ist "GET"(via URL) oder "POST"(via Protokoll[Standard]).
	 *
	 * @var string
	 */
	public $method;
	/**
	 * Bestimmt die Zieldatei, an welche die Formular-Daten beim Abschicken des Formulars gesendet werden sollen.
	 *
	 * @var string
	 */
	public $action;
    
    public $enctype;
	/**
	 * Bestimmt in welchem Fenster oder Frame die Ziel-Datei ($action) geöffnet werden soll.
	 * Standardmässig ist diese Variabel auf "'form_actionFrame_'.self::$instances" gesetzt.
	 * Wenn diese Einstellung belassen wird, wird die Ziel-Datei des Formulars im Formulareigenen <iframe> geöffnet.
	 * Dies hat den Vorteil, dass beim aktualisieren der Seite die Speicher-Befehle in der Zieldatei nicht erneut ausgeführt werden und somit auch keine Fehlermeldungen auftreten.
	 *
	 * @var string
	 */
	public $target;


	/**
	 * Sollen die Felder, die mit Required deklariert sind Fett(<b>) dargestellt werden?
	 * Diese Einstellung greift auf alle Elemente (formElement), die im $items-Array gespeichert sind.
	 * Das Element wird beim Hinzufügen zum Array (mit der Funktion addItem()) mutiert.
	 * Standardmässig ist diese Option aktiviert.
	 *
	 * @var boolean
	 */
	public $requiredBold;

	/**
	 * Soll ein 'Speichern'-Button angezeigt werden, welcher das Formular abschickt?
	 * Falls ja, wird er in der Funktion createButtons() generiert.
	 *
	 * @see form::createButtons()
	 * @var boolean
	 */
	public $saveButton;
	/**
	 * Soll ein 'Back'-Button angezeigt werden, welcher zur vorherigen Seite wechselt?
	 * Falls ja, wird er in der Funktion createButtons() generiert.
	 *
	 * @see form::createButtons()
	 * @todo Diese Option ist noch nicht funktionsfähig. Dieser Button braucht noch den onClick-Teil und ein Icon.
	 * @var boolean
	 */
	public $backButton;


	/**
	 * Array, das alle einzubindenden Javscript-Dateien auflistet.
	 * Standardmässig wird die Datei jsfunc.validateform.js eingebunden, welche für die kontrolle der Formular-Daten zuständig ist (aus TYPO3).
	 *
	 * @var array string
	 */
	public $jsFiles;


	/**
	 * Name des IFrames, das als Standard-Target angegeben wird.
	 *
	 * @var string
	 */
	private $actionFrameName;


	private $tabItem;

	public $caption;
	public $description;

	public $showCaption;
	public $showDescription;

	public $triggerFile; //Name der Datei, die das Formular aufruft, damit nach dem Speichern zur selben zurückgekehrt werden kann.

	/**
	 * Zählt die Instazen eines Objekts
	 *
	 * @var int
	 */
	private static $instances = 0;

	/**
	 * Konstruktor
	 * Arbeitet folgende Befehlskette ab.
	 * 1. Standardwerte werden gesetzt (setDefaults())
	 * 2. Übergebene Werte werden gespeichert.
	 * 3. Über Formulare übergebene Werte ($_GET,$_POST) werden eingebunden.
	 *
	 * @return form
	 */
	function form(&$tabItem=null) {
		$this->tabItem=$tabItem;
		if(!$this->tabItem) { $this->tabItem=new tabItem('Pseudo'); }

		$urlAddition=new formElement('hidden');
		$urlAddition->name='activeTab';
		$urlAddition->value='activeTab_'.$this->tabItem->getName()."=".$this->tabItem->getIndex();
		$this->addElement($urlAddition);


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
	function setDefaults() {
		$this->requiredBold=true;
		$this->saveButton=true;
		$this->backButton=false;
		$this->actionFrameName='form_actionFrame_'.self::$instances;
		$this->target=$this->actionFrameName;
		$this->triggerFile=$_SERVER['SCRIPT_NAME'];

		$this->showCaption=true;
		$this->showDescription=true;

		$this->method="POST";
		$this->jsFiles=array(
			LIB_PATH.'jsfunc.validateform.js',
			LIB_PATH.'jsfunc.tooltip.js',
            LIB_PATH.'googlemaps.js',
		);
	}
    
    function setFormAttributes($action='',$method='POST',$target='') {
        $this->action=$action;
        $this->method=$method;
        $this->target=$target;
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
	 * In dieser Funktion wird das Gerüst für das Formular generiert und die gleichnahmige Funktion wird in allen untergeordneten
	 * Elementen aufgerufen.
	 *
	 * @return array
	 */
	public function wrapContent() {
        $this->initializeElements();
        
		if(!count($this->buttons)) {
			$this->createButtons();
		}

		if($this->getInstances()==0 && is_array($this->jsFiles)) {
			foreach($this->jsFiles as $jsFile) {
				$content['header'].='
'.div::htm_includeJSFile($jsFile);
			}

			$content['CSS']='
.form {
	font-size:11px;
	font-family:tahoma,verdana;
}

.form_caption {
	font-weight:bold;
	color:#417fc6;
	font-size:20px;
	padding-bottom:5px;
}

.form_description {
	font-weight:bold;
	font-size:11px;
	padding-bottom:20px;
	color:#737373;
}';
		}

		$content['main'].='
<form name="'.$this->getFormName().'" enctype="'.$this->enctype.'" method="'.$this->method.'" action="'.$this->action.'" target="'.$this->target.'" onsubmit="return validateForm(\''.$this->getFormName().'\',\''.$this->getValidString().'\',\''.ROOT_DIR.'\'); this.onsubmit=\'\';">
<table class="form">';

		$content['main'].=$this->wrapCaption();
		$content['main'].=$this->wrapDescription();

		$fields=explode(",",$TABLES[$tableName]['palettes'][$palette]);

		$triggerFile=new formElement('hidden');
		$triggerFile->name='triggerFile';
		$triggerFile->value=$this->triggerFile;
		$this->addElement($triggerFile);

		foreach($this->items as $element) {
				div::htm_mergeSiteContent($content,$element->wrapContent());
		}

		if(is_array($this->buttons)) {
			$content['main'].='
	<tr>
		<td>';
			foreach($this->buttons as $button) {
				div::htm_mergeSiteContent($content,$button->wrapContent());
			}
			$content['main'].='
		</td>
	</tr>';
		}
		$content['main'].='
</table>
</form>';
		$content['main'].=$this->getActionFrame();
		return $content;
	}

	function wrapDescription() {
		if($this->showDescription) {
			$content='
<tr>
	<td class="form_description" colspan="2">
		'.$this->description.'
	</td>
</tr>';
		}
		return $content;
	}

	function wrapCaption() {
		if($this->showCaption) {
			$content='
<tr>
	<td class="form_caption" colspan="2">
		'.$this->caption.'
	</td>
</tr>';
		}
		self::$instances++;
		return $content;
	}

	/**
	 * Fügt ein Element vom Typ formButton zum $buttons-Array hinzu. Ist von der Funktionsweise her gleich wie addItem().
	 *
	 * @see form::addItem()
	 * @param formButton $item
	 * @param int $index
	 */
	function addButton(formButton $item, $index="end") {
		if($index == "begin") {
			$index=0;
		} elseif($index == "end" || $index > count($this->buttons)) {
			$index=count($this->buttons);
		}
		if(count($this->buttons)!=0) {
			$i=count($this->buttons)-1;
			while($i>=$index) {
				$this->buttons[$i+1]=$this->buttons[$i];
				$i--;
			}
		}
		$this->buttons[$index]=$item;
	}

	/**
	 * Entfernt einen Button (formButton) aus dem $buttons-Array. Ist von der Funktionsweise her gleich wie removeItem().
	 *
	 * @see form::removeItem()
	 * @param int $index
	 * @return boolean
	 */
	function removeButton($index) {
		if(!$index>(count($this->buttons)-1)) {
			if($index == "last") { $index = $this->buttons[count($this->buttons)-1]; }
			elseif($index == "first") { $index = 0; }

			$i=$index;
			while($i>$index) {
				$this->buttons[$i]=$this->buttons[$i+1];
				$i++;
			}
			unset($this->buttons[count($this->buttons)-1]);
			return true;
		} else {
			return false;
		}
	}

	/************************************
	 *
	 * Hilfsfunktionen
	 *
	 * Funktionen, die helfen ein Formular zu generieren.
	 *
	 **************************************/

	/**
	 * Generiert einen Namen für das Formular. Der einzigartig ist, im Falle, dass mehrere Formulare gleichzeigig angezeigt werden.
	 *
	 * @return string
	 */
	function getFormName() {
		return 'form_'.$this->getInstances();
	}

	/**
	 * Generiert die Standardbuttons 'Speichern' ($saveButton) und 'Zurück' ($backButton) , falls die entprechenden Optionen
	 * aktiviert sind, und fügt diese zum Formular hinzu.
	 *
	 * @todo Diese Funktion ist noch nicht voll funktionsfähig. Die Realisierung des Zurück-Buttons fehlt noch.
	 */
	function createButtons() {
		if($this->saveButton) {
			$this->addButton(new formButton('Speichern',$this->getFormName().".onsubmit();",ICON_DIR.'form_button_saveIcon.gif'),"end");
		}
	}


	/**
	 * Generiert den Valid-String nach den $eval Variablen der Formular-Element (formElement).
	 * Er definiert die Kriterien, nach welchen das Formular überprüft werden soll.
	 * Dieser String wird von der jsfunc.validateform.js::validateForm()-Funkion aus dem Java-Script
	 * jsfunc.validateform.js verwendet um die Eingaben auf ihre Gültigket zu prüfen.
	 *
	 * @todo Der Aubau des Valid-Strings fehlt in dieser Beschreibung noch.
	 * @return string
	 */
	function getValidString() {
			foreach($this->items as $item) {
			if($item instanceof formElement && $item->type!=formElement::TYPE_HIDDEN) {
				if($item->hasEval("confirm")) {
					$validString.='_CONFIRM,'.$item->name.','.$item->name.'2,'.div::var_boolToString($item->isRequired()).','.ucfirst($item->label).',';
				} elseif($item->hasEval("num")) {
					$validString.='_EREG,,^[0-9]+$,'.$item->name.','.div::var_boolToString($item->isRequired()).','.ucfirst($item->label).',';
				} elseif($item->hasEval("datetime")) {
					$validString.='_EREG,,^(([0-2]?[0-9]|3[0-1]).([0]?[1-9]|1[0-2]).[1-3][0-9][0-9][0-9]) ?((([0-1]?[0-9])|(2[0-3])):[0-5][0-9])?$,'.$item->name.','.div::var_boolToString($item->isRequired()).','.ucfirst($item->label).',';
				} elseif($item->hasEval("date")) {
					$validString.='_EREG,,^(([0-2]?[0-9]|3[0-1]).([0]?[1-9]|1[0-2]).[1-3][0-9][0-9][0-9])$,'.$item->name.','.div::var_boolToString($item->isRequired()).','.ucfirst($item->label).',';
				} elseif($item->hasEval("time")) {
					$validString.='_EREG,,^([0-9][0-9][0-9]?):([0-5][0-9])(:([0-5][0-9]))?$,'.$item->name.','.div::var_boolToString($item->isRequired()).','.ucfirst($item->label).',';
				} elseif($item->hasEval("email")) {
					$validString.='_EMAIL,'.$item->name.','.div::var_boolToString($item->isRequired()).','.ucfirst($item->label).',';
				} elseif($item->hasEval("float")) {
                    $validString.='_EREG,,^[-+]?[0-9]+[\\.]?[0-9]*$,'.$item->name.','.div::var_boolToString($item->isRequired()).','.ucfirst($item->label).',';
                } elseif($item->hasEval("float_fromto")) {
                    $validString.='_EREG,,^[-+]?[0-9]+[\\.]?[0-9]*(-[-+]?[0-9]+[\\.]?[0-9])?$,'.$item->name.','.div::var_boolToString($item->isRequired()).','.ucfirst($item->label).',';
                } elseif($item->hasEval("num_fromto")) {
                    $validString.='_EREG,,^[0-9]*(-[0-9]*)?$,'.$item->name.','.div::var_boolToString($item->isRequired()).','.ucfirst($item->label).',';
                } elseif($item->isRequired()) {
					$validString.=$item->name.','.div::var_boolToString($item->isRequired()).','.ucfirst($item->label).',';
				} 
			}

		}
		$validString=substr($validString,0,(strlen($validString)-1));
		return $validString;
	}

	/**
	 * Generiert den HTML-Code für das Standardmässige Ziel-Frame des Formulars.
	 *
	 * @return string
	 */
	function getActionFrame() {
		$frame='
<iframe width="0" height="0" name="'.$this->actionFrameName.'"></iframe>';
		return $frame;
	}
    
    function initializeElements() {
        foreach($this->items as $element) {
            if($element instanceof formElement) $element->init();
        }
    }
    
    function addElement($item, $index="end") {
        $item->form=&$this;
        parent::addElement($item,$index);
    }
    
    function getElementById($elementId) {
        foreach($this->items as $index=>$item) {
            if($item->id==$elementId) {
                $result=&$this->items[$index];
                return $result;
            }
        }
        
    }
    
    function addHiddenFieldsFromArray($array) {
        foreach($array as $name=>$value) {
            $element=new formElement('hidden');
            $element->name=$name;
            $element->value=$value;
            $this->addElement($element); 
        }
    }

}

/**
 * Eine Instanz dieser Klasse steht für ein Formular-Element.
 *
 */
class formElement {
	/**
	 * Über diese Konstanten kann der Typ des Elements angegeben werden.
	 */

	Const TYPE_TEXT='text';
	Const TYPE_CHECKBOX='checkbox';
	Const TYPE_RADIO='radio';
	Const TYPE_SELECT='select';
	Const TYPE_HIDDEN='hidden';
	Const TYPE_TEXTAREA='textarea';
	Const TYPE_PASSWORD='password';
    Const TYPE_FILE='file';
    Const TYPE_CAPTCHA='captcha';
    Const TYPE_COORDNIATE='coordinate';
    
    public $form; //Formular, zu dem das Element gehört.

	/**
	 * Attribute für alle Element-Typen.
	 */

	public $readOnly; //Bei True wird das Element grau hinterlegt.
	public $type; //Typ des Elements (eine TYPE_ Konstante).
	public $name; //Name-Attribut des <input>-Tags.
	public $label; //Der Angezeigte Name des Formular-Felds.
	public $eval; //Keywordliste für die Validierung und Formatierung der Felder (Keywords: required, email, datetime, date ,time, num, confirm)
	public $description; //Beschreibung des Feldes.
	public $fieldWidth; //Die Breite des Feldes in Pixel.
	public $id;
    public $onChange; //onChange-Attribut des Input Feldes.
    public $onBlur;   //onBlur-Attribut des Input Feldes.
    public $onFocus; //onFocus-Attribut des Input Feldes.


	/**
	 * Attribute für Textfelder.
	 */

	public $size; //Die länge des Textfeldes in Stellen.
	public $max; //Die maximale Anzahl Zeichen, die eingegeben werden können.
	public $value; //Der Default-Wert des Feldes.
    


	/**
	 * Attribute für Textareas.
	 */

	public $cols; //Die Breite des Textfeldes in Anzahl Zeichen.
	public $rows; //Die Höhe des Textfeldes in Zeilen angegeben.


	/**
	 * Attribute für Select-Boxes.
	 */

	public $multiple;


	/**
	 * Attribute für die Datenabfüllung der Select-Boxes und Radio-Felder.
	 */

	public $table; //Tabelle, die die Daten enthält.
	public $key; //Spaltenname, der für die Wertübergabe genutzt wird (normalerweise: PrimaryKey)
	public $display; //Komma-getrennte Liste der Spalten, die angezeigt werden sollen.
	public $where; //Where-Teil der SQL-Abfrage.
    public $orderBy; //ORDER BY -Teil der SQL-Abfrage.
    
	/**
	 * Attribute für Captcha-Validierungsbilder.
	 */

	public $length;

	/**
	 * Diverse Attribute.
	 *
	 * @todo Die $requiredBold Variable sollte durch CSS-Styling ersetzt werden.
	 */

	public $requiredBold; //Sollen Labels von Feldern, die als Muss-Felder deklariert sind, fett dargestellt werden?
	public $CSS;
	public $JS;
    public $args; //Eventuelle weitere Werte.
    
	/**
	 * Zählt die Instazen eines Objekts
	 *
	 * @var int
	 */
	private static $instances = 0;
   

	/**
	 * Konstruktor
	 * Arbeitet folgende Befehlskette ab.
	 * 1. Standardwerte werden gesetzt (setDefaults())
	 * 2. Übergebene Werte werden gespeichert.
	 * 3. Über Formulare übergebene Werte ($_GET,$_POST) werden eingebunden.
	 *
	 * @return form
	 */
	function formElement($type='',$readOnly='') {
		$this->setDefaults();

		$this->type=$type!=''?$type:$this->type;
		$this->readOnly=$readOnly!=''?$readOnly:$this->readOnly;

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
		$this->type=formElement::TYPE_TEXT;
		$this->mulitple=false;
		$this->readOnly=false;
		$this->size=50;
		$this->max=0;
		$this->cols=100;
		$this->rows=10;
		$this->fieldWidth=300;


		$this->CSS='
.form_text {
	font-family:tahoma,verdana;
	font-size:12px;
	border:1px solid #3a5f7b;
	background-color:#FFFFFF;
}

.form_text_error {
	font-family:tahoma,verdana;
	font-size:12px;
	border:2px solid #980000;
	background-color:#FFFFFF;
}

.form_select {
	font-family:tahoma,verdana;
	font-size:12px;
	border:1px solid #3a5f7b;
}

.form_select_error {
	font-family:tahoma,verdana;
	font-size:12px;
	font-weight:bold;
	border:2px solid #980000;
	background-color:#980000;
	color:#FFFFFF;
}

.form_checkbox {

}

.form_textarea {
	font-family:tahoma,verdana;
	font-size:12px;
	border:1px solid #3a5f7b;
}

.form_textarea_error {
	font-family:tahoma,verdana;
	font-size:12px;
	border:2px solid #980000;
}';

		$this->JS='
function submitIfEnter(event, frm) {
   	if (event && event.keyCode == 13) {
   		frm.onsubmit();
   	} else {
   		return true;
   	}
}';
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
	 * Diese Funktion ruft je nach Typ des Elements die spezifische Funktion auf, welche den Inhalt des Feldes Liefert.
	 *
	 * @return array
	 */
	function wrapContent() {
		$this->parseFields();  
		$content=array();
		if($this->getInstances()==0) {
			$content['CSS'].=$this->CSS;
			$content['JS'].=$this->JS;
		}
		if($this->type!=formElement::TYPE_HIDDEN AND $this->type!=formElement::TYPE_CAPTCHA) {
			$content['main'].='<tr>
<td>'.$this->getLabel().'</td>
<td style="width:'.$this->fieldWidth.'px">';
		}

		switch($this->type) {
			case formElement::TYPE_TEXT:
				$content['main'].=$this->getInput_text();
			break;
			case formElement::TYPE_SELECT:
				$content['main'].=$this->getInput_select();
			break;
			case formElement::TYPE_RADIO:
				$content['main'].=$this->getInput_radio();
			break;
			case formElement::TYPE_CHECKBOX:
				$content['main'].=$this->getInput_checkbox();
			break;
			case formElement::TYPE_TEXTAREA:
				$content['main'].=$this->getInput_textarea();
			break;
			case formElement::TYPE_PASSWORD:
				$content['main'].=$this->getInput_password();
			break;
			case formElement::TYPE_HIDDEN:
				$content['main'].=$this->getInput_hidden();
			break;
            case formElement::TYPE_FILE:
                $content['main'].=$this->getInput_file();
            break;
			case formElement::TYPE_CAPTCHA:
				$content['main'].=$this->getInput_captcha();
			break;
            case formElement::TYPE_COORDNIATE:
                $content['main'].=$this->getInput_coordinate();
		}

		if($this->type!=formElement::TYPE_HIDDEN) {
			$content['main'].='</td>
</tr>
';
		}

		return $content;
	}
    
    public function init() {
        switch($this->type) {
            case formElement::TYPE_TEXT:
            break;
            case formElement::TYPE_SELECT:                  
            break;
            case formElement::TYPE_RADIO:                 
            break;
            case formElement::TYPE_CHECKBOX:                
            break;
            case formElement::TYPE_TEXTAREA:                
            break;
            case formElement::TYPE_PASSWORD:               
            break;
            case formElement::TYPE_HIDDEN:                 
            break;
            case formElement::TYPE_FILE:                
            break;
            case formElement::TYPE_CAPTCHA:               
            break;
            case formElement::TYPE_COORDNIATE:
                $this->init_coordinate();
            break;
        }
    }


	/**
	 * Formt aus den Klassenvariablen ein Textfeld und gibt den HTML-Code zurück.
	 *
	 * @return string
	 */
	private function getInput_text() {
		$readOnly=($this->readOnly?' readonly':'');
		$max=$this->max!=0?' maxlength="'.$this->max.'"':'';
		$size=$this->size!=0?' size="'.$this->size.'"':'';
		$value=$this->value!=''?' value="'.htmlspecialchars($this->value).'"':'';

		return '<input oldClass="form_text" id="'.$this->id.'" onMouseOver="javascript:Message(\'messagebox_'.$this->id.'\',\'Beschreibung:\',\''.$this->description.'\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_'.$this->id.'\')" errorClass="form_text_error" class="form_text" onkeypress="return submitIfEnter(event, this.form)" type="text" name="'.$this->name.'" style="width:100%"'.$this->getOnBlur().$this->getOnBlur().$this->getOnFocus().$value.$default.$size.$max.$readOnly.'>
';
	}

	/**
	 * Formt ein Select-Feld aus den Klassenvariablen und gibt den HTML-Code zurück.
	 *
	 * @return string
	 */
	private function getInput_select() {
		$items=$this->fetchData();
		if(is_array($items) && is_array($this->items)) {
			array_merge($items,$this->items);
		} elseif (is_array($items)) {
			$this->items=$items;
		}

		$size=$this->size!=0?' style="width:100%"':'';
		$multiple=($this->multiple&&!$this->readOnly?' multiple':'');
		$readOnly=($this->readOnly?' disabled':'');
		if($readOnly) {
			$nameAddon='_disabled';
			$content.=$this->getInput_hidden();
		}
			$content.='<select id="'.$this->id.'" onMouseOver="javascript:Message(\'messagebox_'.$this->id.'\',\'Beschreibung:\',\''.$this->description.'\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_'.$this->id.'\')" oldClass="form_select" errorClass="form_select_error" class="form_select" onkeypress="return submitIfEnter(event, this.form)" name="'.$this->name.$nameAddon.'"'.$this->getOnBlur().$this->getOnBlur().$this->getOnFocus().$mulitple.$readOnly.$size.'>
';

		$content.='<option value="">'.strtoupper($this->label).'...</option>';

		$i=0;
		foreach($this->items as $val=>$display) {
			if(is_array($this->value) && $this->multiple) {
				$preselect = array_search($val,$this->value)?' selected':'';
			} else {
				$preselect = $this->value==$val?' selected':'';
			}

			$content.='<option value="'.$val.'"'.$preselect.'>'.$display.'</option>
';
			$i++;
		}
		$content.='</select>
';
		return $content;
	}

	/**
	 * Formt ein oder mehrere Radio-Button-Feld aus den Klassenvariablen und gibt den HTML-Code zurück.
	 * Es sind auch mehrere Auswahlmöglichkeiten für die Radio-Box möglich. Die Einträge werden unter formElement::$items gespeichert.
	 *
	 * @return string
	 */
	private function getInput_radio() {
		$readonly=($this->readonly?' readonly':'');
		$content='<table id="'.$this->name.'">
';
		$i=0;
		foreach($items as $item) {
			//$preselect=(($this->preselect==$i||$this->reselect==$item[0])?' selected':'');

			$content.='<tr>
<td>
<input oldClass="form_radio" id="'.$this->id.'" onMouseOver="javascript:Message(\'messagebox_'.$this->id.'\',\'Beschreibung:\',\''.$this->description.'\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_'.$this->id.'\')" errorClass="form_radio_error" class="form_radio" type="radio" name="'.$this->name.'" onkeypress="return submitIfEnter(event, this.form)" style="width:100%" value="'.$item[0].'"'.$this->getOnBlur().$this->getOnBlur().$this->getOnFocus().$readonly.'>'.
'</td>
<td>'.
$item[1].
'</td>
</tr>
';
			$i++;
		}
		$content.='</table>
';
		return $content;
	}

	/**
	 * Formt ein Checkbox-Feld aus den Klassenvariablen und gibt den HTML-Code zurück.
	 *
	 * @return string
	 */
	private function getInput_checkbox() {
		$readonly=($this->readonly?' readonly':'');
		$preselect=(($this->value || $this->preselect)?' checked="checked"':'');

		$content.='
<input oldClass="form_checkbox" errorClass="form_checkbox_error" class="form_checkbox" id="'.$this->id.'" onMouseOver="javascript:Message(\'messagebox_'.$this->id.'\',\'Beschreibung:\',\''.$this->description.'\',this.id, 0)" onMouseOut="hideElement(\'messagebox_'.$this->id.'\')" onkeypress="return submitIfEnter(event, this.form)" type="checkbox" name="'.$this->name.'"'.$this->getOnBlur().$this->getOnBlur().$this->getOnFocus().$preselect.$readonly.'>
';
		return $content;
	}

	private function getInput_textarea() {
		$readOnly=($this->readOnly?' disabled':'');
		if($readOnly) {
			$content.=$this->getInput_hidden();
			$nameAddon='_disabled';
		}
		$content.='<textarea oldClass="form_textarea" id="'.$this->id.'" onMouseOver="javascript:Message(\'messagebox_'.$this->id.'\',\'Beschreibung:\',\''.$this->description.'\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_'.$this->id.'\')" errorClass="form_textarea_error" class="form_textarea" name="'.$this->name.$nameAddon.'" style="width:100%"'.$readOnly.' rows="'.$this->rows.'"'.$this->getOnBlur().$this->getOnBlur().$this->getOnFocus().'>'.$this->value.'</textarea>
';
		return $content;
	}

	/**
	 * Formt ein Passwort-Feld aus den Klassenvariablen und gibt den HTML-Code zurück.
	 * Falls formElement::eval "confirm" enthält, werden zwei Passwort-Felder generiert.
	 *
	 * @return string
	 */
	private function getInput_password() {
		$size=$this->size!=0?' size="'.$this->size.'"':'';
		$value=$this->value!=''?' value="'.$this->value.'"':'';

		$content='<input oldClass="form_text" errorClass="form_text_error" id="'.$this->id.'" onMouseOver="javascript:Message(\'messagebox_'.$this->id.'\',\'Beschreibung:\',\''.$this->description.'\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_'.$this->id.'\')" class="form_text" onkeypress="return submitIfEnter(event, this.form)" type="password" name="'.$this->name.'" style="width:100%"'.$this->getOnBlur().$this->getOnBlur().$this->getOnFocus().$size.$value.'>';
		if(preg_match("/confirm/",$this->eval)) {
			$content.='</td>
</tr>
<tr>
<td>'.$this->getLabel().' (wiederh.)</td>
<td><input oldClass="form_text" id="'.$this->id.'" onMouseOver="javascript:Message(\'messagebox_'.$this->id.'\',\'Beschreibung:\',\''.$this->description.'\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_'.$this->id.'\')" errorClass="form_text_error" class="form_text" onkeypress="return submitIfEnter(event, this.form)" type="password" name="'.$this->name.'2" style="width:100%"'.$this->getOnBlur().$this->getOnBlur().$this->getOnFocus().$size.$value.'>';
		}
		return $content;
	}

	/**
	 * Formt ein Hidden-Feld aus den Klassenvariablen und gibt den HTML-Code zurück.
	 *
	 * @return string
	 */
	function getInput_hidden() {
		return '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'"'.$this->getOnBlur().$this->getOnBlur().$this->getOnFocus().'>';
	}
    
    /**
     * Formt ein File-Feld aus den Klassenvariablen und gibt den HTML-Code zurück.
     *
     * @return string
     */
    function getInput_file() {
        return '<input type="file" name="'.$this->name.'"'.$this->getOnBlur().$this->getOnBlur().$this->getOnFocus().'>';
    }

	/**
	 * Formt eine Captcha-Box mit Bild, die einen menschlichen Benutzer sicherstellt.
	 *
	 * @return string
	 */
	function getInput_captcha() {
		$content.='<tr><td>';
		$content.='<img src="'.module::getClassPath('web_captcha').'?generate=true&length='.$this->length.'">';
		$content.='</td><td style="width:'.$this->fieldWidth.'px">';
		$content.=$this->getInput_text();
		return $content;
	}
    
    function getInput_coordinate() {
        $readOnly=' readonly';
        $max=$this->max!=0?' maxlength="'.$this->max.'"':'';
        
        //$size=$this->size!=0?' size="'.($this->size-20).'"':'';
        $value=explode(';',$this->value);
        
        $content.='<table border=0 style="width:100%;font-size:11px">
        <tr>
        ';                        
        $content.='<td style="width:10px">B:</td><td style="width:30%"><input oldClass="form_text" id="'.$this->id.'_latitude" onMouseOver="javascript:Message(\'messagebox_'.$this->id.'\',\'Beschreibung:\',\''.$this->description.'\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_'.$this->id.'\')" errorClass="form_text_error" class="form_text" onkeypress="return submitIfEnter(event, this.form)" type="text" style="width:90%"'.$this->getOnBlur().$this->getOnBlur().$this->getOnFocus().($this->value!=''?' value="'.htmlspecialchars($value[0]).'"':'').$default.$size.$max.$readOnly.'>
        </td>';                                                                                                                                                                                                                                                                                                                                                                                                                                        
         $content.='<td rowspan=2><span id="'.$this->id.'_info"></span><br><a href="javascript: var popupWin = window.open(\''.LIB_PATH.'class.form.php?action=checkCoordinates&lat=\'+document.getElementById(\''.$this->id.'_latitude\').value+\'&lng=\'+document.getElementById(\''.$this->id.'_longitude\').value+\'&posField='.$this->id.'\', \'Popup\', \'width=600,height=400,left=100,top=200,scrollbars=yes\'); popupWin.focus();" id="'.$this->id.'_link"></a>
        </td></tr><tr>';
        $content.='<td>L:</td><td><input oldClass="form_text" id="'.$this->id.'_longitude" onMouseOver="javascript:Message(\'messagebox_'.$this->id.'\',\'Beschreibung:\',\''.$this->description.'\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_'.$this->id.'\')" errorClass="form_text_error" class="form_text" onkeypress="return submitIfEnter(event, this.form)" type="text" style="width:90%"'.$this->getOnBlur().$this->getOnBlur().$this->getOnFocus().($this->value!=''?' value="'.htmlspecialchars($value[1]).'"':'').$default.$size.$max.$readOnly.'>
        </td></tr></table>';
                                                                                                                                                                                                               
        
        

         $content.='<input type="text" style="display: none;" id="'.$this->id.'" name="'.$this->name.'" onChange="var coord=this.value.split(\';\'); document.getElementById(\''.$this->id.'_latitude\').value=coord[0]; document.getElementById(\''.$this->id.'_longitude\').value=coord[1];"'.($this->value!=''?' value="'.htmlspecialchars($this->value).'"':'').'>
         ';                                                                                                                        
         $content.='<input type="text" style="display: none;" id="'.$this->id.'_address" onChange="getLatLngFromAddress(this.value, function (){ if(latlng) {document.getElementById(\''.$this->id.'\').value=latlng.lat()+\';\'+latlng.lng(); document.getElementById(\''.$this->id.'\').onchange(); document.getElementById(\''.$this->id.'_latitude\').value=latlng.lat();document.getElementById(\''.$this->id.'_longitude\').value=latlng.lng();document.getElementById(\''.$this->id.'_link\').innerHTML=\'Prüfen\';document.getElementById(\''.$this->id.'_info\').innerHTML=\'Die Koordinaten wurden erfolgreich ermittelt.\';}else{document.getElementById(\''.$this->id.'_link\').innerHTML=\'Position manuell wählen\';document.getElementById(\''.$this->id.'_info\').innerHTML=\'Die Koordinaten konnten nicht ermittelt werden.\';}}); ">
         ';
         return $content;
    }
    
    function init_coordinate() {
        global $db;                        
        preg_match_all('/\[([^\]]*)\]([^\[]*)/',$this->args['coordinate_addressFields'],$results);
        //echo 'coordinate_addressFields::'.$db->view_array($results);
        $elements=array();
        
        $js.='if(';                                 
        for($i=0;$i<count($results[0]);$i++) {
            $elements[$i]=$this->form->getElementById($results[1][$i]);
            $js.=(($i==0)?'':'&&').'document.getElementById(\''.$elements[$i]->id.'\').value!=\'\'';
            $addressString.=(($i==0)?'':'+').'document.getElementById(\''.$elements[$i]->id.'\').value';
            if($results[2][$i])
                $addressString.='+\''.$results[2][$i].'\'';
        }
        $js.=') {
    
     document.getElementById(\''.$this->id.'_address\').value='.$addressString.';
     document.getElementById(\''.$this->id.'_address\').onchange();
    
}';
        for($i=0;$i<count($elements);$i++) {
            $elements[$i]->onBlur=$js;
        }
        //echo htmlspecialchars($js);

    }

	/**
	 * Erzeugt ein Array, das die Daten aus der Tabelle formElemet::table enthält, um sie in eine Select-Box oder eine Radio-Auswahl einzufüllen.
	 *
	 * @return Array, das Daten fürs die Select-Box enthält ([0]=zu übergebender Wert | [1]=angezeigter Wert)
	 */
	private function fetchData() {
		global $db;


		$separator=" | ";
		if(isset($this->table) && isset($this->key) && isset($this->display)) {
            
			$result=$db->exec_SELECTquery($this->key.','.$this->display,$this->table,$this->where,'',$this->orderBy);
			$data=array();
			$i=0;

			while($row=$db->sql_fetch_row($result)) {
				$str="";
				for($g=1;$g<count($row);$g++) {
					$str.=$row[$g];
					if($g!=(count($row)-1)) { $str.=$separator;	}
				}
				$data[$row[0]]=$str;

				$i++;
			}

			return $data;
		}
	}

	/**
	 * Ist das Formular-Element ein Pflicht-Feld?
	 *
	 * @return boolean
	 */
	function isRequired() {
		return preg_match("/required/i",$this->eval);
	}

	/**
	 * Liefert die formatierte Feldbeschriftung.
	 *
	 * @return string
	 */
	function getLabel() {
		return $this->requiredBold&&$this->isRequired()?div::htm_bold(ucfirst($this->label)):ucfirst($this->label);
	}

	/**
	 * Ersetzt den Platzhalter "<func>{...}</func>" mit dem Resultat das die funktion innerhalb des <func>-Tags liefert.
	 *
	 * @see formElement::parseFields()
	 * @param string $str
	 * @return string
	 */
	function parseString($str,$args=null) {
		global $db;
		$str=div::var_parseString($str,$args);

		If (preg_match_all("/<action>(.*?)<\/action>/s",$str,$action_array)) {
			$action=table::decodeAction();
			for($i=0;$i<count($action_array[0]);$i++) {
				$str=preg_replace("/".preg_quote($action_array[0][$i],"/")."/s",$action[$action_array[1][$i]],$str);
			}
		}

		$action=table::decodeAction();
		$str=preg_replace("/<foreign_uid>/",$action['foreign_uid'],$str);
		$str=preg_replace("/<uid>/",$action['uid'],$str);
		return $str;
	}

	/**
	 * In der WHERE-Klause (formElement::where) der Datenbankabfrage können Platzhalter angegeben werden, welche mit dieser Funktion
	 * durch den "echten" Wert ersetzt werden.
	 */
	function parseFields() {
		$this->where=$this->parseString($this->where);
	}

	/**
	 * Überprüft, ob das übergebene $eval im formElement::eval-String enthalten ist.
	 *
	 * @see formElement::eval
	 * @param string $eval
	 * @return boolean
	 */
	function hasEval($eval) {
		$options=explode(",",$this->eval);
		foreach($options as $option) {
			if($eval==$option) {
				return true;
			}
		}
	}
    
    function getOnChange() {
        return $this->onChange?' onChange="'.$this->parseString($this->onChange).'"':'';
    }
    
    function getOnBlur() {
        return $this->onBlur?' onBlur="'.$this->parseString($this->onBlur).'"':'';
    }
    
    function getOnFocus() {
        return $this->onFocus?' onFocus="'.$this->parseString($this->onFocus).'"':'';
    }
}

/**
 * Diese Klasse Speichert Einstellungen für Buttons, die in Formularen verwendet werden können.
 * Diese Klasse stellt folgende Funktionen zur Verfügung:
 * - Anzeige eines Icons
 * - Frei definierbare Aktionen, die beim klicken ausgeführt wird.
 * - Frei definierbare Formatierung mit CSS.
 *
 * Diese Klasse wird normalerweise automatisch von der form Klasse Instanziiert.
 */
class formButton extends object {
	public $icon; //URL vom Rootverzeichnis zum Bild.
	public $onClick; //Aktion, die beim Klick auf den Button ausgeführt werden soll (Java-Script).
	public $text; //Text des Labels, das angezeigt wird.

	public $CSS;//Die Formatierung des Buttons.
	public $border;
	public $bgColor;
	public $fgColor;

	/**
	 * Zählt die Instazen eines Objekts
	 *
	 * @var int
	 */
	private static $instances = 0;

	/**
	 * Konstruktor
	 * Arbeitet folgende Befehlskette ab.
	 * 1. Standardwerte werden gesetzt (setDefaults())
	 * 2. Übergebene Werte werden gespeichert.
	 * 3. Über Formulare übergebene Werte ($_GET,$_POST) werden eingebunden.
	 *
	 * @return form
	 */
	public function formButton($text,$onClick=null,$icon=null) {
		$this->setDefaults();

		$this->text=$text;
		$this->onClick=$onClick;
		$this->icon=$icon;
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
	function setDefaults() {
		$this->border='1px solid #46b0ee';
		$this->fgColor='#ff9000';
		$this->bgColor='#FFFFFF';
	}

	function getCSS() {
		return '
.form_button_icon_'.$this->getInstances().' {
	cursor:pointer;
	padding:4px;
	text-align:center;
	vertical-align:middle;
	border-top:'.$this->border.';
	border-left:'.$this->border.';
	border-bottom:'.$this->border.';
	background-color:'.$this->bgColor.';
}

.form_button_main_'.$this->getInstances().' {
	cursor:pointer;
	border-top:'.$this->border.';
	border-bottom:'.$this->border.';
	color:'.$this->fgColor.';
	font-family:tahoma;
	font-size:11px;
	font-weight:bold;
	padding:2px;
	background-color:'.$this->bgColor.';
}

.form_button_right_'.$this->getInstances().' {
	cursor:pointer;
	width:0px;
	border-top:'.$this->border.';
	border-right:'.$this->border.';
	border-bottom:'.$this->border.';
	background-color:'.$this->bgColor.';
}';
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
	 * In dieser Funktion wird der HTML-Code für den Button generiert.
	 *
	 * @return array
	 */
	function wrapContent() {
		$content=array();

		$content['CSS'].=$this->getCSS();


		$icon=$this->icon?div::htm_wrapIcon($this->icon):'&nbsp;';
		$content['main'].='<div style="padding:2px;">
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="form_button_icon_'.$this->getInstances().'" onClick="'.$this->onClick.'">'.$icon.'</td>
		<td class="form_button_main_'.$this->getInstances().'" onClick="'.$this->onClick.'">'.$this->text.'</td>
		<td class="form_button_right_'.$this->getInstances().'" onClick="'.$this->onClick.'">&nbsp;</td>
	</tr>
</table>
</div>';

		self::$instances++;
		return $content;
	}    
}

class formSubheader extends object {
    public $text;
    public $CSS;
    public $id;
    
    private static $instances = 0;
    
    public function __construct($text='') {
        $this->text=$text;
        
        $this->CSS='
.form_subheader {
    color:#555555;
    font-family:tahoma;
    font-size:12px;
    font-weight:bold;
    background-color:#dddddd; 
    padding:3px;
    border-bottom:3px solid #555555;
    
}

.form_subheader_field {    
   height:30px;
   vertical-align:bottom;
   margin:30px;
}';
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
    
    
    public function wrapContent() {
        
        $content=array();
        if($this->getInstances()==0) {
            $content['CSS'].=$this->CSS;
        }
        $content['main'].='<tr style="border:1px solid green;margin:10px">
            <td colspan=2 class="form_subheader_field"><p class="form_subheader" id="'.$this->id.'">'.$this->text.'</p></td>
            </tr>';
            
        self::$instances++;
        return $content;
    }
    

}


if($_GET['action']=='checkCoordinates') {
    $content=array();
   // $content['header'].=div::htm_includeJSFile('http://maps.google.com/maps/api/js?sensor=false');
    $content['header'].=div::htm_includeJSFile(LIB_PATH.'googlemaps.js');   
    $content['main'].='<div id="map_canvas" style="width:100%; height:90%"></div>
    <script language="JavaScript" type="text/JavaScript">getCoordinateChooser(\'map_canvas\',\''.$_GET['lat'].'\',\''.$_GET['lng'].'\');</script>
    
    ';
    $button = new formButton('Position übernehmen','opener.document.getElementById(\''.$_GET['posField'].'\').value=latlng.lat()+\';\'+latlng.lng();opener.document.getElementById(\''.$_GET['posField'].'\').onchange(); window.close();');
    div::htm_mergeSiteContent($content,$button->wrapContent());
    div::htm_echoContent($content,"Koordinaten auswählen");
}

?>
