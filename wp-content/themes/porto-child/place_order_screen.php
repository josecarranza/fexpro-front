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

$last = 0;	
foreach($final_result1 as $key3 => $value3)
{
	//echo "<p>" . $key3. "</p>";
	//print_r($value3);
	$sum = 0;
	foreach($value3 as $key4 => $abc)
	{
		$c1 = 0;
		$c5 = 0;
		$last = 0;
			$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
			$ap = wc_get_order_item_meta( $abc, '_qty', true );
			foreach ($variation_size as $key => $size) 
			{
				$c1 += $size['value'];

				$merge1[$key3][$size['label']][] = $ap * $size['value'];
				//$merge7[$size['label']][] = $ap * $size['value'];
				
				$merge3[$size['label']] = $size['label'];
			}
			
			$sum += $c1 * $ap;
			$merge2[$key3][] = $c1;			
	
		//echo "<p>" . $key4 . " " . $sum . "</p>";
	}
	$merge[$key3][] = $sum;
	
} 



$getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM {$wpdb->prefix}factory_order_confirmation_list", ARRAY_A );
$return_array3 = "<select class='onumbers_lists'>";
$return_array3 .= "<option value=''>Select Order No.</option>";
foreach($getallOrdersNumbers as $value)
{
	$return_array3 .= "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";
}
$return_array3 .= "</select'>";

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
	table#demo thead tr.fltrow > td:first-child input, table#demo thead tr.fltrow > th:last-child input {
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
	.for-Excel-only, .order1-number2, .factory_order_number{display: none;}
	input.factory_order_number {
		margin-top: 10px;
	}
	.add-new {
		cursor: pointer;
		background: #000;
		display: inline-block;
		color: #fff;
		padding: 5px;
		border-radius: 5px;
		margin: 10px 0;
	}
	.order1-number2, .only1 {
    text-align: center;
    font-size: 17px;
    color: #f00;
    font-weight: bold;
}
.show .order1-number2{display: block;}
.adding-data:before {
    content: "";
    background: rgba(0,0,0,0.5);
    z-index: 2;
    position: absolute;
    width: 100%;
    height: 100%;
}

table caption{caption-side: top;}


.TF.sticky tr.fltrow th {
    top: -1px !important;
    background: #af0f2c;
}


.TF.sticky th {
    top: 33px !important;
}

body table.TF tr.fltrow th {
    border-bottom: 1px solid #000;
    border-top: 1px solid #000;
    border-left: 1px solid #000;
    border-right: 1px solid #000;
    padding: 0;
    color: #fff;
}
a.submit-in-one {
    position: fixed;
    bottom: 0;
    background: #af0f2c;
    color: #fff;
    padding: 5px;
    right: 0;
    font-size: 18px;
    cursor: pointer;
}
input.factory_order {
    max-width: 110px;
}
select.rspg + span {
    display: none;
}
  </style>
</head>
<body>

<h2>Order Screen Open Units</h2>

