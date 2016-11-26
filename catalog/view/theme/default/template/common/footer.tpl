</div>
<!--footer-->
<?php if (in_array($layout_id,array(6,10,3,7,12,8,4,1,11,5,2,13,9,14))) { ?>
<div class="ui-sortable-handle page-frame"><div class="row"><div class="block"><footer class="footer" id="footer">
  <div class="container">
    <div class="row">
      <?php if ($informations) { ?>
      <div class="col-sm-3">
        <h3><?php echo $text_information; ?></h3>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="col-sm-3">
        <h3><?php echo $text_service; ?></h3>
        <ul class="list-unstyled">
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h3><?php echo $text_account; ?></h3>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
        </ul>
      </div>
    </div>
    <hr>
    <p><?php echo $powered; ?></p>
  </div>
</footer></div></div></div>
<?php } ?>
<!--end-footer-->
<?php if($footer_bottom){ ?>
<div class="container"> <?php echo $footer_bottom; ?> </div>
<?php } ?>
<?php if ($html_debug) { ?>
<script type="text/javascript" src="catalog/view/javascript/html-debug/firebug-lite-debug.js"></script>
<?php } ?>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->

<!-- Theme created by Welford Media for OpenCart 2.0 www.welfordmedia.co.uk -->

</body></html>