<?php

namespace Inc;

class VotingHandler
{

    public function init()
    {
        add_action('the_content', [$this, 'add_voting_area_to_post']);
        add_action('wp_ajax_custom_vote_action', [$this, 'handle_voting_ajax']);
        add_action('wp_ajax_nopriv_custom_vote_action', [$this, 'handle_voting_ajax']);
    }


    /**
     * Adds a voting area at the end of the post content.
     *
     * @param string $content The original post content.
     * @return string The modified content with voting area.
     */
    public function add_voting_area_to_post($content)
    {
        if (!is_single()) {
            return $content;
        }

        global $post;
        $userFingerprint = Utilities::getUserFingerprint();
        $hasVoted = Utilities::checkIfUserHasVoted($post->ID, $userFingerprint);
        $voting_numbers = Utilities::get_voting_numbers($post->ID);

        $disabled = '';
        $yesStatus = '';
        $noStatus = '';
        $positive = 'Yes';
        $negative = 'No';
        $articleText = "Was this article helpful?";

        if ($hasVoted) {
            $disabled = 'disabled';
            $yesStatus = ($hasVoted === 'vote-yes') ? 'voted' : '';
            $noStatus = ($hasVoted === 'vote-no') ? 'voted' : '';
            $positive = $voting_numbers['positive'] . '%';
            $negative = $voting_numbers['negative'] . '%';
            $articleText = "Thank you for your feedback.";
        }

        if (is_single()) {
            $content .= $this->buildHtmlArea($articleText, $yesStatus, $disabled, $positive, $noStatus, $negative);
        }

        return $content;
    }

    /**
     * Builds the HTML for the voting area.
     *
     * @return string The HTML content.
     */
    public function buildHtmlArea(string $articleText, string $yesStatus, string $disabled, string $positive, string $noStatus, string $negative): string
    {
        return sprintf(
            '<div class="article-vote-box">
            <div class="question-title">
                <p>%s</p>
            </div>
            <div class="article-vote-buttons-box">
                <button id="vote-yes" class="article-vote-button %s" %s><i class="fa-solid fa-face-smile"></i><span>%s</span></button>
                <button id="vote-no" class="article-vote-button %s" %s><i class="fa-solid fa-face-frown-open"></i><span>%s</span></button>
            </div>
        </div>',
            $articleText,
            $yesStatus,
            $disabled,
            $positive,
            $noStatus,
            $disabled,
            $negative,
        );
    }


    /**
     * Handles the AJAX request for post voting.
     *
     * This function processes votes on posts, updates the post metadata,
     * and returns the updated voting percentages and vote type in JSON format.
     */
    public function handle_voting_ajax(): void
    {

        // Ensure required data is set
        if (!isset($_POST['post_id'], $_POST['vote_type'])) {
            wp_die('Missing required parameters', 400);
        }

        $post_id = intval($_POST['post_id']);
        $user_fingerprint = md5($_SERVER['REMOTE_ADDR']);
        $vote_type = sanitize_text_field($_POST['vote_type']);

        // Check if user has already voted
        $has_voted = get_post_meta($post_id, 'voted_' . $user_fingerprint, true);
        if (!$has_voted) {
            $this->get_vote_type($post_id);
            update_post_meta($post_id, 'voted_' . $user_fingerprint, $vote_type);
        }

        // Get updated voting numbers and percentages
        $percentages = Utilities::get_voting_numbers($post_id);
        $response = [
            'positive_percentage' => $percentages['positive'],
            'negative_percentage' => $percentages['negative'],
            'vote_type'           => $vote_type,
        ];

        wp_send_json($response);
        wp_die();
    }

    /**
     * Handles the voting logic for a post based on the vote type.
     *
     * @param int $post_id The ID of the post being voted on.
     * @return mixed The type of vote submitted, or null if no valid vote was submitted.
     */
    public function get_vote_type($post_id): mixed
    {
        if (!isset($_POST['vote_type'])) {
            return null;
        }

        $vote_type = $_POST['vote_type'];

        switch ($vote_type) {
            case 'vote-yes':
                $positive_count = (int)get_post_meta($post_id, 'positive_count', true);
                update_post_meta($post_id, 'positive_count', $positive_count + 1);
                break;
            case 'vote-no':
                $negative_count = (int)get_post_meta($post_id, 'negative_count', true);
                update_post_meta($post_id, 'negative_count', $negative_count + 1);
                break;
            default:
                // Handle unexpected vote type
                return null;
        }

        return $vote_type;
    }

}