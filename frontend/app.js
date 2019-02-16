'use strict';

var serviceBase = '/web/';

var yii2AngApp = angular.module('yii2AngApp', [
  'ngRoute',
  'yii2AngApp.invoice',
  'Centrifuge',
  'angularFileUpload'
]);


var yii2AngApp_invoice = angular.module('yii2AngApp.invoice', ['ngRoute','Centrifuge']);



yii2AngApp.config(['$routeProvider', function($routeProvider) {
  $routeProvider.otherwise({redirectTo: '/invoice/index'});
}]);

