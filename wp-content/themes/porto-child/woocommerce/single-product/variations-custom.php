<?php 

global $wpdb;

$current_presale = "ss25-q1";
$config = json_decode(get_option("wi_order_status_list"),true);
$list = [];
if(is_array($config)){
    $list = $config;
}else{
    $list = [];
}

foreach($list  as $os){
    if(isset($os["default"]) && $os["default"] == 1){
        $current_presale = $os["code"];
        break;
    }
}

$sql_variaciones="SELECT DISTINCT a.*,pp.post_excerpt post_excerpt_main, pm1.meta_value thumbnail_id, pm2.meta_value image , pm3.meta_value miniaturas,pm4.meta_value delivery_date,
(SELECT group_concat(concat(b.meta_key,'||',b.meta_value) SEPARATOR '///')  FROM wp_postmeta b WHERE b.post_id=a.ID ) metas
FROM wp_posts a 
INNER JOIN wp_posts pp on a.post_parent = pp.ID
LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=a.ID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attached_file')
LEFT JOIN wp_postmeta pm3 ON (pm3.post_id=pm1.meta_value AND pm3.meta_key='_wp_attachment_metadata')
LEFT JOIN wp_postmeta pm4 ON (pm4.post_id=a.ID AND pm4.meta_key='delivery_date')
WHERE a.post_status IN ('publish') and a.post_type='product_variation'
AND a.post_parent=".$product_id;
$variaciones = $wpdb->get_results($sql_variaciones);
$id_variaciones = array_column($variaciones,"ID");
$id_variaciones = count($id_variaciones)>0 ? $id_variaciones : [0];

$sql_ventas = "SELECT tmp.*, SUM(tmp.Qty) Qty, SUM(tmp.subtotal) subtotal FROM (
    SELECT p.order_id, p.order_item_id, p.order_item_name, 
    MAX(CASE WHEN pm.meta_key = '_product_id' AND p.order_item_id = pm.order_item_id THEN pm.meta_value END) AS productID, 
    MAX(CASE WHEN pm.meta_key = '_qty' AND p.order_item_id = pm.order_item_id THEN pm.meta_value END) AS Qty, 
    MAX(CASE WHEN pm.meta_key = '_variation_id' AND p.order_item_id = pm.order_item_id THEN pm.meta_value END) AS variationID, 
    MAX(CASE WHEN pm.meta_key = '_line_subtotal' AND p.order_item_id = pm.order_item_id THEN pm.meta_value END) AS subtotal
    FROM wp_woocommerce_order_items AS p, wp_woocommerce_order_itemmeta AS pm
    WHERE order_item_type = 'line_item' AND p.order_item_id = pm.order_item_id AND p.order_id IN (
    SELECT pp.ID
    FROM wp_posts pp
    WHERE pp.post_type='shop_order' AND pp.post_status='wc-{$current_presale}')
    GROUP BY p.order_item_id
    HAVING variationID IN(".implode(',',$id_variaciones).")
    ) AS tmp
    GROUP BY tmp.variationID ";
$ventas = $wpdb->get_results($sql_ventas);
$ventas = array_column($ventas,null,'variationID');
//echo $sql_ventas;
$var_data=array();
//$url_image = get_site_url()."/wp-content/uploads/";
$url_image = "https://shop2.fexpro.com/wp-content/uploads/";

$u =  wp_get_current_user();
$rol = isset($u->roles[0])?$u->roles[0]:array();

$discounts = discount_by_rol_margin($u->ID);

$collections =   get_the_terms( $product_id,"pa_collection" );
$collections =  is_array($collections)?array_column($collections,"name"):[];
$collections =  implode(", ",array_unique($collections));

$country =   get_the_terms( $product_id,"pa_country-of-origin" );
$country =  is_array($country)?array_column($country,"name"):[];
$country =  implode(", ",array_unique($country));

$player =   get_the_terms( $product_id,"pa_player" );
$player =  is_array($player)?array_column($player,"name","slug"):[];
// $player =  implode(", ",array_unique($player));

$terms = get_the_terms ($product_id, 'product_cat' );
$cats = unflattenArray2((array)$terms );
            
$product_type = isset($cats[0]) && isset($cats[0]["children"])
                && isset($cats[0]["children"][0]) && isset($cats[0]["children"][0]["children"])
                && isset($cats[0]["children"][0]["children"][0]) && isset($cats[0]["children"][0]["children"][0]["children"])
                ? $cats[0]["children"][0]["children"][0]["children"][0]["name"] : "";

$pa_date= get_the_terms( $product_id,"pa_date" );
$pa_date =  is_array($pa_date)?array_column($pa_date,"name"):[];
$pa_date = isset($pa_date[0])?$pa_date[0]:"-";


