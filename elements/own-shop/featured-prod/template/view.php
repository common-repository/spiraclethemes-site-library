<?php

    $settings = $this->get_settings();
    $id = $this->get_id();

    extract(shortcode_atts(array(
            'prod_count' => '8',
            'prod_columns_count' => '4'
        ), $settings));

    $out = '[featuredprod
        prod_columns_count="'.$prod_columns_count.'"
        prod_count="'.$prod_count.'"]';
    echo shortcode_unautop(do_shortcode($out));

?>