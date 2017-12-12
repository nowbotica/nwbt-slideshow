<?php 

/**
  * Register Widget Area
  */
function tz_slideshow_register_widget_area(){
	register_sidebar( array(
		'name' => __( 'Frontpage Slideshow Area', 'tz_slideshow_widget_domain' ),
		'id' => 'tz_slideshow_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 style="display:none;" class="widgettitle">',
		'after_title' => '</h3>'
	));
}
add_action( 'widgets_init', 'tz_slideshow_register_widget_area' ); ;

/**
 * Widget Scripts
 */
if ( ! function_exists( 'tz_slideshow_scripts' ) ) {

    /*
     *  loads the applications js dependancies and application files
     */
    function tz_slideshow_scripts() {

        wp_enqueue_script( 'slick-js', MVPMSYSTEM_URL .  'application/dependencies/slick/slick.js', array(), 'jquery', true);
        wp_enqueue_script( 'tz-slider-js', MVPMSYSTEM_URL .  'application/frontend/tz-slider.js', array(), 'slick-js', true); 
    }
}
add_action( 'widgets_init', 'tz_slideshow_scripts' ); ;

/*
* Register and load the widget
*/
function tz_slideshow_load_widget() {
    register_widget( 'tz_slideshow_widget' );
}
add_action( 'widgets_init', 'tz_slideshow_load_widget' );

// Creating the widget 
class tz_slideshow_widget extends WP_Widget {
 
	function __construct() {
		parent::__construct(
		 
			// Base ID of your widget
			'tz_slideshow_widget', 
		 
			// Widget name will appear in UI
			__('DynamicSlideshow', 'tz_slideshow_widget_domain'), 
		 
			// Widget description
			array( 'description' => __( 'A flexible slideshow plugin', 'tz_slideshow_widget_domain' ), ) 
		);
	}
	 
	// Creating widget front-end
	 
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$select_display = $instance['select_display'];
		if ( ! empty( $title ) )
			$slideData = explode(",", $title);
			for($i=0;$i< count($slideData);$i++){
			    $slides[] = $slideData[$i];
			}
		?>
		<?php if($select_display == 'contained'):?><div class="container"><?php endif; ?>
		<?php if($slides):?>
        <div class="slick-banner">
			<?php foreach ($slides as $key => $value): ?>
            <article class="slick-banner-slide">
                <div class="wrap">
             	<?php echo wp_get_attachment_image( $slides[$key] , 'full'); ?>
                </div>
            </article>
			<?php endforeach ?>
        </div>
        <?php endif; ?>

		<?php if($select_display == 'contained'):?></div><?php endif; ?>

		<?php
	}

	public function echoForm(){

		return "2017/11/12/IMAG0154-150x150.jpg";
	} 
	         
	// Widget Backend 
	// https://premium.wpmudev.org/blog/adding-custom-widgets-to-the-wordpress-admin-dashboard/
	public function form( $instance ) {
		$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'TZ Slideshow', 'tz_slideshow_widget_domain' );
		$select_display = ( isset( $instance[ 'select_display' ] ) ) ? $instance[ 'select_display' ] : 'contained';
		?>
		<section class="foob">
		<!-- <div ng-app="TzSliderConfigApp">
			<tz-edit-slideshow></tz-edit-slideshow>
		</div> -->
		<h1 id="tz-slider-config">
		
		</h1>
			<!-- <div id="data-setup" data-setup="1,2,3,4"></div> -->
		<p>
		    <label for="<?php echo $this->get_field_id('select_display'); ?>">
		        <?php _e('Full screen:'); ?>
		        <input class="" id="<?php echo $this->get_field_id('select_display'); ?>" name="<?php echo $this->get_field_name('select_display'); ?>" type="radio" value="full" <?php if($select_display === 'full'){ echo 'checked="checked"'; } ?> />
		    </label><br>
		    <label for="<?php echo $this->get_field_id('select_display'); ?>">
		        <?php _e('Contained to grid:'); ?>
		        <input class="" id="<?php echo $this->get_field_id('select_display'); ?>" name="<?php echo $this->get_field_name('select_display'); ?>" type="radio" value="contained" <?php if($select_display === 'contained'){ echo 'checked="checked"'; } ?> />
		    </label>

		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		</section>
		<?php 
	}
	     
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		// $instance = array();
		$instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		
		$instance['select_display'] = strip_tags($new_instance['select_display']);
		$instance['slides'] = strip_tags($new_instance['slides']);

		return $instance;
	}
} // Class wpb_widget ends here
 



