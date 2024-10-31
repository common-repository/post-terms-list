=== Post Terms List ===
Contributors: dat
Donate link: http://dat.perdomani.net/
Tags: post, keywords, terms, list, seo, text
Requires at least: 2.0
Tested up to: 2.5
Stable tag: 0.2

Outputs a list of post-relevant keywords different from the categories. (Maybe useful for SEO).

== Description ==

Analyze a post and outputs the most frequent terms.
This plugin is *heavily based* on [Rob Marsh's Similar Posts plugin](http://rmarsh.com/plugins/similar-posts/) from which is a stripped down version for my personal purposes.
Useful to have a list of related terms different from the categories.

More info and usage on [dat's blog plugin page](http://dat.perdomani.net/post-term-list-plugin/ "dat blog").

== Installation ==

1. Upload `Post_Terms_List.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php if (function_exists(post_term_list)) {	post_term_list($post->ID); } ?>` in your Loop

== Frequently Asked Questions ==

= How I change stop words? =

Change $overusedwords array (if you write in english be sure to delete the italian stop words).

= How to change the number of displayed terms? =

In the function the_terms alter the parameter 20, this is the line `$terms = get_post_terms( $content[’post_content’], $content[’post_title’], $options[’utf8′] == ‘true’, $options[’bias_title’], 20)`.

== Screenshots ==

1. In the red box an example usage of the output of the plugin.

