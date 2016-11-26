<div id="backup-modal" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $text_file_backups; ?> <strong class="backup-filename"></strong></h4>
      </div>
      <div class="modal-body">
          <div class="progress progress-striped active">
            <div class="bar" style="width: 90%;"></div>
          </div>
          <table class="table">

          </table>

      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>

<script>
    window.backups = {
        list_href: '<?php echo $list_href; ?>',
        file_href: '<?php echo $file_href; ?>'
    };
</script>

<!-- The template to display file backups  -->
<script id="template-backup" type="text/x-tmpl">
    {{if items.length == 0}}
        <tbody>
            <tr class="borderless">
                <td><h2><?php echo $text_no_backups; ?></h2></td>
             </tr>
        </tbody>
    {{else}}
        <tbody>
        <tr class="borderless">
           <th>Date</td>
           <th>
           Action
           {{if items.length}}
                <a class="btn btn-remove-backup" href="${remove_files_href}"><i class="fa fa-trash" data-original-title="<?php echo $text_remove_all_backups; ?>" data-toggle="tooltip"></i></a>
            {{/if}}
           </th>
        </tr>
         {{each(index, file) items}}
            <tr>
                <td>${file.date}</td>
                <td>
                    <a class="btn btn-show-file" href="#" data-id="${file.id}"><i class="fa fa-eye" data-original-title="<?php echo $text_view_file_backup; ?>" data-toggle="tooltip"></i></a>
                    <a class="btn btn-remove-file" href="${file.remove_href}"><i class="fa fa-trash" data-original-title="<?php echo $text_remove_backup; ?>" data-toggle="tooltip"></i></a>
                </td>
            </tr>
        {{/each}}
        </tbody>
    {{/if}}

</script>