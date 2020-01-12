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
class Easy_Content_Adder_Public_Display
{

    // Properties
    private $beca_options;
    private $enable;
    private $top;
    private $bottom;
    private $post_types;
    private $selected_post_types;
    private $page_conditionals;

    // Constructor
    public function __construct()
    {
        $this->beca_options = get_option('beca_settings');
        $this->enable = $this->beca_options['enable'];
        $this->bottom = $this->beca_options['bottom'];
        $this->top = $this->beca_options['top'];
        $this->post_types = get_post_types(array('public' => true));
        $this->selected_post_types = array();
    }

    /**
     * Set properties
     *
     * @since    1.1.0
     */
    public function set_properties()
    {
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
     * Modifies the final content based on select settings from /wp-admin/options-general.php?page=beca-options
     *
     * @since    1.1.0
     */
    public function modify_content($content)
    {

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

        return $content;
    }

    /**
     * Check to see which post types have been selected and store them into the array $selected_post_types
     *
     * @since    1.1.0
     */
    public function get_selected_post_types($these_post_types)
    {
        foreach ($these_post_types as $post_type) {

            if (!isset($this->beca_options[$post_type])) {
                $this->beca_options[$post_type] = 0;
            }

            if ($this->beca_options[$post_type] == 1) {
                $this->selected_post_types[] = $post_type;
            }
        }

        return $this->selected_post_types;
    }

    /**
     * Builds final layout
     *
     * @since    1.1.0
     */
    public function build_results($content)
    {

        $this->set_properties;

        $this->get_selected_post_types($this->post_types);

        // Run wpautop() on content to ensure p tags are inserted
        $new_content = wpautop($this->modify_content($content));

        return $new_content;

    }

    /**
     * Replaces the_content with the modified results from build_results();
     *
     * @since    1.1.0
     */
    public function render_results()
    {
        add_filter('the_content', [$this, 'build_results']);
    }

}