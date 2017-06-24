<?php

	include_once '../includes/db_connect.php';
	include_once('../includes/session.php');

	Session::init();
	Session::destroy();
	echo "OK";
	exit();