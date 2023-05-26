<?php
	error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "Submission";
	$parentpage_link = "#";
	$currentpage = "Submission History";
	$page=$childpage = "submission_history";

  //DELETE
	if (isset($_GET['del'])) {
		$id = $_GET['id'];
		try {
			$stmt = $con->prepare("DELETE FROM `application` WHERE id=? LIMIT 1");
			$stmt->execute([$id]);
			if ($stmt->rowCount() > 0) {
				$_SESSION['success'] = 'Application removed';
				header("location:application_pending.php");
				exit();
			} else {
				$_SESSION['error'] = 'Application not found or already removed';
				header("location:application_pending.php");
				exit();
			}
		} catch (Exception $e) {
			$_SESSION['error'] = 'Something went wrong.'. $e->getMessage();
		}
		header("location:application_pending.php");
		exit();
	}
	
	//ACTIVATE SCHOLAR
	if (isset($_GET['action']) && $_GET['action']=='disapprove') {
		$id=$_GET['id'];
		try{
      
      //
			$stmt = $con->prepare("UPDATE `application` SET `status`='2' WHERE id=? LIMIT 1");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong disapproving application.';
				header("location:scholar.php");
				exit();
			}
		}catch(PDOException $e){
			$_SESSION['error']='Something went wrong.'.$e->getMessage();
		}
		header("Location:application_pending.php");
		exit();
	} 
	
	//TERMINATE SCHOLAR
	if (isset($_GET['off']) && $_GET['off']==0) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `scholar` SET `status`='0' WHERE id=? ");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong terminating scholarship.';
				header("location:scholar.php");
				exit();
			}
		}catch(Exception $e){
			$_SESSION['error']='Something went wrong .'.$e->getMessage();
		}
		header("location:scholar.php");
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
      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header">
			 <div class="row">
              <div class="col-6">
						  <h3 class="mb-0">Submission History</h3>
						</div>
						<div class="col-6 text-right">
						</div>
					  </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive py-4">
              <table class="table align-items-center table-flush table-striped" id="datatable-buttons">
                <thead class="thead-light">
                  <tr>
					          <th>Name</th>
                     <th>Application Date</th>
                     <th>Email</th>
                     <th>School</th>
                     <th>Status</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Name</th>
                    <th>Application Date</th>
                     <th>Email</th>
                     <th>School</th>
                     <th>Status</th>
                  </tr>
                </tfoot>
                <tbody>
                  <?php
					try{
					$query = "SELECT `application`.*,
          `application`.`id` AS `application_id`,
          `application`.`status` AS `application_status`,
          `application`.`created_on` AS `application_date`,
          `student`.*,
          `user`.`profileImage` AS `user_image`
          FROM `application`
          INNER JOIN `student` ON `student`.`id`=`application`.`student_id`
          INNER JOIN `user` ON `user`.`username`=`student`.`username`
          WHERE  `application`.`sp_id`='".$user['staff_sp']."'						
					";
					$stmt = $con->prepare($query);
					$stmt->execute();
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
					}catch(Exception $e){
						$_SESSION[]='Something went wrong accessing applicants.';
					}
					foreach ($result as $row) {
          ?>
          <style>
           
            .removed{
              background:red;
            }
          </style>
          <tr>
            <td class="">
              <?php
						  $userphoto = isset($row['user_image']) ? htmlspecialchars($row['user_image'], ENT_QUOTES, 'UTF-8') : '';
              if ($userphoto == "" || $userphoto == "NULL") :
              ?>
              <img src="img/profile.png" class="avatar rounded-circle mr-3">
              <?php else : ?>
              <img src="../student/img/<?php echo $userphoto; ?>" class="avatar rounded-circle mr-3">
              <?php endif; ?>
              <b>
              <?php 
						    $firstname = isset($row['firstname']) ? htmlspecialchars($row['firstname'], ENT_QUOTES, 'UTF-8') : '';
						    $lastname = isset($row['lastname']) ? htmlspecialchars($row['lastname'], ENT_QUOTES, 'UTF-8') : '';
					      $name=short_text($firstname.' '.$lastname);
                echo $name;
						  ?>
              </b>
						</td>
						<td>
               <span class="text-muted">
						<?php 
							$created_on = isset($row['application_date']) ? htmlspecialchars(date('d/m/Y', strtotime($row['application_date'])), ENT_QUOTES, 'UTF-8') : '';
							echo $created_on;
                        ?>
						</span>
						</td>
						<td>
            <a href="mailto:<?php 
						$username= isset($row['username']) ? htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') : '';
						echo $username; ?>" class="font-weight-bold"><?php 
						$username =short_text($username);
						echo $username;
						?></a>
            </td>
            <td>
              <?php 
                $school_intended_name= isset($row['school_intended']) ? htmlspecialchars(short_text($row['school_intended']), ENT_QUOTES, 'UTF-8') : '';
                echo $school_intended_name;
              ?>
            </td>
            <td>
                        <?php $status = $row['application_status'];
                        if ($status == -2) {
                          echo '<span class="badge badge-danger">Removed</span>';
                        } elseif ($status == -1) {
                          echo '<span class="badge badge-danger">Disapproved</span>';
                        } elseif ($status == 0) {
                          echo '<span class="badge badge-warning">Pending</span>';
                        } elseif ($status == 1) {
                          echo '<span class="badge badge-warning">Processed</span>';
                        } elseif ($status == 2) {
                          echo '<span class="badge badge-success">Accepted</span>';
                        } elseif ($status == 3) {
                          echo '<span class="badge badge-warning">Denied</span>';
                        } elseif ($status == 4) {
                          echo '<span class="badge badge-success">Awarded</span>';
                        } elseif ($status == 5) {
                          echo '<span class="badge badge-success">Active</span>';
                        }elseif ($status == 6) {
                          echo '<span class="badge badge-warning">Inactive</span>';
                        }elseif ($status ==7) {
                          echo '<span class="badge badge-danger">Terminated</span>';
                        }
                        ?>
                      </td>
                    </tr>
                    
                      </div>
                  <?php
                  } 
				  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
			<?php
				include("include/footer.php");
			?>
			<script type="text/javascript">
				document.getElementById("close_direct").onclick = function() {
				  location.href = "scholarship_provider.php";
				};
			</script>
			<script>
				$('.select2').select2();
			</script>
			<script src="js/fakultas-prodi.js"></script>
			</div>
		</div>
	</body>
</html>
