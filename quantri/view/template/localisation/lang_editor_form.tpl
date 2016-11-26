<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <div class="pull-right"> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> </div>
    <h1><?php echo $heading_title; ?></h1>
    <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
</div>
<div class="container-fluid">
  <?php if (isset($error['error_warning'])) { ?>
  <div class="alert alert-danger"> <i class="fa fa-exclamation-circle"></i> <?php echo $error['error_warning']; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default col-sm-12">
    <div class="row">
      <div class="tree col-md-2 col-sm-3">
        <input id="search" name="search" class="form-control" placeholder="<?php echo $entry_search; ?>" />
        <div id="search-result"></div>
        <ul>
          <?php foreach($tree_path as $key => $path) { ?>
            <?php if($path) { ?>
              <?php if(is_array($path)) { ?>
              <li> <span><i class="icon-calendar"></i><?php echo $key; ?></span>
                <ul>
                  <?php foreach($path as $files) { ?>
                  <li><a class="get-language-form" ref="<?php echo $key.'/'.$files; ?>"><?php echo basename($files, '.php'); ?></a></li>
                  <?php } ?>
                </ul>
              </li>
              <?php }else{ ?>
              <li><a class="get-language-form" ref="<?php echo $path; ?>"><?php echo basename($path, '.php'); ?></a></li>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </ul>
      </div>
      <div id="language-form" class="col-md-10 col-sm-9"> </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$(document).delegate('.button-add-var', 'click', function(){
		$(this).parent().prev().append("<tr><td><div class=\"remove-var\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></div><span>text_</span><br /><input type=\"text\" class=\"new_value\" value=\"text_\" /></td><?php foreach ($selected as $lang){ ?><td><textarea name=\"<?php echo 'data[' . $lang . '][-]'; ?>\" rows=\"3\" class=\"<?php echo $lang; ?>\"></textarea></td><?php } ?></tr>");
	});
	
	$(document).delegate('.remove-var', 'click', function(){
		$(this).parent().parent().remove();
	});
	
	$(document).delegate('.new_value', 'keyup', function(){
		//alert($(this).val());
		$(this).prevAll('span').html($(this).val());
		<?php foreach ($selected as $lang){ ?>
		$(this).parent().nextAll().children('.<?php echo $lang; ?>').attr('name', 'data[<?php echo $lang; ?>][' + $(this).val() + ']');
		<?php } ?>
	});
	
	$(document).delegate('.button-save-lang', 'click', function(){
		var now = new Date();
		var hours = now.getHours();
		var minutes = now.getMinutes();
		var seconds = now.getSeconds();
		var timeValue = '<?php echo $entry_saved; ?>: ' + ((hours < 10) ? "0" : "") + hours;
		timeValue += ((minutes < 10) ? ":0" : ":") + minutes;
		timeValue += ((seconds < 10) ? ":0" : ":") + seconds;

		
			var save_time = $(this).next('.save_time');
			$.ajax({
				type: 'POST',
				url: $(this).parents('form').attr('action'),
				data: $(this).parents('form').serialize(),
				beforeSend: function() {
					save_time.show();
					save_time.html('<?php echo $entry_saving; ?>');
				},
				success: function(msg) {
					if(msg == 'ok') {
						save_time.fadeOut(500,function(){
							save_time.html(timeValue);
							save_time.fadeIn(500);
						});
					} else if(msg == 'error') {
					alert(msg);
					} else {
					window.location.href = '<?php echo $redirectAmp?>';
					}
				}
			});
	});

	
	$(document).delegate('.button-save-lang-one', 'click', function(){
			$.ajax({
				type: 'POST',
				url: '<?php echo $actionOneFile; ?>',
				data: {data: $(this).parent().children('div').children('textarea').val(), file: $(this).attr('data-url'), type: '<?php echo $type; ?>' },
				success: function(msg) {
					if(msg == 'ok') {
						alert('<?php echo $entry_saved; ?>');
						window.location.href = '<?php echo $redirectAmp?>';
					} else if(msg == 'error') {
					alert(msg);
					} else {
					alert(msg);
					window.location.href = '<?php echo $redirectAmp?>';
					}
				}
			});
	});
	
	$(document).delegate('.filelist', 'click', function(){
		$(this).parent().next().toggle();
		if($(this).hasClass('btn-success')){
			$(this).removeClass('btn-success').addClass('btn-danger');
			$(this).children('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
		}else{
			$(this).removeClass('btn-danger').addClass('btn-success');
			$(this).children('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
		}
	});
	
	$(document).delegate('.get-language-form', 'click', function(event){
		event.preventDefault();
		
		var path = $(this).attr('ref');
		
		$.ajax({
			url: '<?php echo $getForm; ?>',
			type: 'post',
			data: {
				path: path
			},
			dataType: "html",
			success: function(html){
				$('#language-form').html(html);
				$('.buttons-right').fixer({
					gap: 20,
					gapRevers: 40
				});
			},
			
		});
	});
	
	$(document).delegate('input[name="search"]', 'keyup', function(event){
		var key = event.which || event.keyCode;
		var value = $(this).val();
		
		if(key == 13){
			$.ajax({
				url: '<?php echo $search; ?>',
				type: 'post',
				data: {
					keyword: value
				},
				dataType: "html",
				beforeSend: function(){
					$('#search-result').html('<img src="view/image/loading-2.gif" width="100%" />');
				},
				success: function(html){
					$('#search-result').html(html);
				},
				
			});
		}
	});
});

(function( $ ){
		$.fn.toggler = function( fn, fn2 ) {
		var args = arguments,guid = fn.guid || $.guid++,i=0,
		toggler = function( event ) {
			var lastToggle = ( $._data( this, "lastToggle" + fn.guid ) || 0 ) % i;
			$._data( this, "lastToggle" + fn.guid, lastToggle + 1 );
			event.preventDefault();
			return args[ lastToggle ].apply( this, arguments ) || false;
		};
		toggler.guid = guid;
		while ( i < args.length ) {
			args[ i++ ].guid = guid;
		}
		return this.click( toggler );
		};
})( jQuery );

$(function () {
	$('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
	$('.tree li.parent_li > span').on('click', function (e) {
		var children = $(this).parent('li.parent_li').find(' > ul > li');
		if (children.is(":visible")) {
			children.hide('fast');
			$(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
		} else {
			children.show('fast');
			$(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
		}
		e.stopPropagation();
	});
});
</script> 
