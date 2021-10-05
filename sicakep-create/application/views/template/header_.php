<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?></title>
    <meta name="description" content="SICAKEP: Sistem Informasi Capaian Kinerja Pegawai">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="http://www.sultradata.com/project/sicakep/assets/img/logo.png">
	<meta name="theme-color" content="#ffffff">
	<meta property="og:locale" content="id_ID">
	<meta property="og:type" content="website">
	<meta property="og:title" content="SICAKEP - Sistem Informasi Capaian Kinerja Pegawai">
	<meta property="og:description" content="Merupakan aplikasi pencatatan Capaian Kinerja Badan Pusat Statistik (BPS) Lingkup Provinsi Sulawesi Tenggara.">
	
    <link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,700,800,400italic"> -->
    <link href="<?php echo base_url('assets/css/pe-icon-7-stroke.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/helper.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style.default.css" id="theme-stylesheet') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/owl.carousel.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/owl.theme.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/simpletextrotator.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?=base_url();?>assets/css/select2.min.css" rel="stylesheet">
    <link href="<?=base_url();?>assets/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="<?=base_url();?>assets/css/sweetalert.css" rel="stylesheet">
    <!-- <link href="<?php echo base_url('assets/css/bootstrap-material-design.min.css') ?>" rel="stylesheet"> -->
    <link href="<?php echo base_url('assets/css/custom.css') ?>" rel="stylesheet">
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.png') ?>">
  </head>
  <?php
    $lvl = $this->session->userdata('lvl');
    $is_admin = $this->session->userdata('administrator');
  ?>
  <body data-spy="scroll" data-target="#navigation" data-offset="120">
    <script src="<?php echo base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
    <!-- <script src="<?php echo base_url('assets/js/material.min.js') ?>"></script> -->
    <script src="<?php echo base_url('assets/js/jquery.dataTables.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/dataTables.bootstrap.js') ?>"></script>
    <script type="text/javascript" src="<?=base_url();?>assets/js/bootstrap-datepicker.min.js"></script>
    <!-- <script type="text/javascript" src="<?=base_url();?>assets/js/moment-with-locales.min.js"></script> -->
    <div id="all">
      <!-- navbar-->
      <header class="header">
        <div role="navigation" class="navbar navbar-default navbar-fixed-top">
          <div class="container">
            <div class="navbar-header"><a href="<?php echo base_url(); ?>" class="navbar-brand"><img src="<?php echo base_url('assets/img/logo.png') ?>" alt="logo" class="hidden-xs hidden-sm"><img src="<?php echo base_url('assets/img/logo-small.png') ?>" alt="logo" class="visible-xs visible-sm"><span class="sr-only">Go to homepage</span></a>
              <div class="navbar-buttons">
                <button type="button" data-toggle="collapse" data-target=".navbar-collapse" class="navbar-toggle navbar-btn">Menu<i class="pe-7s-menu"></i></button>
              </div>
            </div>
            <div id="navigation" class="collapse navbar-collapse navbar-right">
              <ul class="nav navbar-nav">
              <?php if($this->session->userdata('isLogin')) { ?>
                <?php if($menu==0){ ?>
                  <li class="active">
                    <a href="<?=base_url();?>">
                      <span class="glyphicon glyphicon-home"></span> Beranda
                    </a>
                  </li>
                  <?php } else { ?>
                    <li>
                      <a href="<?=base_url();?>">
                        <span class="glyphicon glyphicon-home"></span> Beranda
                      </a>
                    </li>
                  <?php } ?>

              <?php if($is_admin) { ?>
                <?php if($menu==1){ ?>
                  <li class="dropdown active" >
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Kelola<b class="caret"></b></a>
                    <ul class="dropdown-menu" style="width:100%">
                      <?php if(strcmp(substr($this->session->userdata('satker'), -2 ), '00')==0){ ?>
                        <li>
                          <a href="<?=base_url();?>master/administrator">
                            <span class=""></span> Master Administrator
                          </a>
                        </li>
                      <?php } ?>
                      <li>
                        <a href="<?=base_url();?>master/pegawai">
                          <span class=""></span> Master Pegawai
                        </a>
                      </li>
                    </ul>
                  </li>
                <?php } else { ?>
                <li class="dropdown" >
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Kelola<b class="caret"></b></a>
                  <ul class="dropdown-menu" style="width:100%">
                    <?php if(strcmp(substr($this->session->userdata('satker'), -2 ), '00')==0){ ?>
                      <li>
                        <a href="<?=base_url();?>master/administrator">
                          <span class=""></span> Master Administrator
                        </a>
                      </li>
                    <?php } ?>
                    <li>
                      <a href="<?=base_url();?>master/pegawai">
                        <span class=""></span> Master Pegawai
                      </a>
                    </li>
                  </ul>
                </li>
              <?php } ?>
              <?php } ?>

              <?php if($lvl=='1'||$lvl=='2'||$lvl=='3') { ?>
              <?php if($menu==4){ ?>
                <li class="active">
                  <a href="<?=base_url();?>approve">
                    <span class=""></span> Persetujuan
                  </a>
                </li>
              <?php }else{ ?>
                <li>
                  <a href="<?=base_url();?>approve">
                    <span class=""></span> Persetujuan
                  </a>
                </li>
              <?php } ?>
          <?php } ?>
				
          <?php if($menu==5){ ?>
            <li class="active">
              <a href="<?=base_url();?>skp">
                SKP
              </a>
            </li>
          <?php }else{ ?>
            <li>
              <a href="<?=base_url();?>skp">
                SKP
              </a>
            </li>
          <?php } ?>

          <?php if($menu==6){ ?>
              <li class="dropdown active">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">CKP <b class="caret"></b></a>
                <ul class="dropdown-menu" style="width:100%">
                  <li><a href="<?=base_url();?>ckp">Lihat CKP</a></li>
                  <li><a href="<?=base_url();?>ckp/entri_ckp_target">Entri CKP-Target</a></li>
                  <li><a href="<?=base_url();?>ckp/entri_ckp_realisasi">Entri CKP-Realisasi</a></li>
                  <?php if($lvl=='1'||$lvl=='2'||$lvl=='3') { ?>
                    <li class="divider"></li>
                    <li><a href="<?=base_url();?>ckp/all_ckp">Rekap CKP Pegawai</a></li>
                  <?php } ?>
                </ul>
              </li>
          <?php }else{ ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">CKP <b class="caret"></b></a>
              <ul class="dropdown-menu" style="width:100%">
                <li><a href="<?=base_url();?>ckp">Lihat CKP</a></li>
                <li><a href="<?=base_url();?>ckp/entri_ckp_target">Entri CKP-Target</a></li>
                <li><a href="<?=base_url();?>ckp/entri_ckp_realisasi">Entri CKP-Realisasi</a></li>
              <?php if($lvl=='1'||$lvl=='2'||$lvl=='3') { ?>
                <li class="divider"></li>
                <li><a href="<?=base_url();?>ckp/all_ckp">Rekap CKP Pegawai</a></li>
          <?php } ?>
              </ul>
            </li>
          <?php } ?>

                <?php if($menu==2){ ?>
                <li class="dropdown active">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $this->session->userdata('nama_lengkap'); ?> <b class="caret"></b></a>
                  <ul class="dropdown-menu" style="width:100%">
                    <li><a href="<?=base_url();?>user/profil">Lihat Profil</a></li>
                    <li><a href="<?=base_url();?>user/ubah_password">Ubah Password</a></li>
                  </ul>
                </li>
              <?php }else{ ?>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $this->session->userdata('nama_lengkap'); ?> <b class="caret"></b></a>
                  <ul class="dropdown-menu" style="width:100%">
                    <li><a href="<?=base_url();?>user/profil">Lihat Profil</a></li>
                    <li><a href="<?=base_url();?>user/ubah_password">Ubah Password</a></li>
                  </ul>
                </li>
              <?php } ?>
              <?php } ?>
              </ul>
              <?php if($this->session->userdata('isLogin')) { ?>
                <a href="<?=base_url();?>login/logout" class="btn navbar-btn btn-ghost"><span class="glyphicon glyphicon-log-out"></span> Keluar</a>
              <?php }else{ ?>
                <a href="<?=base_url();?>login" class="btn navbar-btn btn-ghost">Login</a>
              <?php } ?>
            </div>
          </div>
        </div>
      </header>
      <?php echo $breadcrumb; ?>