// This js class handles the logic of all of the User related functions.  This is a subclass of the Generator class.

var User = {

	init: function() {
		// binds all User interface actions to initialization
		User.bindUiActions();
	},

	bindUiActions: function() {

		// $('#login_section :input').keyup(function() {
		// 	User.validateLogIn();
		// });

		$("#nav_logout").click(function() {
			User.logOutUser();
		});

		$("#login_form").submit(function(event) {
			User.logIn(event);
		});

		$("#register_form").submit(function(event) {
			User.registerUser(event);
		});
	},
	
	logOutUser: function() {
		loggedIn = false;
		$.ajax({
        	type: "POST",
        	url: "assets/php/logout.php",             
        	dataType: "html",     
        	success: function(response){
        		Generator.logOutUser();
        	}
    	});
	},

	logIn: function(event) {
		event.preventDefault();
	  	var data = $('#login_form').serialize();
	  	$.ajax({
        	type: "POST",
        	data: data,
        	url: "assets/php/login.php",             
        	dataType: "html",       
        	success: function(response){
        		if (response == "Username error") {
        			alert("Your Username was not valid.  Please try again.");
        		} else if (response == "Password error") {
        			alert("Your password was not valid.  Please try again.");
        		} else {
        			loggedIn = true;
        			Generator.logIn(response);  
        		}
        	}
    	});
	},

	registerUser: function(event) {
		event.preventDefault();
	  	var data = $('#register_form').serialize();
	  	$.ajax({
        	type: "POST",
        	data: data,
        	url: "assets/php/register.php",             
        	dataType: "html",     
        	success: function(response){
        		alert(response);
        		Generator.registerUser(response);
        	}
    	});
	},
}