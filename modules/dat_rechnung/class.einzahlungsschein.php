<?php

/* ------------------------------------------------------------------------ */          
/* 	class.einzahlungsschein.php												*/
/*	Eine Klasse um Einzahlungsscheine mit ESR-Nummer als PDF zu erstellen.
/*  A class to create Swiss payment slips with ESR number in pdf format.
/* ------------------------------------------------------------------------ */
/* Manuel Reinhard, manu@sprain.ch
/* Twitter: @sprain
/* Web: www.sprain.ch
/* Beware, known for being one sandwich short of a picnic.
/* ------------------------------------------------------------------------ 
	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
/* ------------------------------------------------------------------------ 
    Thanks to:
    http://www.smoke8.net/ for public designs of Einzahlungsscheinen
    http://www.developers-guide.net/forums/5431,modulo10-rekursiv for Modulo10 function
    http://ansuz.sooke.bc.ca/software/ocrb.php for OCRB font
    http://blog.fruit-lab.de/fpdf-font-converter/ for FPDF font converter
    http://www.fpdf.de/ for the pdf class
    My mom, my dad and all my fans out there… you made me to who I am. Love you all!
    *breaks down, has to be carried from stage*
/* ------------------------------------------------------------------------ */
/* History:
/* 2010/05/06 - Manuel Reinhard - added project to Github
/* 2010/05/06 - Manuel Reinhard - corrected position on bottom line after feedback from bank
/* 2010/04/24 - Manuel Reinhard - when it all started
/* ------------------------------------------------------------------------ */  


/**************************************
 * Import FPDF-Class
 * You can get the latest version here:
 * http://www.fpdf.de/downloads/releases/
 * or here: http://www.fpdf.org/
 *
 * Adjust path if necessary.
 **************************************/



/**************************************
 * Don't change anything from here on
 * if you don't know what you're doing.
 * Otherwise the earth might disappear
 * in a large black hole. We'll blame you!
 **************************************/
class createEinzahlungsschein {

	//margins in mm
	public $marginTop = 0;
	private $marginLeft = 0;

	//values on payment slip
	public $ezs_bankName = "";
	public $ezs_bankCity = "";
	public $ezs_bankingAccount = "";
	
	public $ezs_recipientName    = "";
	public $ezs_recipientAddress = "";
	public $ezs_recipientCity    = "";
	public $ezs_bankingCustomerIdentification = "";
	
	public $ezs_payerLine1		  = "";
	public $ezs_payerLine2       = "";
	public $ezs_payerLine3       = "";
	public $ezs_payerLine4       = "";
	
	public $ezs_referenceNumber = "";
	public $ezs_amount = 0;
	
	public $pdf = false;
	private $landscapeOrPortrait = "P";
	private $format = "A4";
	
	private $pathToImage = "../dat_rechnung/";
	

	/**
	 * Constructor method for this class
	 */

	public function __construct($marginTop=0, $marginLeft=0, $pdfObject=false, $landscapeOrPortrait="P", $format="A4"){
		
		//set stuff
		$this->marginTop = $marginTop;
		$this->marginLeft = $marginLeft;
		$this->landscapeOrPortrait = $landscapeOrPortrait;
		$this->format = $format;
		
		//
		if($pdfObject != false){
			$this->pdf = $pdfObject;
		}//if
		
	}//function
	
	
	
	/**
	 * Set name, address and banking account of bank
	 * @param string $bankName
	 * @param string $bankCity
	 * @param string $bankingAccount
	 * @return bool
	 */
	 public function setBankData($bankName, $bankCity, $bankingAccount){
	 	$this->ezs_bankName = $bankName;
	 	$this->ezs_bankCity = $bankCity;
	 	$this->ezs_bankingAccount = $bankingAccount;
	 	return true;
	 }//function
	
	
	
	
	/**
	 * Set name and address of recipient of money (= you, I guess)
	 * @param string $recipientName
	 * @param string $recipientAddress
	 * @param string $recipientCity
	 * @param int    $bankingCustomerIdentification
	 * @return bool
	 */
	 public function setRecipientData($recipientName, $recipientAddress, $recipientCity, $bankingCustomerIdentification){
	 	$this->ezs_recipientName    = $recipientName;
	 	$this->ezs_recipientAddress = $recipientAddress;
	 	$this->ezs_recipientCity    = $recipientCity;
	 	$this->ezs_bankingCustomerIdentification = $bankingCustomerIdentification;
	 	return true;
	 }//function
	
	
	
	/**
	 * Set name and address of payer (very flexible four lines of text)
	 * @param string $payerLine1
	 * @param string $payerLine2
	 * @param string $payerLine3
	 * @param string $payerLine4
	 * @return bool
	 */
	 public function setPayerData($payerLine1, $payerLine2, $payerLine3="", $payerLine4=""){
	 	$this->ezs_payerLine1 = $payerLine1;
	 	$this->ezs_payerLine2 = $payerLine2;
	 	$this->ezs_payerLine3 = $payerLine3;
	 	$this->ezs_payerLine4 = $payerLine4;
	 	return true;
	 }//function
	 
	 
	 
