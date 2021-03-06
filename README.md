# Engineering Log Book

## Product Story 

This plugin aims to provide a fast and flexible system for building modern user interfaces to wordpress plugin settings pages. The key feature is the ability to create only a single data node using the wp-settings api and use this to persist a whole object of config changes. 

### Here we show input and output states of our directive

Using php to inject a string into the directive is helpful for this use case of Angularjs. Our aim is to avoid the maintaince overhead of setting up each data node required by our widget using the brittle and complicated wp_settings api.
  
We have already used the api as demonstrated on the [relevant codex page](https://developer.wordpress.org/plugins/settings/custom-settings-page/) to give us a single datapoint `$args['label_for']` of which we have cRUd like control over. If possible we want to avoid adding extra boilerplate code to handle ajax, so no admin_ajax endpoint and no service layer to our microapp. We are not too worried about security at this stage since a mallious editor having access to the site admin would already have complete control over the source code

We use angulars `'=string'` as our scope datatype `tz-edit-slideshow slideshowdata='< ?= echo $mock; ?>'></tz-edit-slideshow` meaning that when the directive compiles scope.slideshowdata will be assigned the value stored in our $mock on the php side.

Finally we output to our config-template.html `<h4>Data to save: {{resultblob}}</h4>` by stringifying our local javascript object in JSON and returning this to the view. Notice in our controller function we have changed one of the options in our mock data object and we can see this change in the resultant template.

```
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
 

  <h4>Data to save: {{resultblob}}</h4>

```

We now have our `do_settings_sections( 'tz-slideshow-options' );` to look at. We know this iterates over a number of sections and displays the code for each one. The example had already set a field using a more complex select option. We only a basic text input for our functionality. This could be hidden in the final build but allows to easily see that our angular code is updating the scope.

To test that everything works as it should do, we are going to paste our $mock object into the text field as plain text. If you have been following the example your pill colour will be replaced by a string representation of JavaScriptObject Notion

To avoid errors do note that `value='<?php echo $options[ $args['label_for'] ];?>'` we use single quote to define out html object since the compiled page will contain double quotes inside the value

```
  <?php echo esc_attr( $args['label_for'] ); ?></p>
   <p><?php echo $options[ $args['label_for'] ];?></p>
   <input type="text" 
   id="<?php echo esc_attr( $args['label_for'] ); ?>"
   name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
   value="<?php echo $options[ $args['label_for'] ];?>"</p>

  [master 117fc17] correctly identifying the option fields from the example select and transferring them to a text input ensuring Update functionality remains intact
```

Now we have our $mock array saved as the value for `$options[ $args['label_for']]` we can test our component works so far by using the saved value in our angular component

```
<section ng-app="TzSliderConfigApp">
    <!-- <tz-edit-slideshow slideshowdata='{"settings": {"width":"contained", "show_captions":true}, "slides":[{"image_id":734, "caption":"Slide One"},{"image_id":735, "caption":"Slide Two"}]}'></tz-edit-slideshow> -->
    <tz-edit-slideshow slideshowdata='<?php echo $options[ $args['label_for'] ];?>'></tz-edit-slideshow>
    
</section>
```

Our next problem is that while we can return `{{resultblob}}` inside our directive template we cannot use to this to update our text input in the existing code. We need to move this code inside the template somewhere. We are looking for something that looks like:</p>

```
  <!-- slideshowdata='<?php echo $options[ $args['label_for'] ];?>' -->
  <tz-edit-slideshow 
  slideshow_id="<?php echo esc_attr( $args['label_for'] ); ?>"
  slideshow_value='<?php echo $options[ $args["label_for"] ];?>'
  slideshow_name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
  ></tz-edit-slideshow>
```

To avoid intotroducing unnessary error into the fairly confusing angular directive set up, we can start by only changing `slideshowdata=""` to `slideshow_value=""` and inside our directive scope object `scope: {slideshowdata: '=slideshow_value'}`. Hopefully Angularjs will be kind to use and not require switching between camel and hyphenate cases (such as in directive name above) [it wasn't].

#### `slideshowdata: '=slideshowValue' === slideshow-value='<?php echo $options[ $args["label_for"] ];?>'`

The real beauty of mixing Angularjs with PHP in this context is that php doesn't really care what you do on the clientside so long as when your form submits it can find the correct key value pairs to post. This means we can simply pass the config values stored in the database at run time to our view, make all our changes to the data using Angularjs (or jQuery even if you're being pragmatic) and update these in our directive template.

After a little messing about with names for things here are the essential functions from the final plugin

```

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
   

 <?php
}

<!-- config template.html -->
<div>
  <input type="text" 
  id="{{id}}"
  name="{{name}}"
  value='{{value}}'
  >
  <!-- // note '' around value vs "" -->
  <h4>ID of field: {{id}}</h4>
  <h4>Name of field: {{name}}</h4>
  <h4>Value to save: {{value}}</h4>
</div>

<!-- tz-slider-config.js -->

```

In the next stage of the build we want to build an actual set of ui elements to manipulate our data in a logical way. Starting by recreating the simple red pill blue pill radio options on the original php code to update the settings field we get</p>

```
  <label>
        <input type="radio" name="project"
               value="contained" ng-model="data.settings.width" />
        Contained
    </label>
    <label>
        <input type="radio" name="project"
               value="full-width" ng-model="data.settings.width" />
        Full Width
    </label>
```

Our next section uses ng-repeat to display slides.

```
    <div ng-repeat="slide in data.slides">
      <label>Image ID</label>
      <input type="number" ng-model="slide.image_id">
      <label>Caption</label>
      <input type="text" ng-model="slide.caption">
    </div>
```

Hopefully by trying it out on your own system you can how this little proof of concept apps provides a simple, terse and flexible way to add additional config options to your wordpress settings pages.

```
git commit -m "proof of concept"
[master 1d5bc30] proof of concept
2 files changed, 53 insertions(+), 4 deletions(-)
```

We can make it more useful by added the functionality to create new slides and delete unwanted slides

```
<button class="button" ng-click="addSlide()">Add Slide</button>
controller: function($scope){

    $scope.addSlide = function(){
      $scope.data.slides.push({"image_id": 1, "caption":"change caption" })
    }
```

```
<div ng-repeat="slide in data.slides track by $index">
  <label>Image ID</label>
  <input type="number" ng-model="slide.image_id">
  <label>Caption</label>
  <input type="text" ng-model="slide.caption">
  <a class="button" ng-click="removeSlide($index)">Add Slide</a>
</div>
controller: function($scope){
  $scope.removeSlide = function(index){
    $scope.data.slides.splice(index, 1)
  }
```

## Setup

Just install via wordpress and hope it doesn't break your site.

## Todo
- migrate to yarn
- https://torquemag.io/2015/12/creating-javascript-single-page-app-wordpress-dashboard