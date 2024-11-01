<?php
/**
 *
 * @package spiraclethemes-site-library
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) :
    die;
endif;


if ('own-shop' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/own-shop-functions.php';
    // helper functions
    require_once SPIR_SITE_LIBRARY_PATH . '/elements/own-shop/helper-functions.php';
    // Widget Category
    require_once SPIR_SITE_LIBRARY_PATH . '/elements/own-shop/widget-category.php';
endif;
if ('purea-magazine' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/purea-magazine-functions.php';
endif;
if ('colon' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/colon-functions.php';
endif;
if ('somalite' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/somalite-functions.php';
endif;
if ('purea-fashion' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/purea-fashion-functions.php';
endif;
if ('own-store' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/own-store-functions.php';
    // helper functions
    require_once SPIR_SITE_LIBRARY_PATH . '/elements/own-shop/helper-functions.php';
    // Widget Category
    require_once SPIR_SITE_LIBRARY_PATH . '/elements/own-shop/widget-category.php';
endif;
if ('colon-plus' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/colon-plus-functions.php';
endif;
if ('own-shop-lite' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/own-shop-lite-functions.php';
    // helper functions
    require_once SPIR_SITE_LIBRARY_PATH . '/elements/own-shop/helper-functions.php';
    // Widget Category
    require_once SPIR_SITE_LIBRARY_PATH . '/elements/own-shop/widget-category.php';
endif;
if ('mestore' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/mestore-functions.php';
endif;

if ('blogson' == $this->theme_slug || 'blogson-child' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/blogson-functions.php';
    // helper functions
    require_once SPIR_SITE_LIBRARY_PATH . '/elements/blogson/helper-functions.php';
    // Widget Category
    require_once SPIR_SITE_LIBRARY_PATH . '/elements/blogson/widget-category.php';
endif;
if ('own-shope' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/own-shope-functions.php';
    // helper functions
    require_once SPIR_SITE_LIBRARY_PATH . '/elements/own-shop/helper-functions.php';
    // Widget Category
    require_once SPIR_SITE_LIBRARY_PATH . '/elements/own-shop/widget-category.php';
endif;
if ('crater-free' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/crater-free-functions.php';
endif;
if ('lawfiz' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/lawfiz-functions.php';
endif;

if ('legalblow' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/legalblow-functions.php';
endif;

if ('own-shop-trend' == $this->theme_slug ) :
    require_once SPIR_SITE_LIBRARY_PATH . '/inc/own-shop-trend-functions.php';
endif;

/*************************************************************/

// Function to access API data
function spiraclethemes_site_library_api_data($theme_name, $demo_name, $file_type) {
    // API URL for accessing files
    $api_url = "https://api.spiraclethemes.com/wp-json/custom/v1/files/$file_type/$theme_name/$demo_name/";

    // Send GET request to the API
    $response = wp_remote_get($api_url);

    // Check if the request was successful
    if (is_wp_error($response)) {
        // Log the error (optional, for debugging purposes)
        error_log('API Request Error: ' . $response->get_error_message());
        return false; // Return false to indicate API request failure
    }

    // Get the response code
    $response_code = wp_remote_retrieve_response_code($response);

    // Check if API returned a valid response (2xx status code)
    if (strpos((string) $response_code, '2') !== 0) {
        // Log the response code (optional, for debugging purposes)
        error_log('API Response Error: ' . $response_code);
        return false; // Return false to indicate API response error
    }

    // Get the response body
    $api_data = wp_remote_retrieve_body($response);

    // Check if API data is valid
    if (empty($api_data)) {
        error_log('Empty API Response');
        return false; // Return false for empty API response
    }

    // Convert JSON string to PHP array
    $api_data_array = json_decode($api_data, true);

    // Check if the data was successfully decoded and contains 'file_path'
    if ($api_data_array && isset($api_data_array['file_path'])) {
        // Construct the file URL
        $file = "https://spiraclethemes.com/" . implode('/', array_slice(explode('/', ltrim($api_data_array['file_path'])), 6));
        return $file;
    } else {
        // No file path found in API response
        error_log('No file path found in API response');
        return false; // Return false for missing file path
    }
}

