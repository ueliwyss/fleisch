var logindrp = 0;
var btndrps = new Array();
var drps = new Array();
var boxes = new Array();
var setblock = new Array();
//
var toggleOpen = 0;
var toggleDivType = '';
var toggleDivId = -1;


function getElementsByClassName(tagname,classname) {
	if (!document.getElementById){return false;}
	var TagElements = document.getElementsByTagName(tagname);
	var elementsByClassName = new Array();
	for (var i=0; i<TagElements.length; i++)
	{if (TagElements[i].className == classname){elementsByClassName[elementsByClassName.length] = TagElements[i];}}
	return elementsByClassName;
}
function pretoggle() {
	var iptTmp =null;
	for (var i=0;i<(getElementsByClassName('div','btndrp')).length;i++ ) {	
		iptTmp=document.getElementById('subbtndrp'+i);
		if( iptTmp != null && typeof(iptTmp) != 'undefined' ) {
			btndrps[i] = new Array("btndrp"+i,"subbtndrp"+i,0);
			if ( iptTmp.style.display=='block' ) 
				btndrps[i][2] = 1;
		}
		
	}
	for (var i=0;i<(getElementsByClassName('div','drp')).length;i++ ) {
		iptTmp=document.getElementById('subdrp'+i);
		if( iptTmp != null && typeof(iptTmp) != 'undefined' ) {	
			drps[i] = new Array("drp"+i,"subdrp"+i,0);
			if ( iptTmp.style.display=='block' ) {
				drps[i][2] = 1;
			}
		}
	}
	for (var i=0;i<(getElementsByClassName('div','boxblock')).length;i++ ) {	
		boxes[i] = new Array("boxblock"+i,1);
		if ( document.getElementById('boxblock'+i).style.display=='none' ) {
			boxes[i][1] = 0;
		}
	}
	for (var i=0;i<(getElementsByClassName('div','setblock')).length;i++ ) {	
		setblock[i] = new Array("setblock"+i,1);
		if ( document.getElementById('setblock'+i).style.display=='none' ) {
			setblock[i][1] = 0;
		}
	}
}
function toggle(divtype,divid) {
	toggleManage(divtype,divid,1);
}
function toggleManage(divtype,divid,manageClose)
{
	// we manage close drop button here
	if( manageClose > 0 ) 
	{
		if( toggleDivType != divtype || toggleDivId != divid ) {
			if( manageClose!=2 )	toggleOpen=1;
			toggleManage(toggleDivType,toggleDivId,0);
			toggleDivType=divtype;
			toggleDivId=divid;
		} else {
			// already open
			if( manageClose == 2 ){
				toggleOpen=0;
				return;
			}
			
			toggleDivType='';
			toggleDivId=-1;
		}
	}
				
	
	if (divtype=='btndrp' || divtype=='btndrplight' )
	{
		// if button not already load
	 	if( btndrps.length <= divid ) {
	 		if( typeof(document.getElementById('subbtndrp'+divid)) == 'undefined' )
	 			return;
	 		else {
		 		btndrps[divid] = new Array("btndrp"+divid,"subbtndrp"+divid,0);
				if ( document.getElementById('subbtndrp'+divid).style.display=='block' ) 
					btndrps[divid][2] = 1;
			}
	 	}
		
		var subid = "subbtndrp" + divid;var Rid = "btndrpR" + divid;
		var imageOn = 'url(/img/lycos/btndrp/btnRon.gif)';
		var imageOff = 'url(/img/lycos/btndrp/btnR.gif)';
		
		if( divtype=='btndrplight' ) {
			imageOn = 'url(/img/lycos/btndrp/arrowOn.gif) no-repeat';
			imageOff = 'url(/img/lycos/btndrp/arrowOff.gif) no-repeat';
		}
			
		if (btndrps[divid][2]==0) {
			btndrps[divid][2] = 1;document.getElementById(subid).style.display='block';
			document.getElementById(Rid).style.background=imageOn;
		}
		else {
			btndrps[divid][2] = 0;document.getElementById(subid).style.display='none';
			document.getElementById(Rid).style.background=imageOff;
		}
	}
	if (divtype=='drp') {
		
		// if not already load
	 	if( drps.length <= divid ) {
	 		if( typeof(document.getElementById('subdrp'+divid)) == 'undefined' )
	 			return;
	 		else {
		 		drps[divid] = new Array("drp"+divid,"subdrp"+divid,0);
				if ( document.getElementById('subdrp'+divid).style.display=='block' ) 
					drps[divid][2] = 1;
			}
	 	}
		
		var subid = "subdrp" + divid;var Rid = "drpR" + divid;
		if (drps[divid][2]==0) {
			drps[divid][2] = 1;document.getElementById(subid).style.display='block';
			document.getElementById(Rid).style.background='url(/img/lycos/drp/drpRon.gif)';
		}
		else {
			drps[divid][2] = 0;document.getElementById(subid).style.display='none';
			document.getElementById(Rid).style.background='url(/img/lycos/drp/drpR.gif)';
		}
	}
	if (divtype=='boxblock') {
		var boxid = "boxblock" + divid;var boxRid = 'boxtopR' + divid;
		if (boxes[divid][1]==0) {
			boxes[divid][1] = 1;document.getElementById(boxid).style.display='block';
			document.getElementById(boxRid).style.background='url(/img/lycos/box/topRopen.gif)';
		}
		else {
			boxes[divid][1] = 0;document.getElementById(boxid).style.display='none';
			document.getElementById(boxRid).style.background='url(/img/lycos/box/topRclose.gif)';
		}
	}
	if (divtype=='setblock') {
		var setid = "setblock" + divid;
		for (var i=0;i<setblock.length;i++ ) {
			setblock[i][1] = 0;document.getElementById("setblock"+i).style.display='none';
			document.getElementById("tab"+i).style.background='url(/img/lycos/settings/itab.gif)';
		}
		setblock[divid][1] = 1;
		document.getElementById(setid).style.display='block';
		document.getElementById("tab"+divid).style.background='url(/img/lycos/settings/atab.gif)';
		for (i=0;i<setblock.length;i++) {
			document.getElementById("tab"+i).style.fontWeight='100';
		}
		document.getElementById("tab"+divid).style.fontWeight='bold';
	}
}
function openToggle(divtype,divid){
	toggleManage(divtype,divid,2);
}
function closeToggle(force){
	
	if( force != 1 && toggleOpen == 1 )
		toggleOpen=0;
	else 	toggle(toggleDivType,toggleDivId);
}
function togglelogin() {
	if (logindrp == 0) {
		logindrp = 1;
		document.getElementById("subdrplogin").style.display='block';
		document.getElementById("drpRlogin").style.background='url(/img/lycos/drp/drpRon.gif)';
	}
	else {
		logindrp = 0;
		document.getElementById("subdrplogin").style.display='none';
		document.getElementById("drpRlogin").style.background='url(/img/lycos/drp/drpR.gif)';
	}
}