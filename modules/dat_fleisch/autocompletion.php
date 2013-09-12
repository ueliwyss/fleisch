<?php
header('Content-Type: text/html; charset=ISO-8859-1');  

include('../../init.php');
$content='';
$content.='<ul>
';
$whereClause='';

$_POST['autocomplete_parameter']=utf8_decode($_POST['autocomplete_parameter']);

$parts=explode(" ",$_POST['autocomplete_parameter'])?explode(" ",$_POST['autocomplete_parameter']):array($_POST['autocomplete_parameter']);
for($i=0;$i<count($parts);$i++) {
    if($i==0) {
        $whereClause.=' WHERE ';
    } else {
        $whereClause.=' AND ';
    }
    
    $whereClause.="(c_nachname LIKE '".$parts[$i]."%' OR c_vorname LIKE '".$parts[$i]."%' OR c_strasse LIKE '".$parts[$i]."%' OR c_ort LIKE '".$parts[$i]."%')";
    
    $parts[$i]='/'.$parts[$i].'/i';
}

$res=mysql_query("SELECT uid, c_vorname, c_nachname, c_strasse, c_plz, c_ort FROM fle_client".$whereClause);

        
        while($tempRow = mysql_fetch_assoc($res))    {
            $content.="<li id=\"".$tempRow['uid']."\"><nobr>".preg_replace($parts,'<b>$0</b>',$tempRow['c_nachname']." ".$tempRow['c_vorname'])."<span class='informal'>, ".preg_replace($parts,'<b>$0</b>',$tempRow['c_strasse'].", ".$tempRow['c_plz']." ".$tempRow['c_ort'])."</span></nobr></li>";
            
        }
        
        
        
        $content.='</ul>';
        
echo $content;







