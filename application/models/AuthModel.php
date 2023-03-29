<?php

class AuthModel extends CI_Model
{
    
    public function __construct()
    {
        $this->load->database();
        
    }
    public function GetCliente($usuario,$password){
        //BORRAR EN PRODUCCION
        $this->SetTemporizador();
        ////////
        $query= $this->db->get_where('clientes',['usuario' => $usuario, 'password' => $password,'estado'=> 1]);
        return $query->row_array();
        
    }
    public function GetAdmin($usuario,$password){
        //BORRAR EN PRODUCCION
        $this->SetTemporizador();
        ////////
        $query= $this->db->get_where('usuarios',['usuario' => $usuario, 'password' => $password, 'activo'=> 1]);
        return $query->row_array();
        
    }

    //BORRAR EN PRODUCCION
    public function SetTemporizador(){
        // $this->db->set('fecha', 'SYSDATE()', FALSE);
        // $this->db->where('id', 1);
        // $this->db->update('temporizador');

        $sql = "call RECREAR_BASE()";
        $this->db->query($sql);  
        
    }
}