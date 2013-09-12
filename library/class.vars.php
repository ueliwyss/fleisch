<?
    
$TABLES['vars']=array(
        'ctrl'=>array(
            'isDbTable'=>true,
            'type'=>'normal',
        ),
        'fields'=>array(
            'uid'=>array(
                'label'=>'id',
                'description'=>'Primärschlüssel-Feld der Tabelle',
                'dbconfig'=>Array(
                    'type'=>'int',
                    'length'=>11,
                    'autoIncrement'=>1,
                    'primaryKey'=>1
                ),
            ),
            'v_key'=>array(
                'label'=>'Schlüssel',
                'description'=>'Text-Schlüssel',
                'formconfig'=>array(
                        'all'=>array(
                        'type'=>'text',
                        'eval'=>'trim,required',
                    ),
                ),
                'dbconfig'=>array(
                    'type'=>'varchar',
                    'length'=>100,
                ),
            ),
            'v_value'=>array(
                'label'=>'Wert',              
                'description'=>'Gespeicherter Wert',
                'formconfig'=>array(
                        'all'=>array(
                        'type'=>'text',
                        'eval'=>'trim,required',
                    ),
                ),
                'dbconfig'=>array(
                    'type'=>'varchar',
                    'length'=>100,
                ),
            ),
            'v_description'=>array(
                'label'=>'Beschreibung',
                'description'=>'',
                'formconfig'=>array(
                    'all'=>array(
                        'type'=>'textarea',
                        'eval'=>'trim'
                    ),
                ),
                'dbconfig'=>array(
                    'type'=>'varchar',
                    'length'=>400,
                ),
            ),
        ),
        'forms'=>array(
            'default'=>array(
            ),
            'new'=>array(
                'title'=>'Bestellung',
                'description'=>'Neuer Wert',
                'fields'=>'v_key,v_value,v_description',
            ),
            'edit'=>array(
                'title'=>'Wert bearbeiten',
                'description'=>'',
                'fields'=>'v_key,v_value,v_description',
            ),
        ),
        'lists'=>array(
            'listAll'=>array(
                'title'=>'Werte',
                'description'=>'Hier werden alle erfassten Werte aufgelistet.',
                'fields'=>'v_key,v_value,v_description',
                'commonActions'=>'drop',
                'actions'=>array(
                    'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
                    'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
                ),
            ),
        ),
        'defaultRows'=>array(
            
        ),
    );
    
class vars {
    public static function getVar($key) {
        global $db;
        
        if($row=$db->exec_SELECTgetRows('v_value','fle_vars','v_key=\''.$key.'\''))
        return $row[0]['v_value'];
        else return false;
    }
    
    public static function setDefaultRow($key,$value='',$description='') {
        global $TABLES;
        if(!is_array($key)) $key=array('v_key'=>$key,'v_value'=>$value,'v_description'=>$description);
        
        $TABLES['vars']['defaultRows'][]=$key;
        
    }
}
?>