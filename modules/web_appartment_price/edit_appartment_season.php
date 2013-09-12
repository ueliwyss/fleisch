<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');

$view=div::http_getGP('view')==''?"list":div::http_getGP('view');
$action=table::decodeAction();
$tableName='appartment_season';

$tab=new tab();


if($view=='list') {
	$tab->addElement($tab1=new tabItem('Jahresabschnitte'));
	$tab1->content=table::getList($tableName,'listAll',false,array(),$tab1);

	$tab->addElement($tab2=new tabItem('Neuer Jahresabschnitt'));
	$tab2->content=table::getForm($tableName,table::MODE_NEW,false,table::MODE_NEW);

} else {
	$tab->addElement(new tabItem('Allgemein',table::getForm($tableName,table::MODE_EDIT,false,table::MODE_EDIT,$action['uid']),true));

	if($action['mode']!=table::MODE_NEW) {
		/*$gallery=new Gallery($_SERVER['PHP_SELF']);
		$gallery->setOptions($_GET);
		$tab->addElement($tab3=new tabItem('Bilder',$gallery->showGallery($action['uid'])));
		div::htm_mergeSiteContent($tab3->content,$gallery->getUploaderButton());*/
	}

	$tab->addElement($rightTab=new tabItem('Preise'));
	$rightTab->content=table::getList('appartment_season','price',true,array("uid"=>$action['uid']),$rightTab);
	$regNewPerm=new formButton('Preis hinzufügen',"top.editElement('".table::MODE_NEW."','appartment_pricehasseason','".NO_UID."','".$action['uid']."',true)",ROOT_DIR.'images/ilist_actions_add.gif');
	div::htm_mergeSiteContent($rightTab->content,$regNewPerm->wrapContent());
}

div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>