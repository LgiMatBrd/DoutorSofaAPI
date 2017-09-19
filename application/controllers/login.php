<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct ();
		// carregando o Model welcome
		$this->load->model ( "login_model" );
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$data = json_decode(file_get_contents("php://input"));
		// Recebe o login
		$username = $data->username;
		// Recebe a senha
		$password = $data->password;

		$retorno = ["resposta" => "0", "mensagem" => "Erro inesperado."];

		if (!$this->authentication->is_loggedin()) {
			if (isset($username) || isset($password)) {

				// Chega os dados informados
				if ($this->authentication->login($username, $password))
				{
					// Usuário fez login com sucesso
					$retorno = ["resposta" => "1", "mensagem" => "Logado com sucesso, seja bem vindo"];
				} else {
					// Erro encontrado ao fazer login
					$retorno = ["resposta" => "0", "mensagem" => "Dados informados estão incorretos."];
				}		
			} else {
				$retorno = ["resposta" => "0", "mensagem" => "Informe todos os paramêtros necessarios."];
			}
		} else {
			$retorno = ["resposta" => "1", "mensagem" => "Você já está logado."];
		}
		echo json_encode($retorno);
	}

	public function deslogar()
	{
		if (!$this->authentication->is_loggedin()) {	
			$retorno = ["resposta" => "0", "mensagem" => "Você não está logado."];
		} else {
			$retorno = ["resposta" => "1", "mensagem" => "Deslogado com sucesso."];
			$this->authentication->logout();
		}
		echo json_encode($retorno);
	}

	public function listarUsuarios()
	{
		if (!$this->authentication->is_loggedin()) {	
			$retorno = ["resposta" => "0", "mensagem" => "Você não está logado."];
		} else {
			$res = $this->login_model->get();	
			echo json_encode($res);	
		}			
		echo json_encode($retorno);
	}
	
	public function registrar()
	{

		$data = json_decode(file_get_contents("php://input"));
		// Recebe o login
		$username = $data->username;
		// Recebe a senha
		$password = $data->password;

		$retorno = ["resposta" => "0", "mensagem" => "Erro inesperado."];

		if (!$this->authentication->is_loggedin()) {		
			if (isset($username) || isset($password)) {
				$user_id = $this->authentication->create_user($username, $password);

				// Chega os dados informados
				if ($user_id !== FALSE)
				{
					// Usuário fez login com sucesso
					$retorno = ["resposta" => "1", "mensagem" => "Registrado com sucesso, seja bem vindo"];
				} else {
					// Erro encontrado ao fazer login
					$retorno = ["resposta" => "0", "mensagem" => "Erro ao registrar um novo usuário, já registrado."];
				}		
			} else {
				$retorno = ["resposta" => "0", "mensagem" => "Informe todos os paramêtros necessarios."];
			}
		} else {
			$retorno = ["resposta" => "1", "mensagem" => "Você já está logado."];
		}
		echo json_encode($retorno);
	}
}
