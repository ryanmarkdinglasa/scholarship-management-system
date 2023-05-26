<?php
	include 'include/conn.php';
	if($_SESSION[SESSION_TYPE] === 'scholarship-provider'){
		$stmt = $con->prepare("SELECT `user`.*,
			`user`.`id` AS `user_id`,
			`staff`.`id` AS `staff_id`,
			`staff`.`user_id` AS `staff_userID`,
			`staff`.`username` AS `staff_username`,
			`staff`.`sp_id` AS  `staff_sp`,
			`scholarship_program`.`name` AS `program_name`,
			`scholarship_program`.`description` AS `program_description`,
			`staff`.`school_id` AS `staff_school`,
			`staff`.`position_id` AS `staff_position`
			FROM `user`
			INNER JOIN `staff` ON `staff`.`username`=`user`.`username`
			INNER JOIN `scholarship_program` ON `scholarship_program`.`id`=`staff`.`sp_id`
			WHERE `user`.`id` = ? LIMIT 1");
		$result=$stmt->execute([$_SESSION['scholarship-provider']]);
		if(!$result){
			$_SESSION['error']='Something went wrong in session.'.$e->getMessage();
			header('location:../');
			exit();
		}
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if(empty($user)){
			header('location:../404.php');
			exit();
		}
	}else{
		header('location:../403.php');
		exit;
	}
	date_default_timezone_set('Asia/Manila'); // change according timezone
	$currentTime = date('d-m-Y h:i:s A', time());
	$today = date("Y-m-d");
	
	
