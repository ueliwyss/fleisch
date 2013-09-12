<?
class template extends object {

	public $text;
	public $html_charset;

	public $entities;

	private $dressing_internals='{§::value::§}';

	private $_isFullFileLoaded=false;



	public function template() {

	}

	public static function getParams() {
		$params=array(
			'options'=>array(
				'dressings'=>array(
					'vars'=>'string',
					'funcs'=>'string',
					'methods'=>'string',
					'entities'=>'string',
					'comment'=>'string',
					'getPostValues'=>'string',
					'sections_begin'=>'string',
					'sections_end'=>'string',
				),
			),
		);

		self::mergeParams($params,null);
		return $params;
	}

	public function setDefaults() {
		$this->configSets[0]=array(
			'options'=>array(
				'vars'=>'{<var>::result::</vars>}',
				'funcs'=>'{<func>::result::</func>}',
				'methods'=>'{<method>::result::</method>}',
				'enitities'=>'{#::result::#}',
				'comment'=>'{*::result::*}',
				'getPostValues'=>'{<gp>::result::</gp>',
				'sections_begin'=>'{<section name="::result::">}',
				'sections_end'=>'{</section>}'
			),
		);

	}

	public function loadTemplate($file, $html_charset='') {
		if($this->text=div::file_readFile($file)) {
			$this->html_charset=$html_charset;
			$this->_isFullFileLoaded=true;
			return true;
		}
		return false;
	}

	public function loadTemplateSection($file, $block, $html_charset='') {
		if(file_exists($file)) {
			$fp = fopen($file, "r");

			$stop=false;
			$saveLine=false;

			$args=array(
				'::result::'=>$block,
			);
			$block_begin=$this->_mergeString($this->config['options']['sections_begin'],$args);
			$block_end=$this->_mergeString($this->config['options']['sections_end'],$args);

			while($line=fgets($fp,1024) AND !$stop) {
				if(!$saveLine) {
					if(preg_match("/".$blockName."/",$line)) {
						//TESTEN!!!!
				  		$line=substr($line,strpos($line,$block_start));
				  		$saveLine=true;
				  	}
				}
				if($saveLine) {
					if(preg_match("/".$blockName."/",$line)) {
						//TESTEN!!!
						$line=substr($line,0,strpos($line,$block_stop));
						$stop=true;
					}
					$this->text.=$line;
				}
			}
			fclose($fp);
		}
	}

	private function _mergeString($string, $args=null) {
		if(is_array($args)) {
			foreach($args as $entity=>$value) {
				$string=str_replace($entitiy,$value,$string);
			}
		}
	}


}