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
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

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
		  <div class="col-md-10 col-md-offset-2">
			
				<ul class="nav nav-pills">	
					<li role="presentation" ><a href="index.php">Home</a></li>
					<li role="presentation"><a href="products.php">Products</a></li>
					<li role="presentation" class="active" ><a href="#">Register</a></li>					
					<li role="presentation"><a href="aboutus.php">About Us</a></li>
				</ul>
			
			</div>
			
			<div class="col-md-10 col-md-offset-2" style="height: 70px;">					 

				<div class="alert alert-success alert-dismissible">
					<a id="alert555" href="#" class="close" data-dismis="alert">&times;</a>
					<span></span>
				</div>
				
				<div class="alert alert-danger alert-dismissible">
					<a id="alert555" href="#" class="close" data-dismis="alert">&times;</a>
					<span></span> 
				</div>				
			</div>
						
			<div class="col-md-3 col-md-offset-3">
				<div id="new_account">
				<h4>NEW ACCOUNT DETAILS</h4>
				<form class="form-group form-group-sm" id="frm_newaccount" method="post" onsubmit="return false;" >
				  <div class="form-group">
					<label for="reg_username">Username</label>
					<input type="text" name="reg_username" class="form-control" id="reg_username" placeholder="username" required="required">
				  </div>
				  <div class="form-group">
					<label for="reg_gender">Gender</label>
					<select name="reg_gender" id="reg_gender" required="required">
						<option value="">Select....</option>
						<option value="F">Female</option>
						<option value="M">Male</option>
					</select>					
				  </div>				  				  
				  <div class="form-group">
					<label for="reg_useremail">Email</label>
					<input type="email" class="form-control" name="reg_useremail" id="reg_useremail" placeholder="Email address" required="required">
				  </div>				   
				  				  				  
				  <div class="form-group">
					<label for="reg_password">Password</label>
					<input type="Password" class="form-control" name="reg_password" id="reg_password" placeholder="Password" required="required"/>
				  </div>
				  <div class="form-group">
					<label for="reg_password_confirm">Confirm Password</label>
					<input type="Password" class="form-control" name="reg_password_confirm" id="reg_password_confirm" placeholder="Confirm Password" required="required"/>
				  </div>
				  
				  <div class="form-group">
					<input type="checkbox" id="reg_terms" name="reg_terms" value="true" required="required"/>
					<label for="reg_terms">I accept the Terms and Conditions</label> 
				  </div>
				  
					<div class="form-group">
						<div class="g-recaptcha" data-sitekey="6LccNZ0UAAAAAIfMHhfXAQI0vQ1LnSWU3A1otR8U"></div>
				  </div>

				  <div class="form-group">				  
				  	<button type="submit" class="btn btn-default btn-primary" id="register">Register</button>
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
	<script type="text/javascript" src="libs/js/bds_register.js" charset="UTF-8"></script>
	<script type="text/javascript">	
		
		$(document).ready(function(){ 
							
			var userForm = $("form#frm_newaccount"),
					username = $("#reg_username"),
					gender = $("#reg_gender"),
					email = $("#reg_useremail"),
					terms = $("input#reg_terms"),
					password1 = $("#reg_password"),
					password2 = $("#reg_password_confirm"),
					usercaptcharesp;			
				
			var client = generalUser();
			
			$("#register").on("click", function () {                        	       
					usercaptcharesp = $("#g-recaptcha-response").val();               		        
		      client.addClient(userForm, username.val(), gender.val(), email.val(), password1.val(), password2.val(), terms.val(), usercaptcharesp);
		    });		   
            			
		});
		
		</script>
  </body>
</html>