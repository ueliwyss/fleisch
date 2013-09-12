<?



$workingPath = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/'));
@chmod($workingPath,777);
$reverse = $_GET['reverse']?true:false;

$files=listAllFiles($workingPath,$reverse);
foreach($files as $file) {
	echo $file.'<br>';
	replaceTerms($file,$reverse);
}


function listAllFiles($dir,$reverse) {

	$otherTerms=array(
		'.xml',
		'.js',
	);

	if($reverse) {
		$phpTerm_bevore='.php5';
		$phpTerm_after='.php';

	} else {
		$phpTerm_bevore='.php';
		$phpTerm_after='.php5';
	}
	$files=array();

	if($handle=opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			if($file != "." && $file != ".." && $dir.'/'.$file != $_SERVER['SCRIPT_FILENAME']) {
				if (is_dir($dir.'/'.$file)) {
					$addition=listAllFiles($dir.'/'.$file,$reverse);
					if(is_array($addition)) {
						$files=array_merge($files,$addition);
					}
	  			} elseif(getEndung($file)==$phpTerm_bevore OR hasTerm($file,$otherTerms)){
	 				$files[]=$dir.'/'.$file;
	  			}
			}
   		}
		closedir($handle);
		return $files;
	}
}

function replaceTerms($file,$reverse) {


	$otherTerms=array(
		'.xml',
		'.js',
	);

	if($reverse) {
		$term_bevore='.php5';
		$term_after='.php';
	} else {
		$term_bevore='.php';
		$term_after='.php5';
	}

	$fp = fopen($file, "r");
	$data = fread($fp, filesize($file));

	if(hasTerm($file,$otherTerms)) {
		if(!$reverse) {
			$data = str_replace($term_after,$term_bevore,$data);
		}

		$data = str_replace($term_bevore,$term_after,$data);


		fclose($fp);

		$fp = fopen($file, "w");
		fwrite($fp,$data);
		fclose($fp);
	} else {

		$data = str_replace($term_bevore,$term_after,$data);
		fclose($fp);
		unlink($file);

		$fp = fopen(str_replace(getEndung($file),'',$file).$term_after, "w");
		fwrite($fp,$data);
		fclose($fp);
	}

}

function getEndung($filename) {
	$endung=strrchr($filename,".");
	return $endung;
}

function getRawFilename($path) {
	if(preg_match("/\//",$path)) {
		$path=substr(strrchr($path,"/"),1);
	}
	return $path;
}

function hasTerm($file,$terms) {
	$output=false;
	foreach($terms as $term) {
		if($term==getEndung($file)) {
			$output=true;
		}
	}
	return $output;
}

?>