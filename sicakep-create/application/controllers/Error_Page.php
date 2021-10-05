<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Error_Page extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
    $this->load->view('error/404_view');
	}
}

?>
