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
define( 'DB_NAME', 'sendigitour' );

/** MySQL database username */
define( 'DB_USER', 'papi' );

/** MySQL database password */
define( 'DB_PASSWORD', 'papi' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '04r {<,=!e)#;O*[+$o<weVlY=7qO5No%uuF0@TTH:3Ib7$m!<r!vTFB%0&>v{cs' );
define( 'SECURE_AUTH_KEY',  'oN8~85 V- d3srE(UZnMp[s<{MTwKPjoNP<cA]gV>>]dk:TYTMl1hHITY}rWl*$G' );
define( 'LOGGED_IN_KEY',    'axoj0RQW9zSv3Us]:M{-CX;BOa.M;pCrC%2HJJWWVzx&a{~&/gh~I%1&#WsHiWOf' );
define( 'NONCE_KEY',        '5.h/w)w]Ja55,D|<@.Td7^TIZW``prESqgSa%v3W)7:u:?,T.Ug,!)/`(pab]6=V' );
define( 'AUTH_SALT',        't> 5B*lL F(])rIeo3ZmB#{LE1iVtMhQ]7vlpFl=:&~1t6DIJPK/Les!T m[Zbg{' );
define( 'SECURE_AUTH_SALT', 'HM&{M(73Dy(}%*8Mh<g_~Uh=rRAu5dBUKMc|oo=h+VF}zE|d9nX{P$C-1+VJW3[V' );
define( 'LOGGED_IN_SALT',   'I^3Q x.m+8_K|,nKx7~&nc^EK]ah)#} S98H0y{VKrQnzoGIH @I<9U&SU.=om85' );
define( 'NONCE_SALT',       '{@B/,OG}1aEt$+:N<~]?*Ia[W6~8?x^NIPK-am[;h;j1EmIM*j9l|r<87bg.eL<g' );

/**#@-*/

/**
 * WordPress database table prefix.
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
define( 'WP_DEBUG', false );
define('FS_METHOD', 'direct');
/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
