<?php
	/*
	*********************************************************************************
	* Title: make_bookings.php
	* Descriptions: The function/method checks the booking dates and hours submitted,
	* 				availability and saves the bookings
	* @author Aubrey Mantji <breowas@gmail.com>
	* @copyright Copyright (c) 2017, AUBDYNAMICS
	*********************************************************************************
	*/			
	
	 //Enter your code here, enjoy!
	 class clGenDay implements JsonSerializable {
		public  $daydate,
	        	$times;

	    public function __construct() {	        
		}    

	    public function jsonSerialize() {
	        return [
	            'date' => $this->daydate,           
	            'times' =>$this->times
	        ];        
	    }
	} 

	
	class clBooking {		
		private $sDay = '', 
			 	$sTime = '',
			 	$sLicense_code = '',			 	
			 	$sUserid = '',
			 	$oDatetime = '',			   
			    $oLink = '';
		private $C_SHOST = "localhost";//,			 	
		private $C_SDBNAME = "tshebofs_bds_db";	
			    
		function __construct() {				
		}
		
		public function set_day($p_sDay){
			$this->sDay = $p_sDay;
		}
		
		public function set_time($p_sTime){
			$this->sTime = $p_sTime;
		}
		
		public function set_license_code($p_sLicense_code){
			$this->sLicense_code = $p_sLicense_code;
		}
		
		public function set_userid($p_sUserid){
			$this->sUserid = $p_sUserid;
		}	
		
		public function get_datetime(){			
			try{			

 				if( $this->sDay == NULL OR $this->sDay == ""){
					return 0;
				}
				if( $this->sTime == NULL OR $this->sTime == ""){
					return 0;
				}
 				$l_sCombo_datetime = $this->sDay.' '.$this->sTime;
 				$l_oDatetime = new DateTime();
 				$l_oNewdate = $l_oDatetime->createFromFormat('d/m/Y G:i', $l_sCombo_datetime);
				$l_sDatetime = $l_oNewdate->format('d/m/Y G:i');
				$this->oDatetime = $l_oNewdate->format('YYYY/MM/DD G:i');			
				
				switch(is_valid_date($l_sDatetime)){
					case TRUE :
						$this->sDay = $l_oNewdate->format('Y/m/d');						
						return $l_sDatetime;
						break;				
					default:
						return 0;
						break;
				}
			} catch (Exception $e) {
				return 0;
			}	
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
		public function set_make_booking(& $p_message){
			/**
			* 
			* @var 
			* 
			*/				
				$l_oSth = NULL;
				$l_bState = FALSE;
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';
				$l_iCurrent_credits;
				$l_idaily_booking_limit;
	
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);	
				
				$this->oLink->beginTransaction();
						
				// 
                $l_sSQLstring = 'SELECT credits FROM credits WHERE pkusername = :p_userid AND fklicense_codeid = :p_licensecodeid';
						
                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
	
                $l_oSth->bindParam(':p_userid', $this->sUserid, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_licensecodeid', $this->sLicense_code, PDO::PARAM_INT);
                                                          
                $l_oSth->execute();
                
                $l_oProfile_data = $l_oSth->fetchAll(PDO::FETCH_NAMED);                          	     
                
                if($l_oProfile_data){
					
					$l_bState = TRUE;
					
					foreach($l_oProfile_data as $row){  
					 
                		$l_iCurrent_credits = $row['credits'];
                		
	                	if( $l_iCurrent_credits < 1 ){
	                		
	                		$l_bState = FALSE;	                		
	                		$p_message = 'Buy lessons to book';
							
						}                			
					}
					
					if($l_bState){				
						
						$l_sSQLstring = 'SELECT COUNT(pkdate) as num_of_bookings FROM bookings 
						WHERE pkuserid = :p_userid AND pkdate = :p_date';
							
		                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
			
		                $l_oSth->bindParam(':p_userid', $this->sUserid, PDO::PARAM_STR);
		                $l_oSth->bindParam(':p_date', $this->sDay, PDO::PARAM_STR);
		               		               
		                $l_oSth->execute();
		                
		                $l_oProfile_data = $l_oSth->fetchAll(PDO::FETCH_NAMED); 
		                
		                if($l_oProfile_data){
							
							$l_bState = TRUE;
							
							foreach($l_oProfile_data as $row){  					 
		                				                		
		                		///Paramatize value below !!!!!!!!!!
		                		$l_idaily_booking_limit = 2;
		                		
			                	if( $row['num_of_bookings'] >= $l_idaily_booking_limit ){
									
									$l_bState = FALSE;									
								}		                			
							}
							
							if($l_bState){
								
								//Update credits
								$l_sNew_credits = strval($l_iCurrent_credits - 1);
			                	
			                	$l_sSQLstring = 'UPDATE credits SET credits = :p_new_credits, last_updated = NOW()
		                               WHERE fklicense_codeid = :p_licensecodeid AND pkusername = :p_userid';
								
				                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 			
							                
				                $l_oSth->bindParam(':p_new_credits', $l_sNew_credits, PDO::PARAM_STR);
				                $l_oSth->bindParam(':p_licensecodeid', $this->sLicense_code, PDO::PARAM_INT);
				                $l_oSth->bindParam(':p_userid', $this->sUserid, PDO::PARAM_STR);                 
				                $l_bState = $l_oSth->execute();
				                
				                if($l_bState){
				                	
				                	$l_oSth = $this->oLink->prepare('INSERT INTO bookings ( pkdate, pktime, pkuserid, fklicense_codeid) VALUES ( :p_date, :p_time, :p_userid, :p_license_codeid ) ');
					                $l_oSth->bindParam(':p_date', $this->sDay, PDO::PARAM_STR);
					                $l_oSth->bindParam(':p_time', $this->sTime, PDO::PARAM_STR);
					                $l_oSth->bindParam(':p_userid', $this->sUserid, PDO::PARAM_STR);
					                $l_oSth->bindParam(':p_license_codeid', $this->sLicense_code, PDO::PARAM_INT);
					                $l_bState = $l_oSth->execute(); 
					                
					                if($l_bState){
										$l_bState = TRUE;
									}
									else{
										$p_message = 'Could not make booking, Try again';
										$l_bState = FALSE;
									}
					                
								}
								else{									
									$p_message = 'Could not make booking, Try again';
									$l_bState = FALSE;
								}	
							}
							else{
								$l_bState = FALSE;								
								$p_message = 'Daily bookings limit reached';
							}
						}            
		               	else{
							$l_bState = FALSE;							
							$p_message = 'Error in selection, try again';
						}
					}
				}
                else{
					$l_bState = FALSE;
					$p_message = 'Buy lesson to book';
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
		
	function is_valid_date($date, $format='d/m/Y G:i'){
		try{
			$f = DateTime::createFromFormat($format, $date);
			$valid = DateTime::getLastErrors();
			return ($valid['warning_count']==0 and $valid['error_count']==0 and $f !== false);
		} catch (Exception $e) {
			return FALSE;
		}		
	}

	function is_ajax(){
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
		strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	if(is_ajax()){			
		
		//Start session
		session_start();
		
		//Check if user has logged on
		if(isset($_SESSION["username"])){	
			
			if( isset($_POST['appointment']) ){	
					
				$l_oAppointment = NULL;		
				$l_oData = $_POST['appointment'];
				$l_oAppointment = json_decode($l_oData);
				
				date_default_timezone_set("Africa/Harare");
				
				$l_oBooking = new clBooking();
				
		 		foreach($l_oAppointment as $key=>$value){			
					
					switch ($key){		
						case 'day':
							$l_oBooking->set_day($value);
							break;						
						case 'time':
							$l_oBooking->set_time($value);	
							break;
						case 'license_code':
							$l_oBooking->set_license_code($value);	
							break;	
					}			
				}				
				
				$l_oBooking->set_userid($_SESSION["username"]);	
				
				switch($l_oBooking->get_datetime()){
					case 0 :
							$l_oJSON['check'] = FALSE;
							$l_oJSON['message'] = 'Appointment not available, try another one';			
							echo json_encode($l_oJSON);
							break;
					default:
					
							switch($l_oBooking->set_make_booking($l_oJSON['message'])){
								case TRUE:
									$l_oJSON['check'] = TRUE;
									$l_oJSON['message'] = 'Booking successful';					
									echo json_encode($l_oJSON);	
									break;
								
								default:
									$l_oJSON['check'] = FALSE;									
									echo json_encode($l_oJSON);
									break;
							}
							break;
				}				
				
			}
			else{

				$l_oJSON['check'] = FALSE;
				$l_oJSON['message'] = 'Connection, error. Try again';				
				echo json_encode($l_oJSON);		
			}		
				
		}
		else{	
					
			$l_oJSON['check'] = FALSE;
			$l_oJSON['message'] = 'You need to sign in to make a booking';				
			echo json_encode($l_oJSON);
		}						
	}	
	
?>