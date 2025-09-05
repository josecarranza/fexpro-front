<?php
	
	defined( 'ABSPATH' ) or die( 'Keep Quit' );
	
	add_filter( 'woo_variation_gallery_images_rest_get_image', function ( $image, $attachment_id ) {
		
		$has_video = trim( get_post_meta( $attachment_id, 'woo_variation_gallery_media_video', true ) );
		
		$image[ 'video_src' ] = $has_video;
		
		return $image;
	}, 10, 2 );