<?php
	/*
	*********************************************************************************
	* Title: get_bookings.php
	* Descriptions: The function/method checks catalogue
	*********************************************************************************
	*/
	
	include_once("db_con.php");
	include_once("class_gen_util.php");	
	include_once("class_product.php");	
	include_once("class_user_profile.php");

	//Check if ajax request was sent	
	if(gen_util::is_ajax()){					
	
		$l_product = new product($dbmysql);	
		$l_user_profile = new user_profile($dbmysql);		    	
		
		$l_oJSON['catalogue'] = $l_product->get_catalogue();
		
		session_start();
			
		if(isset($_SESSION["username"])){			
			$l_oJSON['profile_data'] = $l_user_profile->get_profile($_SESSION["username"]);
		}else{
			$l_oJSON['profile_data'] = FALSE;
		}
		
		echo json_encode($l_oJSON);	
	}	
?>