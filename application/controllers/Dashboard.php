<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \RouterOS\Config;
use \RouterOS\Client;
use \RouterOS\Query;

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
	}

	public function index()
	{
		// Uso de la función
		/*$ip = '192.168.88.1'; // IP del MikroTik
		$login = 'admin'; // Usuario de administrador
		$password = 'terminal'; // Contraseña de administrador
		$username = 'nuevo_usuario'; // Nombre de usuario para el nuevo usuario de hotspot
		$profile = 'default'; // Perfil de hotspot que se aplicará al nuevo usuario

		$this->addHotspotUser($ip, $login, $password, $username, $profile);
*/

		$this->load->view('plantillas/header');
		$this->load->view('dashboard/show');
		$this->load->view('plantillas/footer');
	}



	public function addHotspotUser($ip, $login, $password, $username, $profile)
	{

		require_once 'C:\Proyectos\mktusers\vendor\autoload.php';

		$config = (new Config())
			->set('timeout', 5)
			->set('host', $ip)
			->set('user', $login)
			->set('pass', $password);

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

			// Verificar si la operación fue exitosa
			if ($response === '!trap') {
				echo "Error al agregar el usuario de hotspot: " . $response[1]['message'] . "\n";
			} else {
				echo $response['after']['message'];
			}
		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}
	}
}
