// This js class handles the animation and navigation of this one page web app
var navClicked = false;
var request = "new";

var Generator = {
	init: function() {
		Generator.animateHomePage();
		Generator.bindUiActions();
	},

	bindUiActions: function() {

		$(window).scroll(function(){
			Generator.changeNavbarBackgroundScroll();
		});

		$("#register").click(function(){
			Generator.showRegisterUserForm();
		});

		$("#nav_home").click(function(){
			Generator.homePage();
		});

		$("#nav_service_requests").click(function(){
			Generator.viewServiceRequests();
		});

		$("#create_request").click(function(){
			Generator.createRequest();
		});

		$("#view_request_link").click(function(){
			Generator.viewRequest();
		});

		$("#home_view_requests").click(function(){
			Generator.viewRequestsFromHomePage();
		});

		$("#home_create_request").click(function(){
			Generator.createRequestFromHomePage();
		});

		$("#create_new_request").click(function(){
			Generator.createNewRequest();
		});

		$(".service_request").click(function(){
			var id = $(this).data("request_id");
			Generator.viewServiceRequest(id);
		});

		$("#edit_request").click(function(){
			Generator.editRequest();
		});

		$(".word").click(function(){
			Generator.exportRequest("word");
		});

		$(".excel").click(function(){
			Generator.exportRequest("excel");
		});

		$("#nav_button").click(function() {
			Generator.changeNavbarBackgroundButtonClick();
		});

		$('.navbar_link').click(function() {
			Generator.collapseNav();
		});

		$("#service_form").submit(function(event) {
			Generator.submitRequest(event);
			$("#view_request").fadeOut(300, "swing");
		});

	},

	animateHomePage: function () {
		$("#index_logo").fadeIn(1000, "swing", function () {
  			$("#index_h1").fadeIn(1000, "swing", function () {
  				$("#fadebg").css("opacity", "0");
  				if (loggedIn == true) {
  					$("#nav_service_requests, #nav_logout").css({"display": "initial"});
            		$(".nav>li>a").css({"display": "block"});
			 		$("#logged_in_menu").fadeIn(1000, "swing");
			 	} else {
			 		$("#login_section").fadeIn(1000, "swing");
			 	}
  			});
  		});
	},

	showRegisterUserForm: function() {
		$("#index_logo svg").css({"width": "100px", "height": "100px"});
		$("#login_section").fadeOut(500, "swing", function () {
			$("#register_section").fadeIn(500, "swing");
		});
	},

	homePage: function() {
		$("#register_section, #view_request, #service_request_form, #service_request_success, #logged_in_menu, #service_requests").fadeOut(100, "swing");
    	$("#index_main").css({"margin-top": "10vh"});
		$("#index_logo svg").css({"width": "300px", "height": "40vh"});
    	$("#service_request_title").html("New Service Request");
    	$("#type").val("New");
		setTimeout(function() {
			$((loggedIn == true ? "#logged_in_menu" : "#index_h1, #login_section")).fadeIn(500, "swing"); }, 500
    	);
	},

	viewServiceRequests: function() {
    	$("#index_h1").hide();
    	$("#register_section, #view_request, #service_request_form, #service_request_success, #logged_in_menu").fadeOut(300, "swing", function () {
      		$("#index_main").css({"margin-top": "20px"});
      		$("#index_logo svg").css({"width": "100px", "height": "100px"});
      		$("#service_requests").fadeIn(500, "swing", function () {
        		$("#service_request_title").html("New Service Request");
        		$("#type").val("New");
      		});
      		$("#service_requests").load("assets/php/view_requests.php").css({"text-align": "center", "padding": "8px"});
      		setTimeout(function() {
        		$(".service_request").click(function(){
					var id = $(this).data("request_id");
					Generator.viewServiceRequest(id);
				});
      		}, 100);
    	});
	},

	logOutUser: function() {
		$("#nav_service_requests, #nav_logout").css({"display": "none"});
		$("#register_section, #view_request, #service_request_form, #service_request_success, #logged_in_menu, #service_requests").fadeOut(100, "swing");
        $("#index_main").css({"margin-top": "10vh"}); 
		$("#index_logo svg").css({"width": "300px", "height": "40vh"});
		setTimeout(function() {$("#index_h1, #login_section").fadeIn(500, "swing"); }, 500);
	},

	createRequest: function() {
		FormValidation.resetForm();
  		$("#service_request_success").fadeOut(300, "swing", function () {
    		$("#service_request_form").fadeIn(300, "swing");
    	});
	},

	viewRequest: function() {
  		$("#service_request_success").fadeOut(300, "swing", function () {
    		$("#view_request").fadeIn(300, "swing");
    	});
	},

	viewRequestsFromHomePage: function() {
  		$("#logged_in_menu").fadeOut(300, "swing", function () {
    		$("#index_main").css({"margin-top": "20px"});
			$("#index_logo svg").css({"width": "100px", "height": "100px"});
			$("#index_h1").hide();
			$("#login_section").fadeOut(500, "swing");
			$("#service_requests").fadeIn(500, "swing");
    	});
      	$("#service_requests").load("assets/php/view_requests.php").css({"text-align": "center", "padding": "8px"});
      	setTimeout(function() {
        	$(".service_request").click(function(){
				var id = $(this).data("request_id");
				Generator.viewServiceRequest(id);
			});
      	}, 100);
	},

	createRequestFromHomePage: function() {
		FormValidation.resetForm();
		$("#logged_in_menu").fadeOut(300, "swing", function () {
    		$("#index_main").css({"margin-top": "20px"});
			$("#index_logo svg").css({"width": "100px", "height": "100px"});
			$("#index_h1").hide();
			$("#login_section").fadeOut(500, "swing");
			$("#service_request_form").fadeIn(500, "swing");
  		});
	},

	createNewRequest: function() {
		FormValidation.resetForm();
		$("#view_request").fadeOut(300, "swing", function () {
  			$("#service_request_form").fadeIn(300, "swing");
  		});
	},

	viewServiceRequest: function(id) {
		var serviceOrderID = id;
    	$("#view_request_exports").data('request_id', serviceOrderID);
		$.ajax({    
        	type: "POST",
        	data:{
        		"service_order_id": serviceOrderID
        	},
        	url: "assets/php/view_request.php",             
        	dataType: "json",    
        	success: function(response){
            	Generator.prepareRequest(response, serviceOrderID);
        		var type = $("#type").val();
        		if (type != "Edit") {
        			$("#service_requests").fadeOut(300, "swing", function () {
	    				$("#view_request").fadeIn(300, "swing");
	    			});
        		}
        	}
    	});
	},

	editRequest: function() {
    	$("#service_request_title").html("Edit Service Request");
    	$("#type").val("Edit");
    	$("#id").val($("#request_id").html());

    	var agencyName = $("#request_agency_name").html();
    	$("option[value='" + agencyName + "']").attr('selected','selected');

    	$("#project_name").val($("#request_project_name").html());

    	var budget = $("#request_budget").html();
    	budget = budget.substr(1);
    	$("#budget").val(budget);

    	$("#primary_sales_contact").val($("#request_primary_sales_contact").html());

    	$("#customer_contact").val($("#request_customer_contact").html());

    	$("input[name='supplier_customers'][value='" + $("#request_customers").html() + "']").prop("checked",true);

	    var hqLocation = $("#request_hq_location").html();
	    var locationCut = hqLocation.indexOf(',');
	    var city = hqLocation.substring(0, locationCut);
	    var state = hqLocation.substring(locationCut + 2, hqLocation.length);
	    $("#hq_city").val(city);
	    $("option[value='" + state + "']").attr('selected','selected');

	    $("input[name='supplier_customers'][value='" + $("#request_customers").html() + "']").prop("checked",true);

	    $("input[name='outside_entity'][value='" + $("#request_outside_entity").html() + "']").prop("checked",true);

	    $("input[name='foreign_currency'][value='" + $("#request_foreign_currency").html() + "']").prop("checked",true);

	    $("input[name='network_diagram'][value='" + $("#request_network_diagram").html() + "']").prop("checked",true);

	    $("input[name='backup_diverse'][value='" + $("#request_backup_diverse").html() + "']").prop("checked",true);

	    $("input[name='voice'][value='" + $("#request_voice").html() + "']").prop("checked",true);

		$("#view_request").fadeOut(300, "swing", function () {
  			$("#service_request_form").fadeIn(300, "swing");
  		});
  		$('#service_request_submit').prop('disabled', false);
	},

	exportRequest: function(type) {
		var exportType = type;
    	var id = $("#request_id").html();
    	window.location.href = "assets/php/export.php?type=" + exportType + "&id=" + id;
	},

	changeNavbarBackgroundScroll: function() {
		scrollTop = $(window).scrollTop();
		if (scrollTop >= 20) {
			$('#navbar').css({
		    	"background-color": "black",
		    	"border-bottom": "2px solid white",
			});
		} else {
			$('#navbar').css({
		    	"background-color": "transparent",
		    	"border-bottom": "0px solid white",
			});
		}
	},

	changeNavbarBackgroundButtonClick: function() {
		if (navClicked == false) {
			navClicked = true;
			$("#navbar").css({"background-color": "black"});
		} else {
			navClicked = false;
			$("#navbar").css({"border": "0px solid white"});
			setTimeout(function(){ $("#navbar").css({"background-color": "transparent"}); }, 200);
		}
	},

	collapseNav: function() {
		$('#myNavbar').removeClass('navbar-collapse collapse in').addClass('navbar-collapse collapse');
		navClicked = false;
		$("#navbar").css({"background-color": "transparent"});
	},

	logIn: function(response) {
		$("#welcome_user").html("Welcome " + response);
		$("#nav_service_requests, #nav_logout").css({"display": "block"});
		$("#logged_in_navlinks").css({"margin-top": "20px"});
		$("#index_h1").hide();
		$("#login_section").fadeOut(500, "swing", function () {
			$("#logged_in_menu").fadeIn(500, "swing");
		}); 
	},

	registerUser: function(response) {
		if (response == "New user created successfully") {
			$("#index_logo svg").css({"width": "300px", "height": "300px"});
	  		$("#register_section").fadeOut(200, "swing", function () {
	    		$("#login_section").fadeIn(500, "swing");
	    	});
	    }
	},

	submitRequest: function(event) {
		event.preventDefault();
	  	var requestId = $("#id").val();
	  	$("#request_success_exports").data('request_id', requestId);
	  	var data = $('#service_form').serialize();
	  	$.ajax({    
        	type: "POST",
        	data: data,
        	url: "assets/php/request_form.php",             
        	dataType: "json",     
        	success: function(response){ 
        		$("#submit_message").html(response.message);
        		$("#request_id").html(response.request_id);
        		$.ajax({    
		        	type: "POST",
		        	data:{
		        		"service_order_id": response.request_id
		        	},
		        	url: "assets/php/view_request.php",             
		        	dataType: "json",    
		        	success: function(requestInfo){
		        		Generator.prepareRequest(requestInfo, response.request_id);
		        	}
        		});
        		if (response.message == "New service request<br>created successfully!" || response.message == "Service request<br>updated successfully!") {

        			$("#service_request_form").fadeOut(300, "swing", 
  						function () {
  							$("#service_request_title").html("New Service Request");
    						$("#service_request_success").fadeIn(300, "swing");
    						$("#type").val("New");
    				});
        		}
        	}
    	});
	},

	prepareRequest: function(response, id) {
		$("#request_id").html(id);
		var logoSrc = response.agency_name.toLowerCase().replace(/ /g, "_");
		$("#request_agency_logo").attr("src","../assets/images/" + logoSrc + "_logo.svg");
		$("#request_agency_name").html(response.agency_name);
		$("#request_project_name").html(response.project_name);
		$("#request_created_date").html(response.created_at);
		$("#request_budget").html(response.budget);
		$("#request_primary_sales_contact").html(response.primary_sales_contact);
		$("#request_customer_contact").html(response.customer_contact);
		$("#request_customers").html(response.customers);
		$("#request_hq_location").html(response.hq_location);
		$("#request_outside_entity").html(response.outside_entity);
		$("#request_foreign_currency").html(response.foreign_currency);
		$("#request_network_diagram").html(response.network_diagram);
		$("#request_backup_diverse").html(response.backup_diverse);
		$("#request_voice").html(response.voice);
	},

};