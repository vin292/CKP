<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Fungsional_model extends CI_Model{
	function __construct()
	{
		parent::__construct();
		$this->tbl = "info_fungsional";
	}

	public function get_info_fung_by_id($id)
	{

	$this->db->select('*')
	->from($this->tbl)
	->join('master_jabatan_fungsional', 'info_fungsional.kode_jabatan_to_jenjang=master_jabatan_fungsional.id_jabatan')
	->join('master_gol', 'info_fungsional.gol=master_gol.id_gol')
	->where('nippejafung', $id);
	$query = $this->db->get();

	return $query;
	}

	public function ambil_gol_bynip($niplama){
			$this->db->select('master_gol.n_gol as n_gol, master_gol.pangkat as pangkat, master_jabatan_fungsional.ket as jabatan')
					 ->from('info_fungsional')
					 ->join('master_gol','master_gol.id_gol=info_fungsional.gol')
					 ->join('master_jabatan_fungsional','master_jabatan_fungsional.id_jabatan=info_fungsional.kode_jabatan_to_jenjang')
					 ->where('info_fungsional.nippejafung', $niplama);
			$query = $this->db->get();
			return $query;
	}
}
