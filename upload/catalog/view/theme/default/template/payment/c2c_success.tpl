<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<style media="screen">
  #tenform {
    margin-top: <?php echo $iframe['margin-top']; ?>px;
    height: <?php echo $iframe['height']; ?>px;
    width: 100%;
    overflow: hidden;
  }
  @media only screen and (max-device-width: 480px) {
    #tenform {
      margin-top: -264px;
      height: 1094px;
      width: 100%;
    }}
  </style>
  <div class="container">
  <div id="content"><?php echo $content_top; ?>
    <h1><?php echo $heading_title; ?></h1>
    <p><?php echo $defaulttext; ?></p>
    <?php echo $comment; ?>
    <p><div style="overflow: hidden;"><iframe id="tenform" src="<?php echo $iframe['url']; ?>" scrolling="no" align="center" frameborder="no">
      Ваш браузер не поддерживает плавающие фреймы!
    </iframe></div></p>
    <div style="text-align:center;">
      <form id="form" action="<?php echo $action_url; ?>" method="get" >
      <?php echo $text_4number_label; ?>&nbsp
      <input type="text" placeholder="xxxx" pattern="[0-9]{4}" id="card" name="d4num" title="" required />
      <input type="hidden" name="code" value="<?php echo $secure_code; ?>">
      <input type="hidden" name="order" value="<?php echo $order; ?>">
      <input type="hidden" name="route" value="payment/c2c_universal/payed">
      <input class="button card4numbutton" type="submit" value="<?php echo $text_4number_button; ?>">
      </form>;
    </div>
    <!--<?php echo $comment_after; ?>
    <?php echo $text_message; ?>-->
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
    </div>
    <?php echo $footer; ?>