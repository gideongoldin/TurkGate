<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>TurkGate</title>
	</head>
	<body>        
		<h1>TurkGate</h1>
		<h2>Completion code verification</h2>
		<?php
			// Replace this with your own private salt!
			$salt = 'npeppa'; 
		
	        if (isset($_POST['submit'])) { 
	            $results = stripslashes($_POST["results"]);
						
				require_once('parsecsv-0.3.2/parsecsv.lib.php');
			
				$csv = new parseCSV();
				$csv->delimiter = ",";
				$csv->parse($results);

				?>
			
	            <style type="text/css" media="screen">
					table, td, th { border: 1px solid #CCC; }
					th { background-color: #666; color: #fff; }
				</style>
			
				<table border="0" cellspacing="1" cellpadding="3">
					<tr>
						<?php foreach ($csv->titles as $key => $value): 
							if($value == 'WorkerId' || $value == 'Answer.code'){
								echo '<th>' . $value . '</th>';
								echo '3';
							}
						endforeach; ?>
	                    <th>Submit Time</th>
	                    <th>Elapsed Time</th>
	                    <th>Status</th>
	                </tr>
                
					<?php foreach ($csv->data as $key => $row):
						echo '<tr>';
	                    	echo '<td>' . $row['WorkerId'] . '</td>';
	                        echo '<td>' . $row['Answer.code'] . '</td>';						
							echo '<td>' . $row['SubmitTime'] . '</td>';
							echo '</td><td>' . floor($row['WorkTimeInSeconds'] / 60) . ' [min] ' . ($row['WorkTimeInSeconds'] % 60) . ' [s]' . '</td>';
							echo '<td>';
						
							// Validate codes
							$codeIsValid = true;
							$answerCodeArray = explode('h', $row['Answer.code']);
							if(md5($answerCodeArray[0] . $salt) != $answerCodeArray[1]){
								echo '<span style="background-color:black; color:red; border:1px solid red; font-weight:bold; margin-right:5px;">INVALID CODE</span>';
								$codeIsValid = false;
							}
						
							// Search for duplicate codes
							$codeIsUnique = true;
							foreach($csv->data as $keyInner => $rowInner){
								if($rowInner['Answer.code'] == $row['Answer.code'] && $key != $keyInner){
									echo '<span style="background-color:black; color:yellow; border:1px solid yellow; font-weight:bold;">DUPLICATE CODE</span>';
									$codeIsUnique = false;
								}
							}	
						
							if($codeIsValid && $codeIsUnique){
								echo '<span style="background-color:green; color:black; border:1px solid black; font-weight:bold;">Valid</span>';
							}
							echo '</td>';
						echo '</tr>';
					endforeach; ?>
				</table>
			
	        <?php
	        } else {
	        ?>
	            <h3>Please copy and paste the contents of your results file into the text area below:</h3>
	            <form method="post" action="verifyCompletionCode.php">
	                <textarea name="results" rows="20" cols="100" wrap="off"></textarea>
	                <br />
	                <input type="submit" value="Analyze Codes" name="submit">
	            </form>
	            <?php
	        }
	    ?>
	</body>
</html>

