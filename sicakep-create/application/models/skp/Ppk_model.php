<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ppk_model extends CI_Model {

	var $table = 'skp';
	var $column_order = array('orientasi_pelayanan', 'integritas', 'komitmen', 'disiplin','kerjasama', null);
	var $column_search = array('orientasi_pelayanan', 'integritas', 'komitmen', 'disiplin','kerjasama');
	var $order = array('id' => 'asc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_pk($niplama)
		{
			$s = date("Y-01-08");
			$e = date("Y-12-31");
			$s_date = date("Y-01-08", strtotime(date("Y-m-d", strtotime($s)) . " " . "+0 year"));
			$e_date = date("Y-12-31", strtotime(date("Y-m-d", strtotime($e)) . " " . "+1 year"));
			$this->db->select('*')
					 ->from('ppk')
					 ->where('niplama', $niplama)
					 ->where("(tgl_ppk >='".$s_date."' AND tgl_ppk <= '".$e_date."')");
					 $query = $this->db->get();
			return $query;
		}

	public function get_ppk($niplama){
		$s_date = date("Y-01-01");
		$e_date = date("Y-12-31");
		$query=$this->db->query("SELECT n_keg, skp.id_keg,skp.waktu, skp.satuan_waktu, master_kegiatan.satuan, sum(kuantitas) as target_kuantitas, avg(kualitas) as target_kualitas, realisasi_kuantitas, realisasi_kualitas, pelaksana, pelaksana_lanjutan, penyelia, pertama, muda, madya
			FROM skp
			LEFT OUTER JOIN
			(SELECT id_keg, sum(realisasi) as realisasi_kuantitas, avg(kualitas) as realisasi_kualitas
			FROM ckp
			WHERE niplama='".$niplama."' AND tgl_ckp >= '".$s_date."' AND tgl_ckp <= '".$e_date."' AND status_realisasi='1'
			GROUP BY id_keg
			ORDER BY id_keg) t1 using (id_keg)
			JOIN master_kegiatan ON master_kegiatan.id_keg=skp.id_keg
			WHERE niplama='".$niplama."' AND tgl_skp >= '".$s_date."' AND tgl_skp <= '".$e_date."'
			GROUP BY id_keg

			UNION

			SELECT n_keg, ckp.id_keg, 12 as waktu, 'Bulan' as satuan_waktu, master_kegiatan.satuan, sum(target) as target_kuantitas, 100 as target_kualitas, sum(realisasi) as realisasi_kuantitas, avg(kualitas) as realisasi_kualitas, pelaksana, pelaksana_lanjutan, penyelia, pertama, muda, madya
			FROM ckp
			JOIN master_kegiatan ON master_kegiatan.id_keg=ckp.id_keg
			WHERE niplama='".$niplama."' AND tgl_ckp >= '".$s_date."' AND tgl_ckp <= '".$e_date."' AND status_realisasi='1' AND ckp.id_keg NOT IN (SELECT id_keg FROM skp WHERE niplama='".$niplama."' AND tgl_skp >= '".$s_date."' AND tgl_skp <= '".$e_date."')
			GROUP BY id_keg
			ORDER BY id_keg");
		return $query;
	}


		private function _get_datatables_query()
	{
		$s = date("Y-01-08");
		$e = date("Y-12-31");
		$s_date = date("Y-01-08", strtotime(date("Y-m-d", strtotime($s)) . " " . "+0 year"));
		$e_date = date("Y-12-31", strtotime(date("Y-m-d", strtotime($e)) . " " . "+1 year"));
		if(strcmp(substr($this->session->userdata('satker'), -2),'00')==0){
			if (strcmp($this->session->userdata('lvl'), '3')==0) {
				$this->db->select('*')
						 ->from('ppk')
						 ->join('master_pegawai', 'master_pegawai.niplama=ppk.niplama')
						 ->join('autentifikasi', 'autentifikasi.niplama=ppk.niplama')
						 ->where('autentifikasi.id_level =', '4')
						 ->where('master_pegawai.id_org', $this->session->userdata('organisasi'))
						 ->where('master_pegawai.id_satker', $this->session->userdata('satker'))
						 ->where("(tgl_ppk >='".$s_date."' AND tgl_ppk <= '".$e_date."')");
			}else if (strcmp($this->session->userdata('lvl'), '2')==0) {
				$this->db->select('*')
						 ->from('ppk')
						 ->join('master_pegawai', 'master_pegawai.niplama=ppk.niplama')
						 ->join('autentifikasi', 'autentifikasi.niplama=ppk.niplama')
						 ->where('autentifikasi.id_level =', '3')
						 ->where('master_pegawai.id_org LIKE "%'.substr($this->session->userdata('organisasi'), 0, 3).'%"')
						 ->where("(tgl_ppk >='".$s_date."' AND tgl_ppk <= '".$e_date."')");
			}else{
				$this->db->select('*')
						 ->from('ppk')
						 ->join('master_pegawai', 'master_pegawai.niplama=ppk.niplama')
						 ->join('autentifikasi', 'autentifikasi.niplama=ppk.niplama')
						 ->where('autentifikasi.id_level =', '2')
						 ->where("(tgl_ppk >='".$s_date."' AND tgl_ppk <= '".$e_date."')");
			}
		}else{
			if (strcmp($this->session->userdata('lvl'), '3')==0) {
				$this->db->select('*')
						 ->from('ppk')
						 ->join('master_pegawai', 'master_pegawai.niplama=ppk.niplama')
						 ->join('autentifikasi', 'autentifikasi.niplama=ppk.niplama')
						 ->where('autentifikasi.id_level =', '4')
						 ->where('master_pegawai.id_org', $this->session->userdata('organisasi'))
						 ->where('master_pegawai.id_satker', $this->session->userdata('satker'))
						 ->where("(tgl_ppk >='".$s_date."' AND tgl_ppk <= '".$e_date."')");
			}else{
				$tu=$this->pegawai->check_92810();
				$sosial=$this->pegawai->check_92820();
				$produksi=$this->pegawai->check_92830();
				$distribusi=$this->pegawai->check_92840();
				$nerwilis=$this->pegawai->check_92850();
				$ipds=$this->pegawai->check_92860();

				$this->db->select('*')
		 						 ->from('ppk')
		 						 ->join('master_pegawai', 'ppk.niplama=master_pegawai.niplama')
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

		 		$this->db->where("(tgl_ppk >='".$s_date."' AND tgl_ppk <= '".$e_date."') AND (master_pegawai.id_satker ='".$this->session->userdata('satker')."' AND (".$querys."master_pegawai.id_org='92870' OR autentifikasi.id_level='3'))");
			}
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

	private function _get_datatables_query_kasi()
	{
		$s = date("Y-01-01");
		$e = date("Y-12-31");
		$s_date = date("Y-01-01", strtotime(date("Y-m-d", strtotime($s)) . " " . "+0 year"));
		$e_date = date("Y-12-31", strtotime(date("Y-m-d", strtotime($e)) . " " . "+0 year"));
		$this->db->select('*')
				 ->from('ppk')
				 ->join('master_pegawai', 'master_pegawai.niplama=ppk.niplama')
				 ->join('autentifikasi', 'autentifikasi.niplama=ppk.niplama')
				 ->where("(tgl_ppk >='".$s_date."' AND tgl_ppk <= '".$e_date."')");
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

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function get_datatables_kasi()
	{
		$this->_get_datatables_query_kasi();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_by_id($id)
		{
		$this->db->from('ppk');
		$this->db->where('id',$id);
		$query = $this->db->get();

		return $query->row();
		}
		public function tambah_pk($data)
		{
			$this->db->insert('ppk', $data);
			return $this->db->insert_id();
		}
		public function ubah_pk($where, $data)
		{
			$this->db->update('ppk', $data, $where);
			return $this->db->affected_rows();
		}
		public function delete_by_idpk($id)
		{
		$this->db->where('id', $id);
		$this->db->delete('ppk');
		}
		function count_filtered()
		{
			$this->_get_datatables_query();
			$query = $this->db->get();
			return $query->num_rows();
		}

		public function count_all()
		{
			$this->_get_datatables_query();
			return $this->db->count_all_results();
		}


}

?>
