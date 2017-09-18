<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
		// Recebe o login
		$username = $this->input->get('username', TRUE);
		// Recebe a senha
		$password = $this->input->get('password', TRUE);

		if (!$this->authentication->is_loggedin()) {
			if (isset($username) || isset($password)) {

				// Chega os dados informados
				if ($this->authentication->login($username, $password))
				{
					// Usuário fez login com sucesso
					echo 'Logado com sucesso, seja bem vindo';
				} else {
					// Erro encontrado ao fazer login
					echo 'Dados informados estão incorretos.';
				}		
			} else {
				echo 'Informe todos os paramêtros necessarios.';
			}
		} else {
			echo 'Você está logado.';
		}
	}

	public function deslogar()
	{
		$this->authentication->logout();
	}
	
	public function registrar()
	{
		// Recebe o login
		$username = $this->input->get('username');
		// Recebe a senha
		$password = $this->input->get('password');

		var_dump($username);
		var_dump($password);
		var_dump($_POST['password']);

		if (!$this->authentication->is_loggedin()) {		
			if (isset($username) || isset($password)) {
				$user_id = $this->authentication->create_user($username, $password);

				// Chega os dados informados
				if ($user_id !== FALSE)
				{
					// Usuário fez login com sucesso
					echo 'Registrado com sucesso, seja bem vindo';
				} else {
					// Erro encontrado ao fazer login
					echo 'Erro ao registrar um novo usuário';
				}		
			} else {
				echo 'Informe todos os paramêtros necessarios.'.$username.$password;
			}
		} else {
			echo 'Você está logado.';
		}
	}
}
