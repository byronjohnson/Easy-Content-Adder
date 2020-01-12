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
    private $enable;
    private $top;
    private $bottom;
    private $post_types;
	

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1.0
	 */
	public function __construct() {
        $this->beca_options = get_option('beca_settings');
        $this->enable = $this->beca_options['enable'];
        $this->bottom = $this->beca_options['bottom'];
        $this->top = $this->beca_options['top'];
        $this->post_types = get_post_types(array('public' => true));

    }


    /**
	 * Creates Enable option
	 *
	 * @since    1.1.0
	 */
    public function render_enable_option(){
        ob_start();

        ?>
            <h4><?php _e('Enable', 'beca_domain' ); ?></h4>
            <p>
                <?php 
                    // If "Turn content on" is not selected, set input value to
                    if ( ! isset( $this->beca_options['enable'] ) )
                        $this->beca_options['enable'] = 0;
                    ?>
                <input id="beca_settings[enable]" name="beca_settings[enable]" type="checkbox" value="1" <?php checked($this->beca_options['enable'], '1', true ); ?> />
                <label class="description" for="beca_settings[enable]">
                    <?php _e('Turn content on.', 'beca_domain'); ?>
                </label>

            </p>
        <?php
        
        echo ob_get_clean();
    }

    /**
	 * Creates Post Type checkbox options
	 *
	 * @since    1.1.0
	 */
    public function render_post_type_options(){
        ob_start();
        ?>
            <h4><?php _e('Select which type of pages to display content on.', 'beca_domain' ); ?></h4>
				<?php // get all post types that are public
					

					foreach ( $this->post_types as $post_type ) { 
					   // If no post type is selected, set input value to 0
						if ( ! isset( $this->beca_options[$post_type] ) )
							$this->beca_options[$post_type] = 0;
						?>
					<input id="beca_settings[<?php echo $post_type ?>]" name="beca_settings[<?php echo $post_type ?>]" type="checkbox" value="1" <?php checked($this->beca_options[$post_type], '1', true ); ?> />
					<label class="description" for="beca_settings[<?php echo $post_type ?>]">
						<?php _e($post_type, 'beca_domain'); ?>
					</label>
					<br/>
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
        <h4><?php _e('Select whether to add to top or bottom of post/pages. You can also select both to have the content show at the top and bottom.', 'beca_domain' ); ?></h4>
				<p>
					<?php 
						// If "Turn content on" is not selected, set input value to
						if ( ! isset( $this->beca_options['top'] ) )
							$this->beca_options['top'] = 0;
						if ( ! isset( $this->beca_options['bottom'] ) )
							$this->beca_options['bottom'] = 0;
						?>
					<input id="beca_settings[top]" name="beca_settings[top]" type="checkbox" value="1" <?php checked($this->beca_options['top'], '1', true ); ?> />
					<label class="description" for="beca_settings[top]">
						<?php _e('Add to top', 'beca_domain'); ?>
					</label>
					<br/>
					<input id="beca_settings[bottom]" name="beca_settings[bottom]" type="checkbox" value="1" <?php checked($this->beca_options['bottom'], '1', true ); ?> />
					<label class="description" for="beca_settings[bottom]">
						<?php _e('Add to bottom', 'beca_domain'); ?>
					</label>

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
                <h4><?php _e('Enter content below', 'beca_domain' ); ?></h4>
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

                        <?php $this->render_post_type_options(); ?>
                    
                        <hr class="beca_divider" />

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