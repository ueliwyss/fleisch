<?php
global $RELATIONS;
    
$RELATIONS[]=array(
      'relation'=>'m:1',
      'table_m'=>'fle_order',
      'table_1'=>'fle_client',
      'table_mm'=>'',
      'table_2'=>'',
      'key_m'=>'uid',
      'key_1'=>'uid',
      'key_1_foreign'=>'o_client_id',
      'key_mm_1'=>'',
      'key_mm_2'=>'',
);
$RELATIONS[]=array(
      'relation'=>'m:1',
      'table_m'=>'fle_order',
      'table_1'=>'fle_animal',
      'table_mm'=>'',
      'table_2'=>'',
      'key_m'=>'uid',
      'key_1'=>'uid',
      'key_1_foreign'=>'o_animal_id',
      'key_mm_1'=>'',
      'key_mm_2'=>'',
);    

$TABLES['fle_client']=array(
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
                    'c_vorname'=>array(
                        'label'=>'vorname',
                        'description'=>'Vorname des Kunden.',
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
                    'c_nachname'=>array(
                        'label'=>'nachname',
                        'description'=>'Nachname des Kunden.',
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
                    'c_strasse'=>array(
                        'label'=>'strasse',
                        'description'=>'Adresse des Kunden.',
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
                    'c_plz'=>array(
                        'label'=>'PLZ',
                        'description'=>'Postleitzahl des Kunden.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,num,required',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>11,
                        ),
                    ),
                    'c_ort'=>array(
                        'label'=>'ort',
                        'description'=>'Wohnort des Kunden.',
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
                    'c_tel'=>array(
                        'label'=>'telefonnummer',
                        'description'=>'Telefonnummer des Kunden. Beispiel: <b>031 999 9999</b>.',
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
                    'c_mobile'=>array(
                        'label'=>'mobile',
                        'description'=>'Handy-Nummer.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>200,
                        ),
                    ),
                    'c_email'=>array(
                        'label'=>'email',
                        'description'=>'Email Adresse des Kunden.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,email',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>200,
                        ),
                    ),
                    'c_hack'=>array(
                        'label'=>'Hackfleisch',
                        'description'=>'Portionengrösse Hackfleisch (Als Zahl in g).',
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
                    'c_ragout'=>array(
                        'label'=>'Ragout',
                        'description'=>'Portionengrösse Ragout (Als Zahl in g).',
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
                    'c_gesch'=>array(
                        'label'=>'Geschnetzeltes',
                        'description'=>'Portionengrösse Geschnetzeltes (Als Zahl in g).',
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
                    'c_braten'=>array(
                        'label'=>'Braten',
                        'description'=>'Bratengrösse (Als Zahl in g).',
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
                    'c_plaetzli'=>array(
                        'label'=>'Anzahl Plätzli',
                        'description'=>'Anzahl Plätzli pro Seckli',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,num,required',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>11,
                        ),
                    ),
                    'c_leber'=>array(
                        'label'=>'Leber',
                        'description'=>'Nimmt der Kunde Leber?',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'checkbox',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>1,
                        ),
                        'allocation'=>array(
                            0=>'Nein',
                            1=>'Ja',
                        ),
                    ),
                    
                    'c_numleber'=>array(
                        'label'=>'Anzahl Lebern',
                        'description'=>'Anzahl Lebern',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,num',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>11,
                        ),
                    ),
                    'c_suppenfleisch'=>array(
                        'label'=>'Suppenfleisch',
                        'description'=>'Nimmt der Kunde Suppenfleisch?',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'checkbox',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>1,
                        ),
                        'allocation'=>array(
                            0=>'Nein',
                            1=>'Ja',
                        ),
                    ),
                    'c_tiernahrung'=>array(
                        'label'=>'Tiernahrung',
                        'description'=>'Nimmt der Kunde Innereien als Tiernahrung?',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'checkbox',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>1,
                        ),
                        'allocation'=>array(
                            0=>'Nein',
                            1=>'Ja',
                        ),
                    ),
                    'c_comment'=>array(
                        'label'=>'Bemerkungen',
                        'description'=>'Bemerkungen',
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
                    'c_specials'=>array(
                        'label'=>'Spezialwünsche',
                        'description'=>'Spezialwünsche des Kunden, die jede Bestellung betreffen.',
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
                    'c_knochen'=>array(
                        'label'=>'Markknochen',
                        'description'=>'Nimmt der Kunde Markknochen?',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'checkbox',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>1,
                        ),
                        'allocation'=>array(
                            0=>'Nein',
                            1=>'Ja',
                        ),
                    ),
                    'c_section'=>array(
                        'label'=>'Sektion',
                        'description'=>'Zu welcher Sektion oder Liefertour gehört der Kunde?',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'select',
                                'items'=>array(
                                    1=>'Bern',
                                    2=>'Oberland'
                                ),
                                'eval'=>'required',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                        'allocation'=>array(
                            1=>'Bern',
                            2=>'Oberland',
                        ),
                        
                    ),
                    'c_orderCycle'=>array(
                        'label'=>'Bestellzyklus',
                        'description'=>'In welchen Abständen bestellt der Kunde normalerweise?',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'select',
                                'items'=>array(
                                    1=>'vierteljährlich',
                                    2=>'halbjährlich',
                                    3=>'jährlich',
                                    4=>'unregelmässig',
                                    5=>'selten',
                                    6=>'anders',
                                ),
                                'eval'=>'required',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>2,
                        ),
                        'allocation'=>array(
                            1=>'vierteljährlich',
                            2=>'halbjährlich',
                            3=>'jährlich',
                            4=>'unregelmässig',
                            5=>'selten',
                            6=>'anders',
                        ),
                        
                    ),
                    
                    'c_nextRequest'=>array(
                        'label'=>'Nächste Anfrage',                                         
                        'description'=>'Wann soll ich errinnert werden, den Kunden für eine Bestellung anzufragen?',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,date',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'date',
                            /*'default'=>'CURRENT_TIMESTAMP',*/
                        ),
                    ),
                    
                    'c_coordinate'=>array(
                        'label'=>'Koordinaten',
                        'description'=>'Koordinaten (Breitengrad;Längengrad)',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'coordinate',
                                'eval'=>'trim,required',
                                'coordinate_addressFields'=>'[c_strasse], [c_plz] [c_ort]',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100, 
                        ),
                    ),
                    
                ),
                'forms'=>array(
                    'default'=>array(
                    ),
                    'new'=>array(
                        'title'=>'Neuen Kunden erfassen',
                        'description'=>'',
                        'fields'=>'c_vorname,c_nachname,c_strasse,c_plz,c_ort,c_coordinate,c_tel,c_mobile,c_email,c_section,c_hack,c_gesch,c_ragout,c_plaetzli,c_braten,c_suppenfleisch,c_leber,c_numleber,c_knochen,c_tiernahrung,c_specials,c_comment',
                    ),
                    'edit'=>array(
                        'title'=>'Kunden bearbeiten',
                        'description'=>'',
                        'fields'=>'[Personalien],c_vorname,c_nachname,c_strasse,c_plz,c_ort,c_coordinate,c_tel,c_mobile,c_email,[Sektion],c_section,[Portionengrössen],c_hack,c_gesch,c_ragout,c_plaetzli,c_braten,[Spezialwünsche],c_suppenfleisch,c_leber,c_numleber,c_knochen,c_tiernahrung,c_specials,[Bemerkungen],c_comment',
                    ),
                ),
                'lists'=>array(
                    'default'=>array(
                        'title'=>'Benutzer',
                        'fields'=>'c_vorname,c_nachname,c_strasse,c_plz,c_ort,c_tel,c_mobile,c_email,c_hack,c_gesch,c_ragout,c_leber,c_numleber,c_tiernahrung',
                        'commonActions'=>'drop',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
                            'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
                        ),
                    ),
                    'listAll'=>array(
                        'title'=>'Kunden',
                        'description'=>'Hier werden alle erfassten Kunden aufgelistet.',
                        'fields'=>'c_vorname,c_nachname,c_strasse,c_plz,c_ort,c_tel,c_mobile,c_email',
                        'commonActions'=>'drop',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
                            'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
                        ),
                    ),
                    'listAll_client'=>array(
                        'title'=>'Kunden',
                        'description'=>'Hier werden alle erfassten Kunden aufgelistet.',
                        'fields'=>'c_vorname,c_nachname,c_strasse,c_plz,c_ort,c_tel,c_mobile,c_email', 
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
                            'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
                        ),
                    ),
                    
                ),
                'resources'=>array(
                    'fle_client'=>array(
                        'name'=>'Kunden',
                        'description'=>'Kunden.',
                    ),
                ),
            );
