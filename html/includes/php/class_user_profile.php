<?php
	/*
	*********************************************************************************
	* Title: class_user_profile.php
	* Descriptions: Class for user profile
	*********************************************************************************
	*/
        
    class user_profile{		
		private $dbcon;
		 			    
		function __construct($p_dbcon) {				
			$this->dbcon = $p_dbcon;
		}
		
		public function get_profile($p_user){
			/**
			* 
			* @var 
			* 
			*/					
				
			if(!isset($p_user)){
				return NULL;
			}	
									
			try{
				
				$l_oSth = NULL;
				$this->dbcon->connect();	
				                
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
				                
				$l_oSth = $this->dbcon->getDBlink()->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 

                $l_oSth->bindParam(':p_userid', $p_user, PDO::PARAM_STR);
                                                          
                $l_oSth->execute();
                
                $l_oProfile_data = $l_oSth->fetchAll(PDO::FETCH_NAMED);                          	     
                          
                $l_oProfiles = array();                
                
                foreach($l_oProfile_data as $row){                	                

					$l_oUserData = array(
						"username" => $row['pkusername'],
						"display_name" => $row['display_name'],
						"credits" => $row['credits'],
						"balance" => $row['balance']
						);
					
					array_push($l_oProfiles, $l_oUserData );					
				}	
				
				if(count($l_oProfiles) < 1){
					
					$l_sSQLstring = 'SELECT pkusername, balance FROM account 
						WHERE pkusername = :p_userid limit 1';
						
	                $l_oSth = $this->dbcon->getDBlink()->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 		
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
						
	                $l_oSth = $this->dbcon->getDBlink()->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
		
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

                $this->dbcon->disconnect();
                return $l_oProfiles;                				
		    
		    } catch (Exception $e) {		    	
		    	$this->dbcon->disconnect();
				return FALSE;
			}			
		}								
	}
?>