<?php
/**
 *
 * @package spiraclethemes-site-library
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) :
    die;
endif;


// Define theme constants
define('WP_THEME', wp_get_theme()->Name);
define('WP_THEME_SLUG', wp_get_theme()->get('TextDomain'));

// Function to add dashboard widget
function spiraclethemes_site_library_add_dashboard_widgets() {
    // Set the widget title
    $widget_title = sprintf( __( '%s Theme', 'spiraclethemes-site-library' ), WP_THEME );

    // Add dashboard widget
    wp_add_dashboard_widget(
        'spiraclethemes_site_library_dashboard_widget', // Widget slug
        $widget_title, // Title
        'spiraclethemes_site_library_display_dashboard_widget' // Display function
    );
}

// Function to display dashboard widget content
function spiraclethemes_site_library_display_dashboard_widget() {
    // URL of the API endpoint serving discounts.xml
    $api_url = 'https://api.spiraclethemes.com/discounts/disapi.php';

    // Fetch XML data from the API
    $response = wp_remote_get($api_url);

    // Check if there was an error fetching the data
    if (is_wp_error($response)) {
        echo '<p>' . __( 'Error fetching discount data.', 'spiraclethemes-site-library' ) . '</p>';
    } else {
        // Check HTTP response code for success (200)
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code === 200) {
            // Parse XML data
            $xml_body = wp_remote_retrieve_body($response);

            // Suppress any warnings from XML parsing
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($xml_body);

            // Check for XML parsing errors
            if ($xml === false) {
                echo '<p>' . __( 'Error parsing XML data.', 'spiraclethemes-site-library' ) . '</p>';
            } else {
                // Find the discount information for the current theme
                $theme_slug = WP_THEME_SLUG; // Replace with dynamic value or function to get theme slug
                $theme_discount = null;
                $theme_url = null;

                foreach ($xml->theme as $theme) {
                    if ((string) $theme->slug === $theme_slug) {
                        $theme_discount = (string) $theme->sale;
                        $theme_url = (string) $theme->purchase_url;
                        break;
                    }
                }

                // Display discount information in the widget
                echo '<h3><b>' . __( 'Special Discount', 'spiraclethemes-site-library' ) . '</b></h3>';
                if ($theme_discount && $theme_url) {
                    echo '<span style="background-color: #2196f3; color: white; padding: 3px 6px; border-radius: 4px; font-size: 11px; margin-right: 5px;font-weight: 500;">' . __( 'NEW', 'spiraclethemes-site-library' ) . '</span>';
                    printf(
                        '<span>' . __( 'Unlock the Pro version for just $%1$s! Take advantage of our limited-time discount on %2$s. <a href="%3$s" target="_blank">Buy now</a>!', 'spiraclethemes-site-library' ) . '</span>',
                        esc_html( $theme_discount ),
                        esc_html( WP_THEME ),
                        esc_url( $theme_url )
                    );
                } else {
                    echo '<span>' . __( 'No special discount currently available.', 'spiraclethemes-site-library' ) . '</span>';
                }
            }

            // Clear any XML parsing errors
            libxml_clear_errors();
        } else {
            // Handle HTTP error response codes
            printf(
                '<p>' . __( 'Error fetching discount data. HTTP Status Code: %s', 'spiraclethemes-site-library' ) . '</p>',
                esc_html( $response_code )
            );
        }
    }

    // Display other content in the dashboard widget
    $rocket_img = SPIR_SITE_LIBRARY_URL . 'img/rocket.svg';

    echo '<br/><br/>';
    echo '<h3><b>' . __( 'Deals & Sales Updates', 'spiraclethemes-site-library' ) . '</b></h3>';
    echo '<ul>';
    echo '<li>';
    echo '<span style="background-color: #2196f3; color: white; padding: 3px 6px; border-radius: 4px; font-size: 11px; margin-right: 5px;font-weight: 500;">' . __( 'NEW', 'spiraclethemes-site-library' ) . '</span>';
    echo '<span>' . __( 'Enjoy a complimentary theme install and demo import service with every lifetime license purchase!', 'spiraclethemes-site-library' ) . '</span>';
    echo '</li>';
    echo '</ul>';

    echo '<h3><span><img src="' . esc_url($rocket_img) . '" /> </span><b>' . __( 'Design, Build or Revamp existing WordPress website', 'spiraclethemes-site-library' ) . '</b></h3>';

    echo '<ul style="list-style: disc; padding-left: 20px;">';
    echo '<li><b>100% Tailored Design</b> - Eye-catching site design or redesign. Unlimited revisions</li>';
    echo '<li><b>Custom functionality</b> - Tailored functionality to meet your business needs</li>';
    echo '<li><b>SEO-Friendly</b> - Improve your search engine rankings</li>';
    echo '<li><b>Lightning-Fast</b> - Optimized for speed and performance</li>';
    echo '<li><b>Secure</b> - Enhanced security features for peace of mind</li>';
    echo '<li><b>Mobile-friendly Designs</b> - Responsive design for all devices</li>';
    echo '<li><b>Google Analytics Integrated</b> - Track your website\'s performance</li>';
    echo '<li><b>Live Chat Integrated</b> - Talk to your website\'s visitors in real time</li>';
    echo '<li><b>SSL Renewal Assistance</b> - Keep your website secure</li>';
    echo '<li><b>Spam Protection Setup</b> - Protect against unwanted spam</li>';
    echo '<li><b>6 Months Dedicated Free Support</b> - Expert assistance when you need it</li>';
    echo '</ul>';

   printf(
        '<p style="text-align: left; margin-bottom:20px;margin-top:20px;">
            <a href="mailto:support@spiraclethemes.com?subject=%s&body=%s" 
               style="text-decoration:none; font-weight:500; background-color:#E91E63; color: #FFFFFF; padding: 10px 20px;border-radius: 5px;box-shadow: 0px 1px 5px 1px #55555526;">
               %s
            </a>
        </p>',
        urlencode('Custom WordPress Website Inquiry'),
        urlencode("Hi Spiracle Themes Team,\n\nI'm interested in your custom WordPress website service.\n\nPlease let me know the next steps.\n\nThank you!"),
        __('Get Started Today for Just $699 (Limited Time Offer, Regularly $999)!', 'spiraclethemes-site-library')
    );

    echo '<h3><b>' . __( 'News & Updates', 'spiraclethemes-site-library' ) . '</b></h3>';
    echo '<ul>';

    // Fetch the latest 2 blog posts
    $response_posts = wp_remote_get('https://spiraclethemes.com/wp-json/wp/v2/posts?per_page=3');

    if (is_wp_error($response_posts)) {
        echo '<li>' . __( 'Error: Unable to fetch blog posts. Please try again later.', 'spiraclethemes-site-library' ) . '</li>';
    } else {
        $posts = json_decode(wp_remote_retrieve_body($response_posts));

        if (is_array($posts) && !empty($posts)) {
            foreach ($posts as $post) {
                // Check if the post is published within the last 60 days
                $post_date = strtotime($post->date);
                $sixty_days_ago = strtotime('-7 days');
                $is_new = $post_date > $sixty_days_ago;

                // Display the post title with a "New" badge if it's new
                echo '<li>';
                if ($is_new) {
                    echo '<span style="background-color: #2196f3; color: white; padding: 3px 6px; border-radius: 4px; font-size: 11px; margin-right: 5px;font-weight: 500;">' . __( 'NEW', 'spiraclethemes-site-library' ) . '</span>';
                }
                printf(
                    '<a href="%1$s" target="_blank">%2$s</a>',
                    esc_url( $post->link ),
                    esc_html( $post->title->rendered )
                );
                echo '</li>';
            }
        } else {
            echo '<li>' . __( 'No recent posts found.', 'spiraclethemes-site-library' ) . '</li>';
        }
    }

    echo '</ul>';

    // Display footer links
    echo '<div style="margin-top: 10px; border-top: 1px solid #e5e5e5; padding-top: 10px;">';
    printf(
        '<a href="%1$s" target="_blank" style="text-decoration:none;color:#93003f;font-weight: 600;">' . __( 'Help Us to Translate %2$s', 'spiraclethemes-site-library' ) . ' <span class="dashicons dashicons-external" style="text-decoration: none;font-size: 16px;color: #6c6969;"></span></a> | ',
        esc_url( 'https://translate.wordpress.org/projects/wp-themes/' . WP_THEME_SLUG . '/' ),
        esc_html( WP_THEME )
    );
    printf(
        '<a href="%1$s" target="_blank" style="text-decoration:none;color: #93003f;font-weight: 600;">' . __( 'Write a Review', 'spiraclethemes-site-library' ) . '<span class="dashicons dashicons-external" style="text-decoration: none;font-size: 16px;color: #6c6969;"></span></a>',
        esc_url( 'https://wordpress.org/support/theme/' . WP_THEME_SLUG . '/reviews/#new-post' )
    );

    echo '</div>';
}

// Hook into the dashboard setup action
add_action('wp_dashboard_setup', 'spiraclethemes_site_library_add_dashboard_widgets');


// Ensure our widget is always on top
function spiraclethemes_site_library_move_widget_to_top() {
    global $wp_meta_boxes;

    // Check if our widget is set
    if (isset($wp_meta_boxes['dashboard']['normal']['core']['spiraclethemes_site_library_dashboard_widget'])) {
        $widget_backup = $wp_meta_boxes['dashboard']['normal']['core']['spiraclethemes_site_library_dashboard_widget'];
        unset($wp_meta_boxes['dashboard']['normal']['core']['spiraclethemes_site_library_dashboard_widget']);

        // Insert the widget at the beginning of the array
        $wp_meta_boxes['dashboard']['normal']['core'] =
            array_merge(['spiraclethemes_site_library_dashboard_widget' => $widget_backup], $wp_meta_boxes['dashboard']['normal']['core']);
    }
}
add_action('admin_head', 'spiraclethemes_site_library_move_widget_to_top');