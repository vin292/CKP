<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Skp extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('isLogin')) {
				redirect('login.html');
		}else{
			$this->load->model('kegiatan/kegiatan_model','kegiatan');
			$this->load->model('pegawai/pegawai_model','pegawai');
			$this->load->model('skp/skp_model','skp');
			$this->load->model('ckp/ckp_model','ckp');
			$this->load->model('fungsional/fungsional_model','fungsional');
		}
	}

  public function index()
  {
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Sasaran Kinerja Pegawai');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=5;
		$data['title']='Sasaran Kinerja Pegawai';
		$data['skp']=$this->skp->ambil_skp_bynip($this->session->userdata('niplama'))->result();
		$data['skp_row']=$this->skp->ambil_skp_bynip($this->session->userdata('niplama'))->num_rows();
		$data['fungsional']=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->row();
		$data['fungsional_row']=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->num_rows();
		$data['page']='skp/skp_detil_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
  }

public function input_skp(){
	$this->make_bread->add('Beranda','beranda.html');
	$this->make_bread->add('Sasaran Kinerja Pegawai', 'sasaran-kinerja-pegawai.html');
	$this->make_bread->add('Entri SKP');
	$data['breadcrumb'] = $this->make_bread->output();
	$data['menu']=10;
	$data['title']='Entri Sasaran Kinerja Pegawai';
	$data['kegiatan']=$this->kegiatan->get_kegiatan();
	$data['page']='skp/skp_input_view';
	$this->load->vars($data);
	$this->load->view('template/layout');
}

public function edit_skp(){
		$this->make_bread->add('Beranda','beranda.html');
		$this->make_bread->add('Sasaran Kinerja Pegawai', 'sasaran-kinerja-pegawai.html');
		$this->make_bread->add('Ubah SKP');
		$data['breadcrumb'] = $this->make_bread->output();
		$data['menu']=5;
		$data['title']='Ubah Sasaran Kinerja Pegawai';
		$data['skp']=$this->skp->ambil_skp_bynip($this->session->userdata('niplama'))->result();
		$data['skp_row']=$this->skp->ambil_skp_bynip($this->session->userdata('niplama'))->num_rows();
		$data['fungsional']=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->row();
		$data['fungsional_row']=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->num_rows();
		$data['page']='skp/skp_edit_view';
		$this->load->vars($data);
		$this->load->view('template/layout');
	}

	public function get_skp($id)
	{
		$data = $this->skp->get_skp_byid($id);
		echo json_encode($data);
	}

	public function hapus_skp($id)
{
	$this->skp->delete_by_id($id);
	echo json_encode(array("status" => TRUE));
}

public function ubah_skp()
{
	$data = array(
		'id' => $this->input->post('id'),
		'kuantitas' => $this->input->post('kuantitas'),
		'kualitas' => $this->input->post('kualitas'),
		'waktu' => $this->input->post('waktu'),
		'satuan_waktu' => $this->input->post('satuan_waktu'),
		'biaya' => $this->input->post('biaya'),
		);
	$insert = $this->skp->ubah_skp(array('id' => $this->input->post('id')), $data);
	echo json_encode(array("status" => TRUE));
}

