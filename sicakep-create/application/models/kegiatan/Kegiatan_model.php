<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kegiatan_model extends CI_Model {

	var $table = 'master_kegiatan';
	var $org = 'master_org';
  var $tingkat = 'master_tingkat';
	var $column_order = array('n_keg', 'nm_org', 'satuan', null);
	var $column_search = array('n_keg');
	var $order = array('nm_org' => 'asc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->select('master_kegiatan.id_keg as id, n_keg, satuan, master_kegiatan.id_org, master_org.nm_org')
				 ->from($this->table)
				 ->join('master_org', 'master_kegiatan.id_org=master_org.id_org');

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

	public function get_by_id($id)
{
	$this->db->from($this->table);
	$this->db->where('id_keg',$id);
	$query = $this->db->get();

	return $query->row();
}

	public function tambah_kegiatan($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function tambah_kegiatan_baru($data)
	{
		$this->db->insert('master_kegiatan', $data);
		return $this->db->insert_id();
	}

	public function tambah_kegiatan_temp($data)
	{
		$this->db->insert('master_kegiatan_temp', $data);
		return $this->db->insert_id();
	}

	public function ubah_kegiatan($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}
	public function delete_by_id($id)
{
	$this->db->where('id_keg', $id);
	$this->db->delete($this->table);
}

public function setuju_baru_by_id($id)
{
$this->db->where('id_keg', $id);
$this->db->delete('master_kegiatan_temp');
}

public function delete_baru_by_id($id)
{
$this->db->where('id_keg', $id);
$this->db->delete('master_kegiatan_temp');

$this->db->where('id_keg', $id);
$this->db->delete('master_kegiatan');
}

  function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->select('master_kegiatan.id_keg as id, n_keg, satuan, master_kegiatan.id_org, master_org.nm_org')
				 ->from($this->table)
				 ->join('master_org', 'master_kegiatan.id_org=master_org.id_org');
		return $this->db->count_all_results();
	}

	public function get_kegiatan()
	{
    $this->db->select('master_kegiatan.id_keg as id, n_keg, satuan, master_kegiatan.id_org as id_org, master_org.nm_org')
				 ->from($this->table)
				 ->join('master_org', 'master_kegiatan.id_org=master_org.id_org');
				 $query = $this->db->get();
 		return $query->result();
	}

	public function get_kegiatan_byid2($id)
	{
		$this->db->select('master_kegiatan.id_keg as id, n_keg, master_kegiatan.id_org, master_kegiatan.satuan, master_kegiatan.bukti_fisik, pelaksana, pelaksana_lanjutan, penyelia, pertama, muda, madya, master_butirkegiatan.butir_kegiatan')
				 ->from($this->table)
				 ->join('master_butirkegiatan', 'master_kegiatan.id_butirkegiatan=master_butirkegiatan.id_butirkegiatan')
				 ->where('id_keg',$id);
				 $query = $this->db->get();
		return $query->row();
	}

	public function get_kegiatan_byid($id)
	{
    $this->db->select('master_kegiatan_baru.id_keg as id, n_keg, satuan, ak_maks, master_kegiatan_baru.org as id_unitkerja, master_unitkerja.n_unitkerjapanj, master_tingkat.tingkat')
				 ->from($this->table)
				 ->join('master_unitkerja', 'master_kegiatan_baru.org=master_unitkerja.id')
         ->join('master_tingkat', 'master_kegiatan_baru.id_tingkat=master_tingkat.id_tingkat')
				 ->where('id_unitkerja', $id);
				 $query = $this->db->get();
		return $query->result();
	}

	public function ambil_keg_bynip($niplama)
	{
    $this->db->select('master_kegiatan.id_keg as id, n_keg, satuan')
				 ->from($this->table)
				 ->where('master_kegiatan.id_keg NOT IN (SELECT id_keg FROM skp WHERE skp.niplama="'.$niplama.'")');
				 $query = $this->db->get();
		return $query;
	}

	public function get_butirkegiatan_byid($tingkat)
	{
		$this->db->select('*')
				 ->from('master_butirkegiatan')
				 ->where('id_tingkat', $tingkat);
				 $query = $this->db->get();
		return $query;
	}

	public function get_idmax(){
		$this->db->select('(max(id_keg)+1) as id_keg')
				 ->from('master_kegiatan');
				 $query = $this->db->get();
		return $query->row();
	}

	public function get_kegiatan_baru(){
		$this->db->select('*')
				 ->from('master_kegiatan_temp');
				 $query = $this->db->get();
		return $query;
	}

	private function _get_datatables_baru_query()
	{
		$this->db->select('CONCAT(gelar_depan, " ",nama, " ",gelar_belakang) as nama_l, master_kegiatan.id_keg as id, master_kegiatan.n_keg, master_kegiatan.satuan, master_kegiatan.id_org, master_org.nm_org')
				 ->from($this->table)
				 ->join('master_org', 'master_kegiatan.id_org=master_org.id_org')
				 ->join('master_kegiatan_temp', 'master_kegiatan.id_keg=master_kegiatan_temp.id_keg')
				 ->join('master_pegawai', 'master_pegawai.niplama=master_kegiatan_temp.pembuat')
				 ->where('master_kegiatan.id_keg IN (SELECT id_keg FROM master_kegiatan_temp)');

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

	function get_datatables_baru()
	{
		$this->_get_datatables_baru_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_baru_filtered()
	{
		$this->_get_datatables_baru_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_baru_all()
	{
		$this->_get_datatables_baru_query();
		return $this->db->count_all_results();
	}


}
