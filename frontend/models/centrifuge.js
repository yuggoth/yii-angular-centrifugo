'use strict';
angular.module('Centrifuge', [])
.service('centrifugo', function () {
	var centrifuge = new Centrifuge({
	    url: 'http://localhost:8000/connection',
	    insecure: true
	});

	centrifuge.connect();

    return centrifuge;
});