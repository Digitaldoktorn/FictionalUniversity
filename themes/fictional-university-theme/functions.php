<?php

    /// Creating a Banner Post Type for Pages and Blog posts
    function pageBanner($argsParm = NULL)
    {
        $title  = get_the_title(); 
        $subtitle  = get_field('page_banner_subtitle');
        $bgImageSource = get_field('page_banner_background_image')['sizes']['pageBanner'] ?? get_theme_file_uri('/images/ocean.jpg'); 
 
        $args = array(
            'title' => $title, 
            'subtitle' => $subtitle, 
            'bgImage' => $bgImageSource 
 
        ); 
 
        if (!empty($argsParm))
        {
            $args['title']= !array_key_exists('title', $argsParm) ? $title :$argsParm['title']; 
            $args['subtitle']= !array_key_exists('subtitle', $argsParm) ? $subtitle : $argsParm['subtitle']; 
            $args['bgImage']= !array_key_exists('bgImage', $argsParm) ? $bgImageSource : $argsParm['bgImage']; 
           
        }
       
 
?>
        <!-- Page Banner -->
        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['bgImage']; ?>);"></div>
                <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
                <div class="page-banner__intro">
                    <p><?php echo $args['subtitle']; ?></p>
                </div>
            </div>  
        </div>
 
<?php 
 
        
    }// end pageBanner()



function university_files()
{
    wp_enqueue_style('custom-google-fonts', '/fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyAn6kb_jBGFk4OQQ3MeeP3-Neak1CBGQKM', NULL, '1.0', true);

    if(strstr($_SERVER['SERVER_NAME'], 'localhost')) {
        wp_enqueue_script('main-university-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
    } else {
        wp_enqueue_script('our-vendors-js', get_theme_file_uri( '/bundled-assets/vendors~scripts.8c97d901916ad616a264.js' ), NULL, '1.0', true);
        wp_enqueue_script('main-university-js', get_theme_file_uri( '/bundled-assets/scripts.291f4fbd3120f33dcc5a.js' ), NULL, '1.0', true);
        wp_enqueue_style( 'our-main-styles', get_theme_file_uri('/bundled-assets/styles.291f4fbd3120f33dcc5a.css' ));
    }
    
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features()
{
    // register_nav_menu('headerMenuLocation', 'Header Menu Location');
    // register_nav_menu('footerLocationOne', 'Footer Location One');
    // register_nav_menu('footerLocationTwo', 'Footer Location Two');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size( 'professorLandscape', 400, 260, true );
    add_image_size( 'professorPortrait', 480, 650, true );
    add_image_size( 'pageBanner', 1500, 350, true );
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query) {
    if(!is_admin() AND is_post_type_archive( 'campus' ) AND $query->is_main_query()) {
        $query->set('posts-per-page', -1);
    }

    if(!is_admin() AND is_post_type_archive( 'program' ) AND $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts-per-page', -1);
    }
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

function universityMapKey($api) {
    $api['key'] = 'AIzaSyAn6kb_jBGFk4OQQ3MeeP3-Neak1CBGQKM';
    return $api;
}
add_filter('acf/fields/google_map/api', 'universityMapKey');

