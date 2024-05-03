<?php
defined('BASEPATH') or exit('No direct script access allowed');

use UniFi_API\Client;

class Portal extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model("SitesModel");
		$this->load->model("PortalModel");

		if (!$this->session->userdata('logged_in')) {
			redirect(base_url() . "Login");
			return;
		}

	}

	public function show()
	{

		$site = $this->input->get('site');
		if (!$this->SitesModel->comprobarSite($site)) redirect(base_url() . "Login");
		if(!$this->session->userdata('adm') && $this->session->userdata('site_id') != $site) redirect(base_url() . "Login");
		else $site = $this->SitesModel->getSitePorSite_Id($site);

		$data['portal'] = $this->PortalModel->uno($site->SITE_ID);
		$data['color'] = 'rgba(1,1,1,0.7)';
		$data['previsualizar'] = true; // Sirve para desactivar el form en la opcion previsualizar
		$data['datosPeticion'] = $site->SITE_ID; //Si no incluimos esta variable peta

		$this->load->view('portal/show', $data);
	}

	public function newEditar()
	{

		$site = $this->input->get('site');
		if (!$this->SitesModel->comprobarSite($site)) redirect(base_url() . "Login");
		if(!$this->session->userdata('adm') && $this->session->userdata('site_id') != $site) redirect(base_url() . "Login");
		else $site = $this->SitesModel->getSitePorSite_Id($site);

		$data['portal'] = $this->PortalModel->uno($site->SITE_ID);

		$this->load->view('plantillas/header', array('site_id' => $site->SITE_ID, 'site_nombre' => $site->NOMBRE));
		$this->load->view('portal/newEditar', $data);
		$this->load->view('plantillas/footer');
	}

	public function plantilla()
	{

		$site = $this->input->get('site');
		if (!$this->SitesModel->comprobarSite($site)) redirect(base_url() . "Login");
		if(!$this->session->userdata('adm') && $this->session->userdata('site_id') != $site) redirect(base_url() . "Login");
		else $site = $this->SitesModel->getSitePorSite_Id($site);

		$this->load->view('portal/custom');
	}
	public function plantilla2()
	{

		$site = $this->input->get('site');
		if (!$this->SitesModel->comprobarSite($site)) redirect(base_url() . "Login");
		if(!$this->session->userdata('adm') && $this->session->userdata('site_id') != $site) redirect(base_url() . "Login");
		else $site = $this->SitesModel->getSitePorSite_Id($site);

		$this->load->view('portal/custom2');
	}

	public function plantilla3()
	{

		$site = $this->input->get('site');
		if (!$this->SitesModel->comprobarSite($site)) redirect(base_url() . "Login");
		if(!$this->session->userdata('adm') && $this->session->userdata('site_id') != $site) redirect(base_url() . "Login");
		else $site = $this->SitesModel->getSitePorSite_Id($site);

		$this->load->view('portal/custom3');
	}

	
	public function plantilla4()
	{

		$site = $this->input->get('site');
		if (!$this->SitesModel->comprobarSite($site)) redirect(base_url() . "Login");
		if(!$this->session->userdata('adm') && $this->session->userdata('site_id') != $site) redirect(base_url() . "Login");
		else $site = $this->SitesModel->getSitePorSite_Id($site);

		$this->load->view('portal/custom4');
	}



	public function guardarEditar()
	{

		$site_id = $this->input->post('site_id');
		$titulo = $this->input->post('titulo');
		$usar_titulo = $this->input->post('usar_titulo');
		$texto = $this->input->post('texto');
		$usar_texto = $this->input->post('usar_texto');
		$textoBoton = $this->input->post('textoBoton');
		$color = $this->input->post('color');
		$colorSecundario = $this->input->post('colorSecundario');
		$colorTerciario = $this->input->post('colorTerciario');
		$tamano_logo = $this->input->post('tamano_logo');
		$radio_esquinas = $this->input->post('radio_esquinas');
		$usar_imagen = $this->input->post('usar_imagen');
		$opacidad_fondo = $this->input->post('opacidad_fondo');
		$checkboxEmail = $this->input->post('checkboxEmail');
		$checkboxNombre = $this->input->post('checkboxNombre');
		$checkboxApellidos = $this->input->post('checkboxApellidos');
		$redireccion = $this->input->post('redireccion');
		$limiteSesion = $this->input->post('limiteSesion');
		$limite_sesion_minutos = $this->input->post('limite_sesion_minutos');
		$terminos = $this->input->post('terminos');
		$imagen = $this->input->post('imagen');
		$imagenFondo = $this->input->post('imagenFondo');
		$terminosArchivo = $this->input->post('terminosArchivo');


		// Inicializar libreria para guardar archivos
		$pathImagen = 'uploads/logo/' . $site_id;
		if (!is_dir($pathImagen)) {
			mkdir($pathImagen, 0777, true);
		}
		$config['upload_path'] = $pathImagen;
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_width'] = 900;
		$config['max_height'] = 900;
		$config['max_size'] = 50000; //kb

		$this->load->library('upload', $config);

		// Inicializamos variables de error
		$errorImagen = "";
		$errorImagenFondo = "";
		$errorTerminos = "";

		//Guardar logo
		if ($imagen != "undefined") {
			if (!$this->upload->do_upload('imagen')) { // Si la imagen no cumple con las condiciones o algo falla
				$errorImagen = array('error' => $this->upload->display_errors());
				$portal = $this->PortalModel->uno($site_id);
				echo json_encode(array($portal, $errorImagen, $errorImagenFondo, $errorTerminos));
				return;
			} else {
				$dataImagen = $this->upload->data();
				$dir_imagen = $dataImagen['file_path'] . $dataImagen['file_name'];
				$url_imagen = base_url() . $pathImagen . '/' . $dataImagen['file_name'];

				$this->PortalModel->guardarImagenUrl($site_id, $url_imagen, $dir_imagen);
			}
		}

		//Guardar imagen fondo
		if ($imagenFondo != "undefined") {

			//Inicializamos la libreria con los nuevos ajustes
			$config['max_width'] = 10000;
			$config['max_height'] = 10000;
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('imagenFondo')) { // Si la imagen no cumple con las condiciones o algo falla
				$errorImagenFondo = array('error' => $this->upload->display_errors());
				$portal = $this->PortalModel->uno($site_id);
				echo json_encode(array($portal, $errorImagen, $errorImagenFondo, $errorTerminos));
				return;
			} else {
				$dataImagenFondo = $this->upload->data();
				$dir_imagen_fondo = $dataImagenFondo['file_path'] . $dataImagenFondo['file_name'];
				$url_imagen_fondo = base_url() . $pathImagen . '/' . $dataImagenFondo['file_name'];

				$this->PortalModel->guardarImagenFondoUrl($site_id, $url_imagen_fondo, $dir_imagen_fondo);
			}
		}


		//Guardar archivo de terminos y condiciones
		if ($terminosArchivo != "undefined") {

			//Inicializar libreria con nuevos ajustes
			$pathTerminos = 'uploads/terminos/' . $site_id;
			if (!is_dir($pathTerminos)) {
				mkdir($pathTerminos, 0777, true);
			}
			$config['allowed_types'] = 'pdf';
			$config['upload_path'] = $pathTerminos;
			$config['max_size'] = 100000; //kb
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('terminosArchivo')) { // Si el archivo no cumple con las condiciones o algo falla
				$errorTerminos = array('error' => $this->upload->display_errors());
				$portal = $this->PortalModel->uno($site_id);
				echo json_encode(array($portal, $errorImagen, $errorImagenFondo, $errorTerminos));
				return;
			} else {
				$dataTerminos = $this->upload->data();
				$dir_terminos = $dataTerminos['file_path'] . $dataTerminos['file_name'];
				$url_terminos = base_url() . $pathTerminos . '/' . $dataTerminos['file_name'];

				$this->PortalModel->guardarUrlTerminos($site_id, $url_terminos, $dir_terminos);
			}
		}

		// Guardamos los cambios en BD de datos si no ha habido ningun error con los archivos
		$this->PortalModel->guardarCambios($site_id, $titulo, $usar_titulo, $texto, $usar_texto, $textoBoton, $color, $colorSecundario, $colorTerciario, $tamano_logo, $radio_esquinas, $usar_imagen, $opacidad_fondo, $redireccion, $terminos, $limiteSesion, $limite_sesion_minutos, $checkboxEmail, $checkboxNombre, $checkboxApellidos);

		$portal = $this->PortalModel->uno($site_id);
		echo json_encode(array($portal, $errorImagen, $errorImagenFondo, $errorTerminos));
	}
}
