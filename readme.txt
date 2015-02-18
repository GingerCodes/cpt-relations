=== Custom Post Type Relations ===
Contributors: alxjos, rogin
Tags: post, relations, custom post type
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Define relations with different post types in WordPress and access the related 
posts in the template files.

== Description ==

Custom Post Type (CPT) Relations plugin can be used to create relations within 
different post types in your WordPress.

CPT Relations will list all the registered post types in the wordpress and you 
can create any number of relations for any post type. Once a relation is created, 
you will see a metabox with autocomplete field in the Add New page of the 
selected post type. The field will populate the via AJAX while you type the 
post name you want to related.


== Installation ==
* Upload `cptr-relation` folder to the `/wp-content/plugins/` directory
* Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Is there any limit for the number of relations that can be created? =

No. You can create any number of relations as much as you need.

= How can you get the relations data in the template files =

There are two theme function provided with the plugin for access the relations 
data. They are `cptr_get_relations_ids` and `cptr_get_relations`.

`cptr_get_relations_ids($cptr_keys, $post_id = null)`
    

`$cptr_keys` can be string or array. You will get the key of each relation in the admin 
page for CPT Relation plugin. This argument can also have the value `all` 
which will return all the defined relations for that post.

$post_id should be int. If this is not provided current post is considered.

= Where to submit any issues and get support? =

CPT Relations in an open source project hosted in the GitHub. You can raise any issues at https://github.com/GingerCodes/cpt-relations/issues.