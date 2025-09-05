<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');
$return_array1 = array();
$return_array2 = array();
global $wpdb;

$results = array();

$limit = 10;
$offset = 10;
if($_GET['filter']){
    $limit = $_GET['filter'];
}
if($_GET['page']){
    $offset = $offset * $_GET['page'];
}



$orders1 = wc_get_orders( array(
    'limit'    => -1,
    'status'   => array('wc-pending'),
	'return' => 'ids',
) );

foreach($orders1 as $order_id)
{
	
	$order = wc_get_order( $order_id );
	//$order_items  = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
	foreach ( $order->get_items() as $item_id => $item ) {
	$a = array();
	   $product_id = $item->get_product_id();
	   $variation_id = $item->get_variation_id();
	    if(!empty($product_id) && !empty($variation_id))
	    {
			$final_result1[] = $variation_id;
		}
	}
}

$orderCount = $wpdb->get_results(
    "SELECT order_item_id
        FROM ".$wpdb->prefix."wc_order_product_lookup as ls 
        JOIN ".$wpdb->prefix."posts as p on p.ID = ls.order_id
        WHERE p.post_type='shop_order' AND p.post_status='wc-pending' GROUP BY variation_id
    "
);
$totalRows = count($orderCount) - $offset;

$totalPagination = round($totalRows / $limit);
$currentPage = 1;

if($_GET['page'] && $_GET['page']>1){
    $currentPage = $_GET['page'] + 1;
}

if($_GET['page'] && $_GET['page'] != 1){
    $prevPage = $_GET['page'];
}

$orders = $wpdb->get_results(
    "SELECT COUNT(order_item_id) as totalCountOrder, SUM(product_qty) totalQty, GROUP_CONCAT(order_item_id) as item_id,  variation_id
        FROM ".$wpdb->prefix."wc_order_product_lookup as ls 
        JOIN ".$wpdb->prefix."posts as p on p.ID = ls.order_id
        WHERE p.post_type='shop_order' AND p.post_status='wc-pending' GROUP BY variation_id LIMIT ".$limit." OFFSET ".$offset."
    "
);

/*  echo "<pre>";
	print_r($orders);
	echo "</pre>";
	die(); */
	
