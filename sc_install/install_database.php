<?php
	error_reporting(0);
	session_start();

	require_once('../inc/config.php');

	$pdo->prepare("use :databaseName");
    $query->bindParam(':databaseName', $dbName);
    $query->execute();

	$pdo->query("CREATE TABLE IF NOT EXISTS `sc_admin` (`id` int(11) NOT NULL, `username` varchar(100) NOT NULL, `password` varchar(100) NOT NULL, `salt` varchar(100) NOT NULL, `page_name` varchar(150) NOT NULL, `home_url` varchar(300) NOT NULL,`twitter` varchar(50) NOT NULL)");

	$pdo->query("CREATE TABLE IF NOT EXISTS `sc_projects` (`id` int(11) NOT NULL, `name` varchar(200) NOT NULL, `directory` varchar(250) NOT NULL, `img` varchar(250) DEFAULT NULL, `description` varchar(1000) DEFAULT NULL, `section_id` int(6) DEFAULT NULL, `pos` int(11) NOT NULL)");

	$pdo->query("CREATE TABLE IF NOT EXISTS `sc_sections` (`id` int(11) NOT NULL, `section_name` varchar(150) NOT NULL, `section_pos` int(6) NOT NULL)");

	$pdo->query("ALTER TABLE `sc_admin` ADD PRIMARY KEY (`id`);");
	$pdo->query("ALTER TABLE `sc_projects` ADD PRIMARY KEY (`id`);");
	$pdo->query("ALTER TABLE `sc_sections` ADD PRIMARY KEY (`id`);");

	$pdo->query("ALTER TABLE `sc_admin` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
	$pdo->query("ALTER TABLE `sc_projects` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
	$pdo->query("ALTER TABLE `sc_sections` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

	header('Location: ../install.php?status=check');
	exit();


/*
	$pdo->query("");
*/