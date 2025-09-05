<?php

namespace Barn2\Plugin\WC_Bulk_Variations;

/**
 * Responsible for managing the product table columns.
 *
 * @package   Barn2\woocommerce-bulk-variations
 * @author    Barn2 Plugins <support@barn2.co.uk>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
class WC_Bulk_Variations_Table_Columns {

	/**
	 * @var WC_bulk_variations_table_Args The table args.
	 */
	public $args;

	public function __construct( WC_Bulk_Variations_Args $args ) {
		$this->args = $args;
	}

	public function get_all_columns() {
		return array_merge( $this->get_base_columns(), $this->get_columns(), $this->get_hidden_columns() );
		//print_r($return_arr);
	}
	
	public function get_all_team_attributes() {
		global $product;


		/* $row_array = array();
		array_push($row_array,$column);
		print_r($row_array); */
		//echo $index;
		//echo $product->get_attribute( 'pa_team' );
		//$print=preg_replace('/^([^,]*).*$/', '$2', $product->get_attribute( 'pa_team' ));
		//print_r($product->id);
		
		//return $product->get_attribute( 'pa_team' );
		return $product->id;
		//print_r($return_arr);
	}
	
	public function get_base_columns() {
		return $this->args->base_columns;
	}

	public function get_columns() {
		return $this->args->attribute_columns;
	}

	public function get_hidden_columns() {
		$hidden = array();

		return $hidden;
	}

	public function column_index( $column, $incude_hidden = false ) {
		$cols	 = $incude_hidden ? $this->get_all_columns() : $this->get_columns();
		$index	 = array_search( $column, $cols );
		$index	 = is_int( $index ) ? $index : false; // sanity check

		return $index;
	}

	public function column_indexes( $columns, $include_hidden = false ) {
		return array_map( array( $this, 'column_index' ), $columns, array_fill( 0, count( $columns ), $include_hidden ) );
	}

	public function get_column_header_class( $index, $column ) {
		$class = array( self::get_column_class( $column ) );
		/*echo "<pre>";
		print_r($column);
		echo "</pre>";*/

		return implode( ' ', apply_filters( 'wc_bulk_variations_table_column_class_' . self::unprefix_column( $column ), $class ) );
	}

	public function get_column_heading( $index, $column ) {
		/* $row_array = array();
		array_push($row_array,$column);
		print_r($row_array); */
		//echo $index;
		//echo $product->get_attribute( 'pa_team' );
		//$print=preg_replace('/^([^,]*).*$/', '$2', $product->get_attribute( 'pa_team' ));
		/* $getTeams = $this->get_all_team_attributes();
		$product = wc_get_product($getTeams);
		$current_products = $product->get_children();
		//print_r($current_products);

		if(!empty($getTeams))
		{
			$str_arr = $current_products; 
		}
		else
		{
			$str_arr = '';
		} */		
			
		$heading		 = '';

		$unprefixed_col	 = self::unprefix_column( $column );

				
		if ( $att = $this->get_product_attribute( $column ) ) {
			$heading = Util\Util::get_attribute_label( $att );
		} else {
			$heading = trim( str_replace( array( '_', '-' ), ' ', $unprefixed_col ) );
		}
		$heading = preg_replace('/[0-9]+/', '', $heading);
		$heading = str_replace( '()', ' - ', $heading );
		if(!empty($str_arr))
		{
			if(get_post_meta( $str_arr[$index-1], 'product_team', true ))
			{
					$heading = get_post_meta( $str_arr[$index-1], 'product_team', true ) . ' - '. $heading;
			}
		}
		else
		{
			$heading = $heading;
		}
		

		$heading = apply_filters( 'wc_bulk_variations_table_column_heading_' . $unprefixed_col, $heading );

		return $heading;
	}

	public function is_product_attribute( $column ) {
		
		if ( ( $this->args->attribute_columns && in_array( $column, $this->args->attribute_columns ) ) || ( $this->args->attribute_rows && in_array( $column, $this->args->attribute_rows ) ) ) {
			return true;
		}
		
		return false;
	}

	public function get_product_attribute( $column ) {
		if ( $this->is_product_attribute( $column ) ) {
			return $column;
		}
		return false;
	}

	public static function unprefix_column( $column ) {
		if ( false !== ( $str = strstr( $column, ':' ) ) ) {
			$column = substr( $str, 1 );
		}
		return $column;
	}

	public static function get_column_class( $column ) {
		return Util\Util::sanitize_class_name( 'col-' . self::unprefix_column( $column ) );
	}

	public static function get_column_data_source( $column ) {
		// '.' not allowed in data source
		return str_replace( '.', '', $column );
	}
}