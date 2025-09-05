<?php
class WiOrdersXml
{

	public $db;
	public $model;

	function __construct()
	{

		global $wpdb;
		$this->db = $wpdb;
		$this->model =  new ModelFexproDashboard();

		add_action('admin_menu', array($this, "setup_menu"));

		add_action('wp_ajax_widash_export_order_xml', array($this, "export_order_xml"));
		add_action('wp_ajax_nopriv_widash_export_order_xml', array($this, "export_order_xml"));

		add_action('wp_ajax_widash_export_order_mex_xml', array($this, "export_order_mex_xml"));
		add_action('wp_ajax_nopriv_widash_export_order_mex_xml', array($this, "export_order_mex_xml"));
	}

	function setup_menu()
	{

		add_submenu_page(
			'wi_fexpro_dashboard',
			"Orders XML",
			"Orders XML",
			'manage_options',
			'wi_fexpro_dashboard_orders_xml',
			array($this, "orders")
		);
	}
	function orders()
	{

		$type = isset($_GET['type']) ? $_GET['type'] : '';
		if ($type !== "") {
			switch ($type) {
				case "orders":
					$this->result_orders();
					break;
				case "orders_mex":
					$this->result_orders_mex();
					break;
			}
			return;
		}

		$order_status = $this->model->getOrderStatus();
		include(WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/views/page-orders-xml.php");
	}
	function result_orders()
	{
		$order_status = isset($_GET['order_status']) ? 'order_status' : '';

		$query = new WC_Order_Query(array(
			'limit' => -1,
			'status' => $order_status,
			'return' => 'ids',
		));
		$orders = $query->get_orders();


		echo "<a href='javascript:history.back()'>< Regresar</a><br/><br/>";

		foreach ($orders as $order_id) {

			$order = wc_get_order($order_id);
			if ($order->get_billing_country() == 'MX') {
				continue;
			}

			echo "Panama Orders: " . $order_id . "<br>";
			echo "Panama Order XML is created please click <a href='" . site_url() . "/wp-admin/admin-ajax.php?action=widash_export_order_xml&id=" . $order_id . "' target='_blank'>Click Here " . $order_id . " </a> <br><br/>";
		}
	}
	function export_order_xml()
	{
		$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		$order = wc_get_order($order_id);


		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->formatOutput = true;

		$root = $dom->createElement('Sagemobile');
		$dom->appendChild($root);
		$ab = array();

		$username = [];
		$userAddress = [];
		$userAddress1 = [];
		//$getMarca = [];
		$getVariationSizes = [];

		$i = 1;
		$userid           = $order->get_user_id();
		$username[]       = $order->get_billing_first_name();
		$username[]      .= $order->get_billing_last_name();
		$orderCreateDate  = $order->get_date_created()->format('d/m/Y');

		$userAddress[]    =  $order->get_billing_address_1();
		$userAddress[]   .= $order->get_billing_address_2();

		$userAddress1[]   = WC()->countries->countries[$order->get_billing_country()];
		$userAddress1[]   .= WC()->countries->get_states($order->get_billing_country())[$order->get_billing_state()];
		$userAddress1[]   .= $order->get_billing_city();
		$userAddress1[]   .= $order->get_billing_postcode();
		$userRemarks       = $order->get_customer_note();

		//echo $order->get_formatted_billing_full_name();


		$result = $dom->createElement('Encabezado');
		$root->appendChild($result);

		$result->setAttribute('Cia', "02");
		$result->setAttribute('Otra_Cia', "02");

		foreach ($order->get_items() as $item_id => $item) {
			$j = 1;
			$boxTotal = 0;


			$product_id = $item->get_product_id();
			$variation_id = $item->get_variation_id();


			$getSKU = get_post_meta($variation_id, '_sku', true);

			$name = $item->get_name();
			$quantity = $item->get_quantity();
			$get_product_detail = $item->get_product();
			$product = wc_get_product($product_id);
			$parentSKU = preg_replace('/\-[^-]*$/', '', $getSKU);
			$colorName = str_replace($parentSKU . "-", "", $getSKU);

			$userFirstLastname = implode(" ", $username);
			$userAddressDetails = implode(" ", $userAddress);
			$userAddressDetails1 = implode(" ", $userAddress1);

			$getUserSageCode = get_user_meta($userid, 'customer_code', true);
			$newUserSageCode = str_pad($getUserSageCode, 4, '0', STR_PAD_LEFT);
			/* For Encabezado XML Attribute */

			$result->setAttribute('Pedido', $order_id);
			$result->setAttribute('Cliente', $newUserSageCode);
			$result->setAttribute('Nombre', $userFirstLastname);
			$result->setAttribute('Fecha', $orderCreateDate);
			$result->setAttribute('Tipo', "1");
			$result->setAttribute('Fecha_Entrega', $orderCreateDate);
			$result->setAttribute('Direccion', $userAddressDetails);
			$result->setAttribute('Direccion2', $userAddressDetails1);
			$result->setAttribute('Vendedor', "01");
			$result->setAttribute('AgenteEmbarcador', "");
			$result->setAttribute('Observaciones', $userRemarks);
			$result->setAttribute('ResponsablePedido', "");
			$result->setAttribute('ResponsableImportacion', "");
			$result->setAttribute('Contacto', $userFirstLastname);

			$result->setAttribute('OtrasInstrucciones', "");
			$result->setAttribute('Noordencompra', "");
			$result->setAttribute('TipoEntrega', "1");
			$result->setAttribute('Sustitucion', "");
			$result->setAttribute('Courrier', "");
			$result->setAttribute('Seguro', "");
			$result->setAttribute('Empaque', "");

			/* For Linea Attribute */
			$result1 = $dom->createElement('Linea');
			$result->appendChild($result1);

			//$result1->setAttribute('Producto_padre', $product_id); //Parent product id for quick reference
			$result1->setAttribute('Producto', $getSKU);
			$result1->setAttribute('Color', $colorName);
			//$result1->setAttribute('Brand', $product->get_attribute( 'pa_brand ' )); // Extra field to have color Brand information
			$result1->setAttribute('Cantidad', $quantity);


			$getVariationSizes[] = $item->get_meta('item_variation_size');
			$getVariationSizesCounts = $item->get_meta('item_variation_size');
			foreach ($getVariationSizesCounts as $ap) {
				$newLabel = str_replace("/", "-", $ap['label']);
				$newLabel = str_replace(" ", "", $newLabel);
				$result1->setAttribute('size_' . $newLabel, $ap['value'] * $quantity);
				$boxTotal += $ap['value'];
			}

			$result1->setAttribute('Unit_Box_Qty', $boxTotal); //It is showing Unit Box Qty
			$result1->setAttribute('Total_Box_Qty', $boxTotal * $quantity); //It is showing Total Box Unit Qty
			//echo $boxTotal . "<br>";
			$result1->setAttribute('Precio', $item->get_subtotal() / ($boxTotal * $quantity));

			$result1->appendChild($dom->createTextNode(''));
			$result->appendChild($result1);
		}
		$result->setAttribute('Marca1', '');
		$result->setAttribute('Marca2', '');
		$result->setAttribute('Marca3', '');
		$result->setAttribute('Marca4', '');

		$filename = "PED_" . $order_id . ".xml";
		@ob_clean();
		header('Content-Type: text/xml'); // Establecer el tipo de contenido como XML
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Length: ' . strlen($dom->saveXML()));
		echo $dom->saveXML();
		exit;
	}
	function result_orders_mex()
	{
		$order_status = isset($_GET['order_status']) ? 'order_status' : '';

		$query = new WC_Order_Query(array(
			'limit' => -1,
			'status' => $order_status,
			'return' => 'ids',
		));
		$orders = $query->get_orders();


		echo "<a href='javascript:history.back()'>< Regresar</a><br/><br/>";

		foreach ($orders as $order_id) {

			$order = wc_get_order($order_id);
			if ($order->get_billing_country() != 'MX') {
				continue;
			}

			echo "Mexico Orders: " . $order_id . "<br>";
			echo "Mexico Order XML is created please click <a href='" . site_url() . "/wp-admin/admin-ajax.php?action=widash_export_order_mex_xml&id=" . $order_id . "' target='_blank'>Click Here " . $order_id . " </a> <br><br/>";
		}
	}
	function export_order_mex_xml()
	{
		$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		$order = wc_get_order($order_id);


		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->formatOutput = true;

		$root = $dom->createElement('Sagemobile');
		$dom->appendChild($root);
		$ab = array();

		$username = [];
		$userAddress = [];
		$userAddress1 = [];
		//$getMarca = [];
		$getVariationSizes = [];

		$i = 1;
		$userid           = $order->get_user_id();
		$username[]       = $order->get_billing_first_name();
		$username[]      .= $order->get_billing_last_name();
		$orderCreateDate  = $order->get_date_created()->format('d/m/Y');

		$userAddress[]    =  $order->get_billing_address_1();
		$userAddress[]   .= $order->get_billing_address_2();

		$userAddress1[]   = WC()->countries->countries[$order->get_billing_country()];
		$userAddress1[]   .= WC()->countries->get_states($order->get_billing_country())[$order->get_billing_state()];
		$userAddress1[]   .= $order->get_billing_city();
		$userAddress1[]   .= $order->get_billing_postcode();
		$userRemarks       = $order->get_customer_note();

		//echo $order->get_formatted_billing_full_name();


		$result = $dom->createElement('Encabezado');
		$root->appendChild($result);

		$result->setAttribute('Cia', "08");
		$result->setAttribute('Otra_Cia', "08");

		foreach ($order->get_items() as $item_id => $item) {
			$$j = 1;
			$boxTotal = 0;


			$product_id = $item->get_product_id();
			$variation_id = $item->get_variation_id();

			$product_id = $item->get_product_id();
			$variation_id = $item->get_variation_id();


			$getSKU = get_post_meta($variation_id, '_sku', true);

			$meta = get_post_meta($variation_id);
			$sku = isset($meta['_sku'][0]) ? $meta['_sku'][0] : '';

			$parentSKU = preg_replace('/\-[^-]*$/', '', $sku);
			$colorName = str_replace($parentSKU . "-", "", $sku);
			$skumex = str_replace("-", "", $sku);
			$is_drop = get_post_meta($variation_id, 'drop', true);
			if (!empty($is_drop) == "1") {
				continue;
			}

			$name = $item->get_name();
			$quantity = $item->get_quantity();
			$get_product_detail = $item->get_product();
			$product = wc_get_product($product_id);

			$userFirstLastname = implode(" ", $username);
			$userAddressDetails = implode(" ", $userAddress);
			$userAddressDetails1 = implode(" ", $userAddress1);

			$getUserSageCode = get_user_meta($userid, 'customer_code', true);
			$newUserSageCode = str_pad($getUserSageCode, 4, '0', STR_PAD_LEFT);
			/* For Encabezado XML Attribute */

			$result->setAttribute('Pedido', $order_id);
			$result->setAttribute('Cliente', $newUserSageCode);
			$result->setAttribute('Nombre', $userFirstLastname);
			$result->setAttribute('Fecha', $orderCreateDate);
			$result->setAttribute('Tipo', "1");
			$result->setAttribute('Fecha_Entrega', $orderCreateDate);
			$result->setAttribute('Direccion', $userAddressDetails);
			$result->setAttribute('Direccion2', $userAddressDetails1);
			$result->setAttribute('AgenteEmbarcador', "");
			$result->setAttribute('Observaciones', $userRemarks);
			$result->setAttribute('ResponsablePedido', "");
			$result->setAttribute('ResponsableImportacion', "");
			$result->setAttribute('Contacto', $userFirstLastname);

			$result->setAttribute('OtrasInstrucciones', "");
			$result->setAttribute('Noordencompra', "");
			$result->setAttribute('TipoEntrega', "1");
			$result->setAttribute('Sustitucion', "");
			$result->setAttribute('Courrier', "");
			$result->setAttribute('Seguro', "");
			$result->setAttribute('Empaque', "");

			/* For Linea Attribute */
			$result1 = $dom->createElement('Linea');
			$result->appendChild($result1);

			//$result1->setAttribute('Producto_padre', $product_id); //Parent product id for quick reference
			$result1->setAttribute('Producto', $skumex);
			$result1->setAttribute('Color', $colorName);
			//$result1->setAttribute('Brand', $product->get_attribute( 'pa_brand ' )); // Extra field to have color Brand information
			$result1->setAttribute('Cantidad', $quantity);


			$getVariationSizes[] = $item->get_meta('item_variation_size');
			$getVariationSizesCounts = $item->get_meta('item_variation_size');
			foreach ($getVariationSizesCounts as $ap) {
				$newLabel = str_replace("/", "-", $ap['label']);
				$newLabel = str_replace(" ", "", $newLabel);
				$result1->setAttribute('size_' . $newLabel, $ap['value'] * $quantity);
				$boxTotal += $ap['value'];
			}

			$result1->setAttribute('Unit_Box_Qty', $boxTotal); //It is showing Unit Box Qty
			$result1->setAttribute('Total_Box_Qty', $boxTotal * $quantity); //It is showing Total Box Unit Qty
			//echo $boxTotal . "<br>";
			$result1->setAttribute('Precio', $item->get_subtotal() / ($boxTotal * $quantity));

			//$result1->appendChild( $dom->createElement('Product_name', $name) );

			//$result1->appendChild($dom->createElement(''));

			$result1->appendChild($dom->createTextNode(''));
			$result->appendChild($result1);
		}
		/* echo "<pre>";
		print_r($getVariationSizes);
		echo "</pre>";
		 */
		$result->setAttribute('Marca1', '');
		$result->setAttribute('Marca2', '');
		$result->setAttribute('Marca3', '');
		$result->setAttribute('Marca4', '');

		$filename = "PED_" . $order_id . "_mex.xml";
		@ob_clean();
		header('Content-Type: text/xml'); // Establecer el tipo de contenido como XML
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Length: ' . strlen($dom->saveXML()));
		echo $dom->saveXML();
		exit;
	}
}
