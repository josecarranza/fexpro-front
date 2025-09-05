<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
include('../../wp-load.php');

delete_transient('getTableBodyData');                        
$return_array = array();
$return_array1 = array();
$return_array2 = array();
global $wpdb;

$names = 'RION SPORTS PRODUCTS CO. LTD';
$getZenlineOrdersList = $wpdb->get_results("SELECT * FROM wp_fw22_factory_order_confirmation_list WHERE `factoryname` = '$names'   ", ARRAY_A );


foreach($getZenlineOrdersList as $key => $value)
{
    $vid = $value['vid'];
    $allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM {$wpdb->prefix}wc_order_product_lookup WHERE `variation_id` = '$vid'", ARRAY_A );
    foreach($allData as $bk)
    {
        if ( get_post_status ( $bk['order_id'] ) != 'wc-pending') 
        {
            continue;
        }
        else
        {
            $return_array1[$value['vid']][] = $bk['order_item_id'];
        }
    }
}
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
                    foreach ($variation_size as $key => $size) 
                    {
                        $c1 += $size['value'];
                        $merge1[$key3][$size['label']][] = $ap * $size['value'];
                        $merge3[$size['label']] = $size['label'];
                    }
                    
                }
                array_push($return_array2, $abc);
            }
            
            $sum += $c1 * $ap; 
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Fexpro - Purchase content</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
  <script src="../../wp-content/themes/porto-child/jquery_script.js"></script>
  <style>
  h2 {text-align: center; padding: 15px 0;  text-transform: uppercase;  font-weight: bold;  color: red;  }
    table#demo {  overflow-x: scroll;  max-width: 100%;  display: block;}
    tbody#myTable > tr > td:first-child img { width: 100px;  }    
    span.error {color: #f00;font-size: 10px;display: block;}
    .order_screen_container {float: left;width: 100%;margin-bottom: 15px;padding: 15px;   }
    a.submit-it {background: black;color: #fff;padding: .375rem .75rem;font-size: 1rem;height: calc(1.5em + .75rem + 2px);line-height: 1.5;
        width: auto;float: right;}   
    a.single-submit-it {background: black;  color: #fff;padding: .375rem .75rem;font-size: 1rem;height: calc(1.5em + .75rem + 2px);line-height: 1.5;
    }
    .order_screen_container input {float: left;width: auto;}
    tbody#myTable tr td .red {border-color: red !important;}
    .cart-sizes-attribute {min-width: 350px;  width: 100%; margin-top: 20px; }
    .cart-sizes-attribute .size-guide h5 {border: solid 1px #000;padding: 20px 9px!important;}
    .size-guide h5{color: #000;font-size: 13px;font-weight: 700;line-height: 20px;margin-bottom: 0;}
    .cart-sizes-attribute .size-guide {display: -webkit-box;display: -ms-flexbox;display: flex;background: #fff;}
    .inner-size { display: block;  width: 100%;  -webkit-box-align: center; -ms-flex-align: center; align-items: center;text-align: center;}
    .cart-sizes-attribute .size-guide .inner-size {border: solid 1px #000;border-right: 0;border-left: 0;}
    .inner-size span:first-child {font-weight: bold;background: #008188;color: #fff;   }
    .inner-size span {display: block;width: 100%;border-bottom: solid 1px #000;border-right: 1px solid #000;color: #000;padding: 5px 10px;}
    span#exportexcel {background: #000;color: #fff;cursor: pointer;font-size: 24px;text-align: center;font-weight: bold;margin-bottom: 15px;
        padding: 5px 15px;display: inline-block;margin-left: 5px;border-radius: 5px;transition: all 0.2s ease;}
    span#exportexcel:hover {background: #b41520;   }
    span#stop-refresh {display: none;color: #f00;font-size: 18px;margin-left: 5px;margin-bottom: 15px;width: 100%; }
    .for-Excel-only, .order1-number2, .factory_order_number{display: none;}
    input.factory_order_number {margin-top: 10px;}
    .add-new {cursor: pointer;background: #000;display: inline-block;color: #fff;padding: 5px;border-radius: 5px;margin: 10px 0;   }
    .order1-number2, .only1 {   text-align: center;font-size: 17px;color: #f00;font-weight: bold;}
    .show .order1-number2{display: block;}
    .adding-data:before {content: "";background: rgba(0,0,0,0.5); z-index: 2; position: absolute; width: 100%; height: 100%;}
    table caption{caption-side: top;}
    .TF.sticky tr.fltrow th {top: -1px !important;background: #af0f2c;}
    .TF.sticky th {top: 33px !important;}
    body table.TF tr.fltrow th {border-bottom: 1px solid #000;border-top: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;
    padding: 0;   color: #fff;}
    a.submit-in-one {position: fixed;bottom: 0; background: #af0f2c; color: #fff; padding: 5px; right: 0; font-size: 18px; cursor: pointer;}
    input.factory_order { max-width: 110px;}
    select.rspg + span { display: none;}
    td.redakshu { background: #bb1717;  color: #fff;}

    span#exportexcel1 {
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
    span#exportexcel1:hover {background: #b41520;}
    
    tr.red {background: #ff00002e;}
    span.no-v {display: block;width: 100%;font-size: 16px;color: #000;font-weight: 500;}
    span.no-v strong {   color: #f00;text-decoration: underline;   font-weight: 900;}


  </style>
</head>
<body>

<h2><?php echo $names; ?></h2>

<!--<div class="order_screen_container">
<input class="form-control" id="myInput" type="text" placeholder="Search..">
<a href="Javascript:void(0);" class="submit-it"><i class="fas fa-save"></i></a>
<span id="exportexcel" onclick="fnExcelReport();">Export to XLSX</span>
</div>-->
<span id="exportexcel" onclick="fnExcelReport();" style="display:none;">Export to XLSX</span>
<span id="exportexcel1" onclick="fnExcelReport1();">Export All to XLSX</span>

<span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>


<table class="table table-bordered" id="demo">
   <thead>
        <tr>
          <th style="vertical-align : middle;text-align:center;">Order Number</th>
          <th style="vertical-align : middle;text-align:center;">Product image</th>
          <th style="vertical-align : middle;text-align:center;">Item name</th>
          <th style="vertical-align : middle;text-align:center;">Style sku</th>
          <?php
          if($merge3){
            foreach ($merge3 as $akkk3 => $akkkv3) 
              {
                echo '<th style="vertical-align : middle;text-align:center; display: none">'. $akkk3 .'</th>';
              }  
          }           
          
          ?>
          <th style="vertical-align : middle;text-align:center;">Composition</th>
          <th style="vertical-align : middle;text-align:center;">Producto logo</th>
          <th style="vertical-align : middle;text-align:center;">Factory Order</th>
          <th style="vertical-align : middle;text-align:center;">Factory Name</th>
          <th style="vertical-align : middle;text-align:center;">Delivery Date</th>
          <th style="vertical-align : middle;text-align:center;">Cost price</th>
          <th style="vertical-align : middle;text-align:center;">Total Amount</th>
        </tr>
      </thead>
    <tbody id="myTable">
        <?php

        $tableBody = array();

        foreach($getZenlineOrdersList as $key => $value)
        {
            foreach ($merge3 as $akkk3 => $akkkv3) 
            {
                if(!empty($merge1[$value['vid']]))
                {
                    foreach($merge1[$value['vid']] as $ko => $ko1)
                    {
                        $q1  = 0;
                        
                        if( $akkk3 == $ko)
                        {
                            foreach($ko1 as $ko2 => $ko22)
                            {
                                $q1 += $ko22;
                            }
                            $merge67[$value['vid']][$akkk3][] = $q1;
                        }
                        else                    
                        {
                            $merge67[$value['vid']][$akkk3][] = '';
                        }
                    }   
                }
            }
        }   
            
        $i = 0;
        $len = count($getZenlineOrdersList);
        foreach($getZenlineOrdersList as $key => $value)
        {
            if(!empty(wc_get_product( $value['vid'] )))
			{

                $_product =  wc_get_product( $value['vid']);
                $image_id           = $_product->get_image_id();
                $gallery_thumbnail  = wc_get_image_size( array(100, 100) );
                $thumbnail_size     = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
                $thumbnail_src      = wp_get_attachment_image_src( $image_id, $thumbnail_size );
                
                $fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
                $fabricCompositionString = $fabricComposition[0]->name; 
                
                $logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
                $array_logo = array();
                if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
                if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
                if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
                if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
                
                $logoApplicationString = implode(', ', $array_logo);
            }
            else
            {	
                $_parent_product =  wc_get_product($_product->get_parent_id());
                if(!in_array($_parent_product->get_sku(), $kl))
                {
                    echo "<span class='no-v'>This Style SKU is not avialable in Presale3 anymore : <strong>". $_parent_product->get_sku() . "</strong>. Please remove from Factory Order List. <strong>(Marked with Red Color Row)</strong></span>";
                    array_push($kl, $_parent_product->get_sku());
                }
            }
            
            
            if(empty($value['costprice']))
            {
                $classs = 'redakshu';
            }
            else
            {
                $classs = '';
            }
            
            $row3 = "<div class='cart-sizes-attribute'>";
            $row3 .= '<div class="size-guide"><h5>Sizes</h5>';
            if(!empty($merge1[$value['vid']]))
            {
                $k = '';
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
            }else{
                $k = 'red';
            }    
            $row3 .= "</div>";
            $row3 .= "</div>";
            
            echo "<tr class='" . $k . "'>";
                echo "<td class='".$_product->get_sku()."'>" . $value['forderid'] . "</td>";
                echo "<td class='".$_product->get_sku()."'><img src='" . $thumbnail_src[0] . "'/></td>";
                echo "<td class='".$_product->get_sku()."'>" . $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 . "</td>";
                echo "<td class='".$_product->get_sku()."'>" . $_product->get_sku() . "</td>";

                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['forderid']));
                $imageUrlThumb = str_replace("https://shop.fexpro.com", "",$thumbnail_src[0]);
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $imageUrlThumb ));
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' )) );
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_sku()));

                if(!empty($merge67[$value['vid']]))
                {
                        foreach($merge67[$value['vid']] as $qw => $qr)
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
                                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => '' ));                      
                            }
                            else
                            {
                                echo "<td class='".$_product->get_sku()."' style='display: none;'>" . $fk . "</td>";
                                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fk ));                        
                            }
                        }
                }
                else
                {
                     foreach ($merge3 as $akkk3 => $akkkv3) 
                  {
                    echo "<td class='".$_product->get_sku()."' style='display: none;'></td>";
                    array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => '' ));   
                  }
                }
                echo "<td class='".$_product->get_sku()."'>" . $fabricCompositionString . "</td>";
                echo "<td class='".$_product->get_sku()."'>" . $logoApplicationString . "</td>";
                echo "<td class='".$_product->get_sku()."'>" . $value['forderunits'] . "</td>";             
                echo "<td class='".$_product->get_sku()."'>" . $value['factoryname'] . "</td>";
                echo "<td class='".$_product->get_sku()." $classs'>" . $value['deliverydate'] . "</td>";
                echo "<td class='".$_product->get_sku()." $classs'> " . $value['costprice'] . "</td>";               
                echo "<td class='".$_product->get_sku()." $classs'>" . wc_price($value['forderunits'] * $value['costprice']) . " </td>";    

                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fabricCompositionString ));   
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $logoApplicationString ));   
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['forderunits'] ));   
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['factoryname'] ));   
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['deliverydate'] ));   
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['costprice'] ));
                
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => floatval(($value['forderunits'] * $value['costprice']) )) );   


            echo "</tr>";
        }

        delete_transient('getTableBodyData');

		set_transient('getTableBodyData', $tableBody, 21600);


        ?>
    </tbody>
    <tfoot>
    <?php
    foreach($getZenlineOrdersList as $key => $value)
        {
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
                    echo "<td class='total-line'></td>";
                echo "</tr>";
            }
            $i++;
        }
    ?>
    </tfoot>
