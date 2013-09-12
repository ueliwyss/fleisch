<?
/**
 * Tabmenu
 *
 * @author Ueli Wyss
 * @package IPA-Vorbereitung
 */

include_once(LIB_PATH.'class.tab.php');

class tabMenu extends tab {
	public $target;

	function tabMenu() {
		$this->setDefaults();
	}

	function setDefaults() {
		$this->CSS='
body {
	font-family:tahoma;
	font-size:9px;
	background-color:#417fc6;
}

a{
	font-size:11px;
	font-weight:bold;
	color:#FFFFFF;
	text-decoration:none;
}

a:hover{
	font-size:11px;
	font-weight:bold;
	color:#ff9000;
	text-decoration:underline;
}

.tab {
	width:100%;
}

.tab_header {
	height:25px !important;
	width:100%;
}

.tab_header_leftActive {
	/*
	background-image:url(images/tab_header_leftActive.gif);
	height:32px !important;
	*/
	background-color:#FFFFFF;
	font-size:1px;
	width:15px !important;
	border-left:2px solid #46b0ee;
	border-top:2px solid #46b0ee;

}

.tab_header_leftInactive {
	/*
	background-image:url(images/tab_header_leftInactive.gif);
	background-repeat:no-repeat;
	height:32px !important;
	*/
	background-color:#FFFFFF;
	font-size:1px;
	width:15px !important;
	cursor:pointer;
	border-left:1px solid #46b0ee;
	border-top:1px solid #46b0ee;
	border-bottom:2px solid #46b0ee;
}

.tab_header_midActive {
	/*
	background-image:url(images/tab_header_midActive.gif);
	background-repeat:repeat-x;
	height:32px !important;
	*/
	width:17px !important;
	background-color:#FFFFFF;
	color:#ff9000;
	font-size:11px;
	font-weight:bold;
	vertical-align:middle;
	padding-left:0px;
	padding-right:0px;
	border-top:2px solid #46b0ee;
}

.tab_header_midInactive {
	/*
	background-image:url(images/tab_header_midInactive.gif);
	background-repeat:repeat-x;
	height:32px !important;
	*/
	background-color:#FFFFFF;
	width:10px;
	color:#46b0ee;
	font-size:10px;
	font-weight:bold;
	padding-left:5px;
	padding-right:5px;
	vertical-align:middle;
	cursor:pointer;
	border-bottom:2px solid #46b0ee;
	border-top:1px solid #46b0ee;
}

.tab_header_rightActive {
	background-color:#FFFFFF;
	border-right:2px solid #46b0ee;
	border-top:2px solid #46b0ee;
	/*
	background-image:url(images/tab_header_rightActive.gif);
	background-repeat:no-repeat;
	height:32px !important;
	*/
	font-size:1px;
	width:15px !important;
	cursor:pointer;

}

.tab_header_rightInactive {
	/*
	background-image:url(images/tab_header_rightInactive.gif);
	background-repeat:no-repeat;
	height:32px !important;
	*/
	background-color:#FFFFFF;
	font-size:1px;
	width:15px !important;
	cursor:pointer;
	border-right:1px solid #46b0ee;
	border-top:1px solid #46b0ee;
	border-bottom:2px solid #46b0ee;

}

.tab_header_space {
	border-bottom:2px solid #46b0ee;
	font-size:1px;
	width:5px;
}

.tab_header_firstSpace {
	border-bottom:2px solid #46b0ee;
	font-size:1px;
	width:290px;
	background-image:url(images/logo.gif);
	background-repeat:no-repeat;

}

.tab_header_fullSpace {
	text-align:right;
	border-bottom:2px solid #46b0ee;
	font-size:11px;
}

.tab_content {
	padding:20px;
	background-color:#FFFFFF;
	border-right:1px solid black;
	border-bottom:2px solid #46b0ee;
	border-left:2px solid #46b0ee;
	border-right:2px solid #46b0ee;
	width:100%;
}

.submenu_link {
	color:#46b0ee;
	font-size:11px;
	font-weight:bold;
	text-decoration:none;

}

.submenu_link:hover {
	color:#ff9000;
}';
		$this->JS="
function tab_switch(numTab,tab) {
	var old_id = eval('tab_active_'+numTab);
	if(tab!=old_id) {
		var header_left_old = document.getElementById('tab_header_left_'+numTab+'_'+old_id);
		var header_mid_old = document.getElementById('tab_header_mid_'+numTab+'_'+old_id);
		var header_right_old = document.getElementById('tab_header_right_'+numTab+'_'+old_id);
		var content_old = document.getElementById('tab_content_'+numTab+'_'+old_id);

		var header_left_new = document.getElementById('tab_header_left_'+numTab+'_'+tab);
		var header_mid_new = document.getElementById('tab_header_mid_'+numTab+'_'+tab);
		var header_right_new = document.getElementById('tab_header_right_'+numTab+'_'+tab);
		var content_new = document.getElementById('tab_content_'+numTab+'_'+tab);

		header_left_old.className='tab_header_leftInactive';
		header_left_new.className='tab_header_leftActive';

		header_mid_old.className='tab_header_midInactive';
		header_mid_new.className='tab_header_midActive';

		header_right_old.className='tab_header_rightInactive';
		header_right_new.className='tab_header_rightActive';

		content_old.style.display='none';
		content_new.style.display='block';

		eval('tab_active_'+numTab+' = '+tab+';');
	}
}";
	}

