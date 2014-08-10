jQuery(document).ready(function() {
    console.log("Init");
    var url = ajaxurl + "?action=get_cptr_posts";
    jQuery(".cptr_input_fields").each(function() {
        url = url + "&post_type=" + jQuery(this).attr('data-post-type');
        jQuery(this).tokenInput(url, {
            theme: "facebook",
            preventDuplicates: true,
            allowFreeTagging: true,
//        tokenValue: "ID",
//        propertyToSearch: "post_title",
        });
    })



//    jQuery.each(prepopulatedData, function(field, artists) {
//        console.log(field + ": " + artists);
//        jQuery.each(artists, function(index, artist) {
//            jQuery("#" + field).tokenInput('add', artist);
//            console.log(index + ": " + artist);
//        })
//
//    });
});