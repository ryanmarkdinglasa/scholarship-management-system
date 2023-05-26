<?php
	error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage='';
	$parentpage_link='';
	$page=$currentpage='Scholarship';

 ?>
	<?php
		include("include/header.php");
	?>
	<script>
		function userAvailability() {
		$("#loaderIcon").show();
		jQuery.ajax({
		url: "add_admin_check_username.php",
		data:'username='+$("#username").val(),
		type: "POST",
		success:function(data){
		$("#user-availability-status1").html(data);
		$("#loaderIcon").hide();
		},
		error:function (){}
		});
		}
	</script>
	<script>
		if ( window.history.replaceState ) {
			window.history.replaceState( null, null, window.location.href );
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
					  <li class="breadcrumb-item active" aria-current="page">Scholarship Program</li>
					</ol>
				  </nav>
				</div>
				<div class="col-lg-6 col-5 text-right">
				</div>
			  </div>
			</div>
		  </div>
		</div>
			<!-- Page content -->
			<div class="container-fluid mt--6">
				  <div class="row">
					<div class="col">
					<div class="card">
					<div class="card-header border-0">
					  <div class="row">
						<div class="col-6">
						  <h3 class="mb-0">Scholarship Programs</h3>
						</div>
						<div class="col-6 text-right">
						</div>
					  </div>
					</div>
					<div class="table-responsive">
						<table class="table align-items-center table-flush table-striped" id="datatable-buttons">
						<thead class="thead-light">
						  <tr>
							<th>Scholarship Program Name</th>
							<th>Description</th>
							<th>Options</th>
						  </tr>
						</thead>
						<tbody>
							<?php
							try{
								$sql = "SELECT * FROM `scholarship_program`";
								$query = $con->query($sql);
								$cnt = 1;
								while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
							?>
						  <tr>
							<td class="table-user">
								<?php 
								
								$userphoto=isset($row['img']) ? htmlspecialchars($row['img'], ENT_QUOTES, 'UTF-8') : '';
									if($userphoto=="" || $userphoto=="NULL" ):
								?>
								<img src="img/profile.png" class="avatar rounded-circle mr-3">
								<?php else:?>
									<img src="../scholarship-provider/img/<?php echo $userphoto;?>" class="avatar rounded-circle mr-3">
								<?php endif;?>
								<b>
								<?php 
								$name = isset($row['name']) ? htmlspecialchars(short_text($row['name']), ENT_QUOTES, 'UTF-8') : '';
								echo $name;
								?>
							  </b>
							</td>
							<td>
							  <span class="text-muted"><?php
							  $description = isset($row['description']) ? htmlspecialchars(short_text($row['description']), ENT_QUOTES, 'UTF-8') : '';
							  echo $description;?></span>
							</td>
							<td class="">
							<button class="btn btn-primary text-white" onclick="location.href='<?php echo 'scholarship_program_view.php?id='.$row['id'];?>'" style="color: black;" type="button" >Open</button>
							</td>
						  </tr>
						  <?php  } //while
							}catch(Exception $e){
								$_SESSION['error']='Something went wrong in accessing scholarship program data.';
							}?>
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
		function  toggle_select(id) {
			var X = document.getElementById(id);
			if (X.checked == true) {
			 X.value = "1";
			} else {
			X.value = "0";
			}
		//var sql="update clients set calendar='" + X.value + "' where cli_ID='" + X.id + "' limit 1";
		var who=X.id;
		var chk=X.value
		//alert("Joe is still debugging: (function incomplete/database record was not updated)\n"+ sql);
		  $.ajax({
		//this was the confusing part...did not know how to pass the data to the script
			  url: 'as_status_penyeleksi.php',
			  type: 'post',
			  data: 'who='+who+'&chk='+chk,
			  success: function(output) 
			  { alert('success, server says '+output);
			  },
			  error: function()
			  { alert('something went wrong, save failed');
			  }
		   });
		}
	</script>
	<script type="text/javascript">
	$(function(){
	   $(document).on('click', '.edit', function(e){
		e.preventDefault();
		$('#modal-edit-form').modal('show');
		var id = $(this).data('id');
		document.getElementById('edit_sp_name').value='bogog-mama';
		getRow(id);
	  });
	});
	function getRow(id){
	  $.ajax({
		type: 'POST',
		url: 'scholarship_program_row.php',
		data: {id:id},
		dataType: 'json',
		success: function(response){
		  $('.edit_sp_id').val(response.id);
		  $('#edit_sp_name').val(response.name);
		  $('#edit_sp_description').val(response.description);
		}
	  });
	}
	</script>
	<script type="text/javascript">
	
		document.getElementById("close_direct").onclick = function () {
			location.href = "scholarship_program.php";
		};
	</script>
	<script type="text/javascript">
		$(document).on("click", ".sp-add",function(){
				var lettersRegex = /^[a-zA-Z\s]+$/;
				var numbersRegex = /^[0-9]+$/;
				var specialCharactersRegex = /^[a-zA-Z0-9\s]*$/;
				var sp_name=document.getElementById("sp_name").value.trim();
				if (sp_name==='') {
					$("#sp_name").css({ 
						"border" :"1px solid red",
					});
					$("#sp_name").fadeIn("slow");
					$("#sp_name").focus();
				   return false;
				}
				if (!lettersRegex.test(sp_name)) {
					 $("#sp_name").css({ 
						"border" :"1px solid red",
						"color" :"red",
					});
					$("#sp_name").fadeIn("slow");
					$("#sp_name").focus();
				   return false;
				}
				var sp_description=document.getElementById("sp_description").value.trim();
				if (sp_description==='') {
					$("#sp_description").css({ 
						"border" :"1px solid red",
					});
					$("#sp_description").fadeIn("slow");
					$("#sp_description").focus();
				   return false;
				}
				if (!lettersRegex.test(sp_description)) {
					 $("#sp_description").css({ 
						"border" :"1px solid red",
						"color" :"red",
					});
					$("#sp_description").fadeIn("slow");
					$("#sp_description").focus();
				   return false;
				}
		});
	</script>


        </div>
      </div>

</body>

</html>
