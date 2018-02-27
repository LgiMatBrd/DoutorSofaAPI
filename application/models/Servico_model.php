<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Servico_Model extends CI_Model {
 
 	public function get($identificador) {
		$this->db->select('*');
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
		$this->db->where('FranqueadaDona', $identificador);
		$this->db->order_by("data", "asc");
		$data = $this->db->get('servico');
		$data = $data->result_array();
		return $data;
	}
	
	public function read2($identificador) 
	{  
		$this->db->select('*');
		$this->db->where('id', $identificador);
		$this->db->order_by("data", "asc");
		$data = $this->db->get('servico');
		$data = $data->result_array();
		return $data;
	}

	public function update($id,$data) 
	{
		$this->db->where('id', $id);
		$this->db->update('servico', $data);
	}

	public function delete($identificador) 
	{
		$this->db->where('id', $identificador);
		$this->db->delete('servico'); 
	}

	public function BuscaDadosGrafico1($identificador)
	{
		$sql = "SELECT 
		date_format(servico.`data`,'%Y-%m-%d') as dia,
		coalesce(SUM(servico.preco), 0) as total
		from servico
		where servico.`status` = 1
		and servico.FranqueadaDona = '".$identificador."'
		GROUP BY date_format(servico.`data`,'%Y-%m-%d')";
		
		$dadosGrafico = $this->db->query($sql)->result();
		return $dadosGrafico;		
	}

}