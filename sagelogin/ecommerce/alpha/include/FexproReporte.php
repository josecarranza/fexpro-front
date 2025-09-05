<?php

class FexproReporte{
	public $db;
	public $presale = "presale7";
	
	public function __construct($_presale="")
	{
		global $wpdb;
		$this->db = $wpdb;
		if($_presale!=""){
			$this->presale = $_presale;
		}else{
			//$this->presale = get_option("current_presale_status");
			$list = $this->ordersStatusGet();
			
			foreach($list  as $os){
				if(isset($os["default"]) && $os["default"] == 1){
					$this->presale = $os["code"];
					break;
				}
			}
		}
		
	}
	function ordersStatusGet(){
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
	function totalUnidadesPais(){

		$order_ids = $this->ordersInPresale(true);
		

		$sql=" SELECT tmp.post_id, tmp.country,	woim.meta_value product_id,
		-- woim_2.meta_value qty,woim_3.meta_value total, pm2.meta_value units_per_pack, pm3.meta_value price
		sum(woim_3.meta_value) total , sum(pm2.meta_value*woim_2.meta_value) total_units, sum(pm2.meta_value*woim_2.meta_value*pm3.meta_value) total_calc
		  from wp_woocommerce_order_items woi
		  INNER JOIN wp_woocommerce_order_itemmeta woim ON woi.order_item_id = woim.order_item_id AND woim.meta_key='_variation_id'
		INNER JOIN wp_woocommerce_order_itemmeta woim_2 ON woi.order_item_id = woim_2.order_item_id AND woim_2.meta_key='_qty'
		  INNER JOIN wp_woocommerce_order_itemmeta woim_3 ON woi.order_item_id = woim_3.order_item_id AND woim_3.meta_key='_line_total'
		  INNER JOIN (
		
		SELECT  pm.post_id , um4.meta_value country 
		FROM wp_postmeta pm 
			INNER JOIN wp_usermeta um4 ON pm.meta_value=um4.user_id AND um4.meta_key = ('shipping_country')
		WHERE pm.post_id IN (
				{$order_ids}
			) AND pm.meta_key ='_customer_user'
		) AS tmp ON tmp.post_id=woi.order_id
		LEFT JOIN wp_postmeta pm2 ON woim.meta_value=pm2.post_id AND pm2.meta_key='units_per_pack'
		LEFT JOIN wp_postmeta pm3 ON woim.meta_value=pm3.post_id AND pm3.meta_key='_price'
		GROUP BY tmp.country";

		$r = $this->db->get_results($sql);

		$countries=WC()->countries->get_countries();

		foreach ($r as $key => $value) {
			$r[$key]->country_str = isset($countries[$value->country]) ? $countries[$value->country] :$value->country;
		}

		return $r;

	}
	function report_group_by_pi(){
		$sql="SELECT * FROM (
			SELECT var.*, ifnull(pm1.meta_value,'') pi_numeral, ifnull(pm2.meta_value,'') supplier,ifnull( pm3.meta_value,'') sourcing_office FROM  (
			SELECT ttmp.variationID FROM(
			select
			p.order_id,
			p.order_item_id,
			p.order_item_name,
			max( CASE WHEN pm.meta_key = '_product_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as productID,
			max( CASE WHEN pm.meta_key = '_qty' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as Qty,
			max( CASE WHEN pm.meta_key = '_variation_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as variationID,
			max( CASE WHEN pm.meta_key = '_line_subtotal' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as subtotal
			from
			wp_woocommerce_order_items as p,
			wp_woocommerce_order_itemmeta as pm
			where order_item_type = 'line_item' and
			p.order_item_id = pm.order_item_id
			AND p.order_id IN (
			SELECT pp.ID FROM wp_posts pp
			WHERE pp.post_type='shop_order' AND pp.post_status='wc-{$this->presale}' AND pp.ID >=126125
			)
			group by
			p.order_item_id
			) AS ttmp
			INNER JOIN wp_term_relationships tr ON tr.object_id=ttmp.productID AND tr.term_taxonomy_id IN (6497,5931,3259)
			GROUP BY ttmp.variationID
			) AS var
			LEFT JOIN wp_postmeta pm1 ON pm1.post_id = var.variationID AND pm1.meta_key='pi_numeral'  
			LEFT JOIN wp_postmeta pm2 ON pm2.post_id = var.variationID AND pm2.meta_key='supplier'
			LEFT JOIN wp_postmeta pm3 ON pm3.post_id = var.variationID AND pm3.meta_key='sourcing_office'
			
			) AS tmp
			
			GROUP BY tmp.pi_numeral, tmp.supplier , tmp.sourcing_office 
			ORDER BY pi_numeral ASC, supplier ASC,sourcing_office ASC ";

		$r = $this->db->get_results($sql);
		return $r;
	}

	function products_in_pi_order($filters = array()){
		$sql="	SELECT tmp.order_item_name, tmp.productID, tmp.variationID,SUM(tmp.Qty) qty, SUM(tmp.subtotal) subtotal,pm2.meta_value image ,
		(SELECT group_concat(concat(b.meta_key,'||',b.meta_value) SEPARATOR '///')  FROM wp_postmeta b WHERE b.post_id=tmp.variationID ) metas,
		( SELECT group_concat(concat(tt.taxonomy,'||',t.name,if(tt.taxonomy='product_cat',CONCAT(':',tt.parent,':',tt.term_id),''),if(tt.taxonomy='pa_player',CONCAT(':',t.slug),'')) SEPARATOR '///')
								FROM wp_term_relationships tr
								INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id
								INNER JOIN wp_terms t ON tt.term_id=t.term_id
								WHERE tr.object_id=tmp.productID
						) attributes,
						pm3.meta_value miniaturas,
			pm4.meta_value pi_numeral,
			pm5.meta_value supplier,
			pm6.meta_value sourcing_office
			FROM (
			select ttmp.order_id,ttmp.order_item_id,ttmp.order_item_name,ttmp.productID,SUM(ttmp.Qty) Qty,ttmp.variationID,SUM(ttmp.subtotal) subtotal FROM (
				SELECT ttmp.* from(
			select
			p.order_id,
			p.order_item_id,
			p.order_item_name,
			max( CASE WHEN pm.meta_key = '_product_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as productID,
			max( CASE WHEN pm.meta_key = '_qty' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as Qty,
			max( CASE WHEN pm.meta_key = '_variation_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as variationID,
			max( CASE WHEN pm.meta_key = '_line_subtotal' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as subtotal
			from
			wp_woocommerce_order_items as p,
			wp_woocommerce_order_itemmeta as pm
			where order_item_type = 'line_item' and
			p.order_item_id = pm.order_item_id
			AND p.order_id IN (
			SELECT pp.ID FROM wp_posts pp
			WHERE pp.post_type='shop_order' AND pp.post_status='wc-{$this->presale}' AND pp.ID >=126125
			)
			group by
			p.order_item_id
			) AS ttmp
			INNER JOIN wp_term_relationships tr ON tr.object_id=ttmp.productID AND tr.term_taxonomy_id IN (6497,5931,3259)
			GROUP BY ttmp.order_item_id
			) ttmp
			GROUP BY ttmp.variationID
		) tmp
		LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=tmp.variationID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
		LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attached_file')
		LEFT JOIN wp_postmeta pm3 ON (pm3.post_id=pm1.meta_value AND pm3.meta_key='_wp_attachment_metadata')
		
		LEFT JOIN wp_postmeta pm4 ON (pm4.post_id=tmp.variationID AND pm4.meta_key='pi_numeral')
		LEFT JOIN wp_postmeta pm5 ON (pm5.post_id=tmp.variationID AND pm5.meta_key='supplier')
		LEFT JOIN wp_postmeta pm6 ON (pm6.post_id=tmp.variationID AND pm6.meta_key='sourcing_office')
		
		WHERE 1=1
		AND (pm4.meta_value = '{$filters['pi_numeral']}' OR  ('{$filters['pi_numeral']}'='' AND pm4.meta_value IS NULL))
		AND pm5.meta_value = '{$filters['supplier']}'
		AND pm6.meta_value = '{$filters['sourcing_office']}'
		GROUP BY tmp.variationID
		
		
		";
		
		//echo $sql;
			
		$r=$this->db->get_results($sql);
		return $r;
	


	}
	function get_meta_value_post($_posts=array()){
		$r=$this->db->get_row("SELECT meta_value FROM wp_postmeta pm3 WHERE pm3.meta_key = '_wp_attachment_metadata' AND pm3.post_id IN (".implode(",",$_posts).")");
		return $r;
	}
	 
	
}
?>