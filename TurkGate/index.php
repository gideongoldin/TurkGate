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

<!-- Import the header -->
<?php 
    $title = 'Generate a HIT';
    $description = 'Creating a TurkGate HIT.';
    $basePath = '';
	$pageID = 'generate';
    require_once($basePath . 'includes/header.php'); 
?>
<script src="lib/fixhttp.lib.js"></script>
		
<<<<<<< HEAD
	<div class="sixteen columns clearfix" style="border-top: 1px solid #ccc; padding-top:10px;"> <!-- sixteen columns clearfix -->
		<form method="post" id="hitGenerationForm" name="hitGenerationForm">
		<h3>Generate a HIT</h3>
		<div class="six columns alpha">
			<p>
				From here you may generate the HTML code for your Web Interface HIT, or download files for use with the Command Line Tool.
			</p>
			<p>
				<label for="HITType">HIT Type:</label>
				<select name="HITType">
  					<option value="webInterface">Web Interface</option>
  					<option value="CLT">Command Line Tools</option>
				</select>
			</p>
			<p>
				<label for="surveySites">External Survey Site:</label>
				<select name="surveySites">
  					<option value="LimeSurvey">LimeSurvey</option>
  					<option value="Qualtrics" selected>Qualtrics</option>
  					<option value="Other">Other Sites / Custom</option>
				</select>
			</p>
			<p>
				<label for="externalSurveyURL">External Survey URL:</label>
				<input type="text" name="externalSurveyURL" id="externalSurveyURL" value='' size="40" placeholder="http://surveysite.com/surveyid" autofocus="" required="">
			</p>
			<p>
				<label for="groupName">Group Name:</label>
				<span class="comment">Previously created group names will appear below</span>
				<input type="text" name="groupName" id="groupName" value='' size="40" placeholder="Test group name" required="" class="adjacent">				
			</p>
=======
<div class="sixteen columns">
  <header>
	<h1 class="remove-bottom"><a href="index.php">TurkGate</a></h1>
  </header>
</div>		

	<div class="sixteen columns clearfix" id="containerMain"> <!-- sixteen columns clearfix -->
		<div class="eight columns alpha" id="leftColumnMain">
			<form method="post" id="hitGenerationForm" name="hitGenerationForm">
				<h3>Generate a HIT</h3>
				<p>
					From here you may generate the HTML code for your Web Interface HIT, or download files for use with the Command Line Tool.
				</p>
				<p>
					<label for="HITType">HIT Type:</label>
					<select name="HITType">
	  					<option value="webInterface">Web Interface</option>
	  					<option value="CLT">Command Line Tools</option>
					</select>
				</p>
				<p>
					<label for="surveySites">External Survey Site:</label>
					<select name="surveySites">
	  					<option value="LimeSurvey">LimeSurvey</option>
	  					<option value="Qualtrics" selected>Qualtrics</option>
	  					<option value="Other">Other Sites / Custom</option>
					</select>
				</p>
				<p>
					<label for="externalSurveyURL">External Survey URL:</label>
					<input type="text" name="externalSurveyURL" id="externalSurveyURL" value='' size="40" placeholder="http://surveysite.com/surveyid" autofocus="">
				</p>
				<p>
					<label for="groupName">Group Name:</label>
					<span class="comment">Previously created group names will appear below</span>
					<input type="text" name="groupName" id="groupName" value='' size="40" placeholder="Test group name" class="adjacent">				
				</p>
				<p>
					<label for="associatedURLs">Survey URLs in Group:</label>
					<textarea disabled id="associatedURLs"></textarea>
				</p>
				<p>
					<input type="submit" name="submitForm" id="submitForm" value="Generate HIT">
				</p>
			</form>
		</div>	
	
		<div class="eight columns omega" id="rightColumnMain"> <!-- columns omega -->
