// uploader.js

var browseCount = 0;
var upframe = null;
var result = "";
var lastBytes = 0;
var lastTime = 0;

var tdCount = 27;
var maxWidth = 0;

var currInd = 0;
var currCell = null;
var progressRow = null;
//var progTableRow = null;
var timerProg = 0;
var timerLoad = 0;
var timerFullLoad = 0;
var responseString = "";
var _startUpload = true;
var iTimer = 0;

var fullLoadedMain = false;
var fullLoadedIFrame = false;
var fullLoadedIFrame1 = false;

var maxThumbWidth=140;
var maxThumbHeight=120;
var listWidth=744;
var num_imgPerRow=Math.floor((listWidth-20)/maxThumbWidth);

function initUploader() {
	strMessage = document.getElementById("outMessages");
	var tdBrowse = document.getElementById("browseID");
	document.onclick = function() {outReset();}

	if(isActiveX()) {

		var str = '<input id="fileChooserId" type="file" accept="image/*" onchange="AddFile(this.value, null);" style="display:none">'
		tdBrowse.innerHTML = str;

		iUpload.attachEvent('OnFileDrop', UpdateFileCount);
		iUpload.attachEvent('OnTerminate', iUpload_OnTerminate);
		iUpload.attachEvent('OnComplete', iUpload_OnComplete);
		iUpload.attachEvent('OnFileSizeExceeded', iUpload_OnFileSizeExceeded);
		iUpload.attachEvent('OnSend', iUpload_OnSend);
		iUpload.attachEvent('OnReceived', iUpload_OnReceived);
		iUpload.attachEvent('OnInvalidFilename', iUpload_OnInvalidFilename);
		iUpload.attachEvent('OnDuplicateFilename', iUpload_OnDuplicateFilename);
	} else {
		iUpload = null;
		if(browser == 'ie')
			initForIE();
		else {
			initForOP();
		}
	}

	timerFullLoad = setTimeout('waitFullLoad()', 2);
	// get progress div
	progressRow = document.getElementById("slider");
	initProgressForIE();
	document.getElementById("progressTitleID").innerHTML = "&nbsp;";
	fullLoadedMain = true;
}

//
function getNotification(win, doc) {
	if(browser == 'ie') {
		var frm = doc.getElementById("formUploader");
		url = config.formAction; //respondent; //  formAction
		frm.setAttribute("action", url);
	}
	upframe = getFrame();
	fullLoadedIFrame1 = true;
}

function hiddenInput() {
	return '<input id="file' + browseCount + '" accept="image/*" type="file" name="file' + browseCount + '" size=10 class="text2" onchange="AddFile(this.value, this);">'
}

var opTimer = 0;
function initForOP() {
	document.getElementById("serverForm").innerHTML = "";
	var form = document.getElementById("formUploader").innerHTML =  hiddenInput()+"<input type='hidden' id='albumId' name='album_id' value='"+document.getElementById("album").value+"'>";
	if(browser == 'op')
		document.getElementById("uploaderFrame").addEventListener("load", fromServer, false);
}

function initForIE() {

	document.getElementById("filesListId").innerHTML = '<div id="listDivID" style="height:300px;width:'+listWidth+'px;background:#fff;overflow:auto;" onselect="return false" onselectstart="return false"></div>';
	document.getElementById("title2ID").innerHTML = "&nbsp;"
	// restor fileslist's table top
	var td = document.getElementById("listTop2");
	td.parentNode.removeChild(td);
	document.getElementById("listTop1").width="";
	td = document.getElementById("listBottom2");
	td.innerHTML = "";
}

function initProgressForIE() {

	//
	progressRow = document.getElementById("slider");
	var str = "";
	for(var i = 0; i < tdCount; i++) {
		str += '<img id="prog' + i + '" src="images/progress.gif" align="left" border="0" hspace="0" vspace="0" style="visibility:hidden;marginLeft:0px;width:9px;align:left;">';
	}
	progressRow.innerHTML = str;
	setTimeout('hideProgress()', 50);
}

