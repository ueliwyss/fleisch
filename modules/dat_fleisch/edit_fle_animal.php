<?
include('../../init.php');
include(LIB_PATH.'class.section.php');
include(LIB_PATH.'class.tab.php');
$content['header'].=div::htm_includeJSFile(MOD_DIR.'dat_fleisch/func.js'); 
$content['main'].="<iframe name='pdf' id='pdf' style='width:0px;height:0px;'></iframe>";



if($_GET['animal_id']) {
   
    if($_GET['type']=='allfactures') { 
           fleisch::printAllRechnungen($_GET['animal_id']);
    } elseif($_GET['type']=='bestellliste') {
        fleisch::printOrderList($_GET['animal_id']);
    } else {
        
        $res=mysql_query("SELECT * FROM fle_order LEFT JOIN fle_animal ON fle_order.o_animal_id=fle_animal.uid LEFT JOIN fle_client ON fle_order.o_client_id=fle_client.uid WHERE fle_order.o_animal_id=".$_GET['animal_id']);
  
        $output = array();

        while($tempRow = mysql_fetch_assoc($res))    {
            $output[] = $tempRow;
        } 
        if($_GET['type']=='box') {
           $lab=new PDF_Label(4781);  
        } elseif($_GET['type']=='sack') {
            $lab=new PDF_Label(3490);
        }
        
        $lab->Open();  
        $lab->AddPage(); 
        foreach ($output as $label) {
            if($_GET['type']=='box') {
                $lab->Add_Label(sprintf("%s\n%s %s", $label['c_strasse'], $label['c_plz'], $label['c_ort']),$label['c_nachname'].' '.$label['c_vorname'],($label['o_filet_ord']?'Filet'."\n":'').($label['o_kaese_ord']?$label['o_kaese_ord'].'kg Käse'."\n":'').($label['o_wurst_ord']?($label['o_wurst_ord']>1?$label['o_wurst_ord'].' Würste'."\n":$label['o_wurst_ord'].' Wurst'."\n"):'').($label['o_trockenfleisch_ord']?$label['o_trockenfleisch_ord'].'kg Trockenfleisch'."\n":'').($label['o_leber']?($label['o_numleber']?($label['o_numleber']>1?$label['o_numleber'].' Lebern'."\n":'1 Leber'."\n"):'1 Leber'."\n"):'').($label['o_knochen']?'Markknochen'."\n":'').($label['o_tiernahrung']?'Tiernahrung'."\n":''));
            } elseif($_GET['type']=='sack') {
                $lab->Add_Label(($label['o_filet_ord']?'mit Filet':''),$label['c_nachname'].' '.$label['c_vorname']);
            }
        } 
        $file=TEMP_DIR.basename(tempnam(TEMP_DIR,'tmp'));
        
        //Save PDF to file 
        $lab->Output($file,'F');
        echo "<iframe id='pdf' style='width:0px;height:0px;'></iframe><script>document.getElementById('pdf').src='$file'; </script>";

    }
    //Determine a temporary file name in the current directory
   exit();
}
$view=div::http_getGP('view')==''?"list":div::http_getGP('view');
$action=table::decodeAction();

$tableName='fle_animal';

$tab=new tab();

if(user::curusr_getRollId()==user::ROLL_KUNDE) $listNameadd='_client';

