<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');

$view=div::http_getGP('view')==''?"list":div::http_getGP('view');
$action=table::decodeAction();

$tableName='element';

$tab=new tab();



$content=table::getTreeView('page','peTree',false);
/*if($view=='list') {
	$tab->addElement($tab1=new tabItem('ListAll'));
	$tab1->content=table::getList($tableName,'listAll',false,array(),$tab1);

	$tab->addElement($tab2=new tabItem('New'));
	$tab2->content=table::getForm($tableName,table::MODE_NEW,false,table::MODE_NEW);

} else {
	$tab->addElement(new tabItem('Edit',table::getForm($tableName,table::MODE_EDIT,false,table::MODE_EDIT,$action['uid']),true));

	if($action['mode']!=table::MODE_NEW) {
		//Gallery
	}
}

div::htm_mergeSiteContent($content,$tab->wrapContent());*/
div::htm_echoContent($content);


?>