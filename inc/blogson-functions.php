<?php
/**
 *
 * @package spiraclethemes-site-library
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) :
    die;
endif;


/**
 *  Set theme classname
 */

function spiraclethemes_site_library_admin_classes( $classes ) {
    global $pagenow;
    $classes .= 'theme-blogson';
    return $classes;
}

add_filter( 'admin_body_class', 'spiraclethemes_site_library_admin_classes' );


/**
 *  Set Import files
 */


if ( ! function_exists( 'spiraclethemes_site_library_blogson_set_import_files' ) ) :
function spiraclethemes_site_library_blogson_set_import_files() {

    $customizer_blogson_demo1 = spiraclethemes_site_library_api_data('blogson', 'demo1', 'customizer');
    $widgets_blogson_demo1 = spiraclethemes_site_library_api_data('blogson', 'demo1', 'widgets');
    $content_blogson_demo1 = spiraclethemes_site_library_api_data('blogson', 'demo1', 'content');
    $image_blogson_demo1 = spiraclethemes_site_library_api_data('blogson', 'demo1', 'image');

    return array(
        array(
            'import_file_name'           => esc_html__('Demo 1', 'spiraclethemes-site-library'),
            'import_file_url'          => $content_blogson_demo1,
            'import_widget_file_url'   => $widgets_blogson_demo1,
            'import_customizer_file_url' => $customizer_blogson_demo1,
            'import_preview_image_url'     => $image_blogson_demo1,
            'import_notice'              => esc_html__( 'After you import this demo, you will have to change some menu links. Please check documentation for more information', 'spiraclethemes-site-library' ),
            'preview_url'                  => 'https://wpthemes.spiraclethemes.com/blogson/',
        ),
    );
}
endif;
add_filter( 'pt-ocdi/import_files', 'spiraclethemes_site_library_blogson_set_import_files' );


/**
 *  After Import
 */

if ( ! function_exists( 'spiraclethemes_site_library_blogson_after_import_setup' ) ) :
function spiraclethemes_site_library_blogson_after_import_setup( $selected_import ) {
	//Assign menus to their locations
	$main_menu = get_term_by( 'name', 'Primary', 'nav_menu' );
	$footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );
	$sidebar_social_menu = get_term_by( 'name', 'Social Menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
	      'primary' => $main_menu->term_id,
	      'footer' => $footer_menu->term_id,
	      'social' => $sidebar_social_menu->term_id,
	    )
	);

    //Assign front & blog page
    $front_page = get_page_by_title( 'Home' );  

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page -> ID ); 
    
}
endif;
add_action( 'pt-ocdi/after_import', 'spiraclethemes_site_library_blogson_after_import_setup' );


function spiraclethemes_site_library_blogson_check_pro_plugin() {
    if ( ! function_exists( 'ocdi_register_plugins' ) ) :
        function ocdi_register_plugins( $plugins ) {
         
            // List of plugins used by all theme demos.
            $theme_plugins = [
                [ 
                  'name'     => 'Elementor Website Builder',
                  'slug'     => 'elementor',
                  'required' => true,
                ],
            ];
         
            return array_merge( $plugins, $theme_plugins );
        }
    endif;
    add_filter( 'ocdi/register_plugins', 'ocdi_register_plugins' );
}
add_action( 'admin_init', 'spiraclethemes_site_library_blogson_check_pro_plugin' );


/**
 *  Blogson functions
 */

function spiraclethemes_site_library_blogson_get_categories(){
    $categories = get_categories( [
        'taxonomy'     => 'category',
        'type'         => 'post',
        'child_of'     => 0,
        'parent'       => '',
        'orderby'      => 'name',
        'order'        => 'ASC',
        'hide_empty'   => 1,
        'hierarchical' => 1,
        'exclude'      => '',
        'include'      => '',
        'number'       => 0,
        'pad_counts'   => false,
    ]);
    if( $categories ){
        foreach( $categories as $cat ){
            $cat_select[$cat->slug] = $cat->name;
        }
    } else {
        $cat_select = array(''=>'No categories');
    }
    return $cat_select;
}


function spiraclethemes_site_library_blogson_get_all_posts(){
    $args = array(
        'post_status' => 'publish',
        'post_type' => 'post',
        'posts_per_page' => -1,
        'orderby'       => 'date'
    );
    $the_query = new WP_Query( $args );
    $array_of_post = array();
    $array_of_post['no'] = esc_html__('-- No select --', 'spiraclethemes-site-library');
    if($the_query->have_posts()){
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $array_of_post[get_the_ID()] = get_the_title();
        }
    }
    wp_reset_postdata();
    return $array_of_post;
}



