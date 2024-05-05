<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->model('MKTModel');
	}

	public function show()
	{

		$columna1 = ".id";
		$columna2 = "name";
		$columna3 = "uptime";
		$columna4 = "bytes-in";
		$columna5 = "bytes-out";
		$columna6 = "-";

		$data['columns'] = array($columna1, $columna2, $columna3, $columna4, $columna5, $columna6);
		
		$data['data'] = $this->MKTModel->MostrarRecargarDatosUsuarios();

		$this->load->view('plantillas/header');
		$this->load->view('usuarios/show', $data);
		$this->load->view('plantillas/footer');
	}


	public function GuardarEditar()
	{
		$datos = $this->input->post('datos');

		$this->MKTModel->addHotspotUser($datos['usuario'], $datos['password'], 'default');

		$data = $this->MKTModel->MostrarRecargarDatosUsuarios();

		echo json_encode(array(true, $data));
	}




}
