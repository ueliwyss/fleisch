alphaArray = new Array();
alphaArray[13] = 'ENTER';
alphaArray[35] = 'END';
alphaArray[65] = 'A';
alphaArray[66] = 'B';
alphaArray[67] = 'C';
alphaArray[68] = 'D';
alphaArray[69] = 'E';
alphaArray[70] = 'F';
alphaArray[71] = 'G';
alphaArray[72] = 'H';
alphaArray[73] = 'I';
alphaArray[74] = 'J';
alphaArray[75] = 'K';
alphaArray[76] = 'L';
alphaArray[77] = 'M';
alphaArray[78] = 'N';
alphaArray[79] = 'O';
alphaArray[80] = 'P';
alphaArray[81] = 'Q';
alphaArray[82] = 'R';
alphaArray[83] = 'S';
alphaArray[84] = 'T';
alphaArray[85] = 'U';
alphaArray[86] = 'V';
alphaArray[87] = 'W';
alphaArray[88] = 'X';
alphaArray[89] = 'Y';
alphaArray[90] = 'Z';

shortcuts = new Array();
urls = new Array();

var SHIFT_KEY = 16;
var CTRL_KEY = 17;
var ENTER_KEY = 13;
var SHIFT_DOWN = false;
var CTRL_DOWN = false;
 
function doUp(keyUp) {
	if (keyUp == SHIFT_KEY) {
		SHIFT_DOWN = false;
	}
	if (keyUp == CTRL_KEY) {
		CTRL_DOWN = false;
	}
}
function doDown(keyDown) {
	if (keyDown == SHIFT_KEY) {
		SHIFT_DOWN = true;
	}
	else if (keyDown == CTRL_KEY) {
		CTRL_DOWN = true;
	}
	else if (SHIFT_DOWN && CTRL_DOWN) {
		for (i=0;i<shortcuts.length;i++)
			if (shortcuts[i]==alphaArray[keyDown]) {
				document.location.href=urls[i];
				break;
			}
	}
	else if (keyDown == ENTER_KEY) {
		for (i=0;i<shortcuts.length;i++)
			if (shortcuts[i]=='ENTERSUBMIT') {
				document.location.href=urls[i];
				break;
			}
	}
}