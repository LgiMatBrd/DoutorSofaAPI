<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servico extends CI_Controller {

	public function __construct() {
		parent::__construct ();
		$this->load->model("servico_model");
	}

	public function index()
	{
		$retorno = ["resposta" => "0", "mensagem" => "Erro inesperado."];

		if ($this->authentication->is_loggedin()) {
			$user_identifier = $this->authentication->read('identifier');
			$retorno = $this->servico_model->read($user_identifier);
		} else { 
			$retorno = ["resposta" => "0", "mensagem" => "Você deve estar logado."];	
		}
		echo json_encode($retorno);
	}

	public function listar()
	{
		if (!$this->authentication->is_loggedin()) {	
			$retorno = ["resposta" => "0", "mensagem" => "Você não está logado."];
		} else {
			$this->load->model("login_model");
			$user_identifier = $this->authentication->read('identifier');
			$user_details = $this->login_model->getUsuario($user_identifier);

			$retorno = $this->servico_model->get($user_details);	
		}			
		echo json_encode($retorno);
	}

	public function detalha()
	{
		$data = json_decode(file_get_contents("php://input"));

		if (!$this->authentication->is_loggedin()) {	
			$retorno = ["resposta" => "0", "mensagem" => "Você não está logado."];
		} else {
			$retorno = $this->servico_model->read($data);	
		}			
		echo json_encode($retorno);
	}

	public function deletar()
	{
		$data = json_decode(file_get_contents("php://input"));
		
		$id_usuario = $data->id;

		$this->servico_model->delete($id_usuario);
		
		$retorno = ["resposta" => "1", "mensagem" => "Deletado com sucesso!"];
		
		echo json_encode($retorno);
	}

	public function registrar()
	{

		$data = json_decode(file_get_contents("php://input"));

		$retorno = ["resposta" => "0", "mensagem" => "Erro inesperado."];

		if (isset($data)) {
			$user_id = $this->servico_model->create($data);

			// Chega os dados informados
			if ($user_id !== FALSE)
			{
				// Usuário fez login com sucesso
				$this->load->model("login_model");
				$user_identifier = $this->authentication->read('identifier');
				$user_details = $this->login_model->getUsuario($user_identifier);
				$this->db->set('FranqueadaDona', $user_details[0]['franquia']);
				$this->db->where('id', $user_id);
				$this->db->update('servico'); 

				$retorno = ["resposta" => "1", "mensagem" => "Registrado com sucesso, seja bem vindo"];
			} else {
				// Erro encontrado ao fazer login
				$retorno = ["resposta" => "0", "mensagem" => "Erro ao registrar um novo usuário, já registrado."];
			}		
		} else {
			$retorno = ["resposta" => "0", "mensagem" => "Informe todos os paramêtros necessarios."];
		}

		echo json_encode($retorno);
	}	

}
