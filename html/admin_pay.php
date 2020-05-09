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
					<li role="presentation" class="active"><a href="#">Accounts</a></li>
					<li role="presentation"><a href="admin_products.php">Products</a></li>
					<li role="presentation" ><a href="admin_lcodes.php">License Code</a></li>					
					<li role="presentation"><a href="admin_admin.php">Admin</a></li>
				</ul>
			
			</div>
			
			<div class="row">
				<div class="col-md-8 col-lg-offset-2" style="height: 70px; padding-top: 5px;">					 

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
			
			<div class="col-md-3 col-lg-offset-2">
				<p>Use the form below to update client balances when they have paid funds.</p>
				<form class="form-group form-group-sm" id="frm_pay" method="post" onsubmit="return false;">
				  <div class="form-group">
					<label for="reference">Account Reference</label>
					<input type="text" class="form-control" id="reference" placeholder="Account Reference">
				  </div>
				  <div class="form-group">
					<label for="update_amount">Amount</label>
					<input type="text" pattern="[0-9]+" minlength="2" maxlength="4" title="4321" class="form-control" id="update_amount" placeholder="Amount">
				  </div>
				  <button type="submit" id="btn_update_balance" class="btn btn-primary btn-sm">Update Balance</button>
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
	<script type="text/javascript">		
        		
		function admin()
		{
		    "use strict";

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

		    function updateBalanceSuccess(data, scode, jqXHR) {

		        if (data.check === true) {
		            showSuccessMessage(data.message);
		            //Clear entries
		            $("#reference").val("");
                    $("#update_amount").val("");
		        }
		        else {
		            showErrorMessage(data.message);
		        }
		    }

		    function updateBalanceFail(jqXHR, ip_status, ip_error) {
		        showErrorMessage("Failed to update balance");
		    }

		    function updateBalanceComplete(data) {
		    }

		    function updateBalance(paramName, submitData, functionSuccess, functionFail, functionComplete) {

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
		            success: functionSuccess,
		            error: functionFail,
		            complete: functionComplete
		        });
		    }

		    function checkBalanceInfo(balanceForm, account_reference, amount) {

		        var formFilled;

		        if (balanceForm[0].checkValidity()) {

		            //Remove spaces
		            account_reference = account_reference ? account_reference.replace(/\s/g, "") : "";
		            //Change to uppercase
		            account_reference = account_reference ? account_reference.toUpperCase() : "";
                    //Check if form was filled
		            formFilled = account_reference && amount && true;

		            if (formFilled) {
		                if (amount.match(/^\d+$/) === false) {
		                    showErrorMessage("Use only numbers for field Amount");
		                }
		                else {
		                    var balanceInfo = {
		                        "reference": account_reference || 0,
		                        "amount": amount || 0
		                    };

		                    updateBalance("update_balance", balanceInfo, updateBalanceSuccess, updateBalanceFail, updateBalanceComplete);
		                }
		            }
		            else {
		                showErrorMessage("Complete all fields to update balance");
		            }
		        }
		    }            

		    return {
		        checkBalanceInfo: function (balanceForm, reference, amount) {
		            checkBalanceInfo(balanceForm, reference, amount);
		        }
		    };
		}

		$(document).ready(function () {
            
             var balanceform = $("form#frm_pay"),
                    reference = $("#reference"),
                    amount = $("#update_amount");
            
		    $("#btn_update_balance").on("click", function () {                
		        	       
		        var loggedInAdmin = admin();
                		        
		        loggedInAdmin.checkBalanceInfo(balanceform, reference.val(), amount.val());

		    });

		});
        		
	</script>
  </body>
</html>