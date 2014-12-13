<?php 
    session_start(); 

    if (!include('../config.php')) {
        die('A configuration error occurred. ' 
          . 'Please report this error to the HIT requester.');
    }
    
?>

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
	<title>TurkGate - Verify</title>
	<meta name="description" content="Verify TurkGate completion codes.">
	<meta name="author" content="">
	
	<!-- Add the imports -->
	<?php 
	    $basePath = '../';
	    require_once($basePath . 'includes/imports.php'); 
	?>
    
    <script type="text/javascript">
        // Drag and Drog code
        // http://stackoverflow.com/questions/11313414/html5-drag-and-drop-load-text-file-in-a-textbox-with-js
        $(document).ready(function(){
            var holder = document.getElementById('resultsTextArea');

            holder.ondrop = function(e) {
                e.preventDefault();

                var file = e.dataTransfer.files[0],
                reader = new FileReader();
                reader.onload = function(event) {
                    console.log(event.target);
                    holder.value = event.target.result;
                };
                console.log(file);
                reader.readAsText(file);

                return false;
            };
        });
    </script>
</head>

<body id="verify">
	
  <div class="container"> <!-- Container -->
	
	<!-- Add the header -->
	<?php 
	    $title = 'Verify Codes';
	    require_once($basePath . 'includes/header.php'); 
	?>
<div class="sixteen columns clearfix" style="border-top: 1px solid #ccc; padding-top:10px;"> <!-- sixteen columns clearfix -->

  <?php
      $encryptionKey = constant('KEY');
	  
      // Parse the user-submitted results
      if (isset($_POST['submit'])) {
  	      $results = stripslashes($_POST["results"]);
//  	      $results = $_POST["results"];

	      // Removing all blank lines
	      // This is required for proper CSV parsing
		  $results = preg_replace('/^\n+|^[\t\s]*\n+/m', "", $results);
		  $results .= "\n";
	
	      if (!include('../lib/parsecsv.lib.php')) {
              echo '<p>TurkGate has encountered a configuration error. Please ' 
                  . 'verify that the following file is present: TurkGate/lib/parsecsv.lib.php</p>'
			      . "<p>=<a href='javascript:history.back()'>Go back</a>.</p>"
			      . "<h5>&copy; 2012 <a href='http://gideongoldin.github.com/TurkGate/' target='blank'>TurkGate</a></h5>";
          }
	
	      $csv = new parseCSV();
		  $delim = $csv->auto($results, true, 1, ",\t");
	      $csv->delimiter = ",";
	      $csv->parse($results);
		  
		  // Check for proper parsing, and if not try tab delimited to support copying from Excel
		  // parseCSV has auto parsing
		  if (count($csv->titles) <= 1) {
	        $csv->delimiter = "\t";
	        $csv->parse($results);
		  }
  ?>

  <table>
    <tr>
      <th>Worker ID</td>
      <th>Completion Code</th>
      <th>Elapsed Time</th>
      <th>Submit Time</th>
      <th>Status</th>
    </tr>

    <?php 
          // Extract values from user-submitted results
          // For each row of the parsed CSV data, note key/value(row) pairs
          foreach ($csv->data as $key => $row) :
              // If there exists a completion code...
		      if(!empty($row['Answer.completionCode'])) {
		
			      // Display some basic information
	              // NOTE: Mechanical Turk HIT template's completion code input box must
	              // be named 'completionCode'
 			      echo '<tr>';
                  echo '<td>' . $row['WorkerId'] . '</td>';
	              echo '<td>' . $row['Answer.completionCode'] . '</td>';
	              echo '</td><td>' . floor($row['WorkTimeInSeconds'] / 60) . 'min ' 
	                  . ($row['WorkTimeInSeconds'] % 60) . 's' . '</td>';
	              echo '<td>' . $row['SubmitTime'] . '</td>';

	              // Check if latter half of code is hashing of first half and encryption key
	              echo '<td>';
	              $codeIsValid = true;
	              $completionCodeArray = explode(':', $row['Answer.completionCode']);
	              if (sha1($completionCodeArray[0] . $encryptionKey) != $completionCodeArray[1]) {
	                  echo '<span class="invalid">INVALID HASHING</span>';
	                  $codeIsValid = false;
	              }
				  
				  // check if the workerID in the code matches the actual workerID
				  if (preg_match('/w\[(\w*)\]/', $row['Answer.completionCode'], $matches) == 0) {
			      	
					if (!$codeIsValid) {
						echo '<br>';
					}
			      	$codeIsValid = false;
	                echo '<span class="invalid">MISSING WORKERID</span>';				  	
				  } elseif (strcmp($matches[1], $row['WorkerId']) != 0 ) {
                    if (!$codeIsValid) {
						echo '<br>';
					}
			      	$codeIsValid = false;
					$actualId = $row['WorkerId'];
					$codeId = $matches[1];
	                echo "<span class='invalid'>WORKERID MISMATCH: ACTUAL=$actualId, IN CODE=$codeId</span>";				  	
					  
                    }

	              // Search for duplicate codes and save the row numbers
	              foreach ($csv->data as $keyInner => $rowInner) {
	                  if (!empty($rowInner['Answer.completionCode']) 
	                      && $rowInner['Answer.completionCode'] == $row['Answer.completionCode'] 
	                      && $key != $keyInner) {

						  $duplicateRows[] = $rowInner['WorkerId'];
	                  }
	              }

			      // Only show one duplicate tag per record
			      if(!empty($duplicateRows)) {
			      
					if (!$codeIsValid) {
						echo '<br>';
					}
			      	$codeIsValid = false;
					  
			      	echo '<span class="invalid">';
                    if (count($duplicateRows) == 1) {
                      	echo "DUPLICATE FOUND IN WORKER ID $duplicateRows[0]";
				  	} else {
					  echo 'DUPLICATES FOUND IN WORKER IDs: ';
                      foreach ($duplicateRows as $key => $value) {
                          if ($key > 0) {
                          	echo ', ';
                          }
						  echo $value;
                      }
					}
                    echo '</span>';
					unset($duplicateRows);
 			      }

	              if ($codeIsValid) {
	                  echo '<span class="valid">VALID</span>';
	              }
	              echo '</td>';
	              echo '</tr>';
		      }
          endforeach;
    ?>
  </table>

  <?php 
      } else { 
  ?>

  	<p>
	    Copy and paste or just drag and drop your <a href="http://docs.aws.amazon.com/AWSMechTurk/latest/RequesterUI/ReviewingResultsOutsideoftheRUI.html" target="_blank">results file from Mechanical Turk</a> into the box below.
	  </p>
	  <form method="post" action="verify.php">
	    <div>
	      <textarea name="results" rows="20" wrap="off" id="resultsTextArea"></textarea>
	    </div>
	    <div>
	      <input type="submit" value="Verify codes" name="submit">
	    </div>
	  </form>

  <?php
      }
  ?>
	</div>

	<!-- Import the footer -->
	<?php require_once($basePath . 'includes/footer.php'); ?>

  </div> <!-- Container -->
</body>
</html>
