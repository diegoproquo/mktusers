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



        $host = $this->input->post('host');
        $user = $this->input->post('user');
        $pass = $this->input->post('pass');

		require_once $_ENV['AUTOLOAD'];

        try {

            $config = (new Config())
                ->set('timeout', 5)
                ->set('host', $host)
                ->set('user', $user)
                ->set('pass', $pass);

            $client = new Client($config);

            $conexion = $client->connect();

            if ($conexion == true) {
                $session_data = array(
                    'host'  => $host,
                    'user'  => $user,
                    'pass'  => $pass,
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
