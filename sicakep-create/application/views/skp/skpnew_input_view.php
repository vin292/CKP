  <div class="row" style="margin:20px">
    <div>
      <form onsubmit="return simpan_skp();" method="POST" id="form_keg" class="form-horizontal">
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
                  <input id="tahun_skp" name="tahun_skp" placeholder="" class="form-control readonly" value='' type="text" required>	
                  <span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>
              </div>
            </td>
          </tr>
          <tr>
              <td colspan="7" style="text-align:right">
                <!-- <button type="button" class="btn btn-default noborder" onclick="tambah_kegiatanbaru()">Kegiatan Baru</button> -->
                <button type="button" class="btn btn-success noborder" onclick="tambah_kegiatan()">Tambah Kegiatan</button>
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
            <th colspan="5" style="text-align:center" width="150px">TARGET</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle" width="100px">BIAYA</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle" width="10px">HAPUS</th>
          </tr>
          <tr>
            <th style="text-align:center" width="50px" colspan="2">KUANTITAS</th>
            <th style="text-align:center" width="50px">KUALITAS</th>
            <th style="text-align:center" width="50px" colspan="2">WAKTU</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <table  style="margin:0px;" class="table kosong-border">
        <tr>
          <td align="right">
            <button type="submit" class="btn btn-success noborder">Simpan</button>
            <button type="button" class="btn btn-danger noborder" onclick="window.location.href = '<?=base_url();?>sasaran-kinerja-pegawai.html'">Batal</button>
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

     function tambah_kegiatan(){
       idx++;
       $('#dynamic_field').append('<tr id="row'+idx+'"><td style="text-align:center">'+idx+'</td><td><input type="text" name="nm_kegiatan[]" placeholder="Nama Kegiatan" class="form-control" required/></td><td width="105px"><input type="number" min="0" name="kuantitas[]" placeholder="Kuantitas" class="form-control" style="text-align:center;" required/></td><td width="105px"><input type="text" name="satuan[]" placeholder="Satuan" class="form-control" style="text-align:center;" required/></td><td width="100px"><input type="number" min="0" name="kualitas[]" placeholder="Kualitas" class="form-control" style="text-align:center;" required/></td><td width="100px"><input type="text" name="waktu[]" placeholder="Waktu" class="form-control" style="text-align:center;" required/></td><td><select name="satuan_waktu[]" class="form-control" tabindex="1" style="width: 100%;"><option value="Hari">Hari</option><option value="Minggu">Minggu</option><option value="Bulan">Bulan</option></select></td><td width="100px"><input type="number" min="0" name="biaya[]" placeholder="Biaya" class="form-control" style="text-align:center;"/></td><td width="10px" style="text-align:center"><button type="button" name="remove" id="'+idx+'" class="btn btn-danger btn_remove">X</button></td></tr>');
     };

     $(document).on('click', '.btn_remove', function(){
          var button_id = $(this).attr("id");
          $('#row'+button_id+'').remove();
     });

    function simpan_skp(){
          if($('#tahun_skp').val()== ''){
            swal({
              title: "Harap pilih tahun sebelum menyimpan!",
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
     }

     </script>