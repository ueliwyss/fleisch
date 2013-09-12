<?
include('init.php');
include(LIB_PATH.'class.tabmenu.php');

$menu = new tabMenu();
$menu->fullSpaceContent='<a href="login.php" target="_parent">Logout</a>';
$menu->target="content";


$menuConfig=module::getMenuConfig();
//echo $db->view_array($menuConfig);

foreach($menuConfig as $topLevelItem=>$subItems) {
	$menuItem=new tabMenuItem($topLevelItem);
	$menuItem->target=$menu->target;
	//echo $db->view_array($subItems);
	foreach($subItems as $caption=>$value) {
		$caption=strtolower($caption);
		$caption=substr_replace($caption, strtoupper(substr($caption, 0, 1)), 0, 1);

		$subMenuItem=new tabSubMenuItem($caption,$value);
		$menuItem->addElement($subMenuItem);
	}
	$menu->addElement($menuItem);
}
/*$ticketing=new tabMenuItem("Ticketing");
$ticketing->target=$menu->target;


if(user::curusr_getPermission("ticket")>=user::PERMISSION_READ) {
	$ticket=new tabSubMenuItem("Tickets","edit_ticket.php");
	$ticketing->addItem($ticket);
}

if(user::curusr_getPermission("contract")>=user::PERMISSION_READ) {
	$vertraege=new tabSubMenuItem("Verträge","edit_contract.php");
	$ticketing->addItem($vertraege);
}

$administration=new tabMenuItem("Benutzerverwaltung");
$administration->target=$menu->target;

if(user::curusr_getPermission("usr_user")>=user::PERMISSION_READ) {
	$user=new tabSubMenuItem("Benutzer","edit_usr_user.php");
	$administration->addItem($user);
}

if(user::curusr_getPermission("usr_group")>=user::PERMISSION_READ) {
	$groups=new tabSubMenuItem("Arbeitsgruppen","edit_usr_group.php");
	$administration->addItem($groups);
}

if(user::curusr_getPermission("usr_roll")>=user::PERMISSION_READ) {
	$rolls=new tabSubMenuItem("Rollen","edit_usr_roll.php");
	$administration->addItem($rolls);
}

if(user::curusr_getPermission("usr_department")>=user::PERMISSION_READ) {
	$department=new tabSubMenuItem("Abteilungen","edit_usr_department.php");
	$administration->addItem($department);
}

if(user::curusr_getPermission("usr_sync")>=user::PERMISSION_READ) {
	$sync=new tabSubMenuItem("Synchronisation","edit_usr_sync.php");
	$administration->addItem($sync);
}

if(user::curusr_getPermission("usr_location")>=user::PERMISSION_READ) {
	$sync=new tabSubMenuItem("Standorte","edit_usr_location.php");
	$administration->addItem($sync);
}


if(user::curusr_getPermission("usr_team")>=user::PERMISSION_READ) {
	$team=new tabSubMenuItem("Support-Teams","edit_usr_team.php");
	$administration->addItem($team);
}

if(user::curusr_getPermission("usr_session")>=user::PERMISSION_READ) {
	$sessions=new tabSubMenuItem("Sessions","edit_usr_session.php");
	$administration->addItem($sessions);
}

if($administration->items) {
	$menu->addItem($administration);
}

if($ticketing->items) {
	$menu->addItem($ticketing);
}*/
//$menu->setActive(0);

$content=$menu->wrapContent();
div::htm_echoContent($content);