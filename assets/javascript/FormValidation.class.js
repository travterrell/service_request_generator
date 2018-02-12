// This js class handles the logic of all of the Form validation related functions.  This is a subclass of the Generator class.

var errors = 0;

var firstNameTitle = '<strong>First Name Validation</strong><br><span class="number_special_character_check error">First name can\'t contain numbers or special characters<br></span><span class="length_check error">First name too short<br></span><span class="valid success">First name valid</span>';

var lastNameTitle = '<strong>Last Name Validation</strong><br><span class="number_special_character_check error">Last name can\'t contain numbers or special characters<br></span><span class="length_check error">Last name too short<br></span><span class="valid success">Last name valid</span>';

var registerUsernameTitle = '<strong>Username Validation</strong><br><span class="length_check error">Username can\'t be less than 5 characters<br></span><span class="username_check error">Username already exists<br></span><span class="valid success">Username valid</span>';

var passwordTitle = '<strong>Password Validation</strong><br><span class="length_check error">Password can\'t be less than 5 characters<br></span><span class="special_character_check error">Password has to contain at least 1 special character<br></span><span class="number_check error">Password has to contain at least 1 number<br></span><span class="valid success">Password valid</span>';

var confirmPasswordTitle = '<strong>Password Confirmation Validation</strong><br><span class="match_check error">Confirmation doesn\'t match password<br></span><span class="valid success">Password confirmation valid</span>';

var projectNameTitle = '<strong>Project Name Validation</strong><br><span class="project_name_check error">Project name already exists<br></span><span class="length_check error">Project name too short<br></span><span class="valid success">Project name valid</span>';

var budgetTitle = '<strong>Budget Validation</strong><br><span class="budget_info_check error">Non number, more than one decimal, or invalid dollar amount entered<br></span><span class="budget_amount_check error">Budget must be at least $1000.00<br></span><span class="valid success">Budget valid</span>';

var contactTitle = '<strong>Name Validation</strong><br><span class="valid_name_check error">Only letters and spaces allowed<br></span><span class="length_check error">Name too short<br></span><span class="valid success">Name valid</span>';

var cityTitle = '<strong>City Validation</strong><br><span class="valid_city_check error">City name too short<br></span><span class="number_special_character_check error">City can\'t contain numbers or special characters<br></span><span class="valid success">City valid</span>';

var tooltips = {
	first_name: firstNameTitle,
	last_name: lastNameTitle,
	register_username: registerUsernameTitle,
	register_password: passwordTitle,
	register_confirm_password: confirmPasswordTitle,
	project_name: projectNameTitle,
	budget: budgetTitle,
	primary_sales_contact: contactTitle,
	customer_contact: contactTitle,
	hq_city: cityTitle,
}

