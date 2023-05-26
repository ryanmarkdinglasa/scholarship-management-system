<?php
	error_reporting(0);
	session_start();
	date_default_timezone_set('Asia/Manila');
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$currentpage=$page='feedback';

	//ADD
	if (isset($_POST['post'])) {
        $user_id = $user['id'];;
        $title = trim($_POST["title"]);
        $content = trim($_POST["content"]);
		$status=0;
		$created_on = date('Y-m-d H:i:s');
		if(empty($user_id) || empty($title) || empty($content)){
			$_SESSION['error'] = "Please fill in the required fields.";
			header("Location:feedback.php");
			exit();
		}
		if (!addrecord('feedback',['user_id','title','content','status','created_on'],[$user_id,$title,$content,$status,$created_on])){
			$_SESSION['error'] = "Something went wrong in sending feedback.";
			header("Location:feedback.php");
			exit();
		}else{
			$_SESSION['success'] = "Feedback sent.";
			header("Location:feedback.php");
			exit();
		}
    }

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
										<li class="breadcrumb-item active" aria-current="page">Feedback
										
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
						<h3 class="mb-0">Give Feedback</h3>
					</div>
					<!-- Card body -->
					<div class="card-body">
						<!-- Form groups used in grid -->
						<form role="form" method="POST">
								<div class="col-md-4">
								<h6 class="heading-small text-muted">Help Us Improve Iskalar</h6>
									<div class="form-group">
										<label class="form-control-label" for="exampleFormControlInput1">Title</label>
										<input name="title" type="text" class="form-control" id="title" placeholder="What is the feedback/report about?"required>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label class="form-control-label" for="exampleFormControlInput2">Details</label><br>
										<span><small class="text-muted">*Please include as much info as possible...</small></span>
										<textarea class="ckeditor"  name="content" id="content" rows="8" resize="none"  required></textarea>
										
									</div>
									
									<div class="text-left">
										<button type="submit" id="post" name="post" class="feedback btn btn-primary my-2">Submit</button>
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
		<script>
			$(document).on("click", ".feedback",function(){
				var lettersRegex = /^[a-zA-Z\s]+$/;
				var numbersRegex = /^[0-9]+$/;
				var specialCharactersRegex = /^[a-zA-Z0-9\s]*$/;	
				//check Email Input
				var title=document.getElementById("title").value.trim();
				if (title==='') {
					 $("#title").css({ 
						"border" :"1px solid red",
					});
					$("#title").fadeIn("slow");
					$("#title").focus();
				   return false;
				}
				if(content===''){
					 $("#content").css({ 
						"border" :"1px solid red",
						"color" :"red",
					});
					$("#content").fadeIn("slow");
					$("#content").focus();
					return false;
				}
			});
		</script>
    </body>
</html>
