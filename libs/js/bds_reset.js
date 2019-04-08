
function generalUser() {
	"use strict";
    
    //Local variables   
    var messageHandler = {
        "dangerSpan": $(".alert-danger span"),
        "danger": $(".alert-danger"),
        "successSpan": $(".alert-success span"),
        "success": $(".alert-success")
    };  	
			
	//Local functions
    function showErrorMessage(message) {
        messageHandler.danger.show();
        messageHandler.dangerSpan.text(message);
        messageHandler.danger.fadeOut(6000);
    }

    function showSuccessMessage(message) {
        messageHandler.success.show();
        messageHandler.successSpan.text(message);
        messageHandler.success.fadeOut(6000);
    }
    
    function updatePasswordSuccess(data) {					        	
	    if (data.check === true) {
	        showSuccessMessage(data.message);				
		}
		else{
			showErrorMessage(data.message);
		}     
    }
    
    function resetPasswordSuccess(data) {	
				
        if (data.reset_success === true) {
        	window.location.href = "reset_success.php";                 
        }
        else{
        	
            if (data.check === true) {
                showSuccessMessage(data.message);				
			}
			else{
				showErrorMessage(data.message);
			}  
        }
    }        	
	
	function resetPasswordFail(){
		showErrorMessage("Failed to update password");
	}
		
	function userUtility(paramName, submitData, functionSuccess, functionFail, functionComplete) {

        var jsonSubmitData;
        var tempSubmitData = {};

        jsonSubmitData = JSON.stringify(submitData);

        tempSubmitData[paramName] = jsonSubmitData;

        $.ajax({
            method: "POST",
            url: "/includes/php/user_utils_update.php",
            data: tempSubmitData,
            cache: false,
            dataType: "json",
            success: functionSuccess,
            error: functionFail,
            complete: functionComplete
        });
    }
	
	function validatePassword(enteredPassword) {
	    var p = enteredPassword,
	    	errorMessage = "";
	    
	    if (p.match(/^[\w]+$/) === null) {
            errorMessage = "Use only alphanumerics for password";
        }   
	    else if (p.length < 6) {
	    	errorMessage = "Your password must be at least 6 characters long";	       
	    }	    
	    else if (p.search(/[a-z!]/) < 0) {
	    	errorMessage = "Your password must contain at least one lowercase.";	       
	    }
	    else if (p.search(/[A-Z!]/) < 0) {
	    	errorMessage = "Your password must contain at least one uppercase."	       
	    }
	    else if (p.search(/[0-9]/) < 0) {
	    	errorMessage = "Your password must contain at least one digit.";	       
	    }
	    
	    return errorMessage;     
	}
	
	function resetPassword(userForm, password1, password2) {

        if (userForm[0].checkValidity()) {
			//
			var formFilled = false,
				errorMessage,
            	valid;
            	
            //Remove spaces
            password1 = password1 ? password1.replace(/\s/g, "") : "";
            password2 = password2 ? password2.replace(/\s/g, "") : "";  

            //Check if form was filled
            if (password1 && password2) {
                formFilled = true;
            }					
			
            if (formFilled) {         	  	 
            	                               
                if(password1 !== password2){
					showErrorMessage("Passwords entered must match");					
				}            	  	            	            
                else {
                	
                	errorMessage = validatePassword(password1);
            		valid = errorMessage ? false : true;
                                       
                    if (!valid) {                	
	                    showErrorMessage(errorMessage);
	                }
                	else{						
					
	                    var userInfo = {	                                       
	                        "password_1": password1 || "",
	                        "password_2": password2 || ""	                       
	                    };                 
						
	                    userUtility("update_pw", userInfo, resetPasswordSuccess, resetPasswordFail, function () { return 0; });
					}
                }
            }
            else {
                showErrorMessage("Complete all fields to reset password");
            }
        }
    }
	
	function updatePassword(userForm, password1, password2) {

        if (userForm[0].checkValidity()) {
			//
			var formFilled = false,
				errorMessage,
            	valid;
            	
            //Remove spaces
            password1 = password1 ? password1.replace(/\s/g, "") : "";
            password2 = password2 ? password2.replace(/\s/g, "") : "";  

            //Check if form was filled
            if (password1 && password2) {
                formFilled = true;
            }					
			
            if (formFilled) {         	  	 
            	                               
                if(password1 !== password2){
					showErrorMessage("Passwords entered must match");					
				}            	  	            	            
                else {
                	
                	errorMessage = validatePassword(password1);
            		valid = errorMessage ? false : true;
                                       
                    if (!valid) {                	
	                    showErrorMessage(errorMessage);
	                }
                	else{						
					
	                    var userInfo = {	                                       
	                        "password_1": password1 || "",
	                        "password_2": password2 || ""	                       
	                    };                 
						
	                    userUtility("update_pw", userInfo, updatePasswordSuccess, resetPasswordFail, function () { return 0; });
					}
                }
            }
            else {
                showErrorMessage("Complete all fields to update password");
            }
        }
    }
	
    return {
        resetPassword: function (userForm, password1, password2){
			resetPassword(userForm, password1, password2);	
		},
		updatePassword: function (userForm, password1, password2){
			updatePassword(userForm, password1, password2);	
		}     
    };						
}