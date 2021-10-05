<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('isLogin')) {
				redirect('login.html');
		}else{
				$this->load->library('encrypt');
				$this->load->library("Excel/PHPExcel");
				$this->load->library('make_bread');
				$this->load->model('pegawai/pegawai_model','pegawai');
				$this->load->model('kegiatan/kegiatan_model','kegiatan');
			}
	}

public function pegawai()
	{
		if($this->session->userdata('administrator')){
			$this->make_bread->add('Beranda','beranda.html');
			$this->make_bread->add('Kelola Data Pegawai');
			$data['breadcrumb'] = $this->make_bread->output();
			$data['menu']=1;
			$data['title']='Kelola Data Pegawai';
			$data['level']=$this->pegawai->get_level();
			$data['pegawai']=$this->pegawai->get_pegawai();
			$data['org']=$this->pegawai->get_org();
			$data['gol']=$this->pegawai->get_gol();
			$data['page']='pegawai/pegawai_view';
			$this->load->vars($data);
			$this->load->view('template/layout');
		}else{
			redirect('login.html');
		}
	}

	public function list_pegawai()
	{
	$list = $this->pegawai->get_datatables();
	$data = array();
	$no = $_POST['start'];
	foreach ($list as $pegawai) {
		$no++;
		$row = array();
		$row[] = $pegawai->niplama;

		$nama_l="";
		if($pegawai->gelar_depan!=""){
			$nama_l = $pegawai->gelar_depan.' '.$pegawai->nama;
		}else{
			$nama_l = $pegawai->nama;
		}

		if($pegawai->gelar_belakang!=""){
			$nama_l = $nama_l.' '.$pegawai->gelar_belakang;
		}
		$row[] = $pegawai->nipbaru;
		$row[] = $nama_l;
		$row[] = $pegawai->pangkat."(".str_replace("/","",$pegawai->n_gol).")";
		$row[] = $pegawai->email;
		$row[] = $pegawai->nm_org;

		//add html for action
		$row[] = '<a class="btn btn-sm btn-success noborder" href="javascript:void(0)" style="min-width:70px; margin-bottom:5px;" title="Edit" onclick="ubah_pegawai('."'".$pegawai->niplama."'".')"><i class="glyphicon glyphicon-pencil"></i> Data</a>
				<a class="btn btn-sm btn-warning noborder" href="javascript:void(0)" style="min-width:70px; margin-bottom:5px;" title="Edit" onclick="ubah_hakakses('."'".$pegawai->niplama."'".')"><i class="glyphicon glyphicon-pencil"></i> Akun</a>
				<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" style="min-width:70px; margin-bottom:5px;" title="Hapus" onclick="hapus_pegawai('."'".$pegawai->niplama."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';

		$data[] = $row;
	}

	$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->pegawai->count_all(),
					"recordsFiltered" => $this->pegawai->count_filtered(),
					"data" => $data,
			);
	//output to json format
	echo json_encode($output);
}

public function tambah_pegawai()
{
	$data = array(
		'gelar_depan' => $this->input->post('g_depan'),
		'gelar_belakang' => $this->input->post('g_belakang'),
		'niplama' => $this->input->post('niplama2'),
		'nama' => $this->input->post('nama'),
		'nipbaru' => $this->input->post('nipbaru'),
		'email' => $this->input->post('email'),
		'id_org' => $this->input->post('org'),
		'id_gol' => $this->input->post('gol'),
		'id_satker' => $this->session->userdata('satker'),
		);
	$insert = $this->pegawai->tambah_pegawai($data);
	echo json_encode(array("status" => TRUE));
}

public function tambah_akun()
{
	$data = array(
			'niplama' => $this->input->post('niplama_akun'),
			'username' => $this->input->post('username'),
			'password' => $this->encrypt->encode($this->input->post('password')),
			'id_level' => $this->input->post('level'),
		);
	$insert = $this->pegawai->tambah_akun($data);
	echo json_encode(array("status" => TRUE));
}

public function get_pegawai($id)
{
	$data = $this->pegawai->get_by_id($id);
	echo json_encode($data);
}

public function get_aut($id)
{
	$data = $this->pegawai->get_aut($id);
	echo json_encode($data);
}

