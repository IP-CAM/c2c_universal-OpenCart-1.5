<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<style media="screen">
  #tenform {
    margin-top: -193px;
    height: 757px;
    width: 100%;
  }
  @media only screen and (max-device-width: 480px) {
    #tenform {
      margin-top: -264px;
      height: 1094px;
      width: 100%;
    }}
  </style>
  <div id="content"><?php echo $content_top; ?>
    <h1><?php echo $heading_title; ?></h1>
    <p><?php echo $defaulttext; ?></p>
    <?php echo $comment; ?>
    <p><div style="overflow: hidden;"><iframe id="tenform" src="<?php echo $iframe; ?>" scrolling="no" align="center" frameborder="no">
      Ваш браузер не поддерживает плавающие фреймы!
    </iframe></div></p>
    <div style="text-align:center;"><?php echo $defaulttext2; ?></div>
    <?php echo $comment_after; ?>
    <?php echo $text_message; ?>
    <script>
      $("form").submit(function(e) {

        var ref = $(this).find("[required]");

        $(ref).each(function(){
          if ( $(this).val() == '' )
          {
            alert("Введите 4 последние цифры карты!");

            $(this).focus();

            e.preventDefault();
            return false;
          }
        });  return true;
      });
    </script>
    <?php echo $content_bottom; ?></div>
    <?php echo $footer; ?>