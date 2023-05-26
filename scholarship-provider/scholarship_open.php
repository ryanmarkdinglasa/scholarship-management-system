<?php
	error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
    $parentpage = "Scholarship";
    $parentpage_link = "#";
    $currentpage = "Opening of Scholarship ";
    $childpage = "scholarship_open";

    //
    date_default_timezone_set('Asia/Manila'); // change according timezone
    $today = date("Y-m-d");
    //

    //OPEN A SCHOALRSHIP
	if(isset($_POST['add'])){
        $name=trim($_POST['name']);
        $description=trim($_POST['description']);
        $start=trim($_POST['start']);
        $end=trim($_POST['end']);
        $date_pattern = '/^\d{4}-\d{2}-\d{2}$/';
        if(empty($name) || empty($description)|| empty($start)|| empty($end)){
            $_SESSION['error']='Please fill in all the required fields.';
            header("Location: scholarship_open.php");
            exit();
        }
        if (!preg_match('/^[a-zA-Z0-9,-.]+$/', $name)) {
			$_SESSION['error'] = "Invalid name, special characters are not permitted.";
			header("Location: scholarship_open.php");
			exit();
		}
        if (!preg_match($date_pattern, $start) || !preg_match($date_pattern, $end) ) {
            // Date is not in the correct format
            $_SESSION['error'] = "Invalid date format. Please use the format 'YYYY-MM-DD'.";
            header("Location: scholarship_open.php");
            exit();
        }
        if($start>$end){
            $_SESSION['error'] = "Invalid date start or end.";
            header("Location: scholarship_open.php");
            exit();
        }
        if($start==$end){
            $_SESSION['error'] = "Invalid date, the start date and end dates shouldn't be the same day.";
            header("Location: scholarship_open.php");
            exit();
        }
        // Check if there is a conflict of schedule
        $sql = "SELECT COUNT(*) FROM scholarship WHERE `sp_id`='".$user['staff_sp']."' AND start <= ? AND end >= ?";
        $stmt = $con->prepare($sql);
        $stmt->execute([$end, $start]);
        $counts = $stmt->fetchColumn();
        if ($counts > 0) {
            $_SESSION['error'] = "There is a conflict of schedule. Please choose different dates.";
            header("Location: scholarship_open.php");
            exit();
        }

        $status=1;
        $created_on=date('Y-m-d H:i:s');
        $result=addrecord('scholarship',['sp_id','name','description','start','end','status','created_on'],[$user['staff_sp'],$name,$description,$start,$end,$status,$created_on]);
        if(!$result){
            $_SESSION['error'] = "Something went wrong adding scholarhip opening.";
            header("Location: scholarship_open.php");
            exit();
        }//
        else{
            $_SESSION['success'] = "Scholarship opened.";
            header("Location: scholarship_open.php");
            exit();
        }
    }//

    if(isset($_POST['edit'])){
        $s_open=trim($_POST['id']);
        $name=trim($_POST['edit_name']);
        $description=trim($_POST['edit_description']);
        $start=trim($_POST['edit_start']);
        $end=trim($_POST['edit_end']);
        $date_pattern = '/^\d{4}-\d{2}-\d{2}$/';
        if(empty($name) || empty($description)|| empty($start)|| empty($end)){
            $_SESSION['error']='Please fill in all the required fields.';
            header("Location: scholarship_open.php");
            exit();
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
			$_SESSION['error'] = "Invalid name, special characters are not permitted.";
			header("Location: scholarship_open.php");
			exit();
		}
        if (!preg_match($date_pattern, $start) || !preg_match($date_pattern, $end) ) {
            // Date is not in the correct format
            $_SESSION['error'] = "Invalid date format. Please use the format 'YYYY-MM-DD'.";
            header("Location: scholarship_open.php");
            exit();
        }
        if($start>$end){
            $_SESSION['error'] = "Invalid date start or end.";
            header("Location: scholarship_open.php");
            exit();
        }
        if($start==$end){
            $_SESSION['error'] = "Invalid date, the start date and end dates shouldn't be the same day.";
            header("Location: scholarship_open.php");
            exit();
        }
        // Check if there is a conflict of schedule
        $sql = "SELECT COUNT(*) FROM scholarship WHERE `id`!=? AND `sp_id`=?  AND `start` <= ? AND `end` >= ?";
        $stmt = $con->prepare($sql);
        $stmt->execute([$s_open,$user['staff_sp'],$end, $start]);
        $counts = $stmt->fetchColumn();
        if ($counts > 0) {
            $_SESSION['error'] = "There is a conflict of schedule. Please choose different dates.";
            header("Location: scholarship_open.php");
            exit();
        }

        $status=1;
        $created_on=date('Y-m-d H:i:s');
        $result=updaterecord('scholarship',['id','name','description','start','end','status','updated_on'],[$s_open,$name,$description,$start,$end,$status,$created_on]);
        if(!$result){
            $_SESSION['error'] = "Something went wrong adding scholarhip opening.";
            header("Location: scholarship_open.php");
            exit();
        }//
        else{
            $_SESSION['success'] = "Scholarship opened.";
            header("Location: scholarship_open.php");
            exit();
        }
    }//

    //DELETE SCHOLARSHIP PROVIDER
	if (isset($_GET['del'])) {
		$id = $_GET['id'];
		try {
			$stmt = $con->prepare("DELETE FROM `scholarship` WHERE id=? LIMIT 1");
			$stmt->execute([$id]);
			if ($stmt->rowCount() > 0) {
				$_SESSION['success'] = 'Scholarship removed';
				header("location:scholarship_open.php");
				exit();
			} else {
				$_SESSION['error'] = 'Scholarship not found or already removed';
				header("location:scholarship_open.php");
				exit();
			}
		} catch (Exception $e) {
			$_SESSION['error'] = 'Something went wrong.'. $e->getMessage();
		}
		header("location:scholarship_open.php");
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
    <?php	include("include/sidebar.php");	?>
    <!-- Main content -->
    <div class="main-content" id="panel">
        <?php	include("include/topnav.php"); ?>
		
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
        <div class="col-md-4">
            <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <div class="card bg-secondary border-0 mb-0">
                                <div class="card-body px-lg-5 py-lg-5">
                                    <div class="text-center text-muted mb-4">
                                        <small>Open New Scholarship</small>
                                    </div>
                                    <form role="form" method="post">
                                        <div class="form-group mb-3">
                                            <label class="form-control-label" for="input-username">Name</label>
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input class="form-control" id="name" name="name" placeholder="New Scholarship Name" type="text" title="Enter the Scholarship Name" oninvalid="this.setCustomValidity('Please enter the new Scholarship Name.')" oninput="setCustomValidity('')" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-control-label" for="input-username">Details</label>
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <textarea class="form-control" id="description" name="description" placeholder="New Scholarship Details" rows="3" resize="none"></textarea>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="form-control-label">Open Date</label>
                                                    <input class="form-control"id="start" name="start" data-provide="datepicker" data-date-format="yyyy-mm-dd" placeholder="Select Date" type="text">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="form-control-label">Closed Date</label>
                                                    <input class="form-control" id="end" name="end" data-provide="datepicker" data-date-format="yyyy-mm-dd" placeholder="Select Date" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" id="add" name="add" class="btn btn-primary my-4">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>






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
                                    <h3 class="mb-0">Scholarships</h3>
                                </div>
                                <div class="col-6 text-right">
                                    <a type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-primary btn-round btn-icon" style="color:white;">
                                        <span class="btn-inner--icon"><i class="fas fa-plus" style="color:white;"></i></span>
                                        <span class="btn-inner--text" style="color:white;">New </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Light table -->
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush table-striped" id="datatable-buttons">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Scholarship No.</th>
                                        <th>Scholarship Name</th>
                                        <th>Description</th>
                                        <th>Date Opened </th>
                                        <th>Date Closed</th>
                                        <th>Show</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <?php
                                try{
                                $query="
                                    SELECT *
                                    FROM `scholarship`
                                    WHERE `sp_id`=?
                                ";
                                $stmt = $con->prepare($query);
                                $stmt->execute([$user['staff_sp']]);
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                }catch(Exception $e){
                                    $_SESSION['error']='Something went wrong accessing scholarships.';
                                }
                                $count=1;
                                foreach ($result as $row) {
                                
                                ?>
                                <tbody>
                                    <?php
                                    ?>
                                        <tr>
                                            <td class="table-user">
                                                <?php echo $count; ?>

                                            </td>
                                            <td>
                                                <span class="text-muted"><?php echo short_text($row['name']);?></span>
                                            </td>
                                            <td>
                                                <span class="text-muted"><?php echo short_text($row['description']);?></span>
                                            </td>
                                            <td class="table-user">
                                                <span class="text-muted"><?php echo $row['start']; ?></span>

                                            </td>
                                            <td class="table-user ">
                                                <span class="text-muted"><?php echo $row['end']; ?></span>
                                            </td>
                                            <td>
                                                <?php $end = $row['end'];
                                                if ($end > $today) {
                                                    echo '<span class="badge badge-success">Open</span>';
                                                } else {
                                                    echo '<span class="badge badge-danger">Closed</span>';
                                                }
                                                ?>
                                            </td>

                                            <td class="text-right">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#modal-form2<?php echo $row['id'];?>" type="button"> <i class="fas fa-pen" style="color:#172b4d;"></i>Edit Scholarship</span></a>
                                                        <a class="dropdown-item" href="scholarship_open.php?id=<?php echo $row['id'] ?>&del=delete" onClick="return confirm('Are you sure you want to delete Scholarship <?php echo htmlentities($row['name']); ?> ?')"><i class="fas fa-trash" style="color:#f5365c;"></i> Remove Scholarship</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="col-md-4">
                                            <div class="modal fade" id="modal-form2<?php echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                                                <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body p-0">
                                                            <div class="card bg-secondary border-0 mb-0">
                                                                <div class="card-body px-lg-5 py-lg-5">
                                                                    <div class="text-center text-muted mb-4">
                                                                        <small>Edit Scholarship</small>
                                                                    </div>
                                                                    <form role="form" method="post">
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-control-label" for="input-username">Name</label>
                                                                            <div class="input-group input-group-merge input-group-alternative">
                                                                            <input class="form-control" type="hidden" id="id" name="id" value="<?php echo $row['id'];?>" required>
                                                                                <input class="form-control" id="edit_name" name="edit_name" value="<?php echo $row['name'];?>" placeholder="New Scholarship Name" type="text" title="Enter the Scholarship Name" oninvalid="this.setCustomValidity('Please enter the new Scholarship Name.')" oninput="setCustomValidity('')" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-control-label" for="input-username">Details</label>
                                                                            <div class="input-group input-group-merge input-group-alternative">
                                                                                <textarea class="form-control" id="edit_description" name="edit_description" placeholder="New Scholarship Details" rows="3" resize="none"><?php echo $row['description'];?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row align-items-center">
                                                                            <div class="col">
                                                                                <div class="form-group">
                                                                                    <label class="form-control-label">Open Date</label>
                                                                                    <input class="form-control"id="edit_start" name="edit_start" data-provide="datepicker" value="<?php echo $row['start'];?>" data-date-format="yyyy-mm-dd" placeholder="Select Date" type="text">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col">
                                                                                <div class="form-group">
                                                                                    <label class="form-control-label">Closed Date</label>
                                                                                    <input class="form-control" id="edit_end" name="edit_end" data-provide="datepicker" value="<?php echo $row['end'];?>" data-date-format="yyyy-mm-dd" placeholder="Select Date" type="text">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-center">
                                                                            <button type="submit" id="edit" name="edit" class="btn btn-primary my-4">Save</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    
                                    <?php 
                                $count++;
                                
                                
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            include("include/footer.php"); //Edit topnav on this page
            ?>

            <script type="text/javascript">
                document.getElementById("close_direct").onclick = function() {
                    location.href = "list_scholarship.php";
                };
            </script>
        </div>
    </div>

    </body>

    </html>
