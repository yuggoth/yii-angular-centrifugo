'use strict';
yii2AngApp_invoice.factory("services", ['$http','$location','$route', 
    function($http,$location,$route) {
    var obj = {};
    obj.getContractors = function(){
        return $http.get(serviceBase + 'contractors');
    } 	 
    return obj;   
}]);