<?php
	/*
	*********************************************************************************
	* Title: class_purchase.php
	* Descriptions: The function/method used to buy products available
	*********************************************************************************
	*/				
	
	class purchase {		
		private $sItemid = '', 
			 	$iBalance = 0.00,
			 	$iItem_price = 0.00,
			 	$iQty = 0,
				$iProductid = 0,
				$oDatetime = '',				 	
			 	$sUserid = '';
		private $dbcon;

		function __construct($p_dbcon) {				
			$this->dbcon = $p_dbcon;
		}
		
		public function set_itemid($p_sItemid){
			$this->sItemid = $p_sItemid;
		}
		public function set_userid($p_sUserid){
			$this->sUserid = $p_sUserid;
		}		
		public function check_balance(){
			/**
			* 
			* @var
			* 
			*/					
			try{

				$l_oSth = NULL;
				$this->dbcon->connect();									
				// 
                $l_sSQLstring = 'SELECT account.pkusername, account.balance, catalogue.price as item_price, 
                catalogue.quantity as item_qty, catalogue.fkproductid as productid  
                FROM account, catalogue WHERE account.pkusername = :p_userid AND catalogue.pkitemid = :p_itemid';
						
                $l_oSth = $this->dbcon->getDBlink()->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
	
                $l_oSth->bindParam(':p_userid', $this->sUserid, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_itemid', $this->sItemid, PDO::PARAM_INT);
                                                          
                $l_oSth->execute();
                
                $l_oProfile_data = $l_oSth->fetchAll(PDO::FETCH_NAMED);                          	     
                 
                foreach($l_oProfile_data as $row){   
                
                	if( $row['balance'] < $row['item_price']){
						$this->dbcon->disconnect();
						return FALSE;
					} 
					
					$this->iBalance = $row['balance'];
					$this->iItem_price =   $row['item_price'];
					$this->iQty =  $row['item_qty'];  
					$this->iProductid =  $row['productid'];  	                			
				}		                                   

                $this->dbcon->disconnect();

                return TRUE;                				
		    
		    } catch (Exception $e) {
		    	
		    	$this->dbcon->disconnect();
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
				$l_sNew_balance;
				$l_sNew_credits;
				$l_iCurrent_credits;
									
			try{
				$this->dbcon->connect();	
				//                       
                $this->dbcon->getDBlink()->beginTransaction();
					
                $l_sSQLstring = 'UPDATE account SET balance = :p_new_balance, last_updated = NOW()
                               WHERE pkusername = :p_userid';
						
                $l_oSth = $this->dbcon->getDBlink()->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
	               
                $l_sNew_balance = strval($this->iBalance - $this->iItem_price);
                
                $l_oSth->bindParam(':p_new_balance', $l_sNew_balance, PDO::PARAM_STR);
                $l_oSth->bindParam(':p_userid', $this->sUserid, PDO::PARAM_STR);                 
                $l_bState = $l_oSth->execute();                                                    
                				
				if($l_bState){
					
					//Update credits					 
	                $l_sSQLstring = 'SELECT credits FROM credits 
	                WHERE fklicense_codeid = :p_productid AND pkusername = :p_userid';
							
	                $l_oSth = $this->dbcon->getDBlink()->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
					
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
							
			                $l_oSth = $this->dbcon->getDBlink()->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 			
						                
			                $l_oSth->bindParam(':p_new_credits', $l_sNew_credits, PDO::PARAM_STR);
			                $l_oSth->bindParam(':p_productid', $this->iProductid, PDO::PARAM_INT);
			                $l_oSth->bindParam(':p_userid', $this->sUserid, PDO::PARAM_STR);                 
			                $l_bState = $l_oSth->execute();                                         
			                	                				
						}
					}
					else{						
									
						$l_oSth = $this->dbcon->getDBlink()->prepare('INSERT INTO credits ( pkusername, fklicense_codeid, credits) VALUES ( :p_userid, :p_license_codeid, :p_credits ) ');
		                
		                $l_oSth->bindParam(':p_userid', $this->sUserid, PDO::PARAM_STR);
		                $l_oSth->bindParam(':p_license_codeid', $this->iProductid, PDO::PARAM_INT);
		                $l_oSth->bindParam(':p_credits', $this->iQty, PDO::PARAM_INT);
		                $l_bState = $l_oSth->execute();                                 
		                	
					}
					
					if($l_bState){
						$this->dbcon->getDBlink()->commit();					
					}
					else{
						//Rollback
						$this->dbcon->getDBlink()->rollBack();					
					}					
				}
				else{
					$this->dbcon->getDBlink()->rollBack();	
				}	
					
                $this->dbcon->disconnect();

                return $l_bState;                           				
		    
		    } catch (Exception $e) {
		    	
		    	$this->dbcon->disconnect();
				return FALSE;
			}			
		}			
	}	
?>