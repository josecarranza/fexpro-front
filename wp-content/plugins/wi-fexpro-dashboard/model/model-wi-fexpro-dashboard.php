<?php 
class ModelFexproDashboard{
	public $db;
	public function __construct( )
	{
		global $wpdb;
		$this->db = $wpdb;
		
	}
	function getOrderStatus(){

		$config = json_decode(get_option("wi_order_status_list"),true);
		$list = [];
		if(is_array($config)){
			$list = $config;
		}else{
			$list = [];
		}
		return $list;
	}
	function ordersInPresale($inString=false){
		$sql="SELECT ttmp.order_id FROM (
			select
			p.order_id,
			max( CASE WHEN pm.meta_key = '_product_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as productID
	
			from
			wp_woocommerce_order_items as p,
			wp_woocommerce_order_itemmeta as pm
			where order_item_type = 'line_item' and
			p.order_item_id = pm.order_item_id
			AND p.order_id IN (
			SELECT pp.ID FROM wp_posts pp
			WHERE pp.post_type='shop_order' AND pp.post_status = 'wc-{$this->presale}' AND pp.ID >=126125
			)
			group by
			p.order_item_id
			) AS ttmp
			INNER JOIN wp_term_relationships tr ON tr.object_id=ttmp.productID AND tr.term_taxonomy_id IN (6497,5931,3259)
			GROUP BY ttmp.order_id
		";
	
		$r = $this->db->get_results($sql,ARRAY_A);
		$r = array_column($r,"order_id");
		if($inString){
			$r = implode(",",$r);
		}
		return $r;
	}
	function ordersInAllPresale($inString=false){
		$status_list = $this->getOrderStatus();
		$status_list = array_column($status_list,"code");
		$status_list = array_map(function($x){return "wc-".$x;},$status_list);
		$status_list = implode("','",$status_list);
		$sql="SELECT ttmp.order_id FROM (
			select
			p.order_id,
			max( CASE WHEN pm.meta_key = '_product_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as productID
	
			from
			wp_woocommerce_order_items as p,
			wp_woocommerce_order_itemmeta as pm
			where order_item_type = 'line_item' and
			p.order_item_id = pm.order_item_id
			AND p.order_id IN (
			SELECT pp.ID FROM wp_posts pp
			WHERE pp.post_type='shop_order' AND pp.post_status IN ('{$status_list}')  
			)
			group by
			p.order_item_id
			) AS ttmp
			INNER JOIN wp_term_relationships tr ON tr.object_id=ttmp.productID AND tr.term_taxonomy_id IN (6497,5931,3259)
			GROUP BY ttmp.order_id
		";
		//echo $sql;
		$r = $this->db->get_results($sql,ARRAY_A);
		$r = array_column($r,"order_id");
		if($inString){
			$r = implode(",",$r);
		}
		return $r;
	}
	function getCountries(){
		$ids_orders = $this->ordersInAllPresale(true);
		$sql = "SELECT  pm.post_id , um4.meta_value country 
		FROM wp_postmeta pm 
			INNER JOIN wp_usermeta um4 ON pm.meta_value=um4.user_id AND um4.meta_key = ('shipping_country')
		WHERE pm.post_id IN ({$ids_orders}) AND pm.meta_key ='_customer_user'
		GROUP BY  um4.meta_value
		ORDER BY  um4.meta_value asc";
		$r = $this->db->get_results($sql);
		//echo $sql;
		$countries=WC()->countries->get_countries();

		foreach ($r as $key => $value) {
			$r[$key]->country_str = isset($countries[$value->country]) ? $countries[$value->country] :$value->country;
			if($value->country==""){
				unset($r[$key]);
			}
		}
		//array_filter($r,function($item){return $item->country!="";});
		return $r;
	}
	function getReportSummary($params=[]){
		$status_selected =  $params["status"];
		$status_list = array_map(function($x){return "wc-".$x;},$status_selected);
		$status_list = implode("','",$status_list);

		$countries =  $params["countries"];
		$countries = implode("','",$countries);

		$sql_orders_id="SELECT ttmp.order_id FROM (
			select
			p.order_id,
			max( CASE WHEN pm.meta_key = '_product_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as productID
	
			from
			wp_woocommerce_order_items as p,
			wp_woocommerce_order_itemmeta as pm
			where order_item_type = 'line_item' and
			p.order_item_id = pm.order_item_id
			AND p.order_id IN (
			SELECT pp.ID FROM wp_posts pp
			WHERE pp.post_type='shop_order' AND pp.post_status IN ('{$status_list}')  
			)
			group by
			p.order_item_id
			) AS ttmp
			INNER JOIN wp_term_relationships tr ON tr.object_id=ttmp.productID AND tr.term_taxonomy_id IN (6497,5931,3259)
			GROUP BY ttmp.order_id
		";
		$r = $this->db->get_results($sql_orders_id,ARRAY_A);
		$r = array_column($r,"order_id");

		$ids_orders = implode(",",$r);
		

		$sql=" SELECT tmp.post_id, tmp.country,	woim.meta_value product_id,COUNT(woim.meta_value) skus,

		sum(woim_3.meta_value) total , sum(pm2.meta_value*woim_2.meta_value) total_units, sum(pm2.meta_value*woim_2.meta_value*pm3.meta_value) total_calc,
		pm4.meta_value 'scale_drop',pm5.meta_value 'scale_stock',pm6.meta_value 'scale_ok'
		from wp_woocommerce_order_items woi
		INNER JOIN wp_woocommerce_order_itemmeta woim ON woi.order_item_id = woim.order_item_id AND woim.meta_key='_variation_id'
		INNER JOIN wp_woocommerce_order_itemmeta woim_2 ON woi.order_item_id = woim_2.order_item_id AND woim_2.meta_key='_qty'
		INNER JOIN wp_woocommerce_order_itemmeta woim_3 ON woi.order_item_id = woim_3.order_item_id AND woim_3.meta_key='_line_total'
		INNER JOIN (
			SELECT  pm.post_id , um4.meta_value country 
			FROM wp_postmeta pm 
				INNER JOIN wp_usermeta um4 ON pm.meta_value=um4.user_id AND um4.meta_key = ('shipping_country')
			WHERE pm.post_id IN ({$ids_orders}) AND pm.meta_key ='_customer_user'
		) AS tmp ON tmp.post_id=woi.order_id
		LEFT JOIN wp_postmeta pm2 ON woim.meta_value=pm2.post_id AND pm2.meta_key='units_per_pack'
		LEFT JOIN wp_postmeta pm3 ON woim.meta_value=pm3.post_id AND pm3.meta_key='_price'
		LEFT JOIN wp_postmeta pm4 ON woim.meta_value=pm4.post_id AND pm4.meta_key='scale_drop'
	    LEFT JOIN wp_postmeta pm5 ON woim.meta_value=pm5.post_id AND pm5.meta_key='scale_stock'
	    LEFT JOIN wp_postmeta pm6 ON woim.meta_value=pm6.post_id AND pm6.meta_key='scale_ok'
		WHERE tmp.country IN ('${countries}')
		GROUP BY tmp.country, woim.meta_value
		";

		$r = $this->db->get_results($sql);
		/* 
		echo "<pre>";
		print_r($r);
		echo "</pre>"; */

		$data = [];
		foreach($r as $item){
			if(!isset($data[$item->country])){
				$tmp=[
					"country"=>$item->country,
					"skus"=>0,
					"total"=>0,
					"total_calc"=>0,
					"total_units"=>0,
					"total_skus_drop"=>0,
					"total_drop"=>0,
					"total_calc_drop"=>0,
					"total_units_drop"=>0,
					"total_skus_stock"=>0,
					"total_stock"=>0,
					"total_calc_stock"=>0,
					"total_units_stock"=>0,
					"total_skus_ok"=>0,
					"total_ok"=>0,
					"total_calc_ok"=>0,
					"total_units_ok"=>0
					
				];
				$data[$item->country] = $tmp;
			}
			$data[$item->country]['skus'] += $item->skus;
			$data[$item->country]['total'] += $item->total;
			$data[$item->country]['total_calc'] += $item->total_calc;
			$data[$item->country]['total_units'] += $item->total_units;

			if($item->total_units<$item->scale_drop){
				$data[$item->country]['total_drop'] += $item->total;
				$data[$item->country]['total_calc_drop'] += $item->total_calc;
				$data[$item->country]['total_units_drop'] += $item->total_units;
				$data[$item->country]['total_skus_drop'] += $item->skus;
			}elseif($item->total_units<$item->scale_ok){
				$data[$item->country]['total_stock'] += $item->total;
				$data[$item->country]['total_calc_stock'] += $item->total_calc;
				$data[$item->country]['total_units_stock'] += $item->total_units;
				$data[$item->country]['total_skus_stock'] += $item->skus;
			}else{
				$data[$item->country]['total_ok'] += $item->total;
				$data[$item->country]['total_calc_ok'] += $item->total_calc;
				$data[$item->country]['total_units_ok'] += $item->total_units;
				$data[$item->country]['total_skus_ok'] += $item->skus;
			}
			
		}
		/* echo "<pre>";
		print_r($data);
		echo "</pre>"; */
		$countries=WC()->countries->get_countries();

		foreach ($data as $key => $value) {
			$data[$key]["country_str"] = isset($countries[$key]) ? $countries[$key] :$key;
		}
		return array_values($data);
	}

