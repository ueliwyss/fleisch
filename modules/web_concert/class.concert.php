<?php

//include_once("init.php");

$TABLES['concert']=Array(
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
		'c_date'=>array(
			'label'=>'Datum',
			'description'=>'Datum des Konzerts',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,date',
				),
			),
			'dbconfig'=>array(
				'type'=>'date',
			),
		),
		'c_time'=>array(
			'label'=>'Zeit',
			'description'=>'Zeit',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'required,time',
				),
			),
			'dbconfig'=>array(
				'type'=>'time',
			),
		),
		'c_title'=>array(
			'label'=>'Titel',
			'description'=>'Titel oder Name des Konzerts (Chilbi)',
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
		'c_description'=>array(
			'label'=>'Bemerkungen',
			'description'=>'Text oder Beschreibung zum Konzert (aktzeptiert HTML)',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'textarea',
					'eval'=>'trim',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>1000,
			),
		),
		'c_location'=>array(
			'label'=>'Ort',
			'description'=>'Wo findet das Konzert statt?',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>300,
			),
		),
		'c_link'=>array(
			'label'=>'Link',
			'description'=>'Page des Veranstalters',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'text',
					'eval'=>'trim',
				)
			),
			'dbconfig'=>array(
				'type'=>'varchar',
				'length'=>300,
			),
		),
		'c_canceled'=>array(
			'label'=>'Abgesagt?',
			'description'=>'Datensatz bitte nicht löschen, wenn das Konzert abgesagt wurde, sondern Häcken setzen.',
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
		'c_album_id'=>array(
			'label'=>'Album',
			'description'=>'Album, das Bilder zum Konzert enthält.',
			'foreign_table'=>'album',
			'foreign_key'=>'uid',
			'formconfig'=>array(
				'all'=>array(
					'type'=>'select',
					'foreign_display'=>'a_name',
				),
			),
			'dbconfig'=>array(
				'type'=>'int',
			),
		),
	),
	'forms'=>array(
		'new'=>array(
			'fields'=>'c_title,c_date,c_time,c_description,c_location,c_link,c_canceled,c_album_id',
		),
		'edit'=>array(
			'fields'=>'c_title,c_date,c_time,c_description,c_location,c_link,c_canceled,c_album_id',
		),
	),
	'lists'=>array(
		'listAll'=>array(
			'fields'=>'c_title,c_date,c_time,c_description,c_location,c_link,c_canceled',
			'commonActions'=>'drop',
			'actions'=>array(
				'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
				'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
			),
		),
	),
);

/*$TABLES['comment']=Array(
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
		),*/

// SQL-Querys für die nötigen Tabellen, die erstellt werden müssen, falls sie nicht exisiteren.


	//define('query_crTblConcert_Comment', "CREATE TABLE `concert_comment` (`concert_id` int(11) NOT NULL default '0',`comment_id` int(11) NOT NULL default '0',PRIMARY KEY  (`concert_id`,`comment_id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;");

	//define('query_crTblConcert_Album',"CREATE TABLE `concert_album` (`concert_id` int(11) NOT NULL default '0',`album_id` int(11) NOT NULL default '0',PRIMARY KEY  (`concert_id`,`album_id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;");

class concert {
	public $CSS;

	public $activateComments;
	public $activateAlbums;

	public $comments;
	public $gallery;

	function concert() {
		$this->setDefaults();

		if(file_exists("class.comment.php")) {
			require("class.comment.php");
			$GLOBALS['comments']=new Comment();

			$this->activateComments=true;
		} else {
			$this->activateComments=false;
		}

		//Check: Tabelle 'concert_album'
		if(file_exists("class.gallery.php")) {
			require("class.gallery.php");
			$GLOBALS['gallery']=new Gallery();

			$this->activateAlbums=true;
		} else {
			$this->activateAlbums=false;
		}
	}

	function setDefaults() {
		$this->CSS='
.concert_table {
	width:100%;
}

.concert_title {

}

.concert_item {
	margin:10px;
	border-bottom:1px solid white;
	padding-right:5px;
	padding-bottom:5px;
	padding-top:8px;
}

.concert_header {
	font-weight:bold;
	margin:2px;
	padding-right:3px;
}';
	}


