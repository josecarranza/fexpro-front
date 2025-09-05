<?php 
class WiFexproMenuModel{
	public $db;

	public function __construct() {
		global $wpdb;
		$this->db = $wpdb;

	 
 
	}

	function menu_list_get(){
		$sql="select * from wi_menu";
		$r=$this->db->get_results($sql);
		return $r;
	}

	function save_menu_item($item){
		if(isset($item['id_menu_item']) && $item['id_menu_item']>0){
			$this->db->update('wi_menu_items',$item,['id_menu_item'=> $item['id_menu_item']]);

			return $item['id_menu_item'];
		}else{
			$this->db->insert('wi_menu_items',$item);
			return $this->db->insert_id;
		}
	}

	function getMenuJson($id_menu=0,$hide_disabled=false){
		$sql = "SELECT * 
		FROM wi_menu_items a
		WHERE a.id_menu={$id_menu}
		ORDER BY a.`level` ASC, a.`order` asc";

		$r = $this->db->get_results($sql,ARRAY_A);
		$json = [];
		if(is_array($r)){
			$json = $this->buildTree($r,$hide_disabled);
		}
		return $json;
	}
	function buildTree(array $items,$hide_disabled=false) {
		$tree = [];
		$index = [];
	
		foreach ($items as $item) {
			$_config = isset($item['config']) && $item['config']!=''? json_decode($item['config'],true):[];
			unset($item['config']);
			$item['css'] = isset($_config['css'])?$_config['css']:null;
			$item['hide_title'] = isset($_config['hide_title'])?$_config['hide_title']:null;
			$item['image'] = isset($_config['image'])?$_config['image']:null;
			$item['image_hover'] = isset($_config['image_hover'])?$_config['image_hover']:null;
			$item['disabled'] = isset($_config['disabled'])?$_config['disabled']:null;

			$index[$item['id_menu_item']] = $item;
			$index[$item['id_menu_item']]['items'] = [];
		}
	
		foreach ($items as $item) {
			

			if ($item['parent'] && isset($index[$item['parent']])) {
				$index[$item['parent']]['items'][] = &$index[$item['id_menu_item']];
			} else {
				$tree[] = &$index[$item['id_menu_item']];
			}
		}
	
		return $tree;
	}
	function removeBulk($items=[]){
		$sql = "DELETE from wi_menu_items where id_menu_item in (".implode(',',$items).")";
		$this->db->get_results($sql);
	}
}
