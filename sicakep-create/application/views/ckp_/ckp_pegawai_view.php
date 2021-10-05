  <div class="row" style="margin:20px">
    <?php
    if(!$nl==""){
    if($ckp_row>0 AND $ck_row>0){
      $nama = $pegawai->n_panj;
      $nipbaru = $pegawai->nipbaru;
      $jabatan = $aut->id_lvl;
      $seksi = $pegawai->n_unitkerjapanj;
      $gelar_b = $pegawai->gelar_belakang;
      $seksi_id = $pegawai->id_unitkerja;
      $satker_id = $satker->id;
      $satker_n = $satker->n_satker;
      $so='';
      $jb='';
        if(strcmp(substr($satker_id,2,1),'7')==0){
          if(strcmp($seksi_id,'1')==0){
            $so ='Subbagian '.$seksi.' BPS Kota '.$satker_n;
              if(strcmp($jabatan,'3')==0){
                $jb ='Kepala Subbag '.$seksi;
              }else if(strcmp($jabatan,'4')==0){
                $jb ='Staf Subbag '.$seksi;
              }
          }else{
            if(strcmp($seksi_id,'0')==0){
              $so ='BPS Kota '.$satker_n;
              $jb ='Kepala BPS Kota '.$satker_n;
            }elseif(strcmp($seksi_id,'7')==0){
              $so ='BPS Kota '.$satker_n;
              $jb ='Koordinator Statistik Kecamatan';
            }else{
              $so ='Seksi '.$seksi.' BPS Kota '.$satker_n;
              if(strcmp($jabatan,'3')==0){
                $jb ='Kepala Seksi '.$seksi;
              }else if(strcmp($jabatan,'4')==0){
                $jb ='Staf Seksi '.$seksi;
              }
            }
          }
        }else{
          if(strcmp($seksi_id,'1')==0){
            $so ='Subbagian '.$seksi.' BPS Kab. '.$satker_n;
            if(strcmp($jabatan,'3')==0){
              $jb ='Kepala Subbag '.$seksi;
            }else if(strcmp($jabatan,'4')==0){
              $jb ='Staf Subbag '.$seksi;
            }
          }else{
            if(strcmp($seksi_id,'0')==0){
              $so ='BPS Kab. '.$satker_n;
              $jb ='Kepala BPS Kab. '.$satker_n;
            }elseif(strcmp($seksi_id,'7')==0){
              $so ='BPS Kab. '.$satker_n;
              $jb ='Koordinator Statistik Kecamatan';
            }else{
              $so ='Seksi '.$seksi.' BPS Kab. '.$satker_n;
              if(strcmp($jabatan,'3')==0){
                $jb ='Kepala Seksi '.$seksi;
              }else if(strcmp($jabatan,'4')==0){
                $jb ='Staf Seksi '.$seksi;
              }
            }
          }
        }
    ?>
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
          <div class="form-group">
             <label for="selectMonth">Tampilkan CKP Bulan:</label>
             <select class="form-control" id="selectMonth" style="width:250px; align: right">
             <?php
              $array_bulan = array(1=>"Januari","Februari","Maret", "April", "Mei", "Juni","Juli","Agustus","September","Oktober", "November","Desember");
              $no=0;
              if($m==''){
                $m=date("m");
              }
              foreach ($array_bulan as $row) {
                $no++;
                if($no==$m){
              ?>
                    <option value='<?php echo $no; ?>' selected><?php echo $row; ?></option>
              <?php }else{ ?>
                    <option value='<?php echo $no; ?>'><?php echo $row; ?></option>
              <?php } } ?>
              </select>
           </div>
         </form>
        </td>
       </tr>
     </table>
    <div id="ckp">
    <table cellspacing="0" width="100%" style="margin-bottom:20px;">
      <tr width="100%">
        <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">CAPAIAN KINERJA PEGAWAI TAHUN <?php echo date("Y"); ?></td>
      </tr>
    </table>
    <table cellspacing="0" style="margin-bottom:20px;" width="100%">
      <tr>
        <td width="150px">Satuan Organisasi</td>
        <td style="width:20px; text-align:center">:</td>
        <td><?php echo $so; ?></td>
        <?php
          $kuantitas = 0.35*$av_percentage->av_percentage;
          $kualitas = 0.35*$av_kualitas->av_kualitas;
          $kepatuhan = 0.1*$av_kepatuhan->av_kepatuhan*100;
          $ckp_final = $kualitas+$kuantitas+$beban_kerja+$kepatuhan;
        ?>
      </tr>
      <tr>
        <td>Nama</td>
        <td style="width:20px; text-align:center">:</td>
        <?php if($gelar_b=="") {?>
          <td><?php echo $nama; ?></td>
        <?php }else { ?>
          <td><?php echo $nama.', '.$gelar_b; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td>Jabatan</td>
        <td style="width:20px; text-align:center">:</td>
        <td><?php echo $jb; ?></td>
      </tr>
      <tr>
        <td>Jabatan Fungsional</td>
        <td style="width:20px; text-align:center">:</td>
        <td><?php echo $jb_fung; ?></td>
      </tr>
      <tr>
        <td>Periode</td>
        <td style="width:20px; text-align:center">:</td>
        <td id="periode"><?php echo $periode; ?></td>
      </tr>
    </table>
    <table cellspacing="0" width="100%" class="ckp" style="margin-bottom:20px;border-collapse: collapse;text-align:center" border="1">
      <thead>
      <tr>
        <th rowspan="2" >No</th>
        <th rowspan="2" >Uraian Kegiatan</th>
        <th rowspan="2" width="100px">Satuan</th>
        <th colspan="3" >Kuantitas</th>
        <th rowspan="2" width="70px">Tingkat Kualitas</th>
        <th colspan="2" width="70px">Volume</th>
        <th rowspan="2" width="100px">Keterangan</th>
      </tr>
      <tr>
        <th width="70px">Target</th>
        <th width="70px">Realisasi</th>
        <th width="70px">%</th>
        <th width="70px">Pengali</th>
        <th width="70px">Angka Kredit</th>
      </tr>
      </thead>
      <tfoot>
      <tr>
        <th colspan="8" style="text-align:right; padding-right:10px">JUMLAH ANGKA KREDIT</th>
        <th><?php echo number_format($sum_ak->sum_ak, 2, '.', ' '); ?></th>
        <th><?php echo number_format($beban_kerja, 2, '.', ' '); ?></th>
      </tr>
      <!-- <tr>
        <th colspan="5">CAPAIAN KINERJA PEGAWAI (CKP)</th>
        <th colspan="2"><?php echo number_format(($av_percentage->av_percentage+$av_kualitas->av_kualitas)/2, 2, '.', ' '); ?></th>
        <th colspan="3" bgcolor="#707070"></th>
      </tr> -->
      <tr>
        <th colspan="5">Kuantitas</th>
        <th colspan="2"><?php echo number_format($kuantitas, 2, '.', ' '); ?></th>
        <th colspan="3" bgcolor="#707070"></th>
      </tr>
      <tr>
        <th colspan="5">Kualitas</th>
        <th colspan="2"><?php echo number_format($kualitas, 2, '.', ' '); ?></th>
        <th colspan="3" bgcolor="#707070"></th>
      </tr>
      <tr>
        <th colspan="5">Bobot Volume</th>
        <th colspan="2"><?php echo number_format($beban_kerja, 2, '.', ' '); ?></th>
        <th colspan="3" bgcolor="#707070"></th>
      </tr>
      <tr>
        <th colspan="5">Kepatuhan</th>
        <th colspan="2"><?php echo number_format($kepatuhan, 2, '.', ' '); ?></th>
        <th colspan="3" bgcolor="#707070"></th>
      </tr>
      <tr>
        <th colspan="5">CAPAIAN KINERJA PEGAWAI (CKP)</th>
        <th colspan="2"><?php echo number_format($ckp_final, 2, '.', ' '); ?></th>
        <th colspan="3" bgcolor="#707070"></th>
      </tr>
      </tfoot>
      <tbody>
      <tr>
        <td>(1)</td>
        <td>(2)</td>
        <td>(3)</td>
        <td>(4)</td>
        <td>(5)</td>
        <td>(6)</td>
        <td>(7)</td>
        <td>(8)</td>
        <td>(9)</td>
        <td>(10)</td>
      </tr>
      <tr>
        <td colspan="10" style="text-align: left; font-weight:bold; padding-left:5px">UTAMA</td>
      </tr>
        <?php
        if($ckp_row>0){
        $no=0;
        foreach ($ckp as $row) {
          $no++; ?>
      <tr>
        <td><?php echo $no; ?></td>
        <td style="text-align: left; padding-left:5px"><?php echo $row->n_keg; ?></td>
        <td><?php echo $row->satuan; ?></td>
        <td><?php echo $row->target; ?></td>
        <td><?php echo $row->realisasi; ?></td>
        <td><?php echo number_format($row->persentase, 0, '.', ' '); ?></td>
        <td><?php echo $row->kualitas; ?></td>
        <td><?php echo number_format($row->pengali, 3, '.', ' '); ?></td>
        <td><?php echo number_format($row->ak, 3, '.', ' '); ?></td>
        <td></td>
      </tr>
      <?php }
    }else{?>
      <tr>
        <td>1</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>2</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <?php } ?>
      <tr>
        <td colspan="10" style="text-align: left; font-weight:bold; padding-left:5px">TAMBAHAN</td>
      </tr>
      <?php
      if($ckpt_row>0){
      $no=0;
      foreach ($ckpt as $row) {
        $no++; ?>
      <tr>
      <td><?php echo $no; ?></td>
      <td style="text-align: left; padding-left:5px"><?php echo $row->n_keg; ?></td>
      <td><?php echo $row->satuan; ?></td>
      <td><?php echo $row->target; ?></td>
      <td><?php echo $row->realisasi; ?></td>
      <td><?php echo number_format($row->persentase, 0, '.', ' '); ?></td>
      <td><?php echo $row->kualitas; ?></td>
      <td><?php echo number_format($row->pengali, 3, '.', ' '); ?></td>
      <td><?php echo number_format($row->ak, 3, '.', ' '); ?></td>
      <td></td>
      </tr>
      <?php }
      }else{?>
      <tr>
      <td>1</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      </tr>
      <tr>
      <td>2</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      </tr>
      <?php } ?>
      <tr>
        <th colspan="2" style="text-align:right; padding-right:10px">RATA-RATA KEGIATAN</th>
        <th></th>
        <th></th>
        <th></th>
        <th><?php echo number_format($av_percentage->av_percentage, 2, '.', ' '); ?></th>
        <th><?php echo number_format($av_kualitas->av_kualitas, 2, '.', ' '); ?></th>
        <th colspan="3" bgcolor="#707070"></th>
      </tr>
      <tr>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="10" style="text-align: left; font-weight:bold; padding-left:5px">KEPATUHAN</td>
      </tr>
      <?php
      if($ck_row>0){
      $no=0;
      foreach ($ck as $row) {
        $no++; ?>
    <tr>
      <td><?php echo $no; ?></td>
      <td style="text-align: left; padding-left:5px"><?php echo $row->ket; ?></td>
      <td><?php echo $row->satuan; ?></td>
      <td><?php echo $row->target; ?></td>
      <td><?php echo $row->realisasi; ?></td>
      <td><?php echo number_format($row->persentase, 0, '.', ' '); ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <?php }
  }else{?>
      <tr>
        <td>1</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>2</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    <?php } ?>
      <tr>
        <th colspan="2" style="text-align:right; padding-right:10px">RATA-RATA KEPATUHAN</th>
        <th></th>
        <th></th>
        <th></th>
        <th><?php echo number_format($kepatuhan*10, 2, '.', ' '); ?></th>
        <th></th>
        <th colspan="2" bgcolor="#707070"></th>
        <th><?php echo number_format($kepatuhan, 2, '.', ' '); ?></th>
      </tr>
      <td>&nbsp;</td>
      <tr>
      </tr>
      </tbody>
      </table>

      <table cellspacing="0" width="100%">
        <tr>
          <td colspan="2">Penilaian Kinerja</td>
        </tr>
        <tr>
          <td colspan="2">Tanggal : <?php echo $penilaian_k; ?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="50%" style="text-align:center">Pegawai Yang Dinilai</td>
          <td width="50%" style="text-align:center">Pejabat Penilai</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr style="margin-top:50px">
          <?php if($gelar_b=="") {?>
            <td width="50%" style="text-align:center"><?php echo $nama; ?></td>
          <?php }else{ ?>
            <td width="50%" style="text-align:center"><?php echo $nama.', '.$gelar_b; ?></td>
          <?php } ?>
          <td width="50%" style="text-align:center"><?php echo $penilai->n_panj.', '.$penilai->gelar_belakang; ?></td>
        </tr>
        <tr style="margin-top:50px">
          <td width="50%" style="text-align:center">NIP. <?php echo $nipbaru; ?></td>
          <td width="50%" style="text-align:center">NIP. <?php echo $penilai->nipbaru; ?></td>
        </tr>
      </table>
    </div>
      <?php }else{ ?>
        <form class="form-inline" style="padding-bottom:10px">
          <div class="form-group">
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
        <div class="form-group">
           <label for="selectMonth">Tampilkan CKP Bulan:</label>
           <select class="form-control" id="selectMonth" style="width:250px; align: right">
           <?php
            $array_bulan = array(1=>"Januari","Februari","Maret", "April", "Mei", "Juni","Juli","Agustus","September","Oktober", "November","Desember");
            $no=0;
            if($m==''){
              $m=date("m");
            }
            foreach ($array_bulan as $row) {
              $no++;
              if($no==$m){
            ?>
                  <option value='<?php echo $no; ?>' selected><?php echo $row; ?></option>
            <?php }else{ ?>
                  <option value='<?php echo $no; ?>'><?php echo $row; ?></option>
            <?php } } ?>
            </select>
         </div>
       </form>
       <?php if($pegawai->gelar_belakang==""){ ?>
        <div class="alert alert-warning">
          Maaf catatan kinerja pegawai atas nama <strong><?php echo $pegawai->n_panj;?></strong> untuk <strong>Bulan <?php echo $bln; ?></strong> tidak tersedia !
        </div>
      <?php } else { ?>
        <div class="alert alert-warning">
          Maaf catatan kinerja pegawai atas nama <strong><?php echo $pegawai->n_panj.', '.$pegawai->gelar_belakang;?></strong> untuk <strong>Bulan <?php echo $bln; ?></strong> tidak tersedia !
        </div>
      <?php } } ?>
      <?php }else{ ?>
        <form class="form-inline" style="padding-bottom:10px">
        <div class="form-group">
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
        <div class="form-group">
           <label for="selectMonth">Tampilkan CKP Bulan:</label>
           <select class="form-control" id="selectMonth" style="width:250px; align: right">
           <?php
            $array_bulan = array(1=>"Januari","Februari","Maret", "April", "Mei", "Juni","Juli","Agustus","September","Oktober", "November","Desember");
            $no=0;
            if($m==''){
              $m=date("m");
            }
            foreach ($array_bulan as $row) {
              $no++;
              if($no==$m){
            ?>
                  <option value='<?php echo $no; ?>' selected><?php echo $row; ?></option>
            <?php }else{ ?>
                  <option value='<?php echo $no; ?>'><?php echo $row; ?></option>
            <?php } } ?>
            </select>
         </div>
       </form>
      <div class="alert alert-info">
        Untuk menampilkan CKP Pegawai pilih <strong>Nama Pegawai</strong> dan <strong>Bulan</strong>!
      </div>
      <?php } ?>
  </div>
    <script type="text/javascript">
      $("#selectEmployee").change(function(event) {
      window.location.href = "<?=base_url();?>detil-ckp-pegawai/"+$('#selectEmployee').val()+"/"+$('#selectMonth').val();
      });
      function download(){
        window.location.href = "<?=base_url();?>master/ckp_pegawai_download/"+$('#selectEmployee').val()+"/"+$('#selectMonth').val();
      }
    </script>
    <script type="text/javascript">
      $("#selectMonth").change(function(event) {
      window.location.href = "<?=base_url();?>detil-ckp-pegawai/"+$('#selectEmployee').val()+"/"+$('#selectMonth').val();
      });
    </script>
    <script type="text/javascript">
    function printx(){
      var divToPrint=document.getElementById('ckp');
      var newWin=window.open('','Print-Window');
      newWin.document.open();
      newWin.document.write('<html><head></head><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
      newWin.document.close();

      setTimeout(function(){newWin.close();},10);
    }
    </script>
