<?php

class listFromTable {
    private $list;
    private $tabItem;
    private $section;    
    
    private $listConfig;
    private $query;
    private $relations;
    
    public $tableName;
    public $listName;
    public $inSection;
    public $args;
    
    
    
    function listFromTable($tableName,$listName='default',$inSection=false,$args=array(),&$tabItem=null) {
             $this->tableName=$tableName;
             $this->listName=$listName;
             $this->inSection=$inSection;
             $this->args=$args;
             $this->tabItem=$tabItem;  
    }
    
    public function getList() {
        $this->loadRelations();
        $this->listConfig=$this->getListPalette();
        $this->query = $this->getListQuery();                        
        
        if($listConfig['type']=='grid') {
            $this->list=new gridList($this->query,$this->tabItem);
        } else {  
            $this->list=new tabList($this->query,$this->tabItem);
        }
         
        $this->list->columns=$this->getListColumns();
        

        if($this->inSection) {$this->list->showCaption=false;}

        $this->list->description=$this->listConfig['description'];
        $this->list->caption=$this->listConfig['title'];
        $this->list->itemsPerPage=$this->listConfig['itemsPerPage']?$this->listConfig['itemsPerPage']:$this->list->itemsPerPage;
        $this->list->commonActions=$this->listConfig['commonActions'];
        $this->list->actions=$this->listConfig['actions'];
        $this->list->additionalFields=is_array($this->listConfig['additionalFields'])?$this->listConfig['additionalFields']:array();
        $this->list->isSortable=$this->listConfig['sortable']?true:false;
        //$list->labelsFromDB=false;
        //}

        //$list->items=iList::fetchItems($query,$listConfig['additionalFields']);

        //div::var_saveObject($list,$list->getName()."_".$tableName."_".$listName);


        if($inSection) {
            $this->section=new section($this->listConfig['title'],$this->list->wrapContent());
            $content=$this->section->wrapContent();
        } else {
            $content=$this->list->wrapContent();
        }


        return $content;
    }
    
    private function getListPalette() {
        global $TABLES;

        if($TABLES[$this->tableName]['lists'][$this->listName]) {
            return $TABLES[$this->tableName]['lists'][$this->listName];
        } elseif($TABLES[$this->tableName]['lists']['default']) {
            return $TABLES[$this->tableName]['lists']['default'];
        }
    }
    
    private function getListQuery() {
        global $db;

        $query = array(
            'select'=>$this->getSelectForList(),
            'local_table'=>$tableName.($this->listConfig['local_table']?",".$this->listConfig['local_table']:''),
            'tables'=>implode(',',$this->getTablesForList()),
            'whereClause'=>$this->getWhereForList(),
            'groupBy'=>$this->listConfig['groupBy'],
            'orderBy'=>$this->listConfig['orderBy'],
            'limit'=>'',
            'sortable_field'=>$this->listConfig['sortable'],
            'join'=>$this->getJoinsForList(),
        );
    
        echo $db->view_array($query);
        return $query;
    }
    
    private function getSelectForList($alias=true,$additional=true) {
        global $TABLES,$db;
        
        $querytables=$this->getRelatedTableNames();
        array_unshift($querytables,$this->tableName);
          
        
        $fields=$TABLES[$this->tableName]['lists'][$this->listName]['fields'];
        $fields=explode(",",$fields);
        $select='';
        foreach($fields as $field) {
            $pos=stripos($field,".");
            if(!$pos===false) {
                $tmp=$field;
                 $field=substr($tmp,$pos+1,strlen($tmp)-$pos-1);
                 $table=substr($tmp,0,$pos-1); 
            } else {
                $table="";
            }                    
            foreach($querytables as $relTab) {
                if($table) $relTab=$table;
                if($TABLES[$relTab]['fields'][$field]) {
                    if($TABLES[$relTab]['fields'][$field]['foreign_display'] AND $alias) {
                        $select.=$this->getConcatForForeignKeys($field);
                    } else {
                        $select.=$relTab.'.'.$field;
                        if($alias) $select.=' AS \''.$relTab.'.'.$field.'\'';
                        $select.=','; 
                    }
                    
                    break;
                }      
            }
            
            
        }
        if($additional) {
              $primaryKeys=$db->getPrimaryKey($this->tableName);
              $select.=$this->tableName.'.'.$primaryKeys[0].' AS primaryKey,';
        }
       
        return substr($select,0,strlen($select)-1);
    }
    
    private function getRelatedTableNames() {
        $tableNames=array();
        
        foreach($relations as $rel) {
            if($rel['relation']==('m:1')||$rel['relation']==('1:m')) { 
                $tableNames[]=($rel['table_m']==$this->tableName)?$rel['table_1']:$rel['table_m'];   
            } elseif($rel['relation']=='m:m') {
                $tableNames[]=($rel['table_1']==$this->tableName)?$rel['table_2']:$rel['table_1'];
                $tableNames[]=$rel['table_mm'];  
            }
        }
        return $tableNames;
    }
    
    private function loadRelations() {
        $this->relations=table::getRelations($this->tableName);
    }
    