if($view=='list') {
    if(user::curusr_getPermission("fle_animal")==user::PERMISSION_READ) {
          $tab->addElement($tab1=new tabItem('Tiere'));
          $tab1->content=table::getList($tableName,'listAll_read',false,array(),$tab1);
    }
    
    if(user::curusr_getPermission("fle_animal")==user::PERMISSION_FULL) {
        $tab->addElement($tab1=new tabItem('Tiere'));
          $tab1->content=table::getList($tableName,'listAll_full'.$listNameadd,false,array(),$tab1);
          $tab->addElement($tab2=new tabItem('Neuers Tier'));
          $tab2->content=table::getForm($tableName,table::MODE_NEW,false,table::MODE_NEW);
          $tab->addElement($tab3=new tabItem('Tiere importieren'));
          $tab3->content=importAnimals();
    }
    

} else {
    $tab->addElement($tab1=new tabItem('Allgemein',table::getForm($tableName,table::MODE_EDIT,false,table::MODE_EDIT,$action['uid']),true));
    $tab->addElement($tab2=new tabItem('Bestellungen','',false));
    $tab1->content['main'].='<a href="'.div::http_getURL(array('animal_id'=>$action['uid'],'type'=>'box')).'" target="pdf">Box-Etiketten drucken</a><br>';
    $tab1->content['main'].='<a href="'.div::http_getURL(array('animal_id'=>$action['uid'],'type'=>'sack')).'" target="pdf">Sack-Etiketten drucken</a><br>';
    $tab1->content['main'].='<a href="'.div::http_getURL(array('animal_id'=>$action['uid'],'type'=>'bestellliste')).'" target="pdf">Bestelllisten drucken</a><br>';
    $tab1->content['main'].='<a href="javascript:printRechnung(\''.div::http_getURL(array('animal_id'=>$action['uid'],'type'=>'allfactures')).'\');">Alle Rechnungen drucken</a>';  
    //$tab2->content=table::getList('fle_order','animalOrders',false,array(),$tab1); 
    $tab2->content=table::getList('fle_order','animalOrders_bern'.$listNameadd,true,array(),$tab1); 
    div::htm_mergeSiteContent($tab2->content,table::getList('fle_order','animalOrders_oberland'.$listNameadd,true,array(),$tab1)); 
    if($action['mode']!=table::MODE_NEW) {
        //Gallery
    }
}

