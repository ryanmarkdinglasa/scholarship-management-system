<?php
	error_reporting(0);
	session_start();
	include "include/conn.php";
	include "include/session.php";
	
	//DELETE FEEDBACK
	if (isset($_GET['del'])) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("DELETE FROM `user` WHERE id=?");
			$stmt->execute([$id]);
		}catch(Exception $e){
			$_SESSION['error']='Something went wrong in removing an admin. Please try again.';
			header("location:feedback.php");
			exit();
		}
		header("location:feedback.php");
		exit();
	}
	
	//ACTIVATE ADMIN
	if (isset($_GET['on']) && $_GET['on']==1) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `feedback` SET `status`='1' WHERE id=?");
			$stmt->execute([$id]);
		}catch(Exception $e){
			$_SESSION['error']='Something went wrong in marking a feedback. Please try again.';
			header("location:feedback.php");
			exit();
		}
		header("location:feedback.php");
		exit();
	}

	//DEACTIVATE ADMIN 
	if (isset($_GET['off']) && $_GET['off']==0) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `feedback` SET `status`='0' WHERE id=?");
			$stmt->execute([$id]);
		}catch(Exception $e){
			$_SESSION['error']='Something went wrong in marking a feedback. Please try again.';
			header("location:feedback.php");
			exit();
		}
		header("location:feedback.php");
		exit();
	}	