<?php 
class WiFexproMenu{
	public $model;

	public function __construct() {
		$this->model = new WiFexproMenuModel();
		
		add_action('admin_menu', array($this,"wi_fexpro_menu_setup_menu")  );
		
		// add_action('wp_ajax_collconf_save', array($this,"collconf_save"));
        // add_action('wp_ajax_nopriv_collconf_save', array($this,"collconf_save"));
 
		add_action( 'admin_enqueue_scripts', [$this,'load_wp_media_files'] );

		add_action( 'rest_api_init', array($this, 'register_endpoint_api') );

		add_shortcode('fexpro_menu', [$this,'shortcode_fexpro_menu']);
	 
	}
	function register_endpoint_api(){	
		register_rest_route( 'api_menu/v1', '/save_menu/', array(
			'methods' => 'POST',
			'callback' => array($this,"save_menu"),
		) );
	}
	function wi_fexpro_menu_setup_menu(){
 
		add_menu_page( 'Fexpro Menu', 'Fexpro Menu', 'manage_options', 'wi_fexpro_menu','','');
		 
		add_submenu_page(
			'wi_fexpro_menu',
			"Menu",
			"Menu",
			'manage_options',
			'wi_fexpro_menu',
			array($this,"page_menu")
		);
		
	}
	 
	function page_menu(){
		$menus = $this->model->menu_list_get();
		$id_menu = isset($_GET['id_menu'])?$_GET['id_menu'] : $menus[0]->id_menu;

		$menu_json = $this->model->getMenuJson($id_menu);
		 
		include(WI_PLUGIN_FEXPRO_MENU_PATH."/views/page-menu.php");
	}
	function load_wp_media_files( $page ) {
		
		// change to the $page where you want to enqueue the script
		if( $page == 'toplevel_page_wi_fexpro_menu' ) {
		  // Enqueue WordPress media scripts
		  wp_enqueue_media();
		  // Enqueue custom script that will interact with wp.media
		  wp_enqueue_script( 'myprefix_script', plugins_url( '/js/myscript.js' , __FILE__ ), array('jquery'), '0.1' );
		}
	}
	function save_menu($data){
		$post = json_decode(file_get_contents("php://input"));
		
		$id_menu = $post->id_menu ?? 0;
		$menu = $post->menu;

		if(is_array($menu)){
			foreach ($menu as $i=> $lvl0) {
				$item_0 = $this->getNode($lvl0);
				$item_0['id_menu'] = $id_menu;
				$item_0['level'] = 0;
				$item_0['parent'] = 0;

				$id_item_lvl0 = $this->model->save_menu_item($item_0);

				if(isset($lvl0->items) && count($lvl0->items)>0){
					foreach($lvl0->items as $j => $lvl1){
						$item_1 = $this->getNode($lvl1);
						$item_1['id_menu'] = $id_menu;
						$item_1['level'] = 1;
						$item_1['parent'] = $id_item_lvl0;

						$id_item_lvl1 = $this->model->save_menu_item($item_1);

						if(isset($lvl1->items) && count($lvl1->items)>0){
							foreach($lvl1->items as $k => $lvl2){
								$item_2 = $this->getNode($lvl2);
								$item_2['id_menu'] = $id_menu;
								$item_2['level'] = 2;
								$item_2['parent'] = $id_item_lvl1;
								
								$id_item_lvl2 = $this->model->save_menu_item($item_2);

								if(isset($lvl2->items) && count($lvl2->items)>0){
									foreach($lvl2->items as $l => $lvl3){
										$item_3 = $this->getNode($lvl3);
										$item_3['id_menu'] = $id_menu;
										$item_3['level'] = 3;
										$item_3['parent'] = $id_item_lvl2;
										
										$id_item_lvl3 = $this->model->save_menu_item($item_3);
									}
								}
							}
						}
					}
				}

			}
		}

		$json['error'] = 0;
		wp_send_json($json);


	}

	function getNode($data){
		$item = [];
		$item['id_menu_item'] = $data->id_menu_item ?? 0;
		$item['title'] = $data->title ?? "";
		$item['link'] = $data->link ?? "";
		$item['order'] = $data->order??0;
		

		$_config=[];
		$_config['hide_title'] = $data->hide_title ?? 0;
		$_config['css'] = $data->css ?? null;
		$_config['image'] = $data->image??null;
		$_config['image_hover'] = $data->image_hover??null;
		$item['config'] = json_encode($_config);

		return $item;
	}

	function shortcode_fexpro_menu($atts=[]){
		$id_menu = isset($atts['id_menu'])?$atts['id_menu']:1;
		$css_class = isset($atts['css_class'])?$atts['css_class']:"";
		$menu_json = $this->model->getMenuJson($id_menu);
		ob_start();
		include(WI_PLUGIN_FEXPRO_MENU_PATH."/shortcodes/shortcode-menu-render.php");
		$html = ob_get_clean();

		return $html;
	}
		   
}