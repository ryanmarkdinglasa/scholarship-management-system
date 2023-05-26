<style>
  .user{
    cursor:pointer;
   padding:2px 2px;
    border-radius:2px;
   / border:1px solid red;
    width:200px;
  }
  .user:hover{
    background:rgba(0,0,0,0.1);
  }
</style>
<body>
  <!-- Sidenav -->
  <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main"
  style="margin-top:50px;">
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header d-flex align-items-center">
        <a class="navbar-brand" href="profile.php">
        <div class="media align-items-center user">
          <span class="avatar avatar-sm rounded-circle">
              <?php $userphoto = $user['profileImage']; //user's photo
                if ($userphoto == "") :
              ?>
              <img src="img/profile.png" alt="Image placeholder" style="width:40px;height:40px;border-radius:50%;">
            <?php else : ?>
              <img alt="Image placeholder" src="img/<?php echo htmlentities($userphoto); ?>" style="width:40px;height:40px;border-radius:50%;">
            <?php endif; ?>
          </span>
          <div class="dropdown-header noti-title">
				    <div class="media-body ml-2 d-none d-lg-block">
              <h5 class=" m-0 text-sm  font-weight-bold">
						    <?php echo  htmlentities($user['firstname'].' '.$user['lastname']); //user's name ?>
					    </h5>
              <h6 class="text-overflow m-0" data-toggle="tooltip" data-placement="left" title="User">
                <?php echo htmlentities($user['program_name']); //position ?>
              </h6>
				    </div>
          </div>
        </div>
        </a>
        <div class="ml-auto">
          <!-- Sidenav toggler -->
          <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
              <span class="avatar avatar-sm rounded-circle" style="margin-left:-10px;">
                <?php $userphoto = $user['profileImage']; //user's photo
                  if ($userphoto == "") :
                ?>
                <img src="img/profile.png" alt="Image placeholder">
              <?php else : ?>
                <img alt="Image placeholder" src="img/<?php echo htmlentities($userphoto); ?>" style="width:40px;height:35px;border-radius:50%;">
              <?php endif; ?>
            </span>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
      
          <!-- Nav items -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link <?php echo ($parentpage == "dashboard" ? "active" : "") ?>" href="./">
                <i class="ni ni-shop text-primary"></i>
                <span class="nav-link-text">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($parentpage == "Scholarship" ? "active" : "") ?>" href="#navbar-scholarship" data-toggle="collapse" role="button" aria-expanded="<?php echo ($parentpage == "account" ? "true" : "false") ?>" aria-controls="navbar-dataaccount">
                <i class="ni ni-money-coins text-primary"></i>
                <span class="nav-link-text">Scholarship</span>
              </a>
              <div class="collapse <?php echo ($parentpage == "Scholarship" ? "show" : "") ?>" id="navbar-scholarship">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item">
                    <a href="scholarship_program.php" class="nav-link  <?php echo ($childpage == "scholarship_program" ? "active" : "") ?>">Scholarship Program</a>
                  </li>
                  <?php if($user['staff_position']<2){?>
                  <li class="nav-item">
                    <a href="scholarship_requirement.php" class="nav-link <?php echo ($childpage == "scholarship_requirement" ? "active" : "") ?>">Requirements</a>
                  </li>
				  <li class="nav-item">
                    <a href="scholarship_restriction.php" class="nav-link <?php echo ($childpage == "scholarship_restriction" ? "active" : "") ?>">Restrictions</a>
                  </li>
                  <li class="nav-item">
                    <a href="scholarship_grant.php" class="nav-link <?php echo ($childpage == "scholarship_grant" ? "active" : "") ?>">Scholarship Grants</a>
                  </li>
                  <li class="nav-item">
                    <a href="partnered_school.php" class="nav-link <?php echo ($childpage == "school" ? "active" : "") ?>">Partnered Schools</a>
                  </li>
                  
                  <li class="nav-item">
                    <a href="scholarship_open.php" class="nav-link <?php echo ($childpage == "scholarship_open" ? "active" : "") ?>">Opening Scholarship</a>
                  </li>  
                  <?php }?>
                </ul>
              </div>
            </li>
            <?php if($user['staff_position']<3){?>
            <li class="nav-item">
              <a class="nav-link <?php echo ($parentpage == "Submission" ? "active" : "") ?>" href="#navbar-submissinon" data-toggle="collapse" role="button" aria-expanded="<?php echo ($parentpage == "account" ? "true" : "false") ?>" aria-controls="navbar-dataaccount">
                <i class="ni ni-single-copy-04 text-primary"></i>
                <span class="nav-link-text">Submissions</span>
              </a>
              <div class="collapse <?php echo ($parentpage == "Submission" ? "show" : "") ?>" id="navbar-submissinon">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item">
                    <a href="application_pending.php" class="nav-link  <?php echo ($childpage == "pending" ? "active" : "") ?>">Pending Applications</a>
                  </li>
                  <li class="nav-item">
                    <a href="application_processed.php" class="nav-link <?php echo ($childpage == "processed" ? "active" : "") ?>">Processed Applications</a>
                  </li>
                  <li class="nav-item">
                    <a href="application_history.php" class="nav-link <?php echo ($childpage == "submission_history" ? "active" : "") ?>">Submission History</a>
                  </li>
                </ul>
              </div>
            </li>
            <?php }?>
            <li class="nav-item">
              <a class="nav-link <?php echo ($page == "Scholars" ? "active" : "") ?>" href="scholar.php">
                <i class="ni ni-hat-3  text-primary"></i>
                <span class="nav-link-text">Scholars</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($page == "Report" ? "active" : "") ?>" href="report.php">
                <i class="ni ni-archive-2  text-primary"></i>
                <span class="nav-link-text">Reports</span>
              </a>
            </li
            <?php if($user['staff_position']==1){?>
            <li class="nav-item">
              <a class="nav-link <?php echo ($page == "Staffs" ? "active" : "") ?>" href="staff.php">
                <i class="ni ni-badge  text-primary"></i>
                <span class="nav-link-text">Staffs</span>
              </a>
            </li>
            <?php }?>
            <!--
            <li class="nav-item">
              <a class="nav-link <?php echo ($page == "Messages" ? "active" : "") ?>" href="messages.php">
                <i class="ni ni-chat-round  text-primary"></i>
                <span class="nav-link-text">Messages</span>
              </a>
            </li>-->
            <li class="nav-item">
              <a class="nav-link <?php echo ($page == "announcement" ? "active" : "") ?>" href="announcement.php">
                <i class="ni ni-notification-70 text-primary"></i>
                <span class="nav-link-text">Announcement</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($page == "Notification" ? "active" : "") ?>" href="notification.php">
                <i class="ni ni-bell-55 text-primary"></i>
                <span class="nav-link-text">Notification</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($page == "Calendar" ? "active" : "") ?>" href="calendar.php">
                <i class="ni ni-calendar-grid-58 text-primary"></i>
                <span class="nav-link-text">Calendar</span>
              </a>
            </li>
            <?php if($user['staff_position']<2){?>
            <!--<li class="nav-item">
              <a class="nav-link <?php echo ($page == "userlog" ? "active" : "") ?>" href="userlog.php">
                <i class="fas fa-history text-primary"></i>
                <span class="nav-link-text">Staff Log History</span>
              </a>
            </li>-->
            <?php }?>
          </ul>
          <!-- Divider -->
          <hr class="my-3">
          <!-- Heading -->
          <h6 class="navbar-heading p-0 text-muted">External Link</h6>
          <!-- Navigation -->
          <ul class="navbar-nav mb-md-4">
            <li class="nav-item">
              <a class="nav-link" href="https://www.facebook.com/profile.php?id=100087945312545" target="._blank">
                <i class="fab fa-facebook"></i>
                <span class="nav-link-text">Iskalar Page</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>