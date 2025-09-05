<?php
// Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluye el archivo wp-load.php
include('../../../../wp-load.php');
global $wpdb;

// Define el período de fecha 'q3'
$qdate = "q3";

// Consulta SQL para obtener variaciones vendidas en un período específico
$sql_variations_selled = "SELECT pm.meta_value variation_id
    FROM wp_woocommerce_order_items p 
    INNER JOIN wp_woocommerce_order_itemmeta pm ON p.order_item_id = pm.order_item_id AND pm.meta_key = '_variation_id'
    INNER JOIN wp_posts pp ON pp.ID = pm.meta_value
    INNER JOIN (
        wp_term_relationships tr
        INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'pa_date'
        INNER JOIN wp_terms t ON tt.term_id = t.term_id AND t.slug = '".$qdate."'
    ) ON tr.object_id = pp.post_parent
    WHERE p.order_id IN (
        SELECT pp.ID FROM wp_posts pp
        WHERE pp.post_type = 'shop_order' AND pp.post_status = 'wc-fw24-q3' AND pp.ID >= 126125
    )
    GROUP BY pm.meta_value";

// Obtiene las variaciones vendidas
$variations = $wpdb->get_results($sql_variations_selled, ARRAY_A);
$variations = array_column($variations, "variation_id");

// Configura argumentos para obtener productos basados en variaciones vendidas
$args = array(
    'post_type' => array('product_variation'),
    'post_status' => array('publish', 'private'),
    'numberposts' => -1,
    'post__in' => $variations
);

// Obtiene todas las variaciones de productos
$allProducts = get_posts($args);
$splitArr = array_chunk($allProducts, 100);

// Directorios y configuraciones de imágenes
$images_path = "/var/www/vhosts/fexpro.com/shop.fexpro.com/";
$url_path = "https://shop2.fexpro.com/";
$self_path = realpath(dirname(__FILE__));

// Itera sobre las variaciones divididas
foreach ($splitArr as $sk => $sv) {
    // Crea un nombre de carpeta para el zip
    $zip_folder_name = substr(md5(mt_rand()), 0, 10);
    // Crea la carpeta si no existe
    if (!file_exists($self_path."/images/".$zip_folder_name)) {
        mkdir($self_path."/images/".$zip_folder_name, 0777);
    }

    // Tamaño de imagen
    $size = 'LARGE';

    // Crea un nuevo documento XML
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;

    // Crea el elemento raíz 'Sage'
    $root = $dom->createElement('Sage');
    $dom->appendChild($root);

    // Itera sobre cada variación
    foreach ($sv as $value) {
        $parentID = $value->post_parent;
        $variationID = $value->ID;
        // Verifica si pertenece a la categoría 'PRESALE'
        if (has_term('PRESALE', 'product_cat', $parentID)) {
            $cat = get_the_terms($parentID, 'product_cat');
            $css_slugGender = array();
            $css_slugCategory = array();
            $css_slugSubCategory = array();
            $codigodetalla = array();

            // Obtiene el SKU del producto
            $getSKU = get_post_meta($variationID, '_sku', true);
            $ak = str_replace(' ', '-', strtolower($getSKU));

            // Obtiene las categorías del producto
            foreach ($cat as $cvalue) {
                if ($cvalue->parent != 0) {
                    $term = get_term_by('id', $cvalue->parent, 'product_cat');
                    $css_slugSubCategory[] = $cvalue->name;
                    $css_slugCategory[] = $term->name;
                } else {
                    $css_slugGender[] = $cvalue->name;
                }
            }

            // Verifica si es de la categoría 'Fitness'
            if ($css_slugGender[0] == 'Fitness') {
                continue;
            } else {
                // Crea el elemento 'Imagenes'
                $result = $dom->createElement('Imagenes');
                $root->appendChild($result);

                // Añade atributos al elemento 'Imagenes'
                $result->setAttribute('Producto', $getSKU);

                // Obtiene la imagen principal
                $thumb_image = wp_get_attachment_image_src(get_post_thumbnail_id($variationID), $size, true);
                $server_path = str_replace($url_path, $images_path, $thumb_image[0]);
                $tokens = explode('/', $thumb_image[0]);
                $str = trim(end($tokens));

                // Copia la imagen principal a la carpeta
                if ($str != 'default.png') {
                    $result->setAttribute('MainImage', $str);
                    @copy($server_path, $self_path."/images/".$zip_folder_name."/".$str);
                }

                // Obtiene la galería de imágenes
                $gallery = maybe_unserialize(get_post_meta($variationID, 'woo_variation_gallery_images', true));
                if (!empty($gallery)) {
                    foreach ($gallery as $gvalue) {
                        // Crea elementos para las imágenes secundarias
                        $result1 = $dom->createElement('Secondary');
                        $result->appendChild($result1);

                        // Obtiene y copia las imágenes secundarias
                        $thumb_image1 = wp_get_attachment_image_src($gvalue, $size);
                        $tokens1 = explode('/', $thumb_image1[0]);
                        $str1 = trim(end($tokens1));

                        $result1->setAttribute('Archivo', $str1);
                        $result1->appendChild($dom->createTextNode(''));
                        $result->appendChild($result1);

                        $server_path1 = str_replace($url_path, $images_path, $thumb_image1[0]);
                        @copy($server_path1, $self_path."/images/".$zip_folder_name."/".$str1);
                    }
                }
            }
        }
    }

    // Guarda el documento XML
    $result->appendChild($dom->createTextNode(''));
    $root->appendChild($result);

    // Crea el archivo XML y el zip
    if ($dom->save('images/IMG_' . $ak . '.xml')) {
        $zip_file = 'IMG_' . $ak.".zip";
        $command = "cd ".$self_path."/images/".$zip_folder_name." && zip -r  ../".$zip_file." *";
        shell_exec($command);
        shell_exec("cd ".$self_path."/images/ && rm -rf ".$zip_folder_name);
        echo "Fexpro Style Images XML is created please click <a href='". site_url() ."/wp-content/themes/porto-child/fw23_images/images/IMG_" . $ak . ".xml' target='_blank'>Click Here " . $ak ."</a> | <a href='". site_url() ."/wp-content/themes/porto-child/fw23_images/images/" . $zip_file . "'>".$zip_file."</a><br><hr>";
    } else {
        die('XML Create Error');
    }

    $root->removeChild($result);
}




