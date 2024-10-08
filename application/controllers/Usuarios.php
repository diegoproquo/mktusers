<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*Controlador de los usuarios del MIKROTIK*/

class Usuarios extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->model('MKTModel');
		$this->load->model('UsuariosMktModel');
		$this->load->model('TagsModel');

		if (!$this->session->userdata('logged_in')) {
			redirect(base_url() . "Login");
			return;
		}
	}

	public function show()
	{

		$columna0 = ".";
		$columna1 = ".id";
		$columna2 = "Usuario";
		$columna3 = "Tiempo total de conexión";
		$columna4 = "Perfil";
		$columna5 = "Tráfico descarga";
		$columna6 = "Tráfico subida";
		$columna7 = "Tag";
		$columna8 = "Deshabilitado";
		$columna9 = "Comentario";

		$data['columns'] = array($columna0, $columna1, $columna2, $columna3, $columna4, $columna5, $columna6, $columna7, $columna8, $columna9);

		$usuarios = $this->MKTModel->getUsuariosMKT();

		$data['data'] = $this->MostrarRecargarDatosUsuarios($usuarios[0]);
		$data['conexionMKT'] = $usuarios[1];

		$perfiles = $this->MKTModel->MostrarRecargarDatosPerfiles();
		$data['perfiles'] = $perfiles[0];
		$data['conexionMKT'] = $perfiles[1];

		$data['tags'] = $this->TagsModel->getTags();

		$this->load->view('plantillas/header');
		$this->load->view('usuarios/show', $data);
		$this->load->view('plantillas/footer');
	}


	public function procesarCSV()
	{
		$conexionMKT = true;
		$mensajeError = "";

		$input = file_get_contents('php://input');
		$decodedInput = json_decode($input, true);


		if (isset($decodedInput['csvData']) && !empty($decodedInput['csvData'])) {
			$csvData = $decodedInput['csvData'];

			$usuariosCSV = array();

			// Procesar los datos del CSV y asociarlos a un campo de usuario Mikrotik
			foreach ($csvData as $row) {
				$user = array();
				$user['name'] = $row[$decodedInput['columnaUsuario']];
				$user['password'] = $row[$decodedInput['columnaPassword']];
				$user['comment'] = $row[$decodedInput['columnaComment']];
				$user['profile'] = $decodedInput['perfil'];
				$user['tags'] = $decodedInput['tags'];
				$usuariosCSV[] = $user;
			}

			$data = $this->MKTModel->importarUsuarios($usuariosCSV);
			$mensajeError = $data[0];
			$conexionMKT = $data[1];
		}

		$data = $this->MKTModel->getUsuariosMKT();
		$conexionMKT = $data[1];
		$usuarios = $data[0];

		$this->UsuariosMktModel->sincronizarUsuarios($usuarios);

		if ($mensajeError == "") {
			foreach ($usuariosCSV as $item) {
				$this->UsuariosMktModel->actualizarTag($item['name'], $item['tags']);
			}
		}

		$usuarios = $this->MostrarRecargarDatosUsuarios($data[0]);

		echo json_encode(array($conexionMKT, $usuarios, $mensajeError));
	}

	public function GuardarEditarUsuario()
	{
		$conexionMKT = true;
		$mensajeError = "";

		$datos = $this->input->post('datos');

		if ($datos['id'] == "-1") {
			$data = $this->MKTModel->nuevoUsuarioHotspot($datos['usuario'], $datos['password'], $datos['perfil'], $datos['comentario']);
			$mensajeError = $data[0];
			$conexionMKT = $data[1];
		} else {
			$data = $this->MKTModel->editarUsuarioHotpot($datos['id'], $datos['usuario'], $datos['password'], $datos['perfil'], $datos['comentario']);
			$mensajeError = $data[0];
			$conexionMKT = $data[1];
		}


		$data = $this->MKTModel->getUsuariosMKT();
		$conexionMKT = $data[1];
		$this->UsuariosMktModel->sincronizarUsuarios($data[0]);

		$this->UsuariosMktModel->actualizarTag($datos['usuario'], $datos['tag']);

		$usuarios = $this->MostrarRecargarDatosUsuarios($data[0]);


		echo json_encode(array($conexionMKT, $usuarios, $mensajeError));
	}

	public function AgregarTags($usuarios)
	{

		$usuariosBD = $this->UsuariosMktModel->getUsuariosPorID_MKT($usuarios);

		// Crear un mapa de usuariosBD con todos los campos que necesitas (ID_TAG, COLOR, NOMBRE_TAG)
		$usuariosBDMap = [];
		foreach ($usuariosBD as $usuarioBD) {
			$usuariosBDMap[$usuarioBD->ID_MKT] = [
				'ID_TAG' => $usuarioBD->ID_TAG,
				'COLOR' => $usuarioBD->COLOR,
				'NOMBRE_TAG' => $usuarioBD->NOMBRE_TAG
			];
		}

		// Iterar sobre los usuarios y añadir los datos desde usuariosBDMap
		foreach ($usuarios as &$usuario) {
			if (isset($usuariosBDMap[$usuario['.id']])) {
				$usuario['ID_TAG'] = $usuariosBDMap[$usuario['.id']]['ID_TAG'];
				$usuario['COLOR'] = $usuariosBDMap[$usuario['.id']]['COLOR'];
				$usuario['NOMBRE_TAG'] = $usuariosBDMap[$usuario['.id']]['NOMBRE_TAG'];
			} else {
				// Si no se encuentra, asignar valores nulos
				$usuario['ID_TAG'] = null;
				$usuario['COLOR'] = null;
				$usuario['NOMBRE_TAG'] = null;
			}
		}

		return $usuarios;
	}

	public function EliminarUsuarios()
	{
		$conexionMKT = true;

		$input = file_get_contents('php://input');
		$decodedInput = json_decode($input, true);

		$usuarios = $decodedInput['usuarios'];
		$data = $this->MKTModel->eliminarUsuarios($usuarios);
		$conexionMKT = $data[1];

		$this->UsuariosMktModel->eliminarUsuarios($usuarios);

		$data = $this->MKTModel->getUsuariosMKT();
		$conexionMKT = $data[1];
		$usuarios = $data[0];

		$this->UsuariosMktModel->sincronizarUsuarios($usuarios);

		$usuarios = $this->MostrarRecargarDatosUsuarios($data[0]);


		echo json_encode(array($conexionMKT, $usuarios));
	}

	public function HabilitarUsuarios()
	{
		$conexionMKT = true;

		$input = file_get_contents('php://input');
		$decodedInput = json_decode($input, true);

		$usuarios = $decodedInput['usuarios'];
		$data = $this->MKTModel->habilitarUsuarios($usuarios);
		$conexionMKT = $data[1];

		$data = $this->MKTModel->getUsuariosMKT();
		$conexionMKT = $data[1];

		$usuarios = $this->MostrarRecargarDatosUsuarios($data[0]);

		echo json_encode(array($conexionMKT, $usuarios));
	}

	public function DeshabilitarUsuarios()
	{
		$conexionMKT = true;

		$input = file_get_contents('php://input');
		$decodedInput = json_decode($input, true);

		$usuarios = $decodedInput['usuarios'];

		$data = $this->MKTModel->deshabilitarUsuarios($usuarios);
		$conexionMKT = $data[1];

		$data = $this->MKTModel->getUsuariosMKT();
		$conexionMKT = $data[1];

		$usuarios = $this->MostrarRecargarDatosUsuarios($data[0]);

		echo json_encode(array($conexionMKT, $usuarios));
	}

	public function MostrarRecargarDatosUsuarios($usuariosDB)
	{

		$usuarios = $this->AgregarTags($usuariosDB);

		foreach ($usuarios as &$item) {

			if ($item["COLOR"] != null) {
				$color = $item["COLOR"];
				$nombreTag = $item["NOMBRE_TAG"];

				$item['Tag'] = "<div class='card shadow-sm' style='border-left: .15rem solid $color;'>
									<div class='card-body p-2'>
										<div class='text-xs font-weight-bold text-uppercase' style='color: $color;'>$nombreTag</div>
									</div>
								</div>";
			}
		}

		return $usuarios;
	}
}
