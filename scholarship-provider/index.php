<?php
	session_start();
	error_reporting(0);
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "dashboard";
	//
	function getPercentageChange($oldNumber, $newNumber){
		$decreaseValue = $newNumber - $oldNumber;
		return ($decreaseValue / $oldNumber) * 100;
		echo getPercentageChange(500, 234);
	}
    //
	$year = date('Y');
	$semester_ini = date('n');
	if ($semester_ini <= 6) {
		$semester_ini = ($year - 1).'2';
	} else {
		$semester_ini = '1';
	}
	//
	include("include/header.php");
?>
	<script>
		if (window.history.replaceState) {
		  window.history.replaceState(null, null, window.location.href);
		}
	</script>
	<style>

	</style>
	</head>
		<?php
			include("include/sidebar.php");
		?>
		<!-- ain content -->
		<div class="main-content" id="panel">
			<?php
				 include("include/topnav.php");
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
			<!-- Header -->
			<div class="header bg-primary pb-6">
			  <div class="container-fluid">
				<div class="header-body">
				  <div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
					  <h6 class="h2 text-white d-inline-block mb-0">Dashboard</h6>
					  <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
						<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
						  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i></a></li>
						  <li class="breadcrumb-item active" aria-current="page">Dashboard
						  </li>
						</ol>
					  </nav>
					</div>
					<div class="col-lg-6 col-5 text-right">

					</div>
				  </div>
				  <!-- Card stats -->
				 
                  
                    
				  <div class="row">
				
			
					<div class="col-xl-6 col-md-6">
					  <div class="card card2 card-stats">
					  <?php if($user['staff_position']<3){?>
						<div class="card-body">
						  <div class="row">
							<div class="col">
							  <h5 class="card-title text-uppercase text-muted mb-0">Total scholars funds for last year</h5>
							  
							 
							 <span class="h2 font-weight-bold mb-0">
								<?php
									$current_year=date('Y');
									$prev_year=$current_year-1;
									$sql="SELECT `scholar`.*,
									`sp_grant`.`amount_sem` AS `grant_amount`
									 FROM `scholar`
                                     INNER JOIN `sp_grant` ON `sp_grant`.`id`=`scholar`.`award_no` 
                                     WHERE `scholar`.`sp_id`='".$user['staff_sp']."'
									AND YEAR(`scholar`.`created_on`)='".$prev_year."'";
									$stmt=$con->prepare($sql);
									$result=$stmt->execute();
									//FETCH SCHOLAR BUDGET
									if(!$result){ echo "Something went wrong accessing budget report.";}
									$scholar_budget=$stmt->fetchAll(PDO::FETCH_ASSOC);
									//INITIALIZE TOTAL and Sub total
									$total=$subtotal=0;
									foreach($scholar_budget as $scholar){
										$subtotal=intval($scholar['grant_amount']);
										$total+=$subtotal;
									}
									$funds = "Php " . number_format($total, 0, ',');
									echo $funds;  ?>
							</span>
							</div>
							<div class="col-auto">
							  <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
								<i class="fas fa-hand-holding-usd"></i>
							  </div>
							</div>
						  </div>
						   <p class="mt-3 mb-0 text-sm">
							<span class="text-success mr-2"><i class="fa fa-arrow-up"></i> </span>
							<span class="text-nowrap text-black">From last school year</span>
						  </p> 
						</div>
						<?php }?>
					  </div>
					</div>
					<div class="col-xl-6 col-md-6">
					  <div class="card card2 card-stats">
						<!-- Card body -->
						<?php if($user['staff_position']<3){?>
						<div class="card-body">
						  <div class="row">
							<div class="col">
							  <h5 class="card-title text-uppercase text-muted mb-0">TOTAL SCHOLAR FUNDS REQUIRED THIS SCHOOL YEAR</h5>
							  	<span class="h2 font-weight-bold mb-0"><?php
									$sql="SELECT `scholar`.*,
									`sp_grant`.`amount_sem` AS `grant_amount`
									 FROM `scholar`
                                     INNER JOIN `sp_grant` ON `sp_grant`.`id`=`scholar`.`award_no` 
                                     WHERE `scholar`.`sp_id`='".$user['staff_sp']."'
									AND YEAR(`scholar`.`created_on`)='".$current_year."'";
									$stmt=$con->prepare($sql);
									$result=$stmt->execute();
									//FETCH SCHOLAR BUDGET
									if(!$result){ echo "Something went wrong accessing budget report.";}
									$scholar_budget=$stmt->fetchAll(PDO::FETCH_ASSOC);
									//INITIALIZE TOTAL and Sub total
									$total=$subtotal=0;
									foreach($scholar_budget as $scholar){
										$subtotal=intval($scholar['grant_amount']);
										$total+=$subtotal;
									}
									$funds = "Php " . number_format($total, 0, ',');
									echo $funds;   ?>
								</span>
							</div>
							<div class="col-auto">
							  <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
								<i class="fas fa-hand-holding-usd"></i>
							  </div>
							</div>
						  </div>
						   <p class="mt-3 mb-0 text-sm">
							<span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 0%</span>
							<span class="text-nowrap">For current school year</span>
						  </p> 
						</div>
						<?php }?>
					  </div>
					</div>
				  </div>
				</div>
			  </div>
			</div>
			
			<!-- Page content -->
			<div class="container-fluid mt--6">
			  <div class="row">	
				<div class="col-xl-8">
				  <div class="card bg-white">
					<div class="card-header bg-transparent">
					  <div class="row align-items-center">
						<div class="col-xl-4">
						  <h6 class="text-black text-uppercase ls-1 mb-1">HISTORY</h6>
						  <h5 class="h3 text-primary mb-0 text-uppercase">APPLICANTS</h5>
						</div>
						<!--
						<div class="col-xl-8">
							  <ul class="nav nav-pills justify-content-end">
							<li class="nav-item mr-2 mr-md-0">
							  <a href="?id" class="nav-link py-2 px-3 <?php echo"active"; ?>">
								<span class="d-none d-md-block">Scholarship Selection</span>
								<span class="d-md-none"><i class="fas fa-graduation-cap"></i></span>
							  </a>
							</li>
							<li class="nav-item">
							  <a href="?id=scholarship-need" class="nav-link py-2 px-3 <?php echo "active"; ?>">
								<span class="d-none d-md-block">Need Scholarship</span>
								<span class="d-md-none"><i class="fas fa-hand-holding-usd"></i></span>
							  </a>
							</li>
						  </ul>
						</div>-->
					  </div>
					</div>
					<div class="card-body">
					  <div class="chart">
						
						<?php
						$header ='2';
						if ($header == 'scholarship-need') {
						?>
						  <canvas id="history-scholarship-requirement" class="chart-canvas"></canvas>
						<?php } else { ?>
						  <canvas id="chart-line" class="chart-canvas"></canvas>
						<?php }  ?>
					  </div>
					</div>
				  </div>
				</div>
				<div class="col-xl-4">
				  <div class="card">
					<div class="card-header bg-transparent">
					  <div class="row align-items-center">
						<div class="col">
						  <h6 class="text-uppercase text-muted ls-1 mb-1">HISTORY</h6>
						  <h5 class="h3 text-primary mb-0 text-uppercase">POPULATION PER SCHOOL</h5>
						</div>
					  </div>
					</div>
					<div class="card-body">
					  <div class="chart">
						<canvas id="chart-pie" class="chart-canvas"></canvas>
					  </div>
					</div>
				  </div>
				</div>
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
							`user`.`type` AS `user_type`,
							`user`.`profileImage` AS `user_image`,
							`user`.`firstname` AS `user_fname`, `user`.`lastname` AS `user_lname`
							 FROM `post` 
							 INNER JOIN `user` ON `user`.`id`= `post`.`user_id`
							 WHERE `user`.`type`='".$user['type']."' AND `post`.`status`='1' ORDER BY `post_on` DESC";
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
							<?php $userphoto = $row['user_image'];
								if ($userphoto == "" || $userphoto == "NULL") :
								 echo' <img src="img/profile.png" class="avatar rounded-circle mr-3">';
								else : 
								   echo'<img src="img/'.htmlentities($userphoto).'" class="avatar rounded-circle mr-3">';
								endif;
								//Posted By & Posted On
								echo '<h5 class="text-black  mb-0">'.$row['user_fname'].' '.$row['user_lname'].'<br>';
								echo'<span class="text-muted""><small>';
								$post_created=''.$row['post_on'];
								echo created_on($post_created);
								echo'</small></span></h5>';
								?>
								<?php
								if($user['id']==$row['post_user']){
								?>
								<div class="text-right" style="margin-top:-30px;">
									<div class="dropdown">
									  <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-v"></i>
									  </a>
									  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
										<a class="dropdown-item" href="announcement_edit.php?id=<?php echo $row['post_id'] ?>&edit=edit" style="color: black;" type="button"><i class="fas fa-pen" style="color:#172b4d;"></i> Edit post</a>
										<a class="dropdown-item" href="announcement_controller.php?id=<?php echo $row['post_id'] ?>&del=delete" onClick="return confirm('Are you sure you want to clear, <?php echo htmlentities($row['post_title']); ?> ?')"><i class="fas fa-trash" style="color:#f5365c;"></i> Delete post</a>
									  </div>
									</div>
								</div>
								<?php }?>
							</div>
						  </div>
						  <div class='card-body'>
							<h5 class="h3 text-uppercase text-primary mb-0"><?php echo $row['post_title']; ?></h5>
							<?php echo $row['post_context']; ?>
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
					include("include/footer.php"); //Edit topnav on this page
				?>
			</div>
		</div>
	</body>
</html>