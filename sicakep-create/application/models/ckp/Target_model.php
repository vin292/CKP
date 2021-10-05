<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Target_model extends CI_Model {

	var $table = 'ckp_new';
	var $column_order = array('n_keg','target','status_target', null);
	var $column_search = array('n_keg', 'target', 'status_target');
	var $order = array('tgl_buat' => 'desc');

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Jakarta");
		$this->load->database();
	}
	private function _get_datatables_query($m)
	{
		// if(isset($m)){
			$ym = explode("-", $m);
			$m = $ym[0];
			$y = $ym[1];
		// }

		// $tahun = date("Y");
		$tahun = $y;

		$this->db->select('*')
				 ->from($this->table)
				 ->where('niplama', $this->session->userdata('niplama'))
				 ->where("(bulan_ckp ='".$m."' AND tahun_ckp = '".$tahun."')")
				 ->order_by("tgl_buat", "desc");

		// print_r($this->db->last_query());

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
	public function tambah_target($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function ubah_target($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}
	public function delete_by_id($id)
{
	$this->db->where('id', $id);
	$this->db->delete($this->table);
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
		if(strcmp($this->session->userdata('lvl'), '2')==0){
			$this->db->select('*')
				 ->from($this->table)
				 ->where('niplama', $this->session->userdata('niplama'))
				 ->where("(bulan_ckp ='".$m."' AND tahun_ckp = '".$tahun."')")
				 ->order_by("tgl_buat", "desc");
		}else{
			$this->db->select('*')
			->from($this->table)
			->where('niplama', $this->session->userdata('niplama'))
			->where("(bulan_ckp ='".$m."' AND tahun_ckp = '".$tahun."')")
			->order_by("tgl_buat", "desc");
			 }
		return $this->db->count_all_results();
	}
	public function get_target($id){
		$this->db->select('ckp_new.id, target, tgl_buat, bulan_ckp, master_pegawai.niplama as niplama, nm_keg, satuan, jenis')
				 ->from('ckp_new')
				 ->join('master_pegawai', 'ckp_new.niplama=master_pegawai.niplama')
				 ->where('ckp_new.id', $id);
				 $query = $this->db->get();

	 		  return $query->row();
	}

}
