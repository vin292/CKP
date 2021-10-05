<?php
/*
Developer: Yusfil Khoir Pulungan
Email: yusfilpulungan@bps.go.id
Website: http://saungsikma.com
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Submission_model extends CI_Model {

	var $table = 'ckepatuhan';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	private function _get_datatables_query($m)
	{
		$s_date = date("Y-".$m."-01");
		$e_date = date("Y-".$m."-t", strtotime($s_date));
		if(strcmp($this->session->userdata('lvl'), '2')==0){
			$this->db->select('ckepatuhan.id as id, ket, target, satuan, tgl_buat, tgl_ck, n_panj')
					 ->from('ckepatuhan')
					 ->join('master_pegawai', 'ckepatuhan.niplama=master_pegawai.niplama')
					 ->join('master_unitkerja', 'master_pegawai.id_unitkerja=master_unitkerja.id')
					 ->join('master_kepatuhan', 'ckepatuhan.id_kepatuhan=master_kepatuhan.id')
					 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
					 ->where('autentifikasi.id_lvl =', '3')
					 ->where("(tgl_ck >='".$s_date."' AND tgl_ck <= '".$e_date."')")
					 ->order_by("tgl_buat", "desc");
		}else{
		$this->db->select('ckepatuhan.id as id, ket, target, satuan, tgl_buat, tgl_ck, n_panj')
				 ->from('ckepatuhan')
				 ->join('master_pegawai', 'ckepatuhan.niplama=master_pegawai.niplama')
				 ->join('master_unitkerja', 'master_pegawai.id_unitkerja=master_unitkerja.id')
				 ->join('master_kepatuhan', 'ckepatuhan.id_kepatuhan=master_kepatuhan.id')
				 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
				 ->where('autentifikasi.id_lvl >', '3')
				 ->where("(tgl_ck >='".$s_date."' AND tgl_ck <= '".$e_date."')")
				 ->where("ckepatuhan.creator", $this->session->userdata('niplama'))
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
		$this->_get_datatables_query($m);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	public function tambah_submission($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function ubah_submission($where, $data)
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
		$s_date = date("Y-".$m."-01");
		$e_date = date("Y-".$m."-t", strtotime($s_date));
		if(strcmp($this->session->userdata('lvl'), '2')==0){
			$this->db->select('ckepatuhan.id as id, ket, target, tgl_buat, tgl_ck, n_panj')
					 ->from('ckepatuhan')
					 ->join('master_pegawai', 'ckepatuhan.niplama=master_pegawai.niplama')
					 ->join('master_unitkerja', 'master_pegawai.id_unitkerja=master_unitkerja.id')
					 ->join('master_kepatuhan', 'ckepatuhan.id_kepatuhan=master_kepatuhan.id')
					 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
					 ->where('autentifikasi.id_lvl =', '3')
					 ->where("(tgl_ck >='".$s_date."' AND tgl_ck <= '".$e_date."')")
					 ->order_by("tgl_buat", "desc");
		}else{
		$this->db->select('ckepatuhan.id as id, ket, target, tgl_buat, tgl_ck, n_panj')
				 ->from('ckepatuhan')
				 ->join('master_pegawai', 'ckepatuhan.niplama=master_pegawai.niplama')
				 ->join('master_unitkerja', 'master_pegawai.id_unitkerja=master_unitkerja.id')
				 ->join('master_kepatuhan', 'ckepatuhan.id_kepatuhan=master_kepatuhan.id')
				 ->join('autentifikasi', 'master_pegawai.niplama=autentifikasi.niplama')
				 ->where('autentifikasi.id_lvl >', '3')
				 ->where("(tgl_ck >='".$s_date."' AND tgl_ck <= '".$e_date."')")
				 ->where("ckepatuhan.creator", $this->session->userdata('niplama'))
				 ->order_by("tgl_buat", "desc");
		}
		return $this->db->count_all_results();
	}
	public function get_submission($id){
		$this->db->select('ckepatuhan.id as id, target, tgl_buat, tgl_ck, master_pegawai.niplama as niplama, id_kepatuhan')
				 ->from('ckepatuhan')
				 ->join('master_pegawai', 'ckepatuhan.niplama=master_pegawai.niplama')
				 ->where('ckepatuhan.id', $id);
				 $query = $this->db->get();

	 		  return $query->row();
	}
}
