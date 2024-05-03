<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Perfiles extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');

		/*if (!$this->session->userdata('logged_in')) {
			redirect(base_url() . "Login");
			return;
		}*/

	}

	public function show()
	{
		$this->load->view('perfiles/show');
	}
}
