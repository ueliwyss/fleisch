// JavaScript Document
function editElement(action,tableName,uid,foreign_uid,popup,urlPref,urlExt,form,triggerFile) {
	var pref=urlPref?urlPref:'';
	var ext=urlExt?urlExt:'';
	var f_uid=foreign_uid?'['+foreign_uid+']':'[NO_UID]';
	var f_form=form?'('+form+')':'()';
	if(triggerFile) {
		var file=triggerFile;
	} else {
		var file=pref+'edit_'+tableName+'.php';
	}
	var url=file+'?action='+action+'['+tableName+f_form+']'+f_uid+'['+uid+']&view=single&uid='+ext;

	if(popup) {
		popupWin = window.open(url+"&popup=1", "Popup", "width=600,height=400,left=100,top=200,scrollbars=yes");
		popupWin.focus();
	} else {   
		top.content.location.href=url;
	}
	return false;
}

function dropElement(tableName,uids,foreign_uids) {
	var popup=top.actions?false:true;
	var urlpart='';
	var index=1;
	uids=uids.split(",");


	for (var i = 0; i < uids.length; i++) {
		urlpart+="&uids["+i+"]="+uids[i];
	}


	if(foreign_uids) {
		index=1;
		f_uids=foreign_uids.split(",");

		for (var i = 0; i < f_uids.length; i++) {
			urlpart+="&foreign_uids["+i+"]="+f_uids[i];
		}
	}


	if(confirm("Möchten Sie die Element(e) wirklich löschen?")) {
		if(popup) {
			var frame=top.opener.top.actions;
			var content=top.opener.top.content;
			var actionFile=top.opener.top.actions.location.pathname;
		} else {
			var frame=top.actions;
			var content=top.content;
			var actionFile=top.actions.location.pathname;
		}
		var url=actionFile+'?action=delete&table='+tableName+urlpart;
        
		frame.location.href=url;
		setTimeout("content.location.reload();",200);
	}
}

function sync(uid) {
	var popup=top.actions?false:true;
	if(popup) {
		var frame=top.opener.top.actions;
		var actionFile=top.opener.top.actions.location.pathname;
	} else {
		var frame=top.actions;
		var actionFile=top.actions.location.pathname;
	}
	var url=actionFile+'?action=sync&uid='+uid;
	frame.location.href=url;
}

function action(action,params) {
	var popup=top.content?false:true;

	if(popup) {
		var frame=top.opener.top.actions;
		var content=top.opener.top.content;
		var actionFile=top.opener.top.actions.location.pathname;
	} else {
		var frame=top.actions;
		var content=top.content;
		var actionFile=top.actions.location.pathname;
	}
	var url=actionFile+'?action='+action+params;
	frame.location.href=url;
	content.location.reload();
}

var MESSAGE_INFO=1;
var MESSAGE_WARNING=2;
var MESSAGE_ERROR=3;

function raiseMessage(type,text,number,file,line) {
	if(top.message) {
		var messageFile=top.message.location.pathname;
	} else {
		var messageFile=top.opener.top.message.location.pathname;
	}
	url=messageFile+"?action=add&type="+type+"&text="+text+"&number="+number+"&file="+file+"&line="+line;
	top.message.location.href=url;
	//top.message.addMessage(type,text,number,file,line);
}

function openPopup(url,title,width,height) {
	popup = window.open(url,"POPUP", "width="+width+",height="+height+",left="+(screen.width/2-width/2)+",top="+(screen.height/2-height/2)+",resizable=0");
	popup.focus();
}
          
var activeTab = new Array();
var siteCheckHash = new Array();