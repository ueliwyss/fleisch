<?

$doNotLogin=true;
include_once('init.php');

$currency=new currency();

$content = array();





div::htm_mergeSiteContent($content,$currency->wrapPriceTag('20202020','EUR'));
div::htm_echoContent($content);


?>