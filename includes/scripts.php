<?php 	 

/****************************
* scripts
****************************/

function beca_load_scripts(){
	wp_enqueue_style('beca-styles', plugin_dir_url(__FILE__) . 'css/plugin-styles.css');
}

add_action('wp_enqueue_scripts', 'beca_load_scripts' );


function load_custom_wp_admin_style() {
        
        wp_enqueue_style('beca-styles', plugin_dir_url(__FILE__) . 'css/admin-styles.css');
}

add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

?>

