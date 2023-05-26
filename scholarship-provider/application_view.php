<?php
    error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "Pending Application";
	$parentpage_link = "application_pending.php";
	$currentpage = "Application View";
	$page=$childpage = "pending";
    date_default_timezone_set('Asia/Jakarta'); // change according timezone
    $currentTime = date('d-m-Y h:i:s A', time());
    if(!isset($_GET['id'])){
		header('location:404.php');
		exit();
	}
	$row=$check_id=getrecord('application',['id'],[$_GET['id']]);
	if(empty($check_id)){
		header('location:404.php');
		exit();
	} 

    //GET APPLICANT ALL INFORMATION  
    $query = "SELECT *
    FROM `application`
    WHERE `application`.`id`=:id";
    $stmt = $con->prepare($query);
    $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $application = $stmt->fetch(PDO::FETCH_ASSOC);
    //$row=$result->fetch()
    //
    if (isset($_POST['approve'])) {
    //ACTIVATE SCHOLAR
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `application` SET `status`='1',`process_by`='".$user['id']."' WHERE id=? LIMIT 1");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong approving application.';
				header("location:application_pending.php");
				exit();
			}
		}catch(PDOException $e){
			$_SESSION['error']='Something went wrong.'.$e->getMessage();
		}

        $student_record=getrecord('student',['id'],[$application['student_id']]);
        $subject="ISKALAR Notification";
        $message="\nHello ".strtoupper($student_record['firstname'])."
        \nYou are receiving this email to notify you that your application has been granted.
        \n
        \n
        \n
        \n
        \nThis is an automatically generated message. Please do not reply.";
        send_email($student_record['username'],$subject,$message);
        //
        //SEND NOTIFICATION IN WEB
        $user_record=getrecord('user',['username'],[$student_record['username']]);
        notify($user_record['id'],$user['user_id'],$message);
        //
		header("location:application_processed.php");
		exit();
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
        .application-requirement:hover{
            opacity:0.7;

        }
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
                            <h3 class="mb-0">Appplicant Information</h3>
                        </div>
                        <div class="col-4 text-right">
                            
                        </div>
                    </div>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <!-- Form groups used in grid -->                    
                            <form role="form" method="post">
                                <!-- Address -->
                                
                                <div class="text-right">
                                    <code class="text-default"><mark class="text-default">Application No.: <?php echo $application['id'];?></mark></code>
                                </div>
                                <div>
                                <h6 class="heading-small text-muted mb-4 text-left">Personal information</h6>
                                </div>
                              
                                <?php
                               $sql = "SELECT * FROM `student` WHERE `id` = '".$application['student_id']."' LIMIT 1";
                               $stmt2 = $con->prepare($sql);
                               $stmt2->execute();
                               $application_data = $stmt2->fetch(PDO::FETCH_ASSOC);
                               
                               // Check if there is any data returned from the database
                               if (!$application_data) {
                                   // No data found
                                   $_SESSION['error'] = "No data found for the specified ID.";
                                   header('Location: scholarship_offer.php');
                                   exit();
                               }
                               
                               // Access the fetched data by key
                                ?>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Full Name:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['firstname'].' '.$application_data['middlename'].' '.$application_data['lastname'];?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Email:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['username'];?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Mobile No.:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['contact_no'];?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Date of Birth:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['birthdate'];?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Place of Birth:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['birthplace'];?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Sex:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['gender'];?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Civil Status:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['civil_status'];?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Citizenship:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['citizenship'];?></label>
                                        </div>
                                     </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Permanent Address:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['permanent_address'];?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Zip Code:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['zipcode'];?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">School Name:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['school_name'];?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">School Address:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['school_address'];?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">School Type:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['school_type'];?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Highest Grade/Year:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['educational_attainement'];?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Type of disability(if applicatlbe):</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php
                                                $disability =($application_data['disability']=='')?'None':$application_data['disability'];
                                                echo $disability;?></label>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <!-- Address -->
                                <h6 class="heading-small text-muted mb-4">Family Background</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Father Vital Status:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php echo $application_data['father_vital_status'];?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Father's Name:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php
                                                $father_name =($application_data['father_name']=='')?'None':$application_data['father_name'];
                                                echo $father_name;?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Father's Address:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php
                                                $father_address =($application_data['father_address']=='')?'None':$application_data['father_address'];
                                                echo $father_address;?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Father Occupation:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php 
                                                 $father_occupation =($application_data['father_occupation']=='')?'None':$application_data['father_occupation'];
                                                echo $father_occupation;
                                                ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Father's Name:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php
                                                $father_educationalAtt =($application_data['father_educationalAtt']=='')?'None':$application_data['father_educationalAtt'];
                                                echo $father_educationalAtt;?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Mother Vital Status:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php 
                                                 $mother_vital_status =($application_data['mother_vital_status']=='')?'None':$application_data['mother_vital_status'];
                                                echo $mother_vital_status;
                                                ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Mother's Name:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php
                                                $mother_name =($application_data['mother_name']=='')?'None':$application_data['mother_name'];
                                                echo $mother_name;?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Mother's Address:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php
                                                $mother_address =($application_data['mother_address']=='')?'None':$application_data['mother_address'];
                                                echo $mother_address;?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Mother Occupation:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php 
                                                 $mother_occupation =($application_data['mother_occupation']=='')?'None':$application_data['mother_occupation'];
                                                echo $mother_occupation;
                                                ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Mother's Edducational Attainment:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php
                                                $mother_educationalAtt =($application_data['mother_educationalAtt']=='')?'None':$application_data['mother_educationalAtt'];
                                                echo $mother_educationalAtt;?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Parent's Gross Income Classification:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php 
                                                 $gross_income =($application_data['gross_income']=='')?'None':$application_data['gross_income'];
                                                echo $gross_income;
                                                ?></label>
                                        </div>
                                    </div>
                                    
                                </div>
                                <hr class="my-4" />
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">School Intended to enroll:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php
                                                $school_intended =($application_data['school_intended']=='')?'None':$application_data['school_intended'];
                                                echo $school_intended;?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">School Address:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php 
                                                 $school_intended_address =($application_data['school_intended_address']=='')?'None':$application_data['school_intended_address'];
                                                echo $school_intended_address;
                                                ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="">Type of School:</label><br>
                                            <label class="form-control-label text-muted" for="">
                                                <?php
                                                $school_intended_type =($application_data['school_intended_type']=='')?'None':$application_data['school_intended_type'];
                                                echo $school_intended_type;?></label>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <h6 class="heading-small text-muted mb-4">APPLICATION Requirements</h6>
                                <?php
                                $sql = "SELECT *
                                FROM `requirement` 
                                WHERE `student_id` = '".$application['student_id']."' AND `offer_id`='".$application['offer_id']."' ORDER BY `created_on` DESC";
                                $stmt3 = $con->prepare($sql);
                                $stmt3->execute();
                                // Check if there is any data returned from the database
                                if (!$result || $stmt3->rowCount() === 0) {
                                    // No data found
                                    $_SESSION['error'] = "No data found for the specified ID.";
                                    header('Location: scholarship_offer.php');
                                    exit();
                                }
                                // Access the fetched data by key
                                $requirement_data = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                                $count=1;
                                foreach ($requirement_data as $data) {
                                ?>  
                                <div class="container">
                                    <div class="row py-2">
                                        <label class="form-control-label"> <?php echo $count.'.) '.$data['req_name'];?></label>
                                        <div class="col-md-12">
                                            <a href="../student/img/<?php echo $data['item'];?>" target="_blank" data-toggle="lightbox" data-gallery="gallery" data-max-width="100%" data-max-height="100%">
                                            <img src="../student/img/<?php echo $data['item'];?>" alt="application requirement" class="img-fluid application-requirement" style="width:200px;height:200px; border:1px solid grey;">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                $count++;
                                } 
                                ?>
                                <div class="text-right pb-0">
                                    <button type="submit" id="approve" name="approve" class="btn btn-icon btn-primary text-white my-4"  type="button">
                                        <span class="btn-inner--icon"><i class="fas fa-check"></i></span>
                                        <span class="btn-inner--text">Approve</span>
                                    </button>
                            </form>
                            
                            <a href="application_pending.php?id=<?php echo $_GET['id'];?>&&action=disapprove" type="button" class="btn btn-icon btn-danger text-white my-4" type="button">
                                <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
                                <span class="btn-inner--text">Disapprove</span>
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
