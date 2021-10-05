<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('login/login_model');
		    $this->load->library('encrypt');
    }

    function index($id='')
    {
        $session = $this->session->userdata('isLogin');
        if($session == FALSE)
        {
            $data['status']=$id;
            $this->load->view('login/login_view', $data);
        }else
        {
            redirect('beranda.html');
        }
    }
    
    function do_login()
    {
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $data = $this->login_model->get_pass($username);

		if($data->num_rows()>0){
			$pass = $data->result();
			if(strcmp($password,$this->encrypt->decode($pass[0]->password))==0){
				$niplama = $pass[0]->niplama;
				$lvl = $pass[0]->id_level;

        $is_administrator = $this->login_model->is_administrator($niplama);
        if($is_administrator->num_rows()>0){
          $this->session->set_userdata(array(
                      'administrator'   => TRUE,));
          $this->session->set_userdata(array(
                        'id_admin'   => $is_administrator->result()[0]->id_admin,));
        }else{
          $this->session->set_userdata(array(
  					'administrator'   => FALSE,));
        }

        $pegawai = $this->login_model->get_pegawai_by_id($niplama);
				$this->session->set_userdata(array(
					'isLogin'   => TRUE,
					'niplama' => $niplama,
          'nipbaru' => $pegawai->nipbaru,
					'uname'  => $username,
          'nama' => $pegawai->nama,
          'nama_lengkap' => $pegawai->gelar_depan.' '.$pegawai->nama.' '.$pegawai->gelar_belakang,
          'organisasi' => $pegawai->id_org,
          'satker' => $pegawai->id_satker,
					'lvl' => $lvl,
				));
				redirect(base_url(),'refresh');
			}else{
                redirect(base_url().'login.html/1');
      }
		}else{
      redirect(base_url().'login.html/1');
    }
	}

    function logout()
    {
      $this->session->sess_destroy();
      redirect(base_url(),'refresh');
    }

}
