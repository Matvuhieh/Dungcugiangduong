
<div id="container-tree" role="main" class="relative">
  <div id="tree"></div>
  <div id="data" class="relative">
    <div class="editor-settings">
      <i class="fa fa-repeat" data-original-title="<?php echo $text_restore; ?>" data-toggle="tooltip" id="restore-backup"></i>
      <i class="fa fa-save" data-original-title="<?php echo $text_save_file; ?>" data-toggle="tooltip" id="content-save"></i>
      <i class="fa fa-cog settings-btn" data-original-title="<?php echo $text_settings; ?>" data-toggle="tooltip"></i>
      <i class="fa fa-trash remove-all-backup-btn" data-original-title="<?php echo $text_remove_all_backups; ?>" data-toggle="tooltip"></i>
    </div>
    <div class="content code ">


      <div class="editor-tabs">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist"></ul>
        <!-- Tab panes -->
        <div class="tab-content"></div>
      </div>


    </div>
    <div class="content folder" style="display:none;"></div>
    <div class="content image" style="display:none; position:relative;"><img src="" alt="" style="display:block; position:absolute; left:50%; top:50%; padding:0; max-height:90%; max-width:90%;" /></div>
    <div class="content default" style="text-align:center;"><?php echo $text_select_file_from_tree; ?></div>
  </div>
</div>

<div id="settings-modal" class="modal  fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $text_settings; ?></h4>
      </div>
      <div class="modal-body">
          <form method="post" action="<?php echo $action; ?>">
            <table class="table">
              <tr class="borderless">
                <td colspan="2"><h2><?php echo $text_backup; ?></h2></td>
              </tr>
              <tr>
                  <td><?php echo $text_backup_files; ?></td>
                  <td>
                      <select name="backup_files">
                          <option value="1" <?php if($settings['backup_files'] == 1) { ?> selected <?php } ?>><?php echo $text_yes; ?></option>
                          <option value="0" <?php if($settings['backup_files'] == 0) { ?> selected <?php } ?>><?php echo $text_no; ?></option>
                      </select>
                  </td>
              </tr>
              <tr>
                <td><?php echo $text_backup_copies; ?></td>
                <td><input type="text" name="last_backup_file_copies"  value="<?php echo $settings['last_backup_file_copies']; ?>"/></td>
              </tr>
              <tr>
                    <td colspan="2"><h2><?php echo $text_editor_settings; ?></h2></td>
                </tr>
                <tr>
                  <td><?php echo $text_theme; ?></td>
                  <td>
                    <select name="theme">
                      <?php foreach($themes as $theme) { ?>
                        <option value="<?php echo $theme['filename']; ?>" <?php if($settings['theme'] == $theme['filename']) { ?> selected <?php } ?> ><?php echo $theme['title']; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><?php echo $text_font_size; ?></td>
                  <td>
                    <select name="font_size">
                        <?php for($i = 10; $i < 20; $i++) { ?>
                          <option value="<?php echo $i; ?>" <?php if($settings['font_size'] == $i) { ?> selected <?php } ?>><?php echo $i; ?>px</option>
                        <?php } ?>
                    </select>
                  </td>
                </tr>
              <tr>
                <td colspan="2">
                  <br/>
                  <button class="btn btn-primary form-save"><i class="fa fa-save"></i> <?php echo $text_save; ?></button>
                  <button class="btn btn-primary form-save form-apply"><i class="fa fa-save"></i> <?php echo $text_apply; ?></button>
                  <button class="btn btn-primary form-cancel"><i class="fa fa-reply"></i> <?php echo $text_cancel; ?></button>
                </td>
              </tr>

            </table>
          </form>

      </div>
    </div>
  </div>
</div>


<script>
  window.tree_href = '<?php echo $href; ?>';
  window.remove_backup_href = '<?php echo $remove_backup_href; ?>';
  window.settings = <?php echo json_encode($settings); ?>;
  window.text = {
      'remove_all_backups': '<?php echo $text_remove_all_backups; ?>',
      'all_backups_removed' : '<?php echo $text_all_backups_removed; ?>',
      'select_file_from_tree': '<?php echo $text_select_file_from_tree; ?>',
      'save_file_message': '<?php echo $text_success_save; ?>',
      'save_file_error': '<?php echo $text_error_save; ?>',
      'close': '<?php echo $text_close; ?>',
      'new': '<?php echo $text_new; ?>',
      'folder': '<?php echo $text_folder; ?>',
      'file': '<?php echo $text_file; ?>',
      'rename': '<?php echo $text_rename; ?>',
      'delete': '<?php echo $text_delete; ?>',
      'edit': '<?php echo $text_edit; ?>',
      'cut': '<?php echo $text_cut; ?>',
      'copy': '<?php echo $text_copy; ?>',
      'paste': '<?php echo $text_paste; ?>',
      'upload': '<?php echo $text_upload; ?>',
      'refresh': '<?php echo $text_refresh; ?>',
      'show_backups': '<?php echo $text_show_backups; ?>',
      'download_file': '<?php echo $text_download; ?>'
  };

</script>
