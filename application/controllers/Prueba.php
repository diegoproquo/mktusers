<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use UniFi_API\Client;

class Prueba extends CI_Controller {

	public function __construct() {
        parent::__construct();
        
        $this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->model("SitesModel");
		
		if (!$this->session->userdata('logged_in')) {
			redirect(base_url()."Login");
			return;
		}
    }

	public function index()
	{
		$site = $this->input->get('site');
		if($site == "" || $site == null) redirect(base_url()."Login");
		else $site = $this->SitesModel->getSitePorSite_Id($site);

		$this->load->view('plantillas/header', array('site_id' => $site->SITE_ID, 'site_nombre' => $site->NOMBRE));
		$this->load->view('prueba/show');
		$this->load->view('plantillas/footer');
	}


	public function PruebaConexionUnifi(){

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
		$dataUnifi           = $unifi_connection->list_sites();
		
		echo json_encode($dataUnifi);
		
	}


}
