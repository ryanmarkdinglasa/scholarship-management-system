<?php
	error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "";
	$parentpage_link= "#";
	$page=$currentpage='Notification';
?>
  <?php
  include("include/header.php");
  ?>
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
	<?php include"include/breadcrumbs.php";?>
    <!--  Header & Breadcrumbs -->
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <!-- Table -->
      <div class="row">
        <div class="col">

          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
              <div class="row">
                <div class="col-6">
                  <h3 class="mb-0">Notifications</h3>
                </div>
                <div class="col-6 text-right">
                </div>
              </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive notifcation-table">
				<table class="table align-items-center table-flush table-striped" >
					<thead class="thead-light">
					  <tr>
						<th>Date</th>
						<th>Sender</th>
						<th>Details</th>
						<th>Status</th>
						<th>Option</th>
					  </tr>
					</thead>
					<tfoot class="thead-light">
					  <tr>
						<th>Date</th>
						<th>Sender</th>
						<th>Details</th>
						<th>Status</th>
						<th>Option</th>
					  </tr>
					</tfoot>
					<tbody>
					  <?php 
					  try{
						$sql = "SELECT 
						`notification`.`id` AS `n_id`,
						`notification`.`recepient_id`,
						`notification`.`sender_id`,
						`notification`.`content` AS `details`,
						`notification`.`status` AS `n_status`,
						`notification`.`created_on`,
						`user`.`firstname` AS `user_fname`,
						`user`.`lastname` AS `user_lname`
						FROM `notification`
						INNER JOIN `user` ON `user`.`id`=`notification`.`sender_id`
						WHERE `recepient_id`='".$user['id']."'";
						$query = $con->query($sql);
					  }catch(Exception $e){
						  $_SESSION['error']='Something went wrong in accessing your notifications.';
					  }
						$cnt = 1;
						while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
					  ?>
						<tr>
							</style>
						  <td class="table-user">
							<span class="<?php 
							$created_on = isset($row['created_on']) ? htmlspecialchars(created_on($row['created_on']), ENT_QUOTES, 'UTF-8') : '';
							if($row['n_status']!=1){echo'font-weight-bold';}else{echo'text-muted';}?>">
							<?php echo $created_on; ?></span>
						  </td>
						  <td>
							<span class="<?php 
							$firstname = isset($row['user_fname']) ? htmlspecialchars($row['user_fname'], ENT_QUOTES, 'UTF-8') : '';
							$lastname = isset($row['user_lname']) ? htmlspecialchars($row['user_lname'], ENT_QUOTES, 'UTF-8') : '';
							$name=short_text($firstname.' '.$lastname);
							if($row['n_status']!=1){echo'font-weight-bold';}else{echo'text-muted';}?>"><?php echo $name; ?></span>
						  </td>
						  <td>
							<span class="<?php 
							$details = isset($row['details']) ? htmlspecialchars(short_text($row['details']), ENT_QUOTES, 'UTF-8') : '';
							if($row['n_status']!=1){echo'font-weight-bold';}else{echo'text-muted';}?>">
							<?php echo $details; ?></span>
						  </td>
						  <td>
							<?php $status = $row['n_status'];
							if ($status > 0) {
							  echo '<span class="badge badge-success">Read</span>';
							} else {
							  echo '<span class="badge badge-danger">Unread</span>';
							}
							?>
						  </td>
						  <td class="text-right">
							<div class="dropdown">
							  <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-ellipsis-v"></i>
							  </a>
							  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
								<!--<a class="dropdown-item" href="notification_view.php?id=<?php echo $row['n_id'] ?>" ><i class="fas fa-eye text-primary"></i> View Notifcation</a>-->
								<?php $status = $row['n_status'];
								if ($status > 0) :
								?>
								  <a class="dropdown-item" href="notification_controller.php?id=<?php echo $row['n_id'] ?>&off=0"><i class="fas fa-times" style="color:#fb6340;"></i> Mark as Unread</a>
								<?php else : ?>
								  <a class="dropdown-item" href="notification_controller.php?id=<?php echo $row['n_id'] ?>&on=1"><i class="fas fa-check" style="color:#2dce89;"></i> Mark as Read</span></a>
								<?php endif; ?>
								<a class="dropdown-item" href="notification_controller.php?id=<?php echo $row['n_id'] ?>&del=delete" ><i class="fas fa-trash" style="color:#f5365c;"></i> Delete</a>
							  </div>
							  
							</div>
						  </td>
						</tr>
					  <?php $cnt = $cnt + 1;
					  } ?>
					</tbody>
				</table>
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
          location.href = "data_admin.php";
        };
      </script>


    </div>
  </div>

  </body>

  </html>
