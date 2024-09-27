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
		$this->load->model('ConexionesDiariasModel');
		$this->load->model('TraficoDiarioModel');

		if (!$this->session->userdata('logged_in')) {
			redirect(base_url() . "Login");
			return;
		}
	}

	public function index()
	{


		$columna1 = ".id";
		$columna2 = "Usuario";
		$columna3 = "Tiempo de actividad";
		$columna4 = "Dirección IP";
		$columna5 = "Dirección MAC";
		$columna6 = "Tráfico descarga";
		$columna7 = "Tráfico subida";
		$columna8 = "-";

		$data['columns_usuarios_activos'] = array($columna1, $columna2, $columna3, $columna4, $columna5, $columna6, $columna7, $columna8);
		$data_usuarios_activos = $this->MKTModel->MostrarRecargarDatosUsuariosActivos();

		$data['data_usuarios_activos'] = $data_usuarios_activos[0];
		$data['conexionMKT'] = $data_usuarios_activos[1];


		$datosConexiones7dias = $this->Conexiones7Dias();
		$data['dataConexiones7Dias'] = $datosConexiones7dias[0];
		$data['labelsConexiones7Dias'] = $datosConexiones7dias[1];

		$datosTrafico7dias = $this->Trafico7Dias();
		$data['datatraficoDescarga7Dias'] = $datosTrafico7dias[0];
		$data['datatraficoCarga7Dias'] = $datosTrafico7dias[1];
		$data['labels7Dias'] = $datosTrafico7dias[2];


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

	public function Conexiones7Dias(){
		$data = $this->ConexionesDiariasModel->getConexiones7Dias();

		$labels = [];
		$fecha_actual = date('Y-m-d');
	
		for ($i = 0; $i < 8; $i++) {
			$labels[] = date('d/m', strtotime("-$i day", strtotime($fecha_actual)));
		}

		$labels = array_reverse($labels);

		return array($data, $labels);
	}

	public function Trafico7Dias(){
		$data = $this->TraficoDiarioModel->getTrafico7dias();
		$decarga = $data[0];
		$carga = $data[1];

		$labels = [];
		$fecha_actual = date('Y-m-d');
	
		for ($i = 0; $i < 8; $i++) {
			$labels[] = date('d/m', strtotime("-$i day", strtotime($fecha_actual)));
		}

		$labels = array_reverse($labels);

		return array($decarga, $carga, $labels);
	}
}