	function getSeasons(){
		$sql= "SELECT t.term_id,t.name, t.slug
		FROM wp_terms t
		INNER JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id
		WHERE tt.taxonomy='pa_season' AND tt.`count`>0
		AND t.name NOT LIKE '%21' AND t.name NOT LIKE '%22'
		ORDER BY t.name asc";
		
		$r = $this->db->get_results($sql);
		return $r;
	}

	function getXMLProducts($filters=[]){
		$status="";
		if(isset($filters['status']) && count($filters['status'])>0){
			$status = array_map(function($x){return 'wc-'.$x;},$filters['status']);
			$status =implode("','",$status);
		}
		
		$season = isset($filters['season']) && $filters['season'] !="" ? $filters['season']  : "";
		$qs = isset($filters['qs']) && count($filters['qs'])>0 ? implode("','",$filters['qs']):'';
		$skus = isset($filters['skus']) && count($filters['skus'])>0 ? implode("','",$filters['skus']):'';

		$sql_variations_selled = "SELECT pm.meta_value variation_id, pp.post_parent
		FROM wp_woocommerce_order_items p 
		INNER JOIN wp_woocommerce_order_itemmeta pm ON p.order_item_id=pm.order_item_id AND pm.meta_key='_variation_id'
		INNER JOIN wp_posts pp ON pp.ID=pm.meta_value ";
		if($qs!=""){
			$sql_variations_selled.="
			INNER JOIN (
				wp_term_relationships tr
				INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id AND tt.taxonomy='pa_date'
				INNER JOIN wp_terms t ON tt.term_id=t.term_id AND t.slug IN ('{$qs}')
			) ON tr.object_id=pp.post_parent";
		}
		$sql_variations_selled.="
		INNER JOIN wp_postmeta ppm ON ppm.post_id=pm.meta_value AND ppm.meta_key LIKE 'size_barcode%' AND ppm.meta_value != ''
		";
		if($skus!=""){
			$sql_variations_selled.="
			INNER JOIN wp_postmeta ppm2 ON ppm2.post_id = pm.meta_value AND ppm2.meta_key = '_sku' AND ppm2.meta_value in ('{$skus}')
			";
		}
		if($season!=""){
			$sql_variations_selled.="
			INNER JOIN (
				wp_term_relationships tr2
				INNER JOIN wp_term_taxonomy tt2 ON tr2.term_taxonomy_id = tt2.term_taxonomy_id AND tt2.taxonomy = 'pa_season'
				INNER JOIN wp_terms t2 ON tt2.term_id = t2.term_id AND t2.slug = '{$season}'
			) ON tr2.object_id=pp.post_parent
			";
		}
		if($status!=""){
			$sql_variations_selled.="
			WHERE p.order_id IN (
				SELECT pp.ID FROM wp_posts pp
				WHERE pp.post_type='shop_order' AND pp.post_status in ('{$status}')
			)
			";
		}
		$sql_variations_selled.=" GROUP BY pm.meta_value";
		
		$variations = $this->db->get_results($sql_variations_selled);
		 
		return $variations;
	 
	}

