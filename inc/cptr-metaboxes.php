<?php
add_action('add_meta_boxes', 'cptr_add_metaboxes');

function cptr_add_metaboxes() {
    global $cpt_relations;
    global $post;
    $relation_exists = false;
    foreach ($cpt_relations as $key => $relation) {
        if ($relation['from_pt'] != $post->post_type) {
            unset($cpt_relations[$key]);
        }
    }
    if (!empty($cpt_relations)) {
        $id = "cptr-box";
        $title = "CPT Relations";
        $callback = "cpt_relations_metabox";
        $post_type = $post->post_type;
//        $context = "";
//        $priority = "";
//        $callback_args = "";

        add_meta_box($id, $title, $callback, $post_type);
    }
}

function cpt_relations_metabox($post) {
    global $wpdb;
    global $cpt_relations;

    $selectedPosts = array();
    $allPostsIDs = array();
//    foreach ($cpt_relations as $relation) {
//        $selectedPosts[$fieldName] = get_post_meta($post->ID, $fieldName);
//        if (!empty($selectedPosts[$fieldName])) {
//            $allPostsIDs = array_merge($allPostsIDs, array_values($selectedPosts[$fieldName]));
//        }
//    }
//
//    $posts_args = array(
//        'post_type' => 'artist',
//        'post__in' => $allPostsIDs,
//        'posts_per_page' => -1,
//    );
//    $cpt_related_posts = get_posts($posts_args);
    ?>
    <table class="form-table">
        <?php
        foreach ($cpt_relations as $key => $relation) {
//        var_dump($artists[$fieldName]);
//        if (!empty($selectedPosts[$fieldName])) {
////            var_dump($fieldName);
//            foreach ($selectedPosts[$fieldName] as $selectedArtist) {
////                var_dump($artist);
//                foreach ($artists as $artist) {
//                    if ($artist->ID == $selectedArtist) {
//                        $temp = array('id' => $artist->ID, 'name' => $artist->post_title);
////                        var_dump("Temp : " . $temp);
//                        $prepopulatedData[$fieldName][] = $temp;
//                    }
//                }
//            }
//        }
            ?>
            <tr>
                <td>
                    <label for="<?php echo $relation['key']; ?>"><?php echo ucfirst($relation['name']); ?></label>
                </td>
                <td>
                    <input type="text" name="<?php echo $relation['key']; ?>" id="<?php echo $relation['key']; ?>" class="cptr_input_fields" data-post-type="<?php echo $relation['to_pt']; ?>"/>
                </td>
            </tr>
        <?php }
        ?>
    </table>
    <?php
}
