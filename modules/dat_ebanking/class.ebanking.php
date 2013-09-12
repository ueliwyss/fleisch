<?php
class ebanking extends object{
     public $tab;
     private $skipBankChooser=false;
     Const BANK_RAIFFEISEN=1;
     Const BANK_POSTFINANCE=2;
     public $ebanking;
     private $bank;
     
     public function ebanking($bank=false,$tab=NULL) {
         $this->tab=$tab;
         if($bank) {
             $this->setBank($bank);
         }
         $this->init(); 
         $this->saveToSession();
     }
     
     public function setBank($index,$skipChooser=true) {
         $this->bank=$index;
         switch($this->bank) {
             case self::BANK_RAIFFEISEN:
                $this->ebanking = new raiffeisen_ebanking();
                break;
             case self::BANK_POSTFINANCE:
                $this->ebanking = new postfinance_ebanking();
         }
         $this->skipBankChooser=$skipChooser;
         $this->saveToSession();     
     }
     
      public function getBankChoosingForm(&$tab) {
        $form= new form($tab);
        $form->setFormAttributes(basename($_SERVER['PHP_SELF']),'POST');
        $form->saveButton=false;
         
        
        $ebanking_bank=new formElement('select');
        $ebanking_bank->name='ebanking_bank';
        $ebanking_bank->label='Bank';                       
        $ebanking_bank->items=array(self::BANK_RAIFFEISEN=>'Raiffeisen',self::BANK_POSTFINANCE=>'Postfinance');
        $ebanking_bank->eval='required';
        $form->addElement($ebanking_bank);

        $form->addHiddenFieldsFromArray(array(
            'ebanking_action'=>'ebanking_bank',
        )); 
        
        $form->addButton(new formButton('Weiter',$form->getFormName().".onsubmit();"),"end"); 
        $section=new section('Bank auswählen',$form->wrapContent());
        return $section->wrapContent();
    }
    
    public function init() {
        if($_POST['ebanking_bank']) $this->setBank($_POST['ebanking_bank']);
        if($this->ebanking) $this->ebanking->init();
    }
    
    public function login() {
        if($this->skipBankChooser and $this->ebanking) {  
           return $this->ebanking->login(); 
        } else {
            
            return self::getBankChoosingForm($this->tab);
        }
    }
    
    public function getESRData() {
        if($this->skipBankChooser and $this->ebanking) {
            if($this->ebanking->isLoggedIn()) { //ESR-DATEN ABFRAGEN
            } else { return $this->ebanking->login(); } 
        } else {
            return self::getBankChoosingForm($this->tab);
        }
    }
    
    public static function instanciate($restore=true) {  
        if($obj=object::staticrestoreFromSession('ebanking') AND $restore AND $_POST['ebanking_action']) { $obj->init(); return $obj; }
        else return new self();                
    }
    
    
}    
    
abstract class abstract_ebanking {
    public $ebanking_cookie;
    public $curl;
    
    public $bank;
    public $cookie;
    public $referer;
    public $vars=array();
    
    public $form;
    public $tab;
    
    private $loggedIn=false;
    
    public $skipFirstLoginStep=false;
    
    
    abstract function getLoginForm1();
    abstract function getLoginForm2();
    abstract function login1();
    abstract function login2(); 
    
    
    public function abstract_ebanking(&$tab=NULL) {
      $this->curl=new myCurl();
      if($tab) $this->tab=$tab;
      
      
      $this->form=new form($this->tab);
      $this->form->setFormAttributes(basename($_SERVER['PHP_SELF']),'POST');
      $this->form->saveButton=false;
      $this->cookie=tempnam(TEMP_DIR,'ebanking');
      $this->init();  
    }
    
     
    
    
    public function configLoginForm1() {
        $this->form->flushElements();
        
        $this->form->addHiddenFieldsFromArray(array(
            'ebanking_action'=>'ebanking_login',
        ));
        
        $this->section=new section('Zuganngsdaten eingeben');        
    }
    
    public function configLoginForm2() {
        $this->form->flushElements(); 
        
        $this->form->addHiddenFieldsFromArray(array(
            'ebanking_action'=>'ebanking_login2',
        ));   
        
        $this->section=new section('Zuganngsdaten eingeben'); 
    }
    
    public function isLoggedIn() {
        return $this->loggedIn;
    }
    
    public function init() {
         if($_POST['ebanking_bank']) $this->bank=$_POST['ebanking_bank'];
         if($_POST['ebanking_action']) $this->vars['ebanking_action']=$_POST['ebanking_action'];
    }
    
