<?php

class WiExportXmlSage
{
    public $db;

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;

        add_action('wp_ajax_export_xml_sage_order', array($this, "export_order"));
        add_action('wp_ajax_nopriv_export_xml_sage_order', array($this, "export_order"));

        add_action('admin_footer', array($this, "exportOrderCustomizeColumnBtn")); // For back-end

        // Adding to admin order list bulk dropdown a custom action 'custom_downloads'
        add_filter('bulk_actions-edit-shop_order', array($this, 'bulk_export_sage_order'), 20, 1);
        // Make the action from selected orders
        add_filter('handle_bulk_actions-edit-shop_order', array($this, 'handle_bulk_export_sage_xml'), 10, 3);

        add_action('wp_ajax_render_sage_xml', array($this, "render_sage_xml"));
        add_action('wp_ajax_nopriv_render_sage_xml', array($this, "render_sage_xml"));
    }

    function exportOrderCustomizeColumnBtn()
    {

?>

        <script type="text/javascript">
            (function() {

                jQuery('td.my-column1.column-my-column1 a').off('click').on('click', function() {

                    var orderId = jQuery(this).data('orderid');

                    var c = jQuery(this);

                    jQuery.ajax({



                        type: "POST",

                        url: '<?= get_site_url() ?>/wp-admin/admin-ajax.php',

                        data: {

                            'orderid': orderId,

                            'action': 'export_xml_sage_order',

                            'doing_something': 'doing_something'
                        },

                        beforeSend: function() {

                            jQuery(c).text('Exporting XML');

                        },

                        success: function(msg) {

                            console.log(msg);
                            let _data = JSON.parse(msg);
                            if (_data.error == 0) {
                                jQuery("#post-" + orderId + " .export-sage-status").html("Exported");
                            }

                            //window.location.reload(true);

                            jQuery(c).text('Export XML');

                        },

                        error: function(errorThrown) {

                            console.log(errorThrown);

                            console.log('No update');

                        }

                    });



                });

            })();
        </script>

<?php
    }



    function export_order()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $order_id = $_POST['orderid'];

        $order = wc_get_order($order_id);
        $r = false;
        if ($order->get_billing_country() == 'MX') {
            $r = $this->xml_mx($order_id, $order);
        } else {
            $r = $this->xml_others($order_id, $order);
        }
        if ($r) {
            update_post_meta($order_id, "_export_sage_status", "1");
        }
        @ob_clean();
        $json["error"] = (int)!$r;
        echo json_encode($json);

        exit;
    }

    private function xml_mx($order_id, $order, $render = false)
    {

        $userid           = $order->get_user_id();

        $username[]       = $order->get_billing_first_name();

        $username[]      .= $order->get_billing_last_name();

        $orderCreateDate  = $order->get_date_created()->format('d/m/Y');
        $fechaEntrega = "15/01/2024";

        $userAddress[]    =  $order->get_billing_address_1();
        $userAddress[]   .= $order->get_billing_address_2();


        $userAddress1[]   = WC()->countries->countries[$order->get_billing_country()];
        $userAddress1[]   .= WC()->countries->get_states($order->get_billing_country())[$order->get_billing_state()];
        $userAddress1[]   .= $order->get_billing_city();
        $userAddress1[]   .= $order->get_billing_postcode();

        $userRemarks       = $order->get_customer_note();

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $root = $dom->createElement('Sagemobile');
        $dom->appendChild($root);
        $result = $dom->createElement('Encabezado');

        $root->appendChild($result);



        //echo "Mexico Orders: " . $order_id . "<br>";

        $result->setAttribute('Cia', "08");

        $result->setAttribute('Otra_Cia', "08");



        foreach ($order->get_items() as $item_id => $item) {

            $j = 1;

            $boxTotal = 0;

            $product_id = $item->get_product_id();

            $variation_id = $item->get_variation_id();

            $getSKU = get_post_meta($variation_id, '_sku', true);
            $skumex = str_replace("-", "", $getSKU);

            $is_drop = get_post_meta($variation_id, 'drop', true);
            if ($is_drop == "1") {
                continue;
            }

            $name = $item->get_name();

            $quantity = $item->get_quantity();

            $get_product_detail = $item->get_product();

            $product = wc_get_product($product_id);



            $parentSKU = preg_replace('/\-[^-]*$/', '', $getSKU);

            $colorName = str_replace($parentSKU . "-", "", $getSKU);


            $getMarca[] = $product->get_attribute('pa_brand ');

            //array_unique($getMarca);
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

            $result->setAttribute('Fecha_Entrega', $fechaEntrega);

            $result->setAttribute('Direccion', $userAddressDetails);

            $result->setAttribute('Direccion2', $userAddressDetails1);

            //$result->setAttribute('Vendedor', "01");

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

            //$result1->setAttribute('Producto', $parentSKU . $colorName);
            $result1->setAttribute('Producto', $skumex);

            $result1->setAttribute('Color', $colorName);

            //$result1->setAttribute('Brand', $product->get_attribute( 'pa_brand ' )); // Extra field to have color Brand information

            $result1->setAttribute('Cantidad', $quantity);

            $warehouse = get_post_meta($variation_id, 'warehouse', true);
            //$result1->setAttribute("Almacen", $warehouse);
            $result1->setAttribute("Bodega", $warehouse);



            $getVariationSizes[] = $item->get_meta('item_variation_size');

            $getVariationSizesCounts = $item->get_meta('item_variation_size');

            foreach ($getVariationSizesCounts as $ap) {

                $newLabel = str_replace("/", "-", $ap['label']);

                $newLabel = str_replace(" ", "-", $newLabel);

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

        $result->setAttribute('Marca1', '');
        $result->setAttribute('Marca2', '');
        $result->setAttribute('Marca3', '');
        $result->setAttribute('Marca4', '');
        if ($render) {
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $xml_string = $dom->saveXML();
            if (isset($_GET['download'])) {
                $orderstatus = strtoupper($order->get_status());
                header('Content-Disposition: attachment; filename="PED_'.$order_id.'_'.$orderstatus.'_mex.xml"'); 
                header('Cache-Control: no-cache, must-revalidate');
            }
            echo $xml_string;
            exit;
        }

        $file = 'PED_' . $order_id . '_pre6_mex.xml';
        if ($dom->save(WI_PLUGIN_EXPSAGE_PATH . '/tmp/' . $file)) {
            $success = $this->ftp($file, WI_PLUGIN_EXPSAGE_PATH . '/tmp/' . $file);
            return $success;
        } else {
            return false;
        }
    }

    private function xml_others($order_id, $order, $render = false)
    {

        $userid           = $order->get_user_id();

        $username[]       = $order->get_billing_first_name();

        $username[]      .= $order->get_billing_last_name();

        $orderCreateDate  = $order->get_date_created()->format('d/m/Y');

        $fechaEntrega = "15/01/2024";


        $userAddress[]    =  $order->get_billing_address_1();
        $userAddress[]   .= $order->get_billing_address_2();


        $userAddress1[]   = WC()->countries->countries[$order->get_billing_country()];
        $userAddress1[]   .= WC()->countries->get_states($order->get_billing_country())[$order->get_billing_state()];
        $userAddress1[]   .= $order->get_billing_city();
        $userAddress1[]   .= $order->get_billing_postcode();

        $userRemarks       = $order->get_customer_note();

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $root = $dom->createElement('Sagemobile');
        $dom->appendChild($root);


        $result = $dom->createElement('Encabezado');

        $root->appendChild($result);

        $result->setAttribute('Cia', "02");

        $result->setAttribute('Otra_Cia', "02");



        foreach ($order->get_items() as $item_id => $item) {

            $j = 1;

            $boxTotal = 0;

            $product_id = $item->get_product_id();

            $variation_id = $item->get_variation_id();

            $is_drop = get_post_meta($variation_id, 'drop', true);

            if ($is_drop == "1") {
                continue;
            }

            $getSKU = get_post_meta($variation_id, '_sku', true);

            $name = $item->get_name();

            $quantity = $item->get_quantity();

            $get_product_detail = $item->get_product();

            $product = wc_get_product($product_id);

            /* echo "<pre>";

            print_r($item->get_meta('item_variation_size'));

            echo "</pre>"; */

            /* echo "<pre>";

            print_r($get_product_detail);

            echo "</pre>"; */

            //echo $get_product_detail->attributes['pa_color'];



            //$addColor = $get_product_detail->attributes['pa_color'];

            //$addColor = explode(' - ', $name);

            $parentSKU = preg_replace('/\-[^-]*$/', '', $getSKU);

            $colorName = str_replace($parentSKU . "-", "", $getSKU);

            //$addColor = trim( str_replace( array( '_', '-' ), ' ', $addColor ) );

            //$addColor = ucwords(strtolower(preg_replace('/[0-9]+/', '', $addColor)));





            //echo "<br>";

            $getMarca[] = $product->get_attribute('pa_brand ');

            //array_unique($getMarca);



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

            $result->setAttribute('Fecha_Entrega', $fechaEntrega);

            $result->setAttribute('Direccion', $userAddressDetails);

            $result->setAttribute('Direccion2', $userAddressDetails1);

            //$result->setAttribute('Vendedor', "01");

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


            $warehouse = get_post_meta($variation_id, 'warehouse', true);
            //$result1->setAttribute("Almacen", $warehouse);
            $result1->setAttribute("Bodega", $warehouse);

            $getVariationSizes[] = $item->get_meta('item_variation_size');

            $getVariationSizesCounts = $item->get_meta('item_variation_size');

            foreach ($getVariationSizesCounts as $ap) {

                $newLabel = str_replace("/", "-", $ap['label']);

                $newLabel = str_replace(" ", "-", $newLabel);

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

        $result->setAttribute('Marca1', '');

        $result->setAttribute('Marca2', '');

        $result->setAttribute('Marca3', '');

        $result->setAttribute('Marca4', '');

        if ($render) {
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $xml_string = $dom->saveXML();
            if (isset($_GET['download'])) {
                $orderstatus = strtoupper($order->get_status());
                header('Content-Disposition: attachment; filename="PED_'.$order_id.'_'.$orderstatus.'.xml"'); 
                header('Cache-Control: no-cache, must-revalidate');
            }
            echo $xml_string;
            exit;
        }

        $file = 'PED_' . $order_id . '_pre6.xml';
        if ($dom->save(WI_PLUGIN_EXPSAGE_PATH . '/tmp/' . $file)) {
            $success = $this->ftp($file, WI_PLUGIN_EXPSAGE_PATH . '/tmp/' . $file);
            return $success;
        } else {
            return false;
        }
    }

    function ftp_old($archivo, $ruta)
    {
        $host = 'wwwfexpro.eastus2.cloudapp.azure.com';
        $user = 'ftpfexpro';
        $password = 'WP820.1.com';

        $ftpConn = ftp_connect($host);

        $login = ftp_login($ftpConn, $user, $password);
        //ftp_pasv($ftpConn, true);
        // check connection


        if ((!$ftpConn) || (!$login)) {

            ftp_close($ftpConn);
            return false;
        } else {

            if (ftp_put($ftpConn, $archivo, $ruta, FTP_ASCII)) {

                ftp_close($ftpConn);
                return true;
            } else {

                ftp_close($ftpConn);
                return false;
            }
        }
    }

    function ftp($name, $dataFile)
    {
        //return true;
        $sftpPort = 21;
        //$sftpServer= 'wwwfexpro.eastus2.cloudapp.azure.com';
        $sftpServer = '20.57.63.38';
        $sftpUsername = 'ftpfexpro';
        $sftpPassword = 'WP820.1.com';
        $sftpRemoteDir = "";

        $protocol = $sftpPort == 22 ? "sftp" : "ftp";
        $ch = curl_init($protocol . '://' . $sftpServer . ':' . $sftpPort . $sftpRemoteDir . '/' . $name);

        $fh = fopen($dataFile, 'r');

        if ($fh) {
            curl_setopt($ch, CURLOPT_USERPWD, $sftpUsername . ':' . $sftpPassword);
            curl_setopt($ch, CURLOPT_UPLOAD, true);
            curl_setopt($ch, CURLOPT_FTP_CREATE_MISSING_DIRS, true);
            if ($protocol == "sftp") {
                curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
            }

            curl_setopt($ch, CURLOPT_INFILE, $fh);
            curl_setopt($ch, CURLOPT_INFILESIZE, filesize($dataFile));
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $verbose = fopen('php://temp', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $verbose);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($response) {
                return true;
            } else {
                return false;
            }
        }
    }




    function bulk_export_sage_order($actions)
    {
        $actions['export_sage_xml'] = __('Export Sage XML', 'woocommerce');
        return $actions;
    }


    function handle_bulk_export_sage_xml($redirect_to, $action, $post_ids)
    {
        if ($action !== 'export_sage_xml')
            return $redirect_to; // Exit

        echo "<a href='" . get_site_url() . "/wp-admin/edit.php?post_type=shop_order&orderby=ID&order=desc'>< Regresar</a><br /><br /><br />";
        foreach ($post_ids as $order_id) {

            echo "Presale6 Order XML is created please click <a href='" . site_url() . "/wp-admin/admin-ajax.php?_fs_blog_admin=true&action=render_sage_xml&order_id=" . $order_id . "' target='_blank'>Click Here " . $order_id . " </a> <br>";
        }
    }

    function render_sage_xml()
    {
        $order_id = $_GET["order_id"] ?? 0;
        $order = wc_get_order($order_id);
        $r = false;
        @ob_clean();
        header("Content-type: text/xml");
        if ($order->get_billing_country() == 'MX') {
            $r = $this->xml_mx($order_id, $order, true);
        } else {
            $r = $this->xml_others($order_id, $order, true);
        }
        exit;
    }
}





?>
