<?php

class ListaModel extends CI_Model
{
    
    public function __construct()
    {
        $this->load->database();
    }
    public function GetLista(){
        
        // $nReg = $this->db->count_all("productos");
        // $paginas = ceil($nReg/$tamPag);     

        $query= $this->db->get("productos");
        
        
        return Array(
            "res" => $query->result_array()
        );
    }
}