    public function execAction() {
        if($this->vars['ebanking_action']) {
             switch($this->vars['ebanking_action']) {
                 case 'ebanking_bank':
                    return $this->getLoginForm1();
                 case 'ebanking_login': 
                    $this->login1();
                    return $this->getLoginForm2();
                 case 'ebanking_login2':
                    if($this->login2()) $this->loggedIn=true;                   
                    return true; 
             }
        }
        return false;
    }
    
    public function login() {
        
        if($this->skipFirstLoginStep) {
            $this->login1();
            return $this->getLoginForm2();
        } else {
            if($form=$this->execAction()) {
                 return $form;
            }
            return $this->getLoginForm1();
        }
    }
    
    
    
    
}

class raiffeisen_ebanking extends abstract_ebanking {
    public $contract_p1='40977';
    public $contract_p2='0837';
    public $password='Errors';
    public $pass_addition;
    
    public function catch_758_xprot($string) {
        preg_match("/<input.*name=\"_758_xprot\" value=\"(.*)\"/iU",$string,$_758_xprot);
        $this->vars['_758_xprot']= $_758_xprot[1];
    }
    
    public function catchLoginFormAction($string) {  
        preg_match("/<form name=\"LoginForm\" action=\"(.*)\"/iU",$string,$action);
        $this->vars['ebanking_loginform_action']= $action[1];
    }
    
    public function catchPassAddition($string) {
        preg_match("/<b>([^<]*)<\/b>&nbsp;<inputtype=\"text\"class=\"editfield\"name=\"TAN0\"/",preg_replace("/\r|\n/s", "", str_replace(" ","",$string)),$_pass_addition);
        $this->pass_addition= $_pass_addition[1];
    }
    
    public function getLoginForm1() {
        $this->configLoginForm1();
        
        $ebanking_contract_p1=new formElement('text');
        $ebanking_contract_p1->value=$this->contract_p1;
        $ebanking_contract_p1->name='ebanking_contract_p1';
        $ebanking_contract_p1->label='Vertragsnr. Teil 1';
        $this->form->addElement($ebanking_contract_p1);
        
        $ebanking_contract_p2=new formElement('text');
        $ebanking_contract_p2->value=$this->contract_p2;
        $ebanking_contract_p2->name='ebanking_contract_p2';
        $ebanking_contract_p2->label='Vertragsnr. Teil 2';
        $this->form->addElement($ebanking_contract_p2); 
        
        $ebanking_pass=new formElement('password');
        $ebanking_pass->value=$this->password;
        $ebanking_pass->name='ebanking_password';
        $ebanking_pass->label='Passwort';
        $this->form->addElement($ebanking_pass);
        
        $this->form->addButton(new formButton('Weiter',$this->form->getFormName().".onsubmit();"),"end");
        $this->section->content=$this->form->wrapContent();
        return $this->section->wrapContent(); 
    }
    
    public function getLoginForm2() {
        $this->configLoginForm2();
        
        $ebanking_pass_addition=new formElement('text');                      
        $ebanking_pass_addition->name='ebanking_pass_addition';
        $ebanking_pass_addition->label=$this->vars['ebaning_pass_addition_hint'];
        $this->form->addElement($ebanking_pass_addition);
        
        $this->form->addButton(new formButton('Weiter',$this->form->getFormName().".onsubmit();"),"end");                                                                        
        $this->section->content=$this->form->wrapContent();
        return $this->section->wrapContent(); 
    }
    
