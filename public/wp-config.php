<?php
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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/* Local 1 */
if ($_SERVER['HTTP_HOST']=="local.sterlingjunctionfull.com") {
	define('DB_NAME', 'sterlingjunctionfull');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_HOST', 'localhost');
}
if ($_SERVER['HTTP_HOST']=="sterling.tyrellbain.com") {
	define('WP_HOME', 'http://sterling.tyrellbain.com' );
	define('WP_SITEURL', 'http://sterling.tyrellbain.com' );
	define('DB_NAME', 'db210139_sterling');
	define('DB_USER', 'db210139_sj');
	define('DB_PASSWORD', 'Sterling20!');
	define('DB_HOST', 'external-db.s210139.gridserver.com');
}
/* Local 2 */
else if ($_SERVER['HTTP_HOST']=="localhost") {
	define('DB_NAME', '52wordpresspackage2019');
	define('DB_USER', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_HOST', 'localhost');
}
/* Local 3 */
else if ($_SERVER['HTTP_HOST']=="localhost:8888") {
	define('WP_HOME', 'http://localhost:8888/the-rushton' );
	define('WP_SITEURL', 'http://localhost:8888/the-rushton' );
	define('DB_NAME', 'rushton');
	define('DB_USER', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_HOST', 'localhost');
} 
/* Beta Server */
else if ($_SERVER['HTTP_HOST']=="staging.52beta.ca") {
	define('DB_NAME', 'the-rushton');
	define('DB_USER', '52staging');
	define('DB_PASSWORD', 'Vd]43LYs/J_bWnaz');
	define('DB_HOST', 'localhost');
}else {
	define('DB_NAME', '52wordpresspackage');
	define('DB_USER', 'username');
	define('DB_PASSWORD', 'password');
	define('DB_HOST', 'localhost');
}


/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'f--z)F?5GG`uRGQEq7;`)-W}`c{;;kBTV9n9+g+4Zw<S0^3_T6I(N+}u$W{eyw.e');
define('SECURE_AUTH_KEY',  'q}lCG-h9<+w`xQ d|!dfVh+MWQ)vdK cYg(bH>>hwSeK]+d=qMhk,*&+d V+Imke');
define('LOGGED_IN_KEY',    'LT+OyM|z1[!J-BDfUpY+!h1Pz&4-8J}yx&NjKP3@-xUvAtC)Sc-pdLw7aDi*t|Sd');
define('NONCE_KEY',        'QTG5Q,#pcZW61~YZ3JTBJdFiLfD_$t3K-GV8@Ne-=p kG57y5SO9LbXO^tG=MNXv');
define('AUTH_SALT',        '>2}S6NM(ExB9p~p++]hscDorug7J&2^+#Iuu(PXC^a0DarTR[)cOoVl-V+UbZbKO');
define('SECURE_AUTH_SALT', 'ieG:HS U{Xt7*lUy%~_4^}(.r?Jxr>/p,Ftu@8tvbKKJoY^aH]c``H|6Do]FN|A5');
define('LOGGED_IN_SALT',   '4ms.:&rE+BhGQB44OC?iP-j=U,,m~+,N%Ti~_sJnvsxDZv/*GTWpp18j([;=tzY+');
define('NONCE_SALT',       '=u9-WI-=Se@hhLnOHv,GFRnFu5<dGiqd@LHYj7J-AEzqKTP/]?y?^BHIF]/` HK-');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
