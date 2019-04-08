<?php
	/*
	*********************************************************************************
	* Title: get_bookings.php
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
			 	$sUserid = '',
			 	$oDatetime = '',			   
			    $oLink = '';
		private $C_SHOST = "localhost";			 	
		private $C_SDBNAME = "tshebofs_bds_db";	
		 			    
		function __construct() {				
		}
		
		public function set_day($p_sDay){
			$this->sDay = $p_sDay;
		}
		
		public function set_time($p_sTime){
			$this->sTime = $p_sTime;
		}
		
		public function set_userid($p_sUserid){
			$this->sUserid = $p_sUserid;
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
		
		public function get_booked_dates($p_date){
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
				
				//Get month and year   
                $l_sSQLstring = 'SELECT pkdate, pktime, pkuserid FROM bookings WHERE MONTH(pkdate) = MONTH(:p_month) AND YEAR(pkdate) = YEAR(:p_year)';
                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
	
				$l_sDatetime = $p_date->format('Y-m-d');
				                            
                $l_oSth->bindParam(':p_month', $l_sDatetime, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_year', $l_sDatetime, PDO::PARAM_STR, 11);
                                                          
                $l_oSth->execute();
                
                $l_oBookedDates = $l_oSth->fetchAll(PDO::FETCH_NAMED);                          	     
                          
                $l_oDates = array();                
                
                foreach($l_oBookedDates as $row){
                	                
                	$l_sDate = $row['pkdate'];   
					
					$l_oDate = array(
						"day" => $row['pkdate'],
						"time" => $row['pktime'],
						"userid" => ""
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
		
		public function get_user_profile($p_user){
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
				
			if(!isset($p_user)){
				return NULL;
			}	
									
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);		
				//   
                $l_sSQLstring = 'SELECT credits.pkusername, license_codes.display_name, credits.credits, account.balance,
						  (
						  SELECT DISTINCT COUNT(pkuserid) AS bookings FROM bookings
						  WHERE pkuserid = credits.pkusername AND bookings.fklicense_codeid = credits.fklicense_codeid
						  GROUP BY pkuserid
						) AS bookings
						FROM credits 
						LEFT JOIN account ON credits.pkusername = account.pkusername 
						LEFT JOIN license_codes ON credits.fklicense_codeid = license_codes.pklicense_codeid 
						WHERE credits.pkusername = :p_userid';
						
                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
	
                $l_oSth->bindParam(':p_userid', $p_user, PDO::PARAM_STR);
                                                          
                $l_oSth->execute();
                
                $l_oProfile_data = $l_oSth->fetchAll(PDO::FETCH_NAMED);                          	     
                          
                $l_oProfiles = array();                
                
                foreach($l_oProfile_data as $row){                	                

					$l_oUserData = array(
						"username" => strtoupper($row['pkusername']),
						"display_name" => $row['display_name'],
						"credits" => $row['credits'],
						"balance" => $row['balance']
						);
					
					array_push($l_oProfiles, $l_oUserData );					
				}		                                   
				
				if(count($l_oProfiles) < 1){
					
					$l_sSQLstring = 'SELECT pkusername, balance FROM account 
						WHERE pkusername = :p_userid limit 1';
						
	                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
		
	                $l_oSth->bindParam(':p_userid', $p_user, PDO::PARAM_STR);	                                                          
	                $l_oSth->execute();	                
	                $l_oProfile_data = $l_oSth->fetchAll(PDO::FETCH_NAMED);                          	     
	                          
	                $l_oProfiles = array(); 					
					
					foreach($l_oProfile_data as $row){                	                

						$l_oUserData = array(
							"username" => strtoupper($row['pkusername']),
							"display_name" => "",
							"credits" => 0,
							"balance" => $row['balance']
							);
						
						array_push($l_oProfiles, $l_oUserData );					
					}
				}
				
				if(count($l_oProfiles) < 1){
					
					$l_sSQLstring = 'SELECT pkusername FROM users 
						WHERE pkusername = :p_userid limit 1';
						
	                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
		
	                $l_oSth->bindParam(':p_userid', $p_user, PDO::PARAM_STR);	                                                          
	                $l_oSth->execute();	                
	                $l_oProfile_data = $l_oSth->fetchAll(PDO::FETCH_NAMED);                          	     
	                          
	                $l_oProfiles = array(); 					
					
					foreach($l_oProfile_data as $row){                	                

						$l_oUserData = array(
							"username" => strtoupper($row['pkusername']),
							"display_name" => "",
							"credits" => 0,
							"balance" => 0
							);
						
						array_push($l_oProfiles, $l_oUserData );					
					}
				}
				
                $this->disconnecttodb();

                return $l_oProfiles;                				
		    
		    } catch (Exception $e) {
		    	
		    	$this->disconnecttodb();
				return FALSE;
			}			
		}
		
		public function get_license_codes(){
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
                             
                $l_sSQLstring = 'SELECT pklicense_codeid, display_name FROM license_codes 
                WHERE active = 1 ORDER BY display_name ASC';
						
                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
                                                          
                $l_oSth->execute();
                
                $l_oLicense_codes_data = $l_oSth->fetchAll(PDO::FETCH_NAMED);                          	     
                          
                $l_oLicense_codes = array();                
                
                foreach($l_oLicense_codes_data as $row){         	                

					$l_oLicense_codedata = array(
						"license_code" => $row['pklicense_codeid'],
						"display_name" => $row['display_name']						
						);
					
					array_push($l_oLicense_codes, $l_oLicense_codedata );					
				}		                                   

                $this->disconnecttodb();

                return $l_oLicense_codes;                				
		    
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
	
		date_default_timezone_set("Africa/Harare");

		$l_oBooking = new clBooking();
		$l_sSelected_Month_date = json_decode($_POST['selected_month_date']);
				
		if(isset($l_sSelected_Month_date)){
			$todaysdate = DateTime::createFromFormat("d/m/Y", $l_sSelected_Month_date);
		}	
		else{
			$todaysdate = new DateTime();			
		}
	    	
		$l_oJSON['booked_dates'] = $l_oBooking->get_booked_dates($todaysdate);
		$l_oJSON['license_codes'] = $l_oBooking->get_license_codes();
		
		session_start();
			
		if(isset($_SESSION["username"])){
			$l_oJSON['profile_data'] = $l_oBooking->get_user_profile($_SESSION["username"]);
		}
		
		echo json_encode($l_oJSON);	
	}	
?>