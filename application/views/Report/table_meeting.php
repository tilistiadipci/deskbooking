<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preview</title>
    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url("assets/theme/plugins/bootstrap/css/bootstrap.css") ?>" rel="stylesheet">
</head>

<body>
	<button onclick="ExportToExcel('xlsx')">Export To Excel</button>
    <table id="tbl_exporttable_to_xls" border="1">
       
        <tr>
            <td></td>
            <?php foreach ($header as $key => $value): ?>
                <td><?= $value?></td>
            <?php endforeach ?>
        </tr>
        <?php foreach ($body as $key => $bval): ?>
        	<tr>
                <td></td>
                <?php foreach ($bval as $key => $bvaldata): ?>
                    <td><?= $bvaldata?>
                <?php endforeach ?>
        </tr>
       	
       	<?php endforeach ?>
            
        	
    </table>
    <!-- Jquery Core Js -->
    <script src="<?php echo base_url("assets/theme/plugins/jquery/jquery.min.js");?>"> </script> 
    <script src = "<?php echo base_url("assets/theme/plugins/bootstrap/js/bootstrap.js ");?>" >
    </script>
    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url(" assets/theme/plugins/jquery-slimscroll/jquery.slimscroll.js");?>"> </script> <!-- Waves Effect Plugin Js -->
    <script src = "<?php echo base_url("
    assets / theme / plugins / node - waves / waves.js ");?>" >
    </script>

    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment-timezone.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment-timezone-data.min.js"></script>
    <!-- Jquery CountTo Plugin Js -->
    <script src="<?= base_url()?>assets/external/sheetjs/xlsx.full.min.js"></script>
    <script type="text/javascript">
    	function ExportToExcel(type, fn, dl) {
    		var namefile = moment().format("X")+"_Report_Meeting_apps.xlsx";
	       	var elt = document.getElementById('tbl_exporttable_to_xls');
	       	var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
	       	return dl ?
	        XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
	        XLSX.writeFile(wb, namefile);

	    }
        ExportToExcel('xlsx');
        $(document).ready(function(){
           // do jQuery
            // ExportToExcel('xlsx');
            // window.close();
        })
    </script>
</body>

</html>