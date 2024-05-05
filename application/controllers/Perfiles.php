<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \RouterOS\Config;
use \RouterOS\Client;
use \RouterOS\Query;


class Perfiles extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');

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

		$data['data'] = $this->MostrarRecargarDatos();

		$this->load->view('plantillas/header');
		$this->load->view('perfiles/show', $data);
		$this->load->view('plantillas/footer');
	}

	public function GuardarEditar()
	{
		$datos = $this->input->post('datos');

		$this->addUserProfile($datos['nombre'], $datos['rateUpload'],$datos['rateDownload'],$datos['macCookie'],$datos['cookieTimeout']);

		$data = $this->MostrarRecargarDatos();

		echo json_encode(array(true, $data));
	}

	public function addUserProfile($nombre, $rateUpload, $rateDownload, $macCookie, $cookieTimeout)
	{

		require_once 'C:\Proyectos\mktusers\vendor\autoload.php';

		$config = (new Config())
			->set('timeout', 5)
			->set('host', $this->session->userdata('host'))
			->set('user', $this->session->userdata('user'))
			->set('pass', $this->session->userdata('pass'));

		$client = new Client($config);

		try {

			$client->connect();

			//REVISAR AQUI

			$query = new Query('/ip/hotspot/user/profile/add');
			
			$query->add('=name=' . $nombre);
			$query->add('=password=' . $rateUpload);
			$query->add('=profile=' . $rateDownload);
			$query->add('=profile=' . $macCookie);
			$query->add('=profile=' . $cookieTimeout);

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

			// Consulta para obtener la lista de perfiles de usuario del hotspot
			$query = new Query('/ip/hotspot/user/profile/print');

			// Enviar la consulta al MikroTik
			$perfiles = $client->query($query)->read();
		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}


		foreach ($perfiles as $item) {
			$item['-'] = '
			<div class="dropdown" style="position: static;">
			<button class="dropbtn"><i class="fas fa-ellipsis-vertical"></i></button>
			<div class="dropdown-content" style="cursor:pointer">
			  <a data-toggle="modal" data-target="#modalUsuarios" onclick="ClicEditarPerfil(' . $item['.id'] . ')">Editar</a>
			  <a onclick="ClicEliminarUsuario(' . $item['.id'] . ')" >Eliminar</a>
			</div>
		  </div> ';
		}

		return $perfiles;
	}
}
