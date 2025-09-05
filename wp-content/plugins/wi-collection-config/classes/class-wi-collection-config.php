<?php 
class WiCollectionConfig{
	public $db;
	public $config_key ="wi_collection_config";
	public $departments = [["slug"=>"men","name"=>"Men"],["slug"=>"women","name"=>"Women"],["slug"=>"kids","name"=>"Kids"],["slug"=>"teens","name"=>"Teens"],["slug"=>"accesories","name"=>"Accesories"]];
	public $divisions = [["slug"=>"sport","name"=>"Sports"],["slug"=>"discovery","name"=>"Discovery"],["slug"=>"universities","name"=>"Universities"]];
	public function __construct() {
		global $wpdb;
		$this->db = $wpdb;
		
		add_action('admin_menu', array($this,"wi_collection_setup_menu")  );
		
		add_action('wp_ajax_collconf_save', array($this,"collconf_save"));
        add_action('wp_ajax_nopriv_collconf_save', array($this,"collconf_save"));

		add_action('wp_ajax_collconf_get', array($this,"collconf_get"));
        add_action('wp_ajax_nopriv_collconf_get', array($this,"collconf_get"));

		add_action('wp_ajax_collconf_delete', array($this,"collconf_delete"));
        add_action('wp_ajax_nopriv_collconf_delete', array($this,"collconf_delete"));

		add_action('wp_ajax_collconf_user_save', array($this,"collconf_user_save"));
        add_action('wp_ajax_nopriv_collconf_user_save', array($this,"collconf_user_save"));

		add_action('wp_ajax_collconf_role_save', array($this,"collconf_role_save"));
        add_action('wp_ajax_nopriv_collconf_role_save', array($this,"collconf_role_save"));

		add_filter( 'user_row_actions', [$this,"admin_user_link"], 10, 2 );

		add_action( 'wp_nav_menu_item_custom_fields', [$this,'menu_item_collection_config'] );
		add_action( 'wp_update_nav_menu_item', [$this,'menu_item_collection_config_save'], 10, 2 );
		add_filter( 'megamenu_nav_menu_css_class',  [$this,'show_menu_item_collection'], 10, 2 );

		add_filter('megamenu_walker_nav_menu_start_el',[$this,'custom_menu_item_content'], 10, 2);

		add_shortcode("user_collections",[$this,"shortcode_user_collections"]);
		
		add_action('wp_footer', [$this,'show_collection_for_user']);
	}
	function wi_collection_setup_menu(){
		//add_menu_page( 'Collections Config', 'Collections Config', 'manage_options', 'wi_collection_config',array($this,"wi_collection_admin_page_init") );
		add_menu_page( 'Collections Config', 'Collections Config', 'manage_options', 'wi_collection_config','','');
		 
		add_submenu_page(
			'wi_collection_config',
			"Collections",
			"Collections",
			'manage_options',
			'wi_collection_config',
			array($this,"wi_collection_admin_page_init")
		);
		add_submenu_page(
			'wi_collection_config',
			"Roles config",
			"Roles config",
			'manage_options',
			'wi_roles_collections',
			array($this,"wi_collection_roles_admin_page_init")
		);
	}
	 
