<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Realization_model extends CI_Model {

	var $table = 'ckp_new';
	var $column_order = array('n_keg','target','realisasi','status_realisasi', null);
	var $column_search = array('n_keg','target','realisasi','status_realisasi');
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

		// $tahun = date("Y");
		$tahun = $y;
		
		$this->db->select('*')
			->from($this->table)
			->where('niplama', $this->session->userdata('niplama'))
			->where("(bulan_ckp ='".$m."' AND tahun_ckp = '".$tahun."')")
			->order_by("tgl_buat", "desc");
			
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
		$this->_get_datatables_query($m);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	public function entry_realization($where, $data)
	{
    $this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	function count_filtered($m)
	{
		$this->_get_datatables_query($m);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($m)
	{
		$ym = explode("-", $m);
		$m = $ym[0];
		$y = $ym[1];

		// $tahun = date("Y");
		$tahun = $y;
		
		$this->db->select('*')
			->from($this->table)
			->where('niplama', $this->session->userdata('niplama'))
			->where("(bulan_ckp ='".$m."' AND tahun_ckp = '".$tahun."')")
			->order_by("tgl_buat", "desc");
		return $this->db->count_all_results();
	}

	public function get_realization($id){
		$this->db->select('id, target, realisasi, nm_keg')
				 ->from($this->table)
				 ->where('id', $id);
				 $query = $this->db->get();

	 		  return $query->row();
	}

	private function _get_datatables_queryk($m)
	{
		$s_date = date("Y-".$m."-01");
		$e_date = date("Y-".$m."-t", strtotime($s_date));
		$this->db->select('ckepatuhan.id as id, target, realisasi, tgl_buat, tgl_ck, n_panj, ket, satuan, status')
				 ->from('ckepatuhan')
				 ->join('master_pegawai', 'ckepatuhan.niplama=master_pegawai.niplama')
				 ->join('master_unitkerja', 'master_pegawai.id_unitkerja=master_unitkerja.id')
				 ->join('master_kepatuhan', 'ckepatuhan.id_kepatuhan=master_kepatuhan.id')
				 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
				 ->where('master_pegawai.niplama', $this->session->userdata('niplama'))
				 ->where("(tgl_ck >='".$s_date."' AND tgl_ck <= '".$e_date."')")
				 ->order_by("tgl_buat", "desc");
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

	function get_datatablesk($m)
	{
		$this->_get_datatables_queryk($m);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	public function entry_realizationk($where, $data)
	{
    $this->db->update('ckepatuhan', $data, $where);
		return $this->db->affected_rows();
	}

	function count_filteredk($m)
	{
		$this->_get_datatables_queryk($m);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_allk($m)
	{
		$s_date = date("Y-".$m."-01");
		$e_date = date("Y-".$m."-t", strtotime($s_date));
		$this->db->select('ckepatuhan.id as id, target, realisasi, tgl_buat, tgl_ck, n_panj, ket, satuan, status')
				 ->from('ckepatuhan')
				 ->join('master_pegawai', 'ckepatuhan.niplama=master_pegawai.niplama')
				 ->join('master_unitkerja', 'master_pegawai.id_unitkerja=master_unitkerja.id')
				 ->join('master_kepatuhan', 'ckepatuhan.id_kepatuhan=master_kepatuhan.id')
				 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
				 ->where('master_pegawai.niplama', $this->session->userdata('niplama'))
				 ->where("(tgl_ck >='".$s_date."' AND tgl_ck <= '".$e_date."')")
				 ->order_by("tgl_buat", "desc");
		return $this->db->count_all_results();
	}

	public function get_realizationk($id){
		$this->db->select('ckepatuhan.id as id, target, realisasi, master_pegawai.niplama as niplama, master_kepatuhan.id as id_kepatuhan')
				 ->from('ckepatuhan')
				 ->join('master_pegawai', 'ckepatuhan.niplama=master_pegawai.niplama')
				 ->join('master_unitkerja', 'master_pegawai.id_unitkerja=master_unitkerja.id')
				 ->join('master_kepatuhan', 'ckepatuhan.id_kepatuhan=master_kepatuhan.id')
				 ->where('ckepatuhan.id', $id);
				 $query = $this->db->get();

	 		  return $query->row();
	}
}
