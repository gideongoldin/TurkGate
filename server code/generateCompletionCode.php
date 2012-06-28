<?php session_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<title>TurkGate</title>
	
		<script>
		
			function exit() {
				return "Please verify that you have saved the code on this page before leaving!";
			}
			
			window.onbeforeunload = exit;
			
		</script>
	
	</head>

	<body>

		<?php
			
			// Add the Worker ID and the Group name to the to-be-hashed input string.
			$inputString = 'w[' . $_COOKIE['Worker_ID'] . ']g[' . $_COOKIE['Group_Name'] . ']';
		
			// Add any key-value pairs from the GET array to the input string.
			foreach($_GET as $key => $value) {
				$inputString .= $key[0] . '[' . $value . ']';
			}
			
			// Create/Modify salt. see http://php.net/manual/en/function.crypt.php
			$salt = '$2a$07$usesomesillystringforsalt$';
			
			// Construct the final completion code
			$completionCode = $inputString . ':' . crypt($inputString, $salt);
			
			// Display the completion code to the user
			echo '<div style="width:50%; margin:200px auto;">';
				echo '<p style="text-align:center; font-weight:bold;">Thank you!</p>';
				echo '<p style="text-align:center;">Please enter the code below into the Mechanical Turk HIT page to receive credit for your participation.</p><hr />';
				echo '<p style="text-align:center; margin-top:26px;"><span style="color:yellow; background:black; font-family:Courier, monospace; padding:10px;">';
					echo $completionCode;
				echo '</span></p>';
			echo '</div>';
		
		?>
		
	</body>
	
</html>