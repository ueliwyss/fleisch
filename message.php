<?
include('init.php');

if(div::var_isSavedObject("messageViewer","temp/")) {
	$msgViewer=div::var_restoreObject("messageViewer","temp/");
} else  {
	$msgViewer=new messageViewer();
}
div::htm_echoContent($msgViewer->wrapContent(),"");
div::var_saveObject($msgViewer,"messageViewer","temp/");
?>