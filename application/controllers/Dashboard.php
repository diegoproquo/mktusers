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



}
