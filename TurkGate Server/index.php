<?php
  // Get TurkGate's database configuration
  include 'turkgateConfig.php';

  // Run the install script if no TurkGate database is found
  if(!defined('DATABASE_NAME')) {
    header('Location: install.php');
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
    <h5>&copy; 2012 <a href='https://github.com/gideongoldin/TurkGate'>TurkGate</a></h5>
  </body>
</html>