$TABLES['fle_animal']=array(
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
                    'a_name'=>array(
                        'label'=>'name',
                        'description'=>'Name des Tieres.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                    ),
                    'a_mark'=>array(
                        'label'=>'Ohrmarkennummer',
                        'description'=>'Ohrmarkennummer.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                    ),
                    'a_race'=>array(
                        'label'=>'Rasse',
                        'description'=>'Rasse des Tieres.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'select',
                                'items'=>array(
                                    'Charolais'=>'Charolais',
                                    'Limousin'=>'Limousin',
                                    'Simmentaler'=>'Simmentaler',
                                    'Blaubelgier'=>'Blaubelgier',
                                    'Piemonteser'=>'Piemonteser',
                                ),
                                'eval'=>'',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                        
                    ),
                    'a_selldate'=>array(
                        'label'=>'Verteildatum',
                        'description'=>'Datum, an dem das Fleisch abgepackt und verteilt wird.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,date',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'date',
                            /*'default'=>'CURRENT_TIMESTAMP',*/
                        ),
                    ),
                    'a_weight_val'=>array(
                        'label'=>'Geschätztes Gewicht',
                        'description'=>'Gewicht, das vor dem Schlachten geschätzt wird.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,num',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>11,
                        ),
                    ),
                    'a_weight_real'=>array(
                        'label'=>'Effektives Gewicht',
                        'description'=>'Gewicht des Verwertbaren Fleisches.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,num',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>11,
                        ),
                    ),
                    'a_gender'=>array(
                        'label'=>'Geschlecht',
                        'description'=>'Geschlecht des Kalbes.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                    ),
                     'a_type'=>array(
                        'label'=>'Fleischtyp',
                        'description'=>'Ist es ein Kalb oder ein Jungrind?.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'select',
                                'items'=>array(
                                    'Jungrind'=>'Jungrind',
                                    'Kalb'=>'Kalb',  
                                ),
                                'eval'=>'required',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                    ),
                    'a_born'=>array(
                        'label'=>'Geburtsdatum',
                        'description'=>'Geburtsdatum des Kalbes.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,date',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'date',
                            /*'default'=>'CURRENT_TIMESTAMP',*/
                        ),
                    ),
                    'a_mother_mark'=>array(
                        'label'=>'Nr. Mutter',
                        'description'=>'Ohrmarkennummer der Mutter.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                    ),
                    'a_mother_name'=>array(
                        'label'=>'Name der Mutter',
                        'description'=>'Name der Mutter des Kalbes.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                    ),
                    'a_mother_race'=>array(
                        'label'=>'Rasse Mutter',
                        'description'=>'Rasse der Mutter.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'readOnly',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                        
                    ),
                    'a_father_mark'=>array(
                        'label'=>'Nr. Vater',
                        'description'=>'Ohrmarkennummer des Vaters.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                    ),
                    'a_father_name'=>array(
                        'label'=>'Name des Vaters',
                        'description'=>'Name des Vaters.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                    ),
                    'a_father_race'=>array(
                        'label'=>'Rasse Vater',
                        'description'=>'Rasse des Vaters.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'readOnly',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                        
                    ),
                ),
                   
                'forms'=>array(
                    'default'=>array(
                    ),
                    'new'=>array(
                        'title'=>'Neues Tier erfassen',
                        'description'=>'',
                        'fields'=>'a_selldate,a_type,a_name,a_race,a_weight_val,a_weight_real,a_mother_name,a_father_name,a_mark',
                    ),
                    'edit'=>array(
                        'title'=>'Tier bearbeiten',
                        'description'=>'',
                        'fields'=>'a_selldate,a_type,a_name,a_race,a_weight_val,a_weight_real,a_mother_name,a_father_name,a_mark',
                    ),
                ),
                'lists'=>array(
                    'default'=>array(
                        'title'=>'Tiere',
                        'fields'=>'a_selldate,a_type,a_name,a_race,a_weight_val,a_weight_real,a_mother_name,a_father_name,a_mark',
                        'commonActions'=>'drop',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
                            'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
                        ),
                    ),
                    'listAll_read'=>array(
                        'title'=>'Tiere',
                        'description'=>'Hier werden alle erfassten Tiere aufgelistet.',
                        'fields'=>'a_born,a_selldate,a_type,a_name,a_race,a_weight_val,a_weight_real,a_mother_name,a_father_name,a_mark',
                        'commonActions'=>'drop',
                        'orderBy'=>'a_born',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
                        ),
                    ),
                    'listAll_full'=>array(
                        'title'=>'Tiere',
                        'description'=>'Hier werden alle erfassten Tiere aufgelistet.',
                        'fields'=>'a_born,a_selldate,a_type,a_name,a_race,a_weight_val,a_weight_real,a_mother_name,a_father_name,a_mark',
                        'commonActions'=>'drop',
                        'orderBy'=>'a_born',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
                            'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
                        ),
                    ),
                    'listAll_full_client'=>array(
                        'title'=>'Tiere',
                        'description'=>'Hier werden alle erfassten Tiere aufgelistet.',
                        'fields'=>'a_born,a_selldate,a_type,a_name,a_race,a_weight_val,a_weight_real,a_mother_name,a_father_name,a_mark',
                        'orderBy'=>'a_born',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
                            'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
                        ),
                    ),
                ),
                'resources'=>array(
                    'fle_animal'=>array(
                        'name'=>'Tiere',
                        'description'=>'Erfasste Tiere.',
                    ),
                ),
            );
            
