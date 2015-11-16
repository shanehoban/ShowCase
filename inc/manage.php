<?php
	error_reporting(0);
	session_start();

	if(isset($_SESSION['username']) && !empty($_SESSION['username'])){
		$session = $_SESSION['username'];
	} else {
		header('Location: index.php?error=login');
	}

	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		header('Location: ../index.php?error=no');
		exit();
	}

	if(	!isset($_POST['name']) || empty($_POST['name'])|| !isset($_POST['directory']) || empty($_POST['directory'])){
		header('Location: ../manage.php?error=something_missing');
		exit();
	}

	require_once('functions.php');
	require_once('config.php');

	$method = trim($_POST['method']);
	$name = trim($_POST['name']);
	$directory = $_POST['directory'];
	$img = (isset($_POST['img']) && !empty($_POST['img'])) ? trim($_POST['img']) : '';
	$description = (isset($_POST['description']) && !empty($_POST['description'])) ? trim($_POST['description']) : '';
	$section = (isset($_POST['section']) && !empty($_POST['section'])) ? trim($_POST['section']) : '';


	if(strpos($directory, "http://") === false && strpos($directory, "https://") === false && strpos($directory, "www.") !== false){
		$directory = "http://" . $directory;
	}

	// Section Check
	if(isset($section) && !empty($section)){
		$query = $pdo->prepare('SELECT * FROM sc_sections WHERE section_name = :sectionName LIMIT 1');
		$query->bindParam(':sectionName', $section );
		$query->execute();
		$row = $query->fetch();
		if(isset($row) && !empty($row)){
			$sectionId = $row['id'];
		} else {
			$query = $pdo->prepare('INSERT INTO sc_sections VALUES(NULL, :sectionName, 0)');
			$query->bindParam(':sectionName', $section);
			$query->execute();

			$lastInsertId = $pdo->lastInsertId();
			$query = $pdo->prepare('UPDATE sc_sections SET section_pos = :lastInsertId WHERE id = :lastInsertId');
			$query->bindParam(':lastInsertId', $lastInsertId);
			$query->execute();

			$query = $pdo->prepare('SELECT * FROM sc_sections WHERE section_name = :sectionName LIMIT 1');
			$query->bindParam(':sectionName', $section );
			$query->execute();
			$row = $query->fetch();
			$sectionId = $row['id'];
		}
	} else {
		$sectionId = 0;
	}

	if($method === "edit"){
		$projectID = trim($_POST['projectID']);
		$query = $pdo->prepare('UPDATE sc_projects SET name = :name, directory = :directory, img = :img, description = :description, section_id = :sectionId WHERE id = :projectID');
		$query->bindParam(':name', $name);
		$query->bindParam(':directory', $directory);
		$query->bindParam(':img', $img);
		$query->bindParam(':description', $description);
		$query->bindParam(':projectID', $projectID);
		$query->bindParam(':sectionId', $sectionId);
		$query->execute();

		if(strpos($directory, "http://") === false && strpos($directory, "https://") === false && strpos($directory, "www.") === false){
			if (!file_exists('../sc_projects/'.$directory)) {
			    header("Location: ../index.php?success=project_updated&warning=directory_doesnt_exist");
			    exit();
			}
		}

		header('Location: ../index.php?success=project_updated');
		exit();
	} else if($method === "add"){ // adding new project

		$query = $pdo->prepare('INSERT INTO sc_projects VALUES(NULL, :name, :directory, :img, :description, :sectionId, 0)');
		$query->bindParam(':name', $name);
		$query->bindParam(':directory', $directory);
		$query->bindParam(':img', $img);
		$query->bindParam(':description', $description);
		$query->bindParam(':sectionId', $sectionId);
		$query->execute();

		$lastInsertId = $pdo->lastInsertId();
		$query = $pdo->prepare('UPDATE sc_projects SET pos = :lastInsertId WHERE id = :lastInsertId');
		$query->bindParam(':lastInsertId', $lastInsertId);
		$query->execute();


		if(strpos($directory, "http://") === false && strpos($directory, "https://") === false && strpos($directory, "www.") === false){
			if (!file_exists('../sc_projects/'.$directory)) {
			    header("Location: ../index.php?success=project_added&warning=directory_doesnt_exist");
			    exit();
			}
		}

		header('Location: ../index.php?success=project_added');
		exit();


	} else if($method === "delete"){

		if(	!isset($_POST['projectID']) || empty($_POST['projectID'])){
			header('Location: ../manage.php?error=cannot_delete_nothing');
			exit();
		}

		$projectID = trim($_POST['projectID']);

		$query = $pdo->prepare('DELETE FROM sc_projects WHERE id = :projectID');
		$query->bindParam(':projectID', $projectID);
		$query->execute();

		header('Location: ../manage.php?success=project_deleted');
		exit();

	} else {

		header('Location: ../index.php?error=method_error');
		exit();

	}