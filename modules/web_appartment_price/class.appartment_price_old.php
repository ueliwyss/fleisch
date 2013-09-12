<?

$TABLES['appartment']=Array(
	'ctrl'=>Array(
		'isDbTable'=>true,
		'type'=>'normal',
	),
	'fields'=>Array(
		'uid'=>array(
			'label'=>'id',
			'description'=>'Primärschlüssel-Feld der Tabelle',
			'dbconfig'=>Array(
				'type'=>'int',
				'autoIncrement'=>1,
				'primaryKey'=>1
			),
		),
		'a_name'=>array(
			'label'=>'Sprach-Schlüssel',
			'description'=>'Name der Wohnung',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		'a_description'=>array(
			'label'=>'Beschreibung',
			'description'=>'',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),

		),
		'a_stars'=>array(
			'label'=>'Anzahl Sterne',
			'description'=>'Sterne',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'',
				)
			),
			'dbconfig'=>Array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		'a_images'=>array(
			'label'=>'Bilder',
			'description'=>'Bilder der Wohnung (#Bildpfad#||#Thumbpfad#||#Beschreibung#) Pro Zeile ein Bild! Bildgrösse: B:170px||H:150px',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>5000,
			),

		),
		'a_order'=>array(
			'label'=>'Reihenfolge',
			'description'=>'Anzeigereihenfolge der Wohnungen',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'num',
				)
			),
			'dbconfig'=>Array(
				'type'=>'int',
			),
		),
	),
	'forms'=>array(
		'new'=>array(
			'fields'=>'a_name,a_description,a_stars,a_images,a_order',
		),
		'edit'=>array(
			'fields'=>'a_name,a_description,a_stars,a_images,a_order',
		),
	),
	'lists'=>array(
		'listAll'=>array(
			'fields'=>'a_name,a_description,a_stars,a_order',
			'commonActions'=>array('drop'=>'',),
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
	),

);

$TABLES['appartment_price']=Array(
	'ctrl'=>Array(
		'isDbTable'=>true,
		'type'=>'normal',
	),
	'fields'=>Array(
		'uid'=>array(
			'label'=>'id',
			'description'=>'Primärschlüssel-Feld der Tabelle',
			'dbconfig'=>Array(
				'type'=>'int',
				'autoIncrement'=>1,
				'primaryKey'=>1
			),
		),
		'p_name'=>array(
			'label'=>'Name',
			'description'=>'Beschreibung des Preises',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		'p_currency'=>array(
			'label'=>'Währung',
			'description'=>'Welche Währung hat der Betrag?',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
					'items'=>array(
						'CHF'=>'CHF',
						'USD'=>'USD',
						'EUR'=>'EUR',
					),
					'eval'=>'required'
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),


		'p_value'=>array(
			'label'=>'Wert',
			'description'=>'',
			/*'foreign_where'=>'d_company_id=<func>$user->curusr_getCompany()</func>',*/
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'num,required',
				),
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),

		'p_appartment_id'=>array(
			'label'=>'Wohnung',
			'description'=>'Zugehörigkeit des Preises zu einer Wohnung.',
			'foreign_table'=>'appartment',
			'foreign_key'=>'uid',
			'foreign_display'=>'a_name',
			/*'foreign_where'=>'d_company_id=<func>$user->curusr_getCompany()</func>',*/
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
					'foreign_display'=>'a_name',
					'eval'=>'required',
				),
			),
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>11,
			),
		),
	),
	'forms'=>array(
		'new'=>array(
			'fields'=>'p_name,p_value,p_currency,p_appartment_id',
		),
		'edit'=>array(
			'fields'=>'p_name,p_value,p_currency,p_appartment_id',
		),
	),
	'lists'=>array(
		'listAll'=>array(
			'fields'=>'p_name,p_value,p_currency,p_appartment_id',
			'commonActions'=>array(
				'drop'=>'',
			),
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
		'addToSeason'=>array(
			'title'=>'Preise',
			'description'=>'Wählen sie den Preis aus, die Sie dem Benutzer zuweisen wollen.',
			'fields'=>'p_name,p_value,p_currency,p_appartment_id',
			'actions'=>array(
				'add'=>"javascript:opener.top.action('exec_func','&func=appartment_price::priceToSeason(<action>foreign_uid</action>,<uid>)'); opener.top.editElement('".table::MODE_EDIT."','appartment_season','<action>foreign_uid</action>',''); window.close();",
			),

		),
	),

);

