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
		$columna2 = "Usuario";
		$columna3 = "Tiempo de actividad";
		$columna4 = "Dirección IP";
		$columna5 = "Dirección MAC";
		$columna6 = "Bytes recibidos";
		$columna7 = "Bytes enviados";
		$columna8 = "-";

		$data['columns_usuarios_activos'] = array($columna1, $columna2, $columna3, $columna4, $columna5, $columna6, $columna7, $columna8);
		$data_usuarios_activos = $this->MKTModel->MostrarRecargarDatosUsuariosActivos();

		$data['data_usuarios_activos'] = $data_usuarios_activos[0];
		$data['conexionMKT'] = $data_usuarios_activos[1];		

		$columna1 = ".id";
		$columna2 = "name";
		$columna3 = "uptime";
		$columna4 = "bytes-in";
		$columna5 = "bytes-out";

		$data['columns_ultimas_conexiones'] = array($columna1, $columna2, $columna3, $columna4, $columna5);
		$data_ultimas_conexiones = $this->MKTModel->MostrarRecargarUltimasConexiones();


		$data['data_ultimas_conexiones'] = $data_ultimas_conexiones[0];
		$data['conexionMKT'] = $data_ultimas_conexiones[1];

		$this->load->view('plantillas/header');
		$this->load->view('dashboard/show', $data);
		$this->load->view('plantillas/footer');
	}

	public function ExpulsarUsuario()
	{

		$conexionMKT = true;
		$datos = $this->input->post('datos');

		$data = $this->MKTModel->expulsarUsuario($datos['id']);
		$conexionMKT = $data[1];

		$data = $this->MKTModel->MostrarRecargarDatosUsuariosActivos();
		$conexionMKT = $data[1];

		echo json_encode(array($conexionMKT, $data[0]));
	}

	public function Refrescar()
	{

		$conexionMKT = true;

		$data = $this->MKTModel->MostrarRecargarDatosUsuariosActivos();
		$conexionMKT = $data[1];

		echo json_encode(array($conexionMKT, $data[0]));
	}

}
