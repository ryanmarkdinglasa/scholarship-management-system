<?php
	error_reporting(0);
	session_start();
	include "include/conn.php";
	include "include/session.php";
	include "include/function.php";
	
	//ADD POST
	if (isset($_POST['post'])) {
        $user_id = $user['id'];;
        $title = trim($_POST["title"]);
        $context = trim($_POST["context"]);
        $status = 1;
		$created_on = date('Y-m-d H:i:s');
		if(empty($user_id) || empty($title) || empty($context)){
			$_SESSION['error'] = "All fields are required.";
			header("location:announcement.php");
			exit();
		}
		if (!addrecord('post',['user_id','title','context','status','created_on'],[$user_id,$title,$context,$status,$created_on])){
			$_SESSION['error'] = "Something went wrong in posting announcement.";
			header("location:announcement.php");
			exit();
		}else{
			$_SESSION['success'] = " Announcement posted.";
			header("location:announcement.php");
		exit();
		}
    }

	// DELETE POST
	if (isset($_GET['del'])) {
		$id = $_GET['id'];
		try {
			$stmt = $con->prepare("DELETE FROM `post` WHERE id=? LIMIT 1");
			if ($stmt->execute([$id])) {
				$_SESSION['success'] = 'Post removed.';
				header("location:announcement.php");
				exit();
			} else {
				$_SESSION['error'] = 'Failed to remove post.';
				header("location:announcement.php");
				exit();
			}
		} catch (PDOException $e) {
			$_SESSION['error'] = 'Something went wrong.' . $e->getMessage();
		}
		header("location:announcement.php");
		exit();
	}
	
	//EDIT POST
		if (isset($_POST['edit'])) {
		$post_id=$_POST['post_id'];
        $user_id = $user['id'];;
        $title = $_POST["title"];
        $context = $_POST["context"];
        $status = 1;
		$updated_on = date('Y-m-d H:i:s');
		if(empty($user_id) || empty($title) || empty($context)){
			$_SESSION['error'] = "All fields are required.";
			header("location:announcement.php");
			exit();
		}
		if (!updaterecord('post',['id','user_id','title','context','status','updated_on'],[$post_id,$user_id,$title,$context,$status,$updated_on])){
			$_SESSION['error'] = "Something went wrong in posting announcement.";
			header("location:announcement.php");
			exit();
		}else{
			$_SESSION['success'] = "Post edited.";
			header("location:announcement.php");
			exit();
		}
    }

	
	/*
	//ACTIVATE ADMIN
	if (isset($_GET['on'])) {
		$id = $_GET['id'];
		try {
			$stmt = $con->prepare("UPDATE `user` SET `status`='1' WHERE id=? AND `type`='admin' LIMIT 1");
			if (!$stmt->execute([$id])) {
				$_SESSION['error'] = 'Something went wrong activating an admin. Please try again.';
				header("location:admin.php");
				exit();
			}
		} catch (PDOException $e) {
			$_SESSION['error'] = 'Something went wrong.'. $e->getMessage();
		}
		header("location:admin.php");
		exit();
	}

	
	//DEACTIAVTE ADMIN
	if (isset($_GET['off'])) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `user` SET `status`='0' WHERE id=? AND `type`='admin' LIMIT 1");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong deactivating an admin. Please try again.';
				header("location:admin.php");
				exit();
			}
		}catch(PDOException $e){
			$_SESSION['error']='Something went wrong.'. $e->getMessage();
		}
		header("location:admin.php");
		exit();
	} 
	*/