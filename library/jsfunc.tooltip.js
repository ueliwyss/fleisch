// JavaScript Document
document.write("<div id='alertManager' style='font-family:verdana;font-size:10px;color:#000000;'></div>");

 	var ns4 = document.layers;
         var op5 = (navigator.userAgent.indexOf("Opera 5")!=-1) ||(navigator.userAgent.indexOf("Opera/5")!=-1);
         var op6 = (navigator.userAgent.indexOf("Opera 6")!=-1) ||(navigator.userAgent.indexOf("Opera/6")!=-1);
         var agt=navigator.userAgent.toLowerCase();
         var mac = (agt.indexOf("mac")!=-1);
         var ie = (agt.indexOf("msie") != -1);
         var mac_ie = mac && ie;



var aktiv;

function Message(id,caption,text,pointer,delay) {
		var x_pos;
		var y_pos;
		 var width;
		 var height;
		 var bgcolor_top = "#FFFFFF";
		 var bgcolor_bottom = "#78ACE6";

	window.clearTimeout(aktiv);


	if(!document.getElementById(id)) {
		document.getElementById('alertManager').innerHTML = "<div id='"+id+"' style='visibility:visible;position:absolute;background-color:#FFFFFF;border:1px solid #3a5f7b;padding:5px;'><b>"+caption+"</b><br>"+text+"</div>";
	}
			 width = getElementWidth(id);
			 height = getElementHeight(id);
	document.getElementById(id).style.visibility = 'hidden';

	window.setTimeout("if(document.getElementById('"+id+"')) {samePosition('"+pointer+"','"+id+"');document.getElementById('"+id+"').style.visibility = 'visible';}",1);


	if(delay!=0) {
		aktiv = window.setTimeout("hideElement("+id+")",delay);
	}
}

function hideElement(id) {
	if(document.getElementById(id)) {
		moveXY(document.getElementById(id),0,-200);
		document.getElementById(id).style.visibility = 'hidden';
	}
}

function samePosition(pointerID,elementID) {

	moveXY(document.getElementById(elementID),getPosition(document.getElementById(pointerID)).x+getElementWidth(pointerID)+10,getPosition(document.getElementById(pointerID)).y);
}

function getPosition(element)
/* der Aufruf dieser Funktion ermittelt die absoluten Koordinaten
   des Objekts element */
{
  var elem=element,tagname="",x=0,y=0;

/* solange elem ein Objekt ist und die Eigenschaft offsetTop enthaelt
   wird diese Schleife fuer das Element und all seine Offset-Eltern ausgefuehrt */

  while ((typeof(elem)=="object")&&(typeof(elem.tagName)!="undefined"))
  {
    y+=elem.offsetTop;     /* Offset des jeweiligen Elements addieren */
    x+=elem.offsetLeft;    /* Offset des jeweiligen Elements addieren */
    tagname=elem.tagName.toUpperCase(); /* tag-Name ermitteln, Grossbuchstaben */

/* wenn beim Body-tag angekommen elem fuer Abbruch auf 0 setzen */
    if ((tagname=="BODY")||(elem.id=="alertManager")) {
      elem=0;
	}
/* wenn elem ein Objekt ist und offsetParent enthaelt
   Offset-Elternelement ermitteln */
    if (typeof(elem)=="object"){
      if (typeof(elem.offsetParent)=="object"){
        elem=elem.offsetParent;
	  }
	}
  }

/* Objekt mit x und y zurueckgeben */
  position=new Object();
  position.x=x;
  position.y=y;
  return position;
}

function alertPosition(elementId)
/* gibt eine Meldung mit x und y des zu elementId gehoerenden Elements aus */
{
  var a,element;

/* Element-Objekt zur ID ermitteln */
  element=document.getElementById(elementId);

/* Position bestimmen und melden */
  a=getPosition(element);
  window.alert("Position "+elementId+": ("+a.x+","+a.y+")");
}

function getElementHeight(Elem) {
	if (ns4) {
		var elem = getObjNN4(document, Elem);
		return elem.clip.height;
	} else {
		if(document.getElementById) {
			var elem = document.getElementById(Elem);
		} else if (document.all){
			var elem = document.all[Elem];
		}
		if (op5) {
			xPos = elem.style.pixelHeight;
		} else {
			xPos = elem.offsetHeight;
		}
		return xPos;
	}
}

function getElementWidth(Elem) {
	if (ns4) {
		var elem = getObjNN4(document, Elem);
		return elem.clip.width;
	} else {
		if(document.getElementById) {
			var elem = document.getElementById(Elem);
		} else if (document.all){
			var elem = document.all[Elem];
		}
		if (op5) {
			xPos = elem.style.pixelWidth;
		} else {
			xPos = elem.offsetWidth;
		}
		return xPos;
	}
}

function getObjNN4(obj,name)
{
	var x = obj.layers;
	var foundLayer;
	for (var i=0;i<x.length;i++)
	{
		if (x[i].id == name)
		 	foundLayer = x[i];
		else if (x[i].layers.length)
			var tmp = getObjNN4(x[i],name);
		if (tmp) foundLayer = tmp;
	}
	return foundLayer;
}

function moveXY(myObject, x, y) {
	obj = getStyleObject(myObject);
	if (ns4) {
		obj.top = y;
 		obj.left = x;
	} else {
		if (op5) {
			obj.pixelTop = y;
 			obj.pixelLeft = x;
		} else {
			myObject.style.top = y + 'px';
 			myObject.style.left = x + 'px';
		}
	}
}

function getStyleObject(objectId) {
	if(document.getElementById && document.getElementById(objectId)) {
		return document.getElementById(objectId).style;
	} else if (document.all && document.all(objectId)) {
		return document.all(objectId).style;
	} else if (document.layers && document.layers[objectId]) {
		return getObjNN4(document,objectId);
	} else {
		return false;
	}
}
function EmptyForm(Objekt,Wert) {
	if(eval("document.all."+Objekt+".value") == Wert) {
		eval ("document.all."+Objekt+".value = ''");
         }
}

function openWindow(file,width,height,resizable) {
	if(width == 'full') {
         	width = screen.availWidth-20;
         }
         if(height == 'full') {
         	height = screen.availHeight-51;
         }
         F1 = window.open(file,'upload','width='+width+',height='+height+',dependent=yes,scrollbars=yes,resizable='+resizable);
         F1.focus();
}

function positionUnder(pointerID,elementID) {

	moveXY(document.getElementById(elementID),getPosition(document.getElementById(pointerID)).x,getPosition(document.getElementById(pointerID)).y+getElementHeight(pointerID));
}


