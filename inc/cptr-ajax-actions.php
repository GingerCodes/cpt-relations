<?php

add_action('wp_ajax_get_cptr_posts', 'get_cptr_posts');

function get_cptr_posts() {
    global $wpdb;
    if (isset($_GET['q']) && isset($_GET['post_type'])) {
        $q = $_REQUEST['q'];
        $post_type = $_GET['post_type'];

        $args = array(
            's' => $q,
            'post_type' => $post_type,
            'post_status' => 'publish',
            'order' => 'ASC',
            'orderby' => 'title',
        );


        $posts = new WP_Query($args);
        $result = array();
        foreach ($posts->get_posts() as $post) {
            $result[] = array('id' => $post->ID, 'name' => $post->post_title);
        }

        echo json_encode($result);
        wp_reset_query();
    }
    die();
}
