angular.module('microAzureBlog', ['azure-mobile-service'])
angular.module('your-module-name').constant('AzureMobileServiceClient', {
    API_URL: 'https://<your-api-url>.azure-mobile.net/',
    API_KEY: '<your-api-key>',
})
.controller('blogpostController', function ($scope, $http, Azureservice) {

});

