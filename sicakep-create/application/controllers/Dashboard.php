<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('isLogin')) {
				redirect('login.html');
		}else{
			$this->load->helper('tanggal');
			$this->load->model('dashboard/Dashboard_model','dashboard');
		}
	}

	public function index()
	{
        $this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Dashboard Proses Pengisian Capaian Kinerja Pegawai');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=36;
		$data['title']='Capaian Kinerja Pegawai';
		$bulant=bulan(date('m'));
		if(date('m')==1){
			$bulanr=bulan(12);
			$yearr=date('Y')-1;
		}else{
			$bulanr=bulan(date('m')-1);
			$yearr=date('Y');
		}
		$data['bulant']=$bulant;
		$data['bulanr']=$bulanr;
		$data['target']=$this->dashboard->get_entri_target($bulant);
		
		$data['realisasi']=$this->dashboard->get_entri_realisasi($bulanr, $yearr); //change
		$data['target_t']=$this->dashboard->get_list_target($bulant)->num_rows();
		
		$data['realisasi_t']=$this->dashboard->get_list_realisasi($bulanr, $yearr)->num_rows(); //change
		$data['target_']=$this->dashboard->get_list_target($bulant)->result();
		
		$data['realisasi_']=$this->dashboard->get_list_realisasi($bulanr, $yearr)->result(); //change
		$data['page']='dashboard/dashboard_view';
		
		// print_r($data);
        $this->load->vars($data);
		$this->load->view('template/layout');
    }

}