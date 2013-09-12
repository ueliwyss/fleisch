var popupwin;
var itsux = (navigator.appName.indexOf("Microsoft")!=-1);

function popup(url,title,width,height)
{
	var params = "width="+width+",height="+height+",status=yes,scrollbars=no";
	//'toolbar=1, location=0, directories=0, status=1, scrollbars=0, resizable=1, copyhistory=0, menuBar=0'
	var isPreview = (url.indexOf("preview")!=-1);
	if ( (isPreview) && (title.indexOf("rssPreview")==-1) ) {
		params += ",resizable=yes";
	}
	else {
			params += ",resizable=no";
	}	
	if (isPreview && itsux) {
		pleasewait = '';
		waitinput = document.getElementById("pleasewait");
		if (waitinput!=null)
			pleasewait = waitinput.value;
		popupwin = window.open('about:blank','_blank',params);
		popupwin.document.write('<html><head><style>h1{font:18px "trebuchet ms",tahoma,Geneva,Arial,Helvetica,sans-serif;color:#4f83df}</style></head><body><a id="mylink" href="'+url+'"></a><div id="wait" style="margin-top:100px;text-align:center"><h1>'+pleasewait+'...</h1><div class="bluebox" style="float:none;margin:0 50px 0 50px;clear:both"><div class="corners"><span class="topleft"></span><span class="topright"></span></div><div class="content" style="text-align:center"><p><img src="/img/lycos/cont/loading.gif"/></p></div><div class="corners"><span class="bottomleft"></span><span class="bottomright"></span></div></div><scr'+'ipt>setTimeout(\'document.getElementById("mylink").click()\',1000);</scr'+'ipt></body></html>');
	}
	else {
		params += ",resizable=no";
		if(!popupwin || popupwin.closed) {
			popupwin = window.open(url,title,params);
			popupwin.opener = self; 
		}
		else {
			popupwin.location.replace(url);
		}
	}
	popupwin.focus();
}
function popupscroll(url,title,width,height)
{
	var params = "width="+width+",height="+height+",resizable=no,status=no,scrollbars=yes";
	if(!popupwin || popupwin.closed) {
		popupwin = window.open(url,title,params);
	}
	else {
		popupwin.location.replace(url);
	}
	popupwin.focus();
}
function popupscrollresize(url,title,width,height)
{
	var params = "width="+width+",height="+height+",resizable=yes,status=yes,scrollbars=yes,toolbar=yes,location=yes,menubar=yes";
	if(!popupwin || popupwin.closed) {
		popupwin = window.open(url,title,params);
	}
	else {
		popupwin.location.replace(url);
	}
	popupwin.focus();
}

function printSettings()
{
	var loc = location.href;
	if(loc.indexOf("?") == -1){
		loc = loc +"?"
	}else{
		loc = loc +"&"
	}
	window.open(loc+'print=yes','_blank','height=500,width=440,scrollbars=yes,resizable=yes');
}
