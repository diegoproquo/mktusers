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
        $this->load->view('login/show');
        $this->load->view('plantillas/login/footer');
    }


    public function iniciarSesion()
    {

        $user = $this->input->post('user');
        $pass = $this->input->post('pass');

        require_once $_ENV['AUTOLOAD'];

        try {

            if ($_ENV['USER'] == $user && $_ENV['PASS'] == $pass) {

                $session_data = array(
                    'logged_in' => true
                );

                $this->session->set_userdata($session_data);
                redirect(base_url() . "Dashboard");
                return;
                
            } else {
                $this->session->set_flashdata('error', 'Usuario o contraseña incorrectos');
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
