<?php

namespace Inc;

use WP_Post;

class MetaBoxManager
{
    public function init()
    {
        add_action('add_meta_boxes', [$this, 'add_voting_meta_box']);
    }


    /**
     * Adds a custom meta box to the post edit screen in the WordPress admin.
     */
    public function add_voting_meta_box(): void
    {
        add_meta_box(
            'voting_meta_box',                  // ID of the meta box
            'Voting Results',                   // Title of the meta box
            [$this ,'render_voting_meta_box'],      // Callback function to render the contents of the meta box
            'post',                         // The screen or post type where the meta box should appear
            'normal',                       // The context within the screen where the boxes should display
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

        echo '<p>Positive Percentage: ' . $percentages['positive'] . '%  (' . $percentages['positive_count'] . ')</p>';
        echo '<p>Negative Percentage: ' . $percentages['negative'] . '%  (' . $percentages['negative_count'] . ')</p>';
        echo '<p>Total number of votes: ' . $percentages['total_count'] . '</p>';
    }
}