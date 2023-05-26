<?php
    error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "Scholar";
	$parentpage_link = "scholar.php";
	$currentpage = "Scholar View";
	$page=$childpage = "Scholars";

    if(!isset($_GET['id'])){
		header('location:404.php');
		exit();
	}
	$row=$check_id=getrecord('scholar',['id'],[$_GET['id']]);
	if(empty($check_id)){
		header('location:404.php');
		exit();
	}  
    date_default_timezone_set('Asia/Jakarta'); // change according timezone
    $currentTime = date('d-m-Y h:i:s A', time());
 

    if (isset($_POST['submit'])) {

        $password = '1234';
        $passwordhash = password_hash($password, PASSWORD_DEFAULT);
        $nim = $_POST["tambahnimbaru"];
        $nama_mhs = $_POST["nama_mhs"];
        $email = $_POST['email'];
        $no_telp = $_POST['no_telp'];
        $id_fakultas = $_POST['id_fakultas'];
        $id_prodi = $_POST['id_prodi'];
        $id_role = '5';

        //Prepare Update User Data
        $SQL = $con->prepare("INSERT INTO user_mhs (nim, password, nama_mhs, email, no_telp, id_fakultas, id_prodi, id_role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $SQL->bind_param('ssssssss', $nim, $passwordhash, $nama_mhs, $email, $no_telp, $id_fakultas, $id_prodi, $id_role);
        /* Execute the prepared Statement */
        $status = $SQL->execute();
        /* BK: always check whether the execute() succeeded */
        if ($status === false) {
            // trigger_error($SQL->error, E_USER_ERROR);
            $_SESSION['msg'] = "0";
        }
        $_SESSION['msg'] = "1";
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
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="nim">NIM</label>
                                            <div class="input-group input-group-merge">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><small class="font-weight-bold">@</small></span>
                                                </div>
                                                <input onBlur="userAvailability()" id="tambahnimbaru" name="tambahnimbaru" value="" class="form-control" placeholder="NIM Student" type="text" title="Enter NIMs" oninvalid="this.setCustomValidity('Please enter NIM Student.')" oninput="setCustomValidity('')" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="user-availability-status1"></span> <img src="../assets/img/loading.gif" width="35" id="loadericon" style="display:none;" />
                                                </div>
                                            </div>
                                            <input id="oldnim" name="oldnim" value="" type="hidden" />
                                            <span id="user-availability-status1"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="nama">Student name</label>
                                            <div class="input-group input-group-merge">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" name="nama_mhs" class="form-control" value="" id="nama" placeholder="Student name" oninvalid="this.setCustomValidity('Please enter Student Name.')" oninput="setCustomValidity('')" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="email">Email</label>
                                            <div class="input-group input-group-merge">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" name="email" class="form-control" id="email" value="" placeholder="Student e-mail" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="notelp">No. Telephone</label>
                                            <div class="input-group input-group-merge">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                </div>
                                                <input type="number" name="no_telp" class="form-control" id="notelp" value="" placeholder="Student Phone" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <!-- Address -->
                                <h6 class="heading-small text-muted mb-4">Academic Information</h6>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="faculty">Faculty</label>
                                            <select class="form-control" name="id_fakultas" title="faculty" id="fakultasedit" oninvalid="this.setCustomValidity('Please Select Student Faculty.')" oninput="setCustomValidity('')" required>
                                                <option value="selected">Select Faculty</option>
                                                <?php
                                              
                                                ?>
                                            </select>
                                            <img src="../assets/img/loading.gif" width="35" id="load2" style="display:none;" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="prodi">Study Program</label>

                                            <select class="form-control" name="id_prodi" title="Select Study Program" placeholder="Select Study Program" id="prodiedit" oninvalid="this.setCustomValidity('Please Select Student Study Program.')" oninput="setCustomValidity('')" required>
                                            <option value="selected">Select Study Program</option>
                                        </select>
                                            <img src="../assets/img/loading.gif" width="35" id="load2" style="display:none;" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="dosen_wali">Dosen Wali</label>
                                            <select class="form-control" name="id_dosen_wali" title="Dosen Wali" id="dosen_wali" oninvalid="this.setCustomValidity('Silahkan Pilih Dosen Wali Mahasiswa.')" oninput="setCustomValidity('')" required>
                                            </select>
                                            <img src="../assets/img/loading.gif" width="35" id="load2" style="display:none;" />
                                        </div>
                                    </div> 
                                </div>

                                <div class="text-right pb-0">
                                    <button type="submit" id="submit" name="submit" class="btn btn-icon btn-primary text-white my-4"  type="button">
                                        <span class="btn-inner--icon"><i class="fas fa-user-plus"></i></span>
                                        <span class="btn-inner--text">Add Account</span>
                                    </button>
                            </form>
                            
                            <a href="../adm/mahasiswa" type="button" class="btn btn-icon btn-danger text-white my-4" type="button">
                                <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
                                <span class="btn-inner--text">Cancelled</span>
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
                location.href = "scolar.php";
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