$TABLES['fle_order']=array(
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
                    'o_client_id'=>array(
                        'label'=>'Kunde',
                        'description'=>'Kunde der die Bestellung ausgelöst hat.',
                        'foreign_table'=>'fle_client',
                        'foreign_key'=>'uid',
                        'foreign_display'=>'[c_nachname] [c_vorname], [c_ort]',
                        /*'foreign_where'=>'d_company_id=<func>$user->curusr_getCompany()</func>',*/
                        'formconfig'=>array(
                            'default'=>array(
                                'type'=>'select',
                                'foreign_display'=>'[c_nachname], [c_vorname]',
                                'eval'=>'required',
                            ),
                            'edit'=>array(
                                'readOnly'=>true,
                                'type'=>'select',
                                'foreign_display'=>'[c_nachname] [c_vorname], [c_ort]',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>11,
                        ),
                    ),
                    'o_animal_id'=>array(
                        'label'=>'Tier',
                        'description'=>'Von welchem Tier ist das Fleisch der Bestellung?',
                        'foreign_table'=>'fle_animal',
                        'foreign_key'=>'uid',
                        'foreign_display'=>'[a_name] am [a_selldate]',
                        /*'foreign_where'=>'d_company_id=<func>$user->curusr_getCompany()</func>',*/
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'select',
                                'foreign_display'=>'[a_name] am [a_selldate]',
                                'eval'=>'required',
                                'foreign_where'=>'a_selldate > NOW()',
                                'orderBy'=>'a_selldate DESC'
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>11,
                        ),
                    ),
                    'o_createdate'=>array(
                        'label'=>'Bestelldatum',
                        'description'=>'Bestelldatum.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,date',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'date',
                            /*'default'=>'CURRENT_TIMESTAMP',*/
                        ),
                    ),
                    'o_comment'=>array(
                        'label'=>'Bemerkungen',
                        'description'=>'Bemerkungen',
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
                    'o_specials'=>array(
                        'label'=>'Spezialwünsche',
                        'description'=>'Spezielle Wünsche des Kunden für diese Bestellung.',
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
                    'o_fleisch_ord'=>array(
                        'label'=>'Menge Fleisch',
                        'description'=>'Bestellte Menge in kg.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,required,num_fromto',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                    ),
                    'o_fleisch_real'=>array(
                        'label'=>'Effektives Fleischgewicht',
                        'description'=>'Gewogene Menge in kg.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,float',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'float', 
                        ),
                    ),
                    'o_kaese_ord'=>array(
                        'label'=>'Menge Käse',
                        'description'=>'Bestellte Menge in kg.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,float',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'float', 
                        ),
                    ),
                    'o_kaese_real'=>array(
                        'label'=>'Effektive Menge Käse',
                        'description'=>'Gewogene Menge in kg',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,float',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'float', 
                        ),
                    ),
                    'o_wurst_ord'=>array(
                        'label'=>'Menge Wurst',
                        'description'=>'Bestellte Menge in kg.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,num',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>11, 
                        ),
                    ),
                    'o_wurst_real'=>array(
                        'label'=>'Effektive Menge Wurst',
                        'description'=>'Gewogene Menge in kg',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,float',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'float', 
                        ),
                    ),
                    'o_trockenfleisch_ord'=>array(
                        'label'=>'Menge Trockenfleisch',
                        'description'=>'Bestellte Menge in kg.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,float',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'float', 
                        ),
                    ),
                    'o_trockenfleisch_real'=>array(
                        'label'=>'Effektive Menge Trockenfleisch',
                        'description'=>'Gewogene Menge in kg',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,float',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'float', 
                        ),
                    ),
                    'o_filet_ord'=>array(
                        'label'=>'Menge Filet',
                        'description'=>'Bestellte Menge in kg.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,float_fromto',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'varchar',
                            'length'=>100,
                        ),
                    ),
                    'o_filet_real'=>array(
                        'label'=>'Effektive Menge Filet',
                        'description'=>'Gewogene Menge in kg.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,float',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'float', 
                        ),
                    ),
                    'o_leber'=>array(
                        'label'=>'Leber',
                        'description'=>'Nimmt der Kunde Leber?',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'checkbox',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>1,
                        ),
                        'allocation'=>array(
                            0=>'Nein',
                            1=>'Ja',
                        ),
                    ),
                    'o_numleber'=>array(
                        'label'=>'Anzahl Lebern',
                        'description'=>'Anzahl Lebern',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,num',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>11,
                        ),
                    ),
                    'o_tiernahrung'=>array(
                        'label'=>'Tiernahrung',
                        'description'=>'Nimmt der Kunde Innereien als Tiernahrung?',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'checkbox',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>1,
                        ),
                        'allocation'=>array(
                            0=>'Nein',
                            1=>'Ja',
                        ),
                    ),
                    'o_comment'=>array(
                        'label'=>'Bemerkungen',
                        'description'=>'Bemerkungen',
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
                    'o_knochen'=>array(
                        'label'=>'Markknochen',
                        'description'=>'Nimmt der Kunde Markknochen?',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'checkbox',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>1,
                        ),
                        'allocation'=>array(
                            0=>'Nein',
                            1=>'Ja',
                        ),
                    ),
                    'o_rechnung_sent'=>array(
                        'label'=>'Rechnungsdatum',
                        'description'=>'Tag, an dem die Rechnung gedruckt wurde.<br>Falls die Rechnung erst später abgeschickt wurde, kann das Datum manuell korrigiert werden.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,date',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'date',
                            /*'default'=>'CURRENT_TIMESTAMP',*/
                        ),
                    ),
                    'o_payd'=>array(
                        'label'=>'Bezahlt am',
                        'description'=>'Datum an dem der Kunde bezahlt hat.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,date',
                            ),
                        ),
                        'dbconfig'=>array(
                            'type'=>'date',
                            /*'default'=>'CURRENT_TIMESTAMP',*/
                        ),
                    ),
                    'o_order'=>array(
                        'label'=>'Reihenfolge',
                        'description'=>'Reihenfolge der Bestellung in zugehörigkeit zu einem Tier.',
                        'formconfig'=>array(
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>11,
                        ),
                    ),
                    
                ),
                'forms'=>array(
                    'default'=>array(
                    ),
                    'new'=>array(
                        'title'=>'Bestellung',
                        'description'=>'Neue Bestellung erfassen.',
                        'fields'=>'o_client_id,o_animal_id,o_fleisch_ord,o_fleisch_real,o_filet_ord,o_filet_real,o_kaese_ord,o_kaese_real,o_wurst_ord,o_wurst_real,o_trockenfleisch_ord,o_trockenfleisch_real,o_leber,o_numleber,o_tiernahrung,o_knochen,o_specials,o_comment,o_createdate,o_rechnung_sent,o_payd',
                    ),
                    'edit'=>array(
                        'title'=>'Bestellung bearbeiten',
                        'description'=>'',
                        'fields'=>'o_client_id,o_animal_id,o_fleisch_ord,o_filet_ord,o_kaese_ord,o_wurst_ord,o_trockenfleisch_ord,o_leber,o_numleber,o_tiernahrung,o_knochen,o_specials,o_comment,o_createdate,o_rechnung_sent,o_payd',
                    ),
                    'real_weights'=>array(
                        'title'=>'Gewogene Gewichte eintragen',
                        'description'=>'',
                        'fields'=>'o_fleisch_real,o_filet_real,o_kaese_real,o_wurst_real,o_trockenfleisch_real',
                    ),
                ),
                'lists'=>array(
                    'default'=>array(
                        'title'=>'Bestellungen',
                        'fields'=>'o_client_id,o_animal_id,o_fleisch_ord,o_kaese_ord,o_wurst_ord,o_trockenfleisch_ord,o_leber,o_tiernahrung,o_knochen,o_createdate',
                        'commonActions'=>'drop',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
                            'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
                        ),
                    ),
                    'listAll'=>array(
                        'title'=>'Bestellungen',
                        'description'=>'Hier werden alle erfassten Bestellungen aufgelistet.',
                        'fields'=>'o_client_id,o_animal_id,o_fleisch_ord,o_kaese_ord,o_wurst_ord,o_trockenfleisch_ord,o_leber,o_tiernahrung,o_knochen,o_rechnung_sent,o_payd',
                        'commonActions'=>'drop',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
                            'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
                        ),
                    ),
                    'listAll_client'=>array(
                        'title'=>'Bestellungen',
                        'description'=>'Hier werden alle erfassten Bestellungen aufgelistet.',
                        'fields'=>'o_client_id,o_animal_id,o_fleisch_ord,o_kaese_ord,o_wurst_ord,o_trockenfleisch_ord,o_leber,o_tiernahrung,o_knochen,o_rechnung_sent,o_payd',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'<local_table>\',\'<uid>\')',
                            'drop'=>'javascript:top.dropElement(\'<local_table>\',\'<uid>\')',
                        ),
                    ),
                    'animalOrders'=>array(
                        'title'=>'Bestellungen',
                        'description'=>'Bestellungen, die zu diesem Tier eingegangen sind.',
                        'fields'=>'c_nachname,c_vorname,c_ort,o_fleisch_ord,o_filet_ord,o_kaese_ord,o_wurst_ord,o_trockenfleisch_ord',
                        'sortable'=>'fle_order.o_order',
                        'orderBy'=>'o_order',
                        'whereClause'=>'fle_order.o_animal_id=<action>uid</action>',
                        'itemsPerPage'=>1000,
                        'commonActions'=>'drop',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'fle_order\',\'<uid>\',\'NO_UID\',0)',
                            'drop'=>'javascript:top.dropElement(\'fle_order\',\'<uid>\',\'<foreign_uid>\')',
                        ),
                    ),
                    'animalOrders_bern'=>array(
                        'title'=>'Bern',
                        'description'=>'Bestellungen, die zu diesem Tier eingegangen sind.',
                        'fields'=>'o_order,c_nachname,c_vorname,c_ort,o_fleisch_ord,o_filet_ord,o_kaese_ord,o_wurst_ord,o_trockenfleisch_ord',
                        'sortable'=>'fle_order.o_order',
                        'orderBy'=>'o_order',
                        'whereClause'=>'fle_client.c_section=1 AND fle_order.o_animal_id=<action>uid</action>',
                        'itemsPerPage'=>1000,
                        'commonActions'=>'drop',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'fle_order\',\'<uid>\',\'NO_UID\',0)',
                            'drop'=>'javascript:top.dropElement(\'fle_order\',\'<uid>\',\'<foreign_uid>\')',
                        ),
                    ),
                    'animalOrders_oberland'=>array(
                        'title'=>'Oberland',
                        'description'=>'Bestellungen, die zu diesem Tier eingegangen sind.',
                        'fields'=>'o_order,c_nachname,c_vorname,c_ort,o_fleisch_ord,o_filet_ord,o_kaese_ord,o_wurst_ord,o_trockenfleisch_ord',
                        'sortable'=>'fle_order.o_order',
                        'orderBy'=>'o_order',
                        'whereClause'=>'fle_client.c_section=2 AND fle_order.o_animal_id=<action>uid</action>',
                        'itemsPerPage'=>1000,
                        'commonActions'=>'drop',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'fle_order\',\'<uid>\',\'NO_UID\',0)',
                            'drop'=>'javascript:top.dropElement(\'fle_order\',\'<uid>\',\'<foreign_uid>\')',
                        ),
                    ),
                    
                    'animalOrders_bern_client'=>array(
                        'title'=>'Bern',
                        'description'=>'Bestellungen, die zu diesem Tier eingegangen sind.',
                        'fields'=>'o_order,c_nachname,c_vorname,c_ort,o_fleisch_ord,o_filet_ord,o_kaese_ord,o_wurst_ord,o_trockenfleisch_ord',
                        'sortable'=>'fle_order.o_order',
                        'orderBy'=>'o_order',
                        'whereClause'=>'fle_client.c_section=1 AND fle_order.o_animal_id=<action>uid</action>',
                        'itemsPerPage'=>1000,
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'fle_order\',\'<uid>\',\'NO_UID\',0)',
                            'drop'=>'javascript:top.dropElement(\'fle_order\',\'<uid>\',\'<foreign_uid>\')',
                        ),
                    ),
                    'animalOrders_oberland_client'=>array(
                        'title'=>'Oberland',
                        'description'=>'Bestellungen, die zu diesem Tier eingegangen sind.',
                        'fields'=>'o_order,c_nachname,c_vorname,c_ort,o_fleisch_ord,o_filet_ord,o_kaese_ord,o_wurst_ord,o_trockenfleisch_ord',
                        'sortable'=>'fle_order.o_order',
                        'orderBy'=>'o_order',
                        'whereClause'=>'fle_client.c_section=2 AND fle_order.o_animal_id=<action>uid</action>',
                        'itemsPerPage'=>1000,
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'fle_order\',\'<uid>\',\'NO_UID\',0)',
                            'drop'=>'javascript:top.dropElement(\'fle_order\',\'<uid>\',\'<foreign_uid>\')',
                        ),
                    ),
                    'clientsOrders'=>array(
                        'title'=>'Bestellungen',
                        'description'=>'Bestellungen, die dieser Kunde bisher getätigt hat.',
                        'fields'=>'o_order,c_nachname,c_vorname,c_ort,o_fleisch_ord,o_filet_ord,o_kaese_ord,o_wurst_ord,o_trockenfleisch_ord',
                        'whereClause'=>'fle_order.o_client_id=<action>uid</action>',
                        'commonActions'=>'drop',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'fle_order\',\'<uid>\',\'NO_UID\',0)',
                            'drop'=>'javascript:top.dropElement(\'fle_order\',\'<uid>\',\'<foreign_uid>\')',
                        ),
                    ),
                    
                     'clientsOrders_client'=>array(
                        'title'=>'Bestellungen',
                        'description'=>'Bestellungen, die dieser Kunde bisher getätigt hat.',
                        'fields'=>'o_order,c_nachname,c_vorname,c_ort,o_fleisch_ord,o_filet_ord,o_kaese_ord,o_wurst_ord,o_trockenfleisch_ord',
                        'whereClause'=>'fle_order.o_client_id=<action>uid</action>',
                        'actions'=>array(
                            'edit'=>'javascript:top.editElement(\''.table::MODE_EDIT.'\',\'fle_order\',\'<uid>\',\'NO_UID\',0)',
                            'drop'=>'javascript:top.dropElement(\'fle_order\',\'<uid>\',\'<foreign_uid>\')',
                        ),
                    ),
                ),
                'resources'=>array(
                    'fle_order'=>array(
                        'name'=>'Bestellungen',
                        'description'=>'Bestellungen von Kunden.',
                    ),
                ),
            );



