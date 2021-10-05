<?php 
    $dataPoints = array();
    $dataPoints2 = array();
    foreach($target as $row){
            if(isset($row->target)){
                $y = $row->target/$row->jumlah*100;
            }else{
                $y=0;
            }
            if($y <= 60){
                array_push($dataPoints, array("label"=> $row->id_satker, "y"=> $y, "color"=>"#e74c3c"));
            }else if($y > 60 AND $y < 100){
                array_push($dataPoints, array("label"=> $row->id_satker, "y"=> $y, "color"=>"#e67e22"));
            }else{
                array_push($dataPoints, array("label"=> $row->id_satker, "y"=> $y, "color"=>"#2ecc71"));
            }
            
    }

    foreach($realisasi as $row){
        if(isset($row->target)){
            $y = $row->target/$row->jumlah*100;
        }else{
            $y=0;
        }
        
        if($y <= 60){
            array_push($dataPoints2, array("label"=> $row->id_satker, "y"=> $y, "color"=>"#e74c3c"));
        }else if($y > 60 AND $y < 100){
            array_push($dataPoints2, array("label"=> $row->id_satker, "y"=> $y, "color"=>"#e67e22"));
        }else{
            array_push($dataPoints2, array("label"=> $row->id_satker, "y"=> $y, "color"=>"#2ecc71"));
        }
}
?>

<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	exportEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "<?php echo 'Persentase Pegawai yang Telah Mengisi Target '.$bulant.' Capaian Kinerja (CKP-T)'; ?>" 
	},
    axisY:{
        //minimum: 50,
        maximum: 100,
     },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		//indexLabel: "{y}", //Shows y value on all Data Points
		indexLabelFontColor: "#5A5757",
		indexLabelPlacement: "outside",
        yValueFormatString: "#,##0.0#\"%\"",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();

var chart2 = new CanvasJS.Chart("chartContainer2", {
	animationEnabled: true,
	exportEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "<?php echo 'Persentase Pegawai yang Telah Mengisi Realisasi '.$bulanr.' Capaian Kinerja (CKP-R)'; ?>"
	},
    axisY:{
        //minimum: 50,
        maximum: 100,
     },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		//indexLabel: "{y}", //Shows y value on all Data Points
		indexLabelFontColor: "#5A5757",
		indexLabelPlacement: "outside",
        yValueFormatString: "#,##0.0#\"%\"",
		dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
	}]
});
chart2.render();
 
}
</script>
<div class="row" style="margin:20px">
    <div class="container">
        <?php if($realisasi_t >0) {?>
            <div class="alert alert-warning">
                <strong>Daftar pegawai dengan CKP-R <?=$bulanr?> Belum Entri/Belum disetujui :</strong>
                    <br/>
                    <?php foreach($realisasi_ as $row){ 
                        echo '- '.$row->nama.'.</br>';
                    } ?>
            </div>
        <?php } ?>
        <?php if($target_t >0) {?>
            <div class="alert alert-warning">
            <strong>Daftar pegawai dengan CKP-T <?=$bulant?> Belum Entri/Belum disetujui :</strong>
                    <br/>
                    <?php foreach($target_ as $row){ 
                        echo '- '.$row->nama.'.</br>';
                    } ?>
            </div>
        <?php } ?>
    </div>
    <div class="container">
        <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
    </div>
    <br/>
    <div class="container">
        <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/canvasjs.min.js')?>"></script>