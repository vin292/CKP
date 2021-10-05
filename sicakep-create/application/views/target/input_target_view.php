  <div class="row" style="margin:20px">
    <div >
    <form action="#" method="POST" id="form_keg" class="form-horizontal">
      <table class="table kosong-border"  style="margin-bottom:0px;">
        <tr>
          <td style="font-weight:bold">
            Bulan CKP
          </td>
          <td style="font-weight:bold">
            :
          </td>
          <td colspan="5">
              <div class="input-group date" id="tgl_ckp">
                  <input id="bulan_ckp" name="bulan_ckp" placeholder="" class="form-control readonly" type="text" required>	
                  <span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>
              </div>
          </td>
        </tr>
        <tr>
            <td width="150px" style="font-weight:bold">
              Kegiatan dari SKP
            </td>
            <td width="10px" style="font-weight:bold">
              :
            </td>
            <td colspan="5">
              <select id="id_keg" name="id_keg" data-placeholder="pilih kegiatan dari skp"  class="list_keg" tabindex="1" style="width: 100%;">
                <?php if($keg_skp->num_rows() > 0){
                  $keg = $keg_skp->result();
                    echo '<option selected disabled></option>';
                  foreach ($keg as $result) {
                    echo '<option value="'.$result->id.'">'.$result->n_keg.'</option>';
                  }
                }else{
                    echo '<option selected disabled></option>';
                } ?>
              </select>
            </td>
          </tr>
          <tr>
              <td colspan="7" style="text-align:right">
                <button type="button" class="btn btn-success noborder" onclick="tambah_kegiatan()">Tambah Kegiatan</button>
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
            <th style="text-align:center" width="150px" colspan="2">TARGET</th>
            <th style="text-align:center" width="150px">KETERANGAN</th>
            <th style="text-align:center; vertical-align:middle" width="10px">HAPUS</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <table  style="margin:0px;" class="table kosong-border">
        <tr>
          <td align="right">
            <button type="submit" class="btn btn-success noborder">Simpan</button>
            <button type="button" class="btn btn-danger noborder" onclick="window.location.href = '<?=base_url();?>entri-ckp-target.html'">Batal</button>
          </td>
        </tr>
    </table>
    </form>
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
      format:'MM-yyyy',
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

     function tambah_kegiatan() {
       idx++;
       $('#dynamic_field').append('<tr id="row'+idx+'"><td>'+idx+'</td><td><input type="text" name="nm_kegiatan[]" placeholder="Nama Kegiatan" class="form-control" required/></td><td width="150px"><input type="number" name="target[]" min="0" placeholder="Target Kegiatan" class="form-control" required/></td><td width="150px"><input type="text" name="satuan[]" placeholder="Satuan Kegiatan" class="form-control" required/></td><td width="150px"><select name="jenis[]" class="form-control" tabindex="1" style="width: 100%;"><option value="1">Utama</option><option value="0">Tambahan</option></select></td><td width="10px"><button type="button" name="remove" id="'+idx+'" class="btn btn-danger btn_remove">X</button></td></tr>');

     };

     $(document).on('click', '.btn_remove', function(){
          var button_id = $(this).attr("id");
          $('#row'+button_id+'').remove();
     });


    function simpan_target(){
        $.ajax({
             url:"<?=base_url();?>ckp/simpan_target",
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
                   window.location.href = "<?=base_url();?>entri-ckp-target.html";
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

     function tambah_kegiatanbaru()
     {
       var niplama= <?php echo substr($this->session->userdata('niplama'), 4) ?>;
       $.ajax({
           url : "<?php echo site_url('master/get_idmax/')?>",
           type: "GET",
           dataType: "JSON",
           success: function(data)
           {
             if(data.id_keg.toString().length>4){
               $('[name="id_keg_baru"]').val(niplama+''+data.id_keg.substr(5));
             }else{
               $('[name="id_keg_baru"]').val(niplama+''+data.id_keg);
             }
               $('#modal_form').modal('show');
               $('.modal-title').text('Tambah Kegiatan');

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
            title: "Terjadi kesalahan saat menambah kegiatan baru!",
            type: "error",
            showConfirmButton: true
          });
            $('#btnSave').text('Simpan');
            $('#btnSave').attr('disabled',false);

        }
    });
}

$(function() {
  $("#form_keg").validate({
    rules: {
      bulan_ckp: {
        required: true,
      }
    },
    submitHandler: function(form) {
          simpan_target();
      }
  });
});
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
