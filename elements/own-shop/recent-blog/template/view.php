<?php

    $settings = $this->get_settings();
    $id = $this->get_id();

    extract(shortcode_atts(array(
            'posts_count' => '3',
            'post_cat_slug' => '',
            'post_display_excerpt' => 'false',
            'post_display_readmore' => 'true',
            'post_read_more' => 'READ MORE'
        ), $settings));

    if( is_array($post_cat_slug) && !empty($post_cat_slug) ){
        $post_cat_slug = implode(',', $post_cat_slug);
    }
    else {
        $post_cat_slug='';   
    }

    $out = '[recentblog
        posts_count="'.$posts_count.'"
        post_cat_slug="'.$post_cat_slug.'"
        post_display_readmore="'.$post_display_readmore.'"
        post_read_more="'.$post_read_more.'"
        post_display_excerpt="'.$post_display_excerpt.'"]';
    echo shortcode_unautop(do_shortcode($out));

?>