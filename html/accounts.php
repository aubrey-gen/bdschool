<?php
	//Start session
	session_start();
	
	if(isset($_SESSION["username"])){		
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
	
		<div class="row" >
		  <div class="col-md-10 col-md-offset-2" >
			
				<ul class="nav nav-pills">	
					<li role="presentation"><a href="index.php">Home</a></li>
					<li role="presentation"><a href="products.php">Products</a></li>
					<li role="presentation"><a href="bookings.php">Make a booking</a></li>										
					<li role="presentation"><a href="aboutus.php">About Us</a></li>
					<li role="presentation" class="active" ><a href="#">Profile</a></li>
				</ul>
			
			</div>				
			
		</div>
		
		<div class="row">
			<div class="col-md-8 col-lg-offset-2" style="height: 50px; padding: 5px;">					 

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
		
		<div class="row">
			<div class="col-md-10  col-lg-offset-2">					 
				<h4>Account Reference: <span id="account_reference"></span></h4>					
			</div>
		</div>
		
		<div class="row" >
		
			<div class="col-md-4 col-md-offset-2">
			
				<h4>Your Bookings</h4>
				
				<table class="table table-responsive">
					<thead>
						<tr>
							<th colspan="3"></th>
						</tr>
					</thead>
				
					<tbody id="booking_list">						
					</tbody>					
				</table>
				
			</div>				
			
			<div class="col-md-3">
				<div id="update_account">
				<h4>Update Password</h4>
				<form class="form-horizonta form-group-sm" id="account_update_password" method="post" onsubmit="return false;" >				 			 
			   				  				  				  
				  <div class="form-group">
					<label for="reg_password">Password</label>
					<input type="Password" class="form-control" name="up_password" id="up_password" placeholder="Password" />
				  </div>
				  <div class="form-group">
					<label for="reg_password_confirm">Confirm Password</label>
					<input type="Password" class="form-control" name="up_password_confirm" id="up_password_confirm" placeholder="Confirm Password" />
				  </div>			  
				  
				  <div class="form-group">				  	
				  	<button type="submit" class="btn btn-success" id="update_password">Update password</button>
				  </div>
				</form>
				</div>		
			</div>
			
			<div class="col-md-3">
				<div id="del_account">
				<h4>Delete Account</h4>
				
				<p>
					By deleting your account, you forfeit any monies paid that have not been used and will not be able to claim
					them even if you create another account or the same account again. Check that you have no positive balance and 
					before you delete your account.					
				</p>
				<form class="form-horizontal" id="account_del" method="post" onsubmit="return false;" >
				 				  				  
				  <div class="form-inline">										
					<input type="checkbox" id="del_monies" name="del_monies" value="true" required="required"/>
					<label for="del_monies">I acknowledge that I will forfiet any monies paid and not used</label> 
				  </div>
				  
				  <div class="form-inline">										
					<input type="checkbox" id="del_consent" name="del_consent" value="true" required="required"/>
					<label for="del_consent">I acknowledge that I will not be able to access any content that relate to this account</label> 
				  </div>
				  
				  <div class="form-inline">				  	
				  	<button type="submit" class="btn btn-sm btn-danger" id="update_delete">DELETE ACCOUNT</button>
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
    <script type="text/javascript" src="libs/js/bds_reset.js" charset="UTF-8"></script>
	<script type="text/javascript">	
		//"use strict";
				
		function user_update(p_sname, p_odata, p_funct_success, p_funct_fail, p_funct_complete){
			
			var l_oJSONformdata,
				l_otemp = { };				
			
			l_oJSONformdata = JSON.stringify(p_odata);
			
			l_otemp[p_sname] = l_oJSONformdata;
			
			$.ajax({
				method: "POST",
				url: "/includes/php/user_utils_update.php",
				data: l_otemp,
				cache: false,					
				dataType: "json",									
				success: p_funct_success,										
				error: p_funct_fail, 
				complete: p_funct_complete
			});						
		}	
				
		function user_update_delete_acc_fail(jqXHR, ip_status, ip_error){

			$(".alert-danger span").text("Failed to delete account");
			$(".alert-danger").show();
			$(".alert-danger").fadeOut(5000);
		}
			
		function user_update_delete_acc_success(data, scode, jqXHR){
			
			if(data['check'] === true){
				window.location = "delete_accounts.php";
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
						
		function user_update_delete_acc(){
						
				var oFormdata = {
					'del_monies': $("input#del_monies:checked").val(),
					'del_consent': $("input#del_consent:checked").val()
				};
				
				user_update("update_delacc", oFormdata, user_update_delete_acc_success, user_update_delete_acc_fail, function () { return 0; });											
		}		

		function get_bookings_fail(jqXHR, ip_status, ip_error){
			
			$(".alert-danger span").text("Failed to get your bookings");
			$(".alert-danger").show();
			$(".alert-danger").fadeOut(5000);
		}
			
		function get_bookings_success(data, scode, jqXHR){
			
			var l_selements  = "";
			var l_obookings = data['booked_dates'];
			
			function make_bookings_list(p_selements, p_ojoin){
			
				var lv_elements;								
				
				if(p_selements === ""){
						lv_elements = '<tr>' + 
						'<td>' +  p_ojoin.day_display + '</td>' + 
						'<td>' +  p_ojoin.time_display + '</td>' + 
						'<td>' +  p_ojoin.display_name + '</td>' + 						
						'<td><input class="btn btn-primary cancel_book" type="button" value="Cancel" ' + 
						'data-date="' + p_ojoin.day + '" data-time="' + p_ojoin.time + '" /></td>' + 
						'</tr>';										 
				}
				else{
					lv_elements = p_selements + '<tr>' + 
					'<td>' +  p_ojoin.day_display + '</td>' + 
					'<td>' +  p_ojoin.time_display + '</td>' +
					'<td>' +  p_ojoin.display_name + '</td>' +  					 
					'<td><input class="btn btn-primary cancel_book" type="button" value="Cancel" ' + 
					'data-date="' + p_ojoin.day + '" data-time="' + p_ojoin.time + '" /></td>' + 
					'</tr>';							
				}	
				return lv_elements;
			}
			
			if(l_obookings.length){					
			
				for(var i = 0; i < l_obookings.length; i++ ){
													
					l_selements = make_bookings_list(l_selements, l_obookings[i]);								
				}	
			}
			else{

				l_selements = '<tr><td>No bookings made</td></tr>';
			}

			$( "#booking_list").empty();
			$( "#booking_list").append( l_selements );
			$("#account_reference").text(data['account_reference']);
			
			if(l_obookings){					
				$(".cancel_book").click(cancel_booking);					
			}			
		}

		function get_bookings(){
				
			var oFormdata = {
				'init': "true"					
			};
			
			user_update("init", oFormdata, get_bookings_success, get_bookings_fail, function () { return 0; });											
		}
		
		function cancel_booking_fail(jqXHR, ip_status, ip_error){
						
			$(".alert-danger span").text("Failed to cancel booking");
			$(".alert-danger").show();
			$(".alert-danger").fadeOut(5000);			
		}
			
		function cancel_booking_success(data, scode, jqXHR){
			
			if(data['check'] === true){

				$(".alert-success").show();
				$(".alert-success span").text(data['message']);
				$(".alert-success").fadeOut(3000);
				
				get_bookings_success(data, scode, jqXHR);

			}
			else{
				$(".alert-danger span").text(data['message']);											
				$(".alert-danger").show();	
				$(".alert-danger").fadeOut(3000);		
			}
		}
		
		function cancel_booking(p_obj){
			
			var oFormdata = {
					'bookeddate': $(this).data("date") || 0,
					'bookedtime': $(this).data("time") || 0
			};		

			user_update("update_delbook", oFormdata, cancel_booking_success, cancel_booking_fail, function () { return 0; });											
		}
		
		function init(){
			get_bookings();
		}
			
		$(document).ready(function(){ 
            
            var userform = $("form#account_update_password"),             	
                password1 = $("#up_password"),
                password2 = $("#up_password_confirm");                             
            var user = generalUser();
            
            init();
            
            $("#update_password").on("click", function(){            	
            	user.updatePassword(userform, password1.val(), password2.val());
			});	
			
			$("#update_delete").on("click", function(){
            	user_update_delete_acc();
			});	
            			
		});
			
		</script>
  </body>
</html>