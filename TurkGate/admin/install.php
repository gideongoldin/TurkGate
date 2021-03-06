<!--
Copyright 2012 Adam Darlow and Gideon Goldin

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
-->
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>TurkGate - Installation</title>
	<meta name="description" content="TurkGate installation and uninstallation page.">
	<meta name="author" content="">
	
	<!-- Add the imports -->
	<?php 
    	$basePath = '../';
		require_once($basePath . 'includes/imports.php'); 
		
		// If you add or change any dependencies, update them here
		$modules = array('json', 'mcrypt', 'mysql', 'pcre');
		$missingModules = '';
		foreach ($modules as $module) {
			extension_loaded($module) or $missingModules .= " $module";
		}
	?>
	
</head>

<body id="install">
	
  <div class="container"> <!-- Container -->
	
	<!-- Add the header -->
	<?php 
    	$title = 'Installation';
	    require_once($basePath . 'includes/header.php'); 
	?>

	<?php
	// Check for form submissions
	if (isset($_POST['installSubmit']) || isset($_POST['uninstallSubmit'])) {
		
		// Gather the user-submitted database credentials
		$databaseHost = isset($_POST['databaseHost']) ? $_POST['databaseHost'] : "";
		$databaseName = isset($_POST['databaseName']) ? $_POST['databaseName'] : "";
		$databaseUsername = isset($_POST['databaseUsername']) ? $_POST['databaseUsername'] : "";
		$databasePassword = isset($_POST['databasePassword']) ? $_POST['databasePassword'] : "";
		$baseURL = isset($_POST['turkGateURL']) ? $_POST['turkGateURL'] : "";
		
		echo '<div class="sixteen columns clearfix" style="border-top: 1px solid #ccc; padding-top:10px;">';
		
		// Validate entries that are relevant to both install and uninstall forms
		if (empty($databaseHost) || empty($databaseName) || empty($databaseUsername) || empty($databasePassword)) {
			// Simple form validation for HTMLn where n < 5
			echo '<h1>TurkGate</h1><h2>Installation</h2><p>Error: All fields are required. <p><a href="index.php">Admin home</a></p></div>';
			require_once($basePath . 'includes/footer.php');
			die();
		} else {
			// Connect to the database
			$connection = mysql_connect($databaseHost, $databaseUsername, $databasePassword);
			if(!$connection) {
				echo '<p>Unsuccessful! Error connecting to database. ' . mysql_error() . '. See your system administrator.</p><p><a href="index.php">Admin home</a></p></div>';
				require_once($basePath . 'includes/footer.php');
				die();
			}

			// Select the database
			$db_selected = mysql_select_db($databaseName, $connection);
			if(!$db_selected) {
				echo '<p>Unsuccessful! Error selecting database: ' . mysql_error() . '. See your system administrator.</p><p><a href="index.php">Admin home</a></p></div>';
				require_once($basePath . 'includes/footer.php');
				die();
			}
			
			// Check if the uninstall (vs. install) form was submitted
			if(isset($_POST['uninstallSubmit'])) {
										
				// Remove the table 'SurveyRequest'
				$query = "DROP TABLE $databaseName.SurveyRequest";
				$result = mysql_query($query, $connection);
				if(!$result) {
					echo '<p>TurkGate could not find the SurveyRequest table. Might it have already been removed?</p>';
				} else {
					echo '<p>TurkGate removed the SurveyRequest table!</p>';
				}

				// Close the database connection
				mysql_close($connection);

				// Remove the TurkGate configuration file
				$configFileName = "../config.php";
				$result = unlink($configFileName);
				if(!$result) {
					echo '<p>TurkGate did not remove the file turkGateConfig.php. Might it have already been removed?</p><p><a href="index.php">Admin home</a></p>';
				} else {
					echo '<p>TurkGate removed the file turkGateConfig.php!</p><p><a href="index.php">Admin home</a></p>';
				}
				
				echo '</div>';
				
			} else {
				// The install (vs. uninstall) form was submitted
				
				// Check if the TurkGate URL field is empty or not
				if(!empty($baseURL)) {
					
						// Create the TurkGate configuration file
						$configFileName = "../config.php";
						$configFileHandle = fopen($configFileName, 'w') or die('<h1>TurkGate</h1><h2>Installation</h2><p>Error creating config file. ' . mysql_error() . '.</p><p><a href="index.php">Admin home</a></p>');

						// Generate a random encryption key
						$key = sha1(microtime(true) . mt_rand(10000,90000));

						// Write (to) the TurkGate configuration file
						$configFileString = "<?php
							define('DATABASE_HOST', '" . $databaseHost . "');
							define('DATABASE_NAME', '" . $databaseName . "');
							define('DATABASE_USERNAME', '" . $databaseUsername . "');
							define('DATABASE_PASSWORD', '" . $databasePassword . "');
							define('BASE_URL', '" . $baseURL . "');
							define('KEY', '" . $key . "');
						?>";

						fwrite($configFileHandle, $configFileString);
						fclose($configFileHandle);

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

						mysql_query($sql, $connection) or die('<p>Error creating table: ' . mysql_error() . '</p><p><a href="index.php">Admin home</a></p>');
						mysql_close($connection);

						// Installation is now complete					
						echo '<p>TurkGate Installation successful!</p>';
						echo '<p><a href="index.php">Admin home</a></p>';
						echo '</div>';
				} else {
					// Simple form validation for HTMLn where n < 5
					echo '<h1>TurkGate</h1><h2>Installation</h2><p>Error: All fields are required.</p><p><a href="index.php">Admin home</a></p>';
				}
			}
		}
		require_once($basePath . 'includes/footer.php');		
		exit();
	}?>
	
	<div class="sixteen columns clearfix" style="border-top: 1px solid #ccc; padding-top:10px;"> <!-- sixteen columns clearfix -->
		<div><!-- Tabs -->
			<!-- Tab headers -->
			<ul class="tabs">
				<li id="installTab" rel="tab1">
					Install
				</li>
				<li id="uninstallTab" rel="tab2" style="color:darkred;">
					Uninstall
				</li>
			</ul>
			
			<div class="tab_container">
				<?php
					$disabled = '';
					if ($missingModules) {
						$disabled = 'disabled';
					}
				?>
				<div id="tab1" class="tab_content install-tab <?php echo $disabled; ?>">
					<?php
						if ($missingModules) {
							echo "<h3 class='warning'>Install the following PHP modules before installing TurkGate:</h3><h3 class='modules'>$missingModules</h3>";
						}
					?>
					<p>To install, first enter the URL of your TurkGate folder.</p>
					<form method="post" action="install.php">
						<p>
							<label for="turkGateURL">TurkGate URL:</label>
							<input type="text" class="remove-bottom" name="turkGateURL" id="turkGateURL" required="required" <?php echo $disabled; ?> >
							<span class="comment">(The URL of the directory you pasted TurkGate into. E.g., http://yourdomain.edu/TurkGate)</span>
						</p>
						<p>
							TurkGate needs to store information in a database (e.g., MySQL). Please enter the login information below.
							A table called <span style="font-style: italic">SurveyRequest</span> will be added to your database.
						</p>
			            <p>
							<label for="databaseHost">Database Host:</label>
							<input type="text" class="remove-bottom" name="databaseHost" value="localhost" required="required" <?php echo $disabled; ?> >
							<span class="comment">(This value is usually 'localhost')</span>
						</p>
						<p>
							<label for="databaseName">Database Name:</label>
							<input type="text" class="remove-bottom" name="databaseName" required="required" autofocus="autofocus" <?php echo $disabled; ?> >
							<span class="comment">(E.g., 'TurkGate')</span>
						</p>
						<p>
							<label for="databaseUsername">Database Username:</label>
							<input type="text" name="databaseUsername" required="required" <?php echo $disabled; ?> >
						</p>
						<p>
							<label for="databasePassword">Database Password:</label>
							<input type="password" name="databasePassword" required="required" <?php echo $disabled; ?> >
						</p>
						<p>
							<input type="submit" name="installSubmit" value="Install TurkGate" <?php echo $disabled; ?> >
						</p>
					</form>
				</div><!-- Tab 1 -->
				
				<div id="tab2" class="tab_content danger uninstall-tab">
					<p>To uninstall please enter your database (e.g., MySQL) login information below.
					</p>
					<p>
						Uninstalling TurkGate will remove all TurkGate-generated files and database tables. All of the access records for your groups will be lost. It will not remove TurkGate source files.
					</p>

					<?php 
						// Auto-fill uninstall form
						$installed = @include('../config.php');
						$databaseHost = "localhost";
						$databaseName = "";
						$databaseUsername = "";

						if($installed) {
							$databaseHost = constant('DATABASE_HOST');
							$databaseName = constant('DATABASE_NAME');
							$databaseUsername = constant('DATABASE_USERNAME');
						}
					?>

					<form method="post" action="install.php" onsubmit="if(!confirm('Are you sure you want to uninstall TurkGate?')) { return false; }">
						<p>
							<label for="databaseHost">Database Host:</label>
							<input type="text" class="remove-bottom" name="databaseHost" value="<?php echo $databaseHost; ?>" required="required">
							<span class="comment">(This value is usually 'localhost')</span>
						</p>
						<p>
							<label for="databaseName">Database Name:</label>
							<input type="text" class="remove-bottom" name="databaseName" value="<?php echo $databaseName; ?>" required="required" autofocus="autofocus">
							<span class="comment">(E.g., 'TurkGate')</span>
						</p>
						<p>
							<label for="databaseUsername">Database Username:</label>
							<input type="text" name="databaseUsername" value="<?php echo $databaseUsername; ?>" required="required">
						</p>
						<p>
							<label for="databasePassword">Database Password:</label>
							<input type="password" name="databasePassword" required="required">
						</p>
						<p>
							<input type="submit" name="uninstallSubmit" value="Uninstall TurkGate">
						</p>
					</form>
				</div> <!-- Tab 2 -->
			</div> <!-- Tab Container -->
		</div> <!-- Tabs -->
	</div> <!-- sixteen columns clearfix -->

    
<!-- Import the footer -->
<?php require_once($basePath . 'includes/footer.php'); ?>

  </div> <!-- Container -->


<!-- Custom jQuery actions -->
	<script type="text/javascript">
	
		function chooseTab(tabElement) {
				$("ul.tabs li").removeClass("active");
				tabElement.addClass("active");
				$(".tab_content").hide();
				var activeTab = tabElement.attr("rel"); 
				$("#"+activeTab).show(); 
		}
		
		$(document).ready(function() {
			var tabId = window.location.hash;
			
			switch (tabId) {
				case "#install":
					chooseTab($("#installTab"));
					break;
				case "#uninstall":
					chooseTab($("#uninstallTab"));
					break;
				default:
					chooseTab($("ul.tabs li:first"));
			}
	
			$("ul.tabs li").click(function() { chooseTab($(this)); });
			
			// Animate textarea if exists
			if($('#generatedContent').length > 0) {
				$('#generatedContent').slideDown();
			}
		});
	</script> 
	
			
	<script src="../lib/fixhttp.lib.js"></script>
	<script type="text/javascript">
	  $(document).ready(function() {
	    $('#turkGateURL').blur(function() {
	      $('#turkGateURL').val(fix_http($('#turkGateURL').val(), true));
	    });
	  });
	</script>

</body>
</html>

