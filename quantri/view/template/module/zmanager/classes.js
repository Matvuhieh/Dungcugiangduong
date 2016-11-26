function EditorTabs($el) {
    this._tabIndex = 0;
    this.tabs = {};
    this.activeTabIndex = 1;
    this.$el = $el;
    this.$navTabs = $el.find('.nav-tabs');
    this.$tabContent = $el.find('.tab-content');
    this.onChangeTab = '';

    this._init();
}

EditorTabs.prototype = {
    setOnChangeTab: function(cb) {
        this.onChangeTab = cb;
    },
    _init: function () {
        var self = this;

        this.$navTabs.on("click", "a", function (e) { // click tab header
            e.preventDefault();
            var $t = $(this),
                index = $t.closest('li').attr('data-index');

            if (!$t.hasClass('active')) {
                $t.tab('show');
                self.setActiveTabIndex(index);
                self.triggerChangeTab();
            }
        });

        this.$navTabs.on("click", ".close", function (e) { // close tab
            var $t = $(this),
                index = $t.closest('li').attr('data-index');

            if (index) {
                self.removeTab(index);
            }
        });


        this.$navTabs.tabdrop();

    },
    addTab: function (title, filename, type) {
        var tab = new EditorTab(title, filename, type);
        var index = ++this._tabIndex;

        this.activeTabIndex = index;

        this.tabs[index] = tab;

        this.$navTabs.find('.active').removeClass('active');

        var hrefCss = title.length >= 25 ? 'long-title' : '';

        var $li = $('<li role="presentation" data-index="' + index + '">\
        		            <a href="#tab-' + index + '" role="tab" data-toggle="tab" title="' + filename + '" class="' + hrefCss + '">' + title + '</a>\
        		            <button class="close" type="button" title="' + window.text.close + '">×</button>\
        		          </li>');

        this.$navTabs.append($li);

        var $tab_content = $('<div role="tabpanel" class="tab-pane" id="tab-' + index + '" data-index="' + index + '"></div>');

        if (type == 'editor') {
            $tab_content.append('<textarea id="code' + index + '"></textarea>');
            this.$tabContent.append($tab_content);

            var editor = ace.edit("code" + index);
            editor.setOption("maxLines", 50);
            editor.setOption('minLines', 20);
            editor.setOption('fontSize', window.settings.font_size + 'px');
            editor.setTheme("ace/theme/" + window.settings.theme);
			editor.setOption("showPrintMargin", false);
            editor.session.setMode("ace/mode/html");


            // enable autocompletion and snippets
            editor.setOptions({
                enableBasicAutocompletion: true,
                enableSnippets: true,
                enableLiveAutocompletion: false
            });

            tab.setEditor(editor);
        }
        else {
            this.$tabContent.append($tab_content);

        }

        tab.$tab = $li;
        tab.$content = $tab_content;


        this.$navTabs.find('a:last').trigger('click');
        this.$navTabs.data('tabdrop').layout();

        return tab;

    },
    removeTab: function (index) {
        if (!this.tabs[index]) {
            return;
        }


        this.$navTabs.find('li[data-index="' + index + '"]').remove();
        this.tabs[index].destroy();
        delete this.tabs[index];

        this.$tabContent.find('.tab-pane[data-index="' + index + '"]').remove();


        this.$navTabs.find('a:last').trigger('click');
        this.$navTabs.data('tabdrop').layout();

    },
    getActiveTab: function () {
        return this.tabs[this.activeTabIndex];
    },
    setActiveTabIndex: function (tabIndex) {
        this.activeTabIndex = tabIndex;
    },
    setActiveTab: function (tab) {
        for (var index in this.tabs) {
            if (this.tabs[index] === tab) {
                this.setActiveTabIndex(index);
                this.$navTabs.find('li[data-index="' + index + '"] a').trigger('click');
                break;
            }
        }
    },
    getTabByFilePath: function(filename) {
        for (var x in this.tabs) {
            if (this.tabs[x].filename == filename) {
                return this.tabs[x];
            }
        }

        return null;
    },
    getTabByTitle: function (title) {

        for (var x in this.tabs) {
            if (this.tabs[x].title == title) {
                return this.tabs[x];
            }
        }

        return null;
    },
    getTabsByType: function (type) {
        var items = {}, found = false;

        for (var index in this.tabs) {
            if (this.tabs[index].type == type) {
                found = true;
                items[index] = this.tabs[index];
            }
        }

        return found ? items : null;
    },
    triggerChangeTab: function() {
        this.onChangeTab && this.onChangeTab(this.getActiveTab());
    }

};


function EditorTab(title, filename, type) {
    this.type = type || 'editor';
    this.filename = filename;
    this.title = title;
    this.editor = null;
    this.$content = '';
    this.isBackup = false;

    this.$tab = '';
}

