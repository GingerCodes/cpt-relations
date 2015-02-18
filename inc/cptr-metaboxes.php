<?php
add_action('add_meta_boxes', 'cptr_add_metaboxes');

add_action('save_post', 'cprt_save_relations_metabox_data');

function cptr_add_metaboxes() {
    global $cpt_relations;
    global $post;
    $relation_exists = false;
    if(!empty($cpt_relations)){
        foreach ($cpt_relations as $key => $relation) {
            if ($relation['from_pt'] != $post->post_type) {
                unset($cpt_relations[$key]);
            }
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

    wp_nonce_field('cprt_metabox', 'cprt_metabox_nonce');

    $selectedItems = array();
    $allPostsIDs = array();
    $prepopulatedData = array();
    foreach ($cpt_relations as $key => $relation) {
        $selectedItems[$key] = get_post_meta($post->ID, $relation['key']);
        if (!empty($selectedItems[$key])) {
            $allPostsIDs = array_merge($allPostsIDs, array_values($selectedItems[$key]));
        }
    }
    $allPostsIDs = array_unique($allPostsIDs);

    $posts_args = array(
        'post_type' => 'any',
        'post__in' => $allPostsIDs,
        'posts_per_page' => -1,
    );

    $cpt_related_posts = get_posts($posts_args);
    ?>
    <table class="form-table">
        <?php
        foreach ($cpt_relations as $key => $relation) {
            if (!empty($selectedItems[$key])) {
                foreach ($selectedItems[$key] as $selectedItem) {
                    foreach ($cpt_related_posts as $selectedPost) {
                        if ($selectedPost->ID == $selectedItem) {
                            $temp = array('id' => $selectedPost->ID, 'name' => $selectedPost->post_title);
                            $prepopulatedData[$relation['key']][] = $temp;
                        }
                    }
                }
            }
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
    echo '<script type="text/javascript">';
    echo '  var prepopulatedData= ' . json_encode($prepopulatedData);
    echo '</script>';
}

function cprt_save_relations_metabox_data($post_id) {
    global $cpt_relations;

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */
    // Check if our nonce is set.
    if (!isset($_POST['cprt_metabox_nonce'])) {
        return;
    }

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['cprt_metabox_nonce'], 'cprt_metabox')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (!isset($_POST['post_type']) || (get_post_type($post_id) != $_POST['post_type'] && get_post_type($post_id) != "revision")) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    foreach ($cpt_relations as $key => $relation) {
        if ($relation['from_pt'] != $_POST['post_type']) {
            unset($cpt_relations[$key]);
        }
    }

    foreach ($cpt_relations as $key => $relation) {
        $items = $_POST[$relation['key']];
        $items = explode(",", $items);

        foreach ($items as $key => $item) {
            if (!empty($item) && !is_numeric($item)) {
                $post = array(
                    'post_title' => (ucwords($item)),
                    'post_type' => $relation['to_pt'],
                    'post_status' => 'publish',
                );
                $items[$key] = (string) wp_insert_post($post, $error);
            }
        }

//      Find the new artists added and deleted old artist by comparing the
//      new artist list with the old artists list stored in the table if any.
        $old_items = get_post_meta($post_id, $relation['key']);
        $items_to_add = array_diff($items, $old_items);
        $items_to_delete = array_diff($old_items, $items);

        if (count($old_items) == count($items_to_delete)) {
//          All Artists Are New. Delete All At Once & Add One By One
            delete_post_meta($post_id, $relation['key']);
        } else {
//          Delete the artists present in db but not in submitted form
            foreach ($items_to_delete as $item) {
                delete_post_meta($post_id, $relation['key'], $item);
            }
        }

//      Add the new artist submitted in form one by one
        foreach ($items_to_add as $item) {
            add_post_meta($post_id, $relation['key'], $item);
        }
    }
}
