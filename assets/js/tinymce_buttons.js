(function() {
  /**
   * Check this out!
   * https://madebydenis.com/adding-shortcode-button-to-tinymce-editor/
   */
  if(typeof tinymce !== 'undefined'){
    tinymce.PluginManager.add('nitfaqs-custom-buttons', function( editor, url ) {

      // PDF Viewer shortcode
      editor.addButton( 'nitfaqs_stc_button', {
          title: tinyMCE_nitfaqs_stc_button_object.nitfaqs_plugin_button_name,
          icon: 'faqs',
          onclick: function() {
              // Append shortcode to body with attachment url
              editor.insertContent( '[nit_faqs_list]');
          },
      });
    });
  }
})();
