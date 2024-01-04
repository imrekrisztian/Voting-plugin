<?php

namespace Inc;

use WP_Post;

class MetaBoxManager
{
    public function init(): void
    {
        add_action('add_meta_boxes', [$this, 'add_voting_meta_box']);
    }


    /**
     * Adds a custom meta box to the post edit screen in the WordPress admin.
     */
    public function add_voting_meta_box(): void
    {
        add_meta_box(
            'voting_meta_box',                      // ID of the meta box
            'Voting Results',                       // Title of the meta box
            [$this, 'render_voting_meta_box'],      // Callback function to render the contents of the meta box
            'post',                                 // The screen or post type where the meta box should appear
            'normal',                               // The context within the screen where the boxes should display
            'high'                          // The priority within the context where the boxes should show
        );
    }


    /**
     * Renders the content inside the voting results meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_voting_meta_box(WP_Post $post): void
    {
        $percentages = Utilities::get_voting_numbers($post->ID);

        echo sprintf(
            '<p>Positive Percentage: %d%% (%d)</p>',
            $percentages['positive'],
            $percentages['positive_count']
        );

        echo sprintf(
            '<p>Negative Percentage: %d%% (%d)</p>',
            $percentages['negative'],
            $percentages['negative_count']
        );

        echo sprintf(
            '<p>Total number of votes: %d</p>',
            $percentages['total_count']
        );
    }
}