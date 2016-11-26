<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-staff" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-staff" class="form-horizontal">
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
            <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
              <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-staffname"><?php echo $entry_staffname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="staffname" value="<?php echo $staffname; ?>" placeholder="<?php echo $entry_staffname; ?>" id="input-staffname" class="form-control" />
              <?php if ($error_staffname) { ?>
              <div class="text-danger"><?php echo $error_staffname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div style='display:none;' class="form-group">
            <label class="col-sm-2 control-label" for="input-staff-group"><?php echo $entry_staff_group; ?></label>
            <div class="col-sm-10">
              <select name="staff_group_id" id="input-staff-group" class="form-control">
                <?php foreach ($staff_groups as $staff_group) { ?>
                <?php if ($staff_group['staff_group_id'] == $staff_group_id) { ?>
                <option value="<?php echo $staff_group['staff_group_id']; ?>" selected="selected"><?php echo $staff_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $staff_group['staff_group_id']; ?>"><?php echo $staff_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
              <?php if ($error_firstname) { ?>
              <div class="text-danger"><?php echo $error_firstname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
              <?php if ($error_lastname) { ?>
              <div class="text-danger"><?php echo $error_lastname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div style='display:none;'  class="form-group">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
            </div>
          </div>
         
          <div style='display:none;'  class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" autocomplete="off" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php  } ?>
            </div>
          </div>
          <div style='display:none;'  class="form-group required">
            <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
            <div class="col-sm-10">
              <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
              <?php if ($error_confirm) { ?>
              <div class="text-danger"><?php echo $error_confirm; ?></div>
              <?php  } ?>
            </div>
          </div>
          <div  class="form-group">
            <label class="col-sm-2 control-label" for="input-postpermission"><?php echo $entry_postpermission; ?></label>
            <div class="col-sm-10">
              <select name="postpermission" id="input-postpermission" class="form-control">
				<option value="3" <?php if($postpermission==3) echo "selected='selected'";?> ><?php echo $text_postadmin; ?></option>
				<option value="2" <?php if($postpermission==2) echo "selected='selected'";?> ><?php echo $text_postpublic; ?></option>
                <option value="1" <?php if($postpermission==1) echo "selected='selected'";?> ><?php echo $text_postcreate; ?></option>
                <option value="0" <?php if($postpermission==0) echo "selected='selected'";?> ><?php echo $text_disablepermistion; ?></option>
           
              </select>
            </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 