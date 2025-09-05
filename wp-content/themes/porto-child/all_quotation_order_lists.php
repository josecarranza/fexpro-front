<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
$return_array3 = array();
$return_array4 = array();
global $wpdb;


$getallOrdersList = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}factory_order_confirmation_list", ARRAY_A );
//print_r($getZenlineOrdersList);

foreach($getallOrdersList as $abc)
{
	$variation = wc_get_product($abc['vid']);
	$variable = substr($variation->get_formatted_name(), 0, strpos($variation->get_formatted_name(), " ("));
	$variable = esc_sql($variable);
	$allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM `wp_woocommerce_order_items` WHERE `order_item_name` = '$variable' AND `order_item_type` = 'line_item'", ARRAY_A );
	//print_r($allData);
	//$akp = array_unique($allData);
	foreach($allData as $bk)
	{
		if ( get_post_status ( $bk['order_id'] ) == 'wc-cancelled' || get_post_status ( $bk['order_id'] ) == 'trash') {
			continue;
		}
		else
		{
			
			//echo $bk['order_id'] . "<br>";
			$return_array1[$abc['vid']][] = $bk['order_item_id'];
		}
	}
}


/* echo "<pre>";
print_r($return_array1);
echo "</pre>";  */
$j = 0;
foreach($return_array1 as $key3 => $value3)
{
	$sum = 0;
	foreach($value3 as $key4 => $abc)
	{
		$c1 = 0;
		
			$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
			$get_variation_id = wc_get_order_item_meta( $abc, '_variation_id', true );
			$ap = wc_get_order_item_meta( $abc, '_qty', true );
			if(!in_array($abc, $return_array2))
			{
				if($get_variation_id == $key3)
				{
					//echo $key3 . " - " . $abc . " - " . $ap . "<br>";
					
					foreach ($variation_size as $key => $size) 
					{
						
						$c1 += $size['value'];
						/* if(!in_array($label, $return_array3))
						{
							array_push($return_array3, $label);
						} */
						//echo $key3 . " - " . $size['label'] . " = " . $ap * $size['value'] . "<br>";
						$merge1[$key3][$size['label']][] = $ap * $size['value'];
						
					}
					
				}
				array_push($return_array2, $abc);
			}
			
			$sum += $c1 * $ap; 
			
		//echo "<p>" . $key4 . " " . $sum . "</p>";
	}
	$merge[$key3][] = $sum;
	
} 
/* echo "<pre>";
print_r($merge1);
echo "</pre>"; */
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Fexpro - Purchase content</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
  <script src="jquery_script.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
  h2 {
    text-align: center;
    margin: 15px 0;
    text-transform: uppercase;
    font-weight: bold;
	}
  tbody#myTable > tr > td:first-child img {
    width: 100px;
	}
	
	span.error {
		color: #f00;
		font-size: 10px;
		display: block;
	}
	.order_screen_container {
		float: left;
		width: 100%;
		margin-bottom: 15px;
		background: #25887a;
		padding: 15px;
	}
	a.submit-it {
		background: black;
		color: #fff;
		padding: .375rem .75rem;
		font-size: 1rem;
		height: calc(1.5em + .75rem + 2px);
		line-height: 1.5;
		width: auto;
		float: right;
	}	
	
	a.single-submit-it {
		background: black;
		color: #fff;
		padding: .375rem .75rem;
		font-size: 1rem;
		height: calc(1.5em + .75rem + 2px);
		line-height: 1.5;		
	}
	.order_screen_container input {
		float: left;
		width: auto;
	}
	.order_screen_container span
	{
		float: right;
		width: auto;
	}
	span#exportexcel {
		background: #000;
		color: #fff;
		cursor: pointer;
		font-size: 24px;
		text-align: center;
		font-weight: bold;		
		padding: 5px 15px;
		display: inline-block;
		margin-left: 5px;
		border-radius: 5px;
		transition: all 0.2s ease;
	}
	span#exportexcel:hover {
		background: #b41520;
	}
	tbody#myTable tr td .red {
		border-color: red !important;
	}
	table#demo thead tr.fltrow > td:first-child input {
		display: none !important;
	}
	.cart-sizes-attribute {
		min-width: 350px;
		width: 100%;
		margin-top: 20px;    
	}
	.cart-sizes-attribute .size-guide h5 {
    border: solid 1px #000;
	padding: 20px 9px!important;
	}
	.size-guide h5{
    color: #000;
    font-size: 13px;
    font-weight: 700;
    line-height: 20px;
   
    margin-bottom: 0;
   
	}
	.cart-sizes-attribute .size-guide {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
	}

	.inner-size {
		display: block;
		width: 100%;
		-webkit-box-align: center;
		-ms-flex-align: center;
		align-items: center;
		text-align: center;
	}
	.cart-sizes-attribute .size-guide .inner-size {
		border: solid 1px #000;
		border-right: 0;
		border-left: 0;
	}
	.inner-size span:first-child {
		font-weight: bold;
		background: #008188;
		color: #fff;
	}
	.inner-size span {
		display: block;
		width: 100%;
		border-bottom: solid 1px #000;
		border-right: 1px solid #000;
		color: #000;
		padding: 5px 10px;
	}
  </style>