$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_ebanking_bank',
    'v_value'=>'Raiffeisen',
    'v_description'=>'',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_ebanking_raiffeisen_contract_p1',
    'v_value'=>'40977',
    'v_description'=>'',
); 
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_ebanking_raiffeisen_contract_p2',
    'v_value'=>'0837',
    'v_description'=>'',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_ebanking_raiffeisen_password',
    'v_value'=>'Errors',
    'v_description'=>'',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_tierdatenbank_username',
    'v_value'=>'145608.0',                         
    'v_description'=>'Benutzername für die Tierdatenbank',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_tierdatenbank_password',
    'v_value'=>'886293',
    'v_description'=>'Passwort für die Tierdatenbank',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_rechnung_recipient_name',
    'v_value'=>'Hans-Ulrich und Christine Wyss',
    'v_description'=>'Vor- und Nachname des Rechnungsstellers',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_rechnung_recipient_address',
    'v_value'=>'Bödeli',
    'v_description'=>'Adresse des Rechnungsstellers',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_rechnung_recipient_city',
    'v_value'=>'3822 Isenfluh',
    'v_description'=>'PLZ und Ort des Rechnungsstellers',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_rechnung_recipient_tel',
    'v_value'=>'033 855 24 35',
    'v_description'=>'Telefonnummer des Rechnungsstellers',
);                        
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_rechnung_bank_name',
    'v_value'=>'Raiffeisenbank Lütschinentäler',
    'v_description'=>'Name der Bank',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_rechnung_bank_city',
    'v_value'=>'3822 Lauterbrunnen',
    'v_description'=>'PLZ und Ort der Bank',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_rechnung_bank_PCAccount',
    'v_value'=>'01-28350-4',
    'v_description'=>'Post-Check Konto der Bank. Auf dieses Konto wird der Betrag überwiesen, bevor er auf das Bankinterne Konto des Begünstigten überwiesen wird.',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_rechnung_bank_customerIdentification',
    'v_value'=>'12400',
    'v_description'=>'ESR-Identifikationsnummer. Wird in der Referenznummer integriert. Wird von der Bank nach einer ESR-Anmeldung mittgeteilt. Muss 6-stellig sein.',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_rechnung_price_filet',
    'v_value'=>'64',
    'v_description'=>'Kilopreis für Filet in sFr.(Kein Komma(,); sonder Punkt(.) als Dezimaltrenner benutzen)',
);                                                                               
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_rechnung_price_fleisch',
    'v_value'=>'31',                      
    'v_description'=>'Kilopreis für Fleisch in sFr.(Kein Komma(,); sonder Punkt(.) als Dezimaltrenner benutzen)',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_rechnung_price_wurst',
    'v_value'=>'35',
    'v_description'=>'Kilopreis für Wurst in sFr.(Kein Komma(,); sonder Punkt(.) als Dezimaltrenner benutzen)',
);
$TABLES['vars']['defaultRows'][]=array(
    'v_key'=>'fle_rechnung_price_kaese',
    'v_value'=>'21',                                                                      
    'v_description'=>'Kilopreis für den Käse in sFr.(Kein Komma(,); sonder Punkt(.) als Dezimaltrenner benutzen)',
);            

            
$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_client")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);