    private function getTablesForList() {
        
        $tableNames=array();
        
        foreach($relations as $rel) {
            if($rel['relation']=='m:m') {
                if($this->hasFieldsinSelect($this->tableName,$$this->listName,$rel['table_1']==$this->tableName?$rel['table_2']:$rel['table_1'])) {
                    
                    $tableNames[]=($rel['table_1']==$this->tableName)?$rel['table_2']:$rel['table_1'];
                    $tableNames[]=$rel['table_mm'];
                }      
            }
        }
        
        $tableNames[]=$this->tableName;
        
        return $tableNames;
    }
    
    private function getWhereForList() {
        global $TABLES;
         
        
        $where=array();
        foreach($this->relations as $rel) {
            if($rel['relation']==('m:1')||$rel['relation']==('1:m') AND ($rel['table_1']!=$this->tableName)) {
                
            } elseif($rel['relation']=='m:m') {
                if($this->hasFieldsinSelect($this->tableName,$this->listName,$rel['table_2']==$this->tableName?$rel['table_1']:$rel['table_2'])) {
                    
                     $where[]='('.$rel['table_1'].'.'.$rel['key_1'].'='.$rel['table_mm'].'.'.$rel['key_mm_1'].')';
                     $where[]='('.$rel['table_2'].'.'.$rel['key_2'].'='.$rel['table_mm'].'.'.$rel['key_mm_2'].')';
                } 
            }
        }
        
        if($TABLES[$this->tableName]['lists'][$this->listName]['whereClause']) {          
            $where[]='('.formElement::parseString($TABLES[$this->tableName]['lists'][$this->listName]['whereClause']).')';
        }       
        return implode(' AND ',$where);
    }
    
    private function getJoinsForList() {
        global $db;
        //echo $db->view_array($relations);
        $join='';
        foreach($this->relations as $rel) {
            if($rel['relation']==('m:1')||$rel['relation']==('1:m') AND ($rel['table_1']!=$this->tableName)) {
                $join.=' LEFT JOIN '.$rel['table_1'].' ON '.$rel['table_1'].'.'.$rel['key_1'].'='.$rel['table_m'].'.'.$rel['key_1_foreign'];
            } elseif($rel['relation']=='m:m') {                                                             
                if($rel['table_2']==$this->tableName) {
                     $rel['table_2']=$rel['table_1'];
                     $rel['table_1']=$this->tableName;
                }
                //$join.=' LEFT ('.$rel['table_mm'].' LEFT '.$rel['table_2'].' ON '.$rel['table_mm'].'.'.$rel['key_mm_2'].' = '.$rel['table_2'].'.'.$rel['key_2'].') ON '.$rel['table_1'].'.'.$rel['key_1'].' = '.$rel['table_mm'].'.'.$rel['key_mm_1'];
                

            }
        } 
              
        return $join;
    }
    
    private function getConcatForForeignKeys($fieldName) {
        global $TABLES;
                
        
        foreach($this->relations as $rel) {
           if($rel['key_1_foreign']==$fieldName) {
                $foreign_display=explode(',',$TABLES[$this->tableName]['fields'][$fieldName]['foreign_display']);
                $display_query='';
                for($i=0;$i<count($foreign_display);$i++) {
                    $display_query.=$rel['table_1'].".".$foreign_display[$i];
                    if($i!=(count($foreign_display)-1)) {
                        $display_query.=",', ',";
                    }
                }
                $display_query=" CONCAT(".$display_query.") AS '".$this->tableName.".".$fieldName."',";
                return $display_query;
           }
            
        }
    }
    
    private function hasFieldsinSelect($foreignTable) {
        global $db, $TABLES;
        
        $list=explode(',',$this->getSelectForList(false,false));
        $foreignFields=table::getAllFieldConfigs($foreignTable);
        //echo $db->view_array($list);
        foreach ($list as $listCol) {
            foreach ($foreignFields as $foreignCol=>$att2) {
                
                $listCol2=explode('.',$listCol);
                
                //echo $foreignCol."::".$listCol2[1]."<br>";
                //echo $foreignTable."::".$listCol2[0]."<br>";
                if($foreignCol==$listCol2[1] AND $foreignTable==$listCol2[0]) {
                    return true;
                }
            }
        }   
        return false;        
    }
    
    
    private function getListColumns() {
        global $db;

        $select=explode(',',$this->getSelectForList($this->tableName,$this->listName,false,false));
        
        
        $columns=array();

        
        foreach($select as $fieldName) {
            $tmp=null;

            
            $tmp=explode(".",$fieldName);
            $tmpFieldName=$tmp[1];
            $tmpTableName=$tmp[0];
        

            //$tableName_ofField = table::getListTableNameOfField($this->tableName,$tmpFieldName,$this->listName);
            
            //$this->listNameConfig=table::getListConfig($this->tableName,$tmpFieldName,$this->listName);
            //div::debug($tableName."::".$tmpFieldName."::".$this->listName."-->listConfig",$this->listNameConfig);
            $columns[$fieldName]=array(
                'isImage'=>$this->listNameConfig['isImage'],
                'imagePath'=>$this->listNameConfig['imagePath'],
                'dbField'=>$tmpFieldName,
                'label'=>table::getFieldLabel($tmpTableName,$tmpFieldName),
                'allocation'=>table::getAllocation($tmpTableName,$tmpFieldName),
            );
        }
        
        return $columns;
    }
}

?>