$TABLES['appartment_season']=Array(
	'ctrl'=>Array(
		'isDbTable'=>true,
		'type'=>'normal',
	),
	'fields'=>Array(
		'uid'=>array(
			'label'=>'id',
			'description'=>'Primärschlüssel-Feld der Tabelle',
			'dbconfig'=>Array(
				'type'=>'int',
				'autoIncrement'=>1,
				'primaryKey'=>1
			),
		),
		's_date_from'=>array(
			'label'=>'Datum von:',
			'description'=>'Beginn des Jahresabschnitts',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'date'
				)
			),
			'dbconfig'=>array(
				'type'=>'date',
			),
		),
		's_date_to'=>array(
			'label'=>'Datum bis:',
			'description'=>'Ende des Jahresabschnitts',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'date'
				)
			),
			'dbconfig'=>array(
				'type'=>'date',
			),
		),
		's_name'=>array(
			'label'=>'Sprach-Schlüssel',
			'description'=>'Name des Jahresabschnitts',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		's_color'=>array(
			'label'=>'Frabe',
			'description'=>'angezeigte Farbe im Kalender',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		's_description'=>array(
			'label'=>'Beschreibung',
			'description'=>'Beschreibung',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),

	),
	'forms'=>array(
		'new'=>array(
			'fields'=>'s_name,s_date_from,s_date_to,s_color,s_description',
		),
		'edit'=>array(
			'fields'=>'s_name,s_date_from,s_date_to,s_color,s_description',
		),
	),
	'lists'=>array(
		'listAll'=>array(
			'fields'=>'s_name,s_date_from,s_date_to,s_color,s_description',
			'commonActions'=>array(
				'drop'=>'',
			),
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
		'price'=>array(
			'title'=>'Preise',
			'description'=>'Preise die für diesen Jahresabschnitt geltend gemacht werden.',
			'fields'=>'p_name,p_value,p_currency,p_appartment_id',
			'mm_table'=>'appartment_pricehasseason',
			'mm_key'=>'ps_season_id',
			'mm_foreign_key'=>'ps_price_id',
			'foreign_table'=>'appartment_price',
			'whereClause'=>'appartment_season.uid=<action>uid</action>',
			'commonActions'=>array(
				'drop'=>'',
			),
			'actions'=>array(
				'drop'=>'javascript:top.dropElement(\'appartment_pricehasseason\',\'<uid>\',\'<foreign_uid>\')',
			),
		),
	),

);

$TABLES['appartment_pricehasseason']=Array(
	'ctrl'=>Array(
		'isDbTable'=>true,
		'type'=>'intermediate',
	),
	'fields'=>Array(
		'ps_price_id'=>array(
			'label'=>'Preis',
			'description'=>'Preis einer Wohnung',
			'foreign_table'=>'appartment_price',
			'foreign_key'=>'uid',
			'foreign_display'=>'p_name',
			'dbconfig'=>Array(
				'type'=>'int',
				'length'=>11,
				'primaryKey'=>1
			),
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
				),
			),
		),
		'ps_season_id'=>array(
			'label'=>'Jahresabschnitt',
			'description'=>'Jahresabschnitt, der einem Preis zugewiesen werden kann.',
			'foreign_table'=>'appartment_season',
			'foreign_key'=>'uid',
			'foreign_display'=>'s_name',
			'dbconfig'=>Array(
				'type'=>'int',
				'length'=>11,
				'primaryKey'=>1
			),
			'formconfig'=>array(
				'all'=>array(
					/*'type'=>'select',
					Wie soll das dargestellt werden?*/
				),
			),
		),
	),
);

class appartment_price {

	var $sectionCSS = '.section {
	margin:0;
	color:white;
	border:2px solid #ffffff;
	margin:0;
	padding:0;
	margin-right:3px;
	display:none;

}

.section_head {
	margin:0;
	height:20px;
	vertical-align:middle;
	padding:2px;
	padding-left:5px;
	cursor:pointer;

}
.section_head_title {
	margin:0;
	font-weight:bold;
	font-size:13px;
	padding-left:10px;
	color:#ffffff;
}

