<?
include('../../../init.php');
define('IMAGE_DIR','../bilder/');

include(LIB_PATH.'class.tab.php');
$gallery=new gallery();
?>


<html><head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Von PC hinzuf&#252;gen</title>
<link media="all" type="text/css" href="iescreen.css" rel="stylesheet">
<script src="browser.js" language="javascript" type="text/javascript"></script>
<script src="popup.js" language="javascript" type="text/javascript"></script>
<script src="toggle.js" language="javascript" type="text/javascript"></script>
<script src="formsub.js" language="javascript" type="text/javascript"></script>
<script src="uploader.js" language="javascript" type="text/javascript"></script>
<script src="hotkeys.js" language="javascript" type="text/javascript"></script>
<script src="btndisable.js" language="javascript" type="text/javascript"></script>
</head>
<body style="background:url(gbg.jpg) top left repeat-x;background-color:#c8e8ff" id="popbody" onload="albumChange(document.getElementById('album'))" onunload="unloadUploader()" onkeydown="doDown(event.keyCode);" onkeyup="doUp(event.keyCode);">
<div id="tmpImagesId" style="visibility:hidden;position:absolute;width:1px;height:1px;overflow:hidden"></div>
<div id="popcontainer">
<div id="popgc">
<div id="popcont">
<div id="tool" style='width:100%'>
<div class="left" style='width:10px'></div>
<div class="popmed" style='width:97%'>
<div class="button">
<div style="width:92px" id="btn0" class="btnmain">
<div class="L"></div>
<a style="width:75px;" class="label" href="javascript:SendFiles();" id="btn0lnk" target="_self">
<img src="images/attachfile.gif" class="btnicon" alt=""/>Anf&#252;gen
</a>
<div class="R"></div></div></div>
<img class="sepm" src="images/sepm.gif">
<div class="button">
<div style="width:82px" id="link1" class="link">
<div class="L"></div>
<a style="width:auto" class="linklink" href="javascript:DeleteFiles();" id="link1lnk" target="_self">
<img src="images/delete.gif" class="btnicon" alt=""/>L&#246;schen
</a>
<div class="R"></div>
</div></div>
<img class="sepm" src="images/sepm.gif">
<div class="button">
<div style="width:102px" id="link2" class="link">
<div class="L"></div>
<a style="width:auto" class="linklink" href="javascript:this.close();" id="

