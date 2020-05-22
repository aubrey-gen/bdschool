	
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
	
	function make_profile_display(p_selements, p_ojoin){
					
		var lv_elements = "";																			
		
		if(p_selements === ""){	
												
			lv_elements = " Balance: R " + (p_ojoin.balance || "0"); 
			
			if(p_ojoin.display_name ){											
				lv_elements = lv_elements + ", <b>" + p_ojoin.display_name + "</b>: " + (p_ojoin.credits || " 0"); 				
			}											
		}
		else{
			
			if(p_ojoin.display_name){											
				lv_elements = lv_elements + ", <b>" + p_ojoin.display_name + "</b>: " + (p_ojoin.credits || " 0"); 
			}										
		}
		
		lv_elements = p_selements + lv_elements;
		
		return lv_elements;
	}
	
	function make_catalogue_display(p_selements, p_ojoin){
				
		var lv_elements;								
		
		if(p_selements === ""){
				lv_elements = '<tr>' + 
				'<td>' +  p_ojoin.description + '</td>' + 
				'<td>' +  p_ojoin.price + '</td>' + 
				'<td>' +  p_ojoin.quantity + (p_ojoin.quantity > 1 ? ' lessons' : ' lesson') + '</td>' + 
				'<td><input class="btn btn-primary buy" type="button" value="BUY" data-itemid="' + p_ojoin.itemid + '" /></td>' + 
				'</tr>';										 
			}
			else{
				lv_elements = p_selements + '<tr>' + 
				'<td>' +  p_ojoin.description + '</td>' + 
				'<td>' +  p_ojoin.price + '</td>' + 
				'<td>' +  p_ojoin.quantity + (p_ojoin.quantity > 1 ? ' lessons' : ' lesson') + '</td>' + 
				'<td><input class="btn btn-primary buy" type="button" value="BUY" data-itemid="' + p_ojoin.itemid + '" /></td>' + 
				'</tr>';							
		}	
		return lv_elements;
	}
	
	function user_gen(){		
		//"use strict";
		
		function purchase_success(p_data){
			
			if(p_data['check'] === true){
				showSuccessMessage(p_data['message']);					
				init();
			}
			else{
				showErrorMessage(p_data['message']);							
			}
		}
		
		function purchase_fail(){
			showErrorMessage("Error in purchase");
		}
		
		function purchase(p_item, p_funct_success, p_funct_fail, p_funct_complete){
			
			var l_oFormdata = {					
					'itemid': p_item 
				};
			
			var jsonSubmitData = {};
			var paramName = "cart";
			
			jsonSubmitData[paramName] = JSON.stringify(l_oFormdata);
			
			$.ajax({
					method: "POST",
					url: "/includes/php/purchase.php",
					data: jsonSubmitData,
					cache: false,					
					dataType: "json",									
					success: p_funct_success,					
					error: p_funct_fail,
					complete: p_funct_complete
			});
		}
		
		function buy(p_obj){
			
			var l_sitem = $(this).data("itemid") || 0;
					
			purchase(l_sitem, purchase_success, purchase_fail, function () { return 0; });			
		}	
		
		function get_catalogue_fail(jqXHR, ip_status, ip_error){
			showErrorMessage("Something horrible went wrong");
		}
		
		function get_catalogue_success(data, scode, jqXH){			
		
			if(data['profile_data'] !== false){							
				
				var l_selements  = "";
				var l_oprofile = data['profile_data'];
								
				if(l_oprofile){								
														
					for(var i = 0; i < l_oprofile.length; i++ ){																	
						l_selements = make_profile_display(l_selements, l_oprofile[i]);	
					}
					
					var lv_elements;
					
					lv_elements = '<p><a href="accounts.php">Update profile here</a></p>';							
					l_selements = l_selements + lv_elements;					    

				}				
				
				$( "#profile_data_p" ).empty();
				$( "#profile_data_p" ).append( l_selements );						
				
			}
			
			if(data['catalogue'] !== false){	
				
				var l_selements  = "";
				var l_ocatalogue = data['catalogue'];							
				
				if(l_ocatalogue){					
				
					for(var i = 0; i < l_ocatalogue.length; i++ ){														
						l_selements = make_catalogue_display(l_selements, l_ocatalogue[i]);								
					}	
				}
				
				if(l_selements === ""){
					l_selements = '<tr><td>No products are available at this time</td></tr>';
				}

				$( "#catalogue_details").empty();
				$( "#catalogue_details").append( l_selements );
				
				if(l_ocatalogue){					
					$(".buy").click(buy);					
				}
			}
			else{
				var l_selements = '<tr><td>No products are available</td></tr>';
				$( "#catalogue_details").empty();
				$( "#catalogue_details").append( l_selements );
			}
					
		}
					
		function get_catalogue(p_funct_success, p_funct_fail, p_funct_complete){
			
			$.ajax({
					method: "POST",
					url: "/includes/php/get_products.php",
					cache: false,					
					dataType: 'json',									
					success: p_funct_success,					
					error: p_funct_fail,
					complete: p_funct_complete
			});	
		}
		
		function init(){
			
			get_catalogue(get_catalogue_success, get_catalogue_fail, function () { return 0; });
		}
		
		return {
			init: function(){
				init();
			}
		}
	}		
	
	$( document ).ready(function(){ 
		
		var l_ouser = user_gen();
		
		l_ouser.init();	

	});