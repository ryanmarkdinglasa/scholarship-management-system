<?php
	include("include/session.php");
?>
	<style>
	.notification{
		background:#FFF;
		color:#5E72E4;
	}
	.notification:hover{
		background:rgba(255,255,255,0.7);
	}
	.notification-count{
		width:15px;
		height:15px;
		text-align:center;
		border-radius:50%;
		background:red;
		color:white;
		line-height:9px;
		font-size:10px;
		font-weight:700;
		padding:2px 2px;
		position:abosulute;
		margin:0px -10px;
		
	}
	</style>
  <!-- Topnav -->
  <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom fixed-top " style="height:50px;">
    <div class="container-fluid">
    <div class="">
        <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
          <div class="btn btn-white text-center  px-2" style="text-align:center;">
            <div class="sidenav-toggler-inner text-center " style="width:30px;">
              <i class="ni ni-align-left-2" style="display:none;"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              </div>
            </div>
        </div>
      </div>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="" style="width:80px;height:40px;margin-left:20px;padding:4px 4px;">
        <a class="" href="./">
          <img src="../assets/img/brand/white.png" style="width:75px;height:30px;line-height:5px;" class="navbar-brand-img" alt="...">
        </a>
        </div>
        <!-- Search form -->
        <!-- Navbar links -->
        <ul class="navbar-nav align-items-center ml-md-auto">
          <li class="nav-item dropdown">
            <p class="nav-link mb-0 text-sm  font-weight-bold text-white" href="#">

              <i class="ni ni-calendar-grid-58"></i>
              <?php
              
              $year = date('Y');
              $year_period = date('n');
              if ($year_period <= 6) {
                $year_period = intval($year - 1) . "/" . $year .  " ";
                echo $year_period;
              } else {
                $year_period = $year . "/" . intval($year + 1). " ";
                echo $year_period;
              }?>
            </p>
          </li>
          <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="notification.php" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
				<span class="avatar avatar-sm rounded-circle notification">
                <i class="fa fa-bell cursor-pointer "></i>
              </a>
			  </span>
			  <?php
				$noti =count_notification('notification',$user['id']);
					if($noti>0){
						echo'<span class="notification-count text-muted text-white" style="margin-top:20px;">'.$noti.'</span>';
					}
			  ?>
		  </li>
        </ul>
        <ul class="navbar-nav align-items-center ml-auto ml-md-0">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <?php $userphoto = $user['profileImage']; //user's photo
                    if ($userphoto == "") :
                    ?>
                      <img src="img/profile.png" alt="Image placeholder">
                    <?php else : ?>
                      <img alt="Image placeholder" src="img/<?php echo htmlentities($userphoto); ?>" style="width:40px;height:35px;border-radius:50%;">
                    <?php endif; ?>
                  </span>
                 
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-header noti-title">
				 <div class="media-body ml-2 d-none d-lg-block">
                    <h5 class=" m-0 text-sm  font-weight-bold">
						<?php echo  htmlentities($user['firstname'].' '.$user['lastname']); //user's name ?>
					</h5>
					<h6 class="text-overflow m-0" data-toggle="tooltip" data-placement="left" title="Username">
						<?php echo htmlentities($_SESSION['type']); //position ?>
					</h6>
				</div>
				
            </div>
			<div class="dropdown-divider"></div>
				<a href="profile.php" class="dropdown-item">
				  <i class="ni ni-single-02"></i>
				 <span>Profile</span>
				</a> 
				<!-- <a href="#!" class="dropdown-item">
				  <i class="ni ni-settings-gear-65"></i>
				  <span>Settings</span>
				</a> -->
				<a href="calendar.php" class="dropdown-item">
				  <i class="ni ni-calendar-grid-58"></i>
				  <span>Calendar </span>
				</a>
				<a href="logout.php" class="dropdown-item">
				  <i class="ni ni-user-run"></i>
				  <span>Logout</span>
				</a>	
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <br><br>