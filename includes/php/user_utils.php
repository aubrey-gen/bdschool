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
			 	$newDisplayText = '',	
			 	$displayText = '',
			 	$activated = 0,
			 	$itemId,
			 	$codeId = 0,
			 	$reference = '',
			 	$amount = 0.0,
			 	$productDescription = "",
			 	$price = 0,
			 	$quantity = 0,
			 	$sPassword = '';
		private $C_SHOST = "localhost";			 	
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
				
				if($p_sGender !== "F"){
					
					if($p_sGender !== "M"){
						$l_sErrmsg = "gender must be selected";	
						return $l_sErrmsg;
					}
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
				
		public function setCode($submittedNewCode){
    
	        $l_sErrmsg = "";
			$newcode = trim($submittedNewCode);
			
			if(isset($newcode)){
				if($newcode === ""){
					$l_sErrmsg = "license code must be entered";	
					return $l_sErrmsg;
				}				
			}
			else{
				$l_sErrmsg = "license code must be entered";	
				return $l_sErrmsg;
			}

	        if (preg_match('/^[a-zA-Z0-9 \-]+$/i', $newcode)){
	            $this->sLicensecode = strtoupper($newcode);
	        }
	        else{
				$l_sErrmsg = "license code must be entered";
			}
	    
	        return $l_sErrmsg;
	    }
		
		public function setLicenseCodeDisplayName($submittedDisplayText){
			$l_sErrmsg = "";
			$p_displayText = trim($submittedDisplayText);
			
			if(isset($p_displayText)){
				if($p_displayText === ""){
					$l_sErrmsg = "Display name must be entered";	
					return $l_sErrmsg;
				}
				
				if (preg_match('/^[a-zA-Z0-9 \-]+$/i', $p_displayText)){	            	
		        }
		        else{
					$l_sErrmsg = "Use only alphanumerics, - and space for display name";
				}				
			}
			else{
				$l_sErrmsg = "Display name must be entered";	
				return $l_sErrmsg;
			}
			
			$this->displayText = $p_displayText;
			return $l_sErrmsg;
		}
		
		public function setNewLicenseCodeDisplayName($submittedDisplayText){
			$l_sErrmsg = "";
			$p_displayText = trim($submittedDisplayText);
			
			if(isset($p_displayText)){
				if($p_displayText === ""){
					$l_sErrmsg = "New display name must be entered";	
					return $l_sErrmsg;
				}
				
				if (preg_match('/^[a-zA-Z0-9 \-]+$/i', $p_displayText)){	            	
		        }
		        else{
					$l_sErrmsg = "Use only alphanumerics, - and space for new display name";
				}				
			}
			else{
				$l_sErrmsg = "New display name must be entered";	
				return $l_sErrmsg;
			}
			
			$this->newDisplayText = $p_displayText;
			return $l_sErrmsg;
		}
		
		public function set_reference($reference){			
			$l_sErrmsg = "";
						
			$this->reference = $reference;
			return $l_sErrmsg;
		}
		
		public function encryptPassword($clearTextPassword){
			$l_sErrmsg = "";
			$this->sPassword = password_hash($clearTextPassword, PASSWORD_DEFAULT);
			
			if($this->sPassword == FALSE){
				return FALSE;
			}
			return TRUE;
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
				
				//Check password length and complexity

				if (!preg_match('/^[a-zA-Z0-9]+$/i', $p_sPassword_1)){
		            $l_sErrmsg = "Use only alphanumerics for password";
		        }
				elseif (strlen($p_sPassword_1) < 8 ){
				    $l_sErrmsg = "Your Password Must Contain At Least 8 Characters!";
				}
				elseif(!preg_match("#[0-9]+#",$p_sPassword_1)) {
				    $l_sErrmsg = "Your Password Must Contain At Least one digit!";
				}
				elseif(!preg_match("#[A-Z]+#",$p_sPassword_1)) {
				    $l_sErrmsg = "Your Password Must Contain At Least one Capital Letter!";
				}
				elseif(!preg_match("#[a-z]+#",$p_sPassword_1)) {
				    $l_sErrmsg = "Your Password Must Contain At Least one Lowercase Letter!";
				}				
				
				//Encrypt password				
				$l_sErrmsg = $this->encryptPassword($p_sPassword_1) ? "" : "Could not save password";				
			}	
			else{
				$l_sErrmsg = "Passwords must match";
			}		
			
			return $l_sErrmsg;				
		}
		
		public function setGenPassword($p_sPassword_1, $p_sPassword_2){
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
				
				//Check password length and complexity
				if (!preg_match('/^[a-zA-Z0-9]+$/i', $p_sPassword_1)){
		            $l_sErrmsg = "Use only alphanumerics for password";
		        }
				elseif (strlen($p_sPassword_1) < 6 ){
				    $l_sErrmsg = "Your Password Must Contain At Least 6 Characters!";
				}
				elseif(!preg_match("#[0-9]+#",$p_sPassword_1)) {
				    $l_sErrmsg = "Your Password Must Contain At Least one digit!";
				}
				elseif(!preg_match("#[A-Z]+#",$p_sPassword_1)) {
				    $l_sErrmsg = "Your Password Must Contain At Least one Capital Letter!";
				}
				elseif(!preg_match("#[a-z]+#",$p_sPassword_1)) {
				    $l_sErrmsg = "Your Password Must Contain At Least one Lowercase Letter!";
				}				
				
				//Encrypt password				
				$l_sErrmsg = $this->encryptPassword($p_sPassword_1) ? "" : "Could not save password";				
			}			
			
			return $l_sErrmsg;				
		}
		
		public function setCodeId($codeId){
			$l_sErrmsg = "";
			
			if(isset($codeId)){
				if($codeId === ""){
					$l_sErrmsg = "Select a valid license code";	
					return $l_sErrmsg;
				}
				
				if (preg_match('/^[0-9]+$/i', $codeId)){
					if($codeId < 0){
						$l_sErrmsg = "Select a valid license code";
					}	            	
		        }
		        else{
					$l_sErrmsg = "Select a valid license code";
				}					
			}
			else{
				$l_sErrmsg = "Select a valid license code";	
				return $l_sErrmsg;
			}
			
			$this->codeId = $codeId;
			return $l_sErrmsg;
		}
		
		public function setItemId($itemId){
			$l_sErrmsg = "";
			
			if(isset($itemId)){
				if($itemId === ""){
					$l_sErrmsg = "Select a product";	
					return $l_sErrmsg;
				}
				
				if (preg_match('/^[0-9]+$/i', $itemId)){	            	
		        }
		        else{
					$l_sErrmsg = "Select a valid product";
				}					
			}
			else{
				$l_sErrmsg = "Select a product";	
				return $l_sErrmsg;
			}
			
			$this->itemId = $itemId;
			return $l_sErrmsg;
		}
		
		public function setActivated($activated){
			$l_sErrmsg = "";
			
			if(isset($activated)){
				if($activated === ""){
					$l_sErrmsg = "Invalid activate state";	
					return $l_sErrmsg;
				}				
			}
			else{
				$l_sErrmsg = "Invalid activate state";		
				return $l_sErrmsg;
			}
			
			$this->activated = $activated ? 1 : 0;
			return $l_sErrmsg;
		}
		
		public function setProductionDescription($description){
			$l_sErrmsg = "";
			
			if(isset($description)){
				if($description === ""){
					$l_sErrmsg = "Enter description";	
					return $l_sErrmsg;
				}
				
				if (preg_match('/^[a-zA-Z0-9 \-\(\)]+$/i', $description)){	            	
		        }
		        else{
					$l_sErrmsg = "Use only alphanumerics, -, (, ) and space for description";
				}					
			}
			else{
				$l_sErrmsg = "Enter description";	
				return $l_sErrmsg;
			}
			
			$this->productDescription = $description;
			return $l_sErrmsg;
		}
		
		public function setPrice($price){
			$l_sErrmsg = "";
			
			if(isset($price)){
				if($price === ""){
					$l_sErrmsg = "Enter price";	
					return $l_sErrmsg;
				}
				
				if (preg_match('/^[0-9]+$/i', $price)){	 
					if($price <= 0){
						$l_sErrmsg = "Enter a valid price";
					}           	
		        }
		        else{
					$l_sErrmsg = "Enter a valid price";
				}					
			}
			else{
				$l_sErrmsg = "Enter price";		
				return $l_sErrmsg;
			}
			
			$this->price = $price;
			return $l_sErrmsg;
		}
		
		public function setQuantity($qty){
			$l_sErrmsg = "";
			
			if(isset($qty)){
				if($qty === ""){
					$l_sErrmsg = "Enter quantity";	
					return $l_sErrmsg;
				}
				
				if (preg_match('/^[0-9]+$/i', $qty)){
					if($qty <= 0){
						$l_sErrmsg = "Enter a valid quantity value";
					}	            	
		        }
		        else{
					$l_sErrmsg = "Enter a valid quantity value";
				}					
			}
			else{
				$l_sErrmsg = "Enter quantity";	
				return $l_sErrmsg;
			}
			
			$this->quantity = $qty;
			return $l_sErrmsg;
		}
		
		public function user_check($p_oUser){
		
			$l_sErrmsg = "";
			
			$l_sErrmsg = $this->set_username($p_oUser->username);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}
			
			$l_sErrmsg = $this->set_gender($p_oUser->gender);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}			
			
			$l_sErrmsg = $this->set_email($p_oUser->email);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}	
												
			$l_sErrmsg = $this->setGenPassword($p_oUser->password1, $p_oUser->password2);
			
			if($l_sErrmsg != ""){
				 return $l_sErrmsg ;
			}
			
			return $l_sErrmsg;
		}
		
		public function checkBalanceInfo($balanceInfo){
		
			$l_sErrmsg = "";			
			
			if(isset($balanceInfo->reference)){
				
				if($balanceInfo->reference === ""){
					$l_sErrmsg = "Enter Account reference";
					return $l_sErrmsg;
				}
							
				if(!ctype_alnum($balanceInfo->reference)) {				    
				    $l_sErrmsg = "Acccount Reference must only contain alphanumeric keys";
					return $l_sErrmsg;
				}				
				
				$this->reference = strtoupper($balanceInfo->reference);				
			}
			else{
				$l_sErrmsg = "Enter Account reference";
				return $l_sErrmsg; 	
			}
			
			if(isset($balanceInfo->amount)){				
				
				if($balanceInfo->amount === ""){
					$l_sErrmsg = "Enter amount";
					return $l_sErrmsg; 
				}
				
				if(!is_numeric($balanceInfo->amount)){
					$l_sErrmsg = "Use only numbers for amount";	
					return $l_sErrmsg;
				}
				
				$this->amount = $balanceInfo->amount;				
			}
			else{		
				$l_sErrmsg = "Enter amount";
				return $l_sErrmsg; 	
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
		
		public function set_register_user(){
			
			/**
			* Inserts a new user record.
			* Returns True on Success and False on failure
			* @return {boolean} returns false on failure
			* 
			*/
			/*Sanitize inputs before use*/
			
			$l_oSth = NULL;
			$l_bState = FALSE;
			$l_sUsername = 'tshebofs_bds_general';
			$l_sPassword = '7VELF8C8MjMpwnsv';
									
			try{						
				$this->connecttodb($l_sUsername, $l_sPassword);				
				$l_oSth = $this->oLink->prepare('INSERT INTO users ( pkusername, gender, email, license_code, account_reference, password) 
				 VALUES ( :p_username, :p_gender, :p_email, :p_license_code, :p_account_reference, :p_password) ');
                $l_oSth->bindParam(':p_username', $this->sUsername, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_gender', $this->sGender, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_email', $this->sEmail, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_license_code', $this->sLicensecode, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_account_reference', $this->reference, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_password', $this->sPassword, PDO::PARAM_STR);
                //run SQL query
                $l_bState = $l_oSth->execute();                 

                $this->disconnecttodb();
                
                return $l_bState;				
		    
		    } catch (Exception $e) {

		    	$this->disconnecttodb();
				return FALSE;
			}
		}	
			
		public function login_user($p_oLogindata){
			/**
			* 
			* @var 
			* 
			*/				
				$l_oSth = NULL;
				$l_bState = FALSE;
				$verified = FALSE;
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';					
									
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);	             
                
                $l_sSQLstring = 'SELECT pkusername, password, last_updated, account_reference FROM users WHERE email = :p_username LIMIT 1';

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 					                            
                $l_oSth->bindParam(':p_username', $p_oLogindata->username, PDO::PARAM_STR);                                                             
                $l_oSth->execute();
                
                $l_oUserList = $l_oSth->fetchAll(PDO::FETCH_NAMED);                          	     
                          
                $l_oUserinfo = FALSE;                
                  
                foreach($l_oUserList as $row){    
                	
                	$verified = password_verify($p_oLogindata->password, $row['password']);
                	
                	if($verified){
                		
                		$l_oUserinfo = array();  
                	
						$l_oUser = array(
							"username" => $row['pkusername'],
							"last_updated" => $row['last_updated']
							);
					
						array_push($l_oUserinfo, $l_oUser );
						
						session_start();
						//Delete old session data
						session_regenerate_id(true); 
						$_SESSION["username"] = $row['pkusername'];
						$_SESSION["account_reference"] = $row['account_reference'];	
					}			
				}		                                   
					
                $this->disconnecttodb();

                return $l_oUserinfo;                				
		    
		    } catch (Exception $e) {
		    	
		    	$this->disconnecttodb();		
				return FALSE;
			}
			
		}
		
		public function login($p_oLogindata){
			/**
			* 
			* @var 
			* 
			*/				
				$l_oSth = NULL;
				$l_bState = FALSE;
				$verified = FALSE;
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';					
									
			try{				
				//Check if email is valid
				if($this->set_email($p_oLogindata->username) != ""){					
					return FALSE;
				}
				
				$this->connecttodb($l_sUsername, $l_sPassword);	             
                                
				$l_sSQLstring = 'SELECT username, password, last_updated FROM admin WHERE pkemail = :p_email LIMIT 1';
				
                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 					                            
                $l_oSth->bindParam(':p_email', $this->sEmail, PDO::PARAM_STR);
                                                                          
                $l_oSth->execute();    
                            
                $l_oUserList = $l_oSth->fetchAll(PDO::FETCH_NAMED);                       	                        
                $l_oUserinfo = array();                
                  
                foreach($l_oUserList as $row){  
                  
                	$verified = password_verify($p_oLogindata->password, $row['password']);
                	
                	if($verified){
                		
						$l_oUser = array(
							"username" => $row['username'],
							"last_updated" => $row['last_updated']
						);
				
						array_push($l_oUserinfo, $l_oUser );
						
						session_start();
						//Delete old session data
						session_regenerate_id(true); 
						$_SESSION["admin"] = $row['username'];												
						
						$l_bState = TRUE;
					}    					
				}		                                   
					
                $this->disconnecttodb();

                return $l_bState;               				
		    
		    } catch (Exception $e) {
		    	
		    	$this->disconnecttodb();		
				return FALSE;
			}			
		}
		
		public function updateBalance(){
					
			/**
			* Updates balance.
			* Returns True on Success and False on failure
			* @return {boolean} returns false on failure
			* 
			*/
			/*Sanitize inputs before use*/
			
			$l_oSth = NULL;
			$l_bState = FALSE;
			$l_sUsername = 'tshebofs_bds_general';
			$l_sPassword = '7VELF8C8MjMpwnsv';
			$accountList = FALSE;
									
			try{						
				$this->connecttodb($l_sUsername, $l_sPassword);	
				
				$clientUsername;
				
				$l_sSQLstring = 'SELECT pkusername, email FROM users WHERE account_reference = :p_reference LIMIT 1';

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 					                            
                $l_oSth->bindParam(':p_reference', $this->reference, PDO::PARAM_STR);
                                                                         
                $l_oSth->execute();
                
                $l_oUserList = $l_oSth->fetchAll(PDO::FETCH_NAMED);                     	     
                
                //Check if user exists
                if($l_oUserList){
                	
					foreach($l_oUserList as $row){    
                	
	                	$clientUsername = $row['pkusername'];
	                	
	                	$this->set_email($row['email']);
	                	
	                	$l_sSQLstring = 'SELECT pkusername FROM account WHERE pkusername = :p_username LIMIT 1';

		                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 					                            
		                $l_oSth->bindParam(':p_username', $clientUsername, PDO::PARAM_STR);
		                                                                         
		                $l_oSth->execute();		                
		                $accountList = $l_oSth->fetchAll(PDO::FETCH_NAMED);
	                	
	                	//Check account for user exist, then update if it does
	                	if($accountList){
							$l_sSQLstring = 'UPDATE account SET balance = balance + :p_amount, last_updated = NOW()
	                           WHERE pkusername = :p_username';			
				
							$l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) );
							 
			                $l_oSth->bindParam(':p_amount', $this->amount, PDO::PARAM_STR);
			                $l_oSth->bindParam(':p_username', $clientUsername, PDO::PARAM_STR);
			                
			                $l_bState = $l_oSth->execute();	
						}
						else{
							$l_sSQLstring = 'INSERT INTO account ( pkusername, balance, last_updated) 
		                	VALUES ( :p_username, :p_amount, NOW() )';			
					
							$l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) );
							
							$l_oSth->bindParam(':p_username', $clientUsername, PDO::PARAM_STR);
			                $l_oSth->bindParam(':p_amount', $this->amount, PDO::PARAM_STR);
			                $l_bState = $l_oSth->execute();							
						}	                              						
					}
				}					             
                
                $this->disconnecttodb();                                              
                
                return $l_bState;				
		    
		    } catch (Exception $e) {

		    	$this->disconnecttodb();
				return FALSE;
			}
		}
		
		public function getAdmins(){
			/**
			* 
			* @var 
			* 
			*/				
				$l_oSth = NULL;
				$l_bState = FALSE;
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';
				$l_oUserinfo; 					
									
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);	             
                
                $l_sSQLstring = 'SELECT pkemail, last_updated FROM admin WHERE username <> :paramUsername';

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
					                            
                $l_oSth->bindParam(':paramUsername', $_SESSION["admin"], PDO::PARAM_STR);               
                                                          
                $l_oSth->execute();
                
                $l_oUserList = $l_oSth->fetchAll(PDO::FETCH_NAMED);                      	                            
                                         
                if($l_oUserList){
                	
                	$l_oUserinfo = array(); 
                	
					foreach($l_oUserList as $row){    
                
						$l_oUser = array(
							"email" => $row['pkemail'],
							"last_updated" => $row['last_updated']
							);
					
						array_push($l_oUserinfo, $l_oUser );	
					}
				}
				else{
					$l_oUserinfo = FALSE; 
				}                                 		                                   
					
                $this->disconnecttodb();

                return $l_oUserinfo;                				
		    
		    } catch (Exception $e) {
		    	
		    	$this->disconnecttodb();		
				return FALSE;
			}
			
		}	
		
		public function deleteAdmin(){
			/**
			* 
			* @var 
			* 
			*/				
				$l_oSth = NULL;
				$l_bState = FALSE;
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';
				$l_oUserinfo; 					
									
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);	             
                                            
                $l_sSQLstring = 'DELETE FROM admin WHERE (pkemail = :p_adminemail AND username <> :p_currentadmin)';
						
                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 			

                $l_oSth->bindParam(':p_adminemail', $this->sEmail, PDO::PARAM_STR);   
                $l_oSth->bindParam(':p_currentadmin', $_SESSION["admin"], PDO::PARAM_STR);              
                $l_bState = $l_oSth->execute(); 																	                              		                                   
					
                $this->disconnecttodb();

                return $l_bState;                				
		    
		    } catch (Exception $e) {
		    	
		    	$this->disconnecttodb();		
				return FALSE;
			}			
		}
		
		public function addAdmin(){
			
			/**
			* Inserts a new user record.
			* Returns True on Success and False on failure
			* @return {boolean} returns false on failure
			* 
			*/
			/*Sanitize inputs before use*/
			
			$l_oSth = NULL;
			$l_bState = FALSE;
			$l_sUsername = 'tshebofs_bds_general';
			$l_sPassword = '7VELF8C8MjMpwnsv';
									
			try{						
				$this->connecttodb($l_sUsername, $l_sPassword);				
				$l_oSth = $this->oLink->prepare('INSERT INTO admin ( pkemail, username, password) 
				 VALUES ( :p_email, :p_username, :p_password) ');
				                
                $l_oSth->bindParam(':p_email', $this->sEmail, PDO::PARAM_STR);                                
                $l_oSth->bindParam(':p_username', $this->sUsername, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_password', $this->sPassword, PDO::PARAM_STR);
                
                $l_bState = $l_oSth->execute();                                              

                $this->disconnecttodb();
                return $l_bState;				
		    
		    } catch (Exception $e) {

		    	$this->disconnecttodb();
				return FALSE;
			}
		}
		
		public function updateAdmin(){
			
			/**
			* Inserts a new user record.
			* Returns True on Success and False on failure
			* @return {boolean} returns false on failure
			* 
			*/
			/*Sanitize inputs before use*/
			
			$l_oSth = NULL;
			$l_bState = FALSE;
			$l_sUsername = 'tshebofs_bds_general';
			$l_sPassword = '7VELF8C8MjMpwnsv';
									
			try{						
				$this->connecttodb($l_sUsername, $l_sPassword);				
				$l_oSth = $this->oLink->prepare('UPDATE admin SET password = :p_password, last_updated = NOW()
				WHERE pkemail = :p_email ');
				
				$l_oSth->bindParam(':p_password', $this->sPassword, PDO::PARAM_STR);				                
                $l_oSth->bindParam(':p_email', $this->sEmail, PDO::PARAM_STR);                                
                
                $l_bState = $l_oSth->execute();                                              

                $this->disconnecttodb();
                return $l_bState;				
		    
		    } catch (Exception $e) {

		    	$this->disconnecttodb();
				return FALSE;
			}
		}		
		
		public function addLicenseCode(){
			
			/**
			* Inserts a new license coed.
			* Returns True on Success and False on failure
			* @return {boolean} returns false on failure
			* 
			*/
			/*Sanitize inputs before use*/
			
			$l_oSth = NULL;
			$l_bState = FALSE;
			$l_sUsername = 'tshebofs_bds_general';
			$l_sPassword = '7VELF8C8MjMpwnsv';
			$pkCodeId;
			$activeCode = 0;
									
			try{	
								
				$this->connecttodb($l_sUsername, $l_sPassword);
				$l_sSQLstring = 'SELECT MAX(pklicense_codeid) AS maxval FROM license_codes ';

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) );                                                                                                                 
                $l_oSth->execute();
                
                $l_oUserList = $l_oSth->fetchAll(PDO::FETCH_NAMED);                     	     
                          
                foreach($l_oUserList as $row){    
                	
                	$pkCodeId = $row['maxval'] + 1;
                	                						
					$this->connecttodb($l_sUsername, $l_sPassword);				
					$l_oSth = $this->oLink->prepare('INSERT INTO license_codes ( pklicense_codeid, license_code_name, display_name, active) 
					 VALUES ( :p_pkid, :p_code, :p_text, :p_active) ');
					
					$l_oSth->bindParam(':p_pkid', $pkCodeId, PDO::PARAM_INT);                
	                $l_oSth->bindParam(':p_code', $this->sLicensecode, PDO::PARAM_STR);                                
	                $l_oSth->bindParam(':p_text', $this->displayText, PDO::PARAM_STR);
	                $l_oSth->bindParam(':p_active', $activeCode, PDO::PARAM_INT);
	                
	                $l_bState = $l_oSth->execute();               			
				}	
				
                $this->disconnecttodb();
                return $l_bState;				
		    
		    } catch (Exception $e) {

		    	$this->disconnecttodb();
				return FALSE;
			}
		}
		
		public function getCodes($active = 0){
			/**
			* 
			* @var 
			* 
			*/				
				$l_oSth = NULL;
				$l_bState = FALSE;
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';
				$l_oUserinfo; 					
									
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);	             
                
                if($active == 0){
					$l_sSQLstring = 'SELECT pklicense_codeid, license_code_name, display_name, active FROM license_codes';
				}
				else{
					$l_sSQLstring = 'SELECT pklicense_codeid, license_code_name, display_name, active FROM license_codes 
					WHERE active = 1';
				}                                

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
				                                                          
                $l_oSth->execute();
                
                $l_oUserList = $l_oSth->fetchAll(PDO::FETCH_NAMED);                      	                            
                                         
                if($l_oUserList){
                	
                	$l_oUserinfo = array(); 
                	
					foreach($l_oUserList as $row){    
                
						$l_oUser = array(
							"codeid" => $row['pklicense_codeid'],
							"code" => $row['license_code_name'],
							"displayText" => $row['display_name'],
							"active" => $row['active']
							);
					
						array_push($l_oUserinfo, $l_oUser );	
					}
				}
				else{
					$l_oUserinfo = FALSE; 
				}                                 		                                   
					
                $this->disconnecttodb();

                return $l_oUserinfo;                				
		    
		    } catch (Exception $e) {
		    	
		    	$this->disconnecttodb();		
				return FALSE;
			}
			
		}			
		
		public function updateActive(){
			
			/**
			* Inserts a new user record.
			* Returns True on Success and False on failure
			* @return {boolean} returns false on failure
			* 
			*/
			/*Sanitize inputs before use*/
			
			$l_oSth = NULL;
			$l_bState = FALSE;
			$l_sUsername = 'tshebofs_bds_general';
			$l_sPassword = '7VELF8C8MjMpwnsv';
									
			try{						
				$this->connecttodb($l_sUsername, $l_sPassword);				
				$l_oSth = $this->oLink->prepare('UPDATE license_codes SET active = :p_active
				WHERE pklicense_codeid = :p_codeid ');	
							
				$l_oSth->bindParam(':p_active', $this->activated, PDO::PARAM_INT); 				                
                $l_oSth->bindParam(':p_codeid', $this->codeId, PDO::PARAM_INT);                                              
                
                $l_bState = $l_oSth->execute();                                              

                $this->disconnecttodb();
                return $l_bState;				
		    
		    } catch (Exception $e) {

		    	$this->disconnecttodb();
				return FALSE;
			}
		}
		
		public function deleteCode(){
			/**
			* 
			* @var 
			* 
			*/				
				$l_oSth = NULL;
				$l_bState = FALSE;
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';
				$l_oUserinfo; 	
				$errorMessage = "";				
									
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);	             
                
                $l_sSQLstring = 'SELECT pkdate FROM bookings WHERE fklicense_codeid = :p_codeid LIMIT 1';

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 					                            
                $l_oSth->bindParam(':p_codeid', $this->codeId, PDO::PARAM_INT);  
                                                                         
                $l_oSth->execute();                
                $bookings = $l_oSth->fetchAll(PDO::FETCH_NAMED);                    	     
                                
                foreach($bookings as $row){                   
                	$this->disconnecttodb();
                	return "License code in use";                	                 						
				}               																	                              		                                   
				
				$l_sSQLstring = 'SELECT credits FROM credits WHERE credits > 0 and fklicense_codeid = :p_codeid LIMIT 1';

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 					                            
                $l_oSth->bindParam(':p_codeid', $this->codeId, PDO::PARAM_INT);  
                                                                         
                $l_oSth->execute();
                
                $credits = $l_oSth->fetchAll(PDO::FETCH_NAMED);
                
                foreach($credits as $row){                
                	$this->disconnecttodb();
                	return "License code in use";                	                 						
				}                   	     
                                            
                $l_sSQLstring = 'DELETE FROM license_codes WHERE pklicense_codeid = :p_codeid';
						
                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 	
                $l_oSth->bindParam(':p_codeid', $this->codeId, PDO::PARAM_INT);                 
                $l_bState = $l_oSth->execute();	                      
                
                $this->disconnecttodb();
				$l_bState = TRUE;
				
                return $l_bState ? $errorMessage : "Could delete license code";                				
		    
		    } catch (Exception $e) {
		    	$errorMessage = "Could not delete license code";
		    	$this->disconnecttodb();		
				return $errorMessage;
			}			
		}	
		
		public function updateCode(){
			
			/**
			* Update existing license code.
			* Returns True on Success and False on failure
			* @return {boolean} returns false on failure
			* 
			*/
			/*Sanitize inputs before use*/
			
			$l_oSth = NULL;
			$l_bState = FALSE;
			$l_sUsername = 'tshebofs_bds_general';
			$l_sPassword = '7VELF8C8MjMpwnsv';
									
			try{	
			
				$this->connecttodb($l_sUsername, $l_sPassword);				
				$l_oSth = $this->oLink->prepare('UPDATE license_codes SET display_name = :p_text 
				WHERE license_code_name = :p_codename ');
				
				$l_oSth->bindParam(':p_text', $this->newDisplayText, PDO::PARAM_STR);
				$l_oSth->bindParam(':p_codename', $this->sLicensecode, PDO::PARAM_STR);  
				
                $l_oSth->execute();   
                $l_bState = $l_oSth->rowCount() ? true : false;                                           

                $this->disconnecttodb();
                
                return $l_bState;				                   	     
                
		    } catch (Exception $e) {

		    	$this->disconnecttodb();
				return FALSE;
			}
		}
		
		public function getProducts(){
			/**
			* 
			* @var 
			* 
			*/				
				$l_oSth = NULL;
				$l_bState = FALSE;
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';
				$productInfo; 					
									
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);	             
                
                $l_sSQLstring = 'SELECT pkitemid, price, fkproductid, quantity, description, active FROM catalogue';

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 					                          
                                                    
                $l_oSth->execute();
                
                $productList = $l_oSth->fetchAll(PDO::FETCH_NAMED);              	                          
                                    
                if($productList){
                	
                	$productInfo = array(); 
                	
					foreach($productList as $row){    
                
						$product = array(
							"itemNo" => $row['pkitemid'],
							"price" => $row['price'],
							"productid" => $row['fkproductid'],
							"quantity" => $row['quantity'],
							"description" => $row['description'],
							"active" => $row['active']
							);
					
						array_push($productInfo, $product );	
					}
				}
				else{
					$productInfo = FALSE; 
				}                               		                                   
								
                $this->disconnecttodb();

                return $productInfo;                				
		    
		    } catch (Exception $e) {
		    	
		    	$this->disconnecttodb();		
				return FALSE;
			}			
		}	
		
		public function updateProductActive(){
			
			/**
			* 
			* Returns True on Success and False on failure
			* @return {boolean} returns false on failure
			* 
			*/
			/*Sanitize inputs before use*/
			
			$l_oSth = NULL;
			$l_bState = FALSE;
			$l_sUsername = 'tshebofs_bds_general';
			$l_sPassword = '7VELF8C8MjMpwnsv';
									
			try{						
				$this->connecttodb($l_sUsername, $l_sPassword);				
				$l_oSth = $this->oLink->prepare('UPDATE catalogue SET active = :p_active
				WHERE pkitemid = :p_itemid');	
							
				$l_oSth->bindParam(':p_active', $this->activated, PDO::PARAM_INT); 				                
                $l_oSth->bindParam(':p_itemid', $this->itemId, PDO::PARAM_INT);                                              
                
                $l_bState = $l_oSth->execute();                                              

                $this->disconnecttodb();
                return $l_bState;				
		    
		    } catch (Exception $e) {

		    	$this->disconnecttodb();
				return FALSE;
			}
		}
		
		public function deleteProduct(){
			/**
			* 
			* @var 
			* 
			*/				
				$l_oSth = NULL;
				$l_bState = FALSE;
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';
				$l_oUserinfo; 	
				$errorMessage = "";				
									
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);	             
                
                $l_sSQLstring = 'SELECT pkitemid FROM catalogue WHERE pkitemid = :productid LIMIT 1';

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 					                            
                $l_oSth->bindParam(':productid', $this->itemId, PDO::PARAM_INT);  
                                                                         
                $l_oSth->execute();                
                $products = $l_oSth->fetchAll(PDO::FETCH_NAMED);                    	     
                
                if($products){
					
					$l_sSQLstring = 'DELETE FROM catalogue WHERE pkitemid = :productid';
						
	                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 	
	                $l_oSth->bindParam(':productid', $this->itemId, PDO::PARAM_INT);                 
	                $l_bState = $l_oSth->execute();	                      
	                
	                $this->disconnecttodb();								 			
				}
				else{
					$this->disconnecttodb();
                	return "Product does not exist";
				}
                                				
                return $l_bState ? $errorMessage : "Could not delete product";                				
		    
		    } catch (Exception $e) {
		    	$errorMessage = "Could not delete product";
		    	$this->disconnecttodb();		
				return $errorMessage;
			}			
		}
		
		public function addProduct(){
			
			/**
			* Inserts a new product.
			* Returns True on Success and False on failure
			* @return {boolean} returns false on failure
			* 
			*/
			/*Sanitize inputs before use*/
			
			$l_oSth = NULL;
			$l_bState = FALSE;
			$l_sUsername = 'tshebofs_bds_general';
			$l_sPassword = '7VELF8C8MjMpwnsv';
			$pkItemId = 0;
												
			try{	
								
				$this->connecttodb($l_sUsername, $l_sPassword);
				$l_sSQLstring = 'SELECT MAX(pkitemid) AS maxval FROM catalogue ';

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) );                                                                                                                 
                $l_oSth->execute();
                
                $l_oUserList = $l_oSth->fetchAll(PDO::FETCH_NAMED);                     	     
                          
                foreach($l_oUserList as $row){    
                	                	
                	$pkItemId = $row['maxval'] + 1;
                	                		
					$l_sSQLstring = "INSERT INTO catalogue ( pkitemid, price, fkproductid, quantity, description) 
					 VALUES ( :p_itemid, :p_price, :p_productid, :p_qty, :p_description)";
										
               	    $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) );			
						
					$l_oSth->bindParam(':p_itemid', $pkItemId, PDO::PARAM_INT);                
	                $l_oSth->bindParam(':p_price', $this->price, PDO::PARAM_STR);                                
	                $l_oSth->bindParam(':p_productid', $this->codeId, PDO::PARAM_INT);
	                $l_oSth->bindParam(':p_qty', $this->quantity, PDO::PARAM_INT);
	                $l_oSth->bindParam(':p_description', $this->productDescription, PDO::PARAM_STR);	
					
	                $l_bState = $l_oSth->execute();  
	                           			
				}	
				
                $this->disconnecttodb();
                return $l_bState;				
		    
		    } catch (Exception $e) {

		    	$this->disconnecttodb();
				return FALSE;
			}
		}
		
		public function updateProduct(){
			
			/**
			* Update existing product.
			* Returns True on Success and False on failure
			* @return {boolean} returns false on failure
			* 
			*/
			/*Sanitize inputs before use*/
			
			$l_oSth = NULL;
			$l_bState = FALSE;
			$l_sUsername = 'tshebofs_bds_general';
			$l_sPassword = '7VELF8C8MjMpwnsv';
			$l_sSQLstring = "";
									
			try{	
			
				$this->connecttodb($l_sUsername, $l_sPassword);	
				
				$q = array();
				
				if($this->productDescription != "" ){
				    $q[] = "description = :p_description";
				}
				if($this->price > 0 ){
				    $q[] = "price = :p_price";
				}
				if($this->quantity > 0){
				    $q[] = "quantity = :p_quantity";
				}
				
				$q[] = "last_updated = NOW()";
				
				if(sizeof($q) > 0){//check if we have any updates otherwise don't execute
				    $l_sSQLstring = "UPDATE catalogue SET " . implode(", ", $q) . " WHERE pkitemid= :pkitemid";
				    
				    $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) );
				    	
				    $l_oSth->bindParam(':pkitemid', $this->itemId, PDO::PARAM_INT);
				    
				    if($this->productDescription != "" ){
				        $l_oSth->bindParam(":p_description", $this->productDescription);
				    }
				    if($this->price > 0 ){
				        $l_oSth->bindParam(":p_price", $this->price);
				    }
				    if($this->quantity > 0){
				        $l_oSth->bindParam(":p_quantity", $this->quantity);
				    }
				    
				    $l_oSth->execute();   
                	$l_bState = $l_oSth->rowCount() ? true : false; 
				}			
                   
                $this->disconnecttodb();
                
                return $l_bState;				                   	     
                
		    } catch (Exception $e) {

		    	$this->disconnecttodb();
				return FALSE;
			}
		}
		
		public function sendEmailBalance(){
			try{
				
				$l_bemail_sent = FALSE;
				$l_sto      = $this->sEmail;
				$l_ssubject = 'Balance updated [DO NOT REPLY]';
				$l_smessage = 'Good day,';
				$l_smessage = $l_smessage."\r\n\r\n".'Your balance has been updated by R '.$this->amount.'.';				
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
	    //$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
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
			
		$error_message = "";
		//Get the data
		if(isset($_POST['reg_data'])){	
			
			$l_oData = $_POST['reg_data'];
			$l_oNewuser = json_decode($l_oData);			
			$error_message = "";
						
			if(isset($l_oNewuser->terms)){					
							
				$l_oUser = new clUsers();
				
				//Validate the data
				$error_message = $l_oUser->user_check($l_oNewuser);
				
				$l_oJSON['check'] = ($error_message == "") ? TRUE : FALSE;
				
				if($l_oJSON['check']){
					
					//Save the data
					$reference = getToken(6);
					
					if($reference == ""){
						$error_message = 'Could not create account: Missing Reference';
					}
					else{						
					
						$l_oUser->set_reference($reference);
						
						$l_oJSON['check'] = $l_oUser->set_register_user();
					
						if($l_oJSON['check'] == FALSE){
							$error_message = 'Could not create account';
						}
						else{
							$error_message = 'Account was successfully created';
							$l_oJSON['url'] = "bookings.php";
							$l_oJSON['check'] = TRUE;
						}						
					}
				}				
									
				//Send feedback to user
				$l_oJSON['message'] = $error_message;				
			}
			else{

				$l_oJSON['check'] = FALSE;
				$l_oJSON['message'] = 'You need to accept the terms and conditions to register';				
			}
			
			echo json_encode($l_oJSON);
		}
		elseif(isset($_POST['login_data'])){	
			
			$l_ologData = $_POST['login_data'];
			$l_oReguser = json_decode($l_ologData);			
			$error_message = "";					
												
			$l_ologUser = new clUsers();
			
			$l_oJSON['user_info'] = $l_ologUser->login_user($l_oReguser);
			
			if($l_oJSON['user_info'] == FALSE){				
				$error_message = "Invalid username or password";
				$l_oJSON['check'] = FALSE;
			}
			else{
				$l_oJSON['url'] = "bookings.php";
				$l_oJSON['check'] = TRUE;
			}
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);			
		}
		elseif(isset($_POST['admin_login'])){	
			
			$l_ologData = $_POST['admin_login'];
			$l_oReguser = json_decode($l_ologData);			
			$error_message = "";					
												
			$l_ologUser = new clUsers();
			
			$l_oJSON['check'] = $l_ologUser->login($l_oReguser);
			
			if($l_oJSON['check'] == FALSE){
				$error_message = "Invalid email or password";
			}
			else{
				
				$l_oJSON['url'] = "admin_pay.php";
				$error_message = 'Login successful';
			}
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);			
		}
		elseif(isset($_POST['update_balance'])){	
			//Start session
			session_start();
			$l_oJSON['check'] = FALSE;
			$error_message = "";
			
			if(isset($_SESSION["admin"]))
			{
				$userEnteredData = $_POST['update_balance'];
				$decodedUserEnteredData = json_decode($userEnteredData);										
													
				$admin = new clUsers();
				
				$error_message = $admin->checkBalanceInfo($decodedUserEnteredData);
				
				if($error_message === "")
				{				
					$l_oJSON['check'] = $admin->updateBalance();
					
					if($l_oJSON['check'] == FALSE){
						$error_message = 'Could not update balance';
					}
					else{		
						$error_message = 'Balance updated successfully';
						//Send email notification
						$admin->sendEmailBalance();
					}
				}				
			}
			else{
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}	
					
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);				
		}
      	elseif(isset($_POST['get_admins'])){	
        	//Start session
			session_start();
			
	        if(isset($_SESSION["admin"])){
	        	
	        	$error_message = "";														
			    $admin = new clUsers();
		
				$l_oJSON['adminList'] = $admin->getAdmins();
				
				if($l_oJSON['adminList'] == FALSE){
					$l_oJSON['check'] = FALSE;
					$error_message = 'Could not get administrators';
				}
	            else{
	            	$l_oJSON['check'] = TRUE;
	            }          		               
		    }
			else{
				$l_oJSON['check'] = FALSE;
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);			
		}
		elseif(isset($_POST['reg_admin'])){	
			
			//Start session
			session_start();
			$error_message = "";
			$l_oJSON['check'] = FALSE;
			
	        if(isset($_SESSION["admin"])){
	        		        	   	
	        	$l_oData = $_POST['reg_admin'];
				$newAdminInfo = json_decode($l_oData);			
				$error_message = "";
				
				$l_oUser = new clUsers();								
				
				$error_message = $l_oUser->set_email($newAdminInfo->email);
				
				if($error_message === ""){
					
					$error_message = $l_oUser->set_password($newAdminInfo->password1, $newAdminInfo->password2);
				
					if($error_message === ""){
						
						$generatedUsername = getToken(6);
				
						$error_message = $l_oUser->set_username($generatedUsername);
						
						if($error_message === ""){
							
							$l_oJSON['check'] = $l_oUser->addAdmin();
							
							if($l_oJSON['check']){
								$error_message = "Admin successfully added";
							}
							else{
								$error_message = "Failed to add admin";
							}							
						}
					}				
				}
			}
			else{
				$l_oJSON['check'] = FALSE;
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);			
			
		}
		elseif(isset($_POST['del_admin'])){	
			
			//Start session
			session_start();
			
	        if(isset($_SESSION["admin"])){
	        	
	        	$l_oData = $_POST['del_admin'];
				$l_oNewuser = json_decode($l_oData);			
				$error_message = "";
							
				if(isset($l_oNewuser->email)){					
								
					$l_oUser = new clUsers();
					
					//Validate the data
					$error_message = $l_oUser->set_email($l_oNewuser->email);
					
					$l_oJSON['check'] = ($error_message == "") ? TRUE : FALSE;
					
					if($l_oJSON['check']){					
													
						$l_oJSON['check'] = $l_oUser->deleteAdmin();
						
						if($l_oJSON['check'] == FALSE){
							$error_message = 'Could not delete admin';
						}
						else{
							$error_message = 'Admin was successfully deleted';
						}					
					}
				}
				else{

					$l_oJSON['check'] = FALSE;
					$l_oJSON['message'] = 'Select Admin to delete';					
				}	        	         		               
		    }
			else{
				$l_oJSON['check'] = FALSE;
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);				
			
		}
		elseif(isset($_POST['update_admin'])){	
			
			//Start session
			session_start();
			$error_message = "";
			$l_oJSON['check'] = FALSE;
			
	        if(isset($_SESSION["admin"])){
	        		        	   	
	        	$l_oData = $_POST['update_admin'];
				$existingAdminInfo = json_decode($l_oData);			
				$error_message = "";
				
				$l_oUser = new clUsers();								
				
				$error_message = $l_oUser->set_email($existingAdminInfo->email);
				
				if($error_message === ""){
					
					$error_message = $l_oUser->set_password($existingAdminInfo->password1, $existingAdminInfo->password2);
				
					if($error_message === ""){
						
						$l_oJSON['check'] = $l_oUser->updateAdmin();
							
						if($l_oJSON['check']){
							$error_message = "Admin password was successfully updated";
						}
						else{
							$error_message = "Failed to update admin password";
						}											
					}				
				}
			}
			else{
				$l_oJSON['check'] = FALSE;
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);			
			
		}
		elseif(isset($_POST['reg_lcodes'])){	
			
			//Start session
			session_start();
			$error_message = "";
			$l_oJSON['check'] = FALSE;
			
	        if(isset($_SESSION["admin"])){
	        		        	   	
	        	$l_oData = $_POST['reg_lcodes'];
				$newAdminInfo = json_decode($l_oData);			
				$error_message = "";
				
				$l_oUser = new clUsers();								
				
				$error_message = $l_oUser->setCode($newAdminInfo->code);
				
				if($error_message === ""){
					
					$error_message = $l_oUser->setLicenseCodeDisplayName($newAdminInfo->displayText);
				
					if($error_message === ""){
							
						$l_oJSON['check'] = $l_oUser->addLicenseCode();										
						
						if($l_oJSON['check']){
							$error_message = "License code added";
						}
						else{
							$error_message = "Failed to create new license code";
						}
					}				
				}
			}
			else{
				$l_oJSON['check'] = FALSE;
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);			
			
		}
		elseif(isset($_POST['get_codes'])){	
        	//Start session
			session_start();
			
	        if(isset($_SESSION["admin"])){
	        	
	        	$activeCode = 0;	        	
	        	$l_oData = $_POST['get_codes'];
	        	$newAdminInfo = json_decode($l_oData);		
	        	$error_message = "";														
			    $admin = new clUsers();
			    
			    if(isset($newAdminInfo->active)){
					$activeCode = $newAdminInfo->active ? 1 : 0;
				}
		
				$l_oJSON['codeList'] = $admin->getCodes($activeCode);
				
				if($l_oJSON['codeList'] == FALSE){
					$l_oJSON['check'] = FALSE;
					$error_message = 'Could not get license codes';
				}
	            else{
	            	$l_oJSON['check'] = TRUE;
	            }          		               
		    }
			else{
				$l_oJSON['check'] = FALSE;
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);			
		}
		elseif(isset($_POST['activate_lcodes'])){	
			
			//Start session
			session_start();
			$error_message = "";
			$l_oJSON['check'] = FALSE;
			
	        if(isset($_SESSION["admin"])){
	        		        	   	
	        	$l_oData = $_POST['activate_lcodes'];
				$existingAdminInfo = json_decode($l_oData);			
				$error_message = "";
				
				$l_oUser = new clUsers();								
				
				$error_message = $l_oUser->setCodeId($existingAdminInfo->codeid);
				
				if($error_message === ""){
					
					$error_message = $l_oUser->setActivated($existingAdminInfo->active);
				
					if($error_message === ""){
						
						$l_oJSON['check'] = $l_oUser->updateActive();
							
						if($l_oJSON['check']){
							
							if($existingAdminInfo->active){
								$error_message = "License code was successfully activated";
							}	
							else{
								$error_message = "License code was successfully deactivated";
							}
						}
						else{
							
							if($existingAdminInfo->active){
								$error_message = "Failed to activate license code";
							}	
							else{
								$error_message = "Failed to deactivate license code";
							}
						
						}											
					}				
				}
			}
			else{
				$l_oJSON['check'] = FALSE;
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);			
			
		}
		elseif(isset($_POST['del_lcodes'])){	
			
			//Start session
			session_start();
			$l_oJSON['check'] = FALSE;
			$error_message = "";
			
	        if(isset($_SESSION["admin"])){
	        	
	        	$l_oData = $_POST['del_lcodes'];
				$existingAdminInfo = json_decode($l_oData);			
								
				$l_oUser = new clUsers();
				
				//Validate the data
				$error_message = $l_oUser->setCodeId($existingAdminInfo->codeid);
												
				if($error_message == ""){					
												
					$error_message = $l_oUser->deleteCode();
					
					if($error_message == ""){
						$error_message = 'License code was successfully deleted';
						$l_oJSON['check'] = TRUE;
					}					
				}				        	         		               
		    }
			else{				
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);				
			
		}
		elseif(isset($_POST['update_lcodes'])){	
			
			//Start session
			session_start();
			$error_message = "";
			$l_oJSON['check'] = FALSE;
			
	        if(isset($_SESSION["admin"])){
	        		        	   	
	        	$l_oData = $_POST['update_lcodes'];
				$newAdminInfo = json_decode($l_oData);			
				$error_message = "";
				
				$l_oUser = new clUsers();								
				
				$error_message = $l_oUser->setCode($newAdminInfo->code);
				
				if($error_message === ""){
					
					$error_message = $l_oUser->setNewLicenseCodeDisplayName($newAdminInfo->displayText);
				
					if($error_message === ""){
							
						$l_oJSON['check'] = $l_oUser->updateCode();										
												
						if($l_oJSON['check'] == TRUE){
							$error_message = "License code updated"."-".$newAdminInfo->code."-".$newAdminInfo->displayText;
						}
						else{
							$error_message = "Failed to update license code";
						}
					}				
				}
			}
			else{
				$l_oJSON['check'] = FALSE;
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);			
			
		}
		elseif(isset($_POST['get_products'])){	
        	//Start session
			session_start();
			$error_message = "";
			
	        if(isset($_SESSION["admin"])){
	        		        															
			    $admin = new clUsers();
		
				$l_oJSON['productList'] = $admin->getProducts();
				
				if($l_oJSON['productList'] == FALSE){
					$l_oJSON['check'] = FALSE;
					$error_message = 'Could not get products';
				}
	            else{
	            	$l_oJSON['check'] = TRUE;
	            }          		               
		    }
			else{
				$l_oJSON['check'] = FALSE;
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				

			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);			
		}
		elseif(isset($_POST['activate_product'])){	
			
			//Start session
			session_start();
			$error_message = "";
			$l_oJSON['check'] = FALSE;
			
	        if(isset($_SESSION["admin"])){
	        		        	   	
	        	$l_oData = $_POST['activate_product'];
				$existingAdminInfo = json_decode($l_oData);			
				$error_message = "";
				
				$l_oUser = new clUsers();								
				
				$error_message = $l_oUser->setItemId($existingAdminInfo->product);
				
				if($error_message === ""){
					
					$error_message = $l_oUser->setActivated($existingAdminInfo->active);
				
					if($error_message === ""){
						
						$l_oJSON['check'] = $l_oUser->updateProductActive();
							
						if($l_oJSON['check']){
							
							if($existingAdminInfo->active){
								$error_message = "Product was successfully activated";
							}	
							else{
								$error_message = "Product was successfully deactivated";
							}
						}
						else{
							
							if($existingAdminInfo->active){
								$error_message = "Failed to activate product";
							}	
							else{
								$error_message = "Failed to deactivate product";
							}
						
						}											
					}				
				}
			}
			else{
				$l_oJSON['check'] = FALSE;
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);			
			
		}
		elseif(isset($_POST['del_product'])){	
			
			//Start session
			session_start();
			$l_oJSON['check'] = FALSE;
			$error_message = "";
			
	        if(isset($_SESSION["admin"])){
	        	
	        	$l_oData = $_POST['del_product'];
				$existingAdminInfo = json_decode($l_oData);			
								
				$l_oUser = new clUsers();
				
				//Validate the data				
				$error_message = $l_oUser->setItemId($existingAdminInfo->product);
												
				if($error_message == ""){					
												
					$error_message = $l_oUser->deleteProduct();
					
					if($error_message == ""){
						$error_message = 'Product was successfully deleted';
						$l_oJSON['check'] = TRUE;
					}					
				}				        	         		               
		    }
			else{				
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);				
			
		}
		elseif(isset($_POST['reg_product'])){	
			
			//Start session
			session_start();
			$error_message = "";
			$l_oJSON['check'] = FALSE;
			
	        if(isset($_SESSION["admin"])){
	        		        	   	
	        	$l_oData = $_POST['reg_product'];
				$newAdminInfo = json_decode($l_oData);			
				$error_message = "";
				
				$l_oUser = new clUsers();								
				
				$error_message = $l_oUser->setProductionDescription($newAdminInfo->description);
				
				if($error_message === ""){
					
					$error_message = $l_oUser->setCodeId($newAdminInfo->licenseCode);
				
					if($error_message === ""){
							
						$error_message = $l_oUser->setPrice($newAdminInfo->price);										
						
						if($error_message === ""){
							
							$error_message = $l_oUser->setQuantity($newAdminInfo->quantity);										
							
							if($error_message === ""){	
														
								$l_oJSON['check'] = $l_oUser->addProduct();
							
								if($l_oJSON['check']){
									$error_message = "Product was successfully added";
								}
								else{
									$error_message = "Failed to add new product";
								}
							}												
						}	
					}				
				}
			}
			else{
				$l_oJSON['check'] = FALSE;
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);			
			
		}
		elseif(isset($_POST['update_product'])){	
			
			//Start session
			session_start();
			$error_message = "";
			$l_oJSON['check'] = FALSE;
			
	        if(isset($_SESSION["admin"])){
	        		        	   	
	        	$l_oData = $_POST['update_product'];
				$productInfo = json_decode($l_oData);			
				$error_message = "";
				
				$l_oUser = new clUsers();								
				//Check which fields were filled and then check them after add them
				
				do {
					
					if(isset($productInfo->product)){
						$error_message = $l_oUser->setItemId($productInfo->product);	
						
						if($error_message){
							break;
						}					
					}
					
					if(isset($productInfo->description)){
						$error_message = $l_oUser->setProductionDescription($productInfo->description);
						
						if($error_message){
							break;
						}					
					}
					
					if(isset($productInfo->price)){
						$error_message = $l_oUser->setPrice($productInfo->price);	
						
						if($error_message){
							break;
						}					
					}
					
					if(isset($productInfo->quantity)){
						$error_message = $l_oUser->setQuantity($productInfo->quantity);	
						
						if($error_message){
							break;
						}					
					}
					
					//Update completed fields
					$l_oJSON['check'] = $l_oUser->updateProduct();										
												
					if($l_oJSON['check'] == TRUE){
						$error_message = "Product updated successfully";
					}
					else{
						$error_message = "Failed to update product :{";
					}
					
					break;
					
				} while(TRUE);
								
			}
			else{
				$l_oJSON['check'] = FALSE;
				$l_oJSON['url'] = "admin.php";
				$error_message = "You need to sign in";
			}				
				
			//Send feedback to user
			$l_oJSON['message'] = $error_message;				
			echo json_encode($l_oJSON);			
			
		}
		else{

			$l_oJSON['check'] = FALSE;
			$l_oJSON['message'] = 'Connection error. Try again';				
			echo json_encode($l_oJSON);	
		}
	}	
?>