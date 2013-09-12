function chkall(form)
	{
	len = document.forms[form].elements.length;
	var i=0;
	while(i!=len)
		{
		if (document.forms[form].elements[i].type=='checkbox') 
  	         if(document.forms[form].elements[i].name!="")
		   document.forms[form].elements[i].checked = !document.forms[form].elements[i].checked;
		i++;
		}
	}

submited = false;
//javascript:formsub('searchrss','-1','-1','33','3','-1');
function formsub(form,field,fieldvalue,action,behaviour,target)
	{
	if (!submited) {
		
	openinpopup = (behaviour>3);
	if (openinpopup)
		behaviour -= 4;
		
	if (typeof(document.forms[form].act)!='undefined')
		document.forms[form].act.value = action;
	
	if(behaviour==3)
		{
		if (typeof(localcheck) != 'undefined')
			{
			validlocal=localcheck(action);
			if (!validlocal)
				return;
			}
		}
	if (behaviour == 1 || behaviour == 2)
		{
		checkboxItems = document.forms[form].chkitem;
		
		if (typeof(localcheck) != 'undefined')
			{
			validlocal=localcheck(action,checkboxItems)
			if (!validlocal)
				return;
			}
		nbchecked = 0;
		lastCheckedItem = -1;
		
		if (typeof(checkboxItems)!='undefined')
			{
			//if only one checked
			if (typeof(checkboxItems.length) == 'undefined')
				{
				if (checkboxItems.checked)
					{
					nbchecked = 1;
					lastCheckedItem = checkboxItems.value;
					}
				}
			//multiple selection
			else
				{
				for (i = 0 ; i<checkboxItems.length ; i++)
					{
					if (checkboxItems[i].checked)
						{
						nbchecked++;
						lastCheckedItem = checkboxItems[i].value;
						}
					}
				}
			}
		if (nbchecked == 0)
			{
			window.alert(document.getElementById("nochk"+action).value);
			return;
			}
		else if (behaviour == 1 && nbchecked > 1)
			{
			window.alert(document.getElementById("tmchk"+action).value);
			return;
			}
		}
	
	if (target != -1)
		{
		var oldTarget=document.forms[form].action;
		document.forms[form].action = target;
		}
	if (openinpopup)
		{
		pleasewait = '';
		waitinput = document.getElementById("pleasewait");
		if (waitinput!=null)
			pleasewait = waitinput.value;
		popupwidth = parseInt(field);
		popupheight= parseInt(fieldvalue);
		waitdivpos = fieldvalue-350;
		if (waitdivpos<0) waitdivpos = 0;
		var newwin = window.open('about:blank', form+action, 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width='+popupwidth+',height='+popupheight+',left = 362,top = 234');
		newwin.document.write('<html><head><style>h1{font:18px "trebuchet ms",tahoma,Geneva,Arial,Helvetica,sans-serif;color:#4f83df}</style><link media="all" type="text/css" href="/css/lycos/content.css" rel="stylesheet"><title>'+pleasewait+'...</title></head><body><div id="wait" style="margin-top:'+waitdivpos+'px;text-align:center"><h1>'+pleasewait+'</h1><div class="bluebox" style="float:none;margin:0 50px 0 50px;clear:both"><div class="corners"><span class="topleft"></span><span class="topright"></span></div><div class="content" style="text-align:center"><p><img src="/img/lycos/cont/loading.gif"/></p></div><div class="corners"><span class="bottomleft"></span><span class="bottomright"></span></div></div></body></html>');
		document.forms[form].target=form+action;
		}
	else if (field !=-1 && fieldvalue != -1)
		{
		if(isNaN(field))
			document.forms[form].elements[field].value = fieldvalue;
		}
	
	confMsg = document.getElementById("conf"+action);
	if (confMsg==null || confirm(confMsg.value))
		{
		submited = true;
		setTimeout("setUnSubmited()",5000);
		document.forms[form].submit();
		}
	
	if (target != -1)
		{
		document.forms[form].action = oldTarget;
		if (openinpopup)
			{
			submited = false;
			document.forms[form].target='';
			}
		}
	}
	}

function setUnSubmited()
	{
	submited = false;
	}