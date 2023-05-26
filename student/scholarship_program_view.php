<?php
	error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "Scholarship";
	$parentpage_link = "#";
	$page=$currentpage = "Scholarship Program";
	$childpage = "scholarship_program";

	$row=$check_id=getrecord('scholarship_program',['id'],[$_GET['id']]);
	if(empty($check_id)){
		header('location:404.php?id_error');
		exit();
	}

	//UPDATE PROFILE INFO
	if (isset($_POST['update-sp'])) {
		$password_valid =  $_POST["password_valid"];
		$email = trim($_POST['email']);
		$name = trim($_POST['name']);
		$description = trim($_POST['description']);
		$contactno = $_POST['contactno'];
		$address = trim($_POST['address']);
		$numbersRegex = '/^[0-9]+$/';
		$digitRegex = '/^\d{10}$/';
		$address_pattern = "/^[a-zA-Z0-9\s\-\.,#]+$/";
	
		// Validate parameters
		if (empty($email) || empty($name) || empty($description) || empty($address) || empty($contactno)) {
			$_SESSION['error'] = "Please fill in all fields.";
			header("Location:scholarship_program.php");
			exit();
		}
	
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['error'] = "Invalid email address.";
			header("Location:scholarship_program.php");
			exit();
		}
	
		if (!preg_match($numbersRegex, $contactno) || !preg_match($digitRegex, $contactno) || substr($contactno, 0, 1) !== "9"  || strlen($contactno)!= 10) {
			$_SESSION['error'] = "Invalid mobile number.";
			header("Location:scholarship_program.php");
			exit();
		}
	
		if (!preg_match('/^[a-zA-Z\s]+$/', $name)|| !preg_match('/^[a-zA-Z\s]+$/', $description)) {
			$_SESSION['error'] = "Name should only contain letters.";
			header("Location:scholarship_program.php");
			exit();
		}
	
		if (!preg_match($address_pattern, $address)) {
			$_SESSION['error'] = "Invalid address, special characters are not allowed.";
			header("Location:scholarship_program.php");
			exit();
		}
	
		$check_email = getrecord('scholarship_program', ['email'], [$email]);
		if (!empty($check_email) && ($check_email['email']!=$row['email'])) {
			$_SESSION['error'] = "Email is already taken.";
			header("Location:scholarship_program.php");
			exit();
		}
	
		// Validate password
		if (!password_verify($password_valid, $user["password"])) {
			$_SESSION['error'] = "Invalid password.";
			header("location:scholarship_program.php");
			exit();
		}
	
		// Update SP information
		$stmt = $con->prepare("UPDATE `scholarship_program` SET 
									`name`=?,
                                    `description`=?,
                                    `email`=?,
                                    `phone_no`=?,
                                    `address`=?
                                    WHERE `id` = ?");
		$result= $stmt->execute([$name,$description, $email, $contactno, $address,$user['staff_sp']]);
		//$result = updaterecord('scholarship_program', ['id', 'name', 'description', 'email', 'contactno', 'address'], [1, $name, $description, $email, $contactno, $address]);
		if (!$result) {
			$_SESSION['error'] = "Something went wrong in updating scholarship program information.";
			header("Location:scholarship_program.php");
			exit();
		}
	
		$_SESSION['success'] = "Scholarship program information has been updated.";
		header("Location:scholarship_program.php");
		exit();
	}//UPDATE SP INFO
 
	//UPDATE PROFILE PHOTO
	if (isset($_POST['change-photo']) && isset($_FILES['fileToUpload'])) {
		define('KB', 1024);
		define('MB', 1048576);
		define('GB', 1073741824);
		define('TB', 1099511627776);
		$imgfile = $_FILES["fileToUpload"]["name"];
		$target_dir = "img/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
		// // get the image extension
		if ($imgfile === "" || $imgfile===NULL ) {
			$_SESSION['error']='Please select and image.';
			header("location:scholarship_program.php");
			exit();
		}
		$extension = substr($imgfile, strlen($imgfile) - 4, strlen($imgfile));
		if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
			$_SESSION['error']='The only allowed files are JPG, PNG and JPEG.';
			header("location:scholarship_program.php");
			exit();
		} else {
			if ($_FILES["fileToUpload"]["size"] > 2 * MB){
				$_SESSION['error']='Image file size is more than 2 MB';
				header("location:scholarship_program.php");
				exit();
			}
			else{
			//rename the image file
			$imgnewfile = md5($imgfile) . uniqid() . $extension;
			// Code for move image into directory
			move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "img/" . $imgnewfile);
			// Query for insertion data into database
			try{
				$stmt = $con->prepare("UPDATE `scholarship_program` SET `img`=? WHERE `id`=?");
				if($stmt->execute([$imgnewfile,$row['id']])){
					header("location:scholarship_program.php");
					exit();
				}
			}catch(Exception $e){
				$_SESSION['error']='Sorry, the photo failed to change. try again.';
				header("location:scholarship_program.php");
				exit();
			} 
		  }
		}
	}//UPDATE PROFILE PHOTO
