/**
* @author Aubrey 
*/
	//"use strict";

	var g_oAppointment = { day: '', userid: '', time: ''};	
	var g_oMontharr = [];
	var listofbookings = 		
		 [
	      { timeid: "t07", booked: true, time: "07:00"},
	      { timeid: "t08", booked: true, time: "08:00"},
	      { timeid: "t09", booked: true, time: "09:00"},
	      { timeid: "t10", booked: true, time: "10:00"},
	      { timeid: "t11", booked: true, time: "11:00"},
	      { timeid: "t12", booked: true, time: "12:00"},
	      { timeid: "t13", booked: true, time: "13:00"},
	      { timeid: "t14", booked: true, time: "14:00"},
	      { timeid: "t15", booked: true, time: "15:00"},
	      { timeid: "t16", booked: true, time: "16:00"},
	      { timeid: "t17", booked: true, time: "17:00"},
	      { timeid: "t18", booked: true, time: "18:00"}
	    ]
	    ;
	
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
		    
	function getformatdate(date){									
		return date.substr(-2,2) + '/' + date.substr(5,2) + '/' + date.substr(0,4);
	};		

	function get_times(pDay, pMonth){
		"use strict";
		for(var i = 0; i < pMonth.length; i++){				
			if(pMonth[i].day == pDay ){
				return pMonth[i].time;
			}
		}		
		return [];
	} 
	
	function make_profile_display(p_selements, p_ojoin){
					
		var lv_elements = "";																			
		
		if(p_selements === ""){	
												
			lv_elements = " Balance: R " + (p_ojoin.balance || "0"); 
			
			if(p_ojoin.display_name ){											
				lv_elements = lv_elements + ", <b>" + p_ojoin.display_name + "</b>: " + (p_ojoin.credits || " 0"); 
			}
			
			$("#nav_username").text(p_ojoin.username);										
		}
		else{
			
			if(p_ojoin.display_name){											
				lv_elements = lv_elements + ", <b>" + p_ojoin.display_name + "</b>: " + (p_ojoin.credits || " 0"); 
			}										
		}
		
		lv_elements = p_selements + lv_elements;
		
		return lv_elements;
	}
	
	function make_license_codes_display(p_selements, p_ojoin){
					
		var lv_elements;	
		
		if(p_selements === ""){
				lv_elements = '<option value="">Select....</option>' + 
				'<option value="' + p_ojoin.license_code + '">' + p_ojoin.display_name + '</option>';										 
			}
			else{
				lv_elements = p_selements + '<option value="' + p_ojoin.license_code + '">' + p_ojoin.display_name + '</option>';
				
		}	
		return lv_elements;
	}
	
	function set_available_times(pDay, p_marr){
	    "use strict";
		
		var booked_times = [];
		var seldate = pDay.substr(-4,4) + '-' + pDay.substr(3,2) + '-' + pDay.substr(0,2);		
				
		for(var i = 0; i < p_marr.length; i++){			
			if (p_marr[i].day == seldate){
				booked_times.push(p_marr[i].time.substr(0,5));
			}				
		}
		
		var found = false;
		
		for(var i = 0; i < listofbookings.length; i++){						
			found = $.inArray(listofbookings[i].time, booked_times);
			found = found > -1 ? true : false;
			listofbookings[i].booked = found;
			$("#" + listofbookings[i].timeid).prop("disabled", found);			
		}														
	}					
		
		function bookingsSetupSuccess(data, scode, jqXHR){
			
			if(data['booked_dates'] === false){
						
			}
			else{
				p_montharr = data['booked_dates'];
				set_available_times(g_oAppointment.day, p_montharr);						
				
				if(data['profile_data'] !== false){							
					
					var l_selements  = "";
					var l_oprofile = data['profile_data'];
										
					//Check if there something 
					if(l_oprofile){								
														
						for(var i = 0; i < l_oprofile.length; i++ ){																	
							l_selements = make_profile_display(l_selements, l_oprofile[i]);	
						}
						
						var lv_elements;
						
						lv_elements = '<p><a href="accounts.php">Update profile here</a></p>';							
						l_selements = l_selements + lv_elements;

					    $("#nav_login").hide();														
					    $("#nav_logout").show();							    

					}
					else{							   
					    $("#nav_username").text("Guest");							    
					    $("#nav_login").show();
					    $("#nav_logout").hide();							    
					}
					
					$( "#profile_data_p" ).empty();
					$( "#profile_data_p" ).append( l_selements );						
					
				}
						
				if(data['license_codes'] !== false){	
					
					var l_selements  = "";
					var l_olicense_code = data['license_codes'];
										
					if(l_olicense_code){
						
						for(var i = 0; i < l_olicense_code.length; i++ ){																	
							l_selements = make_license_codes_display(l_selements, l_olicense_code[i]);								
						}
					}
					else{
						l_selements = '<option value="">Select....</option>'
					}

					$( "#book_license_code").empty();
					$( "#book_license_code").append( l_selements );
				}
			}
		}
		
		function bookingsSetupFail(jqXHR, ip_status, ip_error){
			showErrorMessage(jqXHR.responseText);
		}
		
		function get_bookings_setup(){
			//"use strict";
			var paramName = "selected_month_date";			
			var submitData = {};
				
			submitData[paramName] = JSON.stringify(g_oAppointment.day);
			
			$.ajax({
				method: "POST",
				url: "/includes/php/get_bookings.php",
				data: submitData,
				cache: false,					
				dataType: "json",					
				success: bookingsSetupSuccess,								
				error: bookingsSetupFail
			});						
		}
	
	function makeBookingSuccess(data, scode, jqXHR){
		var l_sNewDate;
		
		if(data['check'] === true){
		    showSuccessMessage(data['message']);		
		}
		else {
		    showErrorMessage(data.message);											
		}
				
		l_sNewDate = $("td.active").attr("data-day");

		g_oAppointment.day = l_sNewDate.substr(3,2) + '/' + l_sNewDate.substr(0,2) + '/' + l_sNewDate.substr(-4,4);

		get_bookings_setup();		
	}
	
	function makeBookingFail(jqXHR, ip_status, ip_error){
		showErrorMessage(jqXHR.responseText);
	}
	
	function makeBooking(paramName, appointmentInfo){
		//"use strict";
		var submitAppointmentInfo = {};	
						
		submitAppointmentInfo[paramName] = JSON.stringify(appointmentInfo);
		
		$.ajax({
			method: "POST",
			url: "/includes/php/make_bookings.php",
			data: submitAppointmentInfo,
			cache: false,					
			dataType: "json",				
			success: makeBookingSuccess,							
			error: makeBookingFail 
		});						
	}
				
	function logout_fail(p_data){
		showErrorMessage("Failed to logout");	
	}
	
	function logout_success(successData, functionSuccess){

		if(successData.check === false){				
			showErrorMessage(successData.message);				
		}
		else{
		    showSuccessMessage(successData.message)			
			functionSuccess();			
		}				
	}
	
	function logout_complete(){
	}
	
	function logout(functionSuccess, functionfail, functionComplete){
		
		$.ajax({
			method: "POST",
			url: "/includes/php/logout.php",				
			cache: false,					
			dataType: "json",					
			success: function(data, scode, jqXHR){					
				logout_success(data, functionSuccess);
			},						
			error: functionfail,
			complete: functionComplete
		});						
	}				

	$( document ).ready(function(){      

	    var lst_id = 0;	    
	    var license_code = $("#book_license_code");
	    var paramName = "appointment";

	    $(".book").click(function () {

	        if (this.id.length === 3) {

	            g_oAppointment.time = this.id.substr(1, 2) + ':00';		
								
				if(license_code.val() == ""){					
				    showErrorMessage("Select a license code first before making a booking");
				}
				else{					
				
					var appointmentInfo = {
						"day": g_oAppointment.day,
						"time": g_oAppointment.time,
						"license_code": license_code.val()
					};
									
					makeBooking(paramName, appointmentInfo);
				}	            
	        }
	    });   

	    $('#datetimepicker_bds').datetimepicker({
	        format: 'DD/MM/YYYY',
	        minDate: Date(),
	        //stepping: 10,
	        //ignoreReadonly = false,				
	        //daysOfWeekDisabled: [0],
	        inline: true,
	        sideBySide: true,
	        keepOpen: true,
	        //disabledDates: ["2019-01-31"], 
	        debug: true	/*Keep open until blur event*/	        
	    });	    	

	    $("#userselecteddates").on("click", "#btnseldate", function () {
	        $(this).parent().remove();

	        if ($("#userselecteddates li").children().length < 2) {
	            $('#li_confirm_dates').hide();
	            lst_id = 1;
	        }
	    });


	    $("#datetimepicker_bds").on("dp.update", function () {

	        $('td.day').removeClass("active");
	        $("td.day").not(".disabled, .old, .new").filter(":first").addClass("active");

	        var l_sSelecteddate;
	        var l_sNewDate = $("td.active").attr("data-day");

	        g_oAppointment.day = l_sNewDate.substr(3, 2) + '/' + l_sNewDate.substr(0, 2) + '/' + l_sNewDate.substr(-4, 4);
	        $('#tbl_availtimes th>span').text(g_oAppointment.day);

	        get_bookings_setup();

	    });

	    $("#datetimepicker_bds").on("dp.change", function () {

	        var l_sSelecteddate = $('#datetimepicker_bds').val();
	        g_oAppointment.day = l_sSelecteddate;
	        $('#tbl_availtimes th>span').text(g_oAppointment.day);

	        get_bookings_setup();

	    });

	    $("#datetimepicker_bds").hide();/*Hide the input button*/

		$(".close").click(function(){
			$(this).parent().addClass('hidden');
		});
		
		var l_sSelecteddate = $('#datetimepicker_bds').val();
		g_oAppointment.day = l_sSelecteddate;
		$('#tbl_availtimes th>span').text(g_oAppointment.day);
		
		get_bookings_setup();
					        
        $("#nav_logout").on("click", function(){			
			
			logout(get_bookings_setup, logout_fail, logout_complete);			
				
        });            	
		
	});