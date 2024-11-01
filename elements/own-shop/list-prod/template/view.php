<?php

    $settings = $this->get_settings();
    $id = $this->get_id();

    extract(shortcode_atts(array(
            'prod_options' => '',
            'prod_count' => '8',
            'prod_columns_count' => '4',
            'prod_display_tabs' => 'true'
        ), $settings));

    if( is_array($prod_options) && !empty($prod_options) ){
        $prod_options = implode(',', $prod_options);
    }
    else {
        $prod_options='';
    }
    $out = '[listprod
        prod_options="'.$prod_options.'"
        prod_count="'.$prod_count.'"
        prod_columns_count="'.$prod_columns_count.'"
        prod_display_tabs="'.$prod_display_tabs.'"]';
    echo shortcode_unautop(do_shortcode($out));

?>