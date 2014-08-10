<?php
$ignore_post_types = array(
    'attachment',
    'revision',
    'nav_menu_item',
);
$post_types = get_post_types(array(), 'objects');
$post_types = array_diff_key($post_types, array_flip($ignore_post_types));
?>

<div class='wrap'>
    <h2>Custom Post Type Relations</h2>
    <h3>Existing Relations</h3>
    <table class="wp-list-table widefat fixed posts">
        <thead>
            <tr>
                <th scope="col" class="manage-column">Relation Name</th>
                <th scope="col" class="manage-column">Relation Key</th>
                <th scope="col" class="manage-column">From Post Type</th>
                <th scope="col" class="manage-column">To Post Type</th>
                <th scope="col" class="manage-column"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cpt_relations as $key => $relation) { ?>
                <tr>
                    <td><strong><?php echo $relation['name']; ?></strong></td>
                    <td><?php echo $key; ?></td>
                    <td><?php echo $post_types[$relation['from_pt']]->label; ?></td>
                    <td><?php echo $post_types[$relation['to_pt']]->label; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="cptr[key]" value="<?php echo $key; ?>" />
                            <input type="hidden" name="action" value="delete" />
                            <input type="submit" name="submit" value="Delete" class="button button-cancel"/>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <hr />
    <h3>Define New Relations</h3>
    <?php if (isset($display_result)) { ?>
        <div class="<?php echo $display_result['css_class']; ?>">
            <p><?php echo $display_result['message']; ?></p>
        </div>
    <?php } ?>
    <form method="POST">
        <input type="hidden" name="page" value="cpt-relations">
        <input type="hidden" name="action" value="cptr_add_relation">
        <table class="form-table">
            <tr>
                <th>
                    <label for="cptr_name">Relation Name</label>
                </th>
                <td>
                    <input type="text" id="cptr_name" name="cptr[name]" required="required" value="<?php echo @$_POST['cptr']['name']; ?>"/>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="cptr_from_pt">From Post Type</label>
                </th>
                <td>
                    <select id="cptr_from_pt" name="cptr[from_pt]" required="required" >
                        <option value="">Select One</option>
                        <?php foreach ($post_types as $key => $post_type) { ?>
                            <option value="<?php echo $key; ?>" <?php echo $key == @$_POST['cptr']['from_pt'] ? 'selected' : '' ?>><?php echo $post_type->label; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="cptr_to_pt">To Post Type</label>
                </th>
                <td>
                    <select id="cptr_to_pt" name="cptr[to_pt]" required="required" >
                        <option value="">Select One</option>
                        <?php foreach ($post_types as $key => $post_type) { ?>
                            <option value="<?php echo $key; ?>" <?php echo $key == @$_POST['cptr']['to_pt'] ? 'selected' : '' ?>><?php echo $post_type->label; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
</div>



<script type="text/javascript">
    jQuery(document).ready(function() {

        jQuery("#cptr_from_pt").change(function() {
            jQuery("#cptr_to_pt option").removeAttr('disabled');
            fromPostType = (jQuery("#cptr_from_pt option:selected").text());
            toPostType = (jQuery("#cptr_to_pt option:selected").text());
            if (fromPostType == toPostType) {
                jQuery("#cptr_to_pt").prop('selectedIndex', 0);
            }
            jQuery("#cptr_to_pt option:contains('" + fromPostType + "')").attr("disabled", "disabled");
        });
        jQuery("#cptr_from_pt").change();
    });

</script>