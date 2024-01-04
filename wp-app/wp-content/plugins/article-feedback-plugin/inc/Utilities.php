<?php

namespace Inc;

class Utilities
{
    /**
     * Fetch and hash the user's IP address.
     *
     * @return string The hashed IP address.
     */
    public static function getUserFingerprint()
    {
        return md5($_SERVER['REMOTE_ADDR']);
    }


    /**
     * Check if the user has voted on a post.
     *
     * @param int $postId The ID of the post.
     * @param string $userFingerprint The user's fingerprint.
     * @return bool|string False if not voted, otherwise the vote type.
     */
    public static function checkIfUserHasVoted($postId, $userFingerprint)
    {
        return get_post_meta($postId, 'voted_' . $userFingerprint, true);
    }


    /**
     * Calculates the positive and negative voting percentages for a given post.
     *
     * This function retrieves the count of positive and negative votes stored in post meta and calculates the total value of it.
     *
     * @param $post_id
     * @return array An associative array with 'positive' and 'negative' keys and their corresponding percentage values.
     */
    public static function get_voting_numbers($post_id): array
    {
        $positive_count = (int)get_post_meta($post_id, 'positive_count', true);
        $negative_count = (int)get_post_meta($post_id, 'negative_count', true);
        $total_votes = $positive_count + $negative_count;

        $positive_percentage = ($total_votes > 0) ? round(($positive_count / $total_votes) * 100, 0) : 0;
        $negative_percentage = ($total_votes > 0) ? round(($negative_count / $total_votes) * 100, 0) : 0;

        return [
            'positive'       => $positive_percentage,
            'negative'       => $negative_percentage,
            'positive_count' => $positive_count,
            'negative_count' => $negative_count,
            'total_count'    => $total_votes,
        ];
    }
}