.section_content {
	margin:0;
	color:#ffffff;
	padding-left:15px;
	padding-top:20px;
	padding-bottom:20px;
	padding-right:20px;
}

';

	private $isFirstDescription=true;
	/**
	 * Weist einen Preis einem Jahressaisonabschnitt zu.
	 *
	 * @param int $price_id ID des Preises
	 * @param int $season_id ID des Jahresabschnitts
	 */
	static function priceToSeason($season_id,$price_id) {
		global $db;

		$values=array(
			'ps_price_id'=>$price_id,
			'ps_season_id'=>$season_id,
		);
		$db->exec_INSERTquery("appartment_pricehasseason",$values);
	}

	/**
	 * Löscht die Verbindung zwischen einem Jahresabschnitt und eines Preises aus der Datenbank.
	 *
	 * @param int $season_id
	 * @param int $price_id
	 */
	static function delete_appartment_pricehasseason($season_id,$price_id) {
		global $db;

		$where="ps_season_id=".$season_id.($price_id?" AND ps_price_id=".$price_id:"");
		$db->exec_DELETEquery("appartment_pricehasseason",$where);
	}

	/**
	 * Löscht eine Wohnung aus der Datenbank mit allen ihrer Verbindungen zu anderen Tabellen.
	 *
	 * @param int $appartment_id
	 */
	static function delete_appartment($appartment_id) {
		global $db;

		$values=array(
			'p_appartment_id'=>'null',
		);
		$db->exec_UPDATEquery("appartment_price","p_appartment_id=".$appartment_id,$values);

		$db->exec_DELETEquery("appartment","uid=".$appartment_id);
	}

	/**
	 * Löscht einen Preis aus der Datenbank mit allen ihrer Verbindungen zu anderen Tabellen.
	 *
	 * @param int $price_id
	 */
	static function delete_appartment_price($price_id) {
		global $db;

		$db->exec_DELETEquery("appartment_pricehasseason","ps_price_id=".$price_id);

		$db->exec_DELETEquery("appartment_price","uid=".$price_id);
	}

	/**
	 * Löscht einen Jahresabschnitt aus der Datenbank mit allen ihrer Verbindungen zu anderen Tabellen.
	 *
	 * @param int $season_id
	 */
	static function delete_appartment_season($season_id) {
		global $db;

		$db->exec_DELETEquery("appartment_pricehasseason","ps_season_id=".$season_id);

		$db->exec_DELETEquery("appartment_season","uid=".$season_id);
	}

	function getSeasonsOfAppartment($appartment_id) {
		global $db;


		$query="SELECT appartment_season.uid, s_date_from, s_date_to, s_name, s_color
FROM appartment_season, appartment_pricehasseason, appartment_price
LEFT JOIN appartment ON appartment_price.p_appartment_id = appartment.uid
WHERE appartment_season.uid = appartment_pricehasseason.ps_season_id
AND appartment_price.uid = appartment_pricehasseason.ps_price_id AND appartment.uid=".$appartment_id;

		$res=$db->sql_query($query);

		$output=array();
		while($tempRow = $db->sql_fetch_assoc($res))	{
			$output[$tempRow['uid']] = $tempRow;
		}

		return $output;
	}

	function getAppartments() {
		global $db;

		$output=$db->exec_SELECTgetRows('uid,a_name','appartment','','','a_order ASC','','uid');



		return $output;
	}

	function getAppartment($appartment_id) {
		global $db;

		$output=$db->exec_SELECTgetRows('uid,a_name,a_stars,a_images','appartment','uid='.$appartment_id,'');



		return $output[0];
	}

	function getPriceCalendar($appartment_id=false) {
		global $db;

		$seasons=$this->getSeasonsForCalendar($appartment_id);

		//echo $db->view_array($seasons);

		$calendar=new calendar();
		$calendar->setLang($GLOBALS['lang']);
		$calendar->showToday=true;
		$calendar->showYearTitle=false;
		$calendar->showLegend=true;

		$currency=new currency();
		$currencySelector=$currency->wrapCurrencySelector();
		$calendar->headerContent=$currencySelector['main'];
		$currencySelector['main']='';

		$content=array();
		div::htm_mergeSiteContent($content,$currencySelector);


		$appartment_div='';

		$first_season=true;
		$colors=array();

		foreach($seasons as $season) {

			if(!$colors[$season['s_color']]) {
				$legendcontent=array();
				$appartments=$this->getAppartments();

				$legendcontent['main'].='<div class="legend_season_header">'.text::getText($season['s_name']).' <font size="0.5em">('.text::getText('fw_common_priceperweek').')</font></div>';
				$legendcontent['main'].='<div class="legend_season_content">';

				if($appartment_id) {

					$price=$this->getAppartmentPrice($appartment_id,$season['uid']);
					$cur_picker=$currency->wrapPriceTag($price['p_value'],$price['p_currency']);
					$cur_picker['CSS'].='#priceTag_Price_'.(currency::getInstances()-1).' {
		font-size:20px;
	}';
					$cur_picker['CSS'].='#priceTag_'.(currency::getInstances()-1).' {
		border:none;
	}';
					div::htm_mergeSiteContent($legendcontent,$cur_picker);

				} else {

					$appartment_div='<div id="legend_appartments">';
					$otherAppartments=array();
					foreach($appartments as $uid=>$data) {
						$price=$this->getAppartmentPrice($uid,$season['uid']);
						$cur_picker=$currency->wrapPriceTag($price['p_value'],$price['p_currency']);
						$otherAppartments['main'].='<div>';
						$appartment_div.='<div class="legend_appartment_name"><nobr>'.text::getText($data['a_name']).'</nobr></div>';
						div::htm_mergeSiteContent($otherAppartments,$cur_picker);
						$otherAppartments['main'].='</div>';
					}
					$appartment_div.='</div>';
					div::htm_mergeSiteContent($legendcontent,$otherAppartments);


				}

				$legendcontent['main'].='</div>';



				$content['JS'].=$legendcontent['JS'];
				$content['CSS'].=$legendcontent['CSS'];
			}

			$calendar->setColorArea(div::date_SQLToGer($season['s_date_from']),div::date_SQLToGer($season['s_date_to']),$season['s_color'],$legendcontent);
			if(!$appartment_id && $first_season) {
				$calendar->legendAddition=$appartment_div;
			}

			$colors[$season['s_color']]=$season['s_color'];
			$first_season=false;
		}




		//div::htm_mergeSiteContent($content,$currency->wrapCurrencySelector());

		div::htm_mergeSiteContent($content,$calendar->showYear(false,date('m')));

		$content['header'].=div::htm_includeCSSFile(MOD_DIR.'web_appartment_price/style.css');
		return $content;
	}

	function getSeasonsForCalendar($appartment_id=false) {
		global $db;

		if(!$appartment_id) {
			$appartments=$this->getAppartments();
			$seasons=array();
			foreach($appartments as $appartment) {
				$seasonsOfAppartment=$this->getSeasonsOfAppartment($appartment['uid']);
				foreach($seasonsOfAppartment as $uid=>$data) {
					if(!$seasons[$uid]) {
						$seasons[$uid]=$data;
					}
				}
			}

			return $seasons;
		} else { return $this->getSeasonsOfAppartment($appartment_id); }
	}

	function getAppartmentPrice($appartment_id,$season_id) {
		global $db;

		$query="SELECT  appartment_price.uid,p_value,p_currency FROM appartment_season, appartment_pricehasseason, appartment_price
LEFT JOIN appartment ON appartment_price.p_appartment_id = appartment.uid
WHERE appartment_season.uid = appartment_pricehasseason.ps_season_id
AND appartment_price.uid = appartment_pricehasseason.ps_price_id AND appartment.uid=".$appartment_id." AND appartment_season.uid=".$season_id;

		$res=$db->sql_query($query);

		$output=array();
		while($tempRow = $db->sql_fetch_assoc($res))	{
			$output = $tempRow;
		}

		return $output;
	}

	function getAppartmentDescription($appartment_id) {
		global $db;

		$galleryWidth=300;

		$content=array();

		if($this->isFirstDescription) {
			$content['JS']='


		var galleryWidth='.$galleryWidth.';

		function showImage(image,appID) {
			var freePlace=(galleryWidth-image.width);
			var numPics = eval("numImg_"+appID);

			var picWidth = Math.round(freePlace/(numPics-1));


			var order = image.getAttribute("picOrder");

			var i=1;
			while(i<numPics) {
				if(i>order) {
					document.getElementById("image_"+appID+"_"+i).style.marginLeft = (picWidth*(i-1)+image.width);
				} else {
					document.getElementById("image_"+appID+"_"+i).style.marginLeft = (picWidth*i);
				}
				i++;
			}
		}

		function hideImage(appID) {
			var numPics = eval("numImg_"+appID);
			var picWidth = Math.round(galleryWidth/numPics);

			var i=1;
			while(i<numPics) {
				document.getElementById("image_"+appID+"_"+i).style.marginLeft = (picWidth*i);
				i++;
			}
		}';
		}

		$this->isFirstDescription=false;

		$appartment=$this->getAppartment($appartment_id);

		$images=explode(chr(13).chr(10),$appartment['a_images']);

		$stars=explode("||",$appartment['a_stars']);
		if($stars[1]) {
			$stars[1]='<a href="'.$stars[1].'" target="_blank">';
		}

		$content['main']='


			<div class="appartment_description">
				<div class="appartment_description_header"><h3 name="app_'.$apparmtent_id.'">'.text::getText($appartment['a_name'].'_description_header').'&nbsp;&nbsp;'.$stars[1].str_repeat('<img src="images/star7.png" style="vertical-align:top;">',$stars[0]).($stars[1]?"</a>":"").'</h3></div>
				<div class="appartment_description_imageBox">
					<div class="appartment_description_images" id="app_'.$appartment_id.'">';
		$i=0;
		foreach($images as $image) {
			$image=explode("||",$image);
			$size = @getimagesize( $image["0"]);

			$content['main'].='
			<a href="'.$image[1].'" rel="lightbox['.$appartment_id.']" title="'.$image[2].'">
				<img id="image_'.$appartment_id.'_'.$i.'" src="'.$image["0"].'" alt="'.$image["2"].'" style="position:absolute;margin-left:'.($i*$galleryWidth/count($images)).'px;border:2px solid white;" onMouseover="showImage(this,'.$appartment_id.');" onMouseOut="hideImage('.$appartment_id.');" picOrder="'.$i.'" width="'.$size[0].'" height="'.$size[1].'">
			</a>';

			$i++;
		}

		$content['JS'].='
		var numImg_'.$appartment_id.'='.$i.';';




				$content['main'].='
				</div>
			</div>
				<div class="appartment_description_content">

					<p><ul>'.text::getText($appartment['a_name'].'_description_text').'</ul></p>

				<div class="appartment_description_price"><a href="'.div::http_getURL(array('id'=>'prices.php','app_id'=>$appartment['uid'])).'">'.text::getText('fw_appartments_prices').' '.text::getText('fw_common_from');


		div::htm_mergeSiteContent($content,$this->getPriceTag_fromPrice($appartment_id));

		$content['main'].=text::getText('fw_common_perweek');
		$content['main'].='</div></div></div>';

		return $content;
	}

	function getPriceTag_fromPrice($appartment_id) {
		global $db;

		$output=$db->exec_SELECTgetRows('uid,p_value,p_currency','appartment_price','p_appartment_id='.$appartment_id);

		$smallestval=false;
		$input_currency=$output[0]['p_currency'];


		foreach($output as $price) {
			if($smallestval) {
				$tmp=currency::convert($price['p_value'],$price['p_currency'],$input_currency);
				if($tmp['value']<$smallestval) {
					$smallestval=$tmp['value'];
				}
			} else {
				$tmp=currency::convert($price['p_value'],$price['p_currency'],$input_currency);
				$smallestval=$tmp['value'];
			}
		}

		$currency = new currency();
		$content=$currency->wrapPriceTag($smallestval,trim($input_currency));

		return $content;
	}

	function getAllAppartmentDescriptions() {
		$content=array();
		$appartments=$this->getAppartments();


		$content['main']='<div>';
		foreach($appartments as $appartment) {
			div::htm_mergeSiteContent($content,$this->getAppartmentDescription($appartment['uid']));
		}
		$content['main'].='</div>';
		$content['header'].=div::htm_includeCSSFile(MOD_DIR.'web_appartment_price/style.css');

		return $content;
	}


}