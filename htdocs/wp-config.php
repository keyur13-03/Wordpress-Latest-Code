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

/** The name of the database for WordPress */

define('DB_NAME', "rcmp");


/** MySQL database username */

define('DB_USER', "root");


/** MySQL database password */

define('DB_PASSWORD', "");


/** MySQL hostname */

define('DB_HOST', "localhost");


/** Database Charset to use in creating database tables. */

define('DB_CHARSET', 'utf8mb4');


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

define('AUTH_KEY',         'DVb=W!`Zq}}{H+kix3ALr 8>O]A~!h13+)og;Y&?^ ;WK*&eP6{^@kljq!84(C,|');

define('SECURE_AUTH_KEY',  '; ]]a8!Gb@KvePQ!*s_5k5wF+ka$@|MM*o:7&,Dc!(_f5e0xfc<fkC*4B{X^L]n=');

define('LOGGED_IN_KEY',    ')L4OnK)z&sdu>btBtXW1!;q+Sub-py87yX[Tmr2s[mcomq(Rd`RmpW4Y82}yug!t');

define('NONCE_KEY',        '+PU9aZN>z)bi(vEF)K7gWGDl=$^Q(:]b=>(gH~F@1)0Sd$]sDA0M~pr<2~A~b5pY');

define('AUTH_SALT',        ' *|s6!k%QRM&A_nUpLvI51QQ}(]{x+-o.8*kBc]AV87og|o94,4-lGT<,M=IM4L`');

define('SECURE_AUTH_SALT', '5x(r)|`x:Xyh6rS=3{140NW7^T>1RGqHVW~_yuI%R;+=5&W FYbgVd:4S2;nZ4a]');

define('LOGGED_IN_SALT',   'I=/5<#<[38~iSWgqd5_K$)E!&+/US7)?[dYHPv[%P/K8-2Z|k}2<uopx&W^luubN');

define('NONCE_SALT',       'f1R^SY9Gd^1D+}Wj+aY:T$C*[+82bE!gDnOtAPQC5/L$8oHOD1Y~4KCMhYARGy-Z');


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

