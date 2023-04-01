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
        $query= $this->db->where('idLaboratorio', $idLabo)->order_by('producto')->get("vwproductos");      
        
        return Array(
            "res" => $query->result_array(),
            "paginas" => $nReg,
            "npagina" => $npagina,
            "tamPag" => $tamPag
        );
    }
    public function GetLaboratorios($idCliente){
        // $query=$this->db->get("vwlaboratorios_activos"); 
        // return $query->result_array();
        $sql = sprintf("call sp_select_regiones (%s)",$idCliente);
        $query= $this->db->query($sql);  
        return $query->result_array();
    }
    public function GetLaboratoriosLista(){
        $query=$this->db->get("vwlaboratorios"); 
        return $query->result_array();
        
    }
    public function GetListaLaboratorios(){
        $query=$this->db->get("vwlaboratorio_region"); 
        return $query->result_array();
        
    }
    public function GetLaboratorio($idLaboratorio){
        $query=$this->db->where('idLaboratorio',$idLaboratorio)->get("vwlaboratorios"); 
        return $query->row_array();
    }
    public function CrearSolicitud($idCliente, $precioReal,$descuento,$precioFinal,$tipoPago, $cantidadTotal,$descuentoGeneral,$precioDetalle){
        $valores = ['idCliente'=>$idCliente, 'precioReal'=>"$precioReal", 'descuentoTotal'=>"$descuento", 'precioFinal'=>"$precioFinal",'fechaSolicitud'=> $this->getDateTimeNow(), 'tipoPago'=>$tipoPago, 'cantidadTotal'=>$cantidadTotal,'descuentoGeneral'=>$descuentoGeneral,'precioDetalle'=>$precioDetalle,];
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
    // public function GetClientes(){
    //     $query=$this->db->get("vwclientes"); 
    //     return $query->result_array(); 
    // }
    public function GetListaClientes($idLabo, $npagina, $tamPag){
        
        $nReg = $this->db->where('estado', 1)->count_all_results("vwclientes");
        //$paginas = ceil($nReg/$tamPag);
        $offset = ($npagina-1)*$tamPag;
        $query= $this->db->limit( $tamPag,$offset)->where('estado', 1)->order_by('cliente')->get("vwclientes");      
        
        return Array(
            "res" => $query->result_array(),
            "paginas" => $nReg,
            "npagina" => $npagina,
            "tamPag" => $tamPag
        );
    }
    public function GuardarNuevoCliente($nombre, $usuario, $email,$password,$idRegion,$codigo){
        try {
            $consulta=sprintf("call sp_insertar_cliente('%s','%s','%s','%s','%s',%s,%s)",$nombre, $usuario, $email,$password,$codigo,$idRegion,0);
            $query=$this->db->query($consulta);  
            //$this->db->trans_complete();
            //$result=$query->row_array();
            

            $db_error = $this->db->error();
            if (!empty($db_error) && $db_error['code']!='0') {
                
                if($db_error['code']=='1062'){
                    throw new Exception('Ya se encuentra registrado el cliente en la región seleccionada o el usuario ya existe');                    
                }else{
                    //throw new Exception('Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
                    throw new Exception('Ocurrio un error en la base de datos');
                }
                //return ["Respuesta"=>0,"Detalle:"=>"Ocurrio un error en la base de datos"];
            }
            $query->free_result();
            return  ["Respuesta"=> 1,"Detalle"=>"OK"];
        } catch (Exception $e) {
            return  ["Respuesta"=> 0,"Detalle"=>$e->getMessage()];
        }
        
    }
    public function GuardarCliente($idCliente, $nombre, $usuario, $email,$password,$idRegion,$codigo){
        try {
            $consulta=sprintf("call sp_actualizar_cliente(%s,'%s','%s','%s','%s','%s',%s,%s)",$idCliente, $nombre, $usuario, $email,$password,$codigo,$idRegion,0);
            $query=$this->db->query($consulta);  
            // $query->free_result();

            $db_error = $this->db->error();
            if (!empty($db_error) && $db_error['code']!='0') {
                
                if($db_error['code']=='1062'){
                    throw new Exception('Ya se encuentra registrado el cliente en la región seleccionada o el usuario ya existe');                    
                }else{
                    throw new Exception('No se guardo el registro, ocurrio un error en la base de datos');
                }
            }
            $query->free_result();
            return  ["Respuesta"=> 1,"Detalle"=>"OK"];
        } catch (Exception $e) {
            return  ["Respuesta"=> 0,"Detalle"=>$e->getMessage()];
        }
        
    }
    public function EliminarCliente($idCliente){
        $consulta=sprintf("call sp_eliminar_cliente(%s)",$idCliente);
        $query=$this->db->query($consulta);  
        $query->free_result();
        return  1;
    }
    public function GetListaProductos($idLaboratorio){
        $sql = sprintf("call sp_lista_productos (%s)",$idLaboratorio);
        $query= $this->db->query($sql);  
        return $query->result_array();
    }
    public function GuardarNuevoProducto($idLaboratorio, $codigo, $producto, $forma, $concentracion, $presentacion1, $presentacion2, $precioReal, $descuento,$precioFinal, $codigoProductoLaboratorio){
        $consulta=sprintf("call sp_insertar_producto(%s,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",$idLaboratorio, $codigo, $producto, $forma, $concentracion, $presentacion1, $presentacion2, $precioReal, $descuento,$precioFinal, $codigoProductoLaboratorio);
        $query=$this->db->query($consulta);  
        $result=$query->row_array();
        $query->free_result();
        return  $result["idProducto"];
    }
    public function ActualizarProducto($idProducto, $codigo, $producto, $forma, $concentracion, $presentacion1, $presentacion2, $precioReal, $descuento,$precioFinal, $codigoProductoLaboratorio){
        $consulta=sprintf("call sp_actualizar_producto(%s,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",$idProducto, $codigo, $producto, $forma, $concentracion, $presentacion1, $presentacion2, $precioReal, $descuento,$precioFinal, $codigoProductoLaboratorio);
        $query=$this->db->query($consulta);  
        $query->free_result();
        return  true;
    }
    public function EliminarProducto($idProducto){
        $consulta=sprintf("call sp_eliminar_producto(%s)",$idProducto);
        $query=$this->db->query($consulta);  
        $query->free_result();
        return  1;
    }
    public function GuardarRegiones($regiones){
        //$consulta;
        foreach ($regiones as $i => $region) {
            $consulta=sprintf("call sp_actualizar_region(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)", $region[0], $region[1], $region[2], $region[3], $region[4], $region[5], $region[6], $region[7], $region[8], $region[9], $region[10], $region[11], $region[12]); 
            $query=$this->db->query($consulta);  
            $query->free_result();
        }
        return true;
        //$this->db->insert_batch('mytable', $data);
    }
    public function GetRegiones(){
        $sql ="select* from vwregiones";
        $query= $this->db->query($sql);  
        return $query->result_array();
    }
    public function GuardarDescuentoGeneral($descuentoEfectivo, $descuentoMonto, $descuentoMontoBs)
    {
        $consulta=sprintf("call sp_actualizar_descuento_efectivo(%s)", $descuentoEfectivo); 
        $query=$this->db->query($consulta);  
        $query->free_result();
        $consulta=sprintf("call sp_actualizar_descuento_desde_monto(%s,%s)", $descuentoMonto, $descuentoMontoBs); 
        $query=$this->db->query($consulta);  
        $query->free_result();
        return true;
    }
    public function GetDescuentoEfectivo(){
        $query= $this->db->get("vwdescuento_efectivo");     
        return $query->row_array();
    }
    public function GetDescuentoDesdeMonto(){
        $query= $this->db->get("vwdescuento_desde_monto");     
        return $query->row_array();
    }
}