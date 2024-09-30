<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UsuariosWeb extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->model("UsuariosWebModel");
        $this->load->model("ObtenerUltimoIdModel");

        if (!$this->session->userdata('logged_in')) {
            redirect(base_url() . "Login");
            return;
        }
        if ($this->session->userdata('adm') == "0") {
            $this->session->set_flashdata('error', 'No tiene permisos suficientes');
            redirect(base_url() . "Login");
            return;
        }
    }

    public function show()
    {

        $columna1 = "ID";
        $columna2 = "USUARIO";
        $columna3 = "PASSWORD";
        $columna4 = "ROL";
        $columna5 = "LAST_LOGIN";
        $columna6 = "DELETED_AT";

        $data['columns'] = array($columna1, $columna2, $columna3, $columna4, $columna5, $columna6);

        $data['data'] = $this->MostrarRecargarDatos();

        $this->load->view('plantillas/header');
        $this->load->view('usuarios_web/show', $data);
        $this->load->view('plantillas/footer');
    }

    public function GuardarEditar()
    {
        $datos = $this->input->post('datos');

        //NUevo
        if ($datos['id'] == -1) {
            $id = $this->ObtenerUltimoIdModel->obtenerUltimoId('tbl_usuarios_web');
            $this->UsuariosWebModel->nuevoUsuario($id, $datos['usuario'], $datos['password'], $datos['rol'], null);
        }

        //Editar
        else {
            $this->UsuariosWebModel->guardarCambios($datos['id'], $datos['usuario'], $datos['password'], $datos['rol'], null);
        }

        $data = $this->MostrarRecargarDatos();

        echo json_encode(array(true, $data));
    }

    public function EliminarUsuarioWeb()
    {
		$input = file_get_contents('php://input');
		$decodedInput = json_decode($input, true);

		$datos = $decodedInput['usuariosweb'];

        foreach($datos as $item) $this->UsuariosWebModel->eliminar($item['ID']);

        $data = $this->MostrarRecargarDatos();
        echo json_encode(array(true, $data));
    }

    function MostrarRecargarDatos()
    {

        $usuarios = $this->UsuariosWebModel->getUsuarios();

        foreach ($usuarios as $item) {

            if ($item->ROL == "0") $item->ROL = "Usuario";
            if ($item->ROL == "1") $item->ROL = "Administrador";
        }

        return $usuarios;
    }

}
