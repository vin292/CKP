<div class="row" style="margin:20px">
  <?php if($ppk_row>0){ ?>
    <table cellspacing="0" width="100%" style="margin-bottom:20px;" >
      <tr>
        <td align="left">
          <button class="btn btn-primary noborder" onclick="skp()">Penilaian SKP</button>
          <?php if($pk_row>0){ ?>
            <button class="btn btn-success noborder" onclick="pk()">Penilaian Perilaku Kerja</button>
          <?php } ?>
        </td>
       </tr>
     </table>
    <?php if($pk_row>0){ ?>
    <table cellspacing="0" width="100%" style="margin-bottom:20px;">
      <tr width="100%">
        <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">PENILAIAN PRESTASI KERJA PEGAWAI TAHUN <?php echo date("Y"); ?></td>
      </tr>
    </table>
        <table class="table table-bordered table-responsive" width="100%" style="margin-bottom:40px; border-collapse: collapse;" border="1">
          <tbody>
            <tr>
              <td style='font-weight:bold'>Nilai Sasaran Kinerja Pegawai (SKP)</td>
              <td style='text-align:center; font-weight:bold' colspan="2"><?php echo number_format($nilai/$count, 1, '.', ' '); ?> x 60%</td>
              <td style='text-align:center'><?php echo number_format(($nilai/$count)*0.6, 2, '.', ' '); ?></td>
            </tr>
            <?php
            $lvl=$this->session->userdata('lvl');
            if( strcmp($lvl, '3')== 0 ){
              $jumlah=$pk->orientasi_pelayanan+$pk->integritas+$pk->komitmen+$pk->disiplin+$pk->kerjasama+$pk->kepemimpinan;
              $rata2=$jumlah/6;
           }else{
             $jumlah=$pk->orientasi_pelayanan+$pk->integritas+$pk->komitmen+$pk->disiplin+$pk->kerjasama;
             $rata2=$jumlah/5;
           } ?>
            <tr>
              <td style='font-weight:bold'>Nilai Perilaku Kerja</td>
              <td style='text-align:center; font-weight:bold' colspan="2"><?php echo number_format($rata2, 2, '.', ' '); ?> x 40%</td>
              <td style='text-align:center;'><?php echo number_format($rata2*0.4, 2, '.', ' '); ?></td>
            </tr>
            <tr>
              <td style='text-align:center; font-weight:bold; vertical-align:middle' rowspan="2" colspan="3">NILAI PRESTASI KERJA</td>
              <td style='text-align:center;  font-weight:bold;'><?php echo number_format((($pk->orientasi_pelayanan+$pk->integritas+$pk->komitmen+$pk->disiplin+$pk->kerjasama)/5*0.4)+ ($nilai/$count*0.6), 2, '.', ' '); ?></td>
            </tr>
            <tr>
              <?php $n=((($pk->orientasi_pelayanan+$pk->integritas+$pk->komitmen+$pk->disiplin+$pk->kerjasama)/5*0.4)+ ($nilai/$count*0.6)); if($n <= 50) {?>
                <td style="text-align:center;font-weight:bold;">Buruk</td>
              <?php }else if($n > 50 AND $n <= 60){ ?>
                <td style="text-align:center;font-weight:bold;">Sedang</td>
              <?php }else if($n > 60 AND $n <= 75){ ?>
                <td style="text-align:center;font-weight:bold;">Cukup</td>
              <?php }else if($n > 75 AND $n <= 90.99){ ?>
                <td style="text-align:center;font-weight:bold;">Baik</td>
              <?php }else{ ?>
                  <td style="text-align:center;font-weight:bold;">Sangat Baik</td>
              <?php } ?>
            </tr>
          </tbody>
        </table>
    <?php }else{ ?>
      <div class="alert alert-warning">
        Maaf penilaian perilaku kerja untuk <strong>Tahun <?php echo date('Y'); ?></strong> belum tersedia, silakan menghubungi atasan anda!
      </div>
    <?php } ?>
    <div id="ppk">
    <table cellspacing="0" width="100%" style="margin-bottom:20px;">
      <tr width="100%">
        <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">SASARAN KINERJA PEGAWAI TAHUN <?php echo date("Y"); ?></td>
      </tr>
    </table>
        <table class="table table-bordered table-responsive" width="100%" style="margin-bottom:20px;border-collapse: collapse;" border="1">
          <thead>
            <tr>
              <th rowspan="2" style="text-align:center; vertical-align:middle">NO</th>
              <th rowspan="2" style="text-align:center; vertical-align:middle">KEGIATAN TUGAS JABATAN</th>
              <th rowspan="2" style="text-align:center; vertical-align:middle">AK</th>
              <th colspan="4" style="text-align:center" width="200px">TARGET</th>
              <th rowspan="2" style="text-align:center; vertical-align:middle">AK</th>
              <th colspan="4" style="text-align:center" width="200px">REALISASI</th>
              <th rowspan="2" style="text-align:center; vertical-align:middle">NILAI CAPAIAN SKP</th>
            </tr>
            <tr>
              <th style="text-align:center">KUANTITAS/OUTPUT</th>
              <th style="text-align:center">KUALITAS/MUTU</th>
              <th style="text-align:center" width="100px">WAKTU</th>
              <th style="text-align:center">BIAYA</th>
              <th style="text-align:center">KUANTITAS/OUTPUT</th>
              <th style="text-align:center">KUALITAS/MUTU</th>
              <th style="text-align:center" width="100px">WAKTU</th>
              <th style="text-align:center">BIAYA</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $no=0;
              $nilai=0;
              $count=0;
              foreach ($ppk as $row) {
                $no++;
            ?>
                <tr>
                  <td style='text-align:center'><?php echo $no; ?></td>
                  <td><?php echo $row->n_keg; ?></td>
                  <?php
                    $ak =0;
                    if($Fungsional_row>0){
                      if(strcmp($Fungsional->jabatan, 'A0101')==0){
                        $ak=$row->pelaksana_pemula;
                      }elseif (strcmp($Fungsional->jabatan, 'A0102')==0) {
                        $ak=$row->pelaksana;
                      }elseif (strcmp($Fungsional->jabatan, 'A0103')==0) {
                        $ak=$row->pelaksana_lanjutan;
                      }elseif (strcmp($Fungsional->jabatan, 'A0104')==0) {
                        $ak=$row->penyelia;
                      }elseif (strcmp($Fungsional->jabatan, 'A0105')==0) {
                        $ak=$row->pertama;
                      }elseif (strcmp($Fungsional->jabatan, 'A0106')==0) {
                        $ak=$row->muda;
                      }elseif (strcmp($Fungsional->jabatan, 'A0107')==0) {
                        $ak=$row->madya;
                      }elseif (strcmp($Fungsional->jabatan, 'A0108')==0) {
                        $ak=$row->utama;
                      }
                    }
                      $nilai+=((($row->realisasi_kuantitas/$row->target_kuantitas)+($row->realisasi_kualitas/$row->target_kualitas))/2)*100;
                      $count++;
                  ?>
                  <td style='text-align:center'><?php echo number_format($ak*$row->target_kuantitas, 3, '.', ' '); ?></td>
                  <td style='text-align:center'><?php echo $row->target_kuantitas.' '.$row->satuan; ?></td>
                  <td style='text-align:center'><?php echo $row->target_kualitas; ?></td>
                  <td style='text-align:center'><?php echo $row->waktu.' '.$row->satuan_waktu; ?></td>
                  <?php if(0==0){ ?>
                    <td style='text-align:center'>-</td>
                  <?php }else{ ?>
                    <td style='text-align:center'><?php echo '123'; ?></td>
                  <?php } ?>
                  <td style='text-align:center'><?php echo number_format($ak*$row->realisasi_kuantitas, 3, '.', ' '); ?></td>
                  <?php if(isset($row->realisasi_kuantitas)){ ?>
                    <td style='text-align:center'><?php echo number_format($row->realisasi_kuantitas, 0, '.', ' ').' '.$row->satuan; ?></td>
                  <?php }else{ ?>
                    <td style='text-align:center'><?php echo '0 '.$row->satuan; ?></td>
                  <?php } ?>
                  <?php if(isset($row->realisasi_kualitas)){ ?>
                    <td style='text-align:center'><?php echo number_format($row->realisasi_kualitas, 0, '.', ' '); ?></td>
                  <?php }else{ ?>
                    <td style='text-align:center'><?php echo '0'; ?></td>
                  <?php } ?>
                  <td style='text-align:center'><?php echo $row->waktu.' '.$row->satuan_waktu; ?></td>
                  <?php if(0==0){ ?>
                    <td style='text-align:center'>-</td>
                  <?php }else{ ?>
                    <td style='text-align:center'><?php echo '123'; ?></td>
                  <?php } ?>
                  <?php if(isset($row->realisasi_kuantitas)){ ?>
                    <td style='text-align:center'><?php echo number_format(((($row->realisasi_kuantitas/$row->target_kuantitas)+($row->realisasi_kualitas/$row->target_kualitas))/2)*100, 0, '.', ' '); ?></td>
                  <?php }else{ ?>
                    <td style='text-align:center'><?php echo '0'; ?></td>
                  <?php } ?>
                </tr>
            <?php } ?>
                <tr>
                  <td colspan="12" rowspan="2" style="text-align:center;font-weight:bold; vertical-align:middle">NILAI CAPAIAN SASARAN KINERJA PEGAWAI</td>
                  <td style="text-align:center;font-weight:bold; vertical-align:middle"><?php echo number_format($nilai/$count, 2, '.', ' '); ?></td>
                </tr>
                <tr>
                  <?php if($nilai/$count <= 50) {?>
                    <td style="text-align:center;font-weight:bold; vertical-align:middle">Buruk</td>
                  <?php }else if($nilai/$count > 50 AND $nilai/$count <= 60){ ?>
                    <td style="text-align:center;font-weight:bold; vertical-align:middle">Sedang</td>
                  <?php }else if($nilai/$count > 60 AND $nilai/$count <= 75){ ?>
                    <td style="text-align:center;font-weight:bold; vertical-align:middle">Cukup</td>
                  <?php }else if($nilai/$count > 75 AND $nilai/$count <= 90.99){ ?>
                    <td style="text-align:center;font-weight:bold; vertical-align:middle">Baik</td>
                  <?php }else{ ?>
                      <td style="text-align:center;font-weight:bold; vertical-align:middle">Sangat Baik</td>
                  <?php } ?>
                </tr>
          </tbody>
        </table>
  </div>
  <?php } else { ?>
  <div class="alert alert-warning">
    Maaf penilaian kinerja untuk <strong>Tahun <?php echo date("Y"); ?></strong> belum tersedia. <a href="<?=base_url();?>tambah-skp.html">Klik disini</a> untuk melihat SKP!
  </div>
<?php } ?>
<div id="pk">
  <table cellspacing="0" width="100%" style="margin-bottom:20px;">
    <tr width="100%">
      <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">PERILAKU KERJA TAHUN <?php echo date("Y"); ?></td>
    </tr>
  </table>
  <table class="table table-bordered table-responsive" width="100%" style="margin-bottom:20px;border-collapse: collapse;" border="1">
    <thead>
      <tr>
        <th style="text-align:center">INDIKATOR</th>
        <th style="text-align:center">NILAI</th>
        <th style="text-align:center">KETERANGAN</th>
      </tr>
    </thead>
      <tbody>
          <tr>
              <td>Orientasi Pelayanan</td>
              <td style='text-align:center'><?php echo $pk->orientasi_pelayanan; ?></td>
                <?php $n=$pk->orientasi_pelayanan; if($n <= 50) {?>
                  <td style="text-align:center;font-weight:bold;">Buruk</td>
                <?php }else if($n > 50 AND $n <= 60){ ?>
                  <td style="text-align:center;font-weight:bold;">Sedang</td>
                <?php }else if($n > 60 AND $n <= 75){ ?>
                  <td style="text-align:center;font-weight:bold;">Cukup</td>
                <?php }else if($n > 75 AND $n <= 90.99){ ?>
                  <td style="text-align:center;font-weight:bold;">Baik</td>
                <?php }else{ ?>
                    <td style="text-align:center;font-weight:bold;">Sangat Baik</td>
                <?php } ?>
            </tr>
            <tr>
              <td>Integritas</td>
              <td style='text-align:center'><?php echo $pk->integritas; ?></td>
              <?php $n=$pk->integritas; if($n <= 50) {?>
                <td style="text-align:center;font-weight:bold;">Buruk</td>
              <?php }else if($n > 50 AND $n <= 60){ ?>
                <td style="text-align:center;font-weight:bold;">Sedang</td>
              <?php }else if($n > 60 AND $n <= 75){ ?>
                <td style="text-align:center;font-weight:bold;">Cukup</td>
              <?php }else if($n > 75 AND $n <= 90.99){ ?>
                <td style="text-align:center;font-weight:bold;">Baik</td>
              <?php }else{ ?>
                  <td style="text-align:center;font-weight:bold;">Sangat Baik</td>
              <?php } ?>
            </tr>
            <tr>
              <td>Komitmen</td>
              <td style='text-align:center'><?php echo $pk->komitmen; ?></td>
              <?php $n=$pk->komitmen; if($n <= 50) {?>
                <td style="text-align:center;font-weight:bold;">Buruk</td>
              <?php }else if($n > 50 AND $n <= 60){ ?>
                <td style="text-align:center;font-weight:bold;">Sedang</td>
              <?php }else if($n > 60 AND $n <= 75){ ?>
                <td style="text-align:center;font-weight:bold;">Cukup</td>
              <?php }else if($n > 75 AND $n <= 90.99){ ?>
                <td style="text-align:center;font-weight:bold;">Baik</td>
              <?php }else{ ?>
                  <td style="text-align:center;font-weight:bold;">Sangat Baik</td>
              <?php } ?>
            </tr>
            <tr>
              <td>Disiplin</td>
              <td style='text-align:center'><?php echo $pk->disiplin; ?></td>
              <?php $n=$pk->disiplin; if($n <= 50) {?>
                <td style="text-align:center;font-weight:bold;">Buruk</td>
              <?php }else if($n > 50 AND $n <= 60){ ?>
                <td style="text-align:center;font-weight:bold;">Sedang</td>
              <?php }else if($n > 60 AND $n <= 75){ ?>
                <td style="text-align:center;font-weight:bold;">Cukup</td>
              <?php }else if($n > 75 AND $n <= 90.99){ ?>
                <td style="text-align:center;font-weight:bold;">Baik</td>
              <?php }else{ ?>
                  <td style="text-align:center;font-weight:bold;">Sangat Baik</td>
              <?php } ?>
            </tr>
            <tr>
              <td>Kerjasama</td>
              <td style='text-align:center'><?php echo $pk->kerjasama; ?></td>
              <?php $n=$pk->kerjasama; if($n <= 50) {?>
                <td style="text-align:center;font-weight:bold;">Buruk</td>
              <?php }else if($n > 50 AND $n <= 60){ ?>
                <td style="text-align:center;font-weight:bold;">Sedang</td>
              <?php }else if($n > 60 AND $n <= 75){ ?>
                <td style="text-align:center;font-weight:bold;">Cukup</td>
              <?php }else if($n > 75 AND $n <= 90.99){ ?>
                <td style="text-align:center;font-weight:bold;">Baik</td>
              <?php }else{ ?>
                  <td style="text-align:center;font-weight:bold;">Sangat Baik</td>
              <?php } ?>
            </tr>
            <?php if( strcmp($this->session->userdata('lvl'), '3')== 0 ){ ?>
              <tr>
                <td>Kepemimpinan</td>
                <td style='text-align:center'><?php echo $pk->kepemimpinan; ?></td>
                <?php $n=$pk->kepemimpinan; if($n <= 50) {?>
                  <td style="text-align:center;font-weight:bold;">Buruk</td>
                <?php }else if($n > 50 AND $n <= 60){ ?>
                  <td style="text-align:center;font-weight:bold;">Sedang</td>
                <?php }else if($n > 60 AND $n <= 75){ ?>
                  <td style="text-align:center;font-weight:bold;">Cukup</td>
                <?php }else if($n > 75 AND $n <= 90.99){ ?>
                  <td style="text-align:center;font-weight:bold;">Baik</td>
                <?php }else{ ?>
                    <td style="text-align:center;font-weight:bold;">Sangat Baik</td>
                <?php } ?>
              </tr>
            <?php }else{ ?>
              <tr>
                <td>Kepemimpinan</td>
                <td style='text-align:center' bgcolor="#707070"></td>
                <td style='text-align:center; font-weight:bold' bgcolor="#707070"></td>
              </tr>
            <?php } ?>
            <tr>
              <td>Jumlah</td>
              <td style='text-align:center'><?php echo number_format($jumlah, 0, '.', ' '); ?></td>
              <td style='text-align:center; font-weight:bold'>Baik</td>
            </tr>
            <tr>
              <td style='text-align:center; font-weight:bold; vertical-align:middle'>NILAI PERILAKU KERJA</td>
              <td style='text-align:center;  font-weight:bold;'><?php echo number_format($rata2, 2, '.', ' '); ?></td>
              <?php $n=$rata2; if($n <= 50) {?>
                <td style="text-align:center;font-weight:bold;">Buruk</td>
              <?php }else if($n > 50 AND $n <= 60){ ?>
                <td style="text-align:center;font-weight:bold;">Sedang</td>
              <?php }else if($n > 60 AND $n <= 75){ ?>
                <td style="text-align:center;font-weight:bold;">Cukup</td>
              <?php }else if($n > 75 AND $n <= 90.99){ ?>
                <td style="text-align:center;font-weight:bold;">Baik</td>
              <?php }else{ ?>
                  <td style="text-align:center;font-weight:bold;">Sangat Baik</td>
              <?php } ?>
            </tr>
        </tbody>
  </table>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  $('#ppk').hide();
  $('#pk').hide();
});
function skp() {
  $('#ppk').show();
  $('#pk').hide();
   $('html, body').animate({ scrollTop: $('#ppk').offset().top }, 'slow');
}
function pk() {
  $('#pk').show();
  $('#ppk').hide();
   $('html, body').animate({ scrollTop: $('#pk').offset().top }, 'slow');
}
function printx(){
  var divToPrint=document.getElementById('ppk');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><head></head><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();

  setTimeout(function(){newWin.close();},10);
}
function download(){
  //window.location.href = "<?=base_url();?>master/skp_download/";
}
</script>
