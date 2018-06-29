<?php

namespace NitFAQ\Tax;

use NitFAQ\Interfaces\iTax;

class FAQsCatsTax implements iTax{

    private $postType;

    public function __construct($postType)
    {
        $this->postType = $postType;
        $this->registerTax();
    }

    /**
     * Register Tax
     */
    private function registerTax(){
        //labels array added inside the function and precedes args array

        $labels = array(
            'name'               => __( 'Categories', 'nit-faqs'),
            'singular_name'      => __( 'Category', 'nit-faqs'),
            'add_new'            => __( 'Add New', 'nit-faqs'),
            'add_new_item'       => sprintf( __( 'Add New %s' , 'nit-faqs'), __('Category', 'nit-faqs')),
            'edit_item'          => sprintf( __( 'Edit %s' , 'nit-faqs'), __('Category', 'nit-faqs')),
            'new_item'           => sprintf( __( 'New %s' , 'nit-faqs'), __('Category', 'nit-faqs')),
            'all_items'          => sprintf( __( 'All %s' , 'nit-faqs'), __('Categories', 'nit-faqs')),
            'view_item'          => sprintf( __( 'View %s' , 'nit-faqs'), __('Category', 'nit-faqs')),
            'search_items'       => sprintf( __( 'Search %s' , 'nit-faqs'), __('Category', 'nit-faqs')),
            'not_found'          => sprintf( __( 'No %s found' , 'nit-faqs'), __('Categories', 'nit-faqs')),
            'not_found_in_trash' => sprintf( __( 'No %s found in the Trash' , 'nit-faqs'), __('Categories', 'nit-faqs')),
            'parent_item_colon'  => null,
            'menu_name'          => __('Categories', 'nit-faqs')
        );


        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'categorias-faqs' ),
        );

        register_taxonomy( 'categorias-faqs', $this->postType, $args );
    }
}
