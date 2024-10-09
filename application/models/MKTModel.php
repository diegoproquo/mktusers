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

				// Codificamos el comentario para que se muestre bien en el mikrotik y eliminamos espacios en blanco
				$comentario_utf8 = mb_convert_encoding($comentario, 'ISO-8859-1', 'UTF-8');
				$nombreSinEspacios = preg_replace('/\s+/', '', trim($username));

				$query = new Query('/ip/hotspot/user/add');
				$query->add('=name=' . $nombreSinEspacios);
				$query->add('=password=' . $password);
				$query->add('=profile=' . $profile);
				$query->add('=comment=' . $comentario_utf8);

				// Enviar la consulta al dispositivo MikroTik
				$response = $client->query($query)->read();

				if (isset($response["after"]['message'])) $response = $response["after"]['message'];
				else $response = "";

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

				// Codificamos el comentario para que se muestre bien en el mikrotik y eliminamos espacios en blanco
				$comentario_utf8 = mb_convert_encoding($comentario, 'ISO-8859-1', 'UTF-8');
				$nombreSinEspacios = preg_replace('/\s+/', '', trim($username));

				// Crear una nueva consulta para editar el usuario existente
				$query = new Query('/ip/hotspot/user/set');
				$query->add('=.id=' . $id);
				$query->add('=name=' . $nombreSinEspacios);
				$query->add('=password=' . $password);
				$query->add('=profile=' . $profile);
				$query->add('=comment=' . $comentario_utf8);

				// Enviar la consulta al dispositivo MikroTik
				$response = $client->query($query)->read();

				if (isset($response["after"]['message'])) $response = $response["after"]['message'];
				else $response = "";

				return array($response, true);
			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else {
			return array(array(), false);
		}
	}


	public function getUsuariosMKT()
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

				array_shift($usuarios); //Borramos el primer registro (usuario por defecto)

				$usuariosFormateados = array();
				foreach ($usuarios as $usuario) {

					if (isset($usuario["bytes-in"])) {
						$bytes_in = round($usuario["bytes-in"] * 0.000001, 2);
						if ($bytes_in >= 1000) {
							$bytes_in = $bytes_in / 1000;
							$bytes_in = $bytes_in . " GB";
						} else $bytes_in = $bytes_in . " MB";
					}
					if (isset($usuario["bytes-out"])) {
						$bytes_out = round($usuario["bytes-out"] * 0.000001, 2);
						if ($bytes_out >= 1000) {
							$bytes_out = $bytes_out / 1000;
							$bytes_out = $bytes_out . " GB";
						} else $bytes_out = $bytes_out . " MB";
					}

					$usuarioFormateado = array(
						".id" => isset($usuario[".id"]) ? $usuario[".id"] : "",
						"Usuario" => isset($usuario["name"]) ? $usuario["name"] : "",
						"Tiempo total de conexión" => isset($usuario["uptime"]) ? $usuario["uptime"] : "",
						"Tráfico descarga" => isset($usuario["bytes-in"]) ? $bytes_in  : "",
						"Tráfico subida" => isset($usuario["bytes-out"]) ? $bytes_out : "",
						"Paquetes recibidos" => isset($usuario["packets-in"]) ? $usuario["packets-in"] : "",
						"Paquetes enviados" => isset($usuario["packets-out"]) ? $usuario["packets-out"] : "",
						"Dinámico" => isset($usuario["dynamic"]) ? $usuario["dynamic"] : "",
						"Deshabilitado" => isset($usuario["disabled"]) ? $usuario["disabled"] : "",
						"Comentario" => isset($usuario["comment"]) ? mb_convert_encoding($usuario["comment"], 'UTF-8', 'ISO-8859-1') : "",
						"Perfil" => isset($usuario["profile"]) ? $usuario["profile"] : ""
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
					$item['-'] = '<a type="button" onclick="ExpulsarUsuario(\'' . $item[".id"] . '\')" class="btn btn-danger btn-icon-split btn-sm"><span class="icon text-white-50"><i class="fas fa-trash"></i></span><span class="text text-white">Cerrar sesión</span></a>';
				}
				unset($item);

				$usuariosFormateados = array();
				foreach ($usuarios as $usuario) {

					if (isset($usuario["bytes-in"])) {
						$bytes_in = round($usuario["bytes-in"] * 0.000001, 2);
						if ($bytes_in >= 1000) {
							$bytes_in = $bytes_in / 1000;
							$bytes_in = $bytes_in . " GB";
						} else $bytes_in = $bytes_in . " MB";
					}
					if (isset($usuario["bytes-out"])) {
						$bytes_out = round($usuario["bytes-out"] * 0.000001, 2);
						if ($bytes_out >= 1000) {
							$bytes_out = $bytes_out / 1000;
							$bytes_out = $bytes_out . " GB";
						} else $bytes_out = $bytes_out . " MB";
					}

					$usuarioFormateado = array(
						".id" => isset($usuario[".id"]) ? $usuario[".id"] : "",
						"Usuario" => isset($usuario["user"]) ? $usuario["user"] : "",
						"Actividad" => isset($usuario["uptime"]) ? $usuario["uptime"] : "",
						"Dirección IP" => isset($usuario["address"]) ? $usuario["address"] : "",
						"Dirección MAC" => isset($usuario["mac-address"]) ? $usuario["mac-address"] : "",
						"Descarga" => isset($usuario["bytes-in"]) ? $bytes_in : "",
						"Subida" => isset($usuario["bytes-out"]) ? $bytes_out : "",
						"Paquetes recibidos" => isset($usuario["packets-in"]) ? $usuario["packets-in"] : "",
						"Paquetes enviados" => isset($usuario["packets-out"]) ? $usuario["packets-out"] : "",
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
					$id = $user['.id'];

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

				$error = "";
				$duplicados = [];
				$nombresInvalidos = [];
				$contrasenasInvalidas = [];
				$campoVacio = false;

				$usuariosExistentes = $this->getUsuariosMKT();
				$usuariosExistentes = $usuariosExistentes[0];

				// Comprueba que los usuarios que se están importado sno existen ya. Si hay algun duplicado, no importará ningunn usuario. ()
				$nombresUsuariosExistentes = array_column($usuariosExistentes, 'Usuario');
				foreach ($usuarios as $usuario) {
					if (in_array($usuario['name'], $nombresUsuariosExistentes)) {
						$duplicados[] = $usuario['name'];
					}
					if (!preg_match('/^[a-zA-Z0-9\s@.-]*$/', $usuario['name'])) { //Se permiten @ y .
						// Si el nombre es inválida, agregarla a la lista de nombres inválidos
						$nombresInvalidos[] = $usuario['name']; // Guardamos el nombre para saber a quién pertenece
					}
					if (!preg_match('/^[a-zA-Z0-9\s@.-]*$/', $usuario['name'])) { //Se permiten @ y .
						// Si la contraseña es inválida, agregarla a la lista de contraseñas inválidas
						$contrasenasInvalidas[] = $usuario['name']; // Guardamos el nombre para saber a quién pertenece
					}
					if ($usuario['name'] == "" || $usuario['password'] == "") {
						$campoVacio = true;
					}
				}

				if (!empty($duplicados)) {
					$error = "Los siguientes usuarios ya existen: " . implode(", ", $duplicados);
					return array($error, true);
				}
				if (!empty($nombresInvalidos)) {
					$error .= "El nombre de los siguientes usuarios contiene caracteres no permitidos: " . implode(", ", $nombresInvalidos) . ".";
					return array($error, true);
				}
				if (!empty($contrasenasInvalidas)) {
					$error .= "La contraseña de los siguientes usuarios contiene caracteres no permitidos: " . implode(", ", $contrasenasInvalidas) . ".";
					return array($error, true);
				}
				if ($campoVacio) {
					$error .= "Se han detectado campos de usuario o contraseña vacíos.";
					return array($error, true);
				}

				foreach ($usuarios as $item) {
					$comentario_utf8 = mb_convert_encoding($item['comment'], 'ISO-8859-1', 'UTF-8');
					$nombreSinEspacios = preg_replace('/\s+/', '', trim($item['name']));
					$query = new Query('/ip/hotspot/user/add');
					$query->add('=name=' . $nombreSinEspacios);
					$query->add('=password=' . $item['password']);
					$query->add('=profile=' . $item['profile']);
					$query->add('=comment=' . $comentario_utf8);

					// Enviar la consulta al dispositivo MikroTik
					$response = $client->query($query)->read();

					if (isset($response["after"]['message'])) {
						$error = $response["after"]['message'];
					}
				}

				return array($error, true);
			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}


	// * SECTION PERFILES: Código relacionado con PERFILES Hotspot
	public function addUserProfile($nombre, $rateLimit, $sharedUsers, $macCookie, $macCookieTimeout, $keepaliveTimeout, $scripts)
	{
		$client = $this->conexionMKT();

		if ($client != false) {

			try {

				$client->connect();

				$error = "";

				// Consulta para añadir un perfil de usuario al hotspot
				$query = new Query('/ip/hotspot/user/profile/add');

				$query->add('=name=' . $nombre);

				if (!is_null($rateLimit) && $rateLimit !== '') {
					$query->add('=rate-limit=' . $rateLimit);
				}

				$query->add('=shared-users=' . $sharedUsers);
				$query->add('=add-mac-cookie=' . $macCookie);

				$query->add('=shared-users=' . $sharedUsers);
				$query->add('=add-mac-cookie=' . $macCookie);

				$onLoginScript = $scripts[0]->SCRIPT;
				$onLogoutScript = $scripts[1]->SCRIPT;
				$query->add('=on-login=' . $onLoginScript);
				$query->add('=on-logout=' . $onLogoutScript);

				if (!is_null($macCookieTimeout) && $macCookieTimeout !== '') {
					$query->add('=mac-cookie-timeout=' . $macCookieTimeout);
				}

				if (!is_null($keepaliveTimeout) && $keepaliveTimeout !== '') {
					$query->add('=keepalive-timeout=' . $keepaliveTimeout);
				}

				// Enviar la consulta al dispositivo MikroTik
				$response = $client->query($query)->read();

				if (isset($response["after"]['message'])) {
					$error = $response["after"]['message'];
				}

				return array($error, true);
			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}

	public function editUserProfile($id, $nombre, $rateLimit, $sharedUsers, $macCookie, $macCookieTimeout, $keepaliveTimeout)
	{
		$client = $this->conexionMKT();

		if ($client != false) {

			try {

				$client->connect();

				$error = "";

				// Consulta para editar un perfil de usuario al hotspot
				$query = new Query('/ip/hotspot/user/profile/set');
				$query->add('=.id=' . $id);

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
				} else $query->add('=keepalive-timeout=' . '2h'); //Por defecto mete 2 minutos y te echa constantemente si no estas usando el dispositivo

				// Enviar la consulta al dispositivo MikroTik
				$response = $client->query($query)->read();

				if (isset($response["after"]['message'])) {
					$error = $response["after"]['message'];
				}

				return array($error, true);
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
					$id = $perfil['.id'];

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

				$perfilesFormateados = array();
				foreach ($perfiles as $perfil) {

					$perfilFormateado = $perfil;
					$perfilFormateado["Nombre"] = isset($perfil["name"]) ? $perfil["name"] : "";
					$perfilFormateado["Usuarios simultáneos"] = isset($perfil["shared-users"]) ? $perfil["shared-users"] : "";
					$perfilFormateado["MAC cookie"] = isset($perfil["add-mac-cookie"]) ? $perfil["add-mac-cookie"] : "";
					$perfilFormateado["MAC cookie timeout"] = isset($perfil["mac-cookie-timeout"]) ? $perfil["mac-cookie-timeout"] : "";

					$perfilesFormateados[] = $perfilFormateado;
				}

				return array($perfilesFormateados, true);
			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return array(array(), false);
	}

	public function sincronizarScripts($scripts, $perfiles)
	{
		$client = $this->conexionMKT();

		if ($client != false) {

			try {

				$client->connect();

				$error = "";

				$onLoginScript = $scripts[0]->SCRIPT;
				$onLogoutScript = $scripts[1]->SCRIPT;

				foreach ($perfiles as $perfil) {
					// Consulta para editar un perfil de usuario al hotspot
					$query = new Query('/ip/hotspot/user/profile/set');
					$query->add('=.id=' . $perfil['.id']);

					$query->add('=add-mac-cookie=' . $perfil['add-mac-cookie']);

					$query->add('=on-login=' . $onLoginScript);
					$query->add('=on-logout=' . $onLogoutScript);

					// Enviar la consulta al dispositivo MikroTik
					$response = $client->query($query)->read();

					if (isset($response["after"]['message'])) {
						$error = $response["after"]['message'];
					}
				}

			} catch (\Exception $e) {
				echo "Error: " . $e->getMessage() . "\n";
			}
		} else return;
	}
}
