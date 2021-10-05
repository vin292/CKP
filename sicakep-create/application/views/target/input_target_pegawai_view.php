<div class="container">
  <div class="row">
    <div >
    <form action="#" id="form_keg" class="form-horizontal">
      <table class="table kosong-border"  style="margin-bottom:0px;">
        <tr>
            <td width="150px" style="font-weight:bold">
              Nama Pegawai
            </td>
            <td width="10px" style="font-weight:bold">
              :
            </td>
            <td colspan="5">
              <select id="niplama" name="niplama" data-placeholder="pilih nama pegawai"  class=" list_pegawai" tabindex="1" style="width: 100%;">
                  <option value=""></option>
                  <?php foreach ($pegawai as $row) { ?>
                    <option value="<?php echo $row->niplama; ?>"><?php echo $row->gelar_depan.' '.$row->nama.' '.$row->gelar_belakang; ?></option>
                  <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
          <td style="font-weight:bold">
            Bulan CKP
          </td>
          <td style="font-weight:bold">
            :
          </td>
          <td colspan="5">
              <div class="input-group date" id="tgl_ckp">
                  <input placeholder="" class="form-control" type="text" readonly>	
                  <span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>
              </div>
          </td>
        </tr>
          <tr>
            <td style="font-weight:bold">
                Kegiatan dari SKP
            </td>
            <td style="font-weight:bold">
                :
            </td>
            <td colspan="5">
                <select id="id_keg" name="id_keg" data-placeholder="pilih kegiatan dari skp"  class="list_keg" tabindex="1" style="width: 100%;">
                    <option selected disabled></option>
                </select>
            </td>
          </tr>
                    <tr>
                      <td style="font-weight:bold">
                        Kegiatan Lainnya
                      </td>
                      <td style="font-weight:bold">
                        :
                      </td>
                      <td colspan="5">
                        <select id="id_keg2" name="id_keg2" data-placeholder="pilih kegiatan lain"  class="list_keg" tabindex="1" style="width: 100%;">
                            <option selected disabled></option>
                        </select>
                      </td>
                    </tr>
          <tr>
              <td colspan="7" style="text-align:right">
                <button type="button" class="btn btn-default noborder" onclick="tambah_kegiatanbaru()">Kegiatan Baru</button>
              </td>
          </tr>
      </table>
      <table cellspacing="0" width="100%" style="margin-bottom:20px;margin-top:20px;">
        <tr width="100%">
          <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">TARGET CAPAIAN KINERJA PEGAWAI</td>
        </tr>
      </table>

      <table class="table table-bordered table-responsive" id="dynamic_field" style="margin:0px;">
        <thead>
          <tr>
            <th style="text-align:center; vertical-align:middle" width="10px">NO</th>
            <th style="text-align:center; vertical-align:middle">URAIAN KEGIATAN</th>
            <th style="text-align:center" width="150px">TARGET</th>
            <th style="text-align:center" width="150px">KETERANGAN</th>
            <th style="text-align:center; vertical-align:middle" width="10px">HAPUS</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </form>
    <table  style="margin:0px;" class="table kosong-border">
      <tr>
        <td align="right">
          <button type="button" name="submit" id="submit" class="btn btn-success noborder">Simpan</button>
          <button type="button" class="btn btn-danger noborder" onclick="window.location.href = '<?=base_url();?>persetujuan.html'">Batal</button>
        </td>
      </tr>
  </table>
  </div>
  </div>
</div>

<script type="text/javascript" src="<?=base_url();?>assets/js/select2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $(".list_keg").select2({
      "language": {
          "noResults": function(){
              return "Maaf, kegiatan tidak ditemukan!";
          }
      }
    });

    $(".list_pegawai").select2({
      "language": {
          "noResults": function(){
              return "Maaf, kegiatan tidak ditemukan!";
          }
      }
    });

    $('#tgl_ckp').datepicker({
      autoclose: true,
      startView: "months",
      minViewMode: "months",
      locale:'id',
      format:'MM',
    });

    function get_keg_skp(niplama, url, selector)
    {
      $.ajax({
        url : url,
        dataType :"html",
        data : {niplama:niplama},
        type: "POST",
        success:function(r){
          $(selector).html(r);
        }
      });
    }
      $("#niplama").change(function(){
        var niplama = $('#niplama').val();
        get_keg_skp(niplama,'<?php echo site_url('ckp/get_keg_skp');?>', '#id_keg');
        get_keg_skp(niplama,'<?php echo site_url('ckp/get_keg');?>', '#id_keg2');
      });
  });
