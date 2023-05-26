 <?php
	error_reporting(0);
	session_start();
	if (strlen($_SESSION['verify']) ==0) {
	  header('location:../403.php');
	} else {
	include("include/conn.php");
	include("include/header.php");
	include("include/function.php");
	
	if (isset($_POST['verify'])) {
		$verificationCode = $_SESSION['verify'];
		$code = $_POST['code'];

		if (empty($code)) {
			$_SESSION['verify'] = null;
			$_SESSION['error'] = "Empty code.";
			header("Location: ../forgot-password.php");
			exit();
		}

		if (password_verify($code,$verificationCode)) {
			$_SESSION['change-password'] = $_SESSION['email'];
			$_SESSION['email'] = null;
			header("Location: change-password.php");
			exit();
		} else {
			$_SESSION['verify'] = null;
			$_SESSION['error'] = "Invalid code.";
			header("Location: ../forgot-password.php");
			exit();
		}
	}


	?>
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
									  <h2 class="font-weight-bolder">Account Recovery</h2>
									</div>
									<form method="POST" class="needs-validation">
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
										<p>An email with a verification code was just sent to your email.</p>
										<div class="mb-3">
										 <div class="input-group input-group-merge input-group-alternative">
											<div class="input-group-prepend">
											  <span class="input-group-text"><i class=" "></i></span>
											</div>
											<input class="form-control" placeholder="Enter 6 digit Code" id="code" name="code" aria-describedby="inputGroupPrepend1" type="tel" maxlength='6' title="Enter Code" oninvalid="this.setCustomValidity('Please enter the 6 digit code.')" oninput="setCustomValidity('')" required>
										  </div>
										</div>
										<div class="text-right">
										  <button type="button" onclick="window.location.href='../'"  class="btn  btn-defualt w-60 mt-0 mb-0 ">Cancel</button>
										  <button type="submit" name="verify" value="verify"class="verify code btn  btn-primary w-60 mt-0 mb-0 ">Verify</button>
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
			 <?php
			  ?>
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
	</body>
	<?php
	//}
	?>
	<script>
					//Responsive Checker for code
					const checkCode = document.getElementById('code');
					checkCode.addEventListener('change', () => {
					  const otpRegex = /^\d+$/;
					  if (!otpRegex.test(checkCode.value)){
						$("#code").css({ 
							"border" :"1px solid red",
							"color" :"red",
						});
						$("#code").fadeIn("slow");
						$("#code").focus();
						return false;
					  }else{
						  $("#code").css({ 
							"border" :"",
							"color" :"#000",
						});
						$("#code").fadeIn("slow");
					  }
					});
		//ONCLICK 
			$(document).on("click", ".verify", function() {
			  var otpRegex = /^\d+$/; // Only accepts numeric input
			  // Check Input
			  var code = document.getElementById("code").value.trim();
			  if (code === "") {
				$("#code").css({
				  "border": "1px solid red",
				});
				$("#code").fadeIn("slow");
				$("#code").focus();
				return false;
			  }
			  if (!otpRegex.test(code)) {
				$("#code").css({
				  "border": "1px solid red",
				  "color": "red",
				});
				$("#code").fadeIn("slow");
				$("#code").focus();
				return false;
			  }
			});
	</script>
	<script>
		$('.btnlogin').on('click', function() {
		  var $this = $(this);
		  $this.button('loading');
		  setTimeout(function() {
			$this.button('reset');
		  }, 8000);
		});
	</script>
 </html>
<?php }?>