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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'project' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'CoFHva05Jt=[_8xX1D0LFD?Pban}f=ddp/^Ryj+SEtEWd2yZguOtqO~LBX)r*rIH' );
define( 'SECURE_AUTH_KEY',  'pYz5DE]tf{3da:|xcy`%I&(adn6SY: %:K5sLrbuzS=>B[SK(~G/#f-`B%7R06CW' );
define( 'LOGGED_IN_KEY',    '8=xNk8oVjYxu@0>RBpAw!a*[*5bVY/9Ip=&^.&O][jmaLQ]V*:76Zwn~R6EL%>69' );
define( 'NONCE_KEY',        'LV3?0uljMjN>ZE!KW?CoX=X_+3<pPXdj` (fg/_[iP76bWw1V(j0uHF-h[FUF/,(' );
define( 'AUTH_SALT',        'i2Hr9nW^tMAx9%H^5<W]%Jk(RxpC1V=.E0lSt/ZrPJj7cmenB,zA#K8/0u ^|#3h' );
define( 'SECURE_AUTH_SALT', '+@(.CtJ;LI|3Mi+f1q8Xz,[6gYO:5%DBRTb%>EAF8Vm70,o@}rWT,4j){Fd~,v1i' );
define( 'LOGGED_IN_SALT',   'Vn[R!u(Mro%v>/MKiy}Cm9h&<S]|{[I*q#f!K>r}4[^Ksk/?`,CRLje{eUz@uZou' );
define( 'NONCE_SALT',       'yAKnV~>uz6O};cI$uvAD(vbx_gHc3k6n7U2xE/!6E]oQ(|{*U}EI2V??q$vn(R/l' );

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
define( 'WP_DEBUG', false );
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WPMS_ON', true );
define( 'WPMS_SMTP_PASS', '!@#NikulV!@#' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';