</table>
<script src="../../wp-content/themes/porto-child/dist/tablefilter/tablefilter.js"></script>
<script src="../../wp-content/themes/porto-child/fexpro-ss22-filters-visibility.js"></script>

<script>
const formatToCurrency = amount => {
  return "$" + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
};

$(document).ready(function(){
$( "table thead tr:not(.fltrow) th" ).each(function( index ) {
  if($(this).is(":hidden"))
  {
  console.log( index + ": ");
  jQuery(".fltrow th [ct='"+index+"']").parent().hide();
  }
});

    var d = 0;
    setTimeout(function() { 
    jQuery("table#demo tbody > tr:visible td:last-child .woocommerce-Price-amount bdi").each(function(){
        d += Number($(this).text().replace(/[^0-9.-]+/g,""));
    });
    jQuery(".last-tr td:last-child").text(formatToCurrency(d));
    },500);

$('#demo tr.fltrow input').keypress(function (e) {
    var key = e.which;
    if(key == 13)  
    {
        var d = 0;
        setTimeout(function() {
        
        jQuery("table#demo tbody > tr:visible td:last-child .woocommerce-Price-amount bdi").each(function(){
            d += Number($(this).text().replace(/[^0-9.-]+/g,""));
        });
        jQuery(".last-tr td:last-child").text(formatToCurrency(d));
        
        },500);
    }
    jQuery(".last-tr").css({"display": ""});
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
            for(k = 0 ; k < tab.rows[i].cells.length ; k++) 
            {
                if(tab.rows[i].cells[k].innerHTML.indexOf("uploads") != -1)
                {
                    var abc = tab.rows[i].cells[k].innerHTML.split("https://shop.fexpro.com");
                    var res = abc[1].replace('">', "");
                    myArray1.push({
                        'Title': tab.rows[i].cells[k].getAttribute("class"), 
                        'data':  res
                    });
                }
                else if(tab.rows[i].cells[k].innerHTML.indexOf("woocommerce-Price-amount amount") != -1)
                {
                    var abc2 = tab.rows[i].cells[k].innerHTML.split("$</span>");
                    var res2 = abc2[1].replace('</bdi></span>', "");
                    myArray1.push({
                        'Title': tab.rows[i].cells[k].getAttribute("class"), 
                        'data':  res2
                    });
                }
                else
                {
                    myArray1.push({
                        'Title': tab.rows[i].cells[k].getAttribute("class"), 
                        'data':  tab.rows[i].cells[k].innerHTML
                    });
                }
            }
        }
    } 

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


            function fnExcelReport1()
            {   

                var SITEURL = "<?php echo site_url(); ?>/wp-content/themes/porto-child/";
                var form_data = new FormData();   
                var myArray = [];
                var myArray1 = [];
                var myArrayImage2 = [];
                var data = {};
                var tab = document.getElementById('myTable');
                var i=0, k=0;
                var factory_name = '<?php echo $names;  ?>';
                jQuery( 'table#demo thead > tr:nth-child(2) th' ).each(function() {
                        myArray.push(jQuery(this).text());      
                });
                console.log(myArray);
                form_data.append('getHeaderArray', JSON.stringify(myArray));
                form_data.append('exportFrom', 'view_fectory');
                form_data.append('factory_name', factory_name);
                form_data.append('action', 'export_cart_entries_all_data');
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
