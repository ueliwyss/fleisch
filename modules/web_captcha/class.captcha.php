<?
if($_GET['generate']=='true') {
	unset($_GET['generate']);

	$doNotLogin=true;

	include_once('../../init.php');
	$captcha=new captcha();

	foreach($_GET as $prop=>$value) {
		if($value) {
			$captcha->$prop = $value;
		}
	}
	$captcha->wrapCaptcha();
}


class captcha {
	public $length;
	public $fonts;
	public $bgcolor;
	public $fgcolors;
	public $border;

	public $minImageHeight;
	public $minImageWidth;

	public $numeric;
	public $uppercase;
	public $lowercase;

	public $imageType;

	public $pointIntensity;

	private $captchaString;
	private $maxAngle;
	private $x_factor;
	private $y_factor;



	/**
	 * Konstruktor
	 * Arbeitet folgende Befehlskette ab.
	 * 1. Standardwerte werden gesetzt (setDefaults())
	 * 2. Übergebene Werte werden gespeichert.
	 * 3. Über Formulare übergebene Werte ($_GET,$_POST) werden eingebunden.
	 *
	 * @return captcha
	 */
	function captcha() {
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
		$this->fgcolors=array(
			new color(255,255,255,0),
			new color(131,0,0,0),
			new color(125,125,125,0),
			new color(248,211,41,20),
		);
		$this->border=9;
		$this->length=6;
		$this->bgcolor=new color(0,0,0,0);
		$this->uppercase=true;
		$this->lowercase=false;
		$this->numeric=false;
		$this->minImageHeight=0;
		$this->minImageWidth=0;
		$this->imageType='png';
		$this->fonts=array(
			array(
				'font'=>'Rough.ttf',
				'size'=>20,
			),
			array(
				'font'=>'steelfib.ttf',
				'size'=>27,
			),
			/*array(
				'font'=>'../symbole/justadream.ttf',
				'size'=>32,
			),*/
			array(
				'font'=>'Sickness.ttf',
				'size'=>25,
			),
			//'../symbole/Littlelo.ttf',
			//'../symbole/Ghastly Panic.ttf',
			//'../symbole/LokiCola.ttf',
		);

		$this->maxAngle=25;
		$this->x_factor=1.0;
		$this->y_factor=1.0;
		$this->pointIntensity=400;

	}

	function stringGen () {
	  $charPool=array();

      if($this->uppercase) {
      	$uppercase  = range('A', 'Z');
      	$charPool=array_merge($charPool,$uppercase);
      }

      if($this->numeric) {
      	$numeric    = range(0, 9);
      	$charPool=array_merge($charPool,$numeric);
      }

      if($this->lowercase) {
      	$lowercase = range('a', 'z');
      	$charPool=array_merge($charPool,$lowercase);
      }

      $poolLength = count($charPool) - 1;

      for ($i = 0; $i < $this->length; $i++) {
        $this->captchaString .= $charPool[mt_rand(0, $poolLength)];
      }
    }

