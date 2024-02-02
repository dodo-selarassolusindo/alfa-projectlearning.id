<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

	// public function __construct()
	// {
	// 	parent::__construct();
	// 	if (!$this->ion_auth->logged_in())
	// 	{
	// 		// redirect them to the login page
	// 		redirect('auth/login', 'refresh');
	// 	}
	// }

	public function index()
	{
		// if (!$this->ion_auth->logged_in()) {
		// 	redirect(site_url().'auth/login');
		// } else {
			// $this->load->view('welcome_message');
			$data['_sub_judul'] = 'Dashboard';
			$data['_judul'] = 'Project Learning Membership';
			$data['_view'] = 'welcome/welcome_message_list';
	    	// $data['_caption'] = 'Dashboard';
	    	$this->load->view('welcome/welcome_message', $data);
		// }
	}

	public function index0()
	{
		$this->load->view('welcome_message');
	}
}