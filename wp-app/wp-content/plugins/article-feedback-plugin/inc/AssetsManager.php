<?php

namespace Inc;

class AssetsManager
{
    private $plugin_url;

    public function __construct() {
        // This will correctly point to the main plugin directory
        $this->plugin_url = plugin_dir_url( dirname( __FILE__, 1 ) );
    }

    public function init()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }


    /**
     * Enqueue scripts and styles
     *
     * @return void
     */
    public function enqueue_scripts(): void
    {
        global $post;
        wp_enqueue_style('feedback-plugin-style', $this->plugin_url. 'assets/article-feedback.css');
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');

        wp_enqueue_script('custom-vote-script', $this->plugin_url . 'assets/article-feedback.js', ['jquery'], '', true);
        wp_localize_script('custom-vote-script', 'ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'post_id'  => $post->ID,
        ]);
    }
}