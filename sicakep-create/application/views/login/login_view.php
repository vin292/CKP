<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Login Sistem Informasi Capaian Kinerja Pegawai</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/AdminLTE.min.css') ?>" rel="stylesheet">
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.png') ?>">
    <style>
      body{
        background-image: url(<?php echo base_url('assets/img/bg.jpg') ?>) !important;
        height: 100% !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        background-size: cover !important;
    }
    </style>
  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="<?php echo base_url(); ?>" target='_blank'><span style="color:#ffffff">Sistem Informasi Capaian Kinerja</span> <span style="color:#ffffff"><b style="color">Pegawai</b></span></a>
      </div>
      <div class="login-box-body">
        <p class="login-box-msg">Silahkan Login</p>
        <?php
          if($status==1){
            ?>
            <div class="alert alert-danger" role="alert">
              <span class='glyphicon glyphicon-comment'></span> Username atau password salah!
            </div>
            <?php 
          }
        ?>
        <form action="<?php echo base_url() ?>login/do_login" method="POST">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" name='username' placeholder="Username"/>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name='password' placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <script src="<?php echo base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>

  </body>
</html>