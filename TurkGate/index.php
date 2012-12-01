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
    $title = 'TurkGate Central';
    $description = 'TurkGate tools for researchers.';
    $basePath = '';
    require_once($basePath . 'includes/header.php'); 
?>
<script src="lib/fixhttp.lib.js"></script>
<script type="text/javascript">

	function generateWebCode() {
		var surveyURL = fix_http($('#externalSurveyURL').val());
		var groupName = $('#groupName').val();
		var copyright = "<!-- Copyright (c) 2012 Adam Darlow and Gideon Goldin. For more info, see http://gideongoldin.github.com/TurkGate/ -->\n"; 
		
		var htmlCode = <?php echo json_encode(file_get_contents('resources/WebHIT/webTemplate.html')); ?>;
		htmlCode = htmlCode.replace('[[[Survey URL]]]', surveyURL);
		htmlCode = htmlCode.replace('[[[Group Name]]]', groupName);
		htmlCode = htmlCode.replace('[[[TurkGate URL]]]', "<?php echo constant('BASE_URL'); ?>");
		htmlCode = htmlCode.replace('/<!--[^>]*-->/', copyright);
		
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
	
	$(document).ready(function(){    
		

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
        
        $('#generatedContent').slideUp();
        
        // submitting the form calls whatever function has been set
        $('#hitGenerationForm').submit(function () { 
          var subFunc = $('#hitGenerationForm').data("submitFunction");
          if (typeof subFunc === 'function') {
          	subFunc();
          }
          $('#hitGenerationForm').removeData("submitFunction");      
            	
          return false; 
        });
    });
	
</script>
		
<div class="sixteen columns">
  <header>
	<h1 class="remove-bottom"><a href="index.php">TurkGate</a></h1>
  </header>
</div>		

	<div class="sixteen columns clearfix" style="border-top: 1px solid #ccc; padding-top:10px;"> <!-- sixteen columns clearfix -->
		<form method="post" id="hitGenerationForm" name="hitGenerationForm">
		<h3>Generate a HIT</h3>
		<div class="six columns alpha">
			<p>
				From here you may generate the HTML code for your Web Interface HIT, or download files for use with the Command Line Tool.
			</p>
			<p>
				Please specify a survey URL and group name:
			</p>
			<p>
				<label for="externalSurveyURL">*External Survey URL:</label> <input type="text" name="externalSurveyURL" id="externalSurveyURL" value='' size="40" placeholder="http://surveysite.com/surveyid" autofocus="" required="">
			</p>
			<p>
				<label for="groupName">*Group Name:</label> <input type="text" name="groupName" id="groupName" value='' size="40" placeholder="Test group name" required="">
			</p>
		</div>
		
		<div class="ten columns omega"> <!-- Ten columns omega -->
			<div> <!-- Tabs -->
				
				<!-- Tab headers -->
				<ul class="tabs">
					<li class="active" rel="tab1">
						Web Interface
					</li>
					<li rel="tab2">
						Command Line Tools
					</li>
				</ul>
				
				<div class="tab_container">
					<div id="tab1" class="tab_content">
						<p>
							Generate the HTML code to paste into your HIT. Full instructions are on the <a href="http://gideongoldin.github.com/TurkGate/" target="blank">TurkGate Wiki</a>.
						</p>
						
						<input type="submit" name="generateHTMLCode" id="generateHTMLCode" value="Generate HTML code">
						
						<?php
								$textAreaId = 'generatedHTMLCode';								
								require_once 'lib/autoselect.php';
                        ?>								
						<div id="generatedContent">
							<em><small>Copy and paste the code below into the source code for your HIT:</em></small>
							<textarea rows="8" style="height:80;" id="<?php echo $textAreaId; ?>"></textarea>
						</div>
					</div>
				
					<div id="tab2" class="tab_content">
						<p>
							Download the files for creating your HIT. Full instructions are on the <a href="https://github.com/gideongoldin/TurkGate/wiki/Command-Line-Tools" target="blank">TurkGate Wiki</a>.
						</p>
						<em><small>Download:</small></em>
						
						  <input type="submit" name="downloadCLTFile" id="downloadCLTInputFile" value="survey.input"> 
  						  <input type="submit" name="downloadCLTFile" id="downloadCLTPropertiesFile" value="survey.properties"> 
						  <input type="submit" name="downloadCLTFile" id="downloadCLTQuestionFile" value="survey.question">
					</div>
				</div>
			</div> <!-- Tabs -->
		</div> <!-- Ten columns omega -->
		</form>
	</div> <!-- sixteen columns clearfix -->

	
<div class="sixteen columns" style="border-top: 1px solid #ccc; padding-top:10px;">	
<h3>
	Completion Codes
</h3>
<div class="sixteen columns" style="padding-top:5px; margin-left: 0;">	
<p class="new-section">
	<span class="section-begin">Provide completion codes</span> to your participants at the end of your survey by sending them to one of the following URLs:
</p>
</div>
	<?php
	    $numDigits = 6;
		$generateCodeLink = constant('BASE_URL') . '/codes/generate.php?stamp=' . mt_rand(pow(10, $numDigits), pow(10, $numDigits + 1) - 1);	
	?>
	
	<div class="fourteen columns offset-by-one" style="padding-top:5px;">
		<dl>
			<dt>For Qualtrics:</dt>
			<dd><a href="#"><?php echo $generateCodeLink ?>&responseID=${e://Field/ResponseID}</a></dd>
				
			<dt>For LimeSurvey:</dt>
			<dd><a href="#"><?php echo $generateCodeLink ?>&user={SAVEDID}&survey={SID}</a></dd>	
				
			<dt>For other sites:</dt>
			<dd><a href="#"><?php echo $generateCodeLink ?></a></dd>
		</dl>
	</div>
	<div class="sixteen columns" style="padding-top:5px; margin-left: 0;">	

<p class="new-section">
	<span class="section-begin">Verify completion codes</span> at <a href="codes/verify.php">this page</a>.
</p>
</div>
</div>

<!-- Custom jQuery actions -->
<script type="text/javascript">
	$(document).ready(function() {
		$(".tab_content").hide();
		$(".tab_content:first").show(); 

		$("ul.tabs li").click(function() {
			$("ul.tabs li").removeClass("active");
			$(this).addClass("active");
			$(".tab_content").hide();
			var activeTab = $(this).attr("rel"); 
			$("#"+activeTab).show(); 
		});
	});
</script> 
  
<!-- Import the footer -->
<?php require_once($basePath . 'includes/footer.php'); ?>
