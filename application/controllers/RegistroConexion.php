<?php



defined('BASEPATH') OR exit('No direct script access allowed');

class RegistroConexion extends CI_Controller {

    public function index()
    {
        // Capturar parámetros
        $usuario = $this->input->get('usuario');

        // Cargar el modelo y guardar los datos
        $this->load->model('UsuariosMktModel');
        $this->UsuariosMktModel->insertarConexion($usuario);

        $this->load->model('ConexionesModel');
        $this->ConexionesModel->registrarConexionDiaria();
    }
}