EditorTab.prototype = {
    setTitle: function (title) {
        this.title = title;
    },
    setEditor: function (editor) {
        this.editor = editor;
    },
    setEditorContent: function (content) {
        this.editor.setValue(content);
        this.editor.clearSelection();

        if (this.editor.session.getScrollTop()) {
            this.editor.gotoLine(0, 0);
        }

    },
    setImage: function (src) {
        this.$content.html('<img src="' + src + '" alt="" title="' + this.title + '"/>');
    },
    setEditorMode: function (mode) {
        this.editor.getSession().setMode("ace/mode/" + mode);
    },
    destroy: function () {
        if (this.editor) {
            this.editor.destroy();
            delete this.editor;
        }
    },
    setType: function (type) {
        this.type = type;
    },
    setEditorSettings: function (settings) {
        var value, name;

        for (name in settings) {
            value = settings[name];

            switch (name) {
                case 'theme':
                    this.editor.setTheme("ace/theme/" + value);
                    break;
                case 'font_size':
                    this.editor.setOptions({
                        fontSize: value + 'px'
                    });
                    break;
            }
        }
    },
    setBackupIcon: function() {
        this.$tab.find('a').addClass('fa backup-file');
        this.isBackup = true;
    }
};


function EditorSettings(settings) {
    this.callbacks = {};
    this.$modal = $('#settings-modal');
    this.events();
    this.settings = settings || {
        theme: '',
        font_size: 14
    };
}

EditorSettings.prototype = {
    onChange: function (callback) {
        this.callbacks['onChange'] = callback;
    },
    events: function () {
        var self = this;

        this.$modal.find('.form-save').on('click', function (e) {
            e.preventDefault();

            self.$modal.find('form').find('select, input[type=text]').each(function () {
                var $t = $(this);
                self.settings[$t.attr('name')] = $t.val();
            });

            self.callbacks['onChange'] && self.callbacks['onChange'](self.settings);

            self.sendFormData(self.settings);

            if (!$(this).hasClass('form-apply')) {
                self.hideForm();
            }
        });

        this.$modal.find('.form-cancel').on('click', function (e) {
            e.preventDefault();
            self.hideForm();
        });

    },
    showForm: function () {
        this.$modal.modal('show');
    },
    hideForm: function () {
        this.$modal.modal('hide');
    },
    sendFormData: function (settings) {
        var data = {
            'zmanager': {}
        };

        for (var name in settings) {
            data.zmanager[name] = settings[name];
        }


        $.ajax({
            url: this.$modal.find('form').attr('action'),
            type: "POST",
            data: data,
            success: function () {

            }
        });
    },
    isOpened: function () {
        return this.$modal.is(':visible');
    }
};


function Uploader($el) {
    this.$el = $el;
    this._is_init = false;
    this.folder = '';
    this.$form = $el.find('form');

}

Uploader.prototype = {
    init: function () {

        if (!this._is_init) {
            this._is_init = true;

            this.$form.fileupload({
                url: this.$form.attr('action')
            });

        }
    },
    showForm: function () {
        this.init();
        this.$el.modal('show');
    },
    hideForm: function () {
        this.$el.modal('hide');
    },
    setFolder: function (folder) {
        this.folder = folder;

        this.$el.find('.folder').text(folder);
        this.$form.find('input[name=id]').val(folder);
    }
};

function Backup($el) {
    this.$el = $el;
    this.$table = $el.find('table');
    this.file = '';
    this.reqList = null;
    this.onView = '';
    this.$bar = $el.find('.progress');


    this.events();
}

Backup.prototype = {
    events: function () {
        var self = this;

        this.$table.on('click', '.btn-show-file', function (e) {  // open backup file
            e.preventDefault();
            self.loadBackupFile(this.getAttribute('data-id'))
        });

        this.$table.on('click', '.btn-remove-backup', function(e) {  // remove all copy files
            e.preventDefault();

            self.$table.find('tr [data-toggle="tooltip"]').tooltip('destroy');
            self.$table.find('tr').remove();
            $.get($(this).attr('href'));
        });

        this.$table.on('click', '.btn-remove-file', function(e) {  // remove one file copy
            e.preventDefault();

            var $t = $(this);
            $.get($t.attr('href'));
            $t.closest('tr').find('[data-toggle="tooltip"]').tooltip('destroy');
            $t.closest('tr').remove();
        });

    },
    showForm: function () {
        this.$el.modal('show');
    },
    hideForm: function () {
        this.$el.modal('hide');
    },
    setFile: function (file) {
        this.file = file;
        this.$el.find('.backup-filename').text(file);
    },
    loadBackupList: function (file) {
        var self = this;
        this.setFile(file);
        this.showForm();
        this.$bar.show();

        this.reqList && this.reqList.abort && this.reqList.abort();

        this.reqList = $.get(window.backups.list_href + '&file=' + file, function (data) {
            data = data || {};
            data.items = data.items || [];

            var $html = $('#template-backup').tmpl(data);
            self.$table.html('').append($html);
            self.$bar.hide();
        });
    },
    loadBackupFile: function (id) {
        var self = this;
        this.$bar.show();
        this.$table.hide();

        this.reqList = $.get(window.backups.file_href + '&id=' + id, function (data) {
            self.$bar.hide();
            self.$table.show();
            if(data) {
                self.hideForm();
                self.onView && self.onView(data);
            }
        });
    },
    onViewFile: function(cb) {
        this.onView = cb;
    }
};