	public function addElement($item,$index="end") {
		$item->target=$this->target;
		if($item->active && $this->getActive()!=false) {
			$this->items[$this->getActive()]->active=false;
		}
		if(!$this->getActive()) {
			$item->active=true;
		}
		if($index == "begin") {
			$index=0;
		} elseif($index == "end" || $index > count($this->items)) {
			if(is_array($this->items)) {
				$index=count($this->items);
			} else {
				$index=0;
			}
		}
		if(count($this->items)!=0) {
			$i=count($this->items)-1;
			while($i>=$index) {
				$this->items[$i+1]=$this->items[$i];
				$i--;
			}
		}
		$this->items[$index]=$item;
		return true;
	}
}

class tabMenuItem extends container{
	public $title;
	public $active;
	public $target;
	public $separator="<font color='#46b0ee'><b>&nbsp;&nbsp;||&nbsp;&nbsp;</b></font>";

	function tabMenuItem($title,$active=false) {
		$this->title=$title;
		$this->active=$active;
	}

	function wrapContent() {
		$content=array();
		for($i=0;$i<count($this->items);$i++) {
			div::htm_mergeSiteContent($content,$this->items[$i]->wrapContent());
			if($i!=(count($this->items)-1)) {
				$content['main'].=$this->separator;
			}
		}
		return $content;
	}

	public function addElement($item,$index="end") {
		$item->target=$this->target;
		if($item->active && $this->getActive()!=false) {
			$this->items[$this->getActive()]->active=false;
		}
		if(!$this->getActive()) {
			$item->active=true;
		}
		if($index == "begin") {
			$index=0;
		} elseif($index == "end" || $index > count($this->items)) {
			if(is_array($this->items)) {
				$index=count($this->items);
			} else {
				$index=0;
			}
		}
		if(count($this->items)!=0) {
			$i=count($this->items)-1;
			while($i>=$index) {
				$this->items[$i+1]=$this->items[$i];
				$i--;
			}
		}
		$this->items[$index]=$item;
		return true;
	}

	public function removeElement($index) {
		if($this->items[$index]->active) {
			$this->items[0]->active=true;
		}
		if(!$index>(count($this->items)-1)) {
			if($index == "last") { $index = $this->items[count($this->items)-1]; }
			elseif($index == "first") { $index = 0; }

			$i=$index;
			while($i>$index) {
				$this->items[$i]=$this->items[$i+1];
				$i++;
			}
			unset($this->items[count($this->items)-1]);
			return true;
		} else {
			return false;
		}
	}

	public function getActive() {
		$val=false;
		if(is_array($this->items)) {
			foreach($this->items as $index=>$item) {
				if($item->active) {
					$val=$index;
				}
			}
		}
		return $val;
	}
}

class tabSubMenuItem extends tabMenuItem {
	public $url;

	function tabSubMenuItem($title,$url,$active=false) {
		$this->title=$title;
		$this->url=$url;
		$this->active=$active;
	}

	function wrapContent() {
		$content=array();
		if(is_array($this->items)) {
			foreach($this->items as $item) {
				div::htm_mergeSiteContent($content,$item->wrapContent());
			}
		} else {
			$content['main'].='<a class="submenu_link" target="'.$this->target.'" href="'.$this->url.'">'.$this->title.'</a>';
		}
		return $content;
	}
}