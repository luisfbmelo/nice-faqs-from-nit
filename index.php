<?php
/**
 * Plugin Name: Nice FAQs from NIT
 * Description: Show FAQs in a nice way
 * Version: 1.0
 * Author: Luis Melo - NIT
 * Author URI: http://luisfbmelo.com/
 * Domain Path: /languages/
 **/

namespace NitFAQ;

use NitFAQ\Interfaces;
use NitFAQ\Tax;
use NitFAQ\CPT;
use NitFAQ\Tinymce;

define('NITFAQS_PATH', plugin_dir_path(__FILE__));
define('NITFAQS_URL', plugin_dir_url(__FILE__));

define('NITFAQS_VERSION', '1');

class NitFaqs
{

	public static function get_instance()
	{
        static $instance = null;

		if (!isset($instance)) {
            $instance = new NitFaqs;
            $instance->includes();
            $instance->setup_actions();
            
			$instance->setup_shortcode();
		}
		return $instance;
	}

	private function __construct()
	{
		/** Do nothing **/
	}

	/**
	 * Setup actions
	 */
	private function setup_actions()
	{
        add_action('wp_enqueue_scripts', [$this, 'register_scripts_and_styles']);
        add_action( 'admin_enqueue_scripts', [$this, 'register_admin_scripts_and_styles']);
        add_action( 'init', [$this, 'init'] );
        add_action( 'init', [$this, 'set_cpt'] );
        add_action( 'init', [__NAMESPACE__.'\\Tinymce\\TinyButtonsNitFaqs', 'set_tinymce_buttons'] );
    }
    
    /**
     * Include core files
     */
    private function includes(){
        // Interfaces
        require_once( NITFAQS_PATH . 'inc/interfaces/Tax.php' );
        require_once( NITFAQS_PATH . 'inc/interfaces/withTax.php' );

        // Custom Post Types
        require_once( NITFAQS_PATH . 'inc/cpt/FAQs.php' );

        // Taxonomies
        require_once( NITFAQS_PATH . 'inc/tax/catFaqs.php' );

        // TinyMCE
        require_once( NITFAQS_PATH . 'inc/tinymce/Buttons.php' );
    }

    /**
     * Init instructions
     */
    public function init(){
        $domain = 'nit-faqs';
        
	    load_plugin_textdomain($domain, FALSE, basename(dirname(__FILE__)) . '/languages');
    }

    /**
     * Init custom post type
     */
    public function set_cpt(){
        new CPT\FAQsCPT();

        $this->create_pages();
    }

    /**
     * Create page to include FAQs list
     */
    private function create_pages(){
        $pages = [
            [
                'title' => 'FAQ',
                'slug' => 'faqs',
                'content' => '[nit_faqs_list]',
                'template' => ''
            ]
        ];
        foreach($pages as $page){
            $page_check = get_page_by_title($page['title']);
            $new_page = array(
                    'post_type' => 'page',
                    'post_title' => $page['title'],
                    'post_name' => $page['slug'],
                    'post_content' => $page['content'],
                    'post_status' => 'publish',
                    'post_author' => 1,
            );
            if(!isset($page_check->ID)){
                    $new_page_id = wp_insert_post($new_page);
                    if(!empty($page['template'])){
                            update_post_meta($new_page_id, '_wp_page_template', $page['template']);
                    }
            }
        }
    }

	/**
	 * Setup shortcode
	 */
	private function setup_shortcode()
	{
		add_shortcode("nit_faqs_list", [$this, "print_faqs"]);
    }

    /**
     * Shortcode init
     */
    function print_faqs($atts)
    {
        $fullAtts = shortcode_atts([
            'container-class' => null
        ], $atts);

        ob_start();
        include('views/list.php');
        $output = ob_get_clean();
        return $output;
    }

    /**
     * Process styles and scripts
     */

    function register_scripts_and_styles()
    {
        wp_enqueue_style('nitfaqs-css', NITFAQS_URL . 'assets/css/style.css', false, NITFAQS_VERSION, 'all');
        wp_enqueue_script('nitfaqs.js', NITFAQS_URL . 'assets/js/index.js', array('jquery'), NITFAQS_VERSION, true);
    }

    /**
     * Process styles and scripts for admin area
     */
    function register_admin_scripts_and_styles(){
        wp_enqueue_style('admin-nitfaqs-css', NITFAQS_URL . 'assets/css/admin.css', false, NITFAQS_VERSION, 'all');
    }
}


function nitFaqsInit()
{
	return NitFaqs::get_instance();
}
add_action('plugins_loaded', __NAMESPACE__.'\\nitFaqsInit');