if( !function_exists('spiraclethemes_site_library_blogson_gridposts') ){
    function spiraclethemes_site_library_blogson_gridposts($atts, $content = null) {
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
            'post_trim_title' => 'true',
            'post_trim_title_count' => '7',
            'post_text_position' => 'bottomcenter'
        ), $atts));

        global $post;
        global $paged;
        if ( is_front_page() ) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }
        if($post_ids != ''){
            $post_ids = str_replace(' ', '', $post_ids);
            if (strpos($post_ids, ',') !== false){
                $post_ids = explode(',', $post_ids);
            } else {
                $post_ids = array($post_ids);
            }
        } else {
            $post_ids = array();
        }
        if($post__not_in != ''){
            $post__not_in = str_replace(' ', '', $post__not_in);
            if (strpos($post__not_in, ',') !== false){
                $post__not_in = explode(',', $post__not_in);
            } else {
                $post__not_in = array($post__not_in);
            }
        } else {
            $post__not_in = array();
        }

        $show_section_title = ($show_section_title === 'true' || $show_section_title=='1');
        $post_display_comments = ($post_display_comments === 'true' || $post_display_comments=='1');
        $post_display_date = ($post_display_date == 'true' || $post_display_date=='1');
        $post_display_author = ($post_display_author == 'true' || $post_display_author=='1');
        $post_display_categories = ($post_display_categories === 'true' || $post_display_categories=='1');
        $post_ignore_featured = ($post_ignore_featured === 'true' || $post_ignore_featured=='1' );
        $post_content_show = ($post_content_show === 'true' || $post_content_show == '1' );
        $post_show_readmore = ($post_show_readmore === 'true' || $post_show_readmore == '1' );
        $bottom_lines = '';
        $contentoverimage='';
        $ignorestickyposts='';
       
        if($post_style=== 'style_1' || $post_style=== 'style_2') {
            $contentoverimage="contentoverimage";
        }
        else{
            $contentoverimage="nocontentoverimage";
        }

        $sticky_args = [
            'post__in' => get_option('sticky_posts'),
            'post_status' => 'publish'
        ];
        $sticky_posts = new WP_Query($sticky_args);
        $sticky_count = $sticky_posts->post_count; 
        
        if($post_style=='style_1' && $post_cat_slug == '' && $sticky_count > 0){
            $post_count=3 - $sticky_count;
            $post_columns='span4f';
        }
        elseif($post_style=='style_1'){
            $post_count=3;
           $post_columns='span4f';
        }


        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $post_count,
            'post__in' => $post_ids,
            'post__not_in' => $post__not_in,
            'order'          => $post_order,
            'orderby'        => $post_orderby,
            'post_status'    => 'publish',
        );
        if($post_cat_slug != '' && $post_cat_slug != 'all'){
            $str = str_replace(' ', '', $post_cat_slug);
            $arr = explode(',', $str);    
            $args['tax_query'][] = array(
              'taxonomy'  => 'category',
              'field'   => 'slug',
              'terms'   => $arr
            );

        }
        
        static $post_section_id = 0;
        $out = '';
        ++$post_section_id;
        query_posts( $args );
        
        if( have_posts() ) {
            $out .= '<div class="latest-posts">';
            if( $section_title != '' && $show_section_title==true ) {
                $out .= '<'.$section_title_size.' class="post_title">'.esc_html($section_title).'</'.$section_title_size.'>';
            }
            $out .= '<div id="blog-posts-'.$post_section_id.'" class="row-fluid blog-posts">';
            
            if($post_style=='style_1') {
                while ( have_posts() ) {
                    the_post();
                    $classes = join(' ', get_post_class($post->ID) );
                    $classes .= ' post';
                    if(true) {
                        $classes = str_replace('sticky ', '', $classes);
                        $out .= '<article class="textcenter '.$classes.' '.$contentoverimage.' '.$post_columns.' '.$post_style.' '.$post_text_position.' ">';
                            
                        if(has_post_thumbnail()) { 
                            $post_img_url= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $post_thumbsize );
                        }
                        else {
                            $post_img_url[0] =  esc_url( plugins_url( 'img/no-image.jpg', dirname(__FILE__) ) );
                        }

                        $out .= '<div id="post-'.$post->ID.'" class="post-grid-area-box">';
                            $out .= '<div class="post-grid-area-content" style="background:url('.$post_img_url[0].') no-repeat;">';
                                $out .= '<div class="content-wrapper">';
                                    $out .= '<div class="content">';
                                        $out .= '<div class="content-inner">';
                                            $out .= '<div class="category">';
                                                if( $post_display_categories ) 
                                                $out .= '<span> '.get_the_category_list(', ').' </span>';
                                            $out .= '</div>';
                                            $out .= '<div class="title">';
                                                if($post_trim_title=='true') {
                                                    $out .= '<h2><a href="'.get_the_permalink().'" title="'.esc_html__('Permalink to', 'spiraclethemes-site-library').' '.esc_attr(the_title_attribute('echo=0')).'" rel="bookmark">'. wp_trim_words(get_the_title(), $post_trim_title_count).'</a></h2>';
                                                }
                                                else {
                                                    $out .= '<h2><a href="'.get_the_permalink().'" title="'.esc_html__('Permalink to', 'spiraclethemes-site-library').' '.esc_attr(the_title_attribute('echo=0')).'" rel="bookmark">'. get_the_title() .'</a></h2>';
                                                }
                                            $out .= '</div>';
                                            $out .= '<div class="meta">';
                                                if( $post_display_author ) {
                                                    $out .= '<span class="author"><span>'. esc_html( $post_display_author_pre_text ) .':&nbsp;<a class="author-post-url" href="'. esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) .'">'. get_the_author() .'</a></span></span>';
                                                }
                                                if( $post_display_date ) {
                                                    $out .= '<span class="date"><span>' .get_the_time(get_option('date_format')). '</span></span>';
                                                }
                                                if( $post_display_comments ) {
                                                    $out .= '<span class="comments"><span><a class="post-comments-url" href="'. get_the_permalink().'#comments" >'. get_comments_number('0','1','%') . esc_html__(" Comments","blogson") .'</a></span></span>';
                                                }
                                            $out .= '</div>';

                                        $out .= '</div>';
                                    $out .= '</div>';
                                $out .= '</div>';
                            $out .= '</div>';   
                        $out .= '</div>';
                       
                        $out .= '</article>';
                    }

                }

            }
            if($post_style=='style_2') {
                while ( have_posts() ) {
                    the_post();
                    $classes = join(' ', get_post_class($post->ID) );
                    $classes .= ' post';
                    if(true) {
                        $classes = str_replace('sticky ', '', $classes);
                        $out .= '<article class="textcenter '.$classes.' '.$contentoverimage.' '.$post_columns.' '.$post_style.' '.$post_text_position.' ">';
                            
                        if(has_post_thumbnail()) { 
                            $post_img_url= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $post_thumbsize );
                        }
                        else {
                            $post_img_url[0] =  esc_url( plugins_url( 'img/no-image.jpg', dirname(__FILE__) ) );
                        }
                        $out .= '<div id="post-'.$post->ID.'" class="post-grid-area-box">';
                            $out .= '<div class="post-grid-area-content" style="background:url('.$post_img_url[0].') no-repeat;">';
                                $out .= '<div class="content-wrapper">';
                                    $out .= '<div class="content">';
                                        $out .= '<div class="content-inner">';
                                            $out .= '<div class="category">';
                                                if( $post_display_categories ) 
                                                $out .= '<span> '.get_the_category_list(', ').' </span>';
                                            $out .= '</div>';
                                            $out .= '<div class="title">';
                                                if($post_trim_title=='true') {
                                                    $out .= '<h2><a href="'.get_the_permalink().'" title="'.esc_html__('Permalink to', 'spiraclethemes-site-library').' '.esc_attr(the_title_attribute('echo=0')).'" rel="bookmark">'. wp_trim_words(get_the_title(), $post_trim_title_count).'</a></h2>';
                                                }
                                                else {
                                                    $out .= '<h2><a href="'.get_the_permalink().'" title="'.esc_html__('Permalink to', 'spiraclethemes-site-library').' '.esc_attr(the_title_attribute('echo=0')).'" rel="bookmark">'. get_the_title() .'</a></h2>';
                                                }
                                            $out .= '</div>';
                                            $out .= '<div class="meta">';
                                                if( $post_display_author ) {
                                                    $out .= '<span class="author"><span>'. esc_html( $post_display_author_pre_text ) .':&nbsp;<a class="author-post-url" href="'. esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) .'">'. get_the_author() .'</a></span></span>';
                                                }
                                                if( $post_display_date ) {
                                                    $out .= '<span class="date"><span>' .get_the_time(get_option('date_format')). '</span></span>';
                                                }
                                                if( $post_display_comments ) {
                                                    $out .= '<span class="comments"><span><a class="post-comments-url" href="'. get_the_permalink().'#comments" >'. get_comments_number('0','1','%') . esc_html__(" Comments","blogson") .'</a></span></span>';
                                                }
                                            $out .= '</div>';

                                        $out .= '</div>';
                                    $out .= '</div>';
                                $out .= '</div>';
                            $out .= '</div>';   
                        $out .= '</div>';
                        $out .= '</article>';
                    }

                }
            }
            if($post_style=='style_3') {
                while ( have_posts() ) {
                    the_post();
                    $classes = join(' ', get_post_class($post->ID) );
                    $classes .= ' post';
                    if(true) {
                        $classes = str_replace('sticky ', '', $classes);
                        $out .= '<article class="textcenter '.$classes.' '.$contentoverimage.' '.$post_columns.' '.$post_style.' '.$post_text_position.' ">';
                            
                        if(has_post_thumbnail()) { 
                            $post_img_url= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $post_thumbsize );
                        }
                        else {
                            $post_img_url[0] =  esc_url( plugins_url( 'img/no-image.jpg', dirname(__FILE__) ) );
                        }
                        $out .= '<div id="post-'.$post->ID.'" class="post-grid-area-box">';
                            $out .= '<div class="post-grid">';
                                $out .= '<div class="content-wrapper">';
                                    $out .= '<div class="post-image">';
                                        $out .= '<div class="post-image-wrapper">';
                                            $out .= '<img src="'.esc_url($post_img_url[0]).'" />';
                                        $out .= '</div>';
                                    $out .= '</div>';
                                    $out .= '<div class="content">';
                                        $out .= '<div class="content-inner">';
                                            $out .= '<div class="category">';
                                                if( $post_display_categories ) 
                                                $out .= '<span> '.get_the_category_list(', ').' </span>';
                                            $out .= '</div>';
                                            $out .= '<div class="title">';
                                                if($post_trim_title=='true') {
                                                    $out .= '<h2><a href="'.get_the_permalink().'" title="'.esc_html__('Permalink to', 'spiraclethemes-site-library').' '.esc_attr(the_title_attribute('echo=0')).'" rel="bookmark">'. wp_trim_words(get_the_title(), $post_trim_title_count).'</a></h2>';
                                                }
                                                else {
                                                    $out .= '<h2><a href="'.get_the_permalink().'" title="'.esc_html__('Permalink to', 'spiraclethemes-site-library').' '.esc_attr(the_title_attribute('echo=0')).'" rel="bookmark">'. get_the_title() .'</a></h2>';
                                                }
                                            $out .= '</div>';
                                            $out .= '<div class="meta">';
                                                if( $post_display_author ) {
                                                    $out .= '<span class="author"><span>'. esc_html( $post_display_author_pre_text ) .':&nbsp;<a class="author-post-url" href="'. esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) .'">'. get_the_author() .'</a></span></span>';
                                                }
                                                if( $post_display_date ) {
                                                    $out .= '<span class="date"><span>' .get_the_time(get_option('date_format')). '</span></span>';
                                                }
                                                if( $post_display_comments ) {
                                                    $out .= '<span class="comments"><span><a class="post-comments-url" href="'. get_the_permalink().'#comments" >'. get_comments_number('0','1','%') . esc_html__(" Comments","blogson") .'</a></span></span>';
                                                }
                                            $out .= '</div>';
                                            if( $post_content_show ) {
                                                $out .= '<div class="main-content">';
                                                    $out .= '<p class="post-content">'. wp_trim_words(get_the_content(), $post_excerpt_count).'</p>';
                                                $out .= '</div>';
                                            }
                                            if( $post_show_readmore ) {
                                                $out .= '<div class="post-read-more">';
                                                $out .= '<a href="'.get_the_permalink().'">'. esc_html($post_readmore_text).'</a>';
                                                $out .= '</div>';
                                            }
                                        $out .= '</div>';
                                    $out .= '</div>';
                                $out .= '</div>';
                            $out .= '</div>';   
                        $out .= '</div>';
                        $out .= '</article>';
                    }

                }
            }
    
            $out .= '</div>';
           
            $out .= '</div>';
        }
        wp_reset_query();
        return $out;
    }
    add_shortcode('gridposts', 'spiraclethemes_site_library_blogson_gridposts');
}