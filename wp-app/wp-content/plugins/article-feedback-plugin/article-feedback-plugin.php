<?php

/*
Plugin Name: Was this article helpful?
Description: This plugin is made for users who want to know how satisfied their readers are after reading their posts.
Version: 1.0.0
Author: Krisztián Imre
License: GPLv2 or later
Text Domain: article-feedback
*/

// If this file is called firectly, abort

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}


/**
 * The code that runs during plugin activation
 */
function activate_article_feedback_plugin(): void
{
    Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_article_feedback_plugin' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_article_feedback_plugin(): void
{
    Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_article_feedback_plugin' );


$main = new Inc\Init();
$main->init();