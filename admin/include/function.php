<?php
	error_reporting(E_ALL);
	date_default_timezone_set('Asia/Manila'); // change according timezone
	function dbconnect(){
		global $DB_HOST,$DB_NAME,$DB_USER,$DB_PASS,$DB_CHARSET;
		$DB_HOST='localhost';
		$DB_NAME='dbiskalar';
		$DB_USER='root';
		$DB_PASS='';
		$DB_CHARSET='utf8mb4';
		$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$DB_CHARSET";
		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
		];
		try {
			$pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
		} catch (PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
			exit;
		}
		return $pdo;
	}
	
	//LOG RECORD
	function log_sign_in($username,$type) {
		if(empty($username) || empty($type)){
			return false; // return or throw error here, depending on your preference
		}
		try {
			$con = dbconnect();
			$sql = "INSERT INTO `user_log` (`username`,`type`, `sign_in`) VALUES (?,?, NOW())";
			$stmt = $con->prepare($sql);
			$stmt->bindParam(1, $username,$type, PDO::PARAM_STR);
			$stmt->execute();
			if ($stmt->rowCount() !== 1) {
				throw new Exception('Failed to insert user log');
			}
		} catch (PDOException $e) {
			// Log the error
			error_log($e->getMessage(), 0);
			return false; // return or throw error here, depending on your preference
		} finally {
			$con = null;
		}
		return true;
	}
	//LOG RECORD
	function log_sign_out($username) {
		if(!empty($username)) {
			try {
				$con = dbconnect();
				$stmt = $con->prepare("UPDATE `user_log` SET `sign_out` = NOW() WHERE `username` = ? ORDER BY `id` DESC LIMIT 1");
				$stmt->execute([$username]);
			} catch (PDOException $e) {
				// Log the error
				$_SESSION['error']='Somethign went wrong.'.$e->getMessage();
				error_log($e->getMessage(), 0);
				return false;
			} finally {
				$con = null;
			}
			return true;
		}
		return false;
	}



 
		
	//GET ALL RECORD
	function getall($table){
		if(empty($table)){
			return false;
		}
		$rows = null;
		$con = dbconnect();
		try{
			$stmt = $con->prepare("SELECT * FROM `$table`");
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(Exception $e){
			$_SESSION['error'] = 'Something went wrong.'.$e->getMessage();
			error_log($e->getMessage(), 0);
		}
		$con = null;
		return $rows;
	}

	
	//GET RECORD WITH A SPECIFIC QUERY
	function getrecord_query($sql, $data) {
		if(empty($sql) || empty($data)) {
			return false;
		}
		$row = null;
		$con = dbconnect();
		try {
			$stmt = $con->prepare($sql);
			$stmt->execute($data);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			$_SESSION['error'] = 'Something went wrong: ' . $e->getMessage();
		}
		$con = null;
		return $row;
	}
	
	//GET RECORD WITH A SPECIFIC QUERY
	function getrecord_query2($sql) {
		if(empty($sql) || empty($data)) {
			return false;
		}
		$row = null;
		$con = dbconnect();
		try {
			$stmt = $con->prepare($sql);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			$_SESSION['error'] = 'Something went wrong: ' . $e->getMessage();
		}
		$con = null;
		return $row;
	}

	
	//GET RECORD FROM DB
	function getrecord($table, $fields, $data) {
		if(empty($table) || empty($fields) || empty($data)) {
			return null;
		}
		try {
			$con = dbconnect();
			$fld = implode("=? AND ", $fields) . "=?";
			$sql = "SELECT * FROM `$table` WHERE $fld LIMIT 1";
			$stmt = $con->prepare($sql);
			$stmt->execute($data);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			// Log the error
			$_SESSION['error'] = 'Something went wrong: ' . $e->getMessage();
			error_log($e->getMessage(), 0);
			// Return null on error
			return null;
		}
		// Close connection
		$con = null;
		return $row;
	}

	
	//ADD RECORD TO DB
	function addrecord($table, $fields, $data) {
		// Validate input parameters
		if(empty($table) || empty($fields) || empty($data)) {
			return false;
		}
		if(count($fields) !== count($data)) {
			// Number of fields does not match number of data values
			return false;
		}
		// Sanitize input parameters
		$table = filter_var($table, FILTER_SANITIZE_STRING);
		$fields = array_map('trim', $fields);
		$data = array_map('trim', $data);
		// Connect to database
		try {
			$con = dbconnect();
		} catch(PDOException $e) {
			// Log error and return false on failure
			$_SESSION['error'] = 'Something went wrong: ' . $e->getMessage();
			error_log($e->getMessage(), 0);
			return false;
		}
		// Prepare SQL statement
		$flds = implode("`,`", $fields);
		$placeholders = implode(",", array_fill(0, count($data), "?"));
		$sql = "INSERT INTO `$table`(`$flds`) VALUES($placeholders)";
		$stmt = $con->prepare($sql);
		// Bind parameters
		try {
			for ($i = 0; $i < count($data); $i++) {
				$stmt->bindParam($i + 1, $data[$i]);
			}
		} catch(PDOException $e) {
			// Log error and return false on failure
			$_SESSION['error'] = 'Something went wrong: ' . $e->getMessage();
			error_log($e->getMessage(), 0);
			return false;
		}
		// Execute query
		try {
			$stmt->execute();
		} catch(PDOException $e) {
			// Log error and return false on failure
			error_log($e->getMessage(), 0);
			$_SESSION['error'] = 'Something went wrong: ' . $e->getMessage();
			return false;
		}
		// Check if one record was inserted
		if($stmt->rowCount() !== 1) {
			return false;
		}
		// Close database connection
		$con = null;
		// Return success
		return true;
	}

	//
	function updaterecord($table, $fields, $data) {
		//CHECK PARAMETERS IF EMPTY
		if(empty($table) || empty($fields) || empty($data)){
			return false;
		}
		//CHECK ID first
		$id_field = $fields[0];
		$id = $data[0];
		if(empty($id_field) || empty($id)){
			return false;
		}
		$set_fields = array();
		for ($i = 1; $i < count($fields); $i++) {
			$set_fields[] = "`$fields[$i]` = ?";
		}
		$set_fields_str = implode(", ", $set_fields);

		try{
			$con = dbconnect();
			$sql = "UPDATE `$table` SET $set_fields_str WHERE `$id_field` = ? LIMIT 1";
			$stmt = $con->prepare($sql);
			$params = array_slice($data, 1);
			$params[] = $id;
			$stmt->execute($params);
			$flag = $stmt->rowCount();
		}catch(Exception $e){
			$_SESSION['error'] = 'Something went wrong.'.$e->getMessage();
			$flag = false;
		}
		$con = null;

		return $flag;
	}

	
	// DELETE RECORD
	function deleterecord($table, $id_field, $id) {
		if (empty($table) || empty($id_field) || empty($id)) {
			return false;
		}
		try {
			$con = dbconnect();
			$sql = "DELETE FROM `$table` WHERE `$id_field` = ?";
			$stmt = $con->prepare($sql);
			$stmt->execute([$id]);
			//
			$rows = $stmt->rowCount();
			$con = null;
			if ($rows > 0) {
				return true;
			} else {
				$_SESSION['error'] = 'Record not found';
				return false;
			}
		} catch (Exception $e) {
			$_SESSION['error'] = 'Something went wrong: ' . $e->getMessage();
			error_log($e->getMessage(), 0);
			return false;
		}
	}


	
	//TO CREATE A LONG DATE 
	function longdate($date){
		return $long_date = date('l, F j, Y \a\t g:i A', strtotime($date));
	}
	

	
	//TO CREATE A PAST TIME SINCE CREATED
	function created_on($createdDatetime){
		$createdDate = new DateTime($createdDatetime);
		$currentDate = new DateTime();
		$interval = $currentDate->diff($createdDate);
		$years = $interval->y;
		$months = $interval->m;
		$days = $interval->d;
		$hours = $interval->h;
		$minutes = $interval->i;
		$date='';
		if ($years > 0) {
			if($years==1){
				$text='year ago';
			}else{
				$text='years ago';
			}
			$date=$years.' '.$text ;
		} elseif ($months > 0) {
			if($months==1){
				$text='month ago';
			}else{
				$text='months ago';
			}
			$date= $months.' '.$text ;
		} elseif ($days > 0) {
			if($days==1){
				$text='day ago';
			}else{
				$text='days ago';
			}
			$date= $days.' '.$text ;
		} elseif ($hours > 0) {
			if($hours==1){
				$text='hour ago';
			}else{
				$text='hours ago';
			}
			$date= $hours.' '.$text ;
		} elseif ($minutes > 0) {
			if($minutes==1){
				$text='minute ago';
			}else{
				$text='minutes ago';
			}
			$date= $minutes.' '.$text ;
		} else {
			$date ='Just now';
		}
		return $date;
	}
	
	//SEND NOTIFCATION
	function notify($recepient,$sender,$content){
		if(!empty($recepient) &&!empty($sender) &&!empty($content) ){
			return addrecord('notifcation',['recepient_id','sender_id','content','status','created_on`'],[$recepient,$sender,$content,1,]);
		}//
		else{
			return false;
		}
	}
	
	//GET COUNT like how many notifcations you have
	function count_notification($table,$user){
		try {
			$con=dbconnect();
			$stmt = $con->prepare("SELECT `recepient_id` FROM `$table` WHERE `recepient_id`=? AND `status`='0' ");
			$stmt->execute([$user]);
			$rows = $stmt->rowCount();
			return $rows;
		} catch(PDOException $e) {
			$_SESSION['error'] = 'Something went wrong: ' . $e->getMessage();
			//echo "Error: " . $e->getMessage();
		}
		$con = null;
	}

	//GENERATE A RANDOM PASSWORD
	function generate_password($length = 10) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+=-{}[]\|:;,<.>/?';
		$password = '';
		for ($i = 0; $i < $length; $i++) {
			$password .= $chars[rand(0, strlen($chars) - 1)];
		}
		return $password;
	}
	
	//SEND AN EMAIL AFTER CREATING AN SP,HEI ACCOUNT
	function send_password($name,$email,$role,$password){
				$mail = new PHPMailer(true);
				$flag=false;
				try {
				  $mail->isSMTP();
				  $mail->Host = 'smtp.gmail.com';
				  $mail->SMTPAuth = true;
				  // Gmail ID which you want to use as SMTP server
				  $mail->Username = 'hackfree.iskalar@gmail.com';
				  // Gmail Password
				  $mail->Password = 'dkaxszymnpvvxvvm';
				  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				  $mail->Port = 587;

				  // Email ID from which you want to send the email
				  $mail->setFrom('hackfree.iskalar@gmail.com');
				  // Recipient Email ID where you want to receive emails
				  $mail->addAddress($email);

				  $mail->isHTML(true);
				  $mail->Subject = "Iskalar Application";
				  $mail->Body = "
							<p>$name,</p>
							<br>
							<p>
							You recently requested a scholarship $role account: $email. 
							This operation has completed successfully. You are now able to login to your account.</p>
							p>Your new scholarship admin password is: $password</p>
							<p>You will be requested to change this password once you successfully log into your account.</p>
							<p>Thanks for being an active member of<em><strong>&nbsp;ISKALAR.</strong></em></p>
							<p>Best Regards,<br />
							<b>Hackfree Team</b></p>";
				  $mail->send();
				  $flag=true;
				} catch (Exception $e) {
					$flag= false;
				}
		return $flag;
	}
	
	//SEND EMAIL
	function send_email2($email,$message){
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => "https://rapidprod-sendgrid-v1.p.rapidapi.com/mail/send",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode([
				'personalizations' => [['to' => [['email' => $email]],'subject' => 'Iskalar Account Request']],
				'from' => ['email' => 'hackfree.iskalar@gmail.com'],
				'content' => [['type' => 'text/plain','value' => $message]]
			]),
			CURLOPT_HTTPHEADER => [
				"X-RapidAPI-Host: rapidprod-sendgrid-v1.p.rapidapi.com",
				"X-RapidAPI-Key: 3e7b41810dmsh36643b0730bb46fp1ab16bjsnf465416451bc",
				"content-type: application/json"
			],
		]);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			echo "cURL Error #:" . $err;
			return false;
		} else {
			echo $response;
			return true;
		}
	}
	
	//SHORTEN THE LONG TEXT
	function short_text($text){
		if(!empty($text)){
			if(strlen($text)>30){
				$text = substr($text, 0, 30) . "...";
				return $text;
			}
			return $text;
		}
	}
	
	//SEND EMAIL
	function send_email($email,$subject,$message){
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => "https://rapidprod-sendgrid-v1.p.rapidapi.com/mail/send",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode([
				'personalizations' => [['to' => [['email' => $email]],'subject' => $subject]],
				'from' => ['email' => 'hackfree.iskalar@gmail.com'],
				'content' => [['type' => 'text/plain','value' => $message]]
			]),
			CURLOPT_HTTPHEADER => [
				"X-RapidAPI-Host: rapidprod-sendgrid-v1.p.rapidapi.com",
				"X-RapidAPI-Key: 3e7b41810dmsh36643b0730bb46fp1ab16bjsnf465416451bc",
				"content-type: application/json"
			],
		]);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			echo "cURL Error #:" . $err;
			return false;
		} else {
			echo $response;
			return true;
		}
	}
	
	// OTP generator function
	function generateOTP() {
		$digits = '0123456789';
		$otp = '';
		for($i = 0; $i < 6; $i++) {
			$otp .= $digits[rand(0, 9)];
		}
		return $otp;
	}