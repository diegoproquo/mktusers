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

		/*if (!$this->session->userdata('logged_in')) {
			redirect(base_url() . "Login");
			return;
		}*/
	}

	public function show()
	{
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

		$data['columns'] = array($columna1, $columna2, $columna3, $columna4, $columna5, $columna6, $columna7, $columna8, $columna9, $columna10, $columna11);

		$data['data'] = $this->MKTModel->MostrarRecargarDatosPerfiles();

		$this->load->view('plantillas/header');
		$this->load->view('perfiles/show', $data);
		$this->load->view('plantillas/footer');
	}

	public function GuardarEditar()
	{
		$datos = $this->input->post('datos');

		// TODO comprobar este metodo con un MKT conectado
		$this->MKTModel->addUserProfile($datos['nombre'], $datos['rate'],$datos['sharedUsers'], $datos['macCookie'], $datos['macCookieTimeout'],'24h');

		$data = $this->MKTModel->MostrarRecargarDatosPerfiles();

		echo json_encode(array(true, $data));
	}


}
