<?php
/*
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
 */

require_once('../config.php');

class DatabaseObject {
	
	var $con = NULL;

    /**
	 * Constructor
	 * @params parameters for database access
	 */  
    function DatabaseObject($dbUsername = null,
                           $dbPassword = null,
                           $dbName = null,
                           $dbHost = null) {


                           
        if ($dbUsername === null) {
        	$dbUsername = constant('DATABASE_USERNAME');
        }
        if ($dbPassword === null) {
        	$dbPassword = constant('DATABASE_PASSWORD');
        }
        if ($dbName === null) {
        	$dbName = constant('DATABASE_NAME');
        }
        if ($dbHost === null) {
        	$dbHost = constant('DATABASE_HOST');
        }

	    // connect to the database
	    $this->con = mysql_connect($dbHost, $dbUsername, $dbPassword) 
	           or die('There was an error connecting to the database. ' . 
	             'Please contact the requester to notify them of the error.');
	
	    mysql_select_db($dbName) 
	      or die('There was an error selecting the database. ' . 
	        'Please contact the requester to notify them of the error.');
	}

	/**
 	 * Gets group names from database.
	 */
	function getGroupNames($term = '') {
		$return_arr = array();
		$fetch = mysql_query("SELECT DISTINCT groupName FROM SurveyRequest WHERE groupName LIKE '%$term%'");
		while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
        	$row_array['value'] = $row['groupName'];
        	array_push($return_arr,$row_array); // Adds data to end of array
    	}
 
		echo json_encode($return_arr);
	}
						   
	/**
	 * checkAccess - check whether the worker is allowed to access a survey in the group
	 * @param workerId - Turk worker's unique identifier
	 * @param groupName - name for a group of studies
	 * @param recordAccess - should this be recorded as an access attempt (true - default) or is it just a check (false)
	 * @param surveyURL - URL of the survey being accessed
	 * 
	 * @return true if access allowed, false if denied
	 */					   
	function checkAccess($workerId, $groupName, $recordAccess = true, $surveyURL = '') {
	    // Look for entries with the same workerId and groupName.
	    $query = "SELECT * FROM SurveyRequest WHERE " 
	      . "SurveyRequest.workerID='$workerId' " 
	      . "AND SurveyRequest.groupName='$groupName';";
		  
	    $result = mysql_query($query) 
	              or die('There was an error retreiving access info. Please ' 
	                . 'contact the requester to notify them of the error.');
	
	    //If one exists, this worker has already done a survey in the group and will
	    // be blocked from reaching the survey.
	    if (mysql_numrows($result) > 0) {
	    	return false;
	    }	
		
		if ($recordAccess) {

	        // Add the access to the database to block future access
	        $query = "INSERT INTO SurveyRequest " 
	          . "(SurveyRequest.workerID, SurveyRequest.groupName, SurveyRequest.URL, SurveyRequest.time) " 
	          . "VALUES ('$workerId', '$groupName', '$surveyURL', NOW());";
			  
	        $result = mysql_query($query) 
	                  or die('There was an error saving to the database. Please ' 
	                    . 'contact the requester to notify them of the error.');
		}
		
		return true;
    }
	
	/**
	 * close - releases all resources
	 */
	function close() {
        mysql_close($this->con);		
	}
}

?>