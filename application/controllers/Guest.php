<?php
defined('BASEPATH') or exit('No direct script access allowed');

use UniFi_API\Client;

class Guest extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("SitesModel");
        $this->load->model("PortalModel");
        $this->load->model("ConexionesModel");
        $this->load->model("ObtenerUltimoIdModel");
    }

    public function s($site_id = "")
    {

        $data['datosPeticion']['site_id'] = $site_id;

        // Obtener los datos de la cabecera HTTP
        $data['datosPeticion']['ap_mac'] = $this->input->get('ap');
        $data['datosPeticion']['client_mac'] = $this->input->get('id');
        $data['datosPeticion']['url'] = $this->input->get('url');
        $data['datosPeticion']['ssid'] = $this->input->get('ssid');

        $data['portal'] = $this->PortalModel->uno($site_id);
		$data['previsualizar'] = false; // Sirve para desactivar el form en la opcion previsualizar

        $this->load->view('portal/show', $data);
    }


    public function login()
    {
        // Acceder a los datos del formulario
        $email = $this->input->post('email');
        $nombre = $this->input->post('nombre');
        $apellidos = $this->input->post('apellidos');
        $checkboxTerminos = $this->input->post('checkboxTerminos');

        $site_id = $this->input->post('site_id');
        $ap_mac = $this->input->post('ap_mac');
        $client_mac = $this->input->post('client_mac');
        $ssid = $this->input->post('ssid');
        $url = $this->input->post('url');

        //return var_dump($url);

        // Realizar la autorización del cliente en el controlador UniFi
        $controllerurl = 'https://hermes01.tecnologiasencilla.com:8443';
        $user = 'admin';
        $password = 'Tecn0sencilla';
        $site = $site_id;
        $version = '8.1.113';
        require_once 'vendor/autoload.php';
        $unifi_connection = new Client(
            $user,
            $password,
            $controllerurl,
            $site,
            $version
        );
        $loginresults = $unifi_connection->login();

        //Aplicamos configuracion de tiempo de autorizacion
        $portalConfig = $this->PortalModel->getConfiguracionPortal($site_id);
        // Si tiene desactivado el limite de tiempo o ha guardado 0, aplicamos 8 horas por defecto, si no, aplicamos el limite introducido en BD
        if ($portalConfig[0]['LIMITE_SESION'] == "0" || $portalConfig[0]['LIMITE_SESION_MINUTOS'] == "0") $limiteTimepo = 480; //8 horas
        else $limiteTimepo = $portalConfig[0]['LIMITE_SESION_MINUTOS'];

        // Si hay especificada una redireccion, reemplazamos el valor de la variable $url por el registro de la BD
        if ($portalConfig[0]['REDIRECCION'] != null && $portalConfig[0]['REDIRECCION'] != "") {
            if (strpos($url, "apple") !== false) $url = $portalConfig[0]['REDIRECCION']; //Si es un dispositivo iOS
        }

        if ($loginresults == true) {
            $clienteAutorizado = $unifi_connection->authorize_guest($client_mac, $limiteTimepo, null, null, null, $ap_mac);
            if ($clienteAutorizado == true) {
                $id = $this->ObtenerUltimoIdModel->obtenerUltimoId('tbl_conexiones');
                $fabricante = $this->DevolverFabricante($client_mac);
                $this->ConexionesModel->nuevaConexion($id, $email, $nombre, $apellidos, $ap_mac, $client_mac, $site_id, $ssid, $fabricante, date('Y-m-d H:i:s'));
                $unifi_connection->logout();
                redirect($url . '?timestamp=' . time());
                return;
            } else {
                $loginresults = $unifi_connection->logout();
                $this->session->set_flashdata('errorPortal', 'Error en la autorización del cliente');
                redirect(current_url());
                return;
            }
        } else {
            $loginresults = $unifi_connection->logout();
            $this->session->set_flashdata('errorPortal', 'Error en la conexion con el controlador Unifi');
            redirect(current_url());
            return;
        }
    }

    // Devuelve el fabricante del dispositivo en funcion de la mac.
    // El plan gratuito son 1000 entradas al dia y maximo 2 por segundo
    function DevolverFabricante($mac_address)
    {
        $url = "https://api.macvendors.com/" . urlencode($mac_address);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $fabricante = curl_exec($ch);
        return $fabricante;
    }
}
