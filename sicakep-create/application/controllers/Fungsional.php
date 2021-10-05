<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fungsional extends CI_Controller {

	public function __construct(){
		parent::__construct();
			if(!$this->session->userdata('fungsional')==TRUE) {
					redirect('login.html');
			}else{
				$this->load->library('encrypt');
				$this->load->helper('tanggal');
				$this->load->model('pegawai/pegawai_model','pegawai');
				$this->load->model('fungsional/fungsional_model', 'fungsional');
			}
	}

	public function pak()
	{
		$this->load->library('make_bread');
		$this->make_bread->add('');
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Pengajuan Angka Kredit');
		$Fungsional=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->row();
		$jabatan ="";
		$jb_fung= "";
		if(strcmp($Fungsional->kode_jabatan_to_jenjang, 'A0101')==0){
			$jb_fung ="Statistisi Pelaksana Pemula";
		}elseif (strcmp($Fungsional->kode_jabatan_to_jenjang, 'A0102')==0) {
			$jb_fung ="Statistisi Pelaksana";
		}elseif (strcmp($Fungsional->kode_jabatan_to_jenjang, 'A0103')==0) {
			$jb_fung ="Statistisi Pelaksana Lanjutan";
		}elseif (strcmp($Fungsional->kode_jabatan_to_jenjang, 'A0104')==0) {
			$jb_fung ="Statistisi Penyelia";
		}elseif (strcmp($Fungsional->kode_jabatan_to_jenjang, 'A0105')==0) {
			$jb_fung ="Statistisi Pertama";
		}elseif (strcmp($Fungsional->kode_jabatan_to_jenjang, 'A0106')==0) {
			$jb_fung ="Statistisi Muda";
		}elseif (strcmp($Fungsional->kode_jabatan_to_jenjang, 'A0107')==0) {
			$jb_fung ="Statistisi Madya";
		}elseif (strcmp($Fungsional->kode_jabatan_to_jenjang, 'A0108')==0) {
			$jb_fung ="Statistisi Utama";
		}
		$data['jb_fung']=$jb_fung;
		$data['pegawai']=$this->pegawai->get_pegawai_by_id($this->session->userdata('niplama'));
		$data['gol']=$this->fungsional->ambil_gol_bynip($this->session->userdata('niplama'))->row();
		$data['satker']=$satker=$this->pegawai->get_satker_by_nip($this->session->userdata('niplama'));
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=7;
		$data['page']='fungsional/pak_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}

	public function peraturan_statistisi()
	{
		$this->load->library('make_bread');
		$this->make_bread->add('');
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Peraturan Statistisi');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=11;
		$data['page']='fungsional/peraturan_statistisi_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}

	public function tunjangan_statistisi()
	{
		$this->load->library('make_bread');
		$this->make_bread->add('');
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Peraturan Statistisi', 'peraturan-statistisi.html');
		$this->make_bread->add('Tunjangan Statistisi');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=11;
		$data['page']='fungsional/tunjangan_statistisi_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}

	public function jabatan_statistisi()
	{
		$this->load->library('make_bread');
		$this->make_bread->add('');
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Peraturan Statistisi', 'peraturan-statistisi.html');
		$this->make_bread->add('Jabatan Fungsional Statistisi dan Angka Kreditnya');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=11;
		$data['page']='fungsional/jabatan_statistisi_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}

	public function pelaksanaan_jabatan_statistisi()
	{
		$this->load->library('make_bread');
		$this->make_bread->add('');
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Peraturan Statistisi', 'peraturan-statistisi.html');
		$this->make_bread->add('Jabatan Fungsional Statistisi dan Angka Kreditnya');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=11;
		$data['page']='fungsional/pelaksanaan_jabatan_statistisi_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}

	public function petunjuk_jabatan_statistisi()
	{
		$this->load->library('make_bread');
		$this->make_bread->add('');
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Peraturan Statistisi', 'peraturan-statistisi.html');
		$this->make_bread->add('Jabatan Fungsional Statistisi dan Angka Kreditnya');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=11;
		$data['page']='fungsional/petunjuk_jabatan_statistisi_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}

}

?>
