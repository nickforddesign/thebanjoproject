<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

if( stristr( $_SERVER['SERVER_NAME'], "local:5757" ) ) {
 
 	// Codekit
	define( 'DB_NAME', 'banjo' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASSWORD', 'root' );
	define( 'DB_HOST', 'localhost' );
	
	define( 'WP_HOME', 'http://nick.local:5757');
	define( 'WP_SITEURL', 'http://nick.local:5757');
	
	// Dev will always want debug on and caching off
	define( 'WP_DEBUG', true );
	define( 'WP_CACHE', false );
	 
} elseif( stristr( $_SERVER['SERVER_NAME'], "localhost" ) ) {
 
	// MAMP
	define( 'DB_NAME', 'banjo' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASSWORD', 'root' );
	define( 'DB_HOST', 'localhost' );
	
	define( 'WP_HOME', 'http://localhost');
	define( 'WP_SITEURL', 'http://localhost');
	
	// Testing will always want debug on and caching off
	define( 'WP_DEBUG', true);
	define( 'WP_CACHE', false);

} elseif( stristr( $_SERVER['SERVER_NAME'], "clients" ) ) {
 
	// Staging
	define( 'DB_NAME', 'db115415_banjo' );
	define( 'DB_USER', 'monderer' );
	define( 'DB_PASSWORD', 'cakhH7VP' );
	define( 'DB_HOST', 'internal-db.s115415.gridserver.com' );
	
	define( 'WP_HOME', 'http://clients.monderer.com/banjo');
	define( 'WP_SITEURL', 'http://clients.monderer.com/banjo');
	
	// Testing will always want debug on and caching off
	define( 'WP_DEBUG', false);
	define( 'WP_CACHE', false);
		 
} else {
 
	// Production Environment
	
	if (stristr( $_SERVER['SERVER_NAME'], "emerson")) {
		define( 'WP_HOME', 'http://banjo.emerson.edu/demo'); 
		define( 'WP_SITEURL', 'http://banjo.emerson.edu/demo'); 
	} else {
		define( 'WP_HOME', 'http://thebanjoproject.org/demo'); 
		define( 'WP_SITEURL', 'http://thebanjoproject.org/demo'); 
	}
	
	define( 'DB_NAME', 'banjoproject_db' );
	define( 'DB_USER', 'banjo_admin' );
	define( 'DB_PASSWORD', 'Rhino?Horn!2012' );
	define( 'DB_HOST', 'mysql-1.thebanjoproject.org' );
	
	
	
	// Live Environment will always be the same as production so turn off debug and turn on caching
	define( 'WP_DEBUG', false );
	define( 'WP_CACHE', true );
		
}
 
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_general_ci');;

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '/a^%V`w;mr|f>@Bm?r?/] 4U0( >@pY_v?v&q-3[5c@b}! 8~+Xd]`!8(N8H<Xk!');
define('SECURE_AUTH_KEY',  'GYcJ^KxBI`>iG@7nKXji;2133LLR-n~:Wk{@[imz$|MeO,f<o>wmBoDX%$|C28zG');
define('LOGGED_IN_KEY',    'hgoX/c3#UZthU,|ZY5s+nh5ZG4@aN7Kp&.FO||,~JCvQOpipB<R/9vdS|3h{5q+}');
define('NONCE_KEY',        '}wn/=y#Joz=Q=B|4hKxL%}St Z|D?~yyg)!/?0<Txk<f;Hx|9|j6u$o]:B6XR33o');
define('AUTH_SALT',        '}I$F!s0qnU*6nl1oi5++*>S]0`,B(im+I&b[p/HHO%45 )u+|+-~<Y^6HN=b/>/1');
define('SECURE_AUTH_SALT', '+-5;lP$Dpd{`~J.8sJi#n)D(I%9^:/|PC3Uk$W3Vp8q-.ni(m 4oEka^`a:w6v k');
define('LOGGED_IN_SALT',   'L6gZ8bj>v5%w%F8&h85L9{@Y+=x>Ukc-Ws>_Hd^6VXX:xLzF}3pn(N+j(S)f9nCT');
define('NONCE_SALT',       '??o%+P8! ^VdgWwWv^:[$/Q&+,.&]|(ab5;14>E&N.E+&nW:Q/MF| mwMzA&)IWM');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
//define('WP_DEBUG', false);

if (WP_DEBUG) {
	define('WP_DEBUG_LOG', true);
	define('WP_DEBUG_DISPLAY', false);
	@ini_set('display_errors', 0);
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
