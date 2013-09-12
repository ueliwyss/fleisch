<?php
$TABLES['image']=Array(
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
		'i_created'=>array(
			'label'=>'erstellt',
			'description'=>'Erstelldatum',
			'formconfig'=>array(
				'all'=>array(
					'specialVal'=>'now',
				),
			),
			'dbconfig'=>array(
				'type'=>'datetime',
				/*'default'=>'CURRENT_TIMESTAMP',*/
			),
		),
		'i_description'=>array(
			'label'=>'beschreibung',
			'description'=>'Beschreibung des Bildes',
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
		'i_title'=>array(
			'label'=>'Bildtitel',
			'description'=>'Titel des Bildes',
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
		'i_source'=>array(
			'label'=>'Speicherort',
			'description'=>'Pfad zum Bild',
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
			'listconfig'=>array(
				'all'=>array(
					'isImage'=>true,
					'imagePath'=>'bilder/',
				),
			),
		),
		'i_album_id'=>array(
			'label'=>'Album',
			'description'=>'Album, zudem das Bild gehört.',
			'foreign_table'=>'album',
			'foreign_key'=>'uid',
			'foreign_display'=>'a_name',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
					'foreign_display'=>'a_name',
				),
			),
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>11,
			),
		),
	),
	'lists'=>array(
		'listAll'=>array(
			'type'=>'grid',
			'fields'=>'i_title,i_source,i_description',
			'commonActions'=>'drop',
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
			'whereClause'=>'i_album_id=<uid>',
		),
	),
	'forms'=>array(
		'edit'=>array(
			'fields'=>'i_title,i_description,i_album_id',
		),
	),
);

