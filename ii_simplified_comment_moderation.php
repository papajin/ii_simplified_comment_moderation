<?php
/**
 * @package ii_simplified_comment_moderation
 * @version 1.0
 */
/*
Plugin Name: Simplified Comment Auto Moderation.
Plugin URI: https://papajin.github.io/ii_simplified_comment_moderation/
Description: WP Plugin for comment auto approval. If comment author's email found with already approved comment, the comment will be approved even if the author's name differs.
Version: 1.0
Author URI: https://github.com/papajin
*/


if (! function_exists('ii_simplified_comment_moderation')) {
    /**
     * The function runs almost same query as the native check_comment function
     * but without comment_author in the where statement.
     * We are going to re-check only those comments that are not marked spam and not approved yet, i.e. $approved == 0.
     *
     * @param $approved
     * @param $commentdata
     * @return int result 1 - if such a comment found or still 0 otherwise.
     */
    function ii_simplified_comment_moderation( $approved , $commentdata )
    {
        if($approved === 0) {
            global $wpdb;
            $approved = (int) $wpdb->get_var(
            	$wpdb->prepare(
            		"SELECT comment_approved FROM $wpdb->comments WHERE comment_author_email = %s and comment_approved = '1' LIMIT 1",
		            $commentdata['comment_author_email'] )
            );
        }

        return $approved;
    }
}

add_filter( 'pre_comment_approved' , 'ii_simplified_comment_moderation' , '99', 2 );