<?php



defined('BASEPATH') OR exit('No direct script access allowed');

class RegistroTrafico extends CI_Controller {

    public function index()
    {
        // Capturar parámetros
        $descarga = $this->input->get('descarga');
        $carga = $this->input->get('carga');

        $this->load->model('TraficoDiarioModel');
        $this->TraficoDiarioModel->registrarTraficoSesion($descarga, $carga);
    }
}
