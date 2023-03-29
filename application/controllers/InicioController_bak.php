<?php
class InicioController extends CI_Controller {

        public function __construct(){
                parent::__construct();
                $this->load->model("ListaModel");
                
                  
        }
        public function Index()
        {
                $this->load->library('autorizar');
                if(!$this->autorizar->Cliente()) redirect('/Auth'); 

                $data['nombre']=$_SESSION["cliente"];
                $this->load->view('Plantillas/_TemplateCabecera', $data);
                $this->load->view('Inicio/IndexView');
                $this->load->view('Plantillas/_TemplatePie'); 
        }
        public function Lista(){
                $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('foo' => 'bar')));

                $data["titulo"]="Bienvenidos!";
                $this->load->view('Plantillas/_TemplateCabecera', $data);
                $this->load->view('Inicio/IndexView');
                $this->load->view('Plantillas/_TemplatePie');
        }
        public function SetLista(){
                echo $this->input->post('seleccion');
        }
        //JSON METHODS
        public function GetLista(){
                $clave = $this->input->post('clave');
                $npagina = $this->input->post('npagina');
                $tamPag = intval($this->input->post('tamPag'));
                $idLabo = intval($this->input->post('idLabo'));

                //$offset = ($npagina-1)*$tamPag;
                
                if($clave=="Miguel"){
                        $arr = $this->ListaModel->GetLista($idLabo, $npagina, $tamPag);
                        header("Content-Type: application/json");
                        echo json_encode($arr);
                }
        }
        public function GetLaboratorios(){
                $clave = $this->input->post('clave');                
                if($clave=="Miguel"){
                        $arr = $this->ListaModel->GetLaboratorios();
                        header("Content-Type: application/json");
                        echo json_encode($arr);
                }   
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
                if($clave=="Miguel"){
                        $arr = $this->ListaModel->GetPedidos($idCliente, $idSolicitud);
                        header("Content-Type: application/json");
                        echo json_encode($arr);
                }   
        }
        public function GuardarPedidos(){   
                // $this->load->library('autorizar');
                // if(!$this->autorizar->Cliente()) return false;     
                session_start();     
                //prodsol
                //$this->load->library('session');
                $respuesta="Se ha guardado los productos";
                //Codigos:  1=Se guardo correc, 2=Se guardo pero no se envio email, 3= ocurrio un error al guardar 
                $codigo=1;
                $clave = $this->input->post('clave');
                $solicitudes = $this->input->post('solicitudes');
                $tipoPago = intval($this->input->post('tipoPago'));
                //Guardar solicitudes
                if(sizeof($solicitudes)>0){
                        //solciitud
                        //$idCliente=$this->session->idCliente;
                        $idCliente=$_SESSION["idCliente"];
                        $precioReal=0;
                        $descuentoTotal=0;
                        $precioTotalFinal=0;
                        $precioTotalReal=0;
                        $precioFinal=0;
                        $valores=array();
                        $cantidadTotal=0;
                        $idLaboratorio=0;
                        

                        $tablaHtml="<table  style='background: #e8e8e8;'><tr><td>Codigo</td><td>Producto</td><td>Cantidad</td><td>Total  (Bs.)</td></tr>";

                        foreach ($solicitudes as $key => $value) {
                                
                                $producto=$this->ListaModel->GetProducto($value["idProducto"]);
                                $idLaboratorio=$producto["idLaboratorio"];
                                $_cantidad=$value["cantidad"];
                                $_precioTotalReal = $producto["precioReal"] * $_cantidad;
                                $_descuentoTotal = $_precioTotalReal - ($producto["precioFinal"] * $_cantidad );
                                $_precioTotalFinal = $producto["precioFinal"] * $_cantidad;
                                //nueva solicitud
                                $precioTotalReal += $_precioTotalReal;
                                $descuentoTotal += $_descuentoTotal;
                                $precioTotalFinal += $_precioTotalFinal;
                                $cantidadTotal+=$value["cantidad"];
                                //nuevas prod_sol
                                $productoArr=[
                                        'idSolicitud' => 0,
                                        'idProducto'=>$value["idProducto"],
                                        'cantidad'=>$value["cantidad"], 
                                        'precioUnitario'=>$producto["precioReal"], 
                                        'precioTotalReal'=> $_precioTotalReal, 
                                        'descuento'=> $_descuentoTotal, 
                                        'precioTotalFinal'=>$_precioTotalFinal
                                ];
                                array_push($valores,$productoArr);

                                $tablaHtml.='<tr>';
                                $tablaHtml.='<td>'.$producto["codigo"].'</td>';
                                $tablaHtml.='<td>'.$producto["producto"].'</td>';
                                $tablaHtml.='<td>'.$value["cantidad"].'</td>';
                                $tablaHtml.='<td>'.$_precioTotalFinal.'</td>';
                                $tablaHtml.='</tr>';
                        } 
                        $tablaHtml.='<tr><td colspan="3">Total:</td><td>'.$precioTotalFinal.'</td></tr>';
                        $tablaHtml.="</table>";

                        $idSolicitud = $this->ListaModel->CrearSolicitud($idCliente, $precioTotalReal,$descuentoTotal,$precioTotalFinal,$tipoPago,$cantidadTotal);
                        
                        foreach ($valores as $key => $value) {
                                $value["idSolicitud"] = $idSolicitud;
                                $this->ListaModel->CrearProductoSolicitud($value);
                                
                        };
                        
                        //enviar email
                        $laboratorio = $this->ListaModel->GetLaboratorio($idLaboratorio);
                        $cc="";
                        if($_SESSION["email"] !=""){
                                $cc= $_SESSION["email"] + ',';
                        }
                        $cc = $this->ListaModel->GetAdminEmails();
                        $mail = (new self)->EnviarMail($_SESSION["cliente"], $tablaHtml, $laboratorio, $cc);
                        if(!$mail){
                                $respuesta="Fallo al enviar el correo";
                                $codigo=2;
                        }
                }else{
                        $respuesta="Ocurrio un error al guardar los datos";
                        $codigo=3;
                }
                if($clave=="Miguel"){
                        $arr=['Respuesta'=> $respuesta,'Codigo'=>$codigo];
                        header("Content-Type: application/json");
                        echo json_encode($arr);
                }
        }
        function EnviarMail( $cliente, $tablaHtml,$laboratorio, $cc){
                $res=false;
                $to = $laboratorio["email"];
                $subject = "Nueva solicitud de productos";

                $formato = "
                <html>
                <head>
                <title>Nueva solicitud</title>
                </head>
                <body>
                <h2>Nuevo pedido de farmacia!</h2>
                <hr />
                <h5>Laboratorio: %s </h5>
                <h4>Cliente: %s </h4>
                <h4>Fecha: %s </h4>
                <h4>Detalle: </h4>
                        %s
                <p style='text-align:right; font-size:9pt;'>
                SISTEMA DE SOLICITUD DE PRODUCTOS - 2018
                </p>
                </html>
                ";
                $message= sprintf($formato, $laboratorio["nombre"], $cliente, date('d-m-Y') , $tablaHtml);

                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers               

                $headers .= 'From: ceciliapacheco@markdisbolivia.com' . "\r\n";
                $headers .= 'Cc: '. $cc . "\r\n";

                ;
                if(@mail($to,$subject,$message,$headers)){
                        $res=true;
                }
                return $res;
        }
        public function GetHistorial(){
                session_start();
                $clave = $this->input->post('clave');      
                $npagina = $this->input->post('npagina');
                $tamPag = intval($this->input->post('tamPag'));  
                $id="";                          

                if($clave=="Miguel"){
                        if(isset($_SESSION["idAdmin"])){
                                $arr = $this->ListaModel->GetHistorialTotal($npagina, $tamPag);
                        }else{
                                $id = $_SESSION["idCliente"];
                                $arr = $this->ListaModel->GetHistorial($id, $npagina, $tamPag);
                        }                           
                        header("Content-Type: application/json");
                        echo json_encode($arr);
                } 
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
                if($clave=="Miguel"){
                        $arr = $this->ListaModel->GetCuenta($id,$isAdmin);
                        header("Content-Type: application/json");
                        echo json_encode($arr);
                }   
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
                if($clave=="Miguel"){
                        $res = $this->ListaModel->GuardarCuenta($id,$isAdmin,$datos);
                        //header("Content-Type: application/json");
                        echo json_encode($res);
                }   
        }
}