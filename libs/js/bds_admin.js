function admin() {
    "use strict";
    
    //Local variables   
    var messageHandler = {
        "dangerSpan": $(".alert-danger span"),
        "danger": $(".alert-danger"),
        "successSpan": $(".alert-success span"),
        "success": $(".alert-success")
    };
    var userBalanceInfoForm = {
        "reference": $("#reference"),
        "amount": $("#update_amount")
    };	
	var adminAddForm = {
		"email" : $("#adminemail"),
		"password1" : $("#password1"),
		"password2" : $("#password2")		
	};	
	var licenseCodeForm = {
		"code" : $("#licensecode"),
		"newDisplayText" : $("#newdisplayname"),
		"displayText" : $("#displayname")		
	};
	var productForm = {
		"description" : $("#productDescription"),
		"price" : $("#price"),		
		"quantity" : $("#quantity")		
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
    
	function updateBalanceSuccess(data) {

        if (data.check === true) {
            showSuccessMessage(data.message);
            //Clear entries
            userBalanceInfoForm.reference.val("");
            userBalanceInfoForm.amount.val("");
        }
        else {
            //check if there is any messages     
            if (data.message !== "") {
                //Check if the is a redirect url
				if(data.url){
					window.location.href(data.url);
				}
				else{
					showErrorMessage(data.message);	
				}			
			}
			else{
				showErrorMessage("Unable to update balance");
			}            
        }         
    }

    function updateBalanceFail() {
        showErrorMessage("Failed to update balance");
    }
	
    function eventTableAdmins() {

        $(".admin_delete").on("click", function () {
			                    
            var admin = $(this).parent().parent().attr("data-email");            
            var adminInfo = {
                "email": admin || ""                
            };
			
			if(admin){
				adminUtility("del_admin", adminInfo, deleteAdminSuccess, deleteAdminFail, function () { return 0; });	
			}
            else{
				showErrorMessage("Select Admin to delete");
			}

        });
    }

    function getAdminsSuccess(data) {
        var adminList = $("#adminlist");
        var adminListRows = $("#adminlist tr");
        var hmtlTable;
        var rowtHtmlTable;

        //Clear list no matter the error status
        if (adminList !== null) {
            adminListRows.remove();
        }
        //Check if redirect url exist
        if(data.url){
        	window.location.href(data.url);
		}
		
        //Check if the were any errors
        if (data.check === true) {

            //Get admins
            if (data.adminList.length > 0) {

                //Update table with admins                                        
                if (adminList !== null) {

                    //Loop through data and create a new list
                    data.adminList.forEach(function (adminMember) {

                        rowtHtmlTable = '<tr data-email="' + adminMember.email + '">';
						rowtHtmlTable = rowtHtmlTable + '<td>' + adminMember.email + '</td>';                        
                        rowtHtmlTable = rowtHtmlTable + '<td><button class="btn btn-danger center-block admin_delete" >Delete</button></td>';
                        rowtHtmlTable = rowtHtmlTable + '</tr>';

                        hmtlTable = hmtlTable + rowtHtmlTable;

                    });
                    //Update list           
                    adminList.append(hmtlTable);
                    //Set up events
                    eventTableAdmins();
                }
            }
            else {

                rowtHtmlTable = '<tr><td>Looks like you are the only admin :( </td></tr>';

                hmtlTable = rowtHtmlTable;
                adminList.append(hmtlTable);
            }

        }
        else {
        	
        	rowtHtmlTable = '<tr><td>Looks like you are the only admin :( </td></tr>';

            hmtlTable = rowtHtmlTable;
            adminList.append(hmtlTable);            
        }
    }

    function getAdminsFail(data, jqXHR){
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
			showErrorMessage("Unable to get admins");
		}        
    }
    
    function deleteAdminSuccess(data) {	
		
		refreshAdmins(); 
						
        if (data.check === true) {
            showSuccessMessage(data.message);                       
        }
        else{
        	
            if (data.message !== "") {
                //Chec if redirect url exist
				if(data.url){
					window.location.href(data.url);
				}
				else{
					showErrorMessage(data.message);	
				}			
			}
			else{
				showErrorMessage("Unable to delete admin");
			}  
        }
    }

    function deleteAdminFail() {
        showErrorMessage("Failed to delete admin");
    }
        
    function updateAdminSuccess(data) {	
				
        if (data.check === true) {
        	
            showSuccessMessage(data.message);
            //Clear entered data
            adminAddForm.email.val("");
            adminAddForm.password1.val("");
            adminAddForm.password2.val("");
            //Load update list of admin
            refreshAdmins();
        }
        else{
        	
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
				showErrorMessage("Unable to update admin");
			}  
        }
    }

    function updateAdminFail() {
        showErrorMessage("Failed to update admin");
    }      
    
    function addAdminSuccess(data) {	
				
        if (data.check === true) {
        	
            showSuccessMessage(data.message);
            //Clear entered data
            adminAddForm.email.val("");
            adminAddForm.password1.val("");
            adminAddForm.password2.val("");
            //Load update list of admin
            refreshAdmins();
        }
        else{
        	
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
				showErrorMessage("Unable to add admin");
			}  
        }
    }
    
    function addAdminFail() {
        showErrorMessage("Failed to add admin");
    }  
    
    function eventTableCodes() {

        $(".lcode_delete").on("click", function () {
            
            var licenseCodeId = $(this).parent().parent().attr("data-codeid");                        
            removeLicensecode(licenseCodeId);

        });

        $(".lcode_activate").on("click", function () {

            var licenseCodeId = $(this).parent().parent().attr("data-codeid");
            activateLicensecode(licenseCodeId, true);            
        });
        
        $(".lcode_deactivate").on("click", function () {

            var licenseCodeId = $(this).parent().parent().attr("data-codeid");
            activateLicensecode(licenseCodeId, false);            
        });

        $(".radiobutton").click(function () {
            if ($(this).prop("checked") == true) {
                //run code
                showErrorMessage($(this).text());
            } else {
                //run code
                showErrorMessage("sdsd");
            }
        });
    }

    function getCodesSuccess(data) {
    	var codeList = $("#codelist");
        var codeListRows = $("#codelist tr");
        var hmtlTable;
        var rowtHtmlTable;

        //Clear list no matter the error status
        if (codeList !== null) {
            codeListRows.remove();
        }
        //Check if redirect url exist
        if(data.url){
        	window.location.href(data.url);
		}
		
        //Check if the were any errors
        if (data.check === true) {

            //Get admins
            if (data.codeList.length > 0) {

                //Update table with admins                                        
                if (codeList !== null) {

                    //Loop through data and create a new list
                    data.codeList.forEach(function (adminMember) {

                        rowtHtmlTable = '<tr data-codeid="' + adminMember.codeid + '">';
                         
                        
                        if(adminMember.active === "1"){
							 rowtHtmlTable = rowtHtmlTable + '<td><span class="badge">Active</span></td>';
						}
						else{
							 rowtHtmlTable = rowtHtmlTable + '<td><span class="badge">Inactive</span></td>';
						}
                        
						rowtHtmlTable = rowtHtmlTable + '<td>' + adminMember.code + '</td>';
                        rowtHtmlTable = rowtHtmlTable + '<td>' + adminMember.displayText + '</td>';                       
						
						rowtHtmlTable = rowtHtmlTable + '<td><button class="btn btn-success center-block lcode_activate" >Activate</button></td>';
						rowtHtmlTable = rowtHtmlTable + '<td><button class="btn btn-warning center-block lcode_deactivate" >Deactivate</button></td>';
                        rowtHtmlTable = rowtHtmlTable + '<td><button class="btn btn-danger center-block lcode_delete" >Delete</button></td>';
                        rowtHtmlTable = rowtHtmlTable + '</tr>';

                        hmtlTable = hmtlTable + rowtHtmlTable;

                    });
                    
                    //Update list           
                    codeList.append(hmtlTable);
                    //Set up events
                    eventTableCodes();
                }
            }
            else {

                rowtHtmlTable = '<tr><td>No license codes created :( </td></tr>';

                hmtlTable = rowtHtmlTable;
                codeList.append(hmtlTable);
            }

        }
        else {
        	
        	rowtHtmlTable = '<tr><td>No license codes created :( </td></tr>';

            hmtlTable = rowtHtmlTable;
            codeList.append(hmtlTable);            
        }

    }

    function getCodesFail(data) {
        if (data.message !== "") {
            //Check if redirect url exist
            if (data.url) {
                window.location.href(data.url);
            }
            else {
                showErrorMessage(data.message);
            }
        }
        else {
            showErrorMessage("Unable to get license codes");
        }
    }

    function addLicensecodeSuccess(data) {	
				
        if (data.check === true) {
        	
            showSuccessMessage(data.message);
            //Clear entered data
            licenseCodeForm.code.val("");
            licenseCodeForm.newDisplayText.val("");
            licenseCodeForm.displayText.val("");	
            //Load update list of license codes
            refreshCodes();
        }
        else{
        	
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
				showErrorMessage("Unable to add license code");
			}  
        }
    }

    function addLicensecodeFail() {
        showErrorMessage("Failed to add license code");
    }

    function removeLicensecodeSuccess(data) {
        if (data.check === true) {
            
            showSuccessMessage(data.message);                        
            //Load update list of license codes
            refreshCodes();
        }
        else {

            if (data.message !== "") {
                //Check if redirect url exist
                if (data.url) {
                    window.location.href(data.url);
                }
                else {
                    showErrorMessage(data.message);
                }
            }
            else {
                showErrorMessage("Unable to delete license code");
            }
        }
    }

    function removeLicensecodeFail() {
        showErrorMessage("Failed to delete license code");
    }
    
    function activateLicensecodeSuccess(data) {
        if (data.check === true) {

            showSuccessMessage(data.message);
            //Load update list of license codes
            refreshCodes();
        }
        else {

            if (data.message !== "") {
                //Check if redirect url exist
                if (data.url) {
                    window.location.href(data.url);
                }
                else {
                    showErrorMessage(data.message);
                }
            }
            else {
                showErrorMessage("Unable to activate/de-activate license code");
            }
        }
    }

    function activateLicensecodeFail() {
        showErrorMessage("Failed to activate/de-activate license code");
    }
    
    function updateLicensecodeSuccess(data) {	
				
        if (data.check === true) {
        	
            showSuccessMessage(data.message);
            //Clear entered data
            licenseCodeForm.code.val("");
            licenseCodeForm.newDisplayText.val("");
            licenseCodeForm.displayText.val("");	
            //Load update list of license codes
            refreshCodes();
        }
        else{
        	//Check if there is a message
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
				showErrorMessage("Unable to update license code");
			}  
        }
    }

    function updateLicensecodeFail() {
        showErrorMessage("Failed to update license code");
    }
    
    function eventTableProducts() {

        $(".product_delete").on("click", function () {
            
            var itemNo = $(this).parent().parent().attr("data-itemno");                        
            removeProduct(itemNo);

        });

        $(".product_activate").on("click", function () {

            var itemNo = $(this).parent().parent().attr("data-itemno");
            activateProduct(itemNo, true);            
        });
        
        $(".product_deactivate").on("click", function () {

            var itemNo = $(this).parent().parent().attr("data-itemno");
            activateProduct(itemNo, false);            
        });
        		
    }
    
    function getProductsSuccess(data) {
    	var productList = $("#productslist");
        var productListRows = $("#productslist tr");
        var hmtlTable;
        var rowtHtmlTable;

        //Clear list no matter the error status
        if (productList !== null) {
            productListRows.remove();
        }
        //Check if redirect url exist
        if(data.url){
        	window.location.href(data.url);
		}
		
        //Check if the were any errors
        if (data.check === true) {

            //Get Products
            if (data.productList.length > 0) {

                //Update table with admins                                        
                if (productList !== null) {

                    //Loop through data and create a new list
                    data.productList.forEach(function (product) {

                        rowtHtmlTable = '<tr data-itemno="' + product.itemNo + '">';   
                                             
                        rowtHtmlTable = rowtHtmlTable + '<td><input type="radio" name="rowSelection" />';
                        
                        if(product.active === "1"){
							 rowtHtmlTable = rowtHtmlTable + '<td><span class="badge">Active</span></td>';
						}
						else{
							 rowtHtmlTable = rowtHtmlTable + '<td><span class="badge">Inactive</span></td>';
						}
                        
						rowtHtmlTable = rowtHtmlTable + '<td>' + product.description + '</td>';
                        rowtHtmlTable = rowtHtmlTable + '<td>' + product.price + '</td>';          
                        rowtHtmlTable = rowtHtmlTable + '<td>' + product.quantity + '</td>';              
						
						rowtHtmlTable = rowtHtmlTable + '<td><button class="btn btn-success center-block product_activate" >Activate</button></td>';
						rowtHtmlTable = rowtHtmlTable + '<td><button class="btn btn-warning center-block product_deactivate" >Deactivate</button></td>';
                        rowtHtmlTable = rowtHtmlTable + '<td><button class="btn btn-danger center-block product_delete" >Delete</button></td>';
                        rowtHtmlTable = rowtHtmlTable + '</tr>';

                        hmtlTable = hmtlTable + rowtHtmlTable;

                    });
                    
                    //Update list           
                    productList.append(hmtlTable);
                    //Set up events
                    eventTableProducts();
                }
            }
            else {

                rowtHtmlTable = '<tr><td colspan="7">No products created :( </td></tr>';

                hmtlTable = rowtHtmlTable;
                productList.append(hmtlTable);
            }
        }
        else {
        	
        	rowtHtmlTable = '<tr><td colspan="7">No products created :( </td></tr>';

            hmtlTable = rowtHtmlTable;
            productList.append(hmtlTable);            
        }
    }

    function getProductsFail(data) {
        if (data.message !== "") {
            //Check if redirect url exist
            if (data.url) {
                window.location.href(data.url);
            }
            else {
                showErrorMessage(data.message);
            }
        }
        else {
            showErrorMessage("Unable to get products");
        }
    }
    
    function removeProductSuccess(data){
		if (data.check === true) {

            showSuccessMessage(data.message);
            //Load update list of products
            refreshProducts();
        }
        else {

            if (data.message !== "") {
                //Check if redirect url exist
                if (data.url) {
                    window.location.href(data.url);
                }
                else {
                    showErrorMessage(data.message);
                }
            }
            else {
                showErrorMessage("Unable to delete product");
            }
        }
	}
	
	function removeProductFail(){
		showErrorMessage("Failed to delete product");
	}
    
    function activateProductSuccess(data) {
        if (data.check === true) {

            showSuccessMessage(data.message);
            //Load update list of product
            refreshProducts();
        }
        else {

            if (data.message !== "") {
                //Check if redirect url exist
                if (data.url) {
                    window.location.href(data.url);
                }
                else {
                    showErrorMessage(data.message);
                }
            }
            else {
                showErrorMessage("Unable to activate/de-activate product");
            }
        }
    }

    function activateProductFail() {
        showErrorMessage("Failed to activate/de-activate product");
    }
    
    function getCodesProductsSuccess(data) {
    	
    	var codeList = $("#activeLicenseCodes");
    	var licenseCodes = data['codeList'];
    	var optionList  = "";	
		var optionElement = "";
		var i = 0;
       
        //Clear list no matter the error status
        if (codeList !== null) {
            codeList.empty();
        }
        //Check if redirect url exist
        if(data.url){
        	window.location.href(data.url);
		}	
		
		if(data.check === true) {
											
			if(licenseCodes){
				
				for(i = 0; i < licenseCodes.length; i++ ){														
					optionElement = '<option value="' + licenseCodes[i].codeid + '">' + licenseCodes[i].code + '</option>';	
					optionList = optionList + optionElement;						
				}						
			}			
		}
				
		//optionElement = '<option value="0">Misc.</option>';	
		optionList = optionList + optionElement;
		//
		codeList.append( optionList );		
    }
    
     function getCodesProductsFail(data) {
        if (data.message !== "") {
            //Check if redirect url exist
            if (data.url) {
                window.location.href(data.url);
            }
            else {
                showErrorMessage(data.message);
            }
        }
        else {
            showErrorMessage("Unable to get license codes products");
        }
    }
    
    function addProductSuccess(data) {	
				
        if (data.check === true) {
        	
            showSuccessMessage(data.message);
            //Clear entered data
            productForm.description.val("");
            productForm.price.val("");
            productForm.quantity.val("");	
            //Load update list of license codes and products
            refreshProducts();
        }
        else{
        	
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
				showErrorMessage("Unable to add product");
			}  
        }
    }

    function addProductFail() {
        showErrorMessage("Failed to add product");
    }
        
    function updateProductSuccess(data) {	
				
        if (data.check === true) {
        	
            showSuccessMessage(data.message);
            //Clear entered data
            productForm.description.val("");
            productForm.price.val("");
            productForm.quantity.val("");	
            //Load update list of license codes and products
            refreshProducts();
        }
        else{
        	//Check if there is a message
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
				showErrorMessage("Unable to update product");
			}  
        }
    }

    function updateProductFail() {
        showErrorMessage("Could not update product");
    }
    

    function adminUtility(paramName, submitData, functionSuccess, functionFail, functionComplete) {

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
	    else if (p.length < 8) {
	    	errorMessage = "Your password must be at least 8 characters long";	       
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
    
    function updateAdmin(adminForm, email, password1, password2) {

        var formFilled = false;

        if (adminForm[0].checkValidity()) {

            //Remove spaces
            email = email ? email.replace(/\s/g, "") : "";                  
            //Change to lowercase
            email = email ? email.toLowerCase() : "";

            //Check if form was filled
            if (email && password1 && password2) {
                formFilled = true;
            }
			
			if(password1 !== password2){
				showErrorMessage("Passwords entered must match");
				return;
			}
			
            if (formFilled) {
                var errorMessage = validatePassword(password1);
            	var valid = errorMessage ? false : true;            	           	
                
                if (!valid) {                	
                    showErrorMessage(errorMessage);
                }
                else {
                    var adminInfo = {
                        "email": email || "",
                        "password1": password1 || "",
                        "password2": password2 || ""
                    };

                    adminUtility("update_admin", adminInfo, updateAdminSuccess, updateAdminFail, function () { return 0; });
                }
            }
            else {
                showErrorMessage("Complete all fields to update admin");
            }
        }
    }        
    
    function addAdmin(adminForm, email, password1, password2) {

        var formFilled = false;

        if (adminForm[0].checkValidity()) {

            //Remove spaces
            email = email ? email.replace(/\s/g, "") : "";                 
            //Change to lowercase
            email = email ? email.toLowerCase() : "";

            //Check if form was filled
            if (email && password1 && password2) {
                formFilled = true;
            }
			//Check if passwords match
			if(password1 !== password2){
				showErrorMessage("Passwords entered must match");
				return;
			}
			
            if (formFilled) {
            	var errorMessage = validatePassword(password1);
            	var valid = errorMessage ? false : true;
            	            	            	
                if (!valid) {                	
                    showErrorMessage(errorMessage);
                }
                else {
                    var adminInfo = {
                        "email": email || "",
                        "password1": password1 || "",
                        "password2": password2 || ""
                    };

                    adminUtility("reg_admin", adminInfo, addAdminSuccess, addAdminFail, function () { return 0; });
                }
            }
            else {
                showErrorMessage("Complete all fields to Add admin");
            }
        }
    }
    
     function checkBalanceInfo(balanceForm, account_reference, amount){

        var formFilled = false;

        if (balanceForm[0].checkValidity()) {

            //Remove spaces
            amount = amount ? amount.replace(/\s/g, "") : "";
            //Change to uppercase
            account_reference = account_reference ? account_reference.toUpperCase().trim() : "";

            //Check if form was filled
            if (account_reference && amount) {
                formFilled = true;
            }

            if (formFilled) {
                if (amount.match(/^\d+$/) === null) {
                    showErrorMessage("Use only numbers for field Amount");
                }
                else {
                    var balanceInfo = {
                        "reference": account_reference || 0,
                        "amount": amount || 0
                    };

                    adminUtility("update_balance", balanceInfo, updateBalanceSuccess, updateBalanceFail, function () { return 0; });
                }
            }
            else {
                showErrorMessage("Complete all fields to update balance");
            }
        }
    }

     function refreshCodes() {
         var paramName = "get_codes";
         var adminInfo = {};

         adminUtility(paramName, adminInfo, getCodesSuccess, getCodesFail, function () { return 0; });
     }
     
     function refreshProducts() {
         var paramName = "get_products";
         var adminInfo = {};

         adminUtility(paramName, adminInfo, getProductsSuccess, getProductsFail, function () { return 0; });
         
         //Refresh codes
         getCodesProducts();         
     }

    function refreshAdmins() {
        var paramName = "get_admins";
        var adminInfo = {};

        adminUtility(paramName, adminInfo, getAdminsSuccess, getAdminsFail, function () { return 0; });
    }
    
    function addLicensecode(lcodeForm, licenseCode, displayText) {

        var formFilled = false;

        if (lcodeForm[0].checkValidity()) {
            
            //Remove trailing spaces and change to uppercase
            licenseCode = licenseCode ? licenseCode.toUpperCase().trim() : "";
            //Remove trailing spaces
            displayText = displayText ? displayText.trim() : "";

            //Check if form was filled
            if (licenseCode && displayText){
                formFilled = true;
            }
			
            if (formFilled) {
                if (licenseCode.match(/^[\w\-\s]+$/) === null) {
                    showErrorMessage("Use only alphanumerics, - and space for licence code");
                }
                else if (displayText.match(/^[\w\-\s]+$/) === null) {
                    showErrorMessage("Use only alphanumerics, - and space for display name");
                }
                else {
                    var licenseCodeInfo = {
                        "code": licenseCode || "",
                        "displayText": displayText || ""
                    };

                    adminUtility("reg_lcodes", licenseCodeInfo, addLicensecodeSuccess, addLicensecodeFail, function () { return 0; });
                }
            }
            else {
                showErrorMessage("Complete all fields to add license code");
            }
        }
    }

    function removeLicensecode(licenseCode) {
        //Remove spaces
        licenseCode = licenseCode ? licenseCode.replace(/\s/g, "") : "";

        if (licenseCode === "") {
            showErrorMessage("Select licence code to delete");
        }
        else if (licenseCode.match(/^\d+$/) === null) {
            
        }
        else{
            var licenseCodeInfo = {
                "codeid": licenseCode || ""                
            };
	
            adminUtility("del_lcodes", licenseCodeInfo, removeLicensecodeSuccess, removeLicensecodeFail, function () { return 0; });
        }        
    }

    function activateLicensecode(licenseCode, activate) {
        //Remove spaces
        licenseCode = licenseCode ? licenseCode.replace(/\s/g, "") : "";

        if (licenseCode === "") {
            showErrorMessage("Select licence code to activate/de-activate");
        }
        else if (licenseCode.match(/^\d+$/) === null) {
            showErrorMessage("License code selected is invalid");
        }
        else {
            var licenseCodeInfo = {
                "codeid": licenseCode || "",
                "active" : activate ? 1 : 0
            };

            adminUtility("activate_lcodes", licenseCodeInfo, activateLicensecodeSuccess, activateLicensecodeFail, function () { return 0; });
        }
    }
    
    function updateLicensecode(lcodeForm, licenseCode, displayText) {

        var formFilled = false;

        if (lcodeForm[0].checkValidity()) {
            
            //Remove trailing spaces and change to uppercase
            licenseCode = licenseCode ? licenseCode.toUpperCase().trim() : "";
            //Remove trailing spaces
            displayText = displayText ? displayText.trim() : "";

            //Check if form was filled
            if (licenseCode && displayText){
                formFilled = true;
            }
			
            if (formFilled) {
                if (licenseCode.match(/^[\w\-\s]+$/) === null) {
                    showErrorMessage("Use only alphanumerics, - and space for licence code");
                }
                else if (displayText.match(/^[\w\-\s]+$/) === null) {
                    showErrorMessage("Use only alphanumerics, - and space for new display name");
                }
                else {
                    var licenseCodeInfo = {
                        "code": licenseCode || "",
                        "displayText": displayText || ""
                    };

                    adminUtility("update_lcodes", licenseCodeInfo, updateLicensecodeSuccess, updateLicensecodeFail, function () { return 0; });
                }
            }
            else {
                showErrorMessage("Complete all fields to update license code");
            }
        }
    }
    
    function removeProduct(product) {
        //Remove spaces
        product = product ? product.replace(/\s/g, "") : "";

        if (product === "") {
            showErrorMessage("Select product to delete");
        }
        else if (product.match(/^\d+$/) === null) {
            showErrorMessage("Product selected is invalid");
        }
        else{
            var productInfo = {
                "product": product || ""                
            };
	
            adminUtility("del_product", productInfo, removeProductSuccess, removeProductFail, function () { return 0; });
        }        
    }
	
	function activateProduct(product, activate) {
        //Remove spaces
        product = product ? product.replace(/\s/g, "") : "";

        if (product === "") {
            showErrorMessage("Select Product to activate/de-activate");
        }
        else if (product.match(/^\d+$/) === null) {
            showErrorMessage("Product selected is invalid");
        }
        else {
            var productInfo = {
                "product": product || "",
                "active" : activate ? 1 : 0
            };

            adminUtility("activate_product", productInfo, activateProductSuccess, activateProductFail, function () { return 0; });
        }
    }
    
    function addProduct(productForm, description, licenseCode, price, quantity){
		
		var formFilled = false;

        if (productForm[0].checkValidity()) {

            //Remove spaces
            description = description ? description.trim() : "";
            licenseCode = licenseCode ? licenseCode.replace(/\s/g, "") : "";
            price = price ? price.replace(/\s/g, "") : "";
            quantity = quantity ? quantity.replace(/\s/g, "") : "";

            //Check if form was filled
            if (description && licenseCode && price && quantity) {
                formFilled = true;
            }

            if (formFilled) {
            	if (description.match(/^[\w\-\(\)\s]+$/) === null) {
                    showErrorMessage("Use only alphanumerics, -, (, ) and space for description");
                }
                else if (licenseCode.match(/^\d+$/) === null) {
                    showErrorMessage("Invalid license code");
                }
                else if (price.match(/^\d+$/) === null) {
                    showErrorMessage("Use only numbers for price");
                }
                else if (quantity.match(/^\d+$/) === null) {
                    showErrorMessage("Use only numbers for quantity");
                }
                else {
                    var productInfo = {
                        "description": description,
                        "licenseCode": licenseCode || 0,
                        "price": price || 0,
                        "quantity": quantity || 0
                    };
				
                    adminUtility("reg_product", productInfo, addProductSuccess, addProductFail, function () { return 0; });
                }
            }
            else {
                showErrorMessage("Complete all fields to create product");
            }
        }
	}
	
	function updateProduct(productForm, description, price, quantity, selectedItem ){
		var formFilled = false;

        if (productForm[0].checkValidity()) {

            //Remove spaces
            description = description ? description.trim() : "";            
            price = price ? price.replace(/\s/g, "") : "";
            quantity = quantity ? quantity.replace(/\s/g, "") : "";
            selectedItem = selectedItem ? selectedItem.replace(/\s/g, "") : "";
			
			//Check if a product was selected
            if (!selectedItem){
                showErrorMessage("Select a product to update");
	        	return;
            }
			
            //Check if form was filled
            if (description || price || quantity){
                formFilled = true;
            }

            if (formFilled) {
            	
            	var productInfo = {};
            	
            	if(description){
					if (description.match(/^[\w\-\(\)\s]+$/) === null) {
	                    showErrorMessage("Use only alphanumerics, -, (, ) and space for description");
	                    return;
	                }
	                 productInfo["description"] = description;
				}
            	
            	if(price){
					if (price.match(/^\d+$/) === null) {
	                    showErrorMessage("Use only numbers for price");
	                    return;
	                }
	                productInfo["price"] = price;
				}              
                
                if(quantity){
					if (quantity.match(/^\d+$/) === null) {
	                    showErrorMessage("Use only numbers for quantity");
	                    return;
	                }	
	                productInfo["quantity"] = quantity;				
				}
                
                if (selectedItem.match(/^\d+$/) === null) {
                    showErrorMessage("Invalid product");
                }
                else {
                	
					productInfo["product"] = selectedItem;
                    adminUtility("update_product", productInfo, updateProductSuccess, updateProductFail, function () { return 0; });
                }
            }
            else {
                showErrorMessage("Complete at least one field to update product");
            }
        }
	}
    
    function getCodesProducts() {
         var paramName = "get_codes";
         var adminInfo = {
         	 "active" : 1
         };

         adminUtility(paramName, adminInfo, getCodesProductsSuccess, getCodesProductsFail, function () { return 0; });
     }
	
    return {
        checkBalanceInfo: function (balanceForm, reference, amount) {
            checkBalanceInfo(balanceForm, reference, amount);
        },
        addAdmin: function (adminForm, email, password1, password2){
			addAdmin(adminForm, email, password1, password2);	
		},
		updateAdmin: function (adminForm, email, password1, password2){
			updateAdmin(adminForm, email, password1, password2);	
		},
        loadAdmins: function () {
            refreshAdmins();
        },
        addLicensecode: function (lcodeForm, licenseCode, displayText, newDisplayName){
			addLicensecode(lcodeForm, licenseCode, displayText, newDisplayName);	
        },
        updateLicensecode: function (lcodeForm, licenseCode, newDisplayText){
			updateLicensecode(lcodeForm, licenseCode, newDisplayText);	
        },
        addProduct: function (productForm, description, licenseCode, price, quantity) {
            addProduct(productForm, description, licenseCode, price, quantity);
        },
        updateProduct: function (productForm, description, price, quantity, selectedItem) {
            updateProduct(productForm, description, price, quantity, selectedItem);
        },       
        loadCodes: function () {
            refreshCodes();
        },
        loadProducts: function () {
            refreshProducts();
        }       
    };
}