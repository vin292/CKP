  <div class="row" style="margin:20px">
    <table cellspacing="0" width="100%" style="margin-bottom:20px;" >
      <tr>
        <td align="left">
          <button class="btn btn-primary noborder" onclick="printx()">Cetak</button>
          <button class="btn btn-success noborder" onclick="download()">Excel</button>
        </td>
      </table>
    <div class="table-responsive" id="rekap">
      <table cellspacing="0" width="100%" style="margin-bottom:20px;">
        <tr width="100%">
          <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">DAFTAR CAPAIAN KINERJA PEGAWAI TAHUN <?php echo date('Y'); ?></td>
        </tr>
      </table>
      <table width="100%" class="table table-hover table-bordered" style="margin-bottom:20px;border-collapse: collapse;" border="1">
        <thead>
          <tr>
            <th rowspan="2" style="text-align:center;vertical-align:middle">No</th>
            <th rowspan="2" style="text-align:center;vertical-align:middle">Nama Pegawai</th>
            <th colspan="12" style="text-align:center">Capaian Kinerja Pegawai Tahun <?php echo date('Y'); ?></th>
          </tr>
          <tr>
            <th style="text-align:center">Januari</th>
            <th style="text-align:center">Februari</th>
            <th style="text-align:center">Maret</th>
            <th style="text-align:center">April</th>
            <th style="text-align:center">Mei</th>
            <th style="text-align:center">Juni</th>
            <th style="text-align:center">Juli</th>
            <th style="text-align:center">Agustus</th>
            <th style="text-align:center">September</th>
            <th style="text-align:center">Oktober</th>
            <th style="text-align:center">November</th>
            <th style="text-align:center">Desember</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $x=0;
          $no=0;
          $l=count($januari);
          $data=array_merge($januari, $februari, $maret, $april, $mei, $juni, $juli, $agustus, $september, $oktober, $november, $desember);

          //print_r($data);
          //echo '</br>';
          //echo $data[3]['nama_l'].' : '.$data[$x]['ckp'];
          foreach ( $januari as $row) {
            $no++;
          ?>
            <tr>
              <td style="text-align:center"><?php echo $no; ?></td>
              <td><?php echo $data[$x]['nama_l']; ?></td>
              <?php if(!isset($data[$x]['ckp'])){ ?>
                <td style="text-align:center"></td>
              <?php }else{ ?>
                <td style="text-align:center"><?php echo number_format($data[$x]['ckp'], 2, '.', ' '); ?></td>
              <?php } ?>

              <?php if(!isset($data[$x+1*$l]['ckp'])){ ?>
                <td style="text-align:center"></td>
              <?php }else{ ?>
                <td style="text-align:center"><?php echo number_format($data[$x+1*$l]['ckp'], 2, '.', ' '); ?></td>
              <?php } ?>

              <?php if(!isset($data[$x+2*$l]['ckp'])){ ?>
                <td style="text-align:center"></td>
              <?php }else{ ?>
                <td style="text-align:center"><?php echo number_format($data[$x+2*$l]['ckp'], 2, '.', ' '); ?></td>
              <?php } ?>

              <?php if(!isset($data[$x+3*$l]['ckp'])){ ?>
                <td style="text-align:center"></td>
              <?php }else{ ?>
                <td style="text-align:center"><?php echo number_format($data[$x+3*$l]['ckp'], 2, '.', ' '); ?></td>
              <?php } ?>

              <?php if(!isset($data[$x+4*$l]['ckp'])){ ?>
                <td style="text-align:center"></td>
              <?php }else{ ?>
                <td style="text-align:center"><?php echo number_format($data[$x+4*$l]['ckp'], 2, '.', ' '); ?></td>
              <?php } ?>

              <?php if(!isset($data[$x+5*$l]['ckp'])){ ?>
                <td style="text-align:center"></td>
              <?php }else{ ?>
                <td style="text-align:center"><?php echo number_format($data[$x+5*$l]['ckp'], 2, '.', ' '); ?></td>
              <?php } ?>

              <?php if(!isset($data[$x+6*$l]['ckp'])){ ?>
                <td style="text-align:center"></td>
              <?php }else{ ?>
                <td style="text-align:center"><?php echo number_format($data[$x+6*$l]['ckp'], 2, '.', ' '); ?></td>
              <?php } ?>

              <?php if(!isset($data[$x+7*$l]['ckp'])){ ?>
                <td style="text-align:center"></td>
              <?php }else{ ?>
                <td style="text-align:center"><?php echo number_format($data[$x+7*$l]['ckp'], 2, '.', ' '); ?></td>
              <?php } ?>

              <?php if(!isset($data[$x+8*$l]['ckp'])){ ?>
                <td style="text-align:center"></td>
              <?php }else{ ?>
                <td style="text-align:center"><?php echo number_format($data[$x+8*$l]['ckp'], 2, '.', ' '); ?></td>
              <?php } ?>

              <?php if(!isset($data[$x+9*$l]['ckp'])){ ?>
                <td style="text-align:center"></td>
              <?php }else{ ?>
                <td style="text-align:center"><?php echo number_format($data[$x+9*$l]['ckp'], 2, '.', ' '); ?></td>
              <?php } ?>

              <?php if(!isset($data[$x+10*$l]['ckp'])){ ?>
                <td style="text-align:center"></td>
              <?php }else{ ?>
                <td style="text-align:center"><?php echo number_format($data[$x+10*$l]['ckp'], 2, '.', ' '); ?></td>
              <?php } ?>

              <?php if(!isset($data[$x+11*$l]['ckp'])){ ?>
                <td style="text-align:center"></td>
              <?php }else{ ?>
                <td style="text-align:center"><?php echo number_format($data[$x+11*$l]['ckp'], 2, '.', ' '); ?></td>
              <?php } ?>
            </tr>
          <?php $x++; } ?>
        </tbody>
      </table>
    </div>
  </div>
  <script type="text/javascript">
  function printx(){
    var divToPrint=document.getElementById('rekap');
    var newWin=window.open('','Print-Window');
    newWin.document.open();
    newWin.document.write('<html><head></head><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
    newWin.document.close();

    setTimeout(function(){newWin.close();},10);
  }
  function download(){
    alert('on progress');
  }
  </script>
