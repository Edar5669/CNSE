<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'c9cnsewordpress' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'c9supporter_cnse' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', 'uNsh_J4sS8' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '?D28hVBC/7a8%QDE)Vb&|Yi(O8FweVus,7V#${Xw~IcPW?|x=EJZbxuAJD6QZNB}' );
define( 'SECURE_AUTH_KEY',  'G`D.|Ld}tR7Ip5>*)VC@pm.2_5O3LPu~Vrx!M#Y.d7Ml/aL{0_`i+Hx:^*IA<tJ7' );
define( 'LOGGED_IN_KEY',    'g<q;Vuz~P;l)g4@$~M1OA3j7xts`,%gvL}[:H,u<;G.9f8g_fQa!fCf^p|R&e7Zd' );
define( 'NONCE_KEY',        'B)X WZ~k,|-lIN?:Ox]g7kWM_al`U*-G5tXf/hA&ztKF7d(E&T]p+j0,,4p+ X7v' );
define( 'AUTH_SALT',        'v|5`6WkT`Ou|6j-GbhBdD/Cto+pVO6l$M`4-<y!-kkF*.|#vC/39FUge(]T(JkGY' );
define( 'SECURE_AUTH_SALT', ' =07RxD 9^c7y ).&bjDe+#6Y]nT|Y#-E@TDhX=A&5FRcB?mZ[X~4Zd`Jvxk9)Wr' );
define( 'LOGGED_IN_SALT',   ',#g^5)TdR9h l FUOK+WB8$~`0@]f%-zheQi ~!H.M)(H:oXA|I}w*2{y}w9/TUJ' );
define( 'NONCE_SALT',       'z)},/Q)b<1dLJM$hCxv&$IR#@/5H.kKMM$ue,KOTwK:M:N6xxx:%JIsH=m!.|%r+' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
