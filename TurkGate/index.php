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

    require_once('config.php');
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>TurkGate - Generate a HIT</title>
	<meta name="description" content="Creating a TurkGate HIT.">
	<meta name="author" content="">
	
	<!-- Add the imports -->
	<?php 
	    $basePath = '';
	    require_once($basePath . 'includes/imports.php'); 
	?>
	
</head>

<body id="generate">
	
  <div class="container"> <!-- Container -->
	
	<!-- Add the header -->
	<?php 
	    $title = 'Generate a HIT';
	    require_once($basePath . 'includes/header.php'); 
	?>
		
	<div class="sixteen columns clearfix" id="containerMain"> <!-- sixteen columns clearfix -->
		<div class="eight columns alpha" id="leftColumnMain">

			<p>From here you may generate the HTML code for your Web Interface HIT, or download files for use with the Command Line Tool.</p>

			<form method="post" id="hitGenerationForm" name="hitGenerationForm">
				
					<label for="surveySites" class="adjacent">Survey Site:</label>
					<span class="ui-icon ui-icon-help adjacent help" title="Where did you create your survey?"></span>
					<select name="surveySites" id="surveySites">
	  					<option value="LimeSurvey">LimeSurvey</option>
	  					<option value="Qualtrics" selected>Qualtrics</option>
	  					<option value="Other">Other Sites / Custom</option>
					</select>
				
					<label for="externalSurveyURL" class="adjacent">Survey URL:</label>
					<span class="ui-icon ui-icon-help adjacent help" title="Link to your survey."></span>
					<input type="text" required name="externalSurveyURL" id="externalSurveyURL" value='' size="40" placeholder="http://surveysite.com/surveyid" autofocus="" maxlength="128">
				
					<label for="groupName" class="adjacent">Group Name:</label>
					<span class="ui-icon ui-icon-help adjacent help" title="Workers will be prevented from participating in multiple studies from the same group."></span>
					<div class="comment" class="adjacent">Previously created group names will appear below</div>
					<input type="text" required name="groupName" id="groupName" value='' size="40" placeholder="Test group name" maxlength="128">				
				
				
					<label for="associatedURLs" class="adjacent">Survey URLs in Group:</label>
					<span class="ui-icon ui-icon-help adjacent help" title="Surveys with this group name that have already been accessed."></span>
					<textarea disabled id="associatedURLs"></textarea>
			
					<label for="HITType" class="adjacent">HIT Type:</label>
					<span class="ui-icon ui-icon-help adjacent help" title="How are you creating your HIT, online or with the Command Line Tools?"></span>
					<select name="HITType" id="HITType">
	  					<option value="WebInterface">Web Interface</option>
	  					<option value="CLT">Command Line Tools</option>
					</select>
					
					<input type="submit" name="submitForm" id="submitForm" value="Generate HIT" />

			</form>
		</div>	
	
		<div class="eight columns omega" id="rightColumnMain"> <!-- columns omega -->
			<div id="rightColumnContainer">
				<div id="WebHITContent" style="display:none;">
					<p>
						Copy the HTML code below into your HIT. Full instructions are on the <a href="https://github.com/gideongoldin/TurkGate/wiki/Web-Interface-HIT-Creation" target="blank">TurkGate Wiki</a>.
					</p>
							
					<?php
							$textAreaId = 'generatedHTMLCode';
							$keepAllSelected = false;								
							require_once 'lib/autoselect.php';
		            ?>								
					<div id="generatedContent">
						<textarea id="<?php echo $textAreaId ?>"></textarea>
					</div>
				</div>

				<div id="CLTHITContent" style="display:none;">

					<p>
						Download the files for creating your HIT. Full instructions are on the <a href="https://github.com/gideongoldin/TurkGate/wiki/Command-Line-Tools-HIT-Creation" target="blank">TurkGate Wiki</a>.
					</p>
					
					<p>
						<button id="downloadCLTInputFile">survey.input</button>
						<button id="downloadCLTPropertiesFile">survey.properties</button>
						<button id="downloadCLTQuestionFile">survey.question</button>
					</p>
				</div>

				<div id="CompletionContent" style="display:none;">
					<p >
						<span class="section-begin">Provide completion codes</span> to your participants at the end of your survey by sending them to the following URL:
					</p>

					<p>
						<a href="#" target="_blank" id="completionLink"></a>
					</p>

					<p>
						<span class="section-begin">Verify completion codes</span> at <a href="/codes/verify.php">the verification page</a>.
					</p>
				</div>
			</div>
		</div> <!-- columns omega -->
		
	</div> <!-- columns clearfix -->
  
	<!-- Import the footer -->
	<?php require_once($basePath . 'includes/footer.php'); ?>

  </div> <!-- container -->

<!------------------------------------------ JAVASCRIPT ---------------------------------------->

<script src="lib/fixhttp.lib.js"></script>

