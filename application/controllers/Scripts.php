<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Scripts extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->model('MKTModel');
        $this->load->model('ScriptsModel');

        if (!$this->session->userdata('logged_in')) {
            redirect(base_url() . "Login");
            return;
        }
    }

    public function show()
    {

        $data['scripts'] = $this->ScriptsModel->getScripts();

        $this->load->view('plantillas/header');
        $this->load->view('scripts/show', $data);
        $this->load->view('plantillas/footer');
    }

    public function GuardarEditar()
    {
		$conexionMKT = true;
		$mensajeError = "";

        $datos = $this->input->post('datos');

        $this->ScriptsModel->guardarScript($datos['id'], $datos['script']);
        $scripts = $this->ScriptsModel->getScripts();

        $data = $this->MKTModel->MostrarRecargarDatosPerfiles();
        $perfiles = $data[0];
        $conexionMKT = $data[1];

        $this->MKTModel->sincronizarScripts($scripts, $perfiles);


		echo json_encode(array($conexionMKT, $mensajeError));
    }
}