$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_animal")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);

$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_order")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);

/*$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Superuser")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_import")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);  */   
                    
$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_client")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);

$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_animal")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);

$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_order")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);

/*$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Administrator")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_import")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);*/ 

$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_client")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);

$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_animal")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);

$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_order")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);

/*$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Agent")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_import")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
); */

$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_client")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);

$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_animal")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);

$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_order")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);

/*$TABLES['usr_rollhasperm']['defaultRows'][]=array(
        'rp_roll_id'=>'<func>user::getRollId("Kunde")</func>',
        'rp_resource_id'=>'<func>user::getResourceId("fle_import")</func>',
        'rp_permission'=>user::PERMISSION_FULL,
);*/         
//echo $db->view_array($TABLES['usr_rollhasperm']['defaultRows']);         
class fleisch {
    
    public static function writeRechnung(array $data,fpdf $pdf) {
        global $db;
        //echo $db->view_array($data);
        
        $pdf->AddPage();
        $rechnung=new rechnung($pdf);
        
        $rechnung->setRecipientData(vars::getVar('fle_rechnung_recipient_name'),vars::getVar('fle_rechnung_recipient_address'),vars::getVar('fle_rechnung_recipient_city'),vars::getVar('fle_rechnung_recipient_tel'));
        $rechnung->setPayerData($data['c_vorname']." ".$data['c_nachname'],$data['c_strasse'],$data['c_plz']." ".$data['c_ort']);
        $rechnung->setEZSdata(vars::getVar('fle_rechnung_bank_name'),vars::getVar('fle_rechnung_bank_city'),vars::getVar('fle_rechnung_recipient_PCAccount'),vars::getVar('fle_rechnung_bank_customerIdentification'),$data['fle_order.uid']+100000);
        $rechnung->tableAllocation=
            array(
                'amount'=>array(
                    'width'=>8,
                    'display'=>'test',
                    'spec'=>'AMOUNT'
                ),
                'unit'=>array(
                    'width'=>10,
                    'display'=>'Enh',
                    'spec'=>'UNIT'
                ),
                'description'=>array(
                    'width'=>100,
                    'display'=>'Beschreibung',
                    'spec'=>'DESC'
                ),
                'unitprice'=>array(
                    'width'=>20,
                    'display'=>'Fr./Enh',
                    'spec'=>'UNITPRICE'
                ),
                'price'=>array(
                    'width'=>20,
                    'display'=>'Betrag',
                    'spec'=>'PRICE'
                ),
            );
        $rechnung->tableDisplayCols='amount,unit,description,price';
        $rechnung->calcPrice=true;
        if($data['o_fleisch_ord']) {
            $pricePerUnit=currency::formatValue($data['o_fleisch_price'],"CHF");                                                     
            $rechnung->addPosition(array($data['o_fleisch_real'],'kg',$data['a_type'].'('.$data['a_race'].') à sFr.31.--/kg',31));
        }
        if($data['o_filet_ord']) {
            //$pricePerUnit=currency::formatValue($data['o_filet_price'],"CHF");
            $rechnung->addPosition(array($data['o_filet_real'],'kg','Filet à sFr.'.currency::formatValue(vars::getVar('fle_rechnung_price_filet'),'CHF').'/kg',64));
        }
        if($data['o_kaese_ord']) {
            $rechnung->addPosition(array($data['o_kaese_real'],'kg','Käse à sFr.'.currency::formatValue(vars::getVar('fle_rechnung_price_kaese'),'CHF').'/kg',21));
        }
        if($data['o_wurst_ord']) {                             
            $rechnung->addPosition(array($data['o_wurst_real'],'kg','Wurst à sFr.'.currency::formatValue(vars::getVar('fle_rechnung_price_wurst'),'CHF').'/kg',35));
        }
        if($data['o_trockenfleisch_ord']) {
            $rechnung->addPosition(array($data['o_trockenfleisch_real'],'kg','Trockenfleisch à sFr.'.currency::formatValue(vars::getVar('fle_rechnung_price_trockenfleisch'),'CHF').'/kg',76));
        }
        
         
        $rechnung->title="RECHNUNG"; 
        //$rechnung->text="Sehr geehrter Herr ".$data['c_nachname']; 
            
            
        $rechnung->writeRechnung(false);
        
        
    }

