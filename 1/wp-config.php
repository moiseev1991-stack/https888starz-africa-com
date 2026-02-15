<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
// In Docker: use WORDPRESS_DB_* from docker-compose. Locally: use values below.
/** The name of the database for WordPress */
define( 'DB_NAME', getenv( 'WORDPRESS_DB_NAME' ) ?: 'wp_gugum' );

/** Database username */
define( 'DB_USER', getenv( 'WORDPRESS_DB_USER' ) ?: 'wp_rbje6' );

/** Database password */
define( 'DB_PASSWORD', getenv( 'WORDPRESS_DB_PASSWORD' ) ?: 'Wfy7u!Xw~1hgh34a' );

/** Database hostname (in Docker use service name "db") */
define( 'DB_HOST', getenv( 'WORDPRESS_DB_HOST' ) ?: 'localhost:3306' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'iBH(4utXs:d0k3vh7@R6/||qkw*7M!&kv1Vx/b*yzZ3Y0*XEw215yM|T[1OGg#*d');
define('SECURE_AUTH_KEY', 'iM1f|0xb3Y2v12Iomj(:;679PwaMf[769993W59bq[/7v[bEH5BglU88%!Gv~6UY');
define('LOGGED_IN_KEY', 'l61689h8NCN&Lt0NzZyaOxb))N-##~FQg4vJ[G7xGrl+W)tos6Lh~:33B@!kgY4v');
define('NONCE_KEY', 'qVr7&O48#Zc%e3d4D07]4HB/sR|~cu]3z0T]GI%r3-6Qe8aS40a1m7T4r]wb1vL:');
define('AUTH_SALT', 'v5ck@_x|A5861B~VN|t6dS4[;10L45W-l0122iEY+6-Ge59!a[)lO6iwclNH3(F5');
define('SECURE_AUTH_SALT', 'W4#6Zqm_0j~A492Eo5;vPj6T60*3j145pn5Z15L41k/bC3+W46Q[i#p/57zan6FQ');
define('LOGGED_IN_SALT', '9#AT361Zc;%w(o47XNFW73P2/A1ks62(np6m98764wr)Jr023@cr@r&02f48S-(T');
define('NONCE_SALT', 'b)a298Sw:QiSo~][9+GX/5gB;)d%1qqMo-1lOF_&~M)AxW[)%]Q9Dx9J3q)1w3_D');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'TVXFZYUMh_';


/* Add any custom values between this line and the "stop editing" line. */

define('WP_ALLOW_MULTISITE', true);
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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
