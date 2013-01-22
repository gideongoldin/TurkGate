<?php

	require('databaseobject.php');

	$databaseObject = new DatabaseObject();

	$databaseObject->getGroupNames($_GET['term']);

	$databaseObject->close();

?>