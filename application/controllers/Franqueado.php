<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franqueado extends CI_Controller {

	public function __construct() {
		parent::__construct ();
		$this->load->model("franqueado_model");
	}

	public function index()
	{ 
		$retorno = array("resposta" => "0", "mensagem" => "Erro inesperado.");

		if ($this->authentication->is_loggedin()) {
			$user_identifier = $this->authentication->read('identifier');
			$retorno = $this->franqueado_model->read($user_identifier);
		} else { 
			$retorno = array("resposta" => "0", "mensagem" => "Você deve estar logado.");	
		}
		echo json_encode($retorno);
	}

	public function listar()
	{
		if (!$this->authentication->is_loggedin()) {	
			$retorno = array("resposta" => "0", "mensagem" => "Você não está logado.");
		} else {
			$retorno = $this->franqueado_model->get();	
		}			
		echo json_encode($retorno);
	}

	public function deletar()
	{
		$data = json_decode(file_get_contents("php://input"));

		$id_usuario = $data->id;

		$this->franqueado_model->delete($id_usuario);
		
		$retorno = array("resposta" => "1", "mensagem" => "Deletado com sucesso!");
		
		echo json_encode($retorno);
	}

	public function registrar()
	{

		$data = json_decode(file_get_contents("php://input"));

		$retorno = array("resposta" => "0", "mensagem" => "Erro inesperado.");

		if (isset($data)) {
			$user_id = $this->franqueado_model->create($data);

			// Chega os dados informados
			if ($user_id !== FALSE)
			{
				// Usuário fez login com sucesso
				$retorno = array("resposta" => "1", "mensagem" => "Registrado com sucesso, seja bem vindo");
			} else {
				// Erro encontrado ao fazer login
				$retorno = array("resposta" => "0", "mensagem" => "Erro ao registrar um novo usuário, já registrado.");
			}		
		} else {
			$retorno = array("resposta" => "0", "mensagem" => "Informe todos os paramêtros necessarios.");
		}

		echo json_encode($retorno);
	}	

}