function initFrame() {
	//getFrame();
	var str = "";
	var tdBrowse = null;		// document.getElementById("browseButtonID");

	if(!isActiveX()) {
		browseCount = 0;

		if(browser != 'ie')
			initForOP();

		var div = document.getElementById("listDivID");
		div.innerHTML = "";

		if(browser == 'ie') {
			getFrame();
/*
			var str = '<form name="formUploader" action="' + config.formAction + '" id="formUploader" method="POST" enctype="multipart/form-data">';
			str += '<b>Form for send!</b>';
			str += '<div id="hiddenFileList" style="display:block;"></div>';
			str += '</form><div id="browseID"></div>';
			upframe.body.innerHTML = str;
*/
			var tdBrowse = upframe.getElementById("browseID");
			var str = '<input id="fileChooserId" type="file" accept="image/*" name="file' + browseCount + '" size="5" class="text2" onchange="parent.AddFile(this.value, this);">'
			tdBrowse.innerHTML = str;
		}
	} else {
		if(upframe && upframe.body)
			setTimeout('upframe.body.innerHTML = "";', 100);
	}
	UpdateFileCount();
	document.getElementById("progressTitleID").innerHTML = "&nbsp;";
	responseString = "";
}

function AddFile(filePath, node) {

	if(filePath=='')
		return;
	if(isActiveX()) {

		fileNames=filePath.split("\\");
		fileName=fileNames[fileNames.length-1];
		var code = document.getElementById('iUploadID').AttachFile(fileName,filePath);
		switch(code) {
			case 1: {
				setTimeout('outError(errors.error7)', 1);
				break;
			}
			case 2: {
				setTimeout('outError(errors.error6)', 1);
				break;
			}
			case 3: {
				setTimeout('outError(errors.error1)', 1);
				break;
			}
			default:
				UpdateFileCount();
		}
	} else {
		if(!isImage(filePath)) {setTimeout('outError(errors.error8)', 1);return;}
		if(checkItem(filePath) == false) {
			var nodes=getNodes();

			var shortFilePath = "";
			var s = filePath;
			shortFilePath += s.substring(0, s.indexOf("\\")) + "\\..";
			while(s.indexOf("\\") != -1) {
				s = s.substring(s.indexOf("\\")+1, s.length);
			}
			shortFilePath += "\\" + s;

			var div = document.getElementById("listDivID");
			var w0 = div.offsetWidth;

			var w1 = "88%";
			var w2 = "12%";
			if(browser != 'ie') {
				w1 = "auto"
				w2 = "" + 13 * (w0/100) + "px";
			}




			var str = '<td><table border="0" cellpadding="0" width="' + maxThumbWidth + '" cellspacing="0" style="table-layout:fixed;border-bottom:solid 1px #dee7ee" id="tblList">';
			str += '<tr  height='+(maxThumbHeight+3)+'>'

			//str += '<td width="85%" class="text1" filePath="' + escape(filePath) + '" title="' + filePath + '">' + shortFilePath + '</td>';
			//str += '<td width="15%" style="border-left:1px inset #dee7ee" align="center">';
			str += '<td width="' + (maxThumbWidth+3) + '" height="' + (maxThumbHeight+3) + '" style="border:1px solid black;margin-top:1px;margin-bottom:1px" align="center"><img id="image'+browseCount+'" src="'+filePath+'"></td></tr><tr>';


			str += '<td style="width:100%;padding-left:0px;border-left:1px inset #dee7ee;" align="left" shortPath="'+ shortFilePath +'" filePath="' + filePath + '" title="' + filePath + '">';
			str += '<input id="' + browseCount + '" type="checkbox" name="ch' + browseCount + '" onclick="checkForDeleteButton()">'+shortFilePath+'</td>';





			str += '</tr></table></td>';
			div.innerHTML = organizeNodes(getNodes(),str);

			var image=document.getElementById("image"+browseCount);

			if(image.width>maxThumbWidth || image.height>maxThumbHeight) {
				if((image.width/maxThumbWidth)>(image.height/maxThumbHeight)) {
					image.setAttribute('height',(maxThumbWidth/image.width*image.height));
					image.setAttribute('width',maxThumbWidth);
				} else {
					image.setAttribute('width',(maxThumbHeight/image.height*image.width));
					image.setAttribute('height',maxThumbHeight);
				}
			}



			if(browser != 'ie') {
				browseCount++;

				node.className = "hide";
				var f = document.getElementById("serverForm");
				f.appendChild(node);

				document.getElementById("formUploader").innerHTML += hiddenInput();
				UpdateFileCount();
			} else {
				getFrame();
				hList = upframe.getElementById("hiddenFileList");
				_node = node;
				setTimeout('AddFile0()', 50);
			}
		} else {
			if(browser != 'ie') {
				node.parentNode.removeChild(node)
				document.getElementById("formUploader").innerHTML = hiddenInput()+"<input type='hidden' id='albumId' name='album_id' value='"+document.getElementById("album").value+"'>";
			} else
				restoreInputTag();
			setTimeout('outError(errors.error6)', 1);
		}

		// restore input tag
	}
}

