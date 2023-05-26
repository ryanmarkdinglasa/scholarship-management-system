<?php
	error_reporting(0);
	session_start();
	include"include/conn.php";
	include"include/session.php";
	
	//
	if (isset($_POST['title'], $_POST['start'], $_POST['end'], $_POST['color'])) {
		$title = trim($_POST['title']);
		$start = trim($_POST['start']);
		$end = trim($_POST['end']);
		$color = trim($_POST['color']);
		try {
			$sql = "INSERT INTO `events` (`user_id`, `title`, `start`, `end`, `color`, `created_on`) VALUES (?, ?, ?, ?, ?, NOW())";
			$stmt = $con->prepare($sql);
			$stmt->execute([$user['id'], $title, $start, $end, $color]);
		} catch (Exception $e) {
			$_SESSION['error'] = 'Something went wrong while adding a new event.';
		}
	}
	//
	if (isset($_POST['Event'][0], $_POST['Event'][1], $_POST['Event'][2])) {
		$id = trim($_POST['Event'][0]);
		$start = trim($_POST['Event'][1]);
		$end = trim($_POST['Event'][2]);
		try {
			$sql = "UPDATE `events` SET `start` = ?, `end` = ? WHERE `id` = ?";
			$stmt = $con->prepare($sql);
			$stmt->execute([$start, $end, $id]);
		} catch (Exception $e) {
			$_SESSION['error'] = 'Something went wrong while updating an event.';
		}
	}
	
	//
	if (isset($_POST['delete'], $_POST['id'])) {
	  $id = trim($_POST['id']);
	  try {
		$sql = "DELETE FROM `events` WHERE `id` = ?";
		$stmt = $con->prepare($sql);
		$stmt->execute([$id]);
	  } catch (Exception $e) {
		$_SESSION['error'] = 'Something went wrong while removing an event.';
	  }
	} elseif (isset($_POST['title'], $_POST['color'], $_POST['id'])) {
	  $id = trim($_POST['id']);
	  $title = trim($_POST['title']);
	  $color = trim($_POST['color']);

	  try {
		$sql = "UPDATE `events` SET `title` = ?, `color` = ? WHERE `id` = ?";
		$stmt = $con->prepare($sql);
		$stmt->execute([$title, $color, $id]);
	  } catch (Exception $e) {
		$_SESSION['error'] = 'Something went wrong while updating an event.';
	  }
	}
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit;



