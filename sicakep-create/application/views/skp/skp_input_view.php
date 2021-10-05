  <div class="row" style="margin:20px">
    <div>
      <form action="#" id="form_keg" class="form-horizontal">
      <table class="table kosong-border" style="margin-bottom:0px;">
          <tr>
            <td width="150px" style="font-weight:bold">
              Tahun SKP
            </td>
            <td width="10px" style="font-weight:bold">
              :
            </td>
            <td colspan="5">
            <div class="input-group date" id="tgl_skp">
                  <input placeholder="" class="form-control" type="text" readonly>	
                  <span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>
              </div>
            </td>
          </tr>
          <tr>
            <td style="font-weight:bold">
              Uraian Kegiatan
            </td>
            <td style="font-weight:bold">
              :
            </td>
            <td colspan="5">
              <select id="id_keg2" name="id_keg2" data-placeholder="pilih kegiatan"  class="list_keg" tabindex="1" style="width: 100%;">
                  <option selected disabled></option>
                  <?php foreach ($kegiatan as $row) { ?>
                    <option value="<?php echo $row->id; ?>"><?php echo $row->n_keg; ?></option>
                  <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
              <td colspan="7" style="text-align:right">
                <button type="button" class="btn btn-success noborder" onclick="tambah_kegiatanbaru()">Kegiatan Baru</button>
              </td>
          </tr>
      </table>

      <table cellspacing="0" width="100%" style="margin-bottom:20px;margin-top:20px;">
        <tr width="100%">
          <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">SASARAN KINERJA PEGAWAI</td>
        </tr>
      </table>

      <table class="table table-bordered table-responsive" id="dynamic_field" style="margin:0px;">
        <thead>
          <tr>
            <th rowspan="2" style="text-align:center; vertical-align:middle" width="10px">NO</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle">KEGIATAN TUGAS JABATAN</th>
            <th colspan="4" style="text-align:center" width="150px">TARGET</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle" width="100px">BIAYA</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle" width="10px">HAPUS</th>
          </tr>
          <tr>
            <th style="text-align:center" width="50px">KUANTITAS</th>
            <th style="text-align:center" width="50px">KUALITAS</th>
            <th style="text-align:center" width="50px" colspan="2">WAKTU</th>
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
            <button type="button" class="btn btn-danger noborder" onclick="window.location.href = '<?=base_url();?>sasaran-kinerja-pegawai.html'">Batal</button>
          </td>
        </tr>
    </table>
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

  $('#tgl_skp').datepicker({
      autoclose: true,
      locale:'id',
      format: "yyyy",
      todayHighlight: true,
      orientation: "top auto",
      viewMode: "years",
      minViewMode: "years",
      todayBtn: false,
      todayHighlight: false,
  });

});
</script>
<script type="text/javascript">

     var idx=0;

     $("#id_keg2").change(function(event) {
       idx++;
       $('#dynamic_field').append('<tr id="row'+idx+'"><td style="text-align:center">'+idx+'</td><td style="font-weight:bold">'+$('#id_keg2 option:selected').text()+'</td><td width="105px"><input type="hidden" name="id_kegiatan[]" placeholder="Id Kegiatan" class="form-control" value="'+$('#id_keg2 option:selected').val()+'"/><input type="text" name="kuantitas[]" placeholder="Kuantitas" class="form-control" style="text-align:center;" required/></td><td width="100px"><input type="text" name="kualitas[]" placeholder="Kualitas" class="form-control" style="text-align:center;"/></td><td width="100px"><input type="text" name="waktu[]" placeholder="Waktu" class="form-control" style="text-align:center;"/></td><td><select name="satuan_waktu[]" class="form-control" tabindex="1" style="width: 100%;"><option value="Hari">Hari</option><option value="Minggu">Minggu</option><option value="Bulan">Bulan</option></select></td><td width="100px"><input type="text" name="biaya[]" placeholder="Biaya" class="form-control" style="text-align:center;"/></td><td width="10px" style="text-align:center"><button type="button" name="remove" id="'+idx+'" class="btn btn-danger btn_remove">X</button></td></tr>');
     });

     $(document).on('click', '.btn_remove', function(){
          var button_id = $(this).attr("id");
          $('#row'+button_id+'').remove();
     });

    $('#submit').click(function(){
          if($('#tgl_skp').val()== ''){
            swal({
              title: "Harap pilih bulan sebelum menyimpan!",
              type: "error",
              showConfirmButton: true
            });
          }else{
            $.ajax({
                 url:"<?=base_url();?>/skp/simpan_skp",
                 method:"POST",
                 data:$('#form_keg').serialize(),
                 dataType: "JSON",
                 success:function(data)
                 {
                   if(data.status)
                   {
                     swal({
                       title: "Berhasil menambahkan data SKP!",
                       type: "success",
                       timer: 1000,
                       showConfirmButton: false
                      });
                       window.location.href = "<?=base_url();?>sasaran-kinerja-pegawai.html";
                   }
                   else
                   {
                     for (var i = 0; i < data.inputerror.length; i++)
                     {
                         $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                         $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                     }
                   }
                      //alert(data);
                      //window.location.href = "<?=base_url();?>skp.html";
                 },
                 error: function (jqXHR, textStatus, errorThrown)
                 {
                     swal({
                       title: "Terjadi Kesalahan saat menambahkan SKP!",
                       type: "error",
                       showConfirmButton: true
                     });
                 }
            });
          }
     });

     function tambah_kegiatanbaru()
     {
       var niplama= <?php echo substr($this->session->userdata('niplama'), 4) ?>;
       $.ajax({
           url : "<?php echo site_url('master/get_idmax/')?>",
           type: "GET",
           dataType: "JSON",
           success: function(data)
           {
               save_method = 'add_baru';
               $('#form')[0].reset();
               $('.form-group').removeClass('has-error');
               $('.help-block').empty();
               if(data.id_keg.toString().length>4){
                 $('[name="id_keg_baru"]').val(niplama+''+data.id_keg.substr(5));
               }else{
                 $('[name="id_keg_baru"]').val(niplama+''+data.id_keg);
               }
               $('#modal_form').modal('show');
               $('.modal-title').text('Tambah Kegiatan Baru');
           },
           error: function (jqXHR, textStatus, errorThrown)
           {
             swal({
               title: "Terjadi Kesalahan saat mengambil data!",
               type: "error",
               showConfirmButton: true
             });
           }
       });
     }
     function simpan_kegiatan_baru()
     {
         $('#btnSave').text('menyimpan...');
         $('#btnSave').attr('disabled',true);
         var url;
         var id=document.getElementById("id_keg_baru").value;
         if(save_method == 'add_baru') {
             url = "<?php echo site_url('master/tambah_kegiatan_baru')?>/" + id;
         }else{
             url = "<?php echo site_url('master/tambah_kegiatan_baru')?>/" + id;
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
                     $('#dynamic_field').append('<tr id="row'+idx+'"><td style="text-align:center">'+idx+'</td><td style="font-weight:bold">'+$('#nama').val()+'</td><td width="105px"><input type="hidden" name="id_kegiatan[]" placeholder="Id Kegiatan" class="form-control" value="'+$('#id_keg_baru').val()+'"/><input type="text" name="kuantitas[]" placeholder="Kuantitas" class="form-control" style="text-align:center;" required/></td><td width="100px"><input type="text" name="kualitas[]" placeholder="Kualitas" class="form-control" style="text-align:center;"/></td><td width="100px"><input type="text" name="waktu[]" placeholder="Waktu" class="form-control" style="text-align:center;"/></td><td><select name="satuan_waktu[]" class="form-control" tabindex="1" style="width: 100%;"><option value="Hari">Hari</option><option value="Minggu">Minggu</option><option value="Bulan">Bulan</option></select></td><td width="100px"><input type="text" name="biaya[]" placeholder="Biaya" class="form-control" style="text-align:center;"/></td><td width="10px" style="text-align:center"><button type="button" name="remove" id="'+idx+'" class="btn btn-danger btn_remove">X</button></td></tr>');
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
                 title: "Terjadi Kesalahan saat menambahkan kegiatan baru!",
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
