<?php
	/*
	*********************************************************************************
	* Title: class_product.php
	* Descriptions: Class for product
	*********************************************************************************
	*/
        
    class product {		
		private $dbcon;
		 			    
		function __construct($p_dbcon) {				
			$this->dbcon = $p_dbcon;
		}
	
		public function get_catalogue(){
			/**
			* 
			* @var 
			* 
			*/				
								
			try{
				$l_oSth = NULL;	
				$this->dbcon->connect();	
                             
                $l_sSQLstring = 'SELECT * FROM catalogue WHERE active = 1 ORDER BY pkitemid ASC';
				                
				$l_oSth = $this->dbcon->getDBLink()->prepare($l_sSQLstring, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) ); 
				                                        
                $l_oSth->execute();
                
                $l_oLicense_codes_data = $l_oSth->fetchAll(PDO::FETCH_NAMED);                          	     
                          
                $l_oLicense_codes = array();                
                
                foreach($l_oLicense_codes_data as $row){         	                

					$l_oLicense_codedata = array(						
						"itemid" => $row['pkitemid'],	
						"price" => $row['price'],						
						"description" => $row['description'],
						"quantity" => $row['quantity']						
						);
					
					array_push($l_oLicense_codes, $l_oLicense_codedata );					
				}		                                   
				
				$this->dbcon->disconnect();

                return $l_oLicense_codes;                				
		    
		    } catch (Exception $e) {
		    	$this->dbcon->disconnect();
				return FALSE;
			}			
		}							
	}
?>