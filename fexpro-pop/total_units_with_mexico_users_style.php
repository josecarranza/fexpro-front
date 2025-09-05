<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
$return_array3 = array();
global $wpdb;

$orders = wc_get_orders( array(
    'limit'    => -1,
    'status' => array('wc-processing', 'wc-completed'),
	'return' => 'ids',
) );

foreach($orders as $order_id)
{
	
	$order = wc_get_order( $order_id );
	if($order->get_billing_country() == 'MX')
	{
		//$order_items  = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
		foreach ( $order->get_items() as $item_id	 => $item ) {
		$a = array();
		   $product_id = $item->get_product_id();
		   $variation_id = $item->get_variation_id();
			if(!empty($product_id) && !empty($variation_id))
			{
				/* if( has_term( array( 'popup'), 'product_cat' ,  $product_id) ) 
				{ */
					$getCountry = get_post_meta($bk['order_id'], '_billing_country', true);
					$getCustomerID = get_post_meta($order_id, '_customer_user', true);
					$final_result1[$variation_id][] = $order_id;
					$final_result3[$variation_id][$getCustomerID][] = $item_id;			
					$final_result4[$variation_id][] = $item->get_total() / $item->get_quantity();			
				/* } */
			}
		}
	}
}
/* echo "<pre>";
print_r($final_result3);
echo "</pre>";

die();  */
foreach($final_result1 as $key => $value)
{
  foreach($value as $ak)
  {
	$user_meta = get_post_meta($ak, '_customer_user', true);	
	if(!in_array($user_meta, $return_array1))
	{
		array_push($return_array1, $user_meta);
	}
  }
}

foreach($final_result3 as $key3 => $value3)
{
	//echo "<p>" . $key3. "</p>";
	foreach($value3 as $key4 => $abc)
	{
		$sum = 0;
		$d = 0;
		foreach($abc as $c)
		{
			$c1 = 0;
			$variation_size = wc_get_order_item_meta( $c, 'item_variation_size', true );
			$ap = wc_get_order_item_meta( $c, '_qty', true );
			foreach ($variation_size as $key => $size) 
			{
				$c1 += $size['value'];
				$merge1[$key3][$size['label']][] = $ap * $size['value'];
			}
			
			
			$sum += $c1 * $ap; 
			$d += $c1;
		}
		//echo "<p>" . $key4 . " " . $sum . "</p>";
		$merge[$key3][$key4][] = $sum;
		$merge2[$key3][] = $d;
		/* if(!in_array($key3, $return_array3))
		{
			$m[$key3][] = $c;
			array_push($return_array3, $key3);
		} */		
	}
}

/* echo "<pre>";
print_r($final_result4);
echo "</pre>"; */

/* foreach($merge as $key => $value)
{
	$_product =  wc_get_product( $key); 
	$main_product = wc_get_product( $_product->get_parent_id() );
	$brands = $main_product->get_attribute( 'pa_brand' );
	if(!in_array($brands, $return_array2))
	{
		array_push($return_array2, $brands);
	}
} */

?>

