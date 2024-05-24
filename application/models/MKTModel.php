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

	private function conexionMKT()
	{
		require_once 'C:\Proyectos\mktusers\vendor\autoload.php';

		//TODO repasar como funciona este try catch y si se devuelve bien la instacia $client (lo ideal seria poder implementar MostrarAlertError)
		try {
			$config = (new Config())
				->set('timeout', 5)
				->set('host', $this->host)
				->set('user', $this->user)
				->set('pass', $this->pass);

			$client = new Client($config);
			return $client;
		} catch (\Exception $e) {
			$this->session->set_flashdata('error', "Error: " . $e->getMessage() . "\n");
			redirect(base_url() . "Login");
			return;
		}
	}



	// * SECTION USUARIOS: Código relacionado con USUARIOS Hotspot

	public function nuevoUsuarioHotspot($username, $password, $profile, $comentario)
	{

		$client = $this->conexionMKT();

		try {
			// Conectarse al dispositivo MikroTik
			$client->connect();

			//TODO comprobar si el perfil se aplica correctamente
			$query = new Query('/ip/hotspot/user/add');
			$query->add('=name=' . $username);
			$query->add('=password=' . $password);
			$query->add('=profile=' . $profile);
			$query->add('=comment=' . $comentario);

			// Enviar la consulta al dispositivo MikroTik
			$response = $client->query($query)->read();

		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}
	}

	public function MostrarRecargarDatosUsuarios()
	{

		$client = $this->conexionMKT();

		try {
			// Intentar conectarse
			$client->connect();

			// Consulta para obtener la lista de usuarios del hotspot
			$query = new Query('/ip/hotspot/user/print');

			// Enviar la consulta al MikroTik
			$usuarios = $client->query($query)->read();

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

		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}
	}

	public function MostrarRecargarDatosUsuariosActivos()
	{

		$client = $this->conexionMKT();

		try {
			// Conectarse al dispositivo MikroTik
			$client->connect();

			$query = new Query('/ip/hotspot/active/print');

			// Enviar la consulta al dispositivo MikroTik
			$response = $client->query($query)->read();

			return $response;

		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}
	}

	public function MostrarRecargarUltimasConexiones()
	{

		$client = $this->conexionMKT();

		try {
			// Intentar conectarse
			$client->connect();

			// Consulta para obtener la lista de usuarios del hotspot
			$query = new Query('/ip/hotspot/user/print');

			// Enviar la consulta al MikroTik
			$usuarios = $client->query($query)->read();

			return $usuarios;

		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}
	}

	public function eliminarUsuarios($usuarios)
	{
		$client = $this->conexionMKT();

		try {

			$client->connect();

			foreach ($usuarios as $user) {
				$id = $user['0'];

				// Crear la consulta para eliminar al usuario
				$query = new Query('/ip/hotspot/user/remove');
				$query->add('=.id=' . $id);

				// Enviar la consulta al MikroTik
				$response = $client->query($query)->read();

			}

		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}
	}

	public function habilitarUsuarios($usuarios)
	{
		$client = $this->conexionMKT();

		try {

			$client->connect();

			foreach ($usuarios as $user) {
				$id = $user['0'];

				// Crear la consulta para eliminar al usuario
				$query = new Query('/ip/hotspot/user/set');
				$query->add('=.id=' . $id);
				$query->add('=disabled=no');
				$client->query($query)->read();

				// Enviar la consulta al MikroTik
				$response = $client->query($query)->read();

			}

		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}
	}

	public function deshabilitarUsuarios($usuarios)
	{
		$client = $this->conexionMKT();

		try {

			$client->connect();

			foreach ($usuarios as $user) {
				$id = $user['0'];

				// Crear la consulta para eliminar al usuario
				$query = new Query('/ip/hotspot/user/set');
				$query->add('=.id=' . $id);
				$query->add('=disabled=yes');
				$client->query($query)->read();

				// Enviar la consulta al MikroTik
				$response = $client->query($query)->read();

			}

		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}
	}

	// TODO FALTA PROBARLA Y DESPUES IMPLEMENTARLA. EL COMANDO PARA EXPULSAR FUNCIONA
	public function expelActiveUser($username)
	{
		$client = $this->conexionMKT();

		try {
			// Intentar conectarse
			$client->connect();

			// Primero, obtenemos la lista de usuarios activos
			$query = new Query('/ip/hotspot/active/print');
			$activeUsers = $client->query($query)->read();

			// Buscamos el usuario activo por nombre de usuario
			$userId = null;
			foreach ($activeUsers as $user) {
				if ($user['user'] === $username) {
					$userId = $user['.id'];
					break;
				}
			}

			// Si encontramos al usuario, procedemos a expulsarlo
			if ($userId !== null) {
				$removeQuery = new Query('/ip/hotspot/active/remove');
				$removeQuery->add('=.id=' . $userId);
				$client->query($removeQuery)->read();

				return "User {$username} has been expelled.";
			} else {
				return "User {$username} is not currently active.";
			}
		} catch (\Exception $e) {
			echo "Error: " . $e->getMessage() . "\n";
		}
	}


	// * SECTION PERFILES: Código relacionado con PERFILES Hotspot
	public function addUserProfile($nombre, $rateLimit, $sharedUsers, $macCookie, $macCookieTimeout, $sessionTimeout)
	{
		$client = $this->conexionMKT();

		try {

			$client->connect();

			// Consulta para añadir un perfil de usuario al hotspot
			$query = new Query('/ip/hotspot/user/profile/add');

			$query->add('=name=' . $nombre);

			if (!is_null($rateLimit) && $rateLimit !== '') {
				$query->add('=rate-limit=' . $rateLimit);
			}

			$query->add('=shared-users=' . $sharedUsers);
			$query->add('=add-mac-cookie=' . $macCookie);

			if (!is_null($macCookieTimeout) && $macCookieTimeout !== '') {
				$query->add('=mac-cookie-timeout=' . $macCookieTimeout);
			}

			$query->add('=session-timeout=' . $sessionTimeout);

			$query->add('=keepalive-timeout=' . '3h'); //Por defecto mete 2 minutos y te echa constantemente si no estas usando el dispositivo

			// Enviar la consulta al dispositivo MikroTik
			$response = $client->query($query)->read();

			return $response;

			

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