add_action( 'admin_menu', 'nwbt_tz_add_admin_menu' );
add_action( 'admin_init', 'nwbt_tz_setting_init' );


function nwbt_tz_add_admin_menu(  ) { 

	$page = add_menu_page( 'nwbt_tz_slideshow', 'nwbt_tz_slideshow', 'manage_options', 'nwbt_tz_slideshow', 'nwbt_tz_options_page' );

    /* Using registered $page handle to hook script load */
    add_action('admin_print_scripts-' . $page, 'tz_slideshow_load_admin_scripts');
}



/*
* Register our angular app
*/
function tz_slideshow_load_admin_scripts() {
 
	// if( $hook != 'widgets.php' ) 
	// 	return;
 	
 	wp_enqueue_script( 'angular', MVPMSYSTEM_URL .  'application/dependencies/angular/angular.js', array( 'jquery'), '', true);

	wp_enqueue_script( 'tz-slider-config', MVPMSYSTEM_URL .  'application/backend/tz-slider-config.js', array(), 'angular', true );
	
	// API Token set up
    wp_localize_script( 'tz-slider-config', 'tz_slider_object', array(
            'ajax_nonce'      => wp_create_nonce('tz_slider_nonce'),
            'ajax_url'        => admin_url( 'admin-ajax.php' ) ,
            'url_domain_path' => get_site_url(),
            'partials_path'   => MVPMSYSTEM_URL .  '/application/backend/' ,
            'image_path'      => MVPMSYSTEM_URL .  '/application/build/images/'
        ), '', true);
}


function nwbt_tz_setting_init(  ) { 

	register_setting( 'pluginPage', 'nwbt_tz_setting' );

	add_settings_section(
		'nwbt_tz_pluginPage_section', 
		__( 'Your section description', 'nwbtTz' ), 
		'nwbt_tz_setting_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'nwbt_tz_textarea_field_0', 
		__( 'Settings field description', 'nwbtTz' ), 
		'nwbt_tz_textarea_field_0_render', 
		'pluginPage', 
		'nwbt_tz_pluginPage_section' 
	);


}


function nwbt_tz_textarea_field_0_render(  ) { 

	$options = get_option( 'nwbt_tz_setting' );
	if(!$options){
		$options['nwbt_tz_textarea_field_0'] = '{"settings": {"width":"contained", "show_captions":true}, "slides":[{"image_id":734, "caption":"Slide One"},{"image_id":735, "caption":"Slide Two"}]}' ;

	}
	?>
	<section ng-app="TzSliderConfigApp">
	 	<tz-edit-slideshow 
	 	slideshow-name="nwbt_tz_setting[nwbt_tz_textarea_field_0]"
	 	slideshow-id="nwbt_tz_setting[nwbt_tz_textarea_field_0]"
	 	slideshow-value='<?php echo $options['nwbt_tz_textarea_field_0'];?>'
	 	></tz-edit-slideshow>
	 </section>
	<?php /*
	<textarea cols='40' rows='5' name='nwbt_tz_setting[nwbt_tz_textarea_field_0]'> 
		<?php echo $options['nwbt_tz_textarea_field_0']; ?>
 	</textarea>
	<?php */

}


function nwbt_tz_setting_section_callback(  ) { 

	echo __( 'This section description', 'nwbtTz' );

}


function nwbt_tz_options_page(  ) { 

	?>
	<form action='options.php' method='post'>

		<h2>nwbt_tz_slideshow</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}


/**
  * Creates shortcode to display main application
  *
  */
function nwbtSlideshow(  ) {

  $options = get_option( 'nwbt_tz_setting' );
  $s = json_decode($options['nwbt_tz_textarea_field_0']);
  $settings = $s->settings;
  $slides = $s->slides;
  // var_dump($slides);
  
  ?>

  	<?php if($settings->width == 'contained'):?><div class="container"><?php endif; ?>
		<?php if($slides):?>
        <div class="slick-banner">
			<?php foreach ($slides as $key => $value): ?>
				<?php //echo $slides[$key]->image_id;?>
            <article class="slick-banner-slide">
                <div class="wrap">
             	<?php echo wp_get_attachment_image( $slides[$key]->image_id , 'full'); ?>
				<?php //echo $slides[$key]->caption;?>
                </div>
            </article>
			<?php endforeach ?>
        </div>
        <?php endif; ?>

		<?php if($settings->width == 'contained'):?></div><?php endif; ?>


      <!-- <ui-view autoscroll="false" ng-if='!isRouteLoading'></ui-view> -->

  <?php
}
add_shortcode('nwbtSlideshow', 'nwbtSlideshow');


 