<!--<div class="order_screen_container">
<input class="form-control" id="myInput" type="text" placeholder="Search..">
<a href="Javascript:void(0);" class="submit-it"><i class="fas fa-save"></i></a>
</div>-->
<span id="exportexcel" onclick="fnExcelReport();">Export to XLSX</span>
<span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>
<table class="table table-bordered" id="demo">
   <thead>
        <tr>
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
		  <?php 
		  //print_r($merge1);
		  foreach ($merge3 as $akkk3 => $akkkv3) 
		  {
			echo '<th style="vertical-align : middle;text-align:center; display: none;">'. $akkk3 .'</th>';
		  }
		  ?>
          <th style="vertical-align : middle;text-align:center;">Unit Sold</th>
          <th style="vertical-align : middle;text-align:center;">Factory Order</th>
          <th style="vertical-align : middle;text-align:center;">Open Units</th>
          <!--<th style="vertical-align : middle;text-align:center;">Factory Name</th>-->
          <th style="vertical-align : middle;text-align:center;">Order Number</th>
          <!--<th style="vertical-align : middle;text-align:center;">Delivery Date</th>-->
          <!--<th style="vertical-align : middle;text-align:center;">Cost price</th>-->
		  <th></th>
        </tr>
      </thead>
	<tbody id="myTable">
		<?php 
		$ak = 0;
		/* foreach($merge as $key => $value)
		{
			foreach ($merge3 as $akkk3 => $akkkv3) 
			{
				foreach($merge1[$key] as $ko => $ko1)
				{
					$q1  = 0;
					
					if(	$akkk3 == $ko)
					{
						foreach($ko1 as $ko2 => $ko22)
						{
							$q1 += $ko22;
						}
						$merge67[$key][$akkk3][] = $q1;
					}
					else					
					{
						$merge67[$key][$akkk3][] = '';
					}
				}	
			}
		} */			

		foreach($merge as $key => $value)
		{
			$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM {$wpdb->prefix}factory_order_confirmation_list WHERE `vid`= '$key'");
			$getQtyRemaining = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}factory_order_confirmation_list WHERE vid = $key" );
			
			if($value[0] >= $getQtyRemaining->forderunits )
			{
				$aq = $value[0] - $getQtyRemaining->forderunits;
			}
			else
			{
				$aq = 0;
			}
			if($checkdataExist == 1)
			{				
			$qty = $getQtyRemaining->fnumber;
			}
			else
			{
				$qty = '';
			}
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
					$merge4[$akkk] = $q;
					
					$row3 .= "<span class='clr_val'>" . $q . "</span>";
					$row3 .= "</div>";
				}
			$row3 .= "</div>";
			$row3 .= "</div>";
			$last = 0;
			
			if($getQtyRemaining->vid == $key)
			{
				echo "<tr class='show'>";
					echo "<td class='".$_product->get_sku()."' style='vertical-align : middle;text-align:center;'><img src='" . $thumbnail_src[0] . "'/></td>";
					echo "<td class='".$_product->get_sku()."'>" . $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . $_product->get_sku() . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . $main_product->get_attribute( 'pa_brand' ) . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugGender) . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugCategory) . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugSubCategory) . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . $main_product->get_attribute( 'pa_season' ) . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . $fabricCompositionString . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . $logoApplicationString . "</td>";
					//print_r($merge1);
					
					/* foreach($merge67[$key] as $qw => $qr)
					{
						$fk = 0;
						foreach($qr as $vl)
						{
							if($vl == '')
							{
								continue;
							}
							else
							{
								$fk = $vl;
							}
						}
						if($fk == 0)
						{
							echo "<td class='".$_product->get_sku()."' style='display: none;'></td>";						
						}
						else
						{
							echo "<td class='".$_product->get_sku()."' style='display: none;'>" . $fk . "</td>";						
						}
					} */
					
					//print_r($merge1[$key]);
					foreach ($merge3 as $akkk3 => $akkkv3) 
					{
						if (array_key_exists("$akkkv3",$merge1[$key]))
						{
							echo "<td class='".$_product->get_sku()."' style='display: none;'>" . $merge4[$akkkv3] . "</td>";	
						}
						else
						{
							echo "<td class='".$_product->get_sku()."' style='display: none;'></td>";	
						}
					} 
					
					echo "<td class='".$_product->get_sku()."'>" . $value[0] . "</td>";
					echo "<td class='".$_product->get_sku()."'><input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $key . "' data-minimum_units ='" . $merge2[$key][0] . "' placeholder='Min 24 Units' value='".$getQtyRemaining->forderunits."'/> <span class='for-Excel-only'>".$getQtyRemaining->forderunits."</span></td>";
					echo "<td class='".$_product->get_sku()."'>" . $aq . "</td>";
					//echo "<td class='".$_product->get_sku()."'>" . $return_array2 . "</td>";
					echo "<td class='".$_product->get_sku()."'><span class='order1-number2'>".$getQtyRemaining->forderid."</span></td>";
					//echo "<td class='".$_product->get_sku()."'><input type='date' class='delivery-date' /></td>";
					//echo "<td class='".$_product->get_sku()."'><input type='number' class='cost-price' placeholder='$' /></td>";	
					echo "<td style='vertical-align : middle;text-align:center;'><a href='Javascript:void(0);' class='single-submit-it'><i class='fas fa-save'></i></a></td>";				
				echo "</tr>";
			}
			else
			{
				echo "<tr>";
				echo "<td class='".$_product->get_sku()."' style='vertical-align : middle;text-align:center;'><img src='" . $thumbnail_src[0] . "'/></td>";
				echo "<td class='".$_product->get_sku()."'>" . $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . $_product->get_sku() . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . $main_product->get_attribute( 'pa_brand' ) . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugGender) . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugCategory) . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugSubCategory) . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . $main_product->get_attribute( 'pa_season' ) . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . $fabricCompositionString . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . $logoApplicationString . "</td>";
			
				/* foreach($merge67[$key] as $qw => $qr)
				{
					$fk = 0;
					foreach($qr as $vl)
					{
						if($vl == '')
						{
							continue;
						}
						else
						{
							$fk = $vl;
						}
					}
					if($fk == 0)
					{
						echo "<td class='".$_product->get_sku()."' style='display: none;'></td>";						
					}
					else
					{
						echo "<td class='".$_product->get_sku()."' style='display: none;'>" . $fk . "</td>";						
					}
				} */
				
				foreach ($merge3 as $akkk3 => $akkkv3) 
				{
					if (array_key_exists("$akkkv3",$merge1[$key]))
					{
						echo "<td class='".$_product->get_sku()."' style='display: none;'>" . $merge4[$akkkv3] . "</td>";	
					}
					else
					{
						echo "<td class='".$_product->get_sku()."' style='display: none;'></td>";	
					}
				} 
				
				echo "<td class='".$_product->get_sku()."'>" . $value[0] . "</td>";
				echo "<td class='".$_product->get_sku()."'><input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $key . "' data-minimum_units ='" . $merge2[$key][0] . "' placeholder='Min 24 Units' value='". $value[0] ."'/> <span class='for-Excel-only'>". $value[0] ."</span></td>";
				echo "<td class='".$_product->get_sku()."'>0</td>";
				//echo "<td class='".$_product->get_sku()."'>" . $return_array2 . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . $return_array3 . "<input type='text' name='factory_order_number' class='factory_order_number' placeholder='fex0001'/><div class='add-new'>Add New</div> <span class='order1-number2'></span></td>";
				//echo "<td class='".$_product->get_sku()."'><input type='date' class='delivery-date' /></td>";
				//echo "<td class='".$_product->get_sku()."'><input type='number' class='cost-price' placeholder='$' /></td>";	
				echo "<td style='vertical-align : middle;text-align:center;'><a href='Javascript:void(0);' class='single-submit-it'><i class='fas fa-save'></i></a></td>";				
			echo "</tr>";
			}
		}
		?>
	</tbody>
