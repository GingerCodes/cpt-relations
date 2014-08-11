<?php

function cptr_get_relations_ids($cptr_keys, $post_id = null) {
    global $post;
    global $cpt_relations;
    $selectedItemsIDs = false;

    if (is_null($post_id)) {
        $post_id = $post->ID;
        $post_type = $post->post_type;
    } else {
        $post_type = get_post_type($post_id);
    }

    if (is_array($cptr_keys)) {
        foreach ($cpt_relations as $key => $relation) {
            if (!in_array($relation['key'], $cptr_keys)) {
                unset($cpt_relations[$key]);
            }
        }
    } elseif ($cptr_keys == "all") {
        foreach ($cpt_relations as $key => $relation) {
            if ($relation['from_pt'] != $post_type) {
                unset($cpt_relations[$key]);
            }
        }
    } else {
        foreach ($cpt_relations as $key => $relation) {
            if ($relation['key'] != $cptr_keys) {
                unset($cpt_relations[$key]);
            }
        }
    }

    foreach ($cpt_relations as $key => $relation) {
        $selectedItemsIDs[$relation['key']] = get_post_meta($post->ID, $relation['key']);
    }

    return $selectedItemsIDs;
}

function cptr_get_relations($cptr_keys, $relation_info = true, $post_id = null) {
    global $cpt_relations;
    $return_relations = false;
    $allItemsIDs = array();

    $selectedItemsIDs = cptr_get_relations_ids($cptr_keys, $post_id);

    if ($selectedItemsIDs) {
        foreach ($selectedItemsIDs as $key => $selectedItemIDs) {
            $allItemsIDs = array_merge($allItemsIDs, array_values($selectedItemIDs));
        }

        $allItemsIDs = array_unique($allItemsIDs);

        $posts_args = array(
            'post_type' => 'any',
            'post__in' => $allPostsIDs,
            'posts_per_page' => -1,
        );
        $cpt_related_posts = get_posts($posts_args);

        foreach ($selectedItemsIDs as $key => $selectedItemIDs) {
            if ($relation_info) {
                $return_relations[$key]['info'] = $cpt_relations[substr($key, 6)];
            }
            foreach ($selectedItemIDs as $i => $selectedItemID) {
                foreach ($cpt_related_posts as $related_post) {
                    if ($related_post->ID == $selectedItemID) {
                        $return_relations[$key]['posts'][$selectedItemID] = $related_post;
                    }
                }
            }
        }
    }
    return $return_relations;
}
