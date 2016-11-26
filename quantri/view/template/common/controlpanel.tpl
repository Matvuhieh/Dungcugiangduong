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
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $category; ?>"><img src="view/image/icon/category.png" class="img-responsive"/><?php echo $text_category; ?></a> </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $instrument; ?>"><img src="view/image/icon/product.png" class="img-responsive"/><?php echo $text_instrument; ?></a> </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $order; ?>"><img src="view/image/icon/order.png" class="img-responsive"/><?php echo $text_order; ?></a>
      </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a data-toggle="dropdown" href="javascript:void(0)"><img src="view/image/icon/user.png" class="img-responsive"/><?php echo $text_user; ?></a>
        <ul class="dropdown-menu col-sm-12">
          <li><a href="<?php echo $user1; ?>"><i class="fa fa-user fa-fw"></i><?php echo $text_user; ?></a></li>
          <li><a href="<?php echo $user_group; ?>"><i class="fa fa-users" aria-hidden="true"></i><?php echo $text_user_group; ?></a></li>
          <li><a href="<?php echo $custom_field; ?>"><?php echo $text_custom_field; ?></a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a href="<?php echo $information; ?>"><img src="view/image/icon/information.png" class="img-responsive"/><?php echo $text_information; ?></a> </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link">
        <a href="<?php echo $location; ?>"><img src="view/image/icon/address.png" class="img-responsive"/><?php echo $text_location; ?></a>
      </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a data-toggle="dropdown" href="javascript:void(0)"><img src="view/image/icon/language.png" class="img-responsive"/><?php echo $text_language; ?></a>
        <ul class="dropdown-menu col-sm-12">
          <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
          <li><a href="<?php echo $language_editor; ?>"><?php echo $text_language_editor; ?></a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a data-toggle="dropdown" href="javascript:void(0)"><img src="view/image/icon/user.png" class="img-responsive"/><?php echo $text_staff; ?></a>
        <ul class="dropdown-menu col-sm-12">
          <li><a href="<?php echo $staff; ?>"><i class="fa fa-user fa-fw"></i><?php echo $text_staff; ?></a></li>
          <li><a href="<?php echo $staff_group; ?>"><i class="fa fa-users" aria-hidden="true"></i><?php echo $text_staff_group; ?></a></li>
          <li><a href="<?php echo $api; ?>"><?php echo $text_api; ?></a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a data-toggle="dropdown" href="javascript:void(0)"><img src="view/image/icon/system.png" class="img-responsive"/><?php echo $text_system; ?></a>
        <ul class="dropdown-menu col-sm-12">
          <li><a href="<?php echo $setting; ?>"><i class="fa fa-cog fa-fw"></i><?php echo $text_setting; ?></a></li>
          <li><a href="<?php echo $length_class; ?>"><?php echo $text_length_class; ?></a></li>
          <li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>

        </ul>
      </div>
      <div class="col-lg-2 col-fm-2 col-md-2 col-sm-2 col-fm-3 col-md-3 col-sm-3 col-xs-4 navigation-link"> <a data-toggle="dropdown" href="javascript:void(0)"><img src="view/image/icon/tool.png" class="img-responsive"/><?php echo $text_tools; ?></a>
        <ul class="dropdown-menu col-sm-12">
          <li><a href="<?php echo $upload; ?>"><?php echo $text_upload; ?></a></li>
          <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
          <li><a href="<?php echo $folder_protect; ?>"><?php echo $text_folder_protect; ?></a></li>
          <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
        </ul>
      </div>
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