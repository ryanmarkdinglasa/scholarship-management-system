 <?php
	session_start();
	error_reporting(0);
	include("include/conn.php");
	include("include/header.php");
	include("include/function.php");
	
	if(isset($_POST['search'])){
		$email=trim($_POST['email']);
		if(!empty($email)){
			$user=getrecord('user',['username'],[$email]);
			if(empty($user)){
				$_SESSION['error'] = 'This email is not connected to any account.';
				header("Location: forgot-password.php"); // redirect back to index page if email sending fails
				exit();
			}
			$otp=generateOTP();
			$code=password_hash($otp,PASSWORD_DEFAULT);
			$created_on=date('Y-m-d H:i:s');
			$subject="ISKALAR One-time verification code";
			$message="\nHello ".strtoupper($user['firstname'])."
			\nYou are receiving this email because a request was made for a one-time code that can be used for authentication.
			\nPlease enter the following code for verification: ".$otp."
			\n
			\n
			\n
			\n
			\n
			\nThis is an automatically generated message. Please do not reply.";
			if(send_email($email,$subject,$message)){
				$_SESSION['verify'] = $code;
				$_SESSION['email'] = $email;
				header("Location: verify/");
				exit();
			} else {
				$_SESSION['error'] = 'Something went wrong while sending the OTP to your email.';
				header("Location: forgot-password.php"); // redirect back to index page if email sending fails
				exit();
			}
		}else{
			$_SESSION['error']='Email field is required to search your account.';
			header("Location: forgot-password.php"); // redirect back to index page if email sending fails
			exit();
		}
	}//search and send otp
	
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
									  <h2 class="font-weight-bolder">Find your account</h2>
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
										<p>Please enter your email to search for your account.</p>
										<div class="mb-3">
										 <div class="input-group input-group-merge input-group-alternative">
											<div class="input-group-prepend">
											  <span class="input-group-text"><i class="fas fa-envelope "></i></span>
											</div>
											<input class="form-control" placeholder="Email Address" id="email" name="email" id="validationDefaultUsername" aria-describedby="inputGroupPrepend1" type="text" title="Enter Email" oninvalid="this.setCustomValidity('Please enter your email.')" oninput="setCustomValidity('')" required>
										  </div>
										</div>
										<div class="text-right">
										  <button type="button" onclick="window.location.href='./'"  class="btn  btn-defualt w-60 mt-0 mb-0 ">Cancel</button>
										  <button type="submit" name="search" value="search"class="search btn  btn-primary w-60 mt-0 mb-0 ">Search</button>
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
			  //include("include/footer.php");
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
	<script type="text/javascript">
    document.getElementById("close_direct").onclick = function() {
      location.href = "login";
    };
	</script>
	<script>
					//Responsive Checker for email
					const checkEmail = document.getElementById('email');
					checkEmail.addEventListener('change', () => {
					  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
					  if (!emailRegex.test(checkEmail.value)){
						$("#email").css({ 
							"border" :"1px solid red",
							"color" :"red",
						});
						$("#email").fadeIn("slow");
						$("#email").focus();
						return false;
					  }else{
						  $("#email").css({ 
							"border" :"",
							"color" :"#000",
						});
						$("#email").fadeIn("slow");
					  }
					});
					//ONCLICK 
					$(document).on("click", ".search",function(){
						var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
						//check Email Input
						var email=document.getElementById("email").value.trim();
						if (email==='') {
							 $("#email").css({ 
								"border" :"1px solid red",
							});
							$("#email").fadeIn("slow");
							$("#email").focus();
						   return false;
						}
						if(!emailRegex.test(email)){
							 $("#email").css({ 
								"border" :"1px solid red",
								"color" :"red",
							});
							$("#email").fadeIn("slow");
							$("#email").focus();
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
<?php ?>