<?php

namespace WdmCSP\Compat;

if (!class_exists('WisdmPEP')) {
	/**
	 * This class contains the methods & ajax callbacs required for achieveing
	 * the compatibility with the plugin wisdm product enquery pro.
	 * 
	 * @method static mixed excludeTaxFromPrices($product, $prices)
	 */
	class WisdmPEP {
	
		public function __construct() {
			add_action('wp_ajax_pep_get_csp_prices_for_product', array($this, 'getCspPriceArrayForProduct'));
			include_once CSP_PLUGIN_PATH . '/includes/class-wdm-apply-usp-product-price.php';
		}
		
		/**
		 * This is the callback to the hook 'wp_ajax_pep_get_csp_prices_for_product'
		 * this method echos/returns the CSP prices for the product.
		 *
		 * @return void
		 */
		public function getCspPriceArrayForProduct() {
			if (empty($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'pep-csp-compat')) {
				wp_send_json(array('error' =>true, 'reason'=>'Invalid nonce'));
			}
			
			$postArray = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			if (empty($postArray['product_ids'])) {
				wp_send_json(array('error' =>true));
			}
			
			if (!isset($postArray['user_email']) || !is_email($postArray['user_email'])) {
				wp_send_json(array('error' =>true, 'reason'=>'Invalid Email'));
			}
			$userData = get_user_by('email', $postArray['user_email']);
			if (empty($userData)) {
				wp_send_json(array('error' =>true, 'reason'=>'No user exist'));
			}
			
			$productPrices		= array();
			$userId        		= $userData->ID;
			$productIds			= $postArray['product_ids'];
			foreach ($productIds as $productId) {
				$productObject 				= wc_get_product($productId);
				$cspPrices     				= \WuspSimpleProduct\WuspCSPProductPrice::getQuantityBasedPricing($productId, $userId);
				$cspPrices     				= self::excludeTaxFromPrices($productObject, $cspPrices);
				$regularPrice  				= $productObject->get_price();
				$title         				=   isset($postArray['productName'])?$postArray['productName']:'';
				$productPrices[$productId] 	= array('id'=>$productId, 'title' => $title, 'prices'=>$cspPrices, 'regularPrice'=> $regularPrice);
			}
						
			wp_send_json(array('error'=>false, 'cspPrices'=>$productPrices));
		}
		

		/**
		 * Returns the CSP prices excluding taxes.
		 *
		 * @method static mixed excludeTaxFromPrices( $product, $prices)
		 * @param  object $product WC_Product
		 * @param  mixed $prices - float when a singular price or array of the multiple prices
		 * @return mixed $new_prices array of prices including tax or price excluding tax.
		 */
		public static function excludeTaxFromPrices( $product, $prices) {
			if (is_array($prices) && !empty($prices)) {
				$new_prices = array();
				foreach ($prices as $key => $price) {
						$new_prices[$key] = self::getPriceWithoutWcTax( $product, $price);
				}
				return $new_prices;
			}
			return self::getPriceWithoutWcTax( $product, $prices);
		}


		/**
		 * This medthod checks if the price is neumeric , excludes the
		 * tax from the price & returns the price. 
		 *
		 * @method static getPriceWithoutWcTax()
		 * @param object $product - WC_Product Object
		 * @param float $price
		 * @return float $priceExcludingTax
		 */
		public static function getPriceWithoutWcTax( $product, $price) {
			if (is_numeric($price)) {
				if (version_compare(WC_VERSION, '2.7', '<')) {
					return $product->get_price_excluding_tax(1, $price);
				} else {
					return wc_get_price_excluding_tax($product, array('price' => $price));
				}
			}
			return 0; 
		}
	}
}
	
