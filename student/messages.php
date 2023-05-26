<?php
	error_reporting(E_ALL);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "";
	$parentpage_link = "#";
	$page=$currentpage = "Messages";
	$childpage = "messages";

	$row=$check_id=getrecord('scholarship_program',['id'],[$user['staff_sp']]);
	if(empty($check_id)){
		header('location:404.php');
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
          <div class="col-xl-4 order-xl-2" style="border:1px solid red;">
            <div class="card card-header" style="border:1px solid red;">
				<div class="row align-items-center">
					<div class="col-8">
						<h3 class="mb-0">Chat box  </h3>
					</div>
					<div class="col-4 text-right">
					</div>
				</div>
				<div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4" >
			</div>
			<style>
				.chat-photo{

				}
				.message{
					background:red;
					width:nonepx;
				}
			</style>
            <div class="card-body pt-0" style="border:1px solid red;">
				<div class="row align-items-center">
					<div class="col-12" style="border:1px solid red;">
					<div class="chat-photo">
					<?php
						$userphoto = isset($user['profileImage']) ? htmlspecialchars($user['profileImage'], ENT_QUOTES, 'UTF-8') : '';
                        if ($userphoto == "" || $userphoto == "NULL") :
                        ?>
                        <img src="img/profile.png" class="avatar rounded-circle mr-3">
                        <?php else : ?>
                        <img src="img/<?php echo $userphoto; ?>" class="avatar rounded-circle mr-3">
                        <?php endif; ?>
					</div>
					<div class="chat-name">
						<b>
							<?php 
							$firstname = isset($user['firstname']) ? htmlspecialchars($user['firstname'], ENT_QUOTES, 'UTF-8') : '';
							$lastname = isset($user['lastname']) ? htmlspecialchars($user['lastname'], ENT_QUOTES, 'UTF-8') : '';
							$name=short_text($firstname.' '.$lastname);
							echo $name;
							$today=date('Y-m-d H:i:s');
							echo '</b>';
							echo '<span><small>'.created_on($today).'</small></span>';
							?>
						
						</div>
					</div>
					<div class="col-4 text-right">
						option
					</div>
				</div>
            </div>
          </div>
        
        </div>
		<!--CHATBOX-->
        <div class="col-xl-8">
          <div class="row">
          </div>
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">Receiver Image and Name here </h3>
                </div>
                <div class="col-4 text-right">
                </div>
              </div>
            </div>
            <div class="card-body" style="border:1px solid red;">
            <form name="dosen" method="post">
				<div class="row">
					<div class="col " style="border:1px solid red;">
						<span class=""><p> receiver message<br></p></span>
					</div>
				</div>
				<div class="row">
					<div class="col text-right" style="border:1px solid red;">
						<span class=""><p> sender message<br></p></span>
					</div>
				</div>
				<textarea class="form-control " rows="3" resize="none">
				</textarea>
              </form>
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
				</script>
			</div>
		</div>
	</body>
</html>
