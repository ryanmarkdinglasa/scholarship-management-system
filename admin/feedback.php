<?php
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "";
	$parentpage_link= "#";
	$currentpage='Feedback';
	$page=$childpage = "feedback";

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
            <div class="card-header border-0">
              <div class="row">
                <div class="col-6">
                  <h3 class="mb-0">Feedback/Report List</h3>
                </div>
              </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
              <table class="table align-items-center table-flush table-striped" id="datatable-buttons">
                <thead class="thead-light">
                  <tr>
                    <th>No.</th>
                    <th>Sender</th>
                    <th>Title</th>
                    <th>Details</th>
					          <th>Date Created</th>
                    <th>Status</th>
                    <th>Option</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
					try{
					$sql = "SELECT feedback.*,`feedback`.`id` AS f_id,`user`.`username` FROM `feedback`
					INNER JOIN `user` ON `user`.`id` =`feedback`.`user_id`";
                    $query = $con->query($sql);
					}catch(Exception $e){
					$_SESSION['error']='Something went wrong in accessing feedback.';
					}
					$cnt = 1;
					
                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                    <tr>
						<td class="table-user">
							<?php 
								$feedbackID = $row['id'];
								echo"<span class='text-muted'> ".$cnt."</span>";
							?>
						</td>
						
                      <td>
                        <a href="mailto:<?php echo
                          $username = isset($row['username']) ? htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') : '';
                          $username; ?>" class="font-weight-bold"><?php 
                          $username=short_text($username);
                          echo $username; ?>
                        </a>
                      </td>
                      <td>
                        <span class="text-muted"><?php 
                          $title= isset($row['title']) ? htmlspecialchars(short_text($row['title']), ENT_QUOTES, 'UTF-8') : '';
                          echo $title;?>
                        </span>
                      </td>
                      <td>
                        <span class="text-muted"><small><?php 
                          //$content = isset($row['content']) ? htmlspecialchars(short_text($row['content']), ENT_QUOTES, 'UTF-8') : '';
                          echo short_text($row['content']);?>
                          </small>
                        </span>
                      </td>
                      <td>
                        <span class="text-muted"><?php
                        $created_on = isset($row['created_on']) ? htmlspecialchars(created_on($row['created_on']), ENT_QUOTES, 'UTF-8') : '';
                        echo $created_on; ?></span>
                      </td>
                      <td>
                        <?php $status = $row['status'];
                        if ($status >0) {
                          echo '<span class="badge badge-success">Done</span>';
                        } else {
                          echo '<span class="badge badge-danger">On Progress</span>';
                        }
                        ?>
                      </td>
                      <td class="text-right">
                        <div class="dropdown">
                          <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
								<a class="dropdown-item" href="feedback_view.php?id=<?php echo $row['f_id'] ?>"><i class="fas fa-eye text-primary"></i> View Feedback</a>
                            <?php $status = $row['status'];
								if ($status >0):
                            ?>
								<a class="dropdown-item" href="feedback_controller.php?id=<?php echo $row['f_id'] ?>&off=0"><i class="fas fa-times" style="color:#fb6340;"></i> Mark as on-progress</span></a>
                            <?php	else : ?>
								<a class="dropdown-item" href="feedback_controller.php?id=<?php echo $row['f_id'] ?>&on=1"><i class="fas fa-check" style="color:#2dce89;"></i> Mark as done</a>
                            <?php 	endif; ?>
								
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php $cnt = $cnt + 1;
					}//while 
				
				?>
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
          location.href = "feedback.php";
        };
      </script>


    </div>
  </div>

  </body>

  </html>