	function wi_collection_admin_page_init(){
		$view = $_GET["view"]??"";
		switch($view){
			case "user";
				$user_id = (int)$_GET["user_id"]??0;
				$user_data = get_userdata($user_id);
				$user_config = get_user_meta($user_id,$this->config_key);
				
				$user_config = isset($user_config[0]) ? json_decode($user_config[0]):[];
				

				$brands = $this->get_brands();
				$ids_brands = array_column($brands,"term_id");
				$collections = $this->get_collections($ids_brands);
				$brands      = array_column($brands,"name","slug");
				$collections = array_column($collections,"name","slug");
				
				$departments  = array_column($this->departments,"name","slug");
				
				//$_config = json_decode(get_option($this->config_key));
				$_config =  $this->get_config();
				//usort($_config,function($a,$b){return strcmp($a["brand"], $b["brand"]);});
				$config=[];
				foreach($_config as $c){
					if(!isset($config[$c->brand])){
						$config[$c->brand] = ["name"=>$brands[$c->brand]??"","slug"=>$c->brand,"departments"=>[]];
					}
					if(!isset($config[$c->brand]["departments"][$c->department])){
						$config[$c->brand]["departments"][$c->department] = ["name"=>$departments[$c->department]??"","slug"=>$c->department,"collections"=>[]];
					}
					$config[$c->brand]["departments"][$c->department]["collections"][]=["name"=>$collections[$c->collection]??"","slug"=>$c->collection,"id"=>$c->id];
				}
			
				include(WI_PLUGIN_COLL_PATH."/views/view-user-collections.php");
			break;
			default:
				$brands = $this->get_brands();
				$ids_brands = array_column($brands,"term_id");
				$collections = $this->get_collections($ids_brands);
				$departments =  $this->departments;
				$divisions =  $this->divisions;
				$global_brands = $this->get_global_brands();
				include(WI_PLUGIN_COLL_PATH."/views/view-admin-collections.php");
			break;
		}
		
	}
	function get_brands(){
		$sql="SELECT b.*  FROM wp_term_taxonomy a 
		INNER JOIN wp_terms b ON a.term_id=b.term_id
		INNER JOIN wp_term_relationships tr ON a.term_taxonomy_id=tr.term_taxonomy_id
		WHERE a.taxonomy='pa_brand'
		AND tr.object_id IN (
		SELECT tr.object_id 
		FROM wp_term_relationships tr
		WHERE tr.term_taxonomy_id=5931
		)
		GROUP BY b.term_id
		ORDER BY b.name";

		$r=$this->db->get_results($sql,ARRAY_A);
		return $r;
	}

	function get_collections($ids=[]){
		$sql="
		SELECT  t3.name , t3.slug , group_concat(distinct t2.slug) brand
		FROM wp_term_relationships tr
		INNER JOIN wp_terms t ON tr.term_taxonomy_id=t.term_id
		INNER JOIN wp_term_relationships tr2 ON tr.object_id=tr2.object_id
		INNER JOIN wp_terms t2 ON tr2.term_taxonomy_id=t2.term_id
		INNER JOIN (
			wp_term_relationships tr3 
			INNER JOIN wp_term_taxonomy tt ON tr3.term_taxonomy_id=tt.term_taxonomy_id AND tt.taxonomy='pa_collection'
			INNER JOIN wp_terms t3 ON tt.term_id=t3.term_id
		) ON tr2.object_id=tr3.object_id
		  WHERE  t2.term_id IN (".implode(",",$ids).") 
		GROUP BY t3.slug
		ORDER BY t3.name";

		$sql="SELECT  t3.name , t3.slug 
		from wp_term_taxonomy tt  
		INNER JOIN wp_terms t3 ON tt.term_id=t3.term_id
		WHERE   tt.taxonomy='pa_collection'
		GROUP BY t3.slug
		ORDER BY t3.name";

		$r=$this->db->get_results($sql,ARRAY_A);
		return $r;

	}
	function get_global_brands(){
		$sql = "
		SELECT a.term_id, a.name, a.slug 
		FROM wp_terms a
		INNER JOIN wp_term_taxonomy b ON a.term_id = b.term_id
		WHERE b.taxonomy='pa_global-brand'		
		";
		$r=$this->db->get_results($sql,ARRAY_A);
		return $r;
	}
	function get_config(){
		$sql="SELECT * from wi_collection_config";
		$r=$this->db->get_results($sql);
		return $r;
	}
	function collconf_save(){
		
		$post = json_decode(file_get_contents('php://input'), true);

		$index = $post["index"] ?? -1;
		$id=(int)$post["id"]??0;
		$post=[
			"division" =>  $post["division"]??"",
			"global_brand" =>  $post["global_brand"],
			"brand" =>  $post["brand"],
			"department" =>  $post["department"],
			"collection" =>  $post["collection"],
			"link_pdf" =>  $post["link_pdf"]??"",
			"link_catalog" =>  $post["link_catalog"]??"",
			"show_presale" => $post['show_presale'] ?? "0"
		];
		

		if($id>0){
			$this->db->update("wi_collection_config",$post,["id"=>$id]);
		}else{
			$this->db->insert("wi_collection_config",$post);
		}

		$config = $this->get_config();
		
		//update_option($this->config_key,json_encode($config),false);

		
		echo wp_send_json($config);
		wp_die();
	}
	function collconf_get(){
		//$config = json_decode(get_option($this->config_key));
		$config = $this->get_config();
		echo wp_send_json($config);
		wp_die();
	}
	function collconf_delete(){
		$post = json_decode(file_get_contents('php://input'), true);

		$id = $post["id"] ?? 0;

		if($id>0){
			$this->db->delete("wi_collection_config",["id"=>$id]);
		}
		$config = $this->get_config();
		echo wp_send_json($config);
		wp_die();
	}

