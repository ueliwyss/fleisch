<?
if(isset($_POST['currequest']) OR isset($_GET['currequest'])) {
	header('Content-Type: text/xml');
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

	$doNotLogin=true;
	include_once('../../init.php');

	$cur=new currency();
	$cur->sendAnswer();
	die();
}



class currency {

	Const ForeignCurrencyFile = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";

	private static $instances=0;

	public $inputCurrency;
	public $rates;
	public $usedCurrencies;

	public function currency($inputCurrency='CHF') {
		self::updateCurrencyFile();

		$this->inputCurrency = $inputCurrency;
		$this->setDefaults();

		$this->rates = $this->loadRates();
	}

	private function setDefaults() {
		$this->usedCurrencies= array(
			'USD'=>'$',
			'EUR'=>'&#8364;',
			'CHF'=>'sFr',
		);
	}

	private static function loadRates() {
		global $db;

		$rawRates=simplexml_load_file(self::getLocalCurrencyFile());

		$rates=array();

		foreach($rawRates->Cube->Cube->Cube as $cube) {
			$rates[(string) $cube['currency']]=(string) $cube['rate'];
		}
		$rates['EUR']=1;

		return $rates;
	}

	public static function convert($value,$input_currency,$output_currency) {



		$rates=self::loadRates();

		if(isset($rates[$input_currency]) && isset($rates[$output_currency])) {
			$value=$rates[$output_currency]/$rates[$input_currency]*$value;
			$output=self::formatValue($value,$output_currency);
		}


		return $output;
	}

	public static function formatValue($value,$currency) {
		$output=array();

		if($currency=='CHF') {
			$output['value']=round($value / 0.05) * 0.05;
		} else {
			$output['value']=round($value,2);

		}

		if($output['value']==round($output['value'])) {
			$output['text']=(string) $output['value'].'.--';
		} else {
			$num_absentChars=((strlen(round($output['value'],0))+3)-(strlen(round($output['value'],2))));
			$output['text']=$output['value'].str_repeat('0',$num_absentChars);
		}

		return $output;
	}

	private static function getLocalCurrencyFile() {
		return TEMP_DIR.div::file_getRawFilename(self::ForeignCurrencyFile);
	}

	function updateCurrencyFile() {

		if(file_exists($this->getLocalCurrencyFile())) {
			if(div::date_diff('d',filemtime($this->getLocalCurrencyFile()),time(),true)>=1) {
				$update=true;
			}
		} else {
			$update=true;
		}

		if($update) {
			copy(self::ForeignCurrencyFile,$this->getLocalCurrencyFile());
			return true;
		} else {
			return false;
		}

	}

	public function sendAnswer() {
		global $db;

		$input_currency = div::http_getGP('input_currency');
		$output_currency = div::http_getGP('output_currency');
		$value = div::http_getGP('value');

		$answer=array();
		$answer['currency'] = $this->convert($value,$input_currency,$output_currency);
		$answer['currency']['text']=$this->usedCurrencies[$output_currency]." ".$answer['currency']['text'];


		echo div::xml_ArrayToXML($answer);
	}

	public function wrapPriceTag($value,$input_currency) {
		$ajax = new ajax();
		$ajax->setCustomParams(MOD_DIR.'web_currency/class.currency.php',array(
			'input_currency'=>$input_currency,
			'currequest'=>'',
			'value'=>$value,
			));
		$ajax->jsHandlerFuncName = 'handle_'.self::$instances;
		$ajax->jsLoadingFuncName = 'load_'.self::$instances;
		$ajax->jsErrorFuncName = 'error_'.self::$instances;


		$content=array();

		$price=self::formatValue($value,$current_currency);

		$content['main'].='<div id="priceTag_'.self::$instances.'" class="priceTag"><div id="priceTag_Price_'.self::$instances.'" class="priceTag_Price" value="'.$price['value'].'">'.$this->usedCurrencies[$input_currency].' '.$price['text'].'</div>
		</div>';

		$content['JS']='

    function handle_'.self::$instances.'(caller) {

    	var xml = http_request_'.ajax::getInstances().'.responseXML;
		var textNodes = xml.getElementsByTagName("text");
		var valueNodes = xml.getElementsByTagName("value");


    	document.getElementById("priceTag_Price_'.self::$instances.'").firstChild.nodeValue=xml.getElementsByTagName("text")[0].firstChild.nodeValue;
    	document.getElementById("priceTag_Price_'.self::$instances.'").value=xml.getElementsByTagName("value")[0].firstChild.nodeValue;
    }

    function error_'.self::$instances.'(caller) {
    }

    function load_'.self::$instances.'(caller) {

    }

    function onChange_'.self::$instances.'() {
		selectbox=document.getElementById("priceTag_Select");
		currency=selectbox.options[selectbox.selectedIndex].value;



    	ajaxRequest_'.ajax::getInstances().'(\''.$ajax->getRequestFile().'\',\''.$ajax->getParams().'&output_currency=\'+currency,this);
    }
';

		div::htm_mergeSiteContent($content,$ajax->wrapContent());


		self::$instances++;
		return $content;
	}

	public static function getInstances() {
		if(self::$instances==0) {
			return '0';
		} else {
			return self::$instances;
		}
	}

	public function wrapCurrencySelector($input_currency='CHF') {
		$content=array();
		$content['main'].='<div class="priceTag_Select">'.text::getText('fw_common_currency').'
			<select size=1 id="priceTag_Select" onChange="onChange()" style="float:left;">
		';
		foreach($this->usedCurrencies as $cur=>$symbol) {
			if($input_currency==$cur) {
				$selected=' selected';
			} else {
				$selected='';
			}
			$content['main'].='<option value="'.$cur.'"'.$selected.'>'.$cur.'</option>
			';
		}



		$content['main'].='
				</select>
			</div>';

		$content['JS'].='function onChange() {
			var i=0;
			var stop=false;

			while(!stop) {
				if(eval(\'typeof onChange_\'+i+\'=="function"\')) {
					eval(\'onChange_\'+i+\'.call()\');
				} else {
					stop=true;
				}
				i++;
			}
		}';
		return $content;
	}




}

?>