	function getXMLProductsSeason($filters=[],$selled=true){

		$season = isset($filters['season']) && $filters['season'] !="" ? $filters['season']  : "";
		$qs = isset($filters['qs']) && count($filters['qs'])>0 ? implode("','",$filters['qs']):'';

		$sql_sales = "SELECT pm.meta_value variation_id 
		FROM wp_woocommerce_order_items p
		INNER JOIN wp_woocommerce_order_itemmeta pm ON p.order_item_id=pm.order_item_id AND pm.meta_key='_variation_id'
		INNER JOIN wp_posts _p ON p.order_id = _p.ID AND _p.post_type='shop_order' AND _p.post_date>'2023-01-01' group by pm.meta_value";

		$sales = $this->db->get_results($sql_sales,ARRAY_A);
		$sales = implode(',',array_column($sales,'variation_id'));

		 $sql = "SELECT tmp.* FROM (
			SELECT pp.ID variation_id, pp.post_parent
			FROM wp_posts pp 
			INNER JOIN (wp_term_relationships tr
			INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id AND tt.taxonomy='pa_date'
			INNER JOIN wp_terms t ON tt.term_id=t.term_id AND t.slug IN ('{$qs}')) ON tr.object_id=pp.post_parent
			INNER JOIN wp_postmeta ppm ON ppm.post_id=pp.ID AND ppm.meta_key LIKE 'size_barcode%' AND ppm.meta_value != ''
			INNER JOIN (wp_term_relationships tr2
			INNER JOIN wp_term_taxonomy tt2 ON tr2.term_taxonomy_id = tt2.term_taxonomy_id AND tt2.taxonomy = 'pa_season'
			INNER JOIN wp_terms t2 ON tt2.term_id = t2.term_id AND t2.slug = '{$season}') ON tr2.object_id=pp.post_parent
			 
			GROUP BY pp.ID
			) AS tmp
			";
			if($selled){
				$sql .= "
					WHERE tmp.variation_id IN ({$sales})";
			}else{
				$sql .= "
				WHERE tmp.variation_id NOT IN ({$sales})";
			}
		
