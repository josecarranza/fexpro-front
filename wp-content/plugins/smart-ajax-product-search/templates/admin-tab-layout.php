<div id="layout_tab" class="ymapp-settings__content">
	<table class="form-table">
		<tbody>

		<?php
		ysm_setting( $w_id, 'popup_height', array(
			'type' => 'text',
			'title' => __( 'Popup Max Height', 'smart_search' ),
			'description' => __( 'Popup max height (px). Default is 400px', 'smart_search' ),
			'value' => 400,
		));

		ysm_setting( $w_id, 'display_icon', array(
			'type' => 'checkbox',
			'title' => __('Display Image', 'smart_search'),
			'description' => __('Display featured image in results output', 'smart_search'),
			'value' => 1,
		));

		ysm_setting( $w_id, 'variation_thumb_fallback', array(
			'type'        => 'checkbox',
			'title'       => __( 'Variation Image Fallback', 'smart_search' ),
			'description' => __( 'Display featured image of the parent variable product if variation does not have a thumbnail', 'smart_search' ),
			'value'       => 1,
		));

		ysm_setting( $w_id, 'popup_thumb_size', array(
			'type' => 'text',
			'title' => __('Image Size', 'smart_search'),
			'description' => __('Search results featured image size (px)', 'smart_search'),
			'value' => '50',
		));

		ysm_setting( $w_id, 'display_excerpt', array(
			'type' => 'checkbox',
			'title' => __('Display Excerpt', 'smart_search'),
			'description' => __('Display excerpt in results output', 'smart_search'),
			'value' => 1,
		));

		ysm_setting( $w_id, 'display_price', array(
			'type' => 'checkbox',
			'title' => __('Display Price', 'smart_search'),
			'description' => __('Display product price', 'smart_search'),
			'value' => 1,
		));

		ysm_setting( $w_id, 'display_sku', array(
			'type' => 'checkbox',
			'title' => __('Display SKU', 'smart_search'),
			'description' => __('Display product SKU', 'smart_search'),
			'value' => 1,
		));

		ysm_setting( $w_id, 'display_out_of_stock_label', array(
			'type' => 'checkbox',
			'title' => __('Display "Out of stock" Label', 'smart_search'),
			'description' => __('Display "Out of stock" label if product is not in stock', 'smart_search'),
			'value' => 1,
		));

		ysm_setting( $w_id, 'display_sale_label', array(
			'type' => 'checkbox',
			'title' => __('Display "Sale" Label', 'smart_search'),
			'description' => __('Display "Sale" label for product', 'smart_search'),
			'value' => 1,
		));

		ysm_setting( $w_id, 'display_featured_label', array(
			'type' => 'checkbox',
			'title' => __('Display "Featured" Label', 'smart_search'),
			'description' => __('Display "Featured" label for product', 'smart_search'),
			'value' => 1,
		));

		ysm_setting( $w_id, 'display_add_to_cart', array(
			'type' => 'checkbox',
			'title' => __( 'Display "Add to Cart" Button', 'smart_search' ),
			'description' => __('Display "Add to Cart" Button for product', 'smart_search'),
			'value' => 0,
		));
		/*
								ysm_setting( $w_id, 'display_rating', array(
									'type' => 'checkbox',
									'title' => __('Display Rating', 'smart_search'),
									'description' => __('Display product rating', 'smart_search'),
									'value' => 1,
								));


								ysm_setting( $w_id, 'display_product_categories', array(
									'type' => 'checkbox',
									'title' => __('Display Product Categories', 'smart_search'),
									'description' => __('Display product categories', 'smart_search'),
									'value' => 1,
								));
		*/

		ysm_setting( $w_id, 'popup_desc_pos', array(
			'type' => 'select',
			'title' => __('Excerpt Position', 'smart_search'),
			'description' => __('Excerpt position in results popup', 'smart_search'),
			'value' => '',
			'choices' => array(
				'below_image' => __('Below image', 'smart_search'),
				'below_title' => __('Below title', 'smart_search'),
				'below_price' => __('Below price and SKU', 'smart_search'),
			),
		));

		?>

		</tbody>
	</table>
</div>