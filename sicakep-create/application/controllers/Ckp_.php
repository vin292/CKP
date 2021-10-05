<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ckp extends CI_Controller {
	var $table = 'ckp';

	var $column_order = array('niplama','id_keg','target','tgl_buat','tgl_ckp', null);
	var $column_search = array('niplama','id_keg','target','tgl_buat','tgl_ckp');
	var $order = array('tgl_buat' => 'desc');

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('isLogin')) {
				redirect('login.html');
		}else{
				$this->load->helper('tanggal');
				$this->load->model('ckp/ckp_model','ckp');
				$this->load->model('skp/skp_model','skp');
				$this->load->model('ckp/target_model','target');
				$this->load->model('ckp/realization_model','realization');
				$this->load->model('kegiatan/kegiatan_model','kegiatan');
				$this->load->model('pegawai/pegawai_model','pegawai');
				$this->load->model('fungsional/fungsional_model','fungsional');
			}
	}

  public function index()
  {
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Target Capaian Kinerja Pegawai');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=6;
		$data['title']='Capaian Kinerja Pegawai';
		$data['m']=$this->uri->segment(2);
		$m=$this->uri->segment(2);
		if($m==''){
			$m=date("m");
		}
		$jabatan ="pertama";
		$fungsional=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->row();
		$isfungsional=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->num_rows();
		$jb_fung= "";
		if($isfungsional>0){
			if(strcmp($fungsional->kode_jabatan_to_jenjang, 'A0101')==0){
				$jabatan ="pelaksana_pemula";
				$jb_fung ="Statistisi Pelaksana Pemula";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0102')==0) {
				$jabatan ="pelaksana";
				$jb_fung ="Statistisi Pelaksana";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0103')==0) {
				$jabatan ="pelaksana_lanjutan";
				$jb_fung ="Statistisi Pelaksana Lanjutan";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0104')==0) {
				$jabatan ="penyelia";
				$jb_fung ="Statistisi Penyelia";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0105')==0) {
				$jabatan ="pertama";
				$jb_fung ="Statistisi Pertama";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0106')==0) {
				$jabatan ="muda";
				$jb_fung ="Statistisi Muda";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0107')==0) {
				$jabatan ="madya";
				$jb_fung ="Statistisi Madya";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0108')==0) {
				$jabatan ="utama";
				$jb_fung ="Statistisi Utama";
			}
		}
		$data['jb_fung']=$jb_fung;
		$data['ckp']=$this->ckp->get_ckp2_bynip($m, $this->session->userdata('niplama'), $jabatan)->result();
		$data['ckp_row']=$this->ckp->get_ckp2_bynip($m, $this->session->userdata('niplama'), $jabatan)->num_rows();
		$data['ckpt']=$this->ckp->get_ckpt2_bynip($m, $this->session->userdata('niplama'), $jabatan)->result();
		$data['ckpt_row']=$this->ckp->get_ckpt2_bynip($m, $this->session->userdata('niplama'), $jabatan)->num_rows();
		$sum_ak = $this->ckp->get_sum_ak_bynip($m, $this->session->userdata('niplama'), $jabatan);
		$data['sum_ak']= $sum_ak;
		$data['av_percentage']=$this->ckp->get_av_persentage_bynip($m, $this->session->userdata('niplama'));
		$data['av_kualitas']=$this->ckp->get_av_kualitas_bynip($m, $this->session->userdata('niplama'));
		$data['penilai']=$this->ckp->get_penilai();
		$data['pegawai']=$this->pegawai->get_pegawai_by_id($this->session->userdata('niplama'));
		$data['satker']=$this->pegawai->get_satker_by_nip($this->session->userdata('niplama'));
		$s_date = date("Y-".$m."-01");
		$e_date = date("Y-".$m."-t", strtotime($s_date));
		$data['periode']=tgl_indo($s_date).' s.d '.tgl_indo($e_date);
		$tanggal = strtotime(date("Y-m-d", strtotime($e_date)) . " " . "-1 month");
		$day = date('D', $tanggal);
		if(strcmp($day, "Sat")==0){
			$day = date('Y-m-d', strtotime('-1 day' , $tanggal));
			$data['penilaian_k']=tgl_indo($day);
		}else if(strcmp($day, "Sun")==0){
			$day = date('Y-m-d', strtotime('-2 day' , $tanggal));
			$data['penilaian_k']=tgl_indo(date($day));
		}else{
			$data['penilaian_k']=tgl_indo(date("Y-m-d", $tanggal));
		}
		//$data['penilaian_k']=tgl_indo(date("Y-m-d", strtotime(date("Y-m-d", strtotime($s_date)) . " " . "+1 month")));
		$data['bln']=bln_indo(date("Y-".$m."-d"));
		$data['page']='ckp/ckpt_view';
    $this->load->vars($data);
    $this->load->view('template/layout');
  }

  public function ckp_r()
  {
		$this->make_bread->add('Beranda','beranda');
		$this->make_bread->add('Realisasi Capaian Kinerja Pegawai');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=6;
		$data['title']='Realisasi Capaian Kinerja Pegawai';
		$data['m']=$this->uri->segment(2);
		$m=$this->uri->segment(2);
		if($m==''){
			$m=date("m");
		}
		$jabatan ="pertama";
		$fungsional=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->row();
		$isfungsional=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->num_rows();
		$jb_fung= "";
		if($isfungsional>0){
			if(strcmp($fungsional->kode_jabatan_to_jenjang, 'A0101')==0){
				$jabatan ="pelaksana_pemula";
				$jb_fung ="Statistisi Pelaksana Pemula";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0102')==0) {
				$jabatan ="pelaksana";
				$jb_fung ="Statistisi Pelaksana";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0103')==0) {
				$jabatan ="pelaksana_lanjutan";
				$jb_fung ="Statistisi Pelaksana Lanjutan";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0104')==0) {
				$jabatan ="penyelia";
				$jb_fung ="Statistisi Penyelia";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0105')==0) {
				$jabatan ="pertama";
				$jb_fung ="Statistisi Pertama";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0106')==0) {
				$jabatan ="muda";
				$jb_fung ="Statistisi Muda";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0107')==0) {
				$jabatan ="madya";
				$jb_fung ="Statistisi Madya";
			}elseif (strcmp($fungsional->kode_jabatan_to_jenjang, 'A0108')==0) {
				$jabatan ="utama";
				$jb_fung ="Statistisi Utama";
			}
		}
		$data['jb_fung']=$jb_fung;
		$data['ckp']=$this->ckp->get_ckp_bynip($m, $this->session->userdata('niplama'), $jabatan)->result();
		$data['ckp_row']=$this->ckp->get_ckp_bynip($m, $this->session->userdata('niplama'), $jabatan)->num_rows();
		$data['ckpt']=$this->ckp->get_ckpt_bynip($m, $this->session->userdata('niplama'), $jabatan)->result();
		$data['ckpt_row']=$this->ckp->get_ckpt_bynip($m, $this->session->userdata('niplama'), $jabatan)->num_rows();
		$sum_ak = $this->ckp->get_sum_ak_bynip($m, $this->session->userdata('niplama'), $jabatan);
		$data['sum_ak']= $sum_ak;
		$data['av_percentage']=$this->ckp->get_av_persentage_bynip($m, $this->session->userdata('niplama'));
		$data['av_kualitas']=$this->ckp->get_av_kualitas_bynip($m, $this->session->userdata('niplama'));
		$data['penilai']=$this->ckp->get_penilai();
		$data['pegawai']=$this->pegawai->get_pegawai_by_id($this->session->userdata('niplama'));
		$data['satker']=$this->pegawai->get_satker_by_nip($this->session->userdata('niplama'));
		$s_date = date("Y-".$m."-01");
		$e_date = date("Y-".$m."-t", strtotime($s_date));
		$data['periode']=tgl_indo($s_date).' s.d '.tgl_indo($e_date);
		$tanggal = strtotime(date("Y-m-d", strtotime($s_date)) . " " . "+1 month");
		$day = date('D', $tanggal);
		if(strcmp($day, "Sat")==0){
			$day = date('Y-m-d', strtotime('+2 day' , $tanggal));
			$data['penilaian_k']=tgl_indo($day);
		}else if(strcmp($day, "Sun")==0){
			$day = date('Y-m-d', strtotime('+1 day' , $tanggal));
			$data['penilaian_k']=tgl_indo($day);
		}else{
			$data['penilaian_k']=tgl_indo(date("Y-m-d", $tanggal));
		}
		//$data['penilaian_k']=tgl_indo(date("Y-m-d", strtotime(date("Y-m-d", strtotime($s_date)) . " " . "+1 month")));
		$data['bln']=bln_indo(date("Y-".$m."-d"));
		$data['page']='ckp/ckp_view';
    $this->load->vars($data);
    $this->load->view('template/layout');
  }

	public function entri_ckp_target()
	{
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Capaian Kinerja Pegawai','capaian-kinerja-pegawai.html');
		$this->make_bread->add('Entri CKP-T');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=6;
		$data['title']='Entri Capaian Kinerja Pegawai';
		if(strcmp($this->session->userdata('lvl'), '2')==0){
			$data['kegiatan']=$this->kegiatan->get_kegiatan();
			$data['pegawai']=$this->pegawai->get_pegawai_es4();
		}else{
			$data['kegiatan']=$this->kegiatan->get_kegiatan();
			$data['pegawai']=$this->pegawai->get_pegawai_by_org_jab($this->session->userdata('org'), $this->session->userdata('lvl'));
		}

		$data['page']='target/target_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}

	public function list_target($m)
	{

	$list = $this->target->get_datatables($m);
	$data = array();
	$no = $_POST['start'];
	foreach ($list as $target) {
		$no++;
		$row = array();
		$row[] = $target->nm_keg;
		$row[] = $target->target.' '.$target->satuan;
		/*if(is_null($target->status_target)){
			$row[] = '-';
		}else{
			$row[] = $target->realisasi.' '.$target->satuan;
		}
		$row[] = explode(" ", tgl_indo($target->tgl_ckp))[1];*/

		if(is_null($target->status_target)){
			$row[] = '<span class="label label-warning">Menunggu Persetujuan</span>';
			$row[] = '<a class="btn btn-sm btn-success noborder" style="min-width:70px; margin-bottom:5px" href="javascript:void(0)" title="Edit" onclick="ubah_target('."'".$target->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Data</a>
					<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" style="min-width:70px; margin-bottom:5px" title="Hapus" onclick="hapus_target('."'".$target->id."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';

		}else if($target->status_target=="1"){
			$row[] = '<span class="label label-success">Disetujui</span>';
			$row[] = '<a class="btn btn-sm btn-success noborder" style="min-width:70px; margin-bottom:5px; cursor:not-allowed;" href="javascript:void(0)"><i class="glyphicon glyphicon-pencil"></i> Data</a>
					<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" style="min-width:70px; margin-bottom:5px; cursor:not-allowed;" ><i class="glyphicon glyphicon-trash"></i> Hapus</a>';

		}else{
			$row[] = '<span class="label label-danger">Ditolak</span>';
			$row[] = '<a class="btn btn-sm btn-success noborder" style="min-width:70px; margin-bottom:5px; cursor:not-allowed;" href="javascript:void(0)" ><i class="glyphicon glyphicon-pencil"></i> Data</a>
					<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" style="min-width:70px; margin-bottom:5px; cursor:not-allowed;" ><i class="glyphicon glyphicon-trash"></i> Hapus</a>';

		}

		$data[] = $row;
	}

	$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->target->count_all($m),
					"recordsFiltered" => $this->target->count_filtered($m),
					"data" => $data,
			);
	//output to json format
	echo json_encode($output);
	}
	public function tambah_target()
	{
		date_default_timezone_set("Asia/Jakarta");
		$data = array(
				'niplama' => $this->input->post('niplama'),
				'target' => $this->input->post('target'),
				'realisasi' => '0',
				'kualitas' => '0',
				'tgl_buat' => date('Y-m-d'),
				'tgl_ubah' => date('Y-m-d'),
				'tgl_ckp' => $this->input->post('tgl_ckp'),
				'jenis' => $this->input->post('jenis'),
				'id_keg' => $this->input->post('id_keg'),
				'creator' => $this->session->userdata('niplama'),
				'status' => '0',
			);
		$insert = $this->target->tambah_target($data);
		echo json_encode(array("status" => TRUE));
	}
	public function ubah_target()
	{
		//$this->validasi_akun();
		$data = array(
			'target' => $this->input->post('target'),
			'satuan' => $this->input->post('satuan'),
			'tgl_ubah' => date('Y-m-d'),
			'jenis' => $this->input->post('jenis'),
			'nm_keg' => $this->input->post('nm_keg'),
			);
		$insert = $this->target->ubah_target(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}
	public function hapus_target($id)
	{
		$this->target->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	public function get_target($id)
	{
		$data = $this->target->get_target($id);
		echo json_encode($data);
	}

	public function input_target()
		{
			$this->make_bread->add('Beranda','beranda.html');
			$this->make_bread->add('Capaian Kinerja Pegawai', 'capaian-kinerja-pegawai.html');
			$this->make_bread->add('Entri CKP-T');
			$data['breadcrumb'] = $this->make_bread->output();
			$data['menu']=6;
			$data['title']='Entri Target Capaian Kinerja Pegawai';
			$niplama=$this->session->userdata('niplama');
			$data['keg_skp']=$this->skp->ambil_skp_bynip($niplama);
			$data['keg_lainnya']=$this->kegiatan->ambil_keg_bynip($niplama);
			$data['page']='target/input_target_view';
			$this->load->vars($data);
			$this->load->view('template/layout');
		}

		public function input_target_pegawai()
			{
				$this->make_bread->add('Beranda','beranda.html');
				$this->make_bread->add('Capaian Kinerja Pegawai', 'capaian-kinerja-pegawai.html');
				$this->make_bread->add('Entri CKP-T Pegawai');
				$data['breadcrumb'] = $this->make_bread->output();
				$data['menu']=6;
				if(strcmp($this->session->userdata('lvl'), '1')==0){
					$data['pegawai']=$this->pegawai->get_pegawai_es3();
				}else if(strcmp($this->session->userdata('lvl'), '2')==0){
					if(strcmp(substr($this->session->userdata('satker'), 2, 2), '00')==0){
						$data['pegawai']=$this->pegawai->get_pegawai_es4();
					}else{
						$data['pegawai']=$this->pegawai->get_pegawai_es4_ksk();
					}
				}else{
					$data['pegawai']=$this->pegawai->get_pegawai_by_org_jab($this->session->userdata('organisasi'), $this->session->userdata('lvl'));
				}
				$niplama=$this->session->userdata('niplama');
				$data['keg_skp']=$this->skp->ambil_skp_bynip($niplama);
				$data['keg_lainnya']=$this->kegiatan->ambil_keg_bynip($niplama);
				$data['page']='target/input_target_pegawai_view';
				$this->load->vars($data);
				$this->load->view('template/layout');
			}

		public function simpan_target(){
				$number = count($_POST["nm_kegiatan"]);
				if($number > 0)
				{
						 for($i=0; $i<$number; $i++)
						 {
					 		$data = array(
					 				'niplama' => $this->session->userdata('niplama'),
					 				'target' => $_POST["target"][$i],
					 				'tgl_buat' => date('Y-m-d'),
					 				'tgl_ubah' => date('Y-m-d'),
									'bulan_ckp' => $this->input->post('bulan_ckp'),
									'tahun_ckp' => date("Y"),
					 				'jenis' => $_POST["jenis"][$i],
									 'nm_keg' => $_POST["nm_kegiatan"][$i],
									 'satuan' => $_POST["satuan"][$i],
					 			);
						$insert = $this->target->tambah_target($data);
					 }
				 }
				 echo json_encode(array("status" => TRUE));
			}

			public function simpan_target_pegawai(){
					date_default_timezone_set("Asia/Jakarta");
					$number = count($_POST["id_kegiatan"]);
					if($number > 0)
					{
							 for($i=0; $i<$number; $i++)
							 {
								$data = array(
										'niplama' => $this->input->post('niplama'),
										'target' => $_POST["target"][$i],
										'tgl_buat' => date('Y-m-d'),
										'tgl_ubah' => date('Y-m-d'),
										'tgl_ckp' => $this->input->post('tgl_ckp'),
										'jenis' => $_POST["jenis"][$i],
										'id_keg' => $_POST["id_kegiatan"][$i],
										'status_target' =>'1'
									);
							$insert = $this->target->tambah_target($data);
						 }
					 }
					 echo json_encode(array("status" => TRUE));
				}

		public function get_keg_skp()
		{
			$niplama = $this->input->post('niplama');
			$q = $this->skp->ambil_skp_bynip($niplama);
			$output = '';
			if($q->num_rows() > 0){
					$keg = $q->result();
					$output .= '<option selected disabled></option>';
				foreach ($keg as $result) {
					$output .= '<option value="'.$result->id.'">'.$result->n_keg.'</option>';
				}
			}else{
					$output .= '<option selected disabled></option>';
			}
			echo $output;
		}
		public function get_keg()
		{
			$niplama = $this->input->post('niplama');
			$q = $this->kegiatan->ambil_keg_bynip($niplama);
			$output = '';
			if($q->num_rows() > 0){
				$keg = $q->result();
					$output .= '<option selected disabled></option>';
				foreach ($keg as $result) {
					$output .= '<option value="'.$result->id.'">'.$result->n_keg.'</option>';
				}
			}else{
					$output .= '<option selected disabled></option>';
			}
			echo $output;
		}

		/*public function bulanindonesia($stringbulan)
		{
		$exp = explode(" ", $stringbulan);
		$bulan_angka=array('01','02','03','04','05','06','07','08','09','10','11','12');
		$bulan_huruf=array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus',
		                  'September','Oktober','November','Desember');
		$konversi=str_ireplace($bulanhuruf, $bulan_angka, $exp[0]);
		return $exp[1].'-'.$konversi.'-01';
	}*/

	public function entri_ckp_realisasi()
{
	$this->make_bread->add('Beranda','beranda.html');
	$this->make_bread->add('Capaian Kinerja Pegawai','capaian-kinerja-pegawai.html');
	$this->make_bread->add('Entri CKP-R');
	$data['breadcrumb'] = $this->make_bread->output();
	$data['menu']=6;
	$data['title']='Entri Realisasi Capaian Kinerja Pegawai';
	$data['kegiatan']=$this->kegiatan->get_kegiatan();
	$data['pegawai']=$this->pegawai->get_pegawai_by_org_jab($this->session->userdata('org'), $this->session->userdata('lvl'));
	$data['page']='realisasi/realization_view';
	$this->load->vars($data);
	$this->load->view('template/layout');
}

public function list_realization($m)
	{
	$m=bulan($m);
	$list = $this->realization->get_datatables($m);
	$data = array();
	$no = $_POST['start'];
	foreach ($list as $realization) {
		$no++;
		$row = array();
		$row[] = $realization->nm_keg;
		$row[] = $realization->target.' '.$realization->satuan;

    if(is_null($realization->realisasi)){
			$row[] = '-';
			$row[] = '<span class="label label-info">Belum Dientri</span>';
			$row[] = '<a class="btn btn-sm btn-info noborder" href="javascript:void(0)" title="Entri Realisasi" style="min-width:150px" onclick="entri_realisasi('."'".$realization->id."'".')"><i class="glyphicon glyphicon-tasks"></i> Entri Realisasi</a>';
    }else{
			$row[] = $realization->realisasi.' '.$realization->satuan;
			if(is_null($realization->status_realisasi)){
				$row[] = '<span class="label label-warning">Menunggu Persetujuan</span>';
				$row[] = '<a class="btn btn-sm btn-warning noborder" href="javascript:void(0)" title="Edit" style="min-width:150px" onclick="ubah_realisasi('."'".$realization->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Ubah Realisasi</a>';

			}else if($realization->status_realisasi=="1"){
				$row[] = '<span class="label label-success">Disetujui</span>';
				$row[] = '<a class="btn btn-sm btn-success noborder" title="Sudah Disetujui" style="min-width:150px; cursor:not-allowed"><i class="glyphicon glyphicon-ok"></i> Disetujui</a>';
			}else{
				$row[] = '<span class="label label-danger">Ditolak</span>';
				$row[] = '<a class="btn btn-sm btn-danger noborder" title="Ditolak" style="min-width:150px; cursor:not-allowed"><i class="glyphicon glyphicon-remove"></i> Ditolak</a>';
			}
		}

		$data[] = $row;
	}

	$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->realization->count_all($m),
					"recordsFiltered" => $this->realization->count_filtered($m),
					"data" => $data,
			);

	echo json_encode($output);
	}

	public function entry_realization()
	{
		$data = array(
				'realisasi' => $this->input->post('realisasi'),
			);
		$insert = $this->realization->entry_realization(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function get_realization($id)
	{
		$data = $this->realization->get_realization($id);
		echo json_encode($data);
	}

	public function ckp_download(){
		$this->load->library("Excel/PHPExcel");

		$m=$this->uri->segment(2);
		if($m==''){
			$m=date("m");
		}

		$Fungsional_row=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->num_rows();
		$Fungsional=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->row();
		$jabatan ="pertama";
		$jb_fung= "";
		if($Fungsional_row>0){
			if(strcmp($Fungsional->jabatan, 'A0101')==0){
				$jabatan ="pelaksana_pemula";
				$jb_fung ="Statistisi Pelaksana Pemula";
			}elseif (strcmp($Fungsional->jabatan, 'A0102')==0) {
				$jabatan ="pelaksana";
				$jb_fung ="Statistisi Pelaksana";
			}elseif (strcmp($Fungsional->jabatan, 'A0103')==0) {
				$jabatan ="pelaksana_lanjutan";
				$jb_fung ="Statistisi Pelaksana Lanjutan";
			}elseif (strcmp($Fungsional->jabatan, 'A0104')==0) {
				$jabatan ="penyelia";
				$jb_fung ="Statistisi Penyelia";
			}elseif (strcmp($Fungsional->jabatan, 'A0105')==0) {
				$jabatan ="pertama";
				$jb_fung ="Statistisi Pertama";
			}elseif (strcmp($Fungsional->jabatan, 'A0106')==0) {
				$jabatan ="muda";
				$jb_fung ="Statistisi Muda";
			}elseif (strcmp($Fungsional->jabatan, 'A0107')==0) {
				$jabatan ="madya";
				$jb_fung ="Statistisi Madya";
			}elseif (strcmp($Fungsional->jabatan, 'A0108')==0) {
				$jabatan ="utama";
				$jb_fung ="Statistisi Utama";
			}
		}

		$data['jb_fung']=$jb_fung;

		$ckp=$this->ckp->get_ckp_bynip($m, $this->session->userdata('niplama'), $jabatan)->result();
		$ckpt=$this->ckp->get_ckpt_bynip($m, $this->session->userdata('niplama'), $jabatan)->result();
		$penilai=$this->ckp->get_penilai();
		$pegawai=$this->pegawai->get_pegawai_by_id($this->session->userdata('niplama'));
		$satker=$this->pegawai->get_satker_by_nip($this->session->userdata('niplama'));
		$s_date = date("Y-".$m."-01");
		$e_date = date("Y-".$m."-t", strtotime($s_date));
		$periode=tgl_indo($s_date).' s.d '.tgl_indo($e_date);
		$penilaian_k = strtotime(date("Y-m-d", strtotime($s_date)) . " " . "+1 month");
		$day = date('D', $penilaian_k);
		if(strcmp($day, "Sat")==0){
			$day = date('Y-m-d', strtotime('+2 day' , $penilaian_k));
			$penilaian_k=tgl_indo($day);
		}else if(strcmp($day, "Sun")==0){
			$day = date('Y-m-d', strtotime('+1 day' , $penilaian_k));
			$penilaian_k=tgl_indo(date($day));
		}else{
			$penilaian_k=tgl_indo(date("Y-m-d", $penilaian_k));
		}
		$nama = $pegawai->nama;
		$namap = $penilai->nama;
		$nipbaru = $pegawai->nipbaru;
		$jabatan = $this->session->userdata('lvl');
		$seksi = $pegawai->nm_org;
		$gelar_d = $pegawai->gelar_depan;
		$gelar_b = $pegawai->gelar_belakang;
		$gelar_dp = $penilai->gelar_depan;
		$gelar_bp = $penilai->gelar_belakang;
		$seksi_id = $pegawai->id_org;
		$satker_id = $satker->id_satker;
		$satker_n = $satker->nm_satker;
		$so='';
		$jb='';

		if(strcmp(substr($satker_id,2,1),'7')==0){
			if(strcmp($seksi_id,'1')==0){
				$so =$satker_n;
					if(strcmp($jabatan,'3')==0){
						$jb ='Kepala '.$seksi;
					}else if(strcmp($jabatan,'4')==0){
						$jb ='Staf '.$seksi;
					}
			}else{
				if(strcmp($seksi_id,'0')==0){
					$so =$satker_n;
					$jb ='Kepala BPS '.$satker_n;
				}elseif(strcmp($seksi_id,'7')==0){
					$so =$satker_n;
					$jb ='Koordinator Statistik Kecamatan';
				}else{
					$so =$satker_n;
					if(strcmp($jabatan,'3')==0){
						$jb ='Kepala '.$seksi;
					}else if(strcmp($jabatan,'4')==0){
						$jb ='Staf '.$seksi;
					}
				}
			}
		}else{
			if(strcmp($seksi_id,'1')==0){
				$so =$satker_n;
				if(strcmp($jabatan,'3')==0){
					$jb ='Kepala '.$seksi;
				}else if(strcmp($jabatan,'4')==0){
					$jb ='Staf '.$seksi;
				}
			}else{
				if(strcmp($seksi_id,'0')==0){
					$so =$satker_n;
					$jb ='Kepala BPS '.$satker_n;
				}elseif(strcmp($seksi_id,'7')==0){
					$so =$satker_n;
					$jb ='Koordinator Statistik Kecamatan';
				}else{
					$so =$satker_n;
					if(strcmp($jabatan,'3')==0){
						$jb ='Kepala '.$seksi;
					}else if(strcmp($jabatan,'4')==0){
						$jb ='Staf '.$seksi;
					}
				}
			}
		}
			$nama_l='';
			if($gelar_d=="") {
				if($gelar_b=="") {
					$nama_l=$nama;
				}else {
					$nama_l=$nama.', '.$gelar_b;
				}
			}else {
				if($gelar_b=="") {
					$nama_l=$gelar_d.' '.$nama;
				}else {
					$nama_l=$gelar_d.' '.$nama.', '.$gelar_b;
				}
			}

			$nama_lp='';
			if($gelar_dp=="") {
				if($gelar_bp=="") {
					$nama_lp=$namap;
				}else {
					$nama_lp=$namap.', '.$gelar_bp;
				}
			}else {
				if($gelar_bp=="") {
					$nama_lp=$gelar_dp.' '.$namap;
				}else {
					$nama_lp=$gelar_dp.' '.$namap.', '.$gelar_bp;
				}
			}

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("Yusfil Khoir Pulungan")
									 ->setLastModifiedBy("Yusfil Khoir Pulungan")
									 ->setTitle("Capaian Kinerja Pegawai")
									 ->setSubject("Capaian Kinerja Pegawai")
									 ->setDescription("Capaian Kinerja Pegawai")
									 ->setKeywords("bps ckp")
									 ->setCategory("Capaian Kinerja Pegawai");

		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load("assets/templates/format_ckp_realisasi.xlsx");

		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'CAPAIAN KINERJA PEGAWAI TAHUN '.date('Y'));
		$objPHPExcel->getActiveSheet()->setCellValue('C4', ': '.$so);
		$objPHPExcel->getActiveSheet()->setCellValue('C5', ': '.$nama_l);
		$objPHPExcel->getActiveSheet()->setCellValue('C6', ': '.$jb);
		$objPHPExcel->getActiveSheet()->setCellValue('C7', ': '.$periode);

		$baseRow = 13;
		$utama = 0;
		$styleArray = array(
		  'borders' => array(
		    'left' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		foreach($ckp as $r => $dataRow) {
			$utama = $baseRow + $r;
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($utama,1);
			$objPHPExcel->getActiveSheet()->mergeCells('B'.$utama.':C'.$utama);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$utama.':B'.$utama)->getFont()->setBold(false);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$utama, $r+1);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$utama, $dataRow->nm_keg);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$utama.':K'.$utama)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$utama, $dataRow->satuan);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$utama, $dataRow->target);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$utama, $dataRow->realisasi);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$utama, '=(F'.$utama.'/E'.$utama.')*100');
			$objPHPExcel->getActiveSheet()->getStyle('G'.$utama)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$utama, $dataRow->kualitas);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$utama)->getNumberFormat()->setFormatCode('#,##0.00');

			// if($Fungsional_row>0){
			// 	$objPHPExcel->getActiveSheet()->setCellValue('I'.$utama, $dataRow->pengali);
			// 	$objPHPExcel->getActiveSheet()->getStyle('I'.$utama)->getNumberFormat()->setFormatCode('#,##0.000');
			// 	$objPHPExcel->getActiveSheet()->setCellValue('J'.$utama, $dataRow->ak);
			// 	$objPHPExcel->getActiveSheet()->getStyle('J'.$utama)->getNumberFormat()->setFormatCode('#,##0.000');
			// }else{
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$utama, '');
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$utama, '');
			// }

		}

		$baseRow2=0;
		if($utama>0){
			$baseRow2 = $utama + 3;
		}else{
			$baseRow2 = 13 + 3;
		}
		$tambahan = 0;
		$styleArray = array(
			'borders' => array(
				'left' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);
		foreach($ckpt as $r => $dataRow) {
			$tambahan = $baseRow2 + $r;
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($tambahan,1);
			$objPHPExcel->getActiveSheet()->mergeCells('B'.$tambahan.':C'.$tambahan);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$tambahan.':B'.$tambahan)->getFont()->setBold(false);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$tambahan, $r+1);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$tambahan, $dataRow->nm_keg);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$tambahan.':C'.$tambahan)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$tambahan, $dataRow->satuan);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$tambahan, $dataRow->target);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$tambahan, $dataRow->realisasi);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$tambahan, '=(F'.$tambahan.'/E'.$tambahan.')*100');
			$objPHPExcel->getActiveSheet()->getStyle('G'.$tambahan)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$tambahan, $dataRow->kualitas);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$tambahan)->getNumberFormat()->setFormatCode('#,##0.00');
			// if($Fungsional_row>0){
			// 	$objPHPExcel->getActiveSheet()->setCellValue('I'.$tambahan, $dataRow->pengali);
			// 	$objPHPExcel->getActiveSheet()->getStyle('I'.$tambahan)->getNumberFormat()->setFormatCode('#,##0.000');
			// 	$objPHPExcel->getActiveSheet()->setCellValue('J'.$tambahan, $dataRow->ak);
			// 	$objPHPExcel->getActiveSheet()->getStyle('J'.$tambahan)->getNumberFormat()->setFormatCode('#,##0.000');
			//}else{
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$tambahan, '');
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$tambahan, '');
			//}
		}

		if($tambahan>0){
			$objPHPExcel->getActiveSheet()->setCellValue('G'.($tambahan+2), '=AVERAGE(G13:G'.($tambahan+1).')');
			$objPHPExcel->getActiveSheet()->getStyle('G'.($tambahan+2))->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->setCellValue('H'.($tambahan+2), '=AVERAGE(H13:H'.($tambahan+1).')');
			$objPHPExcel->getActiveSheet()->getStyle('H'.($tambahan+2))->getNumberFormat()->setFormatCode('#,##0.00');

			$objPHPExcel->getActiveSheet()->setCellValue('G'.($tambahan+3), '=AVERAGE(G13:G'.($tambahan).')');
			$objPHPExcel->getActiveSheet()->getStyle('G'.($tambahan+3))->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->setCellValue('H'.($tambahan+3), '=AVERAGE(H13:H'.($tambahan).')');
			$objPHPExcel->getActiveSheet()->getStyle('H'.($tambahan+3))->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->setCellValue('G'.($tambahan+4), '=AVERAGE(G'.($tambahan+3).':H'.($tambahan+3).')');
			$objPHPExcel->getActiveSheet()->getStyle('G'.($tambahan+4))->getNumberFormat()->setFormatCode('#,##0.00');

			$objPHPExcel->getActiveSheet()->setCellValue('B'.($tambahan+7), 'Tanggal : '.$penilaian_k);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($tambahan+11), $nama_l);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($tambahan+12), 'NIP : '.$nipbaru);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.($tambahan+11), $nama_lp);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.($tambahan+12), 'NIP : '.$penilai->nipbaru);
		}else{
			$objPHPExcel->getActiveSheet()->setCellValue('G'.($utama+4), '=AVERAGE(G13:G'.($utama+3).')');
			$objPHPExcel->getActiveSheet()->getStyle('G'.($utama+4))->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->setCellValue('H'.($utama+4), '=AVERAGE(H13:H'.($utama+3).')');
			$objPHPExcel->getActiveSheet()->getStyle('H'.($utama+4))->getNumberFormat()->setFormatCode('#,##0.00');

			$objPHPExcel->getActiveSheet()->setCellValue('G'.($utama+5), '=AVERAGE(G13:G'.($utama).')');
			$objPHPExcel->getActiveSheet()->getStyle('G'.($utama+5))->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->setCellValue('H'.($utama+5), '=AVERAGE(H13:H'.($utama).')');
			$objPHPExcel->getActiveSheet()->getStyle('H'.($utama+5))->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->setCellValue('G'.($utama+6), '=AVERAGE(G'.($utama+5).':H'.($utama+5).')');
			$objPHPExcel->getActiveSheet()->getStyle('G'.($utama+6))->getNumberFormat()->setFormatCode('#,##0.00');

			$objPHPExcel->getActiveSheet()->setCellValue('B'.($utama+9), 'Tanggal : '.$penilaian_k);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($utama+13), $nama_l);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($utama+14), 'NIP : '.$nipbaru);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.($utama+13), $nama_lp);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.($utama+14), 'NIP : '.$penilai->nipbaru);
		}

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Capaian Kinerja Pegawai.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}

	public function ckp_downloadt(){
		$this->load->library("Excel/PHPExcel");

		$m=$this->uri->segment(2);
		if($m==''){
			$m=date("m");
		}

		$Fungsional_row=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->num_rows();
		$Fungsional=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->row();
		$jabatan ="pertama";
		$jb_fung= "";
		if($Fungsional_row>0){
			if(strcmp($Fungsional->jabatan, 'A0101')==0){
				$jabatan ="pelaksana_pemula";
				$jb_fung ="Statistisi Pelaksana Pemula";
			}elseif (strcmp($Fungsional->jabatan, 'A0102')==0) {
				$jabatan ="pelaksana";
				$jb_fung ="Statistisi Pelaksana";
			}elseif (strcmp($Fungsional->jabatan, 'A0103')==0) {
				$jabatan ="pelaksana_lanjutan";
				$jb_fung ="Statistisi Pelaksana Lanjutan";
			}elseif (strcmp($Fungsional->jabatan, 'A0104')==0) {
				$jabatan ="penyelia";
				$jb_fung ="Statistisi Penyelia";
			}elseif (strcmp($Fungsional->jabatan, 'A0105')==0) {
				$jabatan ="pertama";
				$jb_fung ="Statistisi Pertama";
			}elseif (strcmp($Fungsional->jabatan, 'A0106')==0) {
				$jabatan ="muda";
				$jb_fung ="Statistisi Muda";
			}elseif (strcmp($Fungsional->jabatan, 'A0107')==0) {
				$jabatan ="madya";
				$jb_fung ="Statistisi Madya";
			}elseif (strcmp($Fungsional->jabatan, 'A0108')==0) {
				$jabatan ="utama";
				$jb_fung ="Statistisi Utama";
			}
		}

		$data['jb_fung']=$jb_fung;

		$ckp=$this->ckp->get_ckp2_bynip($m, $this->session->userdata('niplama'), $jabatan)->result();
		$ckpt=$this->ckp->get_ckpt2_bynip($m, $this->session->userdata('niplama'), $jabatan)->result();
		$penilai=$this->ckp->get_penilai();
		$pegawai=$this->pegawai->get_pegawai_by_id($this->session->userdata('niplama'));
		$satker=$this->pegawai->get_satker_by_nip($this->session->userdata('niplama'));
		$s_date = date("Y-".$m."-01");
		$e_date = date("Y-".$m."-t", strtotime($s_date));
		$periode=tgl_indo($s_date).' s.d '.tgl_indo($e_date);
		$penilaian_k = strtotime(date("Y-m-d", strtotime($e_date)) . " " . "-1 month");
		$day = date('D', $penilaian_k);
		if(strcmp($day, "Sat")==0){
			$day = date('Y-m-d', strtotime('-1 day' , $penilaian_k));
			$penilaian_k=tgl_indo($day);
		}else if(strcmp($day, "Sun")==0){
			$day = date('Y-m-d', strtotime('-2 day' , $penilaian_k));
			$penilaian_k=tgl_indo(date($day));
		}else{
			$penilaian_k=tgl_indo(date("Y-m-d", $penilaian_k));
		}
		$nama = $pegawai->nama;
		$namap = $penilai->nama;
		$nipbaru = $pegawai->nipbaru;
		$jabatan = $this->session->userdata('lvl');
		$seksi = $pegawai->nm_org;
		$gelar_d = $pegawai->gelar_depan;
		$gelar_b = $pegawai->gelar_belakang;
		$gelar_dp = $penilai->gelar_depan;
		$gelar_bp = $penilai->gelar_belakang;
		$seksi_id = $pegawai->id_org;
		$satker_id = $satker->id_satker;
		$satker_n = $satker->nm_satker;
		$so='';
		$jb='';

		if(strcmp(substr($satker_id,2,1),'7')==0){
			if(strcmp($seksi_id,'1')==0){
				$so =$satker_n;
					if(strcmp($jabatan,'3')==0){
						$jb ='Kepala '.$seksi;
					}else if(strcmp($jabatan,'4')==0){
						$jb ='Staf '.$seksi;
					}
			}else{
				if(strcmp($seksi_id,'0')==0){
					$so =$satker_n;
					$jb ='Kepala BPS '.$satker_n;
				}elseif(strcmp($seksi_id,'7')==0){
					$so =$satker_n;
					$jb ='Koordinator Statistik Kecamatan';
				}else{
					$so =$satker_n;
					if(strcmp($jabatan,'3')==0){
						$jb ='Kepala '.$seksi;
					}else if(strcmp($jabatan,'4')==0){
						$jb ='Staf '.$seksi;
					}
				}
			}
		}else{
			if(strcmp($seksi_id,'1')==0){
				$so =$satker_n;
				if(strcmp($jabatan,'3')==0){
					$jb ='Kepala '.$seksi;
				}else if(strcmp($jabatan,'4')==0){
					$jb ='Staf '.$seksi;
				}
			}else{
				if(strcmp($seksi_id,'0')==0){
					$so =$satker_n;
					$jb ='Kepala BPS '.$satker_n;
				}elseif(strcmp($seksi_id,'7')==0){
					$so =$satker_n;
					$jb ='Koordinator Statistik Kecamatan';
				}else{
					$so =$satker_n;
					if(strcmp($jabatan,'3')==0){
						$jb ='Kepala '.$seksi;
					}else if(strcmp($jabatan,'4')==0){
						$jb ='Staf '.$seksi;
					}
				}
			}
		}
			$nama_l='';
			if($gelar_d=="") {
				if($gelar_b=="") {
					$nama_l=$nama;
				}else {
					$nama_l=$nama.', '.$gelar_b;
				}
			}else {
				if($gelar_b=="") {
					$nama_l=$gelar_d.' '.$nama;
				}else {
					$nama_l=$gelar_d.' '.$nama.', '.$gelar_b;
				}
			}

			$nama_lp='';
			if($gelar_dp=="") {
				if($gelar_bp=="") {
					$nama_lp=$namap;
				}else {
					$nama_lp=$namap.', '.$gelar_bp;
				}
			}else {
				if($gelar_bp=="") {
					$nama_lp=$gelar_dp.' '.$namap;
				}else {
					$nama_lp=$gelar_dp.' '.$namap.', '.$gelar_bp;
				}
			}

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("Yusfil Khoir Pulungan")
									 ->setLastModifiedBy("Yusfil Khoir Pulungan")
									 ->setTitle("Capaian Kinerja Pegawai")
									 ->setSubject("Capaian Kinerja Pegawai")
									 ->setDescription("Capaian Kinerja Pegawai")
									 ->setKeywords("bps ckp")
									 ->setCategory("Capaian Kinerja Pegawai");

		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load("assets/templates/format_ckp_target.xlsx");

		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'CAPAIAN KINERJA PEGAWAI TAHUN '.date('Y'));
		$objPHPExcel->getActiveSheet()->setCellValue('C4', ': '.$so);
		$objPHPExcel->getActiveSheet()->setCellValue('C5', ': '.$nama_l);
		$objPHPExcel->getActiveSheet()->setCellValue('C6', ': '.$jb);
		$objPHPExcel->getActiveSheet()->setCellValue('C7', ': '.$periode);

		$baseRow = 13;
		$utama = 0;
		$styleArray = array(
		  'borders' => array(
		    'left' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		foreach($ckp as $r => $dataRow) {
			$utama = $baseRow + $r;
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($utama,1);
			$objPHPExcel->getActiveSheet()->mergeCells('B'.$utama.':C'.$utama);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$utama.':B'.$utama)->getFont()->setBold(false);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$utama, $r+1);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$utama, $dataRow->nm_keg);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$utama.':K'.$utama)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$utama, $dataRow->satuan);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$utama, $dataRow->target);
			// $objPHPExcel->getActiveSheet()->setCellValue('F'.$utama, $dataRow->realisasi);
			// // $objPHPExcel->getActiveSheet()->setCellValue('G'.$utama, '=(F'.$utama.'/E'.$utama.')*100');
			// $objPHPExcel->getActiveSheet()->getStyle('G'.$utama)->getNumberFormat()->setFormatCode('#,##0.00');
			// $objPHPExcel->getActiveSheet()->setCellValue('H'.$utama, $dataRow->kualitas);
			// $objPHPExcel->getActiveSheet()->getStyle('H'.$utama)->getNumberFormat()->setFormatCode('#,##0.00');

			// if($Fungsional_row>0){
			// 	$objPHPExcel->getActiveSheet()->setCellValue('I'.$utama, $dataRow->pengali);
			// 	$objPHPExcel->getActiveSheet()->getStyle('I'.$utama)->getNumberFormat()->setFormatCode('#,##0.000');
			// 	$objPHPExcel->getActiveSheet()->setCellValue('J'.$utama, $dataRow->ak);
			// 	$objPHPExcel->getActiveSheet()->getStyle('J'.$utama)->getNumberFormat()->setFormatCode('#,##0.000');
			// }else{
				// $objPHPExcel->getActiveSheet()->setCellValue('I'.$utama, '');
				// $objPHPExcel->getActiveSheet()->setCellValue('J'.$utama, '');
			// }

		}

		$baseRow2=0;
		if($utama>0){
			$baseRow2 = $utama + 3;
		}else{
			$baseRow2 = 13 + 3;
		}
		$tambahan = 0;
		$styleArray = array(
			'borders' => array(
				'left' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);
		foreach($ckpt as $r => $dataRow) {
			$tambahan = $baseRow2 + $r;
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($tambahan,1);
			$objPHPExcel->getActiveSheet()->mergeCells('B'.$tambahan.':C'.$tambahan);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$tambahan.':B'.$tambahan)->getFont()->setBold(false);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$tambahan, $r+1);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$tambahan, $dataRow->nm_keg);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$tambahan.':C'.$tambahan)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$tambahan, $dataRow->satuan);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$tambahan, $dataRow->target);
			// $objPHPExcel->getActiveSheet()->setCellValue('F'.$tambahan, $dataRow->realisasi);
			// $objPHPExcel->getActiveSheet()->setCellValue('G'.$tambahan, '=(F'.$tambahan.'/E'.$tambahan.')*100');
			// $objPHPExcel->getActiveSheet()->getStyle('G'.$tambahan)->getNumberFormat()->setFormatCode('#,##0.00');
			// $objPHPExcel->getActiveSheet()->setCellValue('H'.$tambahan, $dataRow->kualitas);
			// $objPHPExcel->getActiveSheet()->getStyle('H'.$tambahan)->getNumberFormat()->setFormatCode('#,##0.00');
			// if($Fungsional_row>0){
			// 	$objPHPExcel->getActiveSheet()->setCellValue('I'.$tambahan, $dataRow->pengali);
			// 	$objPHPExcel->getActiveSheet()->getStyle('I'.$tambahan)->getNumberFormat()->setFormatCode('#,##0.000');
			// 	$objPHPExcel->getActiveSheet()->setCellValue('J'.$tambahan, $dataRow->ak);
			// 	$objPHPExcel->getActiveSheet()->getStyle('J'.$tambahan)->getNumberFormat()->setFormatCode('#,##0.000');
			// }else{
			// 	$objPHPExcel->getActiveSheet()->setCellValue('I'.$tambahan, '');
			// 	$objPHPExcel->getActiveSheet()->setCellValue('J'.$tambahan, '');
			// }
		}

		if($tambahan>0){
			// $objPHPExcel->getActiveSheet()->setCellValue('G'.($tambahan+2), '=AVERAGE(G13:G'.($tambahan+1).')');
			// $objPHPExcel->getActiveSheet()->getStyle('G'.($tambahan+2))->getNumberFormat()->setFormatCode('#,##0.00');
			// $objPHPExcel->getActiveSheet()->setCellValue('H'.($tambahan+2), '=AVERAGE(H13:H'.($tambahan+1).')');
			// $objPHPExcel->getActiveSheet()->getStyle('H'.($tambahan+2))->getNumberFormat()->setFormatCode('#,##0.00');

			// $objPHPExcel->getActiveSheet()->setCellValue('G'.($tambahan+3), '=AVERAGE(G13:G'.($tambahan).')');
			// $objPHPExcel->getActiveSheet()->getStyle('G'.($tambahan+3))->getNumberFormat()->setFormatCode('#,##0.00');
			// $objPHPExcel->getActiveSheet()->setCellValue('H'.($tambahan+3), '=AVERAGE(H13:H'.($tambahan).')');
			// $objPHPExcel->getActiveSheet()->getStyle('H'.($tambahan+3))->getNumberFormat()->setFormatCode('#,##0.00');
			// $objPHPExcel->getActiveSheet()->setCellValue('G'.($tambahan+4), '=AVERAGE(G'.($tambahan+3).':H'.($tambahan+3).')');
			// $objPHPExcel->getActiveSheet()->getStyle('G'.($tambahan+4))->getNumberFormat()->setFormatCode('#,##0.00');

			$objPHPExcel->getActiveSheet()->setCellValue('B'.($tambahan+5), 'Tanggal : '.$penilaian_k);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($tambahan+9), $nama_l);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($tambahan+10), 'NIP : '.$nipbaru);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.($tambahan+9), $nama_lp);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.($tambahan+10), 'NIP : '.$penilai->nipbaru);
		}else{
			// $objPHPExcel->getActiveSheet()->setCellValue('G'.($utama+4), '=AVERAGE(G13:G'.($utama+3).')');
			// $objPHPExcel->getActiveSheet()->getStyle('G'.($utama+4))->getNumberFormat()->setFormatCode('#,##0.00');
			// $objPHPExcel->getActiveSheet()->setCellValue('H'.($utama+4), '=AVERAGE(H13:H'.($utama+3).')');
			// $objPHPExcel->getActiveSheet()->getStyle('H'.($utama+4))->getNumberFormat()->setFormatCode('#,##0.00');

			// $objPHPExcel->getActiveSheet()->setCellValue('G'.($utama+5), '=AVERAGE(G13:G'.($utama).')');
			// $objPHPExcel->getActiveSheet()->getStyle('G'.($utama+5))->getNumberFormat()->setFormatCode('#,##0.00');
			// $objPHPExcel->getActiveSheet()->setCellValue('H'.($utama+5), '=AVERAGE(H13:H'.($utama).')');
			// $objPHPExcel->getActiveSheet()->getStyle('H'.($utama+5))->getNumberFormat()->setFormatCode('#,##0.00');
			// $objPHPExcel->getActiveSheet()->setCellValue('G'.($utama+6), '=AVERAGE(G'.($utama+5).':H'.($utama+5).')');
			// $objPHPExcel->getActiveSheet()->getStyle('G'.($utama+6))->getNumberFormat()->setFormatCode('#,##0.00');

			$objPHPExcel->getActiveSheet()->setCellValue('B'.($utama+7), 'Tanggal : '.$penilaian_k);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($utama+11), $nama_l);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($utama+12), 'NIP : '.$nipbaru);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.($utama+11), $nama_lp);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.($utama+12), 'NIP : '.$penilai->nipbaru);
		}

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Capaian Kinerja Pegawai.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}

	public function all_ckp(){
		$this->load->library('make_bread');
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Capaian Kinerja Pegawai', 'capaian-kinerja-pegawai.html');
		$this->make_bread->add('Rekap CKP Pegawai');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=6;
		$data['januari']=$this->ckp->get_ckp_all(1);
		$data['februari']=$this->ckp->get_ckp_all(2);
		$data['maret']=$this->ckp->get_ckp_all(3);
		$data['april']=$this->ckp->get_ckp_all(4);
		$data['mei']=$this->ckp->get_ckp_all(5);
		$data['juni']=$this->ckp->get_ckp_all(6);
		$data['juli']=$this->ckp->get_ckp_all(7);
		$data['agustus']=$this->ckp->get_ckp_all(8);
		$data['september']=$this->ckp->get_ckp_all(9);
		$data['oktober']=$this->ckp->get_ckp_all(10);
		$data['november']=$this->ckp->get_ckp_all(11);
		$data['desember']=$this->ckp->get_ckp_all(12);
		$data['page']='ckp/all_ckp_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
		}

}

?>
