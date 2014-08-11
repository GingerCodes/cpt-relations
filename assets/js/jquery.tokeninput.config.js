jQuery(document).ready(function() {
    console.log("Init");
    var url = ajaxurl + "?action=get_cptr_posts";
    jQuery(".cptr_input_fields").each(function() {
        url = url + "&post_type=" + jQuery(this).attr('data-post-type');
        jQuery(this).tokenInput(url, {
            theme: "facebook",
            preventDuplicates: true,
            allowFreeTagging: true,
        });
    })



    jQuery.each(prepopulatedData, function(field, items) {
        console.log(field + ": " + items);
        jQuery.each(items, function(index, item) {
            jQuery("#" + field).tokenInput('add', item);
            console.log(index + ": " + item);
        })
    });
});