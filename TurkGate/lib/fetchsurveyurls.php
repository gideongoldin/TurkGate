<?php

	require('databaseobject.php');
	require_once('../config.php');
	
	$databaseObject = new DatabaseObject();

	$databaseObject->getGroupURLs($_GET['group']);

	$databaseObject->close();

?>