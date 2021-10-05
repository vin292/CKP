<!--
Developer: Yusfil Khoir Pulungan
Email: yusfilpulungan@bps.go.id
Website: http://saungsikma.com
-->
<div class="row" style="margin:20px">
  <?php if($skp_row>0){ ?>
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
              <th colspan="3" style="text-align:center" width="200px">TARGET</th>
              <th rowspan="2" style="text-align:center; vertical-align:middle">BIAYA</th>
              <th id="hapus" rowspan="2" style="text-align:center; vertical-align:middle">UBAH</th>
            </tr>
            <tr>
              <th style="text-align:center" >KUANTITAS/OUTPUT</th>
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
                  <td style='text-align:center'><?php echo '-'; ?></td>
                  <td style='text-align:center'><?php echo '-'; ?></td>
                  <td style='text-align:center'><?php echo $row->kuantitas.' '.$row->satuan; ?></td>
                  <td style='text-align:center'><?php echo $row->kualitas; ?></td>
                  <td style='text-align:center'><?php echo $row->waktu.' '.$row->satuan_waktu; ?></td>
                  <?php if($row->biaya==0){ ?>
                    <td style='text-align:center'>-</td>
                  <?php }else{ ?>
                    <td style='text-align:center'><?php echo $row->biaya; ?></td>
                  <?php } ?>
                  <td id="hapus" tyle='text-align:center' align="center">
                    <button class="btn btn-success noborder" onclick="ubah_skp('<?php echo $row->id; ?>')">Ubah</button>
                    <button class="btn btn-danger noborder" onclick="hapus_skp('<?php echo $row->id; ?>')">Hapus</button>
                  </td>
                </tr>
            <?php } ?>
                <tr>
                  <td style='text-align:center' colspan="4">Jumlah</td>
                  <td style='text-align:center'><?php echo '-'; ?></td>
                  <td style='text-align:center' colspan="6"></td>
                </tr>
          </tbody>
        </table>
  </div>
  <?php } else { ?>
  <div class="alert alert-warning">
    Maaf sasaran kinerja untuk <strong>Tahun <?php echo date("Y"); ?></strong> belum tersedia. <a href="<?=base_url();?>entri-skp.html">Klik disini</a> untuk membuat SKP!
  </div>
<?php } ?>
</div>
<script type="text/javascript">
function ubah_skp(id)
{
  $.ajax({
      url : "<?php echo site_url('skp/get_skp/')?>/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
          $('[name="id"]').val(data.id);
          $('[name="n_keg"]').val(data.nm_keg);
          $('[name="kuantitas"]').val(data.kuantitas);
          $('[name="kualitas"]').val(data.kualitas);
          $('[name="waktu"]').val(data.waktu);
          $('[name="satuan_waktu"]').val(data.satuan_waktu);
          $('[name="biaya"]').val(data.biaya);
          $('#modal_form').modal('show');
          $('.modal-title').text('Ubah Kegiatan');

      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        swal({
          title: "Terjadi kesalahan saat mengambil data!",
          type: "error",
          showConfirmButton: true
        });
      }
  });
}
function simpan()
{
    $('#btnSave').text('menyimpan...');
    $('#btnSave').attr('disabled',true);
    var url;
    url = "<?php echo site_url('skp/ubah_skp')?>";
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status)
            {
              swal({
                title: "Berhasil mengubah data SKP!",
                type: "success",
                timer: 1000,
                showConfirmButton: false
               });
                $('#modal_form').modal('hide');
                window.location.href = "<?=base_url();?>ubah-skp.html";
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
            }
            $('#btnSave').text('Simpan');
            $('#btnSave').attr('disabled',false);


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          swal({
            title: "Terjadi kesalahan saat menyimpan perubahan data!",
            type: "error",
            showConfirmButton: true
          });
            $('#btnSave').text('Simpan');
            $('#btnSave').attr('disabled',false);

        }
    });
  }
function hapus_skp(id)
{
    swal({
        title: "Apakah anda akan menghapus data SKP?",
        text: "Data yang sudah dihapus tidak dapat dikembalikan lagi!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Hapus",
        cancelButtonText: "Batal",
        closeOnConfirm: false
      },
      function(){
        $.ajax({
            url : "<?php echo site_url('skp/hapus_skp')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
              swal({
                title: "Berhasil menghapus data SKP!",
                type: "success",
                timer: 1000,
                showConfirmButton: false
               });
                window.location.href = "<?=base_url();?>ubah-skp.html";
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
              swal({
                title: "Terjadi kesalahan saat menghapus data!",
                type: "error",
                showConfirmButton: true
              });
            }
        });
      });
}
</script>
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Ubah Sasaran Kinerja</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input name="id" placeholder="id" class="form-control" type="hidden">
                    <div class="form-body">
                      <div class="form-group">
                            <label class="control-label col-md-3">Kegiatan</label>
                            <div class="col-md-9">
                                <input name="n_keg" placeholder="kegiatan" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                              <label class="control-label col-md-3">Kuantitas</label>
                              <div class="col-md-9">
                                  <input id="kuantitas" name="kuantitas" placeholder="kuantitas" class="form-control" type="number" min="0" required>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group">
                                <label class="control-label col-md-3">Kualitas</label>
                                <div class="col-md-9">
                                    <input id="kualitas" name="kualitas" placeholder="kualitas" class="form-control" type="number" min="0" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                  <label class="control-label col-md-3">Waktu</label>
                                    <div class="col-md-4">
                                      <input id="waktu" name="waktu" placeholder="waktu" class="form-control" type="number" min="0" required>
                                    </div>
                                    <div class="col-md-5">
                                    <select name="satuan_waktu" class="form-control" tabindex="1" style="width: 100%;"><option value="Hari">Hari</option><option value="Minggu">Minggu</option><option value="Bulan">Bulan</option></select>
                                  </div>
                                  <span class="help-block"></span>
                              </div>
                              <div class="form-group">
                                    <label class="control-label col-md-3">Biaya</label>
                                    <div class="col-md-9">
                                        <input name="biaya" placeholder="biaya" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                    </div>
                    <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {
  $("#form").validate({
    rules: {
      kuantitas:{
          required:true,
      },
      kualitas:{
          required:true,
      },
      waktu:{
          required:true,
      }
    },
    submitHandler: function(form) {
          simpan();
      }
  });

});
</script>