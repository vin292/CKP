<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ppk extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('isLogin')) {
				redirect('login.html');
		}else{
      $this->load->helper('tanggal');
      $this->load->model('pegawai/pegawai_model','pegawai');
      $this->load->model('kegiatan/kegiatan_model','kegiatan');
			$this->load->model('skp/ppk_model','ppk');
      $this->load->model('skp/skp_model','skp');
			$this->load->model('fungsional/fungsional_model','fungsional');
		}
	}

  public function index()  {
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Penilaian Prestasi Kerja');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=5;
		$data['title']='Penilaian Prestasi Kerja Pegawai';
		$data['pk']=$this->ppk->get_pk($this->session->userdata('niplama'))->row();
		$data['pk_row']=$this->ppk->get_pk($this->session->userdata('niplama'))->num_rows();
		$data['ppk']=$this->ppk->get_ppk($this->session->userdata('niplama'))->result();
		$data['ppk_row']=$this->ppk->get_ppk($this->session->userdata('niplama'))->num_rows();
		$data['Fungsional']=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->row();
		$data['Fungsional_row']=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->num_rows();
		$ppk=$this->ppk->get_ppk($this->session->userdata('niplama'))->result();
		$nilai=0;
		$count=0;
		foreach ($ppk as $row) {
				$nilai+=((($row->realisasi_kuantitas/$row->target_kuantitas)+($row->realisasi_kualitas/$row->target_kualitas))/2)*100;
				$count++;
		}
		$data['nilai']=$nilai;
		$data['count']=$count;
		$data['page']='skp/ppk_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}

  public function perilaku_kerja(){
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Penilaian Prestasi Kerja', 'penilaian-prestasi-kerja.html');
		$this->make_bread->add('Penilaian Perilaku Kerja Pegawai');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=5;
		$lvl = $this->session->userdata('lvl');

		if(strcmp($this->session->userdata('lvl'), '1')==0){
			$data['pegawai']=$this->pegawai->get_pegawai_es3();
			$data['page']='skp/perilaku_kerjakasi_view';
		}else if(strcmp($this->session->userdata('lvl'), '2')==0){
			if(strcmp(substr($this->session->userdata('satker'), 2, 2), '00')==0){
				$data['pegawai']=$this->pegawai->get_pegawai_es4();
				$data['page']='skp/perilaku_kerjakasi_view';
			}else{
				$data['pegawai']=$this->pegawai->get_pegawai_es4_ksk();
				$data['page']='skp/perilaku_kerjakasi_view';
			}
		}else{
			$data['pegawai']=$this->pegawai->get_pegawai_by_org_jab($this->session->userdata('organisasi'), $this->session->userdata('lvl'));
			$data['page']='skp/perilaku_kerja_view';
		}


		$this->load->vars($data);
		$this->load->view('template/layout');
	}

	public function simpan_ppk(){
		$data = array(
					'niplama' => $this->input->post('niplama'),
					'orientasi_pelayanan' => $this->input->post('orientasi_pelayanan'),
					'integritas' => $this->input->post('integritas'),
					'komitmen' => $this->input->post('komitmen'),
					'disiplin' => $this->input->post('disiplin'),
					'kerjasama' => $this->input->post('kerjasama'),
					'kepemimpinan' => $this->input->post('kepemimpinan'),
					'tgl_ppk' => $this->input->post('tgl_ppk'),
					'tgl_buat' => date('Y-m-d'),
					);
		$insert = $this->ppk->tambah_ppk($data);
		 echo json_encode(array("status" => TRUE));
	}

	public function list_pk()
	{
	$list = $this->ppk->get_datatables();
	$data = array();
	$no = $_POST['start'];
	foreach ($list as $pk) {
		$no++;
		$row = array();
		$row[] = $no;
		$row[] = $pk->gelar_depan.' '.$pk->nama.' '.$pk->gelar_belakang;
		$row[] = tgl_indo($pk->tgl_ppk);
		$row[] = $pk->orientasi_pelayanan;
		$row[] = $pk->integritas;
		$row[] = $pk->komitmen;
		$row[] = $pk->disiplin;
		$row[] = $pk->kerjasama;
		if( strcmp($this->session->userdata('lvl'), '1')== 0 || strcmp($this->session->userdata('lvl'), '2')== 0){
			$row[] = $pk->kepemimpinan;
		}

		//add html for action
		$row[] = '<a class="btn btn-sm btn-primary noborder" href="javascript:void(0)" title="Edit" style="width:75px; margin-bottom:5px" onclick="ubah_pk('."'".$pk->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Data</a>
				<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" style="width:75px; margin-bottom:5px" title="Hapus" onclick="hapus_pk('."'".$pk->id."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';

		$data[] = $row;
	}

	$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->ppk->count_all(),
					"recordsFiltered" => $this->ppk->count_filtered(),
					"data" => $data,
			);
	//output to json format
	echo json_encode($output);
	}

	public function tambah_pk()
	{
	if( strcmp($this->session->userdata('lvl'), '1')== 0 || strcmp($this->session->userdata('lvl'), '2')== 0 ){
		$data = array(
			'niplama' => $this->input->post('niplama'),
			'tgl_ppk' => $this->input->post('tgl_ppk'),
			'orientasi_pelayanan' => $this->input->post('orientasi_pelayanan'),
			'integritas' => $this->input->post('integritas'),
			'komitmen' => $this->input->post('komitmen'),
			'disiplin' => $this->input->post('disiplin'),
			'kerjasama' => $this->input->post('kerjasama'),
			'kepemimpinan' => $this->input->post('kepemimpinan'),
			'tgl_buat' => date('Y-m-d'),
			);
	}else{
	$data = array(
		'niplama' => $this->input->post('niplama'),
		'tgl_ppk' => $this->input->post('tgl_ppk'),
		'orientasi_pelayanan' => $this->input->post('orientasi_pelayanan'),
		'integritas' => $this->input->post('integritas'),
		'komitmen' => $this->input->post('komitmen'),
		'disiplin' => $this->input->post('disiplin'),
		'kerjasama' => $this->input->post('kerjasama'),
		'tgl_buat' => date('Y-m-d'),
		);
	}
	$insert = $this->ppk->tambah_pk($data);
	echo json_encode(array("status" => TRUE));
	}
	public function ubah_pk()
	{
	$data = array(
		'id' => $this->input->post('id'),
		'niplama' => $this->input->post('niplama'),
		'tgl_ppk' => $this->input->post('tgl_ppk'),
		'orientasi_pelayanan' => $this->input->post('orientasi_pelayanan'),
		'integritas' => $this->input->post('integritas'),
		'komitmen' => $this->input->post('komitmen'),
		'disiplin' => $this->input->post('disiplin'),
		'kerjasama' => $this->input->post('kerjasama'),
		);
	$insert = $this->ppk->ubah_pk(array('id' => $this->input->post('id')), $data);
	echo json_encode(array("status" => TRUE));
	}

	public function get_pk($id)
	{
	$data = $this->ppk->get_by_id($id);
	echo json_encode($data);
	}
	public function hapus_pk($id)
	{
	$this->ppk->delete_by_idpk($id);
	echo json_encode(array("status" => TRUE));
	}

}

?>
