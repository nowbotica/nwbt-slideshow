window.tzSliderSystemApiUrl   = tz_slider_object.ajax_url;
window.tzSliderUrlDomainPath  = tz_slider_object.url_domain_path;
window.tzSliderPartialsPath   = tz_slider_object.partials_path;
window.tzSliderImagePath      = tz_slider_object.image_path;
window.tzSliderFormPath       = tz_slider_object.form_path;
window.tzSliderSystemSecurity = tz_slider_object.ajax_nonce;

// Declare app level module which depends on filters, and services
var TzSliderConfigApp = angular.module('TzSliderConfigApp',
    [
    ]);

/* https://torquemag.io/2015/12/creating-javascript-single-page-app-wordpress-dashboard/
* Makes everything better
*/
console.log(TzSliderConfigApp)
TzSliderConfigApp.directive('tzEditSlideshow', ['$parse', function($parse){
    return {
        replace: true,
        templateUrl: tzSliderPartialsPath+'/config-template.html',
        scope: {
          slideshowdata: '=slideshowdata'
        },
        link: function(scope, element, attr) {
        	console.log('data compiled from php', scope.slideshowdata)

        	// scope.resultblob = scope.slideshowdata
        	
        },
        controller: function($scope){
        	// console.log
        	$scope.slideshowdata.settings.width = 'full'
        	$scope.resultblob = JSON.stringify($scope.slideshowdata);
        }
    };
}]);