<?php

    $settings = $this->get_settings();
    $id = $this->get_id();

    extract(shortcode_atts(array(
            'show_section_title' => 'true', 
            'section_title' => '',
            'section_title_size' => 'h2',
            'post_count' => '4',
            'post_columns' => 'span6',
            'post_style' => 'style_1',
            'post_orderby' => 'date',
            'post_order' => 'DESC',
            'post_cat_slug' => '',
            'post_ids' => '',
            'post__not_in' => '',
            'post_thumbsize'     => 'post-thumbnail',
            'post_content_show' => 'true',
            'post_excerpt_count' => '15',
            'post_display_categories' => 'true',
            'post_display_date' => 'true',
            'post_display_author' => 'true',
            'post_display_author_pre_text' => 'By',
            'post_display_comments' => 'true',
            'post_show_readmore' => 'false',
            'post_readmore_text' => 'Read More',
            'post_ignore_featured' => 'true',
            'post_text_position' => 'bottomcenter',
            'post_trim_title' => 'true',
            'post_trim_title_count' => '7'
        ), $settings));

    $post_ids = $post_ids != '' ? $post_ids : array();
    $post_ids = implode(',', $post_ids);

    if( is_array($post_cat_slug) && !empty($post_cat_slug) ){
        $post_cat_slug = implode(',', $post_cat_slug);
    }
    else {
        $post_cat_slug='';   
    }

    $post__not_in = $post__not_in != '' ? $post__not_in : array();
    $post__not_in = implode(',', $post__not_in);

    $out = '[gridposts
        show_section_title="'.$show_section_title.'"
        section_title="'.$section_title.'" 
        section_title_size="'.$section_title_size.'" 
        post_count="'.$post_count.'"
        post_columns="'.$post_columns.'" 
        post_style="'.$post_style.'" 
        post_ids="'.$post_ids.'" 
        post_orderby="'.$post_orderby.'" 
        post_order="'.$post_order.'" 
        post_cat_slug="'.$post_cat_slug.'" 
        post__not_in="'.$post__not_in.'" 
        post_thumbsize="'.$post_thumbsize.'"
        post_content_show="'.$post_content_show.'"
        post_excerpt_count="'.$post_excerpt_count.'" 
        post_display_categories="'.$post_display_categories.'" 
        post_display_date="'.$post_display_date.'"
        post_display_author="'.$post_display_author.'"
        post_display_author_pre_text="'.$post_display_author_pre_text.'" 
        post_display_comments="'.$post_display_comments.'" 
        post_show_readmore="'.$post_show_readmore.'" 
        post_readmore_text="'.$post_readmore_text.'"
        post_ignore_featured="'.$post_ignore_featured.'"
        post_trim_title="'.$post_trim_title.'"
        post_trim_title_count="'.$post_trim_title_count.'"
        post_text_position="'.$post_text_position.'"]';


    echo shortcode_unautop(do_shortcode($out));

?>
    