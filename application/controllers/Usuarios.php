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

		$columna0 = "-";
		$columna1 = ".id";
		$columna2 = "name";
		$columna3 = "uptime";
		$columna4 = "bytes-in";
		$columna5 = "bytes-out";
		$columna6 = "comment";
		$columna7 = "disabled";

		$data['columns'] = array($columna0, $columna1, $columna2, $columna3, $columna4, $columna5, $columna6, $columna7);

		$data['data'] = $this->MKTModel->MostrarRecargarDatosUsuarios();

		//TODO comprobar si se cargan bien los perfiles en el select
		$data['perfiles'] = $this->MKTModel->MostrarRecargarDatosPerfiles();

		$this->load->view('plantillas/header');
		$this->load->view('usuarios/show', $data);
		$this->load->view('plantillas/footer');
	}


	public function NuevoUsuario()
	{
		$datos = $this->input->post('datos');
		$this->MKTModel->nuevoUsuarioHotspot($datos['usuario'], $datos['password'], $datos['perfil'], $datos['comentario']);
		$data = $this->MKTModel->MostrarRecargarDatosUsuarios();
		echo json_encode(array(true, $data));
	}

	public function EliminarUsuarios()
	{
		$datos = $this->input->post('datos');
		$this->MKTModel->eliminarUsuarios($datos['usuarios']);

		$data = $this->MKTModel->MostrarRecargarDatosUsuarios();
		echo json_encode(array(true, $data));
	}

	public function DeshabilitarUsuarios()
	{
		$datos = $this->input->post('datos');
		$this->MKTModel->deshabilitarUsuarios($datos['usuarios']);

		$data = $this->MKTModel->MostrarRecargarDatosUsuarios();
		echo json_encode(array(true, $data));
	}
}
