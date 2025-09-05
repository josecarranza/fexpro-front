<?php 
class WiQuickEasyPurchase{
    public $db;
    public $SLUG_MAIN_CAT = "stock-inmediato";
    public function __construct() {

        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        global $wpdb;
        $this->db = $wpdb;
        //$this->db = new wpdb('shop','3eLp%2q0','fexpro_new_live1','localhost:3308');

       
        //add_action('wp_ajax_order_exporter_wi', array($this,'method_control'));
        //add_action('wp_ajax_nopriv_order_exporter_wi',  array($this,'method_control'));
        add_shortcode('wi-quick-easy-purchase',array($this,"shortcode_wi_quick_easy_purchase"));

        add_action('wp_ajax_qep_get_products', array($this,"products_get"));
        add_action('wp_ajax_nopriv_qep_get_products', array($this,"products_get"));

        add_action('wp_ajax_qep_get_products_export', array($this,"export_xlsx"));
        add_action('wp_ajax_nopriv_qep_get_products_export', array($this,"export_xlsx"));
        
        add_action('wp_ajax_download_xlsx', array($this,"download_xlsx"));
        add_action('wp_ajax_nopriv_download_xlsx', array($this,"download_xlsx"));

        add_action('wp_ajax_qep_add_to_cart', array($this,"qep_add_to_cart"));
        add_action('wp_ajax_nopriv_qep_add_to_cart', array($this,"qep_add_to_cart"));

        add_action('wp_ajax_qep_view_cart', array($this,"qep_view_cart"));
        add_action('wp_ajax_nopriv_qep_view_cart', array($this,"qep_view_cart"));

        add_action('wp_ajax_qep_add_to_cart_bulk', array($this,"qep_add_to_cart_bulk"));
        add_action('wp_ajax_nopriv_qep_add_to_cart_bulk', array($this,"qep_add_to_cart_bulk"));
        
        
        //add_filter( 'woocommerce_add_to_cart_validation', array($this,'woocommerce_add_cart_stock_future'));
    }
    
    function shortcode_wi_quick_easy_purchase() { 
        $cats_childrens=array();
        $categories = $this->filter_categories($cats_childrens);
        
        $brands = $this->filter_brands($cats_childrens);
        $lob = $this->filter_lob();
        $delivery_dates = $this->filter_delivery_date();

        $current_filters = array();
        $current_filters["pa_brand"] = isset($_GET["pa_brand"])?explode(",",$_GET["pa_brand"]):array();
        $current_filters["pa_brand"] = array_map(function($item){ return addslashes($item);},$current_filters["pa_brand"]);

        $current_filters["meta_lob"] = isset($_GET["meta_lob"])?explode(",",$_GET["meta_lob"]):array();
        $current_filters["meta_lob"] = array_map(function($item){ return addslashes($item);},$current_filters["meta_lob"]);

        $current_filters["meta_delivery_date"] = isset($_GET["meta_delivery_date"])?explode(",",$_GET["meta_delivery_date"]):array();
        $current_filters["meta_delivery_date"] = array_map(function($item){ return addslashes($item);},$current_filters["meta_delivery_date"]);

        $is_submit = isset($_GET["submit"])?1:0;

        wp_enqueue_style('wi-qep-styles', WI_PLUGIN_URL. 'assets/css/quick-easy-purchase-styles.css', array(),false, false);
        ob_start();
        include(WI_PLUGIN_PATH."/shortcodes/view-quick-easy-purchase.php");
        $html = ob_get_clean();
        return $html;
    }

