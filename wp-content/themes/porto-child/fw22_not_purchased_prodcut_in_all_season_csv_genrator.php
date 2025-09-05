<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');

global $wpdb;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Fexpro - Without Purchase Product Lists</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
  <script src="jquery_script.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
  
	span#exportexcel {
		background: #000;
		color: #fff;
		cursor: pointer;
		font-size: 24px;
		text-align: center;
		font-weight: bold;
		margin-bottom: 15px;
		padding: 5px 15px;
		display: inline-block;
		margin-left: 5px;
		border-radius: 5px;
		transition: all 0.2s ease;
	}
	span#exportexcel:hover {
		background: #b41520;
	}
	span#stop-refresh {
		display: none;
		color: #f00;
		font-size: 18px;
		margin-left: 5px;
		margin-bottom: 15px;
		width: 100%;
	}
	
  </style>
</head>
<body>



<span id="exportexcel" onclick="fnExcelReport();">Export to XLSX</span>
<span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>


<script src="dist/tablefilter/tablefilter.js"></script>
<script src="test-filters-visibility-for-orders.js"></script>

<script>

function fnExcelReport()
{


   var SITEURL = "<?php echo site_url(); ?>/wp-content/themes/porto-child/";
					var form_data = new FormData();   
		
	
					form_data.append('action', 'fw22_export_unPurchased_Prodcut_Lists');
					jQuery.ajax({
						type: "POST",
						url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
						contentType: false,
						processData: false,
						data: form_data,
						beforeSend: function() {
							jQuery('#exportexcel').text('Creating XLSX File');
							jQuery('#stop-refresh').show();
						},
						success:function(msg) {
							console.log(msg);	
							jQuery('#exportexcel').text('Data Exported');
							setTimeout(function() {
								jQuery('#exportexcel').text('Export All to XLSX');
							},500);
							jQuery('#stop-refresh').hide();
							var data = JSON.parse(msg);
							window.open(SITEURL+"orders/"+data.filename, '_blank');
						},
						error: function(errorThrown){
							console.log(errorThrown);
							console.log('No update');
						}
					});
}
</script>

</body>
</html>
