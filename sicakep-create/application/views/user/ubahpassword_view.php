<div class="container">
  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">Ganti Password</div>
      <div class="panel-body">
        <div class="form-body">
          <form action="#" id="form" class="form-horizontal">
          <input name="niplama" placeholder="niplama" class="form-control" type="hidden">
          <table class="table">
            <tbody>
              <tr>
                <td width="150px">Password Lama</td>
                <td width="5px">:</td>
                <td><input id= "old_pass" name="old_pass" placeholder="password lama" class="form-control" type="password" style="width:300px; border-radius:0px"></td>
              </tr>
              <tr>
                <td width="150px">Password Baru</td>
                <td width="5px">:</td>
                <td><input id= "new_pass" name="new_pass" placeholder="password baru" class="form-control" type="password" style="width:300px; border-radius:0px"></td>
              </tr>
              <tr>
                <td width="150px">Konfirmasi Password</td>
                <td width="5px">:</td>
                <td><input id= "conf_pass" name="conf_pass" placeholder="konfirmasi password" class="form-control" type="password" style="width:300px; border-radius:0px"></td>
              </tr>
              <tr>
                <td width="150px"></td>
                <td width="5px"></td>
                <td>
                  <button type="button" class="btn btn-primary" onclick="simpan()">Simpan</button>
                	<button type="reset" class="btn btn-danger">Batal</button>
                </td>
              </tr>
            </tbody>
          </table>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function simpan()
{
  if($('#old_pass').val()== ''||$('#new_pass').val()== ''||$('#conf_pass').val()== ''){
    swal({
      title: "Tidak boleh ada isian kosong!",
      type: "error",
      showConfirmButton: true
    });
}else{
  $.ajax({
      url : "<?php echo site_url('master/get_password/')?>",
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
          if($('[name="old_pass"]').val()==data){
            if($('[name="new_pass"]').val()==$('[name="conf_pass"]').val()){
              $.ajax({
                  url : "<?php echo site_url('master/ubah_password/')?>",
                  type: "POST",
                  data: $('#form').serialize(),
                  dataType: "JSON",
                  success: function(data)
                  {

                      if(data.status)
                      {
                        swal({
                            title: "Password berhasil diubah?",
                            text: "Silakan melakukan login kembali dengan password yang baru!",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#59B2E0",
                            confirmButtonText: "Login",
                            closeOnConfirm: false
                          },
                          function(){
                              window.location.href = '<?=base_url();?>logout.html';
                          });
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
                        title: "Terjadi kesalahan saat menyimpan data!",
                        type: "error",
                        showConfirmButton: true
                      });
                      $('#btnSave').text('Simpan');
                      $('#btnSave').attr('disabled',false);
                  }
              });
            }else{
              swal({
                title: "Password baru tidak sesuai dengan isian konfirmasi password!",
                type: "error",
                showConfirmButton: true
              });
            }
          }else{
            swal({
              title: "Password lama tidak sesuai!",
              type: "error",
              showConfirmButton: true
            });
          }

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
}
</script>
