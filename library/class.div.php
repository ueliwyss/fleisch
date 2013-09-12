<?php

/**
 * Diverse Funktionen, die von verschiedenen Klassen verwendet werden und nicht eindeutig zugeordnet werden können.
 * Sie sind in folgende Kategorien eingeteilt:
 * - Datums/Zeit Funktionen  ('date_'-Prefix)
 * - HTML Funktionen ('htm_'-Prefix)
 * - HTTP Funktionen ('var_'-Prefix)
 * - Variablenumwandlungs und -formatierungs Funktionen ('var_'-Prefix)
 * - Datei und Dateisystem Funktionen ('file_'-Prefix)
 *
 * @author Ueli Wyss
 * @package IPA-Vorbereitung
 */
class div {

	/************************************
	 *
	 * Datums- und Zeitfunktionen
	 *
	 * Berechnen und formatieren Datums- und Zeitwerte.
	 * Den folgenden Funktionen ist jeweils die Zeichenfolge 'date_' vorangestellt.
	 *
	 **************************************/

	/**
	 * Liefert das aktuelle Datum mit Zeit im Deutschen Format (d.m.Y H:i:s).
	 *
	 * @return datetime
	 */
	static function date_getDateTime() {
	    return date("d.m.Y H:i:s");
	}

	/**
	 * Liefert das aktuelle Datum im Deutschen Format (d.m.Y).
	 *
	 * @return date
	 */
	static function date_getDate() {
		return date("d.m.Y");
	}

	/**
	 * Liefert die aktuelle Zeit im Deutschen Format (H:i:s).
	 *
	 * @return time
	 */
	static function date_getTime() {
		return date("H:i:s");
	}

	/**
	 * Wandelt ein SQL-Formatiertes Datum () ins Deutsche Datumsformat um (d.m.Y H:i:s), damit das Datum, das aus einer Datenbank stammt (für einen Schweizer lesbar) angezeigt werden kann.
	 * Dies ist die Umkehrfunktion von div::date_gerToSQL().
	 *
	 * @author Christian Felken <http://www.webmaster-resource.de>
	 * @subpackage Fremd
	 *
	 * @param datetime $Datum
	 * @return datetime
	 */
	static function date_SQLToGer($Datum) {
	      if(strlen($Datum)==10) {
	         $GewandeltesDatum = substr($Datum, 8, 2);
	         $GewandeltesDatum .= ".";
	         $GewandeltesDatum .= substr($Datum, 5, 2);
	         $GewandeltesDatum .= ".";
	         $GewandeltesDatum .= substr($Datum, 0, 4);
	         return $GewandeltesDatum;
	     } elseif(strlen($Datum)==19)   {
	         $GewandeltesDatum = substr($Datum, 8, 2);
	         $GewandeltesDatum .= ".";
	         $GewandeltesDatum .= substr($Datum, 5, 2);
	         $GewandeltesDatum .= ".";
	         $GewandeltesDatum .= substr($Datum, 0, 4);
	         $GewandeltesDatum .= substr($Datum, 10);
	         return $GewandeltesDatum;
	     }
	    else
	     {
	         return FALSE;
	    }
	 }

	 /**
	 * Wandelt ein Deutsches Datum () ins SQL-Format um (d.m.Y H:i:s), damit die Variabel in die Datenbank eingetragen werden kann.
	 * Dies ist die Umkehrfunktion von div::date_SQLToGer().
	 *
	 * @author Christian Felken <http://www.webmaster-resource.de>
	 * @subpackage Fremd
	 *
	 * @param datetime $Datum
	 * @return datetime
	 */
	 static function date_gerToSQL($Datum) {
	      if(strlen($Datum)==10) {
	         $GewandeltesDatum = substr($Datum, 6, 4);
	         $GewandeltesDatum .= "-";
	         $GewandeltesDatum .= substr($Datum, 3, 2);
	         $GewandeltesDatum .= "-";
	         $GewandeltesDatum .= substr($Datum, 0, 2);
	         return $GewandeltesDatum;
	     } elseif(strlen($Datum)==19) {
	         $GewandeltesDatum = substr($Datum, 6, 4);
	         $GewandeltesDatum .= "-";
	         $GewandeltesDatum .= substr($Datum, 3, 2);
	         $GewandeltesDatum .= "-";
	         $GewandeltesDatum .= substr($Datum, 0, 2);
	         $GewandeltesDatum .= substr($Datum, 10);
	         return $GewandeltesDatum;
	     } else {
	         return FALSE;
	    }
	 }