>>>>>>> Added sliding drawer in index.php.
			<p>
				Generate the HTML code to paste into your HIT. Full instructions are on the <a href="http://gideongoldin.github.com/TurkGate/" target="blank">TurkGate Wiki</a>.
			</p>
						
			<?php
					$textAreaId = 'generatedHTMLCode';								
					require_once 'lib/autoselect.php';
            ?>								
			<div id="generatedContent">
				<em><small>Copy and paste the code below into the source code for your HIT:</small></em>
				<textarea rows="8" style="height:80;" id="<?php echo $textAreaId; ?>"></textarea>
			</div>

				<p>
					Download the files for creating your HIT. Full instructions are on the <a href="https://github.com/gideongoldin/TurkGate/wiki/Command-Line-Tools" target="blank">TurkGate Wiki</a>.
				</p>
				<em><small>Download:</small></em>
				
				<button id="downloadCLTInputFile">survey.input</button>
				<button id="downloadCLTPropertiesFile">survey.properties</button>
				<button id="downloadCLTQuestionFile">survey.question</button>

			<!-- Completion code stuff -->
			<p class="new-section">
				<span class="section-begin">Provide completion codes</span> to your participants at the end of your survey by sending them to one of the following URLs:
			</p>
				<?php
				    $numDigits = 6;
					$generateCodeLink = constant('BASE_URL') . '/codes/generate.php?stamp=' . mt_rand(pow(10, $numDigits), pow(10, $numDigits + 1) - 1);	
				?>
			<dl>
				<dt>For Qualtrics:</dt>
					<dd><a href="#"><?php echo $generateCodeLink ?>&responseID=${e://Field/ResponseID}</a></dd>
						
					<dt>For LimeSurvey:</dt>
					<dd><a href="#"><?php echo $generateCodeLink ?>&user={SAVEDID}&survey={SID}</a></dd>	
						
					<dt>For other sites:</dt>
					<dd><a href="#"><?php echo $generateCodeLink ?></a></dd>
				</dl>
			<p class="new-section">
				<span class="section-begin">Verify completion codes</span> at <a href="codes/verify.php">this page</a>.
			</p>
		</div> <!-- columns omega -->
		
	</div> <!-- columns clearfix -->

<!------------------------------------------ JAVASCRIPT ---------------------------------------->

<!-- Custom jQuery actions -->
<script type="text/javascript">
	$(document).ready(function(){ 
		// CLT Variables
		


		// Setup tabs
		$(".tab_content").hide();
		$(".tab_content:first").show(); 

		$("ul.tabs li").click(function() {
			$("ul.tabs li").removeClass("active");
			$(this).addClass("active");
			$(".tab_content").hide();
			var activeTab = $(this).attr("rel"); 
			$("#"+activeTab).show(); 
		});

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
      			populateAssociatedURLs(ui.item.value);
      		}
      	});

		$("#groupName").keyup( function() { 
			populateAssociatedURLs($("#groupName").val());
		}); 

        $('#externalSurveyURL').blur(function() {
          $('#externalSurveyURL').val(fix_http($('#externalSurveyURL').val()));
        });
        
		// each of the submission buttons sets the function to be called upon submission, but doesn't 
		// actually call the function. Only the submit event triggers the function, allowing it to check 
		// that all required fields have been filled out.
        $('#generateHTMLCode').click( function () { 
        	$('#hitGenerationForm').data("submitFunction", generateWebCode); 
        });
        
        $('#downloadCLTInputFile').click( function () { 
        	$('#hitGenerationForm').data("submitFunction", createDownloadHandler('survey.input')); 
        });
        
        $('#downloadCLTPropertiesFile').click( function () { 
        	$('#hitGenerationForm').data("submitFunction", createDownloadHandler('survey.properties')); 
        });
        
        $('#downloadCLTQuestionFile').click( function () { 
        	$('#hitGenerationForm').data("submitFunction", createDownloadHandler('survey.question')); 
        });
        
        $('#generatedContent').hide();
        
        // submitting the form calls whatever function has been set
        $('#hitGenerationForm').submit(function () { 
        	var subFunc = $('#hitGenerationForm').data("submitFunction");
        	if (typeof subFunc === 'function') {
          		subFunc();
          	}
        
        	$('#hitGenerationForm').removeData("submitFunction");

        	/*
        	layout slideing animation
        	*/

        	//$("#rightColumnMain").show("slide", { direction: "left" }, 1000);
        	$("#rightColumnMain").animate({left:'+=460px'},'slow');   

          	return false;
        });
    });

	function populateAssociatedURLs(groupName) {
			$.ajax({                                      
      			url: 'lib/fetchsurveyurls.php',                        
      			data: "group="+groupName,
      			dataType: 'json', 
      			success: function(data) {
      				$("#associatedURLs").val("");
			        for (var i=0; i<data.length; i++) {
			        	$("#associatedURLs").val($("#associatedURLs").val() + data[i].value + "\n");
			        }
			    } 
    		});
	}

	function generateWebCode() {
		var surveyURL = fix_http($('#externalSurveyURL').val());
		var groupName = $('#groupName').val();
		var copyright = "<!-- Copyright (c) 2012 Adam Darlow and Gideon Goldin. For more info, see http://gideongoldin.github.com/TurkGate/ -->\n"; 
		
		var htmlCode = <?php echo json_encode(file_get_contents('resources/WebHIT/webTemplate.html')); ?>;
		htmlCode = htmlCode.replace('[[[Survey URL]]]', surveyURL);
		htmlCode = htmlCode.replace('[[[Group Name]]]', groupName);
		htmlCode = htmlCode.replace('[[[TurkGate URL]]]', "<?php echo constant('BASE_URL'); ?>");
		htmlCode = htmlCode.replace(/<!--[^>]*-->/, copyright);
		
		$('#generatedHTMLCode').val(htmlCode);
		$('#generatedContent').slideDown();
	}
	
	function endsWith(str, suffix) {
        return str.indexOf(suffix, str.length - suffix.length) !== -1;
    }
	
	function downloadCLTFile(fileName) {
		var surveyURL = fix_http($('#externalSurveyURL').val());
		var groupName = $('#groupName').val();

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
  
<!-- Import the footer -->
<?php require_once($basePath . 'includes/footer.php'); ?>
