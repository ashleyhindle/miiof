var miiofApp = angular.module('miiofApp', []).config(function($interpolateProvider){
	$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
    }
);

miiofApp.controller('CreateCtrl', function ($scope) {
		$scope.lastInvoiceId = 1000;
		$scope.baseItems = {
				'920000' : {
						'itemId': 920000,
						'description': 'Red Wine',
						'quantity': 3,
						'price': 120
				}
		};
		$scope.invoices = {
		
		};
});
