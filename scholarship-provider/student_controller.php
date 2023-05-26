<?php
	error_reporting(0);
	session_start();
	include "include/conn.php";
	//include "include/function.php";
	
	//DELETE STUDENT
	if (isset($_GET['del'])) {
		$id = $_GET['id'];
		try {
			$stmt = $con->prepare("DELETE FROM `user` WHERE id=? LIMIT 1");
			$stmt->execute([$id]);
			if ($stmt->rowCount() > 0) {
				$_SESSION['success'] = 'Student removed';
			} else {
				$_SESSION['error'] = 'Student not found';
			}
		} catch (Exception $e) {
			$_SESSION['error'] = 'Something went wrong.'.$e->getMessage();
		}
		header("location:student.php");
		exit();
	} 
	
	//ACTIVATE STUDENT
	if (isset($_GET['on']) && $_GET['on']==1) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `user` SET `status`='1' WHERE id=? AND `type`='student' LIMIT 1");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong activating student. Please try again.';
			}
		}catch(PDOException $e){
			$_SESSION['error']='Something went wrong.'.$e->getMessage();
		}
		header("location:student.php");
		exit();
	} 
	
	//DEACTIVATE STUDENT
	if (isset($_GET['off']) && $_GET['off']==0) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `user` SET `status`='0' WHERE id=? AND `type`='student' LIMIT 1");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong deactivating student. Please try again.';
			}
		}catch(PDOException $e){
			$_SESSION['error']='Something went wrong.'.$e->getMessage();
		}
		header("location:student.php");
		exit();
	} 
	
	