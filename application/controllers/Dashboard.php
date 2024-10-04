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
		$this->load->model('ConexionesModel');
		$this->load->model('TraficoModel');

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

		$fecha_actual = date('Y-m-d');
		$data['fecha_actual'] = $fecha_actual;

		$datosConexiones7dias = $this->Conexiones7Dias($fecha_actual);
		$data['dataConexiones7Dias'] = $datosConexiones7dias[0];
		$data['labelsConexiones7Dias'] = $datosConexiones7dias[1];

		$datosTrafico7dias = $this->Trafico7Dias($fecha_actual);
		$data['datatraficoDescarga7Dias'] = $datosTrafico7dias[0];
		$data['datatraficoCarga7Dias'] = $datosTrafico7dias[1];
		$data['labelsTrafico7Dias'] = $datosTrafico7dias[2];


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

	public function Conexiones7Dias($fecha){
		$data = $this->ConexionesModel->getConexiones7Dias($fecha);

		$labels = [];
	
		for ($i = 0; $i < 8; $i++) {
			$labels[] = date('d/m', strtotime("-$i day", strtotime($fecha)));
		}

		$labels = array_reverse($labels);

		return array($data, $labels);
	}

	public function Trafico7Dias($fecha){
		$data = $this->TraficoModel->getTrafico7dias($fecha);
		$decarga = $data[0];
		$carga = $data[1];

		$labels = [];
	
		for ($i = 0; $i < 8; $i++) {
			$labels[] = date('d/m', strtotime("-$i day", strtotime($fecha)));
		}

		$labels = array_reverse($labels);

		return array($decarga, $carga, $labels);
	}

	public function ActualizarGraficoBarras(){
		$datos = $this->input->post('datos');

		$fecha = $datos["fechaConexiones"];
		$accion = $datos["accion"];

		$nueva_fecha = date("Y-m-d", strtotime($fecha . " " . $accion));
		$conexiones = $this->Conexiones7Dias($nueva_fecha);

		$html_grafico = actualizarGraficoBarras($conexiones[0], $conexiones[1], "graficoConexiones", "Conexiones semanales");


		echo json_encode(array(true, $nueva_fecha, $html_grafico));

	}
	
	public function ActualizarGraficoFuncion(){
		$datos = $this->input->post('datos');

		$fecha = $datos["fechaTrafico"];
		$accion = $datos["accion"];

		$nueva_fecha = date("Y-m-d", strtotime($fecha . " " . $accion));
		$trafico = $this->Trafico7Dias($nueva_fecha);

		$html_grafico = actualizarGraficoFuncionDoble($trafico[0], $trafico[1], $trafico[2], "graficoTrafico", "Trafico semanal acumulado (MB)");
		
		echo json_encode(array(true, $nueva_fecha, $html_grafico));

	}
}
