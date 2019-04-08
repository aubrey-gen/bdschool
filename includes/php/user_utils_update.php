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
		
		public function encryptPassword($clearTextPassword){
			$l_sErrmsg = "";
			$this->sPassword = password_hash($clearTextPassword, PASSWORD_DEFAULT);
			
			if($this->sPassword == FALSE){
				return FALSE;
			}
			return TRUE;
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
			else{
				$l_sErrmsg = "Passwords must match";
			}			
			
			return $l_sErrmsg;				
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
				$l_oSth = $this->oLink->prepare('INSERT INTO users ( pkusername, gender, email, license_code, password) 
				 VALUES ( :p_username, :p_gender, :p_email, :p_license_code, :p_password) ');
                $l_oSth->bindParam(':p_username', $this->sUsername, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_gender', $this->sGender, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_email', $this->sEmail, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_license_code', $this->sLicensecode, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_password', $this->sPassword, PDO::PARAM_STR);
                
                $l_bState = $l_oSth->execute();                                              

                $this->disconnecttodb();
                return $l_bState;				
		    
		    } catch (Exception $e) {

		    	$this->disconnecttodb();
				return FALSE;
			}
		}
		public function update_password($p_brecovery = FALSE){
			
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
				
				if($p_brecovery){
					
					$l_sSQLstring = 'UPDATE users SET password = :p_new_password, last_updated = NOW()
                               WHERE email = :p_email';			
				
					$l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) );
					 
	                $l_oSth->bindParam(':p_new_password', $this->sPassword, PDO::PARAM_STR);
	                $l_oSth->bindParam(':p_email', $this->sEmail, PDO::PARAM_STR);
				}
				else{
					
					$l_sSQLstring = 'UPDATE users SET password = :p_new_password, last_updated = NOW()
                               WHERE pkusername = :p_username';			
				
					$l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) );
					 
	                $l_oSth->bindParam(':p_new_password', $this->sPassword, PDO::PARAM_STR);
	                $l_oSth->bindParam(':p_username', $this->sUsername, PDO::PARAM_STR);
				}				             
                
                $l_bState = $l_oSth->execute();                                													
				
				if($l_bState){
				
					$l_sSQLstring = 'DELETE FROM password_recovery WHERE (pkuseremail = :p_email)';
						
	                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 			

	                $l_oSth->bindParam(':p_email', $this->sEmail, PDO::PARAM_STR);                 
	                $l_bState = $l_oSth->execute(); 					
				}                                           

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
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';					
									
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);	             
                
                $l_sSQLstring = 'SELECT pkusername, last_updated FROM users WHERE (email = :p_username AND password = :p_password) LIMIT 1';

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
					                            
                $l_oSth->bindParam(':p_username', $p_oLogindata->username, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_password', $p_oLogindata->password, PDO::PARAM_STR);
                                                          
                $l_oSth->execute();
                
                $l_oUserList = $l_oSth->fetchAll(PDO::FETCH_NAMED);                          	     
                          
                $l_oUserinfo = array();                
                  
                foreach($l_oUserList as $row){    
                
					$l_oUser = array(
						"username" => $row['pkusername'],
						"last_updated" => $row['last_updated']
						);
				
					array_push($l_oUserinfo, $l_oUser );
					
					session_start();
					//Delete old session data
					session_regenerate_id(true); 
					$_SESSION["username"] = $row['pkusername'];			
				}		                                   
					
                $this->disconnecttodb();

                return $l_oUserinfo;                				
		    
		    } catch (Exception $e) {
		    	
		    	$this->disconnecttodb();		
				return FALSE;
			}
			
		}
		
		public function cancel_booking(){
			/**
			* 
			* @var 
			* 
			*/				
				$l_oSth = NULL;
				$l_bState = FALSE;
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';					
									
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);	  
				           
                //Validate Booking date and time
                $l_sSQLstring = 'SELECT fklicense_codeid FROM bookings 
                WHERE (pkdate = :p_date AND pktime = :p_time AND pkuserid = :p_username) LIMIT 1';

                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
					                            
                $l_oSth->bindParam(':p_date', $this->sBookeddate, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_time', $this->sBookedtime, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_username', $this->sUsername, PDO::PARAM_STR);
                                                          
                $l_bState = $l_oSth->execute();
                
                if($l_bState){					
				                
	                $l_oUserList = $l_oSth->fetchAll(PDO::FETCH_NAMED);                         	     
	
	                $l_iLicense_code = "";               
	                  
	                foreach($l_oUserList as $row){
						$l_iLicense_code = $row['fklicense_codeid'];			
					}
					
					//	
					$this->oLink->beginTransaction();
					
					$l_sSQLstring = 'DELETE FROM bookings 
                		WHERE (pkdate = :p_date AND pktime = :p_time AND pkuserid = :p_username)';
							
	                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
	             
	                $l_oSth->bindParam(':p_date', $this->sBookeddate, PDO::PARAM_STR);
                	$l_oSth->bindParam(':p_time', $this->sBookedtime, PDO::PARAM_STR);
                	$l_oSth->bindParam(':p_username', $this->sUsername, PDO::PARAM_STR);
                                 
	                $l_bState = $l_oSth->execute(); 
	                				
					if($l_bState){
						
						//Update credits	
			                	
	                	$l_sSQLstring = 'UPDATE credits SET credits = (credits + 1), last_updated = NOW()
                               WHERE fklicense_codeid = :p_license_codeid AND pkusername = :p_username';
						
		                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 			
					                
		                $l_oSth->bindParam(':p_license_codeid', $l_iLicense_code, PDO::PARAM_INT);
		                $l_oSth->bindParam(':p_username', $this->sUsername, PDO::PARAM_STR);                 
		                $l_bState = $l_oSth->execute();      
		                
						if($l_bState){
							$this->oLink->commit();					
						}
						else{
							//Rollback
							$this->oLink->rollBack();					
						}					
					}
					else{
						$this->oLink->rollBack();	
					}						                                   
				}	
				
                $this->disconnecttodb();

                return $l_bState;                				
		    
		    } catch (Exception $e) {
		    	
		    	$this->disconnecttodb();		
				return FALSE;
			}			
		}
		
		public function get_booked_dates(){
			/**
			* 
			* @var 
			* 
			*/				
				$l_oSth = NULL;
				$l_bState = FALSE;
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';
				$l_sDatetime = '';	
				$l_oDates;
				$l_sDate;	
				$l_iCount = 0;					
									
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);		
				
				//Get Booked dates and times                                       
                $l_sSQLstring = "SELECT bookings.pkdate, bookings.pktime, license_codes.display_name FROM bookings 
					 LEFT JOIN license_codes ON license_codes.pklicense_codeid = bookings.fklicense_codeid 
					 WHERE bookings.pkdate > NOW() AND bookings.pkuserid = :p_userid";
                
                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
	
                $l_oSth->bindParam(':p_userid', $this->sUsername, PDO::PARAM_STR);
                                                          
                $l_oSth->execute();
                
                $l_oBookedDates = $l_oSth->fetchAll(PDO::FETCH_NAMED);                          	     
                          
                $l_oDates = array();                
                
                foreach($l_oBookedDates as $row){
                	                	
					$l_sDateJS = strtotime($row['pkdate']);
					$l_sTimeJS = strtotime($row['pktime']);
					
					$l_oDate = array(
						"day" => $row['pkdate'],					
						"time" =>  $row['pktime'],
						"display_name" =>  $row['display_name'],
						"day_display" => date("d F Y", $l_sDateJS),
						"time_display" =>  date("H:i", $l_sTimeJS)						
						);
						
					array_push($l_oDates, $l_oDate );					
				}		                                   

                $this->disconnecttodb();

                return $l_oDates;                				
		    
		    } catch (Exception $e) {
		    	
		    	$this->disconnecttodb();
				return FALSE;
			}			
		}
		
		public function delete_account(){
			/**
			* 
			* @var 
			* 
			*/				
				$l_oSth = NULL;
				$l_bState = FALSE;
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';					
									
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);	  
				           
                //              				
				$l_sSQLstring = 'SELECT COUNT( DISTINCT pkuserid) AS num_del_users 
				FROM bookings WHERE pkuserid LIKE ":p_username%" GROUP BY pkuserid';
				
                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
			               
                $l_oSth->bindParam(':p_username', $this->sUsername, PDO::PARAM_STR);
                                                          
                $l_bState = $l_oSth->execute();
                
                if($l_bState){					
				                
	                $l_oUserList = $l_oSth->fetchAll(PDO::FETCH_NAMED);                         	     
					$l_NumList = count($l_oUserList)  + 1;
	                $l_sNewDeletedUser = "deleted_user_".$l_NumList ;               
	                					
					//	
					$this->oLink->beginTransaction();
					
					$l_sSQLstring = 'UPDATE bookings SET pkuserid = :p_new_username
                		WHERE (pkuserid = :p_username)';
							
	                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
	             	                
                	$l_oSth->bindParam(':p_new_username', $l_sNewDeletedUser, PDO::PARAM_STR);
                	$l_oSth->bindParam(':p_username', $this->sUsername, PDO::PARAM_STR);
                                 
	                $l_bState = $l_oSth->execute(); 
	                				
					if($l_bState){
						
						//
						$l_sSQLstring = 'DELETE FROM account WHERE (pkusername = :p_username)';
						
		                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 			

		                $l_oSth->bindParam(':p_username', $this->sUsername, PDO::PARAM_STR);                 
		                $l_bState = $l_oSth->execute();      
		                
						if($l_bState){
							
							$l_sSQLstring = 'DELETE FROM users WHERE (pkusername = :p_username)';
						
			                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 			

			                $l_oSth->bindParam(':p_username', $this->sUsername, PDO::PARAM_STR);                 
			                $l_bState = $l_oSth->execute(); 													
							
							if($l_bState){
							
								$l_sSQLstring = 'DELETE FROM credits WHERE (pkusername = :p_username)';
							
				                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 			

				                $l_oSth->bindParam(':p_username', $this->sUsername, PDO::PARAM_STR);                 
				                //$l_bState = $l_oSth->execute(); 
				                $l_oSth->execute();					
							}				
						}										
					}										                                   
				}	
				
				
				if($l_bState){
					$this->oLink->commit();					
				}
				else{
					//Rollback
					$this->oLink->rollBack();
				}	
				
                $this->disconnecttodb();

                return $l_bState;                				
		    
		    } catch (Exception $e) {
		    	
		    	$this->disconnecttodb();		
				return FALSE;
			}			
		}	
				
	}
		
	function is_ajax(){
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
		strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
			
	//Check if ajax request was sent	
	if(is_ajax()){			
		
		//Start session
		session_start();
		
		if(isset($_SESSION["email"])){
			
			$error_message = "";
			$l_oUser = new clUsers();

			$l_oUser->set_email($_SESSION["email"]);
			
			//Get the data
			if(isset($_POST['update_pw'])){	
				
				$l_oData = $_POST['update_pw'];
				$l_oNewuser = json_decode($l_oData);			
				$error_message = "";
				
				//Validate the data
				$error_message = $l_oUser->setGenPassword($l_oNewuser->password_1, $l_oNewuser->password_2);
				
				$l_oJSON['check'] = ($error_message == "") ? TRUE : FALSE;				
													
				//Send feedback to user
				if($l_oJSON['check']){
																					
					$l_oJSON['check'] = $l_oUser->update_password(TRUE);			
									
					if($l_oJSON['check']){
														
						$error_message = "Password successfully changed";
						
						session_destroy();
						session_start();
						$_SESSION['password_reset'] = TRUE;
						$l_oJSON['reset_success'] = TRUE;
						
					}
					else{
						$error_message = "Failed to change password. Try again";
					}					
				}
				
				$l_oJSON['message'] = $error_message;				
				echo json_encode($l_oJSON);
			}
			else{
				$l_oJSON['check'] = FALSE;
				$l_oJSON['message'] = "Failed to change password. Try again";				
				echo json_encode($l_oJSON);
			}
			
		}
		else{			
		
		//Check if user has logged on
			if(isset($_SESSION["username"])){
									
				$error_message = "";
				$l_oUser = new clUsers();

				$l_oUser->set_username($_SESSION["username"]);
				
				
				if(isset($_SESSION["username"])){
					$l_oJSON['account_reference'] = $_SESSION["account_reference"];
				}
				else
				{
					$l_oJSON['account_reference'] = "";
				}
				
				//Get the data
				if(isset($_POST['update_pw'])){	
					
					$l_oData = $_POST['update_pw'];
					$l_oNewuser = json_decode($l_oData);			
					$error_message = "";
					
					//Validate the data
					$error_message = $l_oUser->setGenPassword($l_oNewuser->password_1, $l_oNewuser->password_2);
					
					$l_oJSON['check'] = ($error_message == "") ? TRUE : FALSE;				
														
					//Send feedback to user
					if($l_oJSON['check']){
						
						$l_oUser->set_username($_SESSION["username"]);
												
						$l_oJSON['check'] = $l_oUser->update_password();			
										
						if($l_oJSON['check']){
															
							$error_message = "Password successfully changed";
						}
						else{
							$error_message = "Failed to change password. Try again";
						}					
					}
					
					$l_oJSON['message'] = $error_message;				
					echo json_encode($l_oJSON);
					
				}
				elseif(isset($_POST['update_delbook'])){	
					
					$l_oData = $_POST['update_delbook'];
					$l_oNewuser = json_decode($l_oData);			
					$error_message = "";
					
					//Validate the data
					$error_message = $l_oUser->set_bookeddate($l_oNewuser->bookeddate);
					
					$l_oJSON['check'] = ($error_message == "") ? TRUE : FALSE;				
														
					//Send feedback to user
					if($l_oJSON['check']){
						
						$error_message = $l_oUser->set_bookedtime($l_oNewuser->bookedtime);
					
						$l_oJSON['check'] = ($error_message == "") ? TRUE : FALSE;			
						
						if($l_oJSON['check']){						
											
							$l_oUser->set_username($_SESSION["username"]);
													
							$l_oJSON['check'] = $l_oUser->cancel_booking();			
											
							if($l_oJSON['check']){
																
								$error_message = "Booking successfully cancelled";
								
								//Get booked dates
								$l_oJSON['booked_dates'] = $l_oUser->get_booked_dates();
							}
							else{
								$error_message = "Failed to cancel booking. Try again";
							}
						}					
					}
					
					$l_oJSON['message'] = $error_message;				
					echo json_encode($l_oJSON);
									
				}
				elseif(isset($_POST['update_delacc'])){	
					
					$l_oData = $_POST['update_delacc'];
					$l_oNewuser = json_decode($l_oData);			
					$error_message = "";
												
					if(isset($l_oNewuser->del_monies) && isset($l_oNewuser->del_consent)){	
											
						$l_oJSON['check'] = $l_oUser->delete_account();
															
						if($l_oJSON['check']){
															
							$error_message = "Account successfully deleted";
						}
						else{
							$error_message = "Failed to delete account. Try again";
						}
											
						//Send feedback to user
						$l_oJSON['message'] = $error_message;				
						echo json_encode($l_oJSON);
					}
					else{

						$l_oJSON['check'] = FALSE;
						$l_oJSON['message'] = 'You need to check all Checkboxes to proceed';
										
						echo json_encode($l_oJSON);
					}
				}
				elseif(isset($_POST['init'])){	
					
					//Get booked dates
					$l_oJSON['booked_dates'] = $l_oUser->get_booked_dates();
																
					echo json_encode($l_oJSON);								
				}
				else{
					
					$l_oJSON['check'] = FALSE;
					$l_oJSON['message'] = 'Connection, error. Try again';				
					echo json_encode($l_oJSON);	
				}
			}		
			else{
				
				if(isset($_SESSION['password_reset'])){
					$l_oJSON['message'] = 'Password link has expired';
				}
				else{
					$l_oJSON['message'] = 'You need to login';
				}
				
				$l_oJSON['check'] = FALSE;	
								
				echo json_encode($l_oJSON);	
				
			}
		}
	}	
?>