$TABLES['album']=Array(
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
		'a_created'=>array(
			'label'=>'erstellt',
			'description'=>'Erstelldatum',
			'formconfig'=>array(
				'all'=>array(
					'specialVal'=>'now',
				),
			),
			'dbconfig'=>array(
				'type'=>'datetime',
				/*'default'=>'CURRENT_TIMESTAMP',*/
			),
		),
		'a_description'=>array(
			'label'=>'beschreibung',
			'description'=>'Beschreibung des Bildes',
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
		'a_name'=>array(
			'label'=>'Name',
			'description'=>'Name des Albums',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required,unique',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		'a_parent_id'=>array(
			'label'=>'Übergeordnetes Album',
			'description'=>'Übergeordnetes Album',
			'foreign_table'=>'album',
			'foreign_key'=>'uid',
			'foreign_display'=>'a_name',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
					'foreign_display'=>'a_name',
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
			'fields'=>'a_name,a_description,a_created,a_parent_id=0',
		),
		'edit'=>array(
			'fields'=>'a_name,a_description',
		),
	),
	'lists'=>array(
		'listAll'=>array(
			'type'=>'tab',
			'fields'=>'a_name,a_description,a_created',
			'commonActions'=>'drop',
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
	),
);



class gallery {
	//**************************//
	//	Darstellungs-Variabeln	//
	//**************************//

	//Anzahl der Angezeigten Bilder pro Zeile
	var $num_imgperRow;
	//Bei welcher Bild-Nr. soll oben links angefangen werden (Wird für die SQL-Abfrage benötigt)
	var $start;
	//Wieviele Objekte(Alben und Bilder) sollen auf einer Galleryseite angezeit werden.
	var $num_objects;
	//Soll der Bildtitel angezeigt werden?
	var $showTitle;
	//Soll die Beschreibung zum Bild angezeigt werden?
	var $showDescription;
	//Welches ist die Datei auf die das Formular zeigen soll?
	var $formTarget;
	//Thumb-Grösse
	var $maxThumbWidth;
	var $maxThumbHeight;

	//Bildgrösse
	var $maxImageWidth;
	var $maxImageHeight;

	var $objects;


	//**************************//
	//	Laufzeitvariabeln		//
	//**************************//
	//ID des Aktuellen Albums
	var $album_id;
	//ID des Bildes, das anzegeigt werden soll (Wird verwendet, wenn der Einzelanzeigemodus aktiv ist.
	var $bild_id;
	//Ist true, wenn man im Adminberiech angemeldet ist. Das heisst es können bilder hochgeladen werden u.s.w.
	var $admin;

	//Pfad zum BilderOrdner
	var $pathBilder;

	//Anzahl der bereits Ausgegebenen 'Gallerys'
	var $galleryCount;

	//Modus: Entweder 'Album', dann werden die thumbnails angezeigt. Oder 'img', dann werden einzelne bilder angezeigt.
	var $mode;

	//Icons
	var $icons;

	var $CSS;
	var $JS;

	var $tabItem;

	var $ownIconDir;


	function gallery($tabItem='') {
		$this->setDefaults();

		$this->tabItem=$tabItem?$tabItem:new TabItem('Pseudo');
	}

	function setDefaults() {
		$this->maxThumbHeight=150;
		$this->maxThumbWidth=135;
		$this->start=0;
		$this->album_id=0;
		$this->num_imgperRow=4;
		$this->num_objects=12;
		$this->mode="album";
		$this->maxImageHeight=800;
		$this->maxImageWidth=800;

		$this->ownIconDir='uploader/images/';

		$this->classCaller=div::file_getRawFilename($_SERVER['SCRIPT_FILENAME']);
		$this->pathBilder=IMAGE_DIR;
		$this->album_id = 0;
		$this->start = 0;

		$this->icons=Array(
		"nav_next"=>ICON_DIR."ilist_nav_next.gif",
		"nav_prev"=>ICON_DIR."ilist_nav_prev.gif",
		"nav_first"=>ICON_DIR."ilist_nav_first.gif",
		"nav_last"=>ICON_DIR."ilist_nav_last.gif"
	);

		$this->JS='
function setnSubmit(formname,values) {
	for (var i=0;i<values.length;i=i+2) {
	  var name = values[i];
	  var value = values[i+1];
	  document.getElementsByName(name)[0].value = value;
	}
	document[formname].submit();
}

function openPopup(url,title,width,height) {
	popup = window.open(url,"POPUP", "width="+width+",height="+height+",left="+(screen.width/2-width/2)+",top="+(screen.height/2-height/2)+",resizable=0");
	popup.focus();
}';

		$this->CSS='
a {
	color:black;
}

image {
	border:none;
}

table {
	font-size:11px;
}';
	}


	/**
	 * Gibt den aktuellen Einstellungen entsprechend, die Gallery aus (Albumdarstellung oder einzelnes Bild).
	 *
	 * @param [OPTIONAL] int Aus welchem Album sollen die Informationen ausgelesen werden?
	 */
	function showGallery($album_name='') {
		global $db;



		if(!is_numeric($album_name)) {
			$row=$db->exec_SELECTgetRows("uid","album","a_name='".$album_name."'");
			$this->album_id=$row[0]["uid"];
		} else {
			$this->album_id=$album_name;
		}
		$this->objects = $this->getObjects($this->album_id);
		//echo $db->view_array($this->objects);

		$this->album_id=$album_id!=''?$album_id:$this->album_id;

		$content .= "<form name='gallery".$this->galleryCount."' method='post' action='".$this->formTarget."'>\n";
		//Hidden-Inputs, zur übertragung der Werte.
		$content.="<input type='hidden' name='mode' value='".$this->mode."'>\n<input type='hidden' name='start' value='".$this->start."'>\n<input type='hidden' name='bild_id' value='".$this->bild_id."'>\n<input type='hidden' name='album_id' value='".$this->album_id."'>\n";
		$content.="<table>\n<tr>\n<td>\n";
		$content.=$this->wrapHeader();

		$content.="</td>\n</tr>\n";

		$content.="<tr>\n<td>\n";
		$content.="<table style='' cellpadding=0 cellspacing=5 align='center' class='gallery_thumbs'>\n";
/*table-layout:fixed*/
		if($this->mode=="album" || $this->mode=='') {
			$content.=$this->wrapThumbs();
		} elseif($this->mode=="img") {
			$content.=$this->wrapImage($this->bild_id);
		}


		$content.="</table>\n";
		$content.="</td>\n</tr>\n";

		$content.="<tr>\n<td>\n";
		//if($this->mode=="album") {
			$content.=$this->wrapFooter();
		//}
		$content.="</td>\n</tr>\n</table>
</td>
</tr>
</table>";

		$this->content=array(
			'main'=>$content,
			'JS'=>$this->JS,
			'CSS'=>$this->CSS,
		);

		$this->galleryCount++;
		return $this->content;
	}


	function wrapThumbs() {
		global $db;

		$content='';

		$num_rows = Ceil($this->num_objects/$this->num_imgperRow);

			for($i=0;$i<$num_rows;$i++) {
			$content.="<tr>\n";
			for ($j=0;$j<$this->num_imgperRow;$j++) {
				$index = $i*$this->num_imgperRow+$j;
				$object=$this->objects[$index+$this->start];
				$content.="<td width='".($this->maxThumbWidth+20)."' height='".($this->maxThumbHeight+20)."' class='gallery_thumb'>\n";
				if(is_array($object)) {

					$content.="<table align='center'>\n";

					if($object["type"]=="bild") {
						$link_image_add=array(
							'mode'=>'img',
							'bild_id'=>$object["uid"],
							'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex(),
						);
						$link_image=div::http_getURL($link_image_add);
						$link="<a href='".$link_image."'>";

					} else {
						$link_album_add=array(
							'mode'=>'album',
							'album_id'=>$object["album"]["uid"],
							'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex(),
						);
						$link_album=div::http_getURL($link_album_add);
						$link="<a href='".$link_album."'>";
					}
					$content.="<tr><td>".$object["title"]."</td></tr>";
					$content.="<tr><td align='center'>".$link.$this->wrapThumb($object["thumb"])."</a></td></tr>";

					$content.="</table>\n";
				}
				$content.="</td>\n";
			}
			$content.="</tr>\n";
		}
		return $content;
	}

	function wrapHeader() {
		global $db;

		$album=$this->getAlbum($this->album_id);

		$album=$this->getParentAlbums($album["uid"]);




		$content="<table>";

		$link_root_add=array(
			'mode'=>'album',
			'album_id'=>$album["root"]["uid"],
		);
		$link_root=div::http_getURL($link_root_add);
		$content.="<tr><td>";
		//$content.="<a href='".$link_root."'>".$album["root"]["a_name"]."</a>&nbsp;";

		foreach($album["parents"] as $parent) {
			$link_parent_add=array(
				'mode'=>'album',
				'album_id'=>$parent["uid"],
				'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex(),
			);
			$link_parent=div::http_getURL($link_parent_add);

			$content.="/&nbsp;<a href='".$link_parent."'>".$parent["a_name"]."</a>&nbsp;";
		}
		$link_album_add=array(
			'mode'=>'album',
			'album_id'=>$album["uid"],
			'start'=>0,
			'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex(),
		);
		$link_album=div::http_getURL($link_album_add);

		//$content.="/&nbsp;<a href='".$link_album."'>".$album["a_name"]."</a>&nbsp;";
		if(!$this->start==0) {
			$link_start_add=array(
				'mode'=>'album',
				'album_id'=>$this->album_id,
				'start'=>$this->start,
				'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex(),
			);
			$link_start=div::http_getURL($link_start_add);

			$content.="/&nbsp;<a href='".$link_start."'>Page:".$this->getActualPage()."</a>&nbsp;";
		}

		if($this->mode=="img") {
				$img=$this->getImage($this->bild_id);
				$content.="/&nbsp;".div::file_getRawFilename($img["i_source"])."&nbsp;";
		}
		return $content;

	}
	function getActualPage() {
		return ($this->start/$this->num_objects+1);
	}

	function getAlbum($album_id) {
		global $db;

		$album = $db->exec_SELECTgetRows("*","album","uid=".$album_id);
		return $album[0];
	}

	private function getParentAlbums_raw($parent_id) {
		static $count;
		$albums = $GLOBALS['db']->exec_SELECTgetRows("*","album","uid=".$parent_id);

		if(!$albums[0]["a_parent_id"]==0) { $albums=array_merge($albums,$this->getParentAlbums_raw($albums[0]["a_parent_id"]));}


		if(count($albums)==0) {
			return Array();
		} else {
			return $albums;
		}
	}

	function getParentAlbums($parent_id) {


		$parent_id=isset($parent_id)?$parent_id:0;
		$album=$GLOBALS['db']->exec_SELECTgetRows("*","album","a_parent_id=".$parent_id);
		if(!$parent_id==0) {$parents=$this->getParentAlbums_raw($parent_id);krsort($parents);$album[0]["parents"]=$parents;}else {$album[0]["parents"]=Array();}

		$root=Array("uid"=>0,"a_name"=>"Gallery","a_description"=>"Unterste Ebene, die alle weiteren Enthält");
		$album[0]["root"]=$root;


		return $album[0];


	}


	function getObjects($album_id) {
		$num_albumsInPage = $this->getNumAlbumsInPage($album_id);
		$num_imgsInPage = $this->num_objects-$num_albumsInPage;
		//echo "ALBUM_ID:".$album_id.'<br>';
		$bilder=Array();

		$rowsAlbum = $GLOBALS['db']->exec_SELECTgetRows("*","album","a_parent_id=".$album_id,"","a_created"/*,$this->start.",".($this->start+$num_albumsInPage)*/);

			foreach($rowsAlbum as $rowAlbum) {
				$bild = $this->getAlbumImage($rowAlbum["uid"]);
				$bild["type"]="album";
				$bild["album"]=$rowAlbum;
				$bilder[]=$bild;
			}

		$rowsBilder=$GLOBALS['db']->exec_SELECTgetRows("*","image","i_album_id=".$album_id,"","i_created"/*,$this->start.",".$num_imgsInPage*/);


			foreach($rowsBilder as $rowBild) {
				$bild = Array("uid"=>$rowBild["uid"],"type"=>"bild","source"=>$rowBild["i_source"],"title"=>$rowBild["i_title"],"description"=>$rowBild["i_description"],"thumb"=>$this->getThumb(IMAGE_DIR.$rowBild["i_source"],$this->maxThumbHeight,$this->maxThumbWidth));

				$bilder[]=$bild;
			}

		return $bilder;
	}

	function wrapThumb($thumb) {
		$img = "<img src='".$thumb."'>";
		return $img;
	}

	/**
	 * Erstellt über die erhaltenen Informationen ein Thumbnail im Verzeichnis "$pathBilder" mit dem namen:thumb_<Dateiname>_<Breite>x<Höhe>.<Endung>. Gibt diesen Dateinamen zurück.
	 *
	 * @param 	string 		Bildquelle
	 * @param 	int 		Maximale Höhe des Bildes
	 * @param 	int 		Maximale Breite des Bildes
	 * @return 	string  	filename
	 */
	function getThumb($filename,$maxThumbHeight,$maxThumbWidth) {
		$filePath=$this->getRelImgPath().div::file_getRawFilename($filename);

		$thumb_filename = "thumb_".div::file_getRawFilename($filename)."_".$maxThumbWidth."x".$maxThumbHeight.div::file_getEndung($filename);
		$thumb_path = TEMP_DIR.$thumb_filename;

		if(!file_exists($thumb_path)) {
			if(file_exists($filePath)) {
				$size = getimagesize($filePath);
			if($size['1'] < $maxThumbHeight && $size['0'] < $maxThumbWidth) {
				$imageHeight = $size['1'];
			    $imageWidth = $size['0'];
			} else {
				if ($size['1']/$maxThumbHeight > $size['0']/$maxThumbWidth) {
					$imageHeight = $maxThumbHeight;
					$imageWidth = Round($size['0']/($size['1']/$maxThumbHeight),0);
				} else {
					$imageWidth = $maxThumbWidth;
					$imageHeight = Round($size['1']/($size['0']/$maxThumbWidth),0);
				}
			}


			$thumb = ImageCreateTrueColor($imageWidth,$imageHeight);
			if($size[2] == 1) {

				$im = @ImageCreateFromGIF ($filePath); /* Versuch, Datei zu öffnen */
				imagecopyresized($thumb,$im,0,0,0,0,$imageWidth,$imageHeight,$size[0],$size[1]);
				imagegif($thumb,$thumb_path);

			} elseif($size[2] == 3){
				$im = @ImageCreateFromPNG ($filePath); /* Versuch, Datei zu öffnen */
				imagecopyresized($thumb,$im,0,0,0,0,$imageWidth,$imageHeight,$size[0],$size[1]);
				imagepng($thumb,$im,$thumb_path);

			} else {
				$im = @ImageCreateFromJPEG ($filePath); /* Versuch, Datei zu öffnen */
				imagecopyresized($thumb,$im,0,0,0,0,$imageWidth,$imageHeight,$size[0],$size[1]);
				imagejpeg($thumb,$thumb_path,80);

			}
				imagedestroy($thumb);

			} else {                            /* Prüfen, ob fehlgeschlagen */
			       $im = ImageCreate ($maxThumbWidth, $maxThumbHeight);      /* Erzeugen eines leeren Bildes */
			       $tc = ImageColorAllocate ($im, 255, 255, 255);
			       $bgc  = ImageColorAllocate ($im, 0, 0, 0);
			       ImageFilledRectangle ($im, 0, 0, $maxThumbWidth, $maxThumbHeight, $bgc);
			       /* Ausgabe einer Fehlermeldung */
			       ImageString($im, 1, 5, 5, "Bild nicht gefunden", $tc);
			       //echo "File:".$filename.">>".$thumb_path.'<br>';
					$thumb_path=$this->ownIconDir."notfound.jpg";

			       //imagejpeg($im,$thumb_path,90);
			   	   imagedestroy($im);
			}
		}
		return $thumb_path;
	}

	/**
	 * Ermittelt die Anzahl Bilder des angegebenen Albums.
	 *
	 * @return int 	Anzahl Bilder
	 */
	function getNumImgInAlbum($album_id) {
		$row = $GLOBALS['db']->exec_SELECTgetRows("COUNT(*)","image","i_album_id=".$album_id);
		return $row[0]["COUNT(*)"];
	}

	/**
	 * Ermittelt die Anzahl Alben des angegebenen Albums.
	 *
	 * @return int 	Anzahl alben
	 */
	function getNumAlbumInAlbum($album_id) {
		$row = $GLOBALS['db']->exec_SELECTgetRows("COUNT(*)","album","a_parent_id=".$album_id);
		return $row[0]["COUNT(*)"];
	}


	/**
	 * Ermittelt die Anzahl Objekte (Bilder und Alben) im angegebenen Album.
	 *
	 * @return Anzahl Objekte (Bilder und Alben)
	 */
	function getNumObjectsInAlbum($album_id) {
		$numObjects = (($this->getNumAlbumInAlbum($album_id))+($this->getNumImgInAlbum($album_id)));
		return $numObjects;
	}

	/**
	 * Ermittelt die Anzahl Alben auf der Aktuell angezeigten Seite.
	 *
	 * @return int	Anzahl Alben auf der Aktuell angezeigten Seite
	 */
	function getNumAlbumsInPage() {
		$row = $GLOBALS['db']->exec_SELECTgetRows("COUNT(*)","album","a_parent_id=".$this->album_id,'','',$this->start.",".($this->num_objects));
		return $row[0]["COUNT(*)"];
	}

	/**
	 * Liefert ein Array, das Informationen über das angegebene (einzelne) Bild enthält.
	 *
	 * @param 	int 	bild_id
	 * @return 	array	Informationen zu einem Bild
	 */
	function getImage($bild_id) {
		$row = $GLOBALS['db']->exec_SELECTgetRows("*","image","uid=".$bild_id);
		$image=$row[0];
		$image["type"]="image";
		return $image;
	}


	function wrapImage($bild_id) {
		$image=$this->getImage($bild_id);
		$wrappedImage="<a href='".IMAGE_DIR.$image["i_source"]."' target='_blank'><img src='".$this->getThumb(IMAGE_DIR.$image["i_source"],$this->maxImageHeight,$this->maxImageWidth)."'></a>";
		return $wrappedImage;
	}

	/**
	 * Sucht in der Tabelle 'bild' nach dem ersten Bild, das dem angegebenen Album angehört.
	 *
	 * @param 	int		album_id
	 * @return 	array	Bilddaten aus der Tabelle 'bild'.
	 */
	function getAlbumImage($album_id) {
		$stop = false;
		$currentAlbum = $album_id;

		while(!$stop) {


			$result_bild = $GLOBALS['db']->exec_SELECTquery("*","image","i_album_id=".$album_id,"","i_created ASC","0,1");

			$num_rowsBild = $GLOBALS['db']->sql_num_rows($result_bild);


			if($num_rowsBild>0) {
				$row_bild = $GLOBALS['db']->sql_fetch_assoc($result_bild);

				$image = Array("type"=>"bild","source"=>$row_bild["source"],"title"=>$row_bild["title"],"description"=>$row_bild["description"],"thumb"=>$this->getThumb($row_bild["i_source"],$this->maxThumbHeight,$this->maxThumbWidth));
				$stop=true;
			} else {
				$result_album = $GLOBALS['db']->exec_SELECTquery("uid","album","a_parent_id=".$currentAlbum);
				if($GLOBALS['db']->sql_num_rows($result_album)>0) {
					$row_album = $GLOBALS['db']->sql_fetch_assoc($result_album);
					$currentAlbum = $row_album["uid"];
				} else {
					$stop=true;
				}
			}
		}
		return  $image;
	}

	function uploadAll($album_id) {
		foreach($_FILES as $file) {
			$this->uploadFile($file,$album_id);
		}
	}

	function uploadFile($file,$album_id) {


	    $max_byte_size = 1048576;
	    $allowed_types = "(jpg|jpeg|gif|bmp|png)";

	    //+ und - aus dem Namen nehmen.
	    $file['name'] = preg_replace("(\+|\-)","",$file['name']);

	    $temp_name = $file['tmp_name'];
	    $file_name = $file['name'];
	    $file_type = $file['type'];
	    $file_size = $file['size'];
	    $result    = $file['error'];


	    //File Name Check
	    if ($file_name=="") {
	        raiseMessage(3,"Datenupload: Ungültiger Dateiname!");
	        return false;
	    }
	    //File Size Check
	    else if ( $file_size > $max_byte_size) {
	        raiseMessage(3,"Datenupload: Die Datei '".$file_name."' ist grösser als ".($max_byte_size/1024/1024)."MB!");
	        return 3;
	    } elseif(preg_match($allowed_types,$_FILES["file"]["name"])) {
	    	raiseMessage(3,"Datenupload: Falscher Dateityp! Folgende Dateitypen sind erlaubt: ".$allowed_types);
	         return 8;
	    } elseif(file_exists(IMAGE_DIR.$file_name)) {
	    	$i = 1;
	         $gut = false;
	         $endung = strrchr($file_name,".");
	         $name = str_replace($endung,"",$file_name);
	         while($gut==false) {
	             if(!file_exists($this->pathBilder.$name."[".$i."]".$endung)) {
	             	$file_name= $name."[".$i."]".$endung;
	                 $file = $name."[".$i."]".$endung;
	             	$gut = true;
	             }
	             $i++;
	         }

	    }

		$file_path = IMAGE_DIR.$file_name;
		div::var_saveObject(array(
			'FILE_PATH'=>$file_path,
			'TEMP_PATH'=>$temp_name,
		),"gallery2","../temp/");

	    if($result =  move_uploaded_file($temp_name, $file_path)) {
	    	if($this->addImage($file_name,$album_id)) {
	    		raiseMessage(1,"Die Datei wurde erfolgreich hochgeladen und unter '".$this->pathBilder.$file_name."' gespeichert.");
	    	}
	    } else {
	    	raiseMessage(3,"Zugriff verweigert!");
	    	return false;
	    }
	}

	/**
	 * Trägt ein Bild in die Tabelle 'bild' ein.
	 *
	 * @param 	string		Dateiname des Bildes
	 * @param 	int			Album_id
	 * @return 	pointer		MySQL result pointer / DBAL object
	 */
	function addImage($source,$album_id) {
		global $db;

		return $db->exec_INSERTquery("image",Array("i_created"=>div::date_gerToSQL(div::Date_getDateTime()),"i_source"=>$source,"i_album_id"=>$album_id));
	}

	function getUploaderButton() {
		$action=table::decodeAction();
		$content = '<div class="button"><div style="width:170px" id="btn" class="btn"><div class="L"></div>
		<a class="label" href=\'javascript:top.openPopup("uploader/uploader.php?popup=true&uid='.$action['uid'].'","Bilder hinzufügen",800,600)\'><nobr><img src="'.$this->ownIconDir.'attachfile.gif">Bilder hinzufügen...</nobr></a>
		<div class="R"></div></div></div>';
		return array(
			'main'=>$content,
		);
	}
	/**
	 * Liefert ein Array, das alle Daten aus der Tabelle 'album' enthält. Sie werden hirarchisch gegliedert. Das heisst, alle Untergeordneten Alben("childs") sind im jeweiligen Array unter ["childs"] zu finden.
	 *
	 * @return array	Albuminformationen.
	 */
	/*function getAlbums() {
		$albums = Array();//"name"=>"Root-Verzeichnis","description"=>"Unterste Ebene, die alle weiteren Enthält");
		$albums = $this->getChildAlbums('0');
		$albums["childs"] = $this->getLayer($albums);

		return $albums;
	}*/

	/**
	 * Gibt ein Array, das alle Informationen über die Alben, die DEM angegebenen Album untergeordnet sind.
	 *
	 * @param  array	Array mit Informationen über ein Album
	 * @return array	Das Selbe Array nur mit den untergeordneten Alben angefügt.
	 */
	function getLayer($albums) {
		for($i=0;$i<count($albums);$i++) {
			$childs = $this->getLayer($this->getChildAlbums($albums[$i]["uid"]));
			if(count($childs)!=0) {
				$albums[$i]["childs"] = $childs;
			}
		}
		return $albums;
	}

	function wrapAlbumOptionList($albumTree=null) {
		global $db;

		if(!$albumTree){
			$albumTree = $this->getAlbumHierarchy();
			$tab=0;
		}

		static $tab=0;

		$uid=div::http_getGP('uid');

		//if($uid) { $readOnly=' disabled'; }
		if($tab==0){
			$content.='<select onChange="javascript:albumChange(this);"  id="album"'.$readOnly.'>';
		}



		foreach($albumTree as $album) {
			if($uid==$album['uid']) {
				$checked=' selected="selected"';
			} else {
				$checked='';
			}
			$content.='<option value="'.$album["uid"].'"'.$checked.'>'.str_repeat("&nbsp;",(3*($tab))).$album["a_name"].'</option>';
			if(is_array($albumTree["childs"])) {
				$content.=$this->wrapAlbumOptionList($album);
			}
		}

		$tab++;

		if($tab==0){$content.="</select>";}



		return $content;
	}

	/**
	 * Ermittelt die anzahl Seiten nach den aktuellen einstellungen des aktuellen Albums.
	 *
	 * @return int Anzahl Seiten
	 */
	function getNumPages() {
		$numPages=@Ceil($this->getNumObjectsinAlbum($this->album_id)/$this->num_objects);
		return $numPages;
	}

	function wrapFooter() {

		$content="<table width='100%'>\n<tr>\n";
		$numObjects = $this->getNumObjectsinAlbum($this->album_id);
		$numPages = $this->getNumPages();

		if($image_prev=$this->getImageId('prev')) {
			$link_navPrev_add=array(
				'mode'=>$this->mode,
				'start'=>$this->getStart("nav_prev"),
				'bild_id'=>$image_prev['uid'],
				'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex(),
			);
			$link_navPrev=div::http_getURL($link_navPrev_add);
		}


		if($first_image=$this->getImageId('first')) {
			$link_navFirst_add=array(
				'mode'=>$this->mode,
				'start'=>$this->getStart("nav_first"),
				'bild_id'=>$first_image['uid'],
				'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex(),
			);
			$link_navFirst=div::http_getURL($link_navFirst_add);
		}



			$content.="<td  class='gallery_navigation'>".($link_navFirst?"<a href='".$link_navFirst."'>":"")."<img src='".$this->icons["nav_first"]."'>".($link_navFirst?"</a>":"")."</td>\n";
			$content.="<td  class='gallery_navigation'>".($link_navPrev?"<a href='".$link_navPrev."'>":"")."<img src='".$this->icons["nav_prev"]."'>".($link_navPrev?"</a>":"")."</td>\n";


		$content.="<td align='center' class='gallery_navigation'>\n<table>\n<tr>\n";
		$content.="";

		if($this->mode=='album') {
			for($i=0;$i<$numPages;$i++) {
				$from=$i*$this->num_objects;
				if(($i+1)==$numPages) {$to=$numObjects;	} else {$to=($from+$this->num_objects);}

				if($this->start!=$from) {
					$link_navPage_add=array(
						'mode'=>'album',
						'start'=>$from,
						'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex(),
					);
					$link_navPage=div::http_getURL($link_navPage_add);
					$content .= "<td align='center' class='gallery_navigation'><a href='".$link_navPage."'><b>[".($i+1)."]</b></a></td>\n";
				} else {
					$content .= "<td align='center' class='gallery_navigation'>[".($i+1)."]</td>\n";

				}
			}
		}

		if($next_image=$this->getImageId('next')) {
			$link_navNext_add=array(
				'mode'=>$this->mode,
				'start'=>$this->getStart("nav_next"),
				'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex(),
				'bild_id'=>$next_image['uid'],
			);
			$link_navNext=div::http_getURL($link_navNext_add);
		}


		if($last_image=$this->getImageId('last')) {
			$link_navLast_add=array(
				'mode'=>$this->mode,
				'start'=>$this->getStart("nav_last"),
				'activeTab_'.$this->tabItem->getName()=>$this->tabItem->getIndex(),
				'bild_id'=>$last_image['uid'],
			);
			$link_navLast=div::http_getURL($link_navLast_add);
		}


		$content.="</tr>\n</table>\n</td>\n";

		$content.="<td>".($link_navNext?"<a href='".$link_navNext."'>":"")."<img src='".$this->icons["nav_next"]."'>".($link_navNext?"</a>":"")."</td>\n";
		$content.="<td>".($link_navLast?"<a href='".$link_navLast."'>":"")."<img src='".$this->icons["nav_last"]."'>".($link_navLast?"</a>":"")."</td>\n";

		$content .= "</tr>\n</table>\n";
		return $content;
	}

	/**
	 * Liefert ein Array, das die Daten der Alben einthält, die dem angegebenen Album DIREKT untergeordet sind.
	 *
	 * @param 	int		album_id
	 * @return 	array	Alle Spalten verschiedener Alben ('album')
	 */


	function getAlbumHierarchy($parent_id=0) {
		global $db;

		$albums=array();

		$rows = $db->exec_SELECTgetRows("*","album","a_parent_id=".$parent_id);

		for($i=0;$i<count($rows);$i++) {
			array_push($albums,$rows[$i]);
			$childs=$this->getAlbumHierarchy($rows[$i]['uid']);
			if(is_array($childs)) {
				$albums[$i]["childs"]=$childs;
			}
		}

		if(is_array($rows)) {
			return $albums;
		} else {
			return false;
		}
	}

	function getNextImage($bild_id) {



	}

	function getRelImgPath() {
		return IMAGE_DIR;
	}

	function getStart($direction) {
		$numObjects=$this->getNumObjectsinAlbum($this->album_id);

		switch ($direction) {
			case "nav_next":
			    if(($this->start+$this->num_objects)<$numObjects) {$start=$this->start+$this->num_objects;} else {$start=$this->start;}
			    break;
			case "nav_prev":
			    if(($this->start-$this->num_objects)>=0) {$start=$this->start-$this->num_objects;} else {$start=$this->start;}
			    break;
			case "nav_first":
			    $start=0;
			    break;
			case "nav_last":
			    $start=($this->getNumPages()-1)*$this->num_objects;
			    break;
		}
		return $start;
	}

	/**
	 * Trägt ein Album in die Datenbank 'album' ein.
	 *
	 * @param 	string 		Name des Albums
	 * @param 	string 		Beschreibung, die zum Namen angezeigt wird.
	 * @param 	int	 		'album_id' des Übergeordneten Albums.
	 * @return	pointer		MySQL result pointer / DBAL object
	 */
	function addAlbum($name,$description='',$parent_id=0) {
		$data = array(
		"name"=>$name,
		"description"=>$description,
		"date"=>Date_ger2sql(getDateTime()),
		"parent_id"=>$parent_id
		);
		return $GLOBALS['db']->exec_INSERTquery('album',$data);
	}

	function wrapAlbumList($albumTree='',$tab=0)	{
			if($albumTree==''){ $albumTree = $this->getAlbums(); }

			static $tab;
			if($tab==0){
				$content='<table cellpadding="10"><tr style="border:1px solid black"><td><b>Album</b></td><td><b>Beschreibung</b></td><td><b>Alben/Bilder</b></td><td><b>Aktionen</b></td></tr>';
				$content.='<tr><td style="border:1px solid black">'.$albumTree["name"].'</td></tr>';

			}

			$tab++;
			if(is_array($albumTree["childs"])) {
				foreach ($albumTree["childs"] as $album) {
					$content.='<tr><td style="border:1px solid black">'.str_repeat("&nbsp;",(4*($tab))).'<img src="'.$this->ownIconDir.'images/arrow_child.png">&nbsp;'.$album["name"].'</td>';
					$content.='<td style="border:1px solid black">'.$album["description"].'</td>';
					$content.='<td style="border:1px solid black">'.$this->getNumAlbumInAlbum($album["album_id"]).'/'.$this->getNumImgInAlbum($album["album_id"]);
					$content.='<td style="border:1px solid black">
					<a href=""><img src="'.ICON_DIR.'b_edit.png" alt="Bearbeiten..."></a>
					<a href=""><img src="'.ICON_DIR.'b_delete.png" alt="Löschen"></a>
					</td>';
					$content.='</tr>';
					if(is_array($album["childs"])) {
						$content.=$this->wrapAlbumList($album,$tab);
						$tab--;
					}
				}
			}
			if($tab==0){$content.="</table>";}
			return $content;
		}



	/**
	 * Setzt die Anzeigeoptionen, die über das Navigations-Formular(beim Bild-anklicken, next_page usw).
	 *
	 * @param array Das ergebnisarray des Formulars
	 */
	function setOptions($POST) {
		global $db;

		$this->album_id=isset($POST["album_id"])?$POST["album_id"]:'0';
		$this->start=isset($POST["start"])?$POST["start"]:0;
		$this->mode=isset($POST["mode"])?$POST["mode"]:"album";
		$this->bild_id=isset($POST["bild_id"])?$POST["bild_id"]:0;
	}

	/**
	 * @return void
	 * @param unknown $msg
	 * @desc Gibt eine Meldung aus.
	*/
	function message($msg) {
		echo $msg;
	}

	/**
	 * @return void
	 * @desc Gibt den Inhalt dieser Seite aus.
	*/
	function echoContent() {
		echo $this->content;
		$this->content='';
	}

	function delete_image($image_id) {
		global $db;

		$db->exec_DELETEquery("image","uid=".$image_id);
	}

	function delete_album($album_id) {
		global $db;

		$values=array(
			'i_album_id'=>'NULL',
		);
		$db->exec_UPDATEquery("image","i_album_id=".$album_id,$values);

		$db->exec_DELETEquery("album","uid=".$album_id);
	}

	function getImageId($which) {
		$stop=false;
		reset($this->objects);

		//echo "BILD_ID".$this->bild_id.'<br>';

		if($which=='prev' OR $which=='next') {
			while(!$stop) {
				list($key, $image) = each($this->objects);
				switch($which) {
					case 'next':
						if($image['uid']==$this->bild_id) {
							//echo "Nextgefunden -- ".$key.$which.'<br>';
							$image=current($this->objects);
							$stop=true;
						}
					break;
					case 'prev':
						if($image['uid']==$this->bild_id) {
							if(!prev($this->objects)) {
								end($this->objects);
							}
							$image=prev($this->objects);

							//echo "Prevgefunden -- ".$key.$which.'<br>';
							$stop=true;
						}
					break;
				}
			}
		} elseif($which=='last') {

			$image=end($this->objects);
			if($image['type']=='bild') {
				$stop=true;
			} else {
				while(!$stop) {
					if($image=prev($this->objects)) {
						if($image['type']=='bild') {
							$stop=true;
						}
					} else {
						$stop=true;
					}
				}
			}
		} elseif($which=='first') {
			$image=reset($this->objects);
			if($image['type']=='bild' && $which=='first') {
				//echo "Stopamanfang -- ".$which.'<br>';
				$stop=true;
			} else {
				while(!$stop) {
					if($image=next($this->objects)) {
						if($image['type']=='bild') {
							$stop=true;
						}
					} else {
						$stop=true;
					}
				}
			}
		}


		//echo $which.": ".$GLOBALS['db']->view_array($image);
		return $image;
	}
}
?>