<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL
define( 'WP_CACHE', true );
//define( 'WP_MEMORY_LIMIT', '512M' );
 // Added by WP Rocket
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'fexpro_new_live1' );
/** MySQL database username */
define( 'DB_USER', 'shop' );
/** MySQL database password */
define( 'DB_PASSWORD', '3eLp%2q0' );
/** MySQL hostname */
define( 'DB_HOST', 'localhost' );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'wLu/}?tK=S,4m/?@*Mn*JUs);`q]Iez(HM1?>`U>IMJ,Th!A *G9.y[Q`.V%*Q+8' );
define( 'SECURE_AUTH_KEY',  '<5`F7vu_gu>2wofAt2;a{}4/Ty>^-K<A_nPqB)#4]5{i>]eszY`;:%6n65YS8/q<' );
define( 'LOGGED_IN_KEY',    'x]ICC$^4uS5O@Q3@6*$1QwnLcXd^>e^0bh#{E~|G:ScE)yZI0+P;Yugu1~n.XZ]P' );
define( 'NONCE_KEY',        '//n/M`W7D[[gU>y4+Y8sw4!aAS?T?*0+lYR;lN(V*d?=Lk5b+:|MDk}[j;}o]6bu' );
define( 'AUTH_SALT',        'Vth0n|&?6PV$g1:y9d:Ls>$-y+Wfz|]/kNQl|M#iY8YZ:,#4:KEZX9vl9!P*Nb1[' );
define( 'SECURE_AUTH_SALT', 'P*?jft)EU_H8<kXg+*m*RPVq~so~lq9^mq3|t5k3IwE`,KTKNM=[{=^94<kbHDM3' );
define( 'LOGGED_IN_SALT',   'l~E5>B<X+>jR+|p>Eak61^~4QzqcR97BS]], dk/.9a^!hPrY~44i&QH*jIe8Q!K' );
define( 'NONCE_SALT',       'G[G/P6l9yo{FTqSS[K:R?c 3_tVfH_d%<XH!=urKc5}1*h+SW:{%Ay{s{5Vtcq=s' );
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */

define( 'WP_HOME', 'https://shop2.fexpro.com/' );
define( 'WP_SITEURL', 'https://shop2.fexpro.com/' );
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', true );
define('WP_POST_REVISIONS', false );
// define( 'WP_DEBUG', true );
/* That's all, stop editing! Happy publishing. */
define( 'WP_POST_REVISIONS', 1 );
/* define( 'WP_HOME', 'https://shop.fexpro.com' );
define( 'WP_SITEURL', 'https://shop.fexpro.com' ); */
/** Absolute path to the WordPress directory. */

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_AUTH', true);
define('SMTP_USER', 'envios.webinfosv@gmail.com');
define('SMTP_PASS', 'bnpndjxginicvdbt');
define('SMTP_SECURE', 'tls');
//define('SMTP_DEBUG', true);

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
define('SHORTPIXEL_USE_DOUBLE_WEBP_EXTENSION', true);
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
