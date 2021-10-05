<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'beranda';
$route['404_override'] = 'error_page';
$route['beranda.html'] = 'beranda';
$route['master-administrator.html'] = 'master/administrator';
$route['master-pegawai.html'] = 'master/pegawai';
$route['master-kegiatan.html'] = 'master/kegiatan';
$route['kelola-kegiatan-baru.html'] = 'master/kegiatan_baru';
$route['tambah-kegiatan.html'] = 'master/input_kegiatan';
$route["persetujuan.html"] = 'approve';
$route['login.html'] = 'login';
$route['login.html/(.+)'] = 'login/index/$1';
$route['logout.html'] = 'login/logout';
$route["proses-login.html"] = 'login/do_login';
$route["lihat-profil.html"] = 'user/profil';
$route["ubah-password.html"] = 'user/ubah_password';
$route['capaian-kinerja-pegawai.html'] = 'ckp';
$route["detil-target-capaian-kinerja-pegawai/(.*)"] = 'ckp';
$route["unduh-target-capaian-kinerja-pegawai/(.*)"] = 'ckp/ckp_downloadt';
$route["detil-realisasi-capaian-kinerja-pegawai/(.*)"] = 'ckp/ckp_r';
$route["detil-realisasi-capaian-kinerja-pegawai"] = 'ckp/ckp_r';
$route["unduh-realisasi-capaian-kinerja-pegawai/(.*)"] = 'ckp/ckp_download';
$route['entri-ckp-target.html'] = 'ckp/entri_ckp_target';
$route["input-target.html"] = 'ckp/input_target';
$route["input-target-pegawai.html"] = 'ckp/input_target_pegawai';
$route['entri-ckp-realisasi.html'] = 'ckp/entri_ckp_realisasi';
$route['rekap-ckp-pegawai.html'] = 'ckp/all_ckp';
$route['sasaran-kinerja-pegawai.html'] = 'skp';
$route['penilaian-prestasi-kerja.html'] = 'ppk';
$route['penilaian-perilaku-kerja.html'] = 'ppk/perilaku_kerja';
$route['entri-skp.html'] = 'skp/input_skp';
$route['tambah-skp.html'] = 'skp/input_skp';
$route["ubah-skp.html"] = 'skp/edit_skp';
$route['translate_uri_dashes'] = FALSE;
