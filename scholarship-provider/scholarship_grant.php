<?php
   	error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "Scholarship";
	$parentpage_link = "#";
	$page=$currentpage = "Scholarship Grants";
	$childpage = "scholarship_grant";

    //ADD NEW SCHOLARSHIp REQUIREMENT
    if(isset($_POST['add'])){
        $sp_id=$user['staff_sp'];
        $name=trim($_POST['name']);
        $duration=trim($_POST['duration']);
        $amount_sem=trim($_POST['amount_sem']);
        $created_on=date('Y-m-d H:i:s');
        if(empty($name) || empty($duration) || empty($sp_id) || empty($amount_sem) ){
            $_SESSION['error']='All fields are requied.';
            header('Location:scholarship_grant.php');
            exit();
        }
        if (!preg_match('/^[a-zA-Z0-9\s,-.]+$/', $name)){
			$_SESSION['error'] = " Invalid grant naming.";
			header("Location:scholarship_grant.php");
			exit();
		}
        if(!preg_match('/^([0-9]+(\.[0-9]+)?)$/', $duration) || !preg_match('/^([0-9]+(\.[0-9]+)?)$/', $amount_sem) ) {
            $_SESSION['error'] = " Invalid grant duration or amount it should only contain numbers.";
			header("Location:scholarship_grant.php");
			exit();
        }
        $result=addrecord('sp_grant',['sp_id','name','duration','amount_sem','created_on'],[$sp_id,$name,$duration,$amount_sem,$created_on]);
        if(!$result){
            $_SESSION['error']='Something went wrong adding scholarship grant.';
            header('Location:scholarship_grant.php');
            exit();
        }else{
            $_SESSION['success']='Scholarship grant added.';
            header('Location:scholarship_grant.php');
            exit();
        }
    }//add 

     //EDIT SCHOLARSHIP GRANT
     if (isset($_POST['edit'])) {
        $grant_id = trim($_POST['grant_id']);
        $edit_name = trim($_POST['edit_name']);
        $edit_duration = $_POST['edit_duration'];
        $edit_amount_sem = $_POST['edit_amount_sem'];
        $created_on = date('Y-m-d H:i:s');
        if (empty($edit_name) || empty($edit_duration) || empty($grant_id) ) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: scholarship_grant.php');
            exit();
        }
        if (!preg_match('/^[a-zA-Z0-9\s,-.]+$/', $edit_name)) {
            $_SESSION['error'] = 'Invalid grant naming.';
            header('Location: scholarship_grant.php');
            exit();
        }
        if (!preg_match('/^\d*\.?\d+$/', $edit_duration)) {
            $_SESSION['error'] = 'Invalid grant duration, it should only contain digits and an optional decimal point.';
            header('Location: scholarship_grant.php');
            exit();
        }
        if (!preg_match('/^\d+$/', $edit_amount_sem)) {
            $_SESSION['error'] = 'Invalid grant amount, it should only contain digits.';
            header('Location: scholarship_grant.php');
            exit();
        }
        $result = updaterecord('sp_grant', ['id', 'name', 'duration', 'amount_sem', 'updated_on'], [$grant_id, $edit_name, $edit_duration, $edit_amount_sem, $created_on]);
        if (!$result) {
            $_SESSION['error'] = 'Something went wrong updating the scholarship grant.';
            header('Location: scholarship_grant.php');
            exit();
        } else {
            $_SESSION['success'] = 'Scholarship grant updated.';
            header('Location: scholarship_grant.php');
            exit();
        }
    }//edit

    //DELETE SCHOLARSHIP
	if (isset($_GET['del'])) {
		$id = $_GET['id'];
		try {
			$stmt = $con->prepare("DELETE FROM `sp_grant` WHERE id=? LIMIT 1");
			$stmt->execute([$id]);
			if ($stmt->rowCount() > 0) {
				$_SESSION['success'] = 'Scholarship grant removed';
                header('Location:scholarship_grant.php');
                exit();
			} else {
				$_SESSION['error'] = 'Scholarship grant not found or already removed';
                header('Location:scholarship_grant.php');
                exit();
			}
		} catch (Exception $e) {
			$_SESSION['error'] = 'Something went wrong.'. $e->getMessage();
		}
        header('Location:scholarship_grant.php');
		exit();
    }//delete
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
        <?php
        include("include/breadcrumbs.php");
        ?>
        <!-- Header -->
        <div class="col-md-4">
            <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <div class="card bg-secondary border-0 mb-0">
                                <div class="card-body px-lg-5 py-lg-5">
                                    <div class="text-center text-muted mb-4">
                                        <small>Add New Scholarship Grant</small>
                                    </div>
                                    <form role="form" method="post">
                                        <div class="form-group mb-3">
                                            <label class="form-control-label" for="input-username">Name</label>
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"></span>
                                                </div>
                                                <input class="form-control" id="name" name="name" placeholder="New Scholarship Grant Name" type="text" title="Enter the Name of the New Scholarship Requirements" oninvalid="this.setCustomValidity('Please enter the Name of the new Scholarship Grant.')" oninput="setCustomValidity('')" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-control-label" for="input-username">Duration</label>
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"></span>
                                                </div>
                                                <input class="form-control" id="duration" name="duration" placeholder="Enter the Duration" type="text" title="Enter the Duration of the New Scholarship Grant" oninvalid="this.setCustomValidity('Please enter the Duration of the new Scholarship Grant.')" oninput="setCustomValidity('')" required>
                                            </div>
                                            <span class="text-muted"><small>*Duration of the scholarship in terms of semesters</small></span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-control-label" for="input-username">Amount</label>
                                           
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"></span>
                                                </div>
                                                <input class="form-control" id="amount_sem" name="amount_sem" placeholder="Enter Amount per Semester" type="text" title="Enter the Amount per Semester" oninvalid="this.setCustomValidity('Please enter the Amount per Semester of the new Scholarship Grant.')" oninput="setCustomValidity('')" required>
                                            </div>
                                            <span class="text-muted"><small>*Amount per semester</small></span>
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
                                    <h3 class="mb-0">Scholarship Requirements</h3>
                                </div>
                                <div class="col-6 text-right">
                                    <a type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-primary text-white"><i class="fas fa-plus"> </i>  New</a>
                                </div>
                            </div>
                        </div>
                        <!-- Light table -->
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Name of Scholarship Grant</th>
                                        <th>Durataion(Semester)</th>
                                        <th>Amount per Sem</th>
                                        <th>Date Created</th>
                                        <th>Option</th>
                                    </tr>
                                </thead> 
                                <?php
                                $sql = "SELECT * FROM `sp_grant` WHERE `sp_id`='".$user['staff_sp']."'";
                                $query = $con->query($sql);
                                $count = 1;
                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                ?>                               
                                <tbody>
                                        <tr>
                                            <td class="table-user">
                                                <b> <?php echo $count; ?></b>
                                            </td>
                                            <td>
                                                <b> <span class="text-muted"><?php echo $row['name']; ?></span></b>
                                            </td>
                                            <td>
                                                <b> <span class="text-muted"><?php echo $row['duration'].' SEM';?></span></b>
                                            </td>
                                            <td>
                                                <b> <span class="text-muted"><?php echo 'Php '.number_format($row['amount_sem'],2,'.',',');?></span></b>
                                            </td>
                                            <td>
                                                <b> <span class="text-muted"><?php echo created_on($row['created_on']);?></span></b>
                                            </td>
                                            <td class="text-right">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle 	="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#modal-form2<?php echo $row['id'];?>" type="button"> <i class="fas fa-pen" style="color:#172b4d;"></i>Edit Grant</span></a>
                                                        <a class="dropdown-item" href="scholarship_grant.php?id=<?php  echo $row['id'];?>&del=delete" onClick="return confirm('Are you sure you want to delete Terms, <?php echo $row['name']; ?> ?')"><i class="fas fa-trash" style="color:#f5365c;"></i> Delete Grant</a>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <div class="col-md-4">
                                            <div class="modal fade" id="modal-form2<?php echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="modal-form2" aria-hidden="true">
                                                <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body p-0">
                                                            <div class="card bg-secondary border-0 mb-0">
                                                                <div class="card-body px-lg-5 py-lg-5">
                                                                    <div class="text-center text-muted mb-4">
                                                                        <small>Edit Scholarship Grant</small>
                                                                    </div>
                                                                    <form role="form" method="post">
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-control-label" >Name</label>
                                                                            <div class="input-group input-group-merge input-group-alternative">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                                </div>

                                                                                <input name="grant_id" type="hidden" value="<?php  echo $row['id'];?>" />
                                                                                <input class="form-control" id="edit_name" name="edit_name" placeholder="Scholarship Grant Name" type="text" value="<?php echo $row['name']; ?>" title="Enter the Name of the Scholarship Grant" oninvalid="this.setCustomValidity('Please enter the Name of the Scholarship Grant.')" oninput="setCustomValidity('')" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-control-label" for="input-username">Duration</label>
                                                                            <div class="input-group input-group-merge input-group-alternative">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                                </div>
                                                                                <input class="form-control" id="edit_duration" name="edit_duration" placeholder="Scholarship Grant Description" type="number" value="<?php  echo$row['duration'];?>" title="Enter the Duration of the Scholarship Grant" oninvalid="this.setCustomValidity('Please enter the Description of the Scholarship Grant.')" oninput="setCustomValidity('')" required>
                                                                            </div>
                                                                            <span class="text-muted"><small>*Duration of the scholarship in terms of semesters</small></span>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-control-label" for="input-username">Amount</label>
                                                                            <div class="input-group input-group-merge input-group-alternative">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                                </div>
                                                                                <input class="form-control" id="edit_amount_sem" name="edit_amount_sem" placeholder="Scholarship Grant Amount" type="number" value="<?php  echo $row['amount_sem']; ?>" title="Enter the Amount per Semester of the Scholarship Grant" oninvalid="this.setCustomValidity('Please enter the Amount per Semester of the Scholarship Grant.')" oninput="setCustomValidity('')" required>
                                                                            </div>
                                                                            <span class="text-muted"><small>*Amount per semester</small></span>
                                                                        </div>
                                                                        <div class="text-center">
                                                                            <button type="submit" id="edit" name="edit" class="btn btn-primary my-4">Save </button>
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
                                    }//forloop 
                                
                                    ?>
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
                    location.href = "scholarship_requirement.php";
                };
            </script>
        </div>
    </div>

    </body>

    </html>
<?php// } ?>