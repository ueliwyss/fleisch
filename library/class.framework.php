<?
abstract class object {
	//Enhält verschiedene Standardkonfigurationen der Klasse
	public $configSets;

	private $_currentConfigSet=0;
	private $_configFromConfigSet=false;

	//Enthält alle Einstellungsmöglichkeiten (Optionen) der Klasse.
	public $params;

	//Enthält die effektive Konfiguration des Objekts.
	public $config;





	public function object() {

	}

	public function mergeParams(&$params,$parentParams=null) {
		if(is_array($parentParams)) {
			$params=array_merge($parentParams,$params);
		}
	}

	public static function getParams() {
		/*$params=array('hallo');
		self::mergeParams($params,null);
		return $params;*/
	}

	//Ersetzt fehlende Einträge in der Konfiguration mit einträgen aus einem ConfigSet.
	public function mergeConfig() {
		if(!$this->_configFromConfigSet) {
			$this->_handleConfigMerging();
		}
	}

	private function _handleConfigMerging(&$cfgSetPart=null,&$config=null) {
		if(!$cfgSetPart) {
			$cfgSetPart&=$this->configSets[$this->_currentConfigSet];
		}

		if(!$config) {
			$config&=$this->config;
		}

		while(list($option,$value) = each($cfgSetPart)) {
			if(is_array($value)) {
				if(!is_array($config[$option])) {
					$config[$option]=array();
				}
				$this->_handleConfigMerging($value,$config[$option]);
			} else {
				if(!$config[$option]) {
					$config[$option]=$value;
				}
			}
		}
	}
    
    public function saveToSession() {
         $_SESSION['serialized_'.get_class($this)]=serialize($this);
        
    }
    
    public function restoreFromSession() {
        if(!$_SESSION['serialized_'.get_class($this)]) return false;
         return unserialize($_SESSION['serialized_'.get_class($this)]);
    }
    
    public static function staticrestoreFromSession($className) {
        if(!$_SESSION['serialized_'.$className]) return false;
         return unserialize($_SESSION['serialized_'.$className]);
    }
}


abstract class container extends object {

	/**
	 * Array, das alle Elemente des Formulars (Typ: formElement) enthält.
	 *
	 * @var array
	 */
	public $items;

	/**
	 * Standardfunktion: Fügt ein Element zum Array $items hinzu.
	 * Dabei können entweder die beiden Keywords "begin"(Positionierung am Anfang) und "end"(Positionierung am Ende)
	 * zur Positionierung der Elemente innerhalb des Arrays verwendet werden. Andererseits kann der index durch eine Zahl angegeben werden.
	 * Falls eine Position vor dem Ende des Arrays gewählt wird, werden alle nachfolgenden Elemente um eine Stelle nach hinten verschoben.
	 *
	 * @param formElement $item
	 * @param int $index
	 */
	function addElement($item, $index="end") {
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
    
    function flushElements() {
        $this->items=array();
    }
}

class color extends object{
	public $red;
	public $green;
	public $blue;
	public $alpha;
	public $hex;

	public $allocation;

	function color($red, $green=null, $blue=null, $alpha=null) {
		if(!is_numeric($red)) {
			$this->hex=$red;
			$this->hexToRgb();
		} else {
			$this->red=$red;
			$this->green=$green;
			$this->blue=$blue;
			$this->alpha=$alpha;
			$this->rgbToHex();
		}

	}

	function colorAllocate($image) {
		if(!$this->allocation) {
			$this->allocation = imagecolorallocatealpha($image,$this->red,$this->green,$this->blue,$this->alpha);
		}
		return $this->allocation;
	}

	function hexToRgb() {
		$this->hex=str_replace("#","",$this->hex);
		$this->red=hexdec(substr($this->hex,0,2));
		$this->green=hexdec(substr($this->hex,2,2));
		$this->blue=hexdec(substr($this->hex,4,2));
		//echo $this->red.".".$this->green.".".$this->blue;
	}

	function rgbToHex() {
		$this->hex='#'.dechex($this->red).dechex($this->green).dechex($this->blue);
	}
}