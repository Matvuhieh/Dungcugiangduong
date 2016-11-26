$(function () {
    $(window).resize(function () {
        var h = Math.max($(window).height() - 0, 420);
        $('#container-tree, #data, #tree, #data .content').height(h).filter('.default'); //.css('lineHeight', h + 'px');
    }).resize();

    // trigger extension

    ace.require("ace/ext/language_tools");

    var $saveContentBtn = $('#content-save'),
        $restoreBackupBtn = $('#restore-backup');

    var uploader = new Uploader($('#upload-modal'));
    var tabs = new EditorTabs($('.editor-tabs'));
    var backup = new Backup($('#backup-modal'));

    var editorSettings = new EditorSettings(window.settings);
    editorSettings.onChange(function (settings) {
        var items = tabs.getTabsByType('editor');
        if (items) {
            for (var index in items) {
                items[index].setEditorSettings(settings);
            }
        }
    });

    tabs.setOnChangeTab(function(tab) {
        if(!tab) {
            $saveContentBtn.hide();
            $restoreBackupBtn.hide();
            return;
        }

        $saveContentBtn[tab.isBackup ? 'hide' : 'show']();
        $restoreBackupBtn[tab.isBackup ? 'show' : 'hide']();
    });


    $('.code').hide();

    $('.settings-btn').on('click', function () {
        editorSettings.showForm();
    });


    $(window).bind('keydown', function (event) {  //ctrl + s save file content
        if (editorSettings.isOpened()) {
            return;
        }

        if (event.ctrlKey || event.metaKey) {
            switch (String.fromCharCode(event.which).toLowerCase()) {
                case 's':
                    event.preventDefault();
                    $saveContentBtn.trigger('click');
                    break;
            }
        }
    });

    $('.remove-all-backup-btn').on('click', function(e) { // remove all backups btn
        if(confirm(window.text.remove_all_backups + '?')) {
            $.get(window.remove_backup_href);
            showSuccessNotifity(window.text.all_backups_removed);
        }
    });


    $restoreBackupBtn.add($saveContentBtn).on('click', function () { // save btn click, save file content
        var tab = tabs.getActiveTab();
        if (!tab || tab.type != 'editor' || $('#data .code').is(':hidden')) {
            return;
        }

        $saveContentBtn.prop('disabled', true);

        $.ajax({
            url: window.tree_href + '&operation=save_filedata',
            dataType: 'json',
            type: "POST",
            data: {
                content: tab.editor.getValue(),
                id: tab.filename
            },
            success: function (result) {
                $saveContentBtn.prop('disabled', false);
                if(result.success) {
                    showSuccessNotifity();
                }

            },
            error: function () {
                $saveContentBtn.prop('disabled', false);
                showErrorNotifity();
            }
        })
    });

    backup.onViewFile(function(data) {
        data.content = data.filecontent;
        var filetitle = data.filepath.split('/');
        filetitle = filetitle[filetitle.length - 1];

        var tab = appendTab(filetitle + ' (' + data.date + ')', data.filepath, data);
        tab.setBackupIcon();
        tabs.triggerChangeTab();
    });


    var tree = new Tree($('#tree'), {
        onFileDownload: function(data) {
            if (!data || !data.id) {
                return;
            }

            window.location = window.tree_href + '&operation=downloadFile&id=' + data.id;
        },
        onShowBackups: function (data) {
            if (!data || !data.id) {
                return;
            }

            backup.loadBackupList(data.id);
        },
        onUpload: function (data) {
            if (!data || !data.id) {
                return;
            }

            uploader.setFolder(data.id);
            uploader.showForm();
        },
        onChange: function (data) {
            $saveContentBtn.prop('disabled', true);

            var filename = data.selected[0],
                tab = tabs.getTabByFilePath(filename);
            
            if (tab) {
                $('#data .code').show();
                tabs.setActiveTab(tab);
                return;
            }

            $.get(window.tree_href + '&operation=get_content&id=' + data.selected.join(':'), function (d) {
                $saveContentBtn.prop('disabled', false);

                $('#data .content').hide();
                if (!d || typeof d.type === 'undefined') {
                    $('#data .default').html(window.text.select_file_from_tree).show();
                    return;
                }

                var filetitle = filename.split('/');
                filetitle = filetitle[filetitle.length - 1];

                appendTab(filetitle, filename, d);
            });
        }
    });

    function appendTab(title, filename, data) {
        var tab = tabs.getTabByFilePath(filename);

        if (tab) {
            $('#data .code').show();
            tabs.setActiveTab(tab);
            return tab;
        }

        var mode = 'text';
        switch (data.type) {
            case 'sql':
                mode = 'sql';
                break;
            case 'php':
                mode = 'php';
                break;
            case 'js':
                mode = 'javascript';
                break;
            case 'css':
                mode = 'css';
                break;
			case 'tpl':	
            case 'html':
                mode = 'html';
                break;
            case 'htm':
                mode = 'html';
                break;
            case 'json':
                mode = 'json';
                break;
            case 'xml':
                mode = 'xml';
                break;
        }


        switch (data.type) {
            case 'text':
            case 'txt':
            case 'md':
            case 'htaccess':
            case 'log':
            case 'sql':
            case 'php':
            case 'js':
            case 'json':
            case 'css':
            case 'html':
            case 'htm':
            case 'tpl':
            case 'ini':
            case 'xml':
                $('#data .code').show();

                tab = tabs.addTab(title, filename, 'editor');
                tab.setEditorContent(data.content);
                tab.setEditorMode(mode);

                break;
            case 'png':
            case 'jpg':
            case 'jpeg':
            case 'bmp':
            case 'gif':
                $('#data .code').show();
                tab = tabs.addTab(title, filename, 'image');
                tab.setImage(data.content);
                break;
            default:
                $('#data .default').html(data.content).show();
                $saveContentBtn.hide();
                $restoreBackupBtn.hide();
                break;
        }

        return tab;
    }

});

function showSuccessNotifity(message) {
    message = message || window.text.save_file_message;
    $.notify({
        icon: 'fa fa-save',
        message: message
    }, {
        type: 'success',
        offset: {x: 10, y: 50},
        z_index: 1000,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        placement: {
            from: "bottom"
        },
        delay: 3000
    });
}

function showErrorNotifity() {
    $.notify({
        icon: 'fa fa-save',
        message: window.text.save_file_error
    }, {
        type: 'success',
        offset: {x: 10, y: 50},
        z_index: 1000,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        placement: {
            from: "bottom"
        },
        delay: 3000
    });
}
