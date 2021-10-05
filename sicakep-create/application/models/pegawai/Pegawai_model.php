<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai_model extends CI_Model {

	var $table = 'master_pegawai';
	var $autentifikasi = 'autentifikasi';
	var $org = 'master_org';
	var $gol = 'master_gol';
	var $column_order = array('niplama','nipbaru','nama','email', 'master_org.id_org', null);
	var $column_search = array('nipbaru','nama');
	var $order = array('master_org.id_org' => 'asc');

	var $column_order2 = array('niplama','nama','email', 'master_satker_id_satker', null);
	var $column_search2 = array('niplama','nama');
	var $order2 = array('master_satker.id_satker' => 'asc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('encrypt');
	}

	private function _get_datatables_query()
	{
		$this->db->select('*')
				 ->from('master_pegawai')
				 ->join('master_org', 'master_pegawai.id_org=master_org.id_org')
				 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
				 ->where('id_satker', $this->session->userdata('satker'));

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

  public function tambah_pegawai($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function tambah_akun($data)
	{

		$this->db->insert($this->autentifikasi, $data);
		return $this->db->insert_id();
	}

  public function ubah_pegawai($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}
	public function ubah_akun($where, $data)
	{
		$this->db->update($this->autentifikasi, $data, $where);
		return $this->db->affected_rows();
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

  public function get_by_id($id)
{
  $this->db->from($this->table);
  $this->db->where('niplama',$id);
  $query = $this->db->get();

  return $query->row();
}
public function get_aut($id)
{
$this->db->from($this->autentifikasi);
$this->db->where('niplama',$id);
$query = $this->db->get();

return $query->row();
}
  public function delete_by_id($id)
{
  $this->db->where('niplama', $id);
  $this->db->delete($this->table);
}
public function delete_by_id2($id)
{
$this->db->where('niplama', $id);
$this->db->delete($this->autentifikasi);
}
public function get_level()
{
$this->db->from('master_level');
$query = $this->db->get();

return $query->result();
}
public function get_autentifikasi()
{
$this->db->from($this->autentifikasi);
$query = $this->db->get();

return $query->result();
}
public function get_pegawai()
{

$this->db->select('*')
->from('master_pegawai')
->where('master_pegawai.niplama NOT IN (SELECT niplama FROM autentifikasi)')
->where('id_satker', $this->session->userdata('satker'))
->order_by('nama');
$query = $this->db->get();

return $query->result();
}

public function get_administrator_by_id($id)
{

$this->db->select('*')
->from('administrator')
->join('master_pegawai', 'master_pegawai.niplama=administrator.niplama')
->where('administrator.niplama', $id);
$query = $this->db->get();

return $query->row();
}

public function get_pegawai_by_id($id){
		$this->db->select('*')
				 ->from('master_pegawai')
				 ->join('master_org', 'master_org.id_org=master_pegawai.id_org')
				 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
				 ->where('master_pegawai.niplama', $id);
				 $query = $this->db->get();
				return $query->row();
}

public function get_pegawai_detil_by_id($id){
		$this->db->select('master_pegawai.niplama, master_pegawai.nipbaru, master_pegawai.id_satker, gelar_depan, master_pegawai.nama, gelar_belakang, master_pegawai.email, master_org.id_org, master_org.nm_org, autentifikasi.id_level, master_satker.nm_satker, master_gol.pangkat as pangkat, master_gol.n_gol')
				 ->from('master_pegawai')
				 ->join('master_org', 'master_org.id_org=master_pegawai.id_org')
				 ->join('autentifikasi', 'autentifikasi.niplama=master_pegawai.niplama')
				 ->join('master_satker', 'master_satker.id_satker=master_pegawai.id_satker')
				 ->join('master_gol', 'master_pegawai.id_gol=master_gol.id_gol')
				 ->where('master_pegawai.niplama', $id);
				 $query = $this->db->get();
				return $query->row();
}

public function get_satker_by_nip($id){
	$this->db->select('*')
			 ->from('master_satker')
			 ->join('master_pegawai', 'master_satker.id_satker=master_pegawai.id_satker')
			 ->where('master_pegawai.niplama', $id);
			 $query = $this->db->get();
			return $query->row();
}

public function get_org()
{
$this->db->from($this->org);
$query = $this->db->get();

return $query->result();
}

public function get_gol()
{
$this->db->from($this->gol);
$query = $this->db->get();

return $query->result();
}

public function get_pegawai_all()
{
	$this->db->from($this->table);
	$this->db->order_by("nama", "asc");
	$query = $this->db->get();

return $query->result();
}

public function get_pegawai_es3()
{
$this->db->from($this->table);
$this->db->join($this->autentifikasi, 'master_pegawai.niplama=autentifikasi.niplama');
$this->db->where('autentifikasi.id_level', '2');
$this->db->order_by("nama", "asc");
$query = $this->db->get();

return $query->result();
}

public function get_pegawai_es4()
{
$this->db->from($this->table);
$this->db->join($this->autentifikasi, 'master_pegawai.niplama=autentifikasi.niplama');
$this->db->where('master_pegawai.id_satker', $this->session->userdata('satker'));
$this->db->where('autentifikasi.id_level', '3');
$this->db->like('master_pegawai.id_org', substr($this->session->userdata('organisasi'), 0, 3));
$this->db->order_by("nama", "asc");
$query = $this->db->get();

return $query->result();
}

public function get_pegawai_es4_ksk()
{
	$tu=$this->check_92810();
	$sosial=$this->check_92820();
	$produksi=$this->check_92830();
	$distribusi=$this->check_92840();
	$nerwilis=$this->check_92850();
	$ipds=$this->check_92860();
	$querys="SELECT*FROM master_pegawai JOIN autentifikasi ON master_pegawai.niplama=autentifikasi.niplama
	WHERE master_pegawai.id_satker=".$this->session->userdata('satker')." AND (";
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

	$querys.='master_pegawai.id_org="92870" OR autentifikasi.id_level="3")';
	$this->db->query($querys);
	$query = $this->db->query($querys);

return $query->result();
}

public function check_92810()
{
$this->db->from($this->table);
$this->db->join($this->autentifikasi, 'master_pegawai.niplama=autentifikasi.niplama');
$this->db->where('autentifikasi.id_level =', '3');
$this->db->where('master_pegawai.id_satker', $this->session->userdata('satker'));
$this->db->where('id_org', '92810');
$query = $this->db->get();

return $query->num_rows();
}

public function check_92820()
{
$this->db->from($this->table);
$this->db->join($this->autentifikasi, 'master_pegawai.niplama=autentifikasi.niplama');
$this->db->where('autentifikasi.id_level =', '3');
$this->db->where('id_org', '92820');
$this->db->where('master_pegawai.id_satker', $this->session->userdata('satker'));
$this->db->order_by("nama", "asc");
$query = $this->db->get();

return $query->num_rows();
}

public function check_92830()
{
$this->db->from($this->table);
$this->db->join($this->autentifikasi, 'master_pegawai.niplama=autentifikasi.niplama');
$this->db->where('autentifikasi.id_level =', '3');
$this->db->where('id_org', '92830');
$this->db->where('master_pegawai.id_satker', $this->session->userdata('satker'));
$this->db->order_by("nama", "asc");
$query = $this->db->get();

return $query->num_rows();
}

public function check_92840()
{
$this->db->from($this->table);
$this->db->join($this->autentifikasi, 'master_pegawai.niplama=autentifikasi.niplama');
$this->db->where('autentifikasi.id_level =', '3');
$this->db->where('id_org', '92840');
$this->db->where('master_pegawai.id_satker', $this->session->userdata('satker'));
$this->db->order_by("nama", "asc");
$query = $this->db->get();

return $query->num_rows();
}

public function check_92850()
{
$this->db->from($this->table);
$this->db->join($this->autentifikasi, 'master_pegawai.niplama=autentifikasi.niplama');
$this->db->where('autentifikasi.id_level =', '3');
$this->db->where('id_org', '92850');
$this->db->where('master_pegawai.id_satker', $this->session->userdata('satker'));
$this->db->order_by("nama", "asc");
$query = $this->db->get();

return $query->num_rows();
}

public function check_92860()
{
$this->db->from($this->table);
$this->db->join($this->autentifikasi, 'master_pegawai.niplama=autentifikasi.niplama');
$this->db->where('autentifikasi.id_level =', '3');
$this->db->where('master_pegawai.id_org', '92860');
$this->db->where('master_pegawai.id_satker', $this->session->userdata('satker'));
$this->db->order_by("nama", "asc");
$query = $this->db->get();

return $query->num_rows();
}

public function get_pegawai_by_org_jab($id, $id_lvl)
{
$this->db->from($this->table);
$this->db->join($this->autentifikasi, 'master_pegawai.niplama=autentifikasi.niplama');
$this->db->where('autentifikasi.id_level >',$id_lvl);
$this->db->where('id_org',$id);
$this->db->where('id_satker',$this->session->userdata('satker'));
$this->db->order_by("nama", "asc");
$query = $this->db->get();

return $query->result();
}


public function get_pegawai_allstaff($id_lvl)
{
$this->db->from($this->table);
$this->db->join($this->autentifikasi, 'master_pegawai.niplama=autentifikasi.niplama');
$this->db->where('autentifikasi.id_level >', $id_lvl);
$this->db->order_by("nama", "asc");
$query = $this->db->get();

return $query->result();
}

private function _get_datatables_administrator_query()
{

	$this->db->select('master_pegawai.niplama as niplama, master_pegawai.gelar_depan, master_pegawai.nama, master_pegawai.gelar_belakang, id_admin, master_pegawai.email as email, master_satker.nm_satker')
		 	->from('administrator')
		 	->join('master_pegawai', 'master_pegawai.niplama=administrator.niplama')
			->join('master_satker', 'master_pegawai.id_satker=master_satker.id_satker');

	$i = 0;
	foreach ($this->column_search2 as $item)
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
			if(count($this->column_search2) - 1 == $i)
				$this->db->group_end();
		}
		$i++;
	}

	if(isset($_POST['order']))
	{
		$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
	}
	else if(isset($this->order2))
	{
		$order2 = $this->order2;
		$this->db->order_by(key($order2), $order2[key($order2)]);
	}
}

function get_datatables_administrator()
{
	$this->_get_datatables_administrator_query();
	if($_POST['length'] != -1)
	$this->db->limit($_POST['length'], $_POST['start']);
	$query = $this->db->get();
	return $query->result();
}

function count_filtered_administrator()
{
	$this->_get_datatables_administrator_query();
	$query = $this->db->get();
	return $query->num_rows();
}

public function count_all_administrator()
{
	$this->_get_datatables_administrator_query();
	return $this->db->count_all_results();
}

public function tambah_admin($data)
{
	$this->db->insert('administrator', $data);
	return $this->db->insert_id();
}

public function ubah_admin($where, $data)
{
	$this->db->update('administrator', $data, $where);
	return $this->db->affected_rows();
}

public function delete_admin_by_id($id)
{
$this->db->where('niplama', $id);
$this->db->delete('administrator');
}

public function ubah_password($where, $data)
{
	$this->db->update('autentifikasi', $data, $where);
	return $this->db->affected_rows();
}

public function get_pass()
{
	$this->db->from('autentifikasi');
	$this->db->where('username', $this->session->userdata('uname'));
	$query = $this->db->get();
	return $this->encrypt->decode($query->row()->password);
}

}
