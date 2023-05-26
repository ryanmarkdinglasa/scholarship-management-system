<?php
	error_reporting(E_ALL);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
    $parentpage = "Scholarship";
    $parentpage_link = "#";
    $currentpage = "Scholarship Offers ";
    $childpage = "offer";

    //
    date_default_timezone_set('Asia/Manila'); // change according timezone
    $today = date("Y-m-d");
    //

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
        <!-- Page content -->
        <div class="container-fluid mt--6">
            <!-- Table -->
            <div class="row">
                <div class="col">
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header border-1 py-4">
                            <div class="row">
                                <div class="col-6">
                                    <h3 class="mb-0">Scholarships</h3>
                                </div>
                                <div class="col-6 text-right">
                                </div>
                            </div>
                        </div>
                     </div>
                </div>
            </div>    
            <br><br><br>
                        <!-- Light table -->
              
                        <!-- Light table -->
                        <!--SCHOLARSHIP OFFER-->
                        <div class="container-fluid mt--6" >
                           
                            <?php 
                            try{
                                $query = "SELECT `scholarship`.*,
                                `scholarship_program`.`id` AS `sp_id`,
                                `scholarship_program`.`name` AS `sp_name`,
                                `scholarship_program`.`img` AS `sp_img`
                                FROM `scholarship`
                                INNER JOIN `scholarship_program` ON `scholarship_program`.`id`=`scholarship`.`sp_id` 				
                                ";
                                $stmt = $con->prepare($query);
                                $stmt->execute();
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                }catch(Exception $e){
                                    $_SESSION[]='Something went wrong accessing scholars.';
                                }
                                foreach ($result as $row) {
                            ?>
                             <div class="row d-flex justify-content-center " style="padding 20px 20xp; " >
                                <div class="col-lg-8" >
                                    <div class="card shadow border-1 bg-primary text-white" style="border-radius:10px;" >
                                        <div class="card-body py-4 ">
                                            <div class="  text-white rounded-circle mb-0">
                                            <?php
                                            $userphoto = isset($row['sp_img']) ? htmlspecialchars($row['sp_img'], ENT_QUOTES, 'UTF-8') : '';
                                            if ($userphoto == "" || $userphoto == "NULL") :
                                            ?>
                                            <img src="img/profile.png" class="avatar rounded-circle mr-3 border-1">
                                            <?php else : ?>
                                            <img src="../scholarship-provider/img/<?php echo $userphoto; ?>" class="avatar rounded-circle mr-3">
                                            <?php endif; ?>
                                            <b>
                                            <?php 
                                            $firstname = isset($row['sp_name']) ? htmlspecialchars($row['sp_name'], ENT_QUOTES, 'UTF-8') : '';
                                            $name=short_text($firstname);
                                            echo $name;
                                            ?>
                                               
                                            </div>
                                            <br>
                                            <h4 class="h3 text-white text-uppercase"> <?php echo $row['name']; ?></h4>
                                          
                                            <p class="description mt-3"> <?php echo $row['description']; ?></p>
                                            <div>
                                                <p class="description mt-3"><i class="fas fa-clock"></i> </i> Application Deadline :
                                                <?php
                                                    $end = $row['end'];
                                                    $tommorrow = date("Y-m-d");
                                                    if ($end > $tommorrow) :
                                                    ?>
                                                    <span class="badge badge-pill badge-success">OPENED</span></p>
                                                    <?php else : ?>
                                                    <span class="badge badge-pill badge-danger">CLOSED</span></p>
                                                    <?php endif; 
                                                     if ($end > $tommorrow) :
                                                    ?>
                                                    <div class="text-right py-0">
                                                            <button onclick="window.location.href='<?php echo 'filter.php?id='.$row['sp_id'].'&offer='.$row['id'];?>'"class="btn bg-white text-primary py-2">Apply</button>
                                                    </div>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <?php }
                                ?>
                            
                               
                            
                        </div>
                       
                        <!--SCHOLARSHIP OFFER-->
            <?php
            include("include/footer.php"); //Edit topnav on this page
            ?>
            <script type="text/javascript">
                document.getElementById("close_direct").onclick = function() {
                    location.href = "list_scholarship.php";
                };
            </script>
    </body>
</html>
