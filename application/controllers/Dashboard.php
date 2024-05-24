<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->model('MKTModel');
	}

	public function index()
	{

		$columna1 = ".id";
		$columna2 = "user";
		$columna3 = "uptime";
		$columna4 = "address";
		$columna5 = "mac-address";
		$columna6 = "-";

		$data['columns_usuarios_activos'] = array($columna1, $columna2, $columna3, $columna4, $columna5, $columna6);
		$data['data_usuarios_activos'] = $this->MKTModel->MostrarRecargarDatosUsuariosActivos();

		
		$columna1 = ".id";
		$columna2 = "name";
		$columna3 = "uptime";
		$columna4 = "bytes-in";
		$columna5 = "bytes-out";

		$data['columns_ultimas_conexiones'] = array($columna1, $columna2, $columna3, $columna4, $columna5);
		$data['data_ultimas_conexiones'] = $this->MKTModel->MostrarRecargarUltimasConexiones();
		
		$this->load->view('plantillas/header');
		$this->load->view('dashboard/show', $data);
		$this->load->view('plantillas/footer');
	}

	public function ExpulsarUsuario(){
		$datos = $this->input->post('datos');
		$this->MKTModel->expulsarUsuario($datos['id']);

		$data = $this->MKTModel->MostrarRecargarDatosUsuariosActivos();
		echo json_encode(array(true, $data));
	}


}
