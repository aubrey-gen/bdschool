<?php
	/*
	*********************************************************************************
	* Title: purchase.php
	* Descriptions: The function/method used to buy products available
	*********************************************************************************
	*/	

	include_once("db_con.php");
	include_once("class_gen_util.php");	
	include_once("class_purchase.php");	
	
	if(gen_util::is_ajax()){			
		
		//Start session
		session_start();

		$l_oJSON['check'] = FALSE;

		if( isset($_SESSION["username"] )){
		}
		else{			
			$l_oJSON['message'] = 'You need to sign in to make a purchase';				
			echo json_encode($l_oJSON);
			exit();
		}	
		
		if( isset($_POST['cart']) ){
		}else{
			$l_oJSON['message'] = 'Connection, error. Try again';				
			echo json_encode($l_oJSON);	
			exit();
		}

		$l_cart = NULL;				
		$l_cart = json_decode($_POST['cart']);
		
		date_default_timezone_set("Africa/Harare");
		
		$l_purchase = new purchase($dbmysql);		
		//Get item ID
		foreach($l_cart as $key=>$value){	
			
			switch ($key){		
				case 'itemid':
					$l_purchase->set_itemid($value);
					break;	
			}			
		}

		//Update user name
		$l_purchase->set_userid($_SESSION["username"]);

		//Check user balance
		if($l_purchase->check_balance()){			
		}
		else{
			$l_oJSON['message'] = 'Balance not enough';				
			echo json_encode($l_oJSON);
			exit();
		}

		//Update balance and credits		
		if($l_purchase->update_balances()){
			$l_oJSON['check'] = TRUE;
			$l_oJSON['message'] = 'Purchase successful';					
		}
		else{
			$l_oJSON['message'] = 'Could not make a purchase, Try again';				
		}
		
		echo json_encode($l_oJSON);
		exit();							
	}		
?>