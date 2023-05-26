<?php 
	error_reporting(0);
	session_start();
	require_once("include/conn.php");
	require_once("include/session.php");
	if(!empty($_POST["username"])) {
		$username= $_POST["username"];
		$count=0;
		try{
		$stmt = $con->prepare("SELECT `username` FROM `user` WHERE `username`=? AND `username`!='".$user['username']."'");
		$stmt->execute([$username]);
		$count = $stmt->rowCount();
		}catch(Exception $e){
			$_SESSION['error']='Something went wrong in check the username. Please try again.';
		}
		if($count>0){
			echo "<span style='color:red' title='Username Not Available'><i class='fas fa-times-circle'></i></span>";
			echo "<script>$('#submit').prop('disabled',true);</script>";
		}else{	
			echo "<span style='color:green' title='Available usernames'><i class='fas fa-check-circle'></i></span>";
			echo "<script>$('#submit').prop('disabled',false);</script>";
		}
	}


