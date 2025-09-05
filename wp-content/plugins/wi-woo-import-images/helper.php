<?php 
if(!function_exists("message")):
function message($type="",$message=""){
	switch ($type) {
		case 'success':
			echo '<div class="notice notice-success"><p>'.$message.'</p></div>';	
		break;
		case 'error':
			echo '<div class="notice error"><p>'.$message.'</p></div>';	
		break;
		
		default:
			
		break;
	}
}
endif;
if(!function_exists("callcomand_images")):
function callcomand_images($url){
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, "null");
	curl_setopt($ch, CURLOPT_POSTREDIR, 3);


	curl_exec($ch);
	curl_close($ch);
  }

endif;
?>