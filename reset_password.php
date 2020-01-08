<?php
	//Start session
	session_start();
	
	if(isset($_SESSION["username"])){
		header('Location: index.php');
	}

	include_once("./includes/php/password_recovery.php");
	
	if(isset($_SESSION["recover_pw"])){
	}
	else{
		$_SESSION["recover_pw"] = $_GET['prtk'];
	}	
	
	$l_oUser = new clUsers();
		
	$_SESSION["email"] = $l_oUser->verify_token($_SESSION["recover_pw"]);
	
	if($_SESSION["email"]){				
	}
	else{
		//Start session
		session_start();				
		session_destroy();

		header("Location: reset_expired.php");
	}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bones Driving School</title>

    <!-- Bootstrap -->
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/normalise.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" >
	
	<!-- Optional theme -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css" >
	<link rel="stylesheet" href="css/bds-theme.css">
	<link href="css/eonasdan/bootstrap-datetimepicker.css" rel="stylesheet" media="screen">
	<!-- Latest compiled and minified JavaScript -->


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body id="webbody">    
	<div class="container-fluid" id="main">
	
		<div class="row" >
		  <div class="col-md-10 col-md-offset-2" >
										
			</div>				
			
		</div>
		
		<div class="row">
			<div class="col-md-8 col-lg-offset-3" style="height: 70px;">					 

				<div class="alert alert-success alert-dismissible">
					<a id="alertcl" href="#" class="close" data-dismis="alert">&times;</a>
					<span></span>
				</div>
				
				<div class="alert alert-danger alert-dismissible">
					<a id="alertcl" href="#" class="close" data-dismis="alert">&times;</a>
					<span></span> 
				</div>						
					
			</div>
		</div>
		
		<div class="row" >
				
			<div class="col-md-3">
				<div id="update_account">
				<h4>Reset Password</h4>
				<form class="form-horizonta form-group-sm" id="account_update_password" method="post" onsubmit="return false;" >				 			 
			   				  				  				  
				  <div class="form-inline">
					<label for="new_password">Password</label>
					<input type="Password" class="form-control" name="new_password" id="new_password" placeholder="Password" />
				  </div>
				  <div class="form-inline">
					<label for="new_password_confirm">Confirm Password</label>
					<input type="Password" class="form-control" name="new_password_confirm" id="new_password_confirm" placeholder="Confirm Password" />
				  </div>			  
				  
				  <div class="form-inline">
				  	<button type="submit" class="btn btn-success" id="update_password">Reset password</button>
				  </div>
				</form>
				</div>		
			</div>			

		</div>
						
		<div class="row">
		  <div class="col-md-8 col-md-offset-2"><small>Created by Aubrey &copy; 2019</small></div>		  
		</div>
	
	</div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="libs/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
	<script type="text/javascript" src="libs/js/bds_reset.js" charset="UTF-8"></script>
	<script type="text/javascript">	
			
		$(document).ready(function(){      
            
            var userform = $("form#account_update_password"),             	
                password1 = $("#new_password"),
                password2 = $("#new_password_confirm");             
            var user = generalUser();
            
            $("#update_password").on("click", function(){
            	user.resetPassword(userform, password1.val(), password2.val());
			});			
            			
		});

		</script>
  </body>
</html>