<?php

use \RouterOS\Config;
use \RouterOS\Client;
use \RouterOS\Query;

class MKTModel extends CI_Model
{
	private $host;
	private $user;
	private $pass;

	public function __construct()
	{
		$this->host =  $_ENV['MIKROTIK_HOST'];
		$this->user =  $_ENV['MIKROTIK_USER'];
		$this->pass = $_ENV['MIKROTIK_PASS'];
	}

	// * SECTION COMUN

	private function conexionMKT()
	{
		require_once $_ENV['AUTOLOAD'];

		try {
			$config = (new Config())
				->set('timeout', 3)
				->set('host', $this->host)
				->set('user', $this->user)
				->set('pass', $this->pass);

			$client = new Client($config);
			return $client;
		} catch (\Exception $e) {
			$this->session->set_flashdata('error', "Error: " . $e->getMessage() . "\n");
			return false;
		}
	}



	// * SECTION USUARIOS: Código relacionado con USUARIOS Hotspot

	public function nuevoUsuarioHotspot($username, $password, $profile, $comentario)
	{

		$client = $this->conexionMKT();

		if ($client != false) {
			try {
				// Conectarse al dispositivo MikroTik
				$client->connect();

				$query = new Query('/ip/hotspot/user/add');
				$query->add('=name=' . $username);
				$query->add('=password=' . $password);
				$query->add('=profile=' . $profile);
				$query->add('=comment=' . $comentario);

				// Enviar la consulta al dispositivo MikroTik
				$response = $client->query($query)->read();

				return array($response, true);
			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}

	public function editarUsuarioHotpot($id, $username, $password, $profile, $comentario)
	{
		$client = $this->conexionMKT();
	
		if ($client != false) {
			try {
				// Conectarse al dispositivo MikroTik
				$client->connect();
	
				// Crear una nueva consulta para editar el usuario existente
				$query = new Query('/ip/hotspot/user/set');
				$query->add('=.id=' . $id);
				$query->add('=name=' . $username);
				$query->add('=password=' . $password);
				$query->add('=profile=' . $profile);
				$query->add('=comment=' . $comentario);
	
				// Enviar la consulta al dispositivo MikroTik
				$response = $client->query($query)->read();
	
				return array($response, true);

			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else {
			return array(array(), false);
		}
	}
	

	public function MostrarRecargarDatosUsuarios()
	{

		$client = $this->conexionMKT();

		if ($client != false) {

			try {
				// Intentar conectarse
				$client->connect();

				// Consulta para obtener la lista de usuarios del hotspot
				$query = new Query('/ip/hotspot/user/print');

				// Enviar la consulta al MikroTik
				$usuarios = $client->query($query)->read();

				$usuariosFormateados = array();
				foreach ($usuarios as $usuario) {
					$usuarioFormateado = array(
						".id" => isset($usuario[".id"]) ? $usuario[".id"] : "-",
						"Usuario" => isset($usuario["name"]) ? $usuario["name"] : "-",
						"Tiempo de actividad" => isset($usuario["uptime"]) ? $usuario["uptime"] : "-",
						"Bytes recibidos" => isset($usuario["bytes-in"]) ? $usuario["bytes-in"] : "-",
						"Bytes enviados" => isset($usuario["bytes-out"]) ? $usuario["bytes-out"] : "-",
						"Paquetes recibidos" => isset($usuario["packets-in"]) ? $usuario["packets-in"] : "-",
						"Paquetes enviados" => isset($usuario["packets-out"]) ? $usuario["packets-out"] : "-",
						"Dinámico" => isset($usuario["dynamic"]) ? $usuario["dynamic"] : "-",
						"Deshabilitado" => isset($usuario["disabled"]) ? $usuario["disabled"] : "-",
						"Comentario" => isset($usuario["comment"]) ? $usuario["comment"] : "-",
						"Perfil" => isset($usuario["profile"]) ? $usuario["profile"] : "-"
					);
					$usuariosFormateados[] = $usuarioFormateado;
				}
			
				return array($usuariosFormateados, true);
				
			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}

	public function MostrarRecargarDatosUsuariosActivos()
	{

		$client = $this->conexionMKT();

		if ($client != false) {

			try {
				// Conectarse al dispositivo MikroTik
				$client->connect();

				$query = new Query('/ip/hotspot/active/print');

				// Enviar la consulta al dispositivo MikroTik
				$usuarios = $client->query($query)->read();

				foreach ($usuarios as &$item) {
					$item['-'] = '<a type="button" onclick="ExpulsarUsuario(\'' . $item[".id"] . '\')" title="Cerrar sesión"><i class="fa fa-xmark" style="color:red; font-size:20px; cursor:pointer;"></i></a>';
				}
				unset($item);

				$usuariosFormateados = array();
				foreach ($usuarios as $usuario) {
					$usuarioFormateado = array(
						".id" => isset($usuario[".id"]) ? $usuario[".id"] : "-",
						"Usuario" => isset($usuario["user"]) ? $usuario["user"] : "-",
						"Tiempo de actividad" => isset($usuario["uptime"]) ? $usuario["uptime"] : "-",
						"Dirección IP" => isset($usuario["address"]) ? $usuario["address"] : "-",
						"Dirección MAC" => isset($usuario["mac-address"]) ? $usuario["mac-address"] : "-",
						"Bytes recibidos" => isset($usuario["bytes-in"]) ? $usuario["bytes-in"] : "-",
						"Bytes enviados" => isset($usuario["bytes-out"]) ? $usuario["bytes-out"] : "-",
						"Paquetes recibidos" => isset($usuario["packets-in"]) ? $usuario["packets-in"] : "-",
						"Paquetes enviados" => isset($usuario["packets-out"]) ? $usuario["packets-out"] : "-",
						"-" => isset($usuario["-"]) ? $usuario["-"] : "-"
					);
					$usuariosFormateados[] = $usuarioFormateado;
				}

				return array($usuariosFormateados, true);

			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}

	public function eliminarUsuarios($usuarios)
	{
		$client = $this->conexionMKT();

		if ($client != false) {

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

				return array($response, true);

			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}

	public function habilitarUsuarios($usuarios)
	{
		$client = $this->conexionMKT();

		if ($client != false) {

			try {

				$client->connect();

				foreach ($usuarios as $user) {
					$id = $user['.id'];

					// Crear la consulta para eliminar al usuario
					$query = new Query('/ip/hotspot/user/set');
					$query->add('=.id=' . $id);
					$query->add('=disabled=no');
					$client->query($query)->read();

					// Enviar la consulta al MikroTik
					$response = $client->query($query)->read();

				}

				return array($response, true);

			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}

	public function deshabilitarUsuarios($usuarios)
	{
		$client = $this->conexionMKT();

		if ($client != false) {

			try {

				$client->connect();

				foreach ($usuarios as $user) {
					$id = $user['.id'];

					// Crear la consulta para eliminar al usuario
					$query = new Query('/ip/hotspot/user/set');
					$query->add('=.id=' . $id);
					$query->add('=disabled=yes');
					$client->query($query)->read();

					// Enviar la consulta al MikroTik
					$response = $client->query($query)->read();

				}

				return array($response, true);
				
			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}

	public function expulsarUsuario($id)
	{
		$client = $this->conexionMKT();

		if ($client != false) {

			try {
				// Intentar conectarse
				$client->connect();

				// Si encontramos al usuario, procedemos a expulsarlo
				$removeQuery = new Query('/ip/hotspot/active/remove');
				$removeQuery->add('=.id=' . $id);
				$response = $client->query($removeQuery)->read();

				return array($response, true);
			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}

	function importarUsuarios($usuarios)
	{
		$client = $this->conexionMKT();

		if ($client != false) {
			try {
				// Conectarse al dispositivo MikroTik
				$client->connect();

				foreach ($usuarios as $item) {
					$query = new Query('/ip/hotspot/user/add');
					$query->add('=name=' . $item['name']);
					$query->add('=password=' . $item['password']);
					$query->add('=profile=' . $item['profile']);
					$query->add('=comment=' . $item['comment']);

					// Enviar la consulta al dispositivo MikroTik
					$response = $client->query($query)->read();
				}

				return array($response, true);
			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}


	// * SECTION PERFILES: Código relacionado con PERFILES Hotspot
	public function addUserProfile($nombre, $rateLimit, $sharedUsers, $macCookie, $macCookieTimeout, $keepaliveTimeout)
	{
		$client = $this->conexionMKT();

		if ($client != false) {

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

				if (!is_null($keepaliveTimeout) && $keepaliveTimeout !== '') {
					$query->add('=keepalive-timeout=' . $keepaliveTimeout);
				}else $query->add('=keepalive-timeout=' . '2h'); //Por defecto mete 2 minutos y te echa constantemente si no estas usando el dispositivo
				

				// Enviar la consulta al dispositivo MikroTik
				$response = $client->query($query)->read();

				return array($response, true);
			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}

	public function eliminarPefiles($perfiles)
	{
		$client = $this->conexionMKT();

		if ($client != false) {

			try {

				$client->connect();

				foreach ($perfiles as $perfil) {
					$id = $perfil['0'];

					// Crear la consulta para eliminar al usuario
					$query = new Query('/ip/hotspot/user/profile/remove');
					$query->add('=.id=' . $id);

					// Enviar la consulta al MikroTik
					$response = $client->query($query)->read();
				}

				return array($response, true);

			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}


	public function MostrarRecargarDatosPerfiles()
	{

		$client = $this->conexionMKT();

		if ($client != false) {

			try {
				// Intentar conectarse
				$client->connect();

				// Consulta para obtener la lista de perfiles de usuario del hotspot
				$query = new Query('/ip/hotspot/user/profile/print');

				// Enviar la consulta al MikroTik
				$perfiles = $client->query($query)->read();

				return array($perfiles, true);

			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}
}
