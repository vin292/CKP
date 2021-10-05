  <div class="row" style="margin:20px">
        <table cellspacing="0" width="100%" style="margin-bottom:20px;">
          <tr width="100%">
            <td colspan="10" style="text-align:center; font-size:16px; font-weight:bold">PERSETUJUAN CAPAIAN KINERJA PEGAWAI</td>
          </tr>
        </table>
        <div class="row">
          <div class="col-sm-6 col-xs-12">
            <div class="form-group" style="margin-bottom:0px;">
              <button class="btn btn-success btn-sm noborder" onclick="tambah_target_pegawai()"><i class="glyphicon glyphicon-plus"></i> Tambah Target Pegawai</button>
            </div>
          </div>
          <div class="col-sm-6 col-xs-12" align="right">

          <!-- another bulshittt -->
            <div class="form-inline" style="padding-bottom:10px">
              <div class="form-group" style="margin-bottom:0px;">
                <select class="form-control" id="selectPegawai" style="width:150px; align: right">
                  <option value=''>Semua Pegawai</option>
                  <?php foreach ($pegawai as $row) { ?>
                       <option value='<?php echo $row->nama; ?>'><?php echo $row->gelar_depan.' '.$row->nama.' '.$row->gelar_belakang; ?></option>
                  <?php } ?>
                 </select>
               </div>

               <div class="form-group" style="margin-bottom:0px;">
               <?php
                    echo '<select name="fromYear" style="width:150px; align: right" class="form-control" id="selectYear">';
                    // $cur_year = date('Y');
                    if($y==''){
                    $y=date("Y");
                    }
                    $cur_year = $y;

                    for ($year = 2016; $year <= ($cur_year + 5); $year++) {
                        if ($year == $cur_year) {
                            echo '<option value="' . $year . '" selected="selected">' . $year . '</option>';
                        } else {
                            echo '<option value="' . $year . '">' . $year . '</option>';
                        }
                    }
                    echo '<select>';
                ?>
               </div>


            <div class="form-group" style="margin-bottom:0px;">
              <select class="form-control" id="selectMonth" style="width:150px; align: right">
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
             </div>

             <div class="form-group" style="margin-bottom:0px;">
                <button class="btn btn-success btn-sm noborder" onclick="list_target()"><i class="glyphicon glyphicon-search"></i></button>
             </div>

           </div> 
           <!-- this bulshittttttttttttttttttttttttttttttttttttttttt -->
            </div>
    </div>
    <div class="table-responsive">
    		<table id="target" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
          <tr>
            <th>Nama</th>
            <th>Kegiatan</th>
            <th width="100px">Target</th>
            <th width="100px">Realisasi</th>
            <th width="170px">Status Target</th>
            <th width="170px">Status Realisasi</th>
          </tr>
          </thead>
          <tfoot>
          <tr>
    				<th>Nama</th>
            <th>Kegiatan</th>
            <th>Target</th>
            <th>Realisasi</th>
            <th>Status Target</th>
            <th>Status Realisasi</th>
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
var m=$('#selectMonth').val();
var y = $('#selectYear option:selected').text();

