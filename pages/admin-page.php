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
    <p>Define your relations here.</p>
    <pre>
        <?php // var_dump($post_types); ?>
    </pre>
    <form method="POST">
        <input type="hidden" name="page" value="cpt-relations">
        <input type="hidden" name="action" value="cptr_add_relation">
        <table class="form-table">
            <tr>
                <th>
                    <label for="cptr_name">Relation Name</label>
                </th>
                <td>
                    <input type="text" id="cptr_name" name="cptr[name]" required="required" />
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
                            <option value="<?php echo $key; ?>"><?php echo $post_type->label; ?></option>
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
                            <option value="<?php echo $key; ?>"><?php echo $post_type->label; ?></option>
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
    });
</script>