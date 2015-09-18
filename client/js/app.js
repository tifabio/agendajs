// URL de acesso ao servidor RESTful
SERVER_URL = "http://agendajs.herokuapp.com/server";

// Criação ao $app que é o modulo que representa toda a aplicação
var $app = angular.module('app',['ngRoute']);

// Configuração
$app.config(['$routeProvider',function($routeProvider) {
	$routeProvider.
	when('/',{controller:'agendaController', templateUrl:'view/list.html'}).
	when('/contato',{controller:'agendaController', templateUrl:'view/form.html'}).
	when('/contato/:id',{controller:'agendaController', templateUrl:'view/form.html'}).
	otherwise({redirectTo:'/'});
}]);

// Controller
$app.controller("agendaController", function ($scope,$http,$routeParams,$route) {

	// lista de contatos
	$scope.contatos = null;

	// um contato
	$scope.contato = null;

	$scope.getAll = function(){
		$scope.showLoader();
		$http.get($scope.server("/contatos")).success(function(data){
			$scope.contatos = data;			
			$scope.hideLoader();
		});
	}

	$scope.getById = function(){
		if ($routeParams.id!=null) {
			$scope.showLoader();
			$http.get($scope.server("/contato/" + $routeParams.id)).success(function(data) {
				$scope.contato = data;
				$scope.hideLoader();
			});
		} else {
			$scope.contato = {};
		}
	}

	$scope.save = function(){
		$scope.msg = null;
		$scope.showLoader();

		var id = ($scope.contato.id) ? $scope.contato.id : 0;

		$http.post($scope.server("/contato/" + id),$scope.contato).success(function(data) {
			$scope.msg = "Salvo com sucesso";
			$scope.contato = data;
			$scope.hideLoader();
		});
	}

	$scope.del = function(id, nome){
		if (confirm("Deseja excluir " + nome + "?")){
			$scope.msg = null;
			$scope.showLoader();
			$http.delete($scope.server("/contato/" + id)).success(function(s){
				$scope.getAll();
				$scope.msg = "Excluido com sucesso";
				$scope.hideLoader();
			});
		}
	}

});

$app.run(function($rootScope) {
	//Uma flag que define se o ícone de acesso ao servidor deve estar ativado
	$rootScope.showLoaderFlag = false;

	//Força que o ícone de acesso ao servidor seja ativado
	$rootScope.showLoader=function(){
		$rootScope.showLoaderFlag=true;
	}
	//Força que o ícone de acesso ao servidor seja desativado
	$rootScope.hideLoader=function(){
		$rootScope.showLoaderFlag=false;
	}

	// método que retorna a URL completa de acesso ao servidor. 
	$rootScope.server=function(url) {
		return SERVER_URL + url;
	}
});
