<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Login_Model extends CI_Model {
 
	public function getUsuario($identificador) {
		$this->db->select('*');
		$this->db->where('id', $identificador);
		$data = $this->db->get('usuarios');
		$data = $data->result_array();
		return $data;
	}

	public function get() {
		$this->db->select('*');
		$this->db->from('usuarios');
		return $this->db->get()->result();
	}
	
	public function read($identificador) 
	{  
		$this->db->select('*');
		$this->db->where('franquia', $identificador);
		$data = $this->db->get('usuarios');
		$data = $data->result();
		return $data;
	}

	public function post($itens) {
		$res = $this->db->insert('usuarios', $itens);
		if($res){
			return $this->get();
		} else {
			return FALSE;
		}
	}

}