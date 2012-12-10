<script type="text/javascript">
  $(document).ready(function(){ 
	   
    selectAllFunction = function() {
      $this = $(this);
    
      $this.select();
    
      window.setTimeout(function() {
        $this.select();
      }, 1);

      // Work around WebKit's little problem
      $this.mouseup(function() {
        // Prevent further mouseup intervention
        $this.unbind("mouseup");
        return false;
      });
    };

	<?php
		if ($keepAllSelected) {
		    echo "$('#$textAreaId').click(selectAllFunction);";		
			// Handle selections that start outside the element
		    echo "$('#$textAreaId').mouseup(selectAllFunction);";			
		} else {
		    echo "$('#$textAreaId').focus(selectAllFunction);";			
		}
	?>
  });
</script>