    function filter_categories(&$cats_childrens=array()){
        

        $only_cats=array(4595,4104,1829);

        $query1="SET @id_root_cate = (SELECT tax.term_taxonomy_id FROM wp_terms t INNER JOIN wp_term_taxonomy tax ON t.term_id=tax.term_id WHERE t.slug='".$this->SLUG_MAIN_CAT."' AND tax.taxonomy='product_cat')";
        $query2="CREATE TEMPORARY TABLE if NOT exists tmp_categories AS (
            SELECT a.term_taxonomy_id,b.name, b.slug,a.parent, a.count, 1 level FROM wp_term_taxonomy a INNER JOIN wp_terms b ON a.term_id=b.term_id
            WHERE a.taxonomy='product_cat' AND a.count>0
            AND a.parent =@id_root_cate AND term_taxonomy_id IN (".implode(",",$only_cats).")
        )";
        $query3="CREATE TEMPORARY TABLE if NOT exists tmp_categories2 AS (
        SELECT a.term_taxonomy_id,b.name, b.slug,a.parent,a.count, 2 level FROM wp_term_taxonomy a INNER JOIN wp_terms b ON a.term_id=b.term_id
            WHERE a.taxonomy='product_cat' AND a.count>0
            AND a.parent IN (SELECT term_taxonomy_id FROM tmp_categories tmp WHERE tmp.level=1)
        )";
        $query4="CREATE TEMPORARY TABLE if NOT exists tmp_categories3 AS (
        SELECT a.term_taxonomy_id,b.name, b.slug,a.parent,a.count,3 level FROM wp_term_taxonomy a INNER JOIN wp_terms b ON a.term_id=b.term_id
            WHERE a.taxonomy='product_cat' AND a.count>0
            AND a.parent IN (SELECT term_taxonomy_id FROM tmp_categories2 tmp WHERE tmp.level=2)
        )";


        $query5 = " SELECT * FROM (
        SELECT * FROM tmp_categories 
        UNION ALL 
        SELECT * FROM tmp_categories2
        UNION ALL 
        SELECT * FROM tmp_categories3
        ) tmp ORDER BY tmp.level ASC,tmp.name ASC";

        $query6="DROP TEMPORARY TABLE  tmp_categories, tmp_categories2,tmp_categories3";

        $this->db->query($query1);
        $this->db->query($query2);
        $this->db->query($query3);
        $this->db->query($query4);
        $_result = $this->db->get_results($query5);


        $taxonomies=$_result;

        $_ids_cats=array_column($taxonomies,"term_taxonomy_id");
        $getCategoryArr = array();
        $this->db->query($query6);

        $final=array();
        $tree=array();

        $current_filters = array();
        $current_filters["product_cat"] = isset($_GET["product_cat"])?explode(",",$_GET["product_cat"]):array();
        $current_filters["product_cat"] = array_map(function($item){ return addslashes($item);},$current_filters["product_cat"]);

        if (!empty($taxonomies)) :
            $getCategoryArr = array();


            $slugs_ids=array_column($taxonomies,"slug","term_taxonomy_id");
            $parent2=array();
            foreach ($taxonomies as $key => $cat) {
                if($cat->level==1 && !isset($getCategoryArr[$cat->slug])){
                    $getCategoryArr[$cat->slug]=array();
                    $final[$cat->slug]=array(
                        "value" => $cat->slug,
                        "text" => $cat->name,
                        "items"=>array(),
                        "checked"=>in_array($cat->slug,$current_filters["product_cat"])
                    );
                }
                if($cat->level==2 && !isset($getCategoryArr[$slugs_ids[$cat->parent]][$cat->slug]) ){
                    $getCategoryArr[$slugs_ids[$cat->parent]][$cat->slug]=array();
                    $parent2[$cat->slug] = $slugs_ids[$cat->parent];

                    $final[$slugs_ids[$cat->parent]]["items"][$cat->slug]=array(
                        "value" => $cat->slug,
                        "text" => $cat->name,
                        "items"=>array(),
                        "checked"=>in_array($cat->slug,$current_filters["product_cat"])
                    );
                    if(!isset($tree[$cat->parent])){
                        $tree[$cat->parent]=array();
                    }
                    $tree[$cat->parent][]=$cat->term_taxonomy_id.$cat->slug;
                }
                if($cat->level==3){
                    $getCategoryArr[$parent2[$slugs_ids[$cat->parent]]][$slugs_ids[$cat->parent]][$cat->slug]=$cat->name;
                    $final[$parent2[$slugs_ids[$cat->parent]]]["items"][$slugs_ids[$cat->parent]]["items"][]=array(
                        "value" => $cat->slug,
                        "text" => $cat->name,
                        "checked"=>in_array($cat->slug,$current_filters["product_cat"])
                    );
                    if(!isset($tree[$cat->parent])){
                        $tree[$cat->parent]=array();
                    }
                    $tree[$cat->parent][]=$cat->term_taxonomy_id.$cat->slug;
                }
            }
            $__tmp = $getCategoryArr;
            $getCategoryArr=array($SLUG_MAIN_CAT=>$__tmp);
            unset($__tmp);
        endif;
       $final=array_values($final);
       foreach ($final as $key => $value) {
           $final[$key]["items"] = array_values($final[$key]["items"]);
       }
       $cats_childrens=$tree;
        return $final;
    }
    function filter_brands($cats=array()){
        if(!isset($_GET["product_cat"]) || $_GET["product_cat"]==""){
            $cat_in =array($this->SLUG_MAIN_CAT);
        }else{
            $cat_in = explode(",",$_GET["product_cat"]);
            $cat_in = array_map(function($item){ return $item!=""?addslashes($item):false;},$cat_in);
            $cat_in=array_filter($cat_in);
            if(count($cat_in)==0){
                $cat_in =array($this->SLUG_MAIN_CAT);
            }
        }
    
        
        $sql="SELECT b.term_id, b.slug value, b.name text
        FROM wp_term_taxonomy a
        INNER JOIN wp_terms b ON a.term_id=b.term_id
        INNER JOIN wp_term_relationships c ON a.term_taxonomy_id=c.term_taxonomy_id
        WHERE a.taxonomy='pa_brand'
        AND c.object_id IN(
        SELECT p.ID FROM wp_posts p
        INNER JOIN wp_term_relationships tr ON p.ID=tr.object_id 
        INNER JOIN wp_terms tt ON tr.term_taxonomy_id=tt.term_id AND tt.slug IN ('".implode("','",$cat_in)."')
        WHERE p.post_status IN ('publish','private')
        )
        GROUP BY b.term_id
        ORDER BY b.name ASC";

        $r=$this->db->get_results($sql,ARRAY_A);

        $current_filters = array();
        $current_filters["pa_brand"] = isset($_GET["pa_brand"])?explode(",",$_GET["pa_brand"]):array();
        $current_filters["pa_brand"] = array_map(function($item){ return addslashes($item);},$current_filters["pa_brand"]);

        foreach ($r as $key => $value) {
            $r[$key]["checked"] = in_array($value["value"],$current_filters["pa_brand"]);
        }
       
        return $r;
    }
    function filter_lob(){
        $lob=array(array("value"=>"POP","text"=>"POP"),array("value"=>"SPO","text"=>"SPORT"),array("value"=>"UNI","text"=>"UNIVERSITIES"));

        $current_filters = array();
        $current_filters["meta_lob"] = isset($_GET["meta_lob"])?explode(",",$_GET["meta_lob"]):array();
        $current_filters["meta_lob"] = array_map(function($item){ return addslashes($item);},$current_filters["meta_lob"]);

        foreach ($lob as $key => $value) {
            $lob[$key]["checked"] = in_array($value["value"],$current_filters["meta_lob"]);
        }
        return $lob;
    }
    function filter_delivery_date(){

        $current_filters = array();
        $current_filters["meta_delivery_date"] = isset($_GET["meta_delivery_date"])?explode(",",$_GET["meta_delivery_date"]):array();
        $current_filters["meta_delivery_date"] = array_map(function($item){ return addslashes($item);},$current_filters["meta_delivery_date"]);

        $date=date("Y-m-01");
        $current_month = (int)date("m");
        $current_year = date("Y");
        $months=array();
        for($i=0;$i<6;$i++){
            if($i>0)
                $current_month=$current_month+1;

            if($current_month>12){
                $current_month=1;
                $current_year++;
            }
            $tmp_d=$current_year."-".($current_month<10?"0":"").$current_month;
            $tmp_str = date("F",strtotime($tmp_d."-01")).", ".$current_year;
            $months[]=array("value"=>$tmp_d,"text"=>strtoupper($tmp_str),"checked"=>(in_array($tmp_d,$current_filters["meta_delivery_date"])));
        }
        array_unshift($months,array("value"=>"now","text"=>"IMMEDIATE","checked"=>(in_array("now",$current_filters["meta_delivery_date"]))));
        return $months;
    }
    function products_get(){
    
        $per_page=100;
        $pag = isset($_GET["pag"])?(int)$_GET["pag"]:1;
        $limit_start = ($pag-1)*$per_page;
        $limit_end = ($limit_start+$per_page);
        $result=array();
        
        $products=[];

        $url_image = get_site_url()."/wp-content/uploads/";
        $site_url = get_site_url();
        $ids=array();
        $posts=$this->main_products_get($ids);
       
        if ($posts) {
            $count=0;
            foreach ($posts as $key => $main_product) {
                $sql_variaciones="SELECT a.*, pm1.meta_value thumbnail_id, pm2.meta_value image ,pm3.meta_value miniaturas,pm4.meta_value delivery_date,
                (SELECT group_concat(concat(b.meta_key,'||',b.meta_value) SEPARATOR '///')  FROM wp_postmeta b WHERE b.post_id=a.ID ) metas
                FROM wp_posts a 
                LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=a.ID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
                LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attached_file')
                LEFT JOIN wp_postmeta pm3 ON (pm3.post_id=pm1.meta_value AND pm3.meta_key='_wp_attachment_metadata')
                LEFT JOIN wp_postmeta pm4 ON (pm4.post_id=a.ID AND pm4.meta_key='delivery_date')
                WHERE a.post_status IN ('publish')
                AND a.post_parent=".$main_product->ID;
                $variaciones = $this->db->get_results($sql_variaciones);



                if(is_array($variaciones) && count($variaciones)>0){
                    $count_var = count($variaciones);
                    if(($count+$count_var)<$limit_start){
                        $count+=$count_var;
                        continue;
                    }
                    if($count>($limit_end-1)){
                        $count+=$count_var;
                        continue;
                    }
                    
                    //$main_product = wc_get_product( $value->ID );
                    $sql_main_atts="SELECT tt.taxonomy,tt.term_id,t.name,t.slug,tt.parent
                    FROM wp_term_relationships tr
                    INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id
                    INNER JOIN wp_terms t ON tt.term_id=t.term_id
                    WHERE tr.object_id=".$main_product->ID ;
    
                    $main_product_atts=$this->db->get_results($sql_main_atts, ARRAY_A);
                    $cats=array();
                    $main_atts=array();
                    if(is_array($main_product_atts)){
                        foreach ($main_product_atts as $item) {
                           /*if($item["taxonomy"] == "product_cat"){
                            $cats[$item["term_id"]] = $item;
                           }*/
                           if( substr($item["taxonomy"],0,3) == "pa_" ){
                               $main_atts[$item["taxonomy"]] = $item["name"];
                           }
                        }
                    }

                    $u =  wp_get_current_user();
                    $rol = isset($u->roles[0])?$u->roles[0]:array();

                    foreach ($variaciones as $key => $var) {
                     
                        $metas_variation = explode("///",$var->metas);
                        $metas_variation = array_map(function($row){
                            $f = explode("||",$row);
                            return array("key"=>$f[0],"value"=>$f[1]);
                        },$metas_variation);
                        $metas_variation=array_column($metas_variation,"value","key");
                      
                        $color = preg_replace('/[0-9]+/', '',str_replace("Color: ","",$var->post_excerpt));
                        $pa_color = strtolower(str_replace("Color: ","",$var->post_excerpt));

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
                                $delivery=$dd[0] ."-".str_pad($dd[1], 2, "0", STR_PAD_LEFT)."-".str_pad($dd[2], 2, "0", STR_PAD_LEFT);
                            }
                        }
                        
                        $item=array();
                        $item["id"] = $variation_id;
                        $item["main_id"] = $main_product->ID;
                        $item["image"] = $mini!=""?(string)$url_image.$upload_path."/".$mini:"";
    
                        $item['sku'] =  isset($metas_variation["_sku"])?$metas_variation["_sku"]:"";
    
                        $item['product_title'] =  $var->post_title;// . " - " . $color ;
                        $item["product_url"] =  $site_url . '/product/' . $main_product->post_name;
    
                        $item['color'] =  $color ;
                        $item["pa_color"] = $pa_color;
                        $item["delivery_date"] = $delivery;
    
                        //$item['Season'] =  isset($main_atts["pa_season"])?$main_atts["pa_season"]:"";
                        $price=isset($metas_variation["_price"])?$metas_variation["_price"]:0;
                        if($rol!="administrator"){
                            $price_ = role_price_get_by_id($variation_id,$rol);
                            $price = $price_!==null?$price_:$price;
                        }

                        $item['price'] =  $price;
    
                        $item['Division'] =  "";
    
                        $item['brand'] =  isset($main_atts["pa_brand"])?$main_atts["pa_brand"]:""; 
                        $item['gender'] =  isset($main_atts["pa_gender"])?$main_atts["pa_gender"]:"";
                        
                        $item['stock_present'] =  isset($metas_variation["_stock_present"]) ? (int)$metas_variation["_stock_present"]:0;
                        $item['stock_future'] =  isset($metas_variation["_stock_future"]) ? (int)$metas_variation["_stock_future"]:0;

                        $sizes=array();
                        $units_per_pack=0;
                        for($i=1;$i<=10;$i++){
                            if(isset($metas_variation["custom_field".$i]) && $metas_variation["custom_field".$i]!=""){
                                $sizes[]=array("size"=>$metas_variation["custom_field".$i],"value"=>isset($metas_variation["size_box_qty".$i])?(int)$metas_variation["size_box_qty".$i]:"0");
                                $units_per_pack+=isset($metas_variation["size_box_qty".$i])?(int)$metas_variation["size_box_qty".$i]:0;
                            }
                        }
                        $item["sizes"]=$sizes;
                        $item["units_per_pack"] = $units_per_pack;
    
                        if($count>=$limit_start && $count<=($limit_end-1)){
                            $products[] = $item;
                        }
                        

                        $count++;
                    }
                }


            }
        }



        $result["products"] = $products;
        $result["total"] = $count;
        $result["pages"] = $count>0? ceil($count/$per_page):0;

        print_r(json_encode($result));
        die();
    }

    function main_products_get(&$ids_post=array()){
        $slug_category = isset($_GET["product_cat"]) ? trim($_GET["product_cat"]):"";
        $brand = isset($_GET["pa_brand"]) ? trim($_GET["pa_brand"]):"";
        $pag = isset($_GET["pag"])?(int)$_GET["pag"]:1;

        if($slug_category==""){
            $slug_category = array($this->SLUG_MAIN_CAT);
        }else{
            $slug_category = explode(",",$slug_category);
        }
        
        $color = isset($_GET["pa_color"]) ? trim($_GET["pa_color"]):"";
        $lob = isset($_GET["meta_lob"]) ? trim($_GET["meta_lob"]):"";
        $delivery_date = isset($_GET["meta_delivery_date"]) ? preg_replace("/[^a-zA-Z0-9\-,_]+/", "",trim($_GET["meta_delivery_date"])):"";

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            //'paged' => $pag,
            'post_status' => array('publish'),
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $slug_category,
                    'operator'        => 'IN',
                )
            ),
            'meta_query'=>array()
        );
        if ($brand!="") {
            $filter_brand = explode(',', $brand);
            $args['tax_query'][] = array(
                'taxonomy'        => 'pa_brand',
                'field'           => 'slug',
                'terms'           =>  $filter_brand,
                'operator'        => 'IN',
            );
        }

        
   
        $filter_variation=array();

        if($delivery_date!=""){
            $filter_variation["delivery_dates"] = explode(",",$delivery_date);
        }
        if ($lob!="") {
            $filter_variation["meta_lob"] = explode(',', $lob);
        }

  
        $loop = new WP_Query($args);
       
        $meta_delivery_date = isset($_GET["meta_delivery_date"])?explode(",",$_GET["meta_delivery_date"]):array();

        $pos = array_search("now",$meta_delivery_date);

        $only_stock_present=$pos!==false?true:false;
        unset($meta_delivery_date[$pos]);
   
        $loop = $this->filter_variations($loop,$filter_variation);
        
        if($loop!=null && $loop->have_posts()){
            $ids_post = array_column($loop->posts,"ID");
            return $loop->posts;
        }else{
            return null;
        }
    }
    function filter_variations($loop,$filter_variation=array(),$extra_args=array()){
        if ($loop->have_posts()) {
            global $wpdb;
            $ids_posts=array_column($loop->posts,"ID");

            $delivery_dates =  isset($filter_variation["delivery_dates"])?$filter_variation["delivery_dates"]:array();

            $pos = array_search("now",$delivery_dates);
            $only_stock_present=$pos!==false?true:false;
            if($only_stock_present){
                unset($delivery_dates[$pos]);
            }

            $sql="SELECT p.post_parent FROM wp_posts p ";
            if($only_stock_present || count($delivery_dates)>0):
                $sql.=" INNER JOIN wp_postmeta pm ON p.ID = pm.post_id AND (";

                    if($only_stock_present){
                        $sql.=" (pm.meta_key = '_stock_present' AND CAST(pm.meta_value AS SIGNED) > 0) ";
                    }

                    if(count($delivery_dates)>0){
                        $jj=0;
                        foreach ($delivery_dates as  $dd) {
                                $_date = date("Y-m",strtotime($dd."-01"));
                                $sql.=((!$only_stock_present && $jj>0) || $only_stock_present ? "OR" :"")." (pm.meta_key = 'delivery_date' AND DATE_FORMAT(CAST(pm.meta_value AS DATE),'%Y-%m') ='".$_date ."') ";
                                $jj++;
                        }
                    }
                $sql.=" ) ";
                if(count($delivery_dates)>0):
                    $sql.=" INNER JOIN wp_postmeta pms ON p.ID = pms.post_id AND  (pms.meta_key = '_stock_future' AND CAST(pms.meta_value AS SIGNED) > 0)  ";
                endif;

            else:
                $sql.=" INNER JOIN wp_postmeta pms ON p.ID = pms.post_id AND ( (pms.meta_key = '_stock_present' AND CAST(pms.meta_value AS SIGNED) > 0) OR (pms.meta_key = '_stock_future' AND CAST(pms.meta_value AS SIGNED) > 0) ) ";
            endif;
            if(isset($filter_variation["pa_color"]) && count($filter_variation["pa_color"])>0):
                $sql.=" INNER JOIN wp_postmeta pm1 ON p.ID=pm1.post_id  AND (pm1.meta_key='attribute_pa_color' AND pm1.meta_value IN ('".implode("','",$filter_variation["pa_color"])."')) ";
            endif;
            if(isset($filter_variation["meta_lob"]) && count($filter_variation["meta_lob"])>0):
                $sql.=" INNER JOIN wp_postmeta pm2 ON p.ID=pm2.post_id  AND (pm2.meta_key='lob' AND pm2.meta_value IN ('".implode("','",$filter_variation["meta_lob"])."')) ";
            endif;
            $sql.=" WHERE 1=1 
            AND p.post_parent IN (".implode(",",$ids_posts).")
            AND p.post_status IN ('publish') 
            GROUP BY p.post_parent";
            //echo $sql;
            $r = $wpdb->get_results($sql,ARRAY_A);
            $posts_id=count($r)>0?array_column($r,"post_parent"):array(0);
    
            $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post__in' => $posts_id
            );
            //$args = count($extra_args)>0? array_merge($args,$extra_args):$args;
            $loop = new WP_Query($args);
    
            return $loop;
        }else{
            return null;
        }
    }

    function export_xlsx(){
        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        set_time_limit(120);
        $products=[];

        $url_image = get_site_url()."/wp-content/uploads/";

        $ids=array();
        $posts=$this->main_products_get($ids);
        $u =  wp_get_current_user();
        $rol = isset($u->roles[0])?$u->roles[0]:array();
        if ($posts) {
            $count=0;
            foreach ($posts as $key => $main_product) {
                $sql_variaciones="SELECT a.*, pm1.meta_value thumbnail_id, pm2.meta_value image ,
                (SELECT group_concat(concat(b.meta_key,'||',b.meta_value) SEPARATOR '///')  FROM wp_postmeta b WHERE b.post_id=a.ID ) metas
                FROM wp_posts a 
                LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=a.ID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
                LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attached_file')
                WHERE a.post_status IN ('publish')
                AND a.post_parent=".$main_product->ID;
                $variaciones = $this->db->get_results($sql_variaciones);



                if(is_array($variaciones) && count($variaciones)>0){

                    //$main_product = wc_get_product( $value->ID );
                    $sql_main_atts="SELECT tt.taxonomy,tt.term_id,t.name,t.slug,tt.parent
                    FROM wp_term_relationships tr
                    INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id
                    INNER JOIN wp_terms t ON tt.term_id=t.term_id
                    WHERE tr.object_id=".$main_product->ID ;
    
                    $main_product_atts=$this->db->get_results($sql_main_atts, ARRAY_A);
                    $cats=array();
                    $main_atts=array();
                    if(is_array($main_product_atts)){
                        foreach ($main_product_atts as $item) {
                           if($item["taxonomy"] == "product_cat"){
                            $cats[$item["term_id"]] = $item;
                           }
                           if( substr($item["taxonomy"],0,3) == "pa_" ){
                               $main_atts[$item["taxonomy"]] = $item["name"];
                           }
                        }
                    }
                    array_multisort(
                        array_column($cats, 'parent'),
                        array_column($cats, 'term_id'),
                        $cats
                    );
                   // $categories = $main_product->get_categories();//get_the_terms($value->ID, 'product_cat');
                    $cats=array_reverse($cats);
                  
                    $category = isset($cats[1])?$cats[1]["name"]:"";
                    $sub_category = isset($cats[0])?$cats[0]["name"]:"";
                    foreach ($variaciones as $key => $var) {
                     
                        $metas_variation = explode("///",$var->metas);
                        $metas_variation = array_map(function($row){
                            $f = explode("||",$row);
                            
                            return array("key"=>$f[0],"value"=>$f[1]);
                        },$metas_variation);
                        $metas_variation=array_column($metas_variation,"value","key");
                      
                        $color = preg_replace('/[0-9]+/', '',str_replace("Color: ","",$var->post_excerpt));
    
                        $variation_id = $var->ID;
                        $tmp=explode('.', $var->image);
                        $extension = end($tmp);
                        $image = (str_replace(".".$extension,"-150x150.".$extension,$var->image));//$base_url
                        //$_product =  wc_get_product( $variation_id);
    
                        $item=array();
                        //$item["id"] = $value->ID;
                        $item["Image"] = $image;
    
                        $item['Product SKU'] =  isset($metas_variation["_sku"])?$metas_variation["_sku"]:"";
    
                        $item['Product Title'] =  $var->post_title;// . " - " . $color ;
    
                        $item['Color'] =  $color ;
    
                        $item['Season'] =  isset($main_atts["pa_season"])?$main_atts["pa_season"]:"";


                        $price=isset($metas_variation["_price"])?$metas_variation["_price"]:0;
                        if($rol!="administrator"){
                            $price_ = role_price_get_by_id($variation_id,$rol);
                            $price = $price_!==null?$price_:$price;
                        }
                        
                        $item['Price'] = $price;
    
                        $item['Division'] =  "";
    
                        $item['Brand'] =  isset($main_atts["pa_brand"])?$main_atts["pa_brand"]:""; 
    
                        $item['Departament'] =  "";
    
                        $item['Category'] =  $category;
                        $item['Subcategory'] =  $sub_category;
    
                        $item['totalprice'] = 0;
                        $item['Stok'] =  isset($metas_variation["_stock"]) ? $metas_variation["_stock"]:0;
    
                        $item['Qty'] =  0;
    
                        //$item['units_per_pack'] =  isset($metas_variation["size_box_qty1"]) ? $metas_variation["size_box_qty1"]:"";

                        
                        $total_units_per_pack=0;
                        for($i=1;$i<=10;$i++){
                            if(isset($metas_variation["custom_field".$i]) && $metas_variation["custom_field".$i]!=""){
                                $total_units_per_pack+=isset($metas_variation["size_box_qty".$i])?(int)$metas_variation["size_box_qty".$i]:0;
                            }
                        }
                        $item['units_per_pack'] =$total_units_per_pack;

    
                        $item['meta_custom_1'] =  isset($metas_variation["custom_field1"]) ? $metas_variation["custom_field1"]:"";
                        $item['meta_custom_2'] =  isset($metas_variation["custom_field2"]) ? $metas_variation["custom_field2"]:"";
                        $item['meta_custom_3'] =  isset($metas_variation["custom_field3"]) ? $metas_variation["custom_field3"]:"";
                        $item['meta_custom_4'] =  isset($metas_variation["custom_field4"]) ? $metas_variation["custom_field4"]:"";
                        $item['meta_custom_5'] =  isset($metas_variation["custom_field5"]) ? $metas_variation["custom_field5"]:"";
                        $item['meta_custom_6'] =  isset($metas_variation["custom_field6"]) ? $metas_variation["custom_field6"]:"";
                        $item['meta_custom_7'] =  isset($metas_variation["custom_field7"]) ? $metas_variation["custom_field7"]:"";
                        $item['meta_custom_8'] =  isset($metas_variation["custom_field8"]) ? $metas_variation["custom_field8"]:"";
                        $item['meta_custom_9'] =  isset($metas_variation["custom_field9"]) ? $metas_variation["custom_field9"]:"";
                        $item['meta_custom_10'] =  isset($metas_variation["custom_field10"]) ? $metas_variation["custom_field10"]:"";
    
    
                        $products[] = $item;
                    }
                }


            }
        }
        $file=$this->build_excel_catalog( $products);
        $json["error"] = 0;
        $json["download"] = get_site_url()."/wp-admin/admin-ajax.php?action=download_xlsx&file=".$file;
        echo json_encode($json);
        die();
    }
    function build_excel_catalog($data){
   
        $path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
        
    
        $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';
    
    
        define('SITEPATH', str_replace('\\', '/', $path1));
    
        $dataMainHeader = array('Image','SKU', 'Name', 'Color', 'Season', 'Regular Price', 'Division', 'Licence', 'Department','Category','Subcategory','Total Price (USD)','Stock','Qty','Units per pack', 'Meta: custom_field1','Meta: custom_field2','Meta: custom_field3','Meta: custom_field4','Meta: custom_field5','Meta: custom_field6','Meta: custom_field7','Meta: custom_field8','Meta: custom_field9','Meta: custom_field10');
    
        require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';
    
            $objPHPExcel = new PHPExcel(); 
    
            $objPHPExcel->getProperties()
                ->setCreator("Fexpro")
                ->setLastModifiedBy("Fexpro")
                ->setTitle("Products")
                ->setSubject("Products");
    
            // Set the active Excel worksheet to sheet 0
    
            $objPHPExcel->setActiveSheetIndex(0); 
    
    
          $objPHPExcel->getActiveSheet()->setAutoFilter('A1:O1');
          $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray(
                          array(
                              'fill' => array(
                                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                  'color' => array('rgb' => 'CCCCCC')
                              )
                          )
                      );
    
            // Initialise the Excel row number
    
             
    
            // Build headers
            $j=0;
            foreach( $dataMainHeader as $i => $row )
            {
    
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(($i),1,$row);
                $objPHPExcel->getActiveSheet()
                ->getColumnDimensionByColumn($i+1)
                ->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(($i),1)->getFont()->setBold(true);
               
    
            }  
    
            $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(14);
    
            $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(6);
    
    
            // Build cells
    
            $rowCount = 0;
    
            while( $rowCount < count($data) ){ 
    
                $cell = $rowCount+2;
    
                $column=0;
                foreach( $data[$rowCount] as $key => $value ) {
                    $columnChar=PHPExcel_Cell::stringFromColumnIndex((string)$column);
                    //$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 
    
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$cell)->applyFromArray(
                        array(
                            'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('rgb' => '000000')
                                )
                            )
                        )
                    );
    
                    $objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(75);
                    switch ($key) {
                        case 'Image':
                            $file = $upload_path.$value;
                            if (file_exists($file)) {
    
                                $objDrawing = new PHPExcel_Worksheet_Drawing();
                                $objDrawing->setName('Product image');
                                $objDrawing->setDescription('Product image');
    
    
                                //Path to signature .jpg file
    
                                $signature = $file;
    
                                $objDrawing->setPath($signature);
    
                                $objDrawing->setOffsetX(5);                     //setOffsetX works properly
    
                                $objDrawing->setOffsetY(10);                     //setOffsetY works properly
    
                                $objDrawing->setCoordinates($columnChar.$cell);             //set image to cell 
    
                                $objDrawing->setHeight(80);                     //signature height  
    
                                //$objDrawing->getHyperlink()->setUrl('http://www.google.com');
    
                                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 
                              
                                //$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($column,$cell)->getHyperlink()->setUrl('http://www.google.com');
                            }
                            break;
                        default:
    
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column,$cell, $value); 
    
                            break;
    
                    }
                if($columnChar=="L"){
                   $objPHPExcel->getActiveSheet()->setCellValue($columnChar.$cell, "=IF(N$cell=\"\",0,N$cell)*F$cell*O$cell"); 
                   $objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->getNumberFormat()->setFormatCode('0.00'); 
    
                   $objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->applyFromArray(
                          array(
                              'fill' => array(
                                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                  'color' => array('rgb' => 'DDEBF7')
                              )
                          )
                      );
                }
                if($columnChar=="N"){
               
    
                   $objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->applyFromArray(
                          array(
                              'fill' => array(
                                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                  'color' => array('rgb' => 'ED7D31')
                              )
                          )
                      );
                }
        
                $column++;
                }     
                $rowCount++; 
            }  
    
        @ob_clean();
        /*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export1.xlsx"'); 
        header('Cache-Control: max-age=0');*/
    
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->setPreCalculateFormulas(true);
            
        $tmp_name=time();
        $objWriter->save(WI_PLUGIN_PATH."/tmp/".$tmp_name); 
        return $tmp_name;

    }

    function download_xlsx(){
        $file = isset($_GET["file"])?(int)$_GET["file"]:0;
        if(file_exists(WI_PLUGIN_PATH."/tmp/".$file)){
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="export.xlsx"'); 
            header('Cache-Control: max-age=0');
            $binary=file_get_contents(WI_PLUGIN_PATH."/tmp/".$file);
            print($binary);
            @unlink(WI_PLUGIN_PATH."/tmp/".$file);
            die();
        }else{
            header("HTTP/1.0 404 Not Found");
            die();
        }
    }

    function qep_add_to_cart(){

        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
        $quantity = empty($_POST['quantity']) ? 0 : wc_stock_amount($_POST['quantity']);
        $quantity_future = empty($_POST['quantity_future']) ? 0 : wc_stock_amount($_POST['quantity_future']);

        $variation_id = isset($_POST['variation_id'])?(int)$_POST['variation_id']:0;
       
     
    

        $product_status = get_post_status($product_id);

        //$product =  wc_get_product( $variation_p->ID);
        $product_metas = get_post_meta($variation_id);
        $pass_available=false;
        $pass_future=false;
        //$passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        if($quantity>0){
            $message="";
            $pass_available = $this->validate_stock_add_to_cart($product_id,$variation_id,"available",$quantity,$message);
            if(!$pass_available){
                $json["error"] = 1;
                $json["message"] = $message;
                echo wp_send_json($json);
                wp_die();
            }
        }
        if($quantity_future>0){
            $message="";
           
            $pass_future = $this->validate_stock_add_to_cart($product_id,$variation_id,"future",$quantity_future,$message);
            
            if(!$pass_future){
                $json["error"] = 1;
                $json["message"] = $message;
                echo wp_send_json($json);
                wp_die();
            }
        }
        $added_once=false;
        if($quantity>0 && $pass_available){
            $data_extra=array();
            WC()->cart->add_to_cart($product_id,$quantity,$variation_id,null,$data_extra);
            $added_once=true;
        }
        if($quantity_future>0 && $pass_future){
            $data_extra=array();
            $data_extra["type_stock"] = "future";
            WC()->cart->add_to_cart($product_id,$quantity_future,$variation_id,null,$data_extra);
            $added_once=true;
        }
        if($added_once){
            $json["error"] = 0;
            $json["message"] = "Products added to cart";
        }else{
            $json["error"] = 1;
            $json["message"] = "No quantity selected";
        }
        
        echo wp_send_json($json);
        wp_die();

    }

    function validate_stock_add_to_cart($product_id,$variation_id,$type_stock="available",$qty=1,&$message=""){
        $items= WC()->cart->get_cart();
        $product_metas = get_post_meta($variation_id);
     
        $stock_available = isset($product_metas["_stock_present"][0])?(int)$product_metas["_stock_present"][0]:0;
        $stock_future = isset($product_metas["_stock_future"][0])?(int)$product_metas["_stock_future"][0]:0;

        $_stock = $type_stock=="available"? $stock_available: $stock_future;
        $passed=true;
       
        foreach ($items as $key => $item_cart) {
            $item_type_stock =  isset($item_cart["type_stock"])?$item_cart["type_stock"]:"available";
        
            if($item_cart["product_id"]==$product_id && $item_cart["variation_id"]==$variation_id && $item_type_stock == $type_stock){
                $cart_cant = $item_cart["quantity"];
             
                if($cart_cant>0 && ($cart_cant + $qty) > $_stock){
                    $message="You cannot add that amount to the cart — we have ".$_stock." in stock ".$type_stock." and you already have ".$cart_cant." in your cart";
                    $passed=false;
                    break;
                }
                if( $qty> $_stock){
                    $message="You cannot add that amount to the cart — we have ".$_stock." in stock ".$type_stock."";
                    $passed=false;
                    break;
                }
                break;
            }
        }
       
        return $passed;
    }

    function qep_add_to_cart_bulk(){
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $post = json_decode(file_get_contents("php://input"),true);
       /* echo "<pre>";
        print_r($post);
        echo "</pre>";
        wp_die();*/

        $product_id = (int)$post['product_id'];
        $cart=isset($post["cart"]) && count($post["cart"])>0 ? $post["cart"] : null;

        if($cart!=null){
            $product_status = get_post_status($product_id);

            foreach ($cart as $key => $item) {
                $quantity = isset($item["qty"])? (int)$item["qty"] : 0;
                $quantity_future = isset($item["qty_future"])? (int)$item["qty_future"] : 0;

                $variation_id = $key;
                $variation = wc_get_product($variation_id);
                $product_metas = get_post_meta($variation_id);
                $pass_available=false;
                $pass_future=false;
                
                if($quantity>0){
                    $message="";
                    $pass_available = $this->validate_stock_add_to_cart($product_id,$variation_id,"available",$quantity,$message);
                    if(!$pass_available){
                        wc_add_notice( $message ,"error");
                    }
                }
                if($quantity_future>0){
                    $message="";
                
                    $pass_future = $this->validate_stock_add_to_cart($product_id,$variation_id,"future",$quantity_future,$message);
                    
                    if(!$pass_future){
                        wc_add_notice( $message ,"error");
                    }
                }
                $added_once=false;
                if($quantity>0 && $pass_available){
                    $data_extra=array();
                    WC()->cart->add_to_cart($product_id,$quantity,$variation_id,null,$data_extra);
                    $added_once=true;
                    $message='<b>"'.$variation->get_formatted_name().'"</b> has been added to your cart.';
                    wc_add_notice( $message );
                }
                if($quantity_future>0 && $pass_future){
                    $data_extra=array();
                    $data_extra["type_stock"] = "future";
                    WC()->cart->add_to_cart($product_id,$quantity_future,$variation_id,null,$data_extra);
                    $added_once=true;
                    $message='<b>"'.$variation->get_formatted_name().'"</b> has been added to your cart.';
                    wc_add_notice( $message );
                }
                if($added_once){
                    $json["error"] = 0;
                    $json["message"] = "Products added to cart";
                }else{
                    $json["error"] = 1;
                    $json["message"] = "No quantity selected";
                }
            }
        }else{
            $json["error"] = 1;
            $json["message"] = "No quantity selected";
        }


        
        
        echo wp_send_json($json);
        wp_die();

    }

    function qep_view_cart(){
        /*global $wp_filter;
        echo "<pre>";
        print_r($wp_filter["woocommerce_single_product_summary"]);
        echo "</pre>";
        exit;*/
        $items= WC()->cart->get_cart();
        echo json_encode($items);
        die();
    }
        
}
?>