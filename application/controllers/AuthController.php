<?php
class AuthController extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("AuthModel");
    }
    public function Index()
    {           
        $this->load->view('Plantillas/_TemplateCabecera');
        $this->load->view('Login/Index');
        $this->load->view('Plantillas/_TemplatePie'); 
    }
    public function AdminLogin()
    {           
        $this->load->view('Plantillas/_TemplateCabecera');
        $this->load->view('Login/Admin');
        $this->load->view('Plantillas/_TemplatePie'); 
    }
    public function LoginCliente(){
        $username=$this->input->post('username');
        $password=hash('sha256', $this->input->post('password'));
        $cliente = $this->AuthModel->GetCliente($username,$password);
        if($cliente!=null){
            session_start();
            $_SESSION["idCliente"]=$cliente["idCliente"];
            $_SESSION["cliente"]=$cliente["cliente"];
            $_SESSION["email"]=$cliente["email"];

            redirect('/inicio#/laboratorios');
        }
        redirect('/Auth');
    }
    public function LogOutCliente(){
        $this->load->library('autorizar');
        $this->autorizar->LogOutCliente();
        redirect('/Auth');
    }
    public function LoginAdmin(){
        $username=$this->input->post('username');
        $password=hash('sha256', $this->input->post('password'));
        $admin = $this->AuthModel->GetAdmin($username,$password);
        if($admin!=null){
            session_start();
            $_SESSION["idAdmin"]=$admin["idUsuario"];
            $_SESSION["nombre"]=$admin["nombre"];
            $_SESSION["email"]=$admin["email"];

            redirect('/InicioAdmin');
        }
        redirect('/admin');
    }
    public function LogOutAdmin(){
        $this->load->library('autorizar');
        $this->autorizar->LogOutAdmin();
        redirect('/admin');
    }
}