?>
	<?php
		include("include/header.php");
	?>
	<script>
		function userAvailability() {
		  $("#loaderIcon").show();
		  jQuery.ajax({
			url: "check_username.php",
			data: 'username=' + $("#username").val(),
			type: "POST",
			success: function(data) {
			  $("#user-availability-status1").html(data);
			  $("#loaderIcon").hide();
			},
			error: function() {}
		  });
		}
	  </script>
	<script type="text/javascript">
    function valid() {
      if (document.chngpwd.password.value == "") {
        //alert("Old password cannot be empty.");
        $("#current-password").css({ 
			"border" :"1px solid red",
			"color" :"red",
		});
		$("#current-password").fadeIn("slow");
		$("#current-password").focus();
        return false;
      } else if (document.chngpwd.newpassword.value == "") {
        alert("New Password cannot be empty.");
        //document.chngpwd.newpassword.focus();
		$("#new-password").css({ 
			"border" :"1px solid red",
			"color" :"red",
		});
		$("#new-password").fadeIn("slow");
		$("#new-password").focus();
        return false;
      } else if (document.chngpwd.confirmpassword.value == "") {
        //alert("Repeat your new password.");
        //document.chngpwd.confirmpassword.focus();
		$("#confirm-password").css({ 
			"border" :"1px solid red",
			"color" :"red",
		});
		$("#confirm-password").fadeIn("slow");
		$("#confirm-password").focus();
        return false;
      } else if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
        //alert("New passwords don't match.");
        //document.chngpwd.confirmpassword.focus();
		$("#confirm-password").css({ 
			"border" :"1px solid red",
			"color" :"red",
		});
		$("#confirm-password").fadeIn("slow");
		$("#confirm-password").focus();
        return false;
      }
      return true;
    }
	</script>
	<script>
		if (window.history.replaceState) {
		  window.history.replaceState(null, null, window.location.href);
		}
	</script>
	</head>
	<?php
		include("include/sidebar.php");
	?>
	<!-- Main content -->
	<div class="main-content" id="panel">
    <!-- Topnav -->
    <?php
    include("include/topnav.php"); //Edit topnav on this page
    ?>
    <!-- Header -->
	<?php if(isset($_SESSION['error'])){ ?>
        <div data-notify="container" class="alert alert-dismissible alert-danger alert-notify animated fadeInDown" role="alert" data-notify-position="top-center" style="display: inline-block; margin: 0px auto; position: fixed; transition: all 0.5s ease-in-out 0s; z-index: 1080; top: 15px; left: 0px; right: 0px; animation-iteration-count: 1;">
          <span class="alert-icon ni ni-bell-55" data-notify="icon"></span>
          <div class="alert-text" div=""> <span class="alert-title" data-notify="title"> Fail!</span>
            <span data-notify="message"><?php echo $_SESSION['error'];?></span>
          </div><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 10px; top: 5px; z-index: 1082;">
            <span aria-hidden="true">×</span></button>
        </div>
    <?php }  unset($_SESSION['error']); ?>
	<?php if(isset($_SESSION['success'])){ ?>
		<div data-notify="container" class="alert alert-dismissible alert-success alert-notify animated fadeInDown" role="alert" data-notify-position="top-center" style="display: inline-block; margin: 0px auto; position: fixed; transition: all 0.5s ease-in-out 0s; z-index: 1080; top: 15px; left: 0px; right: 0px; animation-iteration-count: 1;">
			<span class="alert-icon ni ni-bell-55" data-notify="icon"></span>
			<div class="alert-text" div=""> <span class="alert-title" data-notify="title"> Success!</span>
				<span data-notify="message"><?php echo $_SESSION['success'];?></span>
			</div><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 10px; top: 5px; z-index: 1082;">
			<span aria-hidden="true">×</span></button>
		</div>
	<?php }  unset($_SESSION['success']); ?>
    <!-- Header -->
   <?php include "include/breadcrumbs.php";?>
      <!-- Page content -->
      <div class="container-fluid mt--6">
        <div class="row">
          <div class="col-xl-4 order-xl-2">
            <div class="card card-header">

              <div class="row justify-content-center" >
                <div class="col-lg-3 order-lg-2">
                  <div class="card-profile-image" >
                    <?php $userphoto = $row['img'];
                    if ($userphoto == "" || $userphoto == "NULL") :
                    ?>
                      <img src="img/profile.png" class="rounded-circle" style="">
                    <?php else : ?>
                      <img src="../scholarship-provider/img/<?php echo $userphoto; ?>" class="rounded-circle" style="border-radius:50%;background:#f1f1f1;">
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">	
            </div>
			<br>
            <div class="card-body pt-0">
              <div class="row">
                <div class="col pb-0 pt-0">
                  	<div class="card-profile-stats d-flex justify-content-center pb-0 mt-0">
					  <h2 class="form-control-label" for="input-last-name"><?php
					  $name = isset($row['name']) ? htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') : '';
					  echo  $name;
					  ?></h2>
					</div>
					<div class="card-profile-stats d-flex justify-content-center" style="margin-top:-20px;">
					  	<label class="form-control-label heading-small text-muted text-center" for="input-last-name"><?php
						$description = isset($row['description']) ? htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') : '';
						echo $description;?></label>
					</div>
					<div class="card-profile-stats d-flex justify-content-center" style="margin-top:-20px;">
						<button onclick="window.location.href='<?php echo 'scholar.php?id='.$row['id'];?>'" class="btn btn-primary text-white">View Scholars</button>
					</div>
                </div>
              </div>
      
                <!-- Multiple -->
       
                  
         

           
            </div>
          </div>
        
        </div>
        <div class="col-xl-8 order-xl-1">
          <div class="row">
          </div>
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">Scholarship Program  </h3>
                </div>
                <div class="col-4 text-right">
                </div>
              </div>
            </div>
            <div class="card-body">

                  <h6 class="heading-small text-muted mb-4">Scholarship Program Information</h6>
                  <div class="pl-lg-4">
                    
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="form-control-label" for="input-first-name">Name</label>
                          <div class="input-group input-group-merge">
							<?php 
							$name = isset($row['name']) ? htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') : '';
							?>
                            <input type="text" id="name" name="name" class="form-control" placeholder="First name" value="<?php echo $name; ?>" readonly>
                          </div>
                        </div>
                      </div>
                    </div>
					<div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="form-control-label" for="input-first-name">Description</label>
                          <div class="input-group input-group-merge">
							<?php 
							$description = isset($row['description']) ? htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') : '';
							?>
                            <input type="text" id="description" name="description" class="form-control" placeholder="First name" value="<?php echo $description; ?>" readonly>
                          </div>
                        </div>
                      </div>
                    </div>
					<div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="form-control-label" for="input-username">Email</label>
                          <div class="input-group input-group-merge">
							<?php 
							$email = isset($row['email']) ? htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') : '';
							?>
                            <input type="text"  id="email" name="email" class="form-control" placeholder="Enter email" value="<?php echo  $email; ?>" readonly>
                          </div>
                          <span id="user-availability-status1"></span>
                        </div>
                      </div>
					  <div class="col-lg-6">
                        <div class="form-group">
                          <label class="form-control-label" for="input-last-name">Mobile No.</label>
                          <div class="input-group input-group-merge">
							<?php 
							$contactno = isset($row['phone_no']) ? htmlspecialchars($row['phone_no'], ENT_QUOTES, 'UTF-8') : '';
							?>
                            <input type="tel" id="contactno" name="contactno" maxlength="10" class="form-control" placeholder="Mobile No." value="<?php echo '+63 '.$contactno; ?>" readonly>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr class="my-4" />
                  <!-- Address -->
                  <h6 class="heading-small text-muted mb-4">Contact Information</h6>
                  <div class="pl-lg-4">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="form-control-label" for="input-address">Current address</label>
							<?php 
							$address = isset($row['address']) ? htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') : '';
							?>
                          <input id="input-address" name="address" class="form-control" placeholder="current address" value="<?php echo $address; ?>" type="text" readonly>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- batas modal form validasi edit profil -->
            </div>
          </div>
        </div>
      </div>
				<?php
					include("include/footer.php"); //Edit topnav on this page
				?>
				<script>
					$('.select2').select2();
				</script>
				<script src="js/app.js"></script>
				<script>
					$.fn.datepicker.defaults.format = "dd/mm/yyyy";
					$('.date_birth').datepicker({
					  format: "yyyy-mm-dd",
					  language: "id",
					  todayHighlight: true
					});
				</script>
				<script>
					$(document).on('change', '.custom-file-input', function(event) {
						$(this).next('.custom-file-label').html(event.target.files[0].name);
					})
					
					//Responsive Checker for email
					const checkEmail = document.getElementById('username');
					checkEmail.addEventListener('change', () => {
					  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
					  if (!emailRegex.test(checkEmail.value)) {
						$("#username").css({ 
							"border" :"1px solid red",
							"color" :"red",
						});
						$("#username").fadeIn("slow");
						$("#username").focus();
						return false;
					  }else{
						  $("#username").css({ 
							"border" :"",
							"color" :"#000",
						});
						$("#username").fadeIn("slow");
					  }
					});
					//Responsive Checker for mobile number
					const checkMobileNo = document.getElementById('contactno');
					checkMobileNo.addEventListener('change', () => {
					  const numbersRegex = /^[0-9]+$/;
					  const digitRegex = /^\d{10}$/;
					  if (!numbersRegex.test(checkMobileNo.value) || !digitRegex.test(checkMobileNo.value) || checkMobileNo.value.charAt(0) !== "9") {
						$("#contactno").css({ 
						  "border" :"1px solid red",
						  "color" :"red",
						});
						$("#contactno").fadeIn("slow");
						$("#contactno").focus();
					  } else {
						$("#contactno").css({ 
						  "border" :"",
						  "color" :"#000",
						});
						$("#contactno").fadeIn("slow");
					  }
					});
				</script>
				<script>
					$(document).on("click", ".update-profile",function(){
						var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
						var lettersRegex = /^[a-zA-Z\s]+$/;
						var numbersRegex = /^[0-9]+$/;
						var specialCharactersRegex = /^[a-zA-Z0-9\s]*$/;
						var digitRegex = /^\d{10}$/;	
						var zipcodeRegex = /^\d{4}$/;	
						//check Email Input
						var username=document.getElementById("username").value.trim();
						if (username==='') {
							 $("#username").css({ 
								"border" :"1px solid red",
							});
							$("#username").fadeIn("slow");
							$("#username").focus();
						   return false;
						}
						if(!emailRegex.test(username)){
							 $("#username").css({ 
								"border" :"1px solid red",
								"color" :"red",
							});
							$("#username").fadeIn("slow");
							$("#username").focus();
							return false;
						}
						//check firstname input if its blank or contains numbers
						var firstname=document.getElementById("firstname").value.trim();
						if (firstname==='') {
							 $("#firstname").css({ 
								"border" :"1px solid red",
							});
							$("#firstname").fadeIn("slow");
							$("#firstname").focus();
						   return false;
						}
						if (!lettersRegex.test(firstname)) {
							 $("#firstname").css({ 
								"border" :"1px solid red",
								"color" :"red",
							});
							$("#firstname").fadeIn("slow");
							$("#firstname").focus();
						   return false;
						}
						
						//check firstname input if its blank or contains numbers
						var middlename=document.getElementById("middlename").value.trim();
						if (middlename!=='' && !lettersRegex.test(middlename)) {
							 $("#middlename").css({ 
								"border" :"1px solid red",
								"color"  :"red",
							});
							$("#middlename").fadeIn("slow");
							$("#middlename").focus();
						   return false;
						}
						
						//check lastname input if its blank or contains numbers
						var lastname=document.getElementById("lastname").value.trim();
						if (lastname==='') {
							 $("#lastname").css({ 
								"border" :"1px solid red",
							});
							$("#lastname").fadeIn("slow");
							$("#lastname").focus();
						   return false;
						}
						if (!lettersRegex.test(lastname)) {
							 $("#lastname").css({ 
								"border" :"1px solid red",
								"color" :"red",
							});
							$("#lastname").fadeIn("slow");
							$("#lastname").focus();
						   return false;
						}

						//check contact_no input if its blank or contains letters or special characters
						var contactno=document.getElementById("contactno").value.trim();
						if (contactno==='') {
							 $("#contactno").css({ 
								"border" :"1px solid red",
							});
							$("#contactno").fadeIn("slow");
							$("#contactno").focus();
						   return false;
						}
						if (contactno.charAt(0) !== "9") {
							 $("#contactno").css({ 
								"border" :"1px solid red",
								"color" :"red",
							});
							$("#contactno").fadeIn("slow");
							$("#contactno").focus();
						   return false;
						}
						if (!numbersRegex.test(contactno)) {
							 $("#contactno").css({ 
								"border" :"1px solid red",
								"color" :"red",
							});
							$("#contactno").fadeIn("slow");
							$("#contactno").focus();
						   return false;
						}
						if (!digitRegex.test(contactno)) {
							 $("#contactno").css({ 
								"border" :"1px solid red",
								"color" :"red",
							});
							$("#contactno").fadeIn("slow");
							$("#contactno").focus();
						   return false;
						}
						//check birthdate input if its blank 
						var birthdate=document.getElementById("birthdate").value.trim();
						if (birthdate==='') {
							 $("#birthdate").css({ 
								"border" :"1px solid red",
							});
							$("#birthdate").fadeIn("slow");
							$("#birthdate").focus();
						   return false;
						}
						// Check if the age is above 15
						const today = new Date();
						const minAgeDate = new Date(today.getFullYear() - 15, today.getMonth(), today.getDate());
						const selectedDate = new Date(birthdate.slice(0, 4), birthdate.slice(5, 7) - 1, birthdate.slice(8, 10));
						if (selectedDate > minAgeDate) {
						  $("#birthdate").css({
							"border": "1px solid red",
						  });
						  $("#birthdate").fadeIn("slow");
						  $("#birthdate").focus();
						  return false;
						}
				</script>
			</div>
		</div>
	</body>
</html>
