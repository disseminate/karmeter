(function(){
	var app = angular.module('meterApp', []);

	meterApp.controller('scoreController', ['$scope', function(){
		$scope.score = 0;
	}])

	meterApp.directive('meterWidget', ['', function(){
		var linkFn;

		linkFn = function(scope, element, attrs){
			var animateLeft, animateRight, indicator;

			indicator = angular.element(element.children()[0]);

			animateLeft = function(){
				$(this).animate({
					left: '-=5'
				})
			};

			animateRight = function(){
				$(this).animate({
					left: '+=5'
				})
			};
		}

		$(indicator).on()

		// Runs during compile


		return {
			restrict: 'E',
        	link: linkFn
		};
	}]);
	app.controller('PanelController', function(){

	});
})();