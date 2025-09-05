<?php
	
	defined( 'ABSPATH' ) or die( 'Keep Quit' );
	
	add_filter( 'woocommerce_get_sections_woo-variation-gallery', function ( $sections ) {
		return wvg_array_insert_before( $sections, 'tutorials', array(
			'license' => esc_html__( 'License', 'woo-variation-gallery-pro' )
		) );
	} );
	
	add_filter( 'woocommerce_get_settings_woo-variation-gallery', function ( $settings, $current_section ) {
		
		switch ( $current_section ):
			case 'license':
				$settings = apply_filters( 'woo_variation_gallery_license_settings', array(
					
					array(
						'name' => __( 'License', 'woo-variation-gallery-pro' ),
						'type' => 'title',
						'desc' => '',
						'id'   => 'woo_variation_gallery_license_options',
					),
					
					array(
						'title' => esc_html__( 'License key', 'woo-variation-gallery-pro' ),
						'type'  => 'text',
						'desc'  => '<br>' . __( 'Please add <a target="_blank" href="https://getwooplugins.com/my-account/downloads/">License key</a> to get automatic update.', 'woo-variation-gallery-pro' ),
						'id'    => 'woo_variation_gallery_license'
					),
					
					
					array(
						'type' => 'sectionend',
						'id'   => 'woo_variation_gallery_license_options'
					),
				
				) );
				break;
		endswitch;
		
		return $settings;
		
	}, 10, 2 );
	
	add_filter( 'attachment_fields_to_edit', function ( $form_fields, $post ) {
		
		$form_fields[ 'woo_variation_gallery_media_title' ] = array(
			'tr' => sprintf( '<hr><h2>%s</h2>', __( 'Variation Gallery Video', 'woo-variation-gallery-pro' ) )
		);
		
		$form_fields[ 'woo_variation_gallery_media_video' ] = array(
			'label' => esc_html__( 'Video URL', 'woo-variation-gallery-pro' ),
			'input' => 'text',
			//'show_in_edit' => false,
			'value' => esc_url( get_post_meta( $post->ID, 'woo_variation_gallery_media_video', true ) )
		);
		
		$form_fields[ 'woo_variation_gallery_media_video_popup' ] = array(
			'label' => '',
			'input' => 'html',
			//'show_in_edit' => false,
			'html'  => '<a class="woo_variation_gallery_media_video_popup_link" href="#"><span class="dashicons dashicons-video-alt3"></span></a>',
		);
		
		$form_fields[ 'woo_variation_gallery_media_video_width' ] = array(
			'label' => esc_html__( 'Width', 'woo-variation-gallery-pro' ),
			'input' => 'text',
			//'show_in_edit' => false,
			'value' => absint( get_post_meta( $post->ID, 'woo_variation_gallery_media_video_width', true ) ),
			'helps' => esc_html__( 'Actual Video Width or Width Ratio. Empty for default', 'woo-variation-gallery-pro' )
		);
		
		$form_fields[ 'woo_variation_gallery_media_video_height' ] = array(
			'label' => esc_html__( 'Height', 'woo-variation-gallery-pro' ),
			'input' => 'text',
			//'show_in_edit' => false,
			'value' => absint( get_post_meta( $post->ID, 'woo_variation_gallery_media_video_height', true ) ),
			'helps' => esc_html__( 'Actual Video Height or Height Ratio. Empty for default', 'woo-variation-gallery-pro' )
		);
		
		return $form_fields;
	}, 10, 2 );
	
	add_filter( 'attachment_fields_to_save', function ( $post, $attachment ) {
		
		if ( isset( $attachment[ 'woo_variation_gallery_media_video' ] ) ) {
			update_post_meta( $post[ 'ID' ], 'woo_variation_gallery_media_video', esc_url( trim( $attachment[ 'woo_variation_gallery_media_video' ] ) ) );
		}
		
		if ( isset( $attachment[ 'woo_variation_gallery_media_video_width' ] ) ) {
			update_post_meta( $post[ 'ID' ], 'woo_variation_gallery_media_video_width', absint( trim( $attachment[ 'woo_variation_gallery_media_video_width' ] ) ) );
		}
		
		if ( isset( $attachment[ 'woo_variation_gallery_media_video_height' ] ) ) {
			update_post_meta( $post[ 'ID' ], 'woo_variation_gallery_media_video_height', absint( trim( $attachment[ 'woo_variation_gallery_media_video_height' ] ) ) );
		}
		
		return $post;
	}, 10, 2 );
	
	add_filter( 'wp_prepare_attachment_for_js', function ( $response, $attachment, $meta ) {
		
		$id        = absint( $attachment->ID );
		$has_video = trim( get_post_meta( $id, 'woo_variation_gallery_media_video', true ) );
		
		$response[ 'woo_variation_gallery_video' ] = $has_video;
		
		return $response;
	}, 10, 3 );
	
	add_filter( 'woo_variation_gallery_admin_template_js', function () {
		require_once 'admin-template-js.php';
	} );
	
	add_filter( 'woo_variation_gallery_slider_template_js', function () {
		require_once 'slider-template-js.php';
	} );
	
	add_filter( 'woo_variation_gallery_thumbnail_template_js', function () {
		require_once 'thumbnail-template-js.php';
	} );
	
	add_filter( 'woo_variation_gallery_get_image_props', function ( $props, $attachment_id ) {
		
		
		$has_video             = trim( get_post_meta( $attachment_id, 'woo_variation_gallery_media_video', true ) );
		$props[ 'video_link' ] = '';
		
		if ( ! empty( $has_video ) ) {
			
			$type = wp_check_filetype( $has_video );
			
			$mime_support = apply_filters( 'wvg_supported_html_video_mime', array(
				'video/mp4',
				'video/ogg',
				'video/webm'
			) );
			
			$video_width  = absint( trim( get_post_meta( $attachment_id, 'woo_variation_gallery_media_video_width', true ) ) );
			$video_height = absint( trim( get_post_meta( $attachment_id, 'woo_variation_gallery_media_video_height', true ) ) );
			
			$props[ 'video_link' ] = $has_video;
			// $props[ 'video_thumbnail_src' ] = woo_variation_gallery()->images_uri( 'play-button.svg' );
			
			
			if ( ! empty( $type[ 'type' ] ) && in_array( $type[ 'type' ], $mime_support ) ) {
				$props[ 'video_embed_type' ] = 'video';
			} else {
				$props[ 'video_embed_type' ] = 'iframe';
				$props[ 'video_embed_url' ]  = wvg_get_simple_embed_url( $has_video );
			}
			
			$props[ 'video_width' ]  = $video_width ? $video_width : 1;
			$props[ 'video_height' ] = $video_height ? $video_height : 1;
			$props[ 'video_ratio' ]  = ( $props[ 'video_height' ] / $props[ 'video_width' ] ) * 100;
		}
		
		return $props;
		
	}, 10, 2 );
	
	add_filter( 'wvg_gallery_image_html_class', function ( $class, $attachment_id, $image ) {
		
		if ( ! empty( $image[ 'video_link' ] ) ) {
			array_push( $class, 'wvg-gallery-video-slider' );
		}
		
		return $class;
	}, 10, 3 );
	
	add_filter( 'woo_variation_gallery_thumbnail_image_html_class', function ( $class, $attachment_id, $image ) {
		
		if ( ! empty( $image[ 'video_link' ] ) ) {
			array_push( $class, 'wvg-gallery-video-thumbnail' );
		}
		
		return $class;
	}, 10, 3 );
	
	add_filter( 'woo_variation_gallery_image_inner_html', function ( $inner_html, $image, $template, $attachment_id, $options ) {
		
		if ( $options[ 'has_only_thumbnail' ] ) {
			return $inner_html;
		}
		
		if ( $image[ 'video_link' ] && $image[ 'video_embed_type' ] === 'iframe' ) {
			$template   = '<div class="wvg-single-gallery-iframe-container" style="padding-bottom: %d%%"><iframe src="%s" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
			$inner_html = sprintf( $template, $image[ 'video_ratio' ], $image[ 'video_embed_url' ] );
		}
		
		if ( $image[ 'video_link' ] && $image[ 'video_embed_type' ] === 'video' ) {
			$template   = '<div class="wvg-single-gallery-video-container" style="padding-bottom: %d%%"><video preload="auto" controls controlsList="nodownload" src="%s"></video></div>';
			$inner_html = sprintf( $template, $image[ 'video_ratio' ], $image[ 'video_link' ] );
		}
		
		return $inner_html;
	}, 10, 5 );