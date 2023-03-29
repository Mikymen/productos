<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autorizar {

        public function Cliente()
        {
            if (session_id() == '' || session_status() == PHP_SESSION_NONE) {
                session_start();                
            };
            if(isset($_SESSION["idCliente"])){
                return true;
            };
            session_destroy();
            return false;
        }
        public function Usuario()
        {
            if (session_id() == '' || session_status() == PHP_SESSION_NONE) {
                session_start();                
            };
            if(isset($_SESSION["idAdmin"])){
                return true;
            };
            session_destroy();
            return false;
        }
        public function LogOutCliente(){
            if (session_id() == '' || session_status() == PHP_SESSION_NONE) {
                session_start();                
            };
            unset($_SESSION["idCliente"]);
            unset($_SESSION["cliente"]);
            unset($_SESSION["email"]);
            session_destroy();
        }
        public function LogOutAdmin(){
            if (session_id() == '' || session_status() == PHP_SESSION_NONE) {
                session_start();                
            };
            unset($_SESSION["idAdmin"]);
            unset($_SESSION["nombre"]);
            unset($_SESSION["email"]);
            session_destroy();
        }
}