* MVP Mechanic

** Product Story 

Remember Someone | 
| Ask for their address
Meet Someone | 
| Ask for their telephone number
Write to Someone |
| Send them your address

Choose your own unforgettable name for them

exclude /-/ include

** Setup

```
$ mkdir -p /c/project/start.here/wordpress/
$ cd !$
$ wget wordpress
$ 
$ echo  'alias go="cd /c/project/start.here/wordpress/wp-content/plugins/mvp-mechanic"' >> ~/.bashrc
```

** Todo
- Create an seo friendly api or something
    - https://blog.keras.io/user-experience-design-for-apis.html
- Migrate to yarn
    - http://tomasalabes.me/blog/web-development/2017/10/03/Migrating-a-bower-module-to-npm-yarn.html
- Fix SCSS Build
- Automatically include mvmp-application-template.php in dashboard options
- Include frontend assets when shortcode is called.
- Create Dev build of assets in mvp-mechanic.php
- //wp_enqueue_style('angular-mighty-datepicker', TZUSYSTEM_URL .  '/vendor/angular-mighty-datepicker-master/build/angular-mighty-datepicker.css', false, '1.0.0', 'all');
- // wp_enqueue_script( 'moment-js', TZUSYSTEM_URL .  '/vendor/moment-develop/moment.js', array());
- cookies vs local storage

        //core: those features any mobile/desktop app could benefit of.
        //components: angular directives and services to support introduced UI components.
        //gestures: services and directives to handle touches easily.
        // wp_enqueue_script( 'mobile-angular-ui', TZUSYSTEM_URL . '/vendor/mobile-angular-ui-1.3.4/dist/js/mobile-angular-ui.js', array(
        //   'jquery', 'angular'
        // ));
function snwb_get_user_role( $user = null ) {
	$user = $user ? new WP_User( $user ) : wp_get_current_user();
	return $user->roles ? $user->roles[0] : false;
}

/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function ot_add_dashboard_widgets() {

    wp_add_dashboard_widget(
                 'ot-blog-managment-panel',     // Widget slug.
                 'Blog Management Panel',       // Title.
                 'snwb_options_page' // Display function.
        );  
}
add_action( 'wp_dashboard_setup', 'ot_add_dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function ot_dashboard_widget_function() {

    // Display whatever it is you want to show.
    echo "<h1>Hello World, I'm a great Dashboard Widget</h1>";


}

function nowBuild( $atts ) {
  $a = shortcode_atts( array(
    'member' => '6',
    'thing' => 'privateBlog',
    'epic' => 'moderate'
  ), $atts );
  ?>
  <section class="nowbuild-service {{system.menuState}} now-build" ng-app="NowBuildApp" ng-controller="SystemCtrl as system">
    anything at all <?= $a['member'];?> <?= $a['thing'];?> 
    <!-- <ui-view autoscroll="false" ng-if='!isRouteLoading' > -->
    <!-- </ui-view> -->
    <!-- <systemloader></systemloader> -->
    <megaloader member="<?= $a['member'];?>" thing="<?= $a['thing'];?>"></megaloader>
  </section>
  <?php
}
add_shortcode('nowBuild', 'nowBuild');

setcookie('dfenginekey', $_POST['pass'], $hour);  


Notice: Trying to get property of non-object in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 199

Notice: Undefined index: row_location in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 199

Notice: Trying to get property of non-object in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 200

Notice: Undefined index: row_duration in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 200

Notice: Trying to get property of non-object in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 201

Notice: Undefined index: row_rating in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 201

Notice: Trying to get property of non-object in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 202

Notice: Undefined index: row_cost in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 202

Notice: Trying to get property of non-object in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 204

Notice: Undefined index: row_yearCompleted in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 204

Notice: Trying to get property of non-object in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 205

Notice: Undefined index: row_designers in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 205

Notice: Trying to get property of non-object in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 206

