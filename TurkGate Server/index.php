<?php 
session_start();

// Get TurkGate's database configuration
if (!include ('turkGateConfig.php')) {
    header('Location: install.php');
    exit();
}

// If the form was submitted, test survey access
if (isset($_POST['submit'])) {
    $workerID = urlencode($_POST['workerID']);
    $groupName = urlencode($_POST['groupName']);
	$surveyUrl = urlencode($_POST['surveyUrl']);
	$source = urlencode($_POST['source']);

    header("Location: surveyAccess.php?assignmentId=test&workerId=$workerID&group=$groupName&survey=$surveyUrl");
	exit();
}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>TurkGate</title>
  </head>
  <body>
  	<h1>TurkGate</h1>
  	<h2>Installation</h2>
    <p>TurkGate has already been installed!</p>
    <br>
    <h2>Testing</h2>
    <p>To test the installation, enter a workerId and group name below and click Test.</p>
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <table>
        <tr>
          <td> Worker Id: </td>
          <td>
          <input type="text" name="workerID" value="1">
          </td>
        </tr>
        <tr>
          <td> Group: </td>
          <td>
          <input type="text" name="groupName" value="test group">
          </td>
        </tr>
      </table>
      <input type="submit" name="submit" value="Test">
      <br>
      <p>If you have a specific survey to test, you can enter its URL below.</p>
      <table>
        <tr>
          <td> Survey URL: </td>
          <td>
          <input type="text" name="surveyURL" value="test">
          </td>
        </tr>
      </table>
      <p>
      	Also specify whether you plan to create an external HIT or use the 
      	Javascript template.
      </p>
      <p>
        <input type="radio" name="source" value="ext">External HIT</input>
      </p>
      <p>
      	<input type="radio" name="source" value="js">Javascript</input>
      </p>
      <p>
      	<input type="submit" name="submit" value="Test">
      </p>
    </form>

    <h5>
      &copy; 2012 
      <a href='https://github.com/gideongoldin/TurkGate'>TurkGate</a>
    </h5>
  </body>
</html>