function isImage(filename) {
	return filename.match(/(jpg|jpeg|gif|bmp|png)/i);
}

function getNodes() {

	var div = document.getElementById("listDivID");


	var nodes = document.getElementById("tmpImagesId");

	nodes.innerHTML='';

	if(div.innerHTML=='') {return nodes;}

		for(var i=0;i<div.firstChild.firstChild.childNodes.length;i++) {
			var node=div.firstChild.firstChild.childNodes[i];
			for(var g=0;g<node.childNodes.length;g++) {

				nodes.innerHTML+=node.childNodes[g].innerHTML;
			}
		}

	return nodes;
}

function organizeNodes(nodes,newNode) {
	var str='';
if(nodes.hasChildNodes()||newNode!='') {
	str = '<table><tr>';
	var counter = 0;

	if(nodes!=null) {
		for(var i=0;i<nodes.childNodes.length;i++) {
			var node = nodes.childNodes[i].firstChild.childNodes[1].firstChild;
			var image=nodes.childNodes[i].firstChild.firstChild.firstChild.firstChild;
			var checkbox=nodes.childNodes[i].firstChild.childNodes[1].firstChild.firstChild;
			var checked=checkbox.getAttribute("checked")==true?'checked':'';

			if(counter==num_imgPerRow) {counter=0;str+='</tr><tr>';}
			counter++;
			str += '<td>';
			str += '<table border="0" cellpadding="0" width="' + (maxThumbWidth+3) + '" cellspacing="0" height="1px" style="table-layout:fixed;border-bottom:solid 1px #dee7ee" id="tblList">';
				str += '<tr  height='+(maxThumbHeight+3)+'>'

				//str += '<td width="85%" class="text1" filePath="' + escape(filePath) + '" title="' + filePath + '">' + shortFilePath + '</td>';
				//str += '<td width="15%" style="border-left:1px inset #dee7ee" align="center">';
				str += '<td width="' + (maxThumbWidth+3) + '" height="' + (maxThumbHeight+3) + '" style="border:1px solid black;margin-top:1px;margin-bottom:1px" align="center"><img id="image'+image.id+'" src="'+node.getAttribute('filePath')+'" width='+image.getAttribute("width")+' height='+image.getAttribute("height")+'></td></tr><tr>';


				str += '<td style="width:0px;border-left:1px inset #dee7ee;padding-left:0px;" align="left" shortPath="'+node.getAttribute('shortPath')+'" filePath="' + node.getAttribute('filePath') + '" title="' + node.getAttribute('title') + '">';
				str += '<input id="' + checkbox.id + '" type="checkbox" name="ch' + checkbox.id + '" onclick="checkForDeleteButton()" '+checked+'>'+node.getAttribute("shortPath")+'</td>';





				str += '</tr></table></td>';

		}
	}
	if(newNode!='') {
		if(counter==num_imgPerRow) {counter=0;str+='</tr><tr>'}
		str += newNode;
	}
	str += '</tr></table>';
}

	return str;

}


var hList = null;
var _node = null;

function AddFile0() {

 	var hList = upframe.getElementById("hiddenFileList");
	if(!hList) {
		//alert(upframe.body.innerHTML)
		hList = upframe.getElementById("hiddenFileList");
	}
	var node = hList.appendChild(_node);
	node.id = "file" + browseCount;
	browseCount++;
	UpdateFileCount();
	_node = null;
	 restoreInputTag();
}

function restoreInputTag() {

	if(browser == 'ie') {
		var str = '';
		var tdBrowse = null;
		if(isActiveX()) {
			var tdBrowse = document.getElementById("browseID");
			str = '<input id="fileChooserId" accept="image/*" type="file" onchange="AddFile(this.value, null);" style="display:none">'
		}
		else {
			var tdBrowse = upframe.getElementById("browseID");
			str = '<input id="fileChooserId" accept="image/*" type="file" name="file' + browseCount + '" onchange="parent.AddFile(this.value, this);">'
		}
		tdBrowse.innerHTML = str;

	}
}

function chooseFile() {

	var node;
	if(isActiveX())
		node = document.getElementById("fileChooserId");
	else {
		getFrame();
		node = upframe.getElementById("fileChooserId");
	}
	if(node)
		node.click();
}

