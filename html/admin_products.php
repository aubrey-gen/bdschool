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
					<li role="presentation" class="active"><a href="#">Products</a></li>
					<li role="presentation"><a href="admin_lcodes.php">License Code</a></li>					
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
				<p>Use the form below to add/update products.</p>
				<form class="form-group form-group-sm" id="frmProduct" method="post" onsubmit="return false;">
				  <div class="form-group">
					<label for="productDescription">Description</label>
					<input type="text" class="form-control" id="productDescription" placeholder="Description">
				  </div>
				  
				  <div class="form-group">
					<label for="activeLicenseCodes">License codes</label>
					<select name="activeLicenseCodes" id="activeLicenseCodes">										
					</select>					
				  </div>
				  
				  <div class="form-group">
					<label for="price">Price</label>
					<input type="text" pattern="[0-9]+" minlength="2" maxlength="4" title="4321" class="form-control" id="price" placeholder="Price">
				  </div>
				  
				  <div class="form-group">
					<label for="quantity">Quantity</label>
					<input type="text" pattern="[0-9]+" minlength="1" maxlength="2" title="4321" class="form-control" id="quantity" placeholder="Quantity">
				  </div>								  
				
				  <button type="submit" id="btn_create_product" class="btn btn-primary btn-sm">Create</button>
				  <button style="float: right;" type="submit" id="btn_update_product" class="btn btn-primary btn-sm">Update</button>
				</form>			
											
			</div>	
            
            <div class="col-md-7">
														
	                <table class="table table-bordered" id="tblproducts">
	                    <thead>
	                        <tr>
	                            <th colspan="8" style="text-align: center;">Existing Products</th>
	                        </tr> 
	                        <tr>
	                        	<th></th>
	                        	<th>Status</th>
	                        	<th>Desscription</th>
	                        	<th>Price</th>
	                        	<th>Qty</th>
	                        	<th></th>
	                        	<th></th>
	                        	<th></th>
	                        </tr>                       
	                    </thead>
	                    
	                    <tbody id="productslist">                       
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
						
	        var  loggedInAdmin = admin(),	        	         
	        	 productForm = $("form#frmProduct"),
	         	 description = $("#productDescription"),
	             licensecode = $("#activeLicenseCodes"),
	             price = $("#price"),
	             quantity = $("#quantity");	                           	                  
           
			$("#btn_create_product").on("click", function () {                        	       
		                        		        
		        loggedInAdmin.addProduct(productForm, description.val(), licensecode.val(), price.val(), quantity.val());

			});
			
			$("#btn_update_product").on("click", function () {   
				var selectedItem = $("input[name=rowSelection]:checked").parent().parent().attr("data-itemno"); ;               		        
		        
		        loggedInAdmin.updateProduct(productForm, description.val(), price.val(), quantity.val(), selectedItem);

			});

			//Get products
		    loggedInAdmin.loadProducts();			
            
		});
        		
	</script>
  </body>
</html>