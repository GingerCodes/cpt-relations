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
    if (isset($_POST['action'])) {
        $cpt_relations = get_option('cpt_relations');
        if (!$cpt_relations) {
            $cpt_relations = array();
        }
        $cpt_relations[] = $_POST['cptr'];
        if (update_option('cpt_relations', $cpt_relations)) {
            echo 'Success';
        } else {
            echo 'Failed';
        }
    }
    include CPTR_PLUGIN_DIR . '/pages/admin-page.php';
}

function cptr_add_relation() {
    echo 'Form Submitted';
}
