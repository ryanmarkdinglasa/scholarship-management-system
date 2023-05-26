 <?php
	session_start();
	error_reporting(0);
	include("include/conn.php");
	include("include/header.php");
	if(!isset($_SESSION['type']) || trim($_SESSION['type']) == ''){
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
									  <h2 class="font-weight-bolder">Sign In</h2>
									</div>
									<form action="sign-in.php" method="POST" class="needs-validation">
									<div class="card-body">
										<div class="mb-3">
										 <div class="input-group input-group-merge input-group-alternative">
											<div class="input-group-prepend">
											  <span class="input-group-text"><i class="fa fa-shield-check"></i></span>
											</div>
											<input class="w-10 input" type='tel' id='code' name='code' required>
										  </div>
										</div>
										</div>
										<div class="text-center">
										  <button type="submit" name="signin" value="signin"class="btn  btn-primary w-100 mt-0 mb-0">Sign in</button>
										</div>
									</div>
									</form>
									<div class="card-footer text-center pt-0 px-lg-2 px-1">
										<p class="mb-4 text-sm mx-auto">
											Don't have an account?
											<a href="register.php" class="text-primary text-gradient font-weight-bold">Sign up</a>
										</p>
										<center>
									<span class="text-center">
										<small> iskalar &copy; 2023</small>
									</span>
								</center>
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
		function showPass() {
		  var x = document.getElementById("password");
		  if (x.type === "password") {
			x.type = "text";
		  } else {
			x.type = "password";
		  }
		}

		$(".toggle-password").click(function() {
		  $(this).toggleClass("fa-eye fa-eye-slash");
		  var input = $($(this).attr("toggle"));
		  if (input.attr("type") == "password") {
			input.attr("type", "text");
		  } else {
			input.attr("type", "password");
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
<?php }
	else{
		header('location:'.$_SESSION['type'].'/');
		exit;
	}?>