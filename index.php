<?php

	session_start();
	include("db.php");
	include("functions.php");

	$page = "home";
	if (!empty($_GET['page'])){
		$page = $_GET['page'];
	}

	$path = "pages/".$page.".php";
	if (file_exists($path)){
		include($path);
	}
	else {
		die("404");
	}