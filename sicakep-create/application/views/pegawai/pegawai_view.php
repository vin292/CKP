  <div class="row" style="margin:20px">
    <table cellspacing="0" width="100%" style="margin-bottom:20px;">
      <tr width="100%">
        <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">DAFTAR PEGAWAI</td>
      </tr>
    </table>
    <button class="btn btn-sm btn-primary noborder" onclick="tambah_pegawai()"><i class="glyphicon glyphicon-plus"></i> Tambah Pegawai</button>
    <button class="btn btn-sm btn-success noborder" onclick="tambah_akun()"><i class="glyphicon glyphicon-plus"></i> Tambah Akun</button>
<div class="table-responsive">
    <table id="pegawai" class="pink table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
      <tr>
        <th>Nip Lama</th>
        <th>Nip Baru</th>
        <th>Nama</th>
        <th>Pangkat/Gol.</th>
        <th>Email</th>
        <th>Unit Organisasi</th>
        <th width="240px">Ubah</th>
      </tr>
      </thead>
      <tfoot>
      <tr>
        <th>Nip Lama</th>
        <th>Nip Baru</th>
        <th>Nama</th>
        <th>Pangkat/Gol.</th>
        <th>Email</th>
        <th>Unit Organisasi</th>
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
    table = $('#pegawai').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('master/list_pegawai')?>",
            "type": "POST"
        },
        lengthChange: false,
        searching: false,
        "language": {
            "lengthMenu": "Tampilkan _MENU_ pegawai",
            "zeroRecords": "Maaf, saat ini data pegawai tidak tersedia",
            "info": "Halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Maaf, data pegawai dengan kategori tersebut tidak ditemukan",
            "infoFiltered": "(Menyaring dari _MAX_ pegawai)",
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



function tambah_pegawai()
{
    save_method = 'add';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Tambah Pegawai');
}
function tambah_akun()
{
    save_method = 'add_akun';
    $('#form_akun')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal3_form').modal('show');
    $('.modal-title').text('Tambah Akun');
}

function ubah_pegawai(id)
{
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();

    $.ajax({
        url : "<?php echo site_url('master/get_pegawai/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="niplama"]').val(data.niplama);
            $('[name="niplama2"]').val(data.niplama);
            $('[name="g_depan"]').val(data.gelar_depan);
            $('[name="g_belakang"]').val(data.gelar_belakang);
            $('[name="nama"]').val(data.nama);
            $('[name="nipbaru"]').val(data.nipbaru);
            $('[name="email"]').val(data.email);
            $('[name="org"]').val(data.id_org);
            $('[name="gol"]').val(data.id_gol);
            $('#modal_form').modal('show');
            $('.modal-title').text('Ubah Pegawai');

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
function ubah_hakakses(id)
{
    save_method = 'update_akun';
    $('#form2_akun')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();

    $.ajax({
        url : "<?php echo site_url('master/get_aut/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="niplama_ubahakun"]').val(data.niplama);
            $('[name="username"]').val(data.username);
            $('[name="password"]').val(data.password.substring(1, 10));
            $('[name="level"]').val(data.id_level);
            $('#modal2_form').modal('show');
            $('.modal-title').text('Ubah Akun');

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
        url = "<?php echo site_url('master/tambah_pegawai')?>";
    }else{
        url = "<?php echo site_url('master/ubah_pegawai')?>";
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
            title: "Terjadi kesalahan saat menambahkan data pegawai!",
            type: "error",
            showConfirmButton: true
          });
            $('#btnSave').text('save');
            $('#btnSave').attr('disabled',false);

        }
    });
}
function simpan_akun()
{
    $('#btnSaveAkun').text('menyimpan...');
    $('#btnSaveAkun').attr('disabled',true);
    var url;
    url = "<?php echo site_url('master/tambah_akun')?>";
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_akun').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status)
            {
                $('#modal3_form').modal('hide');
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
            $('#btnSaveAkun').text('Simpan');
            $('#btnSaveAkun').attr('disabled',false);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          swal({
            title: "Terjadi kesalahan saat mengambil akun!",
            type: "error",
            showConfirmButton: true
          });
            $('#btnSave').text('save');
            $('#btnSave').attr('disabled',false);
        }
    });
}

