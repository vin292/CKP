<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ckp_model extends CI_Model {

	var $table = 'ckp_new';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

public function get_ckp_bynip($m, $id, $jabatan){
		$bulan_ckp = bulan($m);
		$this->db->select('nm_keg, satuan, target, realisasi, ((realisasi/target)*100) as persentase, kualitas')
				 ->from($this->table)
				 ->where("bulan_ckp", $bulan_ckp)
				 ->where('jenis >=', '1')
				 ->where('status_realisasi', '1')
				 ->where('niplama', $id);
				 $query = $this->db->get();
	 		  return $query;
	}

	public function get_ck_bynip($m, $id){
			$s_date = date("Y-".$m."-01");
			$e_date = date("Y-".$m."-t", strtotime($s_date));
			$this->db->select('master_kepatuhan.ket as ket, master_kepatuhan.satuan as satuan, target, realisasi, ((realisasi/target)*100) as persentase')
					 ->from('ckepatuhan')
					 ->join('master_kepatuhan', 'ckepatuhan.id_kepatuhan=master_kepatuhan.id')
					 ->where("(tgl_ck >='".$s_date."' AND tgl_ck <= '".$e_date."')")
					 ->where('niplama', $id);
					 $query = $this->db->get();
		 		  return $query;
		}

public function get_ckpt_bynip($m, $id, $jabatan){
					$s_date = date("Y-".$m."-01");
					$e_date = date("Y-".$m."-t", strtotime($s_date));
					$this->db->select('master_kegiatan.n_keg as n_keg, master_kegiatan.satuan as satuan, target, realisasi, ((realisasi/target)*100) as persentase, kualitas, (master_kegiatan.'.$jabatan.'*ckp.realisasi) as ak, master_kegiatan.'.$jabatan.' as pengali ')
							 ->from('ckp')
							 ->join('master_kegiatan', 'ckp.id_keg=master_kegiatan.id_keg')
							 ->where("(tgl_ckp >='".$s_date."' AND tgl_ckp <= '".$e_date."')")
							 ->where('jenis =', '0')
							 ->where('status_realisasi >=', '1')
							 ->where('niplama', $id);
							 $query = $this->db->get();
				 		  return $query;
}

public function get_sum_ak_bynip($m, $id, $jabatan){
	$s_date = date("Y-".$m."-01");
	$e_date = date("Y-".$m."-t", strtotime($s_date));
	$this->db->select('master_kegiatan.'.$jabatan.' as ak, sum(master_kegiatan.'.$jabatan.'*ckp.realisasi) as sum_ak')
				 ->from('ckp')
				->join('master_kegiatan', 'ckp.id_keg=master_kegiatan.id_keg')
				->where("(tgl_ckp >='".$s_date."' AND tgl_ckp <= '".$e_date."')")
				->where('status_realisasi >=', '1')
				 ->where('niplama', $id);
	$query = $this->db->get();
	return $query->row();
}

public function get_av_persentage_bynip($m, $id){
		$bulan_ckp = bulan($m);
  		$this->db->select('avg(((realisasi/target)*100)) as av_percentage')
  				 ->from($this->table)
				 ->where("bulan_ckp", $bulan_ckp)
				 ->where('status_realisasi >=', '1')
  				 ->where('niplama', $id);
  				 $query = $this->db->get();
  	 		  return $query->row();
}

public function get_av_kualitas_bynip($m, $id){
	$bulan_ckp = bulan($m);
        $this->db->select('avg(kualitas) as av_kualitas')
             ->from($this->table)
			 ->where("bulan_ckp", $bulan_ckp)
			 ->where('status_realisasi >=', '1')
             ->where('niplama', $id);
             $query = $this->db->get();
            return $query->row();
}

public function check_es4_by_id($id)
{
$this->db->from('master_pegawai');
$this->db->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama');
$this->db->where('autentifikasi.id_level', '3');
$this->db->where('master_pegawai.id_org', $id);
$this->db->where('master_pegawai.id_satker', $this->session->userdata('satker'));
$query = $this->db->get();

return $query->num_rows();
}

public function check_es3_by_id($id)
{
$this->db->from('master_pegawai');
$this->db->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama');
$this->db->where('autentifikasi.id_level', '2');
$this->db->where('master_pegawai.id_org', substr($id, 0, -2)."00");
$this->db->where('master_pegawai.id_satker', $this->session->userdata('satker'));
$query = $this->db->get();

return $query->num_rows();
}

public function get_penilai(){
		if(strcmp(substr($this->session->userdata('satker'), -2),'00')==0){
			if(strcmp($this->session->userdata('lvl'), '4')==0){
				if($this->check_es4_by_id($this->session->userdata('organisasi'))>0){
					$this->db->select('nama, gelar_depan, master_pegawai.niplama, nipbaru, gelar_belakang, master_gol.pangkat as pangkat, master_gol.n_gol, master_org.nm_org, master_org.id_org')
							 ->from('master_pegawai')
							 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
							 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
							 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
							 ->where('autentifikasi.id_level', '3')
							 ->where('master_pegawai.id_org', $this->session->userdata('organisasi'))
							 ->where('master_pegawai.id_satker', $this->session->userdata('satker'));
				}else if($this->check_es3_by_id($this->session->userdata('organisasi'))>0){
					$this->db->select('nama, gelar_depan, master_pegawai.niplama, nipbaru, gelar_belakang, master_gol.pangkat as pangkat, master_gol.n_gol, master_org.nm_org, master_org.id_org')
							 ->from('master_pegawai')
							 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
							 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
							 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
							 ->where('autentifikasi.id_level', '2')
							 ->where('master_pegawai.id_org', substr($this->session->userdata('organisasi'), 0, -2)."00")
							 ->where('master_pegawai.id_satker', $this->session->userdata('satker'));
				}else{
					$this->db->select('nama, gelar_depan, master_pegawai.niplama, nipbaru, gelar_belakang, master_gol.pangkat as pangkat, master_gol.n_gol, master_org.nm_org, master_org.id_org')
							 ->from('master_pegawai')
							 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
							 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
							 ->where('master_pegawai.id_org', '92000');
				}
			}else if (strcmp($this->session->userdata('lvl'), '3')==0) {
					if($this->check_es3_by_id($this->session->userdata('organisasi'))>0){
						$this->db->select('nama, gelar_depan, master_pegawai.niplama, nipbaru, gelar_belakang, master_gol.pangkat as pangkat, master_gol.n_gol, master_org.nm_org, master_org.id_org')
								 ->from('master_pegawai')
								 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
								 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
								 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
								 ->where('autentifikasi.id_level', '2')
								 ->where('master_pegawai.id_org', substr($this->session->userdata('organisasi'), 0, -2)."00")
								 ->where('master_pegawai.id_satker', $this->session->userdata('satker'));
					}else{
						$this->db->select('nama, gelar_depan, master_pegawai.niplama, nipbaru, gelar_belakang, master_gol.pangkat as pangkat, master_gol.n_gol, master_org.nm_org, master_org.id_org')
								 ->from('master_pegawai')
								 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
								 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
								 ->where('master_pegawai.id_org', '92000');
					}
			}else if (strcmp($this->session->userdata('lvl'), '2')==0) {
				$this->db->select('nama, gelar_depan, master_pegawai.niplama, nipbaru, gelar_belakang, master_gol.pangkat as pangkat, master_gol.n_gol, master_org.nm_org, master_org.id_org')
						 ->from('master_pegawai')
						 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
						 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
						 ->where('master_pegawai.id_org', '92000');
			}else{
				$this->db->select('nama, gelar_depan, master_pegawai.niplama, nipbaru, gelar_belakang, master_gol.pangkat as pangkat, master_gol.n_gol, master_org.nm_org, master_org.id_org')
						 ->from('master_pegawai')
						 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
						 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
						 ->where('master_pegawai.id_org', '92000');
			}
		}else{
			if(strcmp($this->session->userdata('lvl'), '4')==0){
				if(strcmp($this->session->userdata('organisasi'), '92870')==0){
					$this->db->select('nama, gelar_depan, master_pegawai.niplama, nipbaru, gelar_belakang, master_gol.pangkat as pangkat, master_gol.n_gol, master_org.nm_org, master_org.id_org')
							 ->from('master_pegawai')
							 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
							 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
							 ->join('master_satker', 'master_pegawai.id_satker='.$this->session->userdata('satker'))
							 ->where('master_pegawai.id_org', '92800');
				}else{
					if($this->check_es4_by_id($this->session->userdata('organisasi'))>0){
						$this->db->select('nama, gelar_depan, master_pegawai.niplama, nipbaru, gelar_belakang, master_gol.pangkat as pangkat, master_gol.n_gol, master_org.nm_org, master_org.id_org')
								 ->from('master_pegawai')
								 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
								 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
								 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
								 ->where('autentifikasi.id_level', '3')
								 ->where('master_pegawai.id_org', $this->session->userdata('organisasi'))
								 ->where('master_pegawai.id_satker', $this->session->userdata('satker'));
					}else{
						$this->db->select('nama, gelar_depan, master_pegawai.niplama, nipbaru, gelar_belakang, master_gol.pangkat as pangkat, master_gol.n_gol, master_org.nm_org, master_org.id_org')
								 ->from('master_pegawai')
								 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
								 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
								 ->join('master_satker', 'master_pegawai.id_satker='.$this->session->userdata('satker'))
								 ->where('master_pegawai.id_org', '92800');
					}
				}
			}else if (strcmp($this->session->userdata('lvl'), '3')==0) {
				$this->db->select('nama, gelar_depan, master_pegawai.niplama, nipbaru, gelar_belakang, master_gol.pangkat as pangkat, master_gol.n_gol, master_org.nm_org, master_org.id_org')
						 ->from('master_pegawai')
						 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
						 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
						 ->join('master_satker', 'master_pegawai.id_satker='.$this->session->userdata('satker'))
						 ->where('master_pegawai.id_org', '92800');
			}else if (strcmp($this->session->userdata('lvl'), '2')==0) {
				$this->db->select('nama, gelar_depan, master_pegawai.niplama, nipbaru, gelar_belakang, master_gol.pangkat as pangkat, master_gol.n_gol, master_org.nm_org, master_org.id_org')
						 ->from('master_pegawai')
						 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
						 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
						 ->where('master_pegawai.id_org', '92000');
			}
		}
        $query = $this->db->get();
        return $query->row();
}

public function get_kuantitas_kualitas_by_id($m, $nip){
	$s_date = date("Y-".$m."-01");
	$e_date = date("Y-".$m."-t", strtotime($s_date));
			$query=$this->db->query("select CONCAT(gelar_depan, ' ',nama, ' ',gelar_belakang) as nama_l, (avg(ckp.realisasi/ckp.target)*100) as kuantitas, (avg(ckp.kualitas)) as kualitas
													from ckp
													join master_kegiatan on ckp.id_keg=master_kegiatan.id_keg
													join master_pegawai on master_pegawai.niplama=ckp.niplama
													where (tgl_ckp >='".$s_date."' AND tgl_ckp <= '".$e_date."' AND ckp.status_realisasi ='1' AND master_pegawai.niplama= '".$nip."')");
		return $query->row();
}

		public function get_ckp_all($m){
			$s_date = date("Y-".$m."-01");
			$e_date = date("Y-".$m."-t", strtotime($s_date));
			if(strcmp(substr($this->session->userdata('satker'), -2),'00')==0){
				if (strcmp($this->session->userdata('lvl'), '4')==0) {
					$query=$this->db->query("select CONCAT(gelar_depan, ' ',nama, ' ',gelar_belakang) as nama_l, ckp from master_pegawai
															left outer join
															(select master_pegawai.nama, (0.5*avg((ckp.realisasi/ckp.target)*100) + (0.5*avg(ckp.kualitas))) as ckp
															from ckp
															join master_kegiatan on ckp.id_keg=master_kegiatan.id_keg
															join master_pegawai on master_pegawai.niplama=ckp.niplama
															where (tgl_ckp >='".$s_date."' AND tgl_ckp <= '".$e_date."' AND ckp.status_realisasi ='1')
															group by master_pegawai.nama) t1 using (nama)
															where (master_pegawai.niplama='".$this->session->userdata('niplama')."' AND master_pegawai.id_org='".$this->session->userdata('organisasi')."')
															order by nama");
				}else	if (strcmp($this->session->userdata('lvl'), '3')==0) {
					$query=$this->db->query("select CONCAT(gelar_depan, ' ',nama, ' ',gelar_belakang) as nama_l, ckp from master_pegawai
															left outer join
															(select master_pegawai.nama, (0.5*avg((ckp.realisasi/ckp.target)*100) + (0.5*avg(ckp.kualitas))) as ckp
															from ckp
															join master_kegiatan on ckp.id_keg=master_kegiatan.id_keg
															join master_pegawai on master_pegawai.niplama=ckp.niplama
															where (tgl_ckp >='".$s_date."' AND tgl_ckp <= '".$e_date."' AND ckp.status_realisasi ='1')
															group by master_pegawai.nama) t1 using (nama)
															where (master_pegawai.id_satker='".$this->session->userdata('satker')."' AND master_pegawai.id_org='".$this->session->userdata('organisasi')."')
															order by nama");
				}else if (strcmp($this->session->userdata('lvl'), '2')==0) {
					$query=$this->db->query("select CONCAT(gelar_depan, ' ',nama, ' ',gelar_belakang) as nama_l, ckp from master_pegawai
															left outer join
															(select master_pegawai.nama, (0.5*avg((ckp.realisasi/ckp.target)*100) + (0.5*avg(ckp.kualitas))) as ckp
															from ckp
															join master_kegiatan on ckp.id_keg=master_kegiatan.id_keg
															join master_pegawai on master_pegawai.niplama=ckp.niplama
															where (tgl_ckp >='".$s_date."' AND tgl_ckp <= '".$e_date."' AND ckp.status_realisasi ='1')
															group by master_pegawai.nama) t1 using (nama)
															join autentifikasi on autentifikasi.niplama=master_pegawai.niplama
															where (master_pegawai.id_org LIKE '%".substr($this->session->userdata('organisasi'),0,3)."%' AND autentifikasi.id_level='3' AND master_pegawai.id_satker='".$this->session->userdata('satker')."')
															order by nama");
				}else{
					$query=$this->db->query("select CONCAT(gelar_depan, ' ',nama, ' ',gelar_belakang) as nama_l, ckp from master_pegawai
															left outer join
															(select master_pegawai.nama, (0.5*avg((ckp.realisasi/ckp.target)*100) + (0.5*avg(ckp.kualitas))) as ckp
															from ckp
															join master_kegiatan on ckp.id_keg=master_kegiatan.id_keg
															join master_pegawai on master_pegawai.niplama=ckp.niplama
															where (tgl_ckp >='".$s_date."' AND tgl_ckp <= '".$e_date."' AND ckp.status_realisasi ='1')
															group by master_pegawai.nama) t1 using (nama)
															join autentifikasi on autentifikasi.niplama=master_pegawai.niplama
															where (autentifikasi.id_level='2')
															order by nama");
				}

				}else{
					if (strcmp($this->session->userdata('lvl'), '4')==0) {
						$query=$this->db->query("select CONCAT(gelar_depan, ' ',nama, ' ',gelar_belakang) as nama_l, ckp from master_pegawai
																left outer join
																(select master_pegawai.nama, (0.5*avg((ckp.realisasi/ckp.target)*100) + (0.5*avg(ckp.kualitas))) as ckp
																from ckp
																join master_kegiatan on ckp.id_keg=master_kegiatan.id_keg
																join master_pegawai on master_pegawai.niplama=ckp.niplama
																where (tgl_ckp >='".$s_date."' AND tgl_ckp <= '".$e_date."' AND ckp.status_realisasi ='1')
																group by master_pegawai.nama) t1 using (nama)
																where (master_pegawai.niplama='".$this->session->userdata('niplama')."' AND master_pegawai.id_org='".$this->session->userdata('organisasi')."')
																order by nama");
					}else if (strcmp($this->session->userdata('lvl'), '3')==0) {
						$query=$this->db->query("select CONCAT(gelar_depan, ' ',nama, ' ',gelar_belakang) as nama_l, ckp from master_pegawai
																left outer join
																(select master_pegawai.nama, (0.5*avg((ckp.realisasi/ckp.target)*100) + (0.5*avg(ckp.kualitas))) as ckp
																from ckp
																join master_kegiatan on ckp.id_keg=master_kegiatan.id_keg
																join master_pegawai on master_pegawai.niplama=ckp.niplama
																where (tgl_ckp >='".$s_date."' AND tgl_ckp <= '".$e_date."' AND ckp.status_realisasi ='1')
																group by master_pegawai.nama) t1 using (nama)
																where (master_pegawai.id_satker='".$this->session->userdata('satker')."' AND master_pegawai.id_org='".$this->session->userdata('organisasi')."')
																order by nama");
					}else{
						$query=$this->db->query("select CONCAT(gelar_depan, ' ',nama, ' ',gelar_belakang) as nama_l, ckp from master_pegawai
																left outer join
																(select master_pegawai.nama, (0.5*avg((ckp.realisasi/ckp.target)*100) + (0.5*avg(ckp.kualitas))) as ckp
																from ckp
																join master_kegiatan on ckp.id_keg=master_kegiatan.id_keg
																join master_pegawai on master_pegawai.niplama=ckp.niplama
																where (tgl_ckp >='".$s_date."' AND tgl_ckp <= '".$e_date."' AND ckp.status_realisasi ='1')
																group by master_pegawai.nama) t1 using (nama)
																where (master_pegawai.id_satker='".$this->session->userdata('satker')."')
																order by nama");
					}

				}
				return $query->result_array();
		}


}
