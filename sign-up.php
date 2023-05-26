	<?php
		error_reporting(0);
		session_start();
		include("include/conn.php");
		include("include/function.php");
		
		
		if (isset($_POST["signup"])) {
			//
			$username=trim($_POST['username']);
			$password=password_hash(trim($_POST['password']),PASSWORD_DEFAULT);
			$firstname=trim($_POST['firstname']);
			$middlename=trim($_POST['middlename']);
			$lastname=trim($_POST['lastname']);
			$contactno=trim($_POST['contactno']);
			$birthdate=trim($_POST['birthdate']);
			$birthplace=trim($_POST['birthplace']);
			$gender=trim($_POST['gender']);
			$civil_status=trim($_POST['civil_status']);
			$citizenship=trim($_POST['citizenship']);
			$permanent_address=trim($_POST['permanent_address']);
			$zipcode=trim($_POST['zipcode']);
			$school_name=trim($_POST['school_name']);
			$school_address=trim($_POST['school_address']);
			$school_type=trim($_POST['school_type']);
			$educational_attainement=trim($_POST['educational_attainement']);
			$disability=trim($_POST['disability']);
			$father_vital_status=trim($_POST['father_vital_status']);
			$father_name=trim($_POST['father_name']);
			$father_occupation=trim($_POST['father_occupation']);
			$father_address=trim($_POST['father_address']);
			$father_educationalAtt=trim($_POST['father_educationalAtt']);
			$mother_vital_status=trim($_POST['mother_vital_status']);
			$mother_name=trim($_POST['mother_name']);
			$mother_occupation=trim($_POST['mother_occupation']);
			$mother_address=trim($_POST['mother_address']);
			$mother_educationalAtt=trim($_POST['mother_educationalAtt']);
			$gross_income=trim($_POST['gross_income']);
			$siblings=trim($_POST['siblings']);
			$school_intended=$_POST['school_intended'];
			$school_intended_address=trim($_POST['school_intended_address']);
			$school_intended_type=trim($_POST['school_intended_type']);
			$numbersRegex = '/^[0-9]+$/';
			$digitRegex = '/^\d{10}$/';
			$address_pattern = "/^[a-zA-Z0-9\s\-\.,#]+$/";
			if(empty($username)){
				$_SESSION['error']='Email field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($password)){
				$_SESSION['error']='Password field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($firstname)){
				$_SESSION['error']='First name field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($lastname)){
				$_SESSION['error']='Last name field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($contactno)){
				$_SESSION['error']='Mobile No. field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($birthdate)){
				$_SESSION['error']='Date of birth field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($birthplace)){
				$_SESSION['error']='Place of birth field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($citizenship)){
				$_SESSION['error']='Citizenship field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($permanent_address)){
				$_SESSION['error']='Permanent address field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($zipcode)){
				$_SESSION['error']='Zip code field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($school_name)){
				$_SESSION['error']='High school name field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($school_address)){
				$_SESSION['error']='High school address field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($educational_attainement)){
				$_SESSION['error']='Educational attainment field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($father_name)){
				$_SESSION['error']='Father`s name field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($father_occupation)){
				$_SESSION['error']='Father`s occupation field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($father_address)){
				$_SESSION['error']='Father`s address field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($mother_name)){
				$_SESSION['error']='Mother`s name field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($mother_occupation)){
				$_SESSION['error']='Mother`s occupation field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($mother_address)){
				$_SESSION['error']='Mother`s name address field is empty.';
				header("location:register.php");
				exit;
			}
			if(empty($siblings)){
				$_SESSION['error']='Number of siblins field is empty.';
				header("location:register.php");
				exit;
			}
			/* if(empty($school_intended)){
				$_SESSION['error']='School intended field is empty.';
				header("location:register.php");
				exit;
			}*/
			if(empty($school_intended_address)){
				$_SESSION['error']='School intended address fields are empty.';
				header("location:register.php");
				exit;
			}
			//VALIDATE THE SYSNTAX OF EMAIL/USERNAME
			if (!preg_match("/^\S+@\S+\.\S+$/", $username)) {
			$_SESSION['error'] = "Invalid email address.";
			header("Location:register.php");
			exit();
			}if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
				$_SESSION['error'] = "Invalid email address.";
				header("Location:register.php");
				exit();
			}
			//CHECK USERNAME IF THERE IS ANY DUPLICATION
			$check_username=getrecord('user',['username',],[$username]);
			if(!empty($check_username)){
				$_SESSION['error']='Email is already taken.';
				header("location:register.php");
				exit();
			}
			//VALIDATE MOBILE NO
			if (!preg_match($numbersRegex, $contactno) || !preg_match($digitRegex, $contactno) || substr($contactno, 0, 1) !== "9"  || strlen($contactno)!= 10) {
			$_SESSION['error'] = "Invalid mobile number.";
			header("Location:register.php");
			exit();
			}
			if(!empty($middlename)){
				if (!preg_match('/^[a-zA-Z\s]+$/', $firstname)|| !preg_match('/^[a-zA-Z\s]+$/', $middlename) || !preg_match('/^[a-zA-Z\s]+$/', $lastname) ) {
				$_SESSION['error'] = "Name should only contain letters.";
				header("Location:register.php");
				exit();
				}
			}
			if (!preg_match('/^[a-zA-Z\s]+$/', $firstname) || !preg_match('/^[a-zA-Z\s]+$/', $lastname) ) {
				$_SESSION['error'] = "Name should only contain letters.";
				header("Location:register.php");
				exit();
				}
			//VALIDATE ADDRESS
			if(!preg_match('/^[a-zA-Z0-9\s\-\.,#]+$/', $permanent_address)){
				$_SESSION['error'] = "Invalid address, special characters are not allowed.";
				header("Location:register.php");
				exit();
			}
			//VALIDATE BIRTHPLACE
			if(!preg_match('/^[a-zA-Z0-9\s\-\.,#]+$/', $birthplace)){
				$_SESSION['error'] = "Invalid address, special characters are not allowed.";
				header("Location:register.php");
				exit();
			}
			//VALIDATE PARENTS ADDRESS
			if(!preg_match('/^[a-zA-Z0-9\s\-\.,#]+$/', $father_address)||!preg_match('/^[a-zA-Z0-9\s\-\.,#]+$/', $mother_address)){
				$_SESSION['error'] = "Invalid parents address, special characters are not allowed.";
				header("Location:register.php");
				exit();
			}
			//VALIDATE STUDENTS AGE/BIRTH-DATE, only 15yrs old and above are allowed to register
			$today = new DateTime();
			$minAgeDate = new DateTime($today->format('Y') - 15 . '-' . $today->format('m-d'));
			$selectedDate = new DateTime(substr($birthdate, 0, 4) . '-' . substr($birthdate, 5, 2) . '-' . substr($birthdate, 8, 2));
			if ($selectedDate > $minAgeDate) {
				$_SESSION['error'] = "Invalid birth date.";
				header("Location:register.php");
				exit();
			}
			//VALIATE PARENT's NAMES
			if (!preg_match('/^[a-zA-Z\s]+$/', $father_name)|| !preg_match('/^[a-zA-Z\s]+$/', $mother_name)) {
				$_SESSION['error'] = "Invalid parents name, name should only contain letters.";
				header("Location:register.php");
				exit();
			}
			//VALIADATE PARENTS OCCUPATION
			if (!preg_match('/^[a-zA-Z\s]+$/', $father_occupation)|| !preg_match('/^[a-zA-Z\s]+$/', $mother_occupation)) {
				$_SESSION['error'] = "Invalid parent's occupation, it should only contain letters.";
				header("Location:register.php");
				exit();
			}
			//VALIDATE GROSS INCOME
			if(!preg_match($numbersRegex,$siblings)){
				$_SESSION['error'] = "Invalid number of siblings, it should be a number.";
				header("Location:register.php");
				exit();
			}
			/*
			if (!preg_match('/^[a-zA-Z\s]+$/', $school_intended)) {
				$_SESSION['error'] = "Invalid school intended, it should only contain letters.";
				header("Location:register.php");
				exit();
			}*/
			//VALIDATE PARENTS ADDRESS
			if(!preg_match('/^[a-zA-Z0-9\s\-\.,#]+$/', $school_intended_address)){
				$_SESSION['error'] = "Invalid school intended address, special characters are not allowed.";
				header("Location:register.php");
				exit();
			}
			//VALIDATE PICTURE AND SIGNATUER
			define('KB', 1024);
			define('MB', 1048576);
			define('GB', 1073741824);
			define('TB', 1099511627776);
			$picture = $_FILES["picture"]["name"];
			$signature = $_FILES["signature"]["name"];
			$target_dir = "img/";
			$target_file1 = $target_dir . basename($_FILES["picture"]["name"]);
			$target_file2 = $target_dir . basename($_FILES["signature"]["name"]);
			$imageFileType1 = strtolower(pathinfo($target_file1, PATHINFO_EXTENSION));
			$imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));
			// // get the image extension
			if ($picture === "" || $picture===NULL ) {
				$_SESSION['error']='Please upload a 2x2 picture.';
				header("location:register.php");
				exit();
			}
			// // get the image extension
			if ($signature === "" || $signature===NULL ) {
				$_SESSION['error']='Please upload a signature.';
				header("location:register.php");
				exit();
			}
			$extension1 = substr($picture, strlen($picture) - 4, strlen($picture));
			$extension2 = substr($signature, strlen($signature) - 4, strlen($signature));
			if ($imageFileType1 != "jpg" && $imageFileType1 != "png" && $imageFileType1 != "jpeg") {
				$_SESSION['error']='The only allowed image format are JPG, PNG and JPEG.';
				header("location:register.php");
				exit();
			}
			if ($imageFileType2 != "jpg" && $imageFileType2 != "png" && $imageFileType2 != "jpeg") {
				$_SESSION['error']='The only allowed image format are JPG, PNG and JPEG.';
				header("location:register.php");
				exit();
			}
			$type='student';
			$status=0;
			$isPasswordChanged=0;
			$created_on=date('Y-m-d H:i:s');
			$result1=addrecord('student',
			['username','firstname','middlename','lastname','contact_no','birthdate', 'birthplace','gender','civil_status','citizenship','permanent_address','zipcode','school_name','school_address','school_type','educational_attainement','disability','father_vital_status','father_name','father_occupation','father_address','father_educationalAtt','mother_vital_status','mother_name','mother_occupation','mother_address','mother_educationalAtt','gross_income','siblings','school_intended','school_intended_address','school_intended_type','picture','signature','status','created_on'],
			[$username,$firstname,$middlename,$lastname,$contactno,$birthdate,$birthplace,$gender,$civil_status,$citizenship,$permanent_address,$zipcode,$school_name,$school_address,$school_type,$educational_attainement,$disability,$father_vital_status,$father_name,$father_occupation,$father_address,$father_educationalAtt,$mother_vital_status,$mother_name,$mother_occupation,$mother_address,$mother_educationalAtt,$gross_income,$siblings,$school_intended,$school_intended_address,$school_intended_type,$picture,$signature,$status,$created_on]);
			$result2=addrecord('user',['type','username','password','firstname','middlename','lastname','gender','birthdate','address','phone_no','isPasswordChanged','status','created_on'],[$type,$username,$password,$firstname,$middlename,$lastname,$gender,$birthdate,$permanent_address,$contactno,0,1,$created_on]);
			if($result1){
				//session_start();
				// Set session variables
				//CHECK USER
				$user=getrecord('user',['username','type'],[$username,$type]);
				if(!empty($user)){
					$_SESSION['type']=$type;
					$_SESSION[$type] = $user['id'];
					// Log sign-in
					log_sign_in( $user['username'],$type);
					// Redirect to appropriate page
					header("Location: student/");
					exit;
				}else{
					$_SESSION['error']='Something went wrong. Please sign-in your account.';
					header("location:index.php");
					exit();
				}
			}else{
					$_SESSION['error']='Something went wrong signing up. Please try again.';
					header("location:register.php");
					exit();
				}
		}
		else{
			// Use a generic error message to avoid username enumeration
			$_SESSION['error'] = 'Invalid username or password';
			header('location: index.php?error2');
			exit;
		}
		
