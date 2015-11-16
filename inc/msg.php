<?php
	error_reporting(0);
	session_start();

	/*
		- Either a success or an error can occur - but not both
		- A warning can occur regardless, and/or additionally
		- Errors get preference over success messages in the event where both an error and a success msg is returned (shouldn't happen)
	*/

	$msgText; // to hold one of the two below

	if(isset($_GET['error']) && !empty($_GET['error'])){
		$error = $_GET['error'];
		switch ($error) {
		    case "login":
				$msgText = "You must be logged in to view that.";
		        break;
		    case "no":
		        $msgText = "You can't do that.";
		        break;
		    case "something_missing":
		        $msgText = "Something was missing.";
		        break;
		    case "cannot_delete_nothing":
		        $msgText = "There was nothing passed to delete.";
		        break;
		    case "method_error":
		        $msgText = "That is not supported.";
		        break;
		    case "wrong":
		        $msgText = "Wrong username and/or password combination.";
		        break;
		    default:
		    	$msgText = "Something went wrong. Not sure what it was though...";
		} ?>

		<div class="alert-wrapper">
			<div class="alert alert-danger" role="alert">
				<span class="label label-danger">_&gt;</span> <?php echo $msgText; ?> <i class="fa fa-times pull-right"></i>
			</div>
		</div>

	<?php } else if(isset($_GET['success']) && !empty($_GET['success'])){
		$success = $_GET['success'];
			switch ($success) {
		    	case "logged_out":
					$msgText = "You have been successfully logged out.";
		        	break;
		        case "project_updated":
					$msgText = "Project successfully updated.";
		        	break;
		       	case "project_added":
					$msgText = "Project successfully added.";
		        	break;
		        case "project_deleted":
					$msgText = "Project successfully deleted.";
		        	break;
		        case "section_deleted":
					$msgText = "Section successfully deleted.";
		        	break;
		        case "projects_swapped":
					$msgText = "Projects swapped";
		        	break;
		        case "sections_swapped":
					$msgText = "Sections swapped";
		        	break;
		    }
		?>
		<div class="alert-wrapper">
			<div class="alert alert-success" role="alert">
				<span class="label label-success">_&gt;</span> <?php echo $msgText; ?> <i class="fa fa-times pull-right"></i>
			</div>
		</div>


	<?php } // end if success or error

		if(isset($_GET['warning']) && !empty($_GET['warning'])){
			$warning = $_GET['warning'];
				switch ($warning) {
		    	case "directory_doesnt_exist":
					$msgText = "The directory specified can't be found in the <code>sc_projects</code> folder.";
		        	break;
		    }
			?>
				<div class="alert-wrapper">
					<div class="alert alert-warning <?php echo (isset($success) || isset($warning)) ? 'warning-alert-move' : ''; ?>" role="alert"><span class="label label-warning">_&gt;</span> <?php echo $msgText; ?> <i class="fa fa-times pull-right"></i></div>
				</div>
	<?php } ?>