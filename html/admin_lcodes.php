<?php
	//Start session
	session_start();
	
	if(isset($_SESSION["admin"])){		
	}
	else{
		header("Location: admin.php");
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
					<li role="presentation"><a href="admin_pay.php">Accounts</a></li>
					<li role="presentation"><a href="admin_products.php">Products</a></li>
					<li role="presentation" class="active"><a href="admin_lcodes.php">License Code</a></li>					
					<li role="presentation"><a href="admin_admin.php">Admin</a></li>
				</ul>
			
			</div>
			
			<div class="row">
				<div class="col-md-8 col-lg-offset-2" style="height: 70px; padding-top: 5px;">					 

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
			
			<div class="col-md-3 col-lg-offset-2">
				<p>Use the form below to add/update license codes.</p>
				<form class="form-group form-group-sm" id="frm_lcodes" method="post" onsubmit="return false;">
				  <div class="form-group">
					<label for="licensecode">License code</label>
					<input type="text" class="form-control" id="licensecode" placeholder="License code">
				  </div>
				  <div class="form-group">
					<label for="displayname">Display name</label>
					<input type="text" title="" class="form-control" id="displayname" placeholder="Display name">
				  </div>
				  <div class="form-group">
					<label for="newdisplayname">New display name</label>
					<input type="text" title="" class="form-control" id="newdisplayname" placeholder="New display name">
				  </div>
				  <button type="submit" id="btn_create_lcode" class="btn btn-primary btn-sm">Create</button>
				  <button style="float: right;" type="submit" id="btn_update_lcode" class="btn btn-primary btn-sm">Update</button>
				</form>			
											
			</div>	
            
            <div class="col-md-7">
														
	                <table class="table table-bordered" id="tblcodes">
	                    <thead>
	                        <tr>
	                            <th colspan="6" style="text-align: center;">Existing License Codes</th>
	                        </tr> 
	                        <tr>
	                        	<th>Status</th>
	                        	<th>License Code</th>
	                        	<th>Display text</th>
	                        	<th></th>
	                        	<th></th>
	                        	<th></th>
	                        </tr>                       
	                    </thead>
	                    
	                    <tbody id="codelist">                       
	                    </tbody>
	                </table>
				
            </div>
						
		<div class="row">
		  <div class="col-md-8 col-md-offset-2"><small>Created by Aubrey &copy; 2019</small></div>		  
		</div>
	
	</div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="libs/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="libs/js/bds_admin.js"></script>
	<script type="text/javascript">		
        		
		$(document).ready(function () {
            
             var lcodeform = $("form#frm_lcodes"),
             	licensecode = $("#licensecode"),
                displayname = $("#displayname"),
                newdisplayText = $("#newdisplayname");
             
             var loggedInAdmin = admin();
			
			$("#btn_create_lcode").on("click", function () {                        	       
		                        		        
		        loggedInAdmin.addLicensecode(lcodeform, licensecode.val(), displayname.val());

			});
			
			$("#btn_update_lcode").on("click", function () {                        	       
		                        		        
		        loggedInAdmin.updateLicensecode(lcodeform, licensecode.val(), newdisplayText.val());

			});

			//Get license codes
		    loggedInAdmin.loadCodes();			
            
		});
        		
	</script>
  </body>
</html>