foreach ($variaciones as $key => $var) {
                     
    $metas_variation = explode("///",$var->metas);
    $metas_variation = array_map(function($row){
        $f = explode("||",$row);
        return array("key"=>$f[0],"value"=>$f[1]);
    },$metas_variation);
    $metas_variation=array_column($metas_variation,"value","key");
  
    //$color = preg_replace('/[0-9]+/', '',str_replace("Color: ","",$var->post_excerpt));
    $color = preg_match('/Color:\s*([^,]+)/',$var->post_excerpt, $coincidencias) && isset($coincidencias[1]) ?trim($coincidencias[1]):"";

    //$pa_color = strtolower(str_replace("Color: ","",$var->post_excerpt));
    $pa_color = isset($metas_variation["attribute_pa_color"]) ? $metas_variation["attribute_pa_color"]:"";
    $pa_team = $metas_variation["attribute_pa_team"] ?? "none";
    $pa_player = $metas_variation["attribute_pa_player"] ?? "";
    $miniaturas = unserialize($var->miniaturas);
    $miniaturas= isset($miniaturas["sizes"])? $miniaturas["sizes"]:"";
    $upload_path = $var->image!=""?explode("/",$var->image):"";
    if($upload_path!=""){
        array_pop($upload_path);
        $upload_path=implode("/",$upload_path);
    }

    $variation_id = $var->ID;


    $mini = isset($miniaturas["thumbnail"])?$miniaturas["thumbnail"]["file"]:(isset($miniaturas["medium"])?$miniaturas["medium"]["file"]:"");
    
    $delivery = "";
    if($var->delivery_date!=""){
        $dd= str_replace("/","-",$var->delivery_date);
        $dd= explode("-",$dd);
        if(count($dd)==3){
            $delivery=str_pad($dd[2], 2, "0", STR_PAD_LEFT)."-".str_pad($dd[1], 2, "0", STR_PAD_LEFT)."-".$dd[0];
        }
    }
    $delivery_presale = "";
    if($metas_variation["presale_delivery_date"]!=""){
        $dd= str_replace("/","-",$metas_variation["presale_delivery_date"]);
        $dd= explode("-",$dd);
        if(count($dd)==3){
            $delivery_presale=str_pad($dd[2], 2, "0", STR_PAD_LEFT)."-".str_pad($dd[1], 2, "0", STR_PAD_LEFT)."-".$dd[0];
        }
    }

    $product_team = isset($metas_variation["product_team"]) ? $metas_variation["product_team"]:"";
    $team = $pa_team=="none" && $product_team!="" ? str_replace(" ","-",trim(strtolower($product_team))):$pa_team;

    $item=array();
    $item["id"] = $variation_id;
    $item["main_id"] = $product_id;
    $item["image"] = $mini!=""?(string)$url_image.$upload_path."/".$mini:"";
    $item["image_full"] = $url_image.$var->image;

    $item['sku'] =  isset($metas_variation["_sku"])?$metas_variation["_sku"]:"";




    $item['product_title'] = $var->post_excerpt_main!=""
        ? $var->post_excerpt_main." - ".$color.", ".$product_team.(isset($player[$pa_player])?", ".$player[$pa_player]:"")
        : $var->post_title;// . " - " . $color ;
    $item["product_team"] = $product_team;
    

    $item['color'] =  $color ;
    $item["pa_color"] = $pa_color;
    $item["pa_team"] = $pa_team=="none" && $item["product_team"]!="" ? str_replace(" ","-",trim(strtolower($item["product_team"]))):$pa_team;
    $item['pa_player'] = $pa_player;
    $item["delivery_date"] = $delivery;
    $item["presale_delivery_date"] = $delivery_presale;
    $item["fabric_composition"] = $metas_variation["pa_fabric_composition"]??"";
    $item["country"] = $country;
    $item["collection"] = $collections ;
    $item["product_type"] = $product_type;
    $item["player"] = $player;

    //$item['Season'] =  isset($main_atts["pa_season"])?$main_atts["pa_season"]:"";
    $price=isset($metas_variation["_price"])?$metas_variation["_price"]:0;
    if($rol!="administrator"){
        $price_ = role_price_get_by_id($variation_id,$rol);
        $price = $price_!==null?$price_:$price;

        if(in_array($rol,array("custom_role_mexico1","custom_role_mexico2")) && $discounts["margin"]!=0){
            $_margin = $price - ($price * ($discounts["margin"]/100));
            $iva = 1+($discounts["iva"]/100);
            $final=$_margin / $iva;
            $price=$final;
            
        }

        
    }
    
    $item['price'] =  $price;
    $item['old_price'] = $price!=$price_?$price_:0;

    $item['Division'] =  "";

    $item['brand'] =  isset($main_atts["pa_brand"])?$main_atts["pa_brand"]:""; 
    $item['gender'] =  isset($main_atts["pa_gender"])?$main_atts["pa_gender"]:"";
    
    $item['stock_present'] =  isset($metas_variation["_stock_present"]) ? (int)$metas_variation["_stock_present"]:0;
    $item['stock_future'] =  isset($metas_variation["_stock_future"]) ? (int)$metas_variation["_stock_future"]:0;
    $item['stock_present_china'] =  isset($metas_variation["_stock_present_china"]) ? (int)$metas_variation["_stock_present_china"]:0;
    
    $item["color_clear"] = preg_replace('/[0-9]+/', '', trim($pa_color));

    $item["variation_name"] = ($item["product_team"]!=""?$item["product_team"]." - ":"").$color;
    $item["product_team_name"] = $product->get_name().(($item["product_team"]??"")!=""?"-":"") .($item["product_team"]??"");

    $item["date"] = $pa_date;//isset($main_atts["pa_date"])?$main_atts["pa_date"]:"-";
    $item["logo_application"] = isset($metas_variation["logo_application"]) ? $metas_variation["logo_application"]:"";

    $sizes=array();
    $units_per_pack=0;
    for($i=1;$i<=10;$i++){
        if(isset($metas_variation["custom_field".$i]) && $metas_variation["custom_field".$i]!=""){
            $sizes[]=array(
                "size"=>$metas_variation["custom_field".$i],
                "value"=>isset($metas_variation["size_box_qty".$i])?(int)$metas_variation["size_box_qty".$i]:"0",
                "medidas" => isset($metas_variation["medidas_".$i]) ? $metas_variation["medidas_".$i] : "",
            );
            $units_per_pack+=isset($metas_variation["size_box_qty".$i])?(int)$metas_variation["size_box_qty".$i]:0;
        }
    }
    $item["sizes"]=$sizes;

    $total_units_purchased = isset($ventas[$variation_id]) ? $ventas[$variation_id] : 0 ;
 

    $scale_drop  = isset($metas_variation["scale_drop"])?$metas_variation["scale_drop"]  : "";
    $scale_stock = isset($metas_variation["scale_stock"])?$metas_variation["scale_stock"]: "";
    $scale_ok    = isset($metas_variation["scale_ok"])?$metas_variation["scale_ok"]      : "";
    $scale_drop  = $scale_drop!='' ? (strpos($scale_drop,"-")> -1 ? end(explode("-",$scale_drop))  : $scale_drop):null;
    $scale_stock  = $scale_stock !='' ? (count(explode("-",$scale_stock)) == 2 ? explode("-",$scale_stock) :null) : null;
    $scale_ok  = $scale_ok !='' ? explode('-',$scale_ok)[0] : null;
    $suggestion = "";
    $total_units_purchased_amout= isset($total_units_purchased->Qty)? $total_units_purchased->Qty * $units_per_pack : 0;
    if($scale_drop != null && $scale_stock != null && $scale_ok != null){
        $scale_drop = ((int)$scale_drop)<0?((int)$scale_drop)*-1:(int)$scale_drop;

        $suggestion = $total_units_purchased_amout < $scale_drop ? 'DROP' : ($total_units_purchased_amout >= (int)$scale_stock[0] && $total_units_purchased_amout <= (int)$scale_stock[1] ? 'STOCK' : ($total_units_purchased_amout >= $scale_ok ? 'OK':''));
    }
    $item['suggestion'] = $suggestion;
    $item['total_units_purchased'] = $total_units_purchased;

    
    $gallery_data=[];
    $gallery_data[]=[
        "image"=>$item["image_full"],
        "mini"=> $item["image"]
    ];
    $gallery = isset($metas_variation["woo_variation_gallery_images"]) ? unserialize($metas_variation["woo_variation_gallery_images"]):null;

    if(is_array($gallery)){
        $sql_ga = "SELECT * FROM wp_postmeta pm3 WHERE pm3.meta_key = '_wp_attachment_metadata' AND pm3.post_id IN (".implode(",",$gallery).")";
        $gallery = $wpdb->get_results($sql_ga);
      
       
        if(is_array($gallery)){
            foreach($gallery as $g){
                $g=unserialize($g->meta_value);
                
                $_upload_path = $g["file"]!=""?explode("/",$g["file"]):"";
                if($_upload_path!=""){
                    array_pop($_upload_path);
                    $_upload_path=implode("/",$_upload_path);
                }
                $tmp=[
                    "image"=>(string)$url_image.$g["file"],
                    "mini"=> isset($g["sizes"]) && isset($g["sizes"]["thumbnail"]) && $g["sizes"]["thumbnail"]["file"]!=""?(string)$url_image.$_upload_path."/".$g["sizes"]["thumbnail"]["file"]:""
                ];
                $gallery_data[]=$tmp;
            }
        }

    }
    $item["gallery"] = $gallery_data;
    $var_data[]=$item;
}


?>
