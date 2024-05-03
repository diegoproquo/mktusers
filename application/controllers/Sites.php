<?php
defined('BASEPATH') or exit('No direct script access allowed');

use UniFi_API\Client;

class Sites extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->model("SitesModel");
		$this->load->model("PortalModel");
		$this->load->model("InformesModel");
		$this->load->model("ObtenerUltimoIdModel");

		if (!$this->session->userdata('logged_in')) {
			redirect(base_url() . "Login");
			return;
		}
	}

	public function show()
	{
		// Esta comprobacion hay que ponerla aqui porque si se pone arriba el header no puede acceder a la funcion ObtenerSitesBD si el usuario no es admin
		if ($this->session->userdata('adm') == "0") {
			redirect(base_url() . "Login");
			return;
		}

		$site = $this->input->get('site');
		if (!$this->SitesModel->comprobarSite($site)) redirect(base_url() . "Login");
		if(!$this->session->userdata('adm') && $this->session->userdata('site_id') != $site) redirect(base_url() . "Login");
		else $site = $this->SitesModel->getSitePorSite_Id($site);

		$columna1 = "ID";
		$columna2 = "NOMBRE";
		$columna3 = "SITE_ID";
		$columna4 = "DELETED_AT";
		$columna5 = "-";
		$data['columns'] = array($columna1, $columna2, $columna3, $columna4, $columna5);
		$data['data'] = $this->MostrarRecargarDatos();

		//Opciones para el select de los sites
		$data['sites'] = $this->SitesObtenerTodos();

		$this->load->view('plantillas/header', array('site_id' => $site->SITE_ID, 'site_nombre' => $site->NOMBRE));
		$this->load->view('sites/show', $data);
		$this->load->view('plantillas/footer');
	}

	public function SitesObtenerTodos()
	{

		$sites = $this->ObtenerListadoSites();

		return $sites;
	}

	public function ObtenerSitesBD()
	{

		$adm = false;
		$sites = "";
		$rol = $this->session->userdata('adm');
		if ($rol == "1") {
			$adm = true;
			$sites = $this->SitesModel->getSites();
		}

		echo json_encode(array($adm, $sites));
	}


	public function GuardarEditar()
	{
		$datos = $this->input->post('datos');

		$sites = $this->ObtenerListadoSites();

		foreach ($sites as $item) {
			if ($item->name == $datos['siteDesc']) {
				$site = $item;
				break;
			}
		}

		$site_BD = $this->SitesModel->getSitePorSite_Id($datos['siteDesc']);
		if ($site_BD) {
			echo json_encode(false);
			return;
		}

		$nombre = $site->{'desc'};
		$posicion_punto = strpos($nombre, '.'); // Buscamos la posicion del primer punto

		if ($posicion_punto !== false) { // Si el nombre del site tiene un punto
			// Eliminamos el punto y todos los caracteres a la izquierda de el
			$nombre = substr($nombre, $posicion_punto + 1);
		}
		$id = $this->ObtenerUltimoIdModel->obtenerUltimoId('tbl_sites');
		$this->SitesModel->nuevoSite($id, $site->{'name'}, $nombre, null);
		$this->PortalModel->inicializarPortal($site->{'name'});

		$id = $this->ObtenerUltimoIdModel->obtenerUltimoId('tbl_informes');
		$this->InformesModel->inicializarEmail($id, $site->{'name'});

		$data = $this->MostrarRecargarDatos();

		echo json_encode(array(true, $data));
	}

	function ActualizarNombre()
	{
		$datos = $this->input->post('datos');

		$id = $datos['ID'];
		$nombre = $datos['siteDesc'];

		$this->SitesModel->ActualizarNombre($id, $nombre);

		$data = $this->MostrarRecargarDatos();

		echo json_encode(array(true, $data));
	}

	function MostrarRecargarDatos()
	{

		$sites = $this->SitesModel->getSites();

		foreach ($sites as $item) {
			$item->{'-'} = '
			<div class="dropdown" style="position: static;">
			<button class="dropbtn"><i class="fas fa-ellipsis-vertical"></i></button>
			<div class="dropdown-content" style="cursor:pointer">
				<a onclick="ClicEditarSite(' . $item->ID . ')" data-toggle="modal" data-target="#modalSitesEditar">Editar</a>
			  	<a onclick="ClicEliminarSite(' . $item->ID . ')" >Eliminar</a>
			</div>
		  </div> ';
		}

		return $sites;
	}

	public function ObtenerListadoSites()
	{
		$controllerurl = 'https://hermes01.tecnologiasencilla.com:8443';
		$user = 'admin';
		$password = 'Tecn0sencilla';
		$site = '248s9dpw'; //TestSite
		$version = '8.1.113';

		require_once 'vendor/autoload.php';

		$unifi_connection = new Client(
			$user,
			$password,
			$controllerurl,
			$site,
			$version
		);

		$loginresults   = $unifi_connection->login();
		$sites = $unifi_connection->list_sites();

		return $sites;
	}
}