foreach($orders as $orderKey => $orderValue){
    $variation_id = $orderValue->variation_id;
	if(in_array($variation_id, $final_result1))
	{
    $orderItemArr = explode(",",$orderValue->item_id);
    $sku = get_post_meta($variation_id, '_sku', true);
   
    foreach($orderItemArr as $key4 => $orderItem){
		//echo $orderItem;
         $order_item_data = new WC_Order_Item_Product($orderItem);     
		 //print_r($order_item_data);
		
			 $data = $order_item_data->get_data();
			 $order_id = $order_item_data->get_order_id();
			 $getCustomerID = get_post_meta($order_id, '_customer_user', true);
			 $product_id = $data['product_id'];
			 $qty = $data['quantity'];
			 $meal_name = $data['name'];

			$order_item1[$getCustomerID][] = $qty;
			$sum = 0;
			foreach ((array) $orderItem as $c) {
				$c1 = 0;
				$variation_size = wc_get_order_item_meta( $c, 'item_variation_size', true );
				$ap = wc_get_order_item_meta( $c, '_qty', true );
				foreach ($variation_size as $key => $size) 
				{
					$c1 += $size['value'];
				}
			   
				$sum += $c1 * $ap; 
			}

			$merge33[$variation_id][$getCustomerID][] = $sum;
		
    }
    $order_item[$variation_id]['product_name'] = $meal_name;
    $order_item[$variation_id]['product_sku'] = $sku;
    $order_item[$variation_id]['order_count'] = $orderValue->totalCountOrder;
    $order_item[$variation_id]['total_qty'] = $orderValue->totalQty;
    $order_item[$variation_id]['variation_size'] = $variation_size;
	}

}

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
<style type="text/css">
a.prevBtn, a.nextBtn {
       padding: 12px 50px;
    background: #e8eaf0;
    text-decoration: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    border-radius: 5px;
    margin-right: 6px;

}
.custom_pagination {
    margin: 30px 0px;
    display: inline-block;
}
tbody#myTable > tr > td:first-child img { width: 100px; }
    span.error {color: #f00;font-size: 10px; display: block;}
    .order_screen_container { float: left; width: 100%;margin-bottom: 15px; background: red;   padding: 15px;    }
    a.submit-it {     background: black;    color: #fff;    padding: .375rem .75rem;     font-size: 1rem;  height: calc(1.5em + .75rem + 2px);  line-height: 1.5;   width: auto;  float: right; }   
    a.single-submit-it {    background: black;    color: #fff;   padding: .375rem .75rem;  font-size: 1rem; height: calc(1.5em + .75rem + 2px);
        line-height: 1.5;          }
    .order_screen_container input {   float: left; width: auto;  }
    tbody#myTable tr td .red {    border-color: red !important;  }
    
    .cart-sizes-attribute {    min-width: 350px;     width: 100%;   margin-top: 20px;   }
    .cart-sizes-attribute .size-guide h5 {  border: solid 1px #000;  padding: 20px 9px!important;  }
    .size-guide h5{  color: #000;  font-size: 13px;  font-weight: 700;  line-height: 20px; margin-bottom: 0;  }
    .cart-sizes-attribute .size-guide {  display: -webkit-box;  display: -ms-flexbox;  display: flex; }
    .inner-size { display: block; width: 100%; -webkit-box-align: center; -ms-flex-align: center; align-items: center; text-align: center;
    }
    .cart-sizes-attribute .size-guide .inner-size { border: solid 1px #000; border-right: 0; border-left: 0; }
    .inner-size span:first-child { font-weight: bold; background: #008188; color: #fff; }
    .inner-size span { display: block;  width: 100%;   border-bottom: solid 1px #000; border-right: 1px solid #000;color: #000;padding: 5px 10px;   }
    span#exportexcel { background: #000; color: #fff; cursor: pointer; font-size: 24px; text-align: center; font-weight: bold;      margin-bottom: 15px;  padding: 5px 15px;  display: inline-block;  margin-left: 5px;  border-radius: 5px; transition: all 0.2s ease;
    }
    span#exportexcel:hover {     background: #b41520;  }
    span#stop-refresh { display: none;color: #f00;font-size: 18px;margin-left: 5px;margin-bottom: 15px;width: 100%;}
    .for-Excel-only, .order1-number2, .factory_order_number{display: none;}
    input.factory_order_number {margin-top: 10px;}
    .add-new {cursor: pointer;background: #000;display: inline-block;color: #fff;padding: 5px;border-radius: 5px;margin: 10px 0;}
    .order1-number2, .only1 {text-align: center;font-size: 17px;color: #f00;font-weight: bold;}
    .show .order1-number2{display: block;}
    .adding-data:before {content: "";background: rgba(0,0,0,0.5);z-index: 2;position: absolute;width: 100%;height: 100%;}
    table caption{caption-side: top;}
    .TF.sticky tr.fltrow th {top: -1px !important;background: #af0f2c;}
    .TF.sticky th {top: 33px !important;}
    body table.TF tr.fltrow th {border-bottom: 1px solid #000;border-top: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;
        padding: 0;color: #fff;}
    a.submit-in-one {position: fixed;bottom: 0;background: #af0f2c;color: #fff;padding: 5px;right: 0;font-size: 18px;cursor: pointer;}
    input.factory_order {max-width: 110px;}
    select.rspg + span {display: none;}
    table caption { caption-side: top; display: none; }
    input#pageSize { width: 61px; }

</style>
  <script src="jquery_script.js"></script>

         <?php 
            $nextHref= site_url()."/wp-content/themes/porto-child/revised_speedy_script_for_all_users_fw21.php?page=";
         ?>

         <span id="exportexcel" onclick="fnExcelReport();">Export to XLSX</span>
        <span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>

        <div class="custom_pagination">
            <select name="customDropDown" id="customDropDown">
                <option value="10" data-hrefUrl="<?php echo $nextHref.($_GET['page'])."&filter=10"; ?>" <?php if($_GET['filter'] == 10) { echo "selected='selected'"; }else{ echo "";} ?> >10</option>
                <option value="50" data-hrefUrl="<?php echo $nextHref.($_GET['page'])."&filter=50"; ?>" <?php if($_GET['filter'] == 50) { echo "selected='selected'"; }else{ echo "";} ?> >50</option>
                <option value="100" data-hrefUrl="<?php echo $nextHref.($_GET['page'])."&filter=100"; ?>" <?php if($_GET['filter'] == 100) { echo "selected='selected'"; }else{ echo "";} ?>>100</option>
                <option value="200" data-hrefUrl="<?php echo $nextHref.($_GET['page'])."&filter=200"; ?>" <?php if($_GET['filter'] == 200) { echo "selected='selected'"; }else{ echo "";} ?>>200</option>
				<option value="500" data-hrefUrl="<?php echo $nextHref.($_GET['page'])."&filter=500"; ?>" <?php if($_GET['filter'] == 500) { echo "selected='selected'"; }else{ echo "";} ?>>500</option>
				<option value="1000" data-hrefUrl="<?php echo $nextHref.($_GET['page'])."&filter=1000"; ?>" <?php if($_GET['filter'] == 1000) { echo "selected='selected'"; }else{ echo "";} ?>>1000</option>
				<option value="1500" data-hrefUrl="<?php echo $nextHref.($_GET['page'])."&filter=1500"; ?>" <?php if($_GET['filter'] == 1500) { echo "selected='selected'"; }else{ echo "";} ?>>1500</option>
				<option value="2000" data-hrefUrl="<?php echo $nextHref.($_GET['page'])."&filter=2000"; ?>" <?php if($_GET['filter'] == 2000) { echo "selected='selected'"; }else{ echo "";} ?>>2000</option>
            </select>

            <span>Page :- <input type="number" size="5" name="pageSize" id="pageSize"  data-pageUrl="<?php echo site_url()."/wp-content/themes/porto-child/get_variation_based_sku_filter_bk.php?page="; ?>" data-curPage="<?php echo $_GET['page']; ?>" data-curFilter="<?php echo $_GET['filter']; ?>" value="<?php if($_GET['page']){ echo $_GET['page']; }else{echo "1";} ?>"  /></span>

            <span>Total Pages : <?= $_GET['page']; ?>/ <?= $totalPagination; ?></span>
       </div>
        <table class="table table-bordered" border="1" id="demo">
           <thead>
                <tr>
                  <th style="vertical-align : middle;text-align:center;">Product image</th>
                  <th style="vertical-align : middle;text-align:center;">Style name </th>
                  <th style="vertical-align : middle;text-align:center;">Product sku</th>
                  <th style="vertical-align : middle;text-align:center;">Total qty</th>
                  <th style="vertical-align : middle;text-align:center;">Total order count</th>
                  <?php
                      $countries = WC()->countries->get_countries();
                      //print_r($countries);
                      foreach($order_item1 as $key1 => $value1)
                      {
                          //echo $value1 . "<br>";
                            $user_info = get_userdata($key1);
                            $first_name = $user_info->first_name;
                            $last_name = $user_info->last_name;
                            $getCompany = get_user_meta($key1, 'billing_company', true);
                            $getCountry = $countries[get_user_meta($key1, 'billing_country', true)];
                            echo "<th style='vertical-align : middle;text-align:center;' data-customer_id='". $first_name  . " " . $last_name ."'>" . $first_name  . " " . $last_name . " - " . $getCompany . " - " . $getCountry . "</th>";             
                      }
                    ?>
                     <th style="vertical-align : middle;text-align:center;">Total variations</th>
                </tr>
              </thead>
            <tbody id="myTable">
                <?php 
                  

                foreach($order_item as $key => $value)
                {   

                    $row3 = "<div class='cart-sizes-attribute'>";
                    $row3 .= '<div class="size-guide"><h5>Sizes</h5>';
                        foreach($value['variation_size'] as $variation_label){
                            $row3 .= "<div class='inner-size'><span>" . $variation_label['label']  . "</span>";
                            $row3 .= "<span class='clr_val'>" . $variation_label['value'] * $value['total_qty'] . "</span>";
                            $row3 .= "</div>";
                        }
                    $row3 .= "</div>";
                    $row3 .= "</div>";

                    $_product =  wc_get_product( $key);
                    $image_id           = $_product->get_image_id();
                    $gallery_thumbnail  = wc_get_image_size( array(100, 100) );
                    $thumbnail_size     = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
                    $thumbnail_src      = wp_get_attachment_image_src( $image_id, $thumbnail_size );
                   
                    
                    echo "<tr class='show'>";
                    echo "<td class='".$value['product_sku']."'><img src='" . $thumbnail_src[0] . "'/></td>";
                    echo "<td class='".$value['product_sku']."'>" . $value['product_name']  . $row3 . "</td>";
                    echo "<td class='".$value['product_sku']."'>" . $value['product_sku'] . "</td>";
                    echo "<td class='".$value['product_sku']."'>" . $value['total_qty'] . "</td>";
                    echo "<td class='".$value['product_sku']."'>" . $value['order_count'] . "</td>";
                    
                     $apk = 0;
                    foreach($order_item1 as $key1 => $value1)
                    {
                        $user_info = get_userdata($value1);
                        $first_name = $user_info->first_name;
                        $last_name = $user_info->last_name;
                        echo "<td class='".$value['product_sku']."' data-customer_id='". $first_name  . " " . $last_name ."'>" . $merge33[$key][$key1][0] . "</td>";
                        $apk += $merge33[$key][$key1][0];
                    }
                    echo "<td class='".$value['product_sku']."'>" . $apk . "</td>";



                    echo "</tr>";
                }

                ?>
            </tbody>
        </table>
       
   
<script src="dist/tablefilter/tablefilter.js"></script>
<script src="test-filters-visibility.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
           $('#customDropDown').on('change', function() {
             var url = $(this).find(':selected').data('hrefurl');
              window.location = url;
            });

           $('#pageSize').on('change', function() {
            var pageurl = $(this).data('pageurl');
            var curpage = $(this).val();
            var curfilter = $(this).data('curfilter');
         /*   alert(curpage);
            alert(curfilter);*/
            if(curpage >= 1){
                if(curfilter){
                    var c_Filter ="&filter=" + curfilter;
                    var newUrl = pageurl + curpage + c_Filter;
                }else{
                    var newUrl = pageurl + curpage;
                }
                
            }else{
                if(curfilter){
                    var c_Filter ="&filter" + curfilter;
                    var newUrl = pageurl + 1 + c_Filter;
                }else{
                    var newUrl = pageurl + 1;
                }
                
            }
            
                window.location = newUrl;
            });
        });


            function fnExcelReport(){
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
               // console.log(myArray);
                form_data.append('getHeaderArray', JSON.stringify(myArray));
                for(i = 0 ; i < tab.rows.length ; i++) 
                {     
                    //console.log(tab.rows[i].getAttribute("style"));
                    if(tab.rows[i].getAttribute("style") == 'display: none;')
                    {   
                        continue;
                    }
                    else
                    {
                        for(k = 0 ; k < tab.rows[i].cells.length ; k++) 
                        { 
                            //console.log(tab.rows[i].cells[k]);
                            if(tab.rows[i].cells[k].innerHTML.indexOf("uploads") != -1)
                            {
                                var abc = tab.rows[i].cells[k].innerHTML.split("https://stock.fexpro.com");
                                var res = abc[1].replace('">', "");
                                //myArray1.push(res);
                                myArray1.push({
                                    'Title': tab.rows[i].cells[k].getAttribute("class"), 
                                    'data':  res
                                });
                            }else if(tab.rows[i].cells[k].innerHTML.indexOf("cart-sizes-attribute") != -1){
                                var abc3 = tab.rows[i].cells[k].innerHTML.split('<div class="cart-sizes-attribute"');
                                var res3 = abc3[0].replace('<div class="cart-sizes-attribute"', "");
                                //myArray1.push(res2);
                                myArray1.push({
                                    'Title': tab.rows[i].cells[k].getAttribute("class"), 
                                    'data':  res3
                                });
                            }else{
                                myArray1.push({
                                    'Title': tab.rows[i].cells[k].getAttribute("class"), 
                                    'data':  tab.rows[i].cells[k].innerHTML
                                });
                            }
                        }
                    }
                }
                // console.log(myArray1);
                form_data.append('getBodyArray', JSON.stringify(myArray1));
                form_data.append('action', 'export_customer_variation_data');
                jQuery.ajax({
                    type: "POST",
                    url: "https://stock.fexpro.com/wp-admin/admin-ajax.php",
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
                            jQuery('#exportexcel').text('Export to XLSX');
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

 