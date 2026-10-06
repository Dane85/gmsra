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
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          'tZdj4DP&(=q6q -^*OvhR8`L,3ay(51rkluAUxB)*Ij;^|xX`9VY9`b0OH/q$n&_' );
define( 'SECURE_AUTH_KEY',   '%U&t(No8W*h^SdE*A)PTjnMlu?i!3sozb828lB^k~ x*_$U&|p%s!YT4NtcFQn9/' );
define( 'LOGGED_IN_KEY',     'Dt1p4+l>biUEYvb%5I8tnasXA%i76fm@</585cnz]HyN^l_cBjQ+aZvc{i7|H%l:' );
define( 'NONCE_KEY',         'H:pK3Lk!9.@sC9vT>8`yWuSJ)t=<,S}e2T;3v7CNQu%1oM3cglNQ(_}2U<Qxa*6%' );
define( 'AUTH_SALT',         'q<~ZF A6|qX|^miD7bFy*SJV$32HnDM,1)7t=C:~IPAqCkR).#3Hu=r9WPuM3cSV' );
define( 'SECURE_AUTH_SALT',  '>,Ghgt_b=H(ax>s.P46W3T&Pd?Kojj<&%&?_$x~X!Vk:t/1ql)2t3:l5c.Q_kVN<' );
define( 'LOGGED_IN_SALT',    'WlGj~WRocm,CiX*`Ezr>Ojt^0DbfK-# @-3Fy`AN>;e0v9Uk^=yLQhIecJ$PSQU5' );
define( 'NONCE_SALT',        '?Z=cYMKP{1`2Yj{<YS{[)H,!`z+4k`^H-wN cd-ysr|Q4z:l%`;Y(bdSCzY>B3Ec' );
define( 'WP_CACHE_KEY_SALT', 'FeB`<xn1,5|_e:,~&oe%V@V]+NwGbJ,()~/ gkDn~!uiM_0Mm#vL37p@L@k89m:,' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
