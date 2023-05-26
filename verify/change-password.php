 <?php
	error_reporting(0);
	session_start();
	//include "include/function.php";
	if (strlen($_SESSION['change-password']) ==0) {
		header('location:../403.php');
	} else {
	include("include/conn.php");
	include("include/header.php");
	include("include/function.php");
	
	if(isset($_POST['save'])){
		$new_password=$_POST['new_password'];
		$confirm_password=$_POST['confirm_password'];
		if(empty($confirm_password)||empty($new_password)){
			$_SESSION['error']='All fields are required';
			header("location:change-password.php");
			exit();
		}
		if($confirm_password!==$new_password){
			$_SESSION['error']='Passwords doesn`t match.';
			header("location:change-password.php");
			exit();
		}
		$password=password_hash($new_password,PASSWORD_DEFAULT);
		$updated_on=date('Y-m-d H:i:s');
		$result=updaterecord('user',['username','password'],[$_SESSION['change-password'],$password]);
		if($result){
			$get_session=getrecord('user',['username'],[$_SESSION['change-password']]);
			
			$_SESSION['change-password']=null;
			$_SESSION[$get_session['type']]=$get_session['id'];
			// Log sign-in
			log_sign_in($get_session['username'],$user_type);
			header("location:../".$get_session['type']."/");
			exit();
		}else{
			$_SESSION['error']='Something went wrong in changing password.';
			header("location:change-password.php");
			exit();
		}
	}//search and send otp


	?>
	<script type="text/javascript">
     function validateForm() {
		const newPasswordInput = document.getElementById("new_password");
		const confirmNewPasswordInput = document.getElementById("confirm_password");
		const newPassword = newPasswordInput.value.trim();
		const confirmNewPassword = confirmNewPasswordInput.value.trim();

		if (newPassword === "") {
		  newPasswordInput.style.borderColor = "red";
		  newPasswordInput.style.color = "red";
		  newPasswordInput.focus();
		  return false;
		}

		if (confirmNewPassword === "") {
		  confirmNewPasswordInput.style.borderColor = "red";
		  confirmNewPasswordInput.style.color = "red";
		  confirmNewPasswordInput.focus();
		  return false;
		}

		if (newPassword !== confirmNewPassword) {
		  confirmNewPasswordInput.style.borderColor = "red";
		  confirmNewPasswordInput.style.color = "red";
		  confirmNewPasswordInput.focus();
		   
		  return false;
		}
    // Add additional validation here if needed
    return true;
  }
</script>

	</script>
	<style>
	   .content.cover {
		 border-radius: 20px;
	   }
	</style>
	</head>
	<body class="bg-primary">
	<!-- Main content -->
		<div class="main-content">
			<section class="py-2 pb-3 pt-6" >
				<div class="container mt-4 pb-3" >
					<div class="row justify-content-center" >
						<div class="col-lg-5 col-md-7" >
							<div class="card bg-secondary border-0 mb-0">
									<div class="card-header pb-0 text-start">
									  <h2 class="font-weight-bolder">Change Password</h2>
									</div>
									<form method="POST"  name="changepassword" onsubmit="return validateForm()" class="needs-validation">
									<div class="card-body">
										<?php
											if (isset($_SESSION['error'])) {
												echo '
												<div class="alert alert-danger alert-dismissible fade show" role="alert">
												<span class="alert-text"><i class="fas fa-exclamation-circle">&nbsp</i><strong>' .$_SESSION['error'].'</strong></span>
												<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												  <span aria-hidden="true">&times;</span>
												</button>
											  </div>';
												unset($_SESSION['error']);
											}
										?>
										<div class="mb-3">
											<label>New Password</label>
										 <div class="input-group input-group-merge input-group-alternative">
											<div class="input-group-prepend">
											  <span class="input-group-text"><i class=" "></i></span>
											</div>
											<input class="form-control" type="password" placeholder="Enter your new password" id="new_password" name="new_password" required>
										  </div>
										</div>
										<div class="mb-3">
											<label>Confirm Password</label>
										 <div class="input-group input-group-merge input-group-alternative">
											<div class="input-group-prepend">
											  <span class="input-group-text"><i class=" "></i></span>
											</div>
											<input class="form-control"type="password" placeholder="Confirm your new password" id="confirm_password" name="confirm_password" aria-describedby="inputGroupPrepend1" type="text"  title="Enter New Password" oninvalid="this.setCustomValidity('Please enter your new password.')" oninput="setCustomValidity('')" required>
										  </div>
										</div>
										<div class="text-right">
										  <button type="button" onclick="window.location.href='./'"  class="btn  btn-defualt w-60 mt-0 mb-0 ">Cancel</button>
										  <button type="submit" name="save" value="save" class="change-password btn  btn-primary w-60 mt-0 mb-0 ">Save</button>
										</div>
									</div>
									</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</body>
			 <!-- Core JS -->
			 <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
			 <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
			 <script src="assets/vendor/js-cookie/js.cookie.js"></script>
			 <script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
			 <script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
			 <!-- Optional JS -->
			 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAq_ljbjvx9Z6BGjTAwwxdaa-_n4Mr48-E&ver=3.19.17"></script>
			 <!-- Calender JS -->
			 <script src="assets/vendor/moment/min/moment.min.js"></script>
			 <script src="assets/vendor/fullcalendar/dist/fullcalendar.min.js"></script>
			 <script src="assets/vendor/fullcalendar/dist/locale/id.js"></script>
			 <script src="assets/js/argon.js?v=1.1.0"></script>
			</div>
		</div>
		<script>
			$('.btnlogin').on('click', function() {
			  var $this = $(this);
			  $this.button('loading');
			  setTimeout(function() {
				$this.button('reset');
			  }, 8000);
			});
		</script>
	</body>
 </html>
<?php }?>