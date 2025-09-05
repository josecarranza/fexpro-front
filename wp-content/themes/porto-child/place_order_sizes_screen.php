<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
global $wpdb;

$orders = wc_get_orders( array(
    'limit'    => -1,
    'status'   => array('wc-pending'),
	'return' => 'ids',
) );

foreach($orders as $order_id)
{
	
	$order = wc_get_order( $order_id );
	//$order_items  = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
	foreach ( $order->get_items() as $item_id => $item ) {
	$a = array();
	   $product_id = $item->get_product_id();
	   $variation_id = $item->get_variation_id();
	    if(!empty($product_id) && !empty($variation_id))
	    {
			$getCustomerID = get_post_meta($order_id, '_customer_user', true);
			$final_result1[$variation_id][] = $item_id;			
			$final_result2[$variation_id][] = $order_id;			
		}
	}
}
/* echo "<pre>";
print_r($final_result2);
echo "</pre>"; */
	
foreach($final_result1 as $key3 => $value3)
{
	//echo "<p>" . $key3. "</p>";
	//print_r($value3);
	$sum = 0;
	foreach($value3 as $key4 => $abc)
	{
		$c1 = 0;
			$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
			$ap = wc_get_order_item_meta( $abc, '_qty', true );
			foreach ($variation_size as $key => $size) 
			{
				$c1 += $size['value'];
				$merge1[$key3][$size['label']][] = $ap * $size['value'];
				if($size['label'] == 'XL')
				{
					$merge3[$size['label']] = $size['label'];
					$merge3['XXL'] = 'XXL';
				}
				else
				{
					$merge3[$size['label']] = $size['label'];
				}
			}
			
			$sum += $c1 * $ap;
			$merge2[$key3][] = $c1;			
			
		//echo "<p>" . $key4 . " " . $sum . "</p>";
	}
	$merge[$key3][] = $sum;	
} 
/* ksort($merge3);
echo "<pre>";
print_r($merge3);
echo "</pre>"; */
/* 
uksort($merge3,function($a,$b){
    if(is_int($a)&&is_int($b)) return $a-$b;
    if(is_int($a)&&!is_int($b)) return 1;
    if(!is_int($a)&&is_int($b)) return -1;
    return strnatcasecmp($a,$b);
}); */

$getAllSupplier = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}factory_list", ARRAY_A );
$return_array2 = "<select class='factory_name'>";
$return_array2 .= "<option value=''>Select Factory</option>";
foreach($getAllSupplier as $value)
{
	$return_array2 .= "<option value='" . $value['sage_code'] . "' data-order-number='" . $value['sage_order_number'] . "'>" . $value['supplier_name'] . "</option>";
}
$return_array2 .= "</select'>";



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Fexpro - Purchase content</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="jquery_script.js"></script>
  
  <style>
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
		background: red;
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

<h2>Order Screen Open Units</h2>

<!--<div class="order_screen_container">
<input class="form-control" id="myInput" type="text" placeholder="Search..">
<a href="Javascript:void(0);" class="submit-it"><i class="fas fa-save"></i></a>
</div>-->

