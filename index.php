<?php
	error_reporting(0);
	session_start();

	if(isset($_SESSION['username']) && !empty($_SESSION['username'])){
		$session = $_SESSION['username'];
	}

	require_once('inc/functions.php');
	require_once('inc/config.php');

	if (!tablesExist($pdo) || !file_exists('sc_projects') || empty($dbName)) {
	    header('Location: install.php');
	    exit();
	}

	if(!empty($admin)){
		$projects = getProjects($pdo);
	}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo (!empty($admin)) ? $admin['username'] . "'s Projects | ShowCase" : "ShowCase"; ?> </title>

    <?php require_once('inc/inc.php'); ?>

  </head>

 <body>

 <?php require_once('inc/msg.php'); ?>

  <div class="container">
  	<header>
		 <h2> <?php echo (!empty($admin)) ? $admin['page_name'] : "<span class='lobster'>ShowCase</span>"; ?></h2>
		 	<nav>
		 		<?php if(!empty($admin)){
		 			if(isset($session) && !empty($session)){ ?>
		 				<a href="manage.php?method=add">manage projects</a>
		 				<a href="settings.php">settings</a>
		 				<a href="logout.php">logout</a>
		 			<?php } else { ?>
		 			<a class="login-btn" href="#">login</a>
		 			<?php } ?>
		 		<?php } ?>
		 	</nav>

		 <span class="back-to-link">
		 	<?php if(!empty($admin)){
		 		if($admin['home_url'] !== "-"){
		 		?>
		 		<a href="<?php echo (strpos($admin['home_url'], "http://") === false && strpos($admin['home_url'], "https://") === false) ? "http://" . $admin['home_url'] : $admin['home_url'];?>"><i class="fa fa-long-arrow-left"></i> back to <?php echo $admin['home_url'];?></a>
		 	<?php } } else {
		 		echo 'ShowCase Setup';
		 	} ?>
		 </span>

		<hr>
	</header>

		<?php
		/*
			This will loop through the projects, if they exist
		*/
			$sectionsAdded = [];
			$currentSection = 0;
			$firstSection = true;

			if(!empty($projects)){
				foreach($projects as $row){
					if(isset($row['section_id']) && !empty($row['section_id'])){
						if($currentSection === 0 || $currentSection  !== $row['section_id']){
							$currentSection = $row['section_id'];
							if(!$firstSection){
								echo '<div class="col col-lg-12"><hr class="section-split"></div>';
							}
							$firstSection = false;
						}
						if(!in_array($row['section_id'], $sectionsAdded)){
							array_push($sectionsAdded, $row['section_id']);
							$sectionHeader = getSectionHeader($row['section_id'], $pdo);
							echo '<div class="col col-lg-12"><h4 class="section-h4">' . $sectionHeader . '</h4></div>';
						}
					} else if($currentSection !==0){
						$currentSection = 0;
						echo '<div class="col col-lg-12"><hr class="section-split"></div>';
						echo '<div class="col col-lg-12"><h4 class="section-h4">Other</h4></div>';
					}// end if is part of a section
			?>
					<div class="col col-lg-4">
						<div class="project">
							<a class="project-link" href="<?php echo (strpos($row['directory'], "http://") === false && strpos($row['directory'], "https://") === false) ? 'sc_projects/' . $row['directory'] : $row['directory']?>">
							<?php if(isset($row['img']) && !empty($row['img'])){ ?>
								<img src="<?php echo $row['img']; ?>" />
							<?php } ?>

								<span class="project-link-text <?php echo (isset($row['img']) && !empty($row['img'])) ? "project-link-text-with-image" : "" ;?>">
									<?php echo $row['name']; ?>
								</span>
							</a>
							<?php if(isset($row['description']) && !empty($row['description'])){ ?>
								<span class="tool-tip">
									<?php echo $row['description']; ?>
								</span>
							<?php } ?>
							<?php if(isset($session) && !empty($session)){ ?>
								<a class="project-edit-link" href="manage.php?method=edit&project=<?php echo $row['id'];?>" title="Edit <?php echo $row['name'];?>">
									<i class="fa fa-pencil"></i>
								</a>
							<?php } ?>
						</div>
					</div>
			<?php
				} // end foreach
				// end if data is empty, check to see if admin is empty

			} else if(empty($admin)){
				if(empty($_POST['setupDetails']) && !isset($_POST['setupDetails'])){
				// First time running ShowCase
				$settingUp = true;
			?>
				<div class="setup-block">
					<p><span class="label label-success">_&gt;</span> Welcome to ShowCase.</p>
					<p><span class="label label-success">_&gt;</span> Lets get you going...</p>

					<p><span class="label label-success">_&gt;</span> Please enter a username: <span class="setup-result" data-setup="0"></span><input autofocus class="setup-input" type="text" maxlength="100" data-setup="0"> <span class="btn btn-sm btn-primary setup-btn setup-next" data-setup="0">Next</span></p>

					<p class="setup-show show-1"><span class="label label-success">_&gt;</span> Please enter a password: <span class="setup-result setup-password" data-setup="1"></span><input class="setup-input" type="password" maxlength="100" data-setup="1"> <span class="btn btn-sm btn-primary setup-btn setup-next" data-setup="1">Next</span></p>

					<p class="setup-show show-2"><span class="label label-success">_&gt;</span> Please enter a title for your page: <span class="setup-result" data-setup="2"></span><input class="setup-input" type="text" value="Projects" data-default="Projects" maxlength="150" data-setup="2"> <span class="btn btn-sm btn-primary setup-btn setup-next" data-setup="2">Next</span> <span class="btn btn-sm btn-default setup-btn setup-skip" data-setup="2">Skip</span></p>

					<p class="setup-show show-3"><span class="label label-success">_&gt;</span> Please enter a URL for your homepage: <span class="setup-result" data-setup="3"></span><input class="setup-input" type="url" placeholder="http://www.example.com" data-default="-" maxlength="150" data-setup="3"> <span class="btn btn-sm btn-primary setup-btn setup-next" data-setup="3">Next</span> <span class="btn btn-sm btn-default setup-btn setup-skip" data-setup="3">Skip</span></p>

					<p class="setup-show show-4"><span class="label label-success">_&gt;</span> Please enter your twitter handle: <span class="setup-result" data-setup="4"></span><input class="setup-input" type="text" placeholder="@" data-default="-" maxlength="150" data-setup="4"> <span class="btn btn-sm btn-primary setup-btn setup-next" data-setup="4">Next</span> <span class="btn btn-sm btn-default setup-btn setup-skip" data-setup="4">Skip</span></p>

					<hr>

					<div class="setup-skip-all-section">
						<span class="btn btn-sm btn-default setup-bottom-btn setup-restart">Start Over</span>
						<span class="btn btn-sm btn-default setup-bottom-btn setup-skip-all">Skip the rest</span>
						<span class="btn btn-sm btn-success setup-bottom-btn setup-finish">Finish</span>
					</div>

				<!-- run install JS -->
				<script>initSetup();</script>

				<form class="hidden hidden-setup-form" method="POST" action="index.php">
					<input class="setup-hidden setup-details" type="hidden" name="setupDetails">
				</form>
		<?php
			} else {
				require_once('inc/password.php');

					// get POST data
					$setupDetails = json_decode($_POST['setupDetails']);

					$username = $setupDetails[0];	// username
					$password = $setupDetails[1];	// password
					$salt = base64_encode(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
					$pageName = $setupDetails[2];	// page title
					$homeURL = $setupDetails[3];	// url
					$twitter = $setupDetails[4];	// twitter

					$options = array(
					    'cost' => 8,
					    'salt' => $salt
						);

					$hashedpw = password_hash($password, PASSWORD_BCRYPT, $options);

					$query = $pdo->prepare('INSERT INTO sc_admin VALUES(NULL, :username, :password, :salt, :page_name, :home_url, :twitter)');
					$query->bindParam(':username', $username);
					$query->bindParam(':password', $hashedpw);
					$query->bindParam(':salt', $salt);
					$query->bindParam(':page_name', $pageName);
					$query->bindParam(':home_url', $homeURL);
					$query->bindParam(':twitter', $twitter);
					$query->execute();

					$_SESSION['username'] = $username;

					header('Location: index.php');
					exit();

				}	// end if POST data exists
			}	// end if admin is empty
			else{
				// admin is not empty, and no data exists (no projects in the thing)
				if(isset($session) && !empty($session)){
					echo '<p><span class="label label-success">_&gt;</span> You have no projects in your ShowCase.</a></p>';
					echo '<p><span class="label label-success">_&gt;</span> Click <a href="manage.php?method=add">here</a> to add a project</p>';
				} else {
					echo '<p><span class="label label-success">_&gt;</span> There are no projects on display</p>';

					echo '<p><span class="label label-success">_&gt;</span> To add a project, you must <a class="login-btn" href="#">login</a></p>';
				}
			}

		?>


		<?php for($i=0;$i<10;$i++){
				//echo '<div class="col col-lg-4"> <div class="project"> <a href="test"><img class="project-img"> TestProject </a> <span class="tool-tip"> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. </span> </div> </div> ';
		} ?>


	<?php (!isset($settingUp) || empty($settingUp) || !$settingUp) ? require_once('inc/footer.php') : ''; ?>

  </div> <!-- end container -->

	<?php require_once('inc/login_box.php'); ?>

 </body>
</html>