	function listConcerts() {
		global $db;

		$content=array(
			'CSS'=>$this->CSS,
		);

		$content['main'].='
<table class="concert_table" cellspacing="0" cellpadding="0">';
		$nextc=$db->exec_SELECTgetRows("*","concert","CURDATE()<=c_date","","c_date ASC");

		if(count($nextc)) {
			$content['main'].='
<tr><td colspan=4 class="subheader" style="padding-bottom:10px;padding-top:10px;">Bald auf der Bühne in...</td></tr>
<tr>
	<td class="concert_header">Datum</td>
	<td class="concert_header">Zeit</td>
	<td class="concert_header">Konzert</td>
	<td class="concert_header">Ort</td>
	<td class="concert_header">Bemerkungen</td>
	<td class="concert_header">Link</td>
</tr>';
		}
		foreach($nextc as $concert) {

			if($concert['c_canceled']) {
				$style='text-decoration:line-through;color:red;font-weight:bold;';
				$content['main'].='
<tr>
	<td colspan=6 style="'.$style.'">Abgesagt:</td>
</tr>';
			} else {$style='';}

			$link=$concert['c_link']?'<a href="'.$concert['c_link'].'" target="_blank"><img src="symbole/link_extern.gif"></a>':'';
			$content['main'].='
<tr>
	<td class="concert_item" style="'.$style.'">'.div::date_SQLToGer($concert['c_date']).'&nbsp;</td>
	<td class="concert_item" style="'.$style.'">'.date("H:i",strtotime($concert['c_time'])).'&nbsp;</td>
	<td class="concert_item" style="'.$style.'">'.$concert['c_title'].'&nbsp;</td>
	<td class="concert_item" style="'.$style.'">'.$concert['c_location'].'&nbsp;</td>
	<td class="concert_item" style="'.$style.'">'.$concert['c_description'].'&nbsp;</td>
	<td class="concert_item" style="'.$style.'">'.($link?$link:'&nbsp;').'&nbsp;</td>
</tr>';
		}


		$lastc=$db->exec_SELECTgetRows("*","concert","CURDATE()>c_date AND c_canceled=0","c_date DESC");

			if(count($lastc)) {
				$content['main'].='
<tr><td colspan=4 class="subheader" style="padding-bottom:10px;padding-top:20px;">Gerockte Konzerte</td></tr>
<tr>
	<td class="concert_header">Datum</td>
	<td class="concert_header">Zeit</td>
	<td class="concert_header">Konzert</td>
	<td class="concert_header">Ort</td>
	<td class="concert_header">Bemerkungen</td>
	<td class="concert_header">Link</td>
	<td class="concert_header">Bilder</td>
</tr>';
		}

		foreach($lastc as $concert) {
			$link=$concert['c_link']?'<a href="'.$concert['c_link'].'" target="_blank"><img src="'.ICON_DIR.'/link_extern.gif"></a>':'';
			$link_album=$concert['c_album_id']?'<a href="index.php?id=bilder.php&album='.$concert['c_album_id'].'"><img src="'.ICON_DIR.'/album.gif"></a></td>':'';
			$content['main'].='
<tr>
	<td class="concert_item">'.div::date_SQLToGer($concert['c_date']).'</td>
	<td class="concert_item">'.date("H:i",strtotime($concert['c_time'])).'</td>
	<td class="concert_item">'.$concert['c_title'].'&nbsp;</td>
	<td class="concert_item">'.$concert['c_location'].'&nbsp;</td>
	<td class="concert_item">'.$concert['c_description'].'&nbsp;</td>
	<td class="concert_item">'.($link?$link:'&nbsp;').'</td>
	<td class="concert_item">'.($link_album?$link_album:'&nbsp;').'</td>
</tr>';
		}

		$content['main'].='
</table>';
		return $content;
	}


	function getConcerts($limit) {
		$concerts = div::DB_Table2Array("concert",$limit,"date DESC");
		return $concerts;
	}

	function delete_concert($concert_id) {
		global $db;

		$db->exec_DELETEquery("concert","uid=".$concert_id);
	}
}
