<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');

$view=div::http_getGP('view')==''?"list":div::http_getGP('view');
$action=table::decodeAction();

$tableName='fle_client';

$tab=new tab();

if(user::curusr_getRollId()==user::ROLL_KUNDE) $listNameadd='_client';  

if($view=='list') {
    $tab->addElement($tab1=new tabItem('Kunden'));
    $tab1->content=table::getList($tableName,'listAll'.$listNameadd,false,array(),$tab1);

    $tab->addElement($tab2=new tabItem('Neuer Kunde'));
    $tab2->content=table::getForm($tableName,table::MODE_NEW,false,table::MODE_NEW);

} else {
    $tab->addElement(new tabItem('Allgemein',table::getForm($tableName,table::MODE_EDIT,false,table::MODE_EDIT,$action['uid']),true));
    $tab->addElement(new tabItem('Bestellungen',table::getList('fle_order','clientsOrders'.$listNameadd,false,array()),false));
    
    if($action['mode']!=table::MODE_NEW) {
        //Gallery
    }
}

div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>