<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \RouterOS\Config;
use \RouterOS\Client;
use \RouterOS\Query;

class Usuarios extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
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

		$data['data'] = $this->MostrarRecargarDatos();

		$this->load->view('plantillas/header');
		$this->load->view('usuarios/show', $data);
		$this->load->view('plantillas/footer');
	}

	public function GuardarEditar()
	{
		$datos = $this->input->post('datos');

		//NUevo
		if ($datos['id'] == -1) {
			$id = $this->ObtenerUltimoIdModel->obtenerUltimoId('tbl_usuarios');
			$this->UsuariosModel->nuevoUsuario($id, $datos['nombre'], $datos['usuario'], $datos['password'], $datos['rol'], $datos['site_id'], null);
		}

		//Editar
		else {
			$this->UsuariosModel->guardarCambios($datos['id'], $datos['nombre'], $datos['usuario'], $datos['password'], $datos['rol'], $datos['site_id'], null);
		}

		$data = $this->MostrarRecargarDatos($datos['site_id']);

		echo json_encode(array(true, $data));
	}

	public function EliminarUsuario()
	{
		$datos = $this->input->post('datos');

		$this->UsuariosModel->eliminar($datos['id']);

		$data = $this->MostrarRecargarDatos($datos['site_id']);
		echo json_encode(array(true, $data));
	}

	function MostrarRecargarDatos()
	{

		require_once 'C:\Proyectos\mktusers\vendor\autoload.php';

		$config = (new Config())
			->set('timeout', 5)
			->set('host', '192.168.88.1') // Cambia esta IP por la del MikroTik
			->set('user', 'admin') // Cambia estas credenciales según las tuyas
			->set('pass', 'terminal');

		// Crear un cliente y conectarse al dispositivo MikroTik
		$client = new Client($config);

		try {
			// Intentar conectarse
			$client->connect();

			// Consulta para obtener la lista de usuarios del hotspot
			$query = new Query('/ip/hotspot/user/print');

			// Enviar la consulta al MikroTik
			$usuarios = $client->query($query)->read();

		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}


		foreach ($usuarios as $item) {
			$item['-'] = '
			<div class="dropdown" style="position: static;">
			<button class="dropbtn"><i class="fas fa-ellipsis-vertical"></i></button>
			<div class="dropdown-content" style="cursor:pointer">
			  <a data-toggle="modal" data-target="#modalUsuarios" onclick="ClicEditarUsuario(' . $item['.id'] . ')">Editar</a>
			  <a onclick="ClicEliminarUsuario(' . $item['.id'] . ')" >Eliminar</a>
			</div>
		  </div> ';

		}

		return $usuarios;
	}

	function getUsuario()
	{
		$datos = $this->input->post('datos');

		$usuario = $this->UsuariosModel->uno($datos['id']);

		echo json_encode($usuario);
	}
}
