<?php
	/*
	*********************************************************************************
	* Title: logut.php
	* Descriptions: The function/method Logs out the user
	* 				
	* @author Aubrey Mantji <breowas@gmail.com>
	* @copyright Copyright (c) 2017, AUBDYNAMICS
	*********************************************************************************
	*/		
	
	function is_ajax(){
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
		strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	if(is_ajax()){			
		
		//Start session
		session_start();
				
		$l_blogged_out = session_destroy();
		
		$l_oJSON['check'] = $l_blogged_out;	
		
		if($l_blogged_out == TRUE){
			$l_oJSON['message'] = "You have been succesfully signed out";	
		}
		else{
			$l_oJSON['message'] = "Sign out Failed";	
		}
			
		echo json_encode($l_oJSON);							
	}		
?>