$(document).ready(function() {
    table = $('#target').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            // "url": "<?php echo site_url('approve/list_approve/')?>/" + m,
            "url": "<?php echo site_url('approve/list_approve/')?>/" +m+"-"+y,
            "type": "POST"
        },
        lengthChange: false,
        searching: true,
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
            "targets": [ -1, -2 ],
            "orderable": false,
        },
        ],
    });
    });
    
    // $("#selectMonth").change(function(event) {
    // m = $('#selectMonth').val();
    //   table.ajax.url( "<?php echo site_url('approve/list_approve/')?>/" +m ).load();
    // });

    function list_target(){
        m = $('#selectMonth option:selected').val();
        y = $('#selectYear option:selected').text();

        // alert(m+"-"+y);
        // table.ajax.url("<?php echo site_url('ckp/list_realization/') ?>/" +m+"-"+y).load();
        // table.ajax.url("<?php echo site_url('ckp/list_target/') ?>/" +m).load();

        // window.location.href = "<?=base_url();?>detil-realisasi-capaian-kinerja-pegawai/"+m+"-"+y;
        // event.preventDefault();
        table.ajax.url( "<?php echo site_url('approve/list_approve/')?>/" +m+"-"+y ).load();
    }

    function setuju_target(id)
    {
        save_method = 'setuju';
        $('#form_target')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            url : "<?php echo site_url('approve/get_approve/')?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                $('[name="id_target"]').val(data.id);
                $('[name="niplama_target"]').val(data.niplama).trigger("change");
                $('[name="target_target"]').val(data.target);
                $('[name="nm_keg_target"]').val(data.nm_keg);
                $('[name="jenis_target"]').val(data.jenis);
                $('#modal_form_target').modal('show');
                $('.modal-title').text('Persetujuan Kegiatan');

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

    function setuju(id)
    {
        save_method = 'setuju';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            url : "<?php echo site_url('approve/get_approve/')?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                $('[name="id"]').val(data.id);
                $('[name="niplama"]').val(data.niplama).trigger("change");
                $('[name="target"]').val(data.target);
				$('[name="realisasi"]').val(data.realisasi);
				$('[name="kualitas"]').val(data.kualitas);
                $('[name="nm_keg"]').val(data.nm_keg);
                $('[name="jenis"]').val(data.jenis);
                $('#modal_form').modal('show');
                $('.modal-title').text('Persetujuan Kegiatan');

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

    function simpan_target()
    {
        $('#btnSave').text('menyimpan...');
        $('#btnSave').attr('disabled',true);
        var url;

        if(save_method == 'setuju') {
            url = "<?php echo site_url('approve/setuju_target')?>";
        }else{
            url = "<?php echo site_url('approve/tolak_target')?>";
        }

        $.ajax({
            url : url,
            type: "POST",
            data: $('#form_target').serialize(),
            dataType: "JSON",
            success: function(data)
            {

                if(data.status)
                {
                    $('#modal_form_target').modal('hide');
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
                title: "Terjadi kesalahan saat menyimpan persetujuan target!",
                type: "error",
                showConfirmButton: true
              });
                $('#btnSave').text('Simpan');
                $('#btnSave').attr('disabled',false);

            }
        });
    }

    function simpan()
    {
        $('#btnSave').text('menyimpan...');
        $('#btnSave').attr('disabled',true);
        var url;

        if(save_method == 'setuju') {
            url = "<?php echo site_url('approve/setuju')?>";
        }else{
            url = "<?php echo site_url('approve/tolak')?>";
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
                title: "Terjadi kesalahan saat menyimpan persetujuan realisasi!",
                type: "error",
                showConfirmButton: true
              });
                $('#btnSave').text('Simpan');
                $('#btnSave').attr('disabled',false);

            }
        });
    }

    function tolak_target(id)
    {
        swal({
            title: "Apakah anda akan menolak target pegawai?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Tolak",
            cancelButtonText: "Batal",
            closeOnConfirm: false
          },
          function(){
            $.ajax({
                url : "<?php echo site_url('approve/tolak_target')?>/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                  swal({
                    title: "Target berhasil ditolak!",
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
                    title: "Terjadi kesalahan saat menolak target pegawai!",
                    type: "error",
                    showConfirmButton: true
                  });
                }
            });
          });
    }

    function tolak(id)
    {

        swal({
            title: "Apakah anda akan menolak realisasi pegawai?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Tolak",
            cancelButtonText: "Batal",
            closeOnConfirm: false
          },
          function(){
            $.ajax({
                url : "<?php echo site_url('approve/tolak')?>/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                  swal({
                    title: "Realisasi berhasil ditolak!",
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
                    title: "Terjadi kesalahan saat menolak realisasi pegawai!",
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
									<div class="form-group">
											<label class="control-label col-md-3">Nama Pegawai</label>
											<div class="col-md-9">
                        <select name="niplama" data-placeholder="pilih nama pegawai"  class="form-control list_pegawai" tabindex="1" style="width: 100%;">
                            <option value=""></option>
                            <?php foreach ($pegawai as $row) { ?>
                              <option value="<?php echo $row->niplama; ?>"><?php echo $row->nama; ?></option>
                            <?php } ?>
                        </select>
													<span class="help-block"></span>
											</div>
									</div>
									<div class="form-group">
											<label class="control-label col-md-3">Uraian Kegiatan</label>
                                            <div class="col-md-9">
                                                <input name="nm_keg" placeholder="Uraian Kegiatan" class="form-control" type="text">
                                                <span class="help-block"></span>
                                            </div>
									    </div>
                  <div class="form-group">
                      <label class="control-label col-md-3">Target</label>
                      <div class="col-md-9">
                          <input name="target" placeholder="target" class="form-control" type="text">
                          <span class="help-block"></span>
                      </div>
                  </div>
									<div class="form-group">
											<label class="control-label col-md-3">Realisasi</label>
											<div class="col-md-9">
													<input name="realisasi" placeholder="realisasi" class="form-control" type="text">
													<span class="help-block"></span>
											</div>
									</div>
									<div class="form-group">
											<label class="control-label col-md-3">Kualitas</label>
											<div class="col-md-9">
													<input name="kualitas" placeholder="kualitas" class="form-control" type="text">
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
					<button type="button" id="btnSave" onclick="simpan()" class="btn btn-primary">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>
	</div>
</div>
</div>

<div class="modal fade" id="modal_form_target" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Pegawai</h3>
            </div>
            <div class="modal-body form">
							<div class="form-body">
								<form action="#" id="form_target" class="form-horizontal">
                  <input name="id_target" placeholder="id_target" class="form-control" type="hidden">
									<div class="form-group">
											<label class="control-label col-md-3">Nama Pegawai</label>
											<div class="col-md-9">
                                            <select name="niplama_target" data-placeholder="pilih nama pegawai"  class="form-control list_pegawai" tabindex="1" style="width: 100%;">
                                                <option value=""></option>
                                                <?php foreach ($pegawai as $row) { ?>
                                                <option value="<?php echo $row->niplama; ?>"><?php echo $row->nama; ?></option>
                                                <?php } ?>
                                            </select>
													<span class="help-block"></span>
											</div>
									</div>
									<div class="form-group">
											<label class="control-label col-md-3">Uraian Kegiatan</label>
                                            <div class="col-md-9">
                                                <input name="nm_keg_target" placeholder="Uraian Kegiatan" class="form-control" type="text">
                                                <span class="help-block"></span>
                                            </div>
									    </div>
                  <div class="form-group">
                      <label class="control-label col-md-3">Target</label>
                      <div class="col-md-9">
                          <input name="target_target" placeholder="target" class="form-control" type="text">
                          <span class="help-block"></span>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-md-3">Jenis</label>
                      <div class="col-md-9">
                        <select name="jenis_target" class="form-control" tabindex="1" style="width: 100%;">
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
					<button type="button" id="btnSave" onclick="simpan_target()" class="btn btn-primary">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>
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
            return "Maaf, tidak ditemukan data pegawai!";
        }
    },minimumResultsForSearch: -1
  });
});
</script>
<script>
$(document).ready(function(){
    $(".nav-tabs a").click(function(){
        $(this).tab('show');
    });
});
</script>
<script>
$("#selectPegawai").change(function(event) {
p = $('#selectPegawai').val();
  $('#target').DataTable().search(p).draw();
});
function tambah_target_pegawai()
{
  window.location.href = "<?=base_url();?>input-target-pegawai.html";
}
</script>
</form>
