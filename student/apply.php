<?php
    error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "Scholarship";
	$parentpage_link = "scholarship_offer.php";
	$currentpage = "Apply Scholarship";
	$page=$childpage = "apply";
    date_default_timezone_set('Asia/Jakarta'); // change according timezone
    $currentTime = date('d-m-Y h:i:s A', time());
    if(!isset($_GET['id'])){
		header('location:404.php');
		exit();
	}
	$row=$check_id=getrecord('scholarship_program',['id'],[$_GET['id']]);
	if(empty($check_id)){
		header('location:404.php');
		exit();
	}
    $row1=$check_id1=getrecord('scholarship',['id'],[$_GET['offer']]);
	if(empty($check_id1)){
		header('location:404.php');
		exit();
	}

    //APPLY
    if (isset($_POST['apply']) && isset($_FILES['fileToUpload']) && count($_FILES['fileToUpload']['name']) > 0 ) {
        $check_student_application=getrecord('application',['student_id','sp_id','offer_id'],[$user['student_id'],$_GET['id'],$_GET['offer']]);
        if($check_student_application){
            $_SESSION['error'] = "Already applied to this scholarship offer.";
            header('Location: scholarship_offer.php');
            exit();
        }
       
        define('KB', 1024);
        define('MB', 1048576);
        define('GB', 1073741824);
        define('TB', 1099511627776);
        $files = array();
        $count=0;
        $total=0;
        try {
            foreach ($_FILES['fileToUpload']['tmp_name'] as $key => $tmp_name) {
                // Check if file is an image
                if (!getimagesize($_FILES['fileToUpload']['tmp_name'][$key])) {
                    throw new Exception('Please upload only image files.');
                }
                $req_name=$_POST['req_name'][$key]; // use $key to get corresponding value from $_POST array
                // Check if file type is allowed
                $allowed_file_types = array('jpg', 'jpeg', 'png');
                $imgfile = $_FILES['fileToUpload']['name'][$key];
                $imageFileType = strtolower(pathinfo($imgfile, PATHINFO_EXTENSION));
                if (!in_array($imageFileType, $allowed_file_types)) {
                    throw new Exception('The only allowed files are JPG, PNG and JPEG.');
                }
        
                // Check if file size is allowed
                $max_file_size = 2 * MB;
                if ($_FILES['fileToUpload']['size'][$key] > $max_file_size) {
                    throw new Exception('Image file size is more than 2 MB');
                }
        
                // Sanitize the file name
                $imgfile = preg_replace("/[^A-Za-z0-9\.]/", '', $imgfile);
        
                //rename the image file
                $extension = substr($imgfile, strlen($imgfile) - 4, strlen($imgfile));
                $imgnewfile = md5($imgfile) . uniqid() . $extension;
        
                // Code for move image into directory
                move_uploaded_file($_FILES['fileToUpload']['tmp_name'][$key], 'img/' . $imgnewfile);
        
                // Query for insertion data into database
                $created_on = date('Y-m-d H:i:s');
                
                $stmt = $con->prepare("INSERT INTO `requirement` (`student_id`, `offer_id`,`req_name`,`item`,`created_on`) VALUES (?,?,?,?,?)"); // add one more placeholder
                $result= $stmt->execute([$user['student_id'],$_GET['offer'], $req_name, $imgnewfile,$created_on]); // pass all parameters to execute method
                if($result){
                    $count++;
                }
                $total++;
                $files[] = $imgnewfile;
            }
           
        } catch (Exception $e) {
            // Display error message to user
            echo $e->getMessage();
        }
        $status = 0;
        $created_on = date('Y-m-d H:i:s');
        $process_by=0;
        $student_id = filter_var($user['student_id'], FILTER_VALIDATE_INT);
        $sp_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $offer_id = filter_var($_GET['offer'], FILTER_VALIDATE_INT);
        if (!$student_id || !$sp_id || !$offer_id) {
            // Invalid input
            $error_id = uniqid();
            error_log("Invalid input in scholarship application (error ID: $error_id)");
            $_SESSION['error'] = "An error occurred (error ID: $error_id). Please try again later.";
            header('Location: scholarship_offer.php');
            exit();
        }
        //$application=addrecord('application',[`student_id`, `sp_id`, `offer_id`, `status`, `process_by`, `created_on`],[$student_id, $sp_id, $offer_id, $status,$process_by, $created_on]);
        $stmt = $con->prepare("INSERT INTO `application` (`student_id`, `sp_id`, `offer_id`, `status`, `created_on`) VALUES (?, ?, ?, ?, ?)");
        $application = $stmt->execute([$student_id, $sp_id, $offer_id, $status, $created_on]);
        if (!$application) {
            // Database error
            $error_id = uniqid();
            error_log("Database error in scholarship application (error ID: $error_id)");
            $_SESSION['error'] = "An error occurred (error ID: $error_id). Please try again later.";
            header('Location: scholarship_offer.php');
            exit();
        } else {
            $sql="SELECT `staff`.*,
            `user`.`id` AS `user_id`
            FROM `staff`
            INNER JOIN `user` ON `user`.`username` = `staff`.`username`
            WHERE `sp_id`='".$sp_id."' ";
            $stmt=$con->prepare($sql);
            $stmt->execute();
            $row=$stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($row as $staff){
            $message="\nHello ".strtoupper($staff['firstname'])."
            \nYou are receiving this email to notify you that there is a new application.
            \n
            \n
            \n
            \n
            \nThis is an automatically generated message. Please do not reply.";

            //NOTIFY ALL THE STAFF sa  new applicaiton
            notify($staff['user_id'],$user['user_id'],$message);

            }//loop send all staff a new notfication for the application

            $_SESSION['success'] = 'Application sent';
            header('Location: scholarship_offer.php');
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
                url: "check_username.php",
                data: 'tambahnimbaru=' + $("#tambahnimbaru").val(),
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

    <style>
        .select2-selection__rendered {
            font-size: .875rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px;
            top: 50%;
            transform: translateY(-50%);
            right: 0.01px;
            width: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            background-image: url(https://cdn4.iconfinder.com/data/icons/user-interface-174/32/UIF-76-512.png);
            background-color: transparent;
            background-size: contain;
            border: none !important;
            height: 20px !important;
            width: 20px !important;
            margin: auto !important;
            top: auto !important;
            left: auto !important;
        }
    </style>

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
        <?php include "include/breadcrumbs.php";?>
        <!-- Batas Header & Breadcrumbs -->
        <!-- Page content -->
        <div class="container-fluid mt--6">
            <div class="card mb-4">
                <!-- Card header -->
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <!-- Title -->
                            <h3 class="mb-0">Apply Scholarship</h3>
                        </div>
                        <div class="col-4 text-right">
                            <code class="text-default"><mark class="text-default"></mark></code>
                        </div>
                    </div>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <!-- Form groups used in grid -->                    
                           
                                <!-- Address -->
                                <h6 class="heading-small text-muted mb-4">Requirements</h6>
                                <?php
                                $sql=" SELECT * FROM `sp_requirement` WHERE `sp_id`='".$_GET['id']."' ";
                                $stmt=$con->prepare($sql);
                                $result=$stmt->execute();
                                if(!$result){
                                    $_SESSION['error']='Something went wrong accessing scholarship program requirements.';
                                }
                                $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
                                $count=1;
                                foreach ($rows as $row){
                                ?>
                                <form method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                        </div>
                                            <label class="form-control-label" for="nim"><?php echo $count.'.) '.$row['name'];?></label>
                                            <div class="custom-file">
                                            <input type="hidden" id="req_name[]" name="req_name[]" value="<?php echo $row['name'];?>" />
                                            <input type="file" name="fileToUpload[]" class="custom-file-input" id="fileToUpload[]" lang="id" required>
                                            <label class="custom-file-label" for="fileToUpload[]">Select Files</label>
                                            </div>
                                        
                                    </div>
                                </div>

                                <?php
                                $count++;
                                }
                                ?>
                                <hr class="my-4" />
                                <div class="text-right pb-0">
                                    <button type="submit" id="submit" name="apply" class="btn btn-icon btn-primary text-white my-4"  type="button">
                                        <span class="btn-inner--icon"><i class="fas fa-check"></i></span>
                                        <span class="btn-inner--text">Apply</span>
                                    </button>
                            </form>
                            
                            <a href="scholarship_offer.php??>&&action=disapprove" type="button" class="btn btn-icon btn-danger text-white my-4" type="button">
                                <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
                                <span class="btn-inner--text">Cancel</span>
                            </a>
                </div>
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
            document.getElementById("close_direct").onclick = function() {
                location.href = "application_pending.php";
            };
        </script>
        <script>
            $('.select2').select2();
        </script>
        <script src="js/fakultas-prodi.js?v=1"></script>

    </div>
    </div>

    </body>

    </html>
