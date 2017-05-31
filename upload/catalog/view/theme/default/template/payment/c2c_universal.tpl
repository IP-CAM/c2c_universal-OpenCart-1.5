<!-- Start C2c_universal module -->
<?php if ($c2c_universal_instruction_attach){ ?>
<h2><?php echo $text_instruction; ?></h2>
<div class="content">
  <p><?php echo $c2c_universal_instruction; ?></p>
</div>
<?php } ?>
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
  $.ajax({ 
    type: 'get',
    url: 'index.php?route=payment/c2c_universal/confirm',
    success: function() {
      location = '<?php echo $continue; ?>'
    }
  });
});
//--></script>
<!-- End C2c_universal module -->