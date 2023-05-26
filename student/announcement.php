<?php
	error_reporting(0);
	session_start();
	date_default_timezone_set('Asia/Manila');
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$currentpage=$page='announcement';
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
			<div class="header bg-primary pb-6">
				<div class="container-fluid">
					<div class="header-body">
						<div class="row align-items-center py-4">
							<div class="col-lg-6 col-7">
								<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
									<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
										<li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i></a></li>
										<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
										<li class="breadcrumb-item active" aria-current="page">Announcement
										
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-lg-6 col-5 text-right">
							</div>
						</div>
					</div>
				</div>
			</div>
        <!-- Batas Header & Breadcrumbs -->
        <!-- Page content -->
			<div class="container-fluid mt--6">
				<div class="card mb-4">
					<!-- Card header -->
					<div class="card-header">
						<h3 class="mb-4">Scholarship Program Announcement</h3>
					</div>
					<!-- Card body -->
					
				</div>
				<div class="row">
					<?php
					try{
						$sql = "SELECT 
						`post`.`id` AS `post_id`,
						`post`.`user_id` AS `post_user`,
						`post`.`title` AS `post_title`,
						`post`.`context` AS `post_context`,
						`post`.`status` AS `post_status`,
						`post`.`created_on` AS `post_on`,
						`scholarship_program`.`name` AS `sp_name`,
						`scholarship_program`.`img` AS `sp_img`
					FROM `post` 
					INNER JOIN `scholarship_program` ON `scholarship_program`.`id`= `post`.`sp_id`
					WHERE `post`.`status`='1' 
					ORDER BY `post_on` DESC";
					
						$query = $con->query($sql);
						$count=$query->rowCount();
						if($count<1){
					?>
						<div class="col-xl-12">
							<div class="card">
							  <div class="card-header">
								<h6 class="text-black text-uppercase ls-1 mb-1">ANNOUNCEMENT</h6>
								<h5 class="h3 text-uppercase text-primary mb-0"> </h5>
								<span class="text-muted"><?php //echo $row['created_on']; ?></h5>
							  </div>
							  <div class='card-body'>No Data Found
								<?php //echo $row['context']; ?>
							  </div>
							</div>
						</div>
					<?php
						}
						while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
							
					?>
					<div class="col-xl-12">
						<div class="card">
						  <div class="card-header">
							<div class="poster">
							<?php 
							$userphoto = isset($row['sp_img']) ? htmlspecialchars($row['sp_img'], ENT_QUOTES, 'UTF-8') : '';
							$firstname = isset($row['sp_name']) ? htmlspecialchars($row['sp_name'], ENT_QUOTES, 'UTF-8') : '';
							$lastname = isset($row['user_lname']) ? htmlspecialchars($row['user_lname'], ENT_QUOTES, 'UTF-8') : '';
							$post_on = isset($row['post_on']) ? htmlspecialchars(created_on($row['post_on']), ENT_QUOTES, 'UTF-8') : '';
							if ($userphoto == "" || $userphoto == "NULL") :
								 echo' <img src="img/profile.png" class="avatar rounded-circle mr-3">';
								else : 
								   echo'<img src="../scholarship-provider/img/'.htmlentities($userphoto).'" class="avatar rounded-circle mr-3">';
								endif;
								//Posted By & Posted On
								echo '<h5 class="text-black  mb-0">'.$firstname.'<br>';
								echo'<span class="text-muted""><small>';
								echo $post_on;
								echo'</small></span></h5>';
								?>
								
								
							</div>
							
						  </div>
						  <div class='card-body'>
							<h5 class="h3 text-uppercase text-primary mb-0"><?php
							$title = isset($row['post_title']) ? htmlspecialchars($row['post_title'], ENT_QUOTES, 'UTF-8') : '';
							echo $title; ?></h5>
							<?php 
							$context = isset($row['post_context']) ? $row['post_context'] : '';
							echo $context; ?>
						  </div>
						</div>
					</div>
					<?php	
						}//while
					}catch(Exception $e){
						$_SESSION['error']='Something went wrong in accessing annoouncement post.';
					}
					?>
				</div>
				<?php
				include("include/footer.php"); 
				?>
			</div>
		</div>
    </body>
</html>
