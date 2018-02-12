<!-- Main template for the Teletroid Service Generator -->

<?php
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Teletroid Service Request Generator</title>
    <link rel="icon" type="image/png" href="assets/images/teletroid_logo.png">
    <!-- Latest compiled and minified CSS -->
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  	<!-- jQuery library -->
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  	<!-- Latest compiled JavaScript -->
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
      // console.log(<?php echo json_encode($_SESSION) ?>);
      var loggedIn = <?php echo isset($_SESSION["username"]) ? "true" : "false"; ?>;
    </script>
    <script src="assets/javascript/Generator.class.js"></script>
    <script src="assets/javascript/FormValidation.class.js"></script>
    <script src="assets/javascript/User.class.js"></script>
  	<link rel="stylesheet" id="css" href="assets/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>

	  <div class="container">

      <div id="fadebg"></div>

      <!-- Navigation Bar -->
      <?php include "assets/partials/navbar.php"; ?>

      <!-- Main Logo SVG -->
      <?php include "assets/partials/logo.php"; ?>

      <!-- Login Form -->
      <?php include "assets/partials/login.php"; ?>

      <!-- Logged In Menu -->
      <?php include "assets/partials/logged_in_menu.php"; ?>
      
      <!-- Register Form -->
      <?php include "assets/partials/register.php"; ?>

      <!-- Service Request Form -->
      <?php include "assets/partials/service_request_form.php"; ?>

      <!-- Service Request Success Page (Created/Updated) -->
      <?php include "assets/partials/service_request_success.php"; ?>
      
      <!-- View Single Request Page -->
      <?php include "assets/partials/view_request.php"; ?>

      <!-- View Requests Page -->
      <?php include "assets/partials/view_requests.php"; ?>

    </div>

    <script>
      Generator.init();
      FormValidation.init();
      User.init();
    </script>
  </body>
</html>