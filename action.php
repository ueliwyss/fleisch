<?
include('init.php');


$action=div::http_getGP('action');

if($action=='SQLMut') {
	if($query=div::http_getGP('query')) {
		$result=$db->sql_query($query);
	} else {
		$uids=explode(",",div::http_getGP('uids'));
		$primaryKey=$db->getPrimaryKey(div::http_getGP('table'));

		$i=0;
		foreach($uids as $uid) {
			$where.= $primaryKey[0]."=".$uid;
			if(count($uids)-1!=$i) {
				$where.=' OR ';
			}
			$i++;
		}
		switch (strtoupper(div::http_getGP('method'))) {
			case 'DELETE':
				$db->exec_DELETEquery(div::http_getGP('table'),$where);
			break;
			case 'INSERT':
				$db->exec_INSERTquery(div::http_getGP('table'),div::decodeURLArray('values'));
			break;
			case 'UPDATE':
				$db->exec_UPDATEquery(div::http_getGP('table'),div::http_getGP('where'),div::decodeURLArray('values'));
		}
	}
} elseif($action=="sync") {
	if($uid=div::http_getGP('uid')) {
		if($uid=="all") {
			user::sync_all();
		} else {
			user::sync_synchronize($uid);
		}
	}
} elseif($action=="delete") {
	if($_GET['action']) {
		$data=$_GET;
	} else {
		$data=$_POST;
	}

    table::deleteRows($data['table'],$data['uids']);
   
	
	
} elseif($action=="ticket_changeSupportLevel") {


} elseif($action=="exec_func") {
	eval(div::http_getGP('func').";");
}



?>