<?php

namespace Barn2\Plugin\WC_Bulk_Variations;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

include_once __DIR__ . '/lib/autoloader.php';
include_once __DIR__ . '/lib/class-wc-settings-plugin-promo.php';
require_once __DIR__ . '/lib/class-html-data-table.php';
require_once __DIR__ . '/src/template-functions.php';
require_once __DIR__ . '/lib/class-wc-settings-additional-field-types.php';

spl_autoload_register( [ 'Barn2\Plugin\WC_Bulk_Variations\Autoloader', 'load' ] );

/**
 * The plugin autoloader.
 *
 * @author    Barn2 Plugins <info@barn2.com>
 * @license   GPL-3.0
 * @link      https://barn2.com
 * @copyright Barn2 Media Ltd
 */
final class Autoloader {

    const SOURCE_PATHS = [
        'Barn2\\Plugin\\WC_Bulk_Variations'       => __DIR__ . '/src',
        'Barn2\\Plugin\\WC_Bulk_Variations\\Util' => __DIR__ . '/src/Util',
        'Barn2\\Plugin\\WC_Bulk_Variations\\Admin' => __DIR__ . '/src/Admin'
    ];

    public static function load( $class ) {

        $src_path = false;

        foreach ( self::SOURCE_PATHS as $namespace => $path ) {
            if ( 0 === strpos( $class, $namespace ) ) {
                $src_path = $path;
                break;
            }
        }

        // Bail if the class is not in our namespace.
        if ( ! $src_path ) {
            return;
        }

        // Strip namespace from class name.
        $class = str_replace( $namespace, '', $class );

        // Build the filename - realpath returns false if the file doesn't exist.
        $file = realpath( $src_path . '/' . str_replace( '\\', '/', $class ) . '.php' );

        // If the file exists for the class name, load it.
        if ( $file ) {
            include_once $file;
        }
    }

}
