<?
 
include('../../init.php');

include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');
$content['header'].=div::htm_includeJSFile(MOD_DIR.'dat_fleisch/func.js');
$content['main'].="<iframe name='pdf' id='pdf' style='width:0px;height:0px;'></iframe>";
$content['main'].='
<script src="'.LIB_PATH.'scriptaculous/prototype.js" type="text/javascript"></script>
<script src="'.LIB_PATH.'scriptaculous/scriptaculous.js" type="text/javascript"></script>'; 


if($_GET['order_id']) {      
    fleisch::printRechnung($_GET['order_id']);
    exit();
}

$view=div::http_getGP('view')==''?"list":div::http_getGP('view');
$action=table::decodeAction();

$tableName='fle_order';

$tab=new tab();

if($view=='list') {
    /*$tab->addElement($tab1=new tabItem('Bestellungen'));
    $tab1->content=table::getList($tableName,'listAll',false,array(),$tab1);   */

    /*$tab->addElement($tab2=new tabItem('Neue Bestellung'));
    $tab2->content=table::getForm($tableName,table::MODE_NEW,false,table::MODE_NEW);*/
    $tab->addElement($tab1=new tabItem('Bestellungen'));
    $tab1->content=table::getList($tableName,'listAll',false,array(),$tab1); 
    
    $tab->addElement($tab3=new tabItem('Neue Bestellung','',$_POST['data']['fle_order']['NO_UID']['o_client_id']?true:false));
    $tab3->content['JS']='function submitIfEnter(event, frm) {
       if (event && event.keyCode == 13) {
           frm.onsubmit();
       } else {
           return true;
       }
}
';
    $tab3->content['CSS']='            
.form {
    font-size:11px;
    font-family:tahoma,verdana;
}
 
.form_caption {
    font-weight:bold;
    color:#417fc6;
    font-size:20px;
    padding-bottom:5px;
}
 
.form_description {
    font-weight:bold;
    font-size:11px;
    padding-bottom:20px;
    color:#737373;
}
            
.form_text {
    font-family:tahoma,verdana;
    font-size:12px;
    border:1px solid #3a5f7b;
    background-color:#FFFFFF;
}
 
.form_text_error {
    font-family:tahoma,verdana;
    font-size:12px;
    border:2px solid #980000;
    background-color:#FFFFFF;
}
 
.form_select {
    font-family:tahoma,verdana;
    font-size:12px;
    border:1px solid #3a5f7b;
}
 
.form_select_error {
    font-family:tahoma,verdana;
    font-size:12px;
    font-weight:bold;
    border:2px solid #980000;
    background-color:#980000;
    color:#FFFFFF;
}
 
.form_checkbox {
 
}
 
.form_textarea {
    font-family:tahoma,verdana;
    font-size:12px;
    border:1px solid #3a5f7b;
}
 
.form_textarea_error {
    font-family:tahoma,verdana;
    font-size:12px;
    border:2px solid #980000;
}
            
            
.form_text {
    font-family:tahoma,verdana;
    font-size:12px;
    border:1px solid #3a5f7b;
    background-color:#FFFFFF;
}
 
.form_text_error {
    font-family:tahoma,verdana;
    font-size:12px;
    border:2px solid #980000;
    background-color:#FFFFFF;
}
 
.form_select {
    font-family:tahoma,verdana;
    font-size:12px;
    border:1px solid #3a5f7b;
}
 
.form_select_error {
    font-family:tahoma,verdana;
    font-size:12px;
    font-weight:bold;
    border:2px solid #980000;
    background-color:#980000;
    color:#FFFFFF;
}

.form_button_icon_0 {
    cursor:pointer;
    padding:4px;
    text-align:center;
    vertical-align:middle;
    border-top:1px solid #46b0ee;
    border-left:1px solid #46b0ee;
    border-bottom:1px solid #46b0ee;
    background-color:#FFFFFF;
}
 
.form_button_main_0 {
    cursor:pointer;
    border-top:1px solid #46b0ee;
    border-bottom:1px solid #46b0ee;
    color:#ff9000;
    font-family:tahoma;
    font-size:11px;
    font-weight:bold;
    padding:2px;
    background-color:#FFFFFF;
}
 
.form_button_right_0 {
    cursor:pointer;
    width:0px;
    border-top:1px solid #46b0ee;
    border-right:1px solid #46b0ee;
    border-bottom:1px solid #46b0ee;
    background-color:#FFFFFF;
}


div.autocomplete {
  position:absolute;
  width:250px;
  background-color:white;
  border:1px solid #888;
  margin:0;
  padding:0;
}