function ubah_akun()
{
    $('#btnSaveAkun2').text('menyimpan...');
    $('#btnSaveAkun2').attr('disabled',true);
    var url;

        url = "<?php echo site_url('master/ubah_akun')?>";


    $.ajax({
        url : url,
        type: "POST",
        data: $('#form2_akun').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status)
            {
                $('#modal2_form').modal('hide');
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
            $('#btnSaveAkun2').text('Simpan');
            $('#btnSaveAkun2').attr('disabled',false);


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Terjadi Kesalahan saat menambahkan akun');
            $('#btnSaveAkun2').text('Simpan');
            $('#btnSaveAkun2').attr('disabled',false);

        }
    });
}
function hapus_pegawai(id)
{
    swal({
        title: "Apakah anda akan menghapus data pegawai?",
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
            url : "<?php echo site_url('master/hapus_pegawai')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
              swal({
                title: "Berhasil menghapus data pegawai!",
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
                <h3 class="modal-title">Data Pegawai</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input name="niplama" placeholder="niplama" class="form-control" type="hidden">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nip Lama</label>
                            <div class="col-md-9">
                                <input id=niplama2 name="niplama2" placeholder="niplama" maxlength="9" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Gelar Depan</label>
                            <div class="col-md-9">
                                <input id="g_depan" name="g_depan" placeholder="gelar depan" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama</label>
                            <div class="col-md-9">
                                <input id="nama" name="nama" placeholder="nama" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Gelar Belakang</label>
                            <div class="col-md-9">
                                <input id="g_belakang" name="g_belakang" placeholder="gelar belakang" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nip Baru</label>
                            <div class="col-md-9">
                                <input id="nipbaru" name="nipbaru" placeholder="nipbaru" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Pangkat/Golongan</label>
                            <div class="col-md-9">
                                <select id="gol" name="gol" class="form-control">
                                    <option value="">--Pilih Pangkat/Golongan--</option>
                                    <?php
                                        $no=0;
                                        foreach ($gol as $row) {
                                        $no++;
                                        ?>
                                        <option value="<?php echo $row->id_gol ?>"><?php echo $row->pangkat."(".$row->n_gol.")" ?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Email</label>
                            <div class="col-md-9">
                                <input id="email" name="email" placeholder="email" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit Kerja</label>
                            <div class="col-md-9">
                            <select id="org" name="org" class="form-control">
                                <option value="">--Pilih Unit Organisasi--</option>
                                  <?php
                                    $no=0;
                                    foreach ($org as $row) {
                                      $no++;
                                    ?>
                                      <option value="<?php echo $row->id_org ?>"><?php echo $row->nm_org ?></option>
                                  <?php } ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-success" >Simpan</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </div>
                      
                </form>
            </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal2_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Akun Pegawai</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form2_akun" class="form-horizontal">
                    <input name="niplama_ubahakun" placeholder="niplama" class="form-control" type="hidden">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Username</label>
                            <div class="col-md-9">
                                <input id="username" name="username" placeholder="username" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Password</label>
                            <div class="col-md-9">
                                <input id="password" name="password" placeholder="password" class="form-control" type="password">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Level</label>
                            <div class="col-md-9">
                            <select id="level" name="level" class="form-control">
                                <option value="">--Pilih Level Akun--</option>
                                  <?php
                                    $no=0;
                                    foreach ($level as $row) {
                                      $no++;
                                    ?>
                                      <option value="<?php echo $row->id_level ?>"><?php echo $row->ket ?></option>
                                  <?php } ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" id="btnSaveAkun2" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal3_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Akun Pegawai</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_akun" class="form-horizontal">
                  <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama</label>
                            <div class="col-md-9">
                                <select id="niplama_akun" name="niplama_akun" class="form-control">
                                    <option value="">--Pilih Pegawai--</option>
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
                            <label class="control-label col-md-3">Username</label>
                            <div class="col-md-9">
                                <input id="username" name="username" placeholder="username" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Password</label>
                            <div class="col-md-9">
                                <input id="password" name="password" placeholder="password" class="form-control" type="password">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Level</label>
                            <div class="col-md-9">
                            <select id="level" name="level" class="form-control">
                                <option value="">--Pilih Level Akun--</option>
                                  <?php
                                    $no=0;
                                    foreach ($level as $row) {
                                      $no++;
                                    ?>
                                      <option value="<?php echo $row->id_level ?>"><?php echo $row->ket ?></option>
                                  <?php } ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                      <div class="modal-footer">
                            <button type="submit" id="btnSaveAkun" class="btn btn-success">Simpan</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {
$("#form").validate({
  rules: {
    niplama2: {
      required: true,
      minlength: 9,
      digits: true
    },
    nama: {
      required: true,
      minlength: 5,
    },
    nipbaru: {
      required: true,
      minlength: 18,
    },
    gol:{
        required:true,
    },
    email:{
        required:true,
        email:true,
    },
    org:{
        required:true,
    }
  },
  submitHandler: function(form) {
        simpan();
    }
});

$("#form_akun").validate({
  rules: {
    niplama_akun: {
      required: true,
    },
    username: {
      required: true,
      minlength: 5,
    },
    password: {
      required: true,
      minlength: 5,
    },
    level: {
      required: true,
    },
  },
  submitHandler: function(form) {
        simpan_akun();
    }
});

$("#form2_akun").validate({
  rules: {
    username: {
      required: true,
      minlength: 5,
    },
    password: {
      required: true,
      minlength: 5,
    },
    level: {
      required: true,
    },
  },
  submitHandler: function(form) {
        ubah_akun();
    }
});

});
</script>