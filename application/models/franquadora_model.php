<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Franquadora_Model extends CI_Model {
 
	public function create($identificador) 
	{

	}
	
	public function read($identificador) 
	{
		$this->db->select('*');
		$this->db->where('id', $identificador);
		$data = $this->db->get('usuarios');
		$data = $data->result_array();
		return $data;
	}

	public function update($identificador) 
	{

	}

	public function delete($identificador) 
	{

	}

}