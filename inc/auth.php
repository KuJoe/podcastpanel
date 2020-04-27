<?php

/** Includes code from https://codeshack.io/secure-login-system-php-mysql/#creatingtheloginformdesign **/

	session_start();
	require('config.php');
	require('functions.php');
	$con = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
	if(mysqli_connect_errno()) {
		#$err = 'Failed to connect to MySQL: ' . mysqli_connect_error();
		$err = 'Failed to connect to MySQL.';
		$_SESSION['ERR'] = $err;
		header('Location: ../admin/login.php');
		exit;
	}

	if(!isset($_POST['username'], $_POST['password'])) {
		$err = 'Please fill both the username and password fields!';
		$_SESSION['ERR'] = $err;
		header('Location: ../admin/login.php');
		exit;
	}

	if($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
		$stmt->bind_param('s', $_POST['username']);
		$stmt->execute();
		$stmt->store_result();

		if($stmt->num_rows > 0) {
			$stmt->bind_result($id, $password);
			$stmt->fetch();
			if(password_verify($_POST['password'], $password)) {
				$msg = 'New login recorded! ('.$_POST['username'].')';
				logit($id,$msg);
				updateIP($id);
				session_regenerate_id();
				$_SESSION['loggedin'] = TRUE;
				$_SESSION['name'] = $_POST['username'];
				$_SESSION['uid'] = $id;
				header('Location: ../admin/index.php');
				return true;
			} else {
				$msg = 'Failed login recorded! ('.$_POST['username'].')';
				logit('0',$msg);
				$err = 'Login failed.';
				$_SESSION['ERR'] = $err;
				header('Location: ../admin/login.php');
				exit;
			}
		} else {
			$msg = 'Failed login recorded! ('.$_POST['username'].')';
			logit('0',$msg);
			$err = 'Login failed.';
			$_SESSION['ERR'] = $err;
			header('Location: ../admin/login.php');
			exit;
		}

		$stmt->close();
	}
?>