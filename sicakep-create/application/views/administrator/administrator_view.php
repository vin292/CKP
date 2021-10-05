<div class="row" style="margin:20px">
  <table cellspacing="0" width="100%" style="margin-bottom:20px;">
    <tr width="100%">
      <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">DAFTAR ADMINISTRATOR</td>
    </tr>
  </table>
  <button class="btn btn-sm btn-primary noborder" onclick="tambah_admin()"><i class="glyphicon glyphicon-plus"></i> Tambah Administrator</button>
<div class="table-responsive">
  <table id="admin" class="pink table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
      <th>Nip BPS</th>
      <th>Nama</th>
      <th>Email</th>
      <th>Satuan Kerja</th>
      <th>Level</th>
      <th width="160px">Ubah</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
      <th>Nip BPS</th>
      <th>Nama</th>
      <th>Email</th>
      <th>Satuan Kerja</th>
      <th>Level</th>
      <th>Ubah</th>
    </tr>
    </tfoot>
    <tbody>

    </tbody>
    </table>
</div>
</div>
<script type="text/javascript">

var save_method;
var table;

$(document).ready(function() {
  table = $('#admin').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
          "url": "<?php echo site_url('master/list_administrator')?>",
          "type": "POST"
      },
      lengthChange: false,
      searching: false,
      "language": {
          "lengthMenu": "Tampilkan _MENU_ admin",
          "zeroRecords": "Maaf, saat ini data admin tidak tersedia",
          "info": "Halaman _PAGE_ dari _PAGES_",
          "infoEmpty": "Maaf, data admin dengan kategori tersebut tidak ditemukan",
          "infoFiltered": "(Menyaring dari _MAX_ admin)",
          "paginate": {
            "next": "Selanjutnya",
            "previous": "Sebelumnya"
          }
      },
      "columnDefs": [
      {
          "targets": [ -1 ],
          "orderable": false,
      },
      ],

  });

  $("input").change(function(){
      $(this).parent().parent().removeClass('has-error');
      $(this).next().empty();
  });
  $("textarea").change(function(){
      $(this).parent().parent().removeClass('has-error');
      $(this).next().empty();
  });
  $("select").change(function(){
      $(this).parent().parent().removeClass('has-error');
      $(this).next().empty();
  });

});



function tambah_admin()
{
  save_method = 'add';
  $('#form')[0].reset();
  $('.form-group').removeClass('has-error');
  $('.help-block').empty();
  $('#modal_form').modal('show');
  $('.modal-title').text('Tambah Admin');
}

function ubah_admin(id)
{
  save_method = 'update';
  $('#form')[0].reset();
  $('.form-group').removeClass('has-error');
  $('.help-block').empty();

  $.ajax({
      url : "<?php echo site_url('master/get_admin/')?>/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {

          $('[name="niplama"]').val(data.niplama);
          $('[name="niplama2"]').val(data.niplama);
          $('[name="level"]').val(data.id_admin);
          $('#modal_form').modal('show');
          $('.modal-title').text('Ubah Admin');

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

function reload_table()
{
  table.ajax.reload(null,false);
}

function simpan()
{
  $('#btnSave').text('menyimpan...');
  $('#btnSave').attr('disabled',true);
  var url;

  if(save_method == 'add') {
      url = "<?php echo site_url('master/tambah_admin')?>";
  }else{
      url = "<?php echo site_url('master/ubah_admin')?>";
  }

  $.ajax({
      url : url,
      type: "POST",
      data: $('#form').serialize(),
      dataType: "JSON",
      success: function(data)
      {

          if(data.status)
          {
              $('#modal_form').modal('hide');
              reload_table();
              location.reload();
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
          title: "Terjadi kesalahan saat menambahkan data!",
          type: "error",
          showConfirmButton: true
        });
          $('#btnSave').text('Simpan');
          $('#btnSave').attr('disabled',false);

      }
  });
}

function hapus_admin(id)
{
  swal({
      title: "Apakah anda akan menghapus data?",
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
          url : "<?php echo site_url('master/hapus_admin')?>/"+id,
          type: "POST",
          dataType: "JSON",
          success: function(data)
          {
            swal({
              title: "Berhasil menghapus data administrator!",
              type: "success",
              timer: 1000,
              showConfirmButton: false
             });
              $('#modal_form').modal('hide');
              reload_table();
              location.reload();
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
              <h3 class="modal-title">Data Administrator</h3>
          </div>
          <div class="modal-body form">
              <form action="#" id="form" class="form-horizontal">
                <input name="niplama" placeholder="niplama" class="form-control" type="hidden">
                <div class="form-body">
                  <div class="form-group">
                    <label class="control-label col-md-3">Nama Pegawai</label>
                    <div class="col-md-9">
                        <select id="niplama2" name="niplama2" class="form-control">
                          <option value="">--Pilih Nama Pegawai--</option>
                            <?php
                              $no=0;
                              foreach ($pegawai as $row) {
                              $no++;
                            ?>
                          <option value="<?php echo $row->niplama ?>"><?php echo $row->nama ?></option>
                          <?php } ?>
                        </select>
                        <span class="help-block"></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3">Level</label>
                    <div class="col-md-9">
                      <select id="level" name="level" class="form-control">
                        <option value="">--Pilih Level Administrator--</option>
                          <option value="1">Super Admin</option>
                          <option value="2">Admin Provinsi</option>
                          <option value="3">Admin Kabupaten/Kota</option>
                        </select>
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
      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script>
$(function() {
  $("#form").validate({
    rules: {
      niplama2:{
          required:true,
      },
      level:{
          required:true,
      }
    },
    submitHandler: function(form) {
          simpan();
      }
  });

});
</script>