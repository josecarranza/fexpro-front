<?php 
class WiQuickEasyPurchase{
    public $db;
    public $SLUG_MAIN_CAT = "stock-inmediato";
    public $only_cats     = array(4595,4104,1829,8661,8657,5826);
    public $SLUG_PRESALE ="presale";
    //public $cats_presale = array(5935,5934,5932,5933,6372);//5935 - BOYS , 5934 - GIRLS , 5932 - MENS, 5933 - WOMENS, 6372 - KIDS
    public $cats_presale = array(6372,7884,7912);  //6372 kids-presale,7884 men,7912 women
    public $is_presale=false;
    public $BASIC_CAT = "core";
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

        add_action('wp_ajax_qep_export_cart', array($this,"qep_export_cart"));
        add_action('wp_ajax_nopriv_qep_export_cart', array($this,"qep_export_cart"));
        
        
        //add_filter( 'woocommerce_add_to_cart_validation', array($this,'woocommerce_add_cart_stock_future'));

        //add_action( 'woocommerce_update_cart_action_cart_updated', 'on_action_cart_updated', 20, 1 );
    }
    
    function shortcode_wi_quick_easy_purchase($atts=array()) {
        //$is_presale=isset($atts["version"]) && $atts["version"]==2?true:false;
        $is_presale=((!isset($_GET["is_presale"]) && !isset($_GET["is_stock"])) || isset($_GET["is_presale"]) && $_GET["is_presale"]==1)?true:false;
        if($is_presale){
            //$this->SLUG_MAIN_CAT=$this->SLUG_PRESALE;
            //$this->only_cats =$this->cats_presale;
        }


        //$categories = $this->filter_categories();
        
        $brands         = $this->filter_brands($is_presale);
        $lob            = $this->filter_lob();
        $delivery_dates = $this->filter_delivery_date($is_presale);
        $genders        = $this->filter_gender($is_presale);
        $groups         = $this->filter_group($is_presale);
        $products_type_apparel  = $this->filter_product_type($is_presale,"apparel");
        $products_type_accesor  = $this->filter_product_type($is_presale,"accesories");
        $basics         = $this->filter_basics();

        $collections = $this->filter_collections($is_presale,$brands);
        
        $stock = isset($_GET["stock"])?preg_replace("/[^0-9\-]+/",'',$_GET["stock"]):"";

        $current_filters = array();
        $current_filters["pa_brand"] = isset($_GET["pa_brand"])?explode(",",$_GET["pa_brand"]):array();
        $current_filters["pa_brand"] = array_map(function($item){ return addslashes($item);},$current_filters["pa_brand"]);

        $current_filters["meta_lob"] = isset($_GET["meta_lob"])?explode(",",$_GET["meta_lob"]):array();
        $current_filters["meta_lob"] = array_map(function($item){ return addslashes($item);},$current_filters["meta_lob"]);

        $current_filters["meta_delivery_date"] = isset($_GET["meta_delivery_date"])?explode(",",$_GET["meta_delivery_date"]):array();
        $current_filters["meta_delivery_date"] = array_map(function($item){ return addslashes($item);},$current_filters["meta_delivery_date"]);

        $current_filters["pa_gender"] = isset($_GET["pa_gender"])?explode(",",$_GET["pa_gender"]):array();
        $current_filters["pa_gender"] = array_map(function($item){ return addslashes($item);},$current_filters["pa_gender"]);

        $current_filters["pa_group"] = isset($_GET["pa_group"])?explode(",",$_GET["pa_group"]):array();
        $current_filters["pa_group"] = array_map(function($item){ return addslashes($item);},$current_filters["pa_group"]);

        $current_filters["products_type"] = isset($_GET["products_type"])?explode(",",$_GET["products_type"]):array();
        $current_filters["products_type"] = array_map(function($item){ return addslashes($item);},$current_filters["products_type"]);

        $current_filters["pa_only_basics"] = isset($_GET["pa_only_basics"])?explode(",",$_GET["pa_only_basics"]):array();
        $current_filters["pa_only_basics"] = array_map(function($item){ return addslashes($item);},$current_filters["pa_only_basics"]);

        $current_filters["pa_collection"] = isset($_GET["pa_collection"])?explode(",",$_GET["pa_collection"]):array();
        $current_filters["pa_collection"] = array_map(function($item){ return addslashes($item);},$current_filters["pa_collection"]);

        $current_filters["product_type_apparel"] = (isset($_GET["product_type_apparel"]) && $_GET["product_type_apparel"]!="")?
        explode(",",preg_replace("/[^a-zA-Z0-9\-,_\+ ]+/", "", trim($_GET["product_type_apparel"]))):[];
        
        $current_filters["product_type_accesories"] = (isset($_GET["product_type_accesories"]) && $_GET["product_type_accesories"]!="")?
        explode(",",preg_replace("/[^a-zA-Z0-9\-,_\+ ]+/", "", trim($_GET["product_type_accesories"]))):[];
 


        $is_submit = isset($_GET["submit"])?1:0;

        wp_enqueue_style('wi-qep-styles', WI_PLUGIN_URL. 'assets/css/quick-easy-purchase-styles.css?v=2.1', array(),false, false);
        ob_start();
        include(WI_PLUGIN_PATH."/shortcodes/view-quick-easy-purchase.php");
        $html = ob_get_clean();
        return $html;
    }

    function filter_categories($is_presale=false){
        

        $only_cats=$this->only_cats;
        
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
    function filter_brands($is_presale=false){
        if(!isset($_GET["product_cat"]) || $_GET["product_cat"]==""){
            $cat_in =array($this->SLUG_MAIN_CAT);
            if($is_presale){
                $cat_in =array($this->SLUG_PRESALE);
            }
        }else{
            $cat_in = explode(",",$_GET["product_cat"]);
            $cat_in = array_map(function($item){ return $item!=""?addslashes($item):false;},$cat_in);
            $cat_in=array_filter($cat_in);
            if(count($cat_in)==0){
                $cat_in =array($this->SLUG_MAIN_CAT);
                if($is_presale){
                    $cat_in =array($this->SLUG_PRESALE);
                }
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


        return $r;
    }
    function filter_collections($is_presale=false,$brands=[]){

        $cats = $this->only_cats;
        if($is_presale){
            $cats = $this->cats_presale;
        }
        $user_collections=$this->get_user_collections();
        $_user_collec = array_column($user_collections,"collection");
        $slug_brands = array_column($brands,"value");
        $append_sql="";
        if($is_presale && count($_user_collec)>0){
            $append_sql=" AND t3.slug IN ('".implode("','",$_user_collec)."')";
        }
        $sql="SELECT  t3.name text, t3.slug value, group_concat(distinct concat(t2.slug,'||',t2.name)) brand, group_concat(distinct concat(t4.slug,'||',t4.name)) department
        FROM wp_term_relationships tr
        INNER JOIN wp_terms t ON tr.term_taxonomy_id=t.term_id
        INNER JOIN wp_term_relationships tr2 ON tr.object_id=tr2.object_id
        INNER JOIN wp_terms t2 ON tr2.term_taxonomy_id=t2.term_id AND t2.slug IN ('".implode("','",$slug_brands)."')
        INNER JOIN (
            wp_term_relationships tr3 
            INNER JOIN wp_term_taxonomy tt ON tr3.term_taxonomy_id=tt.term_taxonomy_id AND tt.taxonomy='pa_collection'
            INNER JOIN wp_terms t3 ON tt.term_id=t3.term_id ".$append_sql."
        ) ON tr2.object_id=tr3.object_id
        INNER JOIN (
            wp_term_relationships tr4 
            INNER JOIN wp_term_taxonomy tt4 ON tr4.term_taxonomy_id=tt4.term_taxonomy_id AND tt4.taxonomy='product_cat'
            INNER JOIN wp_terms t4 ON tt4.term_id=t4.term_id AND t4.term_id IN (".implode(",", $cats).")
        ) ON tr2.object_id=tr4.object_id
        GROUP BY t3.slug
        ORDER BY t3.name";
        $r=$this->db->get_results($sql,ARRAY_A);

        $current_filters = array();
        $current_filters["pa_collection"] = isset($_GET["pa_collection"])?explode(",",$_GET["pa_collection"]):array();
        $current_filters["pa_collection"] = array_map(function($item){ return addslashes($item);},$current_filters["pa_collection"]);

        foreach ($r as $key => $value) {
            //$r[$key]["checked"] = in_array($value["value"],$current_filters["pa_collection"]);
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
    function filter_delivery_date($is_presale=false){

        $current_filters = array();
        $current_filters["meta_delivery_date"] = isset($_GET["meta_delivery_date"])?explode(",",$_GET["meta_delivery_date"]):array();
        $current_filters["meta_delivery_date"] = array_map(function($item){ return addslashes($item);},$current_filters["meta_delivery_date"]);
        if(!$is_presale){
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
        }else{
           /* $sql="SELECT * FROM (
                SELECT  IFNULL(if(SUBSTRING(pm.meta_value,3,1)='/',(STR_TO_DATE(pm.meta_value, '%d/%m/%Y')),(STR_TO_DATE(pm.meta_value, '%Y/%m/%d'))),(STR_TO_DATE(pm.meta_value, '%m/%d/%Y'))) delivery_presale  FROM wp_postmeta pm WHERE pm.meta_key = 'presale_delivery_date' AND pm.meta_value!=''
                ) tmp
                WHERE tmp.delivery_presale > '".date("Y-m-d")."'
                GROUP BY tmp.delivery_presale
                ORDER BY tmp.delivery_presale ASC";
            $r=$this->db->get_results($sql);
            $months=array();
            foreach($r as $t){
                $tt=strtotime($t->delivery_presale);
                $tmp_d=date("Y-m",$tt);
                $tmp_str = date("F",$tt).", ".date("Y",$tt);
                $months[]=array("value"=>$tmp_d,"text"=>strtoupper($tmp_str),"checked"=>(in_array($tmp_d,$current_filters["meta_delivery_date"])));
            }
            */

            $sql="SELECT t.* , tt.description
            FROM wp_term_taxonomy tt
            INNER JOIN wp_terms t ON tt.term_id=t.term_id
            INNER JOIN wp_term_relationships c ON tt.term_taxonomy_id=c.term_taxonomy_id
            WHERE tt.taxonomy='pa_date'
            AND c.object_id IN(
		        SELECT p.ID FROM wp_posts p
		        INNER JOIN wp_term_relationships tr ON p.ID=tr.object_id 
		        INNER JOIN wp_terms tt ON tr.term_taxonomy_id=tt.term_id AND tt.slug IN ('".$this->SLUG_PRESALE."')
		        WHERE p.post_status IN ('publish')
		        )
            GROUP BY t.name
            ORDER BY t.name";
            $r=$this->db->get_results($sql);
            foreach($r as $t){
                $months[]=array("value"=>$t->slug,"text"=>$t->name.($t->description!=""?" (".$t->description.")":""),"checked"=>(in_array($t->slug,$current_filters["meta_delivery_date"])));
            }
        }
        return $months;
    }
    function filter_gender(){
        $gender=array(array("value"=>"men","text"=>"MEN"),array("value"=>"women","text"=>"WOMEN"),array("value"=>"kids","text"=>"KIDS")); //,array("value"=>"unisex","text"=>"UNISEX")

        $current_filters = array();
        $current_filters["pa_gender"] = isset($_GET["pa_gender"])?explode(",",$_GET["pa_gender"]):array();
        $current_filters["pa_gender"] = array_map(function($item){ return addslashes($item);},$current_filters["pa_gender"]);

        foreach ($gender as $key => $value) {
            $gender[$key]["checked"] = in_array($value["value"],$current_filters["pa_gender"]);
        }
        return $gender;
    }
    function filter_group($is_presale=false){
        $only_cats=$this->only_cats;
        if($is_presale){
            $only_cats = $this->cats_presale;
        }

        $sql="SELECT * FROM (
        SELECT trim(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(NAME,'SPORTS ',''),'POP ',''),'GIRLS',''),'WOMENS',''),'MENS',''),'KIDS',''),'UNIVERISITIES','')) as name
        FROM wp_terms tt1
        INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
        WHERE tt2.taxonomy='product_cat' and (name!='' and name!='Uncategorized' ) AND tt2.count>0 
            AND  tt2.term_id IN (SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (".implode(",",$only_cats)."))
        ) tmp
        GROUP BY tmp.name
        ORDER BY NAME ASC";
        $r=$this->db->get_results($sql);
        
        $current_filters = array();
        $current_filters["pa_group"] = isset($_GET["pa_group"])?explode(",",$_GET["pa_group"]):array();
        $current_filters["pa_group"] = array_map(function($item){ return addslashes($item);},$current_filters["pa_group"]);
        
        $groups=array();
        foreach ($r as $value) {
            $item=array();
            $item["text"] = $value->name;
            $item["value"] = sanitize_title($value->name);
            $item["checked"] = in_array($item["value"],$current_filters["pa_group"]);
            $groups[]=$item;
        }
        return $groups;

    }
    function filter_product_type($is_presale=false,$type=""){
        $only_cats=$this->only_cats;
        if($is_presale){
            $only_cats = $this->cats_presale;
        }

        $sql="SELECT * FROM (
        SELECT trim(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(NAME,'SPORTS ',''),'POP ',''),'GIRLS',''),'WOMENS',''),'MENS',''),'KIDS',''),'UNIVERISITIES','')) as name
        FROM wp_terms tt1
        INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
        WHERE tt2.taxonomy='product_cat' and (name!='' and name!='Uncategorized' ) 
            AND  tt2.parent IN ( ";
            if($type==""){
                $sql.="SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (".implode(",",$only_cats).") ";
            }else{
                $sql.="SELECT tt3.term_id FROM wp_term_taxonomy tt3 INNER JOIN wp_terms t3 ON tt3.term_id=t3.term_id WHERE tt3.parent IN (".implode(",",$only_cats).") AND t3.name LIKE '%".$type."%' ";
            }
        $sql.="        )
        ) tmp
        GROUP BY tmp.name
        ORDER BY NAME ASC";
        $r=$this->db->get_results($sql);
        
        $current_filters = array();
        $current_filters["pa_product_type"] = isset($_GET["pa_product_type"])?explode(",",$_GET["pa_product_type"]):array();
        $current_filters["pa_product_type"] = array_map(function($item){ return addslashes($item);},$current_filters["pa_product_type"]);
        
        $products_type=array();
        foreach ($r as $value) {
            $item=array();
            $item["text"] = $value->name;
            $item["value"] = sanitize_title($value->name);
            $item["checked"] = in_array($item["value"],$current_filters["pa_product_type"]);
            $products_type[]=$item;
        }
        return $products_type;

    }
    function filter_basics(){
        $basics=array(array("value"=>"1","text"=>"ONLY BASICS"));

        $current_filters = array();
        $current_filters["pa_only_basics"] = isset($_GET["pa_only_basics"])?explode(",",$_GET["pa_only_basics"]):array();
        $current_filters["pa_only_basics"] = array_map(function($item){ return addslashes($item);},$current_filters["pa_only_basics"]);

        foreach ($basics as $key => $value) {
            $basics[$key]["checked"] = in_array($value["value"],$current_filters["pa_only_basics"]);
        }
        return $basics;
    }
    function get_user_collections(){
        $user_id=get_current_user_id();
        $user_config = get_user_meta($user_id,"wi_collection_config");
        $user_config = isset($user_config[0]) ? json_decode($user_config[0]):[];
        if(count($user_config)>0){
            global $wpdb;
            $r=$wpdb->get_results("select division,brand,department,collection from wi_collection_config WHERE id IN (".implode(",",$user_config).")",ARRAY_A);
            $user_config = $r;
        }
        return $user_config;
    }
    function products_get(){
    
        $per_page=20;
        $pag = isset($_GET["pag"])?(int)$_GET["pag"]:1;
        $limit_start = ($pag-1)*$per_page;
        $limit_end = ($limit_start+$per_page);
        $result=array();
        
        $products=[];

        $url_image = get_site_url()."/wp-content/uploads/";
        $site_url = get_site_url();
        $ids=array();
        $total_rows=0;
        $all_ids=null;
        $posts=$this->main_products_get($total_rows,true,$per_page,$all_ids);
        $u =  wp_get_current_user();
        $rol = isset($u->roles[0])?$u->roles[0]:array();

        $discounts = discount_by_rol_margin($u->ID);

        if ($posts) {
            $count=0;
            foreach ($posts as $key => $var) {
                $sql_variacion="SELECT a.*,pp.post_name parent_slug, pm1.meta_value thumbnail_id, pm2.meta_value image ,pm3.meta_value miniaturas,pm4.meta_value delivery_date,
                (SELECT group_concat(concat(b.meta_key,'||',b.meta_value) SEPARATOR '///')  FROM wp_postmeta b WHERE b.post_id=a.ID ) metas,
                ( SELECT group_concat(concat(tt.taxonomy,'||',t.name) SEPARATOR '///')
                        FROM wp_term_relationships tr
                        INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id
                        INNER JOIN wp_terms t ON tt.term_id=t.term_id
                        WHERE tr.object_id=a.post_parent
                ) attributes
                FROM wp_posts a 
                INNER JOIN wp_posts pp ON a.post_parent=pp.ID
                LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=a.ID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
                LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attached_file')
                LEFT JOIN wp_postmeta pm3 ON (pm3.post_id=pm1.meta_value AND pm3.meta_key='_wp_attachment_metadata')
                LEFT JOIN wp_postmeta pm4 ON (pm4.post_id=a.ID AND pm4.meta_key='delivery_date')
                WHERE a.post_status IN ('publish')
                AND a.ID=".$var->ID;
                //echo $sql_variacion;
                $var_data = $this->db->get_row($sql_variacion);

                $main_product_atts = explode("///",$var_data->attributes);
                $main_product_atts = array_map(function($row){
                    $f = explode("||",$row);
                    return array("key"=>$f[0],"value"=>$f[1]);
                },$main_product_atts);
                $cats = array_column(array_filter($main_product_atts,function($obj){ return $obj["key"]=="product_cat";}),"value");
                $main_atts=array_column($main_product_atts,"value","key");
                unset($main_product_atts);
                

                $metas_variation = explode("///",$var_data->metas);
                $metas_variation = array_map(function($row){
                    $f = explode("||",$row);
                    return array("key"=>$f[0],"value"=>$f[1]);
                },$metas_variation);
                $metas_variation=array_column($metas_variation,"value","key");
                
                $color = preg_replace('/[0-9]+/', '',str_replace("Color: ","",$var_data->post_excerpt));
                $pa_color = strtolower(str_replace("Color: ","",$var_data->post_excerpt));

                $miniaturas = unserialize($var_data->miniaturas);
                $miniaturas= isset($miniaturas["sizes"])? $miniaturas["sizes"]:"";
                $upload_path = $var_data->image!=""?explode("/",$var_data->image):"";
                if($upload_path!=""){
                    array_pop($upload_path);
                    $upload_path=implode("/",$upload_path);
                }

                $variation_id = $var->ID;

                $mini = isset($miniaturas["thumbnail"])?$miniaturas["thumbnail"]["file"]:(isset($miniaturas["medium"])?$miniaturas["medium"]["file"]:"");

                $delivery = "";
                if($var_data->delivery_date!=""){
                    $dd= str_replace("/","-",$var_data->delivery_date);
                    $dd= explode("-",$dd);
                    if(count($dd)==3){
                        $delivery=$dd[0] ."-".str_pad($dd[1], 2, "0", STR_PAD_LEFT)."-".str_pad($dd[2], 2, "0", STR_PAD_LEFT);
                    }
                }
                
                $item=array();
                $item["id"] = $variation_id;
                $item["main_id"] = $var->post_parent;
                $item["image"] = $mini!=""?(string)$url_image.$upload_path."/".$mini:"";

                $item['sku'] =  isset($metas_variation["_sku"])?$metas_variation["_sku"]:"";

                $item['product_title'] =  $var->post_title;// . " - " . $color ;
                $item["product_url"] =  $site_url . '/product/' . $var_data->parent_slug;

                $item['color'] =  $color ;
                $item["pa_color"] = $pa_color;
                $item["delivery_date"] = $delivery;

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

                $item["team"] = isset($metas_variation["product_team"])?$metas_variation["product_team"]:"";
                $item["logo_application"] = isset($metas_variation["logo_application"])?$metas_variation["logo_application"]:"";
                $item["composition"] = isset($metas_variation["pa_compositions"])?$metas_variation["pa_compositions"]:"";
                $item["collection"] = isset($main_atts["pa_collection"])?$main_atts["pa_collection"]:"";
                $item["is_basic"] = in_array("basics",$cats)?1:0;
                $item["cats"] = $cats;

                $item["image"] = str_replace("http://34.205.89.113","https://shop2.fexpro.com",$item["image"]);
                $products[] = $item;

            }
        }



        $result["products"] = $products;
        $result["total"] = $total_rows;
        $result["pages"] = $total_rows>0? ceil($total_rows/$per_page):0;
        $result["ids"] = $all_ids;
        print_r(json_encode($result));
        die();
    }

    function main_products_get(&$total_rows=0,$paginate=true,$_per_page=100,&$all_ids=null){

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $slug_category = isset($_GET["product_cat"]) ? trim($_GET["product_cat"]):"";
        $brand = isset($_GET["pa_brand"]) ? trim($_GET["pa_brand"]):"";
        $pag = isset($_GET["pag"])?(int)$_GET["pag"]:1;
        
        $user_collections = get_user_collections();

        $is_presale = isset($_GET["is_presale"]) && $_GET["is_presale"]==1?true:false;
        if($is_presale){
            $this->is_presale=true;
        }

        if($slug_category==""){
            $slug_category = array($this->SLUG_MAIN_CAT);
            if($is_presale){
                $slug_category = array($this->SLUG_PRESALE);
            }
            
        }else{
            $slug_category = explode(",",$slug_category);
        }
        
        $color = isset($_GET["pa_color"]) ? trim($_GET["pa_color"]):"";
        $lob = isset($_GET["meta_lob"]) ? trim($_GET["meta_lob"]):"";
        $delivery_date = isset($_GET["meta_delivery_date"]) ? preg_replace("/[^a-zA-Z0-9\-,_]+/", "",trim($_GET["meta_delivery_date"])):"";
        $gender = isset($_GET["pa_gender"]) ? preg_replace("/[^a-zA-Z0-9\-,_]+/", "",trim($_GET["pa_gender"])):"";

        $group = isset($_GET["pa_group"]) ? preg_replace("/[^a-zA-Z0-9\-,_]+/", "",trim($_GET["pa_group"])):"";
        $product_type_str = isset($_GET["pa_product_type"]) ? preg_replace("/[^a-zA-Z0-9\-,_]+/", "",trim($_GET["pa_product_type"])):"";

        $stock = isset($_GET["stock"]) ? preg_replace("/[^0-9\-]+/", "",trim($_GET["stock"])):"";

        $only_basics = isset($_GET["pa_only_basics"]) && $_GET["pa_only_basics"]==1?true:false;
        $collection = isset($_GET["pa_collection"]) ? preg_replace("/[^a-zA-Z0-9\-,_]+/", "",trim($_GET["pa_collection"])):"";
        
        $division="";
        $slug_division="";
        if($is_presale){

        
            if(count($user_collections) > 0){
                $_divis = array_unique(array_column($user_collections,"division"));
                
                $division=implode(",",$_divis);

            }

            if($division !=""){
                
                $sql="SELECT  t3.* 
                FROM  wp_terms t 
                INNER JOIN wp_term_taxonomy tt2 ON tt2.parent=t.term_id
                INNER JOIN wp_terms t2 ON tt2.term_taxonomy_id=t2.term_id
                INNER JOIN wp_term_taxonomy tt3 ON tt3.parent=t2.term_id
                INNER JOIN wp_terms t3 ON tt3.term_taxonomy_id=t3.term_id
                WHERE t.slug='".implode("','",$slug_category)."' AND t3.slug REGEXP '".(str_replace(",","|",$division))."' ";
                $slug_division =$this->db->get_results($sql,ARRAY_A);
                $slug_division =array_column($slug_division,"slug");
                
                $slug_division =implode(",",$slug_division);
                if($slug_division==""){
                    $slug_division="none404";
                }
                //echo $slug_division;
            }
            if(count($user_collections) > 0){
                $_deparments = array_unique(array_column($user_collections,"department"));
            

                if($gender!=""){
                    $gender_arr=explode(",",$gender);
                    $matches=array_intersect($gender_arr,$_deparments);
        
                    if(count($matches)==0){
                        $gender="none404";
                    }else{
                        $gender = implode(",",$matches);
                    }
                }else{
                    $gender=implode(",",$_deparments);
                }
            
            }
            

           

            if(count($user_collections) > 0){
                $_collection = array_unique(array_column($user_collections,"collection"));
        
                if($collection!=""){
                    $collection_arr=explode(",",$collection);
                    $matches=array_intersect($collection_arr,$_collection);
        
                    if(count($matches)==0){
                        $collection="none404";
                    }else{
                        $collection = implode(",",$matches);
                    }
                }else{
                    $collection=implode(",",$_collection);
                }
                //echo $collection;
            }
        }

        $apparel = isset($_GET["product_type_apparel"]) ? preg_replace("/[^a-zA-Z0-9\-,_\+ ]+/", "",trim($_GET["product_type_apparel"])):"";
        $accesories = isset($_GET["product_type_accesories"]) ? preg_replace("/[^a-zA-Z0-9\-,_\+ ]+/", "",trim($_GET["product_type_accesories"])):"";
        if($apparel!=""){
            $product_type_str.=($product_type_str!=""?",":"").$apparel;
        }
        if($accesories!=""){
            $product_type_str.=($product_type_str!=""?",":"").$accesories;
        }

        if($group!=""){
            $groups=$this->get_slugs_groups($group,$is_presale);
            $slug_category=$groups;
        }
      
        /*if($is_presale){
            $slug_category[]=$this->BASIC_CAT;
        }*/

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            //'paged' => $pag,
            'post_status' => array('publish'),
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => $slug_category,
                            'operator'        => 'IN',
                        )
                    )
                )
            ),
            'meta_query'=>array()
        );
        

        if($lob!=""){
            $lob_slugs=$this->get_slugs_lob($lob,$is_presale);
            $args['tax_query'][0][0][] = array(
                'taxonomy'        => 'product_cat',
                'field'           => 'slug',
                'terms'           =>  $lob_slugs,
                'operator'        => 'IN',
            );
        }
        if($product_type_str!=""){
            $product_type=$this->get_slugs_product_type($product_type_str,$is_presale);
         
            $slug_sub_categories=$product_type;
            $args['tax_query'][] = array(
                'taxonomy'        => 'product_cat',
                'field'           => 'slug',
                'terms'           =>  $slug_sub_categories,
                'operator'        => 'IN',
            );
        }
        if ($slug_division!="") {
            $filter_division = explode(',', $slug_division);
            $args['tax_query'][] = array(
                'taxonomy'        => 'product_cat',
                'field'           => 'slug',
                'terms'           =>  $filter_division,
                'operator'        => 'IN',
            );
        }

        if ($brand!="") {
            $filter_brand = explode(',', $brand);
            $args['tax_query'][] = array(
                'taxonomy'        => 'pa_brand',
                'field'           => 'slug',
                'terms'           =>  $filter_brand,
                'operator'        => 'IN',
            );
        }
        if ($collection!="") {
            $filter_collection = explode(',', $collection);
            $args['tax_query'][] = array(
                'taxonomy'        => 'pa_collection',
                'field'           => 'slug',
                'terms'           =>  $filter_collection,
                'operator'        => 'IN',
            );
        }
        if ($gender!="") {
            $filter_gender = explode(',', $gender);
            $args['tax_query'][] = array(
                'taxonomy'        => 'pa_gender',
                'field'           => 'slug',
                'terms'           =>  $filter_gender,
                'operator'        => 'IN',
            );
        }
        if($only_basics){
            $args['tax_query'][] = array(
                'taxonomy'        => 'product_cat',
                'field'           => 'slug',
                'terms'           => array("basics"),
                'operator'        => 'IN'
            );
        }

        if($is_presale){
            $args['tax_query'][0][1]["relation"]="AND";
            $args['tax_query'][0][1][] = array(
                'taxonomy'        => 'product_cat',
                'field'           => 'slug',
                'terms'           =>  array($this->BASIC_CAT),
                'operator'        => 'IN',
            );
            if($product_type_str!=""){
                $product_type=$this->get_slugs_product_type($product_type_str,false);
                $slug_sub_categories=$product_type;
                $args['tax_query'][0][1][] = array(
                    'taxonomy'        => 'product_cat',
                    'field'           => 'slug',
                    'terms'           =>  $slug_sub_categories,
                    'operator'        => 'IN',
                );
            }
            if($lob!=""){
                $lob_slugs=$this->get_slugs_lob($lob,false);
                $args['tax_query'][0][1][] = array(
                    'taxonomy'        => 'product_cat',
                    'field'           => 'slug',
                    'terms'           =>  $lob_slugs,
                    'operator'        => 'IN',
                );
            }
            if ($delivery_date!="") {
          
                $args['tax_query'][] = array(
                    'taxonomy'        => 'pa_date',
                    'field'           => 'slug',
                    'terms'           =>  explode(",",$delivery_date),
                    'operator'        => 'IN',
                );
            }
        }

        $u =  wp_get_current_user();
        $rol = isset($u->roles[0])?$u->roles[0]:array();

        $role_selected_data  = (array) get_option('afpvu_user_role_visibility');

        if(isset($role_selected_data[$rol]) && isset($role_selected_data[$rol]["afpvu_enable_role"]) && $role_selected_data[$rol]["afpvu_enable_role"] =="yes" && is_array($role_selected_data[$rol]["afpvu_applied_products_role"]) && count($role_selected_data[$rol]["afpvu_applied_products_role"])>0 ){
            $args["post__not_in"] = $role_selected_data[$rol]["afpvu_applied_products_role"];
        }
        
   
        $filter_variation=array();

        if(!$is_presale && $delivery_date!=""){
            $filter_variation["delivery_dates"] = explode(",",$delivery_date);
        }
        //if ($lob!="") {
        //    $filter_variation["meta_lob"] = explode(',', $lob);
       // }
        if($stock!=""){
            $_stock = explode("-",$stock);
            $min = (int)$_stock[0];
            $max = isset($_stock[1])?(int)$_stock[1]:0;
            $filter_variation["stock_min"] = $min;
            $filter_variation["stock_max"] = $max;
        }

        $extra_args=array();
        $extra_args["pag"] = $pag;
        $extra_args["per_pag"] = $_per_page;
        if($paginate==false){
            $extra_args["no_paginate"] = true;
        }
        // echo "<pre>";
        // print_r($args);
        // echo "</pre>";
        
        $loop = new WP_Query($args);
        //echo $loop->request;
        
        $loop = $this->filter_variations($loop,$filter_variation,$extra_args,$total_rows);
        
        if($loop!=null && $loop->have_posts()){
            //$ids_post = array_column($loop->posts,"ID");
            $all_ids=$loop->ids??null;
            return $loop->posts;
        }else{
            return null;
        }
    }
    function filter_variations($loop,$filter_variation=array(),$extra_args=array(),&$total_rows=0){
        if ($loop->have_posts()) {
            global $wpdb;
            $ids_posts=array_column($loop->posts,"ID");
            $delivery_date_meta_key="delivery_date";
            if($this->is_presale){
                $delivery_date_meta_key="presale_delivery_date";
            }
            //echo implode(",",$ids_posts);
            $delivery_dates =  isset($filter_variation["delivery_dates"])?$filter_variation["delivery_dates"]:array();

            $pos = array_search("now",$delivery_dates);
            $only_stock_present=$pos!==false?true:false;
            if($only_stock_present){
                unset($delivery_dates[$pos]);
            }

            $extra_field="";
            if(isset($filter_variation["stock_min"]) && $filter_variation["stock_min"]>0){
                $extra_field=" , (pm_s.meta_value*(SELECT SUM(meta_value) FROM wp_postmeta WHERE post_id=p.ID AND meta_key LIKE 'size_box_qty%')) stock ";
            }

            $select="SELECT p.post_parent, p.ID, p.post_title ".$extra_field." ";
            $sql="  FROM wp_posts p ";
           
            if($only_stock_present || count($delivery_dates)>0):
                $sql.=" INNER JOIN wp_postmeta pm ON p.ID = pm.post_id AND (";

                    if($only_stock_present){
                        $sql.=" (pm.meta_key = '_stock_present' AND CAST(pm.meta_value AS SIGNED) > 0) ";
                    }

                    if(count($delivery_dates)>0){
                        $jj=0;
                        foreach ($delivery_dates as  $dd) {
                                $_date = date("Y-m",strtotime($dd."-01"));
                                $sql.=((!$only_stock_present && $jj>0) || $only_stock_present ? "OR" :"")." (pm.meta_key = '".$delivery_date_meta_key."' AND DATE_FORMAT(CAST(pm.meta_value AS DATE),'%Y-%m') ='".$_date ."') ";
                                $jj++;
                        }
                    }
                $sql.=" ) ";
                if(count($delivery_dates)>0 && !$this->is_presale):
                    $sql.=" INNER JOIN wp_postmeta pms ON p.ID = pms.post_id AND  (pms.meta_key = '_stock_future' AND CAST(pms.meta_value AS SIGNED) > 0)  ";
                endif;

            else:
                if(!$this->is_presale){
                    $sql.=" INNER JOIN wp_postmeta pms ON p.ID = pms.post_id AND ( (pms.meta_key = '_stock_present' AND CAST(pms.meta_value AS SIGNED) > 0) OR (pms.meta_key = '_stock_future' AND CAST(pms.meta_value AS SIGNED) > 0) ) ";
                }
            endif;
            if(isset($filter_variation["pa_color"]) && count($filter_variation["pa_color"])>0):
                $sql.=" INNER JOIN wp_postmeta pm1 ON p.ID=pm1.post_id  AND (pm1.meta_key='attribute_pa_color' AND pm1.meta_value IN ('".implode("','",$filter_variation["pa_color"])."')) ";
            endif;
            if(isset($filter_variation["meta_lob"]) && count($filter_variation["meta_lob"])>0):
                $sql.=" INNER JOIN wp_postmeta pm2 ON p.ID=pm2.post_id  AND (pm2.meta_key='lob' AND pm2.meta_value IN ('".implode("','",$filter_variation["meta_lob"])."')) ";
            endif;

            if((isset($filter_variation["stock_min"]) && $filter_variation["stock_min"]>0) || isset($extra_args["orderbystock"])){
                $search_in =array();
                if($only_stock_present && count($delivery_dates)==0){
                    $search_in =array('_stock_present');
                }
                if(!$only_stock_present && count($delivery_dates)>0){
                    $search_in =array('_stock_future');
                }
                if((!$only_stock_present && count($delivery_dates)==0) || ($only_stock_present && count($delivery_dates)>0)){
                    $search_in =array('_stock_present','_stock_future');
                }
    
                $sql.=" INNER JOIN wp_postmeta pm_s ON p.ID=pm_s.post_id AND (pm_s.meta_key IN ('".implode("','",$search_in)."')) ";
            }

            $sql.=" WHERE 1=1 
            AND p.post_parent IN (".implode(",",$ids_posts).")
            AND p.post_status IN ('publish') 
            ";
            $groupby=" GROUP BY p.ID  ORDER BY p.post_title ASC";
            if(isset($filter_variation["stock_min"]) && $filter_variation["stock_min"]>0){
                $groupby.=" HAVING stock >= ".$filter_variation["stock_min"];
                if(isset($filter_variation["stock_max"]) && $filter_variation["stock_max"]>0){
                    $groupby.=" AND stock <=".$filter_variation["stock_max"];
                }
            }

            //echo $sql;
            $r_total = $wpdb->get_row("select count(p.ID) total, GROUP_CONCAT(p.ID) ids ".$sql);
            //echo "select count(p.ID) total ".$sql;
            $total_rows = (int)$r_total->total??0;
            $ids = $r_total->ids??null;
            
            unset($r_total);
            $start=($extra_args["pag"]-1)*$extra_args["per_pag"];
            
            if(!isset($extra_args["no_paginate"])){
                $groupby.=" LIMIT ".$start.",".$extra_args["per_pag"];
            }
            
            //echo $sql;
            //$r = $wpdb->get_results($sql,ARRAY_A);
            $r = $wpdb->get_results($select.$sql.$groupby);
            // echo $select.$sql.$groupby;
            if(count($r)>0){
                $loop=new class{
                    public function have_posts(){ return true;}
                    public $posts;
                    public $post_count;
                    public $ids;
                };
                $loop->posts=$r;
                $loop->post_count=count($r);
                $loop->ids = $ids;
             
                return $loop;
            }else{
                return null;
            }

        }else{
            return null;
        }
    }

    function export_xlsx_old(){
        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        set_time_limit(120);
        $products=[];

        $url_image = get_site_url()."/wp-content/uploads/";

        $ids=array();
        $posts=$this->main_products_get($ids,false);
        $u =  wp_get_current_user();
        $rol = isset($u->roles[0])?$u->roles[0]:array();

        $discounts = discount_by_rol_margin($u->ID);

        if ($posts) {
            $count=0;
            foreach ($posts as $key => $var) {
                $sql_variacion="SELECT a.*,pp.post_name parent_slug, pm1.meta_value thumbnail_id, pm2.meta_value image ,pm3.meta_value miniaturas,pm4.meta_value delivery_date,
                (SELECT group_concat(concat(b.meta_key,'||',b.meta_value) SEPARATOR '///')  FROM wp_postmeta b WHERE b.post_id=a.ID ) metas,
                ( SELECT group_concat(concat(tt.taxonomy,'||',t.name) SEPARATOR '///')
                        FROM wp_term_relationships tr
                        INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id
                        INNER JOIN wp_terms t ON tt.term_id=t.term_id
                        WHERE tr.object_id=a.post_parent
                ) attributes
                FROM wp_posts a 
                INNER JOIN wp_posts pp ON a.post_parent=pp.ID
                LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=a.ID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
                LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attached_file')
                LEFT JOIN wp_postmeta pm3 ON (pm3.post_id=pm1.meta_value AND pm3.meta_key='_wp_attachment_metadata')
                LEFT JOIN wp_postmeta pm4 ON (pm4.post_id=a.ID AND pm4.meta_key='delivery_date')
                WHERE a.post_status IN ('publish')
                AND a.ID=".$var->ID;
                $var_data = $this->db->get_row($sql_variacion);
                global $cats;
                $cats=array();
                $main_product_atts = explode("///",$var_data->attributes);
                $main_product_atts = array_map(function($row){
                    $f = explode("||",$row);
                    if($f[0]=="product_cat"){
                        global $cats;
                        $cats[]=$f[1];
                    }
                    return array("key"=>$f[0],"value"=>$f[1]);
                },$main_product_atts);
                $main_atts=array_column($main_product_atts,"value","key");
             
                $metas_variation = explode("///",$var_data->metas);
                $metas_variation = array_map(function($row){
                    $f = explode("||",$row);
                    return array("key"=>$f[0],"value"=>$f[1]);
                },$metas_variation);
                $metas_variation=array_column($metas_variation,"value","key");
                
                $color = preg_replace('/[0-9]+/', '',str_replace("Color: ","",$var_data->post_excerpt));
                $pa_color = strtolower(str_replace("Color: ","",$var_data->post_excerpt));

                $miniaturas = unserialize($var_data->miniaturas);
                $miniaturas= isset($miniaturas["sizes"])? $miniaturas["sizes"]:"";
                $upload_path = $var_data->image!=""?explode("/",$var_data->image):"";
                if($upload_path!=""){
                    array_pop($upload_path);
                    $upload_path=implode("/",$upload_path);
                }

                $variation_id = $var->ID;

                $mini = isset($miniaturas["thumbnail"])?$miniaturas["thumbnail"]["file"]:(isset($miniaturas["medium"])?$miniaturas["medium"]["file"]:"");

                $delivery = "";
                if($var_data->delivery_date!=""){
                    $dd= str_replace("/","-",$var_data->delivery_date);
                    $dd= explode("-",$dd);
                    if(count($dd)==3){
                        $delivery=$dd[0] ."-".str_pad($dd[1], 2, "0", STR_PAD_LEFT)."-".str_pad($dd[2], 2, "0", STR_PAD_LEFT);
                    }
                }

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
                
                $sizes=array();
                $units_per_pack=0;
                for($i=1;$i<=10;$i++){
                    if(isset($metas_variation["custom_field".$i]) && $metas_variation["custom_field".$i]!=""){
                        $sizes[]=$metas_variation["custom_field".$i];
                        $units_per_pack+=isset($metas_variation["size_box_qty".$i])?(int)$metas_variation["size_box_qty".$i]:0;
                    }
                }
                $divisions=array("POP"=>"POP","SPO"=>"SPORT","UNI"=>"UNIVERSITY");
                $division = isset($metas_variation["lob"]) && isset($divisions[strtoupper($metas_variation["lob"])])?$divisions[strtoupper($metas_variation["lob"])] :"";

                $group = isset($cats[2])?$cats[2]:"";
                $prod_type = isset($main_atts["product_cat"]) ?$main_atts["product_cat"] :"";
                $item=array();
                //$item["id"] = $variation_id;
                //$item["main_id"] = $var->post_parent;
                $stock_present =  isset($metas_variation["_stock_present"]) ? (int)$metas_variation["_stock_present"]:0;
                $stock_future =  isset($metas_variation["_stock_future"]) ? (int)$metas_variation["_stock_future"]:0;

                $item["image"] = $mini!=""?(string)$upload_path."/".$mini:"";

                $item['sku'] =  isset($metas_variation["_sku"])?$metas_variation["_sku"]:"";

                $item['product_title'] =  $var->post_title;// . " - " . $color ;
                
                $item['price'] =  $price;

                $item['color'] =  $color ;

                $item['units_per_pack'] =$units_per_pack;
             
                $item["delivery_date"] = $stock_present>0?"IMMEDIATE":$delivery ;
                
                $item["available"] = $units_per_pack * ($stock_present>0?$stock_present:$stock_future);

                $item['Division'] =  $division;

                $item['brand'] =  isset($main_atts["pa_brand"])?$main_atts["pa_brand"]:""; 
                $item['gender'] =  isset($main_atts["pa_gender"])?$main_atts["pa_gender"]:"";

                $item['group'] = $group;
                $item['product'] = $prod_type;
                $item['size_chart'] = implode("-",$sizes);

                $item['qty'] = 0;
                $item["total_units"] = 0;
                $item["total_price"] = 0;

                $item["team"] = isset($metas_variation["product_team"])?$metas_variation["product_team"]:"";
                $item["logo_application"] = isset($metas_variation["logo_application"])?$metas_variation["logo_application"]:"";
                $item["composition"] = isset($metas_variation["pa_compositions"])?$metas_variation["pa_compositions"]:"";

                
                $products[] = $item;

                if($stock_present>0 && $stock_future>0){
                    $item["delivery_date"] = $delivery;
                    $item["available"] = $units_per_pack * $stock_future;
                    $products[] = $item;
                }

            }
        }
        //echo json_encode($products);
        //die();
        $file=$this->build_excel_catalog( $products);
        $json["error"] = 0;
        $json["download"] = get_site_url()."/wp-admin/admin-ajax.php?action=download_xlsx&file=".$file;
        echo json_encode($json);
        die();
    }
    function export_xlsx(){
        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        set_time_limit(120);
        $products=[];

        $url_image = get_site_url()."/wp-content/uploads/";

        $post = json_decode(file_get_contents("php://input"),true);

        $ids=array();
        //$posts = $this->main_products_get($ids,false);
        $ids   = $post["ids"];
        $is_presale = (int)$post["is_presale"]??0;

        $u =  wp_get_current_user();
        $rol = isset($u->roles[0])?$u->roles[0]:array();

        $discounts = discount_by_rol_margin($u->ID);

        if ($ids) {
            $count=0;
            foreach ($ids as $key => $variation_id) {
                $sql_variacion="SELECT a.*,pp.post_name parent_slug, pm1.meta_value thumbnail_id, pm2.meta_value image ,pm3.meta_value miniaturas,pm4.meta_value delivery_date,
                (SELECT group_concat(concat(b.meta_key,'||',b.meta_value) SEPARATOR '///')  FROM wp_postmeta b WHERE b.post_id=a.ID ) metas,
                ( SELECT group_concat(concat(tt.taxonomy,'||',t.name) SEPARATOR '///')
                        FROM wp_term_relationships tr
                        INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id
                        INNER JOIN wp_terms t ON tt.term_id=t.term_id
                        WHERE tr.object_id=a.post_parent
                ) attributes
                FROM wp_posts a 
                INNER JOIN wp_posts pp ON a.post_parent=pp.ID
                LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=a.ID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
                LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attached_file')
                LEFT JOIN wp_postmeta pm3 ON (pm3.post_id=pm1.meta_value AND pm3.meta_key='_wp_attachment_metadata')
                LEFT JOIN wp_postmeta pm4 ON (pm4.post_id=a.ID AND pm4.meta_key='delivery_date')
                WHERE a.post_status IN ('publish')
                AND a.ID=".$variation_id;
                $var_data = $this->db->get_row($sql_variacion);
                global $cats;
                $cats=array();
                $main_product_atts = explode("///",$var_data->attributes);
                $main_product_atts = array_map(function($row){
                    $f = explode("||",$row);
                    if($f[0]=="product_cat"){
                        global $cats;
                        $cats[]=$f[1];
                    }
                    return array("key"=>$f[0],"value"=>$f[1]);
                },$main_product_atts);
                $main_atts=array_column($main_product_atts,"value","key");
             
                $metas_variation = explode("///",$var_data->metas);
                $metas_variation = array_map(function($row){
                    $f = explode("||",$row);
                    return array("key"=>$f[0],"value"=>$f[1]);
                },$metas_variation);
                $metas_variation=array_column($metas_variation,"value","key");
                
                $color = preg_replace('/[0-9]+/', '',str_replace("Color: ","",$var_data->post_excerpt));
                $pa_color = strtolower(str_replace("Color: ","",$var_data->post_excerpt));

                $miniaturas = unserialize($var_data->miniaturas);
                $miniaturas= isset($miniaturas["sizes"])? $miniaturas["sizes"]:"";
                $upload_path = $var_data->image!=""?explode("/",$var_data->image):"";
                if($upload_path!=""){
                    array_pop($upload_path);
                    $upload_path=implode("/",$upload_path);
                }

              
                $mini = isset($miniaturas["thumbnail"])?$miniaturas["thumbnail"]["file"]:(isset($miniaturas["medium"])?$miniaturas["medium"]["file"]:"");

                $delivery = "";
                if($var_data->delivery_date!=""){
                    $dd= str_replace("/","-",$var_data->delivery_date);
                    $dd= explode("-",$dd);
                    if(count($dd)==3){
                        $delivery=$dd[0] ."-".str_pad($dd[1], 2, "0", STR_PAD_LEFT)."-".str_pad($dd[2], 2, "0", STR_PAD_LEFT);
                    }
                }

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
                
                $sizes=array();
                $units_per_pack=0;
                for($i=1;$i<=10;$i++){
                    if(isset($metas_variation["custom_field".$i]) && $metas_variation["custom_field".$i]!=""){
                        $sizes[]=$metas_variation["custom_field".$i];
                        $units_per_pack+=isset($metas_variation["size_box_qty".$i])?(int)$metas_variation["size_box_qty".$i]:0;
                    }
                }
                $divisions=array("POP"=>"POP","SPO"=>"SPORT","UNI"=>"UNIVERSITY");
                $division = isset($metas_variation["lob"]) && isset($divisions[strtoupper($metas_variation["lob"])])?$divisions[strtoupper($metas_variation["lob"])] :"";

                $group = isset($cats[2])?$cats[2]:"";
                $prod_type = isset($main_atts["product_cat"]) ?$main_atts["product_cat"] :"";
                $item=array();
                //$item["id"] = $variation_id;
                //$item["main_id"] = $var->post_parent;
                $stock_present =  isset($metas_variation["_stock_present"]) ? (int)$metas_variation["_stock_present"]:0;
                $stock_future =  isset($metas_variation["_stock_future"]) ? (int)$metas_variation["_stock_future"]:0;

                $item["image"] = $mini!=""?(string)$upload_path."/".$mini:"";

                $item['sku'] =  isset($metas_variation["_sku"])?$metas_variation["_sku"]:"";

                $item['product_title'] =  $var_data->post_title;// . " - " . $color ;
                
                $item['price'] =  $price;

                $item['color'] =  $color ;

                $item['units_per_pack'] =$units_per_pack;
             
                $item["delivery_date"] = $stock_present>0?"IMMEDIATE":$delivery ;
                
                $item["available"] = $is_presale==0?($units_per_pack * ($stock_present>0?$stock_present:$stock_future)):"";

                $item['Division'] =  $division;

                $item['brand'] =  isset($main_atts["pa_brand"])?$main_atts["pa_brand"]:""; 
                $item['gender'] =  isset($main_atts["pa_gender"])?$main_atts["pa_gender"]:"";

                $item['group'] = $group;
                $item['product'] = $prod_type;
                $item['size_chart'] = implode("-",$sizes);

                $item['qty'] = 0;
                $item["total_units"] = 0;
                $item["total_price"] = 0;

                $item["team"] = isset($metas_variation["product_team"])?$metas_variation["product_team"]:"";
                $item["logo_application"] = isset($metas_variation["logo_application"])?$metas_variation["logo_application"]:"";
                $item["composition"] = isset($metas_variation["pa_compositions"])?$metas_variation["pa_compositions"]:"";

                //$item["image"] = str_replace("http://34.205.89.113","https://shop2.fexpro.com",$item["image"]);
                
                $products[] = $item;

                if($is_presale==0 && ($stock_present>0 && $stock_future>0)){
                    $item["delivery_date"] = $delivery;
                    $item["available"] = $units_per_pack * $stock_future;
                    $products[] = $item;
                }

            }
        }
        //echo json_encode($products);
        //die();
        $file=$this->build_excel_catalog( $products,$is_presale);
        $json["error"] = 0;
        $json["download"] = get_site_url()."/wp-admin/admin-ajax.php?action=download_xlsx&file=".$file;
        echo json_encode($json);
        die();
    }
    function build_excel_catalog($data,$is_presale=0){
        error_reporting(0);
        ini_set('display_errors', 0);
   
        $path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
        
    
        $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';
    
    
        define('SITEPATH', str_replace('\\', '/', $path1));
    
        $dataMainHeader = array('Image','SKU', 'Name', 'Price','Color', 'Units per Pack', 'Delivery','Available', 'Division', 'Brand','Gender','Group','Product','Size Chart','Qty','Total Units','Total Price (USD)','Team','Logo application','Composition');
    
        require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';
    
            $objPHPExcel = new PHPExcel(); 
    
            $objPHPExcel->getProperties()
                ->setCreator("Fexpro")
                ->setLastModifiedBy("Fexpro")
                ->setTitle("Products")
                ->setSubject("Products");
    
            // Set the active Excel worksheet to sheet 0
    
            $objPHPExcel->setActiveSheetIndex(0); 
    
    
          $objPHPExcel->getActiveSheet()->setAutoFilter('A1:F1');
          $objPHPExcel->getActiveSheet()->getStyle('A1:T1')->applyFromArray( //Q1
                          array(
                              'fill' => array(
                                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                  'color' => array('rgb' => 'CCCCCC')
                              )
                          )
                      );
            if($is_presale==0){
                $objPHPExcel->getActiveSheet()->getStyle('H1')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'c0504d')
                        )
                    )
                );   
            }
                    
                      
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
            if($is_presale==1){
                $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(false);
                $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(0); 
            }
    
            //$objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(false);
            //$objPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(6);
    
    
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
                        case 'image':
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
                if($columnChar=="D"){
                    $objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->getNumberFormat()->setFormatCode("\$#,##0.00");  //'0.00');
                }
                if($is_presale==0 && $columnChar=="H"){
                    $objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'f2dcdb')
                            )
                        )
                    );
                } 
                if($columnChar=="O"){
                    $objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'ED7D31')
                            )
                        )
                    );
                }    
                if($columnChar=="P"){
                   //$objPHPExcel->getActiveSheet()->setCellValue($columnChar.$cell, "=IF(N$cell=\"\",0,N$cell)*F$cell*O$cell"); 
                   $objPHPExcel->getActiveSheet()->setCellValue($columnChar.$cell, "=F$cell*O$cell"); 
                   //$objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->getNumberFormat()->setFormatCode('0.00'); 
    
                   $objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->applyFromArray(
                          array(
                              'fill' => array(
                                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                  'color' => array('rgb' => 'ED7D31')
                              )
                          )
                      );
                }
                if($columnChar=="Q"){
                    $objPHPExcel->getActiveSheet()->setCellValue($columnChar.$cell, "=IF(O$cell=\"\",0,O$cell)*D$cell*F$cell"); 
                     
                    $objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->getNumberFormat()->setFormatCode("\$#,##0.00");  //'0.00');
                    
                    $objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->applyFromArray(
                           array(
                               'fill' => array(
                                   'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                   'color' => array('rgb' => 'DDEBF7')
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
        
        $from_presale = isset($_POST["from_presale"]) && $_POST["from_presale"]==1 ? 1:0;
     
        $cats = (array)get_the_terms( $product_id , 'product_cat' );
        $cats =array_column($cats,"slug");
        $is_presale = in_array($this->SLUG_PRESALE,$cats)?true:false;
        $is_basic = in_array("basics",$cats)?true:false;
        if(!$is_presale && $from_presale==1){
            $is_presale=true;
        }

     

        $product_status = get_post_status($product_id);

        //$product =  wc_get_product( $variation_p->ID);
        //$product_metas = get_post_meta($variation_id);

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
            $data_extra["is_presale"] = $is_presale?1:0;
            if($is_basic){
                $data_extra["is_basic"] = $is_basic;
            }

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
        
        $product_metas = get_post_meta($variation_id);
        $cats = (array)get_the_terms( $product_id , 'product_cat' );
        $cats =array_column($cats,"slug");
        $is_presale = in_array($this->SLUG_PRESALE,$cats)?true:false;
        if($is_presale){
            return true;
        }

        if( $type_stock!="available" && in_array($this->BASIC_CAT,$cats)){
            return true;
        }

        $stock_available = isset($product_metas["_stock_present"][0])?(int)$product_metas["_stock_present"][0]:0;
        $stock_future = isset($product_metas["_stock_future"][0])?(int)$product_metas["_stock_future"][0]:0;

        $_stock = $type_stock=="available"? $stock_available: $stock_future;
        if($_stock==0){
            $message="No stock available";
            return false;
        }
        $items= WC()->cart->get_cart();
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

        $cats = (array)get_the_terms( $product_id , 'product_cat' );
        $cats =array_column($cats,"slug");

        $from_presale = isset($post["from_presale"]) && $post["from_presale"]==1 ? 1:0;
        $is_presale=$from_presale==1?true:false;
       
        if($cart!=null){
            $product_status = get_post_status($product_id);

            foreach ($cart as $key => $item) {
                $quantity = isset($item["qty"])? (int)$item["qty"] : 0;
                $quantity_future = isset($item["qty_future"])? (int)$item["qty_future"] : 0;

                /*$is_presale = in_array($this->SLUG_PRESALE,$cats)?true:false;
                if(!$is_presale && $from_presale==1){
                    $is_presale=true;
                }*/

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
                    $data_extra["is_presale"] = $is_presale?1:0;
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
    function get_slugs_groups($group,$is_presale=false){
        $only_cats = $this->only_cats;

        if($is_presale){
            $only_cats = $this->cats_presale;
        }

        $groups = explode(",",$group);
        
        $includes = array_map(function($item){
            if($item!="t-shirt"){
                $item=str_replace("-"," ",$item);
            }
            return " tt1.name LIKE '%".$item."%' ";
        },$groups);
        $includes = implode(" OR ",$includes);
        $sql="SELECT tt1.term_id,tt1.slug
        FROM wp_terms tt1
        INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
        WHERE tt2.taxonomy='product_cat'
        AND  tt2.term_id IN (SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (".implode(",",$only_cats)."))
        AND (".$includes.")";
        //echo $sql;
        $r=$this->db->get_results($sql,ARRAY_A);
        $r=array_column($r,"slug");
        return $r;
    }
    function get_slugs_product_type($types,$is_presale=false){
        $only_cats = $this->only_cats;
        if($is_presale){
            $only_cats = $this->cats_presale;
        }
        $types = explode(",",$types);
        
        $includes = array_map(function($item){
            if($item!="t-shirt"){
                $item=str_replace("-"," ",$item);
            }
            return " tt1.name LIKE '%".$item."%' ";
        },$types);
        $includes = implode(" OR ",$includes);
        $sql="SELECT tt1.term_id,tt1.slug
        FROM wp_terms tt1
        INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
        WHERE tt2.taxonomy='product_cat'
        AND  tt2.parent IN (SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (".implode(",",$only_cats)."))
        AND (".$includes.")";
       
        $r=$this->db->get_results($sql,ARRAY_A);
        $r=array_column($r,"slug");
        return $r;
    }
    function get_slugs_lob($lobs,$is_presale=false){
        $only_cats = $this->only_cats;
        if($is_presale){
            $only_cats = $this->cats_presale;
        }
        $types = explode(",",$lobs);
        
        $includes = array_map(function($item){
            
            //if($item=="UNI") $item="UNIVERISITIES";
            
            if($item!="UNI"){
                if($item=="SPO") $item="SPORTS";

                return " tt1.name LIKE '".$item."%' ";
            }else{
                return " (tt1.name LIKE 'UNIVERISITIES%' OR tt1.name LIKE 'UNIVERSITY%' ) ";
            }
        },$types);
        $includes = implode(" OR ",$includes);
        $sql="SELECT tt1.term_id,tt1.slug
        FROM wp_terms tt1
        INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
        WHERE tt2.taxonomy='product_cat'
        AND  tt2.term_id IN (SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (".implode(",",$only_cats)."))
        AND (".$includes.")";
        //echo $sql;
        $r=$this->db->get_results($sql,ARRAY_A);
        $r=array_column($r,"slug");
        return $r;
    }

    function qep_export_cart(){
        error_reporting(0);
        ini_set('display_errors', 0);
        $items= WC()->cart->get_cart();
        
        set_time_limit(120);
        $products=[];

        $url_image = get_site_url()."/wp-content/uploads/";

        $ids=array();
        $posts=$this->main_products_get($ids,false);
        $u =  wp_get_current_user();
        $rol = isset($u->roles[0])?$u->roles[0]:array();

        $discounts = discount_by_rol_margin($u->ID);
       
            foreach ($items as $key => $var) {
                $sql_variacion="SELECT a.*,pp.post_name parent_slug, pm1.meta_value thumbnail_id, pm2.meta_value image ,pm3.meta_value miniaturas,pm4.meta_value delivery_date,
                (SELECT group_concat(concat(b.meta_key,'||',b.meta_value) SEPARATOR '///')  FROM wp_postmeta b WHERE b.post_id=a.ID ) metas,
                ( SELECT group_concat(concat(tt.taxonomy,'||',t.name) SEPARATOR '///')
                        FROM wp_term_relationships tr
                        INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id
                        INNER JOIN wp_terms t ON tt.term_id=t.term_id
                        WHERE tr.object_id=a.post_parent
                ) attributes
                FROM wp_posts a 
                INNER JOIN wp_posts pp ON a.post_parent=pp.ID
                LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=a.ID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
                LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attached_file')
                LEFT JOIN wp_postmeta pm3 ON (pm3.post_id=pm1.meta_value AND pm3.meta_key='_wp_attachment_metadata')
                LEFT JOIN wp_postmeta pm4 ON (pm4.post_id=a.ID AND pm4.meta_key='delivery_date')
                WHERE a.post_status IN ('publish')
                AND a.ID=".$var["variation_id"];
                $var_data = $this->db->get_row($sql_variacion);
                global $cats;
                $cats=array();
                $main_product_atts = explode("///",$var_data->attributes);
                $main_product_atts = array_map(function($row){
                    $f = explode("||",$row);
                    if($f[0]=="product_cat"){
                        global $cats;
                        $cats[]=$f[1];
                    }
                    return array("key"=>$f[0],"value"=>$f[1]);
                },$main_product_atts);
                $main_atts=array_column($main_product_atts,"value","key");
             
                $metas_variation = explode("///",$var_data->metas);
                $metas_variation = array_map(function($row){
                    $f = explode("||",$row);
                    return array("key"=>$f[0],"value"=>$f[1]);
                },$metas_variation);
                $metas_variation=array_column($metas_variation,"value","key");
                
                $color = preg_replace('/[0-9]+/', '',str_replace("Color: ","",$var_data->post_excerpt));
                $pa_color = strtolower(str_replace("Color: ","",$var_data->post_excerpt));

                $miniaturas = unserialize($var_data->miniaturas);
                $miniaturas= isset($miniaturas["sizes"])? $miniaturas["sizes"]:"";
                $upload_path = $var_data->image!=""?explode("/",$var_data->image):"";
                if($upload_path!=""){
                    array_pop($upload_path);
                    $upload_path=implode("/",$upload_path);
                }

                $variation_id = $var["variation_id"];

                $mini = isset($miniaturas["thumbnail"])?$miniaturas["thumbnail"]["file"]:(isset($miniaturas["medium"])?$miniaturas["medium"]["file"]:"");

                $delivery = "";
                if($var_data->delivery_date!=""){
                    $dd= str_replace("/","-",$var_data->delivery_date);
                    $dd= explode("-",$dd);
                    if(count($dd)==3){
                        $delivery=$dd[0] ."-".str_pad($dd[1], 2, "0", STR_PAD_LEFT)."-".str_pad($dd[2], 2, "0", STR_PAD_LEFT);
                    }
                }

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
                
                $sizes=array();
                $units_per_pack=0;
                for($i=1;$i<=10;$i++){
                    if(isset($metas_variation["custom_field".$i]) && $metas_variation["custom_field".$i]!=""){
                        $sizes[]=$metas_variation["custom_field".$i];
                        $units_per_pack+=isset($metas_variation["size_box_qty".$i])?(int)$metas_variation["size_box_qty".$i]:0;
                    }
                }
                $divisions=array("POP"=>"POP","SPO"=>"SPORT","UNI"=>"UNIVERSITY");
                $division = isset($metas_variation["lob"]) && isset($divisions[strtoupper($metas_variation["lob"])])?$divisions[strtoupper($metas_variation["lob"])] :"";

                $group = isset($cats[2])?$cats[2]:"";
                $prod_type = isset($main_atts["product_cat"]) ?$main_atts["product_cat"] :"";
                $item=array();
                //$item["id"] = $variation_id;
                //$item["main_id"] = $var->post_parent;
                $stock_present =  isset($metas_variation["_stock_present"]) ? (int)$metas_variation["_stock_present"]:0;
                $stock_future =  isset($metas_variation["_stock_future"]) ? (int)$metas_variation["_stock_future"]:0;

                $item["image"] = $mini!=""?(string)$upload_path."/".$mini:"";

                $item['sku'] =  isset($metas_variation["_sku"])?$metas_variation["_sku"]:"";

                $item['product_title'] =  $var_data->post_title;// . " - " . $color ;
              
                $item['price'] =  $price;

                $item['color'] =  $color ;

                $item['units_per_pack'] =$units_per_pack;
             
                $item["delivery_date"] = $stock_present>0?"IMMEDIATE":$delivery ;
                
                $item["available"] = $units_per_pack * ($stock_present>0?$stock_present:$stock_future);

                $item['Division'] =  $division;

                $item['brand'] =  isset($main_atts["pa_brand"])?$main_atts["pa_brand"]:""; 
                $item['gender'] =  isset($main_atts["pa_gender"])?$main_atts["pa_gender"]:"";

                $item['group'] = $group;
                $item['product'] = $prod_type;
                $item['size_chart'] = implode("-",$sizes);

                $item['qty'] = $var["quantity"];
                $item["total_units"] = 0;
                $item["total_price"] = 0;
                $item["team"] = isset($metas_variation["product_team"])?$metas_variation["product_team"]:"";
                $item["logo_application"] = isset($metas_variation["logo_application"])?$metas_variation["logo_application"]:"";
                $item["composition"] = isset($metas_variation["pa_compositions"])?$metas_variation["pa_compositions"]:"";
                
                $products[] = $item;

                if($stock_present>0 && $stock_future>0){
                    $item["delivery_date"] = $delivery;
                    $item["available"] = $units_per_pack * $stock_future;
                    $products[] = $item;
                }

            }
       
        //echo json_encode($products);
        //die();
        ob_clean();
        $file=$this->build_excel_catalog( $products);
        $json["error"] = 0;
        $json["download"] = get_site_url()."/wp-admin/admin-ajax.php?action=download_xlsx&file=".$file;
        echo json_encode($json);
        die();

    }
    function qep_view_cart(){
        /*global $wp_filter;
        echo "<pre>";
        print_r($wp_filter["woocommerce_single_product_summary"]);
        echo "</pre>";
        exit;*/
        /*$role_selected_data  = (array) get_option('afpvu_user_role_visibility');
        echo "<pre>";
        print_r($role_selected_data);
        echo "</pre>";
        die();*/
        $items= WC()->cart->get_cart();
        echo json_encode($items);
        die();
    }

    function on_action_cart_updated( $cart_updated ){

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){    
            echo "ajax";
            die();    
        }
         echo "noaajax";
         die();
       
       /* $applied_coupons = WC()->cart->get_applied_coupons();
    
        if( count( $applied_coupons ) > 0 ){
            $new_value        = WC()->cart->get_cart_subtotal();
            $discounted       = WC()->cart->coupon_discount_totals;
            $discounted_value = array_values($discounted)[0];
            $new_value        = $new_value-$discounted_value + 100;
    
            WC()->cart->set_total( $new_value );
    
            if ( $cart_updated ) {
                // Recalc our totals
                WC()->cart->calculate_totals();
            }
        }*/
    }
        
}
?>