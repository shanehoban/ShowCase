<?php
	error_reporting(0);
	session_start();

	if(isset($_SESSION['username']) && !empty($_SESSION['username'])){
		$session = $_SESSION['username'];
	}

	require_once('inc/config.php');

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Info | ShowCase</title>

    <?php require_once('inc/inc.php'); ?>

  </head>

 <body>

 <?php require_once('inc/msg.php'); ?>

  <div class="container">
  	<header>
		 <h2 class="lobster">ShowCase</h2>
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

		 		<a href="index.php"><i class="fa fa-long-arrow-left"></i> back to ShowCase</a>
		 </span>

		<hr>
	</header>

	<div class="col col-md-7">
	 <div class="showcase-box add-edit-box info-box">

			<h3>What is ShowCase?</h3>

			<p>ShowCase is a free (as in beer), open source project management tool for displaying your projects effortlessly on the web.</p>
			<p>Here's what you need to use ShowCase:</p>
				<ul>
					<li>Apache (web server)</li>
					<li>PHP</li>
					<li>MySQL</li>
				</ul>
			<p>For now, in order to use ShowCase, you must also self-host it. There may be a web based version that I set up in the future that will be available publicly.</p>

		<hr>

			<h4>Installation</h4>

				<p>Installation in theory should be quite simple (as usual), but in practice likely won't be (as usual).<p>
				<p>I've tried to make it as easy as possible to set up ShowCase on your own server. </p>
				<p>The following steps should do it:</p>

					<ol>
						<li><a href="https://github.com/shanehoban/ShowCase/tree/master" target="_blank">Download the latest version of ShowCase (GitHub)</a></li>
						<li>Update <code>inc/config.php</code> with your MySQL connection details</li>
						<li>Now put all this stuff in a new folder e.g. <code>/showcase/</code> on your server</li>
						<li>In your browser, simply navigate to that new folder<br> e.g. <code>http://www.yourdomain.com/showcase/</code></li>
						<li>Follow the steps on screen</li>
					</ol>

		<hr>

			<h4>Updating ShowCase</h4>

				<p>Just do a <code>git pull origin master</code> and push to your server - ya dingus.</p>
				<p><small>Psst... Don't forget to ensure your <code>config.php</code> is up to date after a pull!</small></p>

		<hr>

			<h4>Licence (MIT)</h4>

				<div class="info-licence">
					<span class="info-show-licence fake-link">Show Licence &downarrow;</span>
					<span class="info-licence-text">
						<?php
							echo nl2br(file_get_contents('licence.txt'));
						?>
					</span>
				</div>

		<hr>

			<p> The current version of ShowCase is &alpha; 0.1.1 - November 12th 2015</p>
			<p>ShowCase is, as everything else is these days, created with <i class="fa fa-heart"></i>, but in Ireland</p>


	 </div>
	</div>


	<div class="col col-md-5 text-right edit-existing-projects">
		<div class="showcase-box">
			<h4>Contact</h4>
			<br>

			<a href="http://www.twitter.com/shanehoban" target="_blank"><i class="fa fa-twitter"></i> @shanehoban</a><br>
			<a href="mailto:shanehoban@gmail.com?subject=ShowCase"><i class="fa fa-envelope-o"></i> Shane - Email</a><br>



			<hr>

			<h4>With Thanks To</h4>
			<br>

			<a href="http://fontawesome.io" target="_blank">Font Awesome by Dave Gandy</a><br>
			<a href="http://getbootstrap.com" target="_blank">Twitter Bootstrap</a><br>
			<a href="https://www.google.com/fonts" target="_blank">Google Web Fonts</a>


		</div>
	</div>

  	<?php require_once('inc/footer.php'); ?>

  </div> <!-- end container -->

	<?php require_once('inc/login_box.php'); ?>

 </body>
</html>