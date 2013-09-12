<?
include('init.php');

?>

<html>
<head>
<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
<title>Domino</title>

<script>
<?
/*echo '	var LIB_PATH="'.LIB_PATH.'";
	var ROOT_DIR="'.ROOT_DIR.'";
	var TEMP_DIR="'.TEMP_DIR.'";
	var ICON_DIR="'.ICON_DIR.'";
	var MOD_DIR="'.MOD_DIR.'";
';*/
echo div::htm_includeJSFileAsString(LIB_PATH.'jsfunc.edit.js');

?>
</script>
<?
//echo div::htm_JSFile(LIB_PATH.'jsfunc.edit.js');
?>
</head>
<frameset id="Fenster" rows="0,100,*,80">
 <frame name="actions" src="action.php" frameborder="0" noresize>
 <frame name="menu" src="menu.php" frameborder="0" scrolling="no" noresize>
 <frame name="content" src="" frameborder="0">
 <frame name="message" src="message.php" frameborder="0">
</frameset>
</head></html>