    public function login1() {                        
         if($this->contract_p1 AND $this->contract_p2 AND $this->password) {
            $curl_opt=array(
                CURLOPT_URL=>'https://tb.raiffeisendirect.ch/entrance/?lang=de',
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_REFERER=>'http://www.raiffeisen.ch/raiffeisen/internet/home.nsf/fHome?ReadForm&Bank=Raiffeisenbank%20L%C3%BCtschinent%C3%A4ler---Q3',
                CURLOPT_COOKIEFILE=>$this->cookie,
                CURLOPT_COOKIEJAR=>$this->cookie, 
                CURLOPT_HTTPHEADER=>array(
                    'Accept:    image/gif, image/jpeg, image/pjpeg, image/pjpeg, application/x-shockwave-flash, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, */*',
                    'Accept-Encoding:    gzip, deflate',
                    'Accept-Language:    de-ch',
                    'Connection: Keep-Alive',
                    
                ),
            );
            $result = div::http_curlRequest($curl_opt,true);
            $this->referer=$curl_opt[CURLOPT_URL];
            
            $this->catch_758_xprot($result);
            $this->catchLoginFormAction($result);
            
            sleep(5);
            
            $curl_opt=array(
                CURLOPT_URL=>'https://tb.raiffeisendirect.ch'.$this->vars['ebanking_loginform_action'],
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_COOKIEFILE=>$this->cookie,
                CURLOPT_COOKIEJAR=>$this->cookie, 
                CURLOPT_FOLLOWLOCATION=>true,
                CURLOPT_REFERER=>$this->referer,
                CURLOPT_POST=>1,
                CURLOPT_POSTFIELDS=>'CID=&lang=de&CID_NUM='.$this->contract_p1.'&CID_CLEAR='.$this->contract_p2.'&PWD='.$this->password.'&date='.div::date_getDateTime().'&os=Microsoft Windows&osPlatform=Windows XP&browser=IE 8.0&browserBuild=8,0,6001,18702&servicePack=0&betaVersion=0&browserPlugins=&screenWidth=1440&screenHeight=900&screenColorDepth=32&scriptEngine=1.3 JScript 5.8.22960&cpuInfo=x86&sysLang=de-ch&_758_xprot='.$this->vars['_758_xprot'],
                CURLOPT_HTTPHEADER=>array(
                    'Accept:    image/gif, image/jpeg, image/pjpeg, image/pjpeg, application/x-shockwave-flash, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, */*',
                    'Accept-Encoding:    gzip, deflate',
                    'Cache-Control:    no-cache',
                    'Accept-Language:    de-ch',
                    'Connection: Keep-Alive', 
                ),
            );
            $result = div::http_curlRequest($curl_opt);
            $this->referer=$curl_opt[CURLOPT_URL];
             
            $this->catchPassAddition($result);
            $this->catchLoginFormAction($result);
            $this->catch_758_xprot($result);
           
         }
    }
    
    public function login2() {
        if($this->pass_addition) {
                if(!$this->skipFirstLoginStep) sleep(5);
                
                $curl_opt=array(
                CURLOPT_URL=>'https://tb.raiffeisendirect.ch'.$this->vars['ebanking_loginform_action'],
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_COOKIEFILE=>$this->cookie,
                CURLOPT_COOKIEJAR=>$this->cookie,
                CURLOPT_REFERER=>$this->referer,
                CURLOPT_FOLLOWLOCATION=>false,
                CURLOPT_POST=>true,
                CURLOPT_POSTFIELDS=>'TAN0='.$this->pass_addition.'&TAN1=&lang=de&_758_xprot='.$this->vars['_758_xprot'],
                CURLOPT_AUTOREFERER=>false,
                CURLOPT_HTTPHEADER=>array(
                    'Accept:    image/gif, image/jpeg, image/pjpeg, image/pjpeg, application/x-shockwave-flash, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, */*',
                    'Accept-Encoding:    gzip, deflate',
                    'Cache-Control:    no-cache',
                    'Accept-Language:    de-ch',
                    'Connection: Keep-Alive',
                    
                ), 
            );
            $result = div::http_curlRequest($curl_opt,1);
            $this->referer=$curl_opt[CURLOPT_URL];
             
            $_location=array();
            preg_match("/Location:(.*)$/m",$result,$_location);
            
            $curl_opt=array(
                CURLOPT_URL=>'https://tb.raiffeisendirect.ch'.$_location[1],
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_COOKIEFILE=>$this->cookie,
                CURLOPT_COOKIEJAR=>$this->cookie, 
                CURLOPT_REFERER=>$this->referer,
                CURLOPT_HEADER=>true,
            );
            $result = div::http_curlRequest($curl_opt,1);
            $this->referer=$curl_opt[CURLOPT_URL];
        }
    }
    
    public function init() {
        parent::init();
        if($_POST['ebanking_pass_addition']) $this->pass_addition= $_POST['ebanking_pass_addition'];
        if($_POST['ebanking_contract_p1']) $this->contract_p1=$_POST['ebanking_contract_p1'];
        if($_POST['ebanking_contract_p2']) $this->contract_p2=$_POST['ebanking_contract_p2'];
        if($_POST['ebanking_password']) $this->password=$_POST['ebanking_password'];
    }
}

class postfinance_ebanking extends abstract_ebanking {
    
    public $postfinance_nr='114921331';
    public $password='Errors';
    public $pass_addition;
    

    
    public function catchPassAddition($string) {  
         preg_match("/<span class=\"form-text\" id=\"challenge\">(.*)<\/span>/",$string,$_pass_addition);
         $this->pass_addition= $_pass_addition[1];
    }
    
    public function getLoginForm1() {
        $ebanking_form=$this->configLoginForm1(); 
        
        $ebanking_userid=new formElement('text');
        $ebanking_userid->value=$this->postfinance_nr;
        $ebanking_userid->name='ebanking_userid';
        $ebanking_userid->label='Postfinance-Nr';
        $this->form->addElement($ebanking_userid);
        
        $ebanking_pass=new formElement('password');
        $ebanking_pass->value=$this->password;
        $ebanking_pass->name='ebanking_password';
        $ebanking_pass->label='Passwort';
        $this->form->addElement($ebanking_pass);
        
        $this->form->addButton(new formButton('Weiter',$this->form->getFormName().".onsubmit();"),"end");                                                                        
        $this->section->content=$this->form->wrapContent();
        return $this->section->wrapContent();
    }
                            
