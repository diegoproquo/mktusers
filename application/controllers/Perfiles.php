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

	}

	public function show()
	{
		$columna0 = "-";
		$columna1 = ".id";
		$columna2 = "name";
		$columna3 = "idle-timeout";
		$columna4 = "keepalive-timeout";
		$columna5 = "status-autorefresh";
		$columna6 = "shared-users";
		$columna7 = "add-mac-cookie";
		$columna8 = "mac-cookie-timeout";
		$columna9 = "adress-list";
		$columna10 = "transparent-proxy";
		$columna11 = "default";

		$data['columns'] = array($columna0, $columna1, $columna2, $columna3, $columna4, $columna5, $columna6, $columna7, $columna8, $columna9, $columna10, $columna11);

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

		$datos = $this->input->post('datos');

		$data = $this->MKTModel->addUserProfile($datos['nombre'], $datos['rate'],$datos['sharedUsers'], $datos['macCookie'], $datos['macCookieTimeout'],'24h');
		$conexionMKT = $data[1];

		$data = $this->MKTModel->MostrarRecargarDatosPerfiles();
		$conexionMKT = $data[1];

		echo json_encode(array($conexionMKT, $data));
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
