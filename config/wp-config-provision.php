<?php
/** 
 * As configurações básicas do WordPress.
 *
 * Esse arquivo contém as seguintes configurações: configurações de MySQL, Prefixo de Tabelas,
 * Chaves secretas, Idioma do WordPress, e ABSPATH. Você pode encontrar mais informações
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. Você pode obter as configurações de MySQL de seu servidor de hospedagem.
 *
 * Esse arquivo é usado pelo script ed criação wp-config.php durante a
 * instalação. Você não precisa usar o site, você pode apenas salvar esse arquivo
 * como "wp-config.php" e preencher os valores.
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar essas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'participacao');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', '');

/** nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Conjunto de caracteres do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8');

/** O tipo de collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer cookies existentes. Isto irá forçar todos os usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         's!rLRNU4!2]e}H^u+zW-(YC<F?x1HC2 B1_[ZZ-p:X)k1%6+&O+Ttss?*<-+p@L ');
define('SECURE_AUTH_KEY',  'faW;J+> 3.U25blUp2d)}3A}fp>[f@=VuNZ#QLlhDZ4+/sKUg13gdN8Vz%OJm:tM');
define('LOGGED_IN_KEY',    'Y9#zsWMMNUpd/];C]3>wd3=M9j#f+Vz/< |rb3<|(io}JBY>]XJ`X-(+>=Y=q}iw');
define('NONCE_KEY',        'q9v4xp!s+*[OO3W=E#)2DV{AUVDsIPJowH{7meIQk_a7}vB?UJ*fv  Mz_eflEcK');
define('AUTH_SALT',        'q0TRGwz,wq,lgr-|w-+$q3[}Tfn^/0zQRdb;<~WM6Y[UEPy)gD?|Q%#FopKxLNSp');
define('SECURE_AUTH_SALT', 'D{~R3*RQd3@aL}2kp(1!&I{c xxLR`j-{vxj!TGNIPZik#rR<r/JppAbeB-_yJKf');
define('LOGGED_IN_SALT',   'Uu0:3Z**!`t4|:3f@x:q2)zM~Y>^Y7THeCOpKst<!3AN?Vwqg>p4q?gJ?fEN|>U!');
define('NONCE_SALT',       '}Sf&a$vcvJd|h<MK<1<o+.%PS{,cHrTlGKBF(3,QVF(J.BK1x {6Ub1mi)Ae_u!.');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der para cada um um único
 * prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';


/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * altere isto para true para ativar a exibição de avisos durante o desenvolvimento.
 * é altamente recomendável que os desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 */
define('WP_DEBUG', true);

/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'participacao.mj.gov.br');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
/** Configura as variáveis do WordPress e arquivos inclusos. */
require_once(ABSPATH . 'wp-settings.php');
