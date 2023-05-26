<?php
	error_reporting(0);
	session_start();
	include "include/conn.php";
	include "include/session.php";
	include "include/function.php";
	
	// ADD SCHOLARSHIP PROGRAM
	if (isset($_POST['add'])) {
		$name = trim($_POST['sp_name']);
		$description = trim($_POST['sp_description']);

		// Input Validation
		if (empty($name) || empty($description)) {
			$_SESSION['error'] = 'All fields are required.';
			header("Location: scholarship_program.php");
			exit;
		}

		// Sanitize Input
		$name = filter_var($name, FILTER_SANITIZE_STRING);
		$description = filter_var($description, FILTER_SANITIZE_STRING);
		$created_on = date('Y-m-d H:i:s');
		
		if (addrecord('scholarship_program', ['name', 'description', 'created_on'], [$name, $description, $created_on])) {
			// Redirect after success
			$_SESSION['success'] = 'New Scholarship program added.';
			header("Location: scholarship_program.php");
			exit();
		} else {
			$_SESSION['error'] = 'Something went wrong adding scholarship provider.';
			header("Location:scholarship_program.php");
			exit();
		}
	}

	// EDIT SCHOLARSHIP PROGRAM
	if (isset($_POST['edit-program'])) {
		$sp_id = $_POST['edit_sp_id'];
		$sp_name = trim($_POST['edit_sp_name']);
		$sp_description = trim($_POST['edit_sp_description']);

		// Input Validation
		if (empty($sp_name) || empty($sp_description)) {
			$_SESSION['error'] = 'All fields are required.';
			header("Location: scholarship_program.php");
			exit();
		}

		if (!ctype_alpha($sp_name)) {
			$_SESSION['error'] = 'Name should contain only letters.';
			header("Location: scholarship_program.php");
			exit();
		}
		if (!preg_match('/^[a-zA-Z\s]+$/', $sp_name)){
			$_SESSION['error'] = "Name should only contain letters.";
			header("Location:profile.php");
			exit();
		}
		 if(!preg_match('/^[a-zA-Z\s]+$/', $sp_description) ) {
			$_SESSION['error'] = "Description should only contain letters.";
			header("Location:profile.php");
			exit();
		}
		// Sanitize Input
		$updated_on = date('Y-m-d H:i:s');

		// Update Scholarship Program
		$stmt = $con->prepare("UPDATE scholarship_program SET `name`=?, `description`=?, `updated_on`=? WHERE `id`=?");
		//$stmt->bind_param("sssi", $sp_name, $sp_description, $updated_on, $sp_id);

		if ($stmt->execute([$sp_name, $sp_description, $updated_on, $sp_id])) {
			// Redirect after success
			$_SESSION['success'] = 'Scholarship program updated.';
			header("Location: scholarship_program.php");
			exit();
		} else {
			$_SESSION['error'] = 'Something went wrong updating scholarship program.';
			header("Location: scholarship_program.php");
			exit();
		}
	}

	//DELETE SCHOLARSHIP PROGRAM
	if (isset($_GET['del'])) {
		$id = $_GET['id'];
		try {
			$stmt = $con->prepare("DELETE FROM `scholarship_program` WHERE id=? LIMIT 1");
			$stmt->execute([$id]);
			if ($stmt->rowCount() > 0) {
				$_SESSION['success'] = 'Scholarship Program removed';
			} else {
				$_SESSION['error'] = 'Scholarship Program not found or already removed';
			}
		} catch (Exception $e) {
			$_SESSION['error'] = 'Something went wrong.'. $e->getMessage();
		}
		header("location:scholarship_program.php");
		exit();
	} 


	
	