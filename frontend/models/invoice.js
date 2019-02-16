'use strict';
yii2AngApp_invoice.factory("services", ['$http','$location','$route', 
    function($http,$location,$route) {
    var obj = {};
    obj.getInvoices = function(status){
        return $http.get(serviceBase + 'invoices',
		{params: { status: status }});
    } 	
    obj.createInvoice = function (invoice) {
        return $http.post( serviceBase + 'invoices', invoice )
            .then( successHandler )
            .catch( errorHandler );
        function successHandler( result ) {
            $location.path('/invoice/index');            
        }
        function errorHandler( result ){
            alert("Error data")
            $location.path('/invoice/create')
        }
    };    
    obj.getInvoice = function(invoiceID){
        return $http.get(serviceBase + 'invoices/' + invoiceID);
    }
 
    obj.updateInvoice = function (invoice) {
        return $http.put(serviceBase + 'invoices/' + invoice.id, invoice )
            .then( successHandler )
            .catch( errorHandler );
        function successHandler( result ) {
            $location.path('/invoice/index');
        }
        function errorHandler( result ){
            alert("Error data")
            $location.path('/invoice/update/' + invoice.id)
        }    
    };    
    obj.deleteInvoice = function (invoiceID) {
        return $http.delete(serviceBase + 'invoices/' + invoiceID)
            .then( successHandler )
            .catch( errorHandler );
        function successHandler( result ) {
            $route.reload();
        }
        function errorHandler( result ){
            alert("Error data")
            $route.reload();
        }    
    };    
    return obj;   
}]);