Notice: Undefined index: row_builders in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 206

Notice: Trying to get property of non-object in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 207

Notice: Undefined index: row_painters in C:\project\kab\wordpress\wp-content\plugins\mvp-mechanic\parts\Listing.php on line 207

// LOCAL STORAGE TODO
// MvpmApp.config(function (localStorageServiceProvider) {
//     localStorageServiceProvider
//         .setPrefix('MvpMechanicStorage')
//         .setStorageType('sessionStorage')
//         .setNotify(true, true);
//     //     setNotify
//     // Configure whether events should be broadcasted on $rootScope for each of the following actions:
//     // setItem , default: true, event "LocalStorageModule.notification.setitem"
//     // removeItem , default: false, event "LocalStorageModule.notification.removeitem"
// });

autentication somethint

write explaination of sql used in listing query

<div>

  <h2>Here we show input and output states of our directive</h2>

  <p>Using php to inject a string into the directive is helpful for this use case of Angularjs. Our aim is to avoid the maintaince overhead of setting up each data node required by our widget using the brittle and complicated wp_settings api.</p>
  
  <p>We have already used the api as demonstrated on the <a href="https://developer.wordpress.org/plugins/settings/custom-settings-page/">relevant codex page</a> to give us a single datapoint <code>$args['label_for']</code> of which we have cRUd like control over. If possible we want to avoid adding extra boilerplate code to handle ajax, so no admin_ajax endpoint and no service layer to our microapp. We are not too worried about security at this stage since a mallious editor having access to the site admin would already have complete control over the source code</p>

  <p>We use angulars '=string' as our scope datatype <code> tz-edit-slideshow slideshowdata='< ?= echo $mock; ?>'></tz-edit-slideshow</code> meaning that when the directive compiles scope.slideshowdata will be assigned the value stored in our $mock on the php side.</p>

  <p>Finally we output to our config-template.html <code><h4>Data to save: {{resultblob}}</h4></code> by stringifying our local javascript object in JSON and returning this to the view. Notice in our controller function we have changed one of the options in our mock data object and we can see this change in the resultant template.</p> 

  <pre><code>
  TzSliderConfigApp.directive('tzEditSlideshow', ['$parse', function($parse){
    return {
        replace: true,
        templateUrl: tzSliderPartialsPath+'/config-template.html',
        scope: {
          slideshowdata: '=slideshowdata'
        },
        link: function(scope, element, attr) {

          console.log('data compiled from php', scope.slideshowdata)
        },
        controller: function($scope){

          $scope.slideshowdata.settings.width = 'full';
          $scope.resultblob = JSON.stringify($scope.slideshowdata);
        }
    };
  }]);
  </code></pre> 

  <h4>Data to save: {{resultblob}}</h4>

</div>

<p>We now have our <code>do_settings_sections( 'tz-slideshow-options' );</code> to look at. We know this iterates over a number of sections and displays the code for each one. The example had already set a field using a more complex select option. We only a basic text input for our functionality. This could be hidden in the final build but allows to easily see that our angular code is updating the scope.</p>

<p>To test that everything works as it should do, we are going to paste our $mock object into the text field as plain text. If you have been following the example your pill colour will be replaced by a string representation of JavaScriptObject Notion</p>

<p>to avoid errors do note that <code>value='<?php echo $options[ $args['label_for'] ];?>'</code> we use single quote to define out html object since the compiled page will contain double quotes inside the value</p>

<pre><code>
  <?php echo esc_attr( $args['label_for'] ); ?></p>
   <p><?php echo $options[ $args['label_for'] ];?></p>
   <input type="text" 
   id="<?php echo esc_attr( $args['label_for'] ); ?>"
   name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
   value="<?php echo $options[ $args['label_for'] ];?>"</p>
</code></pre>

<p>[master 117fc17] correctly identifying the option fields from the example select and transferring them to a text input ensuring Update functionality remains intact</p>

