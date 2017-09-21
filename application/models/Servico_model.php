<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Servico_Model extends CI_Model {
 
 	public function get($user_details) {
		$this->db->select('*');
		$this->db->where('FranqueadaDona', $user_details[0]['franquia']);
		$this->db->from('servico');
		return $this->db->get()->result();
	}

	public function create($data) 
	{
        $this->db->insert('servico', $data);
        return $idOfInsertedData = $this->db->insert_id();
	}
	
	public function read($identificador) 
	{  
		$this->db->select('*');
		$this->db->where('id', $identificador);
		$data = $this->db->get('servico');
		$data = $data->result_array();
		return $data;
	}

	public function update($identificador) 
	{

	}

	public function delete($identificador) 
	{
		$this->db->where('id', $identificador);
		$this->db->delete('servico'); 
	}

}