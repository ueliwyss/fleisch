<?
include ('init.php');
include (LIB_PATH.'class.tab.php');

if(div::http_getGP('action')=='auth') {
	if(user::authUser(div::http_getGP('username'),div::http_getGP('password'))) {
		div::http_redirect('./');
	}
} else {
	user::logoutUser();
}

$form=new form();
$form->saveButton=false;
$form->action=$_SERVER['PHP_SELF'];
$form->target="";

$username=new formElement('text');
$password=new formElement('password');
$loginButton=new formButton("Login",$form->getFormName().".onsubmit()",'images/login_button.gif');

$username->eval="trim,required";
$username->label="Benutername";
$username->name="username";
$password->eval="required";
$password->label="Passwort";
$password->name="password";

$action=new formElement('hidden');
$action->name="action";
$action->value="auth";

$form->addElement($username);
$form->addElement($password);
$form->addElement($action);

$form->addButton($loginButton);


$content=$form->wrapContent();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
echo $content['header'];
echo div::htm_includeCSS($content['CSS']);
echo div::htm_includeJS($content['JS']);
?>
</head>

<body style="margin:0px;scrolling:no">
<table height="100%" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr height="30%">
    <td>&nbsp;</td>
  </tr>
  <tr height="20%">
    <td style="background-color:#0083ca;text-align:right;">
<?

echo $content['main'];



?>
    </td>
  </tr>
  <tr height="40%">
    <td>&nbsp;</td>
  </tr>
</table>

</body>
</html>