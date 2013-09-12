<?
if(isset($_POST['request']) OR isset($_GET['request'])) {
	$doNotLogin=true;
	include_once('../init.php');

	ajax::sendAnswer();
}

class ajax {
	public $jsLoadingFuncName;
	public $jsHandlerFuncName;
	public $jsErrorFuncName;

	public $requestType;

	public $paramAddition;

	private $requestFile;

	public $params=array();

	private static $instances=0;

	public function ajax() {
		$this->setDefaults();
	}

	public function setDefaults() {
		$this->jsFunctions='';
	}

	private function getBasicJS() {
		return "
    var http_request = false;
    var func_caller = false;

    function ajaxRequest_".self::getInstances()."(url,params,caller) {
		//Loading-Funktion wird aufgerufen.

		func_caller=caller;
		".$this->jsLoadingFuncName."(func_caller);

        http_request_".self::getInstances()." = false;

        if (window.XMLHttpRequest) { // Mozilla, Safari,...
            http_request_".self::getInstances()." = new XMLHttpRequest();
            if (http_request_".self::getInstances().".overrideMimeType) {
                http_request_".self::getInstances().".overrideMimeType('text/xml');
                // zu dieser Zeile siehe weiter unten
            }
        } else if (window.ActiveXObject) { // IE
            try {
                http_request_".self::getInstances()." = new ActiveXObject(\"Msxml2.XMLHTTP\");
            } catch (e) {
                try {
                    http_request_".self::getInstances()." = new ActiveXObject(\"Microsoft.XMLHTTP\");
                } catch (e) {}
            }
        }

        if (!http_request_".self::getInstances().") {
            //Fehler-Funktion wird aufgerufen.
            ".$this->jsErrorFuncName."(func_caller);
            return false;
        }


        http_request_".self::getInstances().".onreadystatechange = handleRequest_".self::getInstances().";
        http_request_".self::getInstances().".open('POST', url, true);
        http_request_".self::getInstances().".setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        http_request_".self::getInstances().".send(params);

    }

    function handleRequest_".self::getInstances()."() {
		if (http_request_".self::getInstances().".readyState == 4) {
            if (http_request_".self::getInstances().".status == 200) {
                //Handler-Funktion aufrufen.
                ".$this->jsHandlerFuncName."(func_caller);
            } else {
                //Fehler-Funktion aufrufen.
                ".$this->jsErrorFuncName."(func_caller);
            }
        } else if(http_request_".self::getInstances().".readyState == 2) {
        	//Loading-Funktion wird aufgerufen.
			".$this->jsLoadingFuncName."(func_caller);

        	setTimeout('handleRequest_".self::getInstances()."();',200);
        } else {
        	".$this->jsErrorFuncName."(func_caller);
        }

    }";
	}

	public function wrapContent() {

		$content=array();
		$content['JS']=$this->getBasicJS();

		self::$instances++;

		return $content;
	}

	/**
	 * Soll aufgerufen werden, um das JavaScript-Snippet, das die AJAX-Request auslöst zu erhalten.
	 *
	 * @param unknown_type $url
	 * @param unknown_type $params
	 * @return unknown
	 */
	public function getJSRequestFuncCaller($additionalParams='') {
		if(is_array($additionalParams)) {
			$this->params = array_merge($this->params,$additionalParams);
		}
		return 'ajaxRequest_'.self::getInstances().'(\''.$this->getRequestFile().'\',\''.$this->getParams().'\',this);';
	}

	private static function getClassFileName() {
		return LIB_PATH.'class.ajax.php';
	}

	public function getRequestFile() {
		if(!$this->requestFile) {
			return $this->getClassFileName();
		} else {
			return $this->requestFile;
		}
	}

	public function getParams() {
		$first=true;
		if($this->requestType!='custom') {
			$params='request=';
			$params.='&requestType='.$this->requestType;
			$first=false;
		}



		foreach($this->params as $param=>$value) {
			$params.=($first?'':'&').$param.'='.$value;
			$first=false;
		}
		return $params;
	}

	public static function getInstances() {
		if(self::$instances==0) {
			return '0';
		} else {
			return self::$instances;
		}
	}

	public function setParamsForFile($path) {
		$this->requestType='file';
		$this->params['path']=$path;
	}

	public function setParamsForSQL($select_fields,$from_table=null,$where_clause=null,$groupBy=null,$orderBy=null,$limit=null) {
		$this->requestType='sql';
		if(!$from_table) {
			$this->params['query']=$select_fields;
		} else {
			$this->params['select_fields']=$select_fields;
			$this->params['from_table']=$from_table;
			$this->params['where_clause']=$where_clause;
			$this->params['groupBy']=$groupBy;
			$this->params['orderBy']=$orderBy;
			$this->params['limit']=$limit;
		}
	}

	public function setCustomParams($requestFile,$params='') {
		$this->requestType='custom';
		$this->requestFile=$requestFile;
		if($params!='') {
			$this->params=$params;
		}

	}

	/**
	 * Sendet eine Antwort auf eine AJAX-Request.
	 * Die Parameter, die die gewünschten Daten bezeichnen, werden mittels GET oder POST übertragen.
	 *
	 *
	 */
	public static function sendAnswer() {
		global $db;
		switch(div::http_getGP('requestType')) {
			case 'sql':
				if(div::http_getGP('query')) {
					$result=$db->sql_query(div::http_getGP('query'));
					while($row=$db->sql_fetch_assoc($result)) {
						$rows[]=$row;
					}
					$xml=div::xml_ArrayToXml($rows);
				} else {
					$xml=div::xml_ArrayToXml($db->exec_SELECTgetRows(div::http_getGP('select_fields'),div::http_getGP('from_table'),div::http_getGP('where_clause'),div::http_getGP('groupBy'),div::http_getGP('orderBy'),div::http_getGP('limit')));
				}
			break;
			case 'file':
				$xml=htmlspecialchars(div::file_readFile('../'.div::http_getGp('path')));

			break;
		}
		echo $xml;
	}
}
?>