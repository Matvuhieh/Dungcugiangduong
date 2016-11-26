<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-community-page"><?php echo $entry_community_page; ?></label>
            <div class="col-sm-10">
              <textarea name="config_community_page" placeholder="<?php echo $entry_community_page; ?>" id="input-community-page" class="form-control" rows="10" ><?php echo $config_community_page; ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-community-facebook"><?php echo $entry_community_facebook; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_community_facebook" value="<?php echo $config_community_facebook; ?>" placeholder="<?php echo $entry_community_facebook; ?>" id="input-community-facebook" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-community-google"><?php echo $entry_community_google; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_community_google" value="<?php echo $config_community_google; ?>" placeholder="<?php echo $entry_community_google; ?>" id="input-community-google" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-community-youtube"><?php echo $entry_community_youtube; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_community_youtube" value="<?php echo $config_community_youtube; ?>" placeholder="<?php echo $entry_community_youtube; ?>" id="input-community-youtube" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-community-twitter"><?php echo $entry_community_twitter; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_community_twitter" value="<?php echo $config_community_twitter; ?>" placeholder="<?php echo $entry_community_twitter; ?>" id="input-community-twitter" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-community-pinterest"><?php echo $entry_community_pinterest; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_community_pinterest" value="<?php echo $config_community_pinterest; ?>" placeholder="<?php echo $entry_community_pinterest; ?>" id="input-community-pinterest" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-community-instagram"><?php echo $entry_community_instagram; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_community_instagram" value="<?php echo $config_community_instagram; ?>" placeholder="<?php echo $entry_community_instagram; ?>" id="input-community-instagram" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-community-tumblr"><?php echo $entry_community_tumblr; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_community_tumblr" value="<?php echo $config_community_tumblr; ?>" placeholder="<?php echo $entry_community_tumblr; ?>" id="input-community-tumblr" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-community-zingme"><?php echo $entry_community_zingme; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_community_zingme" value="<?php echo $config_community_zingme; ?>" placeholder="<?php echo $entry_community_zingme; ?>" id="input-community-zingme" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-community-rss"><?php echo $entry_community_rss; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_community_rss" value="<?php echo $config_community_rss; ?>" placeholder="<?php echo $entry_community_rss; ?>" id="input-community-rss" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 