	function admin_user_link( $actions, $user_object ) {
		
		$actions['collections'] = '<a href="'.get_site_url()."/wp-admin/admin.php?page=wi_collection_config&view=user&user_id=".$user_object->ID.'" >Collections</a>'; 
		return $actions;
	}

	function collconf_user_save(){
		
		$post = json_decode(file_get_contents('php://input'));

		$user_id = (int)$post->user??0;
		$config = $post->config??null;
 
		update_user_meta($user_id,$this->config_key,json_encode($config));

		$json["error"]=0;
		$json["config"]  = $config;
		echo wp_send_json($json);
		wp_die();
	}
	
	function menu_item_collection_config($item_id=0) {
		
		//$_config = json_decode(get_option($this->config_key));
		$_config = $this->get_config();
		$menu_item_collection =  get_post_meta( $item_id, '_menu_item_collection', true );
		
		//usort($_config,function($a,$b){return strcmp($a["brand"], $b["brand"]);});
		$config=[];
		foreach($_config as $c){
			if(!isset($config[$c->brand])){
				$config[$c->brand] = ["name"=>$brands[$c->brand]??"","slug"=>$c->brand,"departments"=>[]];
			}
			if(!isset($config[$c->brand]["departments"][$c->department])){
				$config[$c->brand]["departments"][$c->department] = ["name"=>$departments[$c->department]??"","slug"=>$c->department,"collections"=>[]];
			}
			$config[$c->brand]["departments"][$c->department]["collections"][]=["name"=>$collections[$c->collection]??"","slug"=>$c->brand."-".$c->department."-".$c->collection,"coll"=>$c->collection,"id"=>$c->id];
		}
		
		$html= '<p class="description-wide">
		<label>Collection<br>
		<input type="hidden" class="nav-menu-id" value="'.$item_id.'" />
		<select name="menu_item_collection['.$item_id.']" id="menu-item-desc-'.$item_id.'"><option value="">-Select-</option>';
		foreach($config as $brand){
			$html.='<optgroup label="'.$brand["slug"].'">';
			foreach($brand["departments"] as $dep){
				$html.='<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;'.$dep["slug"].'">';
				$html.='<option value="menu_'.$brand["slug"].':'.$dep["slug"].'" '.("menu_".$brand["slug"].':'.$dep["slug"]==$menu_item_collection?'selected':'').'>&nbsp;&nbsp;&nbsp;&nbsp;Menu '.$brand["slug"]. " " .$dep["slug"]."</option>";
				foreach($dep["collections"] as $coll){
					$html.='<option value="'.$coll["id"].'" '.($coll["id"]==$menu_item_collection?'selected':'').'>&nbsp;&nbsp;&nbsp;&nbsp;'.$coll["coll"]."</option>";
				}
				$html.='</optgroup>';
			}
			$html.='</optgroup>';
		}
		$html.='</select>
		</label>
		</p>';

		echo $html;
	}
	
	function menu_item_collection_config_save( $menu_id, $menu_item_db_id ) {
		if ( isset( $_POST['menu_item_collection'][$menu_item_db_id]  ) ) {
			$sanitized_data = sanitize_text_field( $_POST['menu_item_collection'][$menu_item_db_id] );
			update_post_meta( $menu_item_db_id, '_menu_item_collection', $sanitized_data );
		} else {
			delete_post_meta( $menu_item_db_id, '_menu_item_collection' );
		}
	}
	function show_menu_item_collection( $classes, $item ) {
		
		if( is_object( $item ) && isset( $item->ID ) ) {
			$menu_item_collection = get_post_meta( $item->ID, '_menu_item_collection', true );
			if ( ! empty( $menu_item_collection ) ) {
				$classes[]="collection-item-menu";
				$classes[]="coll-".$menu_item_collection;
				
			}
		}
		return $classes;
	}
	function show_collection_for_user(){
		$user_id=get_current_user_id();
		//$user_config = get_user_meta($user_id,$this->config_key);
		//$user_config = isset($user_config[0]) ? json_decode($user_config[0]):[];
		$user_config = $this->config_user_by_role($user_id);
		if(count($user_config)>0){
			echo '<style id="menu-coll-style"> .mega-collection-item-menu{ display:none} ';
			foreach($user_config as $uc){
				echo '.mega-coll-'.$uc.'{ display:list-item;}';
			}
			echo '</style>';
		}
	}

