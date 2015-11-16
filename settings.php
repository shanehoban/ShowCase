<?php
	error_reporting(0);
	session_start();

	if(isset($_SESSION['username']) && !empty($_SESSION['username'])){
		$session = $_SESSION['username'];
	} else {
		header('Location: index.php?error=login');
	}

	require_once('inc/functions.php');
  	require_once('inc/config.php');

	if (!file_exists('sc_projects') || empty($dbName) || !tablesExist($pdo)) {
	    header('Location: install.php');
	    exit();
	}

	$sectionsInUse = getSectionsInUse($pdo);

	$allSections = getAllSections($pdo);

	$sectionsNotInUse = [];

	foreach ($allSections as $section){
		$inUse = false;
		foreach ($sectionsInUse as $sec){
			if($sec['section_name'] === $section['section_name']){
				$inUse = true;
			}
		}
		if(!$inUse){
			array_push($sectionsNotInUse, $section);
		}
	}

	$projectsWithoutSections = getProjectsWithoutSections($pdo);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Settings | ShowCase</title>

    <?php require_once('inc/inc.php'); ?>

  </head>

 <body>

 <?php require_once('inc/msg.php'); ?>

  <div class="container">
  	<header>
		 <h2>Settings</h2>
		 	<nav>
		 		<?php if(!empty($admin)){
		 			if(isset($session) && !empty($session)){ ?>
		 				<a href="manage.php?method=add">manage projects</a>
		 				<a class="active" href="settings.php">settings</a>
		 				<a href="logout.php">logout</a>
		 			<?php } else { ?>
		 			<a class="login-btn" href="#">login</a>
		 			<?php } ?>
		 		<?php } ?>
		 	</nav>

		 <span class="back-to-link">

		 		<a href="index.php"><i class="fa fa-long-arrow-left"></i> back to ShowCase</a>
		 </span>

		<hr>
	</header>


		<div class="col col-md-4">
			<div class="showcase-box settings-box">

				<span class="showcase-tooltip">
					<i class="fa fa-info-circle add-edit-tt pull-right"></i>
					<span class="tooltip-text project-order-tt-text pull-right">
						<i class="fa fa-times pull-right close-edit-tt"></i>
						Move your projects and sections up/down
					</span>
				</span>

				<h3>Project Order</h3>

				<?php

					if(isset($sectionsInUse) && !empty($sectionsInUse)){

						$lastSectionId = false;
						$topSection = true;
						$lastSection = false;
						$sectionCount = 0;

						foreach($sectionsInUse as $section){
							$sectionCount++;

							if(!$lastSectionId){
								$topSection = true;

								} else {
									$topSection = false;
								}

								if($sectionCount === sizeof($sectionsInUse)){
									$lastSection = true;
									$nextSectionPos = false;
								} else {
									$nextSectionPos = $sectionsInUse[$sectionCount]['section_pos'];
								}

							echo '<h4 class="section-header">' . $section['section_name'] . '</h4>';
							echo (!$topSection) ? "<a class='move-up-button' title='Move " . $section['section_name'] . " Section Up' href='inc/settings.php?method=section_swap&swap=". $section['section_pos'] . "&with=$lastSectionId'><i class='fa fa-chevron-up'></i></a>" : '';
							echo (!$lastSection) ? "<a class='move-down-button' title='Move " . $section['section_name'] . " Section Down' href='inc/settings.php?method=section_swap&swap=". $section['section_pos'] . "&with=$nextSectionPos'><i class='fa fa-chevron-down'></i></a>" : '';

							$lastSectionId = $section['section_pos'];


							$projects = getProjectsBySection($section['section_id'], $pdo);

							$lastId = false;
							$top = true;
							$last = false;
							$count = 0;

							foreach($projects as $project){
								$count++;

								if(!$lastId){
									$top = true;
								} else {
									$top = false;
								}

								if($count === sizeof($projects)){
									$last = true;
									$nextId = false;
								} else {
									$nextId = $projects[$count]['pos'];
								}

								echo '<div>' . $project['name'];
								echo (!$top) ? "<a class='move-up-button' title='Move " . $project['name'] . " Up' href='inc/settings.php?method=project_swap&swap=". $project['pos'] . "&with=$lastId'><i class='fa fa-chevron-up'></i></a>" : '';
								echo (!$last) ? "<a class='move-down-button' title='Move " . $project['name'] . " Down' href='inc/settings.php?method=project_swap&swap=". $project['pos'] . "&with=$nextId'><i class='fa fa-chevron-down'></i></a>" : '';
								echo '</div>';

								$lastId = $project['pos'];

							} // end foreach each project without sections

							if($sectionCount !== sizeof($sectionsInUse)){
									echo '<hr>';
								}

						} // end for each section in use

					} // end if sections in use

					if(isset($projectsWithoutSections) && !empty($projectsWithoutSections)){

						echo '<hr>';

						if(isset($sectionsInUse) && !empty($sectionsInUse)){
							echo '<h4>Other</h4>';
						}

						if(sizeof($projectsWithoutSections) === 1){
							echo '<div>' . $projectsWithoutSections[0]['name'] . '</div>';
						} else {

							$lastId = false;
							$top = true;
							$last = false;
							$count = 0;

							foreach($projectsWithoutSections as $project){
								$count++;

								if(!$lastId){
									$top = true;

								} else {
									$top = false;
								}

								if($count === sizeof($projectsWithoutSections)){
									$last = true;
									$nextId = false;
								} else {
									$nextId = $projectsWithoutSections[$count]['pos'];
								}

								echo '<div>' . $project['name'];
								echo (!$top) ? "<a class='move-up-button' title='Move " . $project['name'] . " Up' href='inc/settings.php?method=project_swap&swap=". $project['pos'] . "&with=$lastId'><i class='fa fa-chevron-up'></i></a>" : '';
								echo (!$last) ? "<a class='move-down-button' title='Move " . $project['name'] . " Down' href='inc/settings.php?method=project_swap&swap=". $project['pos'] . "&with=$nextId'><i class='fa fa-chevron-down'></i></a>" : '';
								echo '</div>';

								$lastId = $project['pos'];

							} // end foreach each project without sections
						}


					}

					/*
						If no projects in use at all!
					 */

					if(empty($projectsWithoutSections) && empty($sectionsInUse)){
						echo '<span class="light-gray">You have no projects in your ShowCase</span>';
					}

				?>



			</div> <!-- end showcase box -->
		</div>


		<div class="col col-md-4 col-md-offset-4">
		 <div class="showcase-box settings-box">


			<span class="showcase-tooltip pull-right">
					<i class="fa fa-info-circle pull-right add-edit-tt"></i>
					<span class=" tooltip-text delete-section-tt pull-left">
						<i class="fa fa-times pull-right close-edit-tt"></i>
						Deleting sections here will only remove the section name
						<br> --- <br>
						It will not delete any projects from your ShowCase
					</span>
				</span>

  				<h4>Manage Sections</h4>

	  				<p>
	  					Sections in use:
	  				</p>


  				<?php if(isset($sectionsInUse) && !empty($sectionsInUse)){ ?>

					<ul>
						<?php foreach($sectionsInUse as $section){ ?>
							<li>
								<?php echo $section['section_name'];?> <a title="Delete <?php echo $section['section_name'];?> Section"  class="red-link delete-section-link" href="inc/settings.php?method=delete_section&section_id=<?php echo $section['section_id'];?>">
								<i class="fa fa-trash-o delete"></i>
								</a>
							</li>
						<?php } // end foreach sections ?>
					</ul>
				<?php } else { // end if $sectionsInUse set?>

					<span class="light-gray">You are not using any sections</span>

				<?php } // end else $sectionsInUse not set?>


			<hr>

					<p>
		  				Sections not in use:
		  			</p>

					<?php if(isset($sectionsNotInUse) && !empty($sectionsNotInUse)){ ?>
						<ul>
							<?php foreach($sectionsNotInUse as $section){ ?>
								<li>
									<?php echo $section['section_name'];?> <a title="Delete <?php echo $section['section_name'];?> Section"  class="red-link delete-section-link" href="inc/settings.php?method=delete_section&section_id=<?php echo $section['id'];?>">
									<i class="fa fa-trash-o delete"></i></a>
								</li>
							<?php } // end foreach sections ?>
						</ul>
					<?php } else { // end if $sections set?>

						<span class="light-gray">You have no unused sections.</span>

					<?php } // end else $sections not set?>


			</div> <!-- end showcase box -->
		</div>



 	<?php require_once('inc/footer.php'); ?>

  </div> <!-- end container -->

	<?php require_once('inc/login_box.php'); ?>

 </body>
</html>