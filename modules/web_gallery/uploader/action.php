<div id="errorCode">-1</div>
<?
include_once("../../../init.php");
include(LIB_PATH.'class.tab.php');
define('IMAGE_DIR','../bilder/');

$gallery=new gallery();
$gallery->uploadAll(div::http_getGP('album_id'));

?>