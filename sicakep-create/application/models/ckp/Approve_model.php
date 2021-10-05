<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Approve_model extends CI_Model {

	var $table = 'ckp_new';
	var $column_order = array('nm_keg', 'nama', 'target','realisasi','status_realisasi', null);
	var $column_search = array('nm_keg', 'nama', 'target','realisasi','status_realisasi');
	var $order = array('tgl_buat' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	private function _get_datatables_query($m)
	{
		$ym = explode("-", $m);
		$m = $ym[0];
		$y = $ym[1];

		$m=bulan($m);

		if(strcmp($this->session->userdata('lvl'), '1')==0){
			$this->db->select('ckp_new.id as id, target, realisasi, bulan_ckp, nama, gelar_depan, gelar_belakang, nm_keg, satuan, status_target, status_realisasi')
					 ->from('ckp_new')
					 ->join('master_pegawai', 'ckp_new.niplama=master_pegawai.niplama')
					 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
					 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
					 ->where('autentifikasi.id_level =', '2')
					 ->where("bulan_ckp", $m)
					 ->where("tahun_ckp", $y)
					 ->order_by("tgl_buat", "desc");
		}else if(strcmp($this->session->userdata('lvl'), '2')==0){
			if(strcmp(substr($this->session->userdata('satker'), 2, 2), '00')==0){
				$this->db->select('ckp_new.id as id, target, realisasi, bulan_ckp, nama, gelar_depan, gelar_belakang, nm_keg, satuan, status_target, status_realisasi')
						 ->from('ckp_new')
						 ->join('master_pegawai', 'ckp_new.niplama=master_pegawai.niplama')
						 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
						 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
						 ->where('master_pegawai.id_org LIKE "%'.substr($this->session->userdata('organisasi'), 0, 3).'%"')
						 ->where('master_pegawai.id_satker', $this->session->userdata('satker'))
						 ->where('autentifikasi.id_level =', '3')
						 ->where("bulan_ckp", $m)
						 ->where("tahun_ckp", $y)
						 ->order_by("tgl_buat", "desc");
			}else{
				$tu=$this->pegawai->check_92810();
				$sosial=$this->pegawai->check_92820();
				$produksi=$this->pegawai->check_92830();
				$distribusi=$this->pegawai->check_92840();
				$nerwilis=$this->pegawai->check_92850();
				$ipds=$this->pegawai->check_92860();
				$this->db->select('ckp_new.id as id, target, realisasi, bulan_ckp, nama, gelar_depan, gelar_belakang, nm_keg, satuan, status_target, status_realisasi')
						 ->from('ckp_new')
						 ->join('master_pegawai', 'ckp_new.niplama=master_pegawai.niplama')
						 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
						 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama');
						$querys='';
						if($tu < 1){
		 					$querys.='master_pegawai.id_org="92810" OR ';
		 				}
		 				if($sosial < 1){
		 					$querys.='master_pegawai.id_org="92820" OR ';
		 				}
		 				if($produksi < 1){
		 					$querys.='master_pegawai.id_org="92830" OR ';
		 				}
		 				if($distribusi < 1){
		 					$querys.='master_pegawai.id_org="92840" OR ';
		 				}
		 				if($nerwilis < 1){
		 					$querys.='master_pegawai.id_org="92850" OR ';
		 				}
		 				if($ipds < 1){
		 					$querys.='master_pegawai.id_org="92860" OR ';
		 				}

				$this->db->where("tahun_ckp=".$y." AND (bulan_ckp >='".$m."' AND master_pegawai.id_satker ='".$this->session->userdata('satker')."' AND (".$querys."master_pegawai.id_org='92870' OR autentifikasi.id_level='3'))");

			}
		}else{
		$this->db->select('ckp_new.id as id, target, realisasi, bulan_ckp, nama,  gelar_depan, gelar_belakang, nm_keg, satuan, status_target, status_realisasi')
				 ->from('ckp_new')
				 ->join('master_pegawai', 'ckp_new.niplama=master_pegawai.niplama')
				 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
				 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
				 ->where('autentifikasi.id_level >', '3')
				 ->where('master_pegawai.id_org', $this->session->userdata('organisasi'))
				 ->where('master_pegawai.id_satker', $this->session->userdata('satker'))
				 ->where("bulan_ckp", $m)
				 ->where("tahun_ckp", $y)
				 ->order_by("tgl_buat", "desc");
		}

		$i = 0;
		foreach ($this->column_search as $item)
		{
			if($_POST['search']['value'])
			{
				if($i===0)
				{
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				}else{
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if(count($this->column_search) - 1 == $i)
					$this->db->group_end();
			}
			$i++;
		}

		if(isset($_POST['order']))
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($m)
	{
		// $m=bulan($m);
		$this->_get_datatables_query($m);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	public function tambah_target($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function setuju($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}
  public function tolak($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}
	function count_filtered($m)
	{
		// $m=bulan($m);
		$this->_get_datatables_query($m);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($m)
	{
		$ym = explode("-", $m);
		$m = $ym[0];
		$y = $ym[1];

		$m=bulan($m);
		if(strcmp($this->session->userdata('lvl'), '1')==0){
			$this->db->select('ckp_new.id as id, target, realisasi, bulan_ckp, nama, gelar_depan, gelar_belakang, nm_keg, satuan, status_target, status_realisasi')
						 ->from('ckp_new')
					 ->join('master_pegawai', 'ckp_new.niplama=master_pegawai.niplama')
					 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
					 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
					 ->where('autentifikasi.id_level =', '2')
					 ->where("bulan_ckp", $m)
					 ->where("tahun_ckp", $y)
					 ->order_by("tgl_buat", "desc");
		}else if(strcmp($this->session->userdata('lvl'), '2')==0){
			if(strcmp(substr($this->session->userdata('satker'), 2, 2), '00')==0){
				$this->db->select('ckp_new.id as id, target, realisasi, bulan_ckp, nama, gelar_depan, gelar_belakang, nm_keg, satuan, status_target, status_realisasi')
						 ->from('ckp_new')
						 ->join('master_pegawai', 'ckp_new.niplama=master_pegawai.niplama')
						 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
						 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
						 ->where('master_pegawai.id_org LIKE "%'.substr($this->session->userdata('organisasi'), 0, 3).'%"')
						 ->where('master_pegawai.id_satker', $this->session->userdata('satker'))
						 ->where('autentifikasi.id_level =', '3')
						 ->where("bulan_ckp", $m)
						 ->where("tahun_ckp", $y)
						 ->order_by("tgl_buat", "desc");
			}else{
				$tu=$this->pegawai->check_92810();
				$sosial=$this->pegawai->check_92820();
				$produksi=$this->pegawai->check_92830();
				$distribusi=$this->pegawai->check_92840();
				$nerwilis=$this->pegawai->check_92850();
				$ipds=$this->pegawai->check_92860();
				$this->db->select('ckp_new.id as id, target, realisasi, bulan_ckp, nama, gelar_depan, gelar_belakang, nm_keg, satuan, status_target, status_realisasi')
					 	 ->from('ckp_new')
						 ->join('master_pegawai', 'ckp_new.niplama=master_pegawai.niplama')
						 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
						 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama');
						$querys='';
						if($tu < 1){
		 					$querys.='master_pegawai.id_org="92810" OR ';
		 				}
		 				if($sosial < 1){
		 					$querys.='master_pegawai.id_org="92820" OR ';
		 				}
		 				if($produksi < 1){
		 					$querys.='master_pegawai.id_org="92830" OR ';
		 				}
		 				if($distribusi < 1){
		 					$querys.='master_pegawai.id_org="92840" OR ';
		 				}
		 				if($nerwilis < 1){
		 					$querys.='master_pegawai.id_org="92850" OR ';
		 				}
		 				if($ipds < 1){
		 					$querys.='master_pegawai.id_org="92860" OR ';
		 				}

				$this->db->where("(master_pegawai.id_satker ='".$this->session->userdata('satker')."' AND (".$querys."master_pegawai.id_org='92870' OR autentifikasi.id_level='3'))");

			}
		}else{
			$this->db->select('ckp_new.id as id, target, realisasi, bulan_ckp, nama,  gelar_depan, gelar_belakang, nm_keg, satuan, status_target, status_realisasi')
			->from('ckp_new')
			->join('master_pegawai', 'ckp_new.niplama=master_pegawai.niplama')
			->join('master_org', 'master_pegawai.id_org=master_org.id_org')
			->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
			->where('autentifikasi.id_level >', '3')
			->where('master_pegawai.id_org', $this->session->userdata('organisasi'))
			->where('master_pegawai.id_satker', $this->session->userdata('satker'))
			->where("bulan_ckp", $m)
			->where("tahun_ckp", $y)
			->order_by("tgl_buat", "desc");
		}
		return $this->db->count_all_results();
	}

	public function get_approve($id){
		$this->db->select('ckp_new.id, target, realisasi, kualitas, bulan_ckp, master_pegawai.niplama as niplama, nm_keg, satuan, jenis')
				 ->from('ckp_new')
				 ->join('master_pegawai', 'ckp_new.niplama=master_pegawai.niplama')
				 ->where('ckp_new.id', $id);
				 $query = $this->db->get();

	 		  return $query->row();
	}


}
