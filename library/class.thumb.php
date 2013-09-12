<?

class thumb {
	Const TYPE_JPEG=0;
	Const TYPE_PNG=1;
	Const TYPE_GIF=2;


	public $imagePath;
	public $saveDirectory;

	public $maxImageWidth;
	public $maxImageHeight;

	public $outputFormat;

	public $maximalSized;

	public $albumEffect;

	public $bgColor;

	function thumb($imagePath,$saveDirectory=null) {
		$this->setDefaults();
		$this->imagePath=$imagePath;
		$this->saveDirectory=$saveDirectory;
	}

	private function setDefaults() {
		$this->maxImageHeight=150;
		$this->maxImageWidth=100;
		$this->maximalSized=true;
		$this->bgColor= new color(255,255,255,0);
		$this->outputFormat=self::TYPE_JPEG;
	}

	/**
	 * Erstellt über die erhaltenen Informationen ein Thumbnail im Verzeichnis "$pathBilder" mit dem namen:thumb_<Dateiname>_<Breite>x<Höhe>.<Endung>. Gibt diesen Dateinamen zurück.
	 *
	 * @param 	string 		Bildquelle
	 * @param 	int 		Maximale Höhe des Bildes
	 * @param 	int 		Maximale Breite des Bildes
	 * @return 	string  	filename
	 */
	function getThumb() {
		//$this->imagePath=$this->getRelImgPath().div::file_getRawFilename($filename);
		global $db;
		//echo $db->view_array(get_object_vars($this));
		$generate=false;

		if($this->saveDirectory) {
			$thumb_filename = "thumb_".str_replace(div::file_getEndung($this->imagePath),'',div::file_getRawFilename($this->imagePath))."_".$this->maxImageWidth."x".$this->maxImageHeight.div::file_getEndung($this->imagePath);
			$thumb_path = $this->saveDirectory.$thumb_filename;
			//echo $thumb_path.'<br>';
			if(!file_exists($thumb_path)) {
				$generate=true;
				//echo "generate=true";
			}
		} else {
			$generate=true;
			//echo "generate=true";
		}


		//echo file_exists($this->imagePath)?'existiert':'existiert Nicht';
		//echo '<br>';

		if(file_exists($this->imagePath) AND $generate) {
			//echo "DAtei existiert.";
			$size = getimagesize($this->imagePath);

			if($size['1'] < $this->maxImageHeight && $size['0'] < $this->maxImageWidth) {
				$imageHeight = $size['1'];
			    $imageWidth = $size['0'];
			} else {
				if ($size['1']/$this->maxImageHeight > $size['0']/$this->maxImageWidth) {
					$imageHeight = $this->maxImageHeight;
					$imageWidth = Round($size['0']/($size['1']/$this->maxImageHeight),0);
				} else {
					$imageWidth = $this->maxImageWidth;
					$imageHeight = Round($size['1']/($size['0']/$this->maxImageWidth),0);
				}
			}


			//echo "WIDTH:".$imageWidth."<br>HEIGHT".$imageHeight."<br>";
			if($this->maximalSized) {
				$fullImageWidth=$this->maxImageWidth;
				$fullImageHeight=$this->maxImageHeight;
			} else {
				$fullImageWidth=$imageWidth;
				$fullImageHeight=$imageHeight;
			}




			$thumb = ImageCreateTrueColor($fullImageWidth,$fullImageHeight);

			imagefilledrectangle($thumb,0,0,$fullImageWidth,$fullImageHeight,$this->bgColor->colorAllocate($thumb));

			$thumb_x=($fullImageWidth-$imageWidth)/2;
			$thumb_y=($fullImageHeight-$imageHeight)/2;

			if($size[2] == 1) {
				$im = @ImageCreateFromGIF ($this->imagePath); /* Versuch, Datei zu öffnen */
				imagecopyresized($thumb,$im,$thumb_x,$thumb_y,0,0,$imageWidth,$imageHeight,$size[0],$size[1]);
			} elseif($size[2] == 3){
				$im = @ImageCreateFromPNG ($this->imagePath); /* Versuch, Datei zu öffnen */
				imagecopyresized($thumb,$im,$thumb_x,$thumb_y,0,0,$imageWidth,$imageHeight,$size[0],$size[1]);
			} else {
				$im = @ImageCreateFromJPEG ($this->imagePath); /* Versuch, Datei zu öffnen */
				imagecopyresized($thumb,$im,$thumb_x,$thumb_y,0,0,$imageWidth,$imageHeight,$size[0],$size[1]);
			}

			switch($this->outputFormat) {
				case self::TYPE_GIF:
					imagegif($thumb,$thumb_path);
					break;
				case self::TYPE_JPEG:
					imagejpeg($thumb,$thumb_path,80);
					break;
				case self::TYPE_PNG:
					imagepng($thumb,$im,$thumb_path);
					break;
			}
			imagedestroy($thumb);

		} else {                            /* Prüfen, ob fehlgeschlagen */
		       $im = ImageCreate ($this->maxImageWidth, $this->maxImageHeight);      /* Erzeugen eines leeren Bildes */
		       $tc = ImageColorAllocate ($im, 255, 255, 255);
		       //$bgc  = ImageColorAllocate ($im, 0, 0, 0);
		       ImageFilledRectangle ($im, 0, 0, $this->maxImageWidth, $this->maxImageHeight, $this->bgColor->colorAllocate($im));
		       /* Ausgabe einer Fehlermeldung */
		       ImageString($im, 1, 5, 5, "Bild nicht gefunden", $tc);
				//$thumb_path=$this->ownIconDir."notfound.jpg";
		       //imagejpeg($im,$thumb_path,90);
		   	   imagedestroy($im);
		}

		return $thumb_path;
	}




}

