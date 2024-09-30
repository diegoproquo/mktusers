<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tags extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->model('TagsModel');

		if (!$this->session->userdata('logged_in')) {
			redirect(base_url() . "Login");
			return;
		}
	}

	public function show()
	{
		$columna1 = "ID";
		$columna2 = "Nombre";
		$columna3 = "Color";
		$columna4 = "Deleted_At";

		$data['columns'] = array($columna1, $columna2, $columna3, $columna4);
		$data['data'] = $this->MostrarRecargarDatosTags();

		$this->load->view('plantillas/header');
		$this->load->view('tags/show', $data);
		$this->load->view('plantillas/footer');
	}

    
    public function GuardarEditar()
    {
        $datos = $this->input->post('datos');

        //Nuevo
        if ($datos['id'] == -1) {
            $id = $this->ObtenerUltimoIdModel->obtenerUltimoId('tbl_usuarios_web');
            $this->TagsModel->nuevoTag($id, $datos['nombre'], $datos['color']);
        }

        //Editar
        else {
            $this->TagsModel->guardarCambios($datos['id'], $datos['nombre'], $datos['color'], null);
        }

        $data = $this->MostrarRecargarDatosTags();

        echo json_encode(array(true, $data));
    }

    public function EliminarTag()
    {
		$input = file_get_contents('php://input');
		$decodedInput = json_decode($input, true);

		$datos = $decodedInput['usuariosweb'];

        foreach($datos as $item) $this->TagsModel->eliminar($item['ID']);

        $data = $this->MostrarRecargarDatos();
        echo json_encode(array(true, $data));
    }

    function MostrarRecargarDatosTags()
    {

        $tags = $this->TagsModel->getTags();

        foreach ($tags as $item) {

        }

        return $tags;
    }

}
