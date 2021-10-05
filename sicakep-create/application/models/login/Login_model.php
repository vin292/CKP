<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login_model extends CI_Model{
	function __construct()
	{
		parent::__construct();
		$this->load->library('encrypt');
		$this->tbl = "autentifikasi";
	}

	function cek_user($username="",$password="")
	{
		$query = $this->db->get_where($this->tbl,array('username' => $username, 'password' => $password));
		$query = $query->result_array();
		return $query;
	}

	public function get_all(){
		$this->db->select('*')
		->from('autentifikasi');
		$query = $this->db->get();

		return $query->result();
	}

	public function ubah_password($where, $data)
	{
		$this->db->update('autentifikasi', $data, $where);
		return $this->db->affected_rows();
	}

	public function get_pass($username)
	{
		$this->db->from($this->tbl);
		$this->db->where('username', $username);
		$query = $this->db->get();
		return $query;
	}

	public function is_administrator($niplama)
	{
		$this->db->from('administrator');
		$this->db->where('niplama', $niplama);
		$query = $this->db->get();
		return $query;
	}

	public function is_fungsional($niplama)
	{
		$this->db->from('info_fungsional');
		$this->db->where('nippejafung', $niplama);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_pegawai_by_id($id)
	{

	$this->db->select('*')
	->from('master_pegawai')
	->where('niplama', $id);
	$query = $this->db->get();

	return $query->row();
	}

}