    function delete_client($client_id) {
        global $db;

        $db->exec_DELETEquery("fle_client","uid=".$client_id);
        $db->exec_DELETEquery("fle_order","o_client_id=".$client_id);
    }
    function delete_animal($animal_id) {
        global $db;

        $db->exec_DELETEquery("fle_animal","uid=".$animal_id);
        $db->exec_DELETEquery("fle_order","o_animal_id=".$animal_id);
    }
    function delete_order($order_id) {
        global $db;

        $db->exec_DELETEquery("fle_order","uid=".$order_id);
        
    }
    
    public static function printAllRechnungen($animal_id) {
        global $db;
       $res=mysql_query("SELECT *,fle_order.uid AS 'fle_order.uid' FROM fle_order LEFT JOIN fle_animal ON fle_order.o_animal_id=fle_animal.uid LEFT JOIN fle_client ON fle_order.o_client_id=fle_client.uid WHERE fle_order.o_animal_id=".$animal_id.' ORDER BY o_order');

        $output = array();
        
        while($tempRow = mysql_fetch_assoc($res))    {
            $output[] = $tempRow;
        } 
        
        $pdf=new fpdf('P','mm','A4');
        $i=0; 
         foreach ($output as $facture) {
               $i++;
               if($i==count($output)) {
                    fleisch::printRechnung(0,$facture,true,$pdf);
               } else {
                    fleisch::printRechnung(0,$facture,false,$pdf);
               }        
         }
         $db->exec_UPDATEquery('fle_order','o_animal_id='.$animal_id,array('o_rechnung_sent'=>div::date_gerToSQL(div::date_getDate())));
            
    }
                                