public function ubah_pegawai()
{
	//$this->validasi_pegawai();
	$data = array(
			'gelar_depan' => $this->input->post('g_depan'),
			'gelar_belakang' => $this->input->post('g_belakang'),
			'niplama' => $this->input->post('niplama2'),
			'nama' => $this->input->post('nama'),
			'nipbaru' => $this->input->post('nipbaru'),
			'email' => $this->input->post('email'),
			'id_org' => $this->input->post('org'),
			'id_gol' => $this->input->post('gol'),
		);
	$insert = $this->pegawai->ubah_pegawai(array('niplama' => $this->input->post('niplama2')), $data);
	echo json_encode(array("status" => TRUE));
}

public function ubah_akun()
{

	$data = array(
			'niplama' => $this->input->post('niplama_ubahakun'),
			'username' => $this->input->post('username'),
			'password' => $this->encrypt->encode($this->input->post('password')),
			'id_level' => $this->input->post('level'),
		);
	$insert = $this->pegawai->ubah_akun(array('niplama' => $this->input->post('niplama_ubahakun')), $data);
	echo json_encode(array("status" => TRUE));
}

public function hapus_pegawai($id)
{
	$this->pegawai->delete_by_id($id);
	$this->pegawai->delete_by_id2($id);
	echo json_encode(array("status" => TRUE));
}

public function kegiatan()
{
	if($this->session->userdata('administrator')){
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Kelola Data Kegiatan');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=1;
		$data['keg_temp_row']=$this->kegiatan->get_kegiatan_baru()->num_rows();
		$data['keg_temp']=$this->kegiatan->get_kegiatan_baru()->num_rows();
		$data['org']=$this->pegawai->get_org();
		$data['page']='kegiatan/kegiatan_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}else{
		redirect('login.html');
	}
}

public function kegiatan_baru()
{
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Kelola Data Kegiatan', 'master-kegiatan.html');
		$this->make_bread->add('Kelola Data Kegiatan Baru');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=1;
		$data['keg_temp_row']=$this->kegiatan->get_kegiatan_baru()->num_rows();
		$data['keg_temp']=$this->kegiatan->get_kegiatan_baru()->num_rows();
		$data['org']=$this->pegawai->get_org();
		$data['page']='kegiatan/kegiatan_baru_view';
		$this->load->vars($data);
		$this->load->view('template/layout');

}

