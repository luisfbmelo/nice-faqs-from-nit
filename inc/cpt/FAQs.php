<?php

namespace NitFAQ\CPT;

use NitFAQ\Tax\FAQsCatsTax;

use NitFAQ\Interfaces\iWithTax;
use NitFAQ\Interfaces\iTax;

class FAQsCPT implements iWithTax{

    private $taxs;

    public function __construct()
    {
        $this->registerCPT();
    }

    /**
     * Register CPT
     */
    private function registerCPT(){
        //labels array added inside the function and precedes args array

        $labels = array(
            'name'               => __( 'FAQs', 'nit-faqs'),
            'singular_name'      => __( 'FAQ', 'nit-faqs'),
            'add_new'            => __( 'Add New', 'nit-faqs'),
            'add_new_item'       => sprintf( __( 'Add New %s' , 'nit-faqs'), __('FAQ', 'nit-faqs')),
            'edit_item'          => sprintf( __( 'Edit %s' , 'nit-faqs'), __('FAQ', 'nit-faqs')),
            'new_item'           => sprintf( __( 'New %s' , 'nit-faqs'), __('FAQ', 'nit-faqs')),
            'all_items'          => sprintf( __( 'All %s' , 'nit-faqs'), __('FAQs', 'nit-faqs')),
            'view_item'          => sprintf( __( 'View %s' , 'nit-faqs'), __('FAQ', 'nit-faqs')),
            'search_items'       => sprintf( __( 'Search %s' , 'nit-faqs'), __('FAQ', 'nit-faqs')),
            'not_found'          => sprintf( __( 'No %s found' , 'nit-faqs'), __('FAQ', 'nit-faqs')),
            'not_found_in_trash' => sprintf( __( 'No %s found in the Trash' , 'nit-faqs'), __('FAQ', 'nit-faqs')),
            'parent_item_colon'  => '',
            'menu_name'          => __('FAQs', 'nit-faqs')
        );

        $args = array(
            'labels'                    => $labels,
            'description'               => '',
            'public'                    => true,
            'menu_position'             => 25,
            'hierarchical'              => false,
            'exclude_from_search'       => false,
            'supports'                  => array('title', 'editor'),
            'has_archive'               => false,
            'menu_icon'                 => 'dashicons-megaphone',
            'show_in_rest'              => true,
            'rewrite'                   => array( 'slug' => 'faqs' ),
            'capability_type'           => 'post',
        );

        register_post_type( 'faqs', $args );

        $this->setTaxs('categorias-faqs', new FAQsCatsTax('faqs'));
    }

    /**
     * Get taxonomy instance based on slug
     */
    public function getTaxs(string $slug): iTax {
        return $this->taxs[$slug];
    }

    /**
     * Set taxonomy instance based on slug
     */
    public function setTaxs(string $slug, iTax $targetTax) {
        $this->taxs[$slug] = $targetTax;
    }

    /**
     * Print faqs by term
     */
    public static function printFaqs($tax_terms, $tax, $level = 0){
        $html = '';

        if($level === 0){
            $args = array(
                'posts_per_page' => -1,
                'post_type' => 'faqs',
                'orderby' => 'title',
                'order' => 'ASC',
                'tax_query' => [
                    [
                        'taxonomy' => $tax,
                        'terms'    => get_terms( $tax, [ 'fields' => 'ids'  ] ),
                        'operator' => 'NOT IN'
                    ]
                ]
            );

            $tax_terms_posts = get_posts( $args );

            if(count($tax_terms_posts)>0){
                $html.= '<div class="faqs-category faqs-category-toggle">';
                    $html.= '<div class="faqs-faq-category-title faqs-faq-category-title-toggle">';
                        $html.= '<h4>'.__('Generic', 'nit-faqs').'</h4>';
                    $html.= '</div>';
                $html.= "<div class='faqs-list'>";

                    foreach ( $tax_terms_posts as $post ) {
                        $html.= '<div class="faqs-faq-toggle faqs-faq-div" data-postid="faq_'.$post->ID.'_geral_1">';
                            $html.= '<div class="faqs-faq-title-text">';

                                $html.= '<h4 class="faqs-question-title">' . $post->post_title . '</h4>';

                            $html.= '</div>';


                        $html.= '</div>';
                        $html.= '<div class="faqs-faq-body" id="faq-body-faq_'.$post->ID.'_geral_1"><p>'.$post->post_content.'</p></div>';
                    }

                    $html.= '</div>';
                $html.= '</div>';
            }
        }

        if(count($tax_terms)>0){
            foreach ( $tax_terms as $term ) {

                $childTerms = self::getTermChild( $term, $tax, 'idx' );

                $args = array(
                    'posts_per_page' => -1,
                    'post_type' => 'faqs',
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'tax_query' => [
                        [
                            'taxonomy' => $tax,
                            'field' => 'slug',
                            'terms' => $term->slug,
                            'include_children' => false
                        ]
                    ],
                );

                $tax_terms_posts = get_posts( $args );


                $html.= '<div class="faqs-category faqs-category-toggle">';
                    $html.= '<div class="faqs-faq-category-title faqs-faq-category-title-toggle">';
                        $html.= '<h4>' . $term->name . '</h4>';
                    $html.= '</div>';



                $html.= "<div class='faqs-list'>";

                    foreach ( $tax_terms_posts as $post ) {
                        $html.= '<div class="faqs-faq-toggle faqs-faq-div" data-postid="faq_'.$post->ID.'_'.$term->term_id.'">';
                            $html.= '<div class="faqs-faq-title-text">';

                                $html.= '<h4 class="faqs-question-title">' . $post->post_title . '</h4>';

                            $html.= '</div>';


                        $html.= '</div>';
                        $html.= '<div class="faqs-faq-body" id="faq-body-faq_'.$post->ID.'_'.$term->term_id.'"><p>'.$post->post_content.'</p></div>';
                    }

                    $html.= '</div>';

                    if ($childTerms && sizeof($childTerms)>0){
                        $html .= self::printFaqs($childTerms, $tax, 1);
                    }
                $html.= '</div>';
                wp_reset_postdata();
            }
        }

        return $html;
    }

    /**
     * Get children of given term
     */
    public static function getTermChild($term, $tax, $key = 'name'){

        $getTermsArgs = [
            'taxonomy' => $tax,
            'hide_empty' => 1,
            'title_li' => '',
            'echo' => 0,
            'parent' => $term->term_id
        ];

        $term_children = get_terms($getTermsArgs);

        $namearray = [];

        if (sizeof($term_children)>0){

            $i = 0;

            foreach ( $term_children as $child ) {
                // Get term data

                // Skip empty terms
                if( $child->count <= 0) {
                    continue;
                }

                $namearray[$key!='idx' ? $child->name : $i] = $child;

                $i++;
            }

            // Sort array by name
            ksort($namearray);
        }

        return $namearray;
    }
}