    public static function printRechnung($order_id,$data='',$doOutput=true,fpdf $pdf=NULL) {
        global $db;
        if(!$data) { 
            $res=mysql_query("SELECT *,fle_order.uid AS 'fle_order.uid' FROM fle_order LEFT JOIN fle_animal ON fle_order.o_animal_id=fle_animal.uid LEFT JOIN fle_client ON fle_order.o_client_id=fle_client.uid WHERE fle_order.uid=".$_GET['order_id']);
            $data=mysql_fetch_assoc($res);
        }
        if(!$pdf) {
             $pdf=new fpdf('P','mm','A4');
        }
        
        fleisch::writeRechnung($data,$pdf);
        $db->exec_UPDATEquery('fle_order','uid='.$order_id,array('o_rechnung_sent'=>div::date_gerToSQL(div::date_getDate())));  
        if($doOutput==true) {
            $file=TEMP_DIR.basename(tempnam(TEMP_DIR,'tmp'));
            $pdf->Output($file,'F');     
            echo "<script>document.location.href='$file'; setTimeout('top.content.location.reload();',200);  </script>";
        }
    }
    
    public static function printOrderList($animal_id) {
        global $db;                                                                                                                                                                                                     
        $res=mysql_query("SELECT * FROM fle_order LEFT JOIN fle_animal ON fle_order.o_animal_id=fle_animal.uid LEFT JOIN fle_client ON fle_order.o_client_id=fle_client.uid WHERE fle_order.o_animal_id=".$animal_id.' ORDER BY o_order');
        
        $pdf=new fpdf('P','mm','A4');
        $pdf->SetAutoPageBreak(false);
        
        $pdf->AddPage();
        $pdf->addFont('Tahoma');
        $pdf->addFont('Tahoma','B');
        $pdf->setFont('Tahoma','',10);
        
        $output = array();
        
        while($tempRow = mysql_fetch_assoc($res))    {
            $output[] = $tempRow;
        }
        echo $db->view_array($output);
        $colWidths_1=array(50,30,40,12,15,12,18);
        fleisch::_printOrderList_writeHeader($pdf,$colWidths_1,$output[0]);
        $PosX=10;
        $PosY=($pdf->pageNo()==1?40:20);
        $rowHeight=30; 
        
        $sum_fleisch_min=0;
        $sum_fleisch_max=0;
        $sum_leber=0;
        $sum_markknochen=0;
        
        foreach($output as $order) {
            $PosX=10;
            $i=0;
             if($PosY+$rowHeight>$pdf->h) {
                 $pdf->AddPage();
                 $PosY=20;                                      
                 fleisch::_printOrderList_writeHeader($pdf,$colWidths_1,$order);
             }
           $pdf->setXY($PosX,$PosY);
           $pdf->Cell($colWidths_1[$i],$rowHeight,'','TBL');
           $pdf->setXY($PosX,$PosY+3);
           $pdf->setFont('Tahoma','B',12);
             $pdf->Cell($colWidths_1[$i],4.5,$order['c_vorname']." ".$order['c_nachname'],0,0,'L');
            $pdf->setXY($PosX,$PosY+9); 
           $pdf->setFont('Tahoma','',11);
             $pdf->MultiCell($colWidths_1[$i],4.5,$order['c_strasse']."\n".$order['c_plz']." ".$order['c_ort']."\n".$order['c_tel'],0,'L');
             
             $PosX+=$colWidths_1[$i];
             $i++;
              
             $pdf->setXY($PosX,$PosY); 
             $pdf->Cell($colWidths_1[$i],$rowHeight,'','TB');
             $pdf->setXY($PosX,$PosY+3);
             $pdf->setFont('Tahoma','',9);                                                                                               
             $pdf->MultiCell($colWidths_1[$i],4,$order['c_hack']."g Hack\n".$order['c_gesch']."g Gesch.\n".$order['c_ragout']."g Ragout\n"."Plätzli à ".$order['c_plaetzli']."\n".$order['c_braten']."g Braten",0,'L');
             
             $PosX+=$colWidths_1[$i];
             $i++;
             
            /* $pdf->setXY($PosX,$PosY); 
             $pdf->Cell($colWidths_1[$i],$rowHeight,'','TBR');
             $pdf->setXY($PosX,$PosY);                                                                                               
             $pdf->MultiCell($colWidths_1[$i],4,0,'L');
             
             $PosX+=$colWidths_1[$i];
             $i++;*/
             
             $pdf->setXY($PosX,$PosY);
             $pdf->Cell($colWidths_1[$i],$rowHeight,'',1);
             $pdf->setXY($PosX,$PosY);                                                                                                
             $pdf->MultiCell($colWidths_1[$i],4,($order['o_specials']?$order['o_specials']."\n":"").(!$order['c_suppenfleisch']?"Kein Suppenfleisch\n":""),0,'L');
             
             $PosX+=$colWidths_1[$i];
             $i++;
             
             $pdf->setXY($PosX,$PosY);                                                                                                
             $pdf->Cell($colWidths_1[$i],4,($order['o_leber']?($order['o_numleber']?$order['o_numleber']:1):''),0,0,'L');
             $pdf->setXY($PosX,$PosY);
             $pdf->Cell($colWidths_1[$i],$rowHeight,'',1);
             $PosX+=$colWidths_1[$i];
             $i++;
             
             $pdf->setXY($PosX,$PosY);                                                                                                
             $pdf->Cell($colWidths_1[$i],4,$order['o_filet_ord']?($order['o_filet_ord']<1?($order['o_filet_ord']*1000).'g':$order['o_filet_ord'].'kg'):'',0,0,'L');
             $pdf->setXY($PosX,$PosY);
             $pdf->Cell($colWidths_1[$i],$rowHeight,'',1);
             $PosX+=$colWidths_1[$i];
             $i++;
             
             $pdf->setXY($PosX,$PosY);                                                                                                
             $pdf->Cell($colWidths_1[$i],4,$order['o_fleisch_ord']."kg",0,0,'L');
             $pdf->setXY($PosX,$PosY);
             $pdf->Cell($colWidths_1[$i],$rowHeight,'',1);
             $PosX+=$colWidths_1[$i]; 
             $i++;
             
             $pdf->setXY($PosX,$PosY);                                                                                                
             $pdf->Cell($colWidths_1[$i],$rowHeight,'',1);
             $PosX+=$colWidths_1[$i]; 
             
             $sum_fleisch_min+=substr($order['o_fleisch_ord'],0,(strpos($order['o_fleisch_ord'],'-')?strpos($order['o_fleisch_ord'],'-'):strlen($order['o_fleisch_ord'])));
             $sum_fleisch_max+=substr($order['o_fleisch_ord'],(strpos($order['o_fleisch_ord'],'-')?strpos($order['o_fleisch_ord'],'-')+1:0));
             $sum_leber+=($order['o_leber']?($order['o_numleber']?$order['o_numleber']:1):0);
             $sum_markknochen+=$order['o_markknochen']?1:0;
             
             $PosY+=$rowHeight;  
        }
        
        
        
        //Zusammenfassung: Gesamtgewicht, Anzahl Lebern, Anzahl 
        if($PosY+10>$pdf->h) {
                 $pdf->AddPage();
                 $PosY=20;                                      
                 fleisch::_printOrderList_writeHeader($pdf,$colWidths_1,$order);
        }
        $PosX=array_sum(array_slice($colWidths_1,0,5))+10;
        $pdf->setXY($PosX,$PosY);
        $pdf->Cell($colWidths_1[5],5,($sum_fleisch_min==$sum_fleisch_max?$sum_fleisch_min:$sum_fleisch_min."-".$sum_fleisch_max)."kg",1);
        
        $PosX=array_sum(array_slice($colWidths_1,0,3))+10;
        $pdf->setXY($PosX,$PosY);
        $pdf->Cell($colWidths_1[3],5,$sum_leber."",1);
        
        $PosX=array_sum(array_slice($colWidths_1,0,5))+10;
        $pdf->setXY($PosX,$PosY);
        //$pdf->Cell($colWidths_1[5],5,$sum_fleisch."kg",1);
        
             
        $pdf->addPage();
        $colWidths_2=array(40,20,40,40,40);
        $headers=array($output[0]['a_type']." ".$output[0]['a_race']." ".div::date_SQLToGer($output[0]['a_selldate']),'','Hackfleisch','Geschnetzeltes','Ragout');
        $PosX=10;
        $PosY=10; 
        for($i=0;$i<count($headers);$i++) {
             $pdf->setXY($PosX,$PosY);
             if($i==0 ) { $align='L'; } else { $align='C';}
             $pdf->Cell($colWidths_2[$i],5,$headers[$i],'B',0,$align);
             $PosX+=$colWidths_2[$i]; 
             
             
         }
         $PosY=20;
         foreach($output as $order) {
             $i=0;
             $PosX=10;
             
             
           
           $pdf->setXY($PosX,$PosY);
             $pdf->Cell($colWidths_2[$i],5,$order['c_vorname']." ".$order['c_nachname'].(!$order['c_suppenfleisch']?" (Kein Suppenfleisch)":""),'B',0,'L'); 
             $PosX+=$colWidths_2[$i];
             $i++;
             
              $pdf->setXY($PosX,$PosY);                                                                                               
             $pdf->Cell($colWidths_2[$i],5,$order['o_fleisch_ord']."kg",'B',0,'R');
             $PosX+=$colWidths_2[$i];
             $i++;
               
             $pdf->setXY($PosX,$PosY);                                                                                               
             $pdf->Cell($colWidths_2[$i],5,$order['c_hack']."g",'B',0,'C');
             $PosX+=$colWidths_2[$i];
             $i++;
             
             
             $pdf->setXY($PosX,$PosY);                                                                                               
             $pdf->Cell($colWidths_2[$i],5,$order['c_gesch']."g",'B',0,'C');
             $PosX+=$colWidths_2[$i];
             $i++;
             
           
             $pdf->setXY($PosX,$PosY);                                                                                               
             $pdf->Cell($colWidths_2[$i],5,$order['c_ragout']."g",'B',0,'C');        
             $PosX+=$colWidths_2[$i];
             $i++;
             
             $PosY+=8;
         }
         
         
         
         
         
         
         
          
        
                                                                                                       
        
        $file=TEMP_DIR.basename(tempnam(TEMP_DIR,'tmp'));
        $pdf->Output($file,'F');     
       echo "<script>document.location.href='$file'; setTimeout('top.content.location.reload();',200);  </script>";
       
    } 
    
