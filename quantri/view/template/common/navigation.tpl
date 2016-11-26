<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12 navigation-title"><?php echo $text_header; ?></div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $logo; ?>"><img src="view/image/icon/logo.png" class="img-responsive"/><?php echo $text_logo; ?></a> </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $favicon; ?>"><img src="view/image/icon/favicon.png" class="img-responsive"/><?php echo $text_favicon; ?></a> </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $megamenu; ?>"><img src="view/image/icon/menu.png" class="img-responsive"/><?php echo $text_megamenu; ?></a> </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $navigation; ?>"><img src="view/image/icon/navigation.png" class="img-responsive"/><?php echo $text_navigation; ?></a> </div>
    </div>
    <div class="row">
      <div class="col-sm-12 navigation-title"><?php echo $text_body; ?></div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $slide; ?>"><img src="view/image/icon/slide.png" class="img-responsive"/><?php echo $text_slide; ?></a> </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $news; ?>"><img src="view/image/icon/information.png" class="img-responsive"/><?php echo $text_news; ?></a> </div>
    </div>
    <div class="row">
      <div class="col-sm-12 navigation-title"><?php echo $text_footer; ?></div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $footer_post; ?>"><img src="view/image/icon/post.png" class="img-responsive"/><?php echo $text_footer_post; ?></a> </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $footer_social; ?>"><img src="view/image/icon/social.png" class="img-responsive"/><?php echo $text_social; ?></a> </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $footer_address; ?>"><img src="view/image/icon/address.png" class="img-responsive"/><?php echo $text_footer_address; ?></a> </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $footer_map; ?>"><img src="view/image/icon/map.png" class="img-responsive"/><?php echo $text_footer_map; ?></a> </div>
    </div>
  </div>
</div>
<script type="text/javascript">
(function($){
	$.fn.equalHeights=function(minHeight,maxHeight){
		this.height('auto');
		
		tallest = (minHeight)?minHeight:0;
		this.each(function(){
			if($(this).height()>tallest){
				tallest = $(this).height()
			}
		});
		
		if((maxHeight)&&tallest>maxHeight) tallest = maxHeight;
		
		return this.each(function(){
			$(this).height(tallest)
		}
	)}
})(jQuery)

$(window).load(function(){
	if($(".navigation-link").length){
		$(".navigation-link").equalHeights();
	}
	
	$( window ).resize(function() {
		$(".navigation-link").equalHeights();
	});
});
</script>
<?php echo $footer; ?>