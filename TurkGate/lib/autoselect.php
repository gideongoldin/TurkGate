<script type="text/javascript">
  $('#<?php echo $textAreaId ?>').focus(function() {
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
  });
</script>
