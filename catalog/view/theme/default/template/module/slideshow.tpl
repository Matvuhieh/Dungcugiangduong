<div id="slideshow-<?php echo $module; ?>" class="camera_wrap camera_emboss camera_white_skin" style="max-width: <?php echo $width; ?>px;">
  <?php foreach ($banners as $banner) { ?>
    <?php if($banner['video']){ ?>
      <div data-thumb="<?php echo $banner['thumb']; ?>" data-src="<?php echo $banner['image']; ?>"  data-portrait="true">
        <?php echo $banner['video']; ?>
        <?php echo $banner['html']; ?>
      </div>
    <?php }else{ ?>
      <div data-thumb="<?php echo $banner['thumb']; ?>" data-src="<?php echo $banner['image']; ?>" data-link="<?php echo $banner['link']; ?>" data-target="_blank" data-portrait="true">
        <?php echo $banner['html']; ?>
      </div>
    <?php } ?>
  <?php } ?>
</div>
<script type="text/javascript"><!--
jQuery(function(){
	
	jQuery('#slideshow-<?php echo $module; ?>').camera({
		height: '40%',
		pagination: false,
		thumbnails: false,
		imagePath: '<?php echo $path; ?>/images/'
	});

});
--></script>