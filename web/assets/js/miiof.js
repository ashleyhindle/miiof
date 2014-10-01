var miiofApp = angular.module('miiofApp', []).config(function($interpolateProvider){
	$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
    }
);

miiofApp.controller('CreateCtrl', function ($scope, $http) {
		$scope.lastInvoiceId = 999;
		$scope.invoiceItemsToAdd = 2;
		$scope.invoiceItemIdToStart = 920000;
		$scope.oldInvoiceCount = 0;

		$http({
				method: 'GET',
				url: '/listinvoices',
		}).then(function(response){
				var data = response.data;
				console.log(data);
				console.log(data.lastInvoiceId);
				if(data.lastInvoiceId) { // This is only set if they've logged in via DropBox
						$scope.lastInvoiceId = parseInt(data.lastInvoiceId);
						$scope.invoice.invoiceId = $scope.lastInvoiceId + 1;
						$scope.invoice.notes = data.lastInvoice['notes'];
						$scope.invoice.subject = data.lastInvoice['subject'];
						$scope.invoice.from = data.lastInvoice['from'];
						$scope.invoice.to = data.lastInvoice['to'];
						$scope.invoices = data.invoices;
						$scope.oldInvoiceCount = data.invoiceCount;
				}
		});

		$scope.invoice = {
				'invoiceId': $scope.lastInvoiceId + 1,
				'date': moment().format('MMMM Do YYYY'),
				'notes': "Please send payment within 14 days via bank transfer to the details below:\nSmellynose Ltd\nAccount number: xxxxxxxx\nSort Code: xxxxxx",
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
				'items': [
						{
								'itemId' : 920000,
								'description': 'Red Wine',
								'quantity': 3,
								'price': 32
						},
						{
								'itemId': 920001,
								'description': 'White Wine In The Sun',
								'quantity': 82,
								'price': 10.80
						}
				],
				'lastItemId': $scope.invoiceItemIdToStart + $scope.invoiceItemsToAdd + 1,
				'total': (3 * 32) + (82 * 10.80)
		}
		
		$scope.$watch('invoice.items', function () {
				var total = 0;
				var item = null;
				for(key in $scope.invoice.items) {
						item = $scope.invoice.items[key];
						total += item['price'] * item['quantity'];
				}
				$scope.invoice.total = total;
		},
		true); // Deep watch


		$scope.invoices = {
		
		};

		$scope.removeItem = function(item) {
				console.log('Removing invoice item with itemid: ' + item);
				var index = $scope.invoice['items'].indexOf(item)
				$scope.invoice['items'].splice(index, 1); 
		};

		$scope.addItem = function() {
				$scope.invoice['items'].push({
						'itemId': $scope.invoice.lastItemId,
						'description': '',
						'quantity': 1,
						'price': 0.00
				});

				console.log('Added invoice item with itemid: ' + $scope.invoice.lastItemId);

				$scope.invoice.lastItemId++;
		};
});