var FormValidation = {
	init: function() {
		FormValidation.bindUiActions();
	},

	bindUiActions: function() {
		$('#login_submit').prop('disabled', true);
		$('#register_submit').prop('disabled', true);
		$('#service_request_submit').prop('disabled', true);
		
		FormValidation.setTooltips();

		$('#login_section :input').keyup(function() {
			FormValidation.validateLogIn();
		});

		$('#first_name').keyup(function() {
			FormValidation.validateRegFirstNameInput();
		});

		$('#last_name').keyup(function() {
			FormValidation.validateRegLastNameInput();
		});

		$('#register_username').keyup(function() {
			FormValidation.validateRegUsernameInput();
		});

		$('#register_password').keyup(function() {
			FormValidation.validateRegPasswordInput();
		});

		$('#register_confirm_password').keyup(function() {
			FormValidation.validateRegConfirmPasswordInput();
		});

		$('#register_form :input').keyup(function() {
			FormValidation.validateRegistrationFormCompletion();
		});

		$('#project_name').keyup(function() {
			FormValidation.validateProjectNameInput();
		});

		$('#budget').keyup(function() {
			FormValidation.validateBudgetInput();
		});

		$('#primary_sales_contact').keyup(function() {
			FormValidation.validatePSCInput();
		});

		$('#customer_contact').keyup(function() {
			FormValidation.validateCCInput();
		});

		$('#hq_city').keyup(function() {
			FormValidation.validateCityInput();
		});

		$('#request_form :input').change(function() {
			FormValidation.validateServiceFormCompletion();
		});
	},

	setTooltips: function() {
		for (var key in tooltips) {
			$('#' + key).tooltip({
				trigger: 'focus',
				html: true,
				title: tooltips[key],
			});
		}
	},

	validateLogIn: function() {
		var emptyInputs = $('#login_section :input').parent().find('input[type="text"], input[type="password"]').filter(function() { return $(this).val() == ""; });
	    if (!emptyInputs.length) {
	        $('#login_submit').prop('disabled', false);
	    } else {
	    	$('#login_submit').prop('disabled', true);
	    }
	},

	validateRegFirstNameInput: function() {
		var firstName = $('#first_name').val();

		//check for no numbers or special characters //
		if ( firstName.match(/[^A-Za-z]/g) ) {
			errors++;
			$('#first_name').siblings().prop('disabled', true);
			$(".valid").hide();
			$(".number_special_character_check").show();
		} else {
			$('#first_name').siblings(':not(#register_submit)').prop('disabled', false);
			$(".number_special_character_check").hide();
			if (firstName.length >= 2) {
				$(".length_check").hide();
				$(".valid").show();
				errors = 0;
			} else {
				$(".length_check").show();
				$(".valid").hide();
			}
		}
	},

	validateRegLastNameInput: function() {
		var lastName = $('#last_name').val();

		//check for no numbers or special characters //
		if ( lastName.match(/[^A-Za-z]/g) ) {
			errors++;
			$('#last_name').siblings().prop('disabled', true);
			$(".valid").hide();
			$(".number_special_character_check").show();
		} else {
			$('#last_name').siblings(':not(#register_submit)').prop('disabled', false);
			$(".number_special_character_check").hide();
			if (lastName.length >= 2) {
				$(".length_check").hide();
				$(".valid").show();
				errors = 0;
			} else {
				$(".length_check").show();
				$(".valid").hide();
			}
		}
	},

	validateRegUsernameInput: function() {
		var username = $('#register_username').val();

		$.ajax({
            type: "POST",
            data:{
              "username": username,
            },
            url: "assets/php/username_check.php",             
            dataType: "html",    
            success: function(response){
				if ( response != "Error" ) {
					$('#register_username').siblings().prop('disabled', true);
					$(".valid").hide();
					$(".username_check").show();
					errors++;
				} else {
					$(".username_check").hide();
					errors = 0;
					if ( username.length > 0 && username.length < 5 ) {
						errors++;
						$('#register_username').siblings().prop('disabled', true);
						$(".valid").hide();
						$(".length_check").show();
					} else {
						$('#register_username').siblings(':not(#register_submit)').prop('disabled', false);
						$(".length_check").hide();
						if (username.length > 0) {
							$(".valid").show();
						} else {
							$(".valid").hide();
							errors = 0;
						}
					}
				}
            }
        });		
	},

	validateRegPasswordInput: function() {
		var password = $('#register_password').val();

		// check for password longer than 5 characters //
		if ( password.length < 5 ) {
			errors++;
			$('#register_password').siblings().prop('disabled', true);
			$(".valid").hide();
			$(".length_check").show();
		} else {
			$('#register_password').siblings(':not(#register_submit)').prop('disabled', false);
			$(".length_check").hide();
			$(".valid").show();
			errors = 0;
		}

		if ( !password.match(/[^a-zA-Z0-9\-\/]/) ) {
			errors++;
			$('#register_password').siblings().prop('disabled', true);
			$(".valid").hide();
			$(".special_character_check").show();
		} else {
			$('#register_password').siblings(':not(#register_submit)').prop('disabled', false);
			$(".special_character_check").hide();
			if (password.length > 5) {
				$(".valid").show();
				errors = 0;
			} else {
				$(".valid").hide();
			}
		}

		if ( !password.match(/\d/)) {
			errors++;
			$('#register_password').siblings().prop('disabled', true);
			$(".valid").hide();
			$(".number_check").show();
		} else {
			$('#register_password').siblings(':not(#register_submit)').prop('disabled', false);
			$(".number_check").hide();

		}
	},

	validateRegConfirmPasswordInput: function() {
		var password = $('#register_password').val();
		var confirmPassword = $('#register_confirm_password').val();


		// check for password longer than 5 characters //
		if ( confirmPassword != password ) {
			errors++;
			$('#register_confirm_password').siblings().prop('disabled', true);
			$(".valid").hide();
			$(".match_check").show();
		} else {
			$('#register_confirm_password').siblings(':not(#register_submit)').prop('disabled', false);
			$(".match_check").hide();
			$(".valid").show();
			errors = 0;
		}
	},

	validateProjectNameInput: function() {

		var projectName = $('#project_name').val();
		var title = $('#service_request_title').html();
		title = title.substring(0, 4);
		var type = ( title == "Edit" ? "Edit" : "New");
		var id = ( type == "Edit" ? $('#id').val() : 0);
		$.ajax({    
            type: "POST",
            data:{
              "project_name": projectName,
              "type": type,
              "id": id,
            },
            url: "assets/php/project_name_check.php",             
            dataType: "html",    
            success: function(response){
            	// check for original project name //
				if ( response > 0 ) {
					errors++;
					$('#project_name').siblings().prop('disabled', true);
					$(".valid").hide();
					$(".project_name_check").show();
				} else {
					// $('#project_name').siblings(':not(#service_request_submit)').prop('disabled', false);
					$(".project_name_check").hide();
					if (projectName.length >= 4) {
						$(".length_check").hide();
						$(".valid").show();
						$('#project_name').siblings(':not(#service_request_submit)').prop('disabled', false);
						errors = 0;
					} else {
						$(".length_check").show();
						$(".valid").hide();
						$('#project_name').siblings().prop('disabled', true);
					}
				}
            }
        });
	},

	validateBudgetInput: function() {
		var budget = $('#budget').val();

		$('#budget').siblings().prop('disabled', true);
		$(".budget_amount_check").show();
		if ( !budget.match(/^(\d+)?([.]?\d{0,2})?$/g) ) {
			errors++;
			$(".valid").hide();
			$(".budget_info_check").show();
		} else {
			$(".budget_info_check").hide();
			if (parseFloat(budget) >= 1000) {
				$(".valid").show();
				$(".budget_amount_check").hide();
				errors = 0;
				$('#budget').siblings(':not(#service_request_submit)').prop('disabled', false);
			} else {
				$(".valid").hide();
			}
		}
	},

	validatePSCInput: function() {
		var primarySalesContact = $('#primary_sales_contact').val();

		if ( !primarySalesContact.match(/^([A-Za-z\s])+$/g) ) {
			$('#primary_sales_contact').siblings().prop('disabled', true);
			errors++;
			$(".valid").hide();
			$(".valid_name_check").show();
		} else {
			$(".valid_name_check").hide();
			if (primarySalesContact.length > 1) {
				$(".length_check").hide();
				$(".valid").show();
				$('#primary_sales_contact').siblings().prop('disabled', false);
				errors = 0;
			} else {
				$(".length_check").show();
				$(".valid").hide();
			}
		}
	},

	validateCCInput: function() {
		var customerContact = $('#customer_contact').val();


		if ( !customerContact.match(/^([A-Za-z\s])+$/g) ) {
			$('#customer_contact').siblings().prop('disabled', true);
			errors++;
			$(".valid").hide();
			$(".valid_name_check").show();
		} else {
			$(".valid_name_check").hide();
			if (customerContact.length > 1) {
				$('#customer_contact').siblings().prop('disabled', false);
				$(".length_check").hide();
				$(".valid").show();
				errors = 0;
			} else {
				$(".length_check").show();
				$(".valid").hide();
			}
		}
	},

	validateCityInput: function() {
		var hqCity = $('#hq_city').val();

		$('#hq_city').siblings().prop('disabled', true);
		if ( hqCity.length < 3) {
			errors++;
			$(".valid").hide();
			$(".valid_city_check").show();
		} else {
			$('#hq_city').siblings(':not(#register_submit)').prop('disabled', false);
			$(".valid_city_check").hide();
			$(".valid").show();
			errors = 0;
		}

		if ( hqCity.match(/[^a-zA-Z\s]/g) ) {
			errors++;
			$('#hq_city').siblings().prop('disabled', true);
			$(".valid").hide();
			$(".number_special_character_check").show();
		} else {
			$('#hq_city').siblings(':not(#register_submit)').prop('disabled', false);
			$(".number_special_character_check").hide();
			errors = 0;
		}
	},

	validateServiceFormCompletion: function() {
		var emptyTextInputs = $('#request_form :input').parent().find('input[type="text"]').filter(function() { return $(this).val() == ""; });
	    var checkedRadioInputs = $(':input[type="radio"]:checked').length;
	    var selectedOptionInputs = $('#agency_name, #hq_state').filter(function() { return $(this).val() == ""; });
	    if (!emptyTextInputs.length && checkedRadioInputs >= 6 && selectedOptionInputs.length == 0) {
	        $('#service_request_submit').prop('disabled', false);
	    } else {
	    	$('#service_request_submit').prop('disabled', true);
	    }
	},

	validateRegistrationFormCompletion: function() {
		var emptyInputs = $('#register_form :input').parent().find('input[type="text"], input[type="password"]').filter(function() { return $(this).val() == ""; });
	    if (!emptyInputs.length & errors == 0) {
	        $('#register_submit').prop('disabled', false);
	    } else {
	    	$('#register_submit').prop('disabled', true);
	    }
	},

	resetForm: function() {
		$('#service_request_submit').prop('disabled', true);
		$('#service_form').trigger("reset");
		$("#agency_name, #hq_state").val('');
    	$("#type").val("New");
	},

}