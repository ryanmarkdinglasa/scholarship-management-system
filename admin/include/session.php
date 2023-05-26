<?php
	include 'include/conn.php';
		if($_SESSION['type']=='admin'){
			$stmt = $con->prepare("SELECT * FROM `user` WHERE `id` = ? LIMIT 1");
			$stmt->execute([$_SESSION['admin']]);
			$user = $stmt->fetch(PDO::FETCH_ASSOC);;
			if(empty($user)){
				header('location:../404.php');
				exit;
			}
		}else{
			header('location:../403.php');
			exit;
		}
	
	date_default_timezone_set('Asia/Manila'); // change according timezone
	$currentTime = date('d-m-Y h:i:s A', time());
	$today = date("Y-m-d");
	
	