public function input_kegiatan()
{
		$data['org']=$this->pegawai->get_org();
		$data['page']='kegiatan/input_kegiatan_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
}

	public function list_kegiatan()
	{
			$list = $this->kegiatan->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $kegiatan) {
				$no++;
				$row = array();
				$row[] = $kegiatan->n_keg;
				$row[] = $kegiatan->nm_org;
				$row[] = $kegiatan->satuan;

				//add html for action
				$row[] = '<a class="btn btn-sm btn-success noborder" style="min-width:70px; margin-bottom:5px;" href="javascript:void(0)" title="Edit" onclick="ubah_kegiatan('."'".$kegiatan->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Data</a>
						<a class="btn btn-sm btn-danger noborder" style="min-width:70px; margin-bottom:5px;" href="javascript:void(0)" title="Hapus" onclick="hapus_kegiatan('."'".$kegiatan->id."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';

				$data[] = $row;
			}

			$output = array(
							"draw" => $_POST['draw'],
							"recordsTotal" => $this->kegiatan->count_all(),
							"recordsFiltered" => $this->kegiatan->count_filtered(),
							"data" => $data,
					);
			//output to json format
			echo json_encode($output);
		}

	public function tambah_kegiatan()
	{
		$data = array(
				'n_keg' => $this->input->post('nama'),
				'satuan' => $this->input->post('satuan'),
				'bukti_fisik' => $this->input->post('bukti_fisik'),
				'pelaksana' => $this->input->post('ak_pelaksana'),
				'pelaksana_lanjutan' => $this->input->post('ak_pelaksanalanjutan'),
				'penyelia' => $this->input->post('ak_penyelia'),
				'pertama' => $this->input->post('ak_pertama'),
				'muda' => $this->input->post('ak_muda'),
				'madya' => $this->input->post('ak_madya'),
				'id_org' => $this->input->post('org'),
				'id_butirkegiatan' => $this->input->post('butir_kegiatan'),
			);
		$insert = $this->kegiatan->tambah_kegiatan($data);
		echo json_encode(array("status" => TRUE));
	}

	public function tambah_kegiatan_baru($id_keg)
	{
		//$id_keg = $this->kegiatan->get_idmax()->id_keg;
		$data = array(
				'id_keg' => ($id_keg),
				'n_keg' => $this->input->post('nama'),
				'satuan' => $this->input->post('satuan'),
				'bukti_fisik' => $this->input->post('bukti_fisik'),
				'id_org' => '99999',
				'id_butirkegiatan' => '999999',
			);
			$data2 = array(
					'id_keg' => ($id_keg),
					'n_keg' => $this->input->post('nama'),
					'satuan' => $this->input->post('satuan'),
					'bukti_fisik' => $this->input->post('bukti_fisik'),
					'pembuat' => $this->session->userdata('niplama'),
					'id_org' => '99999',
					'id_butirkegiatan' => '999999',
				);
		$insert = $this->kegiatan->tambah_kegiatan_baru($data);
		$insert2 = $this->kegiatan->tambah_kegiatan_temp($data2);
		echo json_encode(array("status" => TRUE));
	}

	public function ubah_kegiatan()
	{
		$data = array(
			'id_keg' => $this->input->post('id'),
			'n_keg' => $this->input->post('nama'),
			'satuan' => $this->input->post('satuan'),
			'pelaksana' => $this->input->post('ak_pelaksana'),
			'pelaksana_lanjutan' => $this->input->post('ak_pelaksanalanjutan'),
			'penyelia' => $this->input->post('ak_penyelia'),
			'pertama' => $this->input->post('ak_pertama'),
			'muda' => $this->input->post('ak_muda'),
			'madya' => $this->input->post('ak_madya'),
			'id_org' => $this->input->post('org'),
			);
		$insert = $this->kegiatan->ubah_kegiatan(array('id_keg' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ubah_kegiatan_baru()
	{
		$data = array(
			'id_keg' => $this->input->post('id'),
			'n_keg' => $this->input->post('nama'),
			'satuan' => $this->input->post('satuan'),
			'pelaksana' => $this->input->post('ak_pelaksana'),
			'pelaksana_lanjutan' => $this->input->post('ak_pelaksanalanjutan'),
			'penyelia' => $this->input->post('ak_penyelia'),
			'pertama' => $this->input->post('ak_pertama'),
			'muda' => $this->input->post('ak_muda'),
			'madya' => $this->input->post('ak_madya'),
			'id_org' => $this->input->post('org'),
			);
		$insert = $this->kegiatan->ubah_kegiatan(array('id_keg' => $this->input->post('id')), $data);
		$this->kegiatan->setuju_baru_by_id($this->input->post('id'));
		echo json_encode(array("status" => TRUE));
	}

	public function get_idmax(){
		$data=$this->kegiatan->get_idmax();
		echo json_encode($data);
	}

	public function get_kegiatan($id)
	{
		$data = $this->kegiatan->get_kegiatan_byid2($id);
		echo json_encode($data);
	}

	public function hapus_kegiatan($id)
	{
		$this->kegiatan->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function hapus_kegiatan_baru($id)
	{
		$this->kegiatan->delete_baru_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function get_butirkegiatan()
	{
		$tingkat = $this->input->post('tingkat');
		$q = $this->kegiatan->get_butirkegiatan_byid($tingkat);
		$output = '';
		if($q->num_rows() > 0){
			$keg = $q->result();
				$output .= '<option selected value=""></option>';
			foreach ($keg as $result) {
				$output .= '<option value="'.$result->id_butirkegiatan.'">'.$result->butir_kegiatan.'</option>';
			}
		}else{
				$output .= '<option selected disabled value=""></option>';
		}
		echo $output;
	}

	public function administrator()
		{
			if($this->session->userdata('id_admin')==1){
				$this->make_bread->add('Beranda','beranda.html');
				$this->make_bread->add('Kelola Data Administrator');
				$data['breadcrumb'] = $this->make_bread->output();
				$data['menu']=1;
				$data['title']='Kelola Pengguna Administrator';
				$data['level']=$this->pegawai->get_level();
				$data['pegawai']=$this->pegawai->get_pegawai_all();
				$data['org']=$this->pegawai->get_org();
				$data['page']='administrator/administrator_view';
				$this->load->vars($data);
				$this->load->view('template/layout');
			}else{
				redirect('login.html');
			}
		}

		public function list_administrator()
		{
		$list = $this->pegawai->get_datatables_administrator();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pegawai) {
			$no++;
			$row = array();
			$row[] = $pegawai->niplama;

			$nama_l="";
			if($pegawai->gelar_depan!=""){
				$nama_l = $pegawai->gelar_depan.' '.$pegawai->nama;
			}else{
				$nama_l = $pegawai->nama;
			}

			if($pegawai->gelar_belakang!=""){
				$nama_l = $nama_l.' '.$pegawai->gelar_belakang;
			}

			$row[] = $nama_l;
			$row[] = $pegawai->email;
			$row[] = $pegawai->nm_satker;
			if($pegawai->id_admin==1){
				$row[] = "Superadmin";
			}else if($pegawai->id_admin==2){
				$row[] = "Admin Provinsi";
			}else if($pegawai->id_admin==3){
				$row[] = "Admin Kabupaten/Kota";
			}
			//add html for action
			$row[] = '<a class="btn btn-sm btn-success noborder" href="javascript:void(0)" style="min-width:70px; margin-bottom:5px;" title="Edit" onclick="ubah_admin('."'".$pegawai->niplama."'".')"><i class="glyphicon glyphicon-pencil"></i> Data</a>
					<a class="btn btn-sm btn-danger noborder" href="javascript:void(0)" style="min-width:70px; margin-bottom:5px;" title="Hapus" onclick="hapus_admin('."'".$pegawai->niplama."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->pegawai->count_all_administrator(),
						"recordsFiltered" => $this->pegawai->count_filtered_administrator(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function get_admin($id)
	{
		$data = $this->pegawai->get_administrator_by_id($id);
		echo json_encode($data);
	}

	public function tambah_admin()
	{
		$data = array(
		  'niplama' => $this->input->post('niplama2'),
			'id_admin' => $this->input->post('level'),
			);
		$insert = $this->pegawai->tambah_admin($data);
		echo json_encode(array("status" => TRUE));
	}


	public function ubah_admin()
	{
		//$this->validasi_pegawai();
		$data = array(
				'id_admin' => $this->input->post('level'),
			);
		$insert = $this->pegawai->ubah_admin(array('niplama' => $this->input->post('niplama2')), $data);
		echo json_encode(array("status" => TRUE));
	}


	public function hapus_admin($id)
	{
		$this->pegawai->delete_admin_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function list_kegiatan_baru()
	{
			$list = $this->kegiatan->get_datatables_baru();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $kegiatan) {
				$no++;
				$row = array();
				$row[] = $kegiatan->n_keg;
				$row[] = $kegiatan->nm_org;
				$row[] = $kegiatan->satuan;
				$row[] = $kegiatan->nama_l;

				//add html for action
				$row[] = '<a class="btn btn-sm btn-primary noborder" style="min-width:70px; margin-bottom:5px;" href="javascript:void(0)" title="Edit" onclick="ubah_kegiatan('."'".$kegiatan->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Data</a>
						<a class="btn btn-sm btn-danger noborder" style="min-width:70px; margin-bottom:5px;" href="javascript:void(0)" title="Hapus" onclick="hapus_kegiatan('."'".$kegiatan->id."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';

				$data[] = $row;
			}

			$output = array(
							"draw" => $_POST['draw'],
							"recordsTotal" => $this->kegiatan->count_baru_all(),
							"recordsFiltered" => $this->kegiatan->count_baru_filtered(),
							"data" => $data,
					);
			//output to json format
			echo json_encode($output);
		}

		public function get_password()
		{
			$data = $this->pegawai->get_pass();
			echo json_encode($data);
		}

		public function ubah_password()
		{
			$data = array(
					'password' => $this->encrypt->encode($this->input->post('new_pass')),
				);
			$insert = $this->pegawai->ubah_password(array('username' => $this->session->userdata('uname')), $data);
			echo json_encode(array("status" => TRUE));
		}


}
?>
