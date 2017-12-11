<?php
/*
Plugin Name: MVP Mechanic
Plugin URI: http://nowbotica.com/lets-tzu-this/
Description: A rust proof diy starter kit for miro enterprises
Author: Andrew MacKay
Version: 1.2.3
Author URI: http://nowbotica.com/
*/

define( 'MVPMSYSTEM', plugin_dir_path( __FILE__ ) );
define( 'MVPMSYSTEM_URL', plugin_dir_url( __FILE__ ) );

# Includes system wide helpers
include( MVPMSYSTEM . '/parts/System.php');

# Includes the user mangement module
include( MVPMSYSTEM . '/parts/User.php');

# Includes the Profile module
// include( MVPMSYSTEM . '/parts/Profile.php');
// include( MVPMSYSTEM . '/parts/Profile-model.php');

# Includes the Listing module
// include( MVPMSYSTEM . '/parts/Listing.php');
// include( MVPMSYSTEM . '/parts/Listing-model.php');


# Included parts
include( MVPMSYSTEM . '/parts/widgets/slideshow.php');

/**
 * Ensures template file is available in dashboard
 *
 */

/**
  * Creates shortcode to display main application
  *
  */
function mvpmApp(  ) {
  ?>

      <ui-view autoscroll="false" ng-if='!isRouteLoading'></ui-view>

  <?php
}
add_shortcode('mvpmApp', 'mvpmApp');

/**
 * Includes js when shortcode called - kinda slow
 *
 */
//function mvmpApp_head(){
//    global $posts;
//    $pattern = get_shortcode_regex();
//    preg_match('/'.$pattern.'/s', $posts[0]->post_content, $matches);
//    if (is_array($matches) && $matches[2] == 'mvmpApp') {
        //shortcode is being used
        add_action('wp_enqueue_scripts','mvmpApp_css');
        add_action('wp_enqueue_scripts','mvmpApp_scripts');
//    }
//}
//add_action('template_redirect','mvmpApp_head');


/*-------------------------------------------------------------------------------
  Frontend dependencies Javascript and CSS to be included by [mvpmApp] shortcode
-------------------------------------------------------------------------------*/

if ( ! function_exists( 'mvmpApp_css' ) ) {
    /*
     *  loads the applications css dependancies and theme css files
     */
    function mvmpApp_css() {

        // font-awesome.min.css: icon library
        wp_enqueue_style( 'mvmpApp_system', MVPMSYSTEM_URL . '/application/mvpm-system.css', false, '1.0.0', 'all');

    }

}

if ( ! function_exists( 'mvmpApp_scripts' ) ) {
    /*
     *  loads the applications js dependancies and application files
     */
    function mvmpApp_scripts() {

        // '_'
        wp_enqueue_script( 'underscore-js', MVPMSYSTEM_URL .  'application/dependencies/underscore/underscore.js', array(), '', true);

        // 'angular'
        wp_enqueue_script( 'angular', MVPMSYSTEM_URL .  'application/dependencies/angular/angular.js', array(
            'jquery', 'underscore-js'
        ), '', true);

        // 'ui-router'
        wp_enqueue_script( 'angular-ui', MVPMSYSTEM_URL .  'application/dependencies/angular-ui-router/release/angular-ui-router.js', array(
            'angular'
        ), '', true);

        // 'ngAnimate'
        wp_enqueue_script( 'ng-animate', MVPMSYSTEM_URL .  'application/dependencies/angular-animate/angular-animate.js', array(
            'angular'
        ), '', true);

        // MVP Mechanic System files
        wp_enqueue_script( 'mvpm-system', MVPMSYSTEM_URL .  'application/mvpm-system.js', array(
            'jquery',
            'underscore-js',
            'angular',
            'angular-ui',
            'ng-animate'
        ), '', true);

        // API Token set up
        wp_localize_script( 'mvpm-system', 'mvpm_api_object', array(
            'ajax_nonce'      => wp_create_nonce('mvpm_system'),
            'ajax_url'        => admin_url( 'admin-ajax.php' ) ,
            'url_domain_path' => get_site_url(),
            'partials_path'   => MVPMSYSTEM_URL .  '/application/templates' ,
            'image_path'      => MVPMSYSTEM_URL .  '/application/build/images/'
        ), '', true);

        // MVP Mechanic Base Application
        wp_enqueue_script('mvpm-controllers', MVPMSYSTEM_URL . 'application/system/controllers.js', array(
           'mvpm-system'
        ), '', true);
        wp_enqueue_script('mvpm-directives', MVPMSYSTEM_URL . 'application/system/directives.js', array(
           'mvpm-system'
        ), '', true);
        wp_enqueue_script('mvpm-factories', MVPMSYSTEM_URL . 'application/system/factories.js', array(
           'mvpm-system'
        ), '', true);
        wp_enqueue_script('mvpm-services', MVPMSYSTEM_URL . 'application/system/services.js', array(
           'mvpm-system'
        ), '', true);

        // MVP Mechanic User Module
        wp_enqueue_script( 'mvpm-user-module', MVPMSYSTEM_URL . '/application/system/User.js', array(
            'mvpm-system'
        ), '', true);

        wp_localize_script( 'mvpm-system', 'mvpm_user_object', array(
            'mvpm_redirecturl' => home_url(),
            'mvpm_passwordreseturl' => 'resetp',
            'mvpm_registerurl' => 'register',
            'mvpm_loginloadingmessage' => __('Sending user info, please wait...')
        ));

        // // MVP Mechanic Listing Module
        // wp_enqueue_script( 'mvpm-listing-module', MVPMSYSTEM_URL . '/application/system/Listing.js', array(
        //     'mvpm-system'
        // ), '', true);

        // // MVP Mechanic Profile Module
        // wp_enqueue_script( 'mvpm-profile-module', MVPMSYSTEM_URL . '/application/system/Profile.js', array(
        //     'mvpm-system'
        // ), '', true);

                // MVP Mechanic Profile Module
        wp_enqueue_script( 'mvpm-slider-module', MVPMSYSTEM_URL . '/application/system/parts/slider.js', array(
            'mvpm-system'
        ), '', true);
    }
}


function remove_dashboard_meta() {
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
}
add_action( 'admin_init', 'remove_dashboard_meta' );