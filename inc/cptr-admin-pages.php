<?php

add_action('admin_menu', 'cptr_regsiter_admin_page');

function cptr_regsiter_admin_page() {
    $page_title = "Custom Post Type Relations";
    $menu_title = "CPT Relations";
    $capability = "manage_options";
    $menu_slug = "cpt-relations";
    $function = "cpt_relations";
    $icon_url = "dashicons-networking";
    $position = "101.2";

    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
}

function cpt_relations() {
    $cpt_relations = get_option('cpt_relations');
    if (!$cpt_relations) {
        $cpt_relations = array();
    }
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'cptr_add_relation':
                $relation_key = sanitize_title($_POST['cptr']['name']);
                if (!isset($cpt_relations[$relation_key])) {
                    $_POST['cptr']['key'] = $relation_key;
                    $cpt_relations[$relation_key] = $_POST['cptr'];
                    if (update_option('cpt_relations', $cpt_relations)) {
                        $display_result = array(
                            'css_class' => 'updated',
                            'message' => 'Relation Added Successfully.'
                        );
                        unset($_POST);
                    } else {
                        $display_result = array(
                            'css_class' => 'error',
                            'message' => 'Error!!! Unable To Add New Relation.'
                        );
                    }
                } else {
                    $display_result = array(
                        'css_class' => 'error',
                        'message' => 'Error!!! Given Relation Name Already Exists.'
                    );
                }
                break;
            case 'cptr_delete':
                unset($cpt_relations[$_POST['cptr']['key']]);
                if (update_option('cpt_relations', $cpt_relations)) {
                    $display_result = array(
                        'css_class' => 'updated',
                        'message' => 'Relation Deleted Successfully.'
                    );
                    unset($_POST);
                } else {
                    $display_result = array(
                        'css_class' => 'error',
                        'message' => 'Error!!! Unable To Delete Relation.'
                    );
                }
                break;
        }
    }
    include CPTR_PLUGIN_DIR . '/pages/admin-page.php';
}
