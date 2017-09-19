<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franquadora extends CI_Controller {

	public function __construct() {
		parent::__construct ();
		$this->load->model ( "franquadora_model" );
	}

	public function index()
	{
		$retorno = ["resposta" => "0", "mensagem" => "Erro inesperado."];

		if ($this->authentication->is_loggedin()) {
			$user_identifier = $this->authentication->read('identifier');
			$retorno = $this->login_model->read($user_identifier);
		} else {
			$retorno = ["resposta" => "0", "mensagem" => "Você deve estar logado."];	
		}
		echo json_encode($retorno);
	}

}
