<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;
//print_r($order);

$notes = $order->get_customer_order_notes();

global $wpdb;
$return_array = [];
$oID = $order->get_order_number();
$orderTableName = $wpdb->prefix . 'woocommerce_order_items';
$orderMetaTableName = $wpdb->prefix . 'woocommerce_order_itemmeta';
$getOrderItemIDData = $wpdb->get_results("Select `order_item_id` from $orderTableName WHERE `order_item_type` = 'line_item' AND `order_id` = $oID");
//print_r($getOrderItemIDData);
foreach($getOrderItemIDData as $key => $val)
{
	$kp = $val->order_item_id;
	$getOrderItemmetadata = $wpdb->get_results("Select `meta_key`, `meta_value` from $orderMetaTableName WHERE `order_item_id` = $kp");
	//print_r($getOrderItemmetadata);
	foreach($getOrderItemmetadata as $val1)
	{
		$data[$val1->meta_key] = $val1->meta_value;
	}
	array_push($return_array, $data);
}


//  echo "<pre>";
// print_r($return_array);
// echo "</pre>"; 
// die;
$xlsx_data = array();
/* $data['ProductImage'] = 'Product Image';
$data['ProductName'] = 'Product Name';
$data['ProductSKU'] = 'Product SKU';
$data['Unitprice'] = 'Unit Price';
$data['Boxunits'] = 'Box Units'; */

foreach($return_array as $ra1)
{
	$c1 = 0;
	//$xlsx_data = array(); 
	if(!empty($ra1['item_variation_size']))
	{
		foreach (maybe_unserialize($ra1['item_variation_size']) as $key => $size)
		{
			//$c1 += $size['value'];
			$merge1[$ra1['_variation_id']][$size['label']][] = $ra1['_qty'] * $size['value'];
			$merge3[$size['label']] = $size['label'];
			//array_push($xlsx_data, $size['label']); 
			
		}
		
	}
	
}

//  echo "<pre>";
// print_r($merge1);
// echo "</pre>"; 
// die;
?>
<table class="table table-bordered" id="demo" style="display: none;">
   <thead>
        <tr>
          <th style="vertical-align : middle;text-align:center;">Product image</th>
          <th style="vertical-align : middle;text-align:center;">Product Name</th>
          <th style="vertical-align : middle;text-align:center;">Product SKU</th>
		  <?php
		  foreach ($merge3 as $akkk3 => $akkkv3) 
		  {
			echo '<th style="vertical-align : middle;text-align:center;">'. $akkk3 .'</th>';
		  }
		  ?>
		  <th style="vertical-align : middle;text-align:center;">Unit Price</th>
          <th style="vertical-align : middle;text-align:center;">Total Box Units</th>
        </tr>
      </thead>
	<tbody id="myTable">
		<?php 
		foreach($return_array as $value)
		{

			$k = 0;
			$_product =  wc_get_product( $value['_variation_id']);
			if(!empty($_product)){
				$image_id			= $_product->get_image_id();
				$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
				$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
				$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );
				echo "<tr>";
					echo "<td class='".$_product->get_sku()."'>". $thumbnail_src[0] . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 . "</td>";
					echo "<td class='".$_product->get_sku()."'>" . $_product->get_sku() . "</td>";
					foreach ($merge3 as $akkk3 => $akkkv3) 
					{
						echo "<td class='".$_product->get_sku()."'>" . $merge1[$value['_variation_id']][$akkk3][0] . "</td>";	
						$k += $merge1[$value['_variation_id']][$akkk3][0];
					} 
					echo "<td class='".$_product->get_sku()."'>" . $value['_line_subtotal'] / $k . "</td>";	
					echo "<td class='".$_product->get_sku()."'>" . $k . "</td>";					
				echo "</tr>";
			}
			
		}
		?>
	</tbody>
</table>


<div class="order_head_holder">
<p>
<?php
printf(
	/* translators: 1: order number 2: order date 3: order status */
	esc_html__( 'Order #%1$s was placed on %2$s and is currently %3$s.', 'woocommerce' ),
	'<mark class="order-number">' . $order->get_order_number() . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'<mark class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'<mark class="order-status">' . wc_get_order_status_name( $order->get_status() ) . '</mark>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
);
?>
<strong class="moq">Minimum MOQ per style is 1000 Units</strong>
<button onclick="window.print()" class="woocommerce-button button view printt">Print this page</button>
<button class="woocommerce-button button view printt" id="export" onclick="fnExcelReport();">Export Order Items</button>
</p>
</div>

<?php if ( $notes ) : ?>
	<h2><?php esc_html_e( 'Order updates', 'woocommerce' ); ?></h2>
	<ol class="woocommerce-OrderUpdates commentlist notes">
		<?php foreach ( $notes as $note ) : ?>
		<li class="woocommerce-OrderUpdate comment note">
			<div class="woocommerce-OrderUpdate-inner comment_container">
				<div class="woocommerce-OrderUpdate-text comment-text">
					<p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<div class="woocommerce-OrderUpdate-description description">
						<?php echo wpautop( wptexturize( $note->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>

<?php do_action( 'woocommerce_view_order', $order_id ); ?>

<script>
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
	
	jQuery( 'table#demo thead > tr th' ).each(function() {
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
		url: "https://shop2.fexpro.com/wp-admin/admin-ajax.php",
		contentType: false,
		processData: false,
		data: form_data,
		beforeSend: function() {
			jQuery('#exportexcel').text('Creating XLSX File');
			jQuery('#stop-refresh').show();
			jQuery("#export").text('Exporting Data').css('background-color', '#e31d1a');
		},
		success:function(msg) {
			console.log(msg);	
			jQuery("#export").text('Export Order Items').css('background-color', '#000000');
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
