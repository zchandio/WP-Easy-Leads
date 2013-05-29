/**
 * Created by touqeer.shafi@gmail.com
 * Date: 4/30/13
 * Time: 2:36 PM
 * Filename :
 */
(function() {
    tinymce.create('tinymce.plugins.video', {
        init : function(ed, url) {
            ed.addButton('video', {
                title : 'Add Video',
                image : url+'/images/video.png',
                onclick : function() {
                    ed.selection.setContent('[video url="Video Url Here" width="" height=""][/video]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('video', tinymce.plugins.video);
})();

(function() {
    tinymce.create('tinymce.plugins.heading', {
        init : function(ed, url) {
            ed.addButton('heading', {
                title : 'Add a Quote',
                image : url+'/images/heading.png',
                onclick : function() {
                    ed.selection.setContent('[heading type="h1" color="#000"]Heading Content[heading]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('heading', tinymce.plugins.heading);
})();

(function() {
    tinymce.create('tinymce.plugins.button', {
        init : function(ed, url) {
            ed.addButton('button', {
                title : 'Add a button',
                image : url+'/images/button.png',
                onclick : function() {
                    ed.selection.setContent('[button class="" url="" size=""]Button Text[/button]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('button', tinymce.plugins.button);
})();