function checkItem(fileName) {

	var list = getNodes();


	for(var i = 0; i < list.childNodes.length; i++) {

		var child = list.childNodes.item(i);

		if(child && child.nodeType == 1) {
			var f1 = fileName.substring(fileName.lastIndexOf("\\")+1, fileName.length);

			var n = child.firstChild.childNodes[1].firstChild;

			var f2 = unescape(n.getAttribute("filePath"));

			f2 = f2.substring(f2.lastIndexOf("\\")+1, f2.length);
			if(f1 == f2)
				return true;
		}
	}
	return false;
}

// MML
function checkForDeleteButton() {

	if(!isActiveX() || browser == 'ie') {
		var list = document.getElementById("listDivID");
		for(var i = 0; i < list.childNodes.length; i++) {
			var child = list.childNodes.item(i);
			if(child && child.nodeType == 1) {
				if(child.firstChild.firstChild.lastChild.firstChild.checked) {
					//document.getElementById('deleteButtonID').disabled = false;
					return true;
				}
			}
		}
	}
	//document.getElementById('deleteButtonID').disabled = true;
}

function unloadUploader() {
	if(iUpload)
		iUpload.Terminate();
}

function outError(str) {
	strMessage = document.getElementById("outMessages");
	strMessage.style.color = "#ff0000";
	strMessage.innerHTML = str;
}

function outWarning(str) {
//	document.getElementById("cancelButtonID").disabled = false;
	strMessage = document.getElementById("outMessages");
	strMessage.style.color = "#2187da";
	strMessage.innerHTML = str;
}

function outReset() {
	strMessage = document.getElementById("outMessages");
	strMessage.style.color = "#000000";
	strMessage.innerHTML = "&nbsp;";
}

function doNot() {
	window.event.returnValue = false;
	return false;
}


function CheckClick() {

	if(isActiveX()) {
		if(document.getElementById('files_check').checked)
			document.getElementById('iUploadID').CheckAllFiles();
		else
			document.getElementById('iUploadID').UnCheckAllFiles();
	} else {
		var list = getNodes();
		var val = document.getElementById('files_check').checked;
		for(var i = 0; i < list.childNodes.length; i++) {
			var child = list.childNodes.item(i);
			if(child && child.nodeType == 1) {
				var check = child.firstChild.childNodes[1].firstChild.firstChild;//.innerHTML;

				if(check)
					check.checked = val;
			}
		}
		document.getElementById("listDivId").innerHTML=organizeNodes(list,'');
		checkForDeleteButton();
	}
}

function DeleteFiles() {

	if(isActiveX()) {
		document.getElementById('iUploadID').DeleteSelectedFiles();
	} else {
		getFrame();
		//upframe = document.getElementById("uploaderFrame").contentDocument;
		var list = getNodes();

		var ok = false;
		var count = list.childNodes.length;
		var i = -1;
		while(count >= 0 ) {

			i++;

			if(list.hasChildNodes()) {

				var child;
				if(browser == 'ie')
					child = list.childNodes[i];
				else
					child = list.childNodes.item(i);
				if(child && child.nodeType == 1) {
					var check = child.firstChild.childNodes[1].firstChild.firstChild;//.innerHTML;

					if(check.checked == true){
						child.parentNode.removeChild(child);
// hhh
						if(browser == 'ie') {


							var node = upframe.getElementById("file" + check.id);
							node.parentNode.removeChild(node);
						} else {
							var node = document.getElementById("file" + check.id);
							node.parentNode.removeChild(node);
						}

						document.getElementById("listDivId").innerHTML=organizeNodes(list,'');

						list = getNodes();


						i = -1;
						count = list.childNodes.length;
					}
				}
				count--;
			} else
				break;
		}
	}
	document.getElementById('files_check').checked = false;
	UpdateFileCount();
}

function Format(total,decimals) {

	var num = parseFloat(total);
	// First section sets non-number value to zero
	if (!(num = parseFloat(num)))
		num = "0.00";
	// Second section sets two decimal place format
	var Pad = "";
	num = "" + Math.ceil(num * Math.pow(10,decimals + 1) + 5);
	// Pad if less than 0.10
	if(num.length < decimals+1) {
	for(Count = num.length; Count <= decimals; Count++)
		Pad += "0";
	}
	num = Pad + num;
	// Parse into final string
	num = num.substring(0,num.length - decimals - 1) + "." + num.substring(num.length - decimals -1, num.length -1);
	// If less than 1 then add 0 to the left of the decimal
	if((num == "") || (parseFloat(num) < 1))
		num = "0" + num;
	// Final section returns formatted number
	return num;
}

