<h1 class="title"><span><?php echo $heading_title; ?></span></h1>
<div class="row">
  <?php foreach ($informations as $information) { ?>
  <div class="caption">
    <h2><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></h2>
    <p><?php echo $information['description']; ?></p>
  </div>
  <?php } ?>
</div>