    function wrapCaptcha() {
    	session_start();

    	$this->stringGen();

    	$settings=array();

    	for($i=0;$i<$this->length;$i++) {
    		$settings[$i]['colorIndex']=mt_rand(0,sizeof($this->fgcolors)-1);
	     	$settings[$i]['fontIndex']=mt_rand(0,sizeof($this->fonts)-1);
	     	$settings[$i]['angle']=mt_rand(0-$this->maxAngle,$this->maxAngle);

    		$settings[$i]['charDim']=imagettfbbox($this->fonts[$settings[$i]['fontIndex']]['size'],$settings[$i]['angle'],$this->fonts[$settings[$i]['fontIndex']]['font'],$this->captchaString[$i]);
    		$settings[$i]['charWidth']=($settings[$i]['charDim'][4]>$settings[$i]['charDim'][2]?$settings[$i]['charDim'][4]:$settings[$i]['charDim'][2])-($settings[$i]['charDim'][6]>$settings[$i]['charDim'][0]?$settings[$i]['charDim'][6]:$settings[$i]['charDim'][0]);
    		$settings['imageWidth']+=$settings[$i]['charWidth'];
    		$settings[$i]['charHeight']=($settings[$i]['charDim'][1]>$settings[$i]['charDim'][3]?$settings[$i]['charDim'][1]:$settings[$i]['charDim'][3])-($settings[$i]['charDim'][7]>$settings[$i]['charDim'][5]?$settings[$i]['charDim'][7]:$settings[$i]['charDim'][5]);
    		$settings['imageHeight']=$settings[$i]['charHeight']>$settings['imageHeight']?$settings[$i]['charHeight']:$settings['imageHeight'];

    		$settings[$i]['x_distort']=mt_rand(0,$settings[$i]['charWidth']*($this->x_factor-1)/2);
	     	$settings[$i]['y_distort']=mt_rand(0,($settings[$i]['charHeight'])*($this->y_factor-1)/2);
    	}

    	$settings['imageHeight']+=($this->border*2);
    	$settings['imageWidth']+=($this->border*2);

    	$imageHeight=$this->minImageHeight>($settings['imageHeight'])?$this->minImageHeight:($settings['imageHeight']);
    	$imageWidth=$this->minImageWidth>($settings['imageWidth'])?$this->minImageWidth:($settings['imageWidth']);

	     $image = imagecreate($imageWidth, $imageHeight);

	     $bgcolor=$this->bgcolor->colorAllocate($image);


	     /*for($i=0;$i < $this->lineIntensity;$i++) {
	     	$y_line=mt_rand(0,$imageHeight);
	     	imageline($image,0,$y_line,$imageWidth,$y_line,$this->fgcolors[0]);
	     }*/



	     $x_counter=0;

	     for($i=0;$i < $this->length;$i++) {
	     	//imagerectangle($image,$x_counter+$this->border+$settings[$i]['x_distort'],$settings[$i]['y_distort']+$this->border,$x_counter+$settings[$i]['x_distort']+$settings[$i]['charWidth']+$this->border,$settings[$i]['y_distort']+$settings[$i]['charHeight']+$this->border,$this->fgcolors[0]->colorAllocate($image));
	     	imagettftext($image, $this->fonts[$settings[$i]['fontIndex']]['size'],$settings[$i]['angle'],$x_counter+$settings[$i]['x_distort']+$this->border,$settings[$i]['y_distort']+$settings[$i]['charHeight']+$this->border,$this->fgcolors[$settings[$i]['colorIndex']]->colorAllocate($image),$this->fonts[$settings[$i]['fontIndex']]['font'],$this->captchaString[$i]);
	     	$x_counter+=$settings[$i]['charWidth'];
	     }

		for($i=0;$i<$this->pointIntensity;$i++) {
	     	$colorIndex=mt_rand(0,sizeof($this->fgcolors)-1);
	     	$y_point=mt_rand(0,$imageHeight);
	     	$x_point=mt_rand(0,$imageWidth);
	     	imageline($image,$x_point,$y_point,$x_point,$y_point,$this->fgcolors[$colorIndex]->colorAllocate($image));
	     }

	     //imagepstext($image,$this->captchaString,2,20,0,60);
	     //imagestring($image,2,0,60,$this->captchaString,$this->fgcolors[0]->colorAllocate($image));
	     //imagettftext($image,20,0,0,60,$this->fgcolors[0]->colorAllocate($image),$this->fonts[0]['font'],$settings[0]['x_distort']);
	     div::var_saveObject($this->captchaString,"captcha");
	     //$_SESSION['captcha']=$this->captchaString;
	     //imagettftext($image, $this->fontSize,$angle,0,60,$stringcolor,$this->font,session_is_registered('captcha')?'Ja':'Nein');

	     switch ($this->imageType) {
	        case 'jpeg': imagejpeg($image); break;
	        case 'png':  imagepng($image);  break;
	        default:     imagepng($image);  break;
	     }
    }
}


?>
