<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perfiles extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->model('MKTModel');

		if (!$this->session->userdata('logged_in')) {
			redirect(base_url() . "Login");
			return;
		}

	}

	public function show()
	{
		$columna0 = "-";
		$columna1 = ".id";
		$columna2 = "Nombre";
		$columna3 = "idle-timeout";
		$columna4 = "keepalive-timeout";
		$columna5 = "status-autorefresh";
		$columna6 = "Usuarios simultáneos";
		$columna7 = "rate-limit";
		$columna8 = "MAC cookie";
		$columna9 = "MAC cookie timeout";
		$columna10 = "adress-list";
		$columna11 = "transparent-proxy";
		$columna12 = "default";

		$data['columns'] = array($columna0, $columna1, $columna2, $columna3, $columna4, $columna5, $columna6, $columna7, $columna8, $columna9, $columna10, $columna11, $columna12);

		$perfiles = $this->MKTModel->MostrarRecargarDatosPerfiles();
		$data['data'] = $perfiles[0];
		$data['conexionMKT'] = $perfiles[1];

		$this->load->view('plantillas/header');
		$this->load->view('perfiles/show', $data);
		$this->load->view('plantillas/footer');
	}

	public function GuardarEditar()
	{
		$conexionMKT = true;
		$mensajeError = "";

		$datos = $this->input->post('datos');
		
		if ($datos['id'] == "-1") {
			$data = $this->MKTModel->addUserProfile($datos['nombre'], $datos['rate'],$datos['sharedUsers'], $datos['macCookie'], $datos['macCookieTimeout'], $datos['keepaliveTimeout']);
			$mensajeError = $data[0];
			$conexionMKT = $data[1];
		}else{
			$data = $this->MKTModel->editUserProfile($datos['id'], $datos['nombre'], $datos['rate'],$datos['sharedUsers'], $datos['macCookie'], $datos['macCookieTimeout'], $datos['keepaliveTimeout']);
			$mensajeError = $data[0];
			$conexionMKT = $data[1];
		}


		$data = $this->MKTModel->MostrarRecargarDatosPerfiles();
		$conexionMKT = $data[1];

		echo json_encode(array($conexionMKT, $data[0], $mensajeError));
	}

	public function EliminarPerfil()
	{
		$conexionMKT = true;

		$input = file_get_contents('php://input');
    	$decodedInput = json_decode($input, true);
		$perfiles = $decodedInput['perfiles'];

		$data = $this->MKTModel->eliminarPefiles($perfiles);
		$conexionMKT = $data[1];

		$data = $this->MKTModel->MostrarRecargarDatosPerfiles();
		$conexionMKT = $data[1];

		echo json_encode(array($conexionMKT, $data[0]));
	}


}
