<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://byronj.me
 * @since      1.1.0
 *
 * @package    Easy_Content_Adder
 * @subpackage Easy_Content_Adder/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Easy_Content_Adder
 * @subpackage Easy_Content_Adder/admin
 * @author     Byron Johnson <me@byronj.me>
 */
class Easy_Content_Adder_Admin_Display {

    // Properties
    private $beca_options;
    private $post_types;
    private $categories;
    private $category_names;
	

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1.0
	 */
	public function __construct() {
        $this->beca_options = get_option('beca_settings');
        $this->post_types = get_post_types(array('public' => true));
        $this->categories = get_terms(array(
            'hide_empty' => false,
        ));
        $this->category_names = $this->get_term_names($this->categories);

    }


    /**
	 * Creates Enable option
	 *
	 * @since    1.1.0
	 */
    public function render_enable_option(){
        ob_start();

        ?>
            <h3><?php _e('Enable', 'beca_domain' ); ?></h3>
            <p>
                <?php 
                    // If "Turn content on" is not selected, set input value to
                    if ( ! isset( $this->beca_options['enable'] ) )
                        $this->beca_options['enable'] = 0;
                    ?>
                <input id="beca_settings[enable]" name="beca_settings[enable]" type="checkbox" value="1" <?php checked($this->beca_options['enable'], '1', true ); ?> />
                <label class="description" for="beca_settings[enable]">
                    <?php _e('Turn the content on.', 'beca_domain'); ?>
                </label>

            </p>
        <?php
        
        echo ob_get_clean();
    }


    /**
	 * Creates an array of term names
	 *
	 * @since    1.1.1
	 */
    public function get_term_names($terms){
        $new_terms = array();
        foreach($terms as $term){
            array_push( $new_terms, $term->name );
        }

        return $new_terms;
    }
    

    /**
	 * Creates Post Type checkbox options
	 *
	 * @since    1.1.0
	 */
    public function render_checkbox_options($item_array, $section_title, $is_post_types){
        ob_start();
        ?>
                <h4><?php _e($section_title, 'beca_domain' ); ?></h4>
				<?php 
			
					foreach ( $item_array as $item ) { 

					   // If no post type is selected, set input value to 0
						if ( ! isset( $this->beca_options[$item] ) ) {
                            $this->beca_options[$item] = 0;
                        }

                        // If is post types, get the plural name instead
                        if($is_post_types){
                            $post_type_name = get_post_type_object($item);
                            $plural_name = $post_type_name->labels->name;
                        }
				?>
                        <div class="checkbox-container">
                            <input id="beca_settings[<?php echo $item ?>]" name="beca_settings[<?php echo $item ?>]" type="checkbox" value="1" <?php checked($this->beca_options[$item], '1', true ); ?> />
                            <label class="description" for="beca_settings[<?php echo $item ?>]">
                                <?php
                                    if($is_post_types){
                                        echo $plural_name;
                                    } else {
                                        _e(ucfirst($item), 'beca_domain'); 
                                    }
                                ?>
                            </label>
                        </div>
					<?php }
                
        echo ob_get_clean();
    }
    


    /**
	 * Creates Add To Top and Add To Bottom checkbox options
	 *
	 * @since    1.1.0
	 */
    public function render_top_bottom_option(){
        ob_start();
        ?>
        <h4><?php _e('Select where to place the content on each post.', 'beca_domain' ); ?></h4>
				<p>
					<?php 
						// If "Turn content on" is not selected, set input value to
						if ( ! isset( $this->beca_options['top'] ) )
							$this->beca_options['top'] = 0;
						if ( ! isset( $this->beca_options['bottom'] ) )
							$this->beca_options['bottom'] = 0;
						?>
                    <div class="checkbox-container">
                        <input id="beca_settings[top]" name="beca_settings[top]" type="checkbox" value="1" <?php checked($this->beca_options['top'], '1', true ); ?> />
                        <label class="description" for="beca_settings[top]">
                            <?php _e('Add to the top of each post.', 'beca_domain'); ?>
                        </label>
                    </div>
                    <div class="checkbox-container">
                        <input id="beca_settings[bottom]" name="beca_settings[bottom]" type="checkbox" value="1" <?php checked($this->beca_options['bottom'], '1', true ); ?> />
                        <label class="description" for="beca_settings[bottom]">
                            <?php _e('Add to the bottom of each post.', 'beca_domain'); ?>
                        </label>
                    </div>

                </p>
        <?php

        echo ob_get_clean();
    }


    /**
	 * Creates the content editor
	 *
	 * @since    1.1.0
	 */
    public function render_content_editor(){
        ob_start();

    ?>
                <h3 style="margin-bottom: 15px;"><?php _e('Enter the content below', 'beca_domain' ); ?></h3>
				<?php 
					$content = 'beca_settings[added_content]';
					$args = array("textarea_name" => "beca_settings[added_content]");
					$editor_id = 'added_content';

					// If editor is has no content, set the content area to blank
					if ( ! isset( $this->beca_options['added_content'] ) ) {
						$this->beca_options['added_content'] = " ";
					}

					wp_editor(  $this->beca_options['added_content'], $editor_id, $args ); 
				?>
				<p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Options', 'beca_domain');  ?> "/>
                </p>
        <?php 
        echo ob_get_clean();
    }
    
    public function build_form(){
        ob_start(); 
    ?>
            <div class="wrap">	
                    <h2> 
                        <?php  _e('Easy Content Adder Options') ?>
                    </h2>
                    <form method="post" action="options.php">

                        <?php 
                            // Load form settings
                            settings_fields('beca_settings_group'); 
                        ?>
                        
                        <?php $this->render_enable_option(); ?>

                        <hr class="beca_divider" />

                        <h3><?php  _e('Post Types') ?></h3>
                        <?php $this->render_checkbox_options($this->post_types, 'Select which type of posts to display the content on.', true); ?>

                        <hr class="beca_divider" />

                        <h3><?php  _e('Categories and Taxonomies') ?></h3>
                        <?php $this->render_checkbox_options($this->category_names, 'Select which categories and/or taxonomies to display the content on. These options will apply if Posts or a custom post type is selected above.', false); ?>

                        <hr class="beca_divider" />

                        <h3><?php  _e('Content Location') ?></h3>
                        <?php $this->render_top_bottom_option(); ?>

                        <hr class="beca_divider" />

                        <?php $this->render_content_editor(); ?>

                </form>
            </div>
                    
        <?php 
        
		// Outpout HTML
		echo ob_get_clean();
    }

    // create options page under settings menu
	public function add_options_link() {
		add_options_page('Easy Content Adder Options', 'Easy Content Adder', 'manage_options', 'beca-options', [$this, 'build_form'] );
    }
    

	// register options page under settings menu
	public function register_settings(){
		register_setting('beca_settings_group', 'beca_settings');
    }
    
    
    /**
	 * Add menu and settings
	 *
	 * @since    1.1.0
	 */
    public function render_results() {
        add_action('admin_menu',  [ $this, 'add_options_link' ]);
        add_action('admin_init',  [ $this, 'register_settings']);
    }

}