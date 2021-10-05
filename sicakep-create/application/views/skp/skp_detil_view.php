<div class="row" style="margin:20px">
  <?php if($skp_row>0){ ?>
    <table cellspacing="0" width="100%" style="margin-bottom:20px;" >
      <tr>
        <td align="left">
          <button class="btn btn-primary btn-sm noborder" onclick="printx()">Cetak</button>
          <button class="btn btn-success btn-sm noborder" onclick="download()">Excel</button>
        </td>
        <td align="right">
          <button class="btn btn-success btn-sm noborder" onclick="tambah_skp()">Tambah SKP</button>
          <button class="btn btn-danger btn-sm noborder" onclick="edit_skp()">Ubah SKP</button>
        </td>
       </tr>
     </table>
  <div id="skp">
    <table cellspacing="0" width="100%" style="margin-bottom:20px;">
      <tr width="100%">
        <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">SASARAN KINERJA PEGAWAI TAHUN <?php echo date("Y"); ?></td>
      </tr>
    </table>
    <div class="table-responsive">
        <table class="table table-bordered " width="100%" style="margin-bottom:20px;border-collapse: collapse;" border="1">
          <thead>
            <tr>
              <th rowspan="2" style="text-align:center; vertical-align:middle">NO</th>
              <th rowspan="2" style="text-align:center; vertical-align:middle">KEGIATAN TUGAS JABATAN</th>
              <th rowspan="2" style="text-align:center; vertical-align:middle">KODE KEGIATAN</th>
              <th rowspan="2" style="text-align:center; vertical-align:middle">SATUAN AK</th>
              <th rowspan="2" style="text-align:center; vertical-align:middle">AK</th>
              <th colspan="3" style="text-align:center" width="200px">TARGET</th>
              <th rowspan="2" style="text-align:center; vertical-align:middle">BIAYA</th>
            </tr>
            <tr>
              <th style="text-align:center">KUANTITAS/OUTPUT</th>
              <th style="text-align:center">KUALITAS/MUTU</th>
              <th style="text-align:center" width="100px">WAKTU</th>
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
                  <td><?php echo $row->nm_keg; ?></td>
                  <td style='text-align:center'><?php echo '-'; ?></td>
                  <td style='text-align:center'><?php echo '-' ?></td>
                  <td style='text-align:center'><?php echo '-'; ?></td>
                  <td style='text-align:center'><?php echo $row->kuantitas.' '.$row->satuan; ?></td>
                  <td style='text-align:center'><?php echo $row->kualitas; ?></td>
                  <td style='text-align:center'><?php echo $row->waktu.' '.$row->satuan_waktu; ?></td>
                  <?php if($row->biaya==0){ ?>
                    <td style='text-align:center'>-</td>
                  <?php }else{ ?>
                    <td style='text-align:center'><?php echo $row->biaya; ?></td>
                  <?php } ?>
                </tr>
            <?php } ?>
                <tr>
                  <td style='text-align:center' colspan="4">Jumlah</td>
                  <td style='text-align:center'>-</td>
                  <td style='text-align:center' colspan="6"></td>
                </tr>
          </tbody>
        </table>
  </div>
  </div>
  <?php } else { ?>
  <div class="alert alert-warning">
    Maaf sasaran kinerja untuk <strong>Tahun <?php echo date("Y"); ?></strong> belum tersedia. <a href="<?=base_url();?>entri-skp.html">Klik disini</a> untuk membuat SKP!
  </div>
<?php } ?>
</div>
<script type="text/javascript">

function printx(){

	var restorepage = document.body.innerHTML;
	var printcontent = document.getElementById('skp').innerHTML;
	document.body.innerHTML = printcontent;
	window.print();
	document.body.innerHTML = restorepage;

}

function download(){
  window.location.href = "<?=base_url();?>skp/skp_download/";
}
function tambah_skp(){
  window.location.href = "<?=base_url();?>tambah-skp.html";
}
function edit_skp(){
  window.location.href = "<?=base_url();?>ubah-skp.html";
}
</script>
