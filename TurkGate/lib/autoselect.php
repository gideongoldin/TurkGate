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

    selectSomeFunction = function() {
    	// Iff nothing is selected, select all
    	if(!isTextSelected()){
      		selectAllFunction();
   		}
    };

    isTextSelected = function(){
      var input = $('#<?php echo $textAreaId ?>')[0];
	  var startPos = input.selectionStart;
	  var endPos = input.selectionEnd;
	  var doc = document.selection;
	  
	  if (doc && doc.createRange().text.length != 0){
	    return true;
	  } else if (!doc && input.value.substring(startPos,endPos).length != 0){
	    return true;
	  }
   
      return false;
	}

	<?php
		if ($keepAllSelected) {
		    echo "$('#$textAreaId').mousedown(function() { mousedDownInsideTextArea = true; });";		
		    echo "$('html').mouseup(checkMousedDown);";
		    echo "$('#$textAreaId').mouseup(selectAllFunction);";
		} else {
		    echo "$('#$textAreaId').mouseup(selectSomeFunction);";
		}
	?>

  });
</script>
