<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
global $wpdb;



$userID = get_current_user_id();

$customer_orders = get_posts(array(
	'numberposts' => -1,
	'fields' => 'ids',
	'meta_key' => '_customer_user',
	'orderby' => 'date',
	'order' => 'DESC',
	'meta_value' => $userID,
	'post_type' => 'shop_order',
	'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array('wc-presale3'),
));

$Order_Array = []; //

foreach ($customer_orders as $customer_order) {
    $orderq = wc_get_order($customer_order);
	$counter1=1;
    foreach ( $orderq->get_items() as $item ) {
        $product_id = $item->get_product_id();
        $variation_id = $item->get_variation_id();
        if(!empty($product_id) && !empty($variation_id))
        {
			$_product =  wc_get_product( $variation_id);
			$image_id			= $_product->get_image_id();
			$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
			$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
			$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );
			

            $main_product = wc_get_product( $_product->get_parent_id() );
			 $get_sku = $_product->get_sku();
			 $price = $_product->get_price();
			 $odqty = $item->get_quantity() * 24; 
			 $sum = $price * $odqty * 24;
           
			 $cat = get_the_terms( $_product->get_parent_id(), 'product_cat' ); 
			
			 $css_slugCategory = array();
			 foreach($cat as $cvalue)
			 {
			 	$parentCat = get_term_by("id", $cvalue->parent, "product_cat");
			 	$childrenCat = get_term_by("id", $cvalue->term_id, "product_cat");
			 	if($parentCat->name != 'SPRING SUMMER 22' && $parentCat->name != 'Q1'){
			 		if($childrenCat->name != 'SPRING SUMMER 22' && $childrenCat->parent != 0){
			 			$css_slugCategory[] =  $childrenCat->name;
			 		}
			 	}
			 }
			array_multisort(array_map('strlen', $css_slugCategory), $css_slugCategory);
			$prod_brand =  $main_product->get_attribute( 'pa_brand' );
            $Order_Array[$prod_brand][] =  $prod_brand;
			$Order_Array1[$prod_brand][] = $thumbnail_src[0]."##". $css_slugCategory[0]."##". $get_sku."##".$price."##".$odqty."##".$sum ;
			
        }
		$counter1++;
    }
	
}

$newDataArry = array();
$ddddd = array();
$newddddddd = array();
foreach($Order_Array as $key => $value){
    $newDataArry[$key] = (object) array('countTotal' => count($value) );
}

$dataPoints = array();

foreach($newDataArry as $key11 => $value11){
	array_push($dataPoints,  array('label' => $key11, 'y' => $value11->countTotal));
}

// echo "<pre>";
// print_r($Order_Array1);
// echo "</pre>";
// die;
?>

<!DOCTYPE HTML>
<html>
<head>  
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js' id='jquery-core-js'></script>
<link rel='stylesheet' id='bootstrap-css'  href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' media='all' />
<link rel='stylesheet' id='d-css'  href='d.css' media='all' />
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' id='bootstrap-js'></script>
<script src='custom.js' id='custom-js'></script>
<style>
.modal-dialog{ width:50% !important;}
span#exportexcel {background: #000;color: #fff;cursor: pointer;font-size: 24px;text-align: center;font-weight: bold;	margin-bottom: 15px;padding: 5px 15px;display: inline-block;margin-left: 5px;border-radius: 5px;
		transition: all 0.2s ease;}
	span#exportexcel:hover {background: #b41520;}
	span#stop-refresh {	display: none;color: #f00;font-size: 18px;margin-left: 5px;margin-bottom: 15px;	width: 100%;}
</style>
<script>
window.onload = function () {
	
	var chart = new CanvasJS.Chart("chartContainer", {
		animationEnabled: true,
		title:{
			text: "Customer Purchased Prodcut Count",
			fontFamily: "arial black",
			fontColor: "#695A42"
		},
		axisX: {
			interval: 1,
			intervalType: "year"
		},
		data: [
			{
				type: "column",
				showInLegend: false,
				indexLabel: "{y}",
				indexLabelPlacement: "outside",
				indexLabelFontColor: "black",
				dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
			}
		]
	});


		chart.render();


}
</script>
</head>
<body>

<div class="container">
<span id="exportexcel" onclick="fnExcelReport1();">Export to XLSX ALL</span>
<span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>

<table class="table" border="1" id="myTable">
	<tbody>
<?php 
if(!empty($Order_Array1)){
foreach ($Order_Array1 as $orderData => $orderValue) {
	echo '<tr>';
		echo '<td><span class="brandTitle active">'.$orderData.'</span>';
			echo '<div  style="display:none">';
			echo '<table class="table" border="1">';
			echo '<thead>';
				echo '<tr>';
					echo '<td>Image</td>';
					echo '<td>SKU</td>';
					echo '<td>Category</td>';
					echo '<td>Price</td>';
					echo '<td>Qty</td>';
					echo '<td>Sold Unit</td>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			$counter=1;
			$NewDataSum = array();
			foreach($orderValue as $keys => $values){
				$newDataArr = explode("##",$values);
				echo '<tr>';	
					echo '<td><img src='.$newDataArr[0].' width="100" height="100" /></td>';
					echo '<td>'.$newDataArr[2].'</td>';
					echo '<td>'.$newDataArr[1].'</td>';
					echo '<td>'.$newDataArr[3].'</td>';
					echo '<td>'.$newDataArr[4].'</td>';
					echo '<td>'.$newDataArr[5].'</td>';
				echo '</tr>';	
			
				$NewDataSum[] = $newDataArr[5];
				
			}
			
			echo '<tr>';
				echo '<td></td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '<td>$'.array_sum($NewDataSum).'</td>';
			echo '</tr>';
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
		echo '</td>';
	echo '</tr>';
}
}



?>
	</tbody>
</table>



<div id="chartContainer" style="height: 360px; width: 100%;"></div>
</div>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<script>
function fnExcelReport1()
{
	 var SITEURL = "<?php echo site_url(); ?>/wp-content/themes/porto-child/";
	var form_data = new FormData();   
	var data = {};
	form_data.append('action', 'export_custom_graph_data');
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