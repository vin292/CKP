<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approve extends CI_Controller {

	var $table = 'ckp';
	var $column_order = array('nama', 'n_keg', 'target','realisasi', 'tgl_ckp', null);
	var $column_search = array('nama', 'n_keg', 'target', 'realisasi', 'tgl_ckp');
	var $order = array('nama' => 'asc');

	public function __construct(){
		parent::__construct();
			if(!$this->session->userdata('isLogin') || $this->session->userdata('lvl')=='4') {
					redirect('login.html');
			}else{
				$this->load->model('kegiatan/kegiatan_model','kegiatan');
				$this->load->model('pegawai/pegawai_model','pegawai');
				$this->load->model('ckp/approve_model', 'approve');
				$this->load->helper('tanggal');
			}
	}

	public function index()
	{
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Persetujuan');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=4;
		$data['title']='Persetujuan Target dan Realisasi Capaian Kinerja Pegawai';
		$data['kegiatan']=$this->kegiatan->get_kegiatan();
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

		$data['page']='approve/approve_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}

	public function list_approve($m)
	{
	$list = $this->approve->get_datatables($m);
	$data = array();
	$no = $_POST['start'];
	foreach ($list as $approve) {
		$no++;
		$row = array();
		$row[] = $approve->gelar_depan.' '.$approve->nama.' '.$approve->gelar_belakang;
		$row[] = $approve->nm_keg;
		$row[] = $approve->target.' '.$approve->satuan;
		//$row[] = tgl_indo($approve->tgl_ckp);

		if(is_null($approve->status_target)){
				$row[] = '-';
				$row[] = '<a class="btn btn-sm btn-success noborder" href="javascript:void(0)" title="Setuju" style="margin-bottom:5px; min-width:75px;" onclick="setuju_target('."'".$approve->id."'".')"><i class="glyphicon glyphicon-ok"></i> Setuju</a>
				<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" title="Tolak"style="margin-bottom:5px; min-width:75px;" onclick="tolak_target('."'".$approve->id."'".')"><i class="glyphicon glyphicon-remove"></i> Tolak</a>';
				$row[] = '<span class="label label-warning">Target Belum Disetujui</span>';
			}else if(strcmp($approve->status_target,'1')==0){
				if(is_null($approve->realisasi)){
						$row[] = '<span class="label label-info">Realisasi Belum Dientri</span>';
						$row[] = '<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" title="Tolak" style="margin-bottom:5px; min-width:75px;" onclick="tolak_target('."'".$approve->id."'".')"><i class="glyphicon glyphicon-remove"></i> Ganti Tolak</a>';
						$row[] = '<a class="btn btn-sm btn-success noborder" href="javascript:void(0)" title="Setuju" style="margin-bottom:5px; min-width:75px; cursor:not-allowed"><i class="glyphicon glyphicon-ok"></i> Setuju</a>
						<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" title="Tolak" style="margin-bottom:5px; min-width:75px; cursor:not-allowed"><i class="glyphicon glyphicon-remove"></i> Tolak</a>';
					}else{
							$row[] = $approve->realisasi.' '.$approve->satuan;
						if(is_null($approve->status_realisasi)){
							$row[] = '<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" title="Tolak" onclick="tolak_target('."'".$approve->id."'".')"><i class="glyphicon glyphicon-remove"></i> Ganti Tolak</a>';
							$row[] = '<a class="btn btn-sm btn-success noborder" href="javascript:void(0)" title="Setuju" style="margin-bottom:5px; min-width:75px;" onclick="setuju('."'".$approve->id."'".')"><i class="glyphicon glyphicon-ok"></i> Setuju</a>
							<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" title="Tolak" style="margin-bottom:5px; min-width:75px;" onclick="tolak('."'".$approve->id."'".')"><i class="glyphicon glyphicon-remove"></i> Tolak</a>';
						}else if(strcmp($approve->status_realisasi,'1')==0){
							$row[] = '<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" title="Tolak" style="margin-bottom:5px; min-width:75px;" onclick="tolak_target('."'".$approve->id."'".')"><i class="glyphicon glyphicon-remove"></i> Ganti Tolak</a>';
							$row[] = '<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" title="Tolak" style="margin-bottom:5px; min-width:75px;" onclick="tolak('."'".$approve->id."'".')"><i class="glyphicon glyphicon-remove"></i> Ganti Tolak</a>';
						}else{
							$row[] = '<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" title="Tolak" onclick="tolak_target('."'".$approve->id."'".')"><i class="glyphicon glyphicon-remove"></i> Ganti Tolak</a>';
							$row[] = '<a class="btn btn-sm btn-success noborder" href="javascript:void(0)" title="Setuju" onclick="setuju('."'".$approve->id."'".')"><i class="glyphicon glyphicon-ok"></i> Ganti Setuju</a>';
						}
					}
			}else{
				$row[] = '<span class="label label-danger">Target Ditolak</span>';
				$row[] = '<a class="btn btn-sm btn-success noborder" href="javascript:void(0)" title="Setuju" onclick="setuju_target('."'".$approve->id."'".')"><i class="glyphicon glyphicon-ok"></i> Ganti Setuju</a>';
				$row[] = '<span class="label label-danger">Target Ditolak</span>';
			}

		$data[] = $row;
	}

	$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->approve->count_all($m),
					"recordsFiltered" => $this->approve->count_filtered($m),
					"data" => $data,
			);

	echo json_encode($output);
	}

	public function setuju_target()
	{
		//$this->validasi_akun();
		$data = array(
			'target' => $this->input->post('target_target'),
			'status_target' => '1',
			);
		$insert = $this->approve->setuju(array('id' => $this->input->post('id_target')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function setuju()
	{
		//$this->validasi_akun();
		$data = array(
			'target' => $this->input->post('target'),
			'realisasi' => $this->input->post('realisasi'),
			'kualitas' => $this->input->post('kualitas'),
			'status_realisasi' => '1',
			);
		$insert = $this->approve->setuju(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function tolak_target($id)
	{
		$data = array(
			'status_target' => '2',
			);
		$insert = $this->approve->tolak(array('id' => $id), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function tolak($id)
	{
		$data = array(
			'status_realisasi' => '2',
			);
		$insert = $this->approve->tolak(array('id' => $id), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function get_approve($id)
	{
		$data = $this->approve->get_approve($id);
		echo json_encode($data);
	}

	}

	?>
