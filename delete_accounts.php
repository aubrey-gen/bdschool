<?php
	//Start session
	session_start();
	
	if(isset($_SESSION["username"])){	
		session_destroy();	
	}
	else{
		header("Location: index.php");
	}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Delete Account Confirmation</title>

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
	
		<div class="row">
		  <div class="col-md-8 col-md-offset-2 panel panel-default"><h1>BDS</h1></div>
		</div>
				
		<div class="row" >
		
			<div class="col-md-4 col-md-offset-2">
			
				<h4>Account deleted</h4>
				<p>
					We hope that you found this service helpful. You are welcome anytime to create an account again.
				</p>
				<p>
					Thank you for using our service.
				</p>
								
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
   
	<script type="text/javascript">	
		//'use strict';
			
	</script>
  </body>
</html>