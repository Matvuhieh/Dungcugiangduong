function Tree($el, callbacks) {
    callbacks = callbacks || {onChange: '', onUpload: '', onShowBackups: '', onFileDownload: ''};
    var iconDir = 'view/template/module/zmanager/tree/icons/';

    $el
        .jstree({
            'core': {
                'data': {
                    'url': window.tree_href + '&operation=get_node',
                    'data': function (node) {
                        return {'id': node.id};
                    }
                },
                'check_callback': function (o, n, p, i, m) {
                    if (m && m.dnd && m.pos !== 'i') {
                        return false;
                    }
                    if (o === "move_node" || o === "copy_node") {
                        if (this.get_node(n).parent === this.get_node(p).id) {
                            return false;
                        }
                    }
                    if(o == 'delete_node') {
                        if(!confirm(window.text.delete + '?')) {
                            return false;
                        }
                    }
                    return true;
                },
                'force_text': true,
                'themes': {
                    'name': 'proton',
                    'responsive': false,
                    'variant': 'small',
                    'stripes': true
                }
            },
            'sort': function (a, b) {
                return this.get_type(a) === this.get_type(b) ? (this.get_text(a) > this.get_text(b) ? 1 : -1) : (this.get_type(a) >= this.get_type(b) ? 1 : -1);
            },
            'contextmenu': {
                select_node: false,
                'items': function (node) {
                    var tmp = $.jstree.defaults.contextmenu.items();
                    delete tmp.create.action;
                    tmp.create.label = window.text.new;
                    tmp.create.icon = iconDir + 'new.png';
                    tmp.create.submenu = {
                        "create_folder": {
                            "separator_after": true,
                            "label": window.text.folder,
                            "action": function (data) {
                                var inst = $.jstree.reference(data.reference),
                                    obj = inst.get_node(data.reference);
                                inst.create_node(obj, {type: "default"}, "last", function (new_node) {
                                    setTimeout(function () {
                                        inst.edit(new_node);
                                    }, 0);
                                });
                            },
                            icon: iconDir + 'folder.png'
                        },
                        "create_file": {
                            "label": window.text.file,
                            "action": function (data) {
                                var inst = $.jstree.reference(data.reference),
                                    obj = inst.get_node(data.reference);
                                inst.create_node(obj, {type: "file"}, "last", function (new_node) {
                                    setTimeout(function () {
                                        inst.edit(new_node);
                                    }, 0);
                                });
                            },
                            icon: iconDir + 'file.png'
                        }
                    };

                    if (this.get_type(node) === "file") {
                        delete tmp.create;

                        tmp.backup = {
                            label: window.text.show_backups,
                            'action': function (data) {
                                var inst = $.jstree.reference(data.reference),
                                    obj = inst.get_node(data.reference);

                                callbacks.onShowBackups && callbacks.onShowBackups(obj);
                            },
                            icon: iconDir + 'backup.png'
                        };

                        tmp.download = {
                            label: window.text.download_file,
                            'action': function (data) {
                                var inst = $.jstree.reference(data.reference),
                                    obj = inst.get_node(data.reference);

                                callbacks.onFileDownload && callbacks.onFileDownload(obj);
                            },
                            icon: iconDir + 'download.png'
                        };
                    }
                    else {
                        tmp.upload = {
                            label: window.text.upload,
                            'action': function (data) {
                                var inst = $.jstree.reference(data.reference),
                                    obj = inst.get_node(data.reference);

                                callbacks.onUpload && callbacks.onUpload(obj);
                            },
                            icon: iconDir + 'upload.png'
                        };

                        tmp.refresh = {
                            label: window.text.refresh,
                            action: function(data) {
                                var inst = $.jstree.reference(data.reference),
                                    obj = inst.get_node(data.reference);

                                inst.refresh(obj);
                            },
                            icon: iconDir + 'refresh.png'
                        }
                    }

                    if(tmp.rename) {
                        tmp.rename.label = window.text.rename;
                        tmp.rename.icon = iconDir + 'rename.png';
                    }

                    if(tmp.ccp) {
                        tmp.ccp.label = window.text.edit;
                        tmp.ccp.icon = iconDir + 'edit.png';

                        if(tmp.ccp.submenu) {
                            var sub = tmp.ccp.submenu;
                            if(sub.copy) {
                                sub.copy.label = window.text.copy;
                                sub.copy.icon = iconDir + 'copy.png';
                            }

                            if(sub.cut) {
                                sub.cut.label = window.text.cut;
                                sub.cut.icon = iconDir + 'cut.png';
                            }

                            if(sub.paste) {
                                sub.paste.label = window.text.paste;
                                sub.paste.icon = iconDir + 'paste.png';
                            }
                        }
                    }



                    if(tmp.remove) {
                        tmp.remove.label = window.text.delete;
                        tmp.remove.icon = iconDir + 'remove.png';
                    }



                    return tmp;
                }
            },
            'types': {
                'default': {'icon': 'folder'},
                'file': {'valid_children': [], 'icon': 'file'}
            },
            'unique': {
                'duplicate': function (name, counter) {
                    return name + ' ' + counter;
                }
            },
            'plugins': ['state', 'dnd', 'sort', 'types', 'contextmenu', 'unique', 'search'],
            "search": {
                case_sensitive: false,
                show_only_matches: true,
                "ajax": {
                    'data': function (node) {
                        return {'id': node.id};
                    },
                    "url": window.tree_href + '&operation=search'
                }
            }
        })
        .on('delete_node.jstree', function (e, data) {
            $.get(window.tree_href + '&operation=delete_node', {'id': data.node.id})
                .fail(function () {
                    data.instance.refresh();
                });
        })
        .on('create_node.jstree', function (e, data) {
            $.get(window.tree_href + '&operation=create_node', {
                'type': data.node.type,
                'id': data.node.parent,
                'text': data.node.text
            })
                .done(function (d) {
                    data.instance.set_id(data.node, d.id);
                })
                .fail(function () {
                    data.instance.refresh();
                });
        })
        .on('rename_node.jstree', function (e, data) {
            $.get(window.tree_href + '&operation=rename_node', {'id': data.node.id, 'text': data.text})
                .done(function (d) {
                    data.instance.set_id(data.node, d.id);
                })
                .fail(function () {
                    data.instance.refresh();
                });
        })
        .on('move_node.jstree', function (e, data) {
            $.get(window.tree_href + '&operation=move_node', {'id': data.node.id, 'parent': data.parent})
                .done(function (d) {
                    //data.instance.load_node(data.parent);
                    data.instance.refresh();
                })
                .fail(function () {
                    data.instance.refresh();
                });
        })
        .on('copy_node.jstree', function (e, data) {
            $.get(window.tree_href + '&operation=copy_node', {'id': data.original.id, 'parent': data.parent})
                .done(function (d) {
                    //data.instance.load_node(data.parent);
                    data.instance.refresh();
                })
                .fail(function () {
                    data.instance.refresh();
                });
        })
        .on('changed.jstree', function (e, data) {
            if (!data || !data.selected || !data.selected.length || data.selected.length > 1) {
                return;
            }

            if(data.node.type == 'default') {
                data.instance.toggle_node(data.node);
                return;
            }

            callbacks.onChange && callbacks.onChange(data);


        });

}