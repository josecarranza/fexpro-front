<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../wp-load.php');
global $wpdb;

$sql_variations_with_barcode = "SELECT pm.meta_value AS variation_id
FROM wp_woocommerce_order_items p 
INNER JOIN wp_woocommerce_order_itemmeta pm ON p.order_item_id=pm.order_item_id AND pm.meta_key='_variation_id'
INNER JOIN wp_posts pp ON pp.ID=pm.meta_value
LEFT JOIN wp_postmeta ppm ON ppm.post_id=pm.meta_value AND ppm.meta_key LIKE 'size_barcode%' AND ppm.meta_value != ''
WHERE p.order_id IN (
    SELECT pp.ID FROM wp_posts pp
    WHERE pp.post_type='shop_order' AND pp.post_status='wc-fw24-q3' AND pp.ID >= 126125
)
GROUP BY pm.meta_value";

$sql_variations_without_barcode = "SELECT pm.meta_value AS variation_id
FROM wp_woocommerce_order_items p 
INNER JOIN wp_woocommerce_order_itemmeta pm ON p.order_item_id=pm.order_item_id AND pm.meta_key='_variation_id'
INNER JOIN wp_posts pp ON pp.ID=pm.meta_value
LEFT JOIN wp_postmeta ppm ON ppm.post_id=pm.meta_value AND ppm.meta_key LIKE 'size_barcode%' AND ppm.meta_value = ''
WHERE p.order_id IN (
    SELECT pp.ID FROM wp_posts pp
    WHERE pp.post_type='shop_order' AND pp.post_status='wc-fw24-q3' AND pp.ID >= 126125
)
GROUP BY pm.meta_value";

// Función para generar el XML
function generateXML($filename, $variations, $hasBarcode) {
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;

    $root = $dom->createElement('Sagemobile');
    $dom->appendChild($root);

    foreach ($variations as $variation) {
        createProductNode($dom, $root, $variation, $hasBarcode);
    }

    // Guardar el archivo XML
    if ($dom->save('fw24_products_pr/' . $filename)) {
        echo "Fexpro Product XML is created. Please click <a href='" . site_url() . "/wp-content/themes/porto-child/export/fw24_products_pr/{$filename}' target='_blank'>here</a>.<br><hr>";
    } else {
        die('XML Create Error');
    }
}


// Obtener variaciones con barcode
$variations_with_barcode = $wpdb->get_results($sql_variations_with_barcode, ARRAY_A);
$variations_with_barcode = array_column($variations_with_barcode, "variation_id");

// Obtener variaciones sin barcode
$variations_without_barcode = $wpdb->get_results($sql_variations_without_barcode, ARRAY_A);
$variations_without_barcode = array_column($variations_without_barcode, "variation_id");

// Generar XML para productos con barcode
generateXML('INV_with_barcode.xml', $variations_with_barcode, true);

// Generar XML para productos sin barcode
generateXML('INV_without_barcode.xml', $variations_without_barcode, false);
?>