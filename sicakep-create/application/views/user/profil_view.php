<section>
<div class="container">
  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">Data Pegawai</div>
      <div class="panel-body">
        <table class="table table-hover borderless">
          <tbody>
            <tr>
              <td width="150px">Nama</td>
              <td width="5px">:</td>
              <td><?php echo $pegawai->gelar_depan.' '.$pegawai->nama.' '.$pegawai->gelar_belakang; ?></td>
            </tr>
            <tr>
              <td>NIP Lama</td>
              <td>:</td>
              <td><?php echo $pegawai->niplama; ?></td>
            </tr>
            <tr>
              <td>NIP Baru</td>
              <td>:</td>
              <td><?php echo $pegawai->nipbaru; ?></td>
            </tr>
            <tr>
              <td>Email</td>
              <td>:</td>
              <td><?php echo $pegawai->email; ?></td>
            </tr>
            <tr>
              <td>Pangkat</td>
              <td>:</td>
              <td><?php echo $pangkat; ?></td>
            </tr>
            <tr>
              <td>Jabatan</td>
              <td>:</td>
              <td><?php echo $jabatan; ?></td>
            </tr>
            <tr>
              <td>Satuan Kerja</td>
              <td>:</td>
              <td><?php echo $pegawai->nm_satker; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</section>