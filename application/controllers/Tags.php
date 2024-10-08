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
        $this->load->model('ObtenerUltimoIdModel');

		if (!$this->session->userdata('logged_in')) {
			redirect(base_url() . "Login");
			return;
		}
	}

	public function show()
	{

		$data['tags'] = $this->TagsModel->getTags();

		$this->load->view('plantillas/header');
		$this->load->view('tags/show', $data);
		$this->load->view('plantillas/footer');
	}

    
    public function GuardarEditar()
    {
        $datos = $this->input->post('datos');

        //Nuevo
        if ($datos['id'] == -1) {
            $id = $this->ObtenerUltimoIdModel->obtenerUltimoId('tbl_tags');
            $this->TagsModel->nuevoTag($id, $datos['nombre'], $datos['color']);
        }

        //Editar
        else {
            $this->TagsModel->guardarCambios($datos['id'], $datos['nombre'], $datos['color'], null);
        }

        $data = $this->TagsModel->getTags();

        echo json_encode(array(true, $data));
    }

    public function EliminarTag()
    {

        $datos = $this->input->post('datos');

        $this->TagsModel->eliminar($datos['id']);

        $data = $this->TagsModel->getTags();

        echo json_encode(array(true, $data));
    }

}
