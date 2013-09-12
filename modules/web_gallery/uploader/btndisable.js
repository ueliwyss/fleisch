function replaceStr(dastring,str1,str2) {
	start = 0;
	newStr = "";
	while ((index1=dastring.indexOf(str1,start)) != -1 && start<dastring.length) {
		newStr += dastring.substring(start,index1);
		newStr += str2;
		start = index1+str1.length;
	}
	newStr += dastring.substring(start,dastring.length);
	return newStr;
}
enableHTML = new Array();
function enable(btnid,enableIndex) {
	document.getElementById(btnid).innerHTML = enableHTML[enableIndex];
}
function disable(btnid,label,btnlink) {
	enableHTML[enableHTML.length] = document.getElementById(btnid).innerHTML;
	document.getElementById(btnid).innerHTML = label;
	setTimeout("enable('"+btnid+"',"+(enableHTML.length-1)+")",5000);
	document.location.href = btnlink;
}
