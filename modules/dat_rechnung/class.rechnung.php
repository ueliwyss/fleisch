<?php
    require('class.einzahlungsschein.php');
    
    class rechnung {
        public $pdf;
        private $landscapeOrPortrait = "P";
        private $format = "A4";
        private $_Line_Height;
       
        private $ezs;
        public $showEZS=true;
        public $EZS_pos=array('x'=>0,'y'=>0);
        
        public $recipientAddress_pos=array('x'=>25,'y'=>44);
        public $payerAddress_pos=array('x'=>133,'y'=>55);
        public $date_pos=array('x'=>133,'y'=>25);
        public $title_pos=array('x'=>25,'y'=>'85');
        private $text_pos=array('x'=>25);
        private $table_pos=array('x'=>25);
        private $PosX;
        private $PosY;
        
        public $recipientName;
        public $recipientAddress;
        public $recipientCity;
        public $recipientTel;
        public $recipientLogo;
        
        public $payerLine1;
        public $payerLine2;
        public $payerLine3;
        public $payerLine4;
        
        public $title;
        public $text;
        
        public $doOutput=false;
        
        public $font='Tahoma';
        public $fontsize=12;
        public $titlefontsize=15;   
        
        public $tableitems=array(); 
        public $showTableHeader=false;
        public $tableBorder=false; 
        public $tableAllocation=array();
        public $tableDisplayCols;
        public $calcPrice=true;
        
        public $leftMargin=25;   
        
       function rechnung(fpdf $pdf=NULL,createEinzahlungsschein $ezs=NULL) {  
            if($pdf != false){
                    $this->pdf = $pdf;
            }
            if($ezs) {
                $this->ezs=$ezs;
            } else {
                $this->ezs=new createEinzahlungsschein(190,0,$this->pdf);
            }
            
        } 
        
       public function setEZSdata($bankName, $bankCity, $bankingAccount, $bankingCustomerIdentification, $referenceNumber, $amount=0) {
            $this->ezs->ezs_bankName=$bankName;
            $this->ezs->ezs_bankCity=$bankCity;
            $this->ezs->ezs_bankingAccount=$bankingAccount;
            $this->ezs->ezs_bankingCustomerIdentification=$bankingCustomerIdentification;
            $this->ezs->ezs_referenceNumber=$referenceNumber;
            $this->ezs->ezs_amount=$amount;            
        }
        
        
        
        public function setRecipientData($recipientName, $recipientAddress, $recipientCity, $recipientTel='',$recipientLogo='') {
            $this->recipientName=$recipientName;
            $this->ezs->ezs_recipientName=$recipientName;
            
            $this->recipientAddress=$recipientAddress;
            $this->ezs->ezs_recipientAddress=$recipientAddress;
            
            $this->recipientCity=$recipientCity;
            $this->ezs->ezs_recipientCity=$recipientCity;
            
            $this->recipientTel=$recipientTel;
            $this->recipientLogo=$recipientLogo;
        }
        
        public function setPayerData($payerLine1,$payerLine2,$payerLine3='',$payerLine4='') {
            $this->payerLine1=$payerLine1;
            $this->ezs->ezs_payerLine1=$payerLine1;
            
            $this->payerLine2=$payerLine2;
            $this->ezs->ezs_payerLine2=$payerLine2;
            
            $this->payerLine3=$payerLine3;
            $this->ezs->ezs_payerLine3=$payerLine3;
            
            $this->payerLine4=$payerLine4;
            $this->ezs->ezs_payerLine4=$payerLine4;           
        }
        
        public function addPosition($data) {
            $this->tableitems[]=$data;
        }
        
        public function writeRechnung($doOutput=true) { 
           if(!$this->pdf){
                $this->pdf = new FPDF($this->landscapeOrPortrait,'mm',$this->format);
                $this->pdf->AddPage();
                //$this->pdf->SetAutoPageBreak(false);
                
            }
             
            $this->pdf->SetAutoPageBreak(margin,0);
           
     
               
            $this->ezs->pdf=$this->pdf;  
            $this->_checkFPDFFont($this->font);
            $this->_checkFPDFFont($this->font,'B');
            $this->setFont($this->font);
            
            
    
            
            
            //Adresse des Rechnungsstellers.
            $this->pdf->setXY($this->recipientAddress_pos['x'],$this->recipientAddress_pos['y']);
            if($this->recipientLogo AND file_exists($this->recipientLogo)) {
                $imagesize=getimagesize($this->recipientLogo);
                     $this->pdf->Image($this->recipientLogo,$this->recipientAddress_pos['x'],$this->recipientAddress_pos['y'],$imagesize[0],$imagesize[1],strtoupper(str_replace(".","",div::file_getEndung($this->recipientLogo))));
                $this->pdf->setXY($this->recipientAddress_pos['x'],$this->recipientAddress_pos['y']+$imagesize[1]);  
            }
                                                                      
            $this->pdf->MultiCell(65,$this->_Line_Height*0.8,$this->recipientName."\n".$this->recipientAddress."\n".$this->recipientCity."\n\n".$this->recipientTel,0,'L');  
            
            //Adresse des Rechnungsempfängers.
            $this->pdf->setXY($this->payerAddress_pos['x'],$this->payerAddress_pos['y']);  
            $this->pdf->MultiCell(40,$this->_Line_Height*0.8,$this->payerLine1."\n".$this->payerLine2."\n".($this->payerLine3?$this->payerLine3."\n".($this->payerLine4?$this->payerLine4."\n":""):""));                
             
            //Datum
            $this->pdf->setXY($this->date_pos['x'],$this->date_pos['y']);
            $this->pdf->Cell(40,$this->_Line_Height,div::date_withFullMonth(div::date_getDate()),0); 
            
            $this->writeLetter();
            $this->writeTable();
            
            
            //Mit freundlichen Grüssen... 
            $this->setFont($this->font,'');
            $this->PosY+=$this->_Line_Height+20;
            $this->pdf->SetXY($this->PosX,$this->PosY);
            $this->pdf->MultiCell(100,$this->_Line_Height,"Mit freundlichen Grüssen\n".$this->recipientName);
            $this->PosY+=$this->_Line_Height;
            
              
            //Einzahlungsschein
             if($this->PosY>$this->ezs->marginTop) {
                $this->pdf->AddPage();
            } 
            $amount=$this->calcTotalPrice();         
            $this->ezs->ezs_amount=sprintf("%01.2f",$amount['value']);
            
            $this->ezs->createEinzahlungsschein(false,false); 
            
            if($this->doOutput) {
                $file=TEMP_DIR.basename(tempnam(TEMP_DIR,'pdf'));
                
    
                $this->pdf->Output($file);
                echo "<iframe id='pdf' style='width:0px;height:0px;'></iframe><script>document.getElementById('pdf').src='$file'; </script>";
            }    
        }
        
        private function _Get_Height_Chars($pt) {
            $a = array(6=>2, 7=>2.5, 8=>3, 9=>4, 10=>5, 11=>6, 12=>7, 13=>8, 14=>9, 15=>10);
            if (!isset($a[$pt]))
                return false;
            return $a[$pt];
        }
        
        private function setFont($fontName,$fontStyle='',$fontSize=0) {
            if(!$fontsize) $fontsize=$this->fontsize;
            $this->pdf->setFont($fontName,$fontStyle,$fontSize);
            $this->_Line_Height=$this->_Get_Height_Chars($this->pdf->FontSizePt);
        }
        
        private function _checkFPDFFont($fontName, $style='') {    
            if($this->pdf) {
                if(!$this->pdf->fonts[$fontName.$style] AND !$this->pdf->CoreFonts[$fontName.$style]) {
                    
                    return $this->pdf->AddFont($fontName,$style);                    
                }
                return true;
            }
            return false;
        }
        
        private function writeLetter() {
            if($this->title) {
                $this->pdf->setXY($this->title_pos['x'],$this->title_pos['y']);
                $this->setFont($this->font,'B',$this->titleFontsize);
                $this->pdf->Cell(80,$this->_Line_Height,$this->title);
                $this->text_pos['y']=$this->_Line_Height+$this->title_pos['y'];
                $this->setFont($this->font,'',$this->fontsize);
            } else {
                $this->text_pos['y']=$this->title_pos['y'];
            }
            
            if($this->text) {
                //echo "TEXTPOSY:".$this->text_pos['y'];  
                $this->pdf->setXY($this->text_pos['x'],0);
                $this->pdf->MultiCell(80,$this->pdf->w-$this->pdf->lMargin,$this->text);
                $this->table_pos['y']=$this->text_pos['y']+10+$this->_Line_Height;
                
            } else {
                $this->table_pos['y']=$this->text_pos['y']+10;
            }
        }
        
        private function writeTable() {
            $this->PosX=$this->table_pos['x'];
            $this->PosY=$this->table_pos['y'];
            $cols=explode(",",$this->tableDisplayCols);
            $this->prepareAllocationTable();
            global $db;
            //echo $db->view_array($this->table_pos); 
            //echo $db->view_array($this->tableitems); 
            
            $this->writeTableHeader($this->PosX,$this->PosY);  
            
            foreach($this->tableitems as $key=>$item) {
                if(($this->PosY+$this->_Line_Height)>($this->pdf->h-$this->pdf->bMargin)) {
                    $this->pdf->AddPage();
                    $this->PosY=$this->pdf->tMargin;
                }
                $this->pdf->setXY($this->PosX,$this->PosY);
                $this->PosX_item=$this->PosX;
                
                
                
                foreach($cols as $colName) {
                      $width=$this->tableAllocation[$colName]['width'];
                      
                      $this->pdf->setXY($this->PosX_item,$this->PosY);
                      if($this->tableAllocation[$colName]['spec']=="PRICE") {
                            $price=$this->getPriceKey($key);
                            if($price) {
                                $this->pdf->Cell($width,$this->_Line_Height,$price['text'],0,0,'R');
                            } else {
                                $this->pdf->Cell($width,$this->_Line_Height,'','B',0,'R');
                            }
                            
                      } elseif($this->tableAllocation[$colName]['spec']=="AMOUNT" AND $item[$this->tableAllocation[$colName]['pos']]==0 AND $item[$this->getSpecColInd('PRICE')]==0) {
                           $this->pdf->Cell($width,$this->_Line_Height,'','B');
                      } else {
                           $this->pdf->Cell($width,$this->_Line_Height,$item[$this->tableAllocation[$colName]['pos']]);
                           
                           
                      }
                      
                      
                      $this->PosX_item+=$width;
                       
                }
                $this->PosY+=$this->_Line_Height;
                
            }              
            if(count($this->tableitems)) {
                $this->PosY+=5;
                $this->pdf->setXY($this->PosX,$this->PosY);
                $this->setFont('Tahoma','B');     
                $this->pdf->Cell('20',$this->_Line_Height,'Total');
                $this->PosX_price=$this->getSpecColPosX('PRICE')+$this->PosX;
                
                $this->pdf->setX($this->PosX_price);
                $amount=$this->calcTotalPrice();
                $this->pdf->Cell($this->tableAllocation[$this->getSpecCol('PRICE')]['width'],$this->_Line_Height,$amount['value']?$amount['text']:'','TB',0,'R');
                
                     
                
                $this->pdf->setXY($this->PosX_price,$this->PosY+0.5); 
                $this->pdf->Cell($this->tableAllocation[$this->getSpecCol('PRICE')]['width'],$this->_Line_Height,'','B');
            }
           
                
            
            
            
        }
        
        private function getPriceKey($item) {
            $pricecol=$this->getSpecColInd('PRICE');
            if($this->tableitems[$item][$pricecol]==0) {
                if($this->calcPrice) { 
                                    
                     if(is_int($amount=$this->getSpecColInd('AMOUNT')) AND is_int($unitprice=$this->getSpecColInd('UNITPRICE'))) {
                         
                         $this->tableitems[$item][$pricecol]=$this->tableitems[$item][$amount]*$this->tableitems[$item][$unitprice];
                     } 
                     
                }
            }
           $price=$this->tableitems[$item][$pricecol]?currency::formatValue($this->tableitems[$item][$pricecol],"CHF"):false;
           return $price;
        }
                                
        private function getSpecColInd($spec) {
            foreach($this->tableAllocation as $colName=>$data)  {
                if($data['spec']==$spec) {
                    return $data['pos'];
                }
            }
            return "NOTFOUND";
        }
        
        private function getSpecCol($spec) {
            foreach($this->tableAllocation as $colName=>$data)  {
                if($data['spec']==$spec) {
                    return $colName;
                }
            }
            return false;
        }
        
        
        
        private function prepareAllocationTable() {
            global $db;
            
            if(is_array($this->tableAllocation)) {
               $i=0; 
               //echo $db->view_array($this->tableAllocation);
               foreach($this->tableAllocation as $colName=>$data) {
                   //echo $this->tableAllocation['unit']['display']."<br>";
                    $this->tableAllocation[$colName]['pos']=$i;
                    $i++;
                } 
               //echo $db->view_array($this->tableAllocation); 
               //echo $this->tableAllocation['unit']['display']."<br>";
               foreach($this->tableAllocation as $colName=>$data) {
                   
               }
            }
            
            
        }
        
        private function writeTableHeader($PosX,$PosY) {
            if($this->showTableHeader) {
                $cols=explode(",",$this->tableDisplayCols);
                $PosX_header=$PosX;
                $this->setFont($this->font,'B',$this->fontsize);
                
                foreach($cols as $colName) {
                    $width=$this->tableAllocation[$colName]['width'];
                    $this->pdf->setXY($PosX_header,$PosY);
                    $this->pdf->Cell($width,$this->_Line_Height,$this->tableAllocation[$colName]['display'],'TB');
                    $PosX_header+=$width;
                }
                $PosY+=$this->_Line_Height;
                $this->setFont($this->font,'',$this->fontsize);
                $this->PosY+=$this->_Line_Height+5;
                return true;
            }
            return false; 
        }
        
        private function calcTotalPrice() {
            $priceCol=$this->getSpecColInd('PRICE');
            $amount=0;
            foreach($this->tableitems as $item) {
                 if($item[$priceCol]==0) {
                     return false;
                 }
                 $price=currency::formatValue($item[$priceCol],"CHF");
                 
                 $amount+=$price['value'];
                 
            }
            $amount=currency::formatValue($amount,"CHF");
            return $amount;
        }
        
        private function getSpecColPosX($spec) {
            $PosX=0;
            $cols=explode(",",$this->tableDisplayCols); 
            foreach($cols as $col) {
                if($this->tableAllocation[$col]['spec']==$spec) {
                    return $PosX;
                } 
                    
                $PosX+=$this->tableAllocation[$col]['width'];
                
                
            }
            return false;
        }
    }

    



?>