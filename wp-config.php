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
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'kaungkaung' );

/** MySQL database password */
define( 'DB_PASSWORD', 'kaungminkhant1' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',         'gQTgaZZZ2k[8X?xH0nV1*H1`lYU@}UjE<o1Wc 620`lZf>yNK<)5TM}.5L/qw77~' );
define( 'SECURE_AUTH_KEY',  'B3_`M5+M+?hV9yG/(o<mVMvx6.,vvF8{7}1,9r6J(@1/UeOZpvP/Wz[c;?[i}Q:V' );
define( 'LOGGED_IN_KEY',    'H/W<{%Pb^vm8TR{lb<kR&+*CPg0xSM$mrx#meB!:ga.%APj uy`j=-Mkd2C1qZtZ' );
define( 'NONCE_KEY',        '0ntzLG*?na$=HK|wU)J9eLIgMSY(QiIVgQGOpm85HRQflDbh}#6$#k#]]uP,oD|~' );
define( 'AUTH_SALT',        '$B:lIo/Jt~.vc~26Tr;n,2o#QQcu).;cZ;uhL$#/un=S_8OetM %?=Wh`o)yt<T6' );
define( 'SECURE_AUTH_SALT', 'O4?PBKXuimm$ =ww $]W:E.D[y?(`(y&^@~by1mq{OA)Kp{DoQWb/?D9NwW~y*12' );
define( 'LOGGED_IN_SALT',   'izRPX${e|Pr0yn#3A3} 7py~8ui5/2+r!IDUfhcA:BX6->4:WIIfshGx1,iXH2f-' );
define( 'NONCE_SALT',       'wJe@Qzo1:)0*@O#mSO7,nZwCn<5+2=>yF72Xa(Y>x0|@6>x}{ _j=a ^7[:WX6RD' );

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
