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
				  <button type="submit" id="btn_adminlogin" class="btn btn-primary btn-sm">Sign In</button>
				</form>			
											
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
		
		function login_success(data, scode, jqXHR){
				
			if(data['check'] === true){
				window.location.href(data.url);
			}
			else{
				$(".alert-danger span").text(data['message']);											
				$(".alert-danger").show();	
				$(".alert-danger").fadeOut(3000);		
			}			
		}
		
		function login_fail(jqXHR, ip_status, ip_error){
			$(".alert-danger span").text("Failed to login");
			$(".alert-danger").show();
			$(".alert-danger").fadeOut(5000);								
		}
		
		function login_complete(data){			
		}
				
		function login(p_username, p_password, p_funct_success, p_funct_fail, p_funct_complete){
			
			var l_oFormdata = {
					'username': p_username,	
					'password': p_password 
				};
			var l_oJSONformdata;
			
			l_oJSONformdata = JSON.stringify(l_oFormdata);
			
			$.ajax({
					method: "POST",
					url: "/includes/php/user_utils.php",
					data: {"admin_login": l_oJSONformdata},
					cache: false,					
					dataType: 'json',									
					success: p_funct_success,					
					error: p_funct_fail,
					complete: p_funct_complete
			});	
		}
		
		function check_login_failed(){		
			$(".alert-danger span").text("Complete all fields to sign in");
			$(".alert-danger").show();
			$(".alert-danger").fadeOut(6000);	
		}
		
		function check_logins(){
			//'use strict';							
			var l_susername = $("#useremail").val();
			var	l_spassword = $("#password").val();			
			var l_bchecked =  l_susername && l_spassword && true;
			
			if(l_bchecked){
				login(l_susername, l_spassword, login_success, login_fail, login_complete);
			}
			else{
				check_login_failed();
			}			
		}
		
		$("#btn_adminlogin").on("click", function(){			
							
			check_logins();					
        });	
        		
	</script>
  </body>
</html>