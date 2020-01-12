<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://byronj.me
 * @since      1.1.0
 *
 * @package    Easy_Content_Adder
 * @subpackage Easy_Content_Adder/admin/partials
 */

 	// content shown on settings page 
	$public_display = new Easy_Content_Adder_Admin_Display();
	$render_results = $public_display->render_results();
	
	return $render_results;
	
?>