<?
include('../../init_mod.php');

$gallery=new Gallery($_SERVER['PHP_SELF']);

$gallery->setOptions($_POST);

echo $gallery->getUploaderButton();


//$gallery->getAlbums();
//$gallery->showGallery();
$gallery->showGallery(0);


//echo $db->view_array($_POST);


?>