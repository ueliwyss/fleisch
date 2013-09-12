<?php 

include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');

$tab=new tab();

$tab->addElement($tab2=new tabItem('ESR-Daten anfordern'));

//echo $db->view_array($_POST);
$ebanking=ebanking::instanciate();
//$ebanking->setBank(ebanking::BANK_POSTFINANCE);
//$ebanking->ebanking->skipFirstLoginStep=true;

$content=$ebanking->login();
$tab2->content=is_array($content)?$content:array(); 





div::htm_echoContent($tab->wrapContent());
 
?>