	/**
	 * Set payment data
	 * @param float $amount
	 * @param int   $referenceNumber (
	 * @return bool
	 */
	 public function setPaymentData($amount, $referenceNumber){
	 	$this->ezs_amount 		   = sprintf("%01.2f",$amount);
	 	$this->ezs_referenceNumber = $referenceNumber;
	 	return true;
	 }//function
	 
	
	
	
	/**
	 * Does the magic!
	 * @param bool $doOutput
	 * @param string $filename
	 * @param string $saveAction (I, D, F, or S -> see http://www.fpdf.de/funktionsreferenz/?funktion=Output)
	 * @return string or file
	 */
	 public function createEinzahlungsschein($doOutput=true, $displayImage=false, $fileName="", $saveAction=""){
	 
	 	//Set basic stuff
	 	if(!$this->pdf){
	 		$this->pdf = new FPDF($this->landscapeOrPortrait,'mm',$this->format);
			$this->pdf->AddPage();
			$this->pdf->SetAutoPageBreak(margin,0);
	 	}//if
	    
	    
	    //Place image
	    if($displayImage){
	    	$this->pdf->Image($this->pathToImage."ezs_orange.gif", $this->marginLeft, $this->marginTop, 210, 106, "GIF");
	    }//if
	    
	    //Set font
		$this->pdf->SetFont('Arial','',9);
		
		
		//Place name of bank (twice)
		$this->pdf->SetXY($this->marginLeft+6, $this->marginTop+8); 
		$this->pdf->Cell(50, 4,$this->ezs_bankName);
		$this->pdf->SetXY($this->marginLeft+6, $this->marginTop+12); 
		$this->pdf->Cell(50, 4,$this->ezs_bankCity);
		
		$this->pdf->SetXY($this->marginLeft+66, $this->marginTop+8); 
		$this->pdf->Cell(50, 4,$this->ezs_bankName);
		$this->pdf->SetXY($this->marginLeft+66, $this->marginTop+12); 
		$this->pdf->Cell(50, 4,$this->ezs_bankCity);
		
		
		//Place baninkg account (twice)
		$this->pdf->SetXY($this->marginLeft+27, $this->marginTop+43); 
		$this->pdf->Cell(30, 4,$this->ezs_bankingAccount);

		$this->pdf->SetXY($this->marginLeft+90, $this->marginTop+43); 
		$this->pdf->Cell(30, 4,$this->ezs_bankingAccount);


		//Place money amount (twice)
		if($this->ezs_amount > 0){
			$amountParts = explode(".", $this->ezs_amount);
            $this->pdf->SetFont('Arial','',11);
			
			$this->pdf->SetXY($this->marginLeft+5, $this->marginTop+51.4); 
			$this->pdf->Cell(36, 4,$amountParts[0], 0, 0, "R");
			$this->pdf->SetXY($this->marginLeft+47, $this->marginTop+51.4); 
			$this->pdf->Cell(9.5, 4,$amountParts[1], 0, 0, "C");
	
			$this->pdf->SetXY($this->marginLeft+66, $this->marginTop+51.4); 
			$this->pdf->Cell(36, 4,$amountParts[0], 0, 0, "R");
			$this->pdf->SetXY($this->marginLeft+108, $this->marginTop+51.4); 
			$this->pdf->Cell(9.5, 4,$amountParts[1], 0, 0, "C");
		}//if
		
		
		//Place name of receiver (twice)
        $this->pdf->SetFont('Arial','',9);
		$this->pdf->SetXY($this->marginLeft+6, $this->marginTop+23); 
		$this->pdf->Cell(50, 4,$this->ezs_recipientName);
		$this->pdf->SetXY($this->marginLeft+6, $this->marginTop+27); 
		$this->pdf->Cell(50, 4,$this->ezs_recipientAddress);
		$this->pdf->SetXY($this->marginLeft+6, $this->marginTop+31); 
		$this->pdf->Cell(50, 4,$this->ezs_recipientCity);
		
		$this->pdf->SetXY($this->marginLeft+66, $this->marginTop+23); 
		$this->pdf->Cell(50, 4,$this->ezs_recipientName);
		$this->pdf->SetXY($this->marginLeft+66, $this->marginTop+27); 
		$this->pdf->Cell(50, 4,$this->ezs_recipientAddress);
		$this->pdf->SetXY($this->marginLeft+66, $this->marginTop+31); 
		$this->pdf->Cell(50, 4,$this->ezs_recipientCity);
		
		
		//Place name of Payer (twice)
		$this->pdf->SetXY($this->marginLeft+6, $this->marginTop+64); 
		$this->pdf->Cell(50, 4,$this->ezs_payerLine1);
		$this->pdf->SetXY($this->marginLeft+6, $this->marginTop+68); 
		$this->pdf->Cell(50, 4,$this->ezs_payerLine2);
		$this->pdf->SetXY($this->marginLeft+6, $this->marginTop+72); 
		$this->pdf->Cell(50, 4,$this->ezs_payerLine3);
		$this->pdf->SetXY($this->marginLeft+6, $this->marginTop+76); 
		$this->pdf->Cell(50, 4,$this->ezs_payerLine4);
		
		
		$this->pdf->SetXY($this->marginLeft+125, $this->marginTop+48); 
		$this->pdf->Cell(50, 4,$this->ezs_payerLine1);
		$this->pdf->SetXY($this->marginLeft+125, $this->marginTop+52); 
		$this->pdf->Cell(50, 4,$this->ezs_payerLine2);
		$this->pdf->SetXY($this->marginLeft+125, $this->marginTop+56); 
		$this->pdf->Cell(50, 4,$this->ezs_payerLine3);
		$this->pdf->SetXY($this->marginLeft+125, $this->marginTop+60); 
		$this->pdf->Cell(50, 4,$this->ezs_payerLine4);
		
		
		
		
		//Create complete reference number
		$completeReferenceNumber = $this->createCompleteReferenceNumber();
		
		//Place Reference Number (twice)	
		$this->pdf->SetXY($this->marginLeft+123, $this->marginTop+35); 
		$this->pdf->Cell(80, 4, $this->breakStringIntoBlocks($completeReferenceNumber));
		
		$this->pdf->SetFont('Arial','',7);
		$this->pdf->SetXY($this->marginLeft+4.5, $this->marginTop+60); 
		$this->pdf->Cell(50, 4, $this->breakStringIntoBlocks($completeReferenceNumber));
		
		
		//Set bottom line
		$this->pdf->AddFont('OCRB10');
		$this->pdf->SetFont('OCRB10','',10);
		$this->pdf->SetXY($this->marginLeft+64, $this->marginTop+86); 
		$this->pdf->Cell(140,4,$this->createBottomLineString(), 0, 0, "R");
		
		//Output
		if($doOutput){
			$this->pdf->Output($fileName, $saveAction);
			if($fileName != ""){
				return $fileName;
			}//if
		}//if

	 }//function
	 
	 
	 
