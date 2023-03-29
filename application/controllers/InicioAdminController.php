<?php
class InicioAdminController extends CI_Controller {

    public function __construct(){
            parent::__construct();
            $this->load->model("ListaModel");
            
                
    }
    public function Index()
    {
        $this->load->library('autorizar');
        if(!$this->autorizar->Usuario()) redirect('/admin'); 

        $data['nombre']=$_SESSION["nombre"];
        $data['regiones']= json_encode($this->ListaModel->GetRegiones());
        $this->load->view('Plantillas/_AdminTemplateCabecera', $data);
        $this->load->view('Inicio/AdminIndexView');
        $this->load->view('Plantillas/_AdminTemplatePie'); 
    }
    public function GetListaClientes(){
        $clave = $this->input->post('clave');
        $npagina = $this->input->post('npagina');
        $tamPag = intval($this->input->post('tamPag'));
        $idLabo = intval($this->input->post('idLabo'));
        if($clave=="Miguel"){
            $arr = $this->ListaModel->GetListaClientes($idLabo, $npagina, $tamPag);
            header("Content-Type: application/json");
            echo json_encode($arr);
        }

        // $arr = $this->ListaModel->GetClientes();
        // header("Content-Type: application/json");
        // echo json_encode($arr);
    }
    public function GuardarCliente(){
        $res="";
        $nombre = trim($this->input->post('nombre'));   
        $usuario =trim( $this->input->post('usuario'));
        $email = trim($this->input->post('email'));
        $password = trim( $this->input->post('password'));
        $idCliente=$this->input->post('idCliente');
        $idRegion=$this->input->post('idRegion');
        $codigo=$this->input->post('codigo');
        $modo= $this->input->post('modo');
        if($modo=="nuevo"){
            $idCliente=$this->input->post('idCliente'); 
            $res = $this->ListaModel->GuardarNuevoCliente($nombre, $usuario,$email,$password,$idRegion,$codigo);  
        }else if($modo=="editar"){
            $res = $this->ListaModel->GuardarCliente($idCliente,$nombre, $usuario,$email,$password,$idRegion,$codigo);  
            
        }
        header("Content-Type: application/json");
                echo json_encode($res);
    }
    public function EliminarCliente(){
        $idCliente=$this->input->post('idCliente');
        $res = $this->ListaModel->EliminarCliente($idCliente);  
        header("Content-Type: application/json");
                echo json_encode($res);
    }
    public function GetHistorial(){
        session_start();
        $clave = $this->input->post('clave');      
        $npagina = $this->input->post('npagina');
        $tamPag = intval($this->input->post('tamPag'));  
        $id="";                          

        if(isset($_SESSION["idAdmin"])){
                $arr = $this->ListaModel->GetHistorialTotal($npagina, $tamPag);
        }else{
                $id = $_SESSION["idCliente"];
                $arr = $this->ListaModel->GetHistorial($id, $npagina, $tamPag);
        }                           
        header("Content-Type: application/json");
        echo json_encode($arr);
        
    }
    public function GetPedidos(){
        session_start();
        $clave = $this->input->post('clave');    
        $idCliente =""; 
        if(isset($_SESSION["idAdmin"])){
                $idCliente =$this->input->post('idCliente');  
        }else{
                $idCliente = $_SESSION["idCliente"];   
        }
        $idSolicitud = $this->input->post('idSolicitud');             
        
        $arr = $this->ListaModel->GetPedidos($idCliente, $idSolicitud);
        header("Content-Type: application/json");
        echo json_encode($arr);
          
    }
    public function GetCuenta(){
        session_start();
        $clave = $this->input->post('clave');    
        $id =""; 
        $isAdmin=0;
        if(isset($_SESSION["idAdmin"])){
                $id=$_SESSION["idAdmin"];
                $isAdmin =1;  
        }else{
                $id=$_SESSION["idCliente"];   
        }   
        $arr = $this->ListaModel->GetCuenta($id,$isAdmin);
        header("Content-Type: application/json");
        echo json_encode($arr);
           
    }
    public function GuardarCuenta(){
        session_start();
        $clave = $this->input->post('clave');    
        $datos = $this->input->post('datos'); 
        $id =""; 
        $isAdmin=0;
        if(isset($_SESSION["idAdmin"])){
                $id=$_SESSION["idAdmin"];
                $isAdmin =1;  
        }else{
                $id=$_SESSION["idCliente"];   
        }   
        $res = $this->ListaModel->GuardarCuenta($id,$isAdmin,$datos);
        //header("Content-Type: application/json");
        echo json_encode($res);
          
    }
    // public function GetLaboratorios(){
    //     $clave = $this->input->post('clave');                
    //     if($clave=="Miguel"){
    //             $arr = $this->ListaModel->GetLaboratorios();
    //             header("Content-Type: application/json");
    //             echo json_encode($arr);
    //     }   
    // }
    public function GetListaProductos(){
        $idLaboratorio = trim($this->input->post('idLaboratorio'));   
        $arr = $this->ListaModel->GetListaProductos($idLaboratorio);
            header("Content-Type: application/json");
            echo json_encode($arr);
    }
    public function GuardarProducto(){
        $res="";
        $modo= $this->input->post('modo');
        $idLaboratorio = trim($this->input->post('idLaboratorio'));   
        $codigo =trim( $this->input->post('codigo'));
        $producto = trim($this->input->post('producto'));
        $forma = trim( $this->input->post('forma'));
        $concentracion=trim($this->input->post('concentracion'));
        $presentacion1=trim($this->input->post('presentacion1'));
        $presentacion2=trim($this->input->post('presentacion2'));
        $precioReal=trim($this->input->post('precioReal'));
        $descuento=trim($this->input->post('descuento'));
        $precioFinal= sprintf('%0.2f',  $precioReal -($precioReal * ($descuento/100 )));
        $codigoProductoLaboratorio=trim($this->input->post('codigoProductoLaboratorio'));
        
        if($modo=="nuevo"){
            
            $res = $this->ListaModel->GuardarNuevoProducto($idLaboratorio, $codigo, $producto, $forma, $concentracion, $presentacion1, $presentacion2, $precioReal,$descuento,$precioFinal, $codigoProductoLaboratorio);  
        }else if($modo=="editar"){            
            $idProducto=$this->input->post('idProducto'); 
            $res=$idProducto;
            $bol = $this->ListaModel->ActualizarProducto($idProducto, $codigo, $producto, $forma, $concentracion, $presentacion1, $presentacion2, $precioReal, $descuento,$precioFinal,$codigoProductoLaboratorio);   
            if(!$bol) return false;
        }
        header("Content-Type: application/json");
                echo json_encode($res);
    }
    public function EliminarProducto(){
        $idProducto=$this->input->post('idProducto');
        $res = $this->ListaModel->EliminarProducto($idProducto);  
        header("Content-Type: application/json");
                echo json_encode($res);
    }
    public function GetLaboratoriosLista(){               
        
        $arr = $this->ListaModel->GetLaboratoriosLista();
        header("Content-Type: application/json");
        echo json_encode($arr);
        
    }
    public function GetListaLaboratorios(){
        $arr = $this->ListaModel->GetListaLaboratorios();
        header("Content-Type: application/json");
        echo json_encode($arr);
    }
    public function GuardarRegiones(){
        $regiones = $this->input->post('regiones');  
        $res = $this->ListaModel->GuardarRegiones($regiones);
        header("Content-Type: application/json");
        echo json_encode($res);
    }
    public function GetDescuentoEfectivo(){
        $arr = $this->ListaModel->GetDescuentoEfectivo();
        header("Content-Type: application/json");
        echo json_encode($arr);
    }
    public function GetDescuentoDesdeMonto(){
        $arr = $this->ListaModel->GetDescuentoDesdeMonto();
        header("Content-Type: application/json");
        echo json_encode($arr);
    }
    public function GuardarDescuentoGeneral(){
        $descuentoEfectivo = $this->input->post('descuentoEfectivo'); 
        $descuentoMonto = $this->input->post('descuentoMonto');
        $descuentoMontoBs = $this->input->post('descuentoMontoBs');
        $res = $this->ListaModel->GuardarDescuentoGeneral($descuentoEfectivo, $descuentoMonto, $descuentoMontoBs);
        header("Content-Type: application/json");
        echo json_encode($res);
    }
}