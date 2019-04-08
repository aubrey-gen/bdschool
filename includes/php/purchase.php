<?php
	/*
	*********************************************************************************
	* Title: make_bookings.php
	* Descriptions: The function/method used to buy products available
	* 				
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
		private $sItemid = '', 
			 	//$sTime = '',
			 	//$sLicense_code = '',
			 	$iBalance = 0.00,
			 	$iItem_price = 0.00,
			 	$iQty = 0,
			 	$iProductid = 0,			 	
			 	$sUserid = '',
			 	$oDatetime = '',			   
			    $oLink = '';
		private $C_SHOST = "localhost";			 	
		private $C_SDBNAME = "tshebofs_bds_db";	
			    
		function __construct() {				
		}
		
		public function set_itemid($p_sItemid){
			$this->sItemid = $p_sItemid;
		}
		public function set_userid($p_sUserid){
			$this->sUserid = $p_sUserid;
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
		public function check_balance(){
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
                $l_sSQLstring = 'SELECT account.pkusername, account.balance, catalogue.price as item_price, 
                catalogue.quantity as item_qty, catalogue.fkproductid as productid  
                FROM account, catalogue WHERE account.pkusername = :p_userid AND catalogue.pkitemid = :p_itemid';
						
                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
	
                $l_oSth->bindParam(':p_userid', $this->sUserid, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_itemid', $this->sItemid, PDO::PARAM_INT);
                                                          
                $l_oSth->execute();
                
                $l_oProfile_data = $l_oSth->fetchAll(PDO::FETCH_NAMED);                          	     
                 
                foreach($l_oProfile_data as $row){   
                
                	if( $row['balance'] < $row['item_price']){
						$this->disconnecttodb();
						return FALSE;
					} 
					
					$this->iBalance = $row['balance'];
					$this->iItem_price =   $row['item_price'];
					$this->iQty =  $row['item_qty'];  
					$this->iProductid =  $row['productid'];  	                			
				}		                                   

                $this->disconnecttodb();

                return TRUE;                				
		    
		    } catch (Exception $e) {
		    	
		    	$this->disconnecttodb();
				return FALSE;
			}			
		}
		public function update_balances(){
			/**
			* 
			* @var 
			* 
			*/				
				$l_oSth = NULL;
				$l_bState = FALSE;
				$l_sUsername = 'tshebofs_bds_general';
				$l_sPassword = '7VELF8C8MjMpwnsv';
				$l_sNew_balance;
				$l_sNew_credits;
				$l_iCurrent_credits;
									
			try{
				$this->connecttodb($l_sUsername, $l_sPassword);		
				//                       
                $this->oLink->beginTransaction();
					
                $l_sSQLstring = 'UPDATE account SET balance = :p_new_balance, last_updated = NOW()
                               WHERE pkusername = :p_userid';
						
                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
	               
                $l_sNew_balance = strval($this->iBalance - $this->iItem_price);
                
                $l_oSth->bindParam(':p_new_balance', $l_sNew_balance, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_userid', $this->sUserid, PDO::PARAM_STR);                 
                $l_bState = $l_oSth->execute();                                                    
                				
				if($l_bState){
					
					//Update credits					 
	                $l_sSQLstring = 'SELECT credits FROM credits 
	                WHERE fklicense_codeid = :p_productid AND pkusername = :p_userid';
							
	                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
					
	                $l_oSth->bindParam(':p_productid', $this->iProductid, PDO::PARAM_INT);
	                $l_oSth->bindParam(':p_userid', $this->sUserid, PDO::PARAM_STR);
	                                                          
	                $l_oSth->execute();
	                
	                $l_oProfile_data = $l_oSth->fetchAll(PDO::FETCH_NAMED);                        	     
             
	                if($l_oProfile_data){					
					               	                
		                foreach($l_oProfile_data as $row){   
		                	
		                	$l_iCurrent_credits = $row['credits'];
		                	$l_sNew_credits = strval($l_iCurrent_credits + $this->iQty);
		                	
		                	$l_sSQLstring = 'UPDATE credits SET credits = :p_new_credits, last_updated = NOW()
	                               WHERE fklicense_codeid = :p_productid AND pkusername = :p_userid ';
							
			                $l_oSth = $this->oLink->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 			
						                
			                $l_oSth->bindParam(':p_new_credits', $l_sNew_credits, PDO::PARAM_STR);
			                $l_oSth->bindParam(':p_productid', $this->iProductid, PDO::PARAM_INT);
			                $l_oSth->bindParam(':p_userid', $this->sUserid, PDO::PARAM_STR);                 
			                $l_bState = $l_oSth->execute();                                         
			                	                				
						}
					}
					else{						
									
						$l_oSth = $this->oLink->prepare('INSERT INTO credits ( pkusername, fklicense_codeid, credits) VALUES ( :p_userid, :p_license_codeid, :p_credits ) ');
		                
		                $l_oSth->bindParam(':p_userid', $this->sUserid, PDO::PARAM_STR);
		                $l_oSth->bindParam(':p_license_codeid', $this->iProductid, PDO::PARAM_INT);
		                $l_oSth->bindParam(':p_credits', $this->iQty, PDO::PARAM_INT);
		                $l_bState = $l_oSth->execute();                                           
		                	
					}
					
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
			
			if( isset($_POST['cart']) ){	
					
				$l_oAppointment = NULL;		
				$l_oData = $_POST['cart'];
				$l_oAppointment = json_decode($l_oData);
				
				date_default_timezone_set("Africa/Harare");
				
				$l_oBooking = new clBooking();
				
		 		foreach($l_oAppointment as $key=>$value){			
					
					switch ($key){		
						case 'itemid':
							$l_oBooking->set_itemid($value);
							break;	
					}			
				}				
				
				$l_oBooking->set_userid($_SESSION["username"]);	
				//
				
				if($l_oBooking->check_balance()){	
				
					//Update balance and credits							
				
					switch($l_oBooking->update_balances()){
						case TRUE:
							$l_oJSON['check'] = TRUE;
							$l_oJSON['message'] = 'Purchase successful';					
							echo json_encode($l_oJSON);	
							break;
						
						default:
							$l_oJSON['check'] = FALSE;
							$l_oJSON['message'] = 'Could not make a purchase, Try again';				
							echo json_encode($l_oJSON);
							break;
					}	
				}
				else{
					$l_oJSON['check'] = FALSE;
					$l_oJSON['message'] = 'Balance not enough';				
					echo json_encode($l_oJSON);
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
			$l_oJSON['message'] = 'You need to sign in to make a purchase';				
			echo json_encode($l_oJSON);
		}						
	}	
	
?>