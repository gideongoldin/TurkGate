<?php 

session_start();

if (isset($_POST['submit'])) {
    if (strlen($_POST['varName']) > 0) {
        $varName = urlencode($_POST['varName']);
        $varValue = urlencode($_POST['varValue']);

        header("Location: generateCompletionCode.php?$varName=$varValue");
    } else {
        header("Location: generateCompletionCode.php");
    }
}
?>

<html>

  <body>
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
    <!-- This page can be used to test whether SurveyAccess.php sends you where
    you intend and what variables are set. In order to reach this page through
    SurveyForm.php, add it to the SurveySites database along with an identifier
    (e.g. "test"). If you then go to SurveyForm.php with the parameter
    "survey=test 1", it will send you to this page. The "1" is arbitrary but
    required. SurveyForm expects a specific survey identifier but this page
    doesn't care what it is. -->
    <p>
      TurkGate let you through to this page. 
      If you try again with the same worker ID and group it should stop you.
    </p>

    <p>
      To test completion code generation, click the button below. 
      If you want to include a variable in the completion code, 
      first enter its name and value.
    </p>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <table>
        <tr>
          <td> Name: </td>
          <td>
          <input type="text" name="varName">
          </td>
        </tr>
        <tr>
          <td> Value: </td>
          <td>
          <input type="text" name="varValue">
          </td>
        </tr>
      </table>
      <input type="submit" name="submit" value="Go to completion code page">
      <br>
    </form>
    <br>
    <br>
    <p>
      Below is a log of the system state (for debugging purposes).
    </p>
    <ul>
      <?php

    // print all URL parameters
    echo '<li>URL variables:<ul>';
    foreach ($_GET as $key => $value) {
        echo "<li> URL: $key = $value </li>";
    }
    echo '</ul></li>';

    // print all cookies
    echo '<li>Cookies:<ul>';
    foreach ($_COOKIE as $key => $value) {
        echo "<li> COOKIE: $key = $value </li>";
    }
    echo '</ul></li>';

    // print all session variables
    echo '<li>Session variables:<ul>';
    foreach ($_SESSION as $key => $value) {
        echo "<li> SESSION: $key = $value </li>";
    }
    echo '</ul></li>';
      ?>
    </ul>
  </body>
</html>