	/**
	* Creates Modulo10 recursive check digit
	*
	* as found on http://www.developers-guide.net/forums/5431,modulo10-rekursiv
	* (thanks, dude!)
	*
	* @param string $number
	* @return int
	*/
	private function modulo10($number) {
		$table = array(0,9,4,6,8,2,7,1,3,5);
		for ($i=0; $i<strlen($number); $i++) {
			$next = $table[($next + substr($number, $i, 1)) % 10];
		}//for		
		return (10 - $next) % 10;
	}//function
	


	/**
	* Creates complete reference number
	* @return string
	*/
	private function createCompleteReferenceNumber() {
	
		//get reference number and fill with zeros
		$completeReferenceNumber = str_pad($this->ezs_referenceNumber, 20 ,'0', STR_PAD_LEFT);
	
		//add customer identification code
		$completeReferenceNumber = $this->ezs_bankingCustomerIdentification.$completeReferenceNumber;
		
		//add check digit
		$completeReferenceNumber .= $this->modulo10($completeReferenceNumber);
		
		//return
		return $completeReferenceNumber;
	}//function



	/**
	* Creates bottom line string
	* @return string
	*/
	private function createBottomLineString() {
	
		//start it, baby!
		$bottomLineString = "";
	
		//EZS with amount or not?
		if($this->ezs_amount == 0){
			$bottomLineString .= "042>";
		}else{
			$amountParts = explode(".", $this->ezs_amount);
			$bottomLineString .= "01";
			$bottomLineString .= str_pad($amountParts[0], 8 ,'0', STR_PAD_LEFT);
			$bottomLineString .= str_pad($amountParts[1], 2 ,'0', STR_PAD_RIGHT);
			$bottomLineString .= $this->modulo10($bottomLineString);
			$bottomLineString .= ">";
		}//if
		
		//add reference number
		$bottomLineString .= $this->createCompleteReferenceNumber();
		$bottomLineString .= "+ ";
		
		//add banking account
		$bankingAccountParts = explode("-", $this->ezs_bankingAccount);
		$bottomLineString .= str_pad($bankingAccountParts[0], 2 ,'0', STR_PAD_LEFT);
		$bottomLineString .= str_pad($bankingAccountParts[1], 6 ,'0', STR_PAD_LEFT);
		$bottomLineString .= str_pad($bankingAccountParts[2], 1 ,'0', STR_PAD_LEFT);
		$bottomLineString .= ">";
		
		//done!
		return $bottomLineString;
		
	}//function

	


	/**
	* Displays a string in blocks of a certain size.
	* Example: 00000000000000000000 becomes more readable 00000 00000 00000

	* @param string $string
	* @param int $blocksize
	* @return int
	*/
	private function breakStringIntoBlocks($string, $blocksize=5) {
		
		//lets reverse the string (because we want the block to be aligned from the right)
		$newString = strrev($string);
		
		//chop it into blocks
		$newString = chunk_split($newString, $blocksize);
		
		//re-reverse
		$newString = strrev($newString);

		
		return $newString;
		
	}//function



}//class


?>