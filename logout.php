<?php

	session_start();
	session_destroy();
    unset($_SESSION);
	setcookie("PHPSESSID", "", 0);
	header("Location: index.php");
	die();