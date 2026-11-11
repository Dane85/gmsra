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

// ** Database settings - Automatic environment detection ** //
if ( gethostname() === 'ubuntu-2gb-ash-2' || file_exists( __DIR__ . '/wp-config-live.php' ) ) {
	/** Live server settings */
	define( 'DB_NAME', 'gmsra_db' );
	define( 'DB_USER', 'gmsra_user' );
	if ( file_exists( __DIR__ . '/wp-config-live.php' ) ) {
		require_once __DIR__ . '/wp-config-live.php';
	}
	if ( ! defined( 'DB_PASSWORD' ) ) {
		define( 'DB_PASSWORD', getenv( 'DB_PASSWORD' ) ?: '' );
	}
	define( 'DB_HOST', 'localhost' );
	if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) ) {
		define( 'WP_ENVIRONMENT_TYPE', 'production' );
	}
} else {
	/** Local development settings */
	define( 'DB_NAME', 'local' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASSWORD', getenv( 'LOCAL_DB_PASSWORD' ) ?: '' );
	define( 'DB_HOST', 'localhost' );
	if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) ) {
		define( 'WP_ENVIRONMENT_TYPE', 'local' );
	}
}

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
// Salts are NOT stored in Git. Production reads them from wp-config-live.php or the
// untracked wp-salts.php (created on the server by Scripts/deploy/server-migrate-v2.6.sh).
if ( ! defined( 'AUTH_KEY' ) && file_exists( __DIR__ . '/wp-salts.php' ) ) {
	require_once __DIR__ . '/wp-salts.php';
}
if ( ! defined( 'AUTH_KEY' ) ) {
	if ( gethostname() === 'ubuntu-2gb-ash-2' || file_exists( __DIR__ . '/wp-config-live.php' ) ) {
		header( 'HTTP/1.1 503 Service Unavailable' );
		exit( 'Configuration error: authentication salts missing.' );
	}
	// Local development only — throwaway values.
	foreach ( array( 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT', 'WP_CACHE_KEY_SALT' ) as $epic_salt_name ) {
		define( $epic_salt_name, 'local-dev-only-' . $epic_salt_name );
	}
}


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */

if ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
	$clean_host = preg_replace( '/[^a-zA-Z0-9.\-:]/', '', (string) $_SERVER['HTTP_HOST'] );
	$proto      = ( ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) ) ? 'https://' : 'http://';
	define( 'WP_HOME', $proto . $clean_host );
	define( 'WP_SITEURL', $proto . $clean_host );
}



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

if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) ) {
	define( 'WP_ENVIRONMENT_TYPE', 'local' );
}
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