</table>
<a href="javascript:void(0);" class="submit-in-one">Submit</a>
<script src="dist/tablefilter/tablefilter.js"></script>
<script src="test-filters-visibility.js"></script>
<script>
$(document).ready(function(){ 
$( "table thead tr:not(.fltrow) th" ).each(function( index ) {
  if($(this).is(":hidden"))
  {
  console.log( index + ": ");
  jQuery(".fltrow td [ct='"+index+"']").parent().hide();
  jQuery(".fltrow th [ct='"+index+"']").parent().hide();
  }
});

	jQuery(".add-new").on('click', function() {
		jQuery(this).parent().find(".onumbers_lists").toggle();
		jQuery(this).parent().find(".onumbers_lists").prop("selectedIndex", 0);
		jQuery(this).parent().find(".factory_order_number").toggle();		
		jQuery(this).parent().find(".order1-number2").text('');		
		if ($(this).text() == "Add New")
		   $(this).text("Back")
		else
		   $(this).text("Add New");
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
		//$(this).parent().children("span").remove();
		var getLiveValue = $(this).val();
		var getUnitSoldValue = parseInt($(this).parent().prev().text());
		//console.log($(this).next());
		if(getUnitSoldValue < getLiveValue)
		{
			//$(this).parent().append('<span class="error">Value must be less than Unit Sold Total</span>');
			$(this).parent().next().text("0");
			
			//$(this).parent().parent().children().last().text("0");
		}
		else
		{
			//var getNextValue = parseInt($(this).parent().next().text());
			var getDifference = getUnitSoldValue - getLiveValue;
			//var ga = getDifference
			$(this).parent().next().text(getDifference);
			//$(this).parent().parent().children().last().text(getDifference);
		}
		$(this).parent().find('span').text(getLiveValue);
	});
	
	jQuery(".onumbers_lists").change(function() {
		var getLiveValue = $(this).val();
		$(this).parent().find('.order1-number2').text(getLiveValue);
	});
	jQuery(".factory_order_number").on('keyup', function() {
		var getLiveValue = $(this).val();
		$(this).parent().find('.order1-number2').text(getLiveValue);
	});
	
	jQuery(".single-submit-it").on('click', function() {
		
		var form_data = new FormData();		
		var getCurrentRowVariationID = $(this).parent().parent().find("td .factory_order").data('variation_id');
		var getCurrentRowMinimumUnits = $(this).parent().parent().find("td .factory_order").data('minimum_units');
		var getCurrentRowFactoryUnits = $(this).parent().parent().find("td .factory_order").val();
		var getCurrentRowFactoryOrderSelect = $(this).parent().parent().find("td select.onumbers_lists:visible option:selected").val();
		var getCurrentRowFactoryOrder = $(this).parent().parent().find("td .factory_order_number:visible").val();
		var getCurrentRowFactoryOrderText = $(this).parent().parent().find("td .order1-number2").text();
		var getCurrentRowClass = $(this).parent().parent();
		var getCurrentRowClass1 = $(this);
		
		console.log(getCurrentRowVariationID);
		console.log(getCurrentRowFactoryUnits);
		console.log(getCurrentRowFactoryOrder);
		console.log(getCurrentRowFactoryOrderText);
		
		console.log(getCurrentRowFactoryOrderSelect);
		
		if(getCurrentRowFactoryUnits == '')
		{
			$(this).parent().parent().find("td .factory_order").addClass('red');
		}
		else
		{
			$(this).parent().parent().find("td .factory_order").removeClass('red');
		}
		
		if(getCurrentRowFactoryOrder == '')
		{
			$(this).parent().parent().find("td .factory_order_number:visible").addClass('red');
		}
		else
		{
			$(this).parent().parent().find("td .factory_order_number:visible").removeClass('red');
		}
		
		if(getCurrentRowFactoryOrderSelect == '')
		{
			$(this).parent().parent().find("td select.onumbers_lists").addClass('red');
		}
		else
		{
			$(this).parent().parent().find("td select.onumbers_lists").removeClass('red');
		}
		
		
		if(getCurrentRowFactoryUnits == '' || getCurrentRowFactoryOrder == '' || getCurrentRowFactoryOrderSelect == '')
		{
			alert("Data selection is incomplete");
		}
		else
		{
			
			form_data.append('getCurrentRowVariationID', getCurrentRowVariationID);
			form_data.append('getCurrentRowFactoryUnits', getCurrentRowFactoryUnits);
			if(getCurrentRowFactoryOrder === undefined && getCurrentRowFactoryOrderSelect === undefined)
			{
				form_data.append('getCurrentRowFactoryOrder', getCurrentRowFactoryOrderText);
			}
			else if (getCurrentRowFactoryOrder === undefined || getCurrentRowFactoryOrder === null) {
				form_data.append('getCurrentRowFactoryOrder', getCurrentRowFactoryOrderSelect);
			}
			else if (getCurrentRowFactoryOrderSelect === undefined || getCurrentRowFactoryOrderSelect === null) {
				form_data.append('getCurrentRowFactoryOrder', getCurrentRowFactoryOrder);
			}	
			
			form_data.append('action', 'adding_factory_data');
			
			$.ajax({
				type: "POST",
				url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
				contentType: false,
				processData: false,
				data: form_data,
				beforeSend: function() {
					jQuery(this).parent().parent().parent().addClass('adding-data');
				},
				success:function(data) {
					console.log(data);
					jQuery(".onumbers_lists").html(data);
					getCurrentRowClass.find("td .factory_order_number, td select.onumbers_lists, td .add-new").hide();
					getCurrentRowClass.find("td .order1-number2").css({"display": "block"});
					getCurrentRowClass.addClass('remove-data');
					jQuery(this).parent().parent().parent().removeClass('adding-data');
				},
				error: function(errorThrown){
					console.log(errorThrown);
					console.log('No update');
				}
			});
		}
	});
	
	$("a.submit-in-one").on('click', function() {
		var form_data = new FormData();   
		var getallInputValue = [];
		var getallInputOrderSelection = [];
		var getallInputOrderInput = [];
		
		jQuery( 'table#demo #myTable .onumbers_lists:visible' ).each(function() {
			if(jQuery(this).children("option:selected").val() != '')
			{
				getallInputOrderSelection.push(jQuery(this).children("option:selected").val());
				getallInputOrderInput.push(jQuery(this).parent().prev().prev().children(".factory_order").val());
				getallInputValue.push(jQuery(this).parent().prev().prev().children(".factory_order").data('variation_id'));
			}
		});
		
		jQuery( 'table#demo #myTable .factory_order_number:visible' ).each(function() {
			if(jQuery(this).val() != '')
			{
				getallInputOrderSelection.push(jQuery(this).val());	
				getallInputOrderInput.push(jQuery(this).parent().prev().prev().children(".factory_order").val());
				getallInputValue.push(jQuery(this).parent().prev().prev().children(".factory_order").data('variation_id'));
			}
		});
		console.log(getallInputValue);
		console.log(getallInputOrderSelection);
		console.log(getallInputOrderInput);
		
		form_data.append('getallInputValue', JSON.stringify(getallInputValue));
		form_data.append('getallInputOrderSelection', JSON.stringify(getallInputOrderSelection));
		form_data.append('getallInputOrderInput', JSON.stringify(getallInputOrderInput));
		form_data.append('action', 'adding_factory_data_push');
		
		$.ajax({
			type: "POST",
			url: "https://shop2.fexpro.com/wp-admin/admin-ajax.php",
			contentType: false,
			processData: false,
			data: form_data,
			beforeSend: function() {
				
			},
			success:function(data) {
				console.log(data);				
				jQuery(".onumbers_lists").html(data);
				$.each(getallInputValue, function(key, value){
					console.log(value);
					jQuery(".factory_order[data-variation_id='"+value+"']").parent().parent().find("td .factory_order_number, td select.onumbers_lists, td .add-new").hide();
					jQuery(".factory_order[data-variation_id='"+value+"']").parent().parent().find("td .order1-number2").css({"display": "block"});
				});
			},
			error: function(errorThrown){
				console.log(errorThrown);
				console.log('No update');
			}
		});
		
	});
});

