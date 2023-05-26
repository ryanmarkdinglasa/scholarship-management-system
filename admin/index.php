<?php
	session_start();
	error_reporting(0);
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "dashboard";
	date_default_timezone_set('Asia/Manila'); // change according timezone
	$currentTime = date('d-m-Y h:i:s A', time());
	//
	function getPercentageChange($oldNumber, $newNumber){
		$decreaseValue = $newNumber - $oldNumber;
		return ($decreaseValue / $oldNumber) * 100;
		echo getPercentageChange(500, 234);
	}
    //
	$year=$years = date('Y');
	$semester_ini = date('n');
	if ($semester_ini <= 6) {
		$semester_ini = ($years - 1).'2';
	} else {
		$semester_ini = '1';
	}
	//
	try{
		$get_year=(isset($_GET['year'])&& $_GET['year']!='')?$_GET['year']:'';
		if($get_year<$years){
			$year_today=date('Y');
		}else{
			$year_today=$get_year;
		}
	}catch(Exception $e){

	}
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
				</div>
			  </div>
			</div>
			
			<!-- Page content -->
			<div class="container-fluid mt--6 ">
			  <div class="row">	
				<div class="col-xl-12">
				  <div class="card bg-white">
					<div class="card-header ">
					  <div class="row align-items-center">
						<div class="col-xl-4">
						  <h6 class="text-black text-uppercase ls-1 mb-1">LOG HISTORY</h6>
						  <h2 class="h3 text-primary mb-0 text-uppercase">RECURRENT USER</h2>
						</div>
						<div class="col-xl-8">
						<form class="form-inline float-right">
							<div class="form-group">
							<label>Select Year: </label>
							<select class="form-control input-sm" id="select_year">
								<option value="">
									<?php if(empty($get_year)){echo 'Select Year';}
											else{echo $get_year;}
									?>
								</option>
								<?php
									$current_year = date('Y');
									for ($i = $current_year; $i <= $current_year + 50; $i++) {
									//$selected = ($i == $year_today) ? 'selected' : '';
									echo "<option value='$i' $selected>$i</option>";
									}
								?>
							</select>
							</div>
						</form>
						</div>
					  </div>
					</div>
					<div class="card-body " style="width:100%">
					<div class="text-center">
						<label class="form-control-label text-primary">Student</label> |
						<label class="form-control-label">Scholarship Provider</label>
					</div>
					  <div class="chart text-white">
						  <canvas id="user-log-chart"  height="95px;"></canvas>
					  </div>
					</div>
				  </div>
				</div>
				<!--<div class="col-xl-4">
				  <div class="card">
					<div class="card-header bg-transparent">
					  <div class="row align-items-center">
						<div class="col">
						  <h6 class="text-uppercase text-muted ls-1 mb-1">HISTORY</h6>
						  <h5 class="h3 text-primary mb-0 text-uppercase">Submission</h5>
						</div>
					  </div>
					</div>
					<div class="card-body">
					  <div class="chart">
						<canvas id="chart-pie" class="chart-canvas"></canvas>
					  </div>
					</div>
				  </div>
				</div>-->
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
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>

</script>
	<?php
	

	//$year=$_GET['year'];
	$months = array();
	$return = array();
	$borrow = array();
	for( $m = 1; $m <= 12; $m++ ) {
		//scholarship-provider
		$sql = "SELECT * FROM `user_log` WHERE `type`='scholarship-provider' AND MONTH(sign_in) = '$m' AND YEAR(sign_in) = '$year_today'";
		$stmt = $con->prepare($sql);
		$stmt->execute();
		$result=$stmt->rowCount();
		array_push($return, $result);
	
		//student
		$sql = "SELECT * FROM `user_log` WHERE `type`='student' AND MONTH(sign_out) ='$m' AND YEAR(sign_out) = '$year_today'";
		$stmt = $con->prepare($sql);
		$stmt->execute();
		$result=$stmt->rowCount();
		array_push($borrow, $result);
		
	
		$num = str_pad( $m, 2, 0, STR_PAD_LEFT );
		$month =  date('M', mktime(0, 0, 0, $m, 1));
		array_push($months, $month);
	}
	
	$months = json_encode($months);
	$return = json_encode($return);
	$borrow = json_encode($borrow);
	
	?>
	<script src="../assets/chart-js/Chart.js"></script>
	<script>
$(function(){
  var barChartCanvas = $('#user-log-chart').get(0).getContext('2d')
  var barChart = new Chart(barChartCanvas)
  var barChartData = {
    labels  : <?php echo $months; ?>,
    datasets: [
      {
        label               : 'Borrow',
        fillColor           : 'rgba(94, 114, 228, 1)',
        strokeColor         : 'rgba(94, 114, 228, 1)',
        pointColor          : 'rgba(94, 114, 228, 1)',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        data                : <?php echo $borrow; ?>
      },
      {
        label               : 'Return',
        fillColor           : 'rgba(23,43,77,0.9)',
        strokeColor         : 'rgba(23,43,77,0.8)',
        pointColor          : '#172B4D',
        pointStrokeColor    : 'rgba(23,43,77,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(23,43,77,1)',
        data                : <?php echo $return; ?>
      }
    ]
  }
  barChartData.datasets[1].fillColor   = '#172B4D'
  barChartData.datasets[1].strokeColor = '#172B4D'
  barChartData.datasets[1].pointColor  = '#172B4D'
  var barChartOptions                  = {
    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero        : true,
    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines      : true,
    //String - Colour of the grid lines
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    //Number - Width of the grid lines
    scaleGridLineWidth      : 1,
    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines  : true,
    //Boolean - If there is a stroke on each bar
    barShowStroke           : true,
    //Number - Pixel width of the bar stroke
    barStrokeWidth          : 2,
    //Number - Spacing between each of the X value sets
    barValueSpacing         : 5,
    //Number - Spacing between data sets within X values
    barDatasetSpacing       : 1,
    //String - A legend template
    legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
    //Boolean - whether to make the chart responsive
    responsive              : true,
    maintainAspectRatio     : true
  }

  barChartOptions.datasetFill = false
  var myChart = barChart.Bar(barChartData, barChartOptions)
  document.getElementById('legend').innerHTML = myhCart.generateLegend();
});
</script>
<script>
$(function(){
  $('#select_year').change(function(){
    window.location.href = 'index.php?year='+$(this).val();
  });
});
</script>
</html>