<?php

namespace NitFAQ\Tinymce;

class TinyButtonsNitFaqs{
    
    public function __construct()
    {
        
    }

    public static function set_tinymce_buttons()
	{
        static $instance = null;

		if (!isset($instance)) {
			$instance = new TinyButtonsNitFaqs;
            $instance->set_actions();
            $instance->nitfaqs_buttons();
		}
		return $instance;
    }

    /**
     * Set actions
     */
    private function set_actions(){
        add_action( 'after_wp_tiny_mce', [$this, 'setJSVars']);
    }
    
    /**
     * Set button to add shortcut via tinymce
     */
    private function nitfaqs_buttons() {
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            return;
        }
    
        if ( get_user_option( 'rich_editing' ) !== 'true' ) {
            return;
        }
    
        add_filter( 'mce_external_plugins', [ $this, 'nitfaqs_add_buttons' ] );
        add_filter( 'mce_buttons', [$this, 'nitfaqs_register_buttons'] );
    }

    public function nitfaqs_add_buttons( $plugin_array ) {
        $plugin_array['nitfaqs-custom-buttons'] = NITFAQS_URL.'/assets/js/tinymce_buttons.js';
        return $plugin_array;
    }
    
    
    public function nitfaqs_register_buttons( $buttons ) {
    
        if( ! function_exists('activate_plugin') ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
    
        array_push( $buttons, 'nitfaqs_stc_button' );
    
        return $buttons;
    }
    
    
    public function setJSVars() { ?>
        <script type="text/javascript">
            var tinyMCE_nitfaqs_stc_button_object = <?php echo json_encode(
                array(
                    'nitfaqs_plugin_button_name' => esc_html__('Add FAQs List', 'nit-faqs'),
                )
                );
            ?>;
        </script><?php
    }

}