    public static function _printOrderList_writeHeader(fpdf $pdf,$colWidths,$data) {                       
         $headers=array($data['a_type']." ".$data['a_race']." ".div::date_SQLToGer($data['a_selldate'])."\nGeschlecht: ".$data['a_gender']."\nGeburtsdatum: ".div::date_SQLToGer($data['a_born'])."\nMutter: ".$data['a_mother_name']." ".$data['a_mother_race']." ".$data['a_mother_mark']."\nVater: ".$data['a_father_name']." ".str_replace("\n","",$data['a_father_race'])." ".$data['a_father_mark'],'','Sonderwunsch','Lebern','Filet','Fleisch','Fleisch eff.');
         $PosX=10;
         $PosY=10;
         
         for($i=0;$i<count($colWidths);$i++) {
             $pdf->setXY($PosX,$PosY);
             if($i==0) {        
                 $lines=explode("\n",$headers[$i]);
                 
                 
                 $pdf->setFont('Tahoma','B',11);
                 $pdf->Cell($colWidths[$i]+$colWidths[$i+1],10,$lines[0],0,0,'L');
                 $pdf->setXY($PosX,$PosY);
                 $pdf->Cell($colWidths[$i]+$colWidths[$i+1],($pdf->pageNo()==1?30:10),'',1,0,'L'); 
                 $pdf->setFont('Tahoma','',9);
                 if($pdf->pageNo()==1) {
                    for($g=1;$g<count($lines);$g++) {
                         $pdf->setXY($PosX,$PosY+($g*5));
                         $pdf->Cell($colWidths[$i]+$colWidths[$i+1],10,$lines[$g],0,0,'L');
                     } 
                 }
                 $PosX+=$colWidths[$i]; 
                 $i++;
             } else {
                 $pdf->Cell($colWidths[$i],($pdf->pageNo()==1?30:10),'',1,0,'C');
                 $pdf->setXY($PosX,$PosY+($pdf->pageNo()==1?20:0)); 
                 $pdf->Cell($colWidths[$i],10,$headers[$i],0,0,'C');
                 
             }
             
             $PosX+=$colWidths[$i]; 
             
         }
    }
    
    
}
            
/*                  'a_price'=>array(
                        'label'=>'Preis (Fr./kg)',
                        'description'=>'Preis pro Kilo.',
                        'formconfig'=>array(
                            'all'=>array(
                                'type'=>'text',
                                'eval'=>'trim,num',
                            )
                        ),
                        'dbconfig'=>array(
                            'type'=>'int',
                            'length'=>11,
                        ),
                    ), */
?>
