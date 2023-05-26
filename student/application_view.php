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
    date_default_timezone_set('Asia/Manila'); // change according timezone
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
    $query = "SELECT `application`.*,
          `application`.`id` AS `application_id`,
          `application`.`status` AS `application_status`,
          `application`.`created_on` AS `application_date`,
          `student`.*,
          `user`.`profileImage` AS `user_image`
          FROM `application`
          INNER JOIN `student` ON `student`.`id`=`application`.`student_id`
          INNER JOIN `user` ON `user`.`username`=`student`.`username`
          WHERE `application`.`id`='".$_GET['id']."'";
    $stmt = $con->prepare($query);
	$stmt->execute();
    $application_data = $stmt->fetch(PDO::FETCH_ASSOC);
    //
    if (isset($_POST['submit'])) {
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
                            <h3 class="mb-0">Scholar Information</h3>
                        </div>
                        <div class="col-4 text-right">
                            <code class="text-default"><mark class="text-default"></mark></code>
                        </div>
                    </div>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <!-- Form groups used in grid -->                    
                            <form role="form" method="post">
                                <!-- Address -->
                                <h6 class="heading-small text-muted mb-4">Personal information</h6>
                                <?php foreach($application_data as $field){?>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for=""><?php echo $field;?></label>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <hr class="my-4" />
                                <!-- Address -->
                                <h6 class="heading-small text-muted mb-4">Academic Information</h6>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="faculty">Faculty</label>
                                            <img src="../assets/img/loading.gif" width="35" id="load2" style="display:none;" />
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right pb-0">
                                    <button type="submit" id="submit" name="submit" class="btn btn-icon btn-primary text-white my-4"  type="button">
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
