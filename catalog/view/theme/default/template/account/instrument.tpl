<?php echo $header; ?>

<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($instruments) { ?>
      <!--category-content-->
      <form id="borrow-form">
      <div class="page-frame">
        <div class="row">
          <div class="block">
            <div class="instrument-category" id="instrument-category">
              <div class="category-container">
                <div class="row">
                  <div class="col-md-4">
                    <div class="btn-group hidden-xs">
                      <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
                      <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
                    </div>
                  </div>
                  <div class="col-md-1 text-right">
                    <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
                  </div>
                  <div class="col-md-2 text-right">
                    <select id="input-limit" class="form-control" onchange="location = this.value;">
                      <?php foreach ($limits as $limits) { ?>
                      <?php if ($limits['value'] == $limit) { ?>
                      <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <br />
                <div class="row">
                  <?php foreach ($instruments as $instrument) { ?>
                  <div class="instrument-layout instrument-grid col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <?php eval('?>' . $instrument_item . '<?php '); ?>
                  </div>
                  <?php } ?>
                </div>
                <div class="row">
                  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </form>
      <hr />
      <button id="borrow" class="btn"><?php echo $button_order; ?></button>
      <!--end-category-content-->
      <?php } ?>
      <?php if (!$instruments) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript">
$(document).delegate('#borrow', 'click', function(event) {
	$.ajax({
		url: 'index.php?route=account/instrument/borrow',
		data: $('#borrow-form').serialize(),
		dataType: 'json',
		type: 'post',
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['success']) {
				$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
			
			if (json['error']) {
				$('#content').parent().before('<div class="alert alert-danger text-danger"><i class="fa fa-check-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('.instrument-thumb', 'click', function(event) {
	if(event.target.nodeName !== "INPUT"){
		$(this).find('input').click();
	}
});
</script>
<?php echo $footer; ?> 