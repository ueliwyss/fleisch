<?
/**
 * Generierung von Sections
 *
 * @author Ueli Wyss
 * @package IPA-Vorbereitung
 */

class section extends container {
	Const STATUS_CLOSED=0;
	Const STATUS_OPEN=1;

	public $title;
	public $content;

	public $colors;
	public $icons;
	public $align;

	public $status; //0=open :: 1=closed

	private static $instances=0;

	public $CSS;


    
	function section($title,$content='') {
		$this->setDefaults();

		$this->title=$title;
		if($content) div::htm_mergeSiteContent($this->content,$content);
	}

	/**
	 * Filtert Informationen für die Konfiguration dieses Objekts aus den Arrays $_GET und $_POST und weist sie den entsprechenden Eigenschaften zu.
	 *
	 */
	private function init() {

	}

	private function setDefaults() {
		$this->icons=array(
			'SECTION_OPEN'=>ICON_DIR.'section_open.gif',
			'SECTION_CLOSED'=>ICON_DIR.'section_closed.gif',
		);

		$this->colors=array(
			'HEADER_BG'=>'#deecf7',
			'CONTENT_BG'=>'#FFFFFF',
		);

		$this->status=section::STATUS_OPEN;
		$this->align='left';

		$this->CSS='
a:hover{
	text-decoration:underline;
}

.section {
	position:relative;
	color:red;
	width:100%;
	border:1px solid #46b0ee;
	margin:10px;
}

.section_head {
	height:20px;
	width:100%;
	vertical-align:middle;
	padding:2px;
	padding-left:5px;
	cursor:pointer;
	background-color:'.$this->colors['HEADER_BG'].'
}
.section_head_title {
	font-weight:bold;
	font-size:11px;
	padding-left:10px;
	color:#417fc6;
}

.section_content{
	color:#000000;
	width:100%;
	padding-left:15px;
	padding-top:20px;
	padding-bottom:20px;
	padding-right:20px;
	background-color:'.$this->colors['CONTENT_BG'].'
}';
	}

	function getInstances() {
		if(self::$instances==0) {
			return '0';
		} else {
			return self::$instances;
		}
	}

	function wrapContent() {
		$content=array();

		$display=$this->status==section::STATUS_OPEN?'':'display:none;';
		$image=$this->status==section::STATUS_OPEN?$this->icons["SECTION_OPEN"]:$this->icons["SECTION_CLOSED"];
		$alt=$this->status==section::STATUS_OPEN?' alt="Schliessen"':' alt="&Ouml;ffnen"';

		$content['main']='<div class="section" id="section'.$this->getInstances().'">
  <div class="section_head" id="section_head'.$this->getInstances().'"onClick="javascript:section_expand(\'section_navsrc'.$this->getInstances().'\',\'section_content'.$this->getInstances().'\');">
    <table>
      <tr>
        <td><img id="section_navsrc'.$this->getInstances().'" src="'.$image.'"'.$alt.'></td>
		<td class="section_head_title">'.$this->title.'</td>
      </tr>
    </table>
  </div>
  <div class="section_content" id="section_content'.$this->getInstances().'" style="'.$display.';">'.$this->content['main'].'</div>
</div>';

		if($this->getInstances()==0) {
			$content['JS']='function section_expand(image,id) {
	element=document.getElementById(id);
	image=document.getElementById(image);
	if(element.style.display == "none") {
		element.style.display = "block";
		image.src = expander_image_1;
		image.alt="Schliessen";
	} else {
		element.style.display = "none";
		image.src = expander_image_2;
		image.alt="Öffnen";
	}
}
';
			$content['JS'].='
			var expander_image_1 = "'.$this->icons["SECTION_OPEN"].'";
			var expander_image_2 = "'.$this->icons["SECTION_CLOSED"].'";';

			$content['CSS']=$this->CSS;


		}

		$innerContent=$this->content;
		$innerContent['main']='';
		div::htm_mergeSiteContent($content,$innerContent);

		self::$instances++;

		return $content;
	}
}

?>