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

	if(!empty($admin)){
		$projects = getProjects($pdo);
	}

	if(!isset($_GET['method']) && empty($_GET['method'])){
		$method = "add";
	} else {
		$method = $_GET['method'];
	}

	$name = (isset($_GET['name']) && !empty($_GET['name'])) ? $_GET['name'] : '';
	$directory = (isset($_GET['directory']) && !empty($_GET['directory'])) ? $_GET['directory'] : '';
	$img = (isset($_GET['img']) && !empty($_GET['img'])) ? $_GET['img'] : '';
	$description = (isset($_GET['description']) && !empty($_GET['description'])) ? $_GET['description'] : '';
	$section = (isset($_GET['section']) && !empty($_GET['section'])) ? $_GET['section'] : '';

	if($method === "edit" && isset($_GET['project']) && !empty($_GET['project'])){
		$projectID = $_GET['project'];
		$query = $pdo->prepare('SELECT * FROM sc_projects WHERE id = :projectID');
		$query->bindParam(':projectID', $projectID);
		$query->execute();
		$project = $query->fetch();

		$name = (isset($project['name']) && !empty($project['name'])) ? $project['name'] : '';
		$directory = (isset($project['directory']) && !empty($project['directory'])) ? $project['directory'] : '';
		$img = (isset($project['img']) && !empty($project['img'])) ? $project['img'] : '';
		$description = (isset($project['description']) && !empty($project['description'])) ? $project['description'] : '';
		$section = (isset($project['section_id']) && !empty($project['section_id'])) ? $project['section_id'] : '';

		if(isset($section) && !empty($section)){
			$query = $pdo->prepare('SELECT * FROM sc_sections WHERE id = :sectionId LIMIT 1');
			$query->bindParam(':sectionId', $section );
			$query->execute();
			$row = $query->fetch();
			if(isset($row) && !empty($row)){
				$sectionId = $row['id'];
				$section = $row['section_name'];
			}
		}
	}


	$title = ($method === "add") ? "Add Project" : "Edit " . $project['name'];

	$projectArray = [];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Manage Projects | ShowCase</title>

    <?php require_once('inc/inc.php'); ?>

  </head>

 <body>

 <?php require_once('inc/msg.php'); ?>

  <div class="container">
  	<header>
		 <h2>Manage Projects</h2>
		 	<nav>
		 		<?php if(!empty($admin)){
		 			if(isset($session) && !empty($session)){ ?>
		 				<a class="active" href="manage.php?method=add">manage projects</a>
		 				<a href="settings.php">settings</a>
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


	<div class="col col-md-5">
	 <div class="showcase-box add-edit-box">
		<h3><?php echo $title; ?></h3>
		<?php if($method === "edit"){ ?>
			<a class="pull-right add-link" href="manage.php?method=add">add new project</a>
		<?php } ?>
			<div class="manage-form">
				<form class="manage-form" method="POST" action="inc/manage.php">

			  		<p>
			  			<span class="label label-success">_&gt;</span> Project Name:*
			  			<br>
			  			<input name="name" class="setup-input" type="text" value="<?php echo $name;?>" maxlength="200">
			  		</p>

			  		<p>
			  			<span class="label label-success">_&gt;</span> Project Folder Name:*
			  				<span class="showcase-tooltip add-edit-tooltip pull-right">
			  					<i class="fa fa-info-circle pull-right add-edit-tt"></i>
			  					<span class="add-edit-tooltip-text tooltip-text">
			  						<i class="fa fa-times pull-right close-edit-tt"></i>
			  						The name of the folder your project is located, if placed within the <code>sc_projects</code> directory
			  						<br> --- <br>
			  						This can also be a full URL e.g. http://www.example.com/project1337
			  					</span>
			  				</span>
			  			<br>
			  			<input name="directory" class="setup-input" type="text" value="<?php echo $directory;?>" maxlength="200">
			  		</p>

			  		<p>
			  			<span class="label label-success">_&gt;</span> Link to Project Image:
			  			<br>
			  			<input name="img" class="setup-input" type="url" placeholder="http://" value="<?php echo $img;?>" maxlength="250">
			  		</p>

			  		<p>
			  			<span class="label label-success">_&gt;</span> Project Description:
			  			<br>
			  			<textarea name="description" class="setup-input add-edit-project-description" type="text" maxlength="1000"><?php echo rtrim($description);?></textarea>
			  		</p>

			  		<p class="project-section">
			  			<span class="label label-success">_&gt;</span> Project Section:
				  			<span class="showcase-tooltip add-edit-tooltip pull-right">
				  				<i class="fa fa-info-circle pull-right add-edit-tt"></i>
				  				<span class="add-edit-tooltip-text tooltip-text">
				  					<i class="fa fa-times pull-right close-edit-tt"></i>
				  						Split up your projects into different sections, e.g. Web, Android, Chrome
				  						<br> --- <br>
				  						Leave blank and your project will appear at the end of the list
				  				</span>
				  			</span>
			  			<br>
			  			<input name="section" class="setup-input" type="text" value="<?php echo $section;?>" maxlength="150">
			  		</p>

			  		<p>* denotes mandatory fields</p>

			  		<input name="method" class="setup-input edit-input-method" type="hidden" value="<?php echo $method; ?>">

			  		<?php if(isset($projectID) && !empty($projectID)){ ?>
			  			<input name="projectID" class="setup-input" type="hidden" value="<?php echo $projectID; ?>">
					<?php } ?>
			  		<input class="btn btn-sm btn-primary" type="submit" value="<?php echo ucfirst($method); ?> Project">
			  		<?php echo ($method === "edit") ? '<span class="delete-project-btn"><i class="fa fa-times"></i> Delete Project</span>' : '' ?>
			  	</form>

			</div>
		</div>
	</div>


	<div class="col col-md-7 text-right edit-existing-projects">
		<div class="showcase-box">
			<h4>Edit Existing Projects</h4><br>
			<?php
			/*
				This will loop through the projects, if they exist
			*/
				if(!empty($projects)){
					echo '<ul>';
					foreach($projects as $row){
						array_push($projectArray, $row['name']);
				?>
					<li>
						<a href="manage.php?method=edit&project=<?php echo $row['id']; ?>">
							<?php echo $row['name']; ?>
						</a>
					</li>
					<br>
				<?php
					} // end foreach
					echo '</ul>';
				} else { // end if data is empty, check to see if admin is empty
					echo '<p class="light-gray">No projects exist yet</p>';
				}
			?>

			<hr>

			<span class="showcase-tooltip add-edit-tooltip">
				<i class="fa fa-info-circle add-edit-tt projects-to-add-tt"></i>
				<span class="tooltip-text projects-to-add-tooltip-text">
					<i class="fa fa-times pull-right close-edit-tt"></i>
					These are directories found in the <code>sc_projects</code> folder
				</span>
			</span>

			<h4>Projects to Add</h4><br>

			<?php
				$dirs = array_filter(glob('sc_projects/*'), 'is_dir');
				$dirsSize = sizeof($dirs);
				$addedCount = 0;
				if(!empty($dirs)){
					foreach ($dirs as $dir) {
						$dir = str_replace("sc_projects/", "", $dir);
						if(in_array($dir, $projectArray)){
							$addedCount++;
							continue;
						}
						?>
							<a href="manage.php?method=add&name=<?php echo $dir;?>&directory=<?php echo $dir;?>">
								<?php echo $dir;?>
							</a>
							<br>
						<?php
					} // end foreach directory
				} else {
					echo '<p class="light-gray">No projects found</p>';
				}


				if($addedCount === $dirsSize){
					echo '<p class="light-gray">No other projects found</p>';
				}
			?>

		</div>
  	</div> <!-- end sidebar -->

 	<?php require_once('inc/footer.php'); ?>

  </div> <!-- end container -->

	<?php require_once('inc/login_box.php'); ?>

 </body>
</html>