<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');

$view=div::http_getGP('view')==''?"list":div::http_getGP('view');
$action=table::decodeAction();
$tableName='contact';

$tab=new tab();


if($view=='list') {
	$tab->addElement($tab1=new tabItem('Anfragen'));
	$tab1->content=table::getList($tableName,'listAll',false,array(),$tab1);

	$tab->addElement($tab2=new tabItem('Neue Anfrage'));
	$tab2->content=table::getForm($tableName,table::MODE_NEW,false,table::MODE_NEW);

} else {
	$tab->addElement(new tabItem('Allgemein',table::getForm($tableName,table::MODE_EDIT,false,table::MODE_EDIT,$action['uid']),true));

	if($action['mode']!=table::MODE_NEW) {
		/*$gallery=new Gallery($_SERVER['PHP_SELF']);
		$gallery->setOptions($_GET);
		$tab->addElement($tab3=new tabItem('Bilder',$gallery->showGallery($action['uid'])));
		div::htm_mergeSiteContent($tab3->content,$gallery->getUploaderButton());*/
	}
}

div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>