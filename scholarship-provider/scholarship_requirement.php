<?php
   	error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "Scholarship";
	$parentpage_link = "#";
	$page=$currentpage = "Requirements";
	$childpage = "scholarship_requirement";

    //ADD NEW SCHOLARSHIp REQUIREMENT
    if(isset($_POST['add'])){
        $sp_id=$user['staff_sp'];
        $name=trim($_POST['name']);
        $description=trim($_POST['description']);
        $created_on=date('Y-m-d H:i:s');
        if(empty($name) || empty($description) || empty($sp_id) ){
            $_SESSION['error']='All fields are requied.';
            header('Location:scholarship_requirement.php');
            exit();
        }
        $result=addrecord('sp_requirement',['sp_id','name','description','created_on'],[$sp_id,$name,$description,$created_on]);
        if(!$result){
            $_SESSION['error']='Something went wrong adding scholarship requirement.';
            header('Location:scholarship_requirement.php');
            exit();
        }else{
            $_SESSION['success']='Scholarship requirement added.';
            header('Location:scholarship_requirement.php');
            exit();
        }
    }//add requirements

     //EDIT  SCHOLARSHIp REQUIREMENT
     if(isset($_POST['edit'])){
        $requirement_id=$_POST['requirement_id'];
        $edit_name=trim($_POST['edit_name']);
        $edit_description=trim($_POST['edit_description']);
        $created_on=date('Y-m-d H:i:s');
        if(empty($edit_name) || empty($edit_description) || empty($requirement_id) ){
            $_SESSION['error']='All fields are requied.';
            header('Location:scholarship_requirement.php');
            exit();
        }
        $result=updaterecord('sp_requirement',['id','name','description','updated_on'],[$requirement_id,$edit_name,$edit_description,$created_on]);
        if(!$result){
            $_SESSION['error']='Something went wrong updating scholarship requirement.';
            header('Location:scholarship_requirement.php');
            exit();
        }else{
            $_SESSION['success']='Scholarship requirement updated.';
            header('Location:scholarship_requirement.php');
            exit();
        }
        
    }//edit requirements

    //DELETE SCHOLARSHIP REQUIREMENT
    	//DELETE SCHOLARSHIP PROGRAM
	if (isset($_GET['del'])) {
		$id = $_GET['id'];
		try {
			$stmt = $con->prepare("DELETE FROM `sp_requirement` WHERE id=? LIMIT 1");
			$stmt->execute([$id]);
			if ($stmt->rowCount() > 0) {
				$_SESSION['success'] = 'Scholarship requirement removed';
                header('Location:scholarship_requirement.php');
                exit();
			} else {
				$_SESSION['error'] = 'Scholarship requirement not found or already removed';
                header('Location:scholarship_requirement.php');
                exit();
			}
		} catch (Exception $e) {
			$_SESSION['error'] = 'Something went wrong.'. $e->getMessage();
		}
        header('Location:scholarship_requirement.php');
		exit();
    }//edit requirements
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
                                        <small>Add New Requirement</small>
                                    </div>
                                    <form role="form" method="post">
                                        <div class="form-group mb-3">
                                            <label class="form-control-label">Name</label>
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-file-text" aria-hidden="true"></i></span>
                                                </div>
                                                <input class="form-control" id="name" name="name" placeholder="Enter Requirement Name" type="text" title="Enter the Name of the New Requirement" oninvalid="this.setCustomValidity('Please enter the Name of the new Requirement.')" oninput="setCustomValidity('')" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-control-label">Description</label>
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class=""></i></span>
                                                </div>
                                                <input class="form-control" id="descrption" name="description" placeholder="Enter description" type="text" title="Enter the description" oninvalid="this.setCustomValidity('Please enter the Description of the new Requirement.')" oninput="setCustomValidity('')" required>
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
                                    <h3 class="mb-0">Requirements</h3>
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
                                        <th>Name of Scholarship Requirements</th>
                                        <th>Description</th>
                                        <th>Option</th>
                                    </tr>
                                </thead> 
                                <?php
                                $sql = "SELECT * FROM `sp_requirement` WHERE `sp_id`='".$user['staff_sp']."'";
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
                                                <b> <span class="text-muted"><?php echo short_text($row['description']);?></span></b>
                                            </td>
                                            <td class="text-right">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle 	="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                         <a class="dropdown-item" data-toggle="modal" data-target="#modal-form3<?php echo $row['id'];?>" type="button"> <i class="fas fa-eye text-primary"></i></span>View Requirement</a>
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#modal-form2<?php echo $row['id'];?>" type="button"> <i class="fas fa-pen" style="color:#172b4d;"></i>Edit Requirement</span></a>
                                                        <a class="dropdown-item" href="scholarship_requirement.php?id=<?php  echo $row['id'];?>&del=delete" onClick="return confirm('Are you sure you want to delete Terms, <?php echo $row['name']; ?> ?')"><i class="fas fa-trash" style="color:#f5365c;"></i> Delete Requirement</a>
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
                                                                        <small>Edit Requirement</small>
                                                                    </div>
                                                                    <form role="form" method="post">
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-control-label">Name</label>
                                                                            <div class="input-group input-group-merge input-group-alternative">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i class=""></i></span>
                                                                                </div>
                                                                                <input name="requirement_id" type="hidden" value="<?php  echo $row['id'];?>" />
                                                                                <input class="form-control" id="edit_name" name="edit_name" placeholder="New Requirement Name" type="text" value="<?php echo $row['name']; ?>" title="Enter the Name of the Scholarship Requirements" oninvalid="this.setCustomValidity('Please enter the Name of the Scholarship Requirements.')" oninput="setCustomValidity('')" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-control-label">Description</label>
                                                                            <div class="input-group input-group-merge input-group-alternative">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i class=""></i></span>
                                                                                </div>
                                                                                <input class="form-control" id="edit_description" name="edit_description" placeholder="New Requirement Description" type="text" value="<?php  echo $row['description']; ?>" title="Enter the Description of the Scholarship Requirements" oninvalid="this.setCustomValidity('Please enter the Description of the Scholarship Requirements.')" oninput="setCustomValidity('')" required>
                                                                            </div>
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
                                        <div class="col-md-4">
                                            <div class="modal fade" id="modal-form3<?php echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="modal-form2" aria-hidden="true">
                                                <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body p-0">
                                                            <div class="card bg-secondary border-0 mb-0">
                                                                <div class="card-body px-lg-5 py-lg-5">
                                                                    <div class="text-center text-muted mb-4">
                                                                        <small>Requirement No.<?php echo ' '.$count;?></small>
                                                                    </div>
                                                                    
                                                                        <div class="form-group">
                                                                            <label class="form-control-label" for="input-username">Requirement Name</label>
                                                                            <br>
                                                                            <span class="Text-muted heading-small"><?php echo $row['name']?></span>
                                                                            
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="">
                                                                            <label class="form-control-label" for="input-username">Requirement Description</label>
                                                                            <br>
                                                                            <span class="Text-muted heading-small text-left "><?php echo $row['description']?></span>
                                                                            </div>
                                                                        </div>
                                                                  
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