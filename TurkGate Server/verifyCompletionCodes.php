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
	
	=============================================================================

	NOTE: parseCSV library is:
	
	Copyright (c) 2007 Jim Myhrberg (jim@zydev.info).

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN 
	THE SOFTWARE.
	
	=============================================================================
	
	When you are ready to verify survey completion, download the Batch results file for your HIT from within Mechanical Turk. This comma-separated-value file will contain previously generated completion codes.

	Copy and paste the entire contents of this file into the text area at http://yourexternalserver.com/youroptionalsurveypath/verifyCompletionCodes.php, and press Verify codes. Be sure to include the blank new-line character at the end of the file. Note that the `parsecsv-0.3.2` (http://code.google.com/p/parsecsv-for-php/) folder must reside in this same directory.

	The script will then automatically verify all codes. First, it checks whether the information in the first half of a code (e.g., Worker ID, Group Name, and any custom key-value pairs)--when combined with a private salt and hashed--matches the second half of the code. This step helps prevent users from fabricating their own codes, since users do not know your private salt. The script then checks if any codes are duplicated.

	Upon pressing Verify codes, you will see a table of completion codes (including the aforementioned values), elapsed time for the HIT, and submit time for the HIT. This can be helpful in quickly detecting suspiciously fast entries, or in comparing submit times for duplicate rows.

	Like in generateCompletionCode.php, be sure to sure to substitute your own salt value in the PHP file in order to ensure proper security. These two salts must be identical.
-->
<!doctype html>
	<head>
		<title>TurkGate</title>
		<style type="text/css">
			body { margin: 50px auto; text-align: center; }
			table { margin: 0px auto; }
			th { border-bottom: 1px dotted black; }
			td { padding: 0 20px; }
			.valid, .invalid { background: red; color: white; padding: 0 2px; margin: 0 2px; border-radius: 2px; font-family: monospace;  }
			.valid { background: green; }
			footer, a { color: gray; }
		</style>
	</head>
	<body>
	    <header><h1>Completion Code Verification</h1></header>
		
		<?php
		// Prepare salt (replace 'shaker' with your own key)
		// NOTE: This value must match that defined in verifyCompletionCode.php!
		$salt = 'shaker';
	
		// Parse user-submitted results
        if (isset($_POST['submit'])) { 
            $results = stripslashes($_POST["results"]);
			require_once('parsecsv-0.3.2/parsecsv.lib.php');
			$csv = new parseCSV();
			$csv->delimiter = ",";
			$csv->parse($results);
			?>
			
			<table>
				<tr>
					<th>Completion Code</th>
                    <th>Elapsed Time</th>
                    <th>Submit Time</th>
                    <th>Status</th>
                </tr>
                
				<?php
				// Extract values from results file
				// For each element in the parsed CSV row, note its key/value(row) pair
				foreach ($csv->data as $key => $row):
					echo '<tr>';
						// Display basic information
						// NOTE: Mechanical Turk HIT template's completion code input box must be named 'completionCode'
                        echo '<td>' . $row['Answer.completionCode'] . '</td>';						
						echo '</td><td>' . floor($row['WorkTimeInSeconds'] / 60) . 'min ' . ($row['WorkTimeInSeconds'] % 60) . 's' . '</td>';
						echo '<td>' . $row['SubmitTime'] . '</td>';
					
						// Check if latter half of code is hashing of first half and salt
						echo '<td>';
						$codeIsValid = true;
						$completionCodeArray = explode(':', $row['Answer.completionCode']);
						if(sha1($completionCodeArray[0] . $salt) != $completionCodeArray[1]) {
							echo '<span class="invalid">INVALID</span>';
							$codeIsValid = false;
						}
				
						// Search for duplicate codes
						$codeIsUnique = true;
						foreach($csv->data as $keyInner => $rowInner){
							if($rowInner['Answer.completionCode'] == $row['Answer.completionCode'] && $key != $keyInner){
								echo '<span class="invalid">DUPLICATE</span>';
								$codeIsUnique = false;
							}
						}	
				
						if($codeIsValid && $codeIsUnique){
							echo '<span class="valid">VALID</span>';
						}
						echo '</td>';
					echo '</tr>';
				endforeach; 
				?>
			</table>

        <?php
        } else {
        ?>
            <p>Copy and paste the entire contents of your downloaded Mechanical Turk batch results file into the text area below:</p>
            <form method="post" action="verifyCompletionCodes.php">
                <div><textarea name="results" rows="20" cols="100" wrap="off"></textarea></div>
                <div><input type="submit" value="Verify codes" name="submit"></div>
            </form>
        <?php
        }
    	?>
    
		<footer><h6>Powered by <a href="https://github.com/gideongoldin/TurkGate" title="TurkGate" target="_blank">TurkGate</a></h6></footer>
	</body>
</html>

