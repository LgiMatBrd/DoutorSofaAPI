<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Franqueado_Model extends CI_Model {
 
 	public function get() {
		$this->db->select('*');
		$this->db->from('franqueados');
		return $this->db->get()->result();
	}

	public function create($data) 
	{
        $this->db->insert('franqueados', $data);
        return $idOfInsertedData = $this->db->insert_id();
	}
	
	public function read($identificador) 
	{  
		$this->db->select('*');
		$this->db->where('id', $identificador);
		$data = $this->db->get('franqueados');
		$data = $data->result_array();
		return $data;
	}

	public function update($identificador) 
	{

	}

	public function delete($identificador) 
	{
		$this->db->where('id', $identificador);
		$this->db->delete('franqueados'); 
	}
	
	public function BuscaFranquiaUsuario($identificador) 
	{  
		$this->db->select('*');
		$this->db->where('id', $identificador);
		$data = $this->db->get('franqueados');
		$data = $data->result();
		return $data;
	}

}