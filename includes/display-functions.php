<?php 	 

/****************************
* Our display functions for outputting info
****************************/

function beca_add_content($content){

	global $beca_options;
	global $post_types; 

	if ( ! isset( $beca_options['enable'] ) ) {
			$beca_options['enable'] = 0;
	}
	
	// check to see which post types have been selected and store them into the array $selected_post_types.
	$selected_post_types = array();
	foreach ( $post_types as $post_type ) { 

		if ( ! isset( $beca_options[$post_type] ) ) {
			$beca_options[$post_type] = 0;
		}

		if($beca_options[$post_type] == 1) {
			$selected_post_types[] =  $post_type;
		}
	 }
	

	if( 
		
		in_array(get_post_type(), $selected_post_types) &&
		$beca_options['enable'] == 1  
	){
		
		$content .= $beca_options['added_content'];
	}

	return $content;
}

add_filter('the_content', 'beca_add_content' );
?>

