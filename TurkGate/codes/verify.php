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

<!-- Import the header -->
<?php 
    $title = 'TurkGate Administration';
    $description = 'TurkGate tools for the administrator.';
    $basePath = '../';
    require_once($basePath . 'includes/header.php'); 
?>
<div class="sixteen columns">
  <header>
	<h1 class="remove-bottom">TurkGate Code Verification</h1>
  </header>
</div>
<div class="sixteen columns clearfix" style="border-top: 1px solid #ccc; padding-top:10px;"> <!-- sixteen columns clearfix -->

  <?php
      $encryptionKey = constant('KEY');
	  
      // Parse the user-submitted results
      if (isset($_POST['submit'])) {
  	      $results = stripslashes($_POST["results"]);

	      // Add a blank new line in case one was not included
	      // This is required for proper CSV parsing
	      $results .= "\n"; 
	
	      if (!include('../lib/parsecsv.lib.php')) {
              echo '<p>TurkGate has encountered a configuration error. Please ' 
                  . 'verify that the following file is present: TurkGate/lib/parsecsv.lib.php</p>'
			      . "<p>=<a href='javascript:history.back()'>Go back</a>.</p>"
			      . "<h5>&copy; 2012 <a href='http://gideongoldin.github.com/TurkGate/' target='blank'>TurkGate</a></h5>";
          }
	
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
          // Extract values from user-submitted results
          // For each row of the parsed CSV data, note key/value(row) pairs
          foreach ($csv->data as $key => $row) :
              // If there exists a completion code...
		      if(strlen($row['Answer.completionCode']) > 0) {
		
			      // Display some basic information
	              // NOTE: Mechanical Turk HIT template's completion code input box must
	              // be named 'completionCode'
 			      echo '<tr>';
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

	              // Search for duplicate codes
	              $codeIsUnique = true;
	              foreach ($csv->data as $keyInner => $rowInner) {
	                  if ($rowInner['Answer.completionCode'] == $row['Answer.completionCode'] 
	                      && $key != $keyInner) {
		
					      // Only show one duplicate tag per record
					      if($codeIsUnique) {
		                      echo '<span class="invalid">DUPLICATE(S) FOUND</span>';
		                      $codeIsUnique = false;
					      }
	                  }
	              }

	              if ($codeIsValid && $codeIsUnique) {
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
	    Copy and paste the entire contents of your downloaded Mechanical Turk 
	    batch results file into the text area below:
	  </p>
	  <form method="post" action="verify.php">
	    <div>
	      <textarea name="results" rows="20" wrap="off"></textarea>
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