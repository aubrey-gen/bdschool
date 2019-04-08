	
	function user_gen(){		
		//'use strict';	
			
		function login_success(data, scode, jqXHR){
			l_oStatus = data;
			if(data.user_info.length > 0){
				alert("Welcome " + data.user_info[0].username);
				window.location.href = "bookings.php";
				console.log(l_oStatus);	
			}
			else{
				alert("Invalid username or password");
			}				
		}
		
		function login_fail(jqXHR, ip_status, ip_error){
			//$(".alert").hide();						
			$(".alert-danger span").text(jqXHR.responseText);
			$(".alert-danger").show();
			console.log(jqXHR.responseText)   //$.del								
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
					data: {"login_data": l_oJSONformdata},
					cache: false,					
					dataType: 'json',									
					success: p_funct_success,					
					error: p_funct_fail,
					complete: p_funct_complete
			});	
		}
		
		function check_login_failed(){
			alert("Complete all fields to sign");
		}
		
		function check_logins(p_susername, p_spassword){
			//'use strict';							
					
			var l_bchecked =  p_susername && p_spassword && true;
			
			if(l_bchecked){
				login(p_susername, p_spassword, login_success, login_fail, login_complete);
			}
			else{
				check_login_failed();
			}			
		}
		
		return {
			check_logins: function(p_susername, p_spassword){
				check_logins(p_susername, p_spassword);
			}
		}
	}

	$( document ).ready(function(){ 
		
		$("#btn_userlogin").on("click", function(){			
				
				if($("form#frm_login")[0].checkValidity()) {
			        
			        var l_susername = $("#useremail").val();
					var	l_spassword = $("#password").val();						
					var l_ouser = user_gen();
					
					l_ouser.check_logins(l_susername, l_spassword);			        
			        
			    }else {			
	
			    	console.log("invalid form");
				}	
        });			
		
	});