	function shortcode_user_collections($args=[]){
		$brand = $args["brand"]??"";
		$brand_filter = $brand!=""? explode(",",$brand):[];

		$global_brand = $args["global_brand"]??"";
		echo "<!-- global brand {$global_brand}  -->";
		global $user_config,$config;
		if($user_config==null){
			$user_id=get_current_user_id();
			/* $user_config = get_user_meta($user_id,$this->config_key);
			$user_config = isset($user_config[0]) ? json_decode($user_config[0]):[]; */
			$user_config = $this->config_user_by_role($user_id);
			
		}
		if($config==null){

			$_config =  $this->get_config();
			/* echo "<pre style='display:none'>";
			print_r($_config);
			echo "</pre>"; */
			$config=[];
			$brands      = $this->get_brands();
			$collections = $this->get_collections( );
			$brands      = array_column($brands,"name","slug");
			$collections = array_column($collections,"name","slug");
			$departments = array_column($this->departments,"name","slug");
			
			foreach($_config as $c){
				if($c->show_presale == 0){
					continue;
				}
				
				if(!isset($config[$c->brand])){
					$config[$c->brand] = ["name"=>$brands[$c->brand]??"","slug"=>$c->brand,"departments"=>[]];
				}
				if(!isset($config[$c->brand]["departments"][$c->department])){
					$config[$c->brand]["departments"][$c->department] = ["name"=>$departments[$c->department]??"","slug"=>$c->department,"collections"=>[]];
				}
				$link_catalog = isset($c->link_catalog) && $c->link_catalog!="" ? $c->link_catalog.($c->global_brand!='' && $global_brand==$c->global_brand ? "&pa_global-brand=".$c->global_brand :'') :(get_site_url()."/product-category/presale/?f_division=".$c->division."&f_bc_brand[]=".$c->brand."&filter_gender=".$c->department."&f_bc_collection[]=".$c->collection.($c->global_brand!='' && $global_brand==$c->global_brand ? "&pa_global-brand=".$c->global_brand :''));

				$config[$c->brand]["departments"][$c->department]["collections"][]=[
					"name"=>$collections[$c->collection]??"",
					"slug"=>$c->collection,
					"department" =>$c->department,
					"brand" =>$c->brand,
					"pdf" =>$c->link_pdf??"",
					"link_catalog" =>$link_catalog,//$c->link_catalog??"",
					"id" =>$c->id,
					"division"=>$c->division,
					"global_brand" => $c->global_brand
				];
			}
		}

	
	 
		$departments=[];
		foreach($brand_filter as $brand):
			if(isset($config[$brand])){
				if(count($user_config)>0){
					foreach($config[$brand]["departments"] as $dep){
						if(!isset($departments[$dep["slug"]])){
							$departments[$dep["slug"]]=[
								"name" => $dep["name"],
								"collections"=>[]
							];
						}
						foreach($dep["collections"] as $c):
							if(in_array($c["id"],$user_config)){
								$departments[$dep["slug"]]["collections"][]=$c;
							}
						endforeach;
						if(count($departments[$dep["slug"]]["collections"])==0){
							unset($departments[$dep["slug"]]);
						}
						
					}
				}else{
					foreach($config[$brand]["departments"] as $dep){
						if(!isset($departments[$dep["slug"]])){
							$departments[$dep["slug"]]=[
								"name" => $dep["name"],
								"slug" => $dep["slug"],
								"collections"=>[]
							];
						}
						foreach($dep["collections"] as $c):
							if($global_brand=='' OR $global_brand==$c['global_brand']){
								$departments[$dep["slug"]]["collections"][]=$c; 
							}
							/* $departments[$dep["slug"]]["collections"][]=array_filter($c,function($item) use( $global_brand){
								return $global_brand=='' OR $global_brand==$item['global_brand'];
							});  */
						endforeach;
						
						
					}
				}
				
				
			}
		endforeach;

		$_tmp_dep = [];
		if(isset($departments["men"]))  $_tmp_dep["men"]  = $departments["men"];
		if(isset($departments["women"]))  $_tmp_dep["women"]  = $departments["women"];
		if(isset($departments["kids"]))  $_tmp_dep["kids"]  = $departments["kids"];
		if(isset($departments["accesories"]))  $_tmp_dep["accesories"]  = $departments["accesories"];
		if(isset($departments["teens"]))  $_tmp_dep["teens"]  = $departments["teens"];

		
		if(isset($args["department"]) && $args["department"]!="" ){
			$departments=[];
			$departments[$args["department"]] =  isset( $_tmp_dep[$args["department"]])? $_tmp_dep[$args["department"]]:[];
		}else{
			$departments = $_tmp_dep;
		}
		unset($_tmp_dep);
		ob_start();
		include(WI_PLUGIN_COLL_PATH."/shortcodes/shortcode-user-collections.php");
		$html = ob_get_clean();
		return $html;
		 

		

	}

 
	function wi_collection_roles_admin_page_init(){
		$roles = $this->roles_get();
		
		$brands = $this->get_brands();
		$ids_brands = array_column($brands,"term_id");
		$collections = $this->get_collections($ids_brands);
		$brands      = array_column($brands,"name","slug");
		$collections = array_column($collections,"name","slug");
		
		$departments  = array_column($this->departments,"name","slug");
		
		$roles_config = $this->roles_config_get();
		$_config =  $this->get_config();
		
		$config=[];
		foreach($_config as $c){
			if(!isset($config[$c->brand])){
				$config[$c->brand] = ["name"=>$brands[$c->brand]??"","slug"=>$c->brand,"departments"=>[]];
			}
			if(!isset($config[$c->brand]["departments"][$c->department])){
				$config[$c->brand]["departments"][$c->department] = ["name"=>$departments[$c->department]??"","slug"=>$c->department,"collections"=>[]];
			}
			$config[$c->brand]["departments"][$c->department]["collections"][]=["name"=>$collections[$c->collection]??"","slug"=>$c->collection,"id"=>$c->id,"show_presale"=>$c->show_presale];
		}

		include(WI_PLUGIN_COLL_PATH."/views/view-roles-collections.php");
	}
	function roles_get(){
		$roles =[];
		$sql="SELECT * FROM wp_options op WHERE op.option_name = 'wp_user_roles'";

		$r=$this->db->get_row($sql);
		 
		if(isset($r->option_name)){
			$roles = unserialize($r->option_value);
			foreach ($roles as $key=> $item) {
				$roles[$key]['key'] = $key;
			}
 
			usort($roles,function($a,$b){ return strcmp(trim($a["name"]),trim($b["name"]));});
		}
		
		return $roles;
	}

