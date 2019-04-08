<?php
	/*
	*********************************************************************************
	* Title: user_utils.php
	* Descriptions: The functions/methods are used for user management activities
	* 				such as creating, deleting and updating users
	* @author Aubrey Mantji <breowas@gmail.com>
	* @copyright Copyright (c) 2017, AUBDYNAMICS
	*********************************************************************************
	*/
	
	class clUsers {		
		private $sUsername = '', 
			 	$sGender = '',			 	
			 	$sEmail = '',
			 	$sLicensecode = '',
			 	$sBookeddate = '',
			 	$sBookedtime = '',	
			 	$sPassword = '';
		private $C_SHOST = "localhost";//,			 	
		private $C_SDBNAME = "tshebofs_bds_db";	
		 			    
		function __construct() {				
		}
		
		public function set_username($p_sUsername){
			$l_sErrmsg = "";
						
			if(isset($p_sUsername)){
				
				if($p_sUsername === ""){
					$l_sErrmsg = "username must be entered";	
					return $l_sErrmsg;
				} 							
			}else{			
		
				$l_sErrmsg = "username must be entered";	
				return $l_sErrmsg;
			}
			
			$this->sUsername = $p_sUsername;
			return $l_sErrmsg;			
		}
		
		public function set_gender($p_sGender){
			$l_sErrmsg = "";
			
			if(isset($p_sGender)){
				if($p_sGender === ""){
					$l_sErrmsg = "gender must be selected";	
					return $l_sErrmsg;
				}					
				
			}
			else{
				$l_sErrmsg = "gender must be selected";	
				return $l_sErrmsg;
			}
			
			$this->sGender = $p_sGender;
			return $l_sErrmsg;
		}		
		
		public function set_email($p_sEmail){
			$l_sErrmsg = "";
			
			if(isset($p_sEmail)){
				
				if($p_sEmail === ""){
					$l_sErrmsg = "email must be entered";	
					return $l_sErrmsg;
				}
				
				if(!filter_var($p_sEmail, FILTER_VALIDATE_EMAIL)){
					$l_sErrmsg = "email not valid";	
					return $l_sErrmsg;
				}								
			}
			else{
				$l_sErrmsg = "email must be entered";	
				return $l_sErrmsg;
			}
			
			$this->sEmail = $p_sEmail;
			return $l_sErrmsg;
		}
		
		public function set_licensecode($p_sLicensecode){
			$l_sErrmsg = "";
			
			if(isset($p_sLicensecode)){
				if($p_sLicensecode === ""){
					$l_sErrmsg = "license code must be selected";	
					return $l_sErrmsg;
				}				
			}
			else{
				$l_sErrmsg = "license code must be selected";	
				return $l_sErrmsg;
			}
			
			$this->sLicensecode = $p_sLicensecode;
			return $l_sErrmsg;
		}
		
		public function set_bookeddate($p_sBookeddate){
			$l_sErrmsg = "";
			
			if(isset($p_sBookeddate)){
				if($p_sBookeddate === ""){
					$l_sErrmsg = "Booked date must be selected";	
					return $l_sErrmsg;
				}				
			}
			else{
				$l_sErrmsg = "Booked date must be selected";	
				return $l_sErrmsg;
			}
			
			$this->sBookeddate = $p_sBookeddate;
			return $l_sErrmsg;
		}
		
		public function set_bookedtime($p_sBookedtime){
			$l_sErrmsg = "";
			
			if(isset($p_sBookedtime)){
				if($p_sBookedtime === ""){
					$l_sErrmsg = "Booked time must be selected";	
					return $l_sErrmsg;
				}				
			}
			else{
				$l_sErrmsg = "Booked time must be selected";	
				return $l_sErrmsg;
			}
			
			$this->sBookedtime = $p_sBookedtime;
			return $l_sErrmsg;
		}
		
		public function set_password($p_sPassword_1, $p_sPassword_2){
			$l_sErrmsg = "";
			
			if(isset($p_sPassword_1)){
				
				if($p_sPassword_1 === ""){
					$l_sErrmsg = "password must be entered";	
					return $l_sErrmsg;
				}
				
			}
			else{
				$l_sErrmsg = "password must be entered";	
				return $l_sErrmsg;
			}
			
			if(isset($p_sPassword_2)){
				if($p_sPassword_2 === ""){
					$l_sErrmsg = "confirm password must be entered";	
					return $l_sErrmsg;
				}				
			}
			else{
				$l_sErrmsg = "confirm password must be entered";	
				return $l_sErrmsg;
			}
			
			if($p_sPassword_1 === $p_sPassword_2){
				
				//Check pass length and complexity
				
				//encrypt password
				$this->sPassword = $p_sPassword_1;
				return $l_sErrmsg;
			}
			else{
				$l_sErrmsg = "passwords must match";
				return $l_sErrmsg;
			}				
		}
		
		function user_check($p_oUser){
		
			$l_sErrmsg = "";
			
			$l_sErrmsg = $this->set_username($p_oUser->username);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}
			
			$l_sErrmsg = $this->set_gender($p_oUser->gender);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}			
			
			$l_sErrmsg = $this->set_licensecode($p_oUser->license_code);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}
			
			$l_sErrmsg = $this->set_email($p_oUser->email);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}	
												
			$l_sErrmsg = $this->set_password($p_oUser->password_1, $p_oUser->password_2);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}
			
			return $l_sErrmsg;
		}
		
		function recovery_check($p_oUser){
		
			$l_sErrmsg = "";
			
			$l_sErrmsg = $this->set_username($p_oUser->username);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}
			
			$l_sErrmsg = $this->set_gender($p_oUser->gender);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}			
			
			$l_sErrmsg = $this->set_licensecode($p_oUser->license_code);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}
			
			$l_sErrmsg = $this->set_email($p_oUser->email);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}	
												
			$l_sErrmsg = $this->set_password($p_oUser->password_1, $p_oUser->password_2);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}
			
			return $l_sErrmsg;
		}
			
		public function connecttodb($p_sUsername, $p_sPassword){
			/*  Database connection using PDO   */		   
		    $l_sHost = $this->C_SHOST;
		    $l_sDbname = $this->C_SDBNAME;       		    
		    /*  Connect to database */
		    $this->oLink = new PDO("mysql:host=$l_sHost;dbname=$l_sDbname", $p_sUsername, $p_sPassword);		    
		}
		
		public function disconnecttodb(){
			unset($this->oLink);
		}
		
		public function set_recovery_password(){
			
			/**
			* Inserts a new recovery password.
			* Returns True on Success and False on failure
			* @return {boolean} returns false on failure
			* 
			*/
			/*Sanitize inputs before use*/
			
			$l_oSth = NULL;
			$l_bState = FALSE;
			$l_sUsername = 'tshebofs_bds_general';
			$l_sPassword = '7VELF8C8MjMpwnsv';
			$l_sdbusername;
									
			try{						
				$this->connecttodb($l_sUsername, $l_sPassword);	
				//
				$l_sSQLstring = 'SELECT pkusername FROM users WHERE email = :p_useremail LIMIT 1';

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
					                            
                $l_oSth->bindParam(':p_useremail', $this->sEmail, PDO::PARAM_STR);                              
                                                          
                $l_bState = $l_oSth->execute();
                
                if($l_bState){ 
                	//
                	$l_oUserList = $l_oSth->fetchAll(PDO::FETCH_NAMED);                         	                
	                  
	                foreach($l_oUserList as $row){
						$l_sdbusername = $row['pkusername'];			
					} 
					//
					$l_bState = ($l_sdbusername === "") ? FALSE : TRUE;	
					
					if($l_bState){									              
				
						//			
						$l_oSth = $this->oLink->prepare('INSERT INTO password_recovery ( pkuseremail, password, expiry_date, expiry_time, expiry_datetime) 
						 VALUES ( :p_useremail, :p_password, DATE_ADD(NOW(), INTERVAL 24 HOUR), NOW(), DATE_ADD(NOW(), INTERVAL 24 HOUR)) ');
		                
		                $l_oSth->bindParam(':p_useremail', $this->sEmail, PDO::PARAM_STR);                
		                $l_oSth->bindParam(':p_password', $this->sPassword, PDO::PARAM_STR);	                          
		                
		                $l_bState = $l_oSth->execute();  
					} 
	                                                           
				}
                $this->disconnecttodb();
                return $l_bState;				
		    
		    } catch (Exception $e) {

		    	$this->disconnecttodb();
				return FALSE;
			}
		}
		
		function verify_token($p_stoken){
		
			$l_suseremail = "";		
			$l_oUser = new clUsers();
			
			/**
				* 
				* Returns 
				* @return 
				* 
				*/
				/*Sanitize inputs before use*/
				
			$l_oSth = NULL;
			//$l_bState = FALSE;
			$l_sUsername = 'tshebofs_bds_general';
			$l_sPassword = '7VELF8C8MjMpwnsv';
			$l_sdbusername;
			$l_oUserList;
									
			try{						
				$this->connecttodb($l_sUsername, $l_sPassword);	
				//
				$l_sSQLstring = 'SELECT pkuseremail FROM password_recovery 
				WHERE password = :p_token AND expiry_datetime >= NOW() LIMIT 1';

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
					                            
                $l_oSth->bindParam(':p_token', $p_stoken, PDO::PARAM_STR);                              
                                                          
                $l_bState = $l_oSth->execute();
                
                if($l_bState){ 
                	//
                	$l_oUserList = $l_oSth->fetchAll(PDO::FETCH_NAMED);                         	                
	                  
	                foreach($l_oUserList as $row){
						$l_suseremail = $row['pkuseremail'];			
					}	
					                                                       
				}
                $this->disconnecttodb();
                return $l_suseremail;				
		    
		    } catch (Exception $e) {

		    	$this->disconnecttodb();
				return $l_suseremail;
			}		
		}
		
		function send_recovery_email($p_surl){
			try{
				
				$l_bemail_sent = FALSE;
				$l_sto      = $this->sEmail;
				$l_ssubject = 'Password Reset [DO NOT REPLY]';
				$l_smessage = 'Good day,';
				$l_smessage = $l_smessage."\r\n\r\n".'Please use the link below to reset your password.';
				$l_smessage = $l_smessage."\r\n".$p_surl;				
				$l_smessage = $l_smessage."\r\n\r\n".'Kind regards,';
				$l_smessage = $l_smessage."\r\n".'BDSCHOOL Admin';
						   
				$l_oheaders = 'From: admin@bdschool.co.za' . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
								
				$l_bemail_sent = mail($l_sto, $l_ssubject, $l_smessage, $l_oheaders);
				return $l_bemail_sent;
			
			} catch (Exception $e) {		    	
				return FALSE;
			}			
		}		
				
	}		
	
	function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
	}

	function getToken($length=32){
	    $token = "";
	    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
	    $codeAlphabet.= "0123456789";
	    for($i=0;$i<$length;$i++){
	        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
	    }
	    return $token;
	}
		
	function is_ajax(){
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
		strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
			
		
	//Check if ajax request was sent	
	if(is_ajax()){			
		
		if(isset($_POST['recover_pw'])){
			
			$l_oUser = new clUsers();	
			$l_oData = $_POST['recover_pw'];
			$l_oNewuser = json_decode($l_oData);			
			$error_message;						
			$l_stoken;
			$l_surl;
						
			//Generate token for password recovery
			$l_stoken = getToken();
			
			//Validate the data, email
			$error_message = $l_oUser->set_email($l_oNewuser->useremail);
			
			$l_oJSON['check'] = ($error_message == "") ? TRUE : FALSE;				
												
			//
			if($l_oJSON['check']){
										
				//Check if token was generated
				$error_message = $l_oUser->set_password($l_stoken, $l_stoken);	
				
				$l_oJSON['check'] = ($error_message == "") ? TRUE : FALSE;			
								
				if($l_oJSON['check']){
													
					//Save token and email					
					
					$l_oJSON['check'] = $l_oUser->set_recovery_password();
																		
					if($l_oJSON['check']){									
						
						//Create reset link
						$l_surl = "http://www.bdschool.tshebrey.co.za/reset_password.php?prtk=".$l_stoken;
					
						//Send email
						$l_oJSON['check'] = $l_oUser->send_recovery_email($l_surl);
						
						if($l_oJSON['check']){									
							$error_message = "Password recovery request sent, check your email to reset password.";						
						}
						else{
							$error_message = "Failed to generate temporary password. Try again email";
						}						
					}
					else{
						$error_message = "Failed to generate temporary password. Try again";
					}			
				}
				else{
					$error_message = "Failed to generate temporary password. Try again";
				}					
			}
			
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);				
		}
		else{
			$error_message = "Failed to generate temporary password. Try again";
		}		
	}	
?>