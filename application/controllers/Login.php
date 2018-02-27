<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct ();
		$this->load->model ( "login_model" );
	}

	public function index()
	{
		$data = json_decode(file_get_contents("php://input"));
		// Recebe o login
		$username = $data->username;
		// Recebe a senha
		$password = $data->password;

		$retorno = array("resposta" => "0", "mensagem" => "Erro inesperado.");

		if (!$this->authentication->is_loggedin()) {
			if (isset($username) || isset($password)) {

				// Chega os dados informados
				if ($this->authentication->login($username, $password))
				{
					// Usuário fez login com sucesso
					$sessao[0] = $this->authentication->read('identifier');
					$sessao[1] = $this->authentication->read('username');

					$retorno = array("resposta" => "1", "mensagem" => "Logado com sucesso, seja bem vindo", "sessao" => $sessao);

				} else {
					// Erro encontrado ao fazer login
					$retorno = array("resposta" => "0", "mensagem" => "Dados informados estão incorretos.");
				}		
			} else {
				$retorno = array("resposta" => "0", "mensagem" => "Informe todos os paramêtros necessarios.");
			}
		} else {
			$sessao[0] = $this->authentication->read('identifier');
			$sessao[1] = $this->authentication->read('username');

			$retorno = array("resposta" => "1", "mensagem" => "Você já está logado.", "sessao" => $sessao);	
		}
		echo json_encode($retorno);
	}

	public function deslogar()
	{
		if (!$this->authentication->is_loggedin()) {	
			$retorno = array("resposta" => "0", "mensagem" => "Você não está logado.");
		} else {
			$retorno = array("resposta" => "1", "mensagem" => "Deslogado com sucesso.");
			$this->authentication->logout();
		}
		echo json_encode($retorno);
	}

	public function listarUsuarios()
	{
		if (!$this->authentication->is_loggedin()) {	
			$retorno = array("resposta" => "0", "mensagem" => "Você não está logado.");
		} else {
			$user_identifier = $this->authentication->read('identifier');
			$user_details = $this->login_model->getUsuario($user_identifier);

			if($user_details[0]['tipoAcesso'] == 3) {
				//$franquia = $this->franqueado_model->get();
				$retorno = $this->login_model->get();	
			} else {
				$retorno = $this->login_model->read($user_details[0]['franquia']);	
			}					
		}			
		echo json_encode($retorno);
	}

	public function detalhaUsuario()
	{
		if (!$this->authentication->is_loggedin()) {	
			$retorno = array("resposta" => "0", "mensagem" => "Você não está logado.");
		} else {

			$user_identifier = $this->authentication->read('identifier');
			$retorno = $this->login_model->getUsuario($user_identifier);
		}			 
		echo json_encode($retorno);
	}
	
	public function deletar()
	{
		$data = json_decode(file_get_contents("php://input"));

		$user_identifier = $data->id;

		if ($this->authentication->delete_user($user_identifier))
		{
			$retorno = array("resposta" => "1", "mensagem" => "Deletado com sucesso!");
		} else {
			$retorno = array("resposta" => "0", "mensagem" => "Erro ao deletar usuário!");
		}		
		echo json_encode($retorno);
	}
	
	public function registrar()
	{

		$data = json_decode(file_get_contents("php://input"));

		// Recebe o login
		$username = $data->email;
		// Recebe a senha
		$password = $data->senha;

		$retorno = array("resposta" => "0", "mensagem" => "Erro inesperado.");

		if (isset($username) || isset($password)) {
			$user_id = $this->authentication->create_user($username, $password);

			// Chega os dados informados
			if ($user_id !== FALSE)
			{
				$this->db->set('first_name', $data->nome);
				$this->db->set('telefone', $data->telefone);
				$this->db->set('franquia', $data->franquia);
				$this->db->set('tipoAcesso', $data->tipoUsuario);
				$this->db->where('id', $user_id);
				$this->db->update('usuarios'); 

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
