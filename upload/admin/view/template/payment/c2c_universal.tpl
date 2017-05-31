<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php $text_select_bank; ?></td>
          <td>
            <select name="bank">
              <?php foreach ($banks as $bank) { ?>
                <option value="<?php echo $bank['code']; ?>" 
                <?php 
                  if ($active_bank_code == $bank['code']) { 
                    echo "selected"; 
                  } 
                ?>
                >
                <?php echo $bank['name']; ?>
                </option>
              <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td><?php echo $text_card_number; ?></td>
          <td><input type="text" name="card_number"></td>
        </tr>
      </table>
    </form>
  </div>
</div>

<?php echo $footer; ?>