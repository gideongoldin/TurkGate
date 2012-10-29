<!DOCTYPE html>
<html>
  <head>
    <style type="text/css">
		body {
			margin: 50px auto;
			text-align: center;
		}
		table {
			margin: 0px auto;
		}
		th {
			border-bottom: 1px dotted black;
		}
		td {
			padding: 0 20px;
		}
		footer, a {
			color: gray;
		}
    </style>
    <title>TurkGate</title>
  </head>

  <body>
    <header>
      <h1>Survey Request Log</h1>
    </header>

    <table>
      <th>Request ID</th>
      <th>Worker ID</th>
      <th>URL</th>
      <th>Group Name</th>
      <th>Time of Request</th>

      <?php
          // Commonly used variables
          $footer = "<footer><h6>&copy; 2012 " 
            . "<a href='https://github.com/gideongoldin/TurkGate' " 
            . "target='blank'>TurkGate</a></h6></footer>";

          // Include the config file to get DB credentials
          if (!include ('turkGateConfig.php')) {
              header('Location: install.php');
              exit();
          }

          // Connect to the DBMS, and select the TurkGate DB
          $connection = mysql_connect(DATABASE_HOST, 
                                      DATABASE_USERNAME, 
                                      DATABASE_PASSWORD) 
                        or die("<h1>TurkGate</h1><h2>Installation</h2>" . 
                          "<p>Error connecting to database. " . mysql_error() 
                          . ". See your system administrator.</p><p><a " 
                          . "href='javascript:history.back()'>Go back</a></p> $footer");
						  
          mysql_select_db(DATABASE_NAME, $connection) 
            or die("<p>Error selecting database: " . mysql_error() . 
              "</p><p><a href='javascript:history.back()'>Go back</a></p>" . $footer);

          // Populate the table
          $query = 'SELECT * FROM SurveyRequest';
          $result = mysql_query($query)
		              or die("<p>Error performing database query: " . mysql_error() . 
              "</p><p><a href='javascript:history.back()'>Go back</a></p>" . $footer);
		  
          while ($row = mysql_fetch_array($result)) {
              echo '<tr>';
              echo '<td>' . $row['requestID'] . '</td>';
              echo '<td>' . $row['workerID'] . '</td>';
              echo '<td>' . $row['URL'] . '</td>';
              echo '<td>' . $row['groupName'] . '</td>';
              echo '<td>' . $row['time'] . '</td>';
              echo '</tr>';
          }

          // Close the DBMS connection
          mysql_close($connection);
      ?>
    </table>

    <?php echo $footer ?>

  </body>
</html>