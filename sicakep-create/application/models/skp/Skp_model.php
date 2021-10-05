<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skp_model extends CI_Model {

	var $table = 'skp_new';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

  public function set_skp($skp)
	{
    $data=array(
      'B' => $skp['B'],
      'C' => $skp['C'],
      'D' => $skp['D'],
      'E' => $skp['E'],
      'F' => $skp['F'],
      'G' => $skp['G'],
      'H' => $skp['H'],
      'I' => $skp['I'],
      'J' => $skp['J'],
      'K' => $skp['K'],
      'niplama'=>$this->session->userdata('niplama'),
      'tgl_buat'=>date('Y-m-d'),
    );
		$this->db->insert($this->table, $data);
    	return $this->db->insert_id();
	}
	public function tambah_skp($skp)
	{
		$this->db->insert($this->table, $skp);
    	return $this->db->insert_id();
	}

	public function ambil_skp_bynip($niplama){
			$s_date = date("Y-01-01");
			$e_date = date("Y-12-31");
			$this->db->select('skp.id as id, n_keg, kode_unsur, kode_subunsur, kode_butirkegiatan, pelaksana, pelaksana_lanjutan, penyelia, pertama, muda, madya, kuantitas, kualitas, master_kegiatan.satuan as satuan, waktu, satuan_waktu, biaya')
					 ->from('skp')
					 ->join('master_kegiatan','master_kegiatan.id_keg=skp.id_keg')
					 ->join('master_butirkegiatan', 'master_butirkegiatan.id_butirkegiatan=master_kegiatan.id_butirkegiatan')
					 ->join('master_subunsur', 'master_subunsur.id_subunsur=master_butirkegiatan.id_subunsur')
					 ->join('master_unsur', 'master_unsur.id_unsur=master_subunsur.id_unsur')
					 ->where('niplama', $niplama)
					 ->where("( tgl_buat >='".$s_date."' AND tgl_buat <= '".$e_date."')");
			$query = $this->db->get();
			return $query;
	}

	public function get_skp_bynip($niplama){
			$tahun = date("Y");
			$this->db->select('*')
					 ->from($this->table)
					 ->where('niplama', $niplama)
					 ->where("tahun", $tahun);
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

	public function get_skp_byid($id){
		$this->db->select('*')
				 ->from($this->table)
				 ->where('id',$id);
				 $query = $this->db->get();
		return $query->row();
	}

	public function ubah_skp($where, $data)
	{
		$this->db->update('skp', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}

}

?>
