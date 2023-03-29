<?php
class InicioController extends CI_Controller {

        public function __construct(){
                parent::__construct();
                $this->load->model("ListaModel");
        }
        public function Index()
        {
                $lista=array(
                        array("id"=>1,"val"=>"Miguel"),
                        array("id"=>2,"val"=>"Xime"),
                        array("id"=>3,"val"=>"Pablo"));
                $data["titulo"]="Bienvenidos!";
                $data["lista"]=json_encode($lista);
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
                if($clave=="Miguel"){
                        $arr = $this->ListaModel->GetLista();
                        header("Content-Type: application/json");
                        echo json_encode($arr);
                }
        }
}