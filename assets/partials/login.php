<div class="row">
  <h1 id="index_h1">Service Request Generator</h1>
  <div class="col" id="login_section">
    <h3>Log-In</h3>
    <form class="form" name="login_form" id="login_form">
      <input type="text" name="username" id="username" class="form-control" placeholder="Username">
      <input type="password" name="password" id="password" class="form-control" placeholder="Password">
      <input type="submit" id="login_submit" class="form-control submit" value="Submit">
    </form>
    <p>Not registered for this generator?  <a id="register">Register here</a></p>
  </div>
</div>

<div class="modal fade" id="login-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <p id="alert-message"></p>
        <button type="button" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>