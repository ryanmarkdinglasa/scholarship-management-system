<?php
	error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
    date_default_timezone_set('Asia/Manila');
    $parentpage = "Account";
    $childpage = "scholarship_provider";
	//
	if (!isset($_GET['id'])) {
		header('location:404.php');
		exit();
	} else {
	$check_id=getrecord('user',['id'],[$_GET['id']]);
	if(empty($check_id)){
		header('location:404.php');
		exit();
	}	
	//
	$_SESSION['id']=$_GET['id'];
	//EDIT SCHOLARSHIP PROVIDER
	if(isset($_POST['edit'])){
		$id=trim($_POST['user_id']);
		$username=trim($_POST['username']);
		$pass=trim($_POST['password_valid']);
		$password=password_hash($pass, PASSWORD_DEFAULT);
		$firstname=trim($_POST['firstname']);
		$lastname=trim($_POST['lastname']);
		$position=trim($_POST['position']);
		$updated_on=date('Y-m-d H:i:s');
		//
		if(!password_verify($pass, $user['password'])){
			$_SESSION['error'] = "Invalid password.";
			header("Location:staff.php");
			exit();
		}
		if (empty($id) ||empty($username) || empty($firstname) || empty($lastname) || empty($position) || empty($password)){
			$_SESSION['error'] = "Please fill in all fields.";
			header("Location:staff.php");
			exit();
		}
		if (!preg_match('/^[a-zA-Z\s]+$/', $firstname)|| !preg_match('/^[a-zA-Z\s]+$/', $lastname) ) {
			$_SESSION['error'] = "Name should only contain letters.";
			header("Location:staff.php");
			exit();
		}
		
		//CHECK USERNAME IF THERE IS ANY DUPLICATION
		$check_username=getrecord('user',['username',],[$username]);
		if(!empty($check_username) && $check_username['username']!=$username){
			$_SESSION['error']='Email is already taken.';
			header("location:staff.php");
			exit();
		}
		
		$result=updaterecord('user',['id','username','password','firstname','lastname', 'updated_on'],[$id,$username,$password,$firstname,$lastname,$updated_on])
		&& updaterecord('staff',['username','position_id'],[$username,$position]);
		if($result){
			$_SESSION['success'] = "Staff Updated.";
			header("location:staff.php");
			exit();
		}else{
			$_SESSION['error'] = "Something went wrong updating staff.";
			header("location:staff.php");
			exit();
		}
		
	}

?>
    <?php
    include("include/header.php");
    ?>
    <script>
        function userAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "edit_acc_check_username.php",
                data: 'username=' + $("#username").val() + '&oldusername=' + $("#oldusername").val(),
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
        <?php
        include("include/topnav.php"); //Edit topnav on this page
        ?>
		<?php if(isset($_SESSION['success'])){ ?>
			<div data-notify="container" class="alert alert-dismissible alert-success alert-notify animated fadeInDown" role="alert" data-notify-position="top-center" style="display: inline-block; margin: 0px auto; position: fixed; transition: all 0.5s ease-in-out 0s; z-index: 1080; top: 15px; left: 0px; right: 0px; animation-iteration-count: 1;">
			  <span class="alert-icon ni ni-bell-55" data-notify="icon"></span>
			  <div class="alert-text" div=""> <span class="alert-title" data-notify="title"> Success!</span>
				<span data-notify="message"><?php echo $_SESSION['success'];?></span>
			  </div><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 10px; top: 5px; z-index: 1082;">
				<span aria-hidden="true">×</span></button>
			</div>
			<?php }  unset($_SESSION['success']); ?>
			<?php if(isset($_SESSION['error'])){ ?>
			<div data-notify="container" class="alert alert-dismissible alert-danger alert-notify animated fadeInDown" role="alert" data-notify-position="top-center" style="display: inline-block; margin: 0px auto; position: fixed; transition: all 0.5s ease-in-out 0s; z-index: 1080; top: 15px; left: 0px; right: 0px; animation-iteration-count: 1;">
			  <span class="alert-icon ni ni-bell-55" data-notify="icon"></span>
			  <div class="alert-text" div=""> <span class="alert-title" data-notify="title"> Fail!</span>
				<span data-notify="message"><?php echo $_SESSION['error'];?></span>
			  </div><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 10px; top: 5px; z-index: 1082;">
				<span aria-hidden="true">×</span></button>
			</div>
		<?php }  unset($_SESSION['error']); ?>
        <!-- Header -->
        <!-- Header & Breadcrumbs -->
        <div class="header bg-primary pb-6">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-center py-4">
                        <div class="col-lg-6 col-7">
                            <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                    <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i></a></li>
                                    <li class="breadcrumb-item"><a href="./">Dashboards</a></li>
                                    <li class="breadcrumb-item"><a href="./scholarship_provider.php">Scholarship Provider</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit Scholarship Provider
									</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-lg-6 col-5 text-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Batas Header & Breadcrumbs -->
        <!-- Page content -->
        <div class="container-fluid mt--6">
            <div class="card mb-4">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0">Scholarship Provider Form</h3>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <!-- Form groups used in grid -->
                    <?php
						$id = $_GET['id'];
						$query = "SELECT  
						`user`.`id` AS `user_id`,
						`user`.`firstname` AS `user_fname`,
						`user`.`middlename` AS `user_mname`,
						`user`.`lastname` AS `user_lname`,
						`user`.`username` AS `user_username`,
						`staff`.`id` AS `staff_id`,
                        `staff`.`sp_id` AS `staff_sp`,
						`staff`.`username` AS `staff_username`,
						`position`.`id` AS `position_id`,
						`position`.`position_name` AS `position_name`,
						`scholarship_program`.`id` AS `sp_id`,
						`scholarship_program`.`name` AS `sp_name`
						FROM `user`
						INNER JOIN `staff` ON `staff`.`username` = `user`.`username`
						INNER JOIN `position` ON `position`.`id`=`staff`.`position_id`						
						INNER JOIN `scholarship_program` ON `scholarship_program`.`id`=`staff`.`sp_id`
						WHERE `user`.`id`=?";
						$stmt=$con->prepare($query);
						$stmt->execute([$id]);
						$row = $stmt->fetch(PDO::FETCH_ASSOC);				
                    ?>
                        <form role="form" method="post">
                            <!-- Address -->
                            <h6 class="heading-small text-muted mb-4">Personal information</h6>
	
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="username">Email
										<?php 

										?></label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input onBlur="userAvailability()" id="username" name="username" value="<?php 
											$username = isset($row['user_username']) ? htmlspecialchars($row['user_username'], ENT_QUOTES, 'UTF-8') : '';
											echo $username; ?>" class="form-control" placeholder="Email" type="email" title="Enter email" oninvalid="this.setCustomValidity('Please enter email.')" oninput="setCustomValidity('')" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="user-availability-status1"></span> <img src="../assets/img/loading.gif" width="35" id="loadericon" style="display:none;" />
                                            </div>
                                        </div>
                                        <input id="oldusername" name="oldusername" value="<?php echo $row['user_username'] ?>" type="hidden" />
										<input type='hidden' name='user_id' value='<?php echo $row['user_id'];?>'>
                                        <span id="user-availability-status1"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="fname">First Name</label>
                                        <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <div class="input-group-prepend">
                                            </div>
                                            <input type="text" name="firstname" class="form-control" value="<?php 
											$firstname = isset($row['user_fname']) ? htmlspecialchars($row['user_fname'], ENT_QUOTES, 'UTF-8') : '';
											echo $firstname; ?>" id="firstname" placeholder="Enter First Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="lname">Last Name</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></i></span>
                                            </div>
                                            <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Enter Last Name" value="<?php 
											$lastname = isset($row['user_lname']) ? htmlspecialchars($row['user_lname'], ENT_QUOTES, 'UTF-8') : '';
											echo $lastname; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4" />
                            <!-- Address -->
                            <h6 class="heading-small text-muted mb-4">Roles Information</h6>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="role">Position</label>
                                        <select class="form-control" name="position" title="Scholarship Program" id="position">
											<option value='<?php echo $row['position_id'];?>' selected><?php echo $row['position_name'];?></option>
											<option value='1' selected>Administrator</option>
											<option value='2' selected>Staff</option>
											<option value='3' selected>Coordinator</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right pb-0">
                                <button type="button" data-toggle="modal" data-target="#edit" class="btn btn-primary my-4 sp-edit">Edit Data</button>
                                <a type="button" href="scholarship_provider.php" class="btn btn-danger my-4">Cancel</a>
                            </div>
                            <!-- batas modal form validasi edit profil -->
                            <div class="col-md-4">
                                <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                                    <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <div class="card bg-secondary border-0 mb-0">
                                                    <div class="card-body px-lg-5 py-lg-5">
                                                        <div class="text-center text-muted mb-4">
                                                            <small>Enter your password to change user data : <b></b></small>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="input-group input-group-merge input-group-alternative">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                                                </div>
                                                                <input class="form-control" name="password_valid" placeholder="Enter Password" type="password" title="Enter Password" oninvalid="this.setCustomValidity('Please enter your Password.')" oninput="setCustomValidity('')" required>
                                                            </div>
                                                        </div>
                                                        <div class="input-group input-group-merge">
                                                            <div class="input-group-prepend text-left text-muted">
                                                                <span><i class="fas fa-info-circle"></i> <small>Your password is needed as verification that you have access to change this data. </small></span>
                                                            </div>

                                                        </div>
														<div class="text-center">
															<button type="submit" name="edit" class="btn btn-primary my-4">Edit Data</button>
														</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</form>
					</div>
				</div>
            <?php
            include("include/footer.php"); //Edit topnav on this page
            ?>
            <script>
                function toggle_select(id) {
                    var X = document.getElementById(id);
                    if (X.checked == true) {
                        X.value = "1";
                    } else {
                        X.value = "0";
                    }
                    //var sql="update clients set calendar='" + X.value + "' where cli_ID='" + X.id + "' limit 1";
                    var who = X.id;
                    var chk = X.value
                    //alert("Joe is still debugging: (function incomplete/database record was not updated)\n"+ sql);
                    $.ajax({
                        //this was the confusing part...did not know how to pass the data to the script
                        url: 'as_status_penyeleksi.php',
                        type: 'post',
                        data: 'who=' + who + '&chk=' + chk,
                        success: function(output) {
                            alert('success, server says ' + output);
                        },
                        error: function() {
                            alert('something went wrong, save failed');
                        }
                    });
                }
            </script>
            <script type="text/javascript">
				const checkFirstname = document.getElementById('firstname');
				checkFirstname .addEventListener('change', () => {
				  const lettersRegex = /^[a-zA-Z\s]+$/;
				  //const digitRegex = /^\d{10}$/;
				  if (!lettersRegex.test(checkFirstname .value)) {
					$("#firstname").css({ 
					  "border" :"1px solid red",
					  "color" :"red",
					});
					$("#firstname").fadeIn("slow");
					$("#firstname").focus();
				  } else {
					$("#firstname").css({ 
					  "border" :"",
					  "color" :"#000",
					});
					$("#firstname").fadeIn("slow");
				  }
				});
				const checkLastname = document.getElementById('lastname');
				checkLastname .addEventListener('change', () => {
				  const lettersRegex = /^[a-zA-Z\s]+$/;
				  //const digitRegex = /^\d{10}$/;
				  if (!lettersRegex.test(checkLastname .value)) {
					$("#lastname").css({ 
					  "border" :"1px solid red",
					  "color" :"red",
					});
					$("#lastname").fadeIn("slow");
					$("#lastname").focus();
				  } else {
					$("#lastname").css({ 
					  "border" :"",
					  "color" :"#000",
					});
					$("#lastname").fadeIn("slow");
				  }
				});
				const checkSP = document.getElementById('scholarship_program');
				checkSP  .addEventListener('change', () => {
				  const lettersRegex = /^[a-zA-Z\s]+$/;
				  //const digitRegex = /^\d{10}$/;
				if (!lettersRegex.test(checkSP  .value)|| checkSP.value==='') {
					$("#scholarship_program").css({ 
					  "border" :"1px solid red",
					  "color" :"red",
					});
					$("#scholarship_program").fadeIn("slow");
					$("#scholarship_program").focus();
				  } else {
					$("#scholarship_program").css({ 
					  "border" :"",
					  "color" :"#000",
					});
					$("#scholarship_program").fadeIn("slow");
				  }
				});
                document.getElementById("close_direct").onclick = function() {
                    location.href = "scholarship_provider.php";
                };
            </script>
            <script>
                $('.select2').select2();
            </script>
            <script src="js/fakultas-prodi.js"></script>
			<script type="text/javascript">
			$(document).on("click", ".sp-edit",function(){
					var lettersRegex = /^[a-zA-Z\s]+$/;
					var numbersRegex = /^[0-9]+$/;
					var specialCharactersRegex = /^[a-zA-Z0-9\s]*$/;
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
					var scholarship_program=document.getElementById("scholarship_program").value.trim();
					if (scholarship_program==='') {
						$("#scholarship_program").css({ 
							"border" :"1px solid red",
						});
						$("#scholarship_program").fadeIn("slow");
						$("#scholarship_program").focus();
					   return false;
					}
					
			});
		</script>
        </div>
    </div>
    </body>
    </html>
	<?php }?>