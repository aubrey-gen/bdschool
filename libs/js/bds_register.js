
function generalUser() {
	"use strict";
    
    //Local variables   
    var messageHandler = {
        "dangerSpan": $(".alert-danger span"),
        "danger": $(".alert-danger"),
        "successSpan": $(".alert-success span"),
        "success": $(".alert-success")
    };   
	var userAddForm = {
		"username" : $("#reg_username"),
		"gender" : $("#reg_gender"),
		"email" : $("#reg_useremail"),	
		"terms": $("input#reg_terms").val(),		
		"password1" : $("#reg_password"),
        "password2" : $("#reg_password_confirm")
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
    
    function varExists(vartocheck) {
        var declared = true;
        try {
            vartocheck;
        }
        catch (e) {
            if (e.name == "ReferenceError") {
                declared = false;
            }
        }
        return declared;
    }

    function addClientSuccess(data) {	
				
        if (data.check === true) {        	           
            //Clear entered data
            userAddForm.username.val("");
            userAddForm.email.val("");
            userAddForm.password1.val("");
            userAddForm.password2.val("");
            //Check if there is a redirent url
            if(data.url){                
                try {
                    window.location.href(data.url);
                }
                catch (e) {
                   //reload captcha  
                    grecaptcha.reset() ;
                }
            } 
            else{
                //reload captcha  
                grecaptcha.reset() ;
            }              
            //
            showSuccessMessage(data.message);        
        }
        else{
            //Check if CAPTCHA was verified and failed
            if(varExists(data.captcha_failed) && data.captcha_failed == true ){
                //reload captcha
                grecaptcha.reset() ;
            }
            //Check for error messages
            if (data.message !== "") {
                //Check if redirect url exist
				if(data.url){
					window.location.href(data.url);
				}
				else{
					showErrorMessage(data.message);	
				}			
			}
			else{
				showErrorMessage("Unable to add client");
			}  
        }
    }    

    function addClientFail(data) {
        showErrorMessage("Failed to add client");
    }   
	
	function userUtility(paramName, submitData, functionSuccess, functionFail, functionComplete) {

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
	
	function addClient(userForm, username, gender, email, password1, password2, terms, captcha) {

        if (userForm[0].checkValidity()) {
			//
			var formFilled = false,
				errorMessage,
            	valid;
            	
            //Remove spaces
            username = username ? username.replace(/\s/g, "") : "";  
            gender = gender ? gender.replace(/\s/g, "") : "";
            email = email ? email.replace(/\s/g, "") : "";
            password1 = password1 ? password1.replace(/\s/g, "") : "";
            password2 = password2 ? password2.replace(/\s/g, "") : "";  
            terms = terms ? terms.replace(/\s/g, "") : ""; 
            captcha = captcha ? captcha.replace(/\s/g, "") : ""; 
                                  
            //Change to lowercase
            gender = gender ? gender.toUpperCase() : "";
            email = email ? email.toLowerCase() : "";

            //Check captcha
            if(captcha == ""){
                showErrorMessage("Check for robot has not completed");
                formFilled = false;
                return;
            }

            //Check if form was filled
            if (username && gender && email && password1 && password2 && terms && captcha) {
                formFilled = true;
            }					
			
            if (formFilled) {            	
            	    
            	if (username.match(/^[\w\-\s]+$/) === null) {
                    showErrorMessage("Use only alphanumerics for username");
                }
                else if (gender.match(/^[FM]+$/) === null){
                    showErrorMessage("Invalid gender selected");                    
                }                                
                else if(password1 !== password2){
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
	                        "username": username || "",
	                        "gender": gender || "",
	                        "email": email || "",                        
	                        "password1": password1 || "",
	                        "password2": password2 || "",
                            "terms": terms,
                            "captcha": captcha || ""
	                    };

	                    userUtility("reg_data", userInfo, addClientSuccess, addClientFail, function () { return 0; });
					}
                }
            }
            else {
                showErrorMessage("Complete all fields to register");
            }
        }
    }
	
    return {
        addClient: function (userForm, username, gender, email, password1, password2, terms, captcha){
			addClient(userForm, username, gender, email, password1, password2, terms, captcha);	
		}     
    };						
}