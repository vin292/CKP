<!--
Developer: Yusfil Khoir Pulungan
Email: yusfilpulungan@bps.go.id
Website: http://saungsikma.com
-->
<div class="row" style="margin:20px">
  <?php if(!$nl==""){ if($Skp_row>0){ ?>
    <table cellspacing="0" width="100%" style="margin-bottom:20px;" >
      <tr>
        <td align="left">
          <button class="btn btn-primary noborder" onclick="printx()">Cetak</button>
          <button class="btn btn-success noborder" onclick="download()">Excel</button>
        </td>
        <td rowspan="2" align="right">
          <form class="form-inline" style="padding-bottom:10px">
          <div class="form-group" >
             <label for="selectEmployee">Nama Pegawai:</label>
             <select class="form-control" id="selectEmployee" style="width:250px; align: right">
             <?php foreach ($pegawai_all as $row) {
               if($row->gelar_belakang==""){
                 if(strcmp($row->niplama, $nl)==0){
              ?>
                 <option value='<?php echo urlencode(base64_encode($row->niplama)); ?>' selected><?php echo $row->n_panj; ?></option>
              <?php }else{ ?>
                <option value='<?php echo urlencode(base64_encode($row->niplama)); ?>'><?php echo $row->n_panj; ?></option>
              <?php }
              }else{
                if(strcmp($row->niplama,$nl)==0){ ?>
                <option value='<?php echo urlencode(base64_encode($row->niplama)); ?>' selected><?php echo $row->n_panj.', '.$row->gelar_belakang; ?></option>
              <?php }else{ ?>
                <option value='<?php echo urlencode(base64_encode($row->niplama)); ?>' ><?php echo $row->n_panj.', '.$row->gelar_belakang; ?></option>
              <?php }
              } } ?>
              </select>
           </div>
         </form>
        </td>
       </tr>
     </table>
  <div id="skp">
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
              <th rowspan="2" style="text-align:center; vertical-align:middle">KODE KEGIATAN</th>
              <th rowspan="2" style="text-align:center; vertical-align:middle">SATUAN AK</th>
              <th rowspan="2" style="text-align:center; vertical-align:middle">AK</th>
              <th colspan="5" style="text-align:center" width="200px">TARGET</th>
              <th rowspan="2" style="text-align:center; vertical-align:middle">BIAYA</th>
            </tr>
            <tr>
              <th colspan="2" style="text-align:center" width="50px">KUANTITAS/OUTPUT</th>
              <th style="text-align:center">KUALITAS/MUTU</th>
              <th colspan="2" style="text-align:center" width="150px">WAKTU</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $no=0;
              $sum_ak=0;
              foreach ($skp as $row) {
                $no++;
            ?>
            <tr>
              <td style='text-align:center'><?php echo $no; ?></td>
              <td><?php echo $row->n_keg; ?></td>
              <td style='text-align:center'><?php echo $row->kode_unsur.'.'.$row->kode_subunsur.'.'.$row->kode_butirkegiatan; ?></td>
              <?php
                $ak =0;
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
                $sum_ak+=$ak*$row->kuantitas;
              ?>
              <td style='text-align:center'><?php echo number_format($ak, 3, '.', ' '); ?></td>
              <td style='text-align:center'><?php echo number_format($ak*$row->kuantitas, 3, '.', ' '); ?></td>
              <td style='text-align:center'><?php echo $row->kuantitas; ?></td>
              <td style='text-align:center'><?php echo $row->satuan; ?></td>
              <td style='text-align:center'><?php echo $row->kualitas; ?></td>
              <td style='text-align:center'><?php echo $row->waktu; ?></td>
              <td style='text-align:center'><?php echo 'Bulan'; ?></td>
              <?php if($row->biaya==0){ ?>
                <td style='text-align:center'>-</td>
              <?php }else{ ?>
                <td style='text-align:center'><?php echo $row->biaya; ?></td>
              <?php } ?>
            </tr>
            <?php } ?>
            <tr>
              <td style='text-align:center' colspan="4">Jumlah</td>
              <td style='text-align:center'><?php echo number_format($sum_ak, 3, '.', ' '); ?></td>
              <td style='text-align:center' colspan="6"></td>
            </tr>
          </tbody>
        </table>
  </div>
  <?php } else { ?>
    <form class="form-inline" style="padding-bottom:10px">
          <div class="form-group" >
             <label for="selectEmployee">Nama Pegawai:</label>
             <select class="form-control" id="selectEmployee" style="width:250px; align: right">
             <?php foreach ($pegawai_all as $row) {
               if($row->gelar_belakang==""){
                 if(strcmp($row->niplama, $nl)==0){
              ?>
                 <option value='<?php echo urlencode(base64_encode($row->niplama)); ?>' selected><?php echo $row->n_panj; ?></option>
              <?php }else{ ?>
                <option value='<?php echo urlencode(base64_encode($row->niplama)); ?>'><?php echo $row->n_panj; ?></option>
              <?php }
              }else{
                if(strcmp($row->niplama,$nl)==0){ ?>
                <option value='<?php echo urlencode(base64_encode($row->niplama)); ?>' selected><?php echo $row->n_panj.', '.$row->gelar_belakang; ?></option>
              <?php }else{ ?>
                <option value='<?php echo urlencode(base64_encode($row->niplama)); ?>' ><?php echo $row->n_panj.', '.$row->gelar_belakang; ?></option>
              <?php }
              } } ?>
              </select>
           </div>
         </form>
    <div class="alert alert-warning">
      Maaf sasaran kinerja pegawai atas nama <strong><?php echo $pegawai->n_panj.', '.$pegawai->gelar_belakang;?></strong> untuk <strong>Tahun <?php echo date("Y"); ?></strong> belum tersedia !
    </div>
<?php } ?>
<?php } else { ?>
  <form class="form-inline" style="padding-bottom:10px">
        <div class="form-group" >
           <label for="selectEmployee">Nama Pegawai:</label>
           <select class="form-control" id="selectEmployee" style="width:250px; align: right">
             <option value='' selected>Pilih Pegawai</option>
           <?php foreach ($pegawai_all as $row) {
             if($row->gelar_belakang==""){
               if(strcmp($row->niplama, $nl)==0){
            ?>
               <option value='<?php echo urlencode(base64_encode($row->niplama)); ?>' selected><?php echo $row->n_panj; ?></option>
            <?php }else{ ?>
              <option value='<?php echo urlencode(base64_encode($row->niplama)); ?>'><?php echo $row->n_panj; ?></option>
            <?php }
            }else{
              if(strcmp($row->niplama,$nl)==0){ ?>
              <option value='<?php echo urlencode(base64_encode($row->niplama)); ?>' selected><?php echo $row->n_panj.', '.$row->gelar_belakang; ?></option>
            <?php }else{ ?>
              <option value='<?php echo urlencode(base64_encode($row->niplama)); ?>' ><?php echo $row->n_panj.', '.$row->gelar_belakang; ?></option>
            <?php }
            } } ?>
            </select>
         </div>
       </form>
  <div class="alert alert-info">
    Untuk menampilkan SKP Pegawai pilih <strong>Nama Pegawai</strong>!
  </div>
<?php } ?>
</div>
<script type="text/javascript">
  $("#selectEmployee").change(function(event) {
  window.location.href = "<?=base_url();?>detil-skp-pegawai/"+$('#selectEmployee').val();
  });
</script>
<script type="text/javascript">
function printx(){
  var divToPrint=document.getElementById('skp');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><head></head><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();

  setTimeout(function(){newWin.close();},10);
}
function download(){
  window.location.href = "<?=base_url();?>master/skp_pegawai_download/"+$('#selectEmployee').val();
}
</script>
