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

		$ip = '192.168.88.1'; // IP del MikroTik
		$login = 'admin'; // Usuario de administrador
		$password = 'terminal'; // Contraseña de administrador

		$this->addHotspotUser($datos['usuario'], $datos['password'], 'default');

		$data = $this->MostrarRecargarDatos();

		echo json_encode(array(true, $data));
	}

	public function addHotspotUser($username, $password, $profile)
	{

		require_once 'C:\Proyectos\mktusers\vendor\autoload.php';

		$config = (new Config())
			->set('timeout', 5)
			->set('host', '192.168.88.1')
			->set('user', 'admin')
			->set('pass', 'terminal');

		$client = new Client($config);

		try {
			// Conectarse al dispositivo MikroTik
			$client->connect();

			// Verificar si la conexión fue exitosa

			// Construir la consulta para agregar el usuario de hotspot
			$query = new Query('/ip/hotspot/user/add');
			$query->add('=name=' . $username);
			$query->add('=password=' . $password);
			$query->add('=profile=' . $profile);

			// Enviar la consulta al dispositivo MikroTik
			$response = $client->query($query)->read();

			
		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}

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
			$item['dynamic'] = '
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


}