	function collconf_role_save(){
		
		$post = json_decode(file_get_contents('php://input'));

		$role =  trim($post->role??0);
		$config = $post->config??null;
 
		$data=array();
		$this->db->delete("wi_collection_roles",array("role"=>$role));
		foreach($config as $id){
			$tmp = array();
			$tmp["id_collection"] = $id;
			$tmp["role"] = $role;

			$this->db->insert("wi_collection_roles",$tmp);
			
		}

		$roles_config = $this->roles_config_get();

		$json["error"]=0;
		$json["roles_config"]  = $roles_config;
		echo wp_send_json($json);
		wp_die();
	}
	function roles_config_get(){
		$sql = "SELECT GROUP_CONCAT(a.id_collection)collections,a.role from wi_collection_roles a ";
		 
		$sql.="GROUP BY a.role";
		$_r=$this->db->get_results($sql);

		$r=array();
		foreach($_r as $item){
			$r[$item->role]=explode(',',$item->collections);
		}
		return $r;

	}
	function config_user_by_role($user_id){
		//$user_id=557;
		$user_config = get_user_meta($user_id,"wp_capabilities");
	 
		$user_config = is_array($user_config ) && count($user_config )>0 ? array_keys($user_config[0]) : [] ;
 
		$sql = "SELECT distinct id_collection from wi_collection_roles a WHERE a.role IN ('".implode("','",$user_config)."')";
		//echo $sql;
		$r = $this->db->get_results($sql,ARRAY_A);
		if(is_array($r) && count($r)>0){
		
			$user_config = array_column($r,"id_collection");
		}else{
			$user_config = [];
		}

		return $user_config;
		

	}

