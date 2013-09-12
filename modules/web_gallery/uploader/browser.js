function Browser() {
	var ua,	s, i;
	this.isIE = false;
	this.isNS = false;
	this.isOP = false;
	this.version = null;
}
var browser = new Browser();

function BrowserDetection() {
	var agt = navigator.userAgent.toLowerCase();
	var is_major = parseInt(navigator.appVersion);
	var is_minor = parseFloat(navigator.appVersion);
	var is_nav  = ((agt.indexOf('mozilla')!=-1) && (agt.indexOf('spoofer')==-1) && (agt.indexOf('compatible') == -1) && (agt.indexOf('opera')==-1) && (agt.indexOf('webtv')==-1) && (agt.indexOf('hotjava')==-1));
	if(is_nav == true && agt.indexOf('gecko') != -1) {
		var s = "";
		if(agt.indexOf('netscape') != -1) {
			BrowserName = 'Netscape';
			s = agt.substring(agt.indexOf('netscape')+8, agt.length);
			BrowserVersion = s.substring(s.indexOf('/')+1, s.indexOf(' '));
			browser.isNS = true;
		}
		else {
			BrowserName = 'Mozilla'
			BrowserVersion = agt.substring(agt.indexOf('rv:')+3, agt.lastIndexOf(')'));
			browser.isNS = true;
		}
		s = agt.substring(agt.indexOf("windows "), agt.length);
		OSName = s.substring(0, s.indexOf(";"));
	}
	else {
		if(agt.indexOf('opera') != -1) {
			BrowserName = 'Opera'
			var s = agt.substring(agt.indexOf('opera')+6, agt.length);
			BrowserVersion = s.substring(0, s.indexOf(' '));
			s = agt.substring(agt.indexOf("windows "), agt.length);
			OSName = s.substring(0, s.indexOf(")"));
			browser.isOP = true;
		}
		else {
			if(agt.indexOf("msie") != -1) {
				var s = agt.substring(agt.indexOf("msie"), agt.length);
				BrowserName = "MSIE";
				BrowserVersion = s.substring(s.indexOf(" ")+1, s.indexOf(";"));
				OSName = agt.substring(agt.indexOf("windows "), agt.indexOf(")"));
				browser.isIE = true;
			}
		}
	}
}

BrowserDetection();
/*alert(BrowserName + ' - ' + parseFloat(BrowserVersion))*/