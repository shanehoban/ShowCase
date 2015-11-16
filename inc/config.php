<?php

/*
	Host is location of your database
	Username is username for database
	Password is the password for your database
*/
	$host = '127.0.0.1';
	$username = 'root';
	$password = '';


/*  DO NOT EDIT BELOW THIS LINE */

$dbName = "showcase";

try {
	/* install may be done, but what if db dropped? */
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $query = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :databaseName");
    $query->bindParam(':databaseName', $dbName);
    $query->execute();
    $dbName = $query->fetch()[0];

    if(!empty($dbName) && isset($dbName)){
    	$pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    }

} catch (PDOException $e) {
   $pdoError = "<div class='alert alert-danger'>Error connecting to Database.</div> <p><span class='label label-danger'>_&gt;</span> Please check your database host, username, and password in the configuration file. This file is typically located in: <code>'inc/config.php'</code></p>";
}

if(!isset($pdoError) && empty($pdoError)){
	$query = $pdo->prepare('SELECT * FROM sc_admin LIMIT 1');
	$query->execute();
	$admin = $query->fetch();
}