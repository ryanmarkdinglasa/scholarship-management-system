<?php
	error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "Submission";
	$parentpage_link = "#";
	$currentpage = "Pending Application";
	$page=$childpage = "process";

  //ACCEPT APPLICATION
	if (isset($_GET['accept']) && $_GET['accept']==1) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `application` SET `status`='2' WHERE id=? LIMIT 1" );
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong terminating scholarship.';
				header("location:application_processed.php");
				exit();
			}
		}catch(Exception $e){
			$_SESSION['error']='Something went wrong .'.$e->getMessage();
		}
		header("location:application_processed.php");
		exit();
	}
  //DENY GRANT
	if (isset($_GET['deny']) && $_GET['deny']==1) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `application` SET `status`='3' WHERE id=? LIMIT 1" );
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong terminating scholarship.';
				header("location:application_processed.php");
				exit();
			}else{
        //SEND EMAIl FOR NOTIFICATION
        $student_record=getrecord('student',['id'],[$student_id]);
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
      }
		}catch(Exception $e){
			$_SESSION['error']='Something went wrong .'.$e->getMessage();
		}
		header("location:application_processed.php");
		exit();
	}
  //GRANT AWARD TO APPLICANT
  if(isset($_POST['submit'])){
    $application_id=$_POST['application_id'];
    $student_id=$_POST['stud_id'];
    $grant_id=$_POST['grant_id'];
    $school_id=$_POST['school_id'];
    $sp_id=$user['staff_sp'];
    $status=1;
    $created_on=date('Y-m-d H:i:s');
    if(empty($student_id)||empty($grant_id)||empty($school_id)||empty($sp_id)){
        $_SESSION['error']='Some fields are missing.';
        header('Location:application_processed.php');
        exit();
    }
    //UPDATE APPLICATION STATUS
      $stmt = $con->prepare("UPDATE `application` SET `status`='4' WHERE id=? LIMIT 1" );
			if(!$stmt->execute([$application_id])){
				$_SESSION['error']='Something went wrong granting award/scholarship.';
				header("location:application_processed.php");
				exit();
			}
      //
    $result=addrecord('scholar',['student_id','sp_id','award_no','school_id','status','created_on'],[$student_id,$sp_id,$grant_id,$school_id,$status,$created_on]);
    if(!$result){
         $_SESSION['error']='Something went wrong granting scholarship.';
        header('Location:application_processed.php');
        exit();
    }else{
        //SEND EMAIl FOR NOTIFICATION
        $student_record=getrecord('student',['id'],[$student_id]);
        $subject="ISKALAR Notification";
        $message="\nHello ".strtoupper($student_record['firstname'])."
        \nYou are receiving this email to notify you that your application has been granted.
        \nCongrats for being a scholar in ".$user['program_description']."
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
        $_SESSION['success']='Scholar granted.';
        header('Location:scholar.php');
        exit();
    }
  }

  //DENY GRANT AWARD FOR THIS APPLICATION
	if (isset($_GET['action']) && $_GET['action']=='deny') {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `application` SET `status`='4' WHERE id=? LIMIT 1 ");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong terminating scholarship.';
				header("location:application_processed.php");
				exit();
			}else{
        //GET USER INFO
        $sql="SELECT `application`.*,
        `student`.`username` AS `student_username`,
        `student`.`firstname` AS `student_fname`,
        `user`.`id` AS `user_id`
        FROM `application`
        INNER JOIN `student` ON `student`.`id`=`application`.`student_id`
        INNER JOIN `user` ON `user`.`username`=`student`.`username`
        WHERE `application`.`id`='".$id."'";
        $stmt1=$con->prepare($sql);
        $stmt1->execute();
        $row=$stmt1->fetch(PDO::FETCH_ASSOC);
        //NOTIFY THE STUDENT
        $message="
        <br>Hello ".strtoupper($row['student_fname'])."
        <br>You are receiving this email to notify you that your application in ".$user['program_description']." (".$user['program_name'].") has been Denied for grant award.
        <br>
        <br>
        <br>
        <br>This is an automatically generated message. Please do not reply.";
        notify($row['user_id'],$user['user_id'],$message);
        //receiver //sender //message
      }
		}catch(Exception $e){
			$_SESSION['error']='Something went wrong .'.$e->getMessage();
		}
		header("location:application_processed.php");
		exit();
	}
	
	//ACCEPTED APPLICATION
	if (isset($_GET['action']) && $_GET['action']=='accept') {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `application` SET `status`='2' WHERE id=? LIMIT 1");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong accepting application.';
				header("location:application_processed.php");
				exit();
			}else{
        //GET USER INFO
        $sql="SELECT `application`.*,
        `student`.`username` AS `student_username`,
        `student`.`firstname` AS `student_fname`,
        `user`.`id` AS `user_id`
        FROM `application`
        INNER JOIN `student` ON `student`.`id`=`application`.`student_id`
        INNER JOIN `user` ON `user`.`username`=`student`.`username`
        WHERE `application`.`id`='".$id."'";
        $stmt1=$con->prepare($sql);
        $stmt1->execute();
        $row=$stmt1->fetch(PDO::FETCH_ASSOC);
        //NOTIFY THE STUDENT
        $message="
        <br>Hello ".strtoupper($row['student_fname'])."
        <br>You are receiving this email to notify you that your application in ".$user['program_description']." (".$user['program_name'].") has been Accepted.
        <br>
        <br>
        <br>
        <br>This is an automatically generated message. Please do not reply.";
        notify($row['user_id'],$user['user_id'],$message);
        //receiver //sender //message
      }
		}catch(PDOException $e){
			$_SESSION['error']='Something went wrong.'.$e->getMessage();
		}
		header("Location:application_processed.php");
		exit();
	} 
	
  //REMOVED APPLICATION
	if (isset($_GET['action']) && $_GET['action']=='delete') {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `application` SET `status`='-2' WHERE id=? LIMIT 1");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong terminating scholarship.';
				header("location:application_processed.php");
				exit();
			}else{
        //GET USER INFO
        $sql="SELECT `application`.*,
        `student`.`username` AS `student_username`,
        `student`.`firstname` AS `student_fname`,
        `user`.`id` AS `user_id`
        FROM `application`
        INNER JOIN `student` ON `student`.`id`=`application`.`student_id`
        INNER JOIN `user` ON `user`.`username`=`student`.`username`
        WHERE `application`.`id`='".$id."'";
        $stmt1=$con->prepare($sql);
        $stmt1->execute();
        $row=$stmt1->fetch(PDO::FETCH_ASSOC);
        //NOTIFY THE STUDENT
        $message="
        <br>Hello ".strtoupper($row['student_fname'])."
        <br>You are receiving this email to notify you that your application in ".$user['program_description']." (".$user['program_name'].") has been Removed after processing.
        <br>
        <br>
        <br>
        <br>This is an automatically generated message. Please do not reply.";
        notify($row['user_id'],$user['user_id'],$message);
        //receiver //sender //message
      }
		}catch(Exception $e){
			$_SESSION['error']='Something went wrong .'.$e->getMessage();
		}
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
						  <h3 class="mb-0">Processed Applications</h3>
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
                     <th>Date Applied</th>
                     <th>Email</th>
                     <th>School</th>
                     <th>Processed By</th>
                     <th>Status</th>
                     <th>Options</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Name</th>
                    <th>Date Applied</th>
                     <th>Email</th>
                     <th>School</th>
                     <th>Processed By</th>
                     <th>Status</th>
                     <th>Options</th>
                  </tr>
                </tfoot>
                <tbody>
                  <?php
					try{
					$query = "SELECT `application`.*,
          `application`.`id` AS `application_id`,
          `application`.`status` AS `application_status`,
          `application`.`created_on` AS `application_date`,
          `application`.`process_by` AS `staff_id`,
          `student`.*,
          `student`.`id` AS `stud_id`,
          `user`.`profileImage` AS `user_image`
          FROM `application`
          INNER JOIN `student` ON `student`.`id`=`application`.`student_id`
          INNER JOIN `user` ON `user`.`username`=`student`.`username`
          WHERE `application`.`status`='1' OR `application`.`status`='2' AND `application`.`sp_id`='".$user['staff_sp']."'						
					";
					$stmt = $con->prepare($query);
					$stmt->execute();
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
					}catch(Exception $e){
						$_SESSION['error']='Something went wrong accessing applicants.';
					}
					foreach ($result as $row) {
          ?>
          <tr>
            <td class="table-user">
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
						$username= isset($row['username']) ? htmlspecialchars(short_text($row['username']), ENT_QUOTES, 'UTF-8') : '';
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
              <?php 
                $staff_id= isset($row['staff_id']) ? htmlspecialchars(short_text($row['staff_id']), ENT_QUOTES, 'UTF-8') : '';
                $sql_staff="SELECT 
                `user`.`firstname` AS `staff_fname`,
                `user`.`lastname` AS `staff_lname`,
                `user`.`username` AS `staff_username`,
                `user`.`profileImage` AS `staff_image`
              FROM `user`
              WHERE `user`.`id`='".$staff_id."'
                ";
                $get_staff=getrecord_query2($sql_staff);
                $staffphoto = isset($get_staff['profileImage']) ? htmlspecialchars($get_staff['user_image'], ENT_QUOTES, 'UTF-8') : '';
                if ($staffphoto == "" || $staffphoto == "NULL") :
                ?>
                <img src="img/profile.png" class="avatar rounded-circle mr-3">
                <?php else : ?>
                <img src="../scholarship-provider/img/<?php echo $staffphoto; ?>" class="avatar rounded-circle mr-3">
                <?php endif; ?>
                <b>
                <?php 
                  $staff_firstname = isset($get_staff['staff_fname']) ? htmlspecialchars($get_staff['staff_fname'], ENT_QUOTES, 'UTF-8') : '';
                  $staff_lastname = isset($get_staff['staff_lname']) ? htmlspecialchars($get_staff['staff_lname'], ENT_QUOTES, 'UTF-8') : '';
                  $staff_name=short_text($staff_firstname.' '.$staff_lastname);
                  echo $staff_name;
                ?>
                </b>
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
                      <td class="text-right">
                        <div class="dropdown">
                          <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <?php if($status>=2){?>
                            <a class="dropdown-item" data-toggle="modal" data-target="#modal-form2<?php echo $row['application_id'];?>" type="button"> <i class="fas fa-award" style="color:#172b4d;"></i>Grant Award</span></a>
                            <a class="dropdown-item" href="application_processed.php?id=<?php echo $row['application_id'] ?>&action=deny"><i class="fas fa-times" style="color:#fb6340;"></i> Deny Award</a>
                            <?php }?>
                            <a class="dropdown-item" href="application_processed.php?id=<?php echo $row['application_id'] ?>&action=accept"><i class="fas fa-check" style="color:#2dce89;"></i> Accept</span></a>
                           
                            <a class="dropdown-item" href="application_processed.php?id=<?php echo $row['application_id'] ?>&action=delete" onClick="return confirm('Are you sure you want to clear, <?php echo htmlentities($row['firstname']); ?> ?')"><i class="fas fa-trash" style="color:#f5365c;"></i> Remove Application</a>
                            
                          </div>
                        </div>
                      </td>
                    </tr>
                    <div class="col-md-4">
                                            <div class="modal fade" id="modal-form2<?php echo $row['application_id'];?>" tabindex="-1" role="dialog" aria-labelledby="modal-form2" aria-hidden="true">
                                                <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body p-0">
                                                            <div class="card bg-secondary border-0 mb-0">
                                                                <div class="card-body px-lg-5 py-lg-5">
                                                                    <div class="text-center text-muted mb-4">
                                                                        <small> Grant Scholarships</small>
                                                                    </div>
                                                                    <form role="form" method="post">
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-control-label">Scholarship Grant </label>
                                                                            <div class="input-group input-group-merge input-group-alternative">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                                </div>
                                                                                <input name="application_id" id="application_id"type="hidden" value="<?php echo $row['application_id'];?>" />
                                                                                <input name="stud_id" id="stud_id"type="hidden" value="<?php echo $row['stud_id'];?>" />
                                                                                <select class="form-control" id="grant_id" name="grant_id" placeholder="Scholarship Grant Name" type="text" value="<?php //echo $row['name']; ?>" title="Enter the Name of the Scholarship Grant" oninvalid="this.setCustomValidity('Please enter the Name of the Scholarship Grant.')" oninput="setCustomValidity('')" required>
                                                                                <option selected>Select Scholarship Grant</option>
                                                                                <?php
                                                                                   $sql="SELECT * FROM `sp_grant` WHERE `sp_id`='".$user['staff_sp']."'";
                                                                                   $stmt=$con->prepare($sql);
                                                                                   $result=$stmt->execute();
                                                                                   if($result){
                                                                                    $get_grant= $stmt->fetchAll(PDO::FETCH_ASSOC);
//$get_grant= getall('sp_grant');
                                                                                  foreach($get_grant as $grants){
                                                                                ?>
                                                                                  <option value="<?php echo $grants['id'];?>"><?php echo short_text($grants['name'])?></option>
                                                                                <?php 
                                                                                  }//loop
                                                                                }else{
                                                                                  echo "Something went wrong.";
                                                                                }                      
                                                                                ?>
                                                                                </select>
                                                                              </div>
                                                                              
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                          <label class="form-control-label">School Enrolled</label>
                                                                            <div class="input-group input-group-merge input-group-alternative">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                                </div>
                                                                               
                                                                                <select class="form-control" id="school_id" name="school_id" placeholder="School Name" type="text" value="<?php //echo $row['name']; ?>" title="Enter the Name of the School" oninvalid="this.setCustomValidity('Please enter the Name of the School.')" oninput="setCustomValidity('')" required>
                                                                                <option selected>Select School</option>
                                                                                <?php
                                                                                  $sql="SELECT * FROM `school` WHERE `sp_id`='".$user['staff_sp']."'";
                                                                                  $stmt=$con->prepare($sql);
                                                                                  $result=$stmt->execute();
                                                                                  if($result){
                                                                                  $get_school = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                                                 // $get_school=getall('school');
                                                                                  foreach($get_school as $school){
                                                                                ?>
                                                                                  <option value="<?php echo $school['id'];?>"><?php echo short_text($school['school_name'])?></option>
                                                                                <?php 
                                                                                  }//loop
                                                                                }else{
                                                                                  echo "Something went wrong.";
                                                                                }                      
                                                                                ?>
                                                                                </select>
                                                                              </div>
                                                                              
                                                                        </div>
                                                                        <div class="text-center">
                                                                            <button type="submit" id="submit" name="submit" class="btn btn-primary my-4">Save </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