</head>
<body>

<h2>All Quotation Order Lists</h2>

<!--<div class="order_screen_container">
<input class="form-control" id="myInput" type="text" placeholder="Search..">
<a href="Javascript:void(0);" class="submit-it"><i class="fas fa-save"></i></a>
<span id="exportexcel" onclick="fnExcelReport();">Export to XLSX</span>
</div>-->

<table class="table table-bordered" id="demo">
   <thead>
        <tr>
          <th class="hide-in-excel"></th>
		  <th style="vertical-align : middle;text-align:center;">Order Number</th>
          <th style="vertical-align : middle;text-align:center;">Product image</th>
          <th style="vertical-align : middle;text-align:center;">Item name</th>
          <th style="vertical-align : middle;text-align:center;">Style sku</th>
          <th style="vertical-align : middle;text-align:center;">Composition</th>
          <th style="vertical-align : middle;text-align:center;">Producto logo</th>
          <th style="vertical-align : middle;text-align:center;">Unit Sold</th>
          <th style="vertical-align : middle;text-align:center;">Factory Order</th>
          <th style="vertical-align : middle;text-align:center;">Open Units</th>
          <th style="vertical-align : middle;text-align:center;">Factory Name</th>
          <th style="vertical-align : middle;text-align:center;">Delivery Date</th>
          <th style="vertical-align : middle;text-align:center;">Cost price</th>          
          <th style="vertical-align : middle;text-align:center;">Total Amount</th>
        </tr>
      </thead>
	<tbody id="myTable">
		<?php 
		foreach($getallOrdersList as $key => $value)
		{
			$_product =  wc_get_product( $value['vid']);
			//$main_product = wc_get_product( $_product->get_parent_id() );
			($merge[$value['vid']][0] >= $value['forderunits']) ? $alk = $merge[$value['vid']][0] - $value['forderunits'] : $alk = "0";
			
			$image_id			= $_product->get_image_id();
			$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
			$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
			$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );
			
			$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
			$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));
			
			$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
			$array_logo = array();
			if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
			if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
			if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
			if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
			
			$logoApplicationString = implode(', ', $array_logo);
			
			$row3 = "<div class='cart-sizes-attribute'>";
			$row3 .= '<div class="size-guide"><h5>Sizes</h5>';
				foreach ($merge1[$value['vid']] as $akkk => $akkkv) {
					$q  = 0;
					$row3 .= "<div class='inner-size'><span>" . $akkk  . "</span>";
					foreach($akkkv as $akkk1 => $akkkv1)
					{
						$q += $akkkv1;
					}
					$row3 .= "<span class='clr_val'>" . $q . "</span>";
					$row3 .= "</div>";
				}
			$row3 .= "</div>";
			$row3 .= "</div>";
			
			echo "<tr>";
				echo "<td class='hide-in-excel' style='vertical-align : middle;text-align:center;'><a href='Javascript:void(0);' class='single-submit-it'><i class='fas fa-edit'></i></a></td>";
				echo "<td class='".$_product->get_sku()."'>" . $value['forderid'] . "</td>";
				echo "<td class='".$_product->get_sku()."'><img src='" . $thumbnail_src[0] . "'/></td>";
				echo "<td class='".$_product->get_sku()."'>" . $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' );
					echo $row3;
				echo "</td>";
				echo "<td class='".$_product->get_sku()."'>" . $_product->get_sku() . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . $fabricCompositionString . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . $logoApplicationString . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . $merge[$value['vid']][0] . "</td>";
				echo "<td class='".$_product->get_sku()."'><input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $value['vid'] . "' value='" . $value['forderunits'] ."'/><span style='display: none;'>" . $value['forderunits'] . "</span></td>";
				echo "<td class='".$_product->get_sku()."'>" . $alk ." </td>";
				echo "<td class='".$_product->get_sku()."'>" . $value['factoryname'] . "</td>";
				echo "<td class='".$_product->get_sku()."'><input type='date' class='delivery-date' value='" . $value['deliverydate'] . "'/><span style='display: none;'>" . $value['deliverydate'] . "</span></td>";
				echo "<td class='".$_product->get_sku()."'><input type='number' class='cost-price' placeholder='$' value='" . $value['costprice'] . "'/><span style='display: none;'>" . $value['costprice'] . "</span></td>";
				echo "<td class='".$_product->get_sku()."'>" . wc_price($value['forderunits'] * $value['costprice']) . " </td>";				
			echo "</tr>";
		}
		?>
	</tbody>
