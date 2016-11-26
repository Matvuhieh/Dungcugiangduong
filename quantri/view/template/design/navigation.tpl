<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"> <a onclick="javascript : megamenuSubmit();" class="btn btn-primary"><?php echo $button_save; ?></a> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-check-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (isset($success) and $success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i><?php echo $text_edit; ?></h3>
      </div>
      <!-- start menu -->
      <div id="menu-erea">
        <div class="left">
          <div class="data-block"><!-- category -->
            <div class="data-block custom-menu"><!-- category -->
              <div>
                <p>
                  <label><?php echo $entry_title; ?> </label>
                  <input class="form-control title" type="text" placeholder="<?php echo $entry_title; ?>"/>
                </p>
                <p>
                  <label><?php echo $entry_link; ?></label>
                  <input class="form-control url" type="text" placeholder="<?php echo $entry_link; ?>" />
                </p>
                <div>
                  <label><?php echo $entry_icon; ?></label>
                  <p class="input-group">
                    <input class="form-control icon" type="text" placeholder="<?php echo $entry_icon; ?>" />
                    <span class="input-group-addon"></span>
                  </p>
                </div>
              </div>
              <a class="add-to-menu_custom" href="javascript:void(0);"><?php echo $button_add_menu; ?></a> </div>
          </div>
        </div>
        <div class="right">
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <div class="dd" id="menu_area">
              <ol class='dd-list'>
                <?php $model->getMenuHtml(0); ?>
              </ol>
            </div>
            <input id="custom_data" type="hidden" name="custom_data" value="" />
          </form>
        </div>
      </div>
      <!-- end menu --> 
    </div>
  </div>
</div>
<script type="text/javascript">
function get_content_obj_custom(obj){
	var url=jQuery(obj).parent().find("input.url").val();
	var title=jQuery(obj).parent().find("input.title").val();
	var icon=jQuery(obj).parent().find("input.icon").val();
	var result='<li class="dd-item"><div class="dd-handle">'+
	'<div class="bar">'+
		'<span class="title">' + title + '</span>'+		
	'</div>' + 
	'</div><div class="info hide">'+			
				'<p class="input-item"><label><?php echo $entry_title; ?></label></p>'+
				<?php foreach($languages as $language) : ?>
				'<div class="input-group">'+
				'<input class="form-control" type="text" name="title[][<?php echo $language["language_id"]; ?>]" value="' + title + '"  />' +
				'<div class="input-group-addon"><img src="view/image/flags/<?php echo $language["image"]; ?>"/></div>'+
				'</div>'+
				<?php endforeach;?>
				'<p class="input-item"><label><?php echo $entry_link; ?></label><input class="form-control" type="text" name="url[]" value="' + url + '"/></p>'+
				'<div class="input-item"><label><?php echo $entry_icon; ?></label><p class="input-group"><input class="form-control icon" type="text" name="icon[]" value="' + icon + '"/><span class="input-group-addon"></span></p></div>'+
				'<p class="input-item"><a  href="javascript:void(0);" class="remove" onclick="remove_item(this);"><i class="fa fa-trash"></i> <?php echo $button_remove; ?></a></p>'+
				'<div class="hidden-data">' +
					'<input type="hidden" class="parent_id" name="parent_id[]" value=""/>'+
				'</div>'+
			'</div><a href="javascript:void(0);" class="explane" onclick="explane(this)">Explane</a></li>';
	return result;
}
</script> 
<script type="text/javascript">
jQuery('input.icon').iconpicker();
</script> 
<?php echo $footer; ?>