l" target="_self">
<img src="images/cancel.gif" class="btnicon" alt=""/>Schlie&#223;en</a>
<div class="R"></div></div></div>
<div onclick="" id="help">
</div></div>
<div class="right"></div></div>
<div id="bigconth"><h1 style="margin-left:8px;">Von PC hinzuf&#252;gen</h1></div>
<div style="padding:0;height:500px" id="popcontm">
<link media="screen" type="text/css" href="signup.css" rel="stylesheet"><style>
		.progTD { font-size:6px;}
		.text1 { font-size:11px;font-family:trebuchet ms,tahoma;}
		.text2 { font-family:trebuchet ms,tahoma;font-size:11px; color:#4E8AE0;border:solid 1px transparent}
		.text0 { font-family:trebuchet ms,tahoma;font-size:11px;}
		.listTitle { border-top:solid 1px #7F7F7F;border-bottom:solid 1px #7F7F7F;background:#f5f5f5;}
		.hide { position:absolute; visibility:hidden; top:0; left:0; }
		.browseStyle { font-size:10px; border:solid 0px #7f7f9f; font-family:trebuchet ms,tahoma; background:#aabbcc;}
</style>

<script language="Javascript">

		var type = '';

		var iUpload = null;
		var strMessage = null;
		var blankPath = 'b.htm'
		var blankPath = 'b.htm'
		//var blankPath = '{/root/params/protocol}://{/root/params/hostname}{/root/params/blankpath}';
		var browser = 'ie';

		var imageUpload=true;
		config = {
			protocol : 'http',
			DomainName : 'localhost',
			port : '80',
			respondent : 'action.php',
			formAction : 'action.php'
		}

		measurings = {
			mgByte : 'MB',
			kgByte : 'KB',
			minute : 'Min.',
			second : 'Sek.',
			hour : 'Std.'
		}
		messages = {
			progressTitle : 'Ladevorgang l&#228;uft...',
			progressTitleFinished : 'Ladevorgang abgeschlossen',
			antivirusTitle : 'AntiVirus-Scan...',
			antivirusFinished : 'AntiVirus-Scan',

			message1 : 'Gel&#246;scht',
			message2 : 'Beendet',
			message3 : 'OK',
			message4 : 'FEHLER',
			message5 : 'Abgeschlossen'
		}
		buttons = {
			btn_delete : 'L&#246;schen',
			btn_upload : 'Ok',
			btn_addfile : 'Durchsuchen',
			btn_cancel : 'Schlie&#223;en'
		}
		errors = {
			error1 : 'Die Datei kann leider nicht beigelegt werden. Die maximale Dateigr&#246;&#223;e wurde &#252;berschritten. ',
			error2 : 'Sie m&#252;ssen einige zu ladende Dateien ausw&#228;hlen.',
			error3 : 'Maximale Dateiengr&#246;&#223;e &#252;berschritten',
			error4 : 'Virus gefunden',
			error5 : 'Unbekannter Fehler',
			error6 : 'Diesen Dateinamen gibt es bereits. &#196;ndern Sie ihn oder w&#228;hlen Sie eine andere Datei.  ',
			error7 : 'Der Dateiname ist leider ung&#252;ltig. &#196;ndern Sie ihn oder w&#228;hlen Sie eine andere Datei.',
			error8 : 'Das Dateiformat stimmt nicht mit image/* &uuml;berein. Sie k&ouml;nnen nur Bilder hochladen.'
		}

	</script>

	<table style="margin:0;padding:0" id="mainTableId" bgColor="white" width="98%">
	<tr>
	<td style="margin:0;padding:0" class="text0" align="left">
	<div class="formlabel">Bitte klicken Sie auf Suchen, um die zu ladenden Dateien auszuw&#228;hlen</div>
	</td>
	</tr>
	<tr>
	<td align="center" style="margin:0;padding-left:3px" width="100%" id="addButtonId" >
	<div style="float:left;width:340px"></div>
	</td>
	</tr>
	<tr>
	<td style="padding-left:3px;padding-bottom:0;border:none" width="100%" id="title2ID" class="text2" align="left"></td>
	</tr>
	<tr>
	<td width="100%">
				<table cellspacing="0" cellpadding="0" border="0" class="listTitle" width="100%">
				<tr>
				<td id="listTop1" class="text1" width="10px">Gallery :: Upload</td>
				<td style="border-left:1px inset" id="listTop2" class="text1" width="50%"></td><td align="right">Zielalbum:&nbsp;<?
echo $gallery->wrapAlbumOptionList(); ?></td>
				<td id="listTop3" align="right" style="width:40%">
				<input onclick="CheckClick();" id="files_check" name="files_check" type="checkbox"><a href='javascript:document.getElementById("files_check").click();'>Alle Ausw&auml;hlen</a>
				</td>
				</tr>
				</table>
				<input type="hidden" name="popup" value=1>
	</tr>
	<tr>
	<td style="margin:0;padding-left:3px;padding-bottom:0;'" align="left" id="filesListId" width="100%"></td>
	</tr>
	<tr>
	<td style="margin:0;padding:0" width="100%">
				<table class="listTitle" cellspacing="0" cellpadding="0" width="100%" border="0">
				<tr>
				<td style="border-left:0px inset" id="listBottom1">
				<span class="text1">&nbsp;Gesamt&nbsp;:&nbsp;<span class="text1" id="total_files">0</span><span class="text1"> &nbsp;Datei(en)&nbsp;</span></span>
				</td>
				<td style="border-left:solid 1px #777" width="142px" id="listBottom2" colspan="2">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
					<td class="text1" align="left">
					&nbsp;&nbsp;&nbsp;<span class="text1" id="total_size">0</span> / 10.0&nbsp;MB
					</td>
					</tr>
					</table>
				</td>
				</tr>
				</table>
	</td>
	</tr>
	<tr>
	<td style="margin:0;padding:0" height="40px" width="100%" align="center">
				<table width="100%" style="align:center" cellspacing="0" cellpadding="0" border="0" id="progress_table">
				<tr>
				<td style="padding-top:10px;padding-bottom:10px" id="progressTitleID" width="100%" align="center" class="text2"></td>
				</tr>
				<tr>
				<td width="100%" align="center">
				<div style="align:left;padding:0 3px;width:260px;height:13px;background-image:url(images/bar.gif);background-repeat:no-repeat" id="slider"></div>
				</td>
				</tr>
				</table>
	<div id="outMessages" style="color:red;align:center" class="text1">&nbsp;</div>
	</td>
	</tr>
	</table>


	<iframe style="position:absolute;visibility:hidden;height:400px;width:400px;top:10px;left:10px" id="uploaderFrame" onload="fromServer();" name="uploaderFrame" src="b.htm"></iframe>
	<div class="hide" id="browseID"></div></div>
	<div style="padding-left:8px;" id="bigcontft">
	<div style="float:right;margin-left:4px;width:100px;">
	<div class="button">
	<div style="width:92px" id="btn0" class="btnmain">
	<div class="L"></div>
	<a style="width:75px;" class="label" href="javascript:SendFiles();" id="btn0lnk" target="_self">
	<img src="images/attachfile.gif" class="btnicon" alt=""/>Anf&#252;gen
	</a>
	<div class="R"></div></div></div></div></div></div></div></div>

	<form name="actionmsg"></form>
	<form name="localmsg"></form>



	<script>pretoggle();initUploader()</script>
					<!-- SZM VERSION="1.3" -->
					<script language="JavaScript" type="text/javascript">
					<!--
					var IVW="http://lycos.ivwbox.de/cgi-bin/ivw/CP/2D01AQC00000;";
					document.write("<img src=\""+IVW+"?r="+escape(document.referrer)+"\" width=\"1\" height=\"1\" alt=\"\" />");
					// -->
					</script>
					<noscript>
					<img src="http://lycos.ivwbox.de/cgi-bin/ivw/CP/2D01AQC00000;" width="1" height="1" alt="" />
					</noscript>
					<!-- /SZM -->

				<script language="JavaScript" type="text/javascript"><!--
	  var _rsCI='lycos-de';
	  var _rsCG='0';
	  var _rsDT=1;
	  var _rsSI=escape(window.location);
	  var _rsLP=location.protocol.indexOf('https')>-1?'https:':'http:';
	  var _rsRP=escape(document.referrer);
	  var _rsND=_rsLP+'//server-uk.imrworldwide.com/';

	  if (parseInt(navigator.appVersion)>=4) {
	    var _rsRD=(new Date()).getTime();
	    var _rsSE=0;
	    var _rsSV='';
	    var _rsSM=0;
	    _rsCL='<scr'+'ipt language="JavaScript" type="text/javascript" src="'+_rsND+'v5.js"><\/scr'+'ipt>';
	  } else {
	    _rsCL='<img src="'+_rsND+'cgi-bin/m?ci='+_rsCI+'&cg='+_rsCG+'&si='+_rsSI+'&rp='+_rsRP+'">';
	  }
	  document.write(_rsCL);
	//--></script>
	<noscript><img alt="" src="//server-uk.imrworldwide.com/cgi-bin/m?ci=lycos-de&cg=0"></noscript></body></html>