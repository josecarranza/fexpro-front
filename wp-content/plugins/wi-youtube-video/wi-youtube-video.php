<?php 
/*
Plugin Name: WI Youtube Video
Description: WI Youtube Video
Version: 1.0
Requires PHP: 7.0
Author: Web Info Team
Author URI: https://web-informatica.com/
 */
function wi_youtube_video($args=[]){
	$youtube_link = $args["video"]??"";
	preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/", $youtube_link, $matches);

	$youtube_link=$matches[1]??"";
	ob_start();
	include("shortcode-youtube-video.php");
	$html = ob_get_clean();
	return $html;
}
add_shortcode("youtube-video","wi_youtube_video");

