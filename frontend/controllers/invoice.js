'use strict';
yii2AngApp_invoice.config(['$routeProvider', function($routeProvider) {
  $routeProvider
    .when('/invoice/index', {
        templateUrl: 'frontend/views/invoice/index.html',
        controller: 'index'
    })
    .when('/invoice/deleted', {
        templateUrl: 'frontend/views/invoice/index.html',
        controller: 'deleted'
    })
    .when('/invoice/create', {
        templateUrl: 'frontend/views/invoice/create.html',
        controller: 'create',
        resolve: {
            invoice: function(services, $route){
                return services.getInvoices();
            }
        }
    })
    .when('/invoice/update/:invoiceId', {
        templateUrl: 'frontend/views/invoice/update.html',
        controller: 'update',
        resolve: {
          invoice: function(services, $route){
            var invoiceId = $route.current.params.invoiceId;
            return services.getInvoice(invoiceId);
          }
        }
    })
    .when('/invoice/delete/:invoiceId', {
        templateUrl: 'frontend/views/invoice/index.html',
        controller: 'delete',
    })
    .otherwise({
        redirectTo: '/invoice/index'
    });
}]);
// контроллер для главной 
yii2AngApp_invoice.controller('index', ['$scope', '$http', 'services', 'centrifugo',
    function($scope,$http,services,centrifugo) {
    services.getInvoices(1).then(function(data){
        $scope.invoices = data.data;
    });    
    //удаление счета
    $scope.deleteInvoice = function(invoiceID) {
        if(confirm("Вы уверены что хотите удалить счет: " + invoiceID)==true && invoiceID>0){
            services.deleteInvoice(invoiceID);    
         //   $route.reload();
		}
    };
    //слушаем канал в центрифуге
	centrifugo.subscribe('blabla', function (message) {
	    review_count.innerHTML = message.data.review_count;
	    deleted_count.innerHTML = message.data.deleted_count;
	    if (message.data.model) {
	    	//удалить счет из таблицы
	    	if (message.data.model.action == "delete") {
	    		$scope.invoices = $scope.invoices.filter(function (i) {
	                return (i.id != message.data.model.id);
	            });	    		
	    	}
	    	//обновить или добавить счет в таблицу
	    	if (message.data.model.action == "update") {
		    	//ищем модель
	    		var model = $scope.invoices.filter(function (i) {
	                return (i.id == message.data.model.id);
	            });	
	    		//если есть такая модель, обновляем ее в таблице
	    		if (model.length) { 
	    			for(var i = $scope.invoices.length - 1; i >= 0; i--){
	    			    if($scope.invoices[i].id == message.data.model.id){
	    			    	$scope.invoices.splice(i,1,message.data.model);
	    			    }
	    			} 
	    		//а если нет, добавляем в таблицу
	    		} else {
		  	  		$scope.invoices.push(message.data.model);
	    		}
	    	}
	  	  	$scope.$apply();
	    }		
	});
}])
// контроллер для удаленных счетов
.controller('deleted', ['$scope', '$http', 'services', 'centrifugo',
    function($scope,$http,services,centrifugo) {
    services.getInvoices(2).then(function(data){
        $scope.invoices = data.data;
    });
    //слушаем канал в центрифуге
	centrifugo.subscribe('blabla', function (message) {
	    review_count.innerHTML = message.data.review_count;
	    deleted_count.innerHTML = message.data.deleted_count;
	    if (message.data.model) {
	    	if (message.data.model.action == "delete") {	    	
	  	  		$scope.invoices.push(message.data.model);
	    	}
	  	  	$scope.$apply();
	    }	
	});
}])
// контроллер для добавления счета
.controller('create', ['$scope', '$http', 'services','$location','invoice', 
    function($scope,$http,services,$location,invoice) {
    $http.get(serviceBase + 'contractors').then(function(data){
        $scope.contractors = data.data;
    });  
    $scope.createInvoice = function(invoice) {
        var results = services.createInvoice(invoice);
    }  
}])
// контроллер для редактирования счета
.controller('update', ['$scope', '$http', '$routeParams', 'services','$location','invoice', 'FileUploader',
    function($scope,$http,$routeParams,services,$location,invoice,FileUploader) {
    $http.get(serviceBase + 'contractors').then(function(data){
        $scope.contractors = data.data;
    });
    //объявляем uploader для загрузки файлов
    $scope.uploader = new FileUploader({url : serviceBase + 'files?invoice_id=' + invoice.data.id});
    $http.get(serviceBase + 'files?invoice_id=' + invoice.data.id).then(function(data){
    	var files = data.data;

        var fileItems = [];

        for(var i in files){

            var fileItem = new FileUploader.FileItem( $scope.uploader, files[i]);

            fileItem.file.id = files[i].id;
            fileItem.progress = 100;
            fileItem.isUploaded = true;
            fileItem.isSuccess = true;

            fileItems.push(fileItem);
            $scope.uploader.queue.push(fileItem);
            $scope.uploader._onAfterAddingFile(fileItem);
        }

        $scope.uploader._onAfterAddingAll(fileItems);
        $scope.uploader._render();
    });
    // удаление из очереди
    $scope.uploader.removeFromQueue = function(value) {
    	if (value.file.id){
    	$http.delete(serviceBase + 'files/' + value.file.id)
        .catch( errorHandler );
    	}
    function errorHandler( result ){
        alert("Error data")
    }    
    var index = this.getIndexOfItem(value);
    var item = this.queue[index];
    if (item.isUploading) item.cancel();
    this.queue.splice(index, 1);
    item._destroy();
    this.progress = this._getTotalProgress();

    };
    
    //после того как файл добавлен в очередь, надо ему присвоить id и имя, полученные с сервера
    $scope.uploader.onSuccessItem = function(fileItem, response, status, headers) {
        fileItem.file.id = response.id;
        fileItem.file.name = response.name;
    };

    var original = invoice.data;
    $scope.invoice = angular.copy(original);
    $scope.isClean = function() {
        return angular.equals(original, $scope.invoice);
    }
    $scope.updateInvoice = function(invoice) {    
        var results = services.updateInvoice(invoice);
    } 
}]);