	/**
	 * Errechnet den Zeitunterschied zwischen zweier Daten und gibt den als UNIX-Timestamp zurück.
	 *
	 * @author Dave Child <http://www.ilovejackdaniels.com>
	 * @subpackage Fremd
	 *
	 * @param string $interval
	 * @param datetime $datefrom
	 * @param datetime $dateto
	 * @param boolean $using_timestamps
	 * @return timestamp(int)
	 */
	static function date_diff($interval, $datefrom, $dateto, $using_timestamps = false) {
	  /*
	    $interval can be:
	    yyyy - Number of full years
	    q - Number of full quarters
	    m - Number of full months
	    y - Difference between day numbers
	      (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
	    d - Number of full days
	    w - Number of full weekdays
	    ww - Number of full weeks
	    h - Number of full hours
	    n - Number of full minutes
	    s - Number of full seconds (default)
	  */

	  if (!$using_timestamps) {
	    $datefrom = strtotime($datefrom, 0);
	    $dateto = strtotime($dateto, 0);
	  }
	  $difference = $dateto - $datefrom; // Difference in seconds
	  switch($interval) {
	    case 'yyyy': // Number of full years
	      $years_difference = floor($difference / 31536000);
	      if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
	        $years_difference--;
	      }
	      if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
	        $years_difference++;
	      }
	      $datediff = $years_difference;
	      break;
	    case "q": // Number of full quarters
	      $quarters_difference = floor($difference / 8035200);
	      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
	        $months_difference++;
	      }
	      $quarters_difference--;
	      $datediff = $quarters_difference;
	      break;
	    case "m": // Number of full months
	      $months_difference = floor($difference / 2678400);
	      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
	        $months_difference++;
	      }
	      $months_difference--;
	      $datediff = $months_difference;
	      break;
	    case 'y': // Difference between day numbers
	      $datediff = date("z", $dateto) - date("z", $datefrom);
	      break;
	    case "d": // Number of full days
	      $datediff = floor($difference / 86400);
	      break;
	    case "w": // Number of full weekdays
	      $days_difference = floor($difference / 86400);
	      $weeks_difference = floor($days_difference / 7); // Complete weeks
	      $first_day = date("w", $datefrom);
	      $days_remainder = floor($days_difference % 7);
	      $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
	      if ($odd_days > 7) { // Sunday
	        $days_remainder--;
	      }
	      if ($odd_days > 6) { // Saturday
	        $days_remainder--;
	      }
	      $datediff = ($weeks_difference * 5) + $days_remainder;
	      break;
	    case "ww": // Number of full weeks
	      $datediff = floor($difference / 604800);
	      break;
	    case "h": // Number of full hours
	      $datediff = floor($difference / 3600);
	      break;
	    case "n": // Number of full minutes
	      $datediff = floor($difference / 60);
	      break;
	    default: // Number of full seconds (default)
	      $datediff = $difference;
	      break;
	  }
	  return $datediff;
	}
    
    static function date_withFullMonth($data) {
        $str=explode(".",$data);
        $months=array("Januar","Februar","März","Mai","Juni","Juli","August","September","Oktober","November","Dezember");
        return $str[0].". ".$months[intval($str[1])]." ".$str[2];
    }

	/**
	 * Überprüft, ob das übergebene Datum die From der SQL-Daten hat (yyyy-mm-dd hh:mm:ss).
	 *
	 * @param string $date Datum
	 * @return boolean Ist SQL-Datum?
	 */
	static function date_isSQLDate($date) {
		if(preg_match("/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30))) (([0-1][0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))$/",$date) OR preg_match("/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/",$date)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Addiert 2 Zeitstempel zusammen und gibt das Ergebnis zurück.
	 *
	 * @param int $date1 Zeitstempel 1
	 * @param int $date2 Zeitstempel 2
	 * @return int Zeitstempel 1 + Zeitstempel 2
	 */
	static function date_add($date1,$date2) {
		//$date2=strtotime($date2);

		$output=($date1+$date2);
		return $output;
	}

	/**
	 * Addiert zu einem Zeitstempel eine beliebige Anzahl Tage dazu und gibt das Ergebnis als Zeitstempel zurück
	 *
	 * @param int $date Zeitstempel
	 * @param int $num Anzahl Tage
	 * @return int Zeitstempel
	 */
	static function date_addDays($date,$num) {
		$days=(60*60*24*$num);
		//$date=strtotime($date);

		$output=($days+$date);
		return $output;
	}

	/**
	 * Wandelt eine Zeit im Stringformat (hh:mm:ss) in einen Zeitstempel um.
	 *
	 * @param string $str Zeit
	 * @return int Zeitstempel
	 */
	static function date_stringToTime($str) {
		$str=explode(":",$str);

		$time+=$str[0]*60*60;
		$time+=$str[1]*60;
		if($str[2]) {
			$time+=$str[2];
		}
		return $time;
	}

	/**
	 * Errechnet aus einem Zeitstempel die Zeit und gibt diese im hh:mm:ss-Format zurück.
	 *
	 * @param int $time Zeitstempel
	 * @return string Zeit
	 */
	static function date_timeToString($time) {
		$seconds=$time%60;
		$time=$time-$seconds;

		$minutes=(($time/60)%60);
		$time=$time-($minutes*60);

		$hours=($time/60/60);

		$seconds=strlen($seconds)==1?'0'.$seconds:$seconds;
		$minutes=strlen($minutes)==1?'0'.$minutes:$minutes;
		$hours=strlen($hours)==1?'0'.$hours:$hours;


		$time=$hours.":".$minutes.":".$seconds;
		return $time;
	}

	/************************************
	 *
	 * HTML-Funktionen
	 *
	 * Formen aus den Parametern HTML-Code.
	 * Den folgenden Funktionen ist jeweils die Zeichenkette 'htm_' vorangestellt.
	 *
	 **************************************/

	/**
	 * Generiert ein Formular-Feld vom Typ 'hidden'. Diese Funktion wird verwendet, um ein Wert,
	 * der übergeben wurde in ein Formular zu integrieren und an dessen Ziel-Datei weiter zu leiten.
	 *
	 * @param string $name
	 * @return string
	 */
	static function htm_formRedirect($name) {
		return "<input type='hidden' name='".$name."' value='".div::http_getGP($name)."'>";
	}

	/**
	 * Generiert den HTML-Code um ein Java-Script-File einzubinden.
	 *
	 * @param string $filename
	 * @return string
	 */
	static function htm_includeJSFile($filename) {
		$content = '
<script language="JavaScript" src="'.$filename.'" type="text/javascript"></script>';
		return $content;
	}

	/**
	 * Generiert den HTML-Code um ein CSS-File einzubinden.
	 *
	 * @param string $filename
	 * @return string
	 */
	static function htm_includeCSSFile($filename) {
		$content = '
<link rel="stylesheet" type="text/css" href="'.$filename.'">';
		return $content;
	}

	/**
	 * Hüllt das <script>-Tag um den übergebenen JS-Code und gibt das ganze zurück.
	 *
	 * @param string $script
	 * @return string
	 */
	static function htm_includeJS($script) {
		$content = '
<script language="JavaScript" type="text/JavaScript">
		'.$script.'
</script>';
		return $content;
	}

	/**
	 * Liest den Inhalt eines JS-Files und gibt den Inhalt zurück
	 *
	 * @param string $script
	 * @return string
	 */
	static function htm_includeJSFileAsString($script) {
		if (!($fp = fopen($script, "r"))) {
		   //raiseMessage();
		}
		$data = '
'.fread($fp, filesize($script)).'
';
		fclose($fp);
		return $data;
	}


	/**
	 * Hüllt das <style>-Tag um den übergebenen CSS-Code und gibt das ganze zurück.
	 *
	 * @param string $css
	 * @return string
	 */
	static function htm_includeCSS($css) {
		$content = '<style type="text/css">
		'.$css.'
		</style>';
		return $content;
	}

	/**
	 * Gibt einen Standard-HTML-Page-Header zurück.
	 *
	 * @param string $title
	 * @return string
	 */
	static function htm_getPageHeader($title='') {
		return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>'.$title.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body style="margin:0px">';
	}

	/**
	 * Gibt das Ende einer HTML-Page zurück.
	 *
	 * @param string $title
	 * @return string
	 */
	static function htm_getPageFooter() {
		return '</body>
</html>';
	}

	/**
	 * Hüllt das <img>-Tag um den übergebenen Pfad zu einem Bild.
	 *
	 * @param string $fileName
	 * @return string
	 */
	static function htm_wrapIcon($fileName) {
		$html='<img src="'.$fileName.'">';
		return $html;
	}

	/**
	 * Gibt einen Kommentar, der im HTML-Code verwendet werden kann zurück.
	 *
	 * @param string $comment
	 * @return string
	 */
	static function htm_setComment($comment) {
		$content.='<!--
		'.$comment.'
		-->
		';
		return $content;
	}

	/**
	 * Gibt die Zeichenkette in fetter Schrift zurück.
	 *
	 * @param string $str
	 * @return string
	 */
	static function htm_bold($str) {
		return '<b>'.$str.'</b>';
	}


	/**
	 * Fügt den Inhalt zweier Content-Arrays zusammen und speichert das Resultat im 1. Array.
	 *
	 * Ein Content-Array ist folgendermassen aufgebaut:
	 *  'main' >> Der Hauptteil des HTML-Codes, der sich innerhalb des Body-Tags befindet.
	 * 	'header' >> Header-Informationen wie Meta-Tags, Java-Script oder CSS-Datei einbindungen.
	 * 	'CSS' >> Purer (ohne <style>-Tag) CSS-Text, der im Header angebracht wird.
	 * 	'JS' >> Purer (ohne <script>-Tag) Java-Script-Text, der im Header angebracht wird.
	 *
	 * @param array [ref] $stack
	 * @param array $newContent
	 */
	static static function htm_mergeSiteContent(&$stack,$newContent) {
		if(isset($newContent['JS'])) {
			$stack['JS'].='
			'.$newContent['JS'].'
			';
		}
		if(isset($newContent['CSS'])) {
			$stack['CSS'].='
			'.$newContent['CSS'].'
			';
		}
		if(isset($newContent['main'])) {
			$stack['main'].='
			'.$newContent['main'].'
			';
		}
		if(isset($newContent['header'])) {
			$stack['header'].='
			'.$newContent['header'].'
			';
		}
	}

	/**
	 * Gibt den Inhalt eines Content-Arrays als HTML-Seite aus. Diese Funktion ist der einzige Ausgabepunkt... Die einzige Funktion mit echo-Befehl.
	 *
	 * @param unknown_type $content
	 * @param unknown_type $title
	 */
	static function htm_echoContent($content,$title='') {
		global $db;
		$content['JS'] = $content['JS']!=''?div::htm_includeJS($content['JS']):'';
		$content['CSS'] = $content['CSS']!=''?div::htm_includeCSS($content['CSS']):'';

		$html='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>'.$title.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
'.$content['header'].'
'.$content['JS'].'
'.$content['CSS'].'</head>
<body>
'.$content['main'].'
</body>
</html>';

		//echo $db->view_array($content);
		echo $html;
	}


	/*static function getLibDir() {
		return div::getRelRootDir()."library/";
	}*/

	/*static function getRelRootDir() {
		global $_CONFIG;


		if(preg_match("/".$_CONFIG['basic']['pageName']."/",$_SERVER['SCRIPT_NAME'])) {
			$stop=false;
			$count=0;
			$str = substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],"/"));
			while (!$stop) {

				$str=substr($str,0,strrpos($str,"/"));
				if(!preg_match("/".$_CONFIG['basic']['pageName']."/",$str)) {
					$stop=true;
				} else {$count++;}
			}
			$path=str_repeat("../",$count);

		} else {
			$path="http://".$_SERVER['SERVER_NAME'].substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],$_CONFIG['basic']['pageName'])).$_CONFIG['basic']['pageName']."/library/";
		}

		return $path;
	}*/

	/************************************
	 *
	 * HTTP-Funktionen
	 *
	 * Erledigen ihre Aufgabe in der Datenübertragung zwischen Server und Client.
	 * Den folgenden Funktionen ist jeweils die Zeichenkette 'http_' vorangestellt.
	 *
	 **************************************/

	/**
	 * Liefert den Wert einer übergebenen Variabel aus dem $_GET oder $_POST Array - je nach dem wo er vorhanden ist.
	 *
	 * @param string $wert
	 * @return mixed
	 */
	static function http_getGP($wert)
	{
		If ($_GET[$wert] == "") {
	         	return $_POST[$wert];
	         } else {
	         	return $_GET[$wert];
		}
	}
    
    static function http_curlRequest($url,$agent='',$cookie='',$ssl=false,$debugOutput=false) {
        $ch = curl_init();
        
        if(is_array($url)) {
            curl_setopt_array($ch,$url);
            if(isset($agent)) $debugOutput=$agent;
        } else {
            if(!$agent) $agent='Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)';
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
            
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            if($ssl) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($debugOutput) {
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        }
        
        $result = curl_exec ($ch);
        if($debugOutput) {                                                                 
             echo "<br><b>Anfrage:</b><br>".curl_getinfo($ch, CURLINFO_HEADER_OUT)."<br><b>Antwort:</b><br>".htmlentities($result);
        }
            
        curl_close ($ch);
        return $result;
    }

	/************************************
	 *
	 * Variablen Funktionen
	 *
	 * Formen Variablen um, konvertieren Typen oder durchsuchen Arrays oder Listen.
	 * Den folgenden Funktionen ist je nach Kategorie die Zeichenkette 'var_','str_' oder 'array_' vorangestellt.
	 *
	 **************************************/

	/**
	 * Beschreibt eine Variable mit dem Wert $value, falls diese leer ist.
	 *
	 * @param mixed [ref] $var
	 * @param mixed $value
	 */
	static function var_fillIfEmpty(&$var,$value) {
		if(empty($var)) {
			$var=$value;
		}
	}
    
    static function var_unsetKeys($array,$keyList) {
        if(!is_array($keyList)) {
              $keyList=explode(',',$keyList);
        }
        
        foreach($keyList as $key) {
            if($array[$key]) unset($array[$key]);
        }
        
        return $array;
    }

	/**
	 * Formt ein assoziatives Array in ein nummerisches um.
	 *
	 * @todo Wird diese Funktion überhaupt irgendwann benötigt??
	 * @param array $array
	 * @return array
	 */
	static function array_assocToNum($array) {
		$newArray=Array();
		foreach($array as $value){
			if(is_array($value)){
				array_push($newArray,div::array_assocToNum($value));
			}else{
				array_push($newArray,$value);
			}
		}
		return $newArray;
	}


	/**
	 * Schneidet einen String in eine angegebene Länge, falls er diese überschreitet.
	 *
	 * @param string $text
	 * @param int $len
	 * @return string
	 */
	static function str_cut($text,$len) {
		if(strlen($text)>$len) {
			$text=substr($text,0,($len-3))."...";
		}
		return $text;
	}

	/**
	 * Wandelt eine Boolean-Variable in einen String um, der entweder 'true' oder 'false' ergibt. Wird hauptsächlich für tests verwendet.
	 *
	 * @param boolean $boolean
	 * @return string
	 */
	static function var_boolToString($boolean) {
		if($boolean) {
			$val='true';
		} else {
			$val='false';
		}
		return $val;
	}

	/**
	 * Wandelt einen Integer-Wert in einen String um. Da PHP beim Int-Wert 0 einen leeren String erzeugt, wird diese Funktion benötigt.
	 *
	 * @param int $int
	 * @return string
	 */
	static function var_intToString($int) {
		if($int==0) {
			return '0';
		} else {
			return $int;
		}
	}

	static function http_encodeURLArray($name,$array) {
		$i=0;
		foreach($array as $key=>$val) {
			if(!is_array($val)) {
				$str.='&'.$name.'['.$key.']='.$val;
			} else {
				$str.=div::encodeURLArray($name.'['.$key.']',$val);
			}
			$i++;
		}
		return $str;
	}

	/**
	 * Durchsucht eine Stringliste nach dem angegebenen Wert und liefert true falls er gefunden wird.
	 *
	 * @param string $list
	 * @param string $str
	 * @param string $separator
	 * @return boolean
	 */
	static function var_isInList($list,$str,$separator=",") {
		$list=explode($separator,$list);
		$val=false;
		foreach($list as $item) {
			if($item==$str) {
				$val=true;
			}
		}
		return $val;
	}


	/**
	 * Serialisiert ein Objekt und speichert den Inhalt im Temp-Ordner.
	 * Ist die Umkehrfunktion von div::var_restoreObject().
	 *
	 * @see div::var_restoreObject()
	 * @param mixed $obj
	 * @param string $name
	 */
	static function var_saveObject($obj,$name,$path='') {
		$obj = serialize($obj);
		if(!$path) {$path=TEMP_DIR;}
		$fp = fopen($path.$name."_".session_id(), "w");
		fputs($fp, $obj);
		fclose($fp);
	}

	/**
	 * Deserialisiert ein Objekt, das mit der Funktion div::var_saveObjekt() gespeichert wurde und gibt es zurück;
	 * Ist die Umkehrfunktion von div::var_saveObject().
	 *
	 * @see div::var_saveObject
	 * @param string $name
	 * @return mixed
	 */
	static function var_restoreObject($name,$path='') {
		if(!$path) {$path="temp/";}
		$obj = implode("",@file($path.$name."_".session_id()));
		$obj = unserialize($obj);
		return $obj;
	}

	/**
	 * Ist Das Objekt mit dem angegebenen Namen schon gespeichert?
	 *
	 * @param string $name
	 * @return boolean
	 */
	static function var_isSavedObject($name,$path='') {
		if(!$path) {$path="temp/";}
		return file_exists($path.$name."_".session_id());
		//return false;
	}

	/************************************
	 *
	 * Datei- und Dateisystem Funktionen
	 *
	 * Führen Befehle und Berechnungen im Bereich von Dateien, Dateisystem, Pfaden durch.
	 * Den folgenden Funktionen ist jeweils die Zeichenkette 'file_' vorangestellt.
	 *
	 **************************************/

	/**
	 * Liefert die Endung mit? "." der angegebenen Datei oder des Pfads.
	 *
	 * @param string $name
	 * @return boolean
	 */
	static function file_getEndung($filename) {
		$endung=strrchr($filename,".");
		return $endung;
	}


	/**
	 * Liest den Dateinamen aus einem Pfad heraus.
	 *
	 * @param string $path
	 * @return string
	 */
	static function file_getRawFilename($path) {
		if(preg_match("/\//",$path)) {
			$path=substr(strrchr($path,"/"),1);
		}
		return $path;
	}

	static function htm_wrapErrorMsg($message,$code) {
		return "<br>".$message."<br>".$code;
	}

	static function http_getURL($addition,$exceptions='') {
		$url.=$_SERVER['PHP_SELF']."?";
		//echo urlencode($_GET);

		$exceptions=$exceptions?$exceptions.",":'';
		foreach($addition as $name=>$value) {
			$exceptions.=$name.",";
		}
		$exceptions=substr($exceptions,0,(strlen($exceptions)-1));

		foreach($_GET as $name=>$value) {
			if(!div::var_isInList($exceptions,$name)) {
				if(!is_array($value)) {
					$url.=$name."=".$value;
				} else {
					$url.=div::http_encodeURLArray($name,$value);
				}
				$url.="&";
			}
		}
		foreach($addition as $name=>$value) {
			if(!is_array($value)) {
					$url.=$name."=".$value;
				} else {
					$url.=div::http_encodeURLArray($name,$value);
				}
				$url.="&";
		}
		$url=substr($url,0,(strlen($url)-1));
		return $url;
	}

	static function http_redirect($url) {
		echo '<script language="javascript" type="text/javascript">
		top.window.location.href="'.$url.'";
		</script>';
	}

	static function var_replaceUml($str) {
		$str=str_replace("ä","ae",$str);
		$str=str_replace("ö","oe",$str);
		$str=str_replace("ü","ue",$str);

		$str=str_replace("Ä","Ae",$str);
		$str=str_replace("Ö","Oe",$str);
		$str=str_replace("Ü","Ue",$str);
		return $str;
	}

	static function xml_xmlToArray($file) {
		global $db;

		$xml_parser = xml_parser_create();

		if (!($fp = fopen($file, "r"))) {
		   //raiseMessage();
		}

		$data = fread($fp, filesize($file));
		fclose($fp);


		xml_parse_into_struct($xml_parser, $data, $vals, $index);
		xml_parser_free($xml_parser);
        //echo $db->view_array($vals);

		if(!function_exists('sortArray')) {
			function sortArray(&$vals,$reset=false) {
				global $db;

				static $i=0;

				//static $level=1;
				$k=$i;
				//echo "<br><br>Start!!!";
				do  {
					if($vals[$k]['type']!='cdata') {
						//echo "Kein Cdata";
						$level=$vals[$k]['level'];
						//$i=$k;
					}
					$k++;
				} while ($vals[$k]['type']=='cdata');

				$array=array();
				//$i=$pointer;

				while(($level<=$vals[$i]['level'] OR $vals[$i]['type']=='cdata') AND is_array($vals[$i])) {
					$vals[$i]['tag']=str_replace(chr(9),'',$vals[$i]['tag']);
					//$vals[$i]['tag']=htmlentities($vals[$i]['tag'],ENT_QUOTES,'UTF-8');
					//$vals[$i]['tag']=div::xml_fitSpecialChars($vals[$i]['tag'],true);

					if($vals[$i]['type']=='open') {
						$tempI=$i;
						$i++;


						$addition=sortArray($vals);


						//echo "ADDITION:".$db->view_array($addition);

						if(is_array($vals[$tempI]['attributes'])) {
							$array[$vals[$tempI]['tag']]['[ATTRIBUTES]']=$vals[$tempI]['attributes'];
							$array[$vals[$tempI]['tag']]['[CONTENT]']=$addition;
						} else {
							$array[$vals[$tempI]['tag']]=$addition;
						}
					} elseif($vals[$i]['type']=='close') {
						$i++;
					} elseif($vals[$i]['type']=='complete') {
						//echo "VALS:".$vals[$i]['value'];
						$vals[$i]['value']=str_replace(chr(9),'',$vals[$i]['value']);
						//echo "VALS:".$vals[$i]['value'];
						//$vals[$i]['value']=htmlentities($vals[$i]['value'],ENT_QUOTES,'UTF-8');
						$vals[$i]['value']=div::xml_fitSpecialChars($vals[$i]['value'],true);

						//echo "TESTARRAY(".$vals[$i]['tag']."):".$array[$vals[$i]['tag']];
						if(is_array($vals[$i]['attributes'])) {
							$array[$vals[$i]['tag']]['[CONTENT]']=$vals[$i]['value'];
							$array[$vals[$i]['tag']]['[ATTRIBUTES]']=$vals[$i]['attributes'];
							//echo "ATRIB:".$db->view_array($array[$vals[$i]['tag']]['[ATTRIBUTES]']);
						} else {
							$array[$vals[$i]['tag']]=$vals[$i]['value'];
						}
						$i++;
					} else {
						$i++;
					}
				}
				if($reset) {
					$i=0;
				}
				return $array;
			}
		}


		//echo $db->view_array($vals);
		$array=sortArray($vals,true);
		//echo $db->view_array($array);
		return $array;
	}

	static function xml_ArrayToXml_forUpdate($array) {
		global $db;

		if(!function_exists('wrapXML')) {
			function wrapXML($array,$level=1) {
				foreach($array as $tag=>$item) {

					if(is_numeric(substr($tag,0,1))) {
						$tag='NUM_'.$tag;
					}
					if(is_array($item)) {
						if(is_array($item['[ATTRIBUTES]'])) {
							$output.=str_repeat(chr(9),$level).'<'.$tag;
							foreach($item['[ATTRIBUTES]'] as $key=>$value) {
								//$key=div::xml_fitSpecialChars($key);
								//$value=div::xml_fitSpecialChars($value);

								$output.=' '.$key.'=\''.$value.'\'';
							}
							$output.='>'.chr(13).chr(10);
							unset($item['[ATTRIBUTES]']);
						} else {
							$output.=str_repeat(chr(9),$level).'<'.$tag.'>'.chr(13).chr(10);
						}
						if(array_key_exists('[CONTENT]',$item)) {
							if(is_array($item['[CONTENT]'])) {
								$output.=wrapXML($item['[CONTENT]'],$level+1);
							} else {
								$item['[CONTENT]']=div::xml_fitSpecialChars($item['[CONTENT]']);
								$output.=str_repeat(chr(9),$level+1).$item['[CONTENT]'].chr(13).chr(10);
							}
						} else {
							$output.=wrapXML($item,$level+1);
						}

						$output.=str_repeat(chr(9),$level).'</'.$tag.'>'.chr(13).chr(10);
					} else {
						$item=div::xml_fitSpecialChars($item);
						//echo $item;
						$output.=str_repeat(chr(9),$level).'<'.$tag.'>'.$item.'</'.$tag.'>'.chr(13).chr(10);
					}
				}
				return $output;
			}
		}


		$content=wrapXML($array);
		return '<?xml version="1.0" encoding="ISO-8859-1"?><xml>
'.chr(13).chr(10).$content.'</xml>';

	}

	static function xml_fitSpecialChars($xmlValue,$reverse=false) {
		$chars=array(
			'&'=>'&amp;',
			'\''=>'&apos;',
			'<'=>'&lt;',
			'>'=>'&gt;',
			'"'=>'&quot;',
			'Ä'=>'&#196;',
			'Ö'=>'&#214;',
			'Ü'=>'&#220;',
			'ä'=>'&#228;',
			'ö'=>'&#246;',
			'ü'=>'&#252;',
			'ß'=>'&#223;',
			'à'=>'&#133;',
			'/'=>'&#047;',
			'*'=>'&#042;',
		);
		$keys=array_keys($chars);
		if($reverse) {
			for($i=count($chars)-1;$i>=0;$i--) {
				$xmlValue=str_replace($chars[$keys[$i]],$keys[$i],$xmlValue);
			}
			$xmlValue=str_replace('NUM_','',$xmlValue);
			//$xmlValue=htmlspecialchars($xmlValue);
			//echo htmlspecialchars($xmlValue).'<br>';
		} else {
			for($i=0;$i<count($chars);$i++) {
				$xmlValue=str_replace($keys[$i],$chars[$keys[$i]],$xmlValue);
			}
		}

		return $xmlValue;
	}

	public static function file_getFiles($dir,$folders=false) {
		if($handle=opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if($file != "." && $file != "..") {
					if (is_dir($dir.'/'.$file) && $folders) {
						$addition=div::file_getFiles($dir.'/'.$file,$folders);
						if(is_array($addition)) {
							$files=array_merge($files,$addition);
						}
		  			} else {
		 				$files[]=$dir.'/'.$file;
		  			}
				}
	   		}
			closedir($handle);
			return $files;
		}
	}

	public static function file_readFile($file) {
		$fp = fopen($file, "r");
		$data = fread($fp, filesize($file));
		return $data;
	}

	static function file_recRmdir ($path) {
	    // schau' nach, ob das ueberhaupt ein Verzeichnis ist
	    if (!is_dir ($path)) {
	        return -1;
	    }
	    // oeffne das Verzeichnis
	    $dir = opendir ($path);

	    // Fehler?
	    if (!$dir) {
	        return -2;
	    }

	    // gehe durch das Verzeichnis
	    while ($entry = @readdir($dir)) {
	        // wenn der Eintrag das aktuelle Verzeichnis oder das Elternverzeichnis
	        // ist, ignoriere es
	        if ($entry == '.' || $entry == '..') continue;
	        // wenn der Eintrag ein Verzeichnis ist, dann
	        if (is_dir ($path.'/'.$entry)) {
	            // rufe mich selbst auf
	            $res = div::file_recRmdir ($path.'/'.$entry);
	            // wenn ein Fehler aufgetreten ist
	            if ($res == -1) { // dies duerfte gar nicht passieren
	                @closedir ($dir); // Verzeichnis schliessen
	                return -2; // normalen Fehler melden
	            } else if ($res == -2) { // Fehler?
	                @closedir ($dir); // Verzeichnis schliessen
	                return -2; // Fehler weitergeben
	            } else if ($res == -3) { // nicht unterstuetzer Dateityp?
	                @closedir ($dir); // Verzeichnis schliessen
	                return -3; // Fehler weitergeben
	            } else if ($res != 0) { // das duerfe auch nicht passieren...
	                @closedir ($dir); // Verzeichnis schliessen
	                return -2; // Fehler zurueck
	            }
	        } else if (is_file ($path.'/'.$entry) || is_link ($path.'/'.$entry)) {
	            // ansonsten loesche diese Datei / diesen Link
	            $res = @unlink ($path.'/'.$entry);
	            // Fehler?
	            if (!$res) {
	                @closedir ($dir); // Verzeichnis schliessen
	                return -2; // melde ihn
	            }
	        } else {
	            // ein nicht unterstuetzer Dateityp
	            @closedir ($dir); // Verzeichnis schliessen
	            return -3; // tut mir schrecklich leid...
	        }
	    }

	    // schliesse nun das Verzeichnis
	    @closedir ($dir);

	    // versuche nun, das Verzeichnis zu loeschen
	    $res = @rmdir ($path);

	    // gab's einen Fehler?
	    if (!$res) {
	        return -2; // melde ihn
	    }

	    // alles ok
	    return 0;
	}

	public static static function xml_parseXML($data) {
		global $db;

		//$data=preg_replace("(".chr(9)."|".chr(13)."|".chr(10).")","",$data);
		$array=self::_xml_parseString($data);
		//echo $db->view_array($array);
		return $array;
	}

	private static function _xml_parseString($string) {
		global $db;

		$pattern="{<(\w+)(\s(\w*=\"[^>]*\"|\w*='[^>]*'))*(/>|>\s*(.*)\s*</\\1>)}s";

		if(preg_match_all($pattern,$string,$vals)) {
			$array=array();
			//echo $db->view_array($vals);

			for($i=0;$i<count($vals[0]);$i++) {
				$addition=self::_xml_parseString($vals[5][$i]);

				if($vals[2][$i]) {
					preg_match_all("{\s((\w*)=\"(.*?)\")|((\w*)='(.*?)')}",$vals[2][$i],$attributes);
					//echo $db->view_array($attributes);
					for($g=0;$g<count($attributes[0]);$g++) {
						if($attributes[2][$g]) {
							$array[$vals[1][$i]]['[ATTRIBUTES]'][$attributes[2][$g]]=$attributes[3][$g];
						} elseif($attributes[5][$g]) {
							$array[$vals[1][$i]]['[ATTRIBUTES]'][$attributes[5][$g]]=$attributes[6][$g];
						}
					}
					$array[$vals[1][$i]]['[CONTENT]']=$addition;
				} else {
					$array[$vals[1][$i]]=$addition;
				}
			}
			//echo $db->view_array($array);
			return $array;
		} else {
			//echo $string;
			return div::xml_fitSpecialChars($string,true);
		}
	}

	public static function var_parseString($str,$args) {
        global $db;
        
		if(is_string($str)) {
			if(is_array($args)) {
				foreach($args as $name=>$value) {
					$str=preg_replace("/<".$name.">/",$value,$str);
				}
			}
			If (preg_match("/<GP>(.*)<\/GP>/s",$str,$GP)) {
				$pattern=$GP[0];
				$val=$GP[1];
				$str=preg_replace("/<GP>".$val."<\/GP>/s",div::http_getGP($val),$str);
			}
			If (preg_match_all("/<action>(.*)<\/action>/U",$str,$action_array)) {
                
                
				$action=table::decodeAction();
                 
				for($i=0;$i<count($action_array[0]);$i++) {
                    //$action_array[0][$i]=div::var_parseString($action_array[0][$i],$args);
					$str=preg_replace("/".preg_quote($action_array[0][$i],"/")."/s",$action[$action_array[1][$i]],$str);
                    
				}
			}
			If (preg_match_all("/<method>\\$(.*?)->(.*?)\((.*?)[^,]<\/method>/s",$str,$function)) {
				for($i=0;$i<count($function[0]);$i++) {
					$tag=$function[0][$i];
					$object=$GLOBALS[$function[1][$i]];
					$method=$function[2][$i];
					$params=$function[3][$i];

					$str=preg_replace("/".preg_quote($function[0][$i],"/")."/s",call_user_method($method,$object,$params),$str);
				}
			}
			If (preg_match_all("/<func>(.*)<\/func>/U",$str,$function)) {
                global $db;
                
				for($i=0;$i<count($function[0]);$i++) {
					$tag=$function[0][$i];
					$func=div::var_parseString($function[1][$i],$args);

					eval("\$result=".$func.";");
					$str=preg_replace("/".preg_quote($tag,"/")."/s",$result,$str);
				}
			}
		}
        
		return $str;
	}
    
    public static function var_parseString2($str,$args,$startSymbol='<',$endSymbol='>') {
          foreach($args as $search => $replacement) {
              $str=preg_replace("/".$startSymbol.$search.$endSymbol."/s",$replacement,$str);
          }
          return $str;
    }

	/*public static function file_domGzFileDecompress($file, $destination=null) {
		global $db;

		$zp = gzopen($file, "r");
		$data=gzread($zp,10000000);
		gzclose($zp);

		echo htmlspecialchars($data);
		$data=div::xml_parseXML($data);
		echo $db->view_array($data);
		$data=$data['xml'];

		if(!$destination) {
			$basedir=str_replace(div::file_getEndung($file),'',$file);
		} else {
			$basedir=$destination;
		}

		if(is_dir($basedir)) {
			echo div::file_recRmdir($basedir);
		}

		mkdir($basedir,777);

		self::_file_decFiles($data,$basedir);
	}

	private static function _file_decFiles($data,$basedir) {
		global $db;
		foreach($data as $file) {
			echo $db->view_array($file['[ATTRIBUTES]']);
			if($file['[ATTRIBUTES]']['type']=='folder') {
				mkdir($basedir.'/'.div::file_getRawFilename($file['[ATTRIBUTES]']['path']),777);
				if(is_array($file['[CONTENT]'])) {
					self::_file_decFiles($file['[CONTENT]'],$basedir.'/'.div::file_getRawFilename($file['[ATTRIBUTES]']['path']));
				}
			} else {
				$fp=fopen($basedir.'/'.div::file_getRawFilename($file['[ATTRIBUTES]']['path']),"w");
				fwrite($fp,$file['[CONTENT]']);
				fclose($fp);
			}
		}
	}

	public static function file_domGzFileCompress($file,$files,$excludes) {
		global $db;
		echo "file_domGzFileCompress";
		echo $file;

		$data = div::xml_arrayToXML(self::_file_mergeFiles($files,$excludes));
		//echo $data;
		$zp = gzopen($file, "w9");
		gzwrite($zp, $data);
		gzclose($zp);
	}

	private static function _file_mergeFiles($files,$excludes,$rootFolder=null) {
		global $db;

		static $i=0;
		//echo $db->view_array($files);
		if(is_array($files)) {
			foreach($files as $file) {
				if(is_dir($file)) {
					if(!div::var_isInList($excludes,$file)) {
						$i++;
						$rootFolder=$rootFolder.'/'.div::file_getRawFileName($file);
						$data[$i]['[CONTENT]']=self::_file_mergeFiles(div::file_getFiles($file,false),$excludes,$rootFolder);
						$data[$i]['[ATTRIBUTES]']=array('type'=>'folder','path'=>$rootFolder);
						$rootFolder='';
					}
				} else {
					if(!div::var_isInList($excludes,$file)) {
						$i++;
						$data[$i]['[CONTENT]']=div::file_readFile($file);
						$data[$i]['[ATTRIBUTES]']=array('type'=>'file','path'=>$rootFolder.'/'.div::file_getRawFileName($file));
					}
				}

			}
		}
		return $data;
	}*/

	/**
	 * Unvierselle Funktion um Arrays zu XML-TExt zu konvertieren.
	 *
	 * @param input_array $array
	 * @return XML-Text
	 *
	 * @author Honoré Vasconcelos <coolbug2@gmail.com>
	 * @subpackage Fremd
	 */
	public static function xml_ArrayToXML($array){
        static $Depth;

        foreach($array as $key => $value){
            if(!is_array($value)){
                unset($Tabs);
                for($i=1;$i<=$Depth+1;$i++) $Tabs .= "\t";
                if(preg_match("/^[0-9]\$/",$key)) $key = "n$key";
                $XMLtext .= "$Tabs<$key>$value</$key>\n";
            } else {
                $Depth += 1;
                unset($Tabs);
                for($i=1;$i<=$Depth;$i++) $Tabs .= "\t";
                //search for atribut like [name]-ATTR to put atributs to some object
                if(!preg_match("/(-ATTR)\$/", $key)) {
                    if(preg_match("/^[0-9]\$/",$key)) $keyval = "n$key"; else $keyval = $key;
                    $closekey = $keyval;
                    if(is_array($array[$key."-ATTR"])){
                        foreach ($array[$key."-ATTR"] as $atrkey => $atrval ) $keyval .= " ".$atrkey."=\"$atrval\"";
                    }
                    $XMLtext.="$Tabs<$keyval>\n";
                    $XMLtext.=self::xml_ArrayToXML($value);
                    $XMLtext.="$Tabs</$closekey>\n";
                    $Depth -= 1;

                }
            }
        }
        return $XMLtext;
    }

    public static function debug($title,$content) {
    	global $DEBUG_OUTPUT;

    	if($DEBUG_OUTPUT) {
    		echo '<div class="debug" style="border:2px solid red;margin:0;margin-top:10px;padding-left:10px;"><h1 style="font-size:12px">'.$title.'</h1><p>'.$content.'</p></div>';
    	}
    }

}
?>
