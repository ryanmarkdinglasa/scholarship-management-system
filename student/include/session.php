<?php
	include 'include/conn.php';
		if($_SESSION['type']=='student'){
			$stmt = $con->prepare("SELECT `user`.*,
			`user`.`id` AS `user_id`,
			`student`.*,
			`student`.`id` AS `student_id`
			FROM `user`
			INNER JOIN `student` ON `student`.`username`=`user`.`username`
			WHERE `user`.`id` = ? LIMIT 1");
			$stmt->execute([$_SESSION['student']]);
			$user = $stmt->fetch(PDO::FETCH_ASSOC);;
		}else{
			header('location:../403.php');
			exit;
		}
	
	date_default_timezone_set('Asia/Manila'); // change according timezone
	$currentTime = date('d-m-Y h:i:s A', time());
	$today = date("Y-m-d");
	
	
