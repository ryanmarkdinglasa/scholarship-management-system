<?php
	error_reporting(0);
	session_start();
	include "include/conn.php";
	include "include/session.php";
	include "include/function.php";
	
	//RESET SCHOLARSHIP PROVIDER PASSWORD
	if (isset($_GET['reset']) && $_GET['reset'] == 'reset') {
		$id = $_GET['id'];
		$row = getrecord('user', ['id'], [$id]);
		$firstname = $row['firstname'];
		$email = $row['username'];
		$pass = generate_password();
		$isPasswordChanged = $row['isPasswordChanged'] + 1;
		$password = password_hash($pass, PASSWORD_DEFAULT);
		$updated_on = date('Y-m-d H:i:s');
		$result = updaterecord('user', ['id', 'password', 'isPasswordChanged', 'updated_on'], [$id, $password, $isPasswordChanged, $updated_on]);
		if ($result) {
			$message = "Dear $firstname,\n\nYou recently requested for a password reset on your account: $email.\n\nThis operation has been completed successfully. You are now able to login to your account using the new password provided below.\n\nNew Password: $pass\n\nPlease change this password once you successfully log into your account. Thank you for being an active member of ISKALAR.\n\nBest Regards,\nHackfree Team";
			if (send_email2($email, $message)) {
				$_SESSION['success'] = 'Password reset successful. Check your email for your new password.';
				header("location:scholarship_provider.php");
				exit();
			} else {
				$_SESSION['error'] = 'Failed to send new password to your email.';
				header("location:scholarship_provider.php");
				exit();
			}
			
		} else {
			$_SESSION['error'] = 'Something went wrong while resetting your password.';
			header("location:scholarship_provider.php");
			exit();
		}
	}
	
	
	//ADD SCHOLARSHIP PROVIDER
	if(isset($_POST['add'])){
		$username=trim($_POST['username']);
		$pass=generate_password();
		$password=password_hash($pass, PASSWORD_DEFAULT);
		$type='scholarship-provider';
		$firstname='New';
		$lastname='Staff';
		$scholarship_program=$user['staff_sp'];
		$status=1;
		$isPasswordChanged=0;
		$position=$_POST['position'];
		$created_on=date('Y-m-d H:i:s');
		
		$check_username=getrecord('user',['username',],[$username]);
		if(!empty($check_username)){
			$_SESSION['error']='Email is already taken.';
			header("location:scholarship_provider.php");
			exit();
		}
		
		$result=addrecord('user',['type', 'username','password','firstname','lastname','isPasswordChanged','status','created_on'],[$type, $username,$password,$firstname,$lastname,$isPasswordChanged,$status,$created_on]) && addrecord('staff',['username','sp_id','position_id','created_on'],[$username,$scholarship_program,$position,$created_on]);
		if($result){
			$message="
				\n$firstname,
				\n
				\nYou recently requested a scholarship account: $username.
				\nThis operation has completed successfully. You are now able to login to your account.
				\nYour new scholarship admin password is: $pass
				\nYou will be requested to change this password once you successfully log into your account.
				\nThanks for being an active member of ISKALAR.
				\n
				\nBest Regards,
				\nHackfree Team
			";
			if(send_email2($username,$message)){
				$_SESSION['success']='Account created, account password sent.';
				header("location:staff.php");
				exit();
			}else{
				$_SESSION['error']='Fail to send password.';
				header("location:staff.php");
				exit();
			}
		}
		else{
			$_SESSION['error']='Something went wrong adding scholarship provider.';
			header("location:staff.php");
			exit();
		}
	}

	//DELETE SCHOLARSHIP PROVIDER
	if (isset($_GET['del'])) {
		$id = $_GET['id'];
		try {
			$stmt = $con->prepare("DELETE FROM `user` WHERE id=? LIMIT 1");
			$stmt->execute([$id]);
			if ($stmt->rowCount() > 0) {
				$_SESSION['success'] = 'Scholarship provider removed';
				header("location:staff.php");
				exit();
			} else {
				$_SESSION['error'] = 'Scholarship provider not found or already removed';
				header("location:staff.php");
				exit();
			}
		} catch (Exception $e) {
			$_SESSION['error'] = 'Something went wrong.'. $e->getMessage();
		}
		header("location:staff.php");
		exit();
	}
	
	//ACTIVATE SCHOLARSHIP PROVIDER
	if (isset($_GET['on']) && $_GET['on']==1) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `user` SET `status`='1' WHERE id=? AND `type`='scholarship-provider' LIMIT 1");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong activating scholarship provider. Please try again.';
				header("location:staff.php");
				exit();
			}
		}catch(PDOException $e){
			$_SESSION['error']='Something went wrong.'.$e->getMessage();
		}
		header("location:staff.php");
		exit();
	} 
	
	//DEACTIVATE SCHOLARSHIP PROVIDER
	if (isset($_GET['off']) && $_GET['off']==0) {
		$id=$_GET['id'];
		try{
			$stmt = $con->prepare("UPDATE `user` SET `status`='0' WHERE id=? AND `type`='scholarship-provider'");
			if(!$stmt->execute([$id])){
				$_SESSION['error']='Something went wrong deactivating scholarship provider. Please try again.';
				header("location:staff.php");
				exit();
			}
		}catch(Exception $e){
			$_SESSION['error']='Something went wrong .'.$e->getMessage();
		}
		header("location:staff.php");
		exit();
	}		

	