</table>
<script src="dist/tablefilter/tablefilter.js"></script>
<script src="test-filters-visibility.js"></script>

<script>
$(document).ready(function(){
	$("#myInput").on("keyup", function() {
	var value = $(this).val().toLowerCase();
	$("#myTable tr").filter(function() {
	  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
	});
	});
  
	/* $(".factory_name").on('change', function() {
	var getDataFOrder;
	$(this).parent().next().text("");
		if($(this).val() != ""){
			getDataFOrder = $(this).find(':selected').data('order-number');			
			console.log($(this).val());
			console.log(getDataFOrder);
			console.log($(this).parent().parent());
			$(this).parent().next().text(getDataFOrder);		
		}	
	}); */
	
	jQuery(".factory_order").on('keyup', function() {
		$(this).parent().children("span").remove();
		var getLiveValue = $(this).val();
		var getUnitSoldValue = parseInt($(this).parent().prev().text());
		if(getUnitSoldValue < getLiveValue)
		{
			//$(this).parent().append('<span class="error">Value must be less than Unit Sold Total</span>');
			$(this).parent().next().text("0");
			//$(this).parent().parent().children().last().text("0");
		}
		else
		{
			var getDifference = getUnitSoldValue - getLiveValue;
			$(this).parent().next().text(getDifference);
			//$(this).parent().parent().children().last().text(getDifference);
		}
	});
	
	jQuery(".single-submit-it").on('click', function() {
		
		var form_data = new FormData();		
		var getCurrentRowVariationID = $(this).parent().parent().find("td:eq(8) input[type='number']").data('variation_id');
		var getCurrentRowFactoryUnits = $(this).parent().parent().find("td:eq(8) input[type='number']").val();
		//var getCurrentRowUnitSold = $(this).parent().parent().find("td:eq(7)").text();
		var getCurrentRowDeliveryDate = $(this).parent().parent().find("td:eq(11) input").val();
		var getCurrentRowCostPrice = $(this).parent().parent().find("td:eq(12) input").val();
		//var getCurrentRowRemainingQyt = $(this).parent().parent().find("td:eq(13)").text();
		
		console.log(getCurrentRowVariationID);
		console.log(getCurrentRowFactoryUnits);
		//console.log(getCurrentRowUnitSold);
		//console.log(getCurrentRowFactoryNumber);
		//console.log(getCurrentRowFactoryName);
		//console.log(getCurrentRowFactoryOrder);
		console.log(getCurrentRowDeliveryDate);
		console.log(getCurrentRowCostPrice);
		//console.log(getCurrentRowRemainingQyt);
		if(getCurrentRowFactoryUnits == '')
		{$(this).parent().parent().find("td:eq(7) input[type='number']").addClass('red');}
		else
		{$(this).parent().parent().find("td:eq(7) input[type='number']").removeClass('red');}
		
		if(getCurrentRowDeliveryDate == '')
		{$(this).parent().parent().find("td:eq(11) input").addClass('red');}
		else
		{$(this).parent().parent().find("td:eq(11) input").removeClass('red');}
		
		if(getCurrentRowCostPrice == '')
		{$(this).parent().parent().find("td:eq(12) input").addClass('red');}
		else
		{$(this).parent().parent().find("td:eq(12) input").removeClass('red');}
		
		if(getCurrentRowFactoryUnits == '' || getCurrentRowDeliveryDate == '' || getCurrentRowCostPrice == '')
		{
			alert("Data selection is incomplete");
		}
		else
		{
			//$(this).css("cursor", "pointer");
			
			form_data.append('getCurrentRowVariationID', getCurrentRowVariationID);
			form_data.append('getCurrentRowFactoryUnits', getCurrentRowFactoryUnits);
			//form_data.append('getCurrentRowUnitSold', getCurrentRowUnitSold);
			form_data.append('getCurrentRowDeliveryDate', getCurrentRowDeliveryDate);
			form_data.append('getCurrentRowCostPrice', getCurrentRowCostPrice);
			//form_data.append('getCurrentRowRemainingQyt', getCurrentRowRemainingQyt);
			form_data.append('action', 'edit_factory_data');
			
			$.ajax({
				type: "POST",
				url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
				contentType: false,
				processData: false,
				data: form_data,
				beforeSend: function() {
					jQuery(this).parent().parent().addClass('adding-data');
				},
				success:function(data) {
					console.log(data);
					window.location.reload(true);
					//jQuery('#edform [name="save_profile"]').parent().removeClass('listing-loading talent-profile-save-btn');
					//jQuery(this).parent().parent().removeClass('adding-data');
				},
				error: function(errorThrown){
					console.log(errorThrown);
					console.log('No update');
				}
			});
		}
	});
});
</script>

</body>
</html>
