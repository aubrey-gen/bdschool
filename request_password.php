<?php 
	session_start();
			
	if(isset($_SESSION["username"])){
		header('Location: index.php');
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
		  	<div class="col-md-4 col-md-offset-2" >
			
				<ul class="nav nav-pills">	
					<li role="presentation" ><a href="index.php">Home</a></li>
					<li role="presentation"><a href="products.php">Products</a></li>
					<li role="presentation"><a href="bookings.php">Make a booking</a></li>					
					<li role="presentation"><a href="aboutus.php">About Us</a></li>
				</ul>			
			</div>			
			
		</div>
		
		<div class="row">
			<div class="col-md-8 col-lg-offset-3" style="height: 70px;">					 

				<div class="alert alert-success alert-dismissible">
					<a id="alert" href="#" class="close" data-dismis="alert">&times;</a>
					<span></span>
				</div>
				
				<div class="alert alert-danger alert-dismissible">
					<a id="alert" href="#" class="close" data-dismis="alert">&times;</a>
					<span></span> 
				</div>						
					
			</div>
		</div>
		
		<div class="row" >						
			
			<div class="col-md-4 col-md-offset-2">
				<div id="new_account">
				<h4>Password recorvery</h4>
				<form class="form-horizonta form-group-sm" id="request_password" method="post" onsubmit="return false;" >
				  
				  <div class="form-group">
					<p>Enter your email below were the password will be sent</p>					
				  </div>
				  				  
				  <div class="form-group">
					<label for="reg_useremail">Email</label>
					<input type="email" class="form-control" name="rec_useremail" id="rec_useremail" placeholder="Email address" required="required">
				  </div>				  			  
				  				  
				  <div class="form-group">				  	
				  	<button type="submit" class="btn btn-default btn-success" id="recover_password">Request password</button>
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
	<script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="libs/js/eonasdan/moment-with-locales.js"></script>
	<script type="text/javascript" src="libs/js/eonasdan/bootstrap-datetimepicker.js" charset="UTF-8"></script>
	<script type="text/javascript">	
		//'use strict';
		
		function user_update_fail(jqXHR, ip_status, ip_error){

			$(".alert-danger span").text("Failed to update password");
			$(".alert-danger").show();
			$(".alert-danger").fadeOut(5000);
		}
			
		function user_update_success(data, scode, jqXHR){
			
			if(data['check'] === true){

				$(".alert-success").show();
				$(".alert-success span").text(data['message']);
				$(".alert-success").fadeOut(3000);
			}
			else{
				$(".alert-danger span").text(data['message']);											
				$(".alert-danger").show();	
				$(".alert-danger").fadeOut(3000);		
			}
		}
		
		function user_update_complete(data){	
		}
		
		function user_update(p_sname, p_odata, p_funct_success, p_funct_fail, p_funct_complete){
				//'use strict';
				var l_oJSONformdata,
					l_otemp = { };				
				
				l_oJSONformdata = JSON.stringify(p_odata);
				
				l_otemp[p_sname] = l_oJSONformdata;
				
				$.ajax({
					method: "POST",
					url: "/includes/php/password_recovery.php",
					data: l_otemp,
					cache: false,					
					dataType: 'json',									
					success: p_funct_success,										
					error: p_funct_fail, 
					complete: p_funct_complete
				});						
		}
				
		function recover_password(){
				//'use strict';
				
				var oFormdata = {
					'useremail': $("#rec_useremail").val()					
				};
				
				user_update("recover_pw", oFormdata, user_update_success, user_update_fail, user_update_complete);											
		}
			
		$(document).ready(function(){ 
             
            $("#recover_password").on("click", function(){
            	recover_password();
			});					
            			
		});
			
		</script>
  </body>
</html>