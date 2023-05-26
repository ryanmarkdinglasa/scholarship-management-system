<?php
  error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "Feedback";
	$parentpage_link= "feedback.php";
	$currentpage='View Feedback';
	$page=$childpage = "feedback";
	
	//SESSION TO EDIT
	if(!isset($_GET['id'])){
		header('location:404.php');
		exit();
	}
	$row=$check_id=getrecord('feedback',['id'],[$_GET['id']]);
	if(empty($check_id)){
		header('location:404.php');
		exit();
	}
	//
?>
  <?php
  include("include/header.php");
  ?>
  <script>
    function userAvailability() {
      $("#loaderIcon").show();
      jQuery.ajax({
        url: "add_admin_check_username.php",
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
    <?php
    include("include/topnav.php"); //Edit topnav on this page
    ?>
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
	<?php include"include/breadcrumbs.php";?>
    <!--  Header & Breadcrumbs -->

    <!-- Page content -->
    <div class="container-fluid mt--6">
      <!-- Table -->
      <div class="row">
        <div class="col">

          <div class="card">
            <!-- Card header -->
            <div class="card-header border-1">
              <div class="row">
                <div class="col-6">
                  <h3 class="mb-0">Feedback Information</h3>
                </div>
              </div>
            </div>
			<div class="card-body">
		
				<div class="row" style='padding:10px 10px;'>
					 <div class="col-12">
                        <div class="form-group" >
                            <p class="text-muted heading-small" for="email">
							<?php
							echo "<b>STATUS</b><br>";
							$status = isset($row['status']) ? htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') : '';
							$status =($status==0)? 'ON PROGRESS': 'DONE';
							echo $status;?></p>
						</div>
					</div>
                    <div class="col-12">
                        <div class="form-group">
                            <p class="text-muted heading-small" for="email">
							<?php
							echo "<b>SENDER</b><br>";
							$getemail=getrecord('user',['id'],[$row['user_id']]);
							$email = isset($getemail['username']) ? htmlspecialchars($getemail['username'], ENT_QUOTES, 'UTF-8') : '';
							echo $email;?></p>
						</div>
					</div>
					<div class="col-12">
						
						<div class="form-group mb-5 ">
                            <p class="text-muted heading-small">
							<?php
							echo "<b>DETAILS</b><br>";
							$details = isset($row['content']) ? htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8') : '';
							echo $row['content'];
							?>
							</p>
						</div>
					</div>
				</div>
			
			</div>
            <!-- Light table -->
            
            </div>
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
            url: 'as_status_admin.php',
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
          location.href = "feedback.php";
        };
      </script>


    </div>
  </div>

  </body>

  </html>
