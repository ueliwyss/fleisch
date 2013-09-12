<?
class treeView extends container {

	private $icons;

	static $instances=0;

	public function treeView() {
		$this->setDefaults();
	}

	private function setDefaults() {
		$this->icons=array(
			'win'=>array(
				'plusTop'=>ICON_DIR.'treeview_win_plusTop.gif',
				'plusMid'=>ICON_DIR.'treeview_win_plusMid.gif',
				'plusBottom'=>ICON_DIR.'treeview_win_plusBottom.gif',
				'minusTop'=>ICON_DIR.'treeview_win_minusTop.gif',
				'minusMid'=>ICON_DIR.'treeview_win_minusMid.gif',
				'minusBottom'=>ICON_DIR.'treeview_win_minusBottom.gif',
				'lineThru'=>ICON_DIR.'treeview_win_lineThru.gif',
				'lineItemTop'=>ICON_DIR.'treeview_win_lineItemTop.gif',
				'lineItemThru'=>ICON_DIR.'treeview_win_lineItemThru.gif',
				'lineItemBottom'=>ICON_DIR.'treeview_win_lineItemBottom.gif',
			),
			'dom'=>array(
				'plusTop'=>ICON_DIR.'treeview_dom_plusTop.gif',
				'plusMid'=>ICON_DIR.'treeview_dom_plusMid.gif',
				'plusBottom'=>ICON_DIR.'treeview_dom_plusBottom.gif',
				'minusTop'=>ICON_DIR.'treeview_dom_minusTop.gif',
				'minusMid'=>ICON_DIR.'treeview_dom_minusMid.gif',
				'minusBottom'=>ICON_DIR.'treeview_dom_minusBottom.gif',
				'lineThru'=>ICON_DIR.'treeview_dom_lineThru.gif',
				'lineItemTop'=>ICON_DIR.'treeview_dom_lineItemTop.gif',
				'lineItemThru'=>ICON_DIR.'treeview_dom_lineItemThru.gif',
				'lineItemBottom'=>ICON_DIR.'treeview_dom_lineItemBottom.gif',
			),
		);
	}

	private function getJS() {
		return '
var '
	}

	public function wrapContent() {

		self::$instances++;
	}

	public static function getInstances() {
		if(self::$instances==0) {
			return '0';
		} else {
			return self::$instances;
		}
	}
}

class treeViewItem extends container {
	const STATE_OPEN=1;
	const STATE_CLOSED=2;

	public $icon;
	public $text;
	public $onClick;
	public $state;
	public $uid;
	public $itemConfig;
	public $parentItem;

	private function fetchSubItems() {

	}
}

class treeViewItemConfig extends container {
	const MODE_SQL=1;
	const MODE_CUSTOM=2;

	public $state;
	public $level;
	public $parent_element;
	public $icon;
	public $subConfigItems;

	private $requestURL;
	private $mode;
	private $querys;
	private $isDeepestLevel;

	public function treeViewItemConfig() {
		$this->setDefaults();
	}

	public function setDefaults() {
		$this->state=treeViewItem::STATE_CLOSED;
	}

	public function setConfigForSQL($querys) {
		$this->mode=self::MODE_SQL;
		$this->querys=$querys;
	}

	public function setConfigForCustom($requestURL) {
		$this->mode=self::MODE_CUSTOM;
		$this->requestURL=$requestURL;
	}

	function addSubConfig($subConfig, $index="end") {
		//$item->requiredBold=$this->requiredBold;
		$item->parent_element=&$this;

		if($index == "begin") {
			$index=0;
		} elseif($index == "end" || $index > count($this->subConfigItems)) {
			$index=count($this->subConfigItems);
		}
		if(count($this->subConfigItems)!=0) {
			$i=count($this->subConfigItems)-1;
			while($i>=$index) {
				$this->subConfigItems[$i+1]=$this->subConfigItems[$i];
				$i--;
			}
		}
		$this->subConfigItems[$index]=$item;
	}

	public function hasParentElement() {
		return $this->parent_element instanceof treeViewItemConfig;
	}

	private function addItems() {
		if($this->state=treeViewItem::STATE_OPEN) {
			if($this->hasParentElement()) {

			} else {
				$result=$db->sql_query($this->querys[0]['topLevelQuery']);
				while($row=$db->sql_fetch_assoc($result)) {
					$item=new treeViewItem();
					$item->text = $row['text'];
					$item->uid = $row['uid'];
					$item->icon = $this->icon;
					$item->itemConfig = &$this;
					$item->state=$this->hasOpenSubItem()?treeViewItem::STATE_OPEN:treeViewItem::STATE_CLOSED;

					$this->addElement($item);
				}


			}
		}
	}



	public function wrapContent() {
		global $db;

		if(!$this->hasParentElement()) {
			$this->state=treeViewItem::STATE_OPEN;
		}

		if(!is_array($this->items)) {
			$this->isDeepestLevel=true;
		}

		$this->addItems();

		$content=array();
		$content['main'].='<div state="'.$this->state.'" id="treeviewItemContainer_'.$this->level.'_'.treeview::getInstances().'" >
';



		foreach($this->items as $subitemconfig) {
			div::htm_mergeSiteContent($content,$subitemConfig->wrapContent());
		}

		$content['main'].='</div>
';

	}

	public function hasOpenSubItem() {
		foreach($this->subConfigItems as $subConfig) {
			if($subConfig->state==treeViewItem::STATE_OPEN) {
				return true;
			}
		}
		return false;
	}


}
?>