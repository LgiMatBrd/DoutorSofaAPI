'use strict';
angular.module('todoApp', ['ngRoute', 'azure-mobile-service.module']).constant('AzureMobileServiceClient', {
    API_URL: 'https://VisualStudio/SPNc473c603-e199-4d3c-be06-a918d8134f54',
    API_KEY: '0697b073-ee12-4c7b-9077-5f65b9eafbe3',
  })
.config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {
    
    $routeProvider.when("/Login", {
        controller: "loginCtrl",
        templateUrl: "/App/Views/Login.html",
    }).when("/Home", {
        controller: "homeCtrl",
        templateUrl: "/App/Views/Home.html",
    }).when("/TodoList", {
        controller: "todoListCtrl",
        templateUrl: "/App/Views/TodoList.html",
        requireADLogin: true,
    }).when("/UserData", {
        controller: "userDataCtrl",
        templateUrl: "/App/Views/UserData.html",
    }).otherwise({ redirectTo: "/Home" });  
}]);
