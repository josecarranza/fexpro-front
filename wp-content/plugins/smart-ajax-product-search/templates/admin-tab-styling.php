<div id="styles_tab" class="ymapp-settings__content">
	<table class="form-table">
		<tbody>

		<?php if ( ! in_array( $w_id, ysm_get_default_widgets_ids(), true ) ) { ?>

			<th class="ymapp-settings__title"><?php esc_html_e( 'Input Field', 'smart_search' ); ?></th>

			<?php
			ysm_setting( $w_id, 'input_round_border', array(
				'type' => 'checkbox',
				'title' => __('Rounded border', 'smart_search'),
				'description' => __('Display search field with rounded border', 'smart_search'),
				'value' => '',
			));

			ysm_setting( $w_id, 'input_border_color', array(
				'type' => 'color',
				'title' => __('Border Color', 'smart_search'),
				'description' => __('Search field border color', 'smart_search'),
				'value' => '',
			));

			ysm_setting( $w_id, 'input_border_width', array(
				'type' => 'text',
				'title' => __('Border Width, px', 'smart_search'),
				'description' => __('Search field border width in pixels', 'smart_search'),
				'value' => '1',
			));

			ysm_setting( $w_id, 'input_text_color', array(
				'type' => 'color',
				'title' => __('Text Color', 'smart_search'),
				'description' => __('Search field text color', 'smart_search'),
				'value' => '',
			));

			ysm_setting( $w_id, 'input_bg_color', array(
				'type' => 'color',
				'title' => __('Background Color', 'smart_search'),
				'description' => __('Search field background color', 'smart_search'),
				'value' => '',
			));

			ysm_setting( $w_id, 'input_icon_color', array(
				'type' => 'color',
				'title' => __('Icon Color', 'smart_search'),
				'description' => __('Search field icon color', 'smart_search'),
				'value' => '',
			));

			ysm_setting( $w_id, 'input_icon_bg', array(
				'type' => 'color',
				'title' => __('Icon Background', 'smart_search'),
				'description' => __('Search field icon background', 'smart_search'),
				'value' => '',
			));

			$cur_loader = ysm_get_option($w_id, 'loader');
			if ( is_array( $cur_loader ) ) {
				$cur_loader = $cur_loader[0];
			}
			ysm_setting( $w_id, 'loader', array(
				'type' => 'select',
				'title' => __('Loader', 'smart_search'),
				'description' => __('Select loader', 'smart_search') .
					' <img style="margin-left:20px;" class="ysm-loader-preview" src="' .
					YSM_URI . 'assets/images/' .
					( $cur_loader ? $cur_loader : 'loader1' ) .
					'.gif">',
				'value' => 'loader1',
				'choices' => array(
					'loader1' => __('Loader', 'smart_search') . ' 1',
					'loader2' => __('Loader', 'smart_search') . ' 2',
					'loader3' => __('Loader', 'smart_search') . ' 3',
					'loader4' => __('Loader', 'smart_search') . ' 4',
					'loader5' => __('Loader', 'smart_search') . ' 5',
				)
			));
			?>

		<?php } ?>

		<th class="ymapp-settings__title"><?php esc_html_e( 'Results Popup Elements', 'smart_search' ); ?></th>

		<?php
		ysm_setting( $w_id, 'popup_border_color', array(
			'type' => 'color',
			'title' => __('Border Color', 'smart_search'),
			'description' => __('Popup border color', 'smart_search'),
			'value' => '',
		));

		ysm_setting( $w_id, 'popup_bg_color', array(
			'type' => 'color',
			'title' => __('Background Color', 'smart_search'),
			'description' => __('Popup background color', 'smart_search'),
			'value' => '',
		));

		ysm_setting( $w_id, 'popup_title_text_color', array(
			'type' => 'color',
			'title' => __('Title Text Color', 'smart_search'),
			'description' => __('Title text color in results popup', 'smart_search'),
			'value' => '',
		));

		ysm_setting( $w_id, 'popup_desc_text_color', array(
			'type' => 'color',
			'title' => __('Excerpt Text Color', 'smart_search'),
			'description' => __('Excerpt text color in results popup', 'smart_search'),
			'value' => '',
		));

		ysm_setting( $w_id, 'popup_view_all_link_text_color', array(
			'type' => 'color',
			'title' => __('"View all" Link Text Color', 'smart_search'),
			'description' => __('"View all" link text color in results popup', 'smart_search'),
			'value' => '',
		));

		ysm_setting( $w_id, 'popup_view_all_link_bg_color', array(
			'type' => 'color',
			'title' => __('"View all" Link Text Background', 'smart_search'),
			'description' => __('"View all" link text background in results popup', 'smart_search'),
			'value' => '',
		));
		?>

		<th class="ymapp-settings__title"><?php esc_html_e( 'Product Elements', 'smart_search' ); ?></th>

		<?php
		ysm_setting( $w_id, 'popup_price_text_color', array(
			'type' => 'color',
			'title' => __('Price Color', 'smart_search'),
			'description' => __('Price text color in results popup', 'smart_search'),
			'value' => '',
		));

		ysm_setting( $w_id, 'popup_out_of_stock_label_text_color', array(
			'type' => 'color',
			'title' => __('"Out of stock" Label Color', 'smart_search'),
			'description' => __('"Out of stock" label text color in results popup', 'smart_search'),
			'value' => '',
		));

		ysm_setting( $w_id, 'popup_out_of_stock_label_bg_color', array(
			'type' => 'color',
			'title' => __('"Out of stock" Label Background Color', 'smart_search'),
			'description' => __('"Out of stock" label background color in results popup', 'smart_search'),
			'value' => '',
		));

		ysm_setting( $w_id, 'popup_sale_label_text_color', array(
			'type' => 'color',
			'title' => __('"Sale" Label Color', 'smart_search'),
			'description' => __('"Sale" label text color in results popup', 'smart_search'),
			'value' => '',
		));

		ysm_setting( $w_id, 'popup_sale_label_bg_color', array(
			'type' => 'color',
			'title' => __('"Sale" Label Background Color', 'smart_search'),
			'description' => __('"Sale" label background color in results popup', 'smart_search'),
			'value' => '',
		));

		ysm_setting( $w_id, 'popup_featured_label_text_color', array(
			'type' => 'color',
			'title' => __('"Featured" Label Color', 'smart_search'),
			'description' => __('"Featured" label text color in results popup', 'smart_search'),
			'value' => '',
		));

		ysm_setting( $w_id, 'popup_featured_label_bg_color', array(
			'type' => 'color',
			'title' => __('"Featured" Label Background Color', 'smart_search'),
			'description' => __('"Featured" label background color in results popup', 'smart_search'),
			'value' => '',
		));

		?>

		</tbody>
	</table>
</div>