<!-- Custom jQuery actions -->
<script type="text/javascript">

	var isAnimating = false;
	var alreadySubmitted = false;

	$(document).ready(function(){ 

		// Setup tooltips
		$(document).tooltip();

		// Setup autocomplete for group name
		$("#groupName").focus(function() {
			$( this ).autocomplete( "search");
		});

		$( "#groupName" ).autocomplete({
      		source: "lib/fetchgroupnames.php",
      		delay: 0,
      		minLength: 0,
      		select: function(event, ui) { 
      			populateAssociatedURLs(ui.item.value);
      		},
      		change: function(event, ui) { 
      			if(ui.item != null) {
      				populateAssociatedURLs(ui.item.value);
      			}
      		}
      	});

		$("#groupName").keyup( function() { 
			populateAssociatedURLs($("#groupName").val());
		}); 

        $('#externalSurveyURL').blur(function() {
          $('#externalSurveyURL').val(fix_http($('#externalSurveyURL').val()));
        });
        
        // submitting the form calls whatever function has been set
        $('#hitGenerationForm').submit(function () { 

    		if (isAnimating) {
    			return false;
    		}
        	isAnimating = true;

        	if (alreadySubmitted) {
 		      	$("#rightColumnMain").animate(
	 		      		{
	 		      			left:'-=460px'
	 		      		},
	 		      		'fast', 
	 		      		function() {
							resetHITContent();
			 		      	populateHITContent();
	 		      		}
 		      		);   
       		} else {
       			alreadySubmitted = true;
       			populateHITContent();
       		}

          	return false;
        });

    });

	function resetHITContent() {
		$("#WebHITContent").hide();
		$("#CLTHITContent").hide();
		$("#CompletionContent").hide();

		$("#downloadCLTInputFile").unbind("click");
    	$("#downloadCLTPropertiesFile").unbind("click");
    	$("#downloadCLTQuestionFile").unbind("click");
	}

	function populateHITContent() {
		var surveyURL = fix_http($('#externalSurveyURL').val());
		var groupName = $('#groupName').val();

    	switch ($("#HITType").val()) {
    		case "WebInterface":
    			generateWebCode(surveyURL, groupName);
    			$("#WebHITContent").show();
    			break;
    		case "CLT":
    			$("#downloadCLTInputFile").click(function() { downloadCLTFile("survey.input", surveyURL, groupName); });
    			$("#downloadCLTPropertiesFile").click(function() { downloadCLTFile("survey.properties", surveyURL, groupName); });
    			$("#downloadCLTQuestionFile").click(function() { downloadCLTFile("survey.question", surveyURL, groupName); });
    			$("#CLTHITContent").show();
    			break;
    		default:
    			alert("ERROR: Invalid HIT Type.");
    			return false;
    	}


    	// Generate completion link
    	var numDigits = 6;
    	var completionLink = "<?php echo constant('BASE_URL') ?>/codes/generate.php?stamp=" + Math.floor(Math.random() * Math.pow(10, numDigits) + Math.pow(10, numDigits + 1)); 
    	switch($("#surveySites").val()) {
    		case "LimeSurvey":
    			completionLink += "&user={SAVEDID}&survey={SID}";
    			break;
    		case "Qualtrics":
    			completionLink += "&responseID=${e://Field/ResponseID}";
    			break;
    		case "Other":
    			// do nothing
    			break;
    		default:
    			alert("ERROR: Invalid Survey Site.");
    			return false;
    	}
    	$("#completionLink").html(completionLink);
    	$("#completionLink").attr('href', completionLink );
    	$("#CompletionContent").show();
    	
    	$("#rightColumnMain").animate(
    		{
    			left:'+=460px'
    		},
    		'slow',
    		function() {
    			isAnimating = false;
    		}
    	);   
	}

	function populateAssociatedURLs(groupName) {
		$.ajax({                                      
  			url: 'lib/fetchsurveyurls.php',                        
  			data: "group="+groupName,
  			dataType: 'json', 
  			success: function(data) {
  				// Reset box contents
  				$("#associatedURLs").val("");

  				if(data.length == 0) {
  					$("#associatedURLs").val("No surveys were run in this group yet.");
  				} else {
			        for (var i=0; i<data.length; i++) {
			        	$("#associatedURLs").val($("#associatedURLs").val() + data[i].value + "\n");
			        }
		    	}
		    } 
		});
	}

	function generateWebCode(surveyURL, groupName) {
		var copyright = "<!-- Copyright (c) 2012 Adam Darlow and Gideon Goldin. For more info, see http://gideongoldin.github.com/TurkGate/ -->\n"; 
		
		var htmlCode = <?php echo json_encode(file_get_contents('resources/WebHIT/webTemplate.html')); ?>;
		htmlCode = htmlCode.replace('[[[Survey URL]]]', surveyURL);
		htmlCode = htmlCode.replace('[[[Group Name]]]', groupName);
		htmlCode = htmlCode.replace('[[[TurkGate URL]]]', "<?php echo constant('BASE_URL'); ?>");
		htmlCode = htmlCode.replace(/<!--[^>]*-->/, copyright);
		
		$('#generatedHTMLCode').val(htmlCode);
	}
	
	function endsWith(str, suffix) {
        return str.indexOf(suffix, str.length - suffix.length) !== -1;
    }
	
	function downloadCLTFile(fileName, surveyURL, groupName) {

		var url = 'lib/downloadFile.php?file=' + encodeURIComponent(fileName);
		
		if (endsWith(fileName, 'question')) {
			url = url + '&sub1=' + encodeURIComponent('[[[TurkGate URL]]]<?php echo constant('BASE_URL'); ?>');
		}
		else if (endsWith(fileName, 'input')) {
			url = url + '&sub1=' + encodeURIComponent('[[[Survey URL]]]' + surveyURL);
			url = url + '&sub2=' + encodeURIComponent('[[[Group Name]]]' + groupName);
		}
		
		window.open(url);
		
		return false;
	}
	
	function createDownloadHandler(fileName) {
		return function() { return downloadCLTFile(fileName); };
	}

</script>
	</body>
</html>
