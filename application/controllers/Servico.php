<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servico extends CI_Controller {

	public function __construct() {
		parent::__construct ();
		$this->load->model("servico_model");

		$configUpload['upload_path']    = './uploads/';                 #the folder placed in the root of project
		$configUpload['allowed_types']  = 'gif|jpg|png|bmp|jpeg';       #allowed types description
		$configUpload['max_size']       = '0';                          #max size
		$configUpload['max_width']      = '0';                          #max width
		$configUpload['max_height']     = '0';                          #max height
		$configUpload['encrypt_name']   = true;                         #encrypt name of the uploaded file
		$this->load->library('upload', $configUpload);                  #init the upload class
	}

	public function index()
	{
		$retorno = array("resposta" => "0", "mensagem" => "Erro inesperado.");

		if ($this->authentication->is_loggedin()) {
			$user_identifier = $this->authentication->read('identifier');
			$retorno = $this->servico_model->read($user_identifier);
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
			$retorno = array("resposta" => "0", "mensagem" => "Você não está logado.");
		} else {
			$retorno = $this->servico_model->read($data);
			
			$this->db->where('servicoDono', $data);
			$q = $this->db->get('fotos');
			$data = $q->result_array();	
			$retorno['fotos'] = $data;
		}			
		echo json_encode($retorno);
	}

	public function deletar()
	{
		$data = json_decode(file_get_contents("php://input"));
		
		$id_usuario = $data->id;

		$this->servico_model->delete($id_usuario);
		
		$retorno = array("resposta" => "1", "mensagem" => "Deletado com sucesso!");
		
		echo json_encode($retorno);
	}

	public function registrar()
	{

		$data = json_decode(file_get_contents("php://input"));

		$retorno = array("resposta" => "0", "mensagem" => "Erro inesperado.");

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

				$this->load->model("fotos_model");
				if ($data->fotos) {
					foreach ($data->fotos as $key => $value) {
						$decoded=base64_decode($value);
						$nomeImagem = uniqid();
						$caminhoImagem = 'uploads/'.$nomeImagem.'.jpg';
						file_put_contents($caminhoImagem, $decoded);									
						
						$data = array(
						'nome' => $nomeImagem ,
						'caminho' => 'uploads/'.$nomeImagem.'.jpg' ,
						'link' => 'http://'.$_SERVER['SERVER_NAME'].'/'.$caminhoImagem,
						'servicoDono' => $user_id,
						'data' => date("Y-m-d H:i:s")
						);

						$fotos = $this->fotos_model->create($data);
					}
				}
				//$this->load->model("fotos_model");
				//$fotos = $this->fotos_model->create($data);
				

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

	public function editar()
	{

		$data = json_decode(file_get_contents("php://input"));

		$retorno = array("resposta" => "0", "mensagem" => "Erro inesperado.");

		if (isset($data)) {
			$user_id = $this->servico_model->update($data->id, $data);

			// Checa os dados informados
			if ($user_id !== FALSE)
			{
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
