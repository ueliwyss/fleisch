<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');


define('IMAGE_DIR','bilder/');

$view=div::http_getGP('view')==''?"list":div::http_getGP('view');
$action=table::decodeAction();
$tableName='image';

$tab=new tab();


if($view=='list') {
	$tab->addElement($tab1=new tabItem('Allgemein'));
	$tab1->content=table::getList($tableName,'listAll',false,array(),$tab1);

	$tab->addElement($tab2=new tabItem('Neues Album'));
	$tab2->content=table::getForm($tableName,table::MODE_NEW,false,table::MODE_NEW);

} else {
	$tab->addElement($tabItem = new tabItem('Allgemein',table::getForm($tableName,table::MODE_EDIT,false,table::MODE_EDIT,$action['uid']),true));

	$row=$db->exec_SELECTgetRows('i_source','image','uid='.$action['uid']);
	$thumb=new thumb(IMAGE_DIR.$row[0]['i_source'],TEMP_DIR);
	$thumb->maximalSized=false;
	$thumb->maxImageHeight=400;
	$thumb->maxImageWidth=400;

	$content_thumb=array();
	$content_thumb['main']=div::htm_wrapIcon($thumb->getThumb());

	div::htm_mergeSiteContent($tabItem->content,$content_thumb);
	/*if($action['mode']!=table::MODE_NEW) {
		$tab->addElement($tab3=new tabItem('Bilder'));
		$gallery=new Gallery($tab3);
		//$gallery->setOptions($_GET);
		$tab3->content=table::getList('image','listAll',false,null,$tab3);
		div::htm_mergeSiteContent($tab3->content,$gallery->getUploaderButton());
	}*/
}

div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>