<!DOCTYPE html>
<html lang='en' xml:lang='en' xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta charset='utf-8' />
<meta content='en' name='language' />
<meta content='width=device-width, initial-scale=1' name='viewport' />

  <title>Fexpro - Purchase content</title>
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="../wp-content/themes/porto-child/jquery_script.js"></script>

  
  <style>
  select.rspg + span {
    display: none;
}
	h2 {
    text-align: center;
    margin: 15px 0;
    text-transform: uppercase;
    font-weight: bold;
	}
	tbody#myTable > tr > td:first-child img {
		width: 100px;
	}	
	.multiple-search {
    float: left;
    width: 100%;
    padding: 20px 0;
    margin-bottom: 15px;
	}
	.multiple-search input, .multiple-search select {
		width: auto;
		float: left;
		margin: 0 2px;
		border-width: 2px !important;
	}
	.multiple-search input#myInput1 {
		width: 19%;
	}
	table caption{caption-side: top;}
	table#demo thead tr.fltrow > td:first-child input {
		display: none !important;
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
	.TF.sticky tr.fltrow th {
    top: -2px !important;
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

tr.last-tr {
    display: table-row !important;
}
  </style>
</head>
<body>

<h2>Fexpro pop Mexico Customers Role -- Purchase List</h2>

<!--<div class="multiple-search">
<input class="form-control border border-success filter" id="myInput" type="text" placeholder="Search from SKU.." data-col='sku'>
<?php
/* echo "<select id='brand_selection' class='form-control border border-success filter' data-col='brand'>";
echo "<option value=''>Search by Brand...</option>";
foreach($return_array2 as $key => $value)
{
	echo "<option value='" . $value ."'>" . ucfirst(strtolower($value)) . "</option>";
}
echo "</select>"; */
?>
<input class="form-control border border-success filter" type="text" id="myInput1" placeholder="Search by Mens, Womens, Boys, Girls and All.." data-col='category'>
<input class="form-control border border-success filter" type="text" id="myInput2" placeholder="Search by Season.." data-col='season'>
</div>
<span id="exportexcel">Excel</span>-->
<span id="exportexcel" onclick="fnExcelReport();">Export to XLSX</span>
<span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>
<div class="exporting-it">
<table class="table table-bordered" id="demo">
   <thead>
        <tr>
		  <th style="vertical-align : middle;text-align:center;">Product image</th>
          <th  style="vertical-align : middle;text-align:center;">Style name</th>
          <th  style="vertical-align : middle;text-align:center;">Style sku</th>
          <th  style="vertical-align : middle;text-align:center;">Brand</th>
          <th  style="vertical-align : middle;text-align:center;">Gender</th>
          <th  style="vertical-align : middle;text-align:center;">Category</th>
          <th  style="vertical-align : middle;text-align:center;">Sub-category</th>
          <th  style="vertical-align : middle;text-align:center;">Season</th>
		  <th style="vertical-align : middle;text-align:center;">Composition</th>
          <th style="vertical-align : middle;text-align:center;">Producto logo</th>
		  <?php
		  $countries = WC()->countries->get_countries();
		  //print_r($countries);
		  foreach($return_array1 as $key1 => $value1)
		  {
			  //echo $value1 . "<br>";
				$user_info = get_userdata($value1);
				$first_name = $user_info->first_name;
				$last_name = $user_info->last_name;
				$getCompany = get_user_meta($value1, 'billing_company', true);
				$getCountry = $countries[get_user_meta($value1, 'billing_country', true)];
				echo "<th style='vertical-align : middle;text-align:center;' data-customer_id='". $first_name  . " " . $last_name ."'>" . $first_name  . " " . $last_name . " - " . $getCompany . " - " . $getCountry . "</th>";			 
		  }
		  ?>
          <th style="vertical-align : middle;text-align:center;">Selling Price</th>
		  <th style="vertical-align : middle;text-align:center;">Total Unit Purchased</th>
		  <th style="vertical-align : middle;text-align:center;">Total Value</th>
        </tr>
      </thead>
	<tbody id="myTable">
		<?php 
			$i = 0;
			$len = count($merge);
			foreach($merge as $key => $value)
			{
				$_product =  wc_get_product( $key); 
				$main_product = wc_get_product( $_product->get_parent_id() );
				$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
				/* $terms_string = join(', ', wp_list_pluck($cat, 'name'));
				echo $terms_string; */
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
						if($cvalue->parent == '1423')
						{
							$css_slugGender[] = $cvalue->name;
						}
					}
					else
					{
						//$css_slugGender[] = $cvalue->name;
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
					echo "<td class='".$_product->get_sku()."'><img src='" . $thumbnail_src[0] . "'/></td>";
					echo "<td class='".$_product->get_sku()."'>" . $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' );
						echo $row3;
					echo "</td>";
					echo "<td class='".$_product->get_sku()."'>" . $_product->get_sku() . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . $main_product->get_attribute( 'pa_brand' ) . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugGender) . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugCategory) . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugSubCategory) . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . $main_product->get_attribute( 'pa_season' ) . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . $fabricCompositionString . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . $logoApplicationString . "</td>";
					
					$apk = 0;
					foreach($return_array1 as $key1 => $value1)
					{
						$user_info = get_userdata($value1);
						$first_name = $user_info->first_name;
						$last_name = $user_info->last_name;
						//$company_name = get_user_meta($value1, 'billing_company', true);
						echo "<td class='".$_product->get_sku()."' data-customer_id='". $first_name  . " " . $last_name ."'>" . $value[$value1][0] . "</td>";
						$apk += $value[$value1][0];
					}				
					echo "<td class='".$_product->get_sku()."'>" . $final_result4[$key][0] / $merge2[$key][0]  . "</td>";					
					echo "<td class='".$_product->get_sku()."'>" . $apk . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . wc_price($final_result4[$key][0] / $merge2[$key][0] * $apk) . "</td>";
				echo "</tr>";
				echo "</tr>";	
				if ($i == $len - 1) {
					echo "<tr style='background-color: #000; color: #fff;' class='last-tr'>";
							echo "<td class='total-line'></td>";
							echo "<td class='total-line'></td>";
							echo "<td class='total-line'></td>";
							echo "<td class='total-line'></td>";
							echo "<td class='total-line'></td>";
							echo "<td class='total-line'></td>";
							echo "<td class='total-line'></td>";
							echo "<td class='total-line'></td>";
							echo "<td class='total-line'></td>";
							echo "<td class='total-line'></td>";
							foreach($return_array1 as $key1 => $value1)
							{
								$user_info = get_userdata($value1);
								$first_name = $user_info->first_name;
								$last_name = $user_info->last_name;
								echo "<td class='total-line' data-customer_id='" . $first_name  . " " . $last_name . "'></td>";
							}	
							echo "<td class='total-line'></td>";
							echo "<td class='total-line'></td>";
							echo "<td class='total-line'></td>";
					echo "</tr>";
				}
			$i++;
			}
		?>
	</tbody>
