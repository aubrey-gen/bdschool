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
            <div class="col-md-3 col-lg-offset-3" style="height: 70px;">

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

			<div class="col-md-3 col-lg-offset-3">
				<form class="form-group form-group-sm" id="frm_login" method="post" onsubmit="return false;">
				  <div class="form-group">
					<label for="useremail">Email</label>
					<input type="email" class="form-control" id="useremail" placeholder="Email address">
				  </div>
				  <div class="form-group">
					<label for="password">Password</label>
					<input type="Password" class="form-control" id="password" placeholder="Password">
				  </div>
				  <button type="submit" id="btn_userlogin" class="btn btn-primary btn-sm">Sign In</button>				  
				</form>
                <div style="padding:5px;">
                    <a href="registration.php">Don't have an account, create one</a><br/>
                    <a href="request_password.php">Forgot you password, reset it here</a>
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
	<script type="text/javascript">	
        
        function showErrorMessage(message) {
            $(".alert-danger span").text(message);
            $(".alert-danger").show();
            $(".alert-danger").fadeOut(6000);
        }

        function showSuccessMessage(message) {
            $(".alert-success").show();
            $(".alert-success span").text(message);
            $(".alert-success").fadeOut(6000);
        }

        function user_gen() {
            "use strict";	

            function login_success(data, scode, jqXHR) {
                
                if (data.check) {
                    $("#useremail").val("");
                    $("#password").val("");
                    window.location.href = data.url;         
                }
                else {
                    showErrorMessage(data.message);
                }
            }

            function login_fail(jqXHR, ip_status, ip_error) {                
                showErrorMessage("Failed to login");							
            }

            function login_complete(data) {
            }

            function login(paramName, submitData, func_success, func_fail, func_complete) {
                
                var jsonSubmitData;
                var tempSubmitData = {};

                jsonSubmitData = JSON.stringify(submitData);
                tempSubmitData[paramName] = jsonSubmitData;

                $.ajax({
                    method: "POST",
                    url: "/includes/php/user_utils.php",
                    data: tempSubmitData,
                    cache: false,
                    dataType: "json",
                    success: func_success,
                    error: func_fail,
                    complete: func_complete
                });
            }

            function check_login_failed() {
                showErrorMessage("Complete all fields to sign");
            }

            function check_logins(paramName, email, password) {            						

                var l_bchecked = paramName && email && password && true;

                if (l_bchecked) {

                    var loginInfo = {
                        "username": email || "",
                        "password": password || ""
                    };

                    login(paramName, loginInfo, login_success, login_fail, login_complete);
                }
                else {
                    check_login_failed();
                }
            }

            return {
                check_logins: function (paramName, email, password) {
                    check_logins(paramName, email, password);
                }
            };
        }

        $(document).ready(function () {

            var useremail = $("#useremail");
            var userpassword = $("#password")
            var paramName = "login_data";

            $("#btn_userlogin").on("click", function () {

                if ($("form#frm_login")[0].checkValidity()) {
                    
                    var l_ouser = user_gen();                    
                    l_ouser.check_logins(paramName, useremail.val(), userpassword.val());
                }
            });

        });  
        			
	</script>
  </body>
</html>