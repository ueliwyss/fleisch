<?
define('LIB_PATH','library/');
define('ROOT_DIR','');
define('MOD_DIR','modules/');

include(LIB_PATH.'class.div.php');
include_once(LIB_PATH."class.framework.php");
//include(LIB_PATH.'class.form.php');
include(LIB_PATH.'class.db.php');
include('config.conf');
include(LIB_PATH.'class.table.php');
include_once(LIB_PATH."class.module.php");
include_once(LIB_PATH."class.tab.php");
include_once(LIB_PATH."class.thumb.php");
include_once(LIB_PATH."class.update.php");


//include_once(LIB_PATH.'class.ticket.php');

 

$db=new Mysqldb();


if(@$db->sql_pconnect('localhost','root','')) {
	$authorized=true;
} elseif(@$db->sql_pconnect($_CONFIG['MysqlDB']['server'],$_CONFIG['MysqlDB']['username'],$_CONFIG['MysqlDB']['password'])) {
	$authorized=true;
} else {
	if(@$db->sql_pconnect('localhost',div::http_getGP('username'),div::http_getGP('password'))) {
		$authorized=true;
	} else {
		$form=new form();
		$form->saveButton=false;
		$form->action=$_SERVER['PHP_SELF'];
		$form->target="";

		$username=new formElement('text');
		$password=new formElement('password');
		$loginButton=new formButton("Installieren",$form->getFormName().".onsubmit()",'images/login_button.gif');

		$username->eval="trim,required";
		$username->label="Benutername";
		$username->name="username";
		$password->label="Passwort";
		$password->name="password";

		$action=new formElement('hidden');
		$action->name="action";
		$action->value="install";

		$form->addElement($username);
		$form->addElement($password);
		$form->addElement($action);

		$form->addButton($loginButton);
		$content=$form->wrapContent();

		div::htm_echoContent($content);
 	}
}


if($authorized) {
	if(!@mysql_connect($_CONFIG['MysqlDB']['server'],$_CONFIG['MysqlDB']['username'],$_CONFIG['MysqlDB']['password'])) {
		$query_user="CREATE USER '".$_CONFIG['MysqlDB']['username']."'@'".$_CONFIG['MysqlDB']['server']."' IDENTIFIED BY '".$_CONFIG['MysqlDB']['password']."'";
		$db->admin_query($query_user);
		//$query_user_rights="GRANT USAGE ON * . * TO '".$_CONFIG['MysqlDB']['username']."'@'".$_CONFIG['MysqlDB']['server']."' IDENTIFIED BY '".$_CONFIG['MysqlDB']['password']."' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0";
		//$db->admin_query($query_user_rights);
		$set_user_rights=true;
	}

	if(!@$db->sql_select_db($_CONFIG['MysqlDB']['database'])) {
		$query_db="CREATE DATABASE `".$_CONFIG['MysqlDB']['database']."`";
		$db->admin_query($query_db);
	}

	if($set_user_rights) {
		$query_user_db="GRANT ALL PRIVILEGES ON `".$_CONFIG['MysqlDB']['database']."` . * TO '".$_CONFIG['MysqlDB']['username']."'@'".$_CONFIG['MysqlDB']['server']."' WITH GRANT OPTION";
		$db->admin_query($query_user_db);
	}

    
    table::updateTable('module');
    module::registerAllModules(); 
       
	module::loadModules();
    
	echo $db->view_array($TABLES);
    table::updateAllResources(); 
    
	table::updateAllTables();
    
	
   //

	
    
	if(div::http_getGP('repeat')) {
		div::http_redirect('./');
	} else {
		div::http_redirect('install.php?repeat=1');
	}

     

}
?>