			$variations = $this->db->get_results($sql);
		 
			return $variations;
	 
	}
	function getXMLProductsSKU($skus=[] ){

		 
		$skus =  count($skus)>0 ? implode("','",$skus):'';
 
		$sql = "SELECT pp.ID variation_id, pp.post_parent
		FROM wp_posts pp
		INNER JOIN wp_postmeta ppm2 ON ppm2.post_id =pp.ID AND ppm2.meta_key = '_sku' AND ppm2.meta_value in ('{$skus}')
		GROUP BY pp.ID 
			";
			 
		$variations = $this->db->get_results($sql);
		
		return $variations;
	 
	}
	function getOrders($params=[]){
		
		$countries = count($params['countries']) ?  implode("','",$params['countries']): "";

		$status_selected =  $params["status"];
		$status_list = array_map(function($x){return "wc-".$x;},$status_selected);
		$status_list = implode("','",$status_list);

		$country_condition = $countries!="" ? " AND um.meta_value IN ('{$countries}')": "";

		$sql="SELECT p.ID, p.post_date,u.display_name customer_name, um.meta_value country,um2.meta_value company,
		sum(pm1.meta_value*woim1.meta_value) total_units, SUM( woim2.meta_value) total_sale, sum(pm0.meta_value*pm1.meta_value*woim1.meta_value) total_sale_calc,
		pm2.meta_value exported
		FROM wp_posts p 
		INNER JOIN (
			wp_postmeta pm 
			INNER JOIN wp_users u ON pm.meta_key = '_customer_user' AND pm.meta_value=u.ID
			INNER JOIN wp_usermeta um ON u.ID=um.user_id AND um.meta_key='billing_country'
			INNER JOIN wp_usermeta um2 ON u.ID=um2.user_id AND um2.meta_key='billing_company'
		) ON pm.post_id=p.ID {$country_condition}
		INNER JOIN (
		   wp_woocommerce_order_items woi
			INNER JOIN wp_woocommerce_order_itemmeta woim1 ON woi.order_item_id = woim1.order_item_id AND woim1.meta_key='_qty'
			INNER JOIN wp_woocommerce_order_itemmeta woim2 ON woi.order_item_id = woim2.order_item_id AND woim2.meta_key='_line_total'
			inner JOIN  (
			wp_woocommerce_order_itemmeta woim0
			INNER JOIN wp_postmeta pm0 ON woim0.meta_key = '_variation_id' AND woim0.meta_value = pm0.post_id AND pm0.meta_key='_price'
			) ON woim0.order_item_id = woi.order_item_id
			left JOIN  (
			wp_woocommerce_order_itemmeta woim
			INNER JOIN wp_postmeta pm1 ON woim.meta_key = '_variation_id' AND woim.meta_value = pm1.post_id AND pm1.meta_key='units_per_pack'
			) ON woim.order_item_id=woi.order_item_id
		 
		) ON woi.order_item_type='line_item' AND woi.order_id=p.ID
		LEFT JOIN wp_postmeta pm2 ON p.ID = pm2.post_id AND pm2.meta_key='_export_sage_status'
		WHERE p.post_type = 'shop_order'
		AND p.post_status IN ('{$status_list}')
		GROUP BY p.ID
		ORDER BY p.ID DESC";
		//echo $sql;
		$r=$this->db->get_results($sql);

		$countries=WC()->countries->get_countries();

		foreach ($r as $key => $value) {
			$r[$key]->country_str = isset($countries[$value->country]) ? $countries[$value->country] :$value->country;
		 
		}
				
		return $r;

	}
	
}

 ?>