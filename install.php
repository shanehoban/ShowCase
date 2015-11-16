<?php
  error_reporting(0);
  session_start();

  if(isset($_GET['status']) && !empty($_GET['status'])){
    $status = $_GET['status'];
  } else {
    $status = false;
  }

  require_once('inc/functions.php');
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
    <title>Install ShowCase</title>

    <?php require_once('inc/inc.php'); ?>

  </head>

 <body>

 <?php require_once('inc/msg.php'); ?>

  <div class="container">
  	<header>
		 <h2 class="lobster"> ShowCase </h2>
		 <span class="back-to-link">
		 	Installation
		 </span>
		<hr>
	</header>

	<?php

  if(!$status){
      if(isset($pdoError)){
        echo $pdoError;
      } else {
          echo '<p><span class="label label-success">_&gt;</span> Successfully connected to MySQL</p>';

          $query = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbName;");
          $query->bindParam(':dbName', $dbName);
          $query->execute();

          $dbName = $query->fetch()[0];

          if(!empty($dbName) && isset($dbName)){
            echo '<p><span class="label label-warning">_&gt;</span> Database <code>' . $dbName . '</code> already exists.</p>';
            echo '<hr>';
            echo '<p> <span class="btn btn-md btn-primary install-hidden-form-button">  Click here to continue with the installation <i class="fa fa-long-arrow-right"></i></span> </p>';
            ?>

               <form class="hidden hidden-install-form" method="POST" action="install.php?status=install">
                <input class="setup-hidden setup-details" value="<?php echo $dbName;?>" type="hidden" name="databaseName">
               </form>

            <?php
          } else { ?>

            <form method="POST" action="install.php?status=install">
              <p>
                <span class="label label-success">_&gt;</span> Please name your ShowCase Database: <input name="dbName" class="setup-input" type="text" placeholder="<?php echo $databaseDefaultName; ?>" maxlength="50">
                <input type="submit" class="btn btn-sm btn-primary setup-btn setup-next" value="Install">
              </p>
            </form>

          <?php }

      } // end if pdo error

    } // end if status

      // else get status
      else {

        if($status === "check"){
          echo '<p><span class="label label-success">_&gt;</span> Verifying Installation</p>';

            $pdo->prepare("use :dbName");
            $query->bindParam(':dbName', $dbName);
            $query->execute();

            $checkCount = 0;

            $query = $pdo->query("SHOW TABLES LIKE 'sc_admin'");
              if($query && $query->rowCount() > 0){
                 echo '<p><span class="label label-success">_&gt;</span> Found <code>sc_admin</code> table.</p>';
                 $checkCount++;
              }

            $query = $pdo->query("SHOW TABLES LIKE 'sc_projects'");
              if($query && $query->rowCount() > 0){
                echo '<p><span class="label label-success">_&gt;</span> Found <code>sc_projects</code> table.</p>';
                $checkCount++;
              }

            $query = $pdo->query("SHOW TABLES LIKE 'sc_sections'");
              if($query && $query->rowCount() > 0){
                echo '<p><span class="label label-success">_&gt;</span> Found <code>sc_sections</code> table.</p>';
                $checkCount++;
              }

          if($checkCount !== 3){
            echo '<p><span class="label label-danger">_&gt;</span> Cannot find ShowCase tables in database.</p>';
            echo '<p><span class="label label-warning">_&gt;</span> Please try again</p>';
            echo '<hr>';
            echo '<p> <a class="btn btn-md btn-primary" href="install.php">  Click here to try again <i class="fa fa-long-arrow-right"></i></a> </p>';
          } else{

            if(!file_exists('sc_projects')){
              mkdir('sc_projects', 0777, true);
              echo '<p><span class="label label-success">_&gt;</span> Created Directory: <code>sc_projects</code> - easiest thing to do is put your projects in there</p>';
            }

            echo '<p><span class="label label-success">_&gt;</span> Installation Successful</p>';
            echo '<hr>';
            echo '<p> <a class="btn btn-md btn-primary" href="index.php">  Click here to finish </a> </p>';

          }

        } else if($status === "install"){

          if($_SERVER['REQUEST_METHOD'] != 'POST'){
            header('Location: install.php?error=no');
            exit();
          }

          $dbName = (isset($_POST['dbName']) && !empty($_POST['dbName'])) ? $_POST['dbName'] : $databaseDefaultName;



          $query = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :databaseName");
          $query->bindParam(':databaseName', $dbName);
          $query->execute();
          $exists = $query->fetch()[0];

          if(isset($exists) && !empty($exists)){

            $errorCreatingDatabase = false;

            echo '<p><span class="label label-success">_&gt;</span> Located database <code>' . $dbName . '</code></p>';

          } else{

            echo '<p><span class="label label-success">_&gt;</span> Trying to create database <code>' . $dbName . '</code></p>';

            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName`");

            $query = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :databaseName;");
            $query->bindParam(':databaseName', $dbName);
            $query->execute();
            $row = $query->fetch()[0];

            if(isset($row) && !empty($row)){
              $errorCreatingDatabase = false;
              echo '<p><span class="label label-success">_&gt;</span> Created database <code>' . $dbName . '</code></p>';
            } else{
              $errorCreatingDatabase = true;
              echo '<p><span class="label label-danger">_&gt;</span> Could not create database <code>' . $dbName . '</code></p>';
            }

          }

          if(!$errorCreatingDatabase){

            // Update the config now

              $fname = "inc/config.php";
              $fhandle = fopen($fname,"r");
              $content = "";
              $replaced = false;

              while(!feof($fhandle)){
                $line = fgets($fhandle);
                  if(!$replaced && strpos($line, '$dbName') !== false){
                    $line = 'first$dbName' . "\n";
                    $replaced = true;
                  }
                $content = $content . $line;
              }
              fclose($fhandle);

              $content = str_replace('first$dbName', '$dbName = "' . $dbName .'";', $content);

              $fhandle = fopen($fname,"w");
              fwrite($fhandle,$content);
              fclose($fhandle);

            $pdo->prepare("use `:databaseName`");
            $query->bindParam(':databaseName', $dbName);
            $query->execute();

            $checkCount = 0;

            $query = $pdo->query("SHOW TABLES LIKE 'sc_admin';");

            if($query && $query->rowCount() > 0){
               $checkCount++;
            }

            $query = $pdo->query("SHOW TABLES LIKE 'sc_projects';");

            if($query && $query->rowCount() > 0){
              $checkCount++;
            }


            if($checkCount === 0){

               echo '<p><span class="label label-success">_&gt;</span> We could not find any other related tables. It should be safe to proceed.</p>';
               echo '<hr>';
               echo '<a class="btn btn-primary" href="sc_install/install_database.php">Click here to proceed with the installation <i class="fa fa-long-arrow-right"></i></a>';

            } else if($checkCount === 1){ ?>

                <div>
                  <p>
                    <span class="label label-danger">_&gt;</span> It looks like you may have an old version of ShowCase installed, but something doesn't look right.
                  </p>

                  <p>
                    <br>
                    <a class="btn btn-primary" href="sc_install/install_database.php">
                      Click here to do a clean install of ShowCase <i class="fa fa-long-arrow-right"></i>
                    </a>
                    <br>
                    <small style="display: inline-block; margin-top: 10px;">Warning - you will lose all data from any previous installation of ShowCase</small>
                  </p>
                </div>

            <?php } elseif($checkCount === 2){ ?>


                  <p>
                    <span class="label label-danger">_&gt;</span> It looks like you may have ShowCase installed already, and you will lose your data if your proceed.
                  </p>


                  <br>
                    <p>
                      <a class="btn btn-lg btn-primary" href="index.php">
                        <i class="fa fa-hand-o-left"></i>  Click here to go back to ShowCase
                        </a>
                    </p>


                  <hr>

                  <p>
                    <br>
                    <a class="btn btn-primary" href="sc_install/install_database.php">
                      Click here to do a clean install of ShowCase <i class="fa fa-long-arrow-right"></i>
                    </a>
                    <br>
                    <small style="display: inline-block; margin-top: 10px;">Warning - you will lose all data from any previous installation of ShowCase</small>
                  </p>


            <?php }



          } else { ?>
             <p><span class="label label-danger">_&gt;</span> Could not create or locate database: <code><?php echo $dbName; ?></code></p>
             <hr>

                  <p><span class="label label-default">_&gt;</span> This could be because of:
                    <ul>
                      <li>Lack of Database Permissions</li>
                      <li>Your server has restrictions on databases (e.g. CPanel)</li>
                    </ul>
                  </p>

            <p><span class="label label-default">_&gt;</span> You will have to either upgrade your database user permissions or create the database manually in order to continue</p>
            <p><span class="label label-default">_&gt;</span> Try to create the database manually first, and then click the button below. </p>
            <p><span class="label label-default">_&gt;</span> Be sure to use the same database name that you used when manually creating the database</p>
            <hr>

              <p> <a class="btn btn-md btn-primary" href="install.php">  Click here to try again </a> </p>
          <?php }







        } // end if status = install


      } //end else get status ?>



  </div>
 </body>
</html>