<?php
	error_reporting(0);
	session_start();

	require_once('functions.php');
	require_once('config.php');
	require_once('password.php');

	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		header('Location: ../index.php?error=no');
		exit();
	}

	if(	!isset($_POST['username']) || !isset($_POST['password'])){
		header('Location: ../index.php?error=something_missing');
		exit();
	}

	$username 	= trim($_POST['username']);
	$password 	= trim($_POST['password']);

	$query = $pdo->prepare('SELECT * FROM sc_admin WHERE username = :username');
	$query->bindParam(':username', $username);
	$query->execute();
	$row = $query->fetch();

	if(isset($row) && !empty($row) && password_verify($password, $row['password'])){
		$_SESSION['username'] = $row['username'];
		header('Location: ../index.php');
		exit();
	} else{
		header('Location: ../index.php?error=wrong');
		exit();
	}