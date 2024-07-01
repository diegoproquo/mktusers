<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->model('MKTModel');
	}

	public function show()
	{

		$columna0 = "-";
		$columna1 = ".id";
		$columna2 = "Usuario";
		$columna3 = "Tiempo de actividad";
		$columna4 = "Bytes recibidos";
		$columna5 = "Bytes enviados";
		$columna6 = "Comentario";
		$columna7 = "Deshabilitado";

		$data['columns'] = array($columna0, $columna1, $columna2, $columna3, $columna4, $columna5, $columna6, $columna7);

		$usuarios = $this->MKTModel->MostrarRecargarDatosUsuarios();
		$data['data'] = $usuarios[0];
		$data['conexionMKT'] = $usuarios[1];

		$perfiles = $this->MKTModel->MostrarRecargarDatosPerfiles();
		$data['perfiles'] = $perfiles[0];
		$data['conexionMKT'] = $perfiles[1];

		$this->load->view('plantillas/header');
		$this->load->view('usuarios/show', $data);
		$this->load->view('plantillas/footer');
	}


	public function procesarCSV() {
		$conexionMKT = true;
		
        $input = file_get_contents('php://input');
        $decodedInput = json_decode($input, true);


        if (isset($decodedInput['csvData']) && !empty($decodedInput['csvData'])) {
            $csvData = $decodedInput['csvData'];

			$usuarios = array();

            // Procesar los datos del CSV y asociarlos a un campo de usuario Mikrotik
            foreach ($csvData as $row) {
				$user = array();
				$user['name'] = $row[$decodedInput['columnaUsuario']];
				$user['password'] = $row[$decodedInput['columnaPassword']];
				$user['comment'] = $row[$decodedInput['columnaComment']];
				$user['profile'] = $decodedInput['perfil'];
				$usuarios[] = $user;
            }

			$data = $this->MKTModel->importarUsuarios($usuarios);
			$conexionMKT = $data[1];

            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se recibieron datos."]);
        }

		$data = $this->MKTModel->MostrarRecargarDatosUsuarios();
		$conexionMKT = $data[1];
		$usuarios = $data[0];

		// Esto lo hago para manejar mas facil la respuesta en ajax
		if($conexionMKT == true) $conexionMKT = "T";
		else $conexionMKT = "F";

		echo json_encode(array($conexionMKT, $usuarios));
    }




	public function NuevoUsuario()
	{
		$conexionMKT = true;

		$datos = $this->input->post('datos');
		
		$data = $this->MKTModel->nuevoUsuarioHotspot($datos['usuario'], $datos['password'], $datos['perfil'], $datos['comentario']);
		$conexionMKT = $data[1];

		$data = $this->MKTModel->MostrarRecargarDatosUsuarios();
		$conexionMKT = $data[1];

		echo json_encode(array($conexionMKT, $data[0]));
	}

	public function EliminarUsuarios()
	{
		$conexionMKT = true;

		$input = file_get_contents('php://input');
    	$decodedInput = json_decode($input, true);

		$usuarios = $decodedInput['usuarios'];
		$data = $this->MKTModel->eliminarUsuarios($usuarios);
		$conexionMKT = $data[1];

		$data = $this->MKTModel->MostrarRecargarDatosUsuarios();
		$conexionMKT = $data[1];

		echo json_encode(array($conexionMKT, $data[0]));
	}

	public function HabilitarUsuarios()
	{
		$conexionMKT = true;

		$input = file_get_contents('php://input');
    	$decodedInput = json_decode($input, true);

		$usuarios = $decodedInput['usuarios'];
		$data = $this->MKTModel->habilitarUsuarios($usuarios);
		$conexionMKT = $data[1];

		$data = $this->MKTModel->MostrarRecargarDatosUsuarios();
		$conexionMKT = $data[1];

		echo json_encode(array($conexionMKT, $data[0]));
	}

	public function DeshabilitarUsuarios()
	{
		$conexionMKT = true;

		$input = file_get_contents('php://input');
    	$decodedInput = json_decode($input, true);

		$usuarios = $decodedInput['usuarios'];

		$data = $this->MKTModel->deshabilitarUsuarios($usuarios);
		$conexionMKT = $data[1];

		$data = $this->MKTModel->MostrarRecargarDatosUsuarios();
		$conexionMKT = $data[1];

		echo json_encode(array($conexionMKT, $data[0]));
	}
}
