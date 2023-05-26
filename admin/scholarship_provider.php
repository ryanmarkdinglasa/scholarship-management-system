<?php
	error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "Account";
	$parentpage_link = "#";
	$currentpage = "Scholarship Provider";
	$childpage = "scholarship_provider";
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
    <div class="col-md-4">
      <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-body p-0">
              <div class="card bg-secondary border-0 mb-0">
                <div class="card-body px-lg-5 py-lg-5">
                  <div class="text-center text-muted mb-4">
                    <small>Add New Scholarship Provider</small>
                  </div>
                  <form action='scholarship_provider_controller.php' role="form" method="post">
                    <div class="form-group mb-3">
							  		  <label class="form-control-label">Email</label>
                      <div class="input-group input-group-merge input-group-alternative">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                        </div>
                        <input onBlur="userAvailability()" id="username" name="username" class="form-control" placeholder="Enter Email" type="email" title="New Scholarship Provider" oninvalid="this.setCustomValidity('Please enter the new email.')" oninput="setCustomValidity('')" required>
                        <div class="input-group-append">
                          <span class="input-group-text" id="user-availability-status1"></span>
                        </div>
                      </div>
                      <span id="user-availability-status1"></span>
                    </div>
                    <div class="form-group mb-3">
                      <label class="form-control-label">Scholarship Program</label>
                      <div class="input-group input-group-merge input-group-alternative">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-building"></i></span>
                        </div>
                        <select class="form-control" name="scholarship_program" id="scholarship_program" placeholder="Select Scholarship Program" title="Enter Scholarship Program" oninvalid="this.setCustomValidity('Please enter a scholarship program.')" oninput="setCustomValidity('')" required>
                          <option value="" selected>Select Scholarship Program</option>
                          <?php
                          try {
                            $stmt = $con->prepare("SELECT `id`, `name` FROM `scholarship_program`");
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                          } catch(Exception $e) {
                            $_SESSION['error'] = 'Something went wrong accessing list of scholarship program.';
                          }
                          foreach($result as $row) {
                            echo " <option value='".$row['id']."'>".$row['name']."</option>";
                          }
                          ?>
                          <img src="../assets/img/loading.gif" width="35" id="load1" style="display:none;" />
                        </select>
                        <div class="input-group-prepend">
                          <span class="input-group-text" data-toggle="tooltip" data-placement="top" title="Please enter a scholarship program."><i class="fas fa-question-circle"></i></span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                    <label class="form-control-label">Password</label>
                      <div class="input-group input-group-merge input-group-alternative">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                        </div>
						            <?php $password=generate_password();?>
                        <input class="form-control" type="password" id='password_default' name="password_default" placeholder="Password" value="<?php echo $password?>" readonly="readonly">
                      </div>
                      <small>Default password:</small><small style="color:red;"> auto generated</small>
                    </div>
                    <div class="text-center pb-0">
                      <button type="submit" id="submit" name="add" class="btn btn-primary my-4">Add User</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

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
						  <h3 class="mb-0">Scholarship Providers</h3>
						</div>
						<div class="col-6 text-right">
						  <a type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-primary btn-round btn-icon" style="color:white;">
							<span class="btn-inner--icon"><i class="fas fa-user-plus" style="color:white;"></i></span>
							<span class="btn-inner--text" style="color:white;"> New</span>
						  </a>
						</div>
					  </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive py-4">
              <table class="table align-items-center table-flush table-striped" id="datatable-buttons">
                <thead class="thead-light">
                  <tr>
					 <th>Name</th>
                     <th>Date Created</th>
                     <th>Email</th>
                     <th>Scholarship</th>
                     <th>Position</th>
                     <th>Status</th>
                     <th>Options</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Name</th>
                     <th>Date Created</th>
                     <th>Email</th>
                     <th>Scholarship</th>
                     <th>Position</th>
                     <th>Status</th>
                     <th>Options</th>
                  </tr>
                </tfoot>
                <tbody>
                  <?php
					try{
					$query = "SELECT 
						`user`.`id` AS `user_id`,
						`user`.`firstname` AS `user_fname`,
						`user`.`lastname` AS `user_lname`,
						`user`.`username` AS `user_username`,
						`user`.`profileImage` AS `user_image`,
						`user`.`isPasswordChanged` AS `pass_changed`,
						`user`.`status` AS `user_status`,
						`staff`.`id` AS `staff_id`,
						`staff`.`sp_id` AS `staff_sp`,
						`staff`.`school_id` AS `staff_school`,
						`staff`.`position_id` AS `staff_position`,
						`staff`.`created_on` AS `staff_created`,
						`scholarship_program`.`id` AS `sp_id`,
						`scholarship_program`.`name` AS `sp_name`,
						`position`.`id` AS `position_id`,
						`position`.`position_name` AS `position_name`,
						`position`.`description` AS `position_description`
					FROM `user`
					INNER JOIN `staff` ON `staff`.`username` = `user`.`username`
					INNER JOIN `scholarship_program` ON `scholarship_program`.`id` = `staff`.`sp_id`
					INNER JOIN `position` ON `position`.`id` = `staff`.`position_id`
					";
					$stmt = $con->prepare($query);
					$stmt->execute();
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
					}catch(Exception $e){
						$_SESSION[]='Something went wrong accessing scholarship providers.';
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
                          <img src="../scholarship-provider/img/<?php echo $userphoto; ?>" class="avatar rounded-circle mr-3">
                        <?php endif; ?>
                        <b>
                        <?php 
						$firstname = isset($row['user_fname']) ? htmlspecialchars($row['user_fname'], ENT_QUOTES, 'UTF-8') : '';
						$lastname = isset($row['user_lname']) ? htmlspecialchars($row['user_lname'], ENT_QUOTES, 'UTF-8') : '';
						$name=short_text($firstname.' '.$lastname);
                        echo $name;
						?>
                        </b>
						</td>
						<td>
                        <span class="text-muted">
						<?php 
							$created_on = isset($row['staff_created']) ? htmlspecialchars(created_on($row['staff_created']), ENT_QUOTES, 'UTF-8') : '';
							echo $created_on;
                        ?>
						</span>
						</td>
						<td>
                        <a href="mailto:<?php 
						$username= isset($row['user_username']) ? htmlspecialchars($row['user_username'], ENT_QUOTES, 'UTF-8') : '';
						echo $username; ?>" class="font-weight-bold"><?php 
						$username =short_text($username);
						echo $username;
						?></a>
                      </td>
                      <td>
                        <?php 
						$sp= isset($row['sp_name']) ? htmlspecialchars(short_text($row['sp_name']), ENT_QUOTES, 'UTF-8') : '';
                        echo $sp;
                        ?>
                      </td>
                      <td>
						<?php 
						$position= isset($row['position_name']) ? htmlspecialchars(short_text($row['position_name']), ENT_QUOTES, 'UTF-8') : '';
						$position = $row['position_name'];
						echo $position;
                        ?>
                      </td>
                      <td>
                        <?php $status = $row['user_status'];
                        if ($status > 0) {
                          echo '<span class="badge badge-success">Active</span>';
                        } else {
                          echo '<span class="badge badge-danger">Inactive</span>';
                        }
                        ?>
                      </td>
                      <td class="text-right">
                        <div class="dropdown">
                          <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <?php $status = $row['user_status'];
                            if ($status > 0) :
                            ?>
                              <a class="dropdown-item" href="scholarship_provider_controller.php?id=<?php echo $row['user_id'] ?>&off=0"><i class="fas fa-lock" style="color:#fb6340;"></i> Deactivate Account</a>
                            <?php else : ?>
                              <a class="dropdown-item" href="scholarship_provider_controller.php?id=<?php echo $row['user_id'] ?>&on=1"><i class="fas fa-lock-open" style="color:#2dce89;"></i>Activate Account</span></a>
                            <?php endif; ?>
                            <a class="dropdown-item" href="scholarship_provider_edit.php?id=<?php echo $row['user_id'] ?>" style="color: black;" type="button"><i class="fas fa-pen" style="color:#172b4d;"></i> Edit Account</a>
                           
							<a class="dropdown-item" href="scholarship_provider_controller.php?id=<?php echo $row['user_id'] ?>&reset=reset" onClick="return confirm('Are you sure you want to reset password, <?php echo htmlentities($row['user_username']); ?> ?')" style="color: black;" type="button"><i class="fas fa-key" style="color:#5e72e4;"></i> Reset Password</a>
                            <a class="dropdown-item" href="scholarship_provider_controller.php?id=<?php echo $row['user_id'] ?>&del=delete" onClick="return confirm('Are you sure you want to clear, <?php echo htmlentities($row['user_username']); ?> ?')"><i class="fas fa-trash" style="color:#f5365c;"></i> Delete Account</a>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php
                  } 
				  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>-->
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
