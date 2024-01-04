<?php

/**
 * Trigger this file on Plugin uninstall (otherwise an uninstallation function should be static)
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

global $wpdb;

$post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = 'post'");

// Remove all post meta that starts with 'voted_'
$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE 'voted_%'");

foreach ($post_ids as $post_id) {
    delete_post_meta($post_id, 'positive_count');
    delete_post_meta($post_id, 'negative_count');
}


