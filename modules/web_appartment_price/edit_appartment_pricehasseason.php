<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');


$action=table::decodeAction();

//$tab=new tab();


	$content=table::getList('appartment_price','addToSeason');





//div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>