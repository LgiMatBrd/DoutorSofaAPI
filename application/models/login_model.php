<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Login_Model extends CI_Model {
 
public function get() {
 
                        $this->db->select('*');
 
                        $this->db->from('usuarios');
 
            return $this->db->get()->result();
 
            }
 
            public function post($itens){
 
                        $res = $this->db->insert('usuarios', $itens);
 
                        if($res){
 
                                   return $this->get();
 
                        }else{
 
                                   return FALSE;
 
                        }
 
            }
 
}