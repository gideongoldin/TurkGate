<!DOCTYPE html>
	<html>
	<head>
	<title>TurkGate</title>
</head>

<?php
// Commonly used variables
$footer = "<h5>&copy; 2012 <a href='https://github.com/gideongoldin/TurkGate' target='blank'>TurkGate</a></h5>";

// Check for form submissions
if(isset($_POST['submit'])) {
	// Database name is hard-coded
	$database_name = "TurkGate";

	// Gather the user-submitted database credentials
	$database_host = isset($_POST['database_host']) ? $_POST['database_host'] : "";
	$database_username = isset($_POST['database_username']) ? $_POST['database_username'] : "";
	$database_password = isset($_POST['database_password']) ? $_POST['database_password'] : "";

	// Validate entries
	if (empty($database_host) || empty($database_username) || empty($database_username) || empty($database_password)) {
		// For HTMLn where n < 5
		echo "<h1>TurkGate</h1><h2>Installation</h2><p>Error: All fields are required. Please <a href='javascript:history.back()'>go back</a> and re-submit.</p>" . $footer;
	} else {
		// Connect to the database
		$connection = mysql_connect($database_host, $database_username, $database_password) or die("<h1>TurkGate</h1><h2>Installation</h2><p>Error connecting to database. " . mysql_error() . ". See your system administrator.</p><p><a href='javascript:history.back()'>Go back</a></p>" . $footer);

		// Create the TurkGate configuration file
		$configFileName = "turkGateConfig.php";
		$configFileHandle = fopen($configFileName, 'w') or die("<h1>TurkGate</h1><h2>Installation</h2><p>Error creating config file. " . mysql_error() . ".</p>" . $footer);

		// Write (to) the TurkGate configuration file
		$configFileString = "<?php
		define('DATABASE_HOST', '".$database_host."');
		define('DATABASE_NAME', '".$database_name."');
		define('DATABASE_USERNAME', '".$database_username."');
		define('DATABASE_PASSWORD', '".$database_password."');
		?>";
		fwrite($configFileHandle, $configFileString);
		fclose($configFileHandle);

		// Create the TurkGate database		  
		if(mysql_query("CREATE DATABASE IF NOT EXISTS " . $database_name, $connection)) {
			// Select the database
			mysql_select_db($database_name, $connection) or die("<p>Error selecting database: " . mysql_error() . "</p><p><a href='javascript:history.back()'>Go back</a></p>" . $footer);

			// Create the table
			$sql = "CREATE TABLE IF NOT EXISTS SurveyRequest 
			(
				requestID INT NOT NULL AUTO_INCREMENT,
				PRIMARY KEY(requestID),
				workerID VARCHAR(256),
				URL VARCHAR(256),
				groupName VARCHAR(256),
				time DATETIME
			)";

			mysql_query($sql, $connection) or die("<p>Error creating table: " . mysql_error() . "</p><p><a href='javascript:history.back()'>Go back</a></p>" . $footer);
			mysql_close($connection);

			// Installation complete
			echo "<h1>TurkGate</h1><h2>Installation</h2><p>TurkGate Installation successful!</p>";
			echo "<p>You may now close this window.</p>" . $footer;
			exit();
		} else {
			echo "<p>Error creating database. (Might it already exist?): " . mysql_error();
			echo "<p>A configuration file has been created in the TurkGate server directory.</p>" . $footer;
			exit();
		}
	}
	exit();
}
?>

<body>
	<h1>TurkGate</h1>
	<h2>Installation</h2>

	<p>Please enter your database (e.g., MySQL) login information below.</p>
	<p>A new database called "TurkGate" will be created automatically.</p>

	<form method="post" action="install.php">

		<p>
		<label for="database_host">Database Host:</label>
	<input type="text" name="database_host" value="localhost" required="required"> 
		<i>(Note: this value is usually 'localhost')</i>
	</p>

	<p>
		<label for="database_username">Database Username:</label>
	<input type="text" name="database_username" autofocus="autofocus" required="required">
		</p>

	<p>
		<label for="database_password">Database Password:</label>
	<input type="password" name="database_password" required="required">
		</p>

	<p>
		<input type="submit" name="submit" value="Submit">
		</p>

	</form>

	<?php echo $footer ?>

</body>
</html>