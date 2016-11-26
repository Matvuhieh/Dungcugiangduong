<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-slideshow" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-slideshow" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
            <div class="col-sm-10">
              <input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
              <?php if ($error_width) { ?>
              <div class="text-danger"><?php echo $error_width; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-height"><?php echo $entry_height; ?></label>
            <div class="col-sm-10">
              <input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
              <?php if ($error_height) { ?>
              <div class="text-danger"><?php echo $error_height; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-12" for="input-banner"><?php echo $entry_banner; ?></label>
            <div id="slideshow-builder" class="col-sm-12">
            <?php $banner_row = 0; ?>
            <?php foreach($banners as $banner) { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status">
                  <a class="btn btn-danger" onclick="$(this).parent().parent().remove();" style="padding: 2px 5px;" data-toggle="tooltip" data-original-title="<?php echo $button_remove; ?>"><i class="fa fa-minus-circle"></i></a>
                  <?php echo $entry_banner.' '.$banner_row; ?>
                </label>
                <div class="col-sm-10">
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                    <div class="col-sm-10">
                      <a href="" id="thumb-image-<?php echo $banner_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $banner['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                      <input type="hidden" name="banners[<?php echo $banner_row; ?>][image]" value="<?php echo $banner['image']; ?>" placeholder="<?php echo $entry_image; ?>" class="form-control" id="input-image-<?php echo $banner_row; ?>"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-link"><?php echo $entry_link; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="banners[<?php echo $banner_row; ?>][link]" value="<?php echo $banner['link']; ?>" placeholder="<?php echo $entry_link; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-video"><?php echo $entry_video; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="banners[<?php echo $banner_row; ?>][video]" value="<?php echo $banner['video']; ?>" placeholder="<?php echo $entry_video; ?>" class="form-control" />
                    </div>
                  </div>
                  <ul class="nav nav-tabs language">
                    <?php foreach ($languages as $language) { ?>
                      <?php $language_id = $language['language_id']; ?>
                      <li><a href="#language-<?php echo $banner_row; ?>-<?php echo $language_id; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                    <?php } ?>
                  </ul>
                  <div class="tab-content">
                  <?php foreach ($languages as $language) { ?>
                    <?php $language_id = $language['language_id']; ?>
                      <div class="tab-pane" id="language-<?php echo $banner_row; ?>-<?php echo $language_id; ?>">
                        <textarea name="banners[<?php echo $banner_row; ?>][html][<?php echo $language_id; ?>]" class="form-control" id="input-banner-html-<?php echo $banner_row; ?>"><?php echo $banner['html'][$language_id]; ?></textarea>
                      </div>
                  <?php } ?>
                  </div>
                </div>
              </div>
              <?php $banner_row++; ?>
            <?php } ?>
            </div>
            <label class="col-sm-12 control-label"><a onClick="addSlideshow();" data-toggle="tooltip" title="" class="btn btn-success" data-original-title="<?php echo $button_add; ?>"><i class="fa fa-plus-circle"></i></a></label>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
var banner_row = <?php echo $banner_row; ?>;

function addSlideshow(){
	var html = '';
	
	html += '<div class="form-group">';
	  html += '<label class="col-sm-2 control-label" for="input-status">';
	    html += '<a class="btn btn-danger" onclick="$(this).parent().parent().remove();" style="padding: 2px 5px;" data-toggle="tooltip" data-original-title="<?php echo $button_remove; ?>"><i class="fa fa-minus-circle"></i></a>';
	    html += '<?php echo $entry_banner; ?> '+banner_row+'';
	  html += '</label>';
	  html += '<div class="col-sm-10">';
		html += '<div class="form-group">';
		  html += '<label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>';
		  html += '<div class="col-sm-10">';
			html += '<a href="" id="thumb-image-'+banner_row+'" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>';
			html += '<input type="hidden" name="banners['+banner_row+'][image]" value="" placeholder="<?php echo $entry_image; ?>" class="form-control" id="input-image-'+banner_row+'"/>';
		  html += '</div>';
		html += '</div>';
		
		html += '<div class="form-group">';
		  html += '<label class="col-sm-2 control-label" for="input-link"><?php echo $entry_link; ?></label>';
		  html += '<div class="col-sm-10">';
			html += '<input type="text" name="banners['+banner_row+'][link]" value="" placeholder="<?php echo $entry_link; ?>" class="form-control" />';
		  html += '</div>';
		html += '</div>';
		
		html += '<div class="form-group">';
		  html += '<label class="col-sm-2 control-label" for="input-video"><?php echo $entry_video; ?></label>';
		  html += '<div class="col-sm-10">';
			html += '<input type="text" name="banners['+banner_row+'][video]" value="" placeholder="<?php echo $entry_video; ?>" class="form-control" />';
		  html += '</div>';
		html += '</div>';
		
		html += '<ul class="nav nav-tabs language">';
		<?php foreach ($languages as $language) { ?>
		  html += '<li><a href="#language-'+banner_row+'-<?php echo $language["language_id"]; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language["image"]; ?>" title="<?php echo $language["name"]; ?>" /> <?php echo $language["name"]; ?></a></li>';
		<?php } ?>
		html += '</ul>';
		html += '<div class="tab-content">';
		  <?php foreach ($languages as $language) { ?>
		  html += '<div class="tab-pane" id="language-'+banner_row+'-<?php echo $language["language_id"]; ?>">';
		    html += '<textarea name="banners['+banner_row+'][html][<?php echo $language["language_id"]; ?>]" class="form-control" id="input-banner-html-'+banner_row+'"></textarea>';
		  html += '</div>';
		  <?php } ?>
		html += '</div>';
		
	  html += '</div>';
	html += '</div>';
	
	$('#slideshow-builder').append(html);
	$('#input-banner-html-'+banner_row).summernote({ height: 150 });
	$('.language li:first-child a').tab('show');
	banner_row++;
}
</script>
<script type="text/javascript">
$('.language li:first-child a').tab('show');

$('#slideshow-builder textarea').summernote({
	height: 150
});
</script>
<?php echo $footer; ?>