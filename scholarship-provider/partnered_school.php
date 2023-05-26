<?php
   	error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$parentpage = "Scholarship";
	$parentpage_link = "#";
	$page=$currentpage = "School";
	$childpage = "school";

    //ADD NEW
    if(isset($_POST['add'])){
        $sp_id=$user['staff_sp'];//SCHOLARSHIP PROGRAM
        $name=trim($_POST['name']);
        $description=trim($_POST['description']);
        $user_id=$_POST['staffID']; //STAFF ASSIGNED
        $created_on=date('Y-m-d H:i:s');//DATE TODAY

        if(empty($name) || empty($description) || empty($user_id) || $_POST['staffID']==0){
            $_SESSION['error']='Please fill in all the required fields.';
            header('Location:partnered_school.php');
            exit();
        }

        if (!preg_match('/^[a-zA-Z\s,-]+$/', $name) || !preg_match('/^[a-zA-Z\s,-.]+$/', $description)){
			$_SESSION['error'] = " Invalid naming it should only contain letters.";
			header("Location:partnered_school.php");
			exit();
		}

         //CHECK STAFF STATUS (DEACTIVATED)
         $sql="SELECT `staff`.`username` AS `staff_username`,
         `user`.`username` AS `user_username`
         FROM `staff`
         INNER JOIN `user` ON `user`.`username`=`staff`.`username`
         WHERE `staff`.`id`='".$user_id."' AND `status`='0'
        ";
         $stmt=$con->prepare($sql);
        $result=$stmt->execute();
        $check_staff_status=$stmt->fetch(PDO::FETCH_ASSOC);
        //IF DEACTIVATED prompt error
        if(!empty($check_staff_status)){
            $_SESSION['error'] = " Assigned staff is deactivated.";
            header("Location:partnered_school.php");
            exit();
        }

         //INSERT SCHOOL
        $result=addrecord('school',['sp_id','user_id','school_name','description','created_on'],[$sp_id,$user_id,$name,$description,$created_on]);
        if(!$result){
            $_SESSION['error']='Something went wrong adding school.';
            header('Location:partnered_school.php');
            exit();
        }else{
            $_SESSION['success']='School added.';
            header('Location:partnered_school.php');
            exit();
        }
    }//add 

     //EDIT 
     if (isset($_POST['edit'])) {
        $school_id=$_POST['school_id'];
        $edit_name=trim($_POST['edit_name']);
        $edit_description=trim($_POST['edit_description']);
        $edit_user_id=trim($_POST['edit_staff_id']);
        $created_on=date('Y-m-d H:i:s');
        if(empty($edit_name) || empty($edit_description) || empty($edit_user_id) ){
            $_SESSION['error']='Please fill in all the required fields.';
            header('Location:partnered_school.php');
            exit();
        }
        if (!preg_match('/^[a-zA-Z\s,-]+$/', $edit_name) || !preg_match('/^[a-zA-Z\s,-.]+$/', $edit_description)){
			$_SESSION['error'] = " Invalid school naming it should only contain letters.";
			header("Location:partnered_school.php");
			exit();
		}
        //CHECK STAFF STATUS (DEACTIVATED)
        $sql="SELECT `staff`.`username` AS `staff_username`,
            `user`.`username` AS `user_username`
            FROM `staff`
            INNER JOIN `user` ON `user`.`username`=`staff`.`username`
            WHERE `staff`.`id`='".$edit_user_id."' AND `status`='0'
         ";
         $stmt=$con->prepare($sql);
         $result=$stmt->execute();
         $check_staff_status=$stmt->fetch(PDO::FETCH_ASSOC);
         //IF DEACTIVATED prompt error
        if(!empty($check_staff_status)){
            $_SESSION['error'] = " Assigned staff is deactivated.";
			header("Location:partnered_school.php");
			exit();
        }
        //UPDATE INFO
        $sql123="UPDATE `school` SET `user_id`=?,`school_name`=?,`description`=?,`update_on`=? WHERE `id`=?";
        $stmt=$con->prepare($sql123);
        $resutl=$stmt->execute([$edit_user_id,$edit_name,$edit_description,$created_on,$school_id]);
        if(!$result){
            $_SESSION['error']='Something went wrong updating school.';
            header('Location:partnered_school.php');
            exit();
        }else{
            $_SESSION['success']='School edited.';
            header('Location:partnered_school.php');
            exit();
        }
    }//edit

    //DELETE
	if (isset($_GET['del'])) {
		$id = $_GET['id'];
		try {
			$stmt = $con->prepare("DELETE FROM `school` WHERE id=? LIMIT 1");
			$stmt->execute([$id]);
			if ($stmt->rowCount() > 0) {
				$_SESSION['success'] = 'School removed';
                header('Location:partnered_school.php');
                exit();
			} else {
				$_SESSION['error'] = 'School not found or already removed';
                header('Location:partnered_school.php');
                exit();
			}
		} catch (Exception $e) {
			$_SESSION['error'] = 'Something went wrong.'. $e->getMessage();
		}
        header('Location:partnered_school.php');
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
                                        <small>Add New School</small>
                                    </div>
                                    <form role="form" method="post">
                                        <div class="form-group mb-3">
                                            <label class="form-control-label">School Name</label>
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"></span>
                                                </div>

                                                <input class="form-control" id="name" name="name" placeholder="Enter school name" type="text" title="Enter the Name of the New School Partner" oninvalid="this.setCustomValidity('Please enter the Name of the new Scholarship Grant.')" oninput="setCustomValidity('')" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-control-label">School Description</label>
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"></span>
                                                </div>
                                               
                                                <input class="form-control" id="description" name="description" placeholder="Enter the description" type="text" title="Enter the Description of the New School Partner" oninvalid="this.setCustomValidity('Please enter the Duration of the new Scholarship Grant.')" oninput="setCustomValidity('')" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                        <label class="form-control-label">Assign Staff</label>
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"></span>
                                                </div>
                                                <select class="form-control" id="staffID" name="staffID"  title="Assigned the Staff to this school" required>
                                                    <option value='' selected>Select Staff</option>
                                                    <?php
                                                        try{
                                                        $sql2 = "SELECT `staff`.*,
                                                        `staff`.`id` AS `staff_id`,
                                                        `user`.`id` AS `userid`,
                                                        `user`.`status`,
                                                        `user`.`firstname`,
                                                        `user`.`lastname`
                                                        FROM `staff` 
                                                        INNER JOIN `user` ON `user`.`username`=`staff`.`username`
                                                        WHERE 
                                                        `user`.`status`='1' AND
                                                        `staff`.`position_id`='2' AND 
                                                        `staff`.`sp_id`='".$user['staff_sp']."'";
                                                         $query2 = $con->query($sql2);
                                                        }catch(Exception $e){

                                                        }
                                                        while ($show_staff2 = $query2->fetch(PDO::FETCH_ASSOC)) {
                                                            if(!empty($show_staff2)){
                                                     ?>
                                                        <option value="<?php echo $show_staff2['staff_id'];?>"><?php echo short_text($show_staff2['firstname'].' '.$show_staff2['lastname'])?></option>
                                                    <?php 
                                                        }   }                      
                                                    ?>
                                                </select>
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
                                    <h3 class="mb-0">School Partners</h3>
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
                                        <th>School Name</th>
                                        <th>Description</th>
                                        <th>Assigned Staff</th>
                                        <th>Date Created</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tfoot class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>School Name</th>
                                        <th>Description</th>
                                        <th>Assigned Staff</th>
                                        <th>Date Created</th>
                                        <th>Option</th>
                                    </tr>
                                </tfoot> 
                                <?php
                                try{
                                $sql = "SELECT `school`.*,
                                `staff`.`id` AS `staff_id`,
                                `staff`.`username` AS `staff_username`,
                                `user`.`id` AS `user_id`,
                                `user`.`firstname`,
                                `user`.`lastname`,
                                `user`.`status` AS `user_status`  
                                FROM `school`
                                INNER JOIN `staff` ON `staff`.`id`=`school`.`user_id`
                                INNER JOIN `user` ON `user`.`username`=`staff`.`username`
                                WHERE `school`.`sp_id`='".$user['staff_sp']."'";
                                $query = $con->query($sql);
                                }catch(PDOException $e){
                                    $_SESSION['error']='Semething went wrong. '.$e->getMessage();
                                }
                                $count = 1;
                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                ?>                               
                                <tbody>
                                        <tr>
                                            <td class="table-user">
                                                <b> <?php echo $count; ?></b>
                                            </td>
                                            <td>
                                                <b> <span class="text-muted"><?php echo short_text($row['school_name']); ?></span></b>
                                            </td>
                                            <td>
                                                <b> <span class="text-muted"><?php echo short_text($row['description']);?></span></b>
                                            </td>
                                            <td>
                                                <?php
                                                $status=($row['user_status']==0)?'(Deactivated)':'';
                                                ?>
                                                <b> <span class="text-muted"><?php echo short_text($row['firstname'].' '.$row['lastname'].' '.$status);?></span></b>
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
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#modal-form2<?php echo $row['id'];?>" type="button"> <i class="fas fa-pen" style="color:#172b4d;"></i>Edit School</span></a>
                                                        <a class="dropdown-item" href="partnered_school.php?id=<?php  echo $row['id'];?>&del=delete" onClick="return confirm('Are you sure you want to delete School, <?php echo $row['school_name']; ?> ?')"><i class="fas fa-trash" style="color:#f5365c;"></i> Delete School</a>
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
                                                                        <small>Update School</small>
                                                                    </div>
                                                                    <form role="form" method="post">
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-control-label">School Name</label>
                                                                            <div class="input-group input-group-merge input-group-alternative">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                                                </div>
                                                                                <input type="hidden" id="school_id" name="school_id" value="<?php echo $row['id']?>" required/>
                                                                                <input class="form-control" id="edit_name" name="edit_name" placeholder="Scholarship Grant Name" type="text" value="<?php echo $row['school_name']; ?>" title="Enter the Name of the School" oninvalid="this.setCustomValidity('Please enter the Name of the School.')" oninput="setCustomValidity('')" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-control-label">School Description</label>
                                                                            <div class="input-group input-group-merge input-group-alternative">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                                </div>
                                                                               
                                                                                <input class="form-control" id="edit_description" name="edit_description" placeholder="Scholarship Grant Description" type="text" value="<?php  echo $row['description'];?>" title="Enter the Description of the School" oninvalid="this.setCustomValidity('Please enter the Description of the School.')" oninput="setCustomValidity('')" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-control-label">Assign Staff</label>
                                                                            <div class="input-group input-group-merge input-group-alternative">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                                </div>
                                                                                <select class="form-control" id="edit_staff_id" name="edit_staff_id"  title="Assigned the Staff to this school" oninvalid="this.setCustomValidity('Please assign the Staff.')" oninput="setCustomValidity('')" required>
                                                                                <option value="<?php echo $row['staff_id'];?>"><?php echo short_text($row['firstname'].' '.$row['lastname'])?></option>
                                                                                <?php
                                                                                $sql1 = "SELECT `staff`.*,
                                                                                `staff`.`id` AS `staff_id`,
                                                                                `user`.`id` AS `userid`,
                                                                                `user`.`firstname`,
                                                                                `user`.`lastname`
                                                                                FROM `staff` 
                                                                                INNER JOIN `user` ON `user`.`id`=`staff`.`user_id`
                                                                                WHERE `staff`.`position_id`='2' AND `staff`.`sp_id`='".$user['staff_sp']."'";
                                                                                $query1 = $con->query($sql1);
                                                                                while ($show_staff = $query1->fetch(PDO::FETCH_ASSOC)) {
                                                                                ?>
                                                                                <option value="<?php echo $show_staff['staff_id'];?>"><?php echo short_text($show_staff['firstname'].' '.$show_staff['lastname'])?></option>
                                                                               
                                                                                <?php }
                                                                                
                                                                                ?>
                                                                                </select>
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