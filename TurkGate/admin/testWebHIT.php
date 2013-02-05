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

	$template = file_get_contents('../resources/WebHIT/webTemplate.html');
	
	$searchValues = array('[[[Survey URL]]]', '[[[Group Name]]]', '[[[TurkGate URL]]]');
	$replacements = array($_GET['survey'], $_GET['group'], constant('BASE_URL'));
	
	echo str_replace($searchValues, $replacements, $template);
?>