div.autocomplete ul {
  list-style-type:none;
  margin:0;
  padding:0;
}
div.autocomplete ul li.selected { background-color: #c7dff4;}
div.autocomplete ul li {
  list-style-type:none;
  display:block;
  margin:0;
  padding:2px;
  height:20px;
  cursor:pointer;
}

';

//echo $db->view_array($_POST);
if($_POST['data']['fle_order']['NO_UID']['o_client_id']) {
    $client=$db->exec_SELECTgetRows('*','fle_client','uid='.$_POST['data']['fle_order']['NO_UID']['o_client_id']);
    $client=$client[0];
}
    

    $tab3->content['main'].='

    <script language="JavaScript" src="../../library/jsfunc.validateform.js" type="text/javascript"></script>
 
<script language="JavaScript" src="../../library/jsfunc.tooltip.js" type="text/javascript"></script>




<form name="form_0" method="POST" action="../../saveForm.php" target="form_actionFrame_0" onsubmit="return validateForm(\'form_0\',\'data[fle_order][NO_UID][o_client_id],true,Kunde,data[fle_order][NO_UID][o_animal_id],true,Tier,data[fle_order][NO_UID][o_fleisch_ord],true,Menge Fleisch,_EREG,,^[-+]?[0-9]+[\\.]?[0-9]*$,data[fle_order][NO_UID][o_kaese_ord],false,Menge Käse,_EREG,,^[0-9]+$,data[fle_order][NO_UID][o_wurst_ord],false,Menge Wurst,_EREG,,^[-+]?[0-9]+[\\.]?[0-9]*$,data[fle_order][NO_UID][o_trockenfleisch_ord],false,Menge Trockenfleisch,_EREG,,^[0-9]+$,data[fle_order][NO_UID][o_numleber],false,Anzahl Lebern,_EREG,,^(([0-2]?[0-9]|3[0-1]).([0]?[1-9]|1[0-2]).[1-3][0-9][0-9][0-9])$,data[fle_order][NO_UID][o_createdate],false,Bestelldatum,data[fle_order][NO_UID][o_filet_ord],false,Menge Filet,_EREG,,^[-+]?[0-9]+[\\.]?[0-9]*$,data[fle_order][NO_UID][o_filet_real],false,Effektives Gewicht Filet\',\'../../\'); this.onsubmit=\'\';">
<table class="form">
<tr>
    <td class="form_caption" colspan="2">
        Bestellung
    </td>
</tr>
<tr>
    <td class="form_description" colspan="2">
        Neue Bestellung erfassen.
    </td>
</tr>
            <input type="hidden" name="activeTab" value="activeTab_tab_0=">
            
            <tr>
<td><b>Kunde</b></td>';

$tab3->content['main'].='<td>
<input type="text" id="o_client_id_autocomplete_0" name="autocomplete_parameter" oldClass="form_text" errorClass="form_text_error" class="form_text" style="width:100%" size="50" value="'.$_POST['autocomplete_parameter'].'" onMouseOver="javascript:Message(\'messagebox_o_client_id_0\',\'Beschreibung:\',\'Kunde der die Bestellung ausgelöst hat.\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_o_client_id_0\')">
<input type="hidden" id="o_client_id_0" name="data[fle_order][NO_UID][o_client_id]" value="'.$_POST['data']['fle_order']['NO_UID']['o_client_id'].'"/>

<div id="o_client_id_autocomplete_choices_0" class="autocomplete"></div>
</td>
</tr>
 
            
            <tr>
<td><b>Tier</b></td>
<td style="width:300px"><select id="o_animal_id_0" onMouseOver="javascript:Message(\'messagebox_o_animal_id_0\',\'Beschreibung:\',\'Von welchem Tier ist das Fleisch der Bestellung?\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_o_animal_id_0\')" oldClass="form_select" errorClass="form_select_error" class="form_select" onkeypress="return submitIfEnter(event, this.form)" name="data[fle_order][NO_UID][o_animal_id]" style="width:100%">
<option value="">TIER...</option>';
$row=$db->exec_SELECTgetRows('uid,a_name,a_selldate','fle_animal','a_selldate > NOW()','','a_selldate DESC');
$i=0;
foreach($row as $kunde){
    $tab3->content['main'].='<option value="'.$kunde['uid'].'"'.($i==0?' selected':'').'>'.div::date_SQLToGer($kunde['a_selldate']).' '.$kunde['a_name'].'</option>';
    $i=1;
}                 
$tab3->content['main'].='</select>
</td>
</tr>
 
            
            <tr>
<td><b>Menge Fleisch</b></td>
<td style="width:300px"><input oldClass="form_text" id="o_fleisch_ord_0" onMouseOver="javascript:Message(\'messagebox_o_fleisch_ord_0\',\'Beschreibung:\',\'Bestellte Menge in kg.\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_o_fleisch_ord_0\')" errorClass="form_text_error" class="form_text" onkeypress="return submitIfEnter(event, this.form)" type="text" name="data[fle_order][NO_UID][o_fleisch_ord]" style="width:100%" size="50" value="'.$_POST['data']['fle_order']['NO_UID']['o_fleisch_ord'].'">
</td>
</tr>

<td>Menge Filet</td>
<td style="width:300px"><input oldClass="form_text" id="o_filet_ord_0" onMouseOver="javascript:Message(\'messagebox_o_filet_ord_0\',\'Beschreibung:\',\'Bestellte Menge in kg.\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_o_filet_ord_0\')" errorClass="form_text_error" class="form_text" onkeypress="return submitIfEnter(event, this.form)" type="text" name="data[fle_order][NO_UID][o_filet_ord]" style="width:100%" size="25" value="'.$_POST['data']['fle_order']['NO_UID']['o_filet_ord'].'">
</td>
</tr>
 
            
            <tr>
<td>Menge Käse</td>
<td style="width:300px"><input oldClass="form_text" id="o_kaese_ord_0" onMouseOver="javascript:Message(\'messagebox_o_kaese_ord_0\',\'Beschreibung:\',\'Bestellte Menge in kg.\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_o_kaese_ord_0\')" errorClass="form_text_error" class="form_text" onkeypress="return submitIfEnter(event, this.form)" type="text" name="data[fle_order][NO_UID][o_kaese_ord]" style="width:100%" size="50" value="'.$_POST['data']['fle_order']['NO_UID']['o_kaese_ord'].'">
</td>
</tr>
 
 
            
            <tr>
<td>Menge Wurst</td>
<td style="width:300px"><input oldClass="form_text" id="o_wurst_ord_0" onMouseOver="javascript:Message(\'messagebox_o_wurst_ord_0\',\'Beschreibung:\',\'Bestellte Menge in kg.\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_o_wurst_ord_0\')" errorClass="form_text_error" class="form_text" onkeypress="return submitIfEnter(event, this.form)" type="text" name="data[fle_order][NO_UID][o_wurst_ord]" style="width:100%" size="50" value="'.$_POST['data']['fle_order']['NO_UID']['o_wurst_ord'].'">
</td>
</tr>

 
            
            <tr>
<td>Menge Trockenfleisch</td>
<td style="width:300px"><input oldClass="form_text" id="o_trockenfleisch_ord_0" onMouseOver="javascript:Message(\'messagebox_o_trockenfleisch_ord_0\',\'Beschreibung:\',\'Bestellte Menge in kg.\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_o_trockenfleisch_ord_0\')" errorClass="form_text_error" class="form_text" onkeypress="return submitIfEnter(event, this.form)" type="text" name="data[fle_order][NO_UID][o_trockenfleisch_ord]" style="width:100%" size="50" value="'.$_POST['data']['fle_order']['NO_UID']['o_trockenfleisch_ord'].'">
</td>
</tr>



 
            
            <tr>
<td>Leber</td>
<td style="width:300px">
<input oldClass="form_checkbox" errorClass="form_checkbox_error" class="form_checkbox" id="o_leber_0" onMouseOver="javascript:Message(\'messagebox_o_leber_0\',\'Beschreibung:\',\'Nimmt der Kunde Leber?\',this.id, 0)" onMouseOut="hideElement(\'messagebox_o_leber_0\')" onkeypress="return submitIfEnter(event, this.form)" type="checkbox" name="data[fle_order][NO_UID][o_leber]"'.($client['c_leber']?' checked':'').'>
</td>
</tr>
 
            
            <tr>
<td>Anzahl Lebern</td>
<td style="width:300px"><input oldClass="form_text" id="o_numleber_0" onMouseOver="javascript:Message(\'messagebox_o_numleber_0\',\'Beschreibung:\',\'Anzahl Lebern\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_o_numleber_0\')" errorClass="form_text_error" class="form_text" onkeypress="return submitIfEnter(event, this.form)" type="text" name="data[fle_order][NO_UID][o_numleber]" style="width:100%" size="50" value="'.($client['c_numleber']?$client['c_numleber']:($client['c_leber']?'1':'')).'">
</td>
</tr>
 
            
            <tr>
<td>Tiernahrung</td>
<td style="width:300px">
<input oldClass="form_checkbox" errorClass="form_checkbox_error" class="form_checkbox" id="o_tiernahrung_0" onMouseOver="javascript:Message(\'messagebox_o_tiernahrung_0\',\'Beschreibung:\',\'Nimmt der Kunde Innereien als Tiernahrung?\',this.id, 0)" onMouseOut="hideElement(\'messagebox_o_tiernahrung_0\')" onkeypress="return submitIfEnter(event, this.form)" type="checkbox" name="data[fle_order][NO_UID][o_tiernahrung]"'.($client['c_tiernahrung']?' checked':'').'>
</td>
</tr>
 
            
            <tr>
<td>Markknochen</td>
<td style="width:300px">
<input oldClass="form_checkbox" errorClass="form_checkbox_error" class="form_checkbox" id="o_knochen_0" onMouseOver="javascript:Message(\'messagebox_o_knochen_0\',\'Beschreibung:\',\'Nimmt der Kunde Markknochen?\',this.id, 0)" onMouseOut="hideElement(\'messagebox_o_knochen_0\')" onkeypress="return submitIfEnter(event, this.form)" type="checkbox" name="data[fle_order][NO_UID][o_knochen]"'.($client['c_knochen']?' checked':'').'>
</td>
</tr>
            <tr>
<td>Spezialwünsche</td>
<td style="width:300px"><textarea oldClass="form_textarea" id="o_specials_0" onMouseOver="javascript:Message(\'messagebox_o_specials_0\',\'Beschreibung:\',\'Spezialwünsche des Kunden, für diese Bestellung\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_o_specials_0\')" errorClass="form_textarea_error" class="form_textarea" name="data[fle_order][NO_UID][o_specials]" style="width:100%" rows="10">'.$client['c_specials'].'</textarea>
</td>
</tr>
 
            
            <tr>
<td>Bemerkungen</td>
<td style="width:300px"><textarea oldClass="form_textarea" id="o_comment_0" onMouseOver="javascript:Message(\'messagebox_o_comment_0\',\'Beschreibung:\',\'Bemerkungen\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_o_comment_0\')" errorClass="form_textarea_error" class="form_textarea" name="data[fle_order][NO_UID][o_comment]" style="width:100%" rows="10"></textarea>
</td>
</tr>
 
            
            <tr>
<td>Bestelldatum</td>
<td style="width:300px"><input oldClass="form_text" id="o_createdate_0" onMouseOver="javascript:Message(\'messagebox_o_createdate_0\',\'Beschreibung:\',\'Bestelldatum.\',this.id, 2000)" onMouseOut="hideElement(\'messagebox_o_createdate_0\')" errorClass="form_text_error" class="form_text" onkeypress="return submitIfEnter(event, this.form)" type="text" name="data[fle_order][NO_UID][o_createdate]" style="width:100%" size="50" value="'.div::date_getDate().'">
</td>
</tr>
 
            
            <input type="hidden" name="action" value="new[fle_order(new)][NO_UID][NO_UID]">
            
            <input type="hidden" name="triggerFile" value="modules/dat_fleisch/edit_fle_order.php">
            
    <tr>
        <td>
            <div style="padding:2px;">
<table border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="form_button_icon_0" onClick="form_0.onsubmit();"><img src="../../icons/form_button_saveIcon.gif"></td>
        <td class="form_button_main_0" onClick="form_0.onsubmit();">Speichern</td>
        <td class="form_button_right_0" onClick="form_0.onsubmit();">&nbsp;</td>
    </tr>
</table>
</div>
            
        </td>
    </tr>
</table>
</form>
<script language="JavaScript">new Ajax.Autocompleter("o_client_id_autocomplete_0", "o_client_id_autocomplete_choices_0", "'.MOD_DIR.'dat_fleisch/autocompletion.php", {
    minChars : 0,
    afterUpdateElement : getSelectionId

});
function getSelectionId(text, li) {
document.forms["form_0"].elements["data[fle_order][NO_UID][o_client_id]"].value=li.id;
    form_0.elements[\'action\'].value=\'\';
   form_0.attributes[\'action\'].value=\'\';
   form_0.target=\'\';
   form_0.submit();
}

</script>

<iframe width="0" height="0" name="form_actionFrame_0"></iframe>

';


} else {
    $tab->addElement($tab1=new tabItem('Allgemein',table::getForm($tableName,table::MODE_EDIT,false,table::MODE_EDIT,$action['uid']),true));
    $tab1->content['main'].='<a href="javascript:printRechnung(\''.div::http_getURL(array('order_id'=>$action['uid'],'type'=>'facture')).'\');">Rechnung drucken</a>';
     $tab->addElement($tab1=new tabItem('Gewichte',table::getForm($tableName,"real_weights",false,table::MODE_EDIT,$action['uid']),true));
    if($action['mode']!=table::MODE_NEW) {
        //Gallery
    }
}

div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);
?>