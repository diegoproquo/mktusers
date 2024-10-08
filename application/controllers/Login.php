<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('url');

    }

    public function index()
    {
        $this->load->view('plantillas/login/header');
        $this->load->view('login/show');
        $this->load->view('plantillas/login/footer');
    }


    public function iniciarSesion()
    {
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('UsuariosWebModel');
        $usuario = $this->input->post('user');
        $contrasena = $this->input->post('pass');


        // Buscar el usuario en la base de datos
        $usuario_db = $this->UsuariosWebModel->obtenerUsuarioPorNombre($usuario);


        if ($usuario_db) {
            if (password_verify($contrasena, $usuario_db->PASSWORD)) {
                $user = array(
                    'id'  => $usuario_db->ID,
                    'usuario'  => $usuario,
                    'adm'  => $usuario_db->ROL,
                    'logged_in' => true
                );
                $this->UsuariosWebModel->actualizarLastLogin($usuario_db->ID);
                $this->session->set_userdata($user);
                redirect(base_url()."Dashboard");
                return;
            } else {
                $this->session->set_flashdata('error', 'Contraseña incorrecta');
                redirect(base_url()."Login");
                return;
            }
        } else {
            $this->session->set_flashdata('error', 'Usuario no encontrado');
            redirect(base_url()."Login");
            return;
        }

        $this->session->set_flashdata('error', 'Algo no salió como se esperaba');
        redirect(base_url()."Login");
        return;

    }

    public function logout()
    {
        session_destroy();
        redirect(base_url() . "Login");
        return;
    }
}
