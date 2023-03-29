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
                //header("Content-Type: application/json");
                $descuentoEfectivo = $this->ListaModel->GetDescuentoEfectivo();
                $descuentoMonto = $this->ListaModel->GetDescuentoDesdeMonto();
                $data['laboratorios']= json_encode($this->ListaModel->GetLaboratorios($_SESSION["idCliente"]));
                

                $data['descuentoEfectivo']=($descuentoEfectivo['valor']);
                $data['descuentoMonto']=($descuentoMonto['valor']);
                $data['descuentoMontoBs']=($descuentoMonto['desdeMonto']);

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
        // private function ListaLaboratorios(){
        //         $arr = $this->ListaModel->GetLaboratorios($_SESSION["idCliente"]);
        //         return json_encode($arr);
        // }
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

                $metodoPago = intval($this->input->post('metodoPago'));
                //Guardar solicitudes
                if(sizeof($solicitudes)>0){
                        //solciitud
                        //$idCliente=$this->session->idCliente;
                        $idCliente=$_SESSION["idCliente"];
                        $precioReal=0;
                        $descuentoTotal=0;
                        $precioDetalle=0;
                        $precioTotalReal=0;
                        $precioFinal=0;
                        $valores=array();
                        $cantidadTotal=0;
                        $idLaboratorio=0;
                        

                        $tablaHtml="<html><body><table  style='background: #e8e8e8;'><tr style='font-weight:bold;'> <td> Codigo </td><td> Producto </td><td> Cantidad </td><td> Total (Bs.) </td></tr>";

                        foreach ($solicitudes as $key => $value) {
                                
                                $producto=$this->ListaModel->GetProducto($value["idProducto"]);
                                $idLaboratorio=$producto["idLaboratorio"];
                                $_cantidad=$value["cantidad"];
                                $_precioTotalReal = $producto["precioReal"] * $_cantidad;
                                $_descuentoTotal = $_precioTotalReal - ($producto["precioFinal"] * $_cantidad );
                                $_precioDetalle = $producto["precioFinal"] * $_cantidad;
                                //nueva solicitud
                                $precioTotalReal += $_precioTotalReal;
                                $descuentoTotal += $_descuentoTotal;
                                $precioDetalle += $_precioDetalle;
                                $cantidadTotal+=$value["cantidad"];
                                
                                //nuevas prod_sol
                                $productoArr=[
                                        'idSolicitud' => 0,
                                        'idProducto'=>$value["idProducto"],
                                        'cantidad'=>$value["cantidad"], 
                                        'precioUnitario'=>$producto["precioReal"], 
                                        'precioTotalReal'=> strval($_precioTotalReal), 
                                        'descuento'=> strval($_descuentoTotal), 
                                        'precioTotalFinal'=>strval($_precioDetalle)
                                ];
                                array_push($valores,$productoArr);

                                $tablaHtml.='<tr>';
                                $tablaHtml.='<td>'.$producto["codigo"].'</td>';
                                $tablaHtml.='<td>'.$producto["producto"].'</td>';
                                $tablaHtml.='<td>'.$value["cantidad"].'</td>';
                                $tablaHtml.='<td>'.$_precioDetalle.'</td>';
                                $tablaHtml.='</tr>';
                        } 
                        

                        //$precioTotalFinal=$precioDetalle - ($descuentoTotal/100)*$precioDetalle;                      

                        $descuentoEfectivo = $this->ListaModel->GetDescuentoEfectivo()['valor'];
                        $descuentoDesdeMonto = $this->ListaModel->GetDescuentoDesdeMonto();
                        $descuentoMonto=$descuentoDesdeMonto['valor'];
                        $descuentoMontoBs=$descuentoDesdeMonto['desdeMonto'];

                        $descuentoMontoValor = $precioDetalle >= $descuentoMontoBs? ($precioDetalle*($descuentoMonto/100)) : 0;
                        $descuentoEfectivoValor= $metodoPago=="efectivo"? ($precioDetalle*($descuentoEfectivo/100)) : 0;
                        
                        $descuentoGeneral= round($descuentoMontoValor + $descuentoEfectivoValor,2);//round

                        $precioTotalFinal = round($precioDetalle - $descuentoGeneral,2);//round
                        $descuentoGeneral= round($descuentoGeneral,2);

                        $idSolicitud = $this->ListaModel->CrearSolicitud($idCliente, $precioTotalReal,$descuentoTotal,$precioTotalFinal,$tipoPago,$cantidadTotal,$descuentoGeneral,$precioDetalle);

                        $tablaHtml.=sprintf("<tr><td colspan='3'>Descuento del %s%% por pago al contado</td><td> -%s</td></tr>",$descuentoEfectivo, round($descuentoEfectivoValor,2));
                        $tablaHtml.=sprintf("<tr><td colspan='3'>Descuento del %s%% por monto de compra igual o mayor a %s Bs.</td><td> -%s</td></tr>",$descuentoMonto,$descuentoMontoBs, round($descuentoMontoValor));
                        $tablaHtml.="<tr><td colspan='3'>Total:</td><td>".$precioTotalFinal."</td></tr>";
                        $tablaHtml.="</table></body></html>";
                        
                        foreach ($valores as $key => $value) {
                                $value["idSolicitud"] = $idSolicitud;
                                $this->ListaModel->CrearProductoSolicitud($value);
                                
                        };
                        
                        //enviar email
                        // $laboratorio = $this->ListaModel->GetLaboratorio($idLaboratorio);
                        // $cc="";
                        // if($_SESSION["email"] !=""){
                        //         $cc= $_SESSION["email"] . ',';
                        // }
                        // $cc = $this->ListaModel->GetAdminEmails();
                        // $mail = (new self)->EnviarMail($_SESSION["cliente"], $tablaHtml, $laboratorio, $cc);
                        // if(!$mail){
                        //         $respuesta="Fallo al enviar el correo";
                        //         $codigo=2;
                        // }
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
                SISTEMA DE SOLICITUD DE PRODUCTOS - ".date("Y")."
                </p>
                </html>
                ";
                $message= sprintf($formato, $laboratorio["nombre"], $cliente, date('d-m-Y') , $tablaHtml);
                
                $this->load->library('email');
                $this->email->from('pedidos@markdistechnology.com', 'Markdistechnology');
                $this->email->to($to);
                //$this->email->cc("bebelinapac@gmail.com, ceciliapacheco@markdisbolivia.com, m3n740b5cur4@gmail.com");
                $this->email->cc($cc);
                $this->email->subject($subject);
                $this->email->message($message);

                $res = $this->email->send();

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