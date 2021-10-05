<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('isLogin')) {
				redirect('login.html');
		}else{
			$this->load->model('pegawai/pegawai_model','pegawai');
			$this->load->model('fungsional/fungsional_model','fungsional');
		}
	}

	public function profil()
	{
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Profil Pegawai');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=2;
		$data['pegawai']=$this->pegawai->get_pegawai_detil_by_id($this->session->userdata('niplama'));
		$pegawai = $this->pegawai->get_pegawai_detil_by_id($this->session->userdata('niplama'));
		$fungsional_row=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->num_rows();
		$fungsional=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->row();
		$pangkat=$pegawai->pangkat.', '.$pegawai->n_gol.'';
		$jabatan='';
		if($fungsional_row>0){
			if(strcmp($fungsional->kode_jabatan_to_jenjang, 'A0101')==0){
				$jabatan ="Statistisi Pelaksana Pemula";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0102')==0) {
				$jabatan ="Statistisi Pelaksana";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0103')==0) {
				$jabatan ="Statistisi Pelaksana Lanjutan";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0104')==0) {
				$jabatan ="Statistisi Penyelia";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0105')==0) {
				$jabatan ="Statistisi Pertama";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0106')==0) {
				$jabatan ="Statistisi Muda";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0107')==0) {
				$jabatan ="Statistisi Madya";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0108')==0) {
				$jabatan ="Statistisi Utama";
			}
		}else{
			if(strcmp($pegawai->id_level, '1')==0){
				$jabatan ="Kepala ".$pegawai->nm_satker;
			}else if(strcmp($pegawai->id_level, '2')==0){
				if(strcmp(substr($pegawai->id_satker, -2), '00')==0){
					$jabatan ="Kepala ".$pegawai->nm_org;
				}else{
					$jabatan ="Kepala ".$pegawai->nm_satker;
				}
			}else if(strcmp($pegawai->id_level, '3')==0){
				$jabatan ="Kepala ".$pegawai->nm_org;
			}else{
				if(strcmp($pegawai->id_org, '92870')==0){
					$jabatan =$pegawai->nm_org;
				}else{
					$jabatan ="Staff ".$pegawai->nm_org;
				}
			}
		}
		$data['title']='Profil Pegawai';
		$data['jabatan']=$jabatan;
		$data['pangkat']=$pangkat;
		$data['page']='user/profil_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}

	public function ubah_password()
	{
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Ubah Password');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['title']='Ubah Password Pegawai';
		$data['menu']=2;
		$data['page']='user/ubahpassword_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}

}

?>
