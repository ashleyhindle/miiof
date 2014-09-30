var miiofApp = angular.module('miiofApp', []).config(function($interpolateProvider){
	$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
    }
);

miiofApp.controller('CreateCtrl', function ($scope) {
		$scope.lastInvoiceId = 1000;
		$scope.exampleInvoice = {
				'invoiceId': 1000,
				'notes': "Bank details go here, maybe payment terms",
				'currency': {
						'code': 'GBP',
						'html': '&pound;',
						'symbol': '£'
				},
				'subject': 'My First Invoice!',
				'from': {
						'email': 'me@example.com',
						'name': 'Example Company Ltd',
						'phone': '+44 121 621 XXXX',
						'address': 'My Address, Possum Lane, Barbankshire, OE8 XN48'
				},
				'to': {
						'email': 'you@moreexamples.com',
						'name': 'Another Company Ltd',
						'phone': '+1 9120 621 XX',
						'address': 'Idontknow American Addresses, 42nd Street, LA'
				},
				'items': {
						0: {
								'itemId' : 920000,
								'description': 'Red Wine',
								'quantity': 3,
								'price': 32
						},
						1: {
								'itemId': 920001,
								'description': 'White Wine In The Sun',
								'quantity': 82,
								'price': 10.80
						}
				},
				'total': (3 * 32) + (82 * 10.80)
		}

		$scope.invoices = {
		
		};
});
