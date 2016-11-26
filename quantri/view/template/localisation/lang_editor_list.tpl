<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['error_warning']; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
          <table id="localisation" class="table table-striped table-bordered table-hover">
            <tr>
              <td align="center"><input type="radio" name="type" value="front" id="radio_front" checked="checked" />
                <h3 style="margin:0;">
                  <label for="radio_front"><?php echo $entry_frontend; ?></label>
                </h3></td>
              <td align="center"><input type="radio" name="type" value="back" id="radio_back" />
                <h3 style="margin:0;">
                  <label for="radio_back"><?php echo $entry_backend; ?></label>
                </h3></td>
            </tr>
          </table>
          <table id="localisation" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);checkBnt();" /></td>
                <td width="16"></td>
                <td class="text-left"><?php echo $entry_languages; ?></td>
                <td class="text-left"><?php echo $entry_code; ?></td>
                <td class="text-left"><?php echo $entry_directory; ?></td>
                <td class="text-left"><?php echo $entry_permissions; ?></td>
              </tr>
            </thead>
            <?php $language_row = 0; ?>
            <?php foreach ($languages as $language) { ?>
            <tbody id="language-row<?php echo $language_row; ?>">
              <tr>
                <td style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $language['directory']; ?>" /></td>
                <td><img src="view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" /></td>
                <td class="text-left"><?php echo $language['name']; ?></td>
                <td class="text-left"><?php echo $language['code']; ?></td>
                <td class="text-left">/<?php echo $language['directory']; ?>/</td>
                <td class="text-left"><?php echo $writable[$language['code']]['front']; ?><br />
                  <?php echo $writable[$language['code']]['back']; ?></td>
              </tr>
            </tbody>
            <?php $language_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td class="text-right" colspan="7"><a id="bnt_enab" style="display: none;" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                  <button id="bnt_disab" type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button></td>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
  $( document ).ready(function() {
	  checkBnt();
	  $('input:checkbox').change(function() {
	    checkBnt();
	  });
	  $('#bnt_enab').click(function(){
		$('#form').submit();
	  });
	});
	function checkBnt(){
		if($('input[name*=\'selected\']:checkbox:checked').length>0){
		    $('#bnt_enab').show();
		    $('#bnt_disab').hide();
	    }else{
	    	$('#bnt_enab').hide();
		    $('#bnt_disab').show();
	    }
    }
//--></script></div>
<?php echo $footer; ?>