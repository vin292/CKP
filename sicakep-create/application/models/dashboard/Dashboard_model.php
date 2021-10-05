
<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard_model extends CI_Model{
	function __construct()
	{
		parent::__construct();
	}

	public function get_entri_target($bulan)
	{
    $query = $this->db->query('SELECT a.id_satker, b.target, a.jumlah FROM (SELECT master_satker.id_satker, count(niplama) as jumlah 
    FROM master_pegawai
    JOIN master_satker ON master_satker.id_satker=master_pegawai.id_satker
    GROUP BY master_satker.id_satker) a
    JOIN
    (select master_satker.id_satker, a.target FROM (SELECT master_satker.id_satker, nm_satker, COUNT(DISTINCT (master_pegawai.niplama)) as target
            FROM master_satker
            JOIN master_pegawai ON master_pegawai.id_satker=master_satker.id_satker
            LEFT JOIN ckp_new ON master_pegawai.niplama=ckp_new.niplama
            WHERE master_pegawai.niplama IN (SELECT ckp_new.niplama From ckp_new WHERE ckp_new.status_target="1"  AND ckp_new.bulan_ckp="'.$bulan.'")
            GROUP BY nm_satker ORDER By master_satker.id_satker) a
            RIGHT JOIN master_satker ON master_satker.id_satker=a.id_satker
            GROUP BY id_satker) b ON b.id_satker = a.id_satker ');

	$query = $query->result();

	return $query;
    }
    
    public function get_entri_realisasi($bulan, $year)
	{
    $query = $this->db->query('SELECT a.id_satker, b.target, a.jumlah FROM (SELECT master_satker.id_satker, count(niplama) as jumlah 
    FROM master_pegawai
    JOIN master_satker ON master_satker.id_satker=master_pegawai.id_satker
    GROUP BY master_satker.id_satker) a
    JOIN
    (select master_satker.id_satker, a.target FROM (SELECT master_satker.id_satker, nm_satker, COUNT(DISTINCT (master_pegawai.niplama)) as target
            FROM master_satker
            JOIN master_pegawai ON master_pegawai.id_satker=master_satker.id_satker
            LEFT JOIN ckp_new ON master_pegawai.niplama=ckp_new.niplama
            WHERE master_pegawai.niplama IN (SELECT ckp_new.niplama From ckp_new WHERE ckp_new.tahun_ckp="'.$year.'" AND ckp_new.status_realisasi="1" AND ckp_new.bulan_ckp="'.$bulan.'")
            GROUP BY nm_satker ORDER By master_satker.id_satker) a
            RIGHT JOIN master_satker ON master_satker.id_satker=a.id_satker
            GROUP BY id_satker) b ON b.id_satker = a.id_satker ');
	$query = $query->result();
	return $query;
    }
    
    public function get_list_target($bulan){
        $query = $this->db->query('SELECT * FROM master_pegawai 
            JOIN autentifikasi ON master_pegawai.niplama=autentifikasi.niplama
            WHERE master_pegawai.niplama NOT IN (SELECT DISTINCT(ckp_new.niplama) From ckp_new 
            JOIN master_pegawai ON master_pegawai.niplama=ckp_new.niplama
            WHERE autentifikasi.id_level > "1" AND ckp_new.status_target="1"  AND ckp_new.bulan_ckp="'.$bulan.'" AND master_pegawai.id_satker="'.$this->session->userdata('satker').'") AND id_satker="'.$this->session->userdata('satker').'" AND id_level > "1" ORDER BY master_pegawai.nama');
        return $query;
    }

    public function get_list_realisasi($bulan, $year){
        $query = $this->db->query('SELECT * FROM master_pegawai 
            JOIN autentifikasi ON master_pegawai.niplama=autentifikasi.niplama
            WHERE master_pegawai.niplama NOT IN (SELECT DISTINCT(ckp_new.niplama) From ckp_new 
            JOIN master_pegawai ON master_pegawai.niplama=ckp_new.niplama
            WHERE ckp_new.tahun_ckp="'.$year.'" AND autentifikasi.id_level > "1" AND ckp_new.status_realisasi="1"  AND ckp_new.bulan_ckp="'.$bulan.'" AND master_pegawai.id_satker="'.$this->session->userdata('satker').'") AND id_satker="'.$this->session->userdata('satker').'" AND id_level > "1" ORDER BY master_pegawai.nama');
        return $query;
    }

}
