<?php
	error_reporting(0);
	session_start();
	include "include/conn.php";
	include "include/session.php";
	include "include/function.php";
	
	//VIEW NOTIFICATION
	/*if(isset($_GET['id'])){
		//view notification
		header("location:notification.php");
		exit();
	}
	*/
	
	//DELETE NOTIFICATION
	if (isset($_GET['del'])) {
		$id = $_GET['id'];
		try {
			$stmt = $con->prepare("DELETE FROM `notification` WHERE id=? LIMIT 1");
			$stmt->execute([$id]);
			if ($stmt->rowCount() > 0) {
				$_SESSION['success'] = 'Notification removed';
				header("location:notification.php");
				exit();
			} else {
				$_SESSION['error'] = 'Notification not found';
				header("location:notification.php");
				exit();
			}
		} catch (PDOException $e) {
			$_SESSION['error'] = 'Something went wrong.'.$e->getMessage();
		}
		header("location:notification.php");
		exit();
	} 
	
	//MARK AS READ
	if (isset($_GET['on'])) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `notification` SET `status`='1' WHERE id=? LIMIT 1");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong marking notification as unread.';
				header("location:notification.php");
				exit();
			}
		}catch(PDOException $e){
			$_SESSION['error']='Something went wrong.'.$e->getMessage();
		}
		header("location:notification.php");
		exit();
	} 
	
	//MARK AS UNREAD
	if (isset($_GET['off'])) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `notification` SET `status`='0' WHERE id=? LIMIT 1");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong marking notification as unread.';
				header("location:notification.php");
				exit();
			}
		}catch(PDOException $e){
			$_SESSION['error']='Something went wrong.'.$e->getMessage();
		}
		header("location:notification.php");
		exit();
	} 
	
	
	