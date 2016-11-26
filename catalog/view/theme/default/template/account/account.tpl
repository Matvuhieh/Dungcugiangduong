<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="col-sm-3">
        <h2><?php echo $text_my_account; ?></h2>
        <ul class="list-unstyled">
          <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
          <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
          <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
        </ul>
        <h2><?php echo $text_my_orders; ?></h2>
        <ul class="list-unstyled">
          <li><a href="<?php echo $instrument; ?>"><?php echo $text_instrument; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
        </ul>
        <h2><?php echo $text_my_newsletter; ?></h2>
        <ul class="list-unstyled">
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-9">
        <h2>Thông báo từ quản trị</h2>
        <div class="content">
          <?php foreach ($user_historys as $user_history) { ?>
            <div class="well"><?php echo $user_history['comment']; ?></div>
          <?php } ?>
        </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>