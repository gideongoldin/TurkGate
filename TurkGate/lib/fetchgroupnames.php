<?php

	require('databaseobject.php');
	require_once('../config.php');

	$databaseObject = new DatabaseObject();

	$databaseObject->getGroupNames($_GET['term']);

	$databaseObject->close();

?>