function importAnimals() {
    global $db;
    $content=array();
     $content['main'].="<iframe name='upload_animals' id='upload_animals' style='width:0px;height:0px;'></iframe>";
    



    $form2=new form($tab1);
    $form2->setFormAttributes(basename($_SERVER['PHP_SELF']),'POST');

    $act=new formElement('hidden');
    $act->name='import_action';
    $act->value='loadXLS';
    $form2->addElement($act);

    /*$file=new formElement('file');
    $file->name='xlsFile';
    $form2->addElement($file);*/
    $tdb_user=new formElement('text');
    $tdb_user->value=vars::getVar('fle_tierdatenbank_username');
    $tdb_user->name='tdb_username';
    $tdb_user->label='Benutzername der Tierdatenbank';
    $form2->addElement($tdb_user);  

    $tdb_pass=new formElement('password');
    $tdb_pass->value=vars::getVar('fle_tierdatenbank_password');
    $tdb_pass->name='tdb_password';
    $tdb_pass->label='Passwort für die Tierdatenbank';
    $form2->addElement($tdb_pass);  

    $mon=new formElement('text');
    $mon->value=12;
    $mon->name='animal_age';
    $mon->label='Maximales Alter der Tiere in Monaten';

    $form2->addElement($mon);

    $form2->addButton(new formButton('Daten anfordern',$form2->getFormName().".onsubmit();"),"end");
    $form2->enctype='multipart/form-data';
    $section2=new section('Datei auswählen',$form2->wrapContent());        

    div::htm_mergeSiteContent($content,$section2->wrapContent());

        
         
        
        

    if($_POST['import_action']=='loadXLS') {
        //echo __FILE__;
        $file=TEMP_DIR.basename(tempnam(TEMP_DIR,'xls'));
        $user_id = "145608.0"; // Please set your Ebay ID
        $user_password = "886293"; // Please set your Ebay Password
        $cookie_file_path = tempnam(TEMP_DIR,'cok'); // Please set your Cookie File path
        
        //$cookie='AWSUSER_ID=awsuser_id1296751350653r3320; AWSSESSION_ID=awssession_id1296751350653r3320';
        $LOGINURL = "https://www.tierverkehr.ch/tvd/menu.login.action?language=de";
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 (.NET CLR 3.5.30729)";
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$LOGINURL);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
        //curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        //curl_setopt($ch, CURLOPT_HEADER, 1);    
        $result = curl_exec ($ch);
        //echo "<br>Anfrage:<br>".curl_getinfo($ch, CURLINFO_HEADER_OUT);
        //echo "<br>Antwort:<br>".curl_getinfo($ch, CURLINFO_HTTP_CODE);    
        curl_close ($ch);
        //print     $result;
    //ECHO "LOGINSEITE";
    // 2- Post Login Data to Page http://signin.ebay.com/aw-cgi/eBayISAPI.dll

        $LOGINURL = "https://www.tierverkehr.ch/tvd/menu.loginSubmit.action";
        $POSTFIELDS = 'benutzerkennung='. $_POST['tdb_username'] .'&pinCodeString='. $_POST['tdb_password'].'&action=Anmelden';
        $reffer = "https://www.tierverkehr.ch/tvd/menu.login.action?language=de";

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$LOGINURL);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_REFERER, $reffer);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
        //curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);  
        //curl_setopt($ch, CURLOPT_HEADER, 1);
        
        $result = curl_exec ($ch);
        //echo "<br>Anfrage:<br>".curl_getinfo($ch, CURLINFO_HEADER_OUT); 
        //echo "<br>Antwort:<br>".curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        curl_close ($ch); 
        //print     $result;
        
        //ECHO "ANGEMELDET<br>";
        
        $LOGINURL = "https://www.tierverkehr.ch/tvd/tier.tierbestandSuchen.action";
        $POSTFIELDS = 'startdatum=03.02.2011&enddatum=03.02.2011&action=Anzeigen';
        $reffer = "https://www.tierverkehr.ch/tvd/tier.tierbestandSuchen.action";

        //$header = array(
    //'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    //'Accept-Language: de-de,de;q=0.8,en-us;q=0.5,en;q=0.3',
    //'Accept-Encoding: gzip,deflate',
    //'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
    //'Keep-Alive: 115',
    //'Connection: keep-alive',
    //    ); 
        
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$LOGINURL);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        //curl_setopt($ch, CURLOPT_POST, 1); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($ch, CURLOPT_REFERER, $reffer);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
        //curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        //curl_setopt($ch, CURLINFO_HEADER_OUT, true); 
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
        
        
        $result = curl_exec ($ch);
        //echo curl_getinfo($ch, CURLINFO_HEADER_OUT);  
        curl_close ($ch); 
        //print     $result;
        
        $LOGINURL = "https://www.tierverkehr.ch/tvd/tier.tierbestandAnzeigen.action";
        $POSTFIELDS = 'startdatum=03.02.2011&enddatum=03.02.2011&action=Anzeigen';
        $reffer = "httpshttps://www.tierverkehr.ch/tvd/tier.tierbestandSuchen.action";

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$LOGINURL);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_REFERER, $reffer);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
        //curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        //curl_setopt($ch, CURLINFO_HEADER_OUT, true);  
        
        
        $result = curl_exec ($ch);
        //echo curl_getinfo($ch, CURLINFO_HEADER_OUT);  
        curl_close ($ch); 
        //print     $result;
        
        $LOGINURL = "https://www.tierverkehr.ch/tvd/jsp/tier/tierbestandAnzeigen.jsp?startdatum=".div::date_getDate()."&6578706f7274=1&action=Anzeigen&enddatum=".div::date_getDate()."&d-49653-e=2";
        $reffer = "https://www.tierverkehr.ch/tvd/tier.tierbestandAnzeigen.action";


        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$LOGINURL);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);   
        curl_setopt($ch, CURLOPT_REFERER, $reffer);
        $fp = fopen ($file, 'w+');//This is the file where we save the information
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_FILE, $fp);

        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec ($ch);
       
        
        curl_close ($ch);
        
        if($zeilen = file ($file)) {
            $form1=new form($tab1);
            $form1->setFormAttributes(basename($_SERVER['PHP_SELF']),'POST');
            $count=count($zeilen);
            for($i=1;$i<$count;$i++) {
                
                
                $zeilen[$i]=str_replace(array("\"","\n"),"",$zeilen[$i]);
                $zeile=explode(chr(9),$zeilen[$i]);
                
                if(div::date_diff('m',$zeile[3],div::date_getDate())<$_POST['animal_age']) {
                    div::http_curlRequest('https://www.tierverkehr.ch/tvd/tier.tierdetailPopupAnzeigen.action?selectedLand=756&ohrmarkennummer='.substr(str_replace(".","",$zeile[0]),3),'',$cookie_file_path,true);
                    
                    $curl_opt=array(
                        CURLOPT_URL=>'https://www.tierverkehr.ch/tvd/tier.tierdetailAnzeigen.action',
                        CURLOPT_SSL_VERIFYPEER=>false,
                        CURLOPT_COOKIEFILE=>$cookie_file_path,
                        CURLOPT_USERAGENT=>$agent,
                        CURLOPT_REFERER=>'https://www.tierverkehr.ch/tvd/tier.tierdetailPopupAnzeigen.action?selectedLand=756&ohrmarkennummer='.substr(str_replace(".","",$zeile[0]),3),
                    );
                    $result = div::http_curlRequest($curl_opt);
                    
                    
                    $matches=array();
                    preg_match_all("/<TD[^>]*>(.*):<\/TD>[^<]*<TD>(.*)<\/TD>/iU",$result,$matches);
                    $tmp_mother=explode(" - ",$matches[2][array_search("Mutter",$matches[1])]);
                    $tmp_father=explode(" - ",$matches[2][array_search("Vater",$matches[1])]);

                    $zeilen[$i].=chr(9).$tmp_mother[0].chr(9).$tmp_mother[1].chr(9).$tmp_mother[2].chr(9).$tmp_father[0].chr(9).$tmp_father[1].chr(9).$tmp_father[2];
                    
                    
                    $elm=new formElement('checkbox');
                    $elm->label=$zeile[0].str_repeat('&nbsp;',10).$zeile[3].str_repeat('&nbsp;',10).$zeile[1];
                    $elm->name=$zeile[0];
                    if(div::date_diff('m',$zeile[3],div::date_getDate())<$_POST['animal_age']) 
                        $elm->value=1;
                         
                    $form1->addElement($elm); 
                } else {     
                   unset($zeilen[$i]);   
                }
                
            }
           sort($zeilen);         
           
            //Datei aktualisieren
            $tablefile=fopen($file,"w");
            fputs($tablefile,implode("\n",$zeilen));
            fclose($tablefile);

            
            //copy($_FILES['xlsFile']['tmp_name'],TEMP_DIR.basename($_FILES['xlsFile']['tmp_name']));
            
            $file_=new formElement('hidden');
            $file_->name='uploadedFile';
            $file_->value=urlencode($file);
            $form1->addElement($file_);
            
            $act=new formElement('hidden');
            $act->name='import_action';
            $act->value='import';
            $form1->addElement($act);
            
            $form1->addButton(new formButton('Importieren',$form1->getFormName().".onsubmit();"),"end");
            
             $section1=new section('Tiere auswählen',$form1->wrapContent());
            div::htm_mergeSiteContent($content,$section1->wrapContent());
        
            
            
        }
        
    } elseif($_POST['import_action']=='import') {
               
         
        if($zeilen = file (urldecode($_POST['uploadedFile']))) {
                for($i=1;$i<count($zeilen);$i++) {
                    
                    
                    $zeile=explode(chr(9),$zeilen[$i]);
                    
                    if(isset($_POST[str_replace(array(" ","."),"_",$zeile[0])])) {
                        if(!$row=$db->exec_SELECTgetRows('*','fle_animal','a_mark=\''.$zeile[0].'\'')) {
                            if($zeile[4]=='Kreuzung') $zeile[4]='Blaubelgier';
                            if($db->exec_INSERTquery('fle_animal',array('a_mark'=>$zeile[0],'a_name'=>$zeile[1],'a_race'=>$zeile[4],'a_born'=>div::date_gerToSQL($zeile[3]),'a_gender'=>$zeile[2],'a_mother_mark'=>$zeile[11],'a_mother_name'=>$zeile[12],'a_mother_race'=>$zeile[13],'a_father_mark'=>$zeile[14],'a_father_name'=>$zeile[15],'a_father_race'=>$zeile[16]))) {
                               $content2['main'].="<b>".$zeile[0].str_repeat('&nbsp;',10).$zeile[1].' gespeichert.<br></b>'; 
                            } else {
                               $content2['main'].='<b><font color="red">'.$zeile[0].str_repeat('&nbsp;',10).$zeile[1].' konnte nicht gespeichert werden.<br></font></b>'; 
                            }
                        } else {
                               $content2['main'].='<b><font color="orange">'.$zeile[0].str_repeat('&nbsp;',10).$zeile[1].' schon vorhanden.</font></b><br>'; 
                        }
                    }   
                }
                $button=new formButton('Fertig',"location.href='".basename($_SERVER['PHP_SELF'])."'");
                div::htm_mergeSiteContent($content2,$button->wrapContent());
            }
            
            $section1=new section('Tiere speichern',$content2);
            div::htm_mergeSiteContent($content,$section1->wrapContent());
    }



    return $content; 
}

div::htm_mergeSiteContent($content,$tab->wrapContent());
div::htm_echoContent($content);


?>