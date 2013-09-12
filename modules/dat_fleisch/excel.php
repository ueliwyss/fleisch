<?

$zeilen = file ('test.xls');

for($i=1;$i<count($zeilen);$i++) {
    $zeile=explode(chr(9),$zeilen[$i]);
    echo $zeile[2];
}

?>