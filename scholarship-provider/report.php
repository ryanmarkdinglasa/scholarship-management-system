<?php
	error_reporting(E_ALL);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "";
	$parentpage_link = "#";
	$currentpage = "Reports";
	$page=$childpage = "Report";

  //DELETE SCHOLAR
	if (isset($_GET['del'])) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `scholar` SET `status`='4' WHERE id=? LIMIT 1");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong resuming scholarship.';
				header("location:scholar.php");
				exit();
			}
		}catch(PDOException $e){
			$_SESSION['error']='Something went wrong.'.$e->getMessage();
		}
		header("location:scholar.php");
		exit();
	}

	
	//ACTIVATE SCHOLAR
	if (isset($_GET['on']) && $_GET['on']==1) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `scholar` SET `status`='1' WHERE id=? LIMIT 1");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong resuming scholarship.';
				header("location:scholar.php");
				exit();
			}
		}catch(PDOException $e){
			$_SESSION['error']='Something went wrong.'.$e->getMessage();
		}
		header("location:scholar.php");
		exit();
	} 
	
	//TERMINATE SCHOLAR
	if (isset($_GET['off']) && $_GET['off']==0) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `scholar` SET `status`='0' WHERE id=? ");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong terminating scholarship.';
				header("location:scholar.php");
				exit();
			}
		}catch(Exception $e){
			$_SESSION['error']='Something went wrong .'.$e->getMessage();
		}
		header("location:scholar.php");
		exit();
	}	
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
    <?php include "include/breadcrumbs.php";?>
    <!-- Batas Header & Breadcrumbs -->
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header">
			        <div class="row">
                <div class="col-6">
						      <h3 class="mb-0">Generate Report</h3>
						    </div>
		           <div class="col-6 text-right">

			         </div>
				    </div>
          </div>
            <!-- Light table -->
            <div class="card-body">
                
                <form action="print/print-record.php" method="POST" target="_blank">
                <div class="row">
                    <div class="col-lg-4" style="">
                      <h6 class="heading-small text-muted">PRINT REPORT</h6>
                      <br>
                      <label class="form-control-label">List</label>
                      <select class="form-control" id="table" name="table" required>
                        <option value='' selected>Select List</option>
                        <option value="application">Application's List</option>
                        <option value="scholar">Scholar's List</option>
                      </select>
                    </div>
                    <div class="col-lg-4" style="">
                      <h6 class="heading-small text-muted">&nbsp;</h6>
                      <br>
                      <label class="form-control-label">Status</label>
                      <select class="form-control"  id="status" name="status" required>
                        <option value='' selected>Select Status</option>
                        <option value="-1">Disapproved</option>
                        <option value="0">Pending</option>
                        <option value="1">Processed</option>
                        <option value="2">Accepted</option>
                        <option value="3">Not-awarded</option>
                        <option value="4">Awarded</option>
                        <option value="5">Active</option>
                        <option value="7">Inactive</option>
                        <option value="6">Terminated</option>
                      </select>
                    </div>
                    <div class="col-lg-4" style="">
                      <h6 class="heading-small text-muted">&nbsp;</h6>
                      <br>
                      <label class="form-control-label">Year</label>
                      <select class="form-control"  id="year" name="year" required>
                       <option value='' selected>Select Year</option>
                       <?php 
                       for($i=2020;$i<2060;$i++){
                        echo "<option value='".$i."'>".$i."</option>";
                       }
                       ?>
                      </select>
                    </div>
                </div>
                <div class="row">
                  <div class="col-lg-4">
                    <br>
                    <input type="hidden" name="sp_id" value="<?php echo $user['staff_sp'];?>">
                    <input class="btn btn-primary bg-primary prints" name="print" type="submit" target="_blank"  value="Print"/>
                  </div>
                </div>  
                </form>  
            </div>
          </div>
        </div>
      </div>
			<?php
				include("include/footer.php");
			?>
        <script>
        $(document).on("click", ".prints", function() {
          // Check input
          var table = $("#table").val().trim();
          if (table === '') {
            $("#table").css("border", "1px solid red").fadeIn("slow").focus();
            return false;
          }
          var status = $("#status").val().trim();
          if (status === '') {
            $("#status").css("border", "1px solid red").fadeIn("slow").focus();
            return false;
          }
          var year = $("#year").val().trim();
          if (year === '') {
            $("#year").css("border", "1px solid red").fadeIn("slow").focus();
            return false;
          }
        });
      </script>

       <script>
        
        $(document).ready(function() {
          // Update status selection based on table/list selection
          $('#table').change(function() {
            var selectedOption = $(this).val();
            var statusSelect = $('#status');
            
            if (selectedOption === 'scholar') {
            //statusSelect.val('active'); // Set default status to "active" for scholars
            statusSelect.find('option[value="5"]').show(); // Show "terminated" option
            statusSelect.find('option[value="6"]').show();
            statusSelect.find('option[value="7"]').show();
            //HIDE application selection
            statusSelect.find('option[value="-1"]').hide();
            statusSelect.find('option[value="0"]').hide();
            statusSelect.find('option[value="1"]').hide();
            statusSelect.find('option[value="2"]').hide();
            statusSelect.find('option[value="3"]').hide();
            statusSelect.find('option[value="4"]').hide();
          } 
          if(selectedOption === 'application') {
            //statusSelect.val(''); // Reset status selection
            statusSelect.find('option[value="5"]').hide(); // Hide "terminated" option
            statusSelect.find('option[value="6"]').hide();
            statusSelect.find('option[value="7"]').hide();
            //
            statusSelect.find('option[value="-1"]').show();
            statusSelect.find('option[value="0"]').show();
            statusSelect.find('option[value="1"]').show();
            statusSelect.find('option[value="2"]').show();
            statusSelect.find('option[value="3"]').show();
            statusSelect.find('option[value="4"]').show();
          }
        });

        // Hide "Terminated" option initially
        $('#status').find('option[value="5"]').hide();
        $('#status').find('option[value="6"]').hide();
        $('#status').find('option[value="7"]').hide();
        $('#status').find('option[value="-1"]').hide();
        $('#status').find('option[value="0"]').hide();
        $('#status').find('option[value="1"]').hide();
        $('#status').find('option[value="2"]').hide();
        $('#status').find('option[value="3"]').hide();
        $('#status').find('option[value="4"]').hide();
        });
        
      </script>
      
			</div>
		</div>
	</body>
</html>
