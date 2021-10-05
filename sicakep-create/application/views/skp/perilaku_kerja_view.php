  <div class="row" style="margin:20px">
    <table cellspacing="0" width="100%" style="margin-bottom:20px;">
      <tr width="100%">
        <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">PERILAKU KERJA STAFF TAHUN <?php echo date("Y"); ?></td>
      </tr>
    </table>
    <button class="btn btn-success noborder" onclick="tambah_pk()"><i class="glyphicon glyphicon-plus"></i> Tambah Penilaian</button>
    <div class="table-responsive">
      <table id="pk" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
          <th width="40px">No</th>
          <th>Nama Pegawai</th>
          <th>Tanggal Penilaian</th>
          <th>Orientasi Pelayanan</th>
          <th>Integritas</th>
          <th>Komitmen</th>
          <th>Disiplin</th>
          <th>Kerjasama</th>
          <th width="160px">Ubah</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
        </table>
      </div>
  </div>
<script type="text/javascript">

var save_method;
var table;

$(document).ready(function() {
    table = $('#pk').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('ppk/list_pk')?>",
            "type": "POST"
        },
        lengthChange: false,
        searching: false,
        info: false,
        paging: false,
        "language": {
            "lengthMenu": "Tampilkan _MENU_ indikator",
            "zeroRecords": "Maaf, saat ini data indikator tidak tersedia",
            "info": "Halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Maaf, data penilaian tersebut tidak ditemukan",
            "infoFiltered": "(Menyaring dari _MAX_ penilaian)",
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

      $(document).ready(function() {

        $('.datepicker').datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            todayHighlight: true,
            orientation: "top auto",
            todayBtn: true,
            todayHighlight: true,
        });
      });

});
function tambah_pk()
{
    save_method = 'add';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Penilaian Perilaku Kerja');
}
function ubah_pk(id)
{
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();


    $.ajax({
        url : "<?php echo site_url('ppk/get_pk/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id"]').val(data.id);
            $('[name="niplama"]').val(data.niplama);
            $('[name="tgl_ppk"]').val(data.tgl_ppk);
            $('[name="orientasi_pelayanan"]').val(data.orientasi_pelayanan);
            $('[name="integritas"]').val(data.integritas);
            $('[name="komitmen"]').val(data.komitmen);
            $('[name="disiplin"]').val(data.disiplin);
            $('[name="kerjasama"]').val(data.kerjasama);
            $('#modal_form').modal('show');
            $('.modal-title').text('Ubah Penilaian');

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

    if(save_method == 'add') {
        url = "<?php echo site_url('ppk/tambah_pk')?>";
    }else{
        url = "<?php echo site_url('ppk/ubah_pk')?>";
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
    function reload_table()
    {
        table.ajax.reload(null,false);
    }
    function hapus_pk(id)
    {
        swal({
            title: "Apakah anda akan menghapus data penilaian?",
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
                url : "<?php echo site_url('ppk/hapus_pk')?>/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                  swal({
                    title: "Berhasil menghapus data penilaian!",
                    type: "success",
                    timer: 1000,
                    showConfirmButton: false
                   });
                    $('#modal_form').modal('hide');
                    reload_table();
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
                <h3 class="modal-title">Entri Nilai Perilaku Kerja</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input name="id" placeholder="id" class="form-control" type="hidden">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Pegawai</label>
                            <div class="col-md-9">
                              <select id="niplama" name="niplama" data-placeholder="pilih nama pegawai"  class="form-control">
                                  <?php foreach ($pegawai as $row) { ?>
                                    <option value="<?php echo $row->niplama; ?>"><?php echo $row->gelar_depan.' '.$row->nama.' '.$row->gelar_belakang; ?></option>
                                  <?php } ?>
                              </select>
                              <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tanggal Penilaian</label>
                            <div class="col-md-9">
                                <input id="tgl_ppk" name="tgl_ppk" placeholder="  yyyy-mm-dd" class="form-control datepicker" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Orientasi Pelayanan</label>
                            <div class="col-md-9">
                                <input id="orientasi_pelayanan" name="orientasi_pelayanan" placeholder="orientasi pelayanan" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Integritas</label>
                            <div class="col-md-9">
                                <input id="integritas" name="integritas" placeholder="integritas" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Komitmen</label>
                            <div class="col-md-9">
                                <input id="komitmen" name="komitmen" placeholder="komitmen" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Disiplin</label>
                            <div class="col-md-9">
                                <input id="disiplin" name="disiplin" placeholder="disiplin" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kerjasama</label>
                            <div class="col-md-9">
                                <input id="kerjasama" name="kerjasama" placeholder="kerjasama" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                      </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="simpan()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
