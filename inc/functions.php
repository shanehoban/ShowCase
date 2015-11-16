<?php

	function tablesExist($pdo){
		if(isset($pdo) && !empty($pdo)){
			$checkCount = 0;
		    $query = $pdo->query("SHOW TABLES LIKE 'sc_admin'");
		      if($query->rowCount() > 0){
		         $checkCount++;
		      }

		    $query = $pdo->query("SHOW TABLES LIKE 'sc_projects'");
		      if($query->rowCount() > 0){
		        $checkCount++;
		      }

		    $query = $pdo->query("SHOW TABLES LIKE 'sc_sections'");
		      if($query->rowCount() > 0){
		        $checkCount++;
		      }
		    return ($checkCount === 3) ? true : false;
		} else return false;
	}


	function getSectionHeader($sectionId, $pdo){
		$query = $pdo->prepare('SELECT * FROM sc_sections WHERE id = :section_id LIMIT 1');
		$query->bindParam(':section_id', $sectionId);
		$query->execute();
		$row = $query->fetch();
		return $row['section_name'];
	}

	function getProjects($pdo){

		// returns in order of position
		$sectionsInUse = getSectionsInUse($pdo);
		$projects = [];

		foreach ($sectionsInUse as $section){
			$query = $pdo->prepare('SELECT * FROM sc_projects WHERE section_id = :section_id ORDER BY pos ASC');
			$query->bindParam(':section_id', $section['id']);
			$query->execute();
			$pros = $query->fetchAll();
			foreach ($pros as $project){
				array_push($projects, $project);
			}
		}

		$query = $pdo->prepare('SELECT * FROM sc_projects WHERE section_id = 0 ORDER BY pos ASC');
		$query->execute();
		$pros = $query->fetchAll();
		foreach ($pros as $project){
			array_push($projects, $project);
		}

		return $projects;
	}

	function getProjectsWithoutSections($pdo){
		$query = $pdo->prepare('SELECT * FROM sc_projects WHERE section_id = 0 ORDER BY pos ASC');
		$query->execute();
		return $query->fetchAll();
	}

	function sortBySectionPos($a, $b) {
	    return $a['section_pos'] - $b['section_pos'];
	}

	function getSectionsInUse($pdo){
		$sections = [];
		$query = $pdo->prepare('SELECT DISTINCT(section_id) FROM sc_projects ORDER BY section_id ASC');
		$query->execute();
		$data = $query->fetchAll();

		foreach ($data as $row){
			if($row['section_id'] == 0){
				continue;
			} else {
				$query = $pdo->prepare('SELECT * FROM sc_sections WHERE id = :section_id LIMIT 1');
				$query->bindParam(':section_id', $row['section_id']);
				$query->execute();
				$section = $query->fetch();
				$sec = [];
				$sec['section_id'] = $row['section_id'];
				$sec['id'] = $section['id'];
				$sec['section_name'] = $section['section_name'];
				$sec['section_pos'] = $section['section_pos'];
				array_push($sections, $sec);
			}
		}
		usort($sections, 'sortBySectionPos');
		return $sections;
	}

	function getAllSections($pdo){
		$query = $pdo->prepare('SELECT * FROM sc_sections ORDER BY section_pos ASC');
		$query->execute();
		return $query->fetchAll();
	}

	/*
		Used in settings.php
	 */
	function getProjectsBySection($sectionId, $pdo){
		$query = $pdo->prepare('SELECT * FROM sc_projects WHERE section_id = :sectionId ORDER BY pos ASC');
		$query->bindParam(':sectionId', $sectionId);
		$query->execute();
		return $query->fetchAll();
	}