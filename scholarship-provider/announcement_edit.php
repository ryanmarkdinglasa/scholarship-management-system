<?php
	error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$currentpage=$page='announcement';
	include("include/header.php");
	
	//SESSION TO EDIT
	if(!isset($_GET['id'])){
		header('location:404.php');
		exit();
	}
	$row=$check_id=getrecord('post',['id'],[$_GET['id']]);
	if(empty($check_id)){
		header('location:404.php');
		exit();
	}
	//
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
										<li class="breadcrumb-item"><a href="announcement.php">Announcement</a></li>
										<li class="breadcrumb-item active" aria-current="page">Edit Announcement
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
						<h3 class="mb-0">Edit Announcement</h3>
					</div>
					<!-- Card body -->
					<div class="card-body">
						<!-- Form groups used in grid -->
						<form action="announcement_controller.php"role="form" method="POST">
								<div class="col-md-4">
									<div class="form-group">
										<label class="form-control-label" for="exampleFormControlInput1">Announcement Title</label>
										<input name="post_id" type="hidden" class="form-control"  value="<?php echo$row['id']?>">
										<input name="title" type="text" class="form-control" id="exampleFormControlInput1" value="<?php 
										$title = isset($row['title']) ? htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') : '';
										echo$title?>" placeholder="Announcement Title"required>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label class="form-control-label" for="exampleFormControlInput2">Announcement Details</label>
										<textarea class="ckeditor"  name="context" id="exampleFormControlTextarea2" rows="8" resize="none"required> <?php 
										$context = isset($row['context']) ? $row['context'] : '';
										echo $context;?></textarea>
									</div>
									<div class="text-left">
										<button type="submit" id="edit" name="edit" class="btn btn-primary my-2">Post Announcement</button>
									</div>
								</div>
						</form>
					</div>
				</div>
				<?php
				include("include/footer.php"); 
				?>
			</div>
		</div>
    </body>
</html>
