<?php
	//Start session
	session_start();
		
	if(isset($_SESSION["password_reset"])){
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
	
		<div class="row">
			<div class="col-md-8 col-lg-offset-3" style="height: 70px;">					 

				<div class="alert alert-success alert-dismissible">
					<a id="alert555" href="#" class="close" data-dismis="alert">&times;</a>
					<span></span>
				</div>
				
				<div class="alert alert-danger alert-dismissible">
					<a id="alert555" href="#" class="close" data-dismis="alert">&times;</a>
					<span></span> 
				</div>						
					
			</div>
		</div>
		
		<div class="row" >
				
			<div class="col-md-3">
				<div id="update_account">
				<h4>Reset Password</h4>
				
				<p>
					Your password has been successfully reset.
				</p>
				
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
  </body>
</html>