    public function getLoginForm2() {
        $ebanking_form=$this->configLoginForm2(); 
        
        $ebanking_pass_addition=new formElement('text');                      
        $ebanking_pass_addition->name='ebanking_pass_addition';
        $ebanking_pass_addition->label=$this->pass_addition;
        $this->form->addElement($ebanking_pass_addition);
        
       
        $this->form->addButton(new formButton('Weiter',$this->form->getFormName().".onsubmit();"),"end");                                                                        
        $this->section->content=$this->form->wrapContent();
        return $this->section->wrapContent();
    }
    
    public function login1() {                        
         if($this->postfinance_nr AND $this->password) {
               
                $curl_opt=array(
                CURLOPT_URL=>'https://e-finance.postfinance.ch/ef/secure/html/?login',
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_COOKIEFILE=>$this->cookie,
                CURLOPT_COOKIEJAR=>$this->cookie,       
                CURLOPT_FOLLOWLOCATION=>false,
                CURLOPT_HTTPHEADER=>array(
                    'Accept:    image/gif, image/jpeg, image/pjpeg, image/pjpeg, application/x-shockwave-flash, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, */*',
                    'Accept-Encoding:    gzip, deflate',
                    'Cache-Control:    no-cache',
                    'Accept-Language:    de-ch',
                    'Connection: Keep-Alive',
                    
                ), 
            );
            $result = div::http_curlRequest($curl_opt);
            $this->referer=$curl_opt[CURLOPT_URL];
            
            
            
            $curl_opt=array(
                CURLOPT_URL=>'https://e-finance.postfinance.ch/ef/secure/html/?login',
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_COOKIEFILE=>$this->cookie,
                CURLOPT_COOKIEJAR=>$this->cookie,
                CURLOPT_REFERER=>$this->referer,       
                CURLOPT_FOLLOWLOCATION=>true,
                CURLOPT_HTTPHEADER=>array(
                    'Accept:    image/gif, image/jpeg, image/pjpeg, image/pjpeg, application/x-shockwave-flash, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, */*',
                    'Accept-Encoding:    gzip, deflate',
                    'Cache-Control:    no-cache',
                    'Accept-Language:    de-ch',
                    'Connection: Keep-Alive',
                    
                ), 
                CURLOPT_POST=>1,
                CURLOPT_POSTFIELDS=>'p_spr_cd=1&p_seg=1&p_et_nr='.$this->postfinance_nr.'&p_passw='.$this->password.'&p_userid=',
            );
            
            $result = div::http_curlRequest($curl_opt);
            $this->referer=$curl_opt[CURLOPT_URL];
            
            $this->catchPassAddition($result);
         }
    }
    
    public function login2() {
        if($this->pass_addition) {
            
             $curl_opt=array(
                CURLOPT_URL=>'https://e-finance.postfinance.ch/ef/secure/html/?login',
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_COOKIEFILE=>$this->cookie,
                CURLOPT_COOKIEJAR=>$this->cookie,
                CURLOPT_REFERER=>$this->referer,       
                CURLOPT_FOLLOWLOCATION=>true,
                CURLOPT_HTTPHEADER=>array(
                    'Accept:    image/gif, image/jpeg, image/pjpeg, image/pjpeg, application/x-shockwave-flash, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, */*',
                    'Accept-Encoding:    gzip, deflate',
                    'Cache-Control:    no-cache',
                    'Accept-Language:    de-ch',
                    'Connection: Keep-Alive',
                    
                ), 
                CURLOPT_POST=>1,
                CURLOPT_POSTFIELDS=>'p_spr_cd=1&p_seg=0&p_si_nr='.$pass_addition,
            );
            
            $result = div::http_curlRequest($curl_opt);
            $this->referer=$curl_opt[CURLOPT_URL];
        }
    }
    
     public function init() {
        parent::init();
        if($_POST['ebanking_pass_addition']) $this->pass_addition= $_POST['ebanking_pass_addition'];
        if($_POST['ebanking_userid']) $this->postfinance_nr=$_POST['ebanking_userid'];
        if($_POST['ebanking_password']) $this->password=$_POST['ebanking_password'];
    }
} 


class myCurl {
    public $curl;
    public $agent='Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)';
    
    public function myCurl() {
        $this->curl=curl_init();
    }
}                                          
?>