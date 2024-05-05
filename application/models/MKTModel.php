<?php

use \RouterOS\Config;
use \RouterOS\Client;
use \RouterOS\Query;

class MKTModel extends CI_Model
{
    public $host;
    public $user;
    public $pass;

    public function __construct()
    {
        $this->host = $this->session->userdata('host');
        $this->user = $this->session->userdata('user');
        $this->pass = $this->session->userdata('pass');
    }

	// * SECTION COMUN

	private function createClient() {
        require_once 'C:\Proyectos\mktusers\vendor\autoload.php';

        $config = (new Config())
            ->set('timeout', 5)
            ->set('host', $this->host)
            ->set('user', $this->user)
            ->set('pass', $this->pass);

        return new Client($config);
    }

    // * SECTION USUARIOS: Código relacionado con USUARIOS Hotspot

    public function addHotspotUser($username, $password, $profile)
	{

		//TODO comprobar si funciona el aislamiento de este metodo 
		$client = $this->createClient();

		try {
			// Conectarse al dispositivo MikroTik
			$client->connect();

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

	public function MostrarRecargarDatosUsuarios()
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


	// * SECTION PERFILES: Código relacionado con PERFILES Hotspot

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

			//TODO REVISAR ESTE CODIGO
			
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

	public function MostrarRecargarDatosPerfiles()
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
