<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends CI_Controller
{

	public function __construct() {
        parent::__construct();
        
        $this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->model("UsuariosModel");
		$this->load->model("SitesModel");
		$this->load->model("ObtenerUltimoIdModel");

		if (!$this->session->userdata('logged_in')) {
			redirect(base_url()."Login");
			return;
		}
		if ($this->session->userdata('adm') == "0") {
			redirect(base_url()."Login");
			return;
		}
    }

	public function show()
	{
		$site = $this->input->get('site');
		if($site == "" || $site == null) redirect(base_url()."Login");
		if(!$this->session->userdata('adm') && $this->session->userdata('site_id') != $site) redirect(base_url() . "Login");
		else $site = $this->SitesModel->getSitePorSite_Id($site);

		
		$data['site_id'] = $site->SITE_ID;

		$columna1 = "ID";
		$columna2 = "NOMBRE";
		$columna3 = "USUARIO";
		$columna4 = "PASSWORD";
		$columna5 = "ROL";
		$columna6 = "LAST_LOGIN";
		$columna7 = "DELETED_AT";
		$columna8 = "-";

		$data['columns'] = array($columna1, $columna2, $columna3, $columna4, $columna5, $columna6, $columna7, $columna8);

		$data['data'] = $this->MostrarRecargarDatos($site->SITE_ID);

		$this->load->view('plantillas/header', array('site_id' => $site->SITE_ID, 'site_nombre' => $site->NOMBRE));
		$this->load->view('usuarios/show', $data);
		$this->load->view('plantillas/footer');
	}

	public function GuardarEditar()
	{
		$datos = $this->input->post('datos');

		 //NUevo
		if ($datos['id'] == -1) {
			$id = $this->ObtenerUltimoIdModel->obtenerUltimoId('tbl_usuarios');
			$this->UsuariosModel->nuevoUsuario($id, $datos['nombre'], $datos['usuario'], $datos['password'],$datos['rol'], $datos['site_id'], null);
		} 

		//Editar
		else { 
			$this->UsuariosModel->guardarCambios($datos['id'], $datos['nombre'], $datos['usuario'], $datos['password'],$datos['rol'], $datos['site_id'], null);
		}

		$data = $this->MostrarRecargarDatos($datos['site_id']);

		echo json_encode(array(true, $data));
	}

	public function EliminarUsuario()
	{
		$datos = $this->input->post('datos');

		$this->UsuariosModel->eliminar($datos['id']);

		$data = $this->MostrarRecargarDatos($datos['site_id']);
		echo json_encode(array(true, $data));
	}

	function MostrarRecargarDatos($site_id)
	{

		$usuarios = $this->UsuariosModel->getUsuariosPorSiteId($site_id);

		foreach ($usuarios as $item) {
			$item->{'-'} = '
			<div class="dropdown" style="position: static;">
			<button class="dropbtn"><i class="fas fa-ellipsis-vertical"></i></button>
			<div class="dropdown-content" style="cursor:pointer">
			  <a data-toggle="modal" data-target="#modalUsuarios" onclick="ClicEditarUsuario(' . $item->ID . ')">Editar</a>
			  <a onclick="ClicEliminarUsuario(' . $item->ID . ')" >Eliminar</a>
			</div>
		  </div> ';

		  if($item->ROL == "0") $item->ROL = "Usuario";
		  if($item->ROL == "1") $item->ROL = "Administrador";

		}

		return $usuarios;
	}

	function getUsuario(){
		$datos = $this->input->post('datos');

		$usuario = $this->UsuariosModel->uno($datos['id']);

	   echo json_encode($usuario);
	}
}
