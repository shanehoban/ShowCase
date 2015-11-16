<?php
	error_reporting(0);
	session_start();

	require_once('functions.php');
	require_once('config.php');

	if($_SERVER['REQUEST_METHOD'] != 'GET'){
		header('Location: ../index.php?error=no');
		exit();
	}

	//method=delete_section&type=unused&section_id=2

	//	$ = (isset($_GET['']) && !empty($_GET[''])) ? '' : '';

	$method 	= (isset($_GET['method']) && !empty($_GET['method'])) ? $_GET['method'] : '';
	$sectionId = (isset($_GET['section_id']) && !empty($_GET['section_id'])) ? $_GET['section_id'] : '';

	if(isset($method) && !empty($method)){

		if($method === "delete_section"){
			if(isset($sectionId) && !empty($sectionId)){

				$query = $pdo->prepare('DELETE FROM sc_sections WHERE id = :sectionId');
				$query->bindParam(':sectionId', $sectionId);
				$query->execute();

				$query = $pdo->prepare('UPDATE sc_projects SET section_id = 0 WHERE section_id = :sectionId');
				$query->bindParam(':sectionId', $sectionId);
				$query->execute();

				header('Location: ../settings.php?success=section_deleted');
				exit();

			} else {
				header('Location: ../settings.php?error=no&5');
				exit();
			}
		} else if($method === "project_swap"){
			$swap 	= (isset($_GET['swap']) && !empty($_GET['swap'])) ? $_GET['swap'] : '';
			$with = (isset($_GET['with']) && !empty($_GET['with'])) ? $_GET['with'] : '';

			if(isset($swap) && !empty($swap) && isset($with) && !empty($with)){

				$query = $pdo->prepare('SELECT section_id FROM sc_projects WHERE pos = :swap LIMIT 1');
				$query->bindParam(':swap', $swap);
				$query->execute();
				$row = $query->fetch();
				$sec1 = $row['section_id'];

				$query = $pdo->prepare('SELECT section_id FROM sc_projects WHERE pos = :with LIMIT 1');
				$query->bindParam(':with', $with);
				$query->execute();
				$row = $query->fetch();
				$sec2 = $row['section_id'];

				if($sec1 !== $sec2){
					header('Location: ../settings.php?error=no&5&that_was_bold');
					exit();
				}

				$query = $pdo->prepare('UPDATE sc_projects AS pros1 JOIN sc_projects AS pros2 ON (pros1.pos = :swap AND pros2.pos = :with ) SET pros1.pos = pros2.pos, pros2.pos = pros1.pos;');
				$query->bindParam(':swap', $swap);
				$query->bindParam(':with', $with);
				$query->execute();

				header('Location: ../settings.php?success=projects_swapped');
				exit();


			} else {
				header('Location: ../settings.php?error=no&4');
				exit();
			}
		} else if($method === "section_swap"){

			$swap 	= (isset($_GET['swap']) && !empty($_GET['swap'])) ? $_GET['swap'] : '';
			$with = (isset($_GET['with']) && !empty($_GET['with'])) ? $_GET['with'] : '';

			if(isset($swap) && !empty($swap) && isset($with) && !empty($with)){

				$query = $pdo->prepare('UPDATE sc_sections AS secs1 JOIN sc_sections AS secs2 ON (secs1.section_pos = :swap AND secs2.section_pos = :with ) SET secs1.section_pos = secs2.section_pos, secs2.section_pos = secs1.section_pos;');
				$query->bindParam(':swap', $swap);
				$query->bindParam(':with', $with);
				$query->execute();

				header('Location: ../settings.php?success=sections_swapped');
				exit();

			} else{
				header('Location: ../settings.php?error=no&3');
				exit();
			}
	} else { // else method not set
		header('Location: ../settings.php?error=no&1');
		exit();
	}
} else { // else method not set
	header('Location: ../settings.php?error=no');
	exit();
	}