<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller {


	public function index()
	{
		$this->make_bread->add('');
		$this->make_bread->add('Beranda');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=0;

		$data['title']='Sistem Informasi Capaian Kinerja Pegawai';
		$data['page']='beranda';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}
}
