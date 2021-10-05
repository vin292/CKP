  <div class="row" style="margin:20px">
      <table cellspacing="0" width="100%" style="margin-bottom:20px;">
        <tr width="100%">
          <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">DAFTAR TARGET DAN REALISASI KEGIATAN PEGAWAI</td>
        </tr>
      </table>
      <div class="row">
        <div class="col-sm-6 col-xs-12">
        </div>
        <div class="col-sm-6 col-xs-12" align="right">
          <div class="form-group" style="margin-bottom:0px;">
            <?php
                echo '<select name="fromYear" class="form-control2" id="selectYear" style="max-width:250px; align: right">';
                $cur_year = date('Y');
                for ($year = 2016; $year <= ($cur_year + 5); $year++) {
                    if ($year == $cur_year) {
                        echo '<option value="' . $year . '" selected="selected">' . $year . '</option>';
                    } else {
                        echo '<option value="' . $year . '">' . $year . '</option>';
                    }
                }
                echo '<select>';
            ?>

            <select class="form-control2" id="selectMonth" style="width:250px; align: right">
            <?php
             $array_bulan = array(1=>"Januari","Februari","Maret", "April", "Mei", "Juni","Juli","Agustus","September","Oktober", "November","Desember");
             $no=0;
             if($m==''){
               $m=date("m");
             }
             foreach ($array_bulan as $row) {
               $no++;
               if($no==$m){
             ?>
                   <option value='<?php echo $no; ?>' selected><?php echo $row; ?></option>
             <?php }else{ ?>
                   <option value='<?php echo $no; ?>'><?php echo $row; ?></option>
             <?php } } ?>
             </select>

             <button class="btn btn-success btn-sm noborder" onclick="list_target()"><i class="glyphicon glyphicon-search"></i></button>
           </div>
          </div>
  </div>
  <div class="table-responsive">
  		<table id="target" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
          <th>Kegiatan</th>
          <th width="120px">Target</th>
          <th width="120px">Realisasi</th>
          <th width="110px">Status</th>
          <th width="150px">Aksi</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
          <th>Kegiatan</th>
          <th>Target</th>
          <th>Realisasi</th>
          <th>Status</th>
          <th>Aksi</th>
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
var save_methodk;
var tablek;
// var m=$('#selectMonth').val();
var m=$('#selectMonth option:selected').text();
var y = $('#selectYear option:selected').text();

$(document).ready(function() {
    
    table = $('#target').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('ckp/list_realization/')?>/" +m+"-"+y,
            "type": "POST"
        },
        lengthChange: false,
        searching: false,
        "language": {
            "lengthMenu": "Tampilkan _MENU_ kegiatan",
            "zeroRecords": "Maaf, saat ini data target kegiatan pegawai tidak tersedia",
            "info": "Halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Maaf, data pegawai dengan kategori tersebut tidak ditemukan",
            "infoFiltered": "(Menyaring dari _MAX_ kegiatan)",
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

    });
    
    // $("#selectMonth").change(function(event) {
    // m = $('#selectMonth').val();
    //   table.ajax.url( "<?php echo site_url('ckp/list_realization/')?>/" + m ).load();
    // });

    function list_target(){
        m = $('#selectMonth option:selected').text();
        y = $('#selectYear option:selected').text();

        // alert(m+"-"+y);
        table.ajax.url("<?php echo site_url('ckp/list_realization/') ?>/" +m+"-"+y).load();
        // table.ajax.url("<?php echo site_url('ckp/list_target/') ?>/" +m).load();
    }



		function entri_realisasi(id)
		{
		    save_method = 'add';
		    $('#form')[0].reset();
		    $('.form-group').removeClass('has-error');
		    $('.help-block').empty();
        $.ajax({
            url : "<?php echo site_url('ckp/get_realization/')?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                $('[name="id"]').val(data.id);
                $('[name="target"]').val(data.target);
                $('[name="nm_keg"]').val(data.nm_keg);
                $('#modal_form').modal('show');
        		    $('.modal-title').text('Entri Realisasi');
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
    function ubah_realisasi(id)
    {
        save_method = 'update';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            url : "<?php echo site_url('ckp/get_realization/')?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                $('[name="id"]').val(data.id);
                $('[name="target"]').val(data.target);
                $('[name="id_keg"]').val(data.id_keg);
                $('[name="realisasi"]').val(data.realisasi);
                $('#modal_form').modal('show');
                $('.modal-title').text('Ubah Target');

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
            url = "<?php echo site_url('ckp/entry_realization')?>";
        }else{
            url = "<?php echo site_url('ckp/entry_realization')?>";
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


</script>

<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Pegawai</h3>
            </div>
            <div class="modal-body form">
							<div class="form-body">
								<form action="#" id="form" class="form-horizontal">
                  <input name="id" placeholder="id" class="form-control" type="hidden">
									<div class="form-group">
											<label class="control-label col-md-3">Nama Pegawai</label>
											<div class="col-md-9">
                          <input name="nama" placeholder="id" class="form-control" type="text" value="<?php echo $this->session->userdata('nama'); ?>" disabled>
													<span class="help-block"></span>
											</div>
									</div>
									<div class="form-group">
											<label class="control-label col-md-3">Uraian Kegiatan</label>
											<div class="col-md-9">
                                            <input name="nm_keg" placeholder="kegiatan" class="form-control" type="text" disabled>
										    <span class="help-block"></span>
                        </div>
									    </div>
                      <div class="form-group">
                          <label class="control-label col-md-3">Target</label>
                          <div class="col-md-9">
                              <input id="target" name="target" placeholder="target" class="form-control" type="number" min="0" required>
                              <span class="help-block"></span>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="control-label col-md-3">Realisasi</label>
                          <div class="col-md-9">
                              <input id="realisasi" name="realisasi" placeholder="realisasi" class="form-control" type="number" min="0" required>
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
      target:{
          required:true,
      },
      realisasi:{
          required:true,
      }
    },
    submitHandler: function(form) {
          simpan();
      }
  });

});
</script>

<style>
.form-control2 {
    /* display: block; */
    /* width: 100%; */
    height: 35px;
    padding: 6px 12px;
    /* font-size: 16px; */
    line-height: 1.55;
    color: #555555;
    background-color: #ffffff;
    background-image: none;
    border: 1px solid #cccccc;
    border-radius: 2px;
    -webkit-box-shadow: none;
    box-shadow: none;
}
</style>