function UpdateFileCount() {

	var count = 0;
	if(isActiveX()) {
		count = document.getElementById('iUploadID').GetTotalFileSize();
		document.getElementById("total_files").innerHTML = document.getElementById('iUploadID').GetFileCount(); //+" files";
		document.getElementById("total_size").innerHTML="   "+Format((count / 1048576),1); //+" / 5MB";
	} else {
		count = getNodes().childNodes.length
		document.getElementById("total_files").innerHTML = count;
	}
}

function iUpload_OnFileSizeExceeded() {
	outError(errors.error1);
}

function iUpload_OnCancel() {

	outWarning(messages.message1);
	document.getElementById("slider").style.width="0px";
}

function iUpload_OnTerminate() {

	outWarning(messages.message1 + ": "+document.getElementById('iUploadID').GetLastError());
	document.getElementById("slider").style.width="0px";
}
function iUpload_OnComplete() {

	getFrame();
	upframe.body.innerHTML = responseString;
	responseString = "";
	serverResponse();
	//document.getElementById("slider").style.width="0px";
	UpdateFileCount();
}

function iUpload_OnSend(nBytesSend,nBytesTotal) {
	UpdateProgress(nBytesSend,nBytesTotal)
}

function iUpload_OnInvalidFilename() {
	outError(errors.error7);
}

function iUpload_OnDuplicateFilename() {
	outError(errors.error6);
}

function iUpload_OnReceived(ResponseData,nBytesReceived) {
	responseString += ResponseData;
}

function UpdateProgress(nBytesSend,nBytesTotal) {

	var delta = nBytesTotal/tdCount;
	var count = parseInt(nBytesSend/delta) + 1;
	if(progressRow) {
		for(var i = 0; i < count; i++) {
			var img = document.getElementById("prog" + i);
			if(img && img.nodeType == 1)
				img.style.visibility="visible";
		}
	}
}

function SendFiles() {

	var date = new Date();
	lastBytes = 0;
	lastTime = date.getTime();
	var progMess = document.getElementById("progressTitleID");

	if(isActiveX()) {
		fileCount=document.getElementById('iUploadID').GetFileCount();
		if(fileCount < 1) {
			restoreInputTag();
			setTimeout('outError(errors.error2)', 1);
			return;
		}
	} else {
		var list = getNodes();
		fileCount = list.childNodes.length;
		var node = null;
		if(fileCount < 1) {
			setTimeout('outError(errors.error2)', 1);
			return;
		}
		if(browser != 'ie') {
			node = document.getElementById("serverForm");
		} else {
			getFrame();
			node = upframe.getElementById("formUploader");
			node.action = '' + config.formAction; //respondent;
		}

		if(node)
			node.submit();
		if(progMess && progMess.innerHTML != messages.progressTitle)
			runProgress();
		else
			alert("There is already one thread in progress");
	}
	if(progMess)
		progMess.innerHTML = messages.progressTitle;
	if(isActiveX()) {
		document.getElementById('iUploadID').SetConnectionInfo(config.DomainName,config.port,'','','/' + config.formAction);
		if(!document.getElementById('iUploadID').SendFiles())
			alert(document.getElementById('iUploadID').GetLastError());
		document.getElementById("slider").style.width="0px";
	}
}

function CancelUploader() {
	if(isActiveX())
		document.getElementById('iUploadID').Cancel();
	try {
		window.opener.returnUploaderValue("CANCEL");
	} catch(ex) {}
	self.close();
}

//
function isActiveX() {
	iUpload = document.getElementById('iUploadID');
	if(iUpload && (typeof iUpload.ReceiveChunkSize) == 'undefined')
		iUpload = null;
	return false;
}

function fromServer() {

	fullLoadedIFrame = true;
	if(_startUpload == true && browser == 'ie') {
		_startUpload = false;
		return;
	}
	if(timerProg != 0)
		clearTimeout(timerProg);
	if(isActiveX() || browser == 'ie' || browser == 'op')
		upframe = uploaderFrame.document;
	else
		upframe = document.getElementById("uploaderFrame").contentDocument;
	serverResponse();
}

function getFrame() {
	if(isActiveX() || browser == 'ie' || browser == 'op')
		upframe = uploaderFrame.document;
	else
		upframe = document.getElementById("uploaderFrame").contentDocument;
	return upframe;
}

