<?php
function university_files()
{
    // wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);
    wp_enqueue_style('custom-google-fonts', '/fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    // wp_enqueue_style('university_main_styles', get_stylesheet_uri());

    wp_enqueue_script('main-university-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);

    // Lyckas ej med localhost:8000 Eftersom jag kör docker skiljer sig undervisningens kod från min. Därför kör jag utan if else tills vidare
    // if(strstr($_SERVER['SERVER_NAME'], 'localhost:8000')) {
    //     wp_enqueue_script('main-university-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
    // } else {
    //     wp_enqueue_script('our-vendors-js', get_theme_file_uri( '/bundles-assets/vendors.js' ), NULL, '1.0', true);
    //     wp_enqueue_script('main-university-js', get_theme_file_uri( '/bundles-assets/scripts.js' ), NULL, '1.0', true);
    //     wp_enqueue_style( 'our-main-styles', get_theme_file_uri('/bundled-assets/styles.css' ));
    // }
    
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features()
{
    // register_nav_menu('headerMenuLocation', 'Header Menu Location');
    // register_nav_menu('footerLocationOne', 'Footer Location One');
    // register_nav_menu('footerLocationTwo', 'Footer Location Two');
    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query) {
    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('order_by', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', [
            [
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            ]
        ]);
    }
        
}

add_action('pre_get_posts', 'university_adjust_queries');