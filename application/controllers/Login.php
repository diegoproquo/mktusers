<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \RouterOS\Config;
use \RouterOS\Client;
use \RouterOS\Query;

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index()
    {
        $this->load->view('plantillas/login/header');
        $this->load->view('login/show2');
        $this->load->view('plantillas/login/footer');
    }


    public function iniciarSesion()
    {
        $ipadress = $this->input->post('ipadress');
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        require_once 'C:\Proyectos\mktusers\vendor\autoload.php';

        try {
            $config = (new Config())
                ->set('timeout', 5)
                ->set('host', $ipadress)
                ->set('user', $username)
                ->set('pass', $password);

            $client = new Client($config);

            $conexion = $client->connect();

            if ($conexion == true) {
                    $session_data = array(
                        'ipadress'  => $ipadress,
                        'username'  => $username,
                        'password'  => $password,
                        'logged_in' => true
                    );

                    $this->session->set_userdata($session_data);
                    redirect(base_url() . "Dashboard");
                    return;
                
            } else {
                $this->session->set_flashdata('error', 'No ha sido posible conectarse con el Mikrotik');
                redirect(base_url() . "Login");
                return;
            }
        } catch (\Exception $e) {
            $this->session->set_flashdata('error', "Error: " . $e->getMessage() . "\n");
            redirect(base_url() . "Login");
            return;
        }
    }

    public function logout()
    {
        session_destroy();
        redirect(base_url() . "Login");
        return;
    }
}
