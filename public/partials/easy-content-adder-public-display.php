<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://byronj.me
 * @since      1.1.0
 *
 * @package    Easy_Content_Adder
 * @subpackage Easy_Content_Adder/public/partials
 */

 
/****************************
* Our display functions for outputting info
****************************/

$public_display = new Easy_Content_Adder_Public_Display();
$public_display->render_results();

?>