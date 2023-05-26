<?php
	session_start();
	error_reporting(E_ALL);
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	
	$sp_id=$_GET['id'];
	$row=$check_id=getrecord('scholarship_program',['id'],[$sp_id]);
	if(empty($check_id)){
		header('location:404.php');
		exit();
	}
	
	$offer_id=$_GET['offer'];
	$row=$check_id=getrecord('scholarship',['id'],[$offer_id]);
	if(empty($check_id)){
		header('location:404.php');
		exit();
	}
	//
	$id=$user['student_id'];
	//
	$sql="SELECT * FROM `sp_restriction` WHERE `sp_id`='".$sp_id."'";
	$stmt=$con->prepare($sql);
	$stmt->execute();
	$get_restriction=$stmt->fetchAll(PDO::FETCH_ASSOC);
	if(empty($get_restriction)){
		$_SESSION['error']='Something went wrong in accessing scholarship restrictions.';
		header("Location:scholarship_offer.php");
		exit();
	}
	//
	$conditions = [];
	$params = [];
	foreach ($get_restriction as $restriction) {
		$conditions[] = "`" . $restriction['record'] . "` LIKE '%".$restriction['restriction']."%'";
	}
	//
	if (empty($conditions)) {
		$_SESSION['error'] = 'Something went wrong in accessing scholarship restrictions.';
			header("Location: scholarship_offer.php");
			exit();
	}
		$sql = "SELECT * FROM student WHERE `id`='".$id."' AND " . implode(" AND ", $conditions);
		echo $sql;
		try{
		$stmt = $con->prepare($sql);
		$result=$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$no = $stmt->rowCount();
		}catch(PDOEXception $e){
			$_SESSION['error']="Somewthing went wrong.";
		}
	if($no>0 && $result){
			header("Location: apply.php?id=".$sp_id."&offer=".$offer_id);
			exit();
	}else{
		$_SESSION['error'] = "Application restricted. Your information does not meet the scholarship program's requirements.";
		header("Location: scholarship_offer.php");
		exit();
	}
	

?>