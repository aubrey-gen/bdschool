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
					<li role="presentation" class="active" ><a href="#">Make a booking</a></li>					
					<li role="presentation"><a href="aboutus.php">About Us</a></li>
				</ul>
			
			</div>
			
			<div class="col-md-4 col-md-offset-1">         
                
                <ul class="nav nav-pills">                    
                    <li class="nav_username" role="presentation" ><a href="#">Welcome <span id="nav_username">Guest</span></a></li>
                    <li class="nav_username" role="presentation" ><a href="#">|</a></li>    
                    <li id="nav_login" role="presentation" style="display:none"><a href="signin.php">Sign In</a></li>
                    <li id="nav_logout" role="presentation" style="display:none"><a href="#">Sign Out</a></li>
                </ul>
            </div>		
			
		</div>
		
		<div class="row" >
		  <div class="col-md-8 col-md-offset-2">
			<div class="row">
				<div class="col-md-7">	
								
						<div id="profile_data">
							<p id="profile_data_p">								
							</p>										
						</div>
					
				</div>
				
				<div class="col-md-5" style="height: 75px;">					 

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
		  </div>  
		  
		</div>
		
		<div class="row" >
		  <div class="col-md-8 col-md-offset-2">										
										
					<div class="form-group">
						<div class="row">
							<div class="col-md-8">								
								
								<div class="form-inline">
									<label for="book_license_code">License code</label>
									<select name="book_license_code" id="book_license_code" >										
									</select>					
								  </div>
								
								<div class="form-group">
									<div>
										<input type='text' class="form-control" id="datetimepicker_bds"/>										
									</div>
								</div>				
																
							</div>
						
							<div class="col-md-4">																	
										
										<table class="table table-responsive" id="tbl_availtimes">
											<tr>
												<th colspan="2">Available times for <span></span></th>
											</tr>
											<tr>
												<td class="booktime">07:00</td>																							
												<td><input class="btn btn-primary center-block book" type='button' value="Book" id="t07" class="form-control"/></td>
											</tr>
											<tr>
												<td class="booktime">08:00</td>												
												<td><input class="btn btn-primary center-block book" type='button' value="Book" id="t08" class="form-control"/></td>
											<tr>
												<td class="booktime">09:00</td>
												<td><input class="btn btn-primary center-block book" type='button' value="Book" id="t09" class="form-control"/></td>
											</tr>
											<tr>
												<td class="booktime">10:00</td>
												<td><input class="btn btn-primary center-block book" type='button' value="Book" id="t10" class="form-control"/></td>											
											<tr>
												<td class="booktime">11:00</td>
												<td><input class="btn btn-primary center-block book" type='button' value="Book" id="t11" class="form-control"/></td>
											</tr>
											<tr>
												<td class="booktime">12:00</td>
												<td><input class="btn btn-primary center-block book" type='button' value="Book" id="t12" class="form-control"/></td>
											</tr>
											<tr>
												<td class="booktime">13:00</td>
												<td><input class="btn btn-primary center-block book" type='button' value="Book" id="t13" class="form-control" /></td>
											</tr>
											<tr>
												<td class="booktime">14:00</td>
												<td><input class="btn btn-primary center-block book" type='button' value="Book" id="t14" class="form-control"/></td>
											</tr>
											<tr>
												<td class="booktime">15:00</td>
												<td><input class="btn btn-primary center-block book" type='button' value="Book" id="t15" class="form-control"/></td>
											</tr>
											<tr>
												<td class="booktime">16:00</td>
												<td><input class="btn btn-primary center-block book" type='button' value="Book" id="t16" class="form-control"/></td>
											</tr>
											<tr>
												<td class="booktime">17:00</td>
												<td><input class="btn btn-primary center-block book" type='button' value="Book" id="t17" class="form-control"/></td>
											</tr>
											<tr>
												<td class="booktime">18:00</td>												
												<td><input class="btn btn-primary center-block book" type='button' value="Book" id="t18" class="form-control"/></td>
											<tr>
										</table>																																										
							</div>
						</div>
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
	<script src="libs/js/bds_gen.js"></script>
	<script type="text/javascript">				
	</script>
  </body>
</html>