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
            <td><?php echo $text_select_bank; ?></td>
            <td>
              <select name="c2c_universal_active_bank">
                <?php foreach ($banks as $bank) { ?>
                <option value="<?php echo $bank['code']; ?>" 
                  <?php 
                  if ($c2c_universal_active_bank == $bank['code']) { 
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
          <td><input type="text" name="c2c_universal_card" value="<?php echo $c2c_universal_card; ?>"></td>
        </tr>
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="c2c_universal_wait_order_status_id">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $c2c_universal_wait_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_valid_order_status; ?></td>
          <td><select name="c2c_universal_payed_order_status_id">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $c2c_universal_payed_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="c2c_universal_status">
            <?php if ($c2c_universal_status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select></td>
        </tr>
      </table>
    </form>
  </div>
</div>

<?php echo $footer; ?>