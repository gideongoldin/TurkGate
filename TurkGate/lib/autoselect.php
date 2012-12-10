<script type="text/javascript">
  $(document).ready(function(){ 
	
	var mousedDownInsideTextArea = false;
	   
    selectAllFunction = function() {
      $('#<?php echo $textAreaId ?>').select();
    
      window.setTimeout(function() {
        $('#<?php echo $textAreaId ?>').select();
      }, 1);

    };
	
	checkMousedDown = function() {
		if (mousedDownInsideTextArea) {
			mousedDownInsideTextArea = false;
			selectAllFunction();
		}
    };

	<?php
		if ($keepAllSelected) {
		    echo "$('#$textAreaId').mousedown(function() { mousedDownInsideTextArea = true; });";		
		    echo "$('html').mouseup(checkMousedDown);";
		    echo "$('#$textAreaId').mouseup(selectAllFunction);";
		} else {
		    echo "$('#$textAreaId').focus(selectAllFunction);";			
		}
	?>
  });
</script>
