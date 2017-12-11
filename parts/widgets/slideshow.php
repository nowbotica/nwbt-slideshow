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
 


/**
 * register our wporg_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'tz_slideshow_admin_menu' );

function tz_slideshow_admin_menu() {
    /* Register our plugin page */
    $page = add_menu_page( 
    // 'edit.php', // The parent page of this menu
    	__( 'TZ Slideshow', 'tzSlideshow' ), // The Menu Title
        __( 'TZ Slideshow Options', 'tzSlideshow' ), // The Page title
        'manage_options', // The capability required for access to this item
        'tz-slideshow-options', // the slug to use for the page in the URL
        'tz_slideshow_options_page_html' // The function to call to render the page
                           );

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

/**
 * custom option and settings
 */
function tz_slideshow_settings_init() {
	// register a new setting for "wporg" page
	register_setting( 'tz-slideshow-options', 'wporg_options' );
	 
	// register a new section in the "tz-slideshow" page
	add_settings_section(
		'tz-slideshow-settings', //(string) (required) String for use in the 'id' attribute of tags.
	 	__( 'The Matrix has you.', 'tzSlideshow' ), //(string) (required) Title of the section.
	 	'tz_slideshow_section_fill_content', //(string) (required) Function that fills the section with the desired content. The function should echo its output.
	 	'tz-slideshow-options' //(string) (required) The menu page on which to display this section. Should match $menu_slug from Function Reference/add theme page if you are adding a section to an 'Appearance' page, or Function Reference/add options page if you are adding a section to a 'Settings' page.
	 );
 
	 // register a new field in the "tz_slideshow_section_fill_content" section, inside the "tz_slideshow" page
	 add_settings_field(
		'tz_slideshow_field_pill', // (string) (required) String for use in the 'id' attribute of tags. as of WP 4.6 this value is used only internally 
	 	// use $args' label_for to populate the id inside the callback
	 	__( 'Pill', 'tzSlideshow' ), //(string) (required) Title of the field.
	 	'tz_slideshow_field_pill_cb', //(callback) (required) Function that fills the field with the desired inputs as part of the larger form. Passed a single argument, the $args array. Name and id of the input should match the $id given to this function. The function should echo its output.
	 	'tz-slideshow-options', //(string) (required) The menu page on which to display this field. Should match $menu_slug from add_theme_page() or from do_settings_sections().
	 	'tz-slideshow-settings', //(string) (optional) The section of the settings page in which to show the box (default or a section you added with add_settings_section(), look at the page in the source to see what the existing ones are.)
	 	[
	 		'label_for' => 'wporg_field_pill',
	 		'class' => 'wporg_row',
	 		'wporg_custom_data' => 'custom',
	 	] //(array) (optional) Additional arguments that are passed to the $callback function. The 'label_for' key/value pair can be used to format the field title like so: <label for="value">$title</label>.
	 );
}
 
/**
 * register our wporg_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'tz_slideshow_settings_init' );

/**
 * top level menu:
 * callback functions
 */
function tz_slideshow_options_page_html() {
 	// check user capabilities
	 if ( ! current_user_can( 'manage_options' ) ) {
	 	return;
	 }
	 
	 // add error/update messages
	 
	 // check if the user have submitted the settings
	 // wordpress will add the "settings-updated" $_GET parameter to the url
	 if ( isset( $_GET['settings-updated'] ) ) {
	 // add settings saved message with the class of "updated"
	 	// add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
	 }
	 
	 // show error/update messages
	 // settings_errors( 'wporg_messages' );
	 ?>
	 <div class="wrap">
	 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	 <form action="options.php" method="post">
	 <?php
	 // output security fields for the registered setting "wporg"
	 settings_fields( 'tz-slideshow-options' );
	 // output setting sections and their fields
	 // (sections are registered for "wporg", each field is registered to a specific section)
	 do_settings_sections( 'tz-slideshow-options' );
	 // output save settings button
	 submit_button( 'Save Settings' );
	 ?>
	 </form>
	 </div>
 <?php
}

/*
 * pill field cb
 *
 * field callbacks can accept an $args parameter, which is an array.
 * $args is defined at the add_settings_field() function.
 * wordpress has magic interaction with the following keys: label_for, class.
 * the "label_for" key value is used for the "for" attribute of the <label>.
 * the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * you can add custom key value pairs to be used inside your callbacks.
*/

function tz_slideshow_field_pill_cb( $args ) {
	 // get the value of the setting we've registered with register_setting()
	 $options = get_option( 'wporg_options' );
	 $mock = '{"settings": {"width":"contained", "show_captions":true}, "slides":[{"image_id":734, "caption":"Slide One"},{"image_id":735, "caption":"Slide Two"}]}'; 
	 ?>

	 <p><?php echo esc_attr( $args['label_for'] ); ?></p>
	 <p><?php echo $options[ $args["label_for"] ];?></p>
	 
	 <section ng-app="TzSliderConfigApp">
	 	<tz-edit-slideshow 
	 	slideshow-name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
	 	slideshow-id="<?php echo esc_attr( $args['label_for'] ); ?>"
	 	slideshow-value='<?php echo $options[ $args["label_for"] ];?>'
	 	></tz-edit-slideshow>
	 </section>

	 	<!-- slideshow-value='{"settings": {"width":"contained", "show_captions":true}, "slides":[{"image_id":734, "caption":"Slide One"},{"image_id":735, "caption":"Slide Two"}]}' -->
	 <!-- {"settings": {"width":"contained", "show_captions":true}, "slides":[{"image_id":734, "caption":"Slide One"},{"image_id":735, "caption":"Slide Two"}]} -->
	 

 <?php
}
/*
* developers section cb
*
* section callbacks can accept an $args parameter, which is an array.
* $args have the following keys defined: title, id, callback.
* the values are defined at the add_settings_section() function.
*/
function tz_slideshow_section_fill_content( $args ) {
 ?>
 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Configure your slideshow here.', 'wporg' ); ?></p>
 <?php
}
 