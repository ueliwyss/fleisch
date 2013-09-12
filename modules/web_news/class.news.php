<?php

$TABLES['news']=Array(
	'ctrl'=>Array(
		'isDbTable'=>true,
		'type'=>'normal',
	),
	'fields'=>Array(
		'uid'=>array(
			'label'=>'id',
			'description'=>'Primärschlüssel-Feld der Tabelle',
			'dbconfig'=>Array(
				'type'=>'int',
				'autoIncrement'=>1,
				'primaryKey'=>1
			),
		),
		'n_created'=>array(
			'label'=>'erstelldatum',
			'description'=>'Erstelldatum',
			'formconfig'=>array(
				'all'=>array(
					'specialVal'=>'now'
				),
			),
			'dbconfig'=>array(
				'type'=>'datetime',
			),
		),
		'n_title'=>array(
			'label'=>'Titel',
			'description'=>'Titel des Newsbeitrags',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim,required',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>200,
			),
		),
		'n_description'=>array(
			'label'=>'Kurzbeschreibung',
			'description'=>'Kurze Beschreibung oder Einleitung',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim,required',
					'rows'=>3,
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>1000,
			),
		),
		'n_content'=>array(
			'label'=>'Text',
			'description'=>'Inhalt des Newsbeitrags',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim,required',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>3000,
			),
		),
		'n_hidden'=>array(
			'label'=>'Versteckt?',
			'description'=>'Wenn dieses Flag gesetzt ist, wird der Newspost auf der Website nicht angezeigt. Diese Funktion kann zum speichern von Entwürfen verwendet werden.',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'checkbox',
				)
			),
			'dbconfig'=>array(
				'type'=>'int',
				'length'=>1,
			),
			'allocation'=>array(
				1=>'Ja',
				0=>'Nein',
			),
		),
	),
	'forms'=>array(
		'new'=>array(
			'fields'=>'n_title,n_content,n_created,n_hidden',
		),
		'edit'=>array(
			'fields'=>'n_title,n_content,n_hidden',
		),
	),
	'lists'=>array(
		'listAll'=>array(
			'fields'=>'n_title,n_content,n_created,n_hidden',
			'commonActions'=>'drop',
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
	),
);

class news {

	public $CSS;


	function news() {
		$this->setDefaults();
	}

	function setDefaults() {
		$this->CSS='
.news_table {
	font-size:12px;
	color:#FFFFFF;
}

.news_caption {
	color:#ffc600;
	font-weight:bold;
	font-size:12px;
	text-indent:3px;
}
.news_title {
	font-weight:bold;
}

.news_content{
	color:#FFFFFF;
	border-bottom:1px solid black;
	font-family:Tahoma;
}';
	}

	function getLimitedString($string,$length=100) {
		if(strlen($string)>$length) {
			$string = substr($string,0,$length-3)."...";
		}
		return $string;
	}

	function getNews($limit=0) {
		$newsposts=Array();
		$result = $GLOBALS['db']->exec_SELECTquery("*","news","","","n_created DESC",$limit);

		while($row=$GLOBALS['db']->sql_fetch_assoc($result)) {

			$post = Array("id"=>$row['uid'],"title"=>$row['n_title'],"description"=>$this->getLimitedString($row['n_description']),"content"=>$row['n_content'],"date"=>date("d.M.Y H:i",(strtotime($row['n_created']))));
			array_push($newsposts,$post);
		}
		return $newsposts;
	}

	function getNewspost($newspost_id) {
		$result=$GLOBALS['db']->exec_SELECTquery("*","newspost","newspost_id=".$newspost_id,"","date DESC","");

		$row=$GLOBALS['db']->sql_fetch_row($result);
		$post=Array("id"=>$row[0],"title"=>$row[1],"description"=>$this->getLimitedString($row[2]),"content"=>$row[3],"date"=>Date_sql2ger($row[4]));

		return $post;
	}

	function showFullPost($newspost_id) {
		$newspost = $this->getNewspost($newspost_id);
		$content = "<table>\n<tr>\n<td>";
		$content .= $newspost["date"]." - ".$newspost["title"];
		$content .= "</td>\n</tr>\n<tr>\n<td>";
		$content .= $newspost["content"];
		$content .= "</td>\n</tr>\n<tr>\n</table>";
		return array(
			'main'=>$content,
			'CSS'=>$this->CSS,
		);
	}

	function showShortList($limit) {
		$newsposts = $this->getNews($limit);
		$content .= "<table class='news_table'>";
		foreach($newsposts as $newspost) {
			$content .= "<tr>\n<td>\n<table class='news_table'>\n<tr>\n<td><b>";
			$content .= $newspost["date"]." - ".$newspost["title"];
			$content .= "</b></td>\n</tr>\n<tr>\n<td class='news_content'>";
			$content .= div::str_cut($newspost["content"],100);
			$content .= "</td>\n</tr>\n<tr>\n</table>\n</td>\n</tr>\n";
		}
		$content .= "</table>\n";
		return array(
			'main'=>$content,
			'CSS'=>$this->CSS,
		);
	}

	/*function insertPost($title,$description,$content) {
		$GLOBALS['db']->exec_INSERTquery("newspost",Array("title"=>$title,"description"=>$description,"content"=>$content,"date"=>div::Date_ger2sql(div::Date_getDateTime())));
	}*/

	/*function getAddForm($action='news.php') {
		$content .= '<form name="addNews" method="post" action="'.$action.'">
	  <table width="75%">
	    <tr>
	      <td>Titel</td>
	      <td> <input name="news_title" type="text" size="40"> </td>
	    </tr>
	    <tr>
	      <td>Beschreibung</td>
	      <td> <textarea name="news_description" cols="30"></textarea> </td>
	    </tr>
	    <tr>
	      <td>Inhalt</td>
	      <td> <textarea name="news_content" rows="30cols="30"></textarea> </td>
	    </tr>
	     <tr align="center">
	      <td colspan="2">
	        <input type="submit" name="action" value="Speichern">
	      </td>
	    </tr>
	  </table>
	</form>';

		return $content;
	}*/

	/**
	 * @return void
	 * @param string $msg
	 * @desc Gibt eine Meldung aus.
	*/
	function message($msg) {
		echo $msg;
	}

	function showAll() {
		global $db;

		$content['CSS']=$this->CSS;
		$content['main']='
	<table class="news_table">';
		$news=$db->exec_SELECTgetRows("*","news","n_hidden=0","","n_created DESC");

		foreach ($news as $newsItem) {
			$content['main'].='
	<tr>
		<td class="news_title">'.div::date_SQLToGer($newsItem['n_created']).' <b><font size="-1">'.$newsItem['n_title'].'</font></b></td>
	</tr><tr>
		<td class="news_content">'.$newsItem['n_content'].'</td>
	</tr>';
		}

		$content['main'].='</table>';
		return $content;
	}

	/**
	 * @return void
	 * @desc Gibt den Inhalt dieser Seite aus.
	*/
	function echoContent() {
		echo $this->content;
	}
}
?>