	function custom_menu_item_content($content, $item) {
		// Add your custom text before and after the menu item
		$after_text = "";
		if( is_object( $item ) && isset( $item->ID ) ) {
			$menu_item_collection = get_post_meta( $item->ID, '_menu_item_collection', true );
			if ( ! empty( $menu_item_collection ) ) {
			 
				//$after_text = "<!-- ".$this->render_menu_collections($menu_item_collection)." -->";
				$after_text =  $this->render_menu_collections($menu_item_collection);
			}
		}

		 
		return $content . $after_text ;
		 
		//return $before_text . $content . $after_text;
	}
	function render_menu_collections($menu_name=""){
		$is_menu = strpos($menu_name,"menu_") !== false ;
		 
		if($is_menu){
			$brand_dep = explode(":",str_replace("menu_","",$menu_name));
			$brand = $brand_dep[0];
			$department = $brand_dep[1];

			global $user_config,$config;
			if($user_config==null){
				$user_id=get_current_user_id();
				/* $user_config = get_user_meta($user_id,$this->config_key);
				$user_config = isset($user_config[0]) ? json_decode($user_config[0]):[]; */
				$user_config = $this->config_user_by_role($user_id);
				
			}
			if($config==null){

				$_config =  $this->get_config();
			
				$config=[];
				$brands      = $this->get_brands();
				$collections = $this->get_collections( );
				$brands      = array_column($brands,"name","slug");
				$collections = array_column($collections,"name","slug");
				$departments = array_column($this->departments,"name","slug");
				
				foreach($_config as $c){
					if($c->show_presale == 0){
						continue;
					}
					if(!isset($config[$c->brand])){
						$config[$c->brand] = ["name"=>$brands[$c->brand]??"","slug"=>$c->brand,"departments"=>[]];
					}
					if(!isset($config[$c->brand]["departments"][$c->department])){
						$config[$c->brand]["departments"][$c->department] = ["name"=>$departments[$c->department]??"","slug"=>$c->department,"collections"=>[]];
					}
					$link_catalog = isset($c->link_catalog) && $c->link_catalog!="" ? $c->link_catalog :(get_site_url()."/product-category/presale/?f_division=".$c->division."&f_bc_brand[]=".$c->brand."&filter_gender=".$c->department."&f_bc_collection[]=".$c->collection);

					$config[$c->brand]["departments"][$c->department]["collections"][]=[
						"name"=>$collections[$c->collection]??"",
						"slug"=>$c->collection,
						"department" =>$c->department,
						"brand" =>$c->brand,
						"pdf" =>$c->link_pdf??"",
						"link_catalog" =>$link_catalog,//$c->link_catalog??"",
						"id" =>$c->id,
						"division"=>$c->division
					];
				}
			}

		
		
			$departments=[];
			if(isset($config[$brand])){
				if(count($user_config)>0){
					foreach($config[$brand]["departments"] as $dep){
						if(!isset($departments[$dep["slug"]])){
							$departments[$dep["slug"]]=[
								"name" => $dep["name"],
								"collections"=>[]
							];
						}
						foreach($dep["collections"] as $c):
							if(in_array($c["id"],$user_config)){
								$departments[$dep["slug"]]["collections"][]=$c;
							}
						endforeach;
						if(count($departments[$dep["slug"]]["collections"])==0){
							unset($departments[$dep["slug"]]);
						}
						
					}
				}else{
					foreach($config[$brand]["departments"] as $dep){
						if(!isset($departments[$dep["slug"]])){
							$departments[$dep["slug"]]=[
								"name" => $dep["name"],
								"slug" => $dep["slug"],
								"collections"=>[]
							];
						}
						foreach($dep["collections"] as $c):			 
							$departments[$dep["slug"]]["collections"][]=$c; 
						endforeach;
						
						
					}
				}
				
				
			}
			$_tmp_dep = [];
			if(isset($departments["men"]))  $_tmp_dep["men"]  = $departments["men"];
			if(isset($departments["women"]))  $_tmp_dep["women"]  = $departments["women"];
			if(isset($departments["kids"]))  $_tmp_dep["kids"]  = $departments["kids"];
			if(isset($departments["accesories"]))  $_tmp_dep["accesories"]  = $departments["accesories"];
			if(isset($departments["teens"]))  $_tmp_dep["teens"]  = $departments["teens"];

			
			if($department!=""){
				$departments=[];
				$departments[$department] =  isset( $_tmp_dep[$department])? $_tmp_dep[$department]:[];
			} 
			unset($_tmp_dep);
			ob_start();
		/* 	echo "<pre>";
			print_r($departments);
			echo "</pre>"; */
			include(WI_PLUGIN_COLL_PATH."/shortcodes/shortcode-menu-collections.php");
			$html = ob_get_clean();
			return $html;

		}
	}
	
}