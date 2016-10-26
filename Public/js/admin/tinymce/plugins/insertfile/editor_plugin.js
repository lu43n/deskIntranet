
(function() {
	tinymce.PluginManager.requireLangPack('insertfile');

	tinymce.create('tinymce.plugins.insertfile', {
		init : function(ed, url) {
			var t = this;

			t.editor = ed;

			// Register commands
			ed.addCommand('mcefilemanager', function(ui, v) {
                            ed.windowManager.open({
                                file: fileManagerUrl + '/',
                                title: 'Menadżer plików',
                                width: 900,  
                                height: 450,
                                resizable: 'yes',
                                inline: 'yes',
                                popup_css: false,
                                close_previous: 'no'
                              }, {
                                plugin_url : url
                              });
                              
                        });

			// Register buttons
			ed.addButton('insertfile', {
                            title : 'insertfile.desc', 
                            cmd : 'mcefilemanager', 
                            ui : true, 
                            image: url + '/img/insertfile.gif'
                        });

		},

		getInfo : function() {
			return {
				longname : 'elFinder File Manager',
				author : 'elFinder',
				authorurl : '',
				infourl : '',
				version : ''
			};
		}
	}
);

	// Register plugin
	tinymce.PluginManager.add('insertfile', tinymce.plugins.insertfile);
})();