<table class="table table-bordered" id="demo">
   <thead>
        <tr>
          <th></th>
          <th style="vertical-align : middle;text-align:center;">Product image</th>
          <th style="vertical-align : middle;text-align:center;">Item name</th>
          <th style="vertical-align : middle;text-align:center;">Style sku</th>
		  <th  style="vertical-align : middle;text-align:center;">Brand</th>
          <th  style="vertical-align : middle;text-align:center;">Gender</th>
          <th  style="vertical-align : middle;text-align:center;">Category</th>
          <th  style="vertical-align : middle;text-align:center;">Sub-category</th>
          <th  style="vertical-align : middle;text-align:center;">Season</th>
          <th style="vertical-align : middle;text-align:center;">Composition</th>
          <th style="vertical-align : middle;text-align:center;">Producto logo</th>
          <th style="vertical-align : middle;text-align:center;">Unit Sold</th>
          <?php 
		  //print_r($merge1);
		  foreach ($merge3 as $akkk => $akkkv) 
		  {
				echo '<th style="vertical-align : middle;text-align:center;">'. $akkk .'</th>';
		  }
		  ?>
          <th style="vertical-align : middle;text-align:center;">Factory Name</th>
          <th style="vertical-align : middle;text-align:center;">Order Number</th>
          <th style="vertical-align : middle;text-align:center;">Delivery Date</th>
          <th style="vertical-align : middle;text-align:center;">Cost price</th>
        </tr>
      </thead>
	<tbody id="myTable">
		<?php 
		foreach($merge as $key => $value)
		{
			/* $checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM {$wpdb->prefix}factory_order_confirmation_list WHERE `vid`= '$key'");
			if($checkdataExist == 1)
			{
			$getQtyRemaining = $wpdb->get_row( "SELECT `qty` FROM {$wpdb->prefix}factory_order_confirmation_list WHERE vid = $key" );
			$qty = $getQtyRemaining->qty;
			}
			else
			{
				$qty = '';
			} */
			$_product =  wc_get_product( $key);
			$main_product = wc_get_product( $_product->get_parent_id() );
			
			$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
			$css_slugGender = array();
			$css_slugCategory = array();
			$css_slugSubCategory = array();
			foreach($cat as $cvalue)
			{
				if($cvalue->parent != 0)
				{
					$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
					$css_slugSubCategory[] = $cvalue->name;
					$css_slugCategory[] = $term->name;
				}
				else
				{
					$css_slugGender[] = $cvalue->name;
				}
			}
			
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
				foreach ($merge1[$key] as $akkk => $akkkv) {
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
				echo "<td style='vertical-align : middle;text-align:center;'><a href='Javascript:void(0);' class='single-submit-it'><i class='fas fa-save'></i></a></td>";
				echo "<td style='vertical-align : middle;text-align:center;'><img src='" . $thumbnail_src[0] . "'/></td>";
				echo "<td>" . $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 . "</td>";
				echo "<td>" . $_product->get_sku() . "</td>";
				echo "<td>" . $main_product->get_attribute( 'pa_brand' ) . "</td>";
				echo "<td>" . implode(", ", $css_slugGender) . "</td>";
				echo "<td>" . implode(", ", $css_slugCategory) . "</td>";
				echo "<td>" . implode(", ", $css_slugSubCategory) . "</td>";
				echo "<td>" . $main_product->get_attribute( 'pa_season' ) . "</td>";
				echo "<td>" . $fabricCompositionString . "</td>";
				echo "<td>" . $logoApplicationString . "</td>";
				echo "<td>" . $value[0] . "</td>";
				foreach ($merge3 as $akkk => $akkkv) 
				{
					echo "<td><input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $key . "' data-minimum_units ='" . $merge2[$key][0] . "' placeholder='Min 24 Units'/></td>";
				}
				//echo "<td>" . $qty . "</td>";
				echo "<td>" . $return_array2 . "</td>";
				echo "<td><input type='text' name='factory_order_number' class='factory_order_number' placeholder='fex0001'/></td>";
				echo "<td><input type='date' class='delivery-date' /></td>";
				echo "<td><input type='number' class='cost-price' placeholder='$' /></td>";				
			echo "</tr>";
		}
		?>
	</tbody>
</table>
<script src="dist/tablefilter/tablefilter.js"></script>
<script src="test-filters-visibility.js"></script>
<script>
$(document).ready(function(){
  
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
	
	/* jQuery(".factory_order").on('keyup', function() {
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
	}); */
	
	jQuery(".single-submit-it").on('click', function() {
		
		var form_data = new FormData();		
		var getCurrentRowVariationID = $(this).parent().parent().find("td:eq(12) input[type='number']").data('variation_id');
		var getCurrentRowMinimumUnits = $(this).parent().parent().find("td:eq(12) input[type='number']").data('minimum_units');
		//var getCurrentRowFactoryUnits = $(this).parent().parent().find("td:eq(12) input[type='number']").val();
		/* var getCurrentRowFactoryNumber = $(this).parent().parent().find("td:eq(14) select option:selected").val();
		var getCurrentRowFactoryName = $(this).parent().parent().find("td:eq(14) select option:selected").text();
		 */
		var getCurrentRowFactoryOrder = $(this).parent().parent().find(".factory_order_number").val();
		var getCurrentRowDeliveryDate = $(this).parent().parent().find(".delivery-date").val();
		var getCurrentRowCostPrice = $(this).parent().parent().find(".cost-price").val();
		
		jQuery( 'table#demo thead > tr:nth-child(2) th' ).each(function() {
			myArray.push(jQuery(this).text());		
		});
		console.log(getCurrentRowVariationID);
		//console.log(getCurrentRowFactoryUnits);
		//console.log(getCurrentRowUnitSold);
		console.log(getCurrentRowFactoryNumber);
		console.log(getCurrentRowFactoryName);
		console.log(getCurrentRowFactoryOrder);
		console.log(getCurrentRowDeliveryDate);
		console.log(getCurrentRowCostPrice);
		//console.log(getCurrentRowRemainingQyt);
		/* if(getCurrentRowFactoryUnits == '')
		{$(this).parent().parent().find("td:eq(12) input[type='number']").addClass('red');}
		else
		{
			if(getCurrentRowFactoryUnits%getCurrentRowMinimumUnits == 0)
			{
				$(this).parent().parent().find("td:eq(12) input[type='number']").removeClass('red');
			}
			else
			{
				$(this).parent().parent().find("td:eq(12) input[type='number']").val('');
				$(this).parent().parent().find("td:eq(12) input[type='number']").addClass('red');
				alert("Please enter proper units");
			}
		} */
		
		/* if(getCurrentRowFactoryNumber == '')
		{$(this).parent().parent().find("td:eq(14) select").addClass('red');}
		else
		{$(this).parent().parent().find("td:eq(14) select").removeClass('red');}
		
		if(getCurrentRowDeliveryDate == '')
		{$(this).parent().parent().find("td:eq(16) input").addClass('red');}
		else
		{$(this).parent().parent().find("td:eq(16) input").removeClass('red');}
		
		if(getCurrentRowCostPrice == '')
		{$(this).parent().parent().find("td:eq(17) input").addClass('red');}
		else
		{$(this).parent().parent().find("td:eq(17) input").removeClass('red');} */
		
		/* if(getCurrentRowFactoryUnits == '' || getCurrentRowFactoryNumber == '' || getCurrentRowDeliveryDate == '' || getCurrentRowCostPrice == '')
		{
			alert("Data selection is incomplete");
		}
		else
		{
			//$(this).css("cursor", "pointer");
			
			form_data.append('getCurrentRowVariationID', getCurrentRowVariationID);
			form_data.append('getCurrentRowFactoryUnits', getCurrentRowFactoryUnits);
			//form_data.append('getCurrentRowUnitSold', getCurrentRowUnitSold);
			form_data.append('getCurrentRowFactoryNumber', getCurrentRowFactoryNumber);
			form_data.append('getCurrentRowFactoryName', getCurrentRowFactoryName);
			form_data.append('getCurrentRowFactoryOrder', getCurrentRowFactoryOrder);
			form_data.append('getCurrentRowDeliveryDate', getCurrentRowDeliveryDate);
			form_data.append('getCurrentRowCostPrice', getCurrentRowCostPrice);
			//form_data.append('getCurrentRowRemainingQyt', getCurrentRowRemainingQyt);
			form_data.append('action', 'adding_factory_data');
			
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
					//window.location.reload(true);
					//jQuery('#edform [name="save_profile"]').parent().removeClass('listing-loading talent-profile-save-btn');
					//jQuery(this).parent().parent().removeClass('adding-data');
				},
				error: function(errorThrown){
					console.log(errorThrown);
					console.log('No update');
				}
			});
		} */
	});
});
</script>

</body>
</html>
