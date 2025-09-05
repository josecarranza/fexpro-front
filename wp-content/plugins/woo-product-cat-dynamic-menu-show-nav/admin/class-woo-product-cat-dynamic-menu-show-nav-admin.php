<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.webindiainc.com
 * @since      1.0.0
 *
 * @package    Woo_Product_Cat_Dynamic_Menu_Show_Nav
 * @subpackage Woo_Product_Cat_Dynamic_Menu_Show_Nav/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Product_Cat_Dynamic_Menu_Show_Nav
 * @subpackage Woo_Product_Cat_Dynamic_Menu_Show_Nav/admin
 * @author     Vishal <vishalrathod@webindiainc.com>
 */
class Woo_Product_Cat_Dynamic_Menu_Show_Nav_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Product_Cat_Dynamic_Menu_Show_Nav_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Product_Cat_Dynamic_Menu_Show_Nav_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-product-cat-dynamic-menu-show-nav-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Product_Cat_Dynamic_Menu_Show_Nav_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Product_Cat_Dynamic_Menu_Show_Nav_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-product-cat-dynamic-menu-show-nav-admin.js', array( 'jquery' ), $this->version, false );

	}


	public function text_domain_taxonomy_add_new_meta_field() {
    ?>
	    <div class="form-field">
	        <label for="term_meta[cat_show_menu_front]"><?php _e('Category Show Menu', 'text_domain'); ?></label>
	        <input type="checkbox" name="term_meta[cat_show_menu_front]" id="term_meta_cat_show_menu_front" value="1" />
	    </div>
	    <?php
	}


	/*public function text_domain_taxonomy_edit_meta_field($term) {

	    //getting term ID
	    $term_id = $term->term_id;

	    // retrieve the existing value(s) for this meta field. This returns an array
	    $term_meta = get_option("taxonomy_" . $term_id);

	    $menu_name = 'main_menu'; //menu slug
		$locations = get_nav_menu_locations();
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
		$menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) );
		
		$childClass="none";
		if($term_meta['cat_show_menu_front'] == true){
			$childClass="block";
		}
	    ?>
	    <tr class="form-field">
	        <th scope="row" valign="top"><label for="term_meta[cat_show_menu_front]"><?php _e('Category Show Menu', 'text_domain'); ?></label></th>
	        <td>
	            <input type="checkbox" name="term_meta[cat_show_menu_front]" id="term_meta_cat_show_menu_front" value="1" <?php if($term_meta['cat_show_menu_front'] == true)  { echo "checked"; } else {echo '';} ?> />

	            <div class="catShowMenuChildRadio" style="display: <?php echo $childClass; ?>;    padding: 10px 10px 10px 10px; background: #e4e4e4; margin-top: 25px;">
	            	<?php if (!empty($menuitems)) : ?>
	            		<?php 
	            			foreach ($menuitems as $value)  :  	
    							if ( !$value->menu_item_parent ):  // Only parent Item lists 
	            			?>

	            			<input type="radio" id="<?= $value->post_title; ?>" name="term_meta[daynamic_menu_id]" value="<?= $value->ID; ?>" <?php echo ($value->ID== $term_meta['daynamic_menu_id']) ?  "checked" : "" ;  ?> />
	            			<label for="<?= $value->post_title; ?>"><?= $value->post_title; ?></label> <BR>

	            		<?php endif; endforeach; ?>
	            	<?php endif; ?>
	            </div>

	        </td>


	    </tr>

	    
	    <?php
	}


	public function save_taxonomy_custom_meta($term_id) {
		
		if(!$_POST['term_meta']['cat_show_menu_front']){
			$_POST['term_meta']['cat_show_menu_front'] = 0;
			$_POST['term_meta']['remove_menu_id'] = $term_id;
			$_POST['term_meta']['daynamic_menu_id'] = "";
		}
		
		if (isset($_POST['term_meta'])) {
	    	
	        $term_meta = get_option("taxonomy_" . $term_id);

	        if(!empty($term_meta['daynamic_menu_id'])){
	        	if($term_meta['switch_menu_id'] != $term_meta['daynamic_menu_id']){
	        		$_POST['term_meta']['switch_menu_id'] = $term_meta['daynamic_menu_id'];
	        	}
	        }
      

	        $cat_keys = array_keys($_POST['term_meta']);

	        //die;
	        foreach ($cat_keys as $key) {
	            if (isset($_POST['term_meta'][$key])) {
	                $term_meta[$key] = $_POST['term_meta'][$key];
	            }
	        }


	       
	        // Save the option array.
	        update_option("taxonomy_" . $term_id, $term_meta);
	       

	    }
	}*/







}