function fnExcelReport()
{
    var SITEURL = "<?php echo site_url(); ?>/wp-content/themes/porto-child/";
	var form_data = new FormData();   
	var myArray = [];
	var myArray1 = [];
	var myArrayImage2 = [];
	var data = {};
	var tab = document.getElementById('myTable');
	var i=0, k=0;
	
	jQuery( 'table#demo thead > tr:nth-child(2) th:not(:last-child)' ).each(function() {
			myArray.push(jQuery(this).text());		
	});
	console.log(myArray);
	form_data.append('getHeaderArray', JSON.stringify(myArray));
	console.log(tab.rows);
	for(i = 0 ; i < tab.rows.length ; i++) 
    {     
		if(tab.rows[i].getAttribute("style") == 'display: none;')
		{	
			continue;
		}
		else
		{
			// console.log(tab.rows[j]);
			
			//console.log(tab.rows[j].innerHTML);
			for(k = 0 ; k < tab.rows[i].cells.length ; k++) 
			{
				if(tab.rows[i].cells[k].innerHTML.indexOf("uploads") != -1)
				{
					var abc = tab.rows[i].cells[k].innerHTML.split("https://shop2.fexpro.com");
					var res = abc[1].replace('">', "");
					//myArray1.push(res);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("single-submit-it") != -1)
				{
					continue;
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("for-Excel-only") != -1)
				{
					var abc4 = tab.rows[i].cells[k].innerHTML.split('<span class="for-Excel-only">');
					var res4 = abc4[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res4
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("order1-number2") != -1 && tab.rows[i].cells[k].innerHTML.indexOf("display") != -1)
				{
					var abc5 = tab.rows[i].cells[k].innerHTML.split('<span class="order1-number2" style="display: block;">');
					var res5 = abc5[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res5
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("order1-number2") != -1)
				{
					var abc5 = tab.rows[i].cells[k].innerHTML.split('<span class="order1-number2">');
					var res5 = abc5[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res5
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("woocommerce-Price-amount amount") != -1)
				{
					var abc2 = tab.rows[i].cells[k].innerHTML.split("$</span>");
					var res2 = abc2[1].replace('</bdi></span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res2
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("cart-sizes-attribute") != -1)
				{
					var abc3 = tab.rows[i].cells[k].innerHTML.split('<div class="cart-sizes-attribute"');
					var res3 = abc3[0].replace('<div class="cart-sizes-attribute"', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res3
					});
				}
				else
				{
					//myArray1.push(tab.rows[i].cells[k].innerHTML);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  tab.rows[i].cells[k].innerHTML
					});
				}
			}
			//tab.rows[j].innerHTML;
		}
    } 

	
	
	//console.log(tab);
	console.log(myArray1);
	form_data.append('getBodyArray', JSON.stringify(myArray1));
	form_data.append('action', 'export_cart_entries1');
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
				jQuery('#exportexcel').text('Export Cart in XLSX');
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