function serverResponse() {

	getFrame();
	if(upframe.body.innerHTML == "")
		return;
	var div = upframe.getElementById("errorCode");

	// reset progressBar
	document.getElementById("progressTitleID").innerHTML = messages.progressTitleFinished;
	if(div) {
		var eCode = div.innerHTML;
		switch(eCode) {
			case '-1': {
				try {
					if(iUpload)
						document.getElementById("iUploadID").RemoveFiles();
					window.opener.returnUploaderValue("OK");
				} catch(ex) {}
				top.opener.top.content.location.reload(true);
				top.opener.focus();
				setTimeout('self.close();', 1000);
				return;
				break;
			}
			case '1':
			case '2':
			case '3':
			case '4':
			case '5':
			case '6':
			case '7': {
				var str = eval('errors.error' + eCode);
				setTimeout('outError("' + str + '")', 1);
				if(isActiveX())
					document.getElementById("iUploadID").RemoveFiles();
			}
		}
	} else {
		setTimeout('outError(errors.error5)', 1);
	}
	//alert(blankPath)
	fullLoadedIFrame = false;
	fullLoadedIFrame1 = false;
	document.getElementById("uploaderFrame").src = blankPath;
	if(isActiveX())
		document.getElementById("iUploadID").RemoveFiles();
	//if(browser != 'ie')	initFrame();
	_startUpload = true;
	timerLoad = setTimeout('waitFrameLoad()', 2);
	//setTimeout('initFrame0();', 1)
	stopProgressBar();
}

//////////////////////////////////////////////////////// Progress Bar
function runProgress() {
	document.getElementById("progressTitleID").innerHTML = messages.progressTitle;
	currInd = 0;
	progressRow = document.getElementById("slider");
	timerProg = setTimeout('showProgressBar()', 1);
}

function showProgressBar() {

	var imgItem = document.getElementById("prog" + currInd)
	if(currInd >= tdCount) {
		hideProgress();
	}
	if(imgItem) {
		imgItem.style.visibility = "visible";
		currInd++;
	}
	timerProg = setTimeout('showProgressBar()', 50);
}

function stopProgressBar() {
	setTimeout('hideProgress()', 1);
}

function hideProgress() {
	// MML
	if(progressRow) {
		for(var i = 0; i < progressRow.childNodes.length ; i++) {
			var img = document.getElementById("prog" + i);
			if(img && img.nodeType == 1)
				img.style.visibility="hidden";
		}
		currInd = 0;
	}
}

//////////////////////////////////////////////////////////////////
// draw button for IE
function initBrowseButton() {
	var s = '<div align="center" style="float:left;width:154px;" /><div class="button" style="width:92px;float:center;"><div style="width:92px;" onmouseout="this.className=\'btn\'" onmouseover="this.className=\'btn\'" id="btn1" class="btn"><div class="L"></div><a style="width:75px;text-align:center;" class="label" href="javascript:chooseFile();" id="btn1lnk">' + buttons.btn_addfile + '</a><div class="R"></div></div></div>';
	document.getElementById("addButtonId").innerHTML += s;
}

function  endUploaderLoad() {
	if(browser == 'ie') {
		initFrame();
		initBrowseButton();
	}
	fullLoadedMain = true;
}

// wait full uploader load
function waitFullLoad() {
	if(fullLoadedMain == true && fullLoadedIFrame1 == true) {		// fullLoadedIFrame == true &&
		endUploaderLoad();
		clearTimeout(timerFullLoad);
		timerFullLoad = 0;
	} else
		timerFullLoad = setTimeout('waitFullLoad()', 2);
}

function waitFrameLoad() {
	if(fullLoadedIFrame == true && fullLoadedIFrame1 == true) {
		initFrame();
		fullLoadedIFrame = false;
		fullLoadedIFrame1 = false;
		clearTimeout(timerLoad);
		timerLoad = 0;
	} else
		timerLoad = setTimeout('waitFrameLoad()', 2);
}
function albumChange(elem) {
	getFrame();
	upframe.getElementsByName("album_id")[0].value=elem.value;
}
/*
function setOnload() {
	var doc = uploaderFrame.document;
	if (doc) {
		if(doc.addEventListener) {	// Mozilla
			var f = document.getElementById("uploaderFrame");
			f.addEventListener("load", fromServer, false)
		}
	} else {	 // IE
		if(doc.attachEvent) {
            			doc.attachEvent("onload", fromServer)
		}
    	}
	//uploaderFrame.document.onload = fromServer;
}

function waitOPLoad() {

	if(uploaderFrame.document) {
		setOnload();
		clearTimeout(opTimer);
		opTimer = 0;
	} else {
		opTimer = setTimeout('waitOPLoad()', 2);
	}
}
*/