</script>
<script type="text/javascript">

     var idx=0;

     $("#id_keg").change(function(event) {
       idx++;
       $('#dynamic_field').append('<tr id="row'+idx+'"><td>'+idx+'</td><td style="font-weight:bold">'+$('#id_keg option:selected').text()+'</td><td width="150px"><input type="hidden" name="id_kegiatan[]" placeholder="Id Kegiatan" class="form-control" value="'+$('#id_keg option:selected').val()+'"/><input type="text" name="target[]" placeholder="Target Kegiatan" class="form-control" /></td><td width="150px"><select name="jenis[]" class="form-control" tabindex="1" style="width: 100%;"><option value="1">Utama</option><option value="0">Tambahan</option></select></td><td width="10px"><button type="button" name="remove" id="'+idx+'" class="btn btn-danger btn_remove">X</button></td></tr>');
     });

     $("#id_keg2").change(function(event) {
       idx++;
       $('#dynamic_field').append('<tr id="row'+idx+'"><td>'+idx+'</td><td style="font-weight:bold">'+$('#id_keg2 option:selected').text()+'</td><td width="150px"><input type="hidden" name="id_kegiatan[]" placeholder="Id Kegiatan" class="form-control" value="'+$('#id_keg2 option:selected').val()+'"/><input type="text" name="target[]" placeholder="Target Kegiatan" class="form-control" /></td><td width="150px"><select name="jenis[]" class="form-control" tabindex="1" style="width: 100%;"><option value="1">Utama</option><option value="0">Tambahan</option></select></td><td width="10px"><button type="button" name="remove" id="'+idx+'" class="btn btn-danger btn_remove">X</button></td></tr>');

     });

     $(document).on('click', '.btn_remove', function(){
          var button_id = $(this).attr("id");
          $('#row'+button_id+'').remove();
     });


    $('#submit').click(function(){
      if($('#tgl_ckp').val()== ''){
        swal({
          title: "Harap pilih BULAN sebelum menyimpan!",
          type: "error",
          showConfirmButton: true
        });
      }else if($('#niplama').val()== ''){
        swal({
          title: "Harap pilih NAMA PEGAWAI sebelum menyimpan!",
          type: "error",
          showConfirmButton: true
        });
      }else{
        $.ajax({
             url:"<?=base_url();?>/ckp/simpan_target_pegawai",
             method:"POST",
             data:$('#form_keg').serialize(),
             dataType: "JSON",
             success:function(data)
             {
               if(data.status)
               {
                 swal({
                   title: "Berhasil menambahkan data CKP-T!",
                   type: "success",
                   timer: 1000,
                   showConfirmButton: false
                  });
                   window.location.href = "<?=base_url();?>persetujuan.html";
               }
               else
               {
                 for (var i = 0; i < data.inputerror.length; i++)
                 {
                     $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                     $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                 }
               }
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
               swal({
                 title: "Terjadi kesalahan saat menambah data CKP-T!",
                 type: "error",
                 showConfirmButton: true
               });
             }
        });
      }
     });

     function tambah_kegiatanbaru()
     {
       $.ajax({
           url : "<?php echo site_url('master/get_idmax/')?>",
           type: "GET",
           dataType: "JSON",
           success: function(data)
           {
               $('[name="id_keg_baru"]').val(data.id_keg);
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
         save_method = 'add_baru';
         $('#form')[0].reset();
         $('.form-group').removeClass('has-error');
         $('.help-block').empty();
         $('#modal_form').modal('show');
         $('.modal-title').text('Tambah Kegiatan Baru');
     }
     function simpan_kegiatan_baru()
     {
         $('#btnSave').text('menyimpan...');
         $('#btnSave').attr('disabled',true);
         var url;

         if(save_method == 'add_baru') {
             url = "<?php echo site_url('master/tambah_kegiatan_baru')?>";
         }else{
             url = "<?php echo site_url('master/tambah_kegiatan_baru')?>";
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
                     idx++;
                     $('#dynamic_field').append('<tr id="row'+idx+'"><td>'+idx+'</td><td style="font-weight:bold">'+$('#nama').val()+'</td><td width="150px"><input type="hidden" name="id_kegiatan[]" placeholder="Id Kegiatan" class="form-control" value="'+$('#id_keg_baru').val()+'"/><input type="text" name="target[]" placeholder="Target Kegiatan" class="form-control" /></td><td width="150px"><select name="jenis[]" class="form-control" tabindex="1" style="width: 100%;"><option value="1">Utama</option><option value="0">Tambahan</option></select></td><td width="10px"><button type="button" name="remove" id="'+idx+'" class="btn btn-danger btn_remove">X</button></td></tr>');

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
                 title: "Terjadi kesalahan saat menambahkan kegiatan baru!",
                 type: "error",
                 showConfirmButton: true
               });
                 $('#btnSave').text('Simpan');
                 $('#btnSave').attr('disabled',false);

             }
         });
     }
</script>

<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Kegiatan Baru</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                  <input name="id_keg_baru" id="id_keg_baru" placeholder="id" class="form-control" type="hidden">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Kegiatan</label>
                            <div class="col-md-9">
                                <input name="nama" id="nama" placeholder="nama kegiatan" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Satuan</label>
                            <div class="col-md-9">
                                <input name="satuan" placeholder="satuan" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Bukti Fisik</label>
                            <div class="col-md-9">
                                <input name="bukti_fisik" placeholder="bukti fisik" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                      </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="simpan_kegiatan_baru()" class="btn btn-success">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
