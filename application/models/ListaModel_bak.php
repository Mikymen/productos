<?php

class ListaModel extends CI_Model
{    
    public function __construct()
    {
        $this->load->database();
    }
    //productos
    public function GetLista($idLabo, $npagina, $tamPag){
        
        $nReg = $this->db->where('idLaboratorio', $idLabo)->count_all_results("productos");
        //$paginas = ceil($nReg/$tamPag);
        $offset = ($npagina-1)*$tamPag;
        $query= $this->db->limit( $tamPag,$offset)->where('idLaboratorio', $idLabo)->order_by('producto')->get("vwproductos");      
        
        return Array(
            "res" => $query->result_array(),
            "paginas" => $nReg,
            "npagina" => $npagina,
            "tamPag" => $tamPag
        );
    }
    public function GetLaboratorios(){
        $query=$this->db->order_by('nombre')->get("laboratorios"); 
        return $query->result_array();
    }
    public function GetLaboratorio($idLaboratorio){
        $query=$this->db->where('idLaboratorio',$idLaboratorio)->get("laboratorios"); 
        return $query->row_array();
    }
    public function CrearSolicitud($idCliente, $precioReal,$descuento,$precioFinal,$tipoPago, $cantidadTotal){
        $valores = ['idCliente'=>$idCliente, 'precioReal'=>"$precioReal", 'descuentoTotal'=>"$descuento", 'precioFinal'=>"$precioFinal",'fechaSolicitud'=> $this->getDateTimeNow(), 'tipoPago'=>$tipoPago, 'cantidadTotal'=>$cantidadTotal];
        $this->db->insert('solicitudes', $valores);
        $insertId = $this->db->insert_id();
        return  $insertId;
    }
    
    public function CrearProductoSolicitud($valores)
    {
        $this->db->insert('producto_solicitud', $valores);
    }
    public function GetProducto($idProducto){
        $query=$this->db->where('idProducto',$idProducto)->get("vwproductos"); 
        return $query->row_array();
    }
    public function GetHistorial($idCliente, $npagina, $tamPag){
        $nReg = $this->db->where('idCliente', $idCliente)->count_all_results("solicitudes");
        //$paginas = ceil($nReg/$tamPag);
        $offset = ($npagina-1)*$tamPag;

        $query=$this->db->limit( $tamPag,$offset)->where('idCliente',$idCliente)->get("vwhistorial"); 
        
        return Array(
            "res" => $query->result_array(),
            "paginas" => $nReg,
            "npagina" => $npagina,
            "tamPag" => $tamPag
        );
    }
    public function GetHistorialTotal($npagina, $tamPag){
        $nReg = $this->db->count_all_results("solicitudes");
        $offset = ($npagina-1)*$tamPag;
        $query=$this->db->limit( $tamPag,$offset)->get("vwhistorial");         
        return Array(
            "res" => $query->result_array(),
            "paginas" => $nReg,
            "npagina" => $npagina,
            "tamPag" => $tamPag
        );
    }
    public function GetPedidos($idCliente, $idSolicitud){
        $query=$this->db->where(['idCliente'=>$idCliente, 'idSolicitud'=>$idSolicitud])->get("vwpedido"); 
        return $query->result_array();
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/La_Paz');
        //date_default_timezone_set('Brazil/East');
    
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d H:i:s');
    }
    public function GetCuenta($id, $isAdmin){
        $query="";
        if($isAdmin){
            $query=$this->db->select('nombre, usuario, email')->where('idUsuario',$id)->get("usuarios"); 
        }else{
            $query=$this->db->select('cliente, usuario, email')->where('idCliente',$id)->get("clientes");  
        }
        
        return $query->row_array();
    }
    public function GuardarCuenta($id, $isAdmin, $datos){
        $query="";
        $password=trim($datos['password']);
        if( $password!=""){
            $password = hash('sha256', $password);
        }
        if($isAdmin){
            if($password!=""){
                $valores=['usuario'=>$datos["usuario"],'email'=>$datos['email'],'password'=>$password];
            }else{
                $valores=['usuario'=>$datos["usuario"],'email'=>$datos['email']];
            }            
            $this->db->set($valores)->where('idUsuario', $id)->update('usuarios');
        }else{
            if($password!=""){
                $valores=['usuario'=>$datos["usuario"],'email'=>$datos['email'],'password'=>$password];
            }else{
                $valores=['usuario'=>$datos["usuario"],'email'=>$datos['email']];
            }            
            $this->db->set($valores)->where('idCliente', $id)->update('clientes');
        }
        
        return 1;
    }
    public function GetAdminEmails(){
        $query=$this->db->get("vwccemails"); 
        $res=$query->row_array(); 
        return $res["emails"];
    }
}