</table>
</div>

<script src="../wp-content/themes/porto-child/dist/tablefilter/tablefilter.js"></script>
<script src="../wp-content/themes/porto-child/fexpro-popup-filters-visibility.js"></script>


<script>
const formatToCurrency = amount => {
  return "$" + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
};
jQuery(window).bind("load", function () {
	var d = 0;	
	var c = 0;
	setTimeout(function() {
	jQuery("table#demo tbody > tr:not('.last-tr'):visible td:nth-last-child(2)").each(function(){
		c += Number($(this).text());
	});
	console.log(c);
	jQuery(".last-tr td:nth-last-child(2)").text(c);
	
	jQuery("table#demo tbody > tr:not('.last-tr'):visible td:last-child .woocommerce-Price-amount bdi").each(function(){
		//console.log(Number($(this).text().replace(/[^0-9.-]+/g,"")));
		d += Number($(this).text().replace(/[^0-9.-]+/g,""));
	});
	jQuery(".last-tr td:last-child").text(formatToCurrency(d));
	},500);
});
$('#demo tr.fltrow input').keypress(function (e) {
	var key = e.which;
	if(key == 13)  // the enter key code
	{
		var c = 0;
		var d = 0;
		setTimeout(function() {
		jQuery("table#demo tbody > tr:not('.last-tr'):visible td:nth-last-child(2)").each(function(){
			c += Number($(this).text());
			
		});
		console.log(c);
		jQuery(".last-tr td:nth-last-child(2)").text(c);
		
		jQuery("table#demo tbody > tr:not('.last-tr'):visible td:last-child .woocommerce-Price-amount bdi").each(function(){
			//console.log(Number($(this).text().replace(/[^0-9.-]+/g,"")));
			d += Number($(this).text().replace(/[^0-9.-]+/g,""));
		});
		jQuery(".last-tr td:last-child").text(formatToCurrency(d));
		
		},500);
	}
	jQuery(".last-tr").css({"display": ""});
});

$('.pgSlc, .rspg').on('change', function (e) {
	var key = e.which;
		var c = 0;
		var d = 0;
		setTimeout(function() {
		jQuery("table#demo tbody > tr:not('.last-tr'):visible td:nth-last-child(2)").each(function(){
			c += Number($(this).text());
			
		});
		console.log(c);
		jQuery(".last-tr td:nth-last-child(2)").text(c);
		
		jQuery("table#demo tbody > tr:not('.last-tr'):visible td:last-child .woocommerce-Price-amount bdi").each(function(){
			//console.log(Number($(this).text().replace(/[^0-9.-]+/g,"")));
			d += Number($(this).text().replace(/[^0-9.-]+/g,""));
		});
		jQuery(".last-tr td:last-child").text(formatToCurrency(d));
		
		},500);
	
	jQuery(".last-tr").css({"display": ""});
});

$('input.pgInp.nextPage, input.pgInp.lastPage, input.pgInp.previousPage, input.pgInp.firstPage').on('click', function (e) {
	var key = e.which;
		var c = 0;
		var d = 0;
		setTimeout(function() {
		jQuery("table#demo tbody > tr:not('.last-tr'):visible td:nth-last-child(2)").each(function(){
			c += Number($(this).text());
			
		});
		console.log(c);
		jQuery(".last-tr td:nth-last-child(2)").text(c);
		
		jQuery("table#demo tbody > tr:not('.last-tr'):visible td:last-child .woocommerce-Price-amount bdi").each(function(){
			//console.log(Number($(this).text().replace(/[^0-9.-]+/g,"")));
			d += Number($(this).text().replace(/[^0-9.-]+/g,""));
		});
		jQuery(".last-tr td:last-child").text(formatToCurrency(d));
		
		},500);
	
	jQuery(".last-tr").css({"display": ""});
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
	
	jQuery( 'table#demo thead > tr:nth-child(2) th' ).each(function() {
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
					var abc = tab.rows[i].cells[k].innerHTML.split("https://shop.fexpro.com");
					var res = abc[1].replace('">', "");
					//myArray1.push(res);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res
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
