<?
session_start();

$rootDirName='';

function defSpecialDirs() {
	global $rootDirName;
    
	$scriptPath=$_SERVER['SCRIPT_NAME']; 

	$scriptPath=$rootDirName==''?$scriptPath:substr($scriptPath,strpos($scriptPath,$rootDirName)+strlen($rootDirName));   
	$lengthBevore=strlen($scriptPath);
	$scriptPath=str_replace("/","",$scriptPath);
	$lengthAfter=strlen($scriptPath);
    
	$backDirs=($lengthBevore-$lengthAfter)-1;
    
	if($backDirs>0) {
		$rootDir=str_repeat('../',$backDirs);
	}
    
	define('LIB_PATH',$rootDir."library/");
	define('ROOT_DIR',$rootDir);
	define('MOD_DIR',$rootDir.'modules/');
	define('TEMP_DIR',$rootDir.'temp/');
	define('ICON_DIR',$rootDir.'icons/');
	define('UPDATE_DIR',$rootDir.'updates/');
}

defSpecialDirs(); 
require(ROOT_DIR."config.conf");

$TABLES=array();
$RELATIONS=array();


include_once(LIB_PATH."class.div.php");
include_once(LIB_PATH."class.framework.php");
include_once(LIB_PATH."class.table.php");
include_once(LIB_PATH."class.module.php");
include_once(LIB_PATH."class.message.php");
require_once(LIB_PATH."class.db.php");
require_once(LIB_PATH."class.vars.php");
include_once(LIB_PATH."class.thumb.php");
include_once(LIB_PATH."class.update.php");
include_once(LIB_PATH."class.ajax.php");
include_once(LIB_PATH."class.calendar.php");




$DEBUG_OUTPUT=true;

$db=new MySQLDB();

//Datenbank-Verbindung aufgrund der Daten im $_CONFIG-Array aufbauen.
if($db->sql_pconnect($_CONFIG['MysqlDB']['server'], $_CONFIG['MysqlDB']['username'], $_CONFIG['MysqlDB']['password'])) {
	if(!$db->sql_select_db($_CONFIG['MysqlDB']['database'])){
		die(div::http_redirect(ROOT_DIR.'install.php'));
	}
} else {
	die(div::http_redirect(ROOT_DIR.'install.php'));
}

module::loadModules();

if(!$doNotLogin AND !user::isLoggedIn()&&div::file_getRawFilename($_SERVER['SCRIPT_FILENAME'])!="login.php") {
	div::http_redirect(ROOT_DIR."login.php");
}



$config=array(
	'type'=>'test',
	'title'=>'update',
	'name'=>'title',
	'version'=>'12',
);
/*
$file=update::createUpdatePackage($config,$files);

div::file_domGzFileDecompress(UPDATE_DIR.$file);

update::gzFileCompress(TEMP_DIR.session_id().'.dom',$files,'c:/webpages/domino/modules/web_gallery/bilder');

update::gzFileDecompress(TEMP_DIR.session_id().'.dom');*/

?>