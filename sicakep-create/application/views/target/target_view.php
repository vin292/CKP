
  <div class="row" style="margin:20px">
    <table cellspacing="0" width="100%" style="margin-bottom:20px;">
      <tr width="100%">
        <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">DAFTAR TARGET KEGIATAN PEGAWAI</td>
      </tr>
    </table>
    <div class="row">
      <div class="col-xs-6">
        <div class="form-group" style="margin-bottom:0px;">
          <button class="btn btn-success btn-sm noborder" onclick="tambah_target()"><i class="glyphicon glyphicon-plus"></i> Tambah Target</button>
        </div>
      </div>
      <div class="col-xs-6" align="right">
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

          <select class="form-control2" id="selectMonth" style="max-width:250px; align: right">
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
        <th style="text-align:center">Kegiatan</th>
        <th width="120px" style="text-align:center">Target</th>
        <th width="110px" style="text-align:center">Status</th>
        <th width="160px" style="text-align:center">Aksi</th>
      </tr>
      </thead>
      <tfoot>
      <tr>
        <th>Kegiatan</th>
        <th>Target</th>
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
var save_method; //for save method string
var table;
var m=$('#selectMonth option:selected').text();
var y = $('#selectYear option:selected').text();
$(document).ready(function() {
    table = $('#target').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('ckp/list_target/')?>/" +m+"-"+y,
            "type": "POST"
        },
        lengthChange: false,
        searching: false,
        "language": {
            "lengthMenu": "Tampilkan _MENU_ kegiatan",
            "zeroRecords": "Maaf, saat ini data ckp pegawai tidak tersedia",
            "info": "Halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "",
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
    // m = $('#selectMonth option:selected').text();
    //   table.ajax.url("<?php echo site_url('ckp/list_target/')?>/" +m).load();
    // });

    function list_target(){
        m = $('#selectMonth option:selected').text();
        y = $('#selectYear option:selected').text();

        // alert(m+" "+y);
        table.ajax.url("<?php echo site_url('ckp/list_target/') ?>/" +m+"-"+y).load();
        // table.ajax.url("<?php echo site_url('ckp/list_target/') ?>/" +m).load();
    }

		function tambah_target()
		{
      window.location.href = "<?=base_url();?>input-target.html";
		}
    function ubah_target(id)
    {
        save_method = 'update';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#t_ckp').hide();

        $.ajax({
            url : "<?php echo site_url('ckp/get_target/')?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                $('[name="id"]').val(data.id);
                $('[name="target"]').val(data.target);
                $('[name="satuan"]').val(data.satuan);
                $('[name="nm_keg"]').val(data.nm_keg).trigger("change");;
                $('[name="jenis"]').val(data.jenis);
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Ubah Target'); // Set title to Bootstrap modal title

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
        $('#btnSave').text('menyimpan...'); //change button text
        $('#btnSave').attr('disabled',true); //set button disable
        var url;

        if(save_method == 'add') {
            url = "<?php echo site_url('ckp/tambah_target')?>";
        }else{
            url = "<?php echo site_url('ckp/ubah_target')?>";
        }

        // ajax adding data to database
        $.ajax({
            url : url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data)
            {

                if(data.status) //if success close modal and reload ajax table
                {
                    $('#modal_form').modal('hide');
                    $(".list_keg").select2('val', 'Uraian Kegiatan');
                    document.getElementById("target2").value = "";
                    document.getElementById("tgl_ckp").value = "";
                    reload_table();
                }
                else
                {
                    for (var i = 0; i < data.inputerror.length; i++)
                    {
                        $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    }
                }
                $('#btnSave').text('Simpan'); //change button text
                $('#btnSave').attr('disabled',false); //set button enable


            },
            error: function (jqXHR, textStatus, errorThrown)
            {
              swal({
                title: "Terjadi kesalahan saat menambah data CKP-T!",
                type: "error",
                showConfirmButton: true
              });
                $('#btnSave').text('Simpan'); //change button text
                $('#btnSave').attr('disabled',false); //set button enable

            }
        });
    }

    function hapus_target(id)
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
              url : "<?php echo site_url('ckp/hapus_target')?>/"+id,
              type: "POST",
              dataType: "JSON",
              success: function(data)
              {
                swal({
                  title: "Data berhasil dihapus!",
                  type: "success",
                  timer: 500,
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
									<!--<div class="form-group">
											<label class="control-label col-md-3">Nama Pegawai</label>
											<div class="col-md-9">
                        <select name="niplama" data-placeholder="pilih nama pegawai"  class="form-control list_pegawai" tabindex="1" style="width: 100%;">
                            <option value=""></option>
                            <?php foreach ($pegawai as $row) { ?>
                              <option value="<?php echo $row->niplama; ?>"><?php echo $row->n_panj; ?></option>
                            <?php } ?>
                        </select>
													<span class="help-block"></span>
											</div>
									</div>-->
                <div class="form-group">
                      <label class="control-label col-md-3">Uraian Kegiatan</label>
                      <div class="col-md-9">
                          <input id="nm_keg" name="nm_keg" placeholder="target" class="form-control" type="text">
                          <span class="help-block"></span>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-md-3">Target</label>
                      <div class="col-md-9">
                          <input id="target2" name="target" placeholder="target" class="form-control" type="text">
                          <span class="help-block"></span>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-md-3">Satuan</label>
                      <div class="col-md-9">
                          <input id="satuan" name="satuan" placeholder="satuan" class="form-control" type="text">
                          <span class="help-block"></span>
                      </div>
                  </div>
                  <div class="form-group" id="t_ckp">
                      <label class="control-label col-md-3">Tanggal CKP</label>
                      <div class="col-md-9">
                          <input id="tgl_ckp" name="tgl_ckp" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
                          <span class="help-block"></span>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-md-3">Jenis</label>
                      <div class="col-md-9">
                        <select name="jenis" class="form-control" tabindex="1" style="width: 100%;">
                            <option value="1">Utama</option>
                            <option value="0">Tambahan</option>
                        </select>
                          <span class="help-block"></span>
                      </div>
                  </div>
								</div>
					</form>
			</div>
			<div class="modal-footer">
					<button type="button" id="btnSave" onclick="simpan()" class="btn btn-success">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
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
            return "Maaf, tidak ditemukan data pegawai!";
        }
    },minimumResultsForSearch: -1
  });
  $('.datepicker').datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      todayHighlight: true,
      orientation: "top auto",
      todayBtn: true,
      todayHighlight: true,
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