public function simpan_skp(){
		$number = count($_POST["id_kegiatan"]);
		if($number > 0)
		{
				 for($i=0; $i<$number; $i++)
				 {
				$data = array(
					'id_keg' => $_POST["id_kegiatan"][$i],
					'kuantitas' => $_POST["kuantitas"][$i],
					'kualitas' => $_POST["kualitas"][$i],
					'waktu' => $_POST["waktu"][$i],
					'satuan_waktu' => $_POST["satuan_waktu"][$i],
					'biaya' => $_POST["biaya"][$i],
					'niplama' => $this->session->userdata('niplama'),
					'tgl_buat' => date('Y-m-d'),
					'tgl_skp' => $this->input->post('tgl_skp'),
					);
				 $insert = $this->skp->tambah_skp($data);
			 }
		 }
		 echo json_encode(array("status" => TRUE));
	}

	public function skp_download(){
		$this->load->library("Excel/PHPExcel");
		$objPHPExcel = new PHPExcel();

		$skp=$this->skp->ambil_skp_bynip($this->session->userdata('niplama'))->result();
		$aut=$this->pegawai->get_aut($this->session->userdata('niplama'));
		$penilai=$this->ckp->get_penilai();
		$aut_penilai=$this->pegawai->get_aut($penilai->niplama);
		$pegawai=$this->pegawai->get_pegawai_by_id($this->session->userdata('niplama'));
		$satker=$this->pegawai->get_satker_by_nip($this->session->userdata('niplama'));
		$gol = $this->skp->ambil_gol_bynip($this->session->userdata('niplama'))->row();
		$nama = $pegawai->nama;
		$namap = $penilai->nama;
		$nipbaru = $pegawai->nipbaru;
		$jabatan = $aut->id_level;
		$seksi = $pegawai->nm_org;
		$gelar_d = $pegawai->gelar_depan;
		$gelar_b = $pegawai->gelar_belakang;
		$gelar_dp = $penilai->gelar_depan;
		$gelar_bp = $penilai->gelar_belakang;
		$seksi_id = $pegawai->id_org;
		$satker_id = $satker->id_satker;
		$satker_n = $satker->nm_satker;
		$pg=$pegawai->pangkat.', '.$pegawai->n_gol;
		$so='';
		$jb='';
		$jb_penilai='';
		if(strcmp($penilai->id_org,'92000')==0){
			$jb_penilai='Kepala '.$satker_n;
		}else if(strcmp($penilai->id_org,'92800')==0){
			$jb_penilai='Kepala '.$satker_n;
		}else{
			$jb_penilai='Kepala '.$penilai->nm_org;
		}


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

		$skp=$this->skp->ambil_skp_bynip($this->session->userdata('niplama'))->result();

		$Fungsional_row=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->num_rows();
		$Fungsional=$this->fungsional->get_info_fung_by_id($this->session->userdata('niplama'))->row();

		$objPHPExcel->getProperties()->setCreator("Yusfil Khoir Pulungan")
									 ->setLastModifiedBy("Yusfil Khoir Pulungan")
									 ->setTitle("Sasaran Kinerja Pegawai")
									 ->setSubject("Sasaran Kinerja Pegawai")
									 ->setDescription("Sasaran Kinerja Pegawai")
									 ->setKeywords("bps skp")
									 ->setCategory("Sasaran Kinerja Pegawai");

		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load("assets/templates/format_skp.xlsx");

		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->setCellValue('B2', 'AN. '.strtoupper($nama_l));
		$objPHPExcel->getActiveSheet()->setCellValue('D4', strtoupper($nama_lp));
		$objPHPExcel->getActiveSheet()->setCellValue('D5', $penilai->nipbaru);
		$objPHPExcel->getActiveSheet()->setCellValue('D6', $penilai->pangkat.', '.$penilai->n_gol);
		$objPHPExcel->getActiveSheet()->setCellValue('D7', $jb_penilai);
		$objPHPExcel->getActiveSheet()->setCellValue('D8', $so);
		$objPHPExcel->getActiveSheet()->setCellValue('J4', $nama_l);
		$objPHPExcel->getActiveSheet()->setCellValue('J5', $nipbaru);
		$objPHPExcel->getActiveSheet()->setCellValue('J6', $pg);
		$objPHPExcel->getActiveSheet()->setCellValue('J7', $jb);
		$objPHPExcel->getActiveSheet()->setCellValue('J8', $so);

		$baseRow = 13;
		$utama = 0;
		$styleArray = array(
			'borders' => array(
				'left' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);

		foreach($skp as $r => $dataRow) {
			$utama = $baseRow + $r;
			$ak =0;
			if($Fungsional_row>0){
				if(strcmp($Fungsional->jabatan, 'A0101')==0){
					$ak=$dataRow->pelaksana_pemula;
				}elseif (strcmp($Fungsional->jabatan, 'A0102')==0) {
					$ak=$dataRow->pelaksana;
				}elseif (strcmp($Fungsional->jabatan, 'A0103')==0) {
					$ak=$dataRow->pelaksana_lanjutan;
				}elseif (strcmp($Fungsional->jabatan, 'A0104')==0) {
					$ak=$dataRow->penyelia;
				}elseif (strcmp($Fungsional->jabatan, 'A0105')==0) {
					$ak=$dataRow->pertama;
				}elseif (strcmp($Fungsional->jabatan, 'A0106')==0) {
					$ak=$dataRow->muda;
				}elseif (strcmp($Fungsional->jabatan, 'A0107')==0) {
					$ak=$dataRow->madya;
				}elseif (strcmp($Fungsional->jabatan, 'A0108')==0) {
					$ak=$dataRow->utama;
				}
			}else{
				$ak=0;
			}

			$objPHPExcel->getActiveSheet()->insertNewRowBefore($utama,1);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$utama, $r+1);
			$objPHPExcel->getActiveSheet()->mergeCells('C'.$utama.':D'.$utama);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$utama.':M'.$utama)->getFont()->setBold(false);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$utama.':M'.$utama)->getFont()->setUnderline(false);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$utama, $dataRow->n_keg);
			if($Fungsional_row>0){
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$utama, $dataRow->kode_unsur.'.'.$dataRow->kode_subunsur.'.'.$dataRow->kode_butirkegiatan);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$utama, $ak);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$utama)->getNumberFormat()->setFormatCode('#,##0.00');
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$utama, $ak*$dataRow->kuantitas);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$utama)->getNumberFormat()->setFormatCode('#,##0.00');
			}else{
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$utama, '');
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$utama, '');
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$utama, '');
			}
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$utama, $dataRow->kuantitas);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$utama, $dataRow->satuan);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$utama, $dataRow->kualitas);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$utama, $dataRow->waktu);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$utama, $dataRow->satuan_waktu);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$utama, $dataRow->biaya);
		}
		if($Fungsional_row>0){
			$objPHPExcel->getActiveSheet()->setCellValue('G'.($utama+2), '=SUM(G'.($baseRow).':G'.($utama+1).')');
			$objPHPExcel->getActiveSheet()->getStyle('G'.($utama+2))->getNumberFormat()->setFormatCode('#,##0.00');
		}else{
			$objPHPExcel->getActiveSheet()->setCellValue('G'.($utama+2), '');
		}
		$objPHPExcel->getActiveSheet()->setCellValue('D'.($utama+8), $nama_lp);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.($utama+9), $penilai->nipbaru);
		$objPHPExcel->getActiveSheet()->setCellValue('I'.($utama+8), $nama_l);
		$objPHPExcel->getActiveSheet()->setCellValue('I'.($utama+9), $nipbaru);
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Sasaran Kinerja Pegawai.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}


}

?>
