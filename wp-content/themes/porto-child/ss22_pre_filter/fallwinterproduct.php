<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../wp-load.php');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<style type="text/css">
		   span#exportexcel1 {float: right; background: #000;color: #fff;cursor: pointer;   text-align: center;font-weight: bold;margin-bottom: 15px;padding: 9px 15px;margin-top: 2px;display: inline-block;transition: all 0.2s ease;}
	    span#exportexcel1:hover {background: #b41520;}
	    span#stop-refresh, span#stop-refresh1 {display: none;color: #f00;font-size: 18px;	margin-left: 5px;margin-bottom: 15px;width: 100%;}
	</style>

	<script src="../jquery_script.js"></script>

</head>
<body>
<span id="exportexcel1" onclick="fnExcelReport();">Export to XLSX</span>
<span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>


<script type="text/javascript">
	function fnExcelReport()
	{
		var SITEURL = "<?php echo site_url(); ?>/wp-content/themes/porto-child/";
	    var form_data = new FormData();   
	    form_data.append('action', 'createCustomExcelwithProductSKUImages');
	 
	    jQuery.ajax({
	        type: "POST",
	        url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
	        contentType: false,
	        processData: false,
	        data: form_data,
	        beforeSend: function() {
	            jQuery('#exportexcel1').text('Creating XLSX File');
	            jQuery('#stop-refresh').show();
	        },
	        success:function(msg) {
	            console.log(msg);	
	            jQuery('#exportexcel1').text('Data Exported');
	            setTimeout(function() {
	                jQuery('#exportexcel1').text('Export All to XLSX');
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
