<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://byronj.me
 * @since      1.1.0
 *
 * @package    Easy_Content_Adder
 * @subpackage Easy_Content_Adder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Easy_Content_Adder
 * @subpackage Easy_Content_Adder/public
 * @author     Byron Johnson <me@byronj.me>
 */
class Easy_Content_Adder_Public_Display {

    // Properties
    private $beca_options;
    private $enable;
    private $top;
    private $bottom;
    private $post_types;
    private $selected_post_types;
    private $page_conditionals;
    private $categories;
    private $category_names;
    private $selected_category_names;

    // Constructor
    public function __construct() {
        $this->beca_options = get_option('beca_settings');

        if(isset($this->beca_options['enable'])){
            $this->enable = $this->beca_options['enable'];
        }
        
        if(isset($this->beca_options['bottom'])){
            $this->bottom = $this->beca_options['bottom'];
        }
        
        if(isset($this->beca_options['top'])){
            $this->top = $this->beca_options['top'];
        }
        
        $this->categories = get_terms();
        $this->category_names = $this->get_term_names($this->categories);
        $this->selected_category_names = $this->get_selected_options($this->category_names);
    }

    /**
     * Set properties
     *
     * @since    1.1.0
     */
    public function set_properties() {
        if (!isset($this->enable)) {
            $this->enable = 0;
        }

        if (!isset($this->top)) {
            $this->top = 0;
        }
        if (!isset($this->bottom)) {
            $this->bottom = 0;
        }
    }

    /**
	 * Get all post types on the site
	 *
	 * @since    1.1.2
	 */

    public function get_site_post_types() {
        $site_post_types = get_post_types(array('public' => true));
        return $site_post_types;
    }

    /**
	 * Creates an array of term names
	 *
	 * @since    1.1.1
	 */
    public function get_term_names($terms) {
        $new_terms = array();
        
        foreach($terms as $term){
            array_push( $new_terms, $term->name );
        }

        return $new_terms;
    }

    /**
     * Modifies the final content based on select settings from /wp-admin/options-general.php?page=beca-options
     *
     * @since    1.1.0
     */
    public function modify_content($content) {

        // Store the original content
        $base_content = $content;
        $post_type_categories_enabled = 0;

        // Post type of current post
        $this_posts_post_type = get_post_type();

        // Check if the Post Types categories/taxonomies option is enabled
        if(isset($this->beca_options['enable-' . $this_posts_post_type])){
            $post_type_categories_enabled = $this->beca_options['enable-' . $this_posts_post_type];
        }

        $this_post_type_taxonomies = get_object_taxonomies($this_posts_post_type);

        $site_post_types = $this->get_site_post_types();
        $this->selected_post_types = $this->get_selected_options($site_post_types);

        // display content if a post type is chosen and if the enable option is selected
        if (in_array(get_post_type(), $this->selected_post_types) && $this->enable == 1) {


            // display content at top or bottom of content....or both top and bottom
            if ($this->bottom == 1 && $this->top == 0) {
                $content .= $this->beca_options['added_content'];
            } elseif ($this->top == 1 && $this->bottom == 0) {
                $content = $this->beca_options['added_content'] . $content;
            } elseif ($this->top == 1 && $this->bottom == 1) {
                $content = $this->beca_options['added_content'] . $content . $this->beca_options['added_content'];
            } else {
                $content = $content;
            }

        }

        // Begin check if post is a single post type, has registered taxonomies, and has the category option enabled
        if(is_single() && !empty($this_post_type_taxonomies) && $post_type_categories_enabled){
            
            $this_post_categories = get_the_category();
            $site_taxonomies = get_taxonomies();
            $this_post_terms = array();
            $category_matches = array();
            $term_matches = array();
            
        
            // Check if the current post has a matched category in the category options
            if( !empty($this_post_categories) ) {
                $post_category_names = $this->get_term_names($this_post_categories);
                $category_matches = array_intersect( $post_category_names, $this->selected_category_names);                
            }

            // Check if the current post has a matched term in the category options
            if( !empty($site_taxonomies) ){

                foreach($site_taxonomies as $site_taxonomy){
                    $current_terms = wp_get_post_terms(get_post()->ID, $site_taxonomy);
                    if($current_terms){
                        foreach ($current_terms as $single_term) {
                            array_push($this_post_terms, $single_term->name );
                        }
                    }
                }

                $term_matches = array_intersect( $this_post_terms, $this->selected_category_names); 
            }

            // If there are matching categories or terms, show the updated content. otherwise, show the original content
            if( $category_matches || $term_matches){
                return $content;
            } else {
                return $base_content;
            }
            

        } else {
            return $content;
        }

    }

    /**
     * Check to see which post types have been selected and store them into the array $selected_post_types
     *
     * @since    1.1.0
     */
    public function get_selected_options($these_post_types) {
        $new_array = array();
        foreach ($these_post_types as $post_type) {

            if (!isset($this->beca_options[$post_type])) {
                $this->beca_options[$post_type] = 0;
            }

            if ($this->beca_options[$post_type] == 1) {
                $new_array[] = $post_type;
            }
        }

        return $new_array;
    }


    /**
     * Builds final layout
     *
     * @since    1.1.0
     */
    public function build_results($content) {

        $this->set_properties();

        // Run wpautop() on content to ensure p tags are inserted
        $new_content = wpautop($this->modify_content($content));

        return $new_content;

    }

    /**
     * Replaces the_content with the modified results from build_results();
     *
     * @since    1.1.0
     */
    public function render_results() {
        add_filter('the_content', [$this, 'build_results']);
    }

}