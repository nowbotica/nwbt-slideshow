window.tzSliderSystemApiUrl   = tz_slider_object.ajax_url;
window.tzSliderUrlDomainPath  = tz_slider_object.url_domain_path;
window.tzSliderPartialsPath   = tz_slider_object.partials_path;
window.tzSliderImagePath      = tz_slider_object.image_path;

// Declare app level module which depends on filters, and services
var TzSliderConfigApp = angular.module('TzSliderConfigApp', []);

/* 
* Makes everything better
*/
console.log(TzSliderConfigApp)
TzSliderConfigApp.directive('tzEditSlideshow', ['$parse', function($parse){
    return {
        replace: true,
        templateUrl: tzSliderPartialsPath+'/config-template.html',
        scope: {
          data: '=slideshowValue',
          name: '@slideshowName',
          id:   '@slideshowId'
        },
        link: function(scope, element, attr) {
        	console.log('data compiled from php')
        	console.log('slideshowName', scope.name);
        	console.log('slideshowData', scope.data);
        	console.log('slideshowId', scope.id);
        },
        controller: function($scope){

        	$scope.data.settings.width = 'fully functional';
        	$